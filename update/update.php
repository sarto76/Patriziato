<?php
session_start();
if (!$_SESSION['id']) {
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

    function isValidDate(date) {
        var matches = /^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/.exec(date);
        if (matches == null) return false;
        var m = matches[2]-1;
        var d = matches[1];
        var y = matches[3];
        //alert(m);
        var composedDate = new Date(y, m, d);
        if (!((composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y))) {
            //alert("Formato data non corretto, formato corretto dd-mm-yyyy");
            $("#errore_data").css('color', 'red');
            $("#errore_data").html( "Formato data non corretto, formato corretto dd-mm-yyyy" );

        }
        else{
            $("#errore_data").html( "" );
        }
    }
//funzione per controllare se le password corrispondono. Se ok vado a settare anche la variabile pass, così non dà l'errore Jquery
    function passMatch() {
        var p1=$("#password").val();
        var p2=$("#password2").val();

        if(p1.localeCompare(p2)!=0){

        //if (p1!=p2) {
            $("#errore_pass").css('color', 'red');
            $("#errore_pass").html( "Le password non corrispondono" );
            //$("#errore_pass").html( p1.concat(p2) );

        }
        else{
            $("#errore_pass").html( "" );
            //se corrispondono setto la variabile pass a pass
            $("#pass").val("pass");
        }
    }


    <!-- Funzione per mostrare data di morte nel caso in cui il patrizio sia morto -->

    //mostrare/nascondere data_morte in base a vivente
    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'none';
        }
        else document.getElementById('ifYes').style.display = 'block';
    }
    //mostrare/nascondere figlio in base a ruolo
    function yesnoSon() {
        if (document.getElementById('relazione').value == "3") {
            document.getElementById('ifSon').style.display = 'none';
        } else {
            document.getElementById('ifSon').style.display = 'block';
        }
    }
    //no registro max 3 numeri
    $("#registroId").keyup(function () {
        $("#registroId").val(this.value.match(/[0-9]*/));
    });



    function beforeSubmit(){

        if((!$("#pass").val())||(!$("#nap").val())||(!$("#localita").val())||(!$("#padre").val())||(!$("#madre").val())) {
            if (!$("#pass").val()) {

                $("#errore_password").css('color', 'red');
                $("#errore_password").html("Errore: non è stata immessa nessuna password. Inserirla per poter procedere alla registrazione");


                //alert ($("#pass").val());
            }
            if (!$("#nap").val()) {

                $("#errore_nap").css('color', 'red');
                $("#errore_nap").html("Errore: non è stato immesso nessun nap. Inserirlo per poter procedere alla registrazione");

            }
            if (!$("#localita").val()) {
                $("#errore_localita").css('color', 'red');
                $("#errore_localita").html("Errore: non è stata immessa nessuna localit&agrave;. Inserirla per poter procedere alla registrazione");

            }
            if (!$("#padre").val()) {

                $("#errore_padre").css('color', 'red');
                $("#errore_padre").html("Errore: non sono stati immessi i dati del padre. Inserirli per poter procedere alla registrazione");

            }
            if (!$("#madre").val()) {

                $("#errore_madre").css('color', 'red');
                $("#errore_madre").html("Errore: non sono stati immessi i dati della madre. Inserirli per poter procedere alla registrazione");

            }
        }
        else {
            //alert ($("#pass").val());
            $("#errore_nap").html( "" );
            $("#errore_localita").html( "" );
            $("#errore_padre").html( "" );
            $("#errore_madre").html( "" );
            $("#errore_password").html( "" );
            $("#form_patrizi").submit();
        }
    }

    //Elimina il messagio di errore dalle caselle obbligatorie vuote se vengono riempite
    function cancErr(el){
        var inputID = $(el).attr("id");
        //alert (inputID);
        if ($("#inputID").val.length===0) {
            //alert ($("#password").val());
        }
        else{
            var str1 = "errore_";
            var err = str1.concat(inputID);
            var errs='#'.concat(err);
            $(errs).html( "" );
        }

    }




</script>


<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="utf-8">


    <title>Modifica dati catalogo elettorale</title>


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

                <li class="active"><a href="update.php">Modifica dati personali</a></li>


                <?php
                //se è stata immessa la password mostro il menu, altrimenti no
                if(isset($_SESSION['pass'])) {
                    //echo"<li><a href='iscrizione.php'>Iscrizione registro impianti risalita </a></li>";
                }
                ?>


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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $connection = Database::getConnection();
        //riprendo i valori necessari
        $id = $_SESSION['id'];


        // $no_registro = mysqli_real_escape_string($connection, $_POST['no_registro']);
        $cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
        $nome = mysqli_real_escape_string($connection, $_POST['nome']);
        $data_nascita = mysqli_real_escape_string($connection, $_POST['data_nascita']);

        $nas = explode('-', $data_nascita);

        $giorno = $nas[0];
        $mese = $nas[1];
        $anno = $nas[2];
        $data_nascita = $anno . '-' . $mese . '-' . $giorno;


        $imagetemp = $_FILES['foto']['tmp_name'];

        $imagename = $_FILES['foto']['name'];
        //Stores the filetype e.g image/jpeg
        $imagetype = $_FILES['foto']['type'];
        //Stores any error codes from the upload.
        $imageerror = $_FILES['foto']['error'];

        //The path you wish to upload the image to
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/Patriziato/update/images/" . $id . "/";

        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }


        function is_dir_empty($dir)
        {
            if (!is_readable($dir)) return NULL;
            return (count(scandir($dir)) == 2);

        }

        function deleteDir($dirPath)
        {
            if (!is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }

        function make_thumb($tipo, $src, $dest, $desired_width, $desired_h)
        {


            switch ($tipo) {
                case '2':
                    $source_image = imagecreatefromjpeg($src);
                    break;

                case '3':
                    $source_image = imagecreatefrompng($src);
                    break;

                case '1':
                    $source_image = imagecreatefromgif($src);
                    break;

                default:
                    throw new InvalidArgumentException('Il File "' . $src . '" non è un tipo di immagine valido. Sono permessi solo jpg, png o gif.');
                    break;

            }

            /* read the source image */
            //$source_image = imagecreatefromjpeg($src);
            $width = imagesx($source_image);
            $height = imagesy($source_image);

            $desired_height = $desired_h;

            /* create a new, "virtual" image */
            $virtual_image = imagecreatetruecolor($desired_width, $desired_height);


            imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

            /* create the physical thumbnail image to its destination */
            imagejpeg($virtual_image, $dest);
        }


        if ($_FILES['foto']['size'] == 0)
        {
            $foto= "";
        }
        else {

            //se c'è qualcosa nella directory la cancello e la ricreo
            if (!is_dir_empty($imagePath)) {
                deleteDir($imagePath);
                mkdir($imagePath, 0777, true);
            }


            if (is_uploaded_file($imagetemp)) {
                if (move_uploaded_file($imagetemp, $imagePath . $imagename)) {
                    //echo "Sussecfully uploaded your image.";
                    $imagedetails = getimagesize($imagePath . $imagename);
                    $width_orig = $imagedetails[0];
                    $height_orig = $imagedetails[1];
                    $type = $imagedetails[2];

                    $width = 100;
                    $height = $width / ($width_orig / $height_orig);
                    //print_r("aaa " . $type);
                    make_thumb($type, $imagePath . $imagename, $imagePath . "tmb_" . $imagename, $width, $height);
                } else {
                    echo "Problema nel caricamento dell'immagine. PF inviare una mail a patriziato.bosco@gmail.com";
                }
            } else {
                echo "Problema nel caricamento dell'immagine. PF inviare una mail a patriziato.bosco@gmail.com";
            }
            $foto = "images/" . $id . "/" . $imagename;
            //$foto=",foto='".$foto."'";
        }


        $telefono = mysqli_real_escape_string($connection, $_POST['telefono']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $via = mysqli_real_escape_string($connection, $_POST['via']);
        $nap = mysqli_real_escape_string($connection, $_POST['nap']);
        $localita = mysqli_real_escape_string($connection, $_POST['localita']);
        $padre = mysqli_real_escape_string($connection, $_POST['padre']);
        $madre = mysqli_real_escape_string($connection, $_POST['madre']);


        if(!isset($_POST['password']) || trim($_POST['password']) == '')
        {
            $password="";
        }
        else {
            $password = md5(mysqli_real_escape_string($connection, $_POST['password']));
            $password = "password='" . $password . "',";
        }

        //query di inserimento
        $sql = "UPDATE patrizio SET cognome='$cognome',nome='$nome',data_nascita='$data_nascita',telefono='$telefono',email='$email',via='$via',nap='$nap',localita='$localita',padre='$padre',$password madre='$madre',foto='$foto' WHERE id=$id";

        //echo($sql);
        $timestamp = date("Y-m-d H:i:s");
        $log="insert into log (id_patrizio,data_att) values ($id,'$timestamp')";
        if (!$connection->query($sql)) {
            //printf("Errormessage: %s\n", $connection->error);
            echo("<div class='alert alert-danger' id='alert_danger'>Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com.</div>");
        }
        //$sql2 = trim(str_replace("'","\'", $sql));

        else if (!$connection->query($log)) {
            //printf("Errormessage: %s\n", $connection->error);
            echo("<div class='alert alert-danger' id='alert_danger'>Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com.</div>");
        }
        else {
            echo('<div class="alert alert-success">Modifiche effettuate con successo.</div>');
        }
    }


    $id = $_SESSION['id'];
    //echo($id);


    //titolo
    echo('<h2>Modifica dati personali</h2>');
    echo('<h4>I campi contrassegnati con asterisco sono obbligatori</h4><br>');
    if ($result = $connection->query("SELECT *,
                                 IF((TIMESTAMPDIFF(YEAR,`data_nascita`,CURDATE())<18),'no','si') 
                                 as `diritto_di_voto`
                                 FROM patrizio WHERE id=$id and confermato=1")
    ) {
        //form
        echo('<form method="POST" enctype="multipart/form-data" action="update.php" class="form-vertical" id="form_patrizi">');
        while ($row = mysqli_fetch_array($result)) {

            //id nascosto
            //$id=$row['id'];
            echo('<input type="hidden" name="id1"  value=' . $row['id'] . '>');

            //nome
            echo('<label >Nome:</label>');
            //print_r($row['nome']);
            $nome=$row['nome'];
            echo("<input type='text' class='form-control' name='nome' value='$nome' readonly>");
            echo("<p></p>");

            echo('<label >Cognome:</label>');
            $cognome=$row['cognome'];
            echo("<input type='text' class='form-control' name='cognome' value='$cognome' readonly> ");
            echo("<p></p>");

            echo('<label >Data di nascita:</label>');


            $nascita = mysqli_real_escape_string($connection, $row['data_nascita']);
            $nas = explode('-', $nascita);
            $giorno = $nas[0];
            $mese = $nas[1];
            $anno = $nas[2];
            $nascita = $anno . '-' . $mese . '-' . $giorno;

            //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
            //echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_nascita'].'>');
            echo("<input type='text' name='data_nascita' id='data_nascita' onblur='isValidDate(this.value)' class='form-control' placeholder='gg.mm.aaaa'  value='$nascita' readonly>");

            echo('<label ></label>');


            echo('<div id="errore_data"></div>');
            echo("<p></p>");




            echo('<label >Telefono:</label>');

            $telefono=$row['telefono'];
            echo("<input type='text' class='form-control' name='telefono' placeholder='es. 079.332.22.22'  value='$telefono'>");

            echo("<p></p>");
            echo('<label >E-mail:</label>');

            $email=$row['email'];
            echo("<input type='text' class='form-control' name='email' value='$email'>");
            echo("<p></p>");

            echo('<label >*via:</label>');

            $via=$row['via'];
            echo("<input type='text' class='form-control' name='via' id='via'  value='$via'>");
            echo("<p></p>");

            echo('<label >*NAP:</label>');

            $nap=$row['nap'];
            echo("<input type='text' class='form-control' name='nap'  id='nap' value='$nap' onblur='cancErr(this)'>");
            echo('<div id="errore_nap"></div>');
            echo("<p></p>");

            echo('<label >*Localit&agrave;:</label>');

            $localita=$row['localita'];
            echo("<input type='text' class='form-control' name='localita' id='localita' value='$localita' onblur='cancErr(this)'>");
            echo('<div id="errore_localita"></div>');
            echo("<p></p>");


            echo('<label >*Cognome e nome del padre:</label>');

            $padre=$row['padre'];
            echo("<input type='text' class='form-control' name='padre' id='padre'  value='$padre' onblur='cancErr(this)'>");
            echo('<div id="errore_padre"></div>');
            echo("<p></p>");


            echo('<label >*Cognome e nome della madre:</label>');

            $madre=$row['madre'];
            echo("<input type='text' class='form-control' name='madre' id='madre'  value='$madre' onblur='cancErr(this)'>");
            echo('<div id="errore_madre"></div>');
            echo("<p></p>");

            echo('<label >*Password per futuri accessi:</label>');
            $pass=$row['password'];
            echo("<input type='hidden' class='form-control' name='pass' id='pass' value='$pass' >");

            echo("<input type='password' class='form-control' name='password' id='password' onblur='cancErr(this)'>");

            echo('<div id="errore_password"></div>');
            echo("<p></p>");
            echo('<label >*Ripeti password:</label>');

            echo("<input type='password' class='form-control' onblur='passMatch()' name='password2' id='password2'>");



            echo('<label ></label>');

            echo('<div id="errore_pass"></div>');


            echo("<p></p>");
            echo('<label >Foto:</label>');

            echo("<input type='file' class='form-control' name='foto' id='foto' >");


            echo("<p></p>");
            echo('<label ></label>');

            $foto=$row['foto'];
            echo("<img id='blah' name='blah' src='$foto' height='52' width='42'>");




        }

        //bottone modifica
        echo("<p></p>");
        echo("<input class='btn btn-primary' type='button' value='Modifica' name='butt' onclick='beforeSubmit();'></input>");
        echo('</form>');

    }


    ?>

</div> <!-- /container -->

</body>
</html>











