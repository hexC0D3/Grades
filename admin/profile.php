<?php
require_once('../core/core.php');
$user = getCurrentUser();
global $ntdb;
if(!empty($_POST)){
	$data = getPostAjaxDataArray($_POST);
	if(isset($data['username']) && isset($data['color1']) && isset($data['color2'])){
		if(checkHexColor($data['color1'])&&checkHexColor($data['color2'])){
			$_SESSION['firstColor'] = $data['color1'];
			$_SESSION['secondColor'] = $data['color2'];
			echo $ntdb->updateInDatabase('users', array('username', 'color1', 'color2'), array($data['username'], $data['color1'], $data['color2']), 'id', $user['id']);
		}else{
			echo _("The hex color format isn't correct!");
		}
	}
	exit;
}
?>
<form id="updateProfile_form" action="/admin/profile.php" method="POST" callBackUrl="/admin/profile.php" refreshCSS="true">
	<h1><?php echo _("Profile Settings"); ?></h1>
	<input type="text" name="username" value="<?php echo $user['username']; ?>" placeholder="<?php echo _("Username");?>"/>
	<br/><br/>
	<h2><?php echo _("Some cool nice colors can be found"); ?> <a href="http://flatuicolors.com" target="_blank"><?php echo _("here"); ?></a></h2>
	<input type="text" name="color1" value="<?php echo $user['color1']; ?>" placeholder="<?php echo _("Color One (Hexcode, like #000 for black)");?>"/>
	<br/><br/>
	<input type="text" name="color2" value="<?php echo $user['color2']; ?>" placeholder="<?php echo _("Color Two (Hexcode, like #f00 for red)");?>"/>
	<br/><br/>
	<input type="submit" value="<?php echo _("Update Profile"); ?>" />
</form>