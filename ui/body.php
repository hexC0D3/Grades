<?php 
global $ntdb;
$num = 5;
$array = $ntdb->getLastGradesofSubjectsOfCurrentUser($num);
$average_a = 0;
$ver = 0;
echo "<table id='gradesTable' cellspacing='0'>";
foreach($array as $key => $value){
	echo "<tr><td class='subject'>".$key."</td>";
	$s = 0;
	for($i=0;$i<$num;$i++){
		if(isset($value[$i])){
			$secondClass = $value[$i] > 4 ? "" : " negativeMark";
			echo "<td class='mark".$secondClass."'>".$value[$i]."</td>";
			$s += $value[$i];
		}else{
			echo "<td class='mark'>-</td>";
		}
	}
	$average = round($s/count($value), 2);
	if($average>0){
		$average_a+=$average;
		$secondClass = $average > 4 ? "" : " negativeMark";
		$ver++;
	}else{
		$average="-";
		$secondClass="";
	}
	echo "<td class='mark averageMark".$secondClass."'>".$average."</td>";
	echo "</td>";
}
echo "</table>";
?>
<canvas id="theChart" width="500px" height="575px"></canvas>
<div class="clear"></div>
<?php
if($ver != 0){
	$x = ($average_a/$ver);
	$x = round($x, 2);
	$secondClass = $x > 4 ? "" : " class='negativeMark'";
}else{
	$x="-";
	$secondClass="";
}

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
$user = getCurrentUser();
$color1 = $user['color1'];
$color2 = $user['color2'];
?>
<div id="averageMark"> <?php echo _("Average Mark") ." : <span ".$secondClass.">". $x . "</span>"; ?></div>
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