<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasesModel extends Model
{
    use HasFactory;

    protected $table = "cases";

    protected $fillable = [
        'case_date', 
        'active',
        'deceased',
        'recovered', 
        'barangay_id',
        'disease_id',
        'status',
        'created_by',
        'approved_by',
    ];

    public function disease()
    {
        return $this->belongsTo(DiseaseModel::class, 'disease_id');
    }

    public function barangay()
    {
        return $this->belongsTo(BarangayModel::class, 'barangay_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
