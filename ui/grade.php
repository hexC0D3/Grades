<?php
require_once('head.php');
checkHead('gradeData', 'showGadeList', 'showGradeList', 'showAddGrade', 'showEditGrade', 'showGradeList');

function gradeData($data){
	
}

function showGradeList(){
	redirectToHome("Please don't mess around with the url ;)");
}

function showAddGrade(){
	echo "add grade";
}

function showEditGrade($id){
	
}
?>