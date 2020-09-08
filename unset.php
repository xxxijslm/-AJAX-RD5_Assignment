<?php
    session_start();
    session_unset();
    echo $_SESSION['userAcc'];

?>