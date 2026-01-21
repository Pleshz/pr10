<?php
	session_start();
	include("../settings/connect_datebase.php");
	require_once("../libs/autoload.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		if(isset($_POST["g-recaptcha-response"]) == false) {
			echo "Не прошли капчу";
			exit;
		}
		$Secret = "6Ld3hVEsAAAAAK_afY6r-oTeFFdrDD29KRZELCjW";
		$Recaptcha = new \ReCaptcha\ReCaptcha($Secret);

		$Response = $Recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER['REMOTE_ADDR']);

		if($Response->isSuccess()) {
			$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
		
			$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
			$user_new = $query_user->fetch_row();
			$id = $user_new[0];

			if($id != -1) $_SESSION['user'] = $id; 
			echo $id;
		} else {
			echo "Пользователь не распознан";
			exit;
		}
			
	}
?>