<?php 

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateItemAction
{
    public function __invoke(array $data): Item
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $path = $data['image']->store('items', 'public');
            $data['image'] = $path;
        } elseif (isset($data['image']) && is_string($data['image'])) {
            // Keep string path as is
        } else {
            unset($data['image']);
        }

        return Item::create($data);
    }
}
