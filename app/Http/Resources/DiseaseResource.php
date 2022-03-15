<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RiskResource;

class DiseaseResource extends JsonResource
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
            'disease_name' => $this->disease_name,
            'description' => $this->description,
            'homepage' => $this->homepage,
            'archive' => $this->archive,
            'risk' => new RiskResource($this->risk),
        ];
    }
}
