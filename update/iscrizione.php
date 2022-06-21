<?php
session_start();
if (!$_SESSION['pass']) {
    header("location:index.php");
    die;
}
include '../database.php';
?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../css/assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../css/dist/js/bootstrap.min.js"></script>
<!-- <script src="css/assets/js/docs.min.js"></script> -->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../css/assets/js/ie10-viewport-bug-workaround.js"></script>




<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="utf-8">


    <title>Iscrizione registro impianti risalita</title>


    <!-- Bootstrap core CSS -->
    <link href="../css/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap theme -->
    <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/navbar.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/theme.css" rel="stylesheet">


</head>
<body>


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">

                <li><a href="update.php">Modifica dati personali</a></li>
                <li class="active"><a href="iscrizione.php">Iscrizione registro impianti risalita </a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>

<div class="container-fluid">


    <?php

    //se ho cliccato  il tasto esegui (update o delete)
    if (isset($_POST['butt'])) {
        if(isset($_POST['check'])) {
            $chiavi = ($_POST['check']);

            $connection = Database::getConnection();
            $stagione = $_POST['stagione'];

            //ricavo la stagione
            $stag = "select idstagione from stagione where anni='$stagione'";
            if (!$result = $connection->query($stag)) {
                //printf("Errormessage: %s\n", $connection->error);
                echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
            }
            $sta = $result->fetch_array(MYSQLI_NUM);
            $stag_id = $sta[0];

            $num=0;
            $richiedenti=count($chiavi);
            $mancanoDati=0;
            $costo=0;

            //controllo per sicurezza se sono 0
            if ($richiedenti == 0) {
                $costo = 0;
            } //se c'è un solo richiedente il prezzo sarà 100
            else if ($richiedenti == 1) {
                $costo = 100;
                //in ogni caso se sono 2 pagano 200 (che ci siano o meno maggiorenni)
            } else if ($richiedenti == 2) {
                $costo = 200;

            }
            //se sono più di 2 conto tutti i maggiorenni e faccio 100 fr a testa
            else {
                foreach ($chiavi as $val) {

                    $dati = "select diritto_di_voto from patrizio where id =$val and confermato=1";
                    if (!$result = $connection->query($dati)) {
                        //printf("Errormessage: %s\n", $connection->error);
                        echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                    }
                    $tutto = $result->fetch_array(MYSQLI_NUM);


                    //se è maggiorenne
                    if ($tutto[0] == 1) {
                            $costo = $costo + 100;
                            //echo $costo;
                    }


                }
            }


            foreach ($chiavi as $val) {

                //controllo che non ci siano doppioni
                $doppioni = "select nome,cognome from patrizio where confermato=1 and id in (select fk_patrizio from tessera where fk_stagione='$stag_id' and fk_patrizio=$val)";
                if (!$result = $connection->query($doppioni)) {
                    //printf("Errormessage: %s\n", $connection->error);
                    echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                }
                $nm = $result->fetch_array(MYSQLI_NUM);
                $row_cnt = $result->num_rows;
                $doppione = 0;




                //controllo se ha immesso i dati per la fattura
                $id = $_SESSION['id'];
                $dati = "select via,nap,localita,nome,cognome,diritto_di_voto,password from patrizio where id =$id and confermato=1";
                if (!$result = $connection->query($dati)) {
                    //printf("Errormessage: %s\n", $connection->error);
                    echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                }
                $tutto = $result->fetch_array(MYSQLI_NUM);

            //print_r($tutto);
                //se c'è un doppione
                if ($row_cnt > 0) {

                    $nome = $nm[0];
                    $cognome = $nm[1];
                    echo("<div class='alert alert-danger'>Errore: " . $nome . " " . $cognome . " ha gi&agrave; eseguito la richiesta per questa stagione.</div>");
                    $doppione = 1;

                }
                //se non ha inserito NAP e località e password
                else if ($tutto[1] == "" || $tutto[2] == "" || $tutto[6] == "") {

                    if(!$mancanoDati) {
                        echo("<div class='alert alert-danger'>Errore: " . $tutto[3] . " " . $tutto[4] . " non ha inserito via,NAP, localit&agrave; o la password. 
                                Per poter registrare la richiesta &egrave; necessario dapprima inserire questi dati nel menu <i><a href='update.php'>Modifica dati personali</a></i>.</div>");
                        $mancanoDati = 1;
                    }
                }
                else {

                    //se si tratta del componente che ha fatto login immetto l'importo della fattura
                    if ($val == $_SESSION['id']) {


                        $sql = "INSERT into tessera (fk_patrizio,fk_stagione,attiva,data_richiesta,costo) values ($val,$stag_id,0,now(),$costo)";
                        //echo($sql);
                        if (!$connection->query($sql)) {
                            //printf("Errormessage: %s\n", $connection->error);
                            echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                        }


                    } //per gli altri componenti non la immetto
                    else {
                        //query di inserimento
                        $sql = "INSERT into tessera (fk_patrizio,fk_stagione,attiva,data_richiesta) values ($val,$stag_id,0,now())";
                        //echo($sql);
                        if (!$connection->query($sql)) {
                            //printf("Errormessage: %s\n", $connection->error);
                            echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                        }
                    }



                    $timestamp = date("Y-m-d H:i:s");
                    $log="insert into log (id_patrizio,data_att) values ($id,'$timestamp')";
                    if (!$connection->query($log)) {

                        //printf("Errormessage: %s\n", $connection->error);
                        echo('<div class="alert alert-danger">Problema di connessione log. PF inviare una mail a patriziato.bosco@gmail.com</div>');
                    }


                }

                $num++;
            }


            if (!$doppione && !$mancanoDati)
                echo('<div class="alert alert-success">Modifiche effettuate con successo.</div>');
        }
    }


    $dataRich       = date('md'); // on 4th may 2016, would have been 20160504
    $inizio     = 1101;
    $fine       = 1231;
    $annoAttuale= date("Y");
    $prossimoAnno = strtotime("+1 year", time());
    $prossimoAnno = date("Y", $prossimoAnno);
    $scorsoAnno = strtotime("-1 year", time());
    $scorsoAnno = date("Y", $scorsoAnno);


    if(isset($_POST['stagione'])) {
        $anni = $_POST['stagione'];
      //  echo "set ".$anni;
    }
    else{
        $anni=$annoAttuale.'-'.$prossimoAnno;
       // echo "not set ".$anni;
    }

    $id = $_SESSION['id'];
    //echo($id);


    //titolo
    echo('<h2>Iscrizione per tessera impianti di risalita</h2><br>');
    //tiro fuori i patrizi che hanno lo stesso numero di registro
    $query="    SELECT * FROM patrizio p 
                WHERE confermato=1
                AND no_registro 
                in (select no_registro from patrizio where confermato=1 AND id=$id) 
                and id not in(
                SELECT id FROM patrizio p left join tessera t
                on p.id=t.fk_patrizio
                left join stagione s 
                on s.idstagione=t.fk_stagione
                WHERE s.anni='$anni' and confermato=1)
                order by data_nascita ";
    if ($result = $connection->query($query)) {


        //form
        echo('<form method="POST" enctype="multipart/form-data" action="iscrizione.php">');

        echo("<div class='form-row'>");


        if (isset($_POST['stagione'])) {
            $anni = $_POST['stagione'];
            $txt = $annoAttuale . '-' . $prossimoAnno;
            $txt2 = $scorsoAnno . '-' . $annoAttuale;
            if ($dataRich >= $inizio && $dataRich <= $fine) {
                if ($txt == $anni) {
                    $testo = "
                    <option value='$txt' selected>$txt</option>>";
                } else {
                    $testo = "
                    <option value='$txt'>$txt</option>";
                }
            } else {
                if ($txt == $anni) {
                    $testo = "
                    <option value='$txt' selected>$txt</option>
                    <option value='$txt2'>$txt2</option>";
                } else {
                    $testo = "
                    <option value='$txt'>$txt</option>
                    <option value='$txt2' selected>$txt2</option>";
                }
            }


        } else {


            //se la data va dal 1 novembre al 31 dicembre metto solo il prossimo anno
            if ($dataRich >= $inizio && $dataRich <= $fine) {
                $txt = $annoAttuale . '-' . $prossimoAnno;
                $testo = "
                    <option value='$txt'>$txt</option>";
            }//altrimenti metto anche lo scorso
            else {
                $txt = $annoAttuale . '-' . $prossimoAnno;
                $txt2 = $scorsoAnno . '-' . $annoAttuale;
                $testo = "
                    <option value='$txt'>$txt</option>
                    <option value='$txt2'>$txt2</option>";
            }

        }

        echo("<div class='form-row'>");

        echo("<label for='sel1'>Scegliere la stagione:</label>");
        echo("<select class='form-control' name='stagione' id='stagione' onchange='this.form.submit()'>");
        echo $testo;
        echo("</select>");
        echo("</div>");

        echo("</div>");
        echo("<p></p>");
        echo("<hr></hr>");

        //creo un contatore che verifica se ci sono persone, se non ce ne sono nascondo il bottone Richiedi iscrizione
        $i = 0;

        while ($row = mysqli_fetch_array($result)) {
            $cognome = $row['cognome'];
            $nome = $row['nome'];
            $nascita = mysqli_real_escape_string($connection, $row['data_nascita']);
            $nas = explode('-', $nascita);
            $giorno = $nas[0];
            $mese = $nas[1];
            $anno = $nas[2];
            $nascita = $anno . '-' . $mese . '-' . $giorno;
            $id = $row['id'];

            echo('<input type="hidden" name="id[]"  value=' . $row['id'] . '>');


            echo("<div class='form-row'>");

            echo("<div class='form-group col-md-4'>");
            echo("<input type='text' class='form-control' name='nome[]' value='$nome' readonly>");
            echo('</div>');

            echo("<div class='form-group col-md-4'>");
            echo("<input type='text' class='form-control' name='cognome[]' value='$cognome' readonly> ");
            echo('</div>');

            echo("<div class='form-group col-md-2'>");
            echo("<input type='text' name='data_nascita[]' id='data_nascita'  class='form-control'  value='$nascita' readonly>");
            echo('</div>');

            echo("<div class='form-group col-md-2'>");
            echo("<label class='form-check-label'>");

            $dis = "";
            $sel = "Seleziona";
            if ($_SESSION['id'] == $id) {
                $dis = "onclick='return false;'";
                $sel = "Selezionato";
            }

            echo("<input class='form-check-input' type='checkbox' name='check[]' value=$id checked $dis> $sel");
            echo("</label>");
            echo("</div>");


            echo('</div>');
            echo("<p></p>");

            $i++;
        }

        $id = $_SESSION['id'];

        $stmt = $connection->prepare("SELECT * FROM patrizio WHERE confermato = 1 AND id= ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();



        $dati = $result->fetch_array(MYSQLI_ASSOC);
        $nome = $dati['nome'];
        $cognome = $dati['cognome'];
        $via = $dati['via'];
        $nap = $dati['nap'];
        $localita = $dati['localita'];

        //se c'è almeno una persona nel ciclo while
        if ($i > 0) {
            echo("<div class='form-group col-md-4'>");
            echo('<input class="btn btn-primary" type="submit" value="Richiedi iscrizione" name="butt"></input>');
            echo("</div>");
        }


        echo('</form>');

        //se c'è almeno una persona nel ciclo while
        if ($i > 0) {
            echo("<div>");
            echo("<div class='form-group col-md-12'>");
            echo("<div class='alert alert-info'>");
            echo "<b>La fattura verr&agrave; inviata al seguente indirizzo: $nome $cognome, $via, $nap $localita</b>";
            echo("</div>");
            echo("</div>");
            echo("</div>");
        }
        else{
            echo("<div>");
            echo("<div class='form-group col-md-12'>");
            echo("<div class='alert alert-info'>");
            echo "<b>Ha gi&agrave; eseguito l'iscrizione per questa stagione</b>";
            echo("</div>");
            echo("</div>");
            echo("</div>");
        }
    }


    ?>

</div> <!-- /container -->

</body>
</html>











