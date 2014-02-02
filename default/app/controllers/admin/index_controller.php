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
class IndexController extends AdminController {

    public function index() {
        try {
            $this->config = Configuracion::leer();
            if (Input::hasPost('config')) {
                foreach (Input::post('config') as $variable => $valor) {
                    Configuracion::set($variable, $valor);
                }
                if (Configuracion::guardar()) {
                    Flash::valid('La Configuración fue Actualizada Exitosamente...!!!');
                    Acciones::add("Editó la Configuración de la aplicación", 'archivo config.ini');
                    $this->config = Configuracion::leer();
                } else {
                    Flash::warning('No se Pudieron guardar los Datos...!!!');
                }
                $this->config = Configuracion::leer();
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

}
