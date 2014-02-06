<?
	/***********************************************************************************
	 * 		Cabecera
	***********************************************************************************/

	//Extraemos las variables
	extract($_GET);
	extract($_POST);

	//Falta comprobar ataques sql

	define("ROOT_PATH",realpath(dirname(__FILE__)."/.."));

	//Cargamos la configuracin


	require_once(ROOT_PATH."/files/functions.inc.php");
	require_once(ROOT_PATH."/files/db.inc.php");
	require_once(ROOT_PATH."/files/JSON.php");


	//Cargamos la configuración a un array
	//$config=xml2array(ROOT_PATH."/config.xml");
	include(ROOT_PATH."/config.php");

	//Configuración del acceso a base de datos
	define("DB_SERVER",				$config['config']['bda']['server']);
	define("DB_TYPE",				$config['config']['bda']['type']);
	define("DB_DBA",				$config['config']['bda']['bda']);
	define("DB_USER",				$config['config']['bda']['user']);
	define("DB_PASS",				$config['config']['bda']['password']);

	//Cookies
	define("COOKIETIME",			time()+$config['config']['cookies']['time']);

	//Sesión
	define("SESSION_NAME",			$config['config']['session']['name']);

	//Formato de fechas
	define("DATE_FORMAT",			$config['config']['date']['format']);
	define("DATE_FORMAT_EXT",		$config['config']['date']['format_ext']);

	//Charset
	define("CHARSET",				$config['config']['charset']);

	//Mail
	define("ADMIN_NAME",			$config['config']['mail']['to_name']);
	define("ADMIN_MAIL",			$config['config']['mail']['to_mail']);
	define("SMTP_SERVER",			$config['config']['mail']['smtp']['server']);
	define("SMTP_USER",				$config['config']['mail']['smtp']['user']);
	define("SMTP_PASS",				$config['config']['mail']['smtp']['password']);

	//Calculamos la ruta relativa
	$y=substr_count($config['config']['url']['path'],"/");
	$x=substr_count($_SERVER['PHP_SELF'],"/");
	$diff=$x-$y;

	if ($diff==0) 	$relative_path=".";
	if ($diff>0) {
		for($i=0;$i<$diff;$i++)
		{
			$relative_path.="..";
			if ($i<$diff-1) $relative_path.="/";
		}
	}


	//Paths
	//define("THEME_PATH",			$relative_path."/themes/$theme/");
	//define("TEMPLATE_PATH",			THEME_PATH."templates/");
	//define("CSS_PATH",				THEME_PATH."css/");
	//define("MEDIA_PATH",			THEME_PATH."media/");

	//Url
	define("URL",					$config['config']['url']['protocol']."://".$_SERVER['HTTP_HOST'].$config['config']['url']['path']);
	//define("URL_MEDIA",				URL."themes/$theme/media/");
	//define("URL_CSS",				URL."themes/$theme/css/");


	define("GOOGLE_MAPS_KEY",				$config['config']['google_maps_key']);

	/*********** fin de la configuración **************/

	//Inicializamos la session
	session_name(SESSION_NAME);
	session_start();

	//Conectamos con la base de datos
	$connection=new db(DB_TYPE);
	$connection->connect(DB_SERVER,DB_USER,DB_PASS,DB_DBA);


?>