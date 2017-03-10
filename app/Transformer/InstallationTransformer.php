<?php

namespace App\Transformer;

class InstallationTransformer {

    public function transform($installation) {
        return [
            'installation_id' => $installation->installation_id,
            'name' => $installation->name,
            'commissioned' => $installation->commissioned
        ];
    }

}