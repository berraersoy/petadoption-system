<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // Go back to the homepage when you log out.
exit();
?>