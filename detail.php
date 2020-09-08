<?php
     session_start();
     require_once("config.php");
    $userName = $_SESSION['userName'];
    $userId = $_SESSION['userId'];
    $userAcc = $_SESSION['userAcc'];
    $capital = $_SESSION['capital'];
    
    $detailSql = <<< ds
        SELECT type, money, `date`, description, balance
        FROM `transactions` WHERE userId = $userId
        ORDER BY date DESC
    ds;
    $detailResult = mysqli_query($link, $detailSql);
    while ($detailRow = mysqli_fetch_assoc($detailResult)) {
        $date = $detailRow['date'];
        $money = $detailRow['money'];
        $description = $detailRow['description'];
        $balance = $detailRow['balance'];
        $type = $detailRow['type'];
        echo "<tr>";
        echo "<td class='text'>$date</td>";
        if ($type == 0) {
            echo "<td class='text-right'>$money</td>";
            echo "<td class='text-right'></td>";
        }
        else if ($type == 1){
            echo "<td class='text-right'></td>";
            echo "<td class='text-right'>$money</td>";
        }
        echo "<td class='text-right'>$description</td>";
        echo "<td id='balance' name='balance' class='text-right'>$balance</td>";
        echo "</tr>";
    }
?>