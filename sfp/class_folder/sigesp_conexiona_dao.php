<?php
//error_reporting(E_ALL); # report all errors
ini_set("display_errors", "0"); # do not echo any errors
define('ADODB_ERROR_LOG_TYPE',3);
define('ADODB_ERROR_LOG_DEST','../logs/errors.log');
require_once('../librerias/php/adodb/adodb-errorhandler.inc.php');
require_once('../librerias/php/adodb/adodb.inc.php');
require_once('../librerias/php/adodb/adodb-exceptions.inc.php');
require_once('../librerias/php/adodb/adodb-active-record.inc.php');

$server = 'facturacioncheo';
$user = 'dba';
$pwd = '123';
$database = 'db_facturacion_sigesp_test';
$db = NewADOConnection('sqlanywhere://dba:123@facturacioncheo/db_facturacion_sigesp_test');

ADOdb_Active_Record::SetDatabaseAdapter($db);
//$db->debug=0;
$db->Connect($server, $user, $pwd, $database);

//$rs = $db->Execute('select * from sch_cliente');

//$db = NewADOConnection('mysqlt://root@localhost/db_sfp_2008');

//ADOdb_Active_Record::SetDatabaseAdapter($db);
$ADODB_ASSOC_CASE = 0;
?>