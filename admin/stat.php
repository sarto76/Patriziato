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

    <title>Statistiche</title>

    <!-- Bootstrap core CSS -->
    <!-- <link href="css/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	
	  <!-- Bootstrap theme --> 
    <!-- <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet"> -->
	
	  <!-- Custom styles for this template -->
    <!-- <link href="css/theme.css" rel="stylesheet"> -->

    <!-- Custom styles for this template -->
    <!-- <link href="css/signin.css" rel="stylesheet"> -->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="css/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- <script src="css/assets/js/ie-emulation-modes-warning.js"></script> -->
    <!--datatables css-->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css">
    <!--datatables js-->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet"> 

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

        <?php
        $page = 'stat';
        $titolo = 'Stat';
        include('menu.php');

        ?>
    <!-- datatable -->   
    <script type="text/javascript">
    $(document).ready(function() {
      $('#catel').DataTable( {
        "ordering": false,
        "deferRender": true,
        "bFilter": false,
        "bInfo": false,
        "paging": false,
        "language": {
            "sEmptyTable":     "Nessun dato presente nella tabella",
            "sInfo":           "Vista da _START_ a _END_ di _TOTAL_ elementi",
            "sInfoEmpty":      "Vista da 0 a 0 di 0 elementi",
            "sInfoFiltered":   "(filtrati da _MAX_ elementi totali)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ".",
            "sLengthMenu":     "Visualizza _MENU_ elementi",
            "sLoadingRecords": "Caricamento...",
            "sProcessing":     "Elaborazione...",
            "sSearch":         "Cerca:",
            "sZeroRecords":    "La ricerca non ha portato alcun risultato.",
            "oPaginate": {
                "sFirst":      "Inizio",
                "sPrevious":   "Precedente",
                "sNext":       "Successivo",
                "sLast":       "Fine"
            },
            "oAria": {
                "sSortAscending":  ": attiva per ordinare la colonna in ordine crescente",
                "sSortDescending": ": attiva per ordinare la colonna in ordine decrescente"
            }
        }
      } );
    } );
    </script>
    
    <?php
    //titolo pagina
    echo('<h2>Visite Totali</h2>');   
      //tabella              
       echo('<table width="100%" class="table table-striped table-bordered dt-responsive nowrap" id="catel" cellspacing="0">');
        echo('<thead>');
          echo('<tr>');
            echo("<th>Pagina News</th>");
            echo("<th>Pagina Info</th>");
            echo("<th>Pagina Docs</th>");
            echo("<th>Pagina Link</th>");
            echo("<th>Pagina Tour</th>");
            echo("<th>Pagina Contatti</th>");
          echo("</tr>");
        echo("</thead>");
        echo("<tbody>");
			  echo('<tr>');
          //campi 
          echo('<td>'.$_SESSION['countNews'].'</td>');
          echo('<td>'.$_SESSION['countInfo'].'</td>');
          echo('<td>'.$_SESSION['countDocs'].'</td>');
          echo('<td>'.$_SESSION['countLink'].'</td>');
          echo('<td>'.$_SESSION['countTour'].'</td>');
          echo('<td>'.$_SESSION['countContatti'].'</td>');
        echo('</tr>');
        echo('</tbody>');
        echo('</table>');
    //chiudo la connessione
    unset($connection);
	?>

    </div> <!-- /container -->

    <?php //include 'footer.php';?> 
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
    <!--<script>window.jQuery || document.write('<script src="css/assets/js/vendor/jquery.min.js"><\/script>')</script>-->
    <script src="css/dist/js/bootstrap.min.js"></script>
	  <!-- <script src="css/assets/js/docs.min.js"></script> -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="css/assets/js/ie10-viewport-bug-workaround.js"></script>-->
  </body>
</html>
