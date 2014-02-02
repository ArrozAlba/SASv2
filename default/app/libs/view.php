<?php

/**
 * Esta clase permite extender o modificar la clase ViewBase de Kumbiaphp.
 *
 * @category KumbiaPHP
 * @package View
 * */
// @see KumbiaView
require_once CORE_PATH . 'kumbia/kumbia_view.php';

class View extends KumbiaView {

    /**
     * Renderiza un menú para el usuario conectado y para el entorno especificado.
     * @param  int $entorno 
     */
    public static function renderMenu($entorno) {
        if (MyAuth::es_valido()) {
            echo Menu::render(Auth::get('id'), $entorno);
        }
    }

    /**
     * Muestra las excepciones generadas y crea un log de las mismas.
     * 
     * @param  KumbiaException $e [description]
     */
    public static function excepcion(KumbiaException $e) {
        Flash::warning('Lo sentimos, Ha Ocurrido un Error...!!!');
        if (Config::get('config.application.log_exception') || !PRODUCTION) {
            Flash::error($e->getMessage());
        }
        if (!PRODUCTION) {
            Flash::error($e->getTraceAsString());
        }
        Logger::critical($e); //comentar en caso de error de que no se pueda escribir en los logs.
        Flash::info('Si el problema persiste por favor informe al administrador del sistema...!!!');
    }
	
    /**
     * Muestra la vista de notFound de la app.
     * 
     */
	public function notFound(){
		throw new KumbiaException(NULL, 'no_controller');
	}

}
