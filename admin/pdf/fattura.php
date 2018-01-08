<?php


include '../../database.php';
require('fpdf.php');


class PDF extends FPDF
{


    function Header()
    {
        // Select Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Framed title
        // Logo
        $this->Image('intestazione.png',10,10,178);
        // Line break
        $this->Ln(35);
    }

    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','',10);
        $this->Cell(10);
        // Print centered page number
        $this->Cell(0,10,'- Cedola di versamento',0,0,'L');

    }


}




$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $stagione=$_GET['stagione'];
    $costo=$_GET['costo'];

}

$connection = Database::getConnection();
$result = $connection->query("SELECT cognome,nome, via,nap,localita
                                FROM patrizio
                                where id=$id");

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}


$tipologia="Persona singola";
if($costo==200){
    $tipologia="Nucleo famigliare con figli minorenni";
}

$nome=$data[0]['nome'];
$cognome=$data[0]['cognome'];


$pdf->Cell(120);
$pdf->Cell(60,10,$nome." ".$cognome);

$pdf->ln();
$pdf->Cell(120);
$pdf->Cell(60,10,$data[0]['via']);
$pdf->ln();
$pdf->Cell(120);
$pdf->Cell(60,10,$data[0]['nap']." ".$data[0]['localita']);
$pdf->ln();
$pdf->ln();
$tDate=date('d-m-Y');
$pdf->Cell(100);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60, 10, 'Bosco Gurin, '.$tDate, 0, false, 'R');

$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->Cell(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(60,10,"Fattura per iscrizione tessera impianti di risalita stagione $stagione ");

$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->SetFont('Arial','',11);
$pdf->Cell(10);
$pdf->Cell(60,10,"$tipologia");
$pdf->Cell(80);
$pdf->Cell(60,10,"Fr. $costo");
$pdf->ln();
$pdf->Line(20, 170, 180, 170);
$pdf->Cell(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,40,"Totale ");
$pdf->Cell(80);
$pdf->Cell(60,40,"Fr. $costo");

$pdf->Line(20, 180, 180, 180);
$pdf->Line(20, 181, 180, 181);
$pdf->ln();
$pdf->Cell(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,40,"Cordiali saluti");
$pdf->ln();
$pdf->Cell(145);
$pdf->Cell(60,40,"Il Patriziato. ");


$pdf->Output();
$pdf->Output('fatture/'.$cognome.'_'.$nome.'_'.$stagione.'.pdf');
?>
