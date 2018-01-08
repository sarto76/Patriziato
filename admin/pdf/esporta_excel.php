<?php

require('mc_table.php');
include '../../database.php';


function LoadData()
{
    $connection = Database::getConnection();

    $result = $connection->query("SELECT `no_registro`,id, `cognome`, `nome`, DATE_FORMAT(`data_nascita`,'%d-%m-%Y') as `data_nascita`, padre,madre,
                                IF((TIMESTAMPDIFF(YEAR,`data_nascita`,CURDATE())<18),'no','si') 
                                as `diritto_di_voto`, `vivente`, data_inserimento,data_morte,diritto_di_voto,data_perdita_patrizio,telefono,email,
                                via, nap,localita,foto
                                FROM patrizio
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




//$res=LoadData();
//print_r($res);


// Download file
header('Content-Type: text/csv; charset=utf-8');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"catalogo_vers_".date("d-m-Y").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");


$res=LoadData();
// Write data to file

$flag = false;
foreach ($res as $risultati) {
    if (!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($risultati)) . "\r\n";
        $flag = true;
    }
    echo implode("\t", array_values($risultati)) . "\r\n";
}


?>
