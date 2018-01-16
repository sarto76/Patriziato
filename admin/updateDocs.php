<?php
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

    <title>Gestione Docs</title>

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
    <!-- <link href="css/signin.css" rel="stylesheet"> -->

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

        <?php
        $page = 'docs';
        $titolo = 'Docs';
        include('menu.php');

        ?>
	  
	<?php
    //se è settato il tipo di modifica lo registro in un cookie
	if(isset($_GET['mod'])){
		$mod=$_GET['mod'];
		$mod=$_COOKIE['mod'];
	}
	//se sto inserendo un dato (ho schiacciato il bottone inserisci )
	if(isset($_POST['ins']) && $_FILES['userfile']['size'] > 0){
		//nome visualizzato del file
		$name = mysqli_real_escape_string($connection,$_POST['name']);
		//nome+estensione veri del file
		$fileName = $_FILES['userfile']['name'];
		$tmpName = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];

		$fp = fopen($tmpName,'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);

		$query = "INSERT INTO docs (file, name, size, type, content) 
			  VALUES ('$fileName', '$name', '$fileSize', '$fileType', '$content')";
		
		if (mysqli_query($connection, $query)) {
			//$message = "Link caricato con successo";
			//echo "<script type='text/javascript'>alert('$message');</script>";
			//ritorno alla pagina di base
			header('Location: docs.php');
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
		$name = mysqli_real_escape_string($connection,$_POST['name']);
		$mod=$_COOKIE['mod'];
		
		//se è un'eliminazione
		if($mod=='del'){
			$sql="delete from docs where id=$id";
			$result = $connection->query($sql);
			header('Location: docs.php');
		}
		
		//se è una modifica
		if($mod=='update'){
			$sql="UPDATE docs SET name='$name' WHERE id=$id";
			$result = $connection->query($sql);
			header('Location: docs.php');
		}
	}
	
	
	//solo se inserisco mostro la tabella di inserimento
	else if(isset($_GET['mod'])&& $_GET['mod']=='ins'){
		
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		
		//creo il form per l'inserimento
		echo("<form method=\"POST\" enctype=\"multipart/form-data\" action=\"updateDocs.php\"> ");
		//echo("<div class=\"container theme-showcase\" role=\"main\">");
	      //echo("<div class=\"row\">");
	      //echo("<div class=\"col-md-4\">");
	      echo("<table class=\"table table-striped\">");
	        echo("<thead>");
	          echo("<tr>");
	            echo("<th>Nome</th>");
	            echo("<th>File</th>");
	            echo("<th></th>");
	          echo("</tr>");
	        echo("</thead>");
	        echo("<tbody>");
		echo("</td><td><input type='text' name='name'/></td>"); 
		echo("<td><input type=\"file\" name=\"userfile\" id=\"userfile\"></td>"); 
		echo("<td><input class=\"btn btn-primary\" type=\"submit\" id=\"upload\" value=\"Carica\" name=\"ins\"></td>");
		echo("</table>");
		echo("</form>");
		
	}
	//mostro la tabella di modifica se si tratta di update o delete
	else if(!isset($_POST['ins']))
	{
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
		
		if($result=$connection->query("SELECT * FROM docs WHERE id=$id")){
			echo("<form method=\"POST\" action=\"updateDocs.php\"> ");
			echo("<h1>Record scelto</h1>"); 
			echo("<table class=\"table table-striped\">");
			echo("<tr>");
			echo("<th>Nome</th>");
			//echo("<th>Url</th>");
			echo("<td></td>");
			echo("<tr>");
			while($row=mysqli_fetch_array($result)){
				echo("<tr>");
				$id=$row['id'];
				//echo("<td>".$dat."</td>"); 
				echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"name\" id=\"name\" value=\"".$row['name']."\"></input></td>");
				//echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"url\" id=\"url\" value=\"".$row['url']."\"></input></td>");
				echo("<td><input class=\"btn btn-primary\" type=\"submit\" value=\"Esegui\" name=\"butt\"></td>");
			} 
			echo("</table>");  
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
      
	  
	  
   
       
