<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit();
}

$hosts = getAllHosts($conn);
$currentUser = getCurrentUser($conn);


include 'index_view.html';
?>