<?php
    session_start();

    require_once "DBManager.php";

    if(!isset($_SESSION['userid']) && ($_SESSION['username'])){
        header('Location:Login.php');
    }
    session_regenerate_id();

    $dbm = new DBManager();
    $falseflag = $dbm->getExpireTODOTBl($_SESSION['userid'],date("Y-m-d"));
    //期限切れ警告のメッセージ表示
    if($falseflag == NULL){
        $falseflag = 0;
    }
    $todolist = $dbm->getTODOTblByDaySort($_SESSION['userid']);
    //ToDoリスト表示
    $cnt = count($todolist);

    if(isset($_POST['todoid'])){
        $dbm->deleteTODOTbl($_SESSION['userid'],$_POST['todoid']);
        header('Location:ToDoList.php');
    }
?>


    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>To-Do-list</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="./js/load.js"></script>
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/Cube.js"></script>
        <script src="./js/pointer.js"></script>
    </head>

    <body>
        <!-- ロード中の画面 -->
        <div id="loader-bg">
            <div id="loader">
                <img src="./img/loading.gif" width="80" height="80" alt="Now Loading..." />
                <p>Now Loading...</p>
            </div>
        </div>
        <!-- ロード後の画面 -->
        <div id="wrap">
            <div style="width:780px;height:600px;overflow:auto;overflow:none;margin-right:auto;margin-left:auto;padding:30px;">
                <table>
                    <tr>
                        <td>
                            <input type="button" class="complete" value="完了表示" onClick="location.href='CompList.php'">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="info">期限切れ情報</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="kigen">
                                <?php 
        if($falseflag != 0){
            foreach($falseflag as $list){ $work = $list->work; echo"「" . $work . "」"; } echo "の期限が切れています。ただちに終了させてください。";
        }else{
            echo "期限切れの要件はありません。";
        }
        ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="info">未完了ToDoList</div>
                        </td>
                    </tr>
                    <?php foreach($todolist as $list){
                $work = $list->work;
                $doday = date("Y/m/d", strtotime($list->doday));
                $todoid = $list->todoid;
                $today = date("Y/m/d");
                if($doday < $today){
                    $class = "btn-color";
                }else{
                    $class = "btn-color2";
                }
                echo "<form id='the-form' action='ToDoList.php' method='post'>";
                echo "<tr><td><div class=$class><input type='submit' class='work' name='work' value=$work>" . "<div class='doday'>" . $doday ."</div>" . "</td>";
                echo "<input type='hidden' name='todoid' value=$todoid>" . "</form>";
                echo "<form action='Updata.php' method='post'>";
                echo "<td><input type='submit' class='img' value='↻'></div></td></tr>";
                echo "<input type='hidden' name='todoid' value=$todoid>" . "</form>";
            } ?>
                </table>
                <center>
                    <input type="button" class="in" value="＋" onClick="location.href='RegistToDO.php'">
                </center>
            </div>
        </div>
        <!--
    <script>
        //ボタンをクリックした時の処理
        $('#the-form').submit(function(event) {
            // HTMLでの送信をキャンセル
            event.preventDefault();
            var $content = $('#wrap');
        
            // 操作対象のフォーム要素を取得
            var $form = $(this);
                
            // 送信
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                timeout: 10000,  // 単位はミリ秒
        
                // 通信成功時の処理
                success: function(result, textStatus, xhr) {
                    var link = "http://localhost/jk2/todolist/ToDoList.php"
                    $content.fadeOut(600, function() {
                        getPage(link);
                    });
                },
        
                // 通信失敗時の処理
                error: function(xhr, textStatus, error) {
                    alert('NG...');
                }
            });
        });

        function getPage(elm){
            $.ajax({
                type: 'GET',
                url: elm,
                dataType: 'html',
                success: function(url){
                    $('#wrap').load('http://localhost/jk2/todolist/ToDoList.php').fadeIn(600);
                    //$content.html(url).fadeIn(600);
                },
                error:function() {
                        alert('問題がありました。');
                    }
            });
        }
    </script>
    -->
    </body>

    </html>

