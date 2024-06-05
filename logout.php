<?php
session_start();
$_SESSION = array();
session_destroy();

if(isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}
header("Location: index.php");
exit;
?>
