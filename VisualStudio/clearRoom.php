<?php

	$link = mysqli_connect( 
				'localhost',
				'root',
				'orangepi',				// CHANGE PASSWORD !!!
				'rooms');
				
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>без имени</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.30.1" />
</head>

<body>
	<p>
		
		
		
	<?php	
		
		echo "Удаляем комнату... <br>";
		
		if ( mysqli_query($link, 'DELETE FROM actions WHERE rooms_number=' . $_GET['number'] . ';') )
			echo "Room number " . $_GET['number'] . " data cleared from actions table <b>OK</b>" . "<br>";
		else
			echo "<b>Error</b> clearing room number " . $_GET['number'] . " data from actions table: " . mysqli_error($link) . "<br>";
			
		if ( mysqli_query($link, 'DELETE FROM rooms_temp WHERE rooms_number=' . $_GET['number'] . ';') )
			echo "Room number " . $_GET['number'] . " data cleared from rooms_temp table <b>OK</b>" . "<br>";
		else
			echo "<b>Error</b> clearing room number " . $_GET['number'] . " data from rooms_temp table: " . mysqli_error($link) . "<br>";
			
		if ( mysqli_query($link, 'DELETE FROM rooms_data WHERE rooms_number=' . $_GET['number'] . ';') )
			echo "Room number " . $_GET['number'] . " data cleared from rooms_data table <b>OK</b>" . "<br>";
		else
			echo "<b>Error</b> clearing room number " . $_GET['number'] . " data from rooms_data table: " . mysqli_error($link) . "<br>";
	
		
		mysqli_close($link); 

	?>
	
	</p>
	
</body>

</html>
