<html>

<head>

<title>Rooms Info</title>
</head>

<body>

<h1 align="center">Rooms Info</h1>
	
<?php 

	$link = mysqli_connect( 
				'localhost',
				'root',
				'orangepi',				// CHANGE USER NAME !!!
				'rooms');
	
	$rooms_list = mysqli_query($link, 'SELECT * FROM rooms ORDER BY number;');
	

	while( $rooms_list_row = mysqli_fetch_assoc($rooms_list) )
	{
		?>
		
		<table border="1" width="100%">
		<tr>
			<td colspan="7"><h1 align="center">Room_<?php echo $rooms_list_row["number"] . " " . $rooms_list_row["name"]; ?></h1></td>
		</tr>
		
		<tr align="center"> 
			<td><b>Date/Time</b></td>
			<td><b>Temperature</b></td>
			<td><b>Humidity</b></td>
			<td><b>CO2</b></td>
			<td><b>Odour</b></td>
			<td><b>Light</b></td>
			<td><b>Pressure</b></td>
		</tr>
	
		<?php
		$sql_string = 'SELECT  date, temperature, humidity, co2, odor, light, pressure FROM rooms_temp WHERE rooms_number=' . $rooms_list_row["number"] . ' ORDER BY date DESC;';
		if ( !($avg_room_info = mysqli_query($link, $sql_string) ) )
			echo "ERROR";
		
		while ( $avg_room_row = mysqli_fetch_assoc($avg_room_info) )
		{
			?>				

			<tr>
				<td><?php echo $avg_room_row["date"]; ?></td>
				<td><?php echo $avg_room_row["temperature"]; ?></td>
				<td><?php echo $avg_room_row["humidity"]; ?></td>
				<td><?php echo $avg_room_row["co2"]; ?></td>
				<td><?php echo $avg_room_row["odor"]; ?></td>
				<td><?php echo $avg_room_row["light"]; ?></td>
				<td><?php echo $avg_room_row["pressure"]; ?></td>
			</tr>
					
			<?php				
		}

		?>
		</table>
		<?php
	
		mysqli_free_result($avg_room_info);
		
	}
	
	mysqli_free_result($rooms_list); 
	mysqli_close($link); 
		
?>

</body>

</html>
