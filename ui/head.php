<?php
require_once("../core/core.php");

function checkHead($dataCallBack, $myCallBack, $listCallBack, $createCallBack, $editCallBack, $defaultCallBack){
	if(isUserLoggedIn()==false){
		header("Location: /");
	}
	if(!empty($_POST)){
		$data = getPostAjaxDataArray($_POST);
		call_user_func($dataCallBack, $data);
		die();
	}
	
	if($_GET['ajax'] != "true" || $_GET['ajax'] != true){
		require_once(UI_DIR."header.php");
	}
	
	if(isset($_GET['p'])){
		if($_GET['p']=="my"){
			call_user_func($myCallBack);
		}else if($_GET['p']=="list"){
			call_user_func($listCallBack);
		}else if($_GET['p']=="create" || $_GET['p']=="add"){
			call_user_func($createCallBack, $_GET);
		}else if($_GET['p']=="edit" && !empty($_GET['id'])){
			call_user_func($editCallBack, $_GET['id']);
		}else{
			call_user_func($defaultCallBack);
		}
	}else{
		call_user_func($defaultCallBack);
	}
}
?>