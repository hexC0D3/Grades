<?php 
define("ROOT_DIR", explode("core", dirname(__FILE__))[0]);
define("CORE_DIR", ROOT_DIR . 'core/');
define("ADMIN_DIR", ROOT_DIR . 'admin/');
define("AJAX_DIR", ROOT_DIR . 'ajax/');
define("UI_DIR", ROOT_DIR . 'ui/');

session_start();

/** Custom die function **/
function nt_die($arg){
	die($arg);
}
/** Gets data array out of php ajax post **/
function getPostAjaxDataArray($post){
	$array = explode(";", $post['data']);
	$data = array();
	foreach($array as $val){
		if(!empty($val)){
			$exp = explode(":", $val);
			$data[$exp[0]] = $exp[1];
		}
	}
	
	return $data;
}
/** Sorts an array by name **/ 
function compareByName($a, $b) {
	return strcmp($a["name"], $b["name"]);
}
/** Sorts an array by Timestamp **/
function comparyByTimestamp($a, $b) {
	return (strtotime($a["timestamp"]) < strtotime($b["timestamp"]));
}
/** Searches an array by a key and a value **/
function search($array, $key, $value){
	$results = array();
	if(is_array($array)){
		if (isset($array[$key]) && $array[$key] == $value) {
			$results[] = $array;
		}
		foreach ($array as $subarray) {
			$results = array_merge($results, search($subarray, $key, $value));
		}
		return $results;
	}else{
		return array();
	}
}
/** Redirects to home with an error message **/
function redirectToHome($error){
	echo '<script>
			alertify.error("'.$error.'");
			window.location.hash = "#grades";
		</script>';
}

require_once('db.php');

require_once('lang.php');
setupLang();
?>