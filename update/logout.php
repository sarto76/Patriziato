<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
$_SESSION = array();
session_destroy();
header('location: index.php');
exit();
?>

</body>
</html> 