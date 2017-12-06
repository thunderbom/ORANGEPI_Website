<html>

<head>

<title>Rooms Info</title>
</head>

<body>
	
<?php 

/* Подключение к серверу MySQL */ 
$link = mysqli_connect( 
            'localhost',  /* Хост, к которому мы подключаемся */ 
            'root',       /* Имя пользователя */ 
            'orangepi',   /* Используемый пароль */ 
            'rooms');     /* База данных для запросов по умолчанию */ 

if (!$link) { 
   printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
   exit; 
} 

/* Посылаем запрос серверу */ 
if ($result = mysqli_query($link, 'SELECT * FROM rooms')) 
{ 

	$cols_count = mysqli_field_count($link);

	print("Table rooms:\n");
	echo "<br>"; 

	/* Выборка результатов запроса */ 
	while( $row = mysqli_fetch_row($result) )
	{ 
		
		for ($i=0; $i<$cols_count; $i++)
		{
			?>
			<?php echo $row[$i]; ?>
			|
			<?php
		}
		?>
		<br> <br>
		
		<?php
	} 

	/* Освобождаем используемую память */ 
	mysqli_free_result($result); 
} 

/* Закрываем соединение */ 
mysqli_close($link); 
?>

</body>

</html>
