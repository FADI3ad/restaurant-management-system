<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateItemAction
{
    public function __invoke(Item $modelInstance, array $data): bool
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($modelInstance->image && Storage::disk('public')->exists($modelInstance->image)) {
                Storage::disk('public')->delete($modelInstance->image);
            }
            $path = $data['image']->store('items', 'public');
            $data['image'] = $path;
        } elseif (isset($data['image']) && is_string($data['image'])) {
            // Keep string path
        } else {
            unset($data['image']);
        }

        return $modelInstance->update($data);
    }
}