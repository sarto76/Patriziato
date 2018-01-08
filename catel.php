<?php
include 'database.php';
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

    <title>Patrizi</title>

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

          <?php include 'logo.php';?>

          <a class="navbar-brand" href="catel.php">CatE</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CatE<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="active"><a href="catel.php">Patrizi</a></li>
                  <li><a href="catel2.php">Relazioni Patrizi</a></li>
                </ul>
              </li>              
              <li><a href="index.php">News</a></li>
              <li><a href="info.php">Info</a></li>
              <li><a href="docs.php">Docs</a></li>
              <li><a href="link.php">Link</a></li>
                <li><a href="admin/prop.php">Prop</a></li>
              <li><a href="contatti.php">Aiuto</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

    <!-- datatable -->   
    <script type="text/javascript">
    $(document).ready(function() {
      $('#catel').DataTable( {
      "order": [[ 1, "asc" ]],
        "deferRender": true,
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
    echo('<h2>Patrizi</h2><br>');
    $connection=Database::getConnection();

     $result=$connection->query("SELECT `no_registro`, `id`, `cognome`, `nome`, `data_nascita`, 
                                IF((TIMESTAMPDIFF(YEAR,`data_nascita`,CURDATE())<18),'no','si') 
                                as `diritto_di_voto`, `vivente`
                                FROM patrizio");                       
       
    if(mysqli_num_rows($result) == 0){
      ?>
      <div class="alert alert-warning" role="alert">
            <strong>Attenzione!</strong> Non sono presenti patrizi nel database.
          </div>
      <?php
      }else{
      echo('<table width="100%" class="table table-striped table-bordered dt-responsive nowrap" id="catel" cellspacing="0">');
        echo('<thead>');
          echo('<tr>');
            echo("<th>no registro</th>");
            echo("<th>cognome</th>");
            echo("<th>nome</th>");
            echo("<th>data nascita</th>");
            echo("<th>diritto di voto</th>");
            echo("<th>vivente</th>");
            //echo("<th>data inserimento</th>");
            //echo("<th>data morte</th>");
            //echo("<th>data perdita patrizio</th>");
          echo("</tr>");
        echo("</thead>");
        echo("<tbody>");
        }
		while($row=mysqli_fetch_array($result)){
			echo('<tr>');
            $no_registro=$row['no_registro'];
            $id=$row['id'];
            $cognome=$row['cognome'];
            $nome=$row['nome'];
            $data_nascita=date_create($row['data_nascita']);
            $diritto_di_voto=$row['diritto_di_voto']; 
            $vivente=$row['vivente'];    
            //$data_inserimento=date_create($row['data_inserimento']);   
            //$data_morte=date_create($row['data_morte']);   
            //$data_perdita_patrizio=date_create($row['data_perdita_patrizio']); 
            //campi 
            echo("<td>".$no_registro."</td>");
            echo("<td>".$cognome."</td>");
            echo("<td>".$nome."</td>"); 
            echo("<td>".date_format($data_nascita, 'd.m.Y')."</td>");
            echo("<td>".$diritto_di_voto."</td>"); 
            echo("<td>".$vivente."</td>"); 
            //echo("<td>".date_format($data_inserimento, 'd.m.Y')."</td>");
            //echo("<td>".date_format($data_morte, 'd.m.Y')."</td>");
            //echo("<td>".date_format($data_perdita_patrizio, 'd.m.Y')."</td>");   
            echo('</tr>');
            //campo nascosto dove tengo in memoria l'id
            echo("<input type=\"hidden\" name=\"id\" value=$id>"); 
	    } 
    echo('</tbody>');
    echo('</table>');      
    //chiudo la connessione
    unset($connection);
	?>

    </div> <!-- /container -->

    <?php include 'footer.php';?> 
	
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
