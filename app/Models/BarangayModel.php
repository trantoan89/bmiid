<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayModel extends Model
{
    use HasFactory;

    protected $table = "barangay";

    protected $fillable = [
        'barangay_name', 
        'latitude',
        'longitude',
        'population',
        'archive',
    ];

    public function brngyCases()
    {
        return $this->hasMany(CasesModel::class, 'barangay_id');
    }
}
