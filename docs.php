<?php
session_start();

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
    <meta name="author" content="Andrea Pesce">
    <link rel="icon" href="favicon.ico">

    <title>Docs</title>

    <!-- Bootstrap core CSS -->
    <link href="css/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="css/theme.css" rel="stylesheet">-->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="css/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
                    <li class="active"><a href="docs.php">Documenti</a></li>
                    <li><a href="link.php">Link</a></li>
                    <li><a href="prop.php">Propriet&agrave;</a></li>
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
                    <h3 class="panel-title">Documenti</h3>
                </div>
                <div class="panel-body">
                    <!-- lista di link pdf -->
                    <?php
                    if ($result = $connection->query("SELECT id, name FROM docs")) {
                        if (mysqli_num_rows($result) == 0) {
                            ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Attenzione!</strong> Non sono presenti documenti nel database.
                            </div>
                            <?php
                        } else {
                            echo('<div class="list-group">');
                            while (list($id, $name) = mysqli_fetch_array($result)) {
                                ?>
                                <a href="download.php?id=<?php echo $id; ?>" class="list-group-item">
                                    <?php echo $name; ?>
                                </a>
                                <?php
                            }
                            echo('</div>');
                        }
                    }
                    ?>
                </div>
            </div>

        </div> <!-- /col-sm-12 -->
    </div><!-- /row -->

</div> <!-- /container -->

<?php include 'footer.php'; ?>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="css/assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="css/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="css/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
