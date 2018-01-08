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

    <title>Gestione Proprietà</title>

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
            <a class="navbar-brand" href="prop.php">Gestione Proprietà</a>
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
              <li><a href="link.php">Link</a></li>
              <li class="active"><a href="prop.php">Prop</a></li>
              <li><a href="stat.php">Stat</a></li>
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
	if((isset($_POST['ins']) || isset($_POST['update'])) && isset($_FILES['files'])){
		$errors= array();
		//eseguo prima la query per l'inserimento della proprietà
		$title = mysqli_real_escape_string($connection,$_POST['title']);
		$description = mysqli_real_escape_string($connection,$_POST['description']);
		$query1="INSERT INTO `prop`(`title`, `description`) VALUES ('$title','$description');";
		mysqli_query($connection, $query1);	
        foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
            $file_name = $key.$_FILES['files']['name'][$key];
            $file_size =$_FILES['files']['size'][$key];
            $file_tmp =$_FILES['files']['tmp_name'][$key];
            $file_type=$_FILES['files']['type'][$key];			 
			//verifica dimensione files
            if($file_size > 2097152){
                $errors[]='File size must be less than 2 MB';
            }	
			$id_prop_query="SELECT * 
							FROM `prop` 
							ORDER BY `prop`.`id` 
							DESC LIMIT 1";
			$result=$connection->query($id_prop_query);
			$row=mysqli_fetch_array($result);
			//id della proprietà
			$id=$row[0];

			//$blob = fopen($file_tmp, 'rb');
           $blob = addslashes(file_get_contents($file_tmp));
            switch($file_type) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($file_tmp);

                    break;

                case 'image/png':
                    $image = imagecreatefrompng($file_tmp);
                    break;

                case 'image/gif':
                    $image = imagecreatefromgif($file_tmp);
                    break;

                default:
                    throw new InvalidArgumentException('File "'.$file_type.'" is not valid jpg, png or gif image.');
                    break;

            }


            $imagedetails = getimagesize($file_tmp);
            $width_orig = $imagedetails[0];
            $height_orig = $imagedetails[1];
            $width=100;
            $height=$width/($width_orig/$height_orig);
            $image_p = imagecreatetruecolor($width, $height);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            ob_start();
            imagejpeg($image_p, null, 72);
            $content = ob_get_contents();
            ob_end_clean();
            $content = mysqli_real_escape_string($connection,$content);
            //$sql = "UPDATE `photos` set `thumbnail` = '$content' WHERE `ID` = '$ID'";
            //$result = mysql_query($sql, $db);




            $query2="INSERT INTO prop_media(id_prop,datas,mime,thumbnail) values('$id','$blob','$file_type','$content')";


            if (!mysqli_query($connection, $query2)){
                echo("Error description: " . mysqli_error($connection));
            }

        }
        if(empty($error)){

			//header('Location: prop.php');
        }
	}
    //se ho cliccato  il tasto esegui (update o delete)
    if(isset($_POST['butt'])){
		$connection=Database::getConnection();
		//riprendo i valori necessari
	 	$id=$_COOKIE['id'];
		//$fn=$_POST['fn'];
		$title = mysqli_real_escape_string($connection,$_POST['title']);
		$description = mysqli_real_escape_string($connection,$_POST['description']);
		$mod=$_COOKIE['mod'];
		
		//eliminazione singole immagini
		if($mod=='show' && isset($fn)){
			//cartella proprietà
			$dir = 'prop/';
			//elimino l'immagine selezionata'
			unlink($dir.$fn);
			//elimino l'immagine'
			$sql="delete from prop_media where FILE_NAME='$fn' and ID_PROP=$id";
			$result = $connection->query($sql);
			//ritorno alla pagine delle proprietà
			header('Location: prop.php');
		}

		//se è un'eliminazione
		if($mod=='del'){
			//funzione che elimina cartella prop e files al suo interno
			function delete_files($target) {
				if(is_dir($target)){
					//GLOB_MARK adds a slash to directories returned
					$files = glob( $target . '*', GLOB_MARK ); 
					
					foreach( $files as $file )
					{
						delete_files( $file );      
					}
				
					rmdir( $target );
				} elseif(is_file($target)) {
					unlink( $target );  
				}
			}
			//richiamo la funzione
			delete_files('prop/'.$title);
			//eliminazione di tutte le immagini della singola prop dal DB
			$sql1="delete from prop_media where ID_PROP=$id";
			$result1 = $connection->query($sql1);
			//eliminazione proprietà dal DB
			$sql2="delete from prop where id=$id";
			$result2 = $connection->query($sql2);
			//ritorno alla pagina delle proprietà
			header('Location: prop.php');
		}
		
		//se è una modifica
		if($mod=='update'){
			$sql="update prop set title='$title',description='$description' where id=$id";
			$result = $connection->query($sql);
			header('Location: prop.php');
		}
	}
	
	//solo se inserisco mostro la tabella di inserimento
	else if(isset($_GET['mod']) && $_GET['mod']=='ins'){
		
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		
		//creo il form per l'inserimento
		echo("<form method=\"POST\" enctype=\"multipart/form-data\" action=\"updateProp.php\"> ");

	      echo("<table class=\"table table-striped\">");
	        echo("<thead>");
	          echo("<tr>");
	            echo("<th>Titolo</th>");
				echo("<th>Descrizione</th>");
	            echo("<th>Immagini</th>");
	            echo("<th></th>");
	          echo("</tr>");
	        echo("</thead>");
	        echo("<tbody>");
		//titolo
		echo("</td><td><input type='text' name='title' required/></td>"); 
		//descrizione
		echo("</td><td><input type='text' name='description' /></td>"); 
		echo("<td><input type=\"file\" name=\"files[]\" multiple/></td>");
		echo("<td><input class=\"btn btn-primary\" type=\"submit\" id=\"upload\" value=\"Crea\" name=\"ins\"></td>");
		echo('</tbody>');
		echo("</table>");
		//echo('</div>');
    	//echo('</div>');
      	//echo('</div>');
		echo("</form>");
		
	}
	//mostro la tabella di modifica se si tratta di update
	else if(isset($_GET['mod']) && $_GET['mod']=='update'){
	//else if(!isset($_POST['ins'])){
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
		
		if($result=$connection->query("SELECT * FROM prop WHERE id=$id")){
			echo("<form method=\"POST\" enctype=\"multipart/form-data\" action=\"updateProp.php\"> ");
			echo("<div class=\"container theme-showcase\" role=\"main\">");
	      	echo("<div class=\"row\">");
	      	echo("<div class=\"col-md-11\">");
			echo("<table class=\"table table-striped\">");
			echo("<thead>");
			echo("<tr>");
			echo("<th>Titolo</th>");
			echo("<th>Descrizione</th>");
			echo("<th></th>");
			echo("<th></th>");
			echo("<tr>");
			echo("</thead>");
        	echo("<tbody>");
			while($row=mysqli_fetch_array($result)){
				echo("<tr>");
				$id=$row['id'];
				echo("<td><input type=\"text\" name=\"title\" id=\"title\" value=\"".$row['title']."\"></input></td>");
				echo("<td><input type=\"text\" name=\"description\" id=\"description\" value=\"".$row['description']."\"></input></td>");
				echo("<td><input type=\"file\" name=\"files[]\" multiple/></td>"); 
				echo("<td><input class=\"btn btn-warning\" type=\"submit\" value=\"Modifica\" name=\"update\"></td>");
			} 
			echo('</tbody>');
			echo("</table>");
			echo('</div>');
      		echo('</div>');
      		echo('</div>');  
			echo("</form>");
		}
		if($result=$connection->query("SELECT pm.FILE_NAME 
                                    FROM prop_media pm, prop p 
                                    WHERE pm.ID_PROP = p.id
    	                            AND p.id = $id;")){
			if(mysqli_num_rows($result) == 0){
				?>
				<div class="alert alert-warning" role="alert">
						<strong>Attenzione!</strong> Non sono presenti immagini per la proprietà.
					</div>
				<?php
			}else{
				echo("<form method=\"POST\" action=\"updateProp.php\"> ");
				echo("<div class=\"container theme-showcase\" role=\"main\">");
				echo("<div class=\"row\">");
				echo("<div class=\"col-md-2\">");
				echo("<table class=\"table table-striped\">");
				echo("<thead>");
				echo("<tr>");
					echo("<th>Immagini</th>");
					echo("<th></th>");
				echo("</tr>");
				echo("</thead>");
				echo("<tbody>");
			}
			while($row=mysqli_fetch_array($result)){
				echo("<tr>");
				$fn=$row[0];
				//echo("<td>".$dat."</td>"); 
				//echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"name\" id=\"name\" value=\"".$row['name']."\"></input></td>");
				//echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"url\" id=\"url\" value=\"".$row['url']."\"></input></td>");
				//echo("<td><input class=\"btn btn-primary\" type=\"submit\" value=\"Esegui\" name=\"butt\"></td>");
				echo("<td><img src=\"prop/$fn\" width='150px' heigth='50px'></td>");  
				echo("<td><input class=\"btn btn-danger\" type=\"submit\" value=\"Elimina\" name=\"butt\"></td>");
				echo("<input type=\"hidden\" name=\"fn\" value=\"$fn\"/>");
				echo('</tr>');
			} 
			echo('</tbody>');
        	echo('</table>');
			echo('</div>');
			echo('</div>');
			echo('</div>');
			echo("</form>");
			//chiudo la connessione
			unset($connection);
		}  
	}
	//mostro la tabella di eliminazione se si tratta di delete
	else if(isset($_GET['mod']) && $_GET['mod']=='del'){
	//else if(!isset($_POST['ins'])){
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
	
		if($result=$connection->query("SELECT * FROM prop WHERE id=$id")){
			echo("<form method=\"POST\" action=\"updateProp.php\"> ");
			//echo("<div class=\"container theme-showcase\" role=\"main\">");
	      	//echo("<div class=\"row\">");
	      	//echo("<div class=\"col-md-12\">");
			echo("<table class=\"table table-striped\">");
			echo("<thead>");
			echo("<tr>");
			echo("<th>Titolo</th>");
			echo("<th>Descrizione</th>");
			echo("<th></th>");
			echo("<tr>");
			echo("</thead>");
        	echo("<tbody>");
			while($row=mysqli_fetch_array($result)){
				echo("<tr>");
				$id=$row['id'];
				echo("<td><input type=\"text\" style=\" border: none;border-color: transparent;\" name=\"title\" id=\"title\" value=\"".$row['title']."\" readonly></input></td>");
				echo("<td><input type=\"text\" style=\" border: none;border-color: transparent;\" name=\"description\" id=\"description\" value=\"".$row['description']."\" readonly></input></td>");
				echo("<td><input class=\"btn btn-danger\" type=\"submit\" value=\"Elimina\" name=\"butt\"></td>");
			} 
			echo('</tbody>');
			echo("</table>");
			//echo('</div>');
      		//echo('</div>');
      		//echo('</div>');  
			echo("</form>");
		} 
	}
	//mostro la tabella delle immagini se la modalità è show
	else if(isset($_GET['mod']) && $_GET['mod']=='show'){
		$id=$_GET['id'];
		$mod=$_GET['mod'];
		//setto i cookie per la query finale
		setcookie("mod", $mod, time() + (86400 * 30), "/");
		setcookie("id", $id, time() + (86400 * 30), "/");
		
		if($result=$connection->query("SELECT p.title, pm.FILE_NAME 
                                    FROM prop_media pm, prop p 
                                    WHERE pm.ID_PROP = p.id
    	                            AND p.id = $id;")){
			if(mysqli_num_rows($result) == 0){
				?>
				<div class="alert alert-warning" role="alert">
						<strong>Attenzione!</strong> Non sono presenti immagini per la proprietà.
					</div>
				<?php
			}else{
				echo("<form method=\"POST\" action=\"updateProp.php\">");
				echo("<div class=\"container theme-showcase\" role=\"main\">");
				echo("<div class=\"row\">");
				echo("<div class=\"col-md-12\">");
				echo("<table class=\"table table-striped\">");
				echo("<thead>");
				echo("<tr>");
					echo("<th>Immagini</th>");
					echo("<th></th>");
				echo("</tr>");
				echo("</thead>");
				echo("<tbody>");
			}
			while($row=mysqli_fetch_array($result)){
				echo("<tr>");
				//recupero nome cartella proprietà
				$pft=$row[0];
				//recupero immagini proprietà
				$fn=$row[1];
				//echo("<td>".$dat."</td>"); 
				//echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"name\" id=\"name\" value=\"".$row['name']."\"></input></td>");
				//echo("<td><input type=\"text\" style=\"border: none;border-color: transparent;\" name=\"url\" id=\"url\" value=\"".$row['url']."\"></input></td>");
				//echo("<td><input class=\"btn btn-primary\" type=\"submit\" value=\"Esegui\" name=\"butt\"></td>");
				echo("<td><img src=\"prop/$pft/$fn\" width='150px' heigth='50px'></td>");  
				echo("<td><input class=\"btn btn-danger\" type=\"submit\" value=\"Elimina\" name=\"butt\"></td>");
				echo("<input type=\"hidden\" name=\"fn\" value=\"$fn\"/>");
				echo('</tr>');
			} 
			echo('</tbody>');
        	echo('</table>');
			echo('</div>');
			echo('</div>');
			echo('</div>');
			echo("</form>");
			//chiudo la connessione
			unset($connection);
		} 
	}
	?>

	</div> <!-- /container -->

	<?php//include 'footer.php';?> 
	
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
      
	  
	  
   
       
