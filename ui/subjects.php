<?php

require_once('head.php');
checkHead('postCallbackSujects', 'showMySubjects', 'showSubjectList', 'showCreateSubject', 'showEditSubject', 'showSubjectList');

function postCallbackSujects($data){
	global $ntdb;
	$user = getCurrentUser();
	if(!empty($data['deleteSubject'])){
		
		$subject = $ntdb->getAllInformationFrom('subjects', 'id', $data['deleteSubject'])[0];
		$school = $ntdb->getAllInformationFrom('schools', 'id', $subject['schoolID'])[0];
		if($user['schoolID']==$subject['schoolID']&&$school['adminID']==$user['id']){
			$users=$ntdb->getAllInformationFrom('users', array('classID'), array($user['classID']));
			foreach($users as $user){
				$ntdb->removeSubjectFromUser($user['id'], $data['deleteSubject']);
			}
			$tests = $ntdb->getAllInformationFrom('tests', array('subjectID'), array($data['deleteSubject']));
			foreach($tests as $test){
				$ntdb->removeFromDatabase('grades', array('testID'), array($test['id']));
				$ntdb->removeFromDatabase('tests', array('id'), array($test['id']));	
			}
			echo $ntdb->removeFromDatabase('subjects', 'id', $data['deleteSubject']);
		}else{
			echo sanitizeOutput(_("You don't have the permission to do this!"));
		}
	}else if(!empty($data['joinSubject'])){
		if($ntdb->getAllInformationFrom('subjects', 'id', $data['joinSubject'])[0]['schoolID']==$user['schoolID']){
			if($ntdb->addSubjectToUser($user['id'], $data['joinSubject'])!=true){
				echo sanitizeOutput(_("You have already joined this subject!"));
			}else{
				echo true;
			}	
		}else{
			echo sanitizeOutput(_("You aren't able to join this subject!"));
		}
	}else if(!empty($data['leaveSubject'])){
		if($ntdb->removeSubjectFromUser($user['id'], $data['leaveSubject'])!=true){
			echo sanitizeOutput(_("Yet you haven't joined this subject!"));
		}else{
			$tests=$ntdb->getAllInformationFrom('tests', array('classID', 'subjectID'), array($user['classID'], $data['leaveSubject']));
			foreach($tests as $test){
				$ntdb->removeFromDatabase('grades', array('userID', 'testID'), array($user['id'], $test['id']));
			}
			echo true;
		}
	}else{
		if($user['schoolID']==-1){
			echo sanitizeOutput(_("First you have to join a school!"));
		}else{
			if(empty($data['subjectName'])){
				echo sanitizeOutput(_("Subject Name"). " " . _("shouldn't be empty!"));
			}else if(empty($data['isSubjectRelevant'])){
				echo sanitizeOutput(_("Is subject relevant?"). " " . _("shouldn't be empty!"));
			}else{
				$data['isSubjectRelevant'] = $data['isSubjectRelevant']=="false" ? 0 : 1;
				if(!empty($data['updateSubject'])){
					$subject=$ntdb->getAllInformationFrom('subjects', 'id', $data['updateSubject'])[0];
					$school = $ntdb->getAllInformationFrom('schools', 'id', $subject['schoolID'])[0];
					if($school['adminID']==$user['id']){
						echo $ntdb->updateInDatabase('subjects', array('name', 'relevant'), array($data['subjectName'], (int)$data['isSubjectRelevant']), 'id', $data['updateSubject']);
					}else{
						echo sanitizeOutput(_("You don't have the permission to do this!"));
					}
				}else{
					//Check if already exists in the same school
					if(count($ntdb->getAllInformationFrom('subjects', array('name', 'schoolID'), array($data['subjectName'], $user['schoolID'])))>0){
						echo sanitizeOutput(_("This subject already exists!"));
					}else{
						$school = $ntdb->getAllInformationFrom('schools', 'id', $user['schoolID'])[0];
						if($school['adminID']==$user['id']){
							echo $ntdb->addToDatabase('subjects', array('schoolID', 'name', 'relevant'), array($user['schoolID'], $data['subjectName'], (int)$data['isSubjectRelevant']));
						}else{
							echo sanitizeOutput(_("You don't have the permission to do this!"));
						}
					}
				}
			}
		}
	}
}
function showMySubjects($get){
	global $ntdb;
	$user=getCurrentUser();
	$subject=$ntdb->getAllInformationFrom('subjects', array('id'), array($get['id']))[0];
	
	
	if(isset($get['id'])&&!empty($get['id'])){
		echo "<h1>".$subject['name']."</h1>";
	?>
		<table>
			<thead>
				<tr>
					<th><?php echo sanitizeOutput(_("Topic")); ?></th>
					<th><?php echo sanitizeOutput(_("Description")); ?></th>
					<th><?php echo sanitizeOutput(_("Type")); ?></th>
					<th><?php echo sanitizeOutput(_("Test Date")); ?></th>
					<th><?php echo sanitizeOutput(_("Mark")); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$tests=$ntdb->getAllInformationFrom('tests', array('subjectID', 'classID'), array($get['id'],$user['classID']));
					usort($tests, 'compareByTimestamp');
					foreach($tests as $test){
						$grade=$ntdb->getAllInformationFrom('grades', array('testID', 'userID'), array($test['id']), $user['id'])[0];
						$test['timestamp'] = date("d. m. Y", strtotime($test['timestamp']));
						echo "<tr><td>".$test['topic']."</td><td>".$test['description']."</td><td>".$test['type']."</td><td>".$test['timestamp']."</td><td><a href='#page:/ui/grade.php?p=edit&id=".sanitizeOutput($grade['id'])."'>".$grade['mark']."</a> [".$ntdb->getAverageMark($test['id'])."]</td></tr>";
					}
				?>
			</tbody>
		</table>
	<?php }else{
		redirectToHome(_("Choose a subject on the main page by clicking on its name."));
	}
}
function showSubjectList($get){?>

<table>
	<thead><tr><th><?php echo sanitizeOutput(_("Subject Name")); ?></th><th><?php echo sanitizeOutput(_("Relevant ?")); ?></th><th class='actions'><?php echo sanitizeOutput(_("Actions")); ?></th></tr></thead>
	<tbody>
		<?php 
			global $ntdb;
			$school = getCurrentUser()['schoolID'];
			if($school==false){
				redirectToHome(_("First you have to join a school!"));
			}else{
				$array = $ntdb->getAllInformationFrom('subjects', 'schoolID', $school);
				usort($array, 'compareByName');
				foreach($array as $val){
					$rel = $val['relevant']== 1 ? _("Yes") : _("No");
					echo "<tr class='subjectTableRow'><td class='subjectTableName'>".sanitizeOutput($val['name'])."</td><td class='subjectTableRelevance'>".$rel."</td><td class='actions'>".getSubjectTableFunction($val)."</td></tr>";
				}
			}
		?>
	</tbody>
</table>

<?php }
function getSubjectTableFunction($val){
	global $ntdb;
	$user = getCurrentUser();
	$dis1 = $dis2 = "";
	$code = "";
	
	if($ntdb->doesUserHaveSubject($user['id'], $val['id']) == true){
		$dis1 = "disabled";
	}else{
		$dis2 = "disabled";
	}
	
	$code .= '
	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
		<input type="hidden" name="joinSubject" value='.$val['id'].' />
		<input type="submit" class="join" value="'.htmlentities(_("Join")).'" '.$dis1.' />
	</form>
	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list" warning="true" message="'.htmlentities(_("Are you sure, that you want to leave this subject? This will delete all grades related to this subject!")) . '">
		<input type="hidden" name="leaveSubject" value='.$val['id'].' />
		<input type="submit" class="leave" value="'.htmlentities(_("Leave")).'" '.$dis2.' />
	</form>';
	
	
	$school = $ntdb->getAllInformationFrom('schools', 'id', $ntdb->getAllInformationFrom('subjects', 'id', $val['id'])[0]['schoolID'])[0];
	if($school['adminID']==$user['id']){
		$code.='<a href="#page:/ui/subjects.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'"/></a>

	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list" warning="true" message="'.htmlentities(_("Are you sure, that you want to delete this subject? This will delete all grades related to this subject!")) . '">
		<input type="hidden" name="deleteSubject" value='.$val['id'].' />
		<input type="submit" class="delete" value="'.htmlentities(_("Delete")).'"/>
	</form>
	';
	}
	
	return $code;
}
function showCreateSubject($get){
	global $ntdb;
	$user = getCurrentUser();
	$school = $ntdb->getAllInformationFrom('schools', 'id', $user['schoolID'])[0];
	if($user['id']!=$school['adminID']){
		nt_die(_("You aren't allowed to create a subject!"));
	}
?>
<form id="createNewSubject_form" action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
	<h1><?php echo sanitizeOutput(_("Create a new subject")); ?></h1>
	<input name="subjectName" id="subjectName" type="text" placeholder="<?php echo sanitizeOutput(_("Subject Name")); ?>" />
	<br/><br/>
	<div class='checkboxContainer'>
		<span class="checkbox_label"><?php echo sanitizeOutput(_("Is subject relevant?")); ?></span>
		<input type="checkbox" id="isSubjectRelevant" name="isSubjectRelevant" checked="checked" />
		<label for="isSubjectRelevant"></label>
	</div>
	<br/>
	<input type="submit" value="<?php echo sanitizeOutput(_("Create a new subject")); ?>" />
</form>
<?php }

function showEditSubject($id){
	global $ntdb;
	$subject = $ntdb->getAllInformationFrom('subjects', 'id', $id)[0];
	$school = $ntdb->getAllInformationFrom('schools', 'id', $subject['schoolID'])[0];
	if($school['adminID']!=getCurrentUser()['id']){
		nt_die(_("You aren't allowed to edit this subject!"));
	}
	?>
	<form id="updateSubject_form" action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
	<h1><?php echo sanitizeOutput(_("Edit the subject")); ?></h1>
	<input name="subjectName" id="subjectName" type="text" placeholder="<?php echo sanitizeOutput(_("Subject Name")); ?>" value="<?php echo sanitizeOutput($subject['name']); ?>" />
	<br/><br/>
	<div class='checkboxContainer'>
		<span class="checkbox_label"><?php echo sanitizeOutput(_("Is subject relevant?")); ?></span>
		<input type="checkbox" id="isSubjectRelevant" name="isSubjectRelevant" <?php echo sanitizeOutput($subject['relevant']==0 ? "" : "checked='checked'"); ?> />
		<label for="isSubjectRelevant"></label>
	</div>
	<br/>
	<input type="hidden" name="updateSubject" value="<?php echo sanitizeOutput($id); ?>" />
	<input type="submit" value="<?php echo sanitizeOutput(_("Update subject")); ?>" />
</form>
<?php }

?>