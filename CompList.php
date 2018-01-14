<?php
    session_start();

    require_once "DBManager.php";

    if(!isset($_SESSION['userid']) && ($_SESSION['username'])){
        header('Location:Login.php');
    }
    session_regenerate_id();

    $dbm = new DBManager();
    $complist = $dbm->getCompToDo($_SESSION['userid']);
    $comp = 1;
    if($complist == NULL){
        $comp = 0;
    }
?>

    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>ログイン</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/Cube.js"></script>
        <script src="./js/pointer.js"></script>

    </head>

    <body>
        <div class="daimei">完了リスト</div>
        <input type="button" class="complete2" value="通常表示" onClick="location.href='ToDoList.php'">
        <h3>以下のものが完了しました。</h3>
        <?php if($comp ==    1){
        foreach($complist as $list){
                $work = $list->work;
                echo "<div class='complist'>" . $work . "</div>" . "<br>";
        }
    } ?>
    </body>

    </html>