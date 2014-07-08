<?php 
require_once('core/core.php');
if(isUserLoggedIn()){
	if(empty($_GET)){
		require_once(UI_DIR . 'header.php');
		require_once(UI_DIR . 'body.php');
		require_once(UI_DIR . 'footer.php');
	}else if($_GET['ajax'] == true || $_GET['ajax'] == "true"){
		require_once(UI_DIR . 'body.php');
	}
}else{
	header("Location: /login.php#login");
}
?>