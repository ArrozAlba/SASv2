<?php
// Bootstrap de la aplicacion para personalizarlo
// Para cargar cambia en public/index.php el require del bootstrap a app

require_once CORE_PATH . 'kumbia/config.php';

// Lee la configuracion
$config = Config::read('config');

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

if ($config['application']['production']) {
    if (!$config['application']['debug']) {
        error_reporting(0);
        ini_set('display_errors', 'On');
    }
}

// Arranca KumbiaPHP
require_once CORE_PATH . 'kumbia/bootstrap.php';

