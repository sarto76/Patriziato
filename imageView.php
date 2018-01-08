<?php

if(isset($_GET['image_id'])) {
    include 'database.php';
    $connection = Database::getConnection();
    $id=$_GET['image_id'];
    $sql = "SELECT mime,datas FROM prop_media WHERE idprop_media=" . $id;

    //echo $sql;
    if (!$result2 = mysqli_query($connection, $sql)) {
        echo(mysqli_error($connection));
    }
    if ($result2->num_rows === 0) {
        //echo 'No results';
    } else {
        while ($riga = mysqli_fetch_array($result2)) {
            header("Content-type: " . $riga["mime"]);
            echo $riga["datas"];
        }
    }
}


?>