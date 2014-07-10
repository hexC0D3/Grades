<?php
require_once('head.php');
checkHead('gradeData', 'showGadeList', 'showGradeList', 'showAddGrade', 'showEditGrade', 'showGradeList');

function gradeData($data){
	global $ntdb;
	
	$user = getCurrentUser();
	if(isset($data['gradeMark'])&&isset($data['markTestID'])){
		if($ntdb->isInDatabase('tests', 'id', $data['markTestID'])){
			if(isset($data['updateMark'])&&!empty($data['updateMark'])){
				$mark = $ntdb->getAllInformationFrom('grades', 'id', $data['updateMark'])[0];
				if($mark['userID']==$user['id']){
					echo $ntdb->updateInDatabase('grades', array('mark', 'testID'), array($data['gradeMark'], $data['markTestID']), 'id', $data['updateMark']);
				}else{
					echo _("This isn't your mark!");
				}
			}else{
				$a = $ntdb->getAllInformationFrom('grades', 'testID', $data['markTestID']);
				if(empty(search($a, 'userID', $user['id']))){
					echo $ntdb->addToDatabase('grades', array('mark', 'userID', 'testID'), array($data['gradeMark'], $user['id'], $data['markTestID']));
				}else{
					echo _("You have already entered your mark for this test!");
				}
			}
		} else{
			echo _("Invalid Test!");
		}
	}else{
		echo _("Please fill in all required fields!");
	}
}

function showGradeList(){
	redirectToHome("Please don't mess around with the url ;)");
}

function showAddGrade($get){
	global $ntdb;
	
	if(isset($get['test'])){
		$test = $get['test'];
	}
?>
	<form id="addNewMark_form" action="/ui/grade.php" method="POST" callBackUrl="/ui/test.php?p=list">
	<h1><?php echo htmlentities(_("Add a mark")); ?></h1>
	<input name="gradeMark" id="gradeMark" type="number" min="1" max="6" placeholder="<?php echo htmlentities(_("Your Mark (between 1 and 6)")); ?>" />
	<br/><br/>
	<select id="markTestID" name="markTestID" placeholder="<?php echo htmlentities(_("Corresponding Test")); ?>" <?php if(isset($test)){echo 'value="'.$test.'"';}?>>
		<?php 
		global $ntdb;
		$tests = $ntdb->getAllInformationFrom('tests', 'classID', getCurrentUser()['classID']);
		foreach($tests as $test){
			$test['timestamp'] = date("d. m. Y", strtotime($test['timestamp']));
			$subject = $ntdb->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
			echo "<option value='".$test['id']."'>".$test['topic']."</option>";
		}
		?>
	</select>
	<br/><br/>
	<input type="submit" value="<?php echo _("Add mark"); ?>" />
</form>
<?php }

function showEditGrade($id){
	global $ntdb;
	$user = getCurrentUser();
	$mark = $ntdb->getAllInformationFrom('grades', 'id', $id)[0];
	if($user['id']!=$mark['userID']){
		nt_die(_("You aren't allowed to see this page!"));
	}
?>
	<form id="addNewMark_form" action="/ui/grade.php" method="POST" callBackUrl="/ui/test.php?p=list">
		<h1><?php echo htmlentities(_("Add a mark")); ?></h1>
		<input name="gradeMark" id="gradeMark" type="number" min="1" max="6" placeholder="<?php echo htmlentities(_("Your Mark (between 1 and 6)")); ?>" value="<?php echo $mark['mark']; ?>" />
		<br/><br/>
		<select id="markTestID" name="markTestID" placeholder="<?php echo htmlentities(_("Corresponding Test")); ?>" value="<?php echo $id; ?>" >
			<?php 
			global $ntdb;
			$tests = $ntdb->getAllInformationFrom('tests', 'classID', getCurrentUser()['classID']);
			foreach($tests as $test){
				$test['timestamp'] = date("d. m. Y", strtotime($test['timestamp']));
				$subject = $ntdb->getAllInformationFrom('subjects', 'id', $test['subjectID'])[0];
				echo "<option value='".$test['id']."'>".$test['topic'].", ".$subject['name'].", ".$test['timestamp']."</option>";
			}
			?>
		</select>
		<br/><br/>
		<input type="hidden" name="updateMark" value="<?php echo $id; ?>" />
		<input type="submit" value="<?php echo _("Update mark"); ?>" />
	</form>
</form>
<?php }
?>