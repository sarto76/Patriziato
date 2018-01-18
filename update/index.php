<?php
session_start();
include '../database.php';
$connection = Database::getConnection();

include_once '../admin/Counter.php';
$c=new Counter();
$c->write("../admin/update.txt");

if (isset($_POST['login'])) {
    //echo("isset");
    //print_r($_POST);
    $nome = "%{$_POST['nome']}%";
    $cognome = "%{$_POST['cognome']}%";
    $nascita = mysqli_real_escape_string($connection, $_POST['nascita']);
    $password="";


    //$param = "%{$_POST['user']}%";
    //echo($nome." ".$cognome." ".$nascita);

    $nascita = str_replace(".", "-", $nascita);
    $nascita = str_replace(' ', '', $nascita);


    $nas = explode('-', $nascita);
    if (count($nas) == 3) {
        if (checkdate($nas[1], $nas[0], $nas[2])) {
            $giorno = $nas[0];
            $mese = $nas[1];
            $anno = $nas[2];
            $nascita = $anno . '-' . $mese . '-' . $giorno;




            //if($_POST['password']!=""){
            if($_POST['passVisible']==1){

                    $password = md5(mysqli_real_escape_string($connection, $_POST['password']));
                    $sql = 'select id from patrizio where nome like ? AND cognome like ? AND data_nascita=? AND password=? and confermato=1';

                    $result = $connection->prepare($sql);
                    $result->bind_param('ssss', $nome, $cognome, $nascita, $password);
                    //print_r("con pass ".$password);
                    $_SESSION["pass"] = $nome.$cognome.$nascita;


            }
            else{
                $sql = 'select id from patrizio where nome like ? AND cognome like ? AND data_nascita=? and confermato=1';
                $result = $connection->prepare($sql);
                $result->bind_param('sss', $nome, $cognome, $nascita);
                //print_r("senza pass ".$_POST['password']);
            }


            $result->execute();
           // printf("Error: %s.\n", $result->error);
            $result->bind_result($id);

            while ($result->fetch()){
                $cod=$id;

            }
            $result->close();
            if (!empty($cod))//se login avviene con successo
            {


                $_SESSION["id"] = $id;
                //print_r($_SESSION);
                header('Location: update.php');
            } else {
                //Altrimenti stampo un errore
                echo('<div class="alert alert-danger">Dati di accesso non corretti</div>');
            }
        } else {
            echo('<div class="alert alert-danger">Data di nascita non corretta (formato gg-mm-aaaa)</div>');

        }
    }
    else {
        echo('<div class="alert alert-danger">Errore nella data di nascita (formato gg-mm-aaaa)</div>');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    

    <title>login</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap theme -->
    <link href="../css/dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="css/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../css/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


    <script type="text/javascript">

        function isValidDate(date) {

            //var matches = /^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/.exec(date);
            var matches = /^(\d{1,2})[-\.](\d{1,2})[-\.](\d{4})$/.exec(date);
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
                $('#login').hide();

            }
            else{
                $("#errore_data").html( "" );
                $('#login').show();
            }
        }


        $(document).ready(function() {
            $('#nascita').val('');


            $('#entra').on('keyup keypress', function(e) {

                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    //alert('keyup 13');
                    e.preventDefault();
                    return false;
                }
            });

        });

        // questa funzione viene richiamata per decidere se mostrare il campo per la password
        function showPass() {
            isValidDate($('#nascita').val());
            $('#password').val('');

            //eseguo la richiesta ajax al server visualizza.php con dati json, passando le eventuali opzioni (lettere immesse)
            $.ajax({
                type: "POST",
                url: "visualizza.php",
                dataType: 'json',
                cache: false,
                data: {nome: $('#nome').val(), cognome: $('#cognome').val(), data_nascita: $('#nascita').val()},
                //se ha successo
                success: function (records) {


                    //se ritorna qualcosa
                    if (records.length != 0) {
                        $("#errore").html( "" );

                        //$("#login").val("Modifica");
                        if (records[0].password != null) {
                            //console.log("NON NULLA " + records[0].password);
                            $('#password').show();
                            $('#label_pass').show();
                            $('#passVisible').val("1");

                        }
                        else {
                            //console.log("NULLA " + records[0].password);
                            $('#password').hide();
                            $('#label_pass').hide();
                            $('#passVisible').val("0");
                        }

                    }
                    else {

                        $("#errore").css('color', 'red');
                        $("#errore").html( "Dati di accesso non corretti" );

                        $('#login').hide();
                        $('#password').hide();
                        $('#label_pass').hide();
                        $('#passVisible').val("0");

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);

                    //alert(JSON.stringify(records, null, 4));
                }

            });
        }


    </script>


</head>

<body>

<div class="container">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-signin" autocomplete="off" id="entra">
        <h2 class="form-signin-heading">Patriziato Bosco Gurin - Gestione dati personali</h2>

        <br>
        <div class="container">
            <h3 class="form-signin-heading">Login</h3>
            <br>
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" class="form-control" placeholder="es. Luigi" required autofocus autocomplete="off">
        <p></p>
        <label for="cognome">Cognome</label>
        <input type="text" name="cognome" id="cognome" class="form-control" placeholder="es. Bernasconi" required autofocus autocomplete="off">
        <p></p>
        <label for="nascita">Data di Nascita (gg-mm-aaaa)</label>
        <input type="text" name="nascita" id="nascita" class="form-control" placeholder="es. 25-11-2000" required autofocus autocomplete="off">
        <div id="errore_data"></div>
        <input type="text" style="visibility: hidden">
        <p></p>
        <a onclick="showPass()" id="contr"><img src="controlla.png" alt="Controllo dati" width="50" height="50" id="controllo" ></a>
        <p></p>
        <label for="password"  id="label_pass" style="display: none;" value="">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" style="display: none;" autocomplete="off">
        <p></p>
        <input type="hidden" name="passVisible" id="passVisible" value="0">
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login" id="login" style="display: none;" >Accedi</button>
        <div id="errore"></div>
        <div class="alert alert-danger" id="errore" style="display: none;"></div>
        </div>

    </form>
    <p></p>




    <div class="container well">
        <b>Non riesci ad accedere o vuoi iscrivere un nuovo patrizio? <a href="addPatrizio.php">Clicca qui</a> per permettere la verifica dei tuoi dati.</b>
    </div>

</div> <!-- /container -->

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>




</body>
</html>
