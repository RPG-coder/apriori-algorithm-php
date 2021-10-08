<?php
    session_start();
    session_destroy();
    header("refresh:5;url=./index.php");
    echo "You have been logged out succesfully. Redirecting to <strong>Sign-in / Sign-up page</strong> in 5 seconds.";
?>