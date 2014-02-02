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
class PrivilegiosController extends AdminController {

    public $model = 'roles_recursos';

    public function index($page=1) {
        try {
            $this->results = Load::model('recursos')->paginate("page: $page", 'order: recurso');
            $this->roles = Load::model('roles')->find();
            $this->privilegios = Load::model('roles_recursos')->obtener_privilegios();
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function asignar_privilegios($page = 1) {
        //por ahora este paso no es auditable :-s
        try {
            if (Input::hasPost('priv') || Input::hasPost('privilegios_pagina')) {
                $obj = Load::model('roles_recursos');
                $datos = Input::post('priv');
                $priv_pag = Input::post('privilegios_pagina');
                if ($obj->editarPrivilegios($datos, $priv_pag)) {
                    Flash::valid('Los privilegios Fueron Editados Exitosamente...!!!');
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::toAction("index/$page");
    }

}

