<?php
session_start();
session_unset();
session_destroy();

// Ensure cookies are deleted properly
setcookie('user_email', '', time() - 3600, "/", "", false, true);
setcookie('user_password', '', time() - 3600, "/", "", false, true);

// Unset the cookies in PHP
unset($_COOKIE['user_email']);
unset($_COOKIE['user_password']);

// Redirect to index page
header("Location: index.php");
exit();
