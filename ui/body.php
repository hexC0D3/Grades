<?php
global $ntdb;
$user = getCurrentUser();

$num = 5;
$array = $ntdb->getLastGradesofSubjectsOfCurrentUser($num);
$grades=$ntdb->getAllInformationFrom('grades', 'userID', $user['id']);

$averages=array();
/** Generate table with grades **/
if(!empty($array)){
	echo "<table id='gradesTable' cellspacing='0'>";
	$pointsAV=0;
	foreach($array as $key => $value){
		$subject=$ntdb->getAllInformationFrom('subjects', 'name', $key)[0];
		
		echo "<tr><td class='subject'><a href='#page:/ui/subjects.php?p=my&id=".$subject['id']."'>".sanitizeOutput($key)."</a></td>";
		for($i=0;$i<$num;$i++){
			if(isset($value[$i])){
				$secondClass = $value[$i] > 4 ? "" : " negativeMark";
				echo "<td class='mark".$secondClass."'>".sanitizeOutput($value[$i])."</td>";
			}else{
				echo "<td class='mark'>-</td>";
			}
		}
		/** Get all marks of subject and calc the average mark of subject **/
		$average = array();
		foreach($grades as $grade){
			$test=$ntdb->getAllInformationFrom('tests', 'id', $grade['testID'])[0];
			if($test['subjectID']==$subject['id']){
				$average[]=$grade['mark'];
			}
		}
		if(count($average)<=0){
			$average="-";
		}else{
			$average = round(array_sum($average)/count($average), 2);
			
			/** Add average to array **/
			$averages[]=$average;
		}
		$points = "";
		/** Generate points, print it and add it to total **/
		if($average>0){
			if($average > 4){
				$secondClass = "";
				$p=($average-4);
				$pointsAV+=$p;
				$points = " (+" . $p . ")";
			}else{
				$secondClass = " negativeMark";
				$p=(2*(4-$average));
				$pointsAV-=$p;
				$points = " (-" . $p . ")";
			}
		}else{
			$average="-";
			$secondClass="";
		}
		echo "<td class='mark averageMark".$secondClass."'>".$average."<span class='points'>".$points."</span></td>";
		echo "</td>";
	}
	echo '</table>
	
<canvas id="theChart" width="500px" height="575px"></canvas>';
	
	if(isMobile()){
		echo '<script>
	$("#theChart").attr("width", $(window).width());
	</script>';
	}else{
		echo '<script>
	$("#theChart").attr("width", ($(window).width())/2.75);
	</script>';
	}
	?>
	<div class="clear"></div>
	<?php
	/** Recalc and re-round average mark **/
	$count = count($averages);
	$sum = array_sum($averages);
	
	if($count > 0){
		$x = ($sum/$count);
		$x = round($x, 2);
		if($x > 4){
			$secondClass = "";
		}else{
			$secondClass = " class='negativeMark'";
		}
		$pointsAV = $pointsAV>0 ?  " (+" . $pointsAV . ")" : " (-" . $pointsAV . ")";
	}else{
		$x="-";
		$secondClass="";
	}
	/** Generate data for graph.js **/
	$array = $ntdb->getLastGradesOfCurrentUserWithTimeStamp(7);
	$subjects = '"'.implode('", "',array_keys($array)).'"';
	$marks = array();
	$dates = array();
	foreach($array as $key => $timestamp){
		$marks[] = $timestamp[0];
		$dates[] = date("d. M",$key);
	}
	$marks = implode(",", $marks);
	$dates = '"'.implode('", "', $dates).'"';
	$color1 = $user['color1'];
	$color2 = $user['color2'];
	
	?>
	<div id="averageMark"> <?php echo _("Average Mark") ." : <span ".$secondClass.">". $x .$pointsAV. "</span>"; ?></div>
	<script>
	var data =
	{
		labels: [<?php echo $dates; ?>],
		datasets: [{
			label: "Grades",
			fillColor: "<?php echo "rgba(".hex2rgb($color2).", 0.1)"; ?>",
			strokeColor: "<?php echo $color1; ?>",
			pointColor: "<?php echo $color1; ?>",
			pointStrokeColor: "<?php echo $color2; ?>",
			pointHighlightFill: "<?php echo $color2; ?>",
			pointHighlightStroke: "<?php echo $color2; ?>",
			data: [<?php echo $marks; ?>]
		}]
	};
	var options =
	{
		scaleShowGridLines : false,
		scaleGridLineColor : "<?php echo $color1; ?>",
		scaleGridLineWidth : 1,
		bezierCurve : true,
		bezierCurveTension : 0.4,
		pointDot : true,
		pointDotRadius : 4,
		pointDotStrokeWidth : 1,
		pointHitDetectionRadius : 10,
		datasetStroke : true,
		datasetStrokeWidth : 2,
		datasetFill : true,
	};
	$( document ).ready(function() {
		var ctx = document.getElementById("theChart").getContext("2d");
		var myLineChart = new Chart(ctx).Line(data, options);
	});
	</script>
	<?php
}else{
	echo "<h1>".sanitizeOutput(_("Welcome to Grades"))."</h1><br/><div class='clear'></div>";
	echo "<h2 style='float:none;'>".sanitizeOutput(_("You can now start with joining a school and a class. After that you can start adding grades."))."</h2>";
}