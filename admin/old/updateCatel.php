<?php 
session_start();
if(!$_SESSION['username']){
   header("location:index.php");
   die;
}
include '../database.php';
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

    <title>Gestione Catalogo Elettorale</title>

    <!-- Bootstrap core CSS -->
    <link href="css/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Bootstrap theme -->
    <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">
	
	<!-- Custom styles for this template -->
    <link href="css/theme.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="css/signin.css" rel="stylesheet">-->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="css/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="css/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container">

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
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CatE<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="catel.php">Patrizi</a></li>
                  <li><a href="catel2.php">Relazioni Patrizi</a></li>
                  <!-- <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>-->
                </ul>
              </li>
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

    <!-- Funzione per mostrare data di morte nel caso in cui il patrizio sia morto -->   
    <script type="text/javascript">
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
        }else{
            document.getElementById('ifSon').style.display = 'block';
        } 
    }
    //no registro max 3 numeri
    $("#registroId").keyup(function() {
        $("#registroId").val(this.value.match(/[0-9]*/));
    });
    </script>

	<?php
    //se è settato il tipo di modifica lo registro in un cookie
	if(isset($_GET['mod'])){
		$mod=$_GET['mod'];
		$mod=$_COOKIE['mod'];
	}
    //se sto inserendo un dato (ho schiacciato il bottone crea patrizio)
	if(isset($_POST['ins'])){
		$no_registro = mysqli_real_escape_string($connection,$_POST['no_registro']);
		$cognome = mysqli_real_escape_string($connection,$_POST['cognome']);
		$nome = mysqli_real_escape_string($connection,$_POST['nome']);
        $data_nascita = mysqli_real_escape_string($connection,$_POST['data_nascita']);
        $data_inserimento = mysqli_real_escape_string($connection,$_POST['data_inserimento']);
		$vivente = mysqli_real_escape_string($connection,$_POST['vivente']);
        $data_morte = mysqli_real_escape_string($connection,$_POST['data_morte']);
        $diritto_di_voto = mysqli_real_escape_string($connection,$_POST['diritto_di_voto']);
        $data_perdita_patrizio = mysqli_real_escape_string($connection,$_POST['data_perdita_patrizio']);
        //query di inserimento
        $sql = "INSERT INTO patrizio
                (no_registro, cognome, nome, data_nascita, vivente, data_inserimento, data_morte, 
                data_perdita_patrizio)
				VALUES('$no_registro','$cognome','$nome','$data_nascita','$vivente',
                ".($data_inserimento==NULL ? "NULL" : "'$data_inserimento'").",
                ".($data_morte==NULL ? "NULL" : "'$data_morte'").",
                ".($data_perdita_patrizio==NULL ? "NULL" : "'$data_perdita_patrizio'").")";
        //controllo query
		if (mysqli_query($connection, $sql)) {
			header('Location: catel.php');
		}else {
			echo "Errore: " . $sql . "<br>" . mysqli_error($_connection);
		}
	}
    //se ho cliccato  il tasto esegui (update o delete)
    if(isset($_POST['butt'])){
		$connection=Database::getConnection();
		//riprendo i valori necessari
	 	$id=$_COOKIE['id'];
		$mod=$_COOKIE['mod'];

        $no_registro = mysqli_real_escape_string($connection,$_POST['no_registro']);
		$cognome = mysqli_real_escape_string($connection,$_POST['cognome']);
		$nome = mysqli_real_escape_string($connection,$_POST['nome']);
        $data_nascita = mysqli_real_escape_string($connection,$_POST['data_nascita']);
        $data_inserimento = mysqli_real_escape_string($connection,$_POST['data_inserimento']);
		$vivente = mysqli_real_escape_string($connection,$_POST['vivente']);
        $data_morte = mysqli_real_escape_string($connection,$_POST['data_morte']);
        $diritto_di_voto = mysqli_real_escape_string($connection,$_POST['diritto_di_voto']);
        $data_perdita_patrizio = mysqli_real_escape_string($connection,$_POST['data_perdita_patrizio']);
        //riprendo id padre
        $id1=$_POST['id1'];
        //riprendo id figlio
        $id2=$_POST['id2'];
        //riprendo relazione patrizi
        $relazione=$_POST['idRelazione'];
		
		//se è un'eliminazione
		if($mod=='del'){
			$sql="DELETE FROM patrizio 
				  WHERE id=$id";
			$result = $connection->query($sql);
			header('Location: catel.php');
		}
		
		//se è una modifica
		if($mod=='update'){
            //query di inserimento
            $sql = "UPDATE patrizio
                    SET no_registro=$no_registro,
                    cognome='$cognome',
                    nome='$nome',
                    data_nascita='$data_nascita',
                    vivente='$vivente',
                    ".('$data_inserimento'==NULL ? "NULL" : "data_inserimento='$data_inserimento'").",
                    ".('$data_morte'==NULL ? "NULL" : "data_morte='$data_morte'").",
                    diritto_di_voto='$diritto_di_voto',
                    ".('$data_perdita_patrizio'==NULL ? "NULL" : "data_perdita_patrizio='$data_perdita_patrizio'")."
                    WHERE id=$id";
            $result = $connection->query($sql);
            //inserimento della relazione tra i patrizi
            $sql2="INSERT INTO `patrizi`(`id_patrizio1`,`id_patrizio2`,`id_relazione`) 
                   VALUES ($id1,$id2,$relazione)";
            $result2 = $connection->query($sql2);
            header('Location: catel.php');
		}
	}
	
	
	//solo se inserisco mostro la tabella di inserimento
	else if(isset($_GET['mod'])&& $_GET['mod']=='ins'){
		
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");

        //titolo
        echo('<h2>Inserisci nuovo patrizio</h2><br>');
        //form
        echo('<form method="POST" action="updateCatel.php" class="form-horizontal">');
            //no registro
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">No registro:</label>');
                echo('<div class="col-sm-10">');
                //echo('<input id="registroId" type="text" class="form-control" maxlength="3" pattern="([0-9]|[0-9]|[0-9])" name="no_registro" placeholder="Inserisci numero registro">');
                echo('<input type="text" class="form-control" name="no_registro" placeholder="Inserisci numero registro">');
                echo('</div>');
            echo('</div>');
            //cognome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Cognome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="cognome" placeholder="Inserisci cognome" required>');
                echo('</div>');
            echo('</div>');
            //nome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Nome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="nome" placeholder="Inserisci nome" required>');
                echo('</div>');
            echo('</div>');
            //data nascita - Attenzione in firefox non funziona type=date
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di nascita:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" placeholder="Inscerisci data di nascita">');
                echo('</div>');
            echo('</div>');
            //data inserimento - Attenzione in firefox non funziona type=date
            /*
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data d\'inserimento:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_inserimento" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" placeholder="Inscerisci data di acquisizione patriziato">');
                echo('</div>');
            echo('</div>');
            */
            //vivente si/no
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Vivente:</label>');
                echo('<div class="col-sm-10">');
                echo('<label class="radio-inline">');
                    echo('<input type="radio" onclick="javascript:yesnoCheck();" id="yesCheck" name="vivente" value="si" checked>si');
                echo('</label>');
                echo('<label class="radio-inline">');
                    echo('<input type="radio" onclick="javascript:yesnoCheck();" id="noCheck" name="vivente" value="no">no');
                echo('</label>');
                echo('</div>');
            echo('</div>'); 
            //se non più vivente, visualizzo input data morte - Attenzione in firefox non funziona type=date
            echo('<div id="ifYes" style="display:none" class="form-group">');
                echo('<label class="control-label col-sm-2">Data di morte:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_morte" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" placeholder="Inscerisci data di morte">');
                echo('</div>');
            echo('</div>');
            //data perdita patrizio - Attenzione in firefox non funziona type=date
            /*
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di perdita patrizio:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_perdita_patrizio" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" placeholder="Inscerisci data di nascita">');
                echo('</div>');
            echo('</div>');
            */
            //bottone "crea"
            echo('<div class="form-group">');
                echo('<div class="col-sm-offset-2 col-sm-10">');
                echo('<button name="ins" type="submit" class="btn btn-primary">Crea</button>');
                echo('</div>');
            echo('</div>');
        echo('</form>');
	}
	//mostro la tabella di modifica se si tratta di update 
	else if(isset($_GET['mod'])&& $_GET['mod']=='update')
	{
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
		
        //titolo
        echo('<h2>Modifica patrizio</h2><br>');
        if($result=$connection->query("SELECT *,
                                 IF((TIMESTAMPDIFF(YEAR,`data_nascita`,CURDATE())<18),'no','si') 
                                 as `diritto_di_voto`
                                 FROM patrizio WHERE id=$id")){
        //form
        echo('<form method="POST" action="updateCatel.php" class="form-horizontal">');
        while($row=mysqli_fetch_array($result)){
            //no registro
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">No registro:</label>');
                echo('<div class="col-sm-10">');
                //echo('<input id="registroId" type="text" class="form-control" maxlength="3" pattern="([0-9]|[0-9]|[0-9])" name="no_registro" placeholder="Inserisci numero registro">');
                echo('<input type="text" class="form-control" name="no_registro" value='.$row['no_registro'].'>');
                echo('</div>');
            echo('</div>');
            //id nascosto
            //$id=$row['id'];
            echo('<input type="hidden" name="id1"  value='.$row['id'].'>'); 
            //cognome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Cognome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="cognome" value='.$row['cognome'].'>');
                echo('</div>');
            echo('</div>');
            //nome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Nome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="nome" value='.$row['nome'].'>');
                echo('</div>');
            echo('</div>');
            //data nascita - Attenzione in firefox non funziona type=date
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di nascita:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_nascita'].'>');
                echo('</div>');
            echo('</div>');
            //ruolo padre/madre
            if($result=$connection->query("SELECT * FROM `relazione` ORDER BY `relazione`.`id` DESC")){
            echo('<div class="form-group">');
            echo('<label style="cursor:help" title="Selezionare (-) se non ha figli" class="control-label col-sm-2">Ruolo:</label>');
             echo('<div class="col-sm-10">');
                echo('<select name="idRelazione" value='.$_SESSION['idRelazione'].' id="relazione" class="form-control" onchange="yesnoSon()">');
                while($row=mysqli_fetch_array($result)){
                    $_SESSION['idRelazione']=$row['id'];
                    echo('<option value='.$_SESSION['idRelazione'].'>'.$row['tipo'].'</option>');
                }
                echo('</select>');
               echo('</div>');
            echo('</div>');
            }
            //figlio/a
            if($result=$connection->query("SELECT id, cognome, nome FROM patrizio")){
            echo('<div id="ifSon" style="display:none" class="form-group">');
            echo('<label class="control-label col-sm-2">Figlio/a:</label>');
             echo('<div class="col-sm-10">');
                echo('<select name="id2" value='.$_SESSION['id2'].' class="form-control">');
                while($row=mysqli_fetch_array($result)){
                    //memorizzo id figlio
                    $_SESSION['id2']=$row['id'];
                    echo('<option value='.$_SESSION['id2'].'>'.$row['cognome'].' '.$row['nome'].'</option>');
                }
                echo('</select>');
               echo('</div>');
            echo('</div>');
            }
            /*data inserimento - Attenzione in firefox non funziona type=date
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data d\'inserimento:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_inserimento" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_inserimento'].'>');
                echo('</div>');
            echo('</div>');
            */
            /*vivente si/no
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Vivente:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="vivente" value='.$row['vivente'].' disabled>');
                echo('</div>');
            echo('</div>');
            */
            //se non più vivente, visualizzo input data morte - Attenzione in firefox non funziona type=date
            echo('<div id="ifYes" style="display:none" class="form-group">');
                echo('<label class="control-label col-sm-2">Data di morte:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_morte" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_morte'].'>');
                echo('</div>');
            echo('</div>');
            /*diritto di voto si/no
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Diritto di voto:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="diritto_di_voto" value='.$row['diritto_di_voto'].' disabled>');
                echo('</div>');
            echo('</div>');
            */
            /*data perdita patrizio - Attenzione in firefox non funziona type=date
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di perdita patrizio:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_perdita_patrizio" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_perdita_patrizio'].'>');
                echo('</div>');
            echo('</div>');
            */
            //bottone modifica
            echo('<div class="form-group">');
                echo('<div class="col-sm-offset-2 col-sm-10">');
                echo('<input class="btn btn-warning" type="submit" value="Modifica" name="butt"></input>');
                echo('</div>');
            echo('</div>');
            }
        echo('</form>');
        }
	}
	//mostro la tabella di eliminazione se si tratta di delete 
	else if(isset($_GET['mod'])&& $_GET['mod']=='del')
	{
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
		
        //titolo
        echo('<h2>Elimina patrizio</h2><br>');
        if($result=$connection->query("SELECT *,
                                 IF((TIMESTAMPDIFF(YEAR,`data_nascita`,CURDATE())<18),'no','si') 
                                 as `diritto_di_voto`
                                 FROM patrizio WHERE id=$id")){
        //form
        echo('<form method="POST" action="updateCatel.php" class="form-horizontal">');
        while($row=mysqli_fetch_array($result)){
            $id=$row['id'];
            //no registro
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">No registro:</label>');
                echo('<div class="col-sm-10">');
                //echo('<input id="registroId" type="text" class="form-control" maxlength="3" pattern="([0-9]|[0-9]|[0-9])" name="no_registro" placeholder="Inserisci numero registro">');
                echo('<input type="text" class="form-control" name="no_registro" value='.$row['no_registro'].' readonly>');
                echo('</div>');
            echo('</div>');
            //cognome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Cognome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="cognome" value='.$row['cognome'].' readonly>');
                echo('</div>');
            echo('</div>');
            //nome
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Nome:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="nome" value='.$row['nome'].' readonly>');
                echo('</div>');
            echo('</div>');
            //data nascita - Attenzione in firefox non funziona type=date
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di nascita:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_nascita" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_nascita'].' readonly>');
                echo('</div>');
            echo('</div>');
            //data inserimento - Attenzione in firefox non funziona type=date
            /*
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data d\'inserimento:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_inserimento" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_inserimento'].' readonly>');
                echo('</div>');
            echo('</div>');
            */
            //vivente si/no
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Vivente:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="vivente" value='.$row['vivente'].' readonly>');
                echo('</div>');
            echo('</div>');
            //se non più vivente, visualizzo input data morte - Attenzione in firefox non funziona type=date
            echo('<div id="ifYes" style="display:none" class="form-group">');
                echo('<label class="control-label col-sm-2">Data di morte:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_morte" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_morte'].' readonly>');
                echo('</div>');
            echo('</div>');
            //diritto di voto si/no
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Diritto di voto:</label>');
                echo('<div class="col-sm-10">');
                echo('<input type="text" class="form-control" name="diritto_di_voto" value='.$row['diritto_di_voto'].' readonly>');
                echo('</div>');
            echo('</div>');
            //data perdita patrizio - Attenzione in firefox non funziona type=date
            /*
            echo('<div class="form-group">');
                echo('<label class="control-label col-sm-2">Data di perdita patrizio:</label>');
                echo('<div class="col-sm-10">');
                //pattern data dd.mm.yyyy --> (0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}
                echo('<input type="date" class="form-control" name="data_perdita_patrizio" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" value='.$row['data_perdita_patrizio'].' readonly>');
                echo('</div>');
            echo('</div>');
            */
            //bottone "crea"
            echo('<div class="form-group">');
                echo('<div class="col-sm-offset-2 col-sm-10">');
                echo('<input class="btn btn-danger" type="submit" value="Elimina" name="butt"></input>');
                echo('</div>');
            echo('</div>');
        echo('</form>');
            }
        }
	}
	?>

	</div> <!-- /container -->

	<?php //include 'footer.php';?> 
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="css/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="css/dist/js/bootstrap.min.js"></script>
	<!-- <script src="css/assets/js/docs.min.js"></script> -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="css/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>












