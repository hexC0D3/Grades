<?php
require_once("head.php");
checkHead('postCallbackSchool', 'showMySchool', 'showSchoolList', 'showCreateSchool', 'showEditSchool', 'showCreateSchool');

function postCallbackSchool($data){
	if(!empty($data['schoolName'])){
		global $ntdb;
		if(!empty($data['updateSchool'])){//TODO: permission check
			if($ntdb->isInDatabase('schools', 'id', $data['updateSchool'])==true){
				echo $ntdb->updateInDatabase('schools', array('name', 'website'), array($data['schoolName'], $data['schoolWebsite']), 'id', $data['updateSchool']);
			}else{
				echo _("This school doesn't exist!");
			}
		}else{
			if($ntdb->isInDatabase('schools', 'website', $data['schoolWebsite']) || $ntdb->isInDatabase('schools', 'name', $data['schoolName'])){
				echo htmlentities(_("Your school already exists!"));
			}else{
				echo $ntdb->addToDatabase('schools', array('name', 'website'), array($data['schoolName'], $data['schoolWebsite']));
			}	
		}
	}else if(!empty($data['joinSchool'])){
		$user = getCurrentUser();
		if($user['schoolID']==-1){
			global $ntdb;
			echo $ntdb->updateInDatabase('users', 'schoolID', $schoolID, 'id', $user['id']);
		}else{
			echo _("You have already joined a school!");
		}
	}
}
function showMySchool(){
	global $ntdb;
	$schoolID = getCurrentUser()['schoolID'];
	if($schoolID == -1){
		redirectToHome(_("First you have to join a school!"));
	}else{
		die("My School");
	}
}
function showSchoolList(){?>

<table>
	<thead><tr><th><?php echo htmlentities(_("School Name")); ?></th><th class="actions"><?php echo htmlentities(_("Actions")); ?></th></tr></thead>
	<tbody>
		<?php 
			global $ntdb;
			$array = $ntdb->getAllInformationFromTable('schools');
			usort($array, 'compareByName');
			foreach($array as $val){
				echo "<tr class='schoolTableRow'><td class='schoolTableName'><a href='http://".$val['website']."' target='_blank'>".$val['name']."</a></td><td class='schoolTableActions'>".getSchoolTableFunction($val)."</td></tr>";
			}
		?>
	</tbody>
</table>

<?php }
function getSchoolTableFunction($val){
	$dis1=$dis2=$dis3=$dis4="";
	if($val['id'] == getCurrentUser()['schoolID']){
		$dis1="disabled";
	}else{
		$dis2="disabled";
	}
	if(false){/* TODO:Permission check:edit*/
		$dis3="disabled";
	}
	if(false){/* TODO:Permission check:delete*/
		$dis4="disabled";
	}
	return '
	<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=my">
		<input type="hidden" name="joinSchool" value='.$val['id'].' />
		<input type="submit" class="join" value="'._("Join").'" '.$dis1.' />
	</form>
	<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list" warning="true" message="lol">
		<input type="hidden" name="leaveSchool" value='.$val['id'].' />
		<input type="submit" class="leave" value="'._("Leave").'" '.$dis2.' />
	</form>
	<a href="#page:/ui/school.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'" '.$dis3.' /></a>
	<form action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
		<input type="hidden" name="deleteSchool" value='.$val['id'].' />
		<input type="submit" class="delete" value="'._("Delete").'" '.$dis2.' />
	</form>
	';
}
function showCreateSchool(){?>
<form id="createNewSubject_form" action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
	<h1><?php echo htmlentities(_("Create a school")); ?></h1>
	<input name="schoolName" id="schoolName" type="text" placeholder="<?php echo htmlentities(_("School Name")); ?>" />
	<br/><br/>
	<input name="schoolWebsite" id="schoolWebsite" type="text" placeholder="<?php echo htmlentities(_("School Website URL (without http://)")); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo _("Create a school"); ?>" />
</form>
<?php }

function showEditSchool($id){
global $ntdb;
$school = $ntdb->getAllInformationFrom('schools', 'id', $id)[0];
if(false){/*TODO:Permission check:edit school */
	nt_die(_("You aren't allowed to see this page!"));
}
?>
<form id="createNewSubject_form" action="/ui/school.php" method="POST" callBackUrl="/ui/school.php?p=list">
	<h1><?php echo htmlentities(_("Create a school")); ?></h1>
	<input name="schoolName" id="schoolName" type="text" placeholder="<?php echo htmlentities(_("School Name")); ?>" value="<?php echo $school['name']; ?>" />
	<br/><br/>
	<input name="schoolWebsite" id="schoolWebsite" type="text" placeholder="<?php echo htmlentities(_("School Website URL (without http://)")); ?>" value="<?php echo $school['website']; ?>" />
	<br/><br/>
	<input type="hidden" name="updateSchool" value="<?php echo $id; ?>" />
	<input type="submit" value="<?php echo _("Update school"); ?>" />
</form>
<?php }
?>