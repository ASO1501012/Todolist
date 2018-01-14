<?php
	if(isset($_POST['userid']) && ($_POST['pass'])){
		require_once 'userManager.php';
		$um = new userManager();
		$um->logincheck($_POST['userid'],$_POST['pass']);
	}
?>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/Cube.js"></script>
        <script src="./js/common.js"></script>
        <script src="./js/pointer.js"></script>

        <title>ログイン</title>
    </head>
   
    <body>
        <form action="Login.php" method="post">
            <div style="width:290px;margin-right:auto;margin-left:auto;padding:30px;border:solid 1px #000000;">
                <table>
                    <tr>
                        <td style="width:90px;">
                            <div style="text-align:right">ユーザID:</div>
                        </td>
                        <td>
                            <input type="text" name="userid">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:90px;">
                            <div style="text-align:right">パスワード:</div>
                        </td>
                        <td>
                            <input type="password" name="pass">
                        </td>
                    </tr>
                </table>
                <input type="submit" value="ログイン" style="background-color:#8181E4;color:#FFFFFF;width:260px;margin-top:50px;margin-left:auto;margin-right:auto;border:0;">
            </div>
        </form>
    </body>

    </html>
