<?php

namespace App\Transformer;

use App\Models\DeviceSanitise;

class DeviceTransformer {

    public function transform($device) {
        return [
            'device_id' => $device->device_id,
            'label' => $device->label,
            'space_id' => $device->space_id,
            'x' => $device->x,
            'y' => $device->y,
            'last_reading_current' => $device->last_reading_current,
            'last_reading_total' => $device->last_reading_total,
            'last_reading_at' => (empty($device->last_reading_at) ? null: $device->last_reading_at->format('Y-m-d\TH:i:s')),
            'created' => $device->created_at->format('Y-d-m\TH:i:s'),
            'deviceType' => $device->deviceType,
            'provider' => ($device->provider instanceof DeviceSanitise) ? $device->provider->sanitise() : []
        ];
    }

}