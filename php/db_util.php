<?
/*******************************************************
* db_util.php
* Author: @rpastorelle
*******************************************************/

$database = mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Unable to connect to database, please try again later. ' . mysql_error());
mysql_select_db(DBNAME) or die('Cannot select database. ' . mysql_error());

function dbQuery($sql)
{
	$resource = mysql_query($sql) or die(mysql_error());
	return $resource;
}

function dbAffectedRows()
{
	global $database;

	return mysql_affected_rows($database);
}

function dbFetchArray($resource, $resultType = MYSQL_NUM) {
	return mysql_fetch_array($resource, $resultType);
}

function dbFetchAssoc($resource)
{
	return mysql_fetch_assoc($resource);
}

function dbFetchRow($resource)
{
	return mysql_fetch_row($resource);
}

function dbFreeResult($resource)
{
	return mysql_free_result($resource);
}

function dbNumRows($resource)
{
	return mysql_num_rows($resource);
}

function dbSelect($dbname)
{
	return mysql_select_db($dbname);
}

function dbInsertId()
{
	return mysql_insert_id();
}
?>