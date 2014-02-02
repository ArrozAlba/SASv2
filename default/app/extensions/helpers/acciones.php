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
* @package Helper
* @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
* @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
*/
Load::models('auditorias');

class Acciones {

    static public function add($accion_realizada, $tabla_afectada = NULL) {
        try {
            if (MyAuth::es_valido() &&
                    Config::get('config.application.guardar_auditorias') == true) {
                $auditoria = new Auditorias();
                $auditoria->usuarios_id = Auth::get('id');
                $auditoria->accion_realizada = strip_tags($accion_realizada);
                $auditoria->tabla_afectada = strtoupper(strip_tags($tabla_afectada));
                $auditoria->ip = $_SERVER['REMOTE_ADDR'];
                $auditoria->save();
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

}

