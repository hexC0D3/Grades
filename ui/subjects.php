<?php

require_once('head.php');
checkHead('postCallbackSujects', 'showMySubjects', 'showSubjectList', 'showCreateSubject', 'showEditSubject', 'showSubjectList');

function postCallbackSujects($data){
	global $ntdb;
	$user = getCurrentUser();
	if(!empty($data['deleteSubject'])){//TODO: Remove subject of users and test and marks
		
		$subject = $ntdb->getAllInformationFrom('subjects', 'id', $data['deleteSubject'])[0];
		$school = $ntdb->getAllInformationFrom('schools', 'id', $subject['schoolID'])[0];
		if($user['schoolID']==$subject['schoolID'] && $school['adminID']==$user['id']){
			echo $ntdb->removeFromDatabase('subjects', 'id', $data['deleteSubject']);
		}else{
			echo _("You don't have the permission to do this!");
		}
	}else if(!empty($data['joinSubject'])){
		if($ntdb->getAllInformationFrom('subjects', 'id', $data['joinSubject'])[0]['schoolID']==$user['schoolID']){
			if($ntdb->addSubjectToUser($user['id'], $data['joinSubject'])!=true){
				echo _("You have already joined this subject!");
			}else{
				echo true;
			}	
		}else{
			echo _("You aren't able to join this subject!");
		}
	}else if(!empty($data['leaveSubject'])){//TODO: Warning message: loose all grades + delete all grades
		if($ntdb->removeSubjectFromUser($user['id'], $data['leaveSubject'])!=true){
			echo _("Yet you haven't joined this subject!");
		}else{
			echo true;
		}
	}else{
		if($user['schoolID']==-1){
			echo _("First you have to join a school!");
		}else{
			$school = $ntdb->getAllInformationFrom('schools', 'id', $user['schoolID'])[0];
			if($user['id']==$school['adminID']){
				if(empty($data['subjectName'])){
					echo _("Subject Name"). " " . _("shouldn't be empty!");
				}else if(empty($data['isSubjectRelevant'])){
					echo _("Is subject relevant?"). " " . _("shouldn't be empty!");
				}else{
					$data['isSubjectRelevant'] = $data['isSubjectRelevant']=="false" ? 0 : 1;
					if(!empty($data['updateSubject'])){//TODO: check permissions
						if($ntdb->getAllInformationFrom('subjects', 'id', $data['updateSubject'])[0]['schoolID']==$user['schoolID']){
							echo $ntdb->updateInDatabase('subjects', array('name', 'relevant'), array($data['subjectName'], (int)$data['isSubjectRelevant']), 'id', $data['updateSubject']);
						}else{
							echo _("You don't have the permission to do this!");
						}
					}else{
						//Check if already exists in the same school
						$subjects = $ntdb->getAllInformationFrom('subjects', 'name', $data['subjectName']);
						$exists = false;
						if(!empty($subject)){
							foreach($subjects as $subject){
								if($subject['schoolID']==$user['schoolID']){
									$exists=true;
									break;
								}
							}
						}else{
							$exists=false;
						}
						if($exists==true){
							echo _("This subject already exists!");
						}else{
							echo $ntdb->addToDatabase('subjects', array('schoolID', 'name', 'relevant'), array($user['schoolID'], $data['subjectName'], (int)$data['isSubjectRelevant']));
						}
					}
				}	
			}else{
				echo _("You don't have the permission to do this!");
			}
		}
	}
}
function showMySubjects(){
	die("my subjects");
}
function showSubjectList(){?>

<table>
	<thead><tr><th><?php echo htmlentities(_("Subject Name")); ?></th><th><?php echo htmlentities(_("Relevant ?")); ?></th><th class='actions'><?php echo htmlentities(_("Actions")); ?></th></tr></thead>
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
					echo "<tr class='subjectTableRow'><td class='subjectTableName'>".$val['name']."</td><td class='subjectTableRelevance'>".$rel."</td><td>".getSubjectTableFunction($val)."</td></tr>";
				}
			}
		?>
	</tbody>
</table>

<?php }
function getSubjectTableFunction($val){
	global $ntdb;
	$dis1 = $dis2 = $dis3 = $dis4 = "";
	if($ntdb->doesUserHaveSubject(getCurrentUser()['id'], $val['id']) == true){
		$dis1 = "disabled";
	}else{
		$dis2 = "disabled";
	}
	return '
	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
		<input type="hidden" name="joinSubject" value='.$val['id'].' />
		<input type="submit" class="join" value="'.htmlentities(_("Join")).'" '.$dis1.' />
	</form>
	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
		<input type="hidden" name="leaveSubject" value='.$val['id'].' />
		<input type="submit" class="leave" value="'.htmlentities(_("Leave")).'" '.$dis2.' />
	</form>
	
	<a href="#page:/ui/subjects.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'" '.$dis3.' /></a>
	
	<form action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list" warning="true" message="'.htmlentities(_("Are you sure, that you want to delete this subject? This will delete all of the test and grades related to this subject!")) . '">
		<input type="hidden" name="deleteSubject" value='.$val['id'].' />
		<input type="submit" class="delete" value="'.htmlentities(_("Delete")).'" '.$dis4.' />
	</form>
	';
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
	<h1><?php echo htmlentities(_("Create a new subject")); ?></h1>
	<input name="subjectName" id="subjectName" type="text" placeholder="<?php echo htmlentities(_("Subject Name")); ?>" />
	<br/><br/>
	<div class='checkboxContainer'>
		<span class="checkbox_label"><?php echo _("Is subject relevant?"); ?></span>
		<input type="checkbox" id="isSubjectRelevant" name="isSubjectRelevant" checked="checked" />
		<label for="isSubjectRelevant"></label>
	</div>
	<br/>
	<input type="submit" value="<?php echo _("Create a new subject"); ?>" />
</form>
<?php }

function showEditSubject($id){
	global $ntdb;
	$subject = $ntdb->getAllInformationFrom('subjects', 'id', $id)[0];
	if($subject['schoolID']!=getCurrentUser()['schoolID']){
		nt_die(_("You aren't allowed to edit this subject!"));
	}
	?>
	<form id="updateSubject_form" action="/ui/subjects.php" method="POST" callBackUrl="/ui/subjects.php?p=list">
	<h1><?php echo htmlentities(_("Edit the subject")); ?></h1>
	<input name="subjectName" id="subjectName" type="text" placeholder="<?php echo htmlentities(_("Subject Name")); ?>" value="<?php echo $subject['name']; ?>" />
	<br/><br/>
	<div class='checkboxContainer'>
		<span class="checkbox_label"><?php echo _("Is subject relevant?"); ?></span>
		<input type="checkbox" id="isSubjectRelevant" name="isSubjectRelevant" <?php echo $subject['relevant']==0 ? "" : "checked='checked'"; ?> />
		<label for="isSubjectRelevant"></label>
	</div>
	<br/>
	<input type="hidden" name="updateSubject" value="<?php echo $id; ?>" />
	<input type="submit" value="<?php echo _("Update subject"); ?>" />
</form>
<?php }

?>