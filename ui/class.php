<?php
require_once("head.php");
checkHead('postCallbackClass', 'showClassroom', 'showClassList', 'showCreateClass', 'showEditClass', 'showClassroom');

function postCallbackClass($data){
	global $ntdb;
	$user = getCurrentUser();
	if(!empty($data['deleteClass'])){//TODO: Warning message + remove all tests and of this class
		if($ntdb->getAllInformationFrom('classes', 'id', $data['deleteClass'])[0]['adminID']==$user['id']){
			echo "Class wasn't deleted, grades is running in [WIP] mode!";
			//echo $ntdb->removeFromDatabase('classes', 'id', $data['deleteClass']); TODO:Uncomment line if done
		}else{
			echo _("You don't have the permission to do this!");
		}
	}else if(!empty($data['joinClass'])){
		if($user['classID']==-1){
			if($user['schoolID']==$ntdb->getAllInformationFrom('classes', 'id', $data['joinClass'])[0]['schoolID']){//TODO: test
				echo $ntdb->updateInDatabase('users', array('classID'), array($data['joinClass']), 'id', $user['id']);
			}else{
				echo _("You aren't able to join this class!");
			}
		}else{
			echo _("You have already joined a class!");
		}
	}else if(!empty($data['leaveClass'])){//TODO: Warning message: loose all grades + remove all grades
		if($user['classID']==$data['leaveClass']){
			if($ntdb->getAllInformationFrom('classes', 'id', $data['leaveClass'])[0]['adminID']!=$user['id']){
				echo $ntdb->updateInDatabase('users', array('classID'), array(-1), 'id', $user['id']);
			}else{
				echo _("You are not able to leave this class, because you're the admin of it!");
			}
		}else{
			echo _("You aren't a member of this class!");
		}
	}else if(!empty($data['className'])){
		if(isset($user['schoolID']) && $user['schoolID']!=-1){
			if(!empty($data['updateClass'])){
				if($ntdb->isInDatabase('classes', 'id', $data['updateClass'])==true){
					if($ntdb->getAllInformationFrom('classes', 'id', $data['updateClass'])[0]['adminID']==$user['id']){
						echo $ntdb->updateInDatabase('classes', array('name'), array($data['className']), 'id', $data['updateClass']);
					}else{
						echo _("You don't have the permission to do this!");
					}
				}else{
					echo _("This class doesn't exist!");
				}
			}else{
				if($ntdb->isInDatabase('classes', 'adminID', $user['id'])==true){
					echo _("You are already admin of a class!");
				}else{
					//Check if already exists in the same school
					$subjects = $ntdb->getAllInformationFrom('classes', 'name', $data['className']);
					$exists = false;
					foreach($subjects as $subject){
						if($subject['schoolID']==$user['schoolID']){
							$exists=true;
							break;
						}
					}
					if($exists){
						echo _("This class already exists!");
					}else{
						echo $ntdb->addToDatabase('classes', array('schoolID', 'name', 'adminID'), array($user['schoolID'], $data['className'], $user['id']));
						$classID = $ntdb->getAllInformationFrom('classes', 'name', $data['className'])[0]['id'];
						$ntdb->updateInDatabase('users', 'classID', $classID, 'username', $user['username']);
					}
				}
			}
		}else{
			echo _("First you have to join a school!");
		}
	}
}
function showClassroom(){
	global $ntdb;
	$classID = getCurrentUser()['classID'];
	if($classID==-1){
		redirectToHome(_("First you have to join a class!"));
	}else{
		die("Classroom");
	}
}
function showClassList(){?>

<table>
	<thead><tr><th><?php echo htmlentities(_("Class Name")); ?></th><th><?php echo htmlentities(_("Class Admin")); ?></th><th class="actions"><?php echo htmlentities(_("Actions")); ?></th></tr></thead>
	<tbody>
		<?php 
			global $ntdb;
			$array = $ntdb->getAllInformationFromTable('classes');
			usort($array, 'compareByName');
			foreach($array as $val){
				$cAdmin = $ntdb->getAllInformationFrom('users', 'id', $val['adminID'])[0];
				echo "<tr class='classTableRow'><td class='classTableName'>".$val['name']."</td><td class='classTableAdmin'>".$cAdmin['username']."</td><td class='classTableActions'>".getClassTableFunction($val)."</td></tr>";
			}
		?>
	</tbody>
</table>

<?php }
function getClassTableFunction($val){
	$return = "";
	$dis1 = $dis2 = $dis3 = $dis4 = "";
	if(getCurrentUser()['classID']==$val['id']){
		$dis1="disabled";
	}else{
		$dis2="disabled";
	}
	$return .= '
	<form action="/ui/class.php" method="POST" callBackUrl="/ui/class.php?p=my">
		<input type="hidden" name="joinClass" value='.$val['id'].' />
		<input type="submit" class="join" value="'.htmlentities(_("Join")).'" '.$dis1.'/>
	</form>
	<form action="/ui/class.php" method="POST" callBackUrl="/ui/class.php?p=list" warning="true" message="'.htmlentities(_("If you leave this class all test and the related grades will be lost for you! Do you want to continue?")).'">
		<input type="hidden" name="leaveClass" value='.$val['id'].' />
		<input type="submit" class="leave" value="'.htmlentities(_("Leave")).'" '.$dis2.'/>
	</form>
	';
	if($val['adminID']!=getCurrentUser()['id']){
		$dis3="disabled";
		$dis4="disabled";
	}else{
		$return .= '
		<a href="#page:/ui/class.php?p=edit&id='.$val['id'].'"><input type="button" value="'._("Edit").'" '.$dis3.' /></a>
		<form action="/ui/class.php" method="POST" callBackUrl="/ui/class.php?p=list" warning="true" message="'.htmlentities(_("Are you sure, that you want to delete your class? This will delete all tests of this class and also the corresponding grades!")).'">
			<input type="hidden" name="deleteClass" value='.$val['id'].' />
			<input type="submit" class="delete" value="'.htmlentities(_("Delete")).'" '.$dis4.' />
		</form>
		';
	}
	return $return;
}
function showCreateClass(){?>
<form id="createNewSubject_form" action="/ui/class.php" method="POST" callBackUrl="/ui/class.php?p=list">
	<h1><?php echo htmlentities(_("Create a new class")); ?></h1>
	<input name="className" id="className" type="text" placeholder="<?php echo htmlentities(_("Class Name")); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo _("Create a new class"); ?>" />
</form>
<?php }

function showEditClass($id){
	global $ntdb;
	$class = $ntdb->getAllInformationFrom('classes', 'id', $id)[0];
	if($class['adminID']!=getCurrentUser()['id']){
		nt_die(_("You aren't allowed to see this page!"));
	}
	?>
<form id="createNewSubject_form" action="/ui/class.php" method="POST" callBackUrl="/ui/class.php?p=list">
	<h1><?php echo htmlentities(_("Create a new class")); ?></h1>
	<input name="className" id="className" type="text" placeholder="<?php echo htmlentities(_("Class Name")); ?>" value='<?php echo $class['name']; ?>'" />
	<br/><br/>
	<input type="hidden" name="updateClass" value="<?php echo $id; ?>" />
	<input type="submit" value="<?php echo _("Update class"); ?>" />
</form>
<?php }
?>