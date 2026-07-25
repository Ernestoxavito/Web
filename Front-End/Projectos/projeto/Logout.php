
<?php 
session_start();
session_unset();
session_destroy();
header("Location: chad.php");
header("Location: Index.php");
header("Location: Dashboardd.php");
header("Location: login.php");
exit();
?>





