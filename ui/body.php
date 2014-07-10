<body>
	<?php 
	global $ntdb;
	$num = 5;
	$array = $ntdb->getLastGradesOfCurrentUser($num);
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
	<canvas id="theChart" width="1000%" height="1000%"></canvas>
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
	?>
	<div id="averageMark"> <?php echo _("Average Mark") ." : <span ".$secondClass.">". $x . "</span>"; ?></div>
</body>