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
            <a class="navbar-brand" href="catel.php"><?php echo $titolo?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <li <?php echo ($page == 'catel') ? 'class="active"' : ''; ?>><a href="catel.php">Catalogo
                        elettorale</a></li>
                <li <?php echo ($page == 'tessere_richieste') ? 'class="active"' : ''; ?>><a
                            href="tessere_richieste.php">Tessere in sospeso</a></li>
                <li <?php echo ($page == 'non_confermati') ? 'class="active"' : ''; ?>><a
                            href="non_confermati.php">Richieste Patrizi</a></li>
                <li <?php echo ($page == 'news') ? 'class="active"' : ''; ?>><a href="news.php">News</a></li>
                <li <?php echo ($page == 'info') ? 'class="active"' : ''; ?>><a href="info.php">Info</a></li>
                <li <?php echo ($page == 'docs') ? 'class="active"' : ''; ?>><a href="docs.php">Docs</a></li>
                <li <?php echo ($page == 'link') ? 'class="active"' : ''; ?>><a href="link.php">Link</a></li>
                <li <?php echo ($page == 'prop') ? 'class="active"' : ''; ?>><a href="prop.php">Prop</a></li>
                <li <?php echo ($page == 'stat') ? 'class="active"' : ''; ?>><a href="stat.php">Stat</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo " " . $_SESSION["username"] ?>
                    </a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>