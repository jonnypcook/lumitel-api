<?php

namespace App\Transformer;

class InstallationTransformer {

    public function transform($installation) {
        return [
            'installation_id' => $installation->installation_id,
            'name' => $installation->name,
            'commissioned' => $installation->commissioned,
            'address' => [
                'address_id' => $installation->address->address_id,
                'line1' => $installation->address->line1,
                'line2' => $installation->address->line2,
                'line3' => $installation->address->line3,
                'city' => $installation->address->city,
                'postcode' => $installation->address->postcode,
                'longitude' => $installation->address->longitude,
                'latitude' => $installation->address->latitude,

            ]
        ];
    }

}