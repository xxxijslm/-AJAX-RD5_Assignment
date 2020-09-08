<?php
    header('Content-Type: application/json; charset=UTF-8');
    $userName = $_POST['userName'];
    $userAcc = $_POST['userAcc'];
    $password = $_POST['password'];
    $passwordAgain = $_POST['passwordAgain'];
    // echo($userName);
    if ($password == $passwordAgain) {
        $hash = hash('sha256', $password);
        $command = <<<lines
            SELECT * FROM `users`
            WHERE userAcc='$userAcc'
        lines;
        require_once("config.php");
        $result = mysqli_query($link, $command);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo json_encode(array (
                'err' => '*帳號已經註冊',
            ));
            // $erracc = "*帳號已經註冊";
            // echo($erracc);
        }
        else {
            $sql = <<<multi
                INSERT INTO users
                (userAcc, userName, password)
                VALUE
                ('$userAcc', '$userName', '$hash')
            multi;
            // echo($sql);              
            mysqli_query($link, $sql);
            $selectIdSql = <<<si
                SELECT userId FROM `users`
                WHERE userAcc = '$userAcc'
            si;
            $selectIdResult = mysqli_query($link, $selectIdSql);
            $selectIdRow = mysqli_fetch_assoc($selectIdResult);
            $userId = $selectIdRow['userId'];
            $insertAccSql = <<<ia
                INSERT INTO accounts
                (userId)
                VALUES
                ($userId)
            ia;
            mysqli_query($link, $insertAccSql);
            echo json_encode(array (
                'suc' => '註冊成功請重新登入',
            ));
            // echo("註冊成功請重新登入");
        }
    }
    else {
        echo json_encode(array (
            'err' => '*輸入密碼不一致',
        ));
        // $err = "*輸入密碼不一致";
        // echo($err);
    }
?>