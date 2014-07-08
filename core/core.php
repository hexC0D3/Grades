<?php 
define("ROOT_DIR", explode("core", dirname(__FILE__))[0]);
define("CORE_DIR", ROOT_DIR . 'core/');
define("ADMIN_DIR", ROOT_DIR . 'admin/');
define("AJAX_DIR", ROOT_DIR . 'ajax/');
define("UI_DIR", ROOT_DIR . 'ui/');

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