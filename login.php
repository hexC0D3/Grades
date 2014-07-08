<?php 
require_once("core/core.php");
if(isset($_GET['logout'])){
	if($_GET['logout'] == true || $_GET['logout'] == "true"){
		if(isset($_COOKIE['_loginToken'])) {
			unset($_COOKIE['_loginToken']);
			setcookie('_loginToken', '', time() - 3600);
		}
	}	
}
if(isUserLoggedIn()){
	header("Location: /");
}else{
	if(!empty($_POST['resetPW'])){
		$resetPW = $_POST['resetPW'];
		//reset password
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			//username
			
		}else{
			//mail
			
  		}
	}else if(!empty($_POST['passwordVerify'])){
		if(!empty($_POST['password']) && !empty($_POST['username']) && !empty($_POST['mail'])){
			if($_POST['passwordVerify']==$_POST['password']){
				//register
				global $ntdb;
				echo $ntdb->registerUser($_POST['username'], $_POST['password'], $_POST['mail'], -1, -1, "");
			}
		}
	}else if(!empty($_POST['username']) && !empty($_POST['password'])){
		if(tryToLogIn($_POST['username'], $_POST['password'])){
			header("Location: /");
		}else{
			$loginError = true;
		}
	}
	
?>
<!DOCTYPE html>
<head>
	<title><?php echo _("Login");?> - Grades</title>
	<style type="text/css"><?php require_once(CORE_DIR . 'styles/login.css'); ?></style>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<header>

</header>
<body>
	<div id="container">
		<div id="topBar">
			<i id="lock" class="fa fa-lock"></i>
			<div id="title">Grades</div>
		</div>
		<div id="login">
			<form  action="/login.php#login" method="POST">
				<input type="text" name="username" placeholder="<?php echo htmlentities(_("Username"));?>" class="username login inputField" value="<?php if(isset($_POST['username'])){echo $_POST['username'];} ?>"></input>
				<i class="fa fa-user iconBeforeInput"></i>
				<br/>
				<input type="password" name="password" placeholder="<?php echo htmlentities(_("Password")); ?>" class="password login inputField"></input>
				<i class="fa fa-unlock iconBeforeInput"></i>
				<br/>
				<input type="submit" value="<?php echo htmlentities(_("Sign In")); ?>" />
				<?php #if($loginError){echo "<span class='error'>ERROR: Your login details aren't correct!</span>";} ?>
			</form>
		</div>
		<div id="register">
			<form  action="/login.php#register" method="POST">
				<input type="text" name="username" placeholder="<?php echo htmlentities(_("Username"));?>" class="username register inputField" value="<?php if(isset($_POST['username'])){echo $_POST['username'];} ?>"></input>
				<i class="fa fa-user iconBeforeInput"></i>
				<br/>
				<input type="password" name="password" placeholder=<?php echo htmlentities(_("Password"));?> class="password register inputField"></input>
				<i class="fa fa-unlock iconBeforeInput"></i>
				<br/>
				<input type="password" name="passwordVerify" placeholder="<?php echo htmlentities(_("Verify Password"));?>" id="passwordVerify" class="register inputField"></input>
				<i class="fa fa-unlock iconBeforeInput"></i>
				<br/>
				<input type=text name="mail" placeholder="<?php echo htmlentities(_("E-Mail"));?>" id="mail" class="register inputField" value="<?php if(isset($_POST['mail'])){echo $_POST['mail'];} ?>"></input>
				<i class="fa fa-paper-plane iconBeforeInput"></i>
				<br/>
				<input type="submit" value="<?php echo htmlentities(_("Register"));?>" />
			</form>
		</div>
		<div id="resetPW">
			<form  action="/login.php#resetPW" method="POST">
				<input type=text name="resetPW" placeholder="<?php echo htmlentities(_("Username or E-Mail"));?>" class="register inputField"></input>
				<i class="fa fa-user iconBeforeInput"></i>
				<br/>
				<input type="submit" value="<?php echo htmlentities(_("Send me a mail"));?>" />
			</form>
		</div>
		<div id="loginList">
			<span><a href="#login"><?php echo _("Login");?></a></span><span><a href="#register"><?php echo htmlentities(_("Register"));?></a></span><span><a href="#resetPW"><?php echo htmlentities(_("Reset password"));?></a></span>
		</div>
	</div>
</body>
<footer>

</footer>
<?php }
?>