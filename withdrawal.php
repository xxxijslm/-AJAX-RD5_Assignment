<?php
    header('Content-Type: application/json; charset=UTF-8');
    session_start();
    require_once("config.php");
    $userName = $_SESSION['userName'];
    $userId = $_SESSION['userId'];
    $userAcc = $_SESSION['userAcc'];
    $capital = $_SESSION['capital'];
    $money = $_POST['withdrawalNum'];
    $description = $_POST['description'];
    $findLimitSql = <<<fl
        SELECT type, money, DATE_FORMAT(date, '%Y-%m-%d') 
        FROM `transactions`
        WHERE date(`date`) = CURRENT_DATE AND type = 1 AND userId = $userId
    fl;
    $findLimitResult = mysqli_query($link, $findLimitSql);
    $limit = 100000;
    while ($findLimitRow = mysqli_fetch_assoc($findLimitResult)) {
        $limit -= $findLimitRow['money'];
    }



        $findCapitalSql = <<<fc
            SELECT capital 
            FROM `accounts`
            WHERE userId = $userId;
        fc;
        $findCapitalResult = mysqli_query($link, $findCapitalSql);
        $findCapitalRow = mysqli_fetch_assoc($findCapitalResult);
        $findCapital = $findCapitalRow['capital'];
        if ($findCapital >= $money) {
            if($limit > 0 && $money <= $limit) {
                if (100 <= $money && $money <= 100000) {
                    $findCapital -= $money;
                    $updateCapitalSql = <<<uc
                    UPDATE accounts SET capital = $findCapital WHERE userId = $userId
                    uc;
                    mysqli_query($link, $updateCapitalSql);
                    $insertTransSql = <<< it
                        INSERT INTO transactions
                        (type, userId, money, date, description, balance)
                        VALUES
                        (1, $userId, $money, current_timestamp(), '$description', $findCapital)
                    it;
                    mysqli_query($link, $insertTransSql);
                    echo json_encode( array (
                        'suc' => "提示：提款成功！"
                    ));
                    // echo "<script>alert('提示：提款成功！'); location.href = 'secret.php';</script>";
                    $_SESSION['capital'] = $findCapital;
                }
                else {
                    echo json_encode( array (
                        'err' => "警告：輸入金額必須介於NTD 100-100000"
                    ));
                    // echo "<script>alert('警告：輸入金額必須介於NTD 100-100000');</script>";
                }
            }
            else {
                if ($limit <= $capital)
                    echo json_encode( array (
                        'err' => "警告：今日提款已達到上限, 目前只能提款NTD$limit"
                    ));
                    // echo "<script>alert('警告：今日提款已達到上限, 目前只能提款NTD$limit');</script>";
                else
                    echo json_encode( array (
                        'err' => "警告：今日提款已達到上限, 目前只能提款NTD$capital"
                    ));
                    // echo "<script>alert('警告：今日提款已達到上限, 目前只能提款NTD$capital');</script>";
            } 
        }
        else {
            echo json_encode( array (
                'err' => "警告：現有金額不足，請輸入適合的金額"
            ));
            // echo "<script>alert('警告：現有金額不足，請輸入適合的金額');</script>";
        }
?>