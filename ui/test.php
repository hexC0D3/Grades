<?php
require_once('head.php');
checkHead('testData', 'showMyTests', 'showTestList', 'showCreateTest', 'showEditTest', 'showMyTests');

function testData($data){
	
}
function showMyTests(){
	echo "my tests";
}
function showTestList(){
	echo "test list";
}
function showCreateTest(){ ?>
<form id="createNewTest_form" action="/ui/test.php" method="POST" callBackUrl="/ui/test.php?p=list">
	<h1><?php echo htmlentities(_("Create a test")); ?></h1>
	<input name="testTopic" id="testTopic" type="text" placeholder="<?php echo htmlentities(_("Test Topic")); ?>" />
	<br/><br/>
	<input name="testType" id="testType" type="text" placeholder="<?php echo htmlentities(_("Test Type (written, oral,..)")); ?>" />
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
	<input name="testDate" id="testDate" type="text" placeholder="<?php echo htmlentities(_("Test Date and Time (dd. MM. yyyy HH:mm)")); ?>" />
	<br/><br/>
	<input type="submit" value="<?php echo _("Create a test"); ?>" />
</form>
<?php }
function showEditTest($id){
	
}
?>