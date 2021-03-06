<?php 

if(file_exists(CORE_DIR.'config.php')){
	require_once(CORE_DIR.'config.php');#get db access data
	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if($db->connect_errno){
		nt_die("Not able to connect to db!");
	}else{
		global $ntdb;
		$ntdb = new NTDB($db);
	}	
}

class NTDB{
	private $mysqli;
	/** Init Database Object **/
	public function NTDB(){
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	}
	/** Get table from the database **/
	function getAllInformationFromTable($tableName){
		if ($stmt = $this->mysqli->prepare("SELECT * FROM ".$tableName)) {
			$stmt->execute();

			$meta = $stmt->result_metadata();
			while ($field = $meta->fetch_field()) {
				$parameters[] = &$row[$field->name];
			}
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			while ($stmt->fetch()) {
				foreach($row as $key => $val) {
					$x[$key] = $val;
				}
				$results[] = $x;
			}
			$stmt->close();
			return $results;
		}
		return false;
	}
	/** Get row(s) from the database **/
	function getAllInformationFrom($tableName, $arrayOfKeys, $arrayOfValues){
		if(is_string($arrayOfKeys)){/*Fallback*/
			$arrayOfKeys=array($arrayOfKeys);
			$arrayOfValues=array($arrayOfValues);
		}
		if(count($arrayOfKeys)==count($arrayOfValues)){
			$statement = " WHERE ".$arrayOfKeys[0]."=?";
			for($i=1;$i<count($arrayOfKeys);$i++){
				$statement.=" AND ".$arrayOfKeys[$i]."=?";
			}
			$types="";
			foreach($arrayOfValues as $key => $value){
				if(is_string($value)){
					$types.="s";
				}else if(is_int($value)){
					$types.="i";
				}else if(is_bool($value)){
					$arrayOfValues[$key]=(int)$value;
					$types.="i";
				}else{
					return false;
				}
			}
			$values = array();
			for($i = 0;$i<count($arrayOfValues);$i++){
				$values[$i] = &$arrayOfValues[$i];
			}
			if ($stmt = $this->mysqli->prepare("SELECT * FROM ".$tableName.$statement)) {
				call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $values));
				$stmt->execute();
				
				$meta = $stmt->result_metadata();
				while ($field = $meta->fetch_field()) {
					$parameters[] = &$row[$field->name];
				}
				call_user_func_array(array($stmt, 'bind_result'), $parameters);
				while ($stmt->fetch()) {
					foreach($row as $key => $val) {
						$x[$key] = $val;
					}
					$results[] = $x;
				}
				$stmt->close();
				if(isset($results)){
					return $results;
				}else{
					return null;
				}
			}
		}
		return false;
	}
	/** Remove something from the database **/
	function removeFromDatabase($tableName, $arrayOfKeys, $arrayOfValues){
		if(is_string($arrayOfKeys)){/*Fallback*/
			$arrayOfKeys=array($arrayOfKeys);
			$arrayOfValues=array($arrayOfValues);
		}
		if(count($arrayOfKeys)==count($arrayOfValues)){
			$statement = " WHERE ".$arrayOfKeys[0]."=?";
			for($i=1;$i<count($arrayOfKeys);$i++){
				$statement.=" AND ".$arrayOfKeys[$i]."=?";
			}
			$types="";
			foreach($arrayOfValues as $key => $value){
				if(is_string($value)){
					$types.="s";
				}else if(is_int($value)){
					$types.="i";
				}else if(is_bool($value)){
					$arrayOfValues[$key]=(int)$value;
					$types.="i";
				}else{
					return false;
				}
			}
			$values = array();
			for($i = 0;$i<count($arrayOfValues);$i++){
				$values[$i] = &$arrayOfValues[$i];
			}
			if ($stmt = $this->mysqli->prepare("DELETE FROM ".$tableName.$statement)) {
				call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $values));
				$stmt->execute();
				$stmt->close();
				return true;
			}
		}
		return false;
	}
	/** Update something in the database **/
	function updateInDatabase($tableName, $keysToUpdate, $valuesToUpdate, $keyToSearch, $valueToSearch){
		if((count($keysToUpdate)==count($valuesToUpdate)) && count($valuesToUpdate) >= 1){
			$set = "SET ".$keysToUpdate[0]."=?";
			for($i=1;$i<count($valuesToUpdate);$i++){
				$set.=", ".$keysToUpdate[$i]." =?";
			}

			$types = "";

			foreach($valuesToUpdate as $key => $val){
				if(is_string($val)){
					$types.="s";
					continue;
				}else if(is_int($val)){
					$types.="i";
					continue;
				}else if(is_bool($val)){
					$types.="i";
					$valuesToUpdate[$key] = (int)$val;
					continue;
				}else{
					return false;
				}
			}

			if(is_string($valueToSearch)){
				$types.="s";
			}else if(is_int($valueToSearch)){
				$types.="i";
			}else if(is_bool($valueToSearch)){
				$types.="i";
				$valueToSearch = (int)$valueToSearch;
			}

			$values = array();
			for($i = 0;$i<count($valuesToUpdate);$i++){
				$values[$i] = &$valuesToUpdate[$i];
			}
			if ($stmt = $this->mysqli->prepare("UPDATE ".$tableName ." ". $set . " WHERE ".$keyToSearch." = ?")) {
				call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $values, array(&$valueToSearch)));
				$stmt->execute();
				$stmt->close();
				return true;
			}
		}
		return false;
	}
	/** Resets the password of a user **/
	function resetPassword($user){
		$mail=$user['mail'];
		$uuid = uniqid('', true);
		$expireTime = time() + 7200;/* +2h */
		$bool=$this->addToDatabase('mailTokens', array('tokenContent','tokenMeta','tokenMail','tokenType','tokenIP','tokenExpireTime'), array($uuid,$user['id'],$mail,1,$_SERVER['REMOTE_ADDR'],date('Y-m-d H:i:s',$expireTime)));
		if(sendMail($mail, MAIL_FROM, _("Grades: password reset"), sanitizeOutput(_("The reset of your password was requested! If you didn't request it, ignore this mail. If you want to reset your password click"))." <a href='https://".$_SERVER["SERVER_NAME"]."/mail.php?token=".$uuid."&mail=".$mail."'>".sanitizeOutput(_("here"))."</a>.")){
			if($bool){
				return true;
			}
		}
		return false;
	}
	/** Adds a user to the databse **/
	function addUser($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2){
		if(!$this->user_exists($username, $mail)){
			return $this->addToDatabase('users', array('username', 'password', 'mail', 'classID', 'schoolID', 'subjectIDs', 'color1', 'color2'), array($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2));
		}else{
			return false;
		}
	}
	/** Register a new user **/
	function registerUser($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2){
		global $ntdb;
		if(!$this->user_exists($username, $mail)){
			$password = hashPassword($password);
			$uuid = uniqid('', true);
			$expireTime = time() + 7200;/* +2h */
			$meta=$username.";".$password.";".$mail.";".$classID.";".$schoolID.";".$subjectIDs.";".$color1.";".$color2;
			
			$bool=$ntdb->addToDatabase('mailTokens', array('tokenContent','tokenMeta','tokenMail','tokenType','tokenIP','tokenExpireTime'), array($uuid,$meta,$mail,0,$_SERVER['REMOTE_ADDR'],date('Y-m-d H:i:s',$expireTime)));
			$msg = _("Hi").", \r\n"._("Click"). " <a href='https://".$_SERVER["SERVER_NAME"]."/mail.php?token=".$uuid."&mail=".$mail."'>".sanitizeOutput(_("here"))."</a> ".sanitizeOutput(_("to verify your Grades account."));
			if(sendMail($mail, MAIL_FROM, _("Grades: registration"), $msg)&&$bool){
				return true;
			}else{
				echo sanitizeOutput(_("Error while sending mail! Please report this to me@tyratox.ch"));
				return false;
			}	
		}else{
			return false;
		}
	}
	/** Checks if a user exists **/
	function user_exists($username, $mail){
		$array = $this->getAllInformationFrom('users', 'username', $username);
		if(is_array($array)&&!empty($array)&&isset($array[0])&&is_array($array[0])&&!empty($array[0])){
			return true;
		}else{
			$array = $this->getAllInformationFrom('users', 'mail', $mail);
			if(is_array($array)&&!empty($array)&&isset($array[0])&&is_array($array[0])&&!empty($array[0])){
				return true;
			}else{
				return false;
			}
		}
	}
	/** Adds a subject from user **/
	function addSubjectToUser($userID, $subjectID){
		$currentSubjects = explode(",", $this->getAllInformationFrom('users', 'id', $userID)[0]['subjectIDs']);
		if(($key = array_search($subjectID, $currentSubjects)) !== false) {
			return false;
		}else{
			$currentSubjects[] = $subjectID;
			$newSubjects = implode(",", $currentSubjects);
			return $this->updateInDatabase('users', array('subjectIDs'), array($newSubjects), 'id', $userID);
		}
	}
	/** Remove a subject from user **/
	function removeSubjectFromUser($userID, $subjectID){
		$currentSubjects = explode(",", $this->getAllInformationFrom('users', 'id', $userID)[0]['subjectIDs']);
		if(($key = array_search($subjectID, $currentSubjects)) !== false) {
			unset($currentSubjects[$key]);
			$newSubjects = implode(",", $currentSubjects);
			return $this->updateInDatabase('users', array('subjectIDs'), array($newSubjects), 'id', $userID);
		}else{
			return false;
		}
	}
	/** Checks if the user has a specific subject **/
	function doesUserHaveSubject($userID, $subjectID){
		$currentSubjects = explode(",", $this->getAllInformationFrom('users', 'id', $userID)[0]['subjectIDs']);
		if(($key = array_search($subjectID, $currentSubjects)) !== false) {
			return true;
		}else{
			return false;
		}
	}
	/** Remove all subjects from user **/
	function removeAllSubjectsFromUser($userID){
		return $this->updateInDatabase('users', array('subjectIDs'), array(''), 'id', $userID);
	}
	/** Check if something already exists in the database **/
	function isInDatabase($tableName, $key, $value){
		$x = $this->getAllInformationFrom($tableName, $key, $value);
		if($x[0]!=null){
			return true;
		}else{
			return false;
		}
	}
	/** Adds something to the database **/
	function addToDatabase($tableName, $arrayOfKeys, $arrayOfValues){
		if((count($arrayOfKeys)==count($arrayOfValues)) && count($arrayOfKeys)>0){
			$keys = implode(", ", $arrayOfKeys);
			$qMarks = "?";
			for($i = 1;$i<(count($arrayOfKeys));$i++){
				$qMarks.=", ?";
			}

			$types = "";

			foreach($arrayOfValues as $key => $val){
				if(is_string($val)){
					$types.="s";
					continue;
				}else if(is_int($val)){
					$types.="i";
					continue;
				}else if(is_bool($val)){
					$types.="i";
					$arrayOfValues[$key] = (int)$val;
					continue;
				}else{
					return false;
				}
			}

			$values = array();
			for($i = 0;$i<count($arrayOfValues);$i++){
				$values[$i] = &$arrayOfValues[$i];
			}
			if ($stmt = $this->mysqli->prepare("INSERT INTO ".$tableName." (".$keys.") VALUES (".$qMarks.")")) {
				call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $values));
				$stmt->execute();
				$stmt->close();
				return true;
			}
			return false;
		}else{
			return false;
		}
	}
	/** Wipe all data (grades) of a user **/
	function wipeDataOfUser($userID){
		$this->removeFromDatabase('grades', 'userID', $userID);
	}
	/** Removes a user from a school and sets a new admin **/
	function safelyRemoveUserFromSchool($userID){
		$this->wipeDataOfUser($userID);
		echo $this->updateInDatabase('users', array('schoolID'), array('-1'), 'id', $userID);
		if($this->isInDatabase('schools', 'adminID', $userID)){
			$schools=$this->getAllInformationFrom('schools', 'adminID', $userID);
			foreach($schools as $school){/*just in case*/
				$u = $this->getRandomUserOfSchool($school['id']);
				$this->setNewSchoolAdmin($u['id'], $class['id']);
			}
		}
	}
	/** Removes a user from a class and sets a new admin **/
	function safelyRemoveUserFromClass($userID){
		$this->wipeDataOfUser($userID);
		echo $this->updateInDatabase('users', array('classID'), array('-1'), 'id', $userID);
		if($this->isInDatabase('classes', 'adminID', $userID)){
			$classes=$this->getAllInformationFrom('classes', 'adminID', $userID);
			foreach($classes as $class){/*just in case*/
				$u = $this->getRandomUserOfClass($class['id']);
				$this->setNewClassAdmin($u['id'], $class['id']);
			}
		}
	}
	/** Sets a new class admin and notifies every member **/
	function setNewClassAdmin($userID, $classID){
		$this->updateInDatabase('classes', array('adminID'), array($userID), 'id', $classID);
		$user = $this->getAllInformationFrom('users', 'id', $userID)[0];
		$this->sendMailToClass($classID, _("Grades: new class admin"), $user['username'].sanitizeOutput(_(" is your new class admin."))."\r\n".sanitizeOutput(_("Your Grades Team")));
	}
	/** Sets a new school admin and notifies every member **/
	function setNewSchoolAdmin($userID, $schoolID){
		$this->updateInDatabase('schools', array('adminID'), array($userID), 'id', $schoolID);
		$user = $this->getAllInformationFrom('users', 'id', $userID)[0];
		$this->sendMailToSchool($schoolID, _("Grades: new school admin"), $user['username'].sanitizeOutput(_(" is your new school admin."))."\r\n".sanitizeOutput(_("Your Grades Team")));
	}
	/** Sends a mail to the whole school (with name as prefix) **/
	function sendMailToSchool($schoolID, $subject, $message){
		$users = $this->getAllInformationFrom('users', 'schoolID', $schoolID);
		foreach($users as $user){
			sendMail($user['mail'], MAIL_FROM, $subject, "Hi " . $user['username']."<br/>".$message);
		}
	}
	/** Sends a mail to the whole class (with name as prefix) **/
	function sendMailToClass($classID, $subject, $message){
		$users = $this->getAllInformationFrom('users', 'classID', $classID);
		foreach($users as $user){
			sendMail($user['mail'], MAIL_FROM, $subject, "Hi " . $user['username']."<br/>".$message);
		}
		return true;
	}
	/** Get random user of class **/
	function getRandomUserOfClass($classID){
		$users = $this->getAllInformationFrom('users', 'classID', $classID);
		$rand_keys = array_rand($users);
		return $users[$rand_keys];
	}
	/** Get random user of school **/
	function getRandomUserOfSchool($schoolID){
		$users = $this->getAllInformationFrom('users', 'schoolID', $schoolID);
		$rand_keys = array_rand($users);
		return $users[$rand_keys];
	}
	/** Get Average mark of test **/
	function getAverageMark($testID){
		$grades = $this->getAllInformationFrom('grades', 'testID', $testID);
		if(!empty($grades)&&is_array($grades)){
			$marks = array();
			foreach($grades as $grade){
				$marks[] = $grade['mark'];
			}
			if(count($marks)>0){
				return (array_sum($marks)/count($marks));
			}
		}
		return "";
	}
	/** Try to login user **/
	function tryToLogIn($username, $password){
		$array = $this->getAllInformationFrom('users', 'username', $username);
		foreach($array as $user){
			if(verifyPassword($password, $user['password'])){
				return $user['id'];
			}
		}
		return false;
	}
	/** Generate token */
	function generateToken($id){
		$this->removeFromDatabase('tokens', 'tokenUser', $id);
		$uuid = uniqid('', true);

		$expireTime = time() + 7200;
		$eDate = date('Y-m-d H:i:s',$expireTime);
		$ip = $_SERVER['REMOTE_ADDR'];
		$arrayOfKeys = array('tokenContent', 'tokenUser', 'tokenIP', 'tokenExpireTime');
		$arrayOfValues = array($uuid, $id, $ip, $eDate);
		$this->addToDatabase('tokens', $arrayOfKeys, $arrayOfValues);

		return $uuid;
	}
	/** Checks if token is valid */
	function verifyToken($token){
		$array = $this->getAllInformationFrom('tokens', 'tokenContent', $token)[0];
		if(!empty($array)){
			$ip = $array['tokenIP'];
			$tTime = strtotime($array['tokenExpireTime']);
			$curtime = date('Y-m-d H:i:s',time());
			if($ip == $_SERVER['REMOTE_ADDR'] && $curtime < $tTime){
				return true;
			}
		}

		return false;
	}
	/** Gets user from token */
	function getTokenUser($token){
		$array = $this->getAllInformationFrom('tokens', 'tokenContent', $token)[0];
		if(!empty($array)){
			$ip = $array['tokenIP'];
			$tTime = strtotime($array['tokenExpireTime']);
			$curtime = date('Y-m-d H:i:s',time());

			if($ip == $_SERVER['REMOTE_ADDR'] && $curtime < $tTime){
				return $array['tokenUser'];
			}
		}

		return false;
	}
	function getLastGradesOfCurrentUserWithTimeStamp($numberOfGrades){
		$user = getCurrentUser();
		$array = $this->getAllInformationFrom('tests', 'classID', $user['classID']);
		if(empty($array)){return array();};
		usort($array, 'compareReversedByTimestamp');
		if(count($array)<$numberOfGrades){
			$numberOfGrades=0;
		}else{
			$numberOfGrades = count($array)-$numberOfGrades;
		}
		$tests = array_slice($array, $numberOfGrades);
		$newArray = array();
		foreach($tests as $test){
			$mark = $this->getAllInformationFrom('grades', array('testID', 'userID'), array($test['id'], $user['id']))[0];
			$subject = $this->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
			$newArray[strtotime($test['timestamp'])][] = $mark['mark'];
		}
		return $newArray;
	}
	function getLastGradesofSubjectsOfCurrentUser($numberOfGradesPerSubject){
		$user = getCurrentUser();
		$grades = $this->getAllInformationFrom('grades', 'userID', $user['id']);
		$array = array();
		foreach($grades as $grade){
			$array[] = $this->getAllInformationFrom('tests', array('id'), array($grade['testID']))[0];
		}
		if(empty($array)){return array();};
		usort($array, 'compareReversedByTimestamp');
		$newArray = array();
		foreach($array as $test){
			$mark = $this->getAllInformationFrom('grades', array('testID', 'userID'), array($test['id'], $user['id']))[0];
			$subject = $this->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
			if(empty($newArray[$subject['name']])||count($newArray[$subject['name']])<=$numberOfGradesPerSubject){
				$newArray[$subject['name']][] = $mark['mark'];
			}
		}
		return $newArray;
	}
}
/** Returns current user **/
function getCurrentUser(){
	global $ntdb;
	if(isset($_SESSION['_loginToken'])){
		$id = $ntdb->getTokenUser($_SESSION['_loginToken']);
		$user = $ntdb->getAllInformationFrom('users', 'id', $id);
		return $user[0];
	}else{
		return false;
	}
}
/** Trys to log in a user **/
function tryToLogIn($username, $password){
	global $ntdb;
	$id = $ntdb->tryToLogIn($username, $password);
	if($id!=false){
		$token = $ntdb->generateToken($id);
		$_SESSION['_loginToken'] = $token;
		return true;
	}else{
		return false;
	}
}
/** Checks if a user is logged in **/
function isUserLoggedIn(){
	global $ntdb;
	if(!empty($_SESSION['_loginToken'])){
		if($ntdb->verifyToken($_SESSION['_loginToken'])){
			return true;
		}
	}
	return false;
}
/** Hashes a password **/
function hashPassword($pw){
	return password_hash($pw, PASSWORD_DEFAULT);
}
/** Verify password **/
function verifyPassword($pw, $hash){
	return password_verify($pw, $hash);
}
/** Check if color is set, otherwise use default ones **/
$_SESSION['firstColor'] = (isset($_SESSION['firstColor'])&&!empty($_SESSION['firstColor'])) ? $_SESSION['firstColor'] : "#2c3e50";
$_SESSION['secondColor'] = (isset($_SESSION['secondColor'])&&!empty($_SESSION['secondColor'])) ? $_SESSION['secondColor'] : "#34495e";

function setupTables(){
	#create needed tables
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	#settings
	$table =
	'CREATE TABLE IF NOT EXISTS settings(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    value VARCHAR(200) NOT NULL
	)';
	$mysqli->query($table);
	#users
	$table =
	'CREATE TABLE IF NOT EXISTS users(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(200) NOT NULL,
    password VARCHAR(300) NOT NULL,
	mail VARCHAR(200) NOT NULL,
	classID INT NOT NULL,
	schoolID INT NOT NULL,
	subjectIDs VARCHAR(1000) NOT NULL,
	color1 VARCHAR(7) NOT NULL,
	color2 VARCHAR(7) NOT NULL
	)';
	$mysqli->query($table);

	#classes
	$table =
	'CREATE TABLE IF NOT EXISTS classes(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	schoolID INT NOT NULL,
    name VARCHAR(200) NOT NULL,
	adminID INT NOT NULL
	)';
	$mysqli->query($table);

	#schools
	$table =
	'CREATE TABLE IF NOT EXISTS schools(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	adminID INT NOT NULL, 
	name VARCHAR(200) NOT NULL,
	website VARCHAR(200) NOT NULL
	)';
	$mysqli->query($table);

	#tests
	$table =
	'CREATE TABLE IF NOT EXISTS tests(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(200) NOT NULL,
	type VARCHAR(200) NOT NULL,
	description VARCHAR(200) NOT NULL,
	subjectID INT NOT NULL,
	classID INT NOT NULL,
	timestamp TIMESTAMP NOT NULL
	)';
	$mysqli->query($table);

	#subjects
	$table =
	'CREATE TABLE IF NOT EXISTS subjects(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	schoolID INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    relevant TINYINT(1) NOT NULL
	)';
	$mysqli->query($table);

	#marks
	$table =
	'CREATE TABLE IF NOT EXISTS grades(
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    testID INT NOT NULL,
	userID INT NOT NULL,
	mark DOUBLE NOT NULL
	)';
	$mysqli->query($table);

	#tokens
	$table='
	CREATE TABLE tokens (
	tokenID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	tokenContent varchar(30) NOT NULL,
	tokenUser INT NOT NULL,
	tokenIP varchar(30) NOT NULL,
	tokenExpireTime timestamp NOT NULL
	)';
	$mysqli->query($table);
	
	#mailTokens
	$table='
	CREATE TABLE mailTokens (
	tokenID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	tokenContent varchar(30) NOT NULL,
	tokenMeta varchar(200) NOT NULL,
	tokenMail varchar(30) NOT NULL,
	tokenType INT NOT NULL,
	tokenIP varchar(30) NOT NULL,
	tokenExpireTime timestamp NOT NULL
	)';/* tokenType: 0=register, 1=resetPW*/
	$mysqli->query($table);
}
function clearTables(){
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$sql = 'TRUNCATE settings;TRUNCATE users;TRUNCATE classes;TRUNCATE subjects;TRUNCATE tests;TRUNCATE marks;TRUNCATE schools;';
	$mysqli->query($sql);
}
function recreateTables(){
	clearTables();
	setupTables();
}
?>
