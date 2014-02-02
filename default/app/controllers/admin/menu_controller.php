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
Load::models('menus');

class MenuController extends AdminController {

    /**
     * Luego de ejecutar las acciones, se verifica si la petición es ajax
     * para no mostrar ni vista ni template.
     */
    protected function after_filter() {
        if (Input::isAjax()) {
            View::select(NULL, NULL);
        }
    }

    public function index($pagina = 1) {
        try {
            $menus = new Menus();
            $this->menus = $menus->menus_paginados($pagina);
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function crear() {
        $this->titulo = 'Crear Menu';
        try {
            if (Input::hasPost('menu')) {
                $menu = new Menus(Input::post('menu'));

                if ($menu->save()) {
                    Flash::valid('El Menu fué agregado Exitosamente...!!!');
                    if (!Input::isAjax()) {
                        return Router::redirect();
                    }
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function editar($id) {
        $this->titulo = 'Editar Menu';
        try {
            View::select('crear');

            $id = (int) $id;

            $menu = new Menus();

            $this->menu = $menu->find_first($id);

            if ($this->menu) {//verificamos la existencia del menu
                if (Input::hasPost('menu')) {

                    if ($menu->update(Input::post('menu'))) {
                        Flash::valid('El Menu fué actualizado Exitosamente...!!!');
                        if (!Input::isAjax()) {
                            return Router::redirect();
                        }
                    } else {
                        Flash::warning('No se Pudieron Guardar los Datos...!!!');
                    }
                }
            } else {
                Flash::warning("No existe ningun menú con id '{$id}'");
                if (!Input::isAjax()) {
                    return Router::redirect();
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function activar($id) {
        try {
            $id = (int) $id;

            $menu = new Menus();

            if (!$menu->find_first($id)) {
                Flash::warning("No existe ningun menú con id '{$id}'");
            } elseif ($menu->activar()) {
                Flash::valid("El menu <b>{$menu->nombre}</b> Esta ahora <b>Activo</b>...!!!");
            } else {
                Flash::warning("No se Pudo Activar el menu <b>{$menu->nombre}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function desactivar($id) {
        try {
            $id = (int) $id;

            $menu = new Menus();

            if (!$menu->find_first($id)) {
                Flash::warning("No existe ningun menú con id '{$id}'");
            } elseif ($menu->desactivar()) {
                Flash::valid("El menu <b>{$menu->nombre}</b> Esta ahora <b>Inactivo</b>...!!!");
            } else {
                Flash::warning("No se Pudo Desactivar el menu <b>{$menu->menu}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function eliminar($id = NULL) {
        try {
            if (is_int($id)) {
                $menu = new Menus();

                if (!$menu->find_first($id)) {
                    Flash::warning("No existe ningun menú con id '{$id}'");
                } elseif ($menu->delete()) {
                    Flash::valid("El Menu <b>{$menu->nombre}</b> fué Eliminado...!!!");
                } else {
                    Flash::warning("No se Pudo Eliminar el Menu <b>{$menu->nombre}</b>...!!!");
                }
            } elseif (is_string($id)) {
                $menu = new Menus();
                if ($menu->delete_all("id IN ($id)")) {
                    Flash::valid("Los Menús <b>{$id}</b> fueron Eliminados...!!!");
                } else {
                    Flash::warning("No se Pudieron Eliminar los Menús...!!!");
                }
            } elseif (Input::hasPost('menu_id')) {
                $this->menus = Input::post('menu_id');
                return;
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

}
