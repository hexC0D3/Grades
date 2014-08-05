<?php
require_once("core/core.php");

removeInvalidTokens();
$error="";

if(isset($_GET)){
	if(!empty($_GET)){
		if(isset($_GET['token'])&&!empty($_GET['token'])&&isset($_GET['mail'])&&!empty($_GET['mail'])){
			global $ntdb;
			$token = $ntdb->getAllInformationFrom('mailTokens', 'tokenContent', $_GET['token']);
			if(is_array($token)){
				$token=$token[0];
				$tTime = strtotime($token['tokenExpireTime']);
				$curtime = date('Y-m-d H:i:s',time());
				if($token['tokenIP']==$_SERVER['REMOTE_ADDR']&&$token['tokenMail']==$_GET['mail']&&$curtime < $tTime){
					if($token['tokenType']==0){
						/** Register User **/
						$exp = explode(";", $token['tokenMeta']);
						if(count($exp)==8){
							$ntdb->addUser($exp[0], $exp[1], $exp[2], $exp[3], $exp[4], $exp[5], $exp[6], $exp[7]);
							$ntdb->removeFromDatabase('mailTokens', 'tokenContent', $_GET['token']);
							header("Location: /?skip=true#login");
						}
					}else if($token['tokenType']==1){
						/** Reset Password **/
						if(isset($_POST)&&!empty($_POST)){
							if(isset($_POST['pw'])&&isset($_POST['pw_verify'])&&!empty($_POST['pw'])&&!empty($_POST['pw_verify'])){
								if($_POST['pw']==$_POST['pw_verify']){
									$pw = hashPassword($_POST['pw']);
									$ntdb->updateInDatabase('users', array('password'), array($pw), 'id', $token['tokenMeta']);
									$ntdb->removeFromDatabase('mailTokens', 'tokenContent', $_GET['token']);
									header("Location: /?skip=true#login");
									exit;
								}else{
									$error=sanitizeOutput(_("The entered password don't match!"));
								}
							}else{
								$error=sanitizeOutput(_("You have to enter a new password!"));
							}
						}
						?>
						<!DOCTYPE html>
						<head>
							<title>Grades - <?php echo sanitizeOutput(_("Password Reset")); ?></title>
							<meta name="viewport" content="user-scalable=no" />
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
							<h1>Grades - <?php echo sanitizeOutput(_("Password Reset")); ?></h1>
							<form action="/mail.php?token=<?php echo $_GET['token']."&mail=".$_GET['mail']; ?>" method="post">
								<input type="password" name="pw" placeholder="<?php echo sanitizeOutput(_("New Password")); ?>" />
								<br/><br/>
								<input type="password" name="pw_verify" placeholder="<?php echo sanitizeOutput(_("Confirm New Password")); ?>" />
								<br/><br/>
								<input type="submit" value="<?php echo sanitizeOutput(_("Setup"));?>"/>
							</form>
							<div style="color:#e74c3c;font-size:200%;"><?php echo $error; ?></div>
						</body>
					<?php }
				}else{
					die(sanitizeOutput(_("Please re-send the mail, we weren't able to verify, that this is your account!")));
				}
			}
		}
	}
}
function removeInvalidTokens(){
	global $ntdb;
	$arrays = $ntdb->getAllInformationFromTable('mailTokens');
	foreach($arrays as $array){
		if(date('Y-m-d H:i:s',time()) > strtotime($array['tokenExpireTime'])){
			$ntdb->removeFromDatabase('mailTokens', 'tokenContent', $array['tokenContent']);
		}
	}
}
die(sanitizeOutput(_("Something went wrong! If you believe this is an error please report this to me@tyratox.ch")));
?>