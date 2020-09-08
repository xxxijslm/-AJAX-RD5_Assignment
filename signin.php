<?php
    header('Content-Type: application/json; charset=UTF-8');
    session_start();
    $userAcc = $_POST['loginUserAcc'];
    $password = $_POST['loginPassword'];
    if ($userAcc != "" and $password != "") {
        $hash = hash('sha256', $password);
        $sql = <<<multi
            SELECT * 
            FROM `users`
            WHERE userAcc = '$userAcc' AND password = '$hash'
        multi;
        require_once("config.php");
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $userId = $row['userId'];
        // var_dump($row["userName"]);
        $findCapitalSql = <<<fc
            SELECT capital
            FROM `accounts`
            WHERE userId = $userId
        fc;
        $findCapitalResult = mysqli_query($link, $findCapitalSql);
        $findCapitalRow = mysqli_fetch_assoc($findCapitalResult);
        // header ("Location: secret.php");
        if ($row) {
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['userAcc'] = $row['userAcc'];
            $_SESSION['userName'] = $row['userName'];
            $_SESSION['capital'] = $findCapitalRow['capital'];
            // header("Location: secret.php");
            echo json_encode( array (
                'finduserId' => $_SESSION['userId'],
                'userAcc' => $_SESSION['userAcc'],
                'userName' => $_SESSION['userName'],
                'capital' => $_SESSION['capital']
            ));
        }
        else {
            echo json_encode( array (
                'err' => "帳號未註冊或帳號密碼錯誤！請重新輸入"
            ));
            // echo("帳號未註冊或帳號密碼錯誤！請重新輸入");
        }
    }
    
?>