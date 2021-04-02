<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\BarangayResource;

class CasesResource extends JsonResource
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
            'case_date' => $this->case_date, 
            'active' => $this->active,
            'deceased' => $this->deceased,
            'recovered' => $this->recovered, 
            'barangay_id' => $this->barangay_id,
            'disease_id' => $this->disease_id,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'dicease' => new DiseaseResource($this->disease),
            'barangay' => new BarangayResource($this->barangay),
            'createdBy' => new UserResource($this->createdBy),
            'approvedBy' => new UserResource($this->approvedBy),
        ];
    }
}
