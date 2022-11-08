<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    public function toArray($request)
    {
        // dd($this->collection);
        return [
            'data' => $this->collection
                ->map
                ->toArray($request)
                ->all(),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
