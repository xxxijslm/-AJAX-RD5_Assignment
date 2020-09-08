<?php
    session_start();
    require_once("config.php");
    $userName = $_SESSION['userName'];
    $userId = $_SESSION['userId'];
    $userAcc = $_SESSION['userAcc'];
    $capital = $_SESSION['capital'];
    $money = $_POST['depositNum'];
    $description = $_POST['description'];



        $findCapitalSql = <<<fc
            SELECT capital 
            FROM `accounts`
            WHERE userId = $userId;
        fc;
        $findCapitalResult = mysqli_query($link, $findCapitalSql);
        $findCapitalRow = mysqli_fetch_assoc($findCapitalResult);
        $findCapital = $findCapitalRow['capital'];
        if (10 <= $money && $money <= 100000) {
            $findCapital += $money;
            $updateCapitalSql = <<<uc
                UPDATE accounts SET capital = $findCapital WHERE userId = $userId
            uc;
            mysqli_query($link, $updateCapitalSql);
            $insertTransSql = <<< it
                INSERT INTO transactions
                (type, userId, money, date, description, balance)
                VALUES
                (0, $userId, $money, current_timestamp(), '$description', $findCapital)
            it;
            mysqli_query($link, $insertTransSql);
            echo ('提示：存款成功！');
            $_SESSION['capital'] = $findCapital;
        }
        else {
            echo ('警告：輸入金額必須介於NTD 10-100000');
        }

?>