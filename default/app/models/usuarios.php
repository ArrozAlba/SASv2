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
class Usuarios extends ActiveRecord
{
//put your code here
//    public $debug = true;

    const ROL_DEFECTO = 1;

    protected function initialize()
    {
        $min_clave = Config::get('config.application.minimo_clave');
        //$this->belongs_to('roles');
        $this->has_many('auditorias');
        $this->has_many('roles_usuarios');
        $this->has_and_belongs_to_many('roles', 'model: roles', 'fk: roles_id', 'through: roles_usuarios', 'key: usuarios_id');
        $this->validates_presence_of('login', 'message: Debe escribir un <b>Login</b> para el Usuario');
        $this->validates_presence_of('clave', 'message: Debe escribir una <b>Contraseña</b>');
        $this->validates_length_of('clave', 50, $min_clave, "too_short: La Clave debe tener <b>Minimo {$min_clave} caracteres</b>");
        $this->validates_presence_of('clave2', 'message: Debe volver a escribir la <b>Contraseña</b>');
        $this->validates_presence_of('nombres', 'message: Debe escribir su <b>nombre completo</b>');
        $this->validates_presence_of('email', 'message: Debe escribir un <b>correo electronico</b>');
        $this->validates_email_in('email', 'message: Debe escribir un <b>correo electronico</b> válido');
        $this->validates_uniqueness_of('login', 'message: El <b>Login</b> ya está siendo utilizado');
    }

    protected function before_save()
    {
        if (isset($this->clave2) and $this->clave !== $this->clave2) {
            Flash::error('Las <b>CLaves</b> no Coinciden...!!!');
            return 'cancel';
        } elseif (isset($this->clave2)) {
            $this->clave = MyAuth::hash($this->clave);
        }
    }

    /**
     * Devuelve los usuarios de la bd Paginados.
     * 
     * @param  integer $pagina numero de pagina a mostrar
     * @return array          resultado de la consulta
     */
    public function paginar($pagina = 1)
    {
        return $this->paginate("page: $pagina");
    }

    public function numAcciones($pagina = 1)
    {
        $cols = "usuarios.*,COUNT(auditorias.id) as num_acciones";
        //$join = "INNER JOIN roles ON roles.id = usuarios.roles_id ";
        $join = "LEFT JOIN auditorias ON usuarios.id = auditorias.usuarios_id";
        $group = 'usuarios.' . join(',usuarios.', $this->fields);
        $sql = "SELECT $cols FROM $this->source $join GROUP BY $group";
        return $this->paginate_by_sql($sql, "page: $pagina");
        //comentada la siguiente linea debido a que el active record lanzaba
        //una advertencia de que el count esta devolviendo mas de 1 registro,
        //esto es por el group by
        //return $this->paginate("page: $pagina", "columns: $cols", "join: $join", "group: $group");
    }

    /**
     * Realiza un cambio de clave de usuario.
     * 
     * @param  array $datos datos del formulario
     * @return boolean devuelve verdadero si se realizó el update
     */
    public function cambiarClave(array $datos)
    {
        $this->clave = $datos['nueva_clave'];
        $this->clave2 = $datos['nueva_clave2'];
        return $this->update();
    }

    /**
     * Guarda los datos de un usuario, y los roles que va a poseer
     *
     * @param array $data datos que se enviaron del form
     * @param array $roles ids de los roles a guardar para el user
     * @return boolean retorna TRUE si se pudieron guardar los datos con exito
     */
    public function guardar($data, $roles)
    {
        $this->begin();

        if (!$this->save($data)) {
            $this->rollback();
            return FALSE;
        }

        $rolUser = Load::model('roles_usuarios');

        if (is_array($roles) && count($roles)) {

            if (!$rolUser->delete_all("usuarios_id = '$this->id'")) {
                Flash::error('No se pudieron Guardar los Roles para el usuario');
                $this->rollback();
                return FALSE;
            }

            foreach ($roles as $e) {
                if (!$rolUser->asignarRol($this->id, $e)) {
                    Flash::error('No se pudieron Guardar los Roles para el usuario');
                    $this->rollback();
                    return FALSE;
                }
            }
        } else {
            Flash::error('Debe seleccionar al menos un Rol para el Usuario');
            $this->rollback();
            return FALSE;
        }

        $this->commit();
        return TRUE;
    }

    /**
     * Crea un arreglo con pares idRol => nombreRol con los roles
     * que posee el usuario.
     * 
     * @return array
     */
    public function rolesUserIds()
    {
        $roles_id = array();
        if ($this->roles_usuarios) {
            foreach ($this->roles_usuarios as $e) {
                $roles_id["$e->roles_id"] = $e->roles_id;
            }
        } else {
            Flash::warning('Hay algo extraño, este user no tiene roles asignados aun...!!!');
        }
        return $roles_id;
    }

    /**
     * Obtiene un arreglo con los nombres de los roles que posee el usuario.
     * @return array
     */
    public function getRolesNames()
    {
        $res = Load::model('roles')->distinct('rol',
                        "join: INNER JOIN roles_usuarios ru ON ru.roles_id = roles.id AND ru.usuarios_id = '$this->id'");
        return join(', ', $res);
    }

    /**
     * Realiza el proceso de registro de un usuario desde el frontend.
     * @return boolean true si la operación fué exitosa.
     */
    public function registrar()
    {
        $this->activo = 0; //por defecto las cuentas están desactivadas
        $clave = $this->clave;

        $this->begin(); //iniciamos una transaccion

        if ($this->save() && Load::model('roles_usuarios')->asignarRol($this->id, self::ROL_DEFECTO)) {
            $hash = md5($this->login . $this->id . $this->clave);
            $correo = Load::model('correos');
            if ($correo->enviarRegistro($this)) {
                $this->commit();
                return TRUE;
            } else {
                Flash::error("No se Pudo Mandar el Correo...!!!");
                $this->rollback();
                return FALSE;
            }
        } else {
            $this->rollback();
            return FALSE;
        }
    }

    /**
     * Faltan revisar cosas acá, porque si ya un user se habia activado y
     * algun admin lo bloquea, por el correo de gmail se puede volver a
     * activar
     *
     * @param <type> $id_usuario
     * @param <type> $hash
     * @return <type>
     */
    public function activarCuenta($id_usuario, $hash)
    {
        if ($this->find_first((int) $id_usuario)) { //verificamos la existencia del user
            if (md5($this->login . $this->id . $this->clave) === $hash) {
                $this->activo = 1;
                if ($this->save()) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Obtiene la plantilla a usar por el usuario.
     * 
     * Devuelve la plantilla del usuario ( que tenga plantilla asignada )
     * que tenga la mayor cantidad de privilegios.
     * 
     * @param  array $roles_id array con los ids de los roles.
     * @return string           plantilla a usar.
     */
    public function obtenerPlantilla($roles_id)
    {
        $res = Load::model('roles')->find_by_sql('select plantilla,MAX(c)
                from (select roles_id, count(id) as c
                      from roles_recursos GROUP BY roles_id) as t
                INNER JOIN roles on roles.id = t.roles_id
                WHERE roles.id IN (' . join(',',$roles_id). ')
                GROUP BY plantilla');
        return $res->plantilla;
    }

}
