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

    <title>Patriziato Bosco Gurin</title>

    <!-- Bootstrap core CSS -->
    <link href="css/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

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
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php include 'logo.php'; ?>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">News</a></li>
                    <li><a href="info.php">Info</a></li>
                    <li><a href="docs.php">Documenti</a></li>
                    <li><a href="link.php">Link</a></li>
                    <li class="active"><a href="prop.php">Propriet&agrave;</a></li>
                    <li><a href="tour.php">Tour</a></li>
                    <li><a href="contatti.php">Contatti</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <?php include 'slideshow.php'; ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Propriet&agrave;</h3>
                </div>
                <div class="panel-body">

                    <?php
                    $connection = Database::getConnection();
                    $result = $connection->query("SELECT * FROM prop");
                    if (mysqli_num_rows($result) == 0) {
                        ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Attenzione!</strong> Non sono presenti proprietà nel database.
                        </div>
                        <?php
                    } else {
                        echo("<div class=\"table-responsive\">");
                        echo("<table class=\"table table-striped\">");
                        echo("<thead>");
                        echo("<tr>");
                        echo("<th>Titolo</th>");
                        echo("<th>Descrizione</th>");
                        echo("<th></th>");
                        echo("</tr>");
                        echo("</thead>");
                        echo("<tbody>");
                    }
                    while ($row = mysqli_fetch_array($result)) {
                        echo('<tr>');
                        $title = $row['title'];
                        $description = $row['description'];
                        $id = $row['id'];

                        echo("<td><b>" . $title . "</b></td>");
                        echo("<td>" . $description . "</td>");




                        $sql = "SELECT idprop_media FROM prop_media,prop
                                where prop.id=prop_media.id_prop
                                and prop.id='$id'
                                ORDER BY idprop_media DESC";
                       
                        if (!$result2= mysqli_query($connection,$sql)){
                            echo(mysqli_error($connection));
                        }
                        if($result2->num_rows === 0)
                        {
                            //echo 'No results';
                        }
                        else {
                            while ($riga = mysqli_fetch_array($result2)) {
                                echo("<td>");
                                $idf=$riga["idprop_media"];
                                //print_r ($idf);
                                echo("<a target='_blank' href='imageView.php?image_id=$idf'><img src='thumbView.php?image_id=$idf'/></a>");


                                echo("</td>");
                            }
                        }





                        echo('</tr>');

                    }
                    echo('</tbody>');
                    echo('</table>');

                    echo('</div>');

                    //chiudo la connessione
                    unset($connection);
                    ?>
                </div>
            </div>

        </div> <!-- /col-sm-12 -->
    </div><!-- /row -->
</div><!-- /container -->

        <?php include 'footer.php';?>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="css/assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/dist/js/bootstrap.min.js"></script>
        <!-- <script src="css/assets/js/Prop.min.js"></script> -->
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

