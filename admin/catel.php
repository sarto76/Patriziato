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
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

    <title>Gestione Catalogo Elettorale</title>

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
        $page = 'catel';
        $titolo = 'Gestione CatE';
        include('menu.php');

        ?>

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
    echo('<h2>Elenco patrizi</h2><br>');
    $connection=Database::getConnection();
//print_r($connection);
     $result=$connection->query("SELECT `no_registro`,id, `cognome`, `nome`, `data_nascita`, padre,madre,
                               diritto_di_voto, `vivente`, data_inserimento,data_morte,diritto_di_voto,data_perdita_patrizio,telefono,email,
                                via, nap,localita,foto
                                FROM patrizio
                                where vivente=1
                                and data_perdita_patrizio is null
                                and confermato=1
                                ");

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
            echo("<th><span style='display:none;'>YYYYMMDD</span>data nascita</th>");
            echo("<th>padre</th>");
            echo("<th>madre</th>");
            echo("<th>diritto di voto</th>");
        //    echo("<th>vivente</th>");
        //echo("<th>data inserimento</th>");
        //echo("<th>data morte</th>");
        //echo("<th>data perdita patrizio</th>");
        echo("<th>telefono</th>");
        echo("<th>email</th>");
        echo("<th>via</th>");
        echo("<th>nap</th>");
        echo("<th>localit&agrave;</th>");
        echo("<th>foto</th>");

            //echo("<th>data inserimento</th>");
            //echo("<th>data morte</th>");
            //echo("<th>data perdita patrizio</th>");
            echo("<th></th>");
      //      echo("<th></th>");
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
            if(!empty($row['data_nascita']));
            {
                $data_nascita = date_create($row['data_nascita']);
            }


            $diritto_di_voto=$row['diritto_di_voto'];
            $vivente=$row['vivente'];
            $padre=$row['padre'];
            $madre=$row['madre'];
            $inserimento=$row['data_inserimento'];
            $morte=$row['data_morte'];
            $dir_voto=$row['diritto_di_voto'];
            $perdita=$row['data_perdita_patrizio'];
            $telefono=$row['telefono'];
            $email=$row['email'];
            $via=$row['via'];
            $nap=$row['nap'];
            $localita=$row['localita'];
            $foto=$row['foto'];

            //a partire dal percorso e nome della foto originale estrapolo il nome e percorso del tmb
            $nom_foto= substr($foto, strrpos($foto, '/')+1);
            $percors_foto= substr($foto, 0,strrpos($foto, '/')+1);
            $nome_tmb="tmb_".$nom_foto;
            $tmb_foto=$percors_foto.$nome_tmb;

            //$data_inserimento=date_create($row['data_inserimento']);
            //$data_morte=date_create($row['data_morte']);
            //$data_perdita_patrizio=date_create($row['data_perdita_patrizio']);
            //campi


            echo("<td>".$no_registro."</td>");
            echo("<td>".$cognome."</td>");
            echo("<td>".$nome."</td>");
            if (!empty($row['data_nascita']))
            {
                echo("<td><span  style='display:none;'>" . date_format($data_nascita, 'YYYYMMDD') . "</span>" . date_format($data_nascita, 'd.m.Y') . "</td>");
            }
            else{
                echo("<td></td>");
            }
            echo("<td>" . $padre . "</td>");
            echo("<td>" . $madre . "</td>");
            echo("<td>".$diritto_di_voto."</td>");
          //  echo("<td>".$vivente."</td>");
            //echo("<td>".$inserimento."</td>");
           // echo("<td>".$morte."</td>");
            //echo("<td>".$perdita."</td>");
            echo("<td>".$telefono."</td>");
            echo("<td>".$email."</td>");
            echo("<td>".$via."</td>");
            echo("<td>".$nap."</td>");
            echo("<td>".$localita."</td>");
            echo("<td><img src='$tmb_foto' alt='$nom_foto'></td>");
            //echo("<td>".date_format($data_inserimento, 'd.m.Y')."</td>");
            //echo("<td>".date_format($data_morte, 'd.m.Y')."</td>");
            //echo("<td>".date_format($data_perdita_patrizio, 'd.m.Y')."</td>");
            //2 link che mi inviano alla pagina di modifica/cancellazione/inserimento, passando id e modalità
            echo('<td><a href="update.php?id=' . $row['id'] . '"><button class="btn btn-warning" type="button">Modifica</button></a></td>');
            //echo('<td><a href="#"><button class="btn btn-danger" type="button">Elimina</button></a></td>');
            echo('</tr>');
            //campo nascosto dove tengo in memoria l'id
            echo("<input type=\"hidden\" name=\"id\" value=$id>"); 
	    } 
        echo('</tbody>');
        echo('</table>');
        //link che mi invia alla pagina di modifica/cancellazione/inserimento, passando la modalità





        echo("<div class='btn-toolbar'>");
            echo("<div class='btn-group'>");
              echo('<a href="addPatrizio.php"><button class="btn btn-primary" type="button">Aggiungi Nuovo</button></a>');
              echo('<a target = "_blank" href="pdf/esporta_catalogo.php"><button class="btn btn-success" type="button">Esporta catalogo in pdf</button></a>');
              echo('<a target = "_blank" href="pdf/esporta_excel.php"><button class="btn btn-warning" type="button">Esporta catalogo in excel</button></a>');
            echo("</div>");
        echo("</div>");
    
    


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
