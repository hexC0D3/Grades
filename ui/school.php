<?php
require_once("head.php");
checkHead('postCallbackSchool', 'showSchoolList', 'showSchoolList', 'showCreateSchool', 'showEditSchool', 'showSchoolList');

function postCallbackSchool($data){
	global $ntdb;
	$user = getCurrentUser();
	
	if(!empty($data['schoolName'])){
		if(!empty($data['updateSchool'])){
			if($ntdb->isInDatabase('schools', 'id', $data['updateSchool'])==true){
				$school = $ntdb->getAllInformationFrom('schools', 'id', $data['updateSchool'])[0];
				if($school['adminID']==$user['id']){
					echo $ntdb->updateInDatabase('schools', array('name', 'website'), array($data['schoolName'], $data['schoolWebsite']), 'id', $data['updateSchool']);
				}else{
					echo sanitizeOutput(_("You don't have the permission to do this!"));
				}
			}else{
				echo sanitizeOutput(_("This school doesn't exist!"));
			}
		}else{
			if($ntdb->isInDatabase('schools', 'website', $data['schoolWebsite']) || $ntdb->isInDatabase('schools', 'name', $data['schoolName'])){
				echo sanitizeOutput(_("Your school already exists!"));
			}else{
				if($user['schoolID']==-1){
					echo $ntdb->addToDatabase('schools', array('adminID', 'name', 'website'), array($user['id'], $data['schoolName'], $data['schoolWebsite']));
					$school = $ntdb->getAllInformationFrom('schools', array('name', 'website', 'adminID'), array($data['schoolName'], $data['schoolWebsite'], $user['id']))[0];
					$ntdb->updateInDatabase('users', array('schoolID'), array($school['id']), 'id', $user['id']);
				}else{
					echo sanitizeOutput(_("You have already joined a school!"));
				}
			}	
		}
	}else if(!empty($data['joinSchool'])){
		if($user['schoolID']==-1){
			echo $ntdb->updateInDatabase('users', array('schoolID'), array($data['joinSchool']), 'id', $user['id']);
		}else{
			echo sanitizeOutput(_("You have already joined a school!"));
		}
	}else if(!empty($data['deleteSchool'])){
		if($ntdb->getAllInformationFrom('schools', 'id', $data['deleteSchool'])[0]['adminID']==$user['id']){
			echo $ntdb->removeFromDatabase('schools', 'id', $data['deleteSchool']);
		}else{
			echo sanitizeOutput(_("You don't have the permission to do this!"));
		}
	}else if(!empty($data['leaveSchool'])){
		if($ntdb->isInDatabase('schools', 'adminID', $user['id'])){
			echo sanitizeOutput(_("You can't leave this school, because you're the admin of it!"));
		}else{
			$ntdb->safelyRemoveUserFromSchool($user['id']);
		}
	}
}
function showSchoolList($get){?>

<table>
	<thead><tr><th><?php echo sanitizeOutput(_("School Name")); ?></th><th class="actions"><?php echo sanitizeOutput(_("Actions")); ?></th></tr></thead>
	<tbody>
		<?php 
			global $ntdb;
			$array = $ntdb->getAllInformationFromTable('schools');
			usort($array, 'compareByName');
			foreach($array as $val){
				$val = sanitizeOutput($val);
				echo "<tr class='schoolTableRow'><td class='schoolTableName'><a href='http://".$val['website']."' target='_blank'>".$val['name']."</a></td><td class='schoolTableActions actions'>".getSchoolTableFunction($val)."</td></tr>";
			}
		?>
	</tbody>
</table>

<?php }
function getSchoolTableFunction($val){
	$return = "";
	
	global $ntdb;
	$user = getCurrentUser();
	
	$dis1=$dis2=$dis3=$dis4="";
	if($val['id'] == $user['schoolID']){
		$dis1="disabled";
	}else{
		$dis2="disabled";
	}//TODO: warning message
	$return .= '
		<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
			<input type="hidden" name="joinSchool" value='.$val['id'].' />
			<input type="submit" class="join" value="'._("Join").'" '.$dis1.' />
		</form>
		<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list" warning="true" message="'.sanitizeOutput(_("Do you really want to leave this school? This will wipe all of your data!")).'"">
			<input type="hidden" name="leaveSchool" value='.$val['id'].' />
			<input type="submit" class="leave" value="'._("Leave").'" '.$dis2.' />
		</form>
		';
	if($user['id']!=$val['adminID']){
		$dis3="disabled";
		$dis4="disabled";
	}else{
		$return.='
		<a href="#page:/ui/school.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'" '.$dis3.' /></a>
		<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
			<input type="hidden" name="deleteSchool" value='.$val['id'].' />
			<input type="submit" class="delete" value="'._("Delete").'" '.$dis4.' />
		</form>
		';
	}
	return $return;
}
function showCreateSchool($get){ ?>
<form id="createNewSubject_form" action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
	<h1><?php echo sanitizeOutput(_("Create a school")); ?></h1>
	<input name="schoolName" id="schoolName" type="text" placeholder="<?php echo sanitizeOutput(_("School Name")); ?>" />
	<br/><br/>
	<input name="schoolWebsite" id="schoolWebsite" type="text" placeholder="<?php echo sanitizeOutput(_("School Website URL (without http://)")); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo sanitizeOutput(_("Create a school")); ?>" />
</form>
<?php }

function showEditSchool($id){
global $ntdb;
$school = $ntdb->getAllInformationFrom('schools', 'id', $id)[0];
if(getCurrentUser()['id']!=$school['adminID']){
	nt_die(_("You aren't allowed to see this page!"));
}
?>
<form id="createNewSubject_form" action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
	<h1><?php echo sanitizeOutput(_("Create a school")); ?></h1>
	<input name="schoolName" id="schoolName" type="text" placeholder="<?php echo sanitizeOutput(_("School Name")); ?>" value="<?php echo sanitizeOutput($school['name']); ?>" />
	<br/><br/>
	<input name="schoolWebsite" id="schoolWebsite" type="text" placeholder="<?php echo sanitizeOutput(_("School Website URL (without http://)")); ?>" value="<?php echo sanitizeOutput($school['website']); ?>" />
	<br/><br/>
	<input type="hidden" name="updateSchool" value="<?php echo sanitizeOutput($id); ?>" />
	<input type="submit" value="<?php echo sanitizeOutput(_("Update school")); ?>" />
</form>
<?php }
?>