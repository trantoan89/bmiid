<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils;

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
        'reason'
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

    // Return min year of case date in cases table in database
    public static function minYear() {
        return substr(self::min('case_date'), 0, 4);
    }

    public function getActiveCases($args) {
        $ret = [
            'date' => '',
            'disease_name' => ''
        ];

        $date = $args['date'];
        
        // Validate date
        if (strlen($date) != 10) {
            return $ret;
        }
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 7);
        
        if (!$year || !is_numeric($year)) {
            return $ret;
        }
        
        if ((!$month || !is_numeric($month)) && $day && 
            is_numeric($day)) {
            return $ret;
        }
        
        $ret['date'] = $year .' '. Utils::MONTHS[intval($month)];

        if (!isset($args['disease_id'])) {
            return $ret;
        }
        $disease_id = $args['disease_id'];
        if (!is_numeric($disease_id)) {
            return $ret;
        }

        $date_tag = "and YEAR(case_date)=$year";
        if (intval($day) != 0) {
            $date_tag = "and case_date='$year-$month-$day'";
        } else if (intval($month) != 0) {
            $date_tag .= " and MONTH(case_date)=$month";
        }
        
        $col_name = [
            'active'    => 'Active',
            'deceased'  => 'Deceased',
            'recovered' => 'Recovered'
        ];
        
        $result = DB::select("select case_date as 'Case date', active as ${col_name['active']}, deceased as ${col_name['deceased']}, recovered as ${col_name['recovered']}, barangay_name as 'Barangay name', concat(A.first_name, ' ', A.last_name) as 'Created by', concat(B.first_name, ' ', B.last_name) as 'Approved by', cases.created_at as 'Created at' from cases join barangay on cases.barangay_id = barangay.id join users A on cases.created_by = A.id join users B on cases.approved_by = B.id where disease_id=$disease_id $date_tag and active>0");
// Log::info('CasesModel', ['result' => $result]);
        $stat = [];
        $header = [];
        $content = [];
        if (!empty($result)) {
            $header = array_keys((array)($result[0]));
// Log::info('CasesModel', ['header' => $header]);
            $content = Utils::ao2aa($result, $header);
// Log::info('CasesModel', ['content' => $content]);

            $result = collect($result);
            $stat[$col_name['active']] = $result->sum($col_name['active']);
            $stat[$col_name['deceased']] = $result->sum($col_name['deceased']);
            $stat[$col_name['recovered']] = $result->sum($col_name['recovered']);
        }
        
        $ret['disease_name'] = DiseaseModel::find($disease_id)->disease_name;
        $ret['header'] = $header;
        $ret['content'] = $content;
        $ret['stat'] = $stat;
        
        // Log::info($ret);
        
        return $ret;
    }
}
