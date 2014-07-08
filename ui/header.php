<!Doctype html>
<head>
	<title>Grades</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<style type="text/css">
	<?php require_once('style.css'); ?>
	<?php require_once('js/alertify/alertify.core.css'); ?>
	<?php require_once('js/alertify/alertify.default.css'); ?>
	</style>
</head>
<header>
	<div id="menuBar"><a href="#grades" class="title">Grades</a>
		<ul>
			<li class="drop-down-arrow" tabindex="0"><?php echo htmlentities(_("Grades")); ?><ul class="submenu">
				<li><a href='#page:/ui/grade.php?p=my'><?php echo htmlentities(_("My Grades")); ?></a></li>
				<li><a href='#page:/ui/grade.php?p=list'><?php echo htmlentities(_("Grade List")); ?></a></li>
				<li><a href='#page:/ui/grade.php?p=add'><?php echo htmlentities(_("Add grade")); ?></a></li>
				
				<li class="seperator"></li>
				
				<li><a href='#page:/ui/test.php?p=my'><?php echo htmlentities(_("My tests")); ?></a></li>
				<li><a href='#page:/ui/test.php?p=list'><?php echo htmlentities(_("Test List")); ?></a></li>
				<li><a href='#page:/ui/test.php?p=create'><?php echo htmlentities(_("Create a test")); ?></a></li>
			</ul></li>
			<li class="drop-down-arrow" tabindex="0"><?php echo htmlentities(_("Subjects")); ?><ul class="submenu">
				<li><a href='#page:/ui/subjects.php?p=my'><?php echo htmlentities(_("My Subjects")); ?></a></li>
				<li><a href='#page:/ui/subjects.php?p=list'><?php echo htmlentities(_("Subject List")); ?></a></li>
				<li><a href='#page:/ui/subjects.php?p=create'><?php echo htmlentities(_("Create Subject")); ?></a></li>
			</ul></li>
			<li class="drop-down-arrow" tabindex="0"><?php echo htmlentities(_("My Class")); ?><ul class="submenu">
				<li><a href='#page:/ui/class.php?p=classroom'><?php echo htmlentities(_("Go to classroom")); ?></a></li>
				<li><a href='#page:/ui/class.php?p=list'><?php echo htmlentities(_("Class List")); ?></a></li>
				<li><a href='#page:/ui/class.php?p=create'><?php echo htmlentities(_("Create a class")); ?></a></li>
			</ul></li>
			<li class="drop-down-arrow" tabindex="0"><?php echo htmlentities(_("Schools")); ?><ul class="submenu">
				<li><a href='#page:/ui/school.php?p=my'><?php echo htmlentities(_("My School")); ?></a></li>
				<li><a href='#page:/ui/school.php?p=list'><?php echo htmlentities(_("School List")); ?></a></li>
				<li><a href='#page:/ui/school.php?p=create'><?php echo htmlentities(_("Create a school")); ?></a></li>
			</ul></li>
			<li class="drop-down-arrow" tabindex="0"><?php echo htmlentities(_("Profile")); ?><ul class="submenu">
				<li><a href='/login.php?logout=true#login'><?php echo htmlentities(_("Logout")); ?></a></li>
			</ul></li>
		</ul>
	</div>
</header>
<div id="page">