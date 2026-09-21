<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/libs/ProxonRegisterCatalog.php';

final class ProxonRegisterCatalogTest extends TestCase
{
    public function testFwtCatalogLoadsExpectedRegisterCount(): void
    {
        $catalog = new ProxonRegisterCatalog(dirname(__DIR__) . '/docs/catalog', 'fwt');

        self::assertSame(731, $catalog->count());
        self::assertNotNull($catalog->find('holding', 16));
        self::assertNotNull($catalog->find('input', 195));
    }

    public function testCatalogPreservesConflicts(): void
    {
        $catalog = new ProxonRegisterCatalog(dirname(__DIR__) . '/docs/catalog', 'fwt');

        self::assertNotEmpty($catalog->conflicts());
        self::assertNotEmpty($catalog->find('holding', 16)['conflicts']);
    }

    public function testTemperatureScalingIsNormalized(): void
    {
        $catalog = new ProxonRegisterCatalog(dirname(__DIR__) . '/docs/catalog', 't300');

        self::assertSame(-100, $catalog->normalize('input', 811, 0));
        self::assertSame(0, $catalog->normalize('input', 811, 1000));
    }
}
