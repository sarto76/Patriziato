
<?php
/*
session_start();
if (!$_SESSION['id']) {
    header("location:index.php");
    die;

}*/


///PAGINA LATO SERVER CHE ESEGUE LE RICHIESTE AL DB //////////////

//creo oggetto PDO per il db SQLite
include '../database.php';
$connection = Database::getConnection();


$nome=$_POST['nome'];
$cognome=$_POST['cognome'];
$data_nascita=$_POST['data_nascita'];
$data_nascita = str_replace(".", "-", $data_nascita);
$data_nascita = str_replace(' ', '', $data_nascita);

$nas = explode('-', $data_nascita);

$giorno = $nas[0];
$mese = $nas[1];
$anno = $nas[2];
$data_nascita = $anno . '-' . $mese . '-' . $giorno;


$sql = "select * from patrizio where nome like '%".$nome."%' AND cognome like '%".$cognome."%' AND data_nascita='$data_nascita'";
//$result = $connection->prepare($sql);
//$result->bind_param('sss', $nome, $cognome, $nascita);
//$result->execute();
//$result->bind_result($id);

$result = $connection->query($sql);
$data = array();
while($row = $result->fetch_assoc()){
    $data[] = $row;
}
echo json_encode($data);




/*
if (!empty($pass))//se c'è una password
{


} else {
    //Altrimenti stampo un errore
    echo('<h3 style="text-align: center">Dati di accesso non corretti</h3>');
}


//ricostruisco la query completa
$sql = $select . $from . $where;
//preparo la query
$statement = $connSqlite->prepare($sql);
//eseguo la query
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
//metto il risultato della query in forma json es {"nome":"Bob","cognome":"Dylan","anni":"68","genere":"Dance"}

$json = json_encode($results);
*/
//ritorno jason al client
//echo($json);
?>


	




