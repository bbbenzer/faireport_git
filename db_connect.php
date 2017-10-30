<?php

/*
 *  -- ************************************************************
 -- Author		:	PARADOX
 -- Create date	:	25-07-2017
 -- Update date	:	PARADOX
 -- Update By	:   25-07-2017
 -- Description	:	connect [Version 1.0]
 -- ************************************************************
 */

$DB_SERVER = "localhost";// db server
$DB_USER = "root";// db user
$DB_PASSWORD = "";// db password (mention your db password here)
$DB_DATABASE = "fai_fai";// database name

$meConnect = mysql_connect($DB_SERVER, $DB_USER, $DB_PASSWORD) or die("Error conncetion mysql...");
$meDatabase = mysql_select_db($DB_DATABASE);
mysql_query("SET NAMES UTF8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");

set_time_limit(0);

ini_set('mysql.connect_timeout','0');
ini_set('max_execution_time', '0');
?>
