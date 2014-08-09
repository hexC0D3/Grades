<!DOCTYPE html>
<head>
	<title>Grades</title>
	<meta name="viewport" content="user-scalable=no" />
	<link rel="icon" type="image/png" href="/img/favicon.png">
	<style type="text/css">
	<?php require_once('style.css.php'); ?>
	<?php require_once('js/alertify/alertify.core.css'); ?>
	<?php require_once('js/alertify/alertify.default.css'); ?>
	</style>
	<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
	<header>
		<div id="menuBar"><a href="#menuBar"><div id="mobileMenuTrigger" class="fa fa-bars"></div></a><a href="#grades" class="title">Grades</a>
			<ul>
				<?php 
				global $ntdb;
				$user = getCurrentUser();
				$class = $ntdb->getAllInformationFrom('classes', 'id', $user['classID'])[0];
				$school = $ntdb->getAllInformationFrom('schools', 'id', $user['schoolID'])[0];
				?>
				<li class="drop-down-arrow" tabindex="0"><?php echo sanitizeOutput(_("Grades")); ?><ul class="submenu">
					<li><a href='#grades'><?php echo sanitizeOutput(_("Grade List")); ?></a></li>
					<li><a href='#page:/ui/grade.php?p=add'><?php echo sanitizeOutput(_("Add Mark")); ?></a></li>
					
					<li class="seperator"></li>
					
					<li><a href='#page:/ui/test.php?p=list'><?php echo sanitizeOutput(_("Test List")); ?></a></li>
					<li><a href='#page:/ui/test.php?p=create'>"<?php echo sanitizeOutput(_("Create a test")); ?>"</a></li>
				</ul></li>
				<li class="drop-down-arrow" tabindex="0"><?php echo sanitizeOutput(_("Subjects")); ?><ul class="submenu">
					<li><a href='#page:/ui/subjects.php?p=list'><?php echo sanitizeOutput(_("Subject List")); ?></a></li>
					<?php
					if($user['id']==$school['adminID']){
						echo "<li><a href='#page:/ui/subjects.php?p=create'>".sanitizeOutput(_("Create Subject")) . "</a></li>";
					}
					?>
				</ul></li>
				<li class="drop-down-arrow" tabindex="0"><?php echo sanitizeOutput(_("My Class")); ?><ul class="submenu">
					<li><a href='#page:/ui/class.php?p=classroom'><?php echo sanitizeOutput(_("Go to classroom")); ?></a></li>
					<li><a href='#page:/ui/class.php?p=list'><?php echo sanitizeOutput(_("Class List")); ?></a></li>
					<?php
					if($user['classID']==-1){
						echo "<li><a href='#page:/ui/class.php?p=create'>".sanitizeOutput(_("Create a class"))."</a></li>";
					}
					?>
				</ul></li>
				<li class="drop-down-arrow" tabindex="0"><?php echo sanitizeOutput(_("Schools")); ?><ul class="submenu">
					<li><a href='#page:/ui/school.php?p=list'><?php echo sanitizeOutput(_("School List")); ?></a></li>
					<?php
					if($user['schoolID']==-1){
						echo "<li><a href='#page:/ui/school.php?p=create'><".sanitizeOutput(_("Create a school"))."</a></li>";
					}?>
				</ul></li>
				<li class="drop-down-arrow" tabindex="0"><?php echo sanitizeOutput(_("Profile")); ?><ul class="submenu">
					<li><a href='#page:/admin/profile.php'><?php echo sanitizeOutput(_("Settings")); ?></a></li>
					<li><a href='/logout.php'><?php echo sanitizeOutput(_("Logout")); ?></a></li>
				</ul></li>
			</ul>
		</div>
	</header>
	<div id="page">