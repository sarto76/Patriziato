<?php
session_start();
if(!$_SESSION['username']){
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


<script type="text/javascript">

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
                $('#blah').attr('height', 52);
                $('#blah').attr('width', 42);

            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        $("#foto").change(function () {
            readURL(this);
        });
    });

    function isValidDate(date,err) {
        //alert(err);
        var matches = /^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/.exec(date);
        if (matches == null){
            $(err).css('color', 'red');
            $(err).html( "Formato data non corretto, formato corretto dd-mm-yyyy" );
        }
        var m = matches[2]-1;
        var d = matches[1];
        var y = matches[3];
        //alert(m);
        var composedDate = new Date(y, m, d);
        if (!((composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y))) {
            //alert("Formato data non corretto, formato corretto dd-mm-yyyy");
            $(err).css('color', 'red');
            $(err).html( "Formato data non corretto, controllare anni, mesi e giorni" );

        }
        else{
            $(err).html( "" );
        }
    }



    //no registro max 3 numeri
    $("#registroId").keyup(function () {
        $("#registroId").val(this.value.match(/[0-9]*/));
    });



</script>


<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="utf-8">


    <title>Richieste Patrizi</title>

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

<div class="container">


    <?php

    if (isset($_GET['id'])) {
        $_SESSION["id"] = $_GET['id'];
    }


    //se ho cliccato  il tasto esegui (update o delete)
    if (isset($_POST['butt'])) {
        $connection = Database::getConnection();
        //riprendo i valori necessari
        $id = $_SESSION['id'];


        $no_registro = mysqli_real_escape_string($connection, $_POST['no_registro']);
        $cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
        $nome = mysqli_real_escape_string($connection, $_POST['nome']);
        $telefono = mysqli_real_escape_string($connection, $_POST['telefono']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $via = mysqli_real_escape_string($connection, $_POST['via']);
        $nap = mysqli_real_escape_string($connection, $_POST['nap']);
        if ($nap == '')
            $nap = 'null';


        $localita = mysqli_real_escape_string($connection, $_POST['localita']);
        $padre = mysqli_real_escape_string($connection, $_POST['padre']);
        $madre = mysqli_real_escape_string($connection, $_POST['madre']);

        $data_nascita = mysqli_real_escape_string($connection, $_POST['data_nascita']);
        $nas = explode('-', $data_nascita);
        //print_r ($nas);
        $data_nascita = $nas[2] . '-' . $nas[1] . '-' . $nas[0];


        $diritto_di_voto = mysqli_real_escape_string($connection, $_POST['diritto_di_voto']);

        $confermato = mysqli_real_escape_string($connection, $_POST['confermato']);

        //query di inserimento
        $sql = "UPDATE patrizio SET no_registro='$no_registro',cognome='$cognome',nome='$nome',data_nascita='$data_nascita',
        confermato='$confermato',
        telefono='$telefono',email='$email',via='$via',
        nap=$nap,localita='$localita',padre='$padre', madre='$madre' WHERE id=$id";

        //echo($sql);

        $timestamp = date("Y-m-d H:i:s");
        $log="insert into log (id_patrizio,data_att) values ($id,'$timestamp')";

        if (!$connection->query($sql)) {
            //printf("Errormessage: %s\n", $connection->error);
            echo('<div class="alert alert-danger">Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com</div>');
        }

        else if (!$connection->query($log)) {

            printf("Errormessage: %s\n", $connection->error);
            echo('<div class="alert alert-danger">Problema di connessione log. PF inviare una mail a patriziato.bosco@gmail.com</div>');
        }
        else {
            //echo('<div class="alert alert-success">Modifiche effettuate con successo.</div>');
            header('Location: non_confermati.php');
        }
    }


    $id = $_SESSION['id'];
    //echo($id);

    ?>
    <?php
    $page = 'non_confermati';
    $titolo = 'Richieste';
    include('menu.php');

    ?>
    <?php

    //titolo
    echo('<h2>Modifica non confermati</h2><br>');
    if ($result = $connection->query("SELECT *FROM patrizio WHERE id=$id"))
    {
        //form
        echo('<form method="POST" enctype="multipart/form-data" action="mod_non_confermati.php" class="form-vertical">');
        while ($row = mysqli_fetch_array($result)) {

            //id nascosto
            //$id=$row['id'];
            echo('<input type="hidden" name="id1"  value=' . $row['id'] . '>');


            echo('<label >Numero registro:</label>');

            $no_registro=$row['no_registro'];
            echo("<input type='text' class='form-control' name='no_registro' value='$no_registro' >");
            echo("<p></p>");

            //nome
            echo('<label >Nome:</label>');
            //print_r($row['nome']);
            $nome=$row['nome'];
            echo("<input type='text' class='form-control' name='nome' value='$nome' >");
            echo("<p></p>");

            echo('<label >Cognome:</label>');
            $cognome=$row['cognome'];
            echo("<input type='text' class='form-control' name='cognome' value='$cognome' > ");
            echo("<p></p>");

            echo('<label >Data di nascita:</label>');


            $nascita = $row['data_nascita'];
            if($nascita!="") {
                $nas = explode('-', $nascita);
                $giorno = $nas[0];
                $mese = $nas[1];
                $anno = $nas[2];
                $nascita = $anno . '-' . $mese . '-' . $giorno;
            }

            //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
            //echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_nascita'].'>');
            echo("<input type='text' required name='data_nascita' id='data_nascita' onblur='isValidDate(this.value,\"#errore_data\")' class='form-control' placeholder='gg-mm-aaaa'  value='$nascita'>");

            echo('<label ></label>');


            echo('<div id="errore_data"></div>');
            echo("<p></p>");

            echo('<label >Cognome e nome del padre:</label>');

            $padre=$row['padre'];
            echo("<input type='text' class='form-control' name='padre'  value='$padre'>");
            echo("<p></p>");


            echo('<label >Cognome e nome della madre:</label>');

            $madre=$row['madre'];
            echo("<input type='text' class='form-control' name='madre'  value='$madre'>");

            echo("<p></p>");


            echo('<label >Diritto di voto:</label>');
            //print_r($row['nome']);
            $diritto_di_voto=$row['diritto_di_voto'];
            //echo("<input type='text' class='form-control' name='nome' value='$diritto_di_voto' >");

            ?>
            <input type="radio" name="diritto_di_voto" <?php if( $diritto_di_voto == "1") { echo "checked"; }?>  value="1">sì
            <input type="radio" name="diritto_di_voto" <?php if( $diritto_di_voto == "0") { echo "checked"; }?> value="0">no

            <?php
            echo("<p></p>");

            echo('<label >Telefono:</label>');

            $telefono=$row['telefono'];
            echo("<input type='text' class='form-control' name='telefono' placeholder='es. 079.332.22.22'  value='$telefono'>");

            echo("<p></p>");
            echo('<label >E-mail:</label>');

            $email=$row['email'];
            echo("<input type='text' class='form-control' name='email' value='$email'>");
            echo("<p></p>");

            echo('<label >via:</label>');

            $via=$row['via'];
            echo("<input type='text' class='form-control' name='via'  value='$via'>");
            echo("<p></p>");

            echo('<label >NAP:</label>');

            $nap=$row['nap'];
            echo("<input type='text' class='form-control' name='nap'  value='$nap'>");
            echo("<p></p>");

            echo('<label >Localit&agrave;:</label>');

            $localita=$row['localita'];
            echo("<input type='text' class='form-control' name='localita'  value='$localita'>");
            echo("<p></p>");

            echo('<label >Confermato:</label>');
            //print_r($row['nome']);
            $confermato=$row['confermato'];


            ?>
            <input type="radio" name="confermato" <?php if( $confermato == "1") { echo "checked"; }?>  value="1">confermato
            <input type="radio" name="confermato" <?php if( $confermato == "0") { echo "checked"; }?> value="0">non confermato

            <?php

            //bottone modifica

            echo("<p></p>");
            echo('<input class="btn btn-primary" type="submit" value="Modifica" name="butt"></input>');




        }
        echo('</form>');
    }


    ?>

</div> <!-- /container -->

</body>
</html>











