<?php
require_once("../core/core.php");

if(file_exists("../core/config.php")){
	die(sanitizeOutput(_("The config file already exists! Please delete this file! You can also delete the config file to redo the setup.")));
}
$error="";
if(isset($_POST)&&!empty($_POST)){
	if(isset($_POST['db_name'])&&isset($_POST['db_user'])&&isset($_POST['db_pw'])&&isset($_POST['db_host'])&&isset($_POST['admin_user'])&&isset($_POST['admin_pw'])){
		if(!empty($_POST['db_name'])&&!empty($_POST['db_user'])&&!empty($_POST['db_pw'])&&!empty($_POST['db_host'])&&!empty($_POST['admin_user'])&&!empty($_POST['admin_pw'])){
			if($_POST['admin_pw']==$_POST['admin_pw_confirm']){
				$db = new mysqli($_POST['db_host'], $_POST['db_user'], $_POST['db_pw'], $_POST['db_name']);
				if($db->connect_errno){
					$error=sanitizeOutput(_("Please check your database data"));
				}else{
					$contents = file_get_contents("../core/config-sample.php");
					$contents = str_replace("###DB_NAME###", $_POST['db_name'], $contents);
					$contents = str_replace("###DB_USER###", $_POST['db_user'], $contents);
					$contents = str_replace("###DB_PASSWORD###", $_POST['db_pw'], $contents);
					$contents = str_replace("###DB_HOST###", $_POST['db_host'], $contents);
					file_put_contents("../core/config.php", $contents);
					require(CORE_DIR."config.php");
					$contents = str_replace("###ADMIN_USER###", $_POST['admin_user'], $contents);
					$contents = str_replace("###ADMIN_HASH###", hashPassword($_POST['admin_pw']), $contents);
					file_put_contents("../core/config.php", $contents);
					setupTables();
					/** Deletes setup file
					 unlink(ADMIN_DIR.'setup.php');**/
					header("Location: /");
				}	
			}else{
				$error=sanitizeOutput(_("The 2 entered passwords don't match!"));
			}
		}
	}
}
?>
<!DOCTYPE html>
<head>
	<title>Grades - <?php echo sanitizeOutput(_("Setup")); ?></title>
	<meta name="viewport" content="user-scalable=no" />
	<link rel="icon" type="image/png" href="/img/favicon.png">
	<style type="text/css">
	<?php require_once('../ui/style.css.php'); ?>
	body{
		margin-top:2%;
		margin-left:2%;
	}
	</style>
	<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
</head>
<body>
	<h1>Grades - <?php echo sanitizeOutput(_("Setup")); ?></h1>
	<form action="/admin/setup.php" method="post">
		<input type="text" name="db_name" placeholder="<?php echo sanitizeOutput(_("Database Name")); ?>" />
		<br/><br/>
		<input type="text" name="db_user" placeholder="<?php echo sanitizeOutput(_("Database Username")); ?>" />
		<br/><br/>
		<input type="password" name="db_pw" placeholder="<?php echo sanitizeOutput(_("Database Password")); ?>" />
		<br/><br/>
		<input type="text" name="db_host" placeholder="<?php echo sanitizeOutput(_("Database Host, 'localhost' in most cases")); ?>" />
		<br/><br/>
		<input type="text" name="admin_user" placeholder="<?php echo sanitizeOutput(_("Admin Username")); ?>" />
		<br/><br/>
		<input type="password" name="admin_pw" placeholder="<?php echo sanitizeOutput(_("Admin Password")); ?>" />
		<br/><br/>
		<input type="password" name="admin_pw_confirm" placeholder="<?php echo sanitizeOutput(_("Confirm Admin Password")); ?>" />
		<br/><br/>
		<input type="submit" value="<?php echo sanitizeOutput(_("Setup"));?>"/>
	</form>
	<div style="color:#e74c3c;font-size:200%;"><?php echo $error; ?></div>
</body>