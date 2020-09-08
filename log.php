<?php
    session_start();
    echo json_encode( array (
        'finduserId' => $_SESSION['userId'],
        'userAcc' => $_SESSION['userAcc'],
        'userName' => $_SESSION['userName'],
        'capital' => $_SESSION['capital']
    ));
?>