<?php

class ProxonCentral extends IPSModuleStrict
{
    public function Create(): void
    {
        parent::Create();

        $this->RegisterPropertyInteger('Interval', 30);
        $this->RegisterPropertyBoolean('EnableT300', false);
        $this->RegisterTimer('Poller', 0, 'PROXON_RequestStatus($_IPS[\'TARGET\']);');
    }

    public function ApplyChanges(): void
    {
        parent::ApplyChanges();

        $this->RegisterVariableFloat('CurrentTemperature', $this->Translate('Current Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 1);
        $this->RegisterVariableFloat('TargetTemperature', $this->Translate('Target Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_SLIDER,
            'TEMPLATE' => VARIABLE_TEMPLATE_SLIDER_ROOM_TEMPERATURE
        ], 2);
        $this->EnableAction('TargetTemperature');

        // T300 is read through the FWT parent and is deliberately read-only in this phase.
        $this->RegisterVariableBoolean('T300Available', $this->Translate('T300 Available'), [], 20);
        $this->RegisterVariableFloat('T300NormalWaterTemperature', $this->Translate('T300 Normal Water Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 21);
        $this->RegisterVariableFloat('T300EHeaterTemperature', $this->Translate('T300 E-Heater Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 22);
        $this->RegisterVariableBoolean('T300EHeaterEnabled', $this->Translate('T300 E-Heater Enabled'), [], 23);
        $this->RegisterVariableInteger('T300OperatingMode', $this->Translate('T300 Operating Mode'), [], 24);
        $this->RegisterVariableFloat('T300T5EvaporatorIn', $this->Translate('T300 T5 Evaporator In'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 25);
        $this->RegisterVariableFloat('T300T6EvaporatorOut', $this->Translate('T300 T6 Evaporator Out'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 26);
        $this->RegisterVariableFloat('T300T20TankBottom', $this->Translate('T300 T20 Tank Bottom'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 27);
        $this->RegisterVariableFloat('T300T21TankMiddle', $this->Translate('T300 T21 Tank Middle'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'TEMPLATE' => VARIABLE_TEMPLATE_VALUE_PRESENTATION_ROOM_TEMPERATURE
        ], 28);
        $this->RegisterVariableBoolean('T300Compressor', $this->Translate('T300 Compressor'), [], 29);
        $this->RegisterVariableBoolean('T300Solar', $this->Translate('T300 Solar'), [], 30);
        $this->RegisterVariableBoolean('T300EHeaterRelay', $this->Translate('T300 E-Heater Relay'), [], 31);
        $this->RegisterVariableBoolean('T300Fan', $this->Translate('T300 Fan'), [], 32);
        $this->RegisterVariableBoolean('T300Defrost', $this->Translate('T300 Defrost'), [], 33);
        $this->RegisterVariableInteger('T300LastUpdate', $this->Translate('T300 Last Update'), [], 34);

        $this->SetTimerInterval('Poller', max(1, $this->ReadPropertyInteger('Interval')) * 1000);
    }

    public function RequestStatus(): void
    {
        // CurrentTemperature -> FC4, 263, INT16 (0.01 °C Resolution)
        $data = $this->RequestRegisters(4, 263, 1);
        if ($data === null) {
            return;
        }
        $currentTemperature = $this->ToSignedInt16($data[0]);
        $this->SetValue('CurrentTemperature', $currentTemperature / 100.0);

        // TargetTemperature -> FC3, 70, INT16 (0.01 °C Resolution)
        $data = $this->RequestRegisters(3, 70, 1);
        if ($data === null) {
            return;
        }
        $targetTemperature = $this->ToSignedInt16($data[0]);
        $this->SetValue('TargetTemperature', $targetTemperature / 100.0);

        if ($this->ReadPropertyBoolean('EnableT300')) {
            $this->RequestT300Status();
            return;
        }

        $this->SetValue('T300Available', false);
    }

    public function SetTemperature(float $value): void
    {
        $address = 70;
        $data = pack('n*', intval($value * 100));
        $result = $this->SendDataToParent(json_encode([
            'DataID' => '{E310B701-4AE7-458E-B618-EC13A1A6F6A8}',
            'Function' => 6,
            'Address' => $address,
            'Quantity' => 1,
            'Data' => bin2hex($data)
        ], JSON_THROW_ON_ERROR));

        if ($result === false) {
            return;
        }

        // The upstream basis exposes the target value optimistically. The later
        // read cycle remains authoritative and will replace it with read-back.
        $this->SetValue('TargetTemperature', $value);
    }

    public function RequestAction(string $ident, mixed $value): void
    {
        switch ($ident) {
            case 'TargetTemperature':
                $this->SetTemperature((float) $value);
                break;
        }
    }

    private function RequestT300Status(): void
    {
        // T300 behind FWT: holding 2000..2003, input 811..814 and 824..828.
        $setpoints = $this->RequestRegisters(3, 2000, 4);
        $temperatures = $this->RequestRegisters(4, 811, 4);
        $relays = $this->RequestRegisters(4, 824, 5);
        if ($setpoints === null || $temperatures === null || $relays === null) {
            $this->SetValue('T300Available', false);
            return;
        }

        $this->SetValue('T300NormalWaterTemperature', $setpoints[0] / 10.0);
        $this->SetValue('T300EHeaterEnabled', $setpoints[1] > 0);
        $this->SetValue('T300OperatingMode', $setpoints[2]);
        $this->SetValue('T300EHeaterTemperature', $setpoints[3] / 10.0);

        $this->SetValue('T300T5EvaporatorIn', ($temperatures[0] * 0.1) - 100.0);
        $this->SetValue('T300T6EvaporatorOut', ($temperatures[1] * 0.1) - 100.0);
        $this->SetValue('T300T20TankBottom', ($temperatures[2] * 0.1) - 100.0);
        $this->SetValue('T300T21TankMiddle', ($temperatures[3] * 0.1) - 100.0);

        $this->SetValue('T300Compressor', $relays[0] > 0);
        $this->SetValue('T300Solar', $relays[1] > 0);
        $this->SetValue('T300EHeaterRelay', $relays[2] > 0);
        $this->SetValue('T300Fan', $relays[3] > 0);
        $this->SetValue('T300Defrost', $relays[4] > 0);
        $this->SetValue('T300LastUpdate', time());
        $this->SetValue('T300Available', true);
    }

    /** @return list<int>|null */
    private function RequestRegisters(int $function, int $address, int $quantity): ?array
    {
        try {
            $payload = json_encode([
                'DataID' => '{E310B701-4AE7-458E-B618-EC13A1A6F6A8}',
                'Function' => $function,
                'Address' => $address,
                'Quantity' => $quantity,
                'Data' => ''
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            trigger_error($exception->getMessage(), E_USER_WARNING);
            return null;
        }

        $response = $this->SendDataToParent($payload);
        if ($response === false || !is_string($response) || strlen($response) < 2) {
            return null;
        }

        $values = unpack('n*', substr($response, 2));
        if ($values === false || count($values) < $quantity) {
            return null;
        }

        return array_values(array_slice($values, 0, $quantity));
    }

    private function ToSignedInt16(int $value): int
    {
        return $value >= 0x8000 ? $value - 0x10000 : $value;
    }
}
