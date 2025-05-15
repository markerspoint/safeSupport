<?php
session_start();
session_unset();
session_destroy();

// Redirect to index page
header("Location: index.php");
exit();
?>
