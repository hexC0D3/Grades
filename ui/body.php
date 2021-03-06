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
		$rel = $subject['relevant']==0 ? false : true;
		
		echo "<tr><td class='subject'><a href='#page:/ui/subjects.php?p=my&id=".$subject['id']."'>".sanitizeOutput($key)."</a></td>";
		for($i=0;$i<$num;$i++){
			if(isset($value[$i])){
				$secondClass = $value[$i] >= 4 ? "" : " negativeMark";
				echo "<td class='mark".$secondClass."'>".sanitizeOutput($value[$i])."</td>";
			}else{
				echo "<td class='mark'></td>";
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
			
			if($rel){
				/** If releavant add average to array **/
				$averages[]=$average;
			}
		}
		$points = "";
		/** Generate points, print it and add it to total **/
		if($average>0){
			if($average >= 4){
				$secondClass = "";
				$p=round($average*2)/2;
				$p = ($p-4);
				if($rel){
					$pointsAV+=$p;
					$points = " (+" . $p . ")";
				}
			}else{
				$secondClass = " negativeMark";
				$p=round($average*2)/2;
				$p = 2*(4-$p);
				if($rel){
					$pointsAV-=$p;
					$points = " (-" . $p . ")";
				}
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
		$pointsAV = $pointsAV>0 ?  " (+" . $pointsAV . ")" : " (" . $pointsAV . ")";
	}else{
		$x="-";
		$secondClass="";
	}
	/** Generate data for graph.js **/
	$array = $ntdb->getLastGradesOfCurrentUserWithTimeStamp($num);
	$subjects = '"'.implode('", "',array_keys($array)).'"';
	$marks = array();
	$dates = array();
	$dates_js=array();
	$realDates=array();
	$min="1";
	$max="6";
	for($i=1;$i<count($marks);$i++){
		$min.=", 1";
		$max.=", 6";
	}
	foreach($array as $key => $timestamp){
		$marks[] = str_replace(",", ".", $timestamp[0]);
		$dates[] = date("d. m. Y",$key);
		$dates_js[] = date("Y, m, d",$key);
	}
	$marks = implode(",", $marks);
	$color1 = $user['color1'];
	$color2 = $user['color2'];
	$labels="'".$dates[0]."','".$dates[count($dates)-1]."'";
	?>
	<div id="averageMark"> <?php echo _("Average Mark")." : <span ".$secondClass.">". $x .$pointsAV. "</span>"; ?></div>
	<script>
	var data =
	{
		labels: [<?php echo $labels; ?>],
		xBegin : new Date(<?php echo $dates_js[0]; ?>),
	    xEnd : new Date(<?php echo $dates_js[count($dates_js)-1]; ?>),
		datasets: [{
			label: "Grades",
			fillColor: "<?php echo "rgba(".hex2rgb($color2).", 0.1)"; ?>",
			strokeColor: "<?php echo $color1; ?>",
			pointColor: "<?php echo $color1; ?>",
			pointStrokeColor: "<?php echo $color2; ?>",
			pointHighlightFill: "<?php echo $color2; ?>",
			pointHighlightStroke: "<?php echo $color2; ?>",
			data: [<?php echo $marks; ?>],
			xPos : [<?php echo "new Date(".implode("), new Date(", $dates_js).")"; ?>],
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
		graphMin:1,
	    graphMax:6,
	    scaleOverride : true,
	    scaleStartValue : 1,
	    scaleSteps : 10,
	    scaleStepWidth : 0.5,
	    inGraphDataShow : false,
	    inGraphDataTmpl : "<%=v3%>",
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
