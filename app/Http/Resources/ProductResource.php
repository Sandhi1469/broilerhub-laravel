<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imgUrl = null;
        if (!empty($this->img)) {
            if (str_starts_with($this->img, 'http://') || str_starts_with($this->img, 'https://')) {
                $imgUrl = $this->img;
            } else {
                $imgUrl = url('storage/' . ltrim($this->img, '/'));
            }
        }

        return [
            'id' => $this->product_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'img' => $this->img,
            'img_url' => $imgUrl,
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category?->category,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
