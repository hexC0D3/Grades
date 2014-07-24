<?php
	session_start();
	if(isset($_SESSION['_loginToken'])){
		session_destroy();
	}
	header("Location: /?skip=true");
?>