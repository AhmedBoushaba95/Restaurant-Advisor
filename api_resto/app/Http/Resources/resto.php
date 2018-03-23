<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class resto extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'description' => $this->description,
        'categorie' => $this->categorie,
        'note' => $this->note,
        'address' => $this->address,
        'phone' => $this->phone,
        'website' => $this->website,
        'open_week' => $this->open_week,
        'close_week' => $this->close_week,
        'open_weekend' => $this->open_weekend,
        'close_weekend' => $this->close_weekend
      ];
    }
}
