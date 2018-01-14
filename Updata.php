<?php
    session_start();

    if(!isset($_SESSION['userid']) && ($_SESSION['username'])){
        header('Location:Login.php');
    }
    session_regenerate_id();
    if(isset($_POST['work']) && ($_POST['doday'])){
		require_once 'DBManager.php';
		$dbm = new DBManager();
        $dbm->updateTODOTbl($_SESSION['userid'],$_POST['todoid'],$_POST['doday'],$_POST['work']);
        header('Location:ToDOList.php');
	}
?>

    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Updata-ToDO</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/Cube.js"></script>
        <script src="./js/pointer.js"></script>

    </head>

    <body>
        <form action="Updata.php" method="post">
            <div style="width:400px;margin-right:auto;margin-left:auto;margin-top:auto;padding:30px;">
                <table>
                    <tr>
                        <td>
                            <div class="tagu">ToDo更新</div>
                        </td>
                        <td>
                            <input type="button" class="complete" value="通常表示" onClick="location.href='ToDoList.php'">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80px;">
                            <div style="text-align:right;opacity: 0.5">内容:</div>
                        </td>
                        <td>
                            <input type="text" name="work">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80px;">
                            <div style="text-align:right;opacity: 0.5">期限:</div>
                        </td>
                        <td>
                            <input type="text" name="doday">
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <input type="submit" value="更新" style="background-color:#0066CC;color:#FFFFFF;width:260px;margin-top:50px;margin-left:auto;margin-right:auto;border:0;">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="todoid" value="<?php echo $_POST['todoid']; ?>">
        </form>
    </body>

    </html>