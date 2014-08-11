<?php
require_once('head.php');
checkHead('testData', 'showTestList', 'showTestList', 'showCreateTest', 'showEditTest', 'showTestList');

function testData($data){
	global $ntdb;
	$user = getCurrentUser();
	if(!empty($data['testTopic'])&&!empty($data['testType'])&&!empty($data['testSubjectID'])&&!empty($data['testDate'])){
		if($ntdb->isInDatabase('subjects', 'id', $data['testSubjectID'])==true && $ntdb->isInDatabase('classes', 'id', $user['classID'])==true){
			$array = explode(". ", $data['testDate']);
			$data['testDate'] = date("Y-m-d H:i:s",strtotime($array[2]."-".$array[1]."-".$array[0]." 12:00:00"));
			if(isset($data['updateTest']) && !empty($data['updateTest'])){
				$test = $ntdb->getAllInformationFrom('tests', array('id'), array($data['updateTest']))[0];
				if($test['classID']==$user['classID']){
					echo $ntdb->updateInDatabase('tests', array('topic', 'type', 'description', 'subjectID', 'timestamp'), array($data['testTopic'], $data['testType'], $data['testDesc'], $data['testSubjectID'], $data['testDate']), 'id', $data['updateTest']);
				}else{
					echo _("You don't have the permission to do this!");
				}
			}else{
				echo $ntdb->addToDatabase('tests', array('topic', 'type', 'description', 'subjectID', 'classID', 'timestamp'), array($data['testTopic'], $data['testType'], $data['testDesc'], $data['testSubjectID'], $user['classID'], $data['testDate']));
			}
		}else{
			print_r($data);
			echo sanitizeOutput(_("Something went wrong, please contact me@tyratox.ch"));
		}
	}else{
		if(isset($data['deleteTest'])){
			$class = $ntdb->getAllInformationFrom('classes', 'id', $data['deleteTest'])[0];
			if($class['adminID']==$user['id']){
				echo $ntdb->removeFromDatabase('tests', 'id', $data['deleteTest']);
			}else{
				echo sanitizeOutput(_("You don't have the permission to do this!"));
			}
		}else{
			echo sanitizeOutput(_("Please fill in all required fields!"));
		}
	}
}
function showTestList($get){
	global $ntdb;
	$user = getCurrentUser();
	if($user['classID']==-1){
		redirectToHome(_("First you have to join a class!"));
	}
	$class = $ntdb->getAllInformationFrom('classes', 'id', $user['classID'])[0];
	$admin = false;
	if($class['adminID']==$user['id']){
		$admin = true;
	}
?>
<table>
	<thead><tr><th><?php echo sanitizeOutput(_("Topic")); ?></th><th style="width:20%;"><?php echo sanitizeOutput(_("Subject")); ?></th><th><?php echo sanitizeOutput(_("Mark")); ?></th><th><?php echo sanitizeOutput(_("Date")); ?></th><?php if($admin==true){ echo '<th class="actions">'.sanitizeOutput(_("Actions")).'</th>'; }?></tr></thead>
	<tbody>
		<?php 
			global $ntdb;
			$array = $ntdb->getAllInformationFrom('tests', 'classID', $user['classID']);
			
			usort($array, 'compareByTimestamp');
			$grades = $ntdb->getAllInformationFrom('grades', 'userID', $user['id']);
			foreach($array as $val){
				if($ntdb->doesUserHaveSubject($user['id'], $val['subjectID'])){
					$subject = $ntdb->getAllInformationFrom('subjects', 'id', $val['subjectID'])[0];
					$val['timestamp'] = date("d. m. Y", strtotime($val['timestamp']));
					$m = search($grades, 'testID', $val['id']);
					$mark = generateAddMarkLink($val);
					if(isset($m)&&!empty($m)){
						$mark = "<a href='#page:/ui/grade.php?p=edit&id=".sanitizeOutput($m[0]['id'])."'>".sanitizeOutput($m[0]['mark'])."</a>";
					}
					$average=$ntdb->getAverageMark($val['id']);
					$mark.=" [".$average."]";
					?>
					<tr class='testTableRow'><td class='testTableTopic'><?php echo sanitizeOutput($val['topic']); ?></td><td class='testTableSubject'><?php echo sanitizeOutput($subject['name']); ?></td><td class='testTableMark'><?php echo $mark; ?></td><td class='testTableDate'><?php echo sanitizeOutput($val['timestamp']); ?></td><?php if($admin==true){echo '<td class="testTableActions actions">'.getTestTableFunction($val).'</td>';}?></tr>
					<?php
				}
			}
		?>
	</tbody>
</table>
<?php }
function generateAddMarkLink($val){
	return "<a href='#page:/ui/grade.php?p=add&test=".$val['id']."'>Add Mark</a>";
}
function getTestTableFunction($val){
	global $ntdb;
	$user = getCurrentUser();
	
	$return = '<a href="#page:/ui/test.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'" /></a>';
	$class=$ntdb->getAllInformationFrom('classes', array('id'), array($user['classID']))[0];
	if($class['adminID']==$user['id']){
		$return.='<form action="/ui/test.php" method="POST" callBackUrl="/ui/class.php?p=list" warning="true" message="'.htmlentities(_("Are you sure, that you want to delete this test? This will delete all grades related to this test!")).'">
			<input type="hidden" name="deleteTest" value='.$val['id'].' />
			<input type="submit" class="delete" value="'.htmlentities(_("Delete")).'" />
		</form>';
	}
	return $return;
}
function showCreateTest($get){
	global $ntdb;
	$user = getCurrentUser();
?>
<form id="createNewTest_form" action="/ui/test.php" method="POST" callBackUrl="/ui/test.php?p=list">
	<h1><?php echo sanitizeOutput(_("Create a test")); ?></h1>
	<div class="clear"></div>
	<h2><?php echo sanitizeOutput(_("Please check first if this test already exists!"));?></h2>
	<input name="testTopic" id="testTopic" type="text" placeholder="<?php echo sanitizeOutput(_("Test Topic")); ?>" />
	<br/><br/>
	<input name="testType" id="testType" type="text" placeholder="<?php echo sanitizeOutput(_("Test Type(written, oral, ..)")); ?>" />
	<br/><br/>
	<input name="testDesc" id="testDesc" type="text" maxlength="200" placeholder="<?php echo sanitizeOutput(_("Short Test Description")); ?>" />
	<br/><br/>
	<select id="testSubjectID" name="testSubjectID" placeholder="<?php echo sanitizeOutput(_("Test Subject")); ?>">
		<?php 
		global $ntdb;
		$subjects = $ntdb->getAllInformationFrom('subjects', 'schoolID', getCurrentUser()['schoolID']);
		foreach($subjects as $subject){
			$subject=sanitizeOutput($subject);
			echo "<option value='".$subject['id']."'>".$subject['name']."</option>";
		}
		?>
	</select>
	<br/><br/>
	<input name="testDate" id="testDate" class="datepicker" type="text" placeholder="<?php echo sanitizeOutput(_("Test Date"). " dd. mm. yyyy"); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo sanitizeOutput(_("Create a test")); ?>" />
</form>
<?php }
function showEditTest($id){
	global $ntdb;
	$user = getCurrentUser();
	$test = $ntdb->getAllInformationFrom('tests', 'id', $id)[0];
	if($test['classID']!=$user['classID']){
		nt_die(_("You aren't allowed to see this page!"));
	}
	$test['timestamp'] = date("d. m. Y", strtotime($test['timestamp']));
?>
<form id="createNewTest_form" action="/ui/test.php" method="POST" callBackUrl="/ui/test.php?p=list">
	<h1><?php echo sanitizeOutput(_("Create a test")); ?></h1>
	<input name="testTopic" id="testTopic" type="text" placeholder="<?php echo sanitizeOutput(_("Test Topic")); ?>" value="<?php echo sanitizeOutput($test['topic']); ?>" />
	<br/><br/>
	<input name="testType" id="testType" type="text" placeholder="<?php echo sanitizeOutput(_("Test Type(written, oral, ..)")); ?>" value="<?php echo sanitizeOutput($test['type']); ?>"/>
	<br/><br/>
	<input name="testDesc" id="testDesc" type="text" maxlength="200" placeholder="<?php echo sanitizeOutput(_("Short Test Description")); ?>" value="<?php echo sanitizeOutput($test['description']); ?>" />
	<br/><br/>
	<select id="testSubjectID" name="testSubjectID" placeholder="<?php echo sanitizeOutput(_("Test Subject")); ?>" value="<?php echo sanitizeOutput($test['subjectID']); ?>">
		<?php 
		global $ntdb;
		$subjects = $ntdb->getAllInformationFrom('subjects', 'schoolID', getCurrentUser()['schoolID']);
		foreach($subjects as $subject){
			$subject=sanitizeOutput($subject);
			echo "<option value='".$subject['id']."'>".$subject['name']."</option>";
		}
		?>
	</select>
	<br/><br/>
	<input name="testDate" id="testDate" class="datepicker" type="text" placeholder="<?php echo sanitizeOutput(_("Test Date (dd. mm. yyyy)")); ?>" value="<?php echo sanitizeOutput($test['timestamp']); ?>" />
	<br/><br/>
	<input type="hidden" name="updateTest" value="<?php echo sanitizeOutput($id); ?>" />
	<input type="submit" value="<?php echo sanitizeOutput(_("Update test")); ?>" />
</form>
<?php }
?>