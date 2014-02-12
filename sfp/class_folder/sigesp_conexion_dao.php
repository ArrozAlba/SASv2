<?php
require_once('../librerias/php/adodb/adodb.inc.php');
require_once('../librerias/php/adodb/adodb-active-record.inc.php');

$host=$_SESSION["ls_hostname"]; 
$user=$_SESSION["ls_login"];
$pass=$_SESSION["ls_password"];
$base=$_SESSION["ls_database"];
$db = NewADOConnection("postgres");
$db->NConnect($host,$user,$pass,$base);
//$db->debug=1;
ADOdb_Active_Record::SetDatabaseAdapter($db);
$ADODB_ASSOC_CASE = 0;

?>