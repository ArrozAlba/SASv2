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
Load::models('recursos');

class RecursosController extends AdminController {

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
            $recursos = new Recursos();
            $this->recursos = $recursos->paginate("page: $pagina");
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function crear() {
        try {
            $this->titulo = 'Crear Recurso';

            if (Input::hasPost('recurso')) {
                $recurso = new Recursos(Input::post('recurso'));
                if ($recurso->save()) {
                    Flash::valid('El Recurso Ha Sido Agregado Exitosamente...!!!');
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
        $this->titulo = 'Editar Recurso';
        try {
            $id = (int) $id;

            View::select('crear');

            $recurso = new Recursos();
            $this->recurso = $recurso->find_first($id);

            if ($this->recurso) {//validamos la existencia del recurso.
                if (Input::hasPost('recurso')) {
                    if ($recurso->update(Input::post('recurso'))) {
                        Flash::valid('El Recurso ha sido Actualizado Exitosamente...!!!');
                        if (!Input::isAjax()) {
                            return Router::redirect();
                        }
                    } else {
                        Flash::warning('No se Pudieron Guardar los Datos...!!!');
                        unset($this->recurso); //para que cargue el $_POST en el form
                    }
                }
            } else {
                Flash::warning("No existe ningun recurso con id '{$id}'");
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
            $rec = new Recursos();

            $id = (int) $id;

            if (!$rec->find_first($id)) {
                Flash::warning("No existe ningun recurso con id '{$id}'");
            } elseif ($rec->activar()) {
                Flash::valid("El recurso <b>{$rec->recurso}</b> Esta ahora <b>Activo</b>...!!!");
            } else {
                Flash::warning("No se Pudo Activar el Recurso <b>{$rec->recurso}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function desactivar($id) {
        try {
            $rec = new Recursos();

            $id = (int) $id;

            if (!$rec->find_first($id)) {
                Flash::warning("No existe ningun recurso con id '{$id}'");
            } elseif ($rec->desactivar()) {
                Flash::valid("El recurso <b>{$rec->recurso}</b> Esta ahora <b>Inactivo</b>...!!!");
            } else {
                Flash::warning("No se Pudo Desactivar el Recurso <b>{$rec->recurso}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function eliminar($id = NULL) {
        try {
            $rec = new Recursos();

            if (is_int($id)) {

                if (!$rec->find_first($id)) {
                    Flash::warning("No existe ningun recurso con id '{$id}'");
                } elseif ($rec->delete()) {
                    Flash::valid("El recurso <b>{$rec->recurso}</b> ha sido Eliminado...!!!");
                } else {
                    Flash::warning("No se Pudo Eliminar el Recurso <b>{$rec->recurso}</b>...!!!");
                }
            } elseif (is_string($id)) {
                if ($rec->delete_all("id IN ($id)")) {
                    Flash::valid("Los Recursos <b>{$id}</b> fueron Eliminados...!!!");
                } else {
                    Flash::warning("No se Pudieron Eliminar los Recursos...!!!");
                }
            } elseif (Input::hasPost('recursos_id')) {
                $this->ids = Input::post('recursos_id');
                return;
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function escaner($pagina = 1) {
        try {
            $recurso = new Recursos();
            $this->recursos = $recurso->obtener_recursos_nuevos($pagina);
            if (Input::hasPost('guardar') || Input::hasPost('descripcion')) {
                if ($recurso->guardar_nuevos()) {
                    $this->recursos = $recurso->obtener_recursos_nuevos($pagina);
                    Input::delete();
                    Flash::valid('Los Recursos Fueron Guardados Exitosamente...!!!');
                } else {
                    Flash::warning('Por favor Complete los datos requeridos he intente guardar nuevamente');
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

}
