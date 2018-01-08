<?php
session_start();
//se login effettuato vai alla pagina catel
if(isset($_SESSION['username'])){
    header('Location: catel.php');
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

    <title>login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Bootstrap theme -->
    <link href="css/dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

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

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form-signin">
        <h2 class="form-signin-heading">Patriziato Bosco Gurin</h2>
        <label for="inputEmail" class="sr-only">Nome Utente</label>
        <input type="text" name="username" id="inputEmail" class="form-control" placeholder="Nome Utente" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="pass" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
      </form>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php	
$connection=Database::getConnection();
if(isset($_POST['login'])){
	$username = mysqli_real_escape_string($connection,$_POST['username']);
	$password = md5(mysqli_real_escape_string($connection,$_POST['pass']));
	$sql='select * from utente where username=? AND password=?';
	$result=$connection->prepare($sql);
	$result->bind_param('ss', $username,$password);
	$result->execute();
	$result->store_result();
	$check_user = $result->num_rows;
	$result->close();
	if($check_user>0)//se login avviene con successo
	{
    //salvo il nome utente 
    $_SESSION["username"] = $username;

    /*The address of the page (if any) which referred the user agent to the current page.
     This is set by the user agent. Not all user agents will set this,
     and some provide the ability to modify HTTP_REFERER as a feature.
     In short, it cannot really be trusted.
     
     ATTENZIONE CON LE PAGINE HTTPS

     */
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    header('Location: catel.php');
	}else{
		//Altrimenti stampo un errore
		echo('<center><h2>Errore di connessione</h2></center>');
	}
}
?>