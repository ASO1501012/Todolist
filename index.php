<?php
require_once "DBManager.php";

//書き込みの場合
$dbMng = new DBManager();
$dbMng->insertTestTbl($userid,$pass,$username);


//検索の場合
$resultArray = $dbMng->getTestTblById(1);

//検索した結果をループしながら、全件表示
//※foreachは、データがある間ループしてくれる命令
foreach ($resultArray as $testtbl) {
  echo "ID:" . $testtbl->id;
  echo " Mail:" . $testtbl->mail . "<br>";
}

?>