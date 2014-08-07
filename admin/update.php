<?php
require_once("../core/core.php");

if(isset($_POST['username'])&&isset($_POST['password'])){
	if(!empty($_POST['username'])&&!empty($_POST['password'])){
		if($_POST['username']==ADMIN_USER && verifyPassword($_POST['password'], ADMIN_HASH)){
			if(updateGrades()==true){
				die();
				header("Location: /");
			}else{
				nt_die(_("ERROR WHILE UPDATING"));
			}
		}
	}
}
function updateGrades(){
	if(($download=downloadUpdate())!=false){
		if(extractZipFile($download)){
			$di = new RecursiveDirectoryIterator(ADMIN_DIR."update/");
			foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
				if(strpos($filename, "/.")===false&&strpos($filename, "/..")===false){
					$name=explode('Grades-master/', $filename)[1];
// 					if(strpos(realpath(ROOT_DIR.$name), ROOT_DIR)!==false){ FIXME: dir traversal
						if(file_exists(ROOT_DIR.$name)){
							unlink(ROOT_DIR.$name);
						}
						rename($filename, ROOT_DIR.$name);
						if(file_exists($filename)){
							unlink($filename);
						}
// 					}
				}
			}
			unlink(ADMIN_DIR.'update.zip');
			return true;
		}
	}
	return false;
}
function extractZipFile($file){
	$zip = new ZipArchive();
	$res = $zip->open($file);
	if ($res === TRUE) {
		$zip->extractTo(ADMIN_DIR.'update/');
		$zip->close();
		return true;
	} else {
		return false;
	}
}
function downloadUpdate(){
	if(getLatestVersion(ADMIN_DIR."update.zip")){		
		return ADMIN_DIR."/update.zip";
	}else{
		return false;
	}
}
function getLatestVersion($path){
	return get_data('https://github.com/hexC0D3/Grades/archive/master.zip', $path);
}
function get_data($url, $path){
	$fp = fopen ($path, 'w+');
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_exec($ch);
	curl_close($ch);
	return true;
}
function normalizePath($path) {
	return array_reduce(explode('/', $path), create_function('$a, $b', '
         if($a === 0)
             $a = &quot;/&quot;;

         if($b === &quot;&quot; || $b === &quot;.&quot;)
             return $a;

         if($b === &quot;..&quot;)
             return dirname($a);

         return preg_replace(&quot;/\/+/&quot;, &quot;/&quot;, &quot;$a/$b&quot;);
     '), 0);
}
?>
<!DOCTYPE html>
<head>
	<title>Grades - <?php echo sanitizeOutput(_("Update")); ?></title>
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
	<h1>Grades - <?php echo sanitizeOutput(_("Update")); ?></h1>
	<form action="/admin/update.php" method="post">
		<input type="text" name="username" placeholder="<?php echo sanitizeOutput(_("Admin Username")); ?>" />
		<br/><br/>
		<input type="password" name="password" placeholder="<?php echo sanitizeOutput(_("Admin Password")); ?>" />
		<br/><br/>
		<input type="submit" value="<?php echo sanitizeOutput(_("Update Grades"));?>"/>
	</form>
	<div style="color:#e74c3c;font-size:200%;"><?php echo sanitizeOutput($error); ?></div>
</body>