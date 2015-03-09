<?php
require_once '../../../wp-load.php';
session_start();
session_unset();
$loginlink = site_url();
header('Location:' . $loginlink);
exit();
?>