<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=> $this->book_title,
            'summary'=> $this->book_summary,
            'price' => $this->book_price,
            'final_price' => $this->final_price,
            'rating' => $this->star,
            'author' => $this->author,
            'category' => $this->category,
            'reviews' => $this->reviews
        ];
    }
}
