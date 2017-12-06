<?php
/*
 * ChangeNumber.php
 * 
 * Copyright 2017 Dmitry <Dmitry@HOME_R650>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
 
	$link = mysqli_connect( 
				'localhost',
				'root',
				'orangepi',				// CHANGE PASSWORD !!!
				'rooms');
				
	// Меняем номер комнаты во всех таблицах базы данных
	
	// Начало SQL-запроса для добавления строки в таблицу действий. Добавляем начало вида "INSERT INTO actions (rooms_number, action) VALUES ('01', 'X01" чтобы дальше продолжать команду
	
	
	if ($_GET['oldNumber'] < 10 )		// Добавляем 0 перед числом, если номер комнаты меньше 10
			$oldNum = "0" . $_GET['oldNumber']
		else
			$oldNum = $_GET['oldNumber'];
			
	$sql_actionQuery = "INSERT INTO actions (rooms_number, action) VALUES ('" . $_GET['oldNumber'] . "', '" . "X" . $oldNum;
	$updateActions = 0;		// Нужно ли будет добавлять строку в таблицу действий для отправки на комнату? Например если меняем только имя, то в этом нет необходимости
	
	$room_number = $_GET['oldNumber'];	// Переменная актуального номера комнаты для будущих запросов. Если номер не менялся, то она остается oldNum, а если менялся, то станет newNum
		
	if ( isset($_GET['newNumber']) )	// Если отправлен новый номер комнаты
	{
		$sql_query = 'UPDATE rooms_temp SET rooms_number=' . $_GET['newNumber'] . ' WHERE rooms_number=' . $_GET['oldNumber'] . ';';
		mysqli_query($link, $sql_query);
		$sql_query = 'UPDATE rooms_data SET rooms_number=' . $_GET['newNumber'] . ' WHERE rooms_number=' . $_GET['oldNumber'] . ';';
		mysqli_query($link, $sql_query);
		mysqli_query($link, $sql_query);
		$sql_query = 'UPDATE actions SET rooms_number=' . $_GET['newNumber'] . ' WHERE rooms_number=' . $_GET['oldNumber'] . ';';
		mysqli_query($link, $sql_query);
		$sql_query = 'UPDATE rooms SET number=' . $_GET['newNumber'] . ' WHERE number=' . $_GET['oldNumber'] . ';';
		mysqli_query($link, $sql_query);
	
		// Создаем команду в таблице actions, чтобы обработчик отправил на комнату сигнал о смене номера
		if ($_GET['newNumber'] < 10 )		// Добавляем 0 перед числом, если номер комнаты меньше 10
			$newNum = "0" . $_GET['newNumber'];
		else
			$newNum = $_GET['newNumber'];
					
		$sql_actionQuery = $sql_actionQuery . "N" . $newNum;
		$room_number = $_GET['newNumber'];
		$updateActions = 1;
	}
	
		
	if ( isset($_GET['newName']) )	// Если отправлено новое название комнаты
	{
		// Имя нам нужно менять только в базе данных, на саму комнату ничего не отправляем
		// Используем переменную актуального номера комнаты $room_number, т.к. номер комнаты мог уже поменяться
		// Не забыть, что для строкового значения нужно добавить кавычки в отличии от остальных запросов
		$sql_query = 'UPDATE rooms SET name="' . $_GET['newName'] . '" WHERE number=' . $room_number . ';';
		mysqli_query($link, $sql_query);

	}
	
	if ( isset($_GET['newFrequency']) )	// Если отправлена новая частота опросов комнаты
	{
		// Новый интервал нужно записать и в БД и послать на комнату в формате "I0120" для 1 раза в 1200 секунд
		
		// Обновление БД
		// Используем переменную актуального номера комнаты $room_number, т.к. номер комнаты мог уже поменяться
		$sql_query = 'UPDATE rooms SET frequency=' . $_GET['newFrequency'] . ' WHERE number=' . $room_number . ';';
		mysqli_query($link, $sql_query);
		
		
		// Формирование строки команды для комнаты
		if ($_GET['newFrequency'] < 10 )		// Добавляем впереди необходимое количество нулей
			$newInterval = "000" . $_GET['newFrequency'];
		elseif ($_GET['newFrequency'] < 100 )
			$newInterval = "00" . $_GET['newFrequency'];
		elseif ($_GET['newFrequency'] < 1000 )
			$newInterval = "0" . $_GET['newFrequency'];
		else $newInterval = $_GET['newFrequency'];
	
		$sql_actionQuery = $sql_actionQuery . "I" . $newInterval;
		$updateActions = 1;
	}
	
	if ( isset($_GET['newOutput']) )	// Если отправлено новое состояние выхода
	{
		// Новое состояние выхода нужно записать в БД и отправить на комнату в формате "A01" - активировать выход 01, "D01" - деактивировать выход 01
		
		// Обновление БД
		// Перевод On/Off/Auto в число 1/0/2
		if ($_GET['newOutput'] == "On")
			$outputState = 1;
		elseif ($_GET['newOutput'] == "Off")
			$outputState = 0;
		elseif ($_GET['newOutput'] == "Auto")
			$outputState = 2;	
				
		// Используем переменную актуального номера комнаты $room_number, т.к. номер комнаты мог уже поменяться
		$sql_query = 'UPDATE rooms SET frequency=' . $outputState . ' WHERE number=' . $room_number . ';';
		mysqli_query($link, $sql_query);
		
		
		// Формирование строки команды для комнаты
		if ($_GET['newOutput'] == "On")
			$sql_actionQuery = $sql_actionQuery . "A04";		// Пока все шлем на выход 4, потом надо сделать его выбор
		elseif ($_GET['newOutput'] == "Off" || $_GET['newOutput'] == "Auto")
			$sql_actionQuery = $sql_actionQuery . "D04";		// Пока все шлем на выход 4, потом надо сделать его выбор
		
		$updateActions = 1;
	}
	
	$sql_actionQuery = $sql_actionQuery . "#');";	// Завершаем команду символами "#');"
	if ($updateActions == 1)		// Если требуется отправить действие на комнату
		mysqli_query($link, $sql_actionQuery);		// Добавить в таблицу actions новое действие для этой комнаты
		
		
	mysqli_close($link);  // Закрываем базу данных
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
			echo $sql_actionQuery;
		
		?>
		
		<script>
		
			window.parent.reload();	// Как-то надо форсировать перезагрузку головного окна после отправки данных формы, эта строка не помогает
		</script>
	
	</p>
	
</body>

</html>
