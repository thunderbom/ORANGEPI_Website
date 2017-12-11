<html>

<head>

<title>Rooms Info</title>
</head>

<body>

<h1 align="center">Rooms Info</h1>
	
<?php 

	require '_constants.php';		// Including file with constants
	$link = mysqli_connect( 
				$dbHostname,
				$dbUserName,
				$dbPassword,				// CHANGE PASSWORD !!!
				$dbDatabase);
	
	$rooms_list = mysqli_query($link, 'SELECT * FROM rooms;');
	

	while( $rooms_list_row = mysqli_fetch_row($rooms_list) )
	{
		$sql_string = 'SELECT rooms_number, temperature, humidity, co2, odor, light, date FROM rooms_data WHERE rooms_number=' . $rooms_list_row[0] . ' ORDER BY date DESC LIMIT 1;';
		$avg_room_info = mysqli_query($link, $sql_string);
		
		$sql_string = 'SELECT rooms_number, temperature, humidity, co2, odor, light, date FROM rooms_temp WHERE rooms_number=' . $rooms_list_row[0] . ' ORDER BY date DESC LIMIT 1;';
		
		$temp_room_info = mysqli_query($link, $sql_string);
		$temp_room_row = mysqli_fetch_assoc($temp_room_info); // Better to do it with If, but then how to insert data in the second column?
		
		if ($avg_room_row = mysqli_fetch_assoc($avg_room_info) )
		{
		
			
			?>				

			<table border="1" width="500" id="room1">
				<tr>
					<td colspan="3">
					<h2 align="center"><?php echo $rooms_list_row[1]; ?></h2>
					</td>
				</tr>
				<tr>
					<td>Properties:</td>
					<td>
					<p align="center"><b>Current</b></td>
					<td>
					<p align="center"><b>Average</b></td>
				</tr>
				
					<tr>
					<td>Room number:</td>
					<td colspan="2" align="center">
						<?php echo $avg_room_row["rooms_ID"]; ?>
					 
					</td>
				</tr>
				<tr>
					<td>Temperature:</td>
					<td><?php echo $temp_room_row["temperature"]; ?></td>
					<td><?php echo $avg_room_row["temperature"]; ?></td>
				</tr>
				<tr>
					<td>Humidity:</td>
					<td><?php echo $temp_room_row["humidity"]; ?></td>
					<td><?php echo $avg_room_row["humidity"]; ?></td>
				</tr>

				<tr>
					<td>CO2:</td>
					<td><?php echo $temp_room_row["co2"]; ?></td>
					<td><?php echo $avg_room_row["co2"]; ?></td>
				</tr>
				<tr>
					<td>Odour:</td>
					<td><?php echo $temp_room_row["odor"]; ?></td>
					<td><?php echo $avg_room_row["odor"]; ?></td>
				</tr>
				<tr>
					<td>Light:</td>
					<td><?php echo $temp_room_row["light"]; ?></td>
					<td><?php echo $avg_room_row["light"]; ?></td>
				</tr>
				<tr>
					<td>Date/Time:</td>
					<td><?php echo $temp_room_row["date"]; ?></td>
					<td><?php echo $avg_room_row["date"]; ?></td>
				</tr>
			</table>
					
					
		<?php				
		}
		mysqli_free_result($avg_room_info);
		
	}
	
	mysqli_free_result($rooms_list); 
	mysqli_close($link); 
		
?>

</body>

</html>
