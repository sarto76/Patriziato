<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove only admin session
unset($_SESSION['username']);

// destroy the session 
//session_destroy(); 

// come back to login
header('Location: index.php');
?>

</body>
</html> 