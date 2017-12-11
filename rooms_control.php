<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Rooms Control</title>
</head>

<body>

<?php 
	require '_constants.php';		// Including file with constants
	$link = mysqli_connect( 
				$dbHostname,
				$dbUserName,
				$dbPassword,				// CHANGE PASSWORD !!!
				$dbDatabase);
	
	$rooms_list = mysqli_query($link, 'SELECT * FROM rooms ORDER BY number ASC;');
	
?>

<form method="GET" action="ChangeNumber.php" target="interactive" onsubmit="frm_submit(this); return false; this.reset();">
	
	<script>
		// Defining variables
		var i=1;
		var roomNumbers = new Array();
		var roomNames = new Array();
		var roomFrequecys = new Array();
		var roomOutputs = new Array();
		var outputStates = new Array("Off", "On", "Auto");
		
	</script>
		
	
	
	<p><span lang="ru">Номер комнаты: </span>
		<select size="1" id="sel_roomNumber" name="oldNumber" onchange="numberChanged()">
			<?php	// Заполняем массивы с данными по комнатам
				while( $rooms_list_row = mysqli_fetch_assoc($rooms_list) )
				{
			?>
					<script>
						roomNumbers[i] = <?php echo $rooms_list_row['number']; ?>;
						roomNames[i] = <?php echo '"' . $rooms_list_row['name'] . '"'; ?>;
						roomFrequecys[i] = <?php echo $rooms_list_row['frequency']; ?>;
						roomOutputs[i] = <?php echo $rooms_list_row['activated']; ?>;
						i=i+1;
					// Попутно заполняем выпадающий список с номерами комнат
					</script>
			
			<option> <?php echo $rooms_list_row['number']; } ?> </option>
		</select>
			
		&nbsp;&nbsp;&nbsp; <input type="checkbox" id="cb_changeNumber" name="cb_changeNumber" onchange="numberChanging()" value="ON"><span lang="ru"> Поменять на:</span>
		<input type="text" id="txt_newNumber" name="newNumber" size="20" disabled="true" onchange="" value="" >
		<input type="Button" value="Удалить" id="b_delRoom" name="b_delRoom" onclick="deleteRoom()">
		<input type="Button" value="Очистить данные" id="b_clearRoom" name="b_clearRoom" onclick="clearRoom()">
	</p>
	
	<p>
		<span lang="ru">Название: <input type="text" id="txt_roomName" name="roomName" size="20" disabled="true" onchange="" value=<?php echo $room_name ?> ></span>&nbsp;&nbsp;&nbsp;
		<input type="checkbox" id="cb_roomNameChanged"  name="cb_roomNameChanged" onchange="nameChanging()" value="ON"><span lang="ru">Изменить</span>
		<input type="text" id="txt_newName" name="newName" size="20" disabled="true" value="" >
	</p>
	
	<p>
		<span lang="ru">Частота опроса: <input type="text" id="txt_roomFrequency" name="sampleFrequency" size="20" disabled="true" value=<?php echo $sample_frequency ?>>&nbsp;&nbsp;&nbsp;
		<input type="checkbox" id="cb_freqChanged" name="cb_freqChanged" onchange="frequencyChanging()" value="ON">Задать</span>
		<input type="text" id="txt_newFrequency" name="newFrequency" size="20" disabled="true" value="" >
	</p>
	
	<p>
		<span lang="ru">Активировать выход: <select size="1" disabled="true" id="sel_outputState" name="activateOutput">
			<option selected>Auto</option>
			<option>On</option>
			<option>Off</option>
		</select>&nbsp;&nbsp;&nbsp; 
		<input type="checkbox" id="cb_outputChanged" name="cb_outputChanged" onchange="outputChanging()" value="ON">Задать</span>
		<select size="1" disabled="true" id="sel_newState" name="newOutput">
			<option selected>Auto</option>
			<option>On</option>
			<option>Off</option>
		</select>
	</p>
	<p>&nbsp;</p>
	<p><input type="submit" value="Отправить" name="B1"><input type="reset" value="Сбросить" name="B2"></p>
</form>

<div style="height:20%; position:absolute; bottom:0px;" id="statusBar">
	
</div>

<script>
	
	var sel_number = document.getElementById("sel_roomNumber");
	var txt_name = document.getElementById("txt_roomName");
	var txt_frequency = document.getElementById("txt_roomFrequency");
	var sel_output = document.getElementById("sel_outputState");
	
	var cb_nameChanged = document.getElementById("cb_roomNameChanged");
	var cb_freqChanged = document.getElementById("cb_freqChanged");
	var cb_outputChanged = document.getElementById("cb_outputChanged");
	var cb_changeNumber = document.getElementById("cb_changeNumber");
	
	var txt_newNumber = document.getElementById("txt_newNumber");
	var txt_newName = document.getElementById("txt_newName");
	var txt_newFrequency = document.getElementById("txt_newFrequency");
	var sel_newOutput = document.getElementById("sel_newState");
	
	numberChanged();		// Вызываем эту функцию, чтобы подгрузились правильные значения для первой комнаты по окончании загрузки страницы
	
	function clearRoom()
	{
		var xhr = new XMLHttpRequest();

		xhr.open('GET', 'clearRoom.php?number=' + sel_number.value, true);

		xhr.send();

		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState != 4) return;

			b_clearRoom.value = 'Готово!';				// ВНИМАНИЕ!! b_delRoom используется напрямую обращение к ID без getElementById
			b_clearRoom.disabled = false;

			b_clearRoom.onmouseover = function()
			{
				b_clearRoom.value = 'Очистить данные';
				// alert("MOUSE");
				b_clearRoom.onmouseover = null;
			}

			if (xhr.status != 200) 
			{
				
				statusBar.innerHTML = 'ERROR ' + xhr.status + ': ' + xhr.statusText;
				// alert(xhr.status + ': ' + xhr.statusText);
				// alert ("Ошибка");
			} else 
			{
				//alert(xhr.responseText);
				statusBar.innerHTML = xhr.responseText;
				// alert ("Room Deleted OK");
			}

		}

		b_clearRoom.value = 'Очищаю...'; // (2)
		b_clearRoom.disabled = true;
	}
	
	
	function deleteRoom()		// Надо как-то получить выбранный номер комнаты, чтобы знать, что удаляем. Возможно надо по нажатию кнопки постить в новый файл deleteRoom.php?number=...
	{
		var xhr = new XMLHttpRequest();

		xhr.open('GET', 'deleteRoom.php?number=' + sel_number.value, true);

		xhr.send();

		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState != 4) return;

			b_delRoom.value = 'Готово!';				// ВНИМАНИЕ!! b_delRoom используется напрямую обращение к ID без getElementById
			b_delRoom.disabled = false;

			b_delRoom.onmouseover = function()
			{
				b_delRoom.value = 'Удалить';
				// alert("MOUSE");
				b_delRoom.onmouseover = null;
			}

			if (xhr.status != 200) 
			{
				
				statusBar.innerHTML = 'ERROR ' + xhr.status + ': ' + xhr.statusText;
				// alert(xhr.status + ': ' + xhr.statusText);
				// alert ("Ошибка");
			} else 
			{
				//alert(xhr.responseText);
				statusBar.innerHTML = xhr.responseText;
				// alert ("Room Deleted OK");
			}

		}

		b_delRoom.value = 'Удаляю...'; // (2)
		b_delRoom.disabled = true;
	}
	
	function numberChanged()	// При выборе другого номера комнаты подгружаем в другие поля ее данные
	{
		txt_name.value = roomNames[roomNumbers.indexOf(parseInt(sel_number.value))];		
		txt_frequency.value = roomFrequecys[roomNumbers.indexOf(parseInt(sel_number.value))];
		sel_output.value = outputStates[roomOutputs[roomNumbers.indexOf(parseInt(sel_number.value))]];
		// txt_name.value = roomNames[sel_number.value];		
		// txt_frequency.value = roomFrequecys[sel_number.value];
		// sel_output.value = outputStates[roomOutputs[sel_number.value]];
		
	}
	
	function numberChanging()
	{
		if (cb_changeNumber.checked == true)
		{
			txt_newNumber.disabled = false;
			// cb_NameChanged.disabled = true;
			// cb_freqChanged.disabled = true;
		}
		else
		{
			txt_newNumber.disabled = true;
			// cb_NameChanged.disabled = false;
			// cb_freqChanged.disabled = false;
		}	
	}
	
	function nameChanging()
	{
		if (cb_nameChanged.checked == true)
		{
			txt_newName.disabled = false;
			// cb_NameChanged.disabled = true;
			// cb_freqChanged.disabled = true;
		}
		else
		{
			txt_newName.disabled = true;
			// cb_NameChanged.disabled = false;
			// cb_freqChanged.disabled = false;
		}	
	}
	
	function frequencyChanging()
	{
		if (cb_freqChanged.checked == true)
		{
			txt_newFrequency.disabled = false;
			// cb_NameChanged.disabled = true;
			// cb_freqChanged.disabled = true;
		}
		else
		{
			txt_newFrequency.disabled = true;
			// cb_NameChanged.disabled = false;
			// cb_freqChanged.disabled = false;
		}	
	}

	
	function outputChanging() 
	{
		if (cb_outputChanged.checked == true)
		{
			sel_newOutput.disabled = false;
			// cb_NameChanged.disabled = true;
			// cb_freqChanged.disabled = true;
		}
		else
		{
			sel_newOutput.disabled = true;
			// cb_NameChanged.disabled = false;
			// cb_freqChanged.disabled = false;
		}
	}

	function frm_submit(f)	// При нажатии на кнопку "Отправить"
	{
		// alert (roomNumbers.indexOf(parseInt(txt_newNumber.value)));
		if (txt_newNumber.value == sel_number.value || txt_newNumber.value == "")	// Если введенное значение не отличается от старого, или пустое
			txt_newNumber.disabled = true;
		else
			if ( roomNumbers.indexOf(parseInt(txt_newNumber.value)) >=0 )		// Если новый номер комнаты совпадает с одним из имеющихся, то не подтверждаем форму
			{
				alert ("Room with this number is already exist");
				statusBar.innerHTML = "Room with this number is already exist";
				txt_newNumber.focus();
				return false;
			}
		
		if (txt_newName.value == txt_name.value || txt_newName.value == "")	// Если введенное значение не отличается от старого, или пустое
			txt_newName.disabled = true;
			
		if (txt_newFrequency.value == txt_frequency.value || txt_newFrequency.value == "")	// Если введенное значение не отличается от старого, или пустое
			txt_newFrequency.disabled = true;
		
		if (sel_newOutput.value == sel_output.value || sel_newOutput.value == "")	// Если введенное значение не отличается от старого, или пустое
			sel_newOutput.disabled = true;
			
		// При смене состояния выходов появлять окошечко, на какое время менять. 0=бесконечно, если не 0, то через это время возвращаемся в режим Авто.
		// Как это реализовать пока не понятно - нужно передавать этот интервал в программу-обработчик
		// Возможно добавить новую первую букву для строк команд помимо R и X, например C (Code), что команда предназначается для программы на Orangepi
		
		statusBar.innerHTML = "Submitting form";
		// alert("Submitting form");
		f.submit();
		window.location.reload(); // Как-то надо форсировать перезагрузку окна после отправки формы... Эта строка не работает
	}
	

</script>



</body>

</html>
