<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangayResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
       return [
        'id' => $this->id,
        'barangay_name' => $this->barangay_name, 
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'population' => $this->population,
        'archive' => $this->archive,
    ];
    }
}
