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
class Roles extends ActiveRecord {
    
//    public $debug = true;

    protected function initialize() {
        //relaciones
        $this->has_and_belongs_to_many('recursos', 'model: recursos', 'fk: recursos_id', 'through: roles_recursos', 'key: roles_id');
        $this->has_and_belongs_to_many('usuarios', 'model: usuarios', 'fk: usuarios_id', 'through: roles_usuarios', 'key: roles_id');
        
        //validaciones
        $this->validates_presence_of('rol','message: Debe escribir el <b>Nombre del Rol</b>');
        $this->validates_uniqueness_of('rol','message: Este Rol <b>ya existe</b> en el sistema');
        
    }

    /**
     * Devuelve los recursos a los que un rol tiene acceso.
     * 
     * @return array 
     */
    public function getRecursos(){
        $columnas = "r.*";
        $join = "INNER JOIN roles_recursos as rr ON rr.roles_id = roles.id ";
        $join .= "INNER JOIN recursos as r ON rr.recursos_id = r.id ";
        $where = "roles.id = '$this->id'";
        return $this->find($where, "columns: $columnas" , "join: $join");
    }

}

