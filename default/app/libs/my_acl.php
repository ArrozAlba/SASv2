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
 * @package Libs
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
class MyAcl
{

    /**
     * Objeto Acl2
     *
     * @var SimpleAcl
     */
    static protected $_acl = null;
    /**
     * arreglo con los templates para cada usuario
     *
     * @var array 
     */
    protected $_templates = array();
    /**
     * Recurso al que se esta intentando acceder
     *
     * @var string 
     */
    protected $_recurso_actual = NULL;

    /**
     * Crea las reglas para el ACL.
     */
    public function __construct()
    {

        //cargamos la lib Acl2 con el adaptador por defecto (SimpleAcl)
        self::$_acl = Acl2::factory();

        //obtenemos todos los roles del usuario actual
        $user = Load::model('usuarios')->find_first(Auth::get('id'));

        $roles_id = $this->_establecerRoles($user->getRoles());

        $this->_establecerTemplate($user->id, $user->obtenerPlantilla($roles_id));

        self::$_acl->user(Auth::get('id'), $roles_id);
    }

    /**
     * Establece los roles del usuario en el ACL
     *
     * @param <type> $roles resultado de una consulta del ActiveRecord
     * @return array arreglo con los ids de los roles a los que pertenece el usuario actual conectado
     */
    protected function _establecerRoles($roles)
    {
        $roles_id = array();
        foreach ($roles as $e) {
            if ($e->activo) { //seguridad
                self::$_acl->parents($e->id, explode(',', $e->padres)); //seteamos los padres del rol
                $this->_establecerRecursos($e->id, $e->getRecursos()); //establecemos los recursos permitidos para el rol
                $roles_id[] = $e->id; //vamos cargando los ids de los roles en un arreglo.
            }
        }
        return $roles_id;
    }

    /**
     * Establece los recursos a los que un rol tiene acceso
     *
     * @param int $rol id del rol
     * @param array $recursos resultado de una consulta del ActiveRecord
     */
    protected function _establecerRecursos($rol, $recursos)
    {
        $urls = array();
        foreach ($recursos as $e) {
            if ($e->activo) { //seguridad, solo recursos activos
                $urls[] = $e->recurso;
            }
        }
        self::$_acl->allow($rol, $urls); //damos permiso al rol de acceder al arreglo de recursos
    }

    /**
     * Indica cual será el template que se le mostrará al usuario
     *
     * Es util cuando queremos mostrar pantallas diferentes dependiendo del user
     *
     * @param int $user id del usuario
     * @param string $template nombre del template a usar para el rol
     */
    protected function _establecerTemplate($user, $template)
    {
        if (!empty($template)) {
            $this->_templates["$user"] = $template; //establecemos el template para el rol
        }
    }

    /**
     * Verifica si el usuario conectado tiene permisos de acceso al recurso actual
     *
     * Por defecto trabaja con el id del usuario en sesión.
     * Ademas hace uso del Router para obtener el recurso actual.
     *
     * @return boolean resultado del chequeo
     */
    public function check()
    {

        $usuario = Auth::get('id');
        $modulo = Router::get('module');
        $controlador = Router::get('controller');
        $accion = Router::get('action');

        if (isset($this->_templates["$usuario"])) {
            if (file_exists(APP_PATH . 'views/_shared/templates/' . $this->_templates["$usuario"] . '.phtml')) {
                View::template("{$this->_templates["$usuario"]}");
            } else {
                Flash::error("No existe el template <b>{$this->_templates["$usuario"]}</b> El cual está siendo usado por el perfil actual");
            }
        }
        if ($modulo) {
            $recurso1 = "$modulo/$controlador/$accion";
            $recurso2 = "$modulo/$controlador/*";  //por si tiene acceso a todas las acciones
            $recurso3 = "$modulo/*/*";  //por si tiene acceso a todos los controladores
        } else {
            $recurso1 = "$controlador/$accion";
            $recurso2 = "$controlador/*"; //por si tiene acceso a todas las acciones
            $recurso3 = "*/*";  //por si tiene acceso a todos los controladores
        }
        $recurso4 = "*";  //por si tiene acceso a todo el sistema
        //si se cumple algunas de las codiciones, el user tiene permiso.
        return self::$_acl->check($recurso1, $usuario) ||
        self::$_acl->check($recurso2, $usuario) ||
        self::$_acl->check($recurso3, $usuario) ||
        self::$_acl->check($recurso4, $usuario);
    }

    /**
     * Verifica si un usuario a excedido el numero de intentos de entrar a un
     * recursos consecutivamente sin tener permisos.
     *
     * @return boolean devuelve true si se ha sobrepasado el limite de intentos
     */
    public function limiteDeIntentosPasado()
    {
        if (Session::has('intentos_acceso')) {
            $intentos = Session::get('intentos_acceso') + 1;
            Session::set('intentos_acceso', $intentos);
            $max_intentos = Config::get('config.application.intentos_acceso');
            return $intentos >= $max_intentos;
        } else {
            Session::set('intentos_acceso', 0);
        }
        return false;
    }

    /**
     * Reinicia el numero de intentos de un usuario por acceder a un recurso en cero.
     */
    public function resetearIntentos()
    {
        Session::set('intentos_acceso', 0);
    }

}
