<?php
include 'database.php';
if(isset($_GET['id'])){
	// if id is set then get the file with the id from database
	$id = $_GET['id'];
	if($result=$connection->query("SELECT file, type, size, content FROM docs WHERE id = '$id'"))
	list($file, $type, $size, $content) = mysqli_fetch_array($result);
	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$file");
	echo $content;
	exit;
}
?>