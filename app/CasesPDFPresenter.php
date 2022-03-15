<?php
namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


// Table with MultiCells
class PDF_MC_Table extends PDF_Diag
{
    var $widths;
    var $aligns;

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}

class BaguioHeaderPDF extends PDF_MC_Table
{
    const A4_WIDTH = 210; // mm
    
    public $page_width = self::A4_WIDTH;

    public $margins = [
        'l' => 17, // left
        't' => 20, // top
        'r' => 15  // right
    ];
    
    function __construct($orientation='P', $unit='mm', $size='A4', 
                         $use_header=false) {
        parent::__construct($orientation, $unit, $size);

        $this->use_header = $use_header;
        
        $this->AddPage();
        
        if (!$use_header) {
            $this->_header_first_page();
        }
    }

    function Header() {
        if ($this->use_header) {
            $this->_header_first_page();
        }
    }
    
    function _header_first_page() {
        $this->SetMargins($this->margins['l'], $this->margins['t'],
                          $this->margins['r']);
        
		/* Text logo */
        $x_txt_logo = 20;
        $y = 17;
        $w_txt_logo = 90;
		$this->Image("../public/images/1280px-University_of_Baguio_".
                     "textlogo.png",$x_txt_logo, $y, $w_txt_logo);

        /* Logo */
        $x = $x_txt_logo + $w_txt_logo + 30;
        $y = $y * 3 / 4;
        $w = 50;
		$this->Image("../public/images/ubseal-2.png",$x, $y, $w);
        
        /* Address */
        $this->SetFont('Arial','B',14);
        $this->Cell(0, 36, '', 0, 1);
        $this->Cell($w_txt_logo, 14,'Gen. Luna Rd, Baguio, 2600 Benguet',0,1);
	}
}

class CasesPDFPresenter extends \App\Contracts\CasesPresenter
{
    protected $pdf;
    
    function __construct() {
        $this->pdf = new BaguioHeaderPDF();
    }
        
    protected function drawTitle($data) {
        $pdf = $this->pdf;
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0, 8, '',0,1);
        $pdf->Cell(40, 8, '',0,0);
        $pdf->Cell(100, 14, 'Baguio Map Integration for Infectious Diseases',0,1);
        
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25, 7, 'Case date: ',0,0);
        $pdf->Cell(25, 7, $data['date'],0,1);
        $pdf->Cell(25, 7, 'Disease: ',0,0);
        $pdf->Cell(25, 7, $data['disease_name'],0,1);

        return $pdf;
    }
    
    protected function drawTable($t_header, $t_content) {
        $pdf = $this->pdf;
        
        $pdf->margins['l'] = 10;
        $pdf->margins['r'] = 10;
        $pdf->SetLeftMargin($pdf->margins['l']);
        $pdf->SetRightMargin($pdf->margins['r']);
        $pdf->Cell(25, 7, '',0,1);

        // Calculate size for each column
        $col_width = [];
        $sum_s = 0;
        foreach ($t_header as $h) {
            $l = strlen($h);
            $col_width[] = $l;
            $sum_s += $l;
        }
        
        $m = $pdf->page_width - $pdf->margins['l'] - $pdf->margins['r'];
        foreach ($col_width as $i => $s) {
            $col_width[$i] = (int)round($s / $sum_s * $m, 0);
        }

        $pdf->SetWidths($col_width);
        
        // Print header
        $pdf->SetFont('Arial','B',10);
        $pdf->Row($t_header);
        
        // Print content
        $pdf->SetFont('Arial','',10);
        foreach ($t_content as $content) {
            $pdf->Row($content);
        }
        
        return $pdf;
    }
    
    protected function drawPieChart($data) {
        $pdf = $this->pdf;
        
        $valX = $pdf->GetX();
        $valY = $pdf->GetY();
        $pdf->SetXY(55, $valY+4);
        $colors = [
            [255, 0, 0],   // Red
            [255, 165, 0], // Yellow
            [0, 128, 0]    // Green
        ];
        $pdf->PieChart(120, 60, $data, '%l (%p)', $colors);
        $pdf->SetXY($valX, $valY + 60);
        
        return $pdf;
    }
    
    protected function drawBarChart($data) {
        $pdf = $this->pdf;
        
        $pdf->Ln(8);
        $valX = $pdf->GetX();
        $valY = $pdf->GetY();
        $pdf->BarDiagram(180, 60, $data, '%l: %v (%p)', [82,206,255]);
        $pdf->SetXY($valX, $valY + 60);
        
        return $pdf;
    }
    
    public function output()
    {
        $data = $this->data;
        
        $pdf = $this->drawTitle($data);
        
        if (!isset($data['header'])) {
            $pdf->Output();
            exit;
        }
        
        if (!empty($data['stat'])) {
            $pdf = $this->drawPieChart($data['stat']);
            $pdf = $this->drawBarChart($data['stat']);
        }
        
        $pdf = $this->drawTable($data['header'], $data['content']);
        
        // Add text "Generated by: user_name"
        $user = Auth::user();
        $user_name = $user->first_name .' '. $user->last_name;
        $pdf->Ln(4);
        $pdf->Cell($pdf->page_width - $pdf->margins['l'] - $pdf->margins['r'],
                   8, "Generated by: \"$user_name\"",0,0,'R');

        $pdf->Output();
        exit;
    }
}
