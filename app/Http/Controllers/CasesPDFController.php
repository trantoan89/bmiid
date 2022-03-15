<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Contracts\CasesPresenter;
use App\Models\CasesModel;
 
class CasesPDFController extends Controller
{
    public function __construct(CasesModel $cases, 
                                CasesPresenter $presenter)
    {
        $this->cases = $cases;
        $this->presenter = $presenter;
    }
    
    public function getPDF(Request $request)
    {
        $date = $request->input('date'); // yyyy-mm-dd
                                         // dd can be 00
                                         // mm can be 00
                                         // yyyy must be a valid year
        $disease_id = $request->input('dsid');
        
        if (!$date || !$disease_id) {
            return '';
        }

        $data = $this->cases->getActiveCases([
            'date' => $date,
            'disease_id' => $disease_id
        ]);
        
        $this->presenter->load($data);
        
        $this->presenter->output();
        
        // exit;
    }
}