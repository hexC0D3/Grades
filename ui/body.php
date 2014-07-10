<body>
	<?php 
	global $ntdb;
	$num = 5;
	$array = $ntdb->getLastGradesOfCurrentUser($num);
// 	print_r($array);
// 	$array["Math"] = array(5,3,2,4.3,4.2,4.5);
// 	$array["German"] = array(5,3,2,4.3,4.2,4.5);
// 	$array["English"] = array(5,3,2,4.3,4.2,4.5);
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
		if($average>0){$ver++;}else{$average="-";}
		$average_a+=$average;
		$secondClass = $average > 4 ? "" : " negativeMark";
		echo "<td class='mark averageMark".$secondClass."'>".$average."</td>";
		echo "</td>";
	}
	echo "</table>";
	?>
	<canvas id="theChart" width="1000%" height="1000%"></canvas>
	<div class="clear"></div>
	<?php
	$x = ($average_a/$ver);
	$secondClass = $x > 4 ? "" : " class='negativeMark'";
	?>
	<div id="averageMark"> <?php echo _("Average Mark") ." : <span ".$secondClass.">". $x . "</span>"; ?></div>
</body>