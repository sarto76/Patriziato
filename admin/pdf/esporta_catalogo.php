<?php

require('mc_table.php');
include '../../database.php';

class PDF extends FPDF
{
    function LoadData()
    {
        $connection = Database::getConnection();

        $result = $connection->query("SELECT `cognome`, `nome`, DATE_FORMAT(`data_nascita`,'%d-%m-%Y') as `data_nascita`, padre,madre
                                FROM patrizio
                                where vivente=1
                                and data_perdita_patrizio is null
                                order by cognome,data_nascita desc");

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            /*
            $cognome = $row["cognome"];
            $nome = $row["nome"];
            $data_nascita = $row["data_nascita"];
            $padre = $row["padre"];
            $madre = $row["madre"];
            $telefono = $row["telefono"];
            $email = $row["email"];
            $via = $row["via"];
            $nap = $row["nap"];
            $localita = $row["localita"];
            */


        }
        return $data;
    }

    // Simple table
    function BasicTable($header, $data)
    {
        // Header
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
    }
}

/*
$pdf = new PDF();
// Column headings
$header = array('Cognome', 'Nome', 'Data di nascita', 'Nome madre', 'Nome madre', 'telefono', 'email', 'via', 'nap', 'localit&agrave;');
// Data loading
$data = $pdf->LoadData();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);

$pdf->Output('patrizi.pdf','I');
*/
$loc = new PDF();
$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(40,40,30,35,35));
//srand(microtime()*1000000);
$dati=$loc->LoadData();
//print_r($dati);

for($i=0;$i<count($dati);$i++)
    $pdf->Row(array($dati[$i]['cognome'],$dati[$i]['nome'],$dati[$i]['data_nascita'],$dati[$i]['padre'],$dati[$i]['madre']));
$pdf->Output();
?>
