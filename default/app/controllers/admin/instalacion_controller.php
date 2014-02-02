<?php
/**
* Backend - KumbiaPHP Backend
* PHP version 5
* LICENSE
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
* @package Controller
* @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
* @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
*/
Load::models('instalacion');

class InstalacionController extends AppController {

    protected function before_filter() {
        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') { //seguridad
            header('HTTP/1.0 403 Forbidden');
            exit('No tienes permisos para acceder a esta dirección...!!!');
        }
        if (defined('SIN_MOD_REWRITE')) {
            Flash::error('Debes tener instalado el Mod Rewrite de Apache para poder usar KumbiaPHP');
        }
    }

    public function index($index_entorno = 0) {
        $inst = new Instalacion();
        //$this->entornos_bd = $inst->entornosConexion();
        //$this->entorno = $inst->entorno($index_entorno);
        $this->database = $inst->configuracionEntorno($index_entorno);

        if (Input::hasPost('database')) {
            if ($inst->guardarDatabases($index_entorno, $_POST['database'])) {
                if ($inst->verificarConexion()) {
                    return Router::toAction('paso2');
                }
            } else {
                Flash::error('No se han podido guardar los datos de Configuración');
            }
        } elseif (Input::isAjax()) {
            View::response('ajax', NULL);
        }
    }

    public function paso3() {
        $inst = new Instalacion();
        $this->config = $inst->obtenerConfig();
        $this->entornos = $inst->entornosConexion();
        $this->entornos = array_combine($this->entornos, $this->entornos);
        if (Input::hasPost('config')) {
            if ($inst->guardarConfig(Input::post('config'))) {
                return Router::toAction('instalacion_finalizada');
            } else {
                Flash::warning('No se Pudieron guardar los Datos...!!!');
            }
        }
    }

    public function paso2() {
        $inst = new Instalacion();
        if ($inst->verificarConexion()) {
            $database = $inst->configuracionEntorno(0);
            if ($inst->existeArchivoSql($database['type'])) {
                // $this->tablas_crear = $inst->listarTablasPorCrear();
                $this->tablas_existentes = $inst->listarTablasExistentes();
                $this->sql = APP_PATH . 'config/sql/';
                if ( $inst->instalacionBDCorrecta($this->tablas_existentes) ) {
                    View::response('completado');
                }
            }else{
                Flash::error("No existe el driver de Conexión <b>{$database['type']}</b> para conectarse a la Base de datos"); 
                View::response('error');
            }
        }else{
            View::response('error');
        }
    }

    public function instalacion_finalizada() {
        
    }

    public function quitar_instalacion() {
        Configuracion::leer();
        Configuracion::set('routes', 'Off');
        Configuracion::guardar();
        Router::redirect('/');
    }

}