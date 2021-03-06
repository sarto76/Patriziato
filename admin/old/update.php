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
        if (matches == null) return false;
        var m = matches[2]-1;
        var d = matches[1];
        var y = matches[3];
        //alert(m);
        var composedDate = new Date(y, m, d);
        if (!((composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y))) {
            //alert("Formato data non corretto, formato corretto dd-mm-yyyy");
            $(err).css('color', 'red');
            $(err).html( "Formato data non corretto, formato corretto dd-mm-yyyy" );

        }
        else{
            $(err).html( "" );
        }
    }

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
            $foto = "../update/images/" . $id . "/" . $imagename;
            //$foto=",foto='".$foto."'";
        }

        $telefono = mysqli_real_escape_string($connection, $_POST['telefono']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $via = mysqli_real_escape_string($connection, $_POST['via']);
        $nap = mysqli_real_escape_string($connection, $_POST['nap']);
        if ($nap=='')
            $nap='null';


        $localita = mysqli_real_escape_string($connection, $_POST['localita']);
        $padre = mysqli_real_escape_string($connection, $_POST['padre']);
        $madre = mysqli_real_escape_string($connection, $_POST['madre']);
        $no_registro=mysqli_real_escape_string($connection, $_POST['no_registro']);
        $vivente=mysqli_real_escape_string($connection, $_POST['vivente']);

        $data_inserimento=mysqli_real_escape_string($connection, $_POST['data_inserimento']);
        $nas = explode('.', $data_inserimento);
        $data_inserimento = $nas[2] . '-' . $nas[1] . '-' . $nas[0];


        $data_morte=mysqli_real_escape_string($connection, $_POST['data_morte']);
        $nas = explode('.', $data_morte);
        $data_morte = $nas[2] . '-' . $nas[1] . '-' . $nas[0];


        $diritto_di_voto=mysqli_real_escape_string($connection, $_POST['diritto_di_voto']);
        $data_perdita_patrizio=mysqli_real_escape_string($connection, $_POST['data_perdita_patrizio']);
        $nas = explode('.', $data_perdita_patrizio);
        $data_perdita_patrizio = $nas[2] . '-' . $nas[1] . '-' . $nas[0];



        if(!isset($_POST['password']) || trim($_POST['password']) == '')
        {
            $password="";
        }
        else {
            $password = md5(mysqli_real_escape_string($connection, $_POST['password']));
            $password = "password='" . $password . "',";
        }

        //query di inserimento
        $sql = "UPDATE patrizio SET no_registro='$no_registro',cognome='$cognome',nome='$nome',data_nascita='$data_nascita',
        vivente='$vivente',data_inserimento=if(STRCMP('$data_inserimento','--'),'$data_inserimento',NULL),
        data_morte=if(STRCMP('$data_morte','--'),'$data_morte',NULL),diritto_di_voto='$diritto_di_voto',
        data_perdita_patrizio=if(STRCMP('$data_perdita_patrizio','--'),'$data_perdita_patrizio',NULL),
        telefono='$telefono',email='$email',via='$via',
        nap=$nap,localita='$localita',padre='$padre',$password madre='$madre' $foto WHERE id=$id";

        //echo($sql);

        if (!$connection->query($sql)) {
            //printf("Errormessage: %s\n", $connection->error);
            echo "Problema di connessione. PF inviare una mail a patriziato.bosco@gmail.com";
        }
        $timestamp = date("Y-m-d H:i:s");
        $log="insert into log (id_patrizio,data_att) values ($id,'$timestamp')";
        if (!$connection->query($log)) {

            printf("Errormessage: %s\n", $connection->error);
            //echo "Problema di connessione log. PF inviare una mail a patriziato.bosco@gmail.com";
        }

        echo('<div class="alert alert-success">Modifiche effettuate con successo.</div>');
    }


    $id = $_SESSION['id'];
    //echo($id);

    ?>
    <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="catel.php">Gestione CatE</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <li><a href="catel.php">Catalogo elettorale</a></li>
              <li><a href="news.php">News</a></li>
              <li><a href="info.php">Info</a></li>
              <li><a href="docs.php">Docs</a></li>
              <li><a href="link.php">Link</a></li>
              <li><a href="prop.php">Prop</a></li>
              <li><a href="stat.php">Stat</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo " ".$_SESSION["username"]?></a></li>
    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
</div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>
    <?php

    //titolo
    echo('<h2>Modifica dati personali</h2><br>');
    if ($result = $connection->query("SELECT *FROM patrizio WHERE id=$id"))
    {
        //form
        echo('<form method="POST" enctype="multipart/form-data" action="update.php" class="form-vertical">');
        while ($row = mysqli_fetch_array($result)) {

            //id nascosto
            //$id=$row['id'];
            echo('<input type="hidden" name="id1"  value=' . $row['id'] . '>');


            echo('<label >Numero registro:</label>');
            //print_r($row['nome']);
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


            $nascita = mysqli_real_escape_string($connection, $row['data_nascita']);
            if($nascita!="") {
                $nas = explode('-', $nascita);
                $giorno = $nas[0];
                $mese = $nas[1];
                $anno = $nas[2];
                $nascita = $anno . '-' . $mese . '-' . $giorno;
            }

            //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
            //echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_nascita'].'>');
            echo("<input type='text' name='data_nascita' id='data_nascita' onblur='isValidDate(this.value,\"#errore_data\")' class='form-control' placeholder='gg.mm.aaaa'  value='$nascita'>");

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


            echo('<label >Data inserimento:</label>');
            //print_r($row['nome']);
            $data_inserimento=$row['data_inserimento'];
            if($data_inserimento!="") {
                $nas = explode('-', $data_inserimento);
                $data_inserimento = $nas[2] . '-' . $nas[1] . '-' . $nas[0];
            }


            echo("<input type='text' class='form-control' name='data_inserimento' id='data_inserimento' onblur='isValidDate(this.value,\"#errore_datains\")' placeholder='gg.mm.aaaa' value='$data_inserimento' >");
            echo('<label ></label>');


            echo('<div id="errore_datains"></div>');
            echo("<p></p>");

            echo('<label >vivente:</label>');
            //print_r($row['nome']);
            $vivente=$row['vivente'];

            ?>
            <input type="radio" name="vivente" <?php if( $vivente == "1") { echo "checked"; }?>  value="1">sì
            <input type="radio" name="vivente" <?php if( $vivente == "0") { echo "checked"; }?> value="0">no

            <?php

            echo("<p></p>");

            echo('<label >Data morte:</label>');
            //print_r($row['nome']);
            $data_morte=$row['data_morte'];
            if($data_morte!="") {
                $nas = explode('-', $data_morte);
                $data_morte = $nas[2] . '-' . $nas[1] . '-' . $nas[0];
            }
            echo("<input type='text' class='form-control' name='data_morte' id='data_morte' onblur='isValidDate(this.value,\"#errore_datamor\")' placeholder='gg.mm.aaaa' value='$data_morte' >");
            echo('<label ></label>');


            echo('<div id="errore_datamor"></div>');
            echo("<p></p>");

            echo('<label >Diritto di voto:</label>');
            //print_r($row['nome']);
            $diritto_di_voto=$row['diritto_di_voto'];
            //echo("<input type='text' class='form-control' name='nome' value='$diritto_di_voto' >");

            ?>
            <input type="radio" name="diritto_di_voto" <?php if( $diritto_di_voto == "1") { echo "checked"; }?>  value="1">sì
            <input type="radio" name="diritto_di_voto" <?php if( $diritto_di_voto == "0") { echo "checked"; }?> value="0">no

            <?php
            //echo("<input type='text' class='form-control' name='diritto_di_voto' value='$diritto_di_voto' >");



            echo("<p></p>");

            echo('<label >Data perdita patrizio:</label>');
            //print_r($row['nome']);
            $data_perdita_patrizio=$row['data_perdita_patrizio'];
            if($data_perdita_patrizio!="") {
                $nas = explode('-', $data_perdita_patrizio);
                $data_perdita_patrizio = $nas[2] . '-' . $nas[1] . '-' . $nas[0];
            }
            echo("<input type='text' class='form-control' name='data_perdita_patrizio' id='data_perdita_patrizio' onblur='isValidDate(this.value,\"#errore_dataper\")' placeholder='gg.mm.aaaa' value='$data_perdita_patrizio' >");
            echo('<label ></label>');


            echo('<div id="errore_dataper"></div>');
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


            echo('<label >Foto:</label>');

            echo("<input type='file' class='form-control' name='foto' id='foto' >");


            echo("<p></p>");
            echo('<label ></label>');

            $foto=$row['foto'];
            echo("<img id='blah' name='blah' src='$foto' height='52' width='42'>");


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











