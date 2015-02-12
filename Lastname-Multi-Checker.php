<?php

header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time', 3000000); //300 seconds = 5 minutes

$mysqli = new mysqli("localhost", "root", "", "dbtest");
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

function CReadfile($name, $readname)
{
	return 'http://www.khonthai.com/online/WCHECKLNAME/check_lname.php?lname_write='.$name.'&lname_read='.$readname;
}

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$start = $_GET['start'];
$end = $_GET['end'];

$query = "SELECT name,readname FROM name LIMIT $start,$end";
$mysqli->query("SET NAMES utf8");   


if ($result = $mysqli->query($query)) {

	echo '<table border="1">';
	echo '<tr>';
    echo '<td>#no</td>';
    echo '<td>full</td>';
    echo '<td>read</td>';
    echo '<td>desc</td>';
  	echo '</tr>';
	echo '<tr>';
    /* fetch object array */
    $x = 1; 

    while ($row = $result->fetch_row()) {

    	$name 		= $row[0];
    	$readname 	= $row[1];

		$returned_content = get_data(CReadfile($name,$readname));

		//1799180914
		// $convertcrc32 = crc32($returned_content);

		// if($convertcrc32 == intval(1799180914))[]
		// {
			echo '<tr>';
			echo "<td>".$x."</td>";
			echo "<td>".$name."</td>";
			echo "<td>".$readname."</td>";
			echo "<td>".$returned_content."</td>";
			echo '</tr>';

			$x++;
		// }

		// if($returned_content = $convertcrc32)
		// {
		// 	echo '<input type="text" value="'.$name .'"><input type="text" value="'.$readname .'">='.$returned_content."</br>";
		// }
    }
    
    echo '</table>';

    /* free result set */
    $result->close();
}

/* close connection */
$mysqli->close();
 
?>


