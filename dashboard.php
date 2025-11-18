<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<h1>Welcome <?= $_SESSION['username'] ?>!</h1>
<p>You are logged in to APIIT Lost & Found.</p>
