<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use HasFactory;

    protected $table = "percent";

    protected $fillable = [
        'low_risk', 
        'severe_risk',
        'high_risk',
        'disease_id',
    ];

    public function riskPercent()
    {
        return $this->belongsTo(DiseaseModel::class, 'disease_id');
    }
}
