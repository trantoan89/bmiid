<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiskResource extends JsonResource
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
            'low_risk' => $this->low_risk, 
            'severe_risk' => $this->severe_risk,
            'high_risk' => $this->high_risk,
            'disease_id' => $this->disease_id,
        ];
    }
}
