<?php
/*
MySQL (Community) Server Installation on 32-bit Windows XP running Apache

On Windows, the recommended way to run MySQL is to install it as a Windows service, whereby MySQL starts and stops automatically when Windows starts and stops. A MySQL server installed as a service can also be controlled from the command line commands, or with the graphical Services utility like phpMyAdmin.

PHP ---> MySQL CONNECTORS (php_mysql.dll and php_mysqli.dll as extensions)
MySQL provides the mysql and mysqli extensions for the Windows operating system on http://dev.mysql.com/downloads/connector/php/ for MySQL version 4.1.16 and higher, MySQL 5.0.18, and MySQL 5.1. As with enabling any PHP extension in php.ini (such as php_mysql.dll), the PHP directive extension_dir should be set to the directory where the PHP extensions are located.

MySQL is no longer enabled by default, so the php_mysql.dll DLL must be enabled inside of php.ini. Also, PHP needs access to the MySQL client library. A file named libmysql.dll is included in the Windows PHP distribution and in order for PHP to talk to MySQL this file needs to be available to the Windows systems PATH.

Following PHP Script is useful to test PHP connection with MySQL.
*/

//$connect = mysql_connect("Your Host Name", "MySQL root directory", 'MySQL password, if any');
//$connect = mysql_connect("Host Name or Address - 127.0.0.1", "root", 'password');
$connect = mysql_connect("localhost", "root", 'orangepi');
if ($connect){
echo "Congratulations!\n<br>";
echo "Successfully connected to MySQL database server.\n<br>";
}else{
$error = mysql_error();
echo "Could not connect to the database. Error = $error.\n<br>";
exit();
}

// Closing connection
$close = mysql_close($connect);
if ($close){
echo "\n<br>";
echo "Now closing the connection...\n<br>";
echo "MySQL connection closed successfully as well.\n<br>";
}else{
echo "There's a problem in closing MySQL connection.\n<br>";
}
exit();
?>
