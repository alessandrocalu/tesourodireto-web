<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'third_party/fpdf/fpdf.php';

class PdfRelat extends FPDF
{
    var $pdf;
    var $widths;
    var $aligns;
    
    public function createPdf() {
		$this->AddPage();      
    }
    
    public function addTitle($title) {
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30,10,$title,0,0,'C');
		// Line break
		$this->Ln(20);						        
    }
    
    public function renderPdf() {
        $this->Output();  
    }

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns=$a;
    }    
    
    // Colored table
    public function fancyTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Header
        $w = array(40, 35, 40, 45);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
            $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
            $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
    }    
    
    public function improvedTable($header, $data, $_widths = null, $_aligns = null) {
        // Column widths
        //$w = array(40, 35, 40, 45);
        $w = 190/count($header);
        $widths = array();        
        
        $this->SetFont('Courier','B',10);
        
        // Header
        for($i=0;$i<count($header);$i++){
            $widths[] = $w;                
        }
        
        if($_widths != null) {
            $widths = $_widths;
        }
        
        if($_aligns != null) {
            $this->SetAligns($_aligns);
        }
               
        $this->SetWidths($widths);
            
        $this->Row($header);
        // Data
        foreach($data as $row)
        {
            $this->SetFont('Courier','',8);
            $this->Row($row, $_aligns);
            // for($i=0;$i<count($header);$i++)
            //     $this->MultiCell($w, 7, $row[$i], 0, 'LR');
                
        }
        // Closing line
        $this->Cell(190,0,'','T');
    }    
    
    function Row($data)
    {   
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=7*$nb;
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
            $this->SetDrawColor(221,221,221);
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,6,utf8_decode($data[$i]),0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt)
    {
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