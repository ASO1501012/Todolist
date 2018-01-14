<?php
//テーブル用のクラスを読み込む
require_once "TestTblDT.php";
require_once 'DBLogininfo.php';
require_once "ToDoTblDT.php";
class DBManager{
  public $user = "secuser";
  public $password = "password";
  public $dbhost = "localhost";
  public $dbname = "secdb";
  private $myPdo;

  //接続のメソッド
 public function dbConnect(){
    try{
      $this->myPdo = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname  . ';charset=utf8', $this->user, $this->password, array(PDO::ATTR_EMULATE_PREPARES => false));

    }catch(PDOException $e) {
      print('データベース接続失敗'.$e->getMessage());
      throw $e;
    }

  }


  //切断のメソッド
  public function dbDisconnect(){
    unset($myPdo);
  }


  //検索のメソッド
  public function getUserInfoTblByUserId($userid){
    try{
      //DBに接続
      $this->dbConnect();

      //SQLを生成
      $stmt = $this->myPdo->prepare('SELECT * FROM userinf WHERE userid = :keyid');
      $stmt->bindParam(':keyid', $userid, PDO::PARAM_STR);
      //SQLを実行
      $stmt->execute();

  
      //取得したデータを１件ずつループしながらクラスに入れていく
      $list = array();
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        //データを入れるクラスをnew
        $rowData = new TestTblDT();

        //DBから取れた情報をカラム毎に、クラスに入れていく
        $rowData->userid = $row["userid"];
        $rowData->userpass = $row["pass"];
        $rowData->username = $row["username"];

        //取得した一件を配列に追加する
        array_push($list, $rowData);
      }
  
      $this->dbDisconnect();
  
      //結果が格納された配列を返す
      return $list;

    }catch (PDOException $e) {
      print('検索に失敗。'.$e->getMessage());
    }
  }

  //書き込みのメソッド
  public function insertuserInfo($userid, $pass,$username){
    try{
      //DBに接続
      $this->dbConnect();

      $stmt = $this->myPdo->prepare('INSERT INTO userinf(userid, pass, username) VALUES (:id, :pass, :name)');
      $stmt->bindValue(':id', $userid, PDO::PARAM_STR);
      $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
      $stmt->bindValue(':name', $username, PDO::PARAM_STR);

      
      //SQL実行
      $stmt->execute();

      //DB切断
      $this->dbDisconnect();

    }catch (PDOException $e) {
      print('書き込み失敗。'.$e->getMessage());
      throw $e;
    }

  }

  public function insertTODO($userid,$doday,$work){
    try{
      $this->dbConnect();

      $stmt = $this->myPdo->prepare('INSERT INTO todolist(userid,doday,work) VALUES (:id,:doday,:work)');
      $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
      $stmt->bindValue(':doday',$doday,PDO::PARAM_STR);
      $stmt->bindValue(':work',$work,PDO::PARAM_STR);

      $stmt->execute();

      $this->dbDisconnect();

    }catch(PDOException $e){
        print('書き込み失敗。'.$e->getMessage());
        throw $e;
    }

  }

  public function getTODOTblByDaySort($userid){
    try{
      $this->dbConnect();

      $stmt = $this->myPdo->prepare('SELECT * FROM todolist WHERE userid = :id AND delflag = 0 ORDER BY doday');
      $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
      $stmt->execute();

      //取得したデータを１件ずつループしながらクラスに入れていく
      $list = array();
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        //データを入れるクラスをnew
        $rowData = new ToDoTblDT();

        //DBから取れた情報をカラム毎に、クラスに入れていく
        $rowData->userid = $row["userid"];
        $rowData->todoid = $row["todoid"];
        $rowData->doday = $row["doday"];
        $rowData->work = $row["work"];

        //取得した一件を配列に追加する
        array_push($list, $rowData);
      }

      $this->dbDisconnect();
      return $list;
    }catch(PDOException $e){
      print('取得失敗。'.$e->getMessage());
      throw $e;
    }
  }

  public function getExpireTODOTBl($userid,$today){
    try{
      $this->dbConnect();

      $stmt = $this->myPdo->prepare('SELECT * FROM todolist WHERE userid = :id and doday < :day and delflag = 0 ORDER BY doday');
      $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
      $stmt->bindValue(':day',$today,PDO::PARAM_STR);
      $stmt->execute();


      //取得したデータを１件ずつループしながらクラスに入れていく
      $list = array();
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        //データを入れるクラスをnew
        $rowData = new ToDoTblDT();

        //DBから取れた情報をカラム毎に、クラスに入れていく
        $rowData->userid = $row["userid"];
        $rowData->todoid = $row["todoid"];
        $rowData->doday = $row["doday"];
        $rowData->work = $row["work"];


        //取得した一件を配列に追加する
        array_push($list, $rowData);
      }

      $this->dbDisconnect();
      return $list;
    }catch(PDOException $e){
      print('取得失敗。'.$e->getMessage());
      throw $e;
    }
  }

  public function getCompToDo($userid){
        try{
        $this->dbConnect();
        $stmt = $this->myPdo->prepare('SELECT work FROM todolist WHERE userid = :id and delflag = 1 ORDER BY doday');
        $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
        $stmt->execute();

      //取得したデータを１件ずつループしながらクラスに入れていく
      $list = array();
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        //データを入れるクラスをnew
        $rowData = new ToDoTblDT();

        //DBから取れた情報をカラム毎に、クラスに入れていく
        $rowData->work = $row["work"];


        //取得した一件を配列に追加する
        array_push($list, $rowData);
      }

      $this->dbDisconnect();
      return $list;
      }catch(PDOException $e){
        print('削除失敗。'.$e->getMessage());
        throw $e;
      }
  }

  public function deleteTODOTbl($userid,$todoid){
      try{
        $this->dbConnect();
        $stmt = $this->myPdo->prepare('UPDATE todolist SET delflag=1 WHERE userid = :id and todoid = :toid');
        $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
        $stmt->bindValue(':toid',$todoid,PDO::PARAM_STR);
        $stmt->execute();

        $this->dbDisconnect();
      }catch(PDOException $e){
        print('削除失敗。'.$e->getMessage());
        throw $e;
      }
  }

  public function updateTODOTbl($userid,$todoid,$doday,$work){
      try{
        $this->dbConnect();
        $stmt = $this->myPdo->prepare('UPDATE todolist SET doday=:doday,work=:work WHERE userid = :id and todoid = :toid');
        $stmt->bindValue(':doday',$doday,PDO::PARAM_STR);
        $stmt->bindValue(':work',$work,PDO::PARAM_STR);
        $stmt->bindValue(':id',$userid,PDO::PARAM_STR);
        $stmt->bindValue(':toid',$todoid,PDO::PARAM_STR);
        $stmt->execute();

        $this->dbDisconnect();
      }catch(PDOException $e){
        print('更新失敗。'.$e->getMessage());
        throw $e;
      }
  }

}
?>