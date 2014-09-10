<?php
global $ntdb;

if(!empty($_POST['resetPW'])){
	$resetPW = $_POST['resetPW'];
	//reset password
	if(!filter_var($resetPW, FILTER_VALIDATE_EMAIL)){
		//username
		if($ntdb->isInDatabase('users', 'username', $resetPW)){
			$ntdb->resetPassword($ntdb->getAllInformationFrom('users', 'username', $resetPW)[0]);
		}else{
			$error=sanitizeOutput(_("User not found!"));
		}
	}else{
		//mail
		if($ntdb->isInDatabase('users', 'mail', $resetPW)){
			$ntdb->resetPassword($ntdb->getAllInformationFrom('users', 'mail', $resetPW)[0]);
		}else{
			$error=sanitizeOutput(_("User not found!"));
		}
	}
	$error=sanitizeOutput(_("Check your mail account."));
}else if(!empty($_POST['passwordVerify'])){
	if(!empty($_POST['password']) && !empty($_POST['username']) && !empty($_POST['mail'])){
		if($_POST['passwordVerify']==$_POST['password']){
			global $ntdb;
			if($ntdb->registerUser($_POST['username'], $_POST['password'], $_POST['mail'], -1, -1, "", $_SESSION['firstColor'], $_SESSION['secondColor'])){
				$error=sanitizeOutput(_("Check your mail account."));
			}else{
				$error=sanitizeOutput(_("Your mail or your username is already in use!"));
			}
		}else{
			$error=sanitizeOutput(_("The entered passwords aren't equal!"));
		}
	}else{
		$error = sanitizeOutput(_("Please fill in all fields!"));
	}
}else if(isset($_POST['username'])&&!empty($_POST['username'])&&isset($_POST['password'])&&!empty($_POST['password'])){
	if(tryToLogIn($_POST['username'], $_POST['password'])){
		$user = getCurrentUser();
		$user['color1']="#2c3e50";
		$user['color2']="#34495e";
		header("Location: /");
	}else{
		$error = sanitizeOutput(_("Please check your login!"));
	}
}else if(!empty($_POST)){
	$error = sanitizeOutput(_("Please fill in all fields!"));
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Grades</title>
		<meta name="viewport" content="user-scalable=no" />
		<link rel="icon" type="image/png" href="/img/favicon.png">
		<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<style type="text/css">
		<?php
			require_once(CORE_DIR.'styles/welcome.css.php');
			$color="bg-midnightblue";
		?>
		</style>
	</head>
	<body>
	<header>
		<div id="navbar" class="<?php echo $color; ?> shadow">
			<ul>
				<div id="navbar-left">
					<a href="#grades"><?php include("img/icon_bar.svg");?></a>
					<span class="color-white noselect">Grades</span>
				</div>	
		</div>
	</header>
	
	<div id="page" class="vis">
		<ul class="nav-menu">
			<li>
				<a href="#register">
					<i class="nav-icon fa fa-lock"></i>
					<div class="nav-content">
						<h2 class="nav-main"><?php echo sanitizeOutput(_("Register")); ?></h2>
            		</div>
        		</a>
			</li>
			<li>
				<a href="#login">
					<i class="nav-icon fa fa-unlock-alt"></i>
					<div class="nav-content">
						<h2 class="nav-main"><?php echo sanitizeOutput(_("Login")); ?></h2>
            		</div>
        		</a>
			</li>
			<li>
				<a href="#resetPW">
					<i class="nav-icon fa fa-folder"></i>
					<div class="nav-content">
						<h2 class="nav-main"><?php echo sanitizeOutput(_("Reset your Password")); ?></h2>
            		</div>
        		</a>
			</li>
			
		</ul>
		<div class="clear"></div>
		<div id="error"><?php if(isset($error)&&!empty($error)){echo $error;}?></div>
	</div>

	<div id="login" class="window">
		<div class="windowContent">
			<h1><?php echo sanitizeOutput(_("Login")); ?></h1>
			<br/>
			<form action="/" method="POST">
				<input type="text" name="username" placeholder="<?php echo sanitizeOutput(_("Username")); ?>"/>
				<br/><br/>
				<input type="password" name="password" placeholder="<?php echo sanitizeOutput(_("Password")); ?>"/>
				<br/><br/>
				<input type="submit" value="<?php echo sanitizeOutput(_("Log In")); ?>"/>
			</form>
		</div>
	</div>
	<div id="register" class="window">
		<div class="windowContent">
			<h1><?php echo sanitizeOutput(_("Register")); ?></h1>
			<br/>
			<form action="/" method="POST">
				<input type="text" name="username" placeholder="<?php echo sanitizeOutput(_("Username")); ?>"/>
				<br/><br/>
				<input type="password" name="password" placeholder="<?php echo sanitizeOutput(_("Password")); ?>"/>
				<br/><br/>
				<input type="password" name="passwordVerify" placeholder="<?php echo sanitizeOutput(_("Verify Password")); ?>"/>
				<br/><br/>
				<input type="text" name="mail" placeholder="<?php echo sanitizeOutput(_("Mail")); ?>"/>
				<br/><br/>
				<input type="submit" value="<?php echo sanitizeOutput(_("Register")); ?>"/>
			</form>
		</div>
	</div>
	<div id="resetPW" class="window">
		<div class="windowContent">
			<h1><?php echo sanitizeOutput(_("Reset Password")); ?></h1>
			<br/>
			<form action="/" method="POST">
				<input type="text" name="resetPW" placeholder="<?php echo sanitizeOutput(_("Username or E-Mail")); ?>"/>
				<br/><br/>
				<input type="submit" value="<?php echo sanitizeOutput(_("Reset your password")); ?>"/>
			</form>
		</div>
	</div>
	<div id="overlay"></div>
	<div id="close"><a href="#">X</a></div>
	</body>
</html>