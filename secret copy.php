<?php
    session_start();
    require_once("config.php");
    if (!isset($_SESSION['userAcc'])) {
        header ("Location: index.html");
    }
    $userName = $_SESSION['userName'];
    $userId = $_SESSION['userId'];
    $userAcc = $_SESSION['userAcc'];
    $capital = $_SESSION['capital'];
    $updateCapitalSql = <<<uc
        SELECT capital
        FROM `accounts`
        WHERE userId = $userId
    uc;
    $updateCapitalResult = mysqli_query($link, $updateCapitalSql);
    $updateCapitalRow = mysqli_fetch_assoc($updateCapitalResult);
    $_SESSION['capital'] = $updateCapitalRow['capital'];
    if (isset($_POST['deposit'])) {
        header("Location: deposit.php");
    }
    if (isset($_POST['withdrawal'])) {
        header("Location: withdrawal.php");
    }
    if (isset($_POST['detail'])) {
        header("Location: detail.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="secret.css" type="text/css">
    <title>Document</title>
</head>
<body>
    <div class="center">
        <form method="POST" action="">
            <h3> <?= $userName?> </h3>
            <h4 id="cash" name="cash"> <?= "帳號:" . $userAcc. "  總資本額: NTD*****"?> </h4>
            <button type="submit" id="deposit" name="deposit">存款</button>
            <button type="submit" id="withdrawal" name="withdrawal">提款</button>
            <button type="submit" id="detail" name="detail">查詢明細</button>
            <br>
            <a id="logout" style=" cursor: pointer;">登出</a>
        </form>
    </div>
</body>
<script>
    $("#cash").mouseover(function () {
        $("#cash").text("<?= "帳號:" . $userAcc. "  總資本額: NTD" . $capital?>");
    })
    $("#cash").mouseout(function () {
        $("#cash").text("<?= "帳號:" . $userAcc. "  總資本額: NTD*****"?>");
    })
    $('#logout').click(function () {
        <?php session_destroy();?>
        window.location.href = 'index.html';
    })
</script>
</html>