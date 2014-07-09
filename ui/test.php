<?php
require_once('head.php');
checkHead('testData', 'showTestList', 'showTestList', 'showCreateTest', 'showEditTest', 'showTestList');

function testData($data){
	
}
function showTestList(){
	echo "test list";
}
function showCreateTest(){
global $ntdb;
$user = getCurrentUser();
$class = $ntdb->getAllInformationFrom('classes', 'id', $user['classID'])[0];
if($class['adminID']!=$user['id']){
	nt_die(_("You aren't allowed to see this page!"));
}
?>
<form id="createNewTest_form" action="/ui/test.php" method="POST" callBackUrl="/ui/test.php?p=list">
	<h1><?php echo htmlentities(_("Create a test")); ?></h1>
	<input name="testTopic" id="testTopic" type="text" placeholder="<?php echo htmlentities(_("Test Topic")); ?>" />
	<br/><br/>
	<select id="testType" name="testType" placeholder="<?php echo htmlentities(_("Test Type(written, oral, ..)")); ?>">
		<option><?php echo _("written"); ?></option>
		<option><?php echo _("oral"); ?></option>
		<option><?php echo _("multiple choice"); ?></option>
		<option><?php echo _("mixed"); ?></option>
	</select>
	<br/><br/>
	<input name="testDesc" id="testDesc" type="text" maxlength="200" placeholder="<?php echo htmlentities(_("Short Test Description")); ?>" />
	<br/><br/>
	<select id="testSubjectID" name="testSubjectID" placeholder="<?php echo htmlentities(_("Test Subject")); ?>">
		<?php 
		global $ntdb;
		$subjects = $ntdb->getAllInformationFrom('subjects', 'schoolID', getCurrentUser()['schoolID']);
		foreach($subjects as $subject){
			echo "<option value='".$subject['id']."'>".$subject['name']."</option>";
		}
		?>
	</select>
	<br/><br/>
	<input name="testDate" id="testDate" class="datepicker" type="text" placeholder="<?php echo htmlentities(_("Test Date and Time (dd. mm. yyyy)")); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo _("Create a test"); ?>" />
</form>
<?php }
function showEditTest($id){
	
}
?>