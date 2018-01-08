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

    <title>Gestione Link</title>

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
            <a class="navbar-brand" href="link.php">Gestione Link</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
							<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CatE<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="catel.php">Patrizi</a></li>
                  <li><a href="catel2.php">Relazioni Patrizi</a></li>
                </ul>
              </li>
              <li><a href="news.php">News</a></li>
              <li><a href="info.php">Info</a></li>
              <li><a href="docs.php">Docs</a></li>
              <li class="active"><a href="link.php">Link</a></li>
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
    //se è settato il tipo di modifica lo registro in un cookie
	if(isset($_GET['mod'])){
		$mod=$_GET['mod'];
		$mod=$_COOKIE['mod'];
	}
    //se sto inserendo un dato (ho schiacciato il bottone inserisci )
	if(isset($_POST['ins'])){
		$nome = mysqli_real_escape_string($connection,$_POST['nome']);
		$url = mysqli_real_escape_string($connection,$_POST['url']);
		$sql = "INSERT INTO link(nome, url) VALUES('$nome','$url')";
		if (mysqli_query($connection, $sql)) {
			//$message = "Link caricato con successo";
			//echo "<script type='text/javascript'>alert('$message');</script>";
			//ritorno alla pagina di base
			header('Location: link.php');
		}else {
			echo "Errore: " . $sql . "<br>" . mysqli_error($_connection);
		}
	}
    //se ho cliccato  il tasto esegui (update o delete)
    if(isset($_POST['butt'])){
		$connection=Database::getConnection();
		//riprendo i valori necessari
	 	$id=$_COOKIE['id'];
		//$nome=$_POST['nome'];
		$nome = mysqli_real_escape_string($connection,$_POST['nome']);
		$url=$_POST['url'];
		$mod=$_COOKIE['mod'];
		
		//se è un'eliminazione
		if($mod=='del'){
			$sql="DELETE FROM Link WHERE id=$id";
			$result = $connection->query($sql);
			header('Location: link.php');
		}
		
		//se è una modifica
		if($mod=='update'){
			$sql="UPDATE link SET nome='$nome', url='$url' WHERE id=$id";
			$result = $connection->query($sql);
			header('Location: link.php');
		}
	}
	
	
	//solo se inserisco mostro la tabella di inserimento
	else if(isset($_GET['mod'])&& $_GET['mod']=='ins'){
		
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		
		//titolo
    echo('<h2>Inserisci link</h2><br>');
		//form
		echo('<form method="POST" action="updateLink.php" class="form-horizontal">');
		//nome
		echo('<div class="form-group">');
				echo('<label class="control-label col-sm-2">Nome:</label>');
				echo('<div class="col-sm-10">');
				echo('<textarea required class="form-control" name="nome" placeholder="Inserisci nome"></textarea>');
				echo('</div>');
		echo('</div>');
		//url
		echo('<div class="form-group">');
				echo('<label class="control-label col-sm-2">Url:</label>');
				echo('<div class="col-sm-10">');
				echo('<input required pattern="https?://.+" type="url" class="form-control" name="url" placeholder="Inserisci url">');
				echo('</div>');
		echo('</div>');
		//bottone inserisci
		echo('<div class="form-group">');
				echo('<div class="col-sm-offset-2 col-sm-10">');
				echo('<button name="ins" type="submit" class="btn btn-primary">Inserisci</button>');
				echo('</div>');
		echo('</div>');
		echo("</form>");				
	}
	//mostro la tabella di modifica se si tratta di update o delete
	else if(isset($_GET['mod'])&& $_GET['mod']=='update')
	{
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");

		//titolo
    echo('<h2>Modifica link</h2><br>');
		
		if($result=$connection->query("SELECT * FROM Link WHERE id=$id")){
			//form
		echo('<form method="POST" action="updateLink.php" class="form-horizontal">');
			while($row=mysqli_fetch_array($result)){
				//id nascosto
				echo('<input type="hidden" name="id"  value='.$row['id'].'>'); 
				//nome
				echo('<div class="form-group">');
						echo('<label class="control-label col-sm-2">Nome:</label>');
						echo('<div class="col-sm-10">');
						echo('<textarea required class="form-control" name="nome">'.$row['nome'].'</textarea>');
						echo('</div>');
				echo('</div>');
				//url
				echo('<div class="form-group">');
						echo('<label class="control-label col-sm-2">Url:</label>');
						echo('<div class="col-sm-10">');
						echo('<input required pattern="https?://.+" type="url" class="form-control" name="url" value='.$row['url'].'>');
						echo('</div>');
				echo('</div>');
				//bottone modifica
				echo('<div class="form-group">');
						echo('<div class="col-sm-offset-2 col-sm-10">');
						echo('<button name="butt" type="submit" class="btn btn-warning">Modifica</button>');
						echo('</div>');
				echo('</div>');
			} 
			echo("</form>");
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
    echo('<h2>Elimina link</h2><br>');

		if($result=$connection->query("SELECT * FROM Link WHERE id=$id")){

		//form
		echo('<form method="POST" action="updateLink.php" class="form-horizontal">');
			while($row=mysqli_fetch_array($result)){
				//id nascosto
				echo('<input type="hidden" name="id"  value='.$row['id'].'>'); 
				//nome
				echo('<div class="form-group">');
						echo('<label class="control-label col-sm-2">Nome:</label>');
						echo('<div class="col-sm-10">');
						echo('<textarea readonly class="form-control" name="nome">'.$row['nome'].'</textarea>');
						echo('</div>');
				echo('</div>');
				//url
				echo('<div class="form-group">');
						echo('<label class="control-label col-sm-2">Url:</label>');
						echo('<div class="col-sm-10">');
						echo('<input readonly pattern="https?://.+" type="url" class="form-control" name="url" value='.$row['url'].'>');
						echo('</div>');
				echo('</div>');
				//bottone elimina
				echo('<div class="form-group">');
						echo('<div class="col-sm-offset-2 col-sm-10">');
						echo('<button name="butt" type="submit" class="btn btn-danger">Elimina</button>');
						echo('</div>');
				echo('</div>');
			} 
			echo("</form>");
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
      
	  
	  
   
       
