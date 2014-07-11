<?php
function getLang(){
	$subDomain = str_replace(DOMAIN, "", $_SERVER["HTTP_HOST"]);
	$locale = "";
	switch ($subDomain) {
		case "de":
		case "ch":
			$locale = "de_DE";
			break;
	
		case "us":
			$locale = "en_US";
			break;
	
		case "en":
			$locale = "en_EN";
			break;
	
		default:
			$locale = "en_US";
			break;
	}
	return $locale.".utf8";
}
function setLang($locale, $domain, $encoding){ 
	putenv("LC_ALL=".$locale);
	setlocale(LC_ALL, $locale);
	bindtextdomain($domain, CORE_DIR."lang/"); 
	bind_textdomain_codeset($domain, $encoding);
	textdomain($domain);
	
	return true;
}
function setupLang(){
	setLang(getLang(), "grades", "UTF-8");
}
?>