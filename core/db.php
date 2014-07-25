<?php 
require_once('config.php');#get db access data

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($db->connect_errno){
	nt_die("Not able to connect to db!");
}else{
	global $ntdb;
	$ntdb = new NTDB($db);
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
	/** Get row from the database **/
	function getAllInformationFrom($tableName, $key, $value){
		if ($stmt = $this->mysqli->prepare("SELECT * FROM ".$tableName." WHERE ".$key."=?")) {
			$type="";
			if(is_string($value)){
				$type="s";
			}else if(is_int($value)){
				$type="i";
			}else{
				return false;
			}
			$stmt->bind_param($type, $value);
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
			#print_r($results);

			$stmt->close();
			if(isset($results)){
				return $results;
			}else{
				return null;
			}
		}
		return false;
	}
	/** Remove something from the database **/
	function removeFromDatabase($tableName, $key, $value){
		if ($stmt = $this->mysqli->prepare("DELETE FROM ".$tableName." WHERE ".$key." = ?")) {
			$type="";
			if(is_string($value)){
				$type="s";
			}else if(is_int($value)){
				$type="i";
			}else{
				return false;
			}
			$stmt->bind_param($type, $value);
			$stmt->execute();
			$stmt->close();
			return true;
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
	/** Adds a user to the databse **/
	function addUser($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2){
		$check = $this->getAllInformationFrom('users', 'username', $username)[0];
		if(!empty($check)){
			return false;
		}else{
			return $this->addToDatabase('users', array('username', 'password', 'mail', 'classID', 'schoolID', 'subjectIDs', 'color1', 'color2'), array($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2));
		}
	}
	/** Register a new user **/
	function registerUser($username, $password, $mail, $classID, $schoolID, $subjectIDs,  $color1, $color2){
		$password = hashPassword($password);
		return $this->addUser($username, $password, $mail, $classID, $schoolID, $subjectIDs, $color1, $color2);//FIXME
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
	function tryToLogIn($username, $password){
		$array = $this->getAllInformationFrom('users', 'username', $username);
		foreach($array as $user){
			if($user['password'] == $password){
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
		usort($array, 'compareByTimestamp');
		if(count($array)<$numberOfGrades){
			$numberOfGrades=0;
		}else{
			$numberOfGrades = count($array)-$numberOfGrades;
		}
		$tests = array_slice($array, $numberOfGrades);
		$newArray = array();
		foreach($tests as $test){
			$mark = $this->getAllInformationFrom('grades', 'testID', $test['id'])[0];
			$subject = $this->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
			$newArray[$subject['name']][] = array($mark['mark'], strtotime($test['timestamp']));
		}
		return $newArray;
	}
	function getLastGradesofSubjectsOfCurrentUser($numberOfGradesPerSubject){
		$user = getCurrentUser();
		$array = $this->getAllInformationFrom('tests', 'classID', $user['classID']);
		usort($array, 'compareByTimestamp');
		if(count($array)<$numberOfGradesPerSubject){
			$numberOfGradesPerSubject=0;
		}else{
			$numberOfGradesPerSubject = count($array)-$numberOfGradesPerSubject;
		}
		$newArray = array();
		foreach($array as $test){
			$mark = $this->getAllInformationFrom('grades', 'testID', $test['id'])[0];
			$subject = $this->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
			if(count($newArray[$subject['name']])<=$numberOfGradesPerSubject){
				$newArray[$subject['name']][] = $mark['mark'];
			}
		}
		return $newArray;
	}
}
function getCurrentUser(){
	global $ntdb;
	$id = $ntdb->getTokenUser($_SESSION['_loginToken']);
	$user = $ntdb->getAllInformationFrom('users', 'id', $id);
	return $user[0];
}
function tryToLogIn($username, $password){
	global $ntdb;
	$password = hashPassword($password);
	$id = $ntdb->tryToLogIn($username, $password);
	if($id!=false){
		$token = $ntdb->generateToken($id);
		$_SESSION['_loginToken'] = $token;
		return true;
	}else{
		return false;
	}
}
function isUserLoggedIn(){
	global $ntdb;
	if(!empty($_SESSION['_loginToken'])){
		if($ntdb->verifyToken($_SESSION['_loginToken'])){
			return true;
		}
	}
	return false;
}
function hashPassword($pw){
	return md5($pw);
}

/** Gets random flat UI color **/
function randFlatColor(){
	global $ntdb;
	$user = getCurrentUser();

	$flatColors = array(array("#1abc9c", "#16a085"), array("#2ecc71", "#27ae60"), array("#3498db", "#2980b9"), array("#9b59b6", "#8e44ad"), array("#34495e", "#2c3e50"), array("#f1c40f", "#f39c12"), array("#e67e22", "#d35400"), array("#e74c3c", "#c0392b"));
	$rKey = array_rand($flatColors, 1);
	$rand = $flatColors[$rKey];
	
	$ntdb->updateInDatabase('users', array("color1", "color2"), array($rand[0], $rand[1]), 'id', $user['id']);

	$_SESSION['firstColor'] = $rand[0];
	$_SESSION['secondColor'] = $rand[1];
	return $rand;
}
/** Check if color is set, otherwise use default ones **/
if(!isset($_SESSION['firstColor'])){
	randFlatColor();
}
$_SESSION['firstColor'] = (isset($_SESSION['firstColor'])&&!empty($_SESSION['firstColor'])) ? $_SESSION['firstColor'] : "#1abc9c";
$_SESSION['secondColor'] = (isset($_SESSION['secondColor'])&&!empty($_SESSION['secondColor'])) ? $_SESSION['secondColor'] : "#16a085";


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
    password VARCHAR(200) NOT NULL,
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
