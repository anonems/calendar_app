<?php   
session_start();
if( $_SESSION["connecte"] === true){
session_destroy();
header("location: index.php");
exit();
}
?>