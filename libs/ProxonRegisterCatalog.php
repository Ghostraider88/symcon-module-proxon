<?php

declare(strict_types=1);

/**
 * Read-only access to the generated PROXON planning register catalog.
 *
 * This class deliberately does not know about Symcon objects or Modbus
 * transports. It is usable by simulators, validation tests and later module
 * adapters. The catalog remains the source of truth for addresses and scales.
 */
final class ProxonRegisterCatalog
{
    /** @var array<string, array<string, mixed>> */
    private array $entries = [];

    /**
     * @throws RuntimeException when the catalog is missing or invalid
     */
    public function __construct(string $catalogDirectory, string $device)
    {
        $device = strtolower(trim($device));
        if (!in_array($device, ['fwt', 't300'], true)) {
            throw new InvalidArgumentException('Unsupported PROXON catalog device: ' . $device);
        }

        $path = rtrim($catalogDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $device . '.json';
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException('Unable to read PROXON register catalog: ' . $path);
        }

        try {
            $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Invalid PROXON register catalog JSON: ' . $path, 0, $exception);
        }

        if (!is_array($decoded)) {
            throw new RuntimeException('PROXON register catalog must contain an array: ' . $path);
        }

        foreach ($decoded as $entry) {
            if (!is_array($entry)) {
                throw new RuntimeException('PROXON register catalog contains a non-object entry: ' . $path);
            }
            $this->validateEntry($entry, $path);
            $key = $this->makeKey((string) $entry['register_space'], (int) $entry['address']);
            if (isset($this->entries[$key])) {
                throw new RuntimeException('Duplicate PROXON catalog key: ' . $key);
            }
            $this->entries[$key] = $entry;
        }
    }

    /** @return array<string, mixed>|null */
    public function find(string $registerSpace, int $address): ?array
    {
        return $this->entries[$this->makeKey($registerSpace, $address)] ?? null;
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return array_values($this->entries);
    }

    public function count(): int
    {
        return count($this->entries);
    }

    /** @return list<array<string, mixed>> */
    public function conflicts(): array
    {
        return array_values(array_filter(
            $this->entries,
            static fn(array $entry): bool => !empty($entry['conflicts'])
        ));
    }

    /**
     * Normalize one raw register value using the catalog transformation.
     *
     * The catalog formula is engineering = raw * multiplier + offset. This
     * helper intentionally rejects values outside uint16/int16 bounds instead
     * of silently clamping a device response.
     */
    public function normalize(string $registerSpace, int $address, int $raw): int|float
    {
        $entry = $this->find($registerSpace, $address);
        if ($entry === null) {
            throw new OutOfBoundsException(sprintf('Unknown PROXON register %s:%d', $registerSpace, $address));
        }
        if ($raw < 0 || $raw > 0xFFFF) {
            throw new OutOfBoundsException('Raw PROXON register value must be between 0 and 65535.');
        }

        $dataType = strtolower((string) ($entry['data_type'] ?? ''));
        if (str_contains($dataType, 'int16') && !str_contains($dataType, 'uint')) {
            $raw = $raw >= 0x8000 ? $raw - 0x10000 : $raw;
        }

        $scale = is_array($entry['scale'] ?? null) ? $entry['scale'] : [];
        $multiplier = (float) ($scale['multiplier'] ?? 1.0);
        $offset = (float) ($scale['offset'] ?? 0.0);
        $value = ($raw * $multiplier) + $offset;

        return $this->isWholeNumber($value) ? (int) $value : $value;
    }

    private function validateEntry(array $entry, string $path): void
    {
        foreach (['device', 'register_space', 'address', 'function_read', 'confidence', 'sources'] as $required) {
            if (!array_key_exists($required, $entry)) {
                throw new RuntimeException(sprintf('Catalog entry in %s misses field %s.', $path, $required));
            }
        }

        if (!in_array($entry['register_space'], ['holding', 'input'], true)) {
            throw new RuntimeException('Invalid PROXON register space in ' . $path);
        }
        if (!is_int($entry['address']) || $entry['address'] < 0) {
            throw new RuntimeException('Invalid PROXON register address in ' . $path);
        }
        if (!is_array($entry['sources'])) {
            throw new RuntimeException('Catalog sources must be an array in ' . $path);
        }
    }

    private function makeKey(string $registerSpace, int $address): string
    {
        $registerSpace = strtolower(trim($registerSpace));
        if (!in_array($registerSpace, ['holding', 'input'], true)) {
            throw new InvalidArgumentException('Invalid PROXON register space: ' . $registerSpace);
        }
        if ($address < 0) {
            throw new InvalidArgumentException('PROXON register address cannot be negative.');
        }
        return $registerSpace . ':' . $address;
    }

    private function isWholeNumber(float $value): bool
    {
        return abs($value - round($value)) < 1e-9;
    }
}
