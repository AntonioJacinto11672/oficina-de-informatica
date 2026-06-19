<?php

namespace App\adms\Models;
 use Mpdf\Mpdf;
 

class AdmsMpdf {

    private $dados;
    private $conn;
    
    public function __construct() {
        
    }

    public function teste() {
        echo "<h1>Testenda Outra vez</h1>";
        $html = "<h1>Testenda Mais Outra vez</h1>";
        $pdf = new Mpdf();
        
        
        
        $pdf->SetDisplayMode("fullpage");
        $pdf->WriteHTML($html);
        $pdf->Output();
        
        
    }

}
