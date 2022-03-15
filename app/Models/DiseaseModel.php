<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseModel extends Model
{
    use HasFactory;

    protected $table = "disease";

    protected $fillable = [
        'disease_name', 
        'description',
        'homepage',
        'archive',
    ];

    public function cases()
    {
        return $this->hasMany(CasesModel::class, 'disease_id');
    }

    public function risk()
    {
        return $this->hasOne(Risk::class, 'disease_id');
    }
}
