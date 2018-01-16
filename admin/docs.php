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

    <title>Gestione Documenti</title>

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
    <!-- link href="css/signin.css" rel="stylesheet"> -->

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
    $connection=Database::getConnection();
		$result=$connection->query("SELECT * FROM docs");
    if(mysqli_num_rows($result) == 0){
      ?>
      <div class="alert alert-warning" role="alert">
            <strong>Attenzione!</strong> Non sono presenti documenti nel database.
          </div>
      <?php
      }else{
      echo("<div class=\"table-responsive\">");
      echo("<table class=\"table table-striped\">");
        echo("<thead>");
          echo("<tr>");
            echo("<th>Nome</th>");
            echo("<th>File</th>");
            echo("<th></th>");
            echo("<th></th>");
          echo("</tr>");
        echo("</thead>");
        echo("<tbody>");
        }
			while($row=mysqli_fetch_array($result)){
				echo('<tr>');
        $name=$row['name'];
        $file=$row['file'];
				$id=$row['id'];
				//campo nascosto dove tengo in memoria l'id
				echo("<input type=\"hidden\" name=\"id\" value=$id>"); 

        echo("<td>".$name."</td>"); 
        echo("<td>".$file."</td>"); 
				//2 link che mi inviano alla pagina di modifica/cancellazione/inserimento, passando id e modalità
				echo('<td><a href="updateDocs.php?mod=update&id=' . $row['id'] . '"><button class="btn btn-warning" type="button">Modifica</button></a></td>');  
				echo('<td><a href="updateDocs.php?mod=del&id=' . $row['id'] . '"><button class="btn btn-danger" type="button">Elimina</button></a></td>');   
				echo('</tr>');
			  } 
        echo('</tbody>');
        echo('</table>');
        //link che mi invia alla pagina di modifica/cancellazione/inserimento, passando la modalità
        echo('<a href="updateDocs.php?mod=ins"><button class="btn btn-primary" type="button">Carica</button></a>');  
        echo('</div>');    
        
      
    //chiudo la connessione
    unset($connection);
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
