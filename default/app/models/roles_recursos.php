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
 * @package Modelos
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
class RolesRecursos extends ActiveRecord {

//    public $debug = true;

    protected function initialize() {
        $this->belongs_to('roles');
    }

    /**
     * Devuelve los privilegios que tiene  los roles a los recursos.
     * 
     * Crea un array donde los indicen son la union de el id del rol con
     * el recurso separados por un guion, ejemplo "4-10" y el valor de 
     * esa posicion es el id del registro que tiene la info de la relación.
     * 
     * @return array 
     */
    public function obtener_privilegios() {
        $privilegios = array();
        foreach ($this->find() as $e) {
            $privilegios["{$e->roles_id}-{$e->recursos_id}"] = $e->id;
        }
        return $privilegios;
    }

    /**
     * Elimina todos los registros de la tabla.
     * 
     * @return [type] [description]
     */
    public function eliminarTodos() {
        return $this->delete_all();
    }

    /**
     * Elimina todos los registros por id ejemplo 
     * 
     * <code>
     * 
     * eliminarPorIds("1,2,3,4");
     * 
     * </code>
     * 
     * elimina los registros con id 1,2,3 y 4
     *
     * @param string $ids
     * @return boolean 
     */
    public function eliminarPorIds($ids) {
        if (!empty($ids)) {
            $ids = str_replace('"', "'", Util::encomillar($ids));
            $res = $this->delete_all("id IN ($ids)");
            $this->log();
            return $res;
        }else{
            return true;
        }
    }

    /**
     * Guarda un nuevo registro.
     * 
     * @param  int $rol     id del rol
     * @param  int  $recurso id del recuro
     * @return booelan         
     */
    public function guardar($rol, $recurso) {
        if ($this->existe($rol, $recurso))
            return TRUE;
        
        return $this->create(array(
            'roles_id' => $rol,
            'recursos_id' => $recurso
        ));
    }

    /**
     * Modifica los privilegios en una pagina dada.
     *  
     * @param  array $privilegios privilegios a conceder
     * @param  string $privilegios_a_eliminar 
     * @return boolean  
     */
    public function editarPrivilegios($privilegios, $privilegios_a_eliminar) {
        $this->begin();
        //elimino todo de la bd
        if (!$this->eliminarPorIds($privilegios_a_eliminar)) {
            $this->rollback();
            return FALSE;
        }
        foreach ((array) $privilegios as $e) {
            $data = explode('/', $e); //el formato es 1/4 = rol/recurso
            if (!$this->guardar($data[0], $data[1])) {
                $this->rollback();
                return FALSE;
            }
        }
        $this->commit();
        return TRUE;
    }

    /**
     * Verifica la existencia de un privilegio.
     * 
     * @param  int $rol     id del rol
     * @param  int $recurso id del recurso
     * @return boolean
     */
    public function existe($rol, $recurso) {
        return $this->exists("roles_id = '$rol' AND recursos_id = '$recurso'");
    }

}

