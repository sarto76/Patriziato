<?php
include 'database.php';
if(isset($_GET['id'])){
	// if id is set then get the file with the id from database
	$id = $_GET['id'];

    $stmt = $connection->prepare("SELECT file, type, size, content FROM docs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doc = $result->fetch_assoc();

    $file = $doc['file'];
    $type = $doc['type'];
    $size = $doc['size'];
    $content = $doc['content'];

	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$file");
	echo $content;
	exit;
}
?>