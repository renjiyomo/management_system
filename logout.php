<?php
session_start();
session_destroy();

header('Location: /management_system/login.php');
exit();
?>