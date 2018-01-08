<?php
session_start();
include '../database.php';
$connection = Database::getConnection();



if (isset($_POST['login'])) {
    $nome = "%{$_POST['nome']}%";
    $cognome = "%{$_POST['cognome']}%";
    $nascita = mysqli_real_escape_string($connection, $_POST['nascita']);
    $password="";


    //$param = "%{$_POST['user']}%";
    $nas = explode('-', $nascita);
    if (count($nas) == 3) {
        if (checkdate($nas[1], $nas[0], $nas[2])) {
            $giorno = $nas[0];
            $mese = $nas[1];
            $anno = $nas[2];
            $nascita = $anno . '-' . $mese . '-' . $giorno;




            if($_POST['password']!=""){

                    $password = md5(mysqli_real_escape_string($connection, $_POST['password']));
                    $sql = 'select id from patrizio where nome like ? AND cognome like ? AND data_nascita=? AND password=?';

                    $result = $connection->prepare($sql);
                    $result->bind_param('ssss', $nome, $cognome, $nascita, $password);
                    //print_r("con pass ".$password);


            }
            else{
                $sql = 'select id from patrizio where nome like ? AND cognome like ? AND data_nascita=?';
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
                echo('<h3 style="text-align: center;color:red;">Dati di accesso non corretti</h3>');
            }
        } else {
            echo('<h3 style="text-align: center;color:red;">Data di nascita non corretta (formato gg-mm-aaaa)</h3>');

        }
    }
    else {
        echo('<h3 style="text-align: center;color:red;">Errore nella data di nascita (formato gg-mm-aaaa)</h3>');
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
    <link rel="icon" href="favicon.ico">

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
                $('#login').hide();

            }
            else{
                $("#errore_data").html( "" );
                $('#login').show();
            }
        }


        $(document).ready(function() {
            $('#nascita').val('');

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
                            console.log("NON NULLA " + records[0].password);
                            $('#password').show();
                            $('#label_pass').show();

                        }
                        else {
                            console.log("NULLA " + records[0].password);
                            $('#password').hide();
                            $('#label_pass').hide();
                        }

                    }
                    else {

                        $("#errore").css('color', 'red');
                        $("#errore").html( "Dati di accesso non corretti" );

                        $('#login').hide();
                        $('#password').hide();
                        $('#label_pass').hide();

                    }
                }

                //alert(JSON.stringify(records, null, 4));

            });
        }


    </script>


</head>

<body>

<div class="container">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-signin" autocomplete="off">
        <h2 class="form-signin-heading">Patriziato Bosco Gurin - Modifica dati personali</h2>
        <label for="nome" class="sr-only">Nome</label>
        <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" required autofocus autocomplete="off">
        <p></p>
        <label for="cognome" class="sr-only">Cognome</label>
        <input type="text" name="cognome" id="cognome" class="form-control" placeholder="Cognome" required autofocus autocomplete="off">
        <p></p>
        <label for="nascita" class="sr-only">Data di Nascita (gg-mm-aaaa)</label>
        <input type="text" name="nascita" id="nascita" class="form-control" placeholder="Data di nascita (gg-mm-aaaa)" autocomplete="off">
        <div id="errore_data"></div>
        <p></p>
        <a onclick="showPass()" id="contr"><img src="controlla.png" alt="Controllo dati" width="50" height="50" id="controllo" ></a>
        <p></p>
        <label for="password"  id="label_pass" style="display: none;" value="">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" style="display: none;" autocomplete="off">
        <p></p>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login" id="login" style="display: none;" >Modifica</button>
        <div id="errore"></div>
    </form>
</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>




</body>
</html>
