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
Load::models('auditorias', 'usuarios');

class AuditoriasController extends AdminController {

    public function index($pag= 1) {
        try {
            Session::delete('filtro_auditorias_usuario');
            $usr = new Usuarios();
            $this->usuarios = $usr->numAcciones($pag);
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function usuario($id, $pagina = 1) {
        $this->url = "admin/auditorias/usuario/$id";
        try {
            $usr = new Usuarios();
            $aud = new Auditorias();
            $this->usuario = $usr->find_first($id);
            $this->auditorias = $aud->porUsuario($usr,new Filtro(), $pagina);
            if (!$this->auditorias->items) {
                Flash::info('Este usuario no ha realizado ninguna acción en el sistema...!!!');
                return Router::redirect();
            }
            $this->tablas_afectadas = $aud->tablasAfectadas();
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function resultados_usuario($id,$pagina = 1) {
        $this->url = "admin/auditorias/resultados_usuario/$id";
        try {
            if (Input::hasPost('filtro')) {
                Session::set('filtro_auditorias_usuario', Input::post('filtro'));
            }
            $usr = new Usuarios();
            $aud = new Auditorias();
            $this->usuario = $usr->find_first($id);
            $this->tablas_afectadas = $aud->tablasAfectadas();
            $this->filtro = Session::get('filtro_auditorias_usuario');
            $filtro = new Filtro($aud->get_source(), $this->filtro);
            $this->auditorias = $aud->porUsuario($usr,$filtro ,$pagina);
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        View::select('usuario');
    }

}
