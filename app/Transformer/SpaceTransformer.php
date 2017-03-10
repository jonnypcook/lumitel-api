<?php

namespace App\Transformer;

class SpaceTransformer {

    public function transform($space) {
        return [
            'space_id' => $space->space_id,
            'installation_id' => $space->installation_id,
            'parent_id' => $space->parent_id,
            'image_id' => $space->image_id,
            'vendor_id' => $space->vendor_id,
            'name' => $space->name,
            'description' => $space->description,
            'level' => $space->level,
            'width' => $space->width,
            'height' => $space->height,
            'left' => $space->left,
            'top' => $space->top,
            'created' => $space->created_at->format('Y-d-m\TH:i:s')
        ];
    }

}