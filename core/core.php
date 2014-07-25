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
function compareByTimestamp($a, $b) {
	return (strtotime($a["timestamp"]) < strtotime($b["timestamp"]));
}
/** Converts hex to rgb **/
function hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return implode(",", $rgb); // returns the rgb values separated by commas
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
/** Check hex color **/
function checkHexColor($color){
	if(preg_match('/^#[a-f0-9]{6}$/i', $color)){
		return $color;
	}
}
require_once('db.php');

require_once('lang.php');
setupLang();
?>