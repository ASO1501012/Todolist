<?php

	session_start();

	require_once 'DBManager.php';
	class UserManager{
		public function registUser($userid,$pass,$username){
			$dbm = new DBManager();
			$listcnt = $dbm->getUserInfoTblByUserId($userid);
			$listlength = count($listcnt);
			if($listlength == 0){
				$hash = $this->passwordHash($pass);
				$dbm->insertuserInfo($userid,$hash,$username);
				header('Location: RegistComp.php');
			}else{
				echo "登録できませんでした。";
				header('Location: RegistMiss.php');
			}
		}

		public function logincheck($userid,$pass){
			$dbm = new DBManager();
			$um = new UserManager();
			$listcnt = $dbm->getUserInfoTblByUserId($userid);
			$listlength = count($listcnt);
			foreach($listcnt as $list){
				$username = $list->username;
			}

			if($listlength >= 1){
				$passcheck = $this->passwordCheck($pass);
				if($passcheck == true){
					$_SESSION['userid'] = $userid;
					$_SESSION['username'] = $username;

					session_regenerate_id();
					header('Location:ToDoList.php');
				}else{
					//パスワードが不一致
					header('Location: Login.php');
				}
			}else{
				//ユーザーIDが不一致
				header('Location: Login.php');
			}
		}

		public function passwordCheck($pass){
			$userhash = $this->passwordHash($pass);
			$flag = password_verify($pass,$userhash);
			return $flag;
		}

		public function passwordHash($pass){
			$hash = password_hash($pass,PASSWORD_DEFAULT);
			return $hash;
		}
	}
?>
		