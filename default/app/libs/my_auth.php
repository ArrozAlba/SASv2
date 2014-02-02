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
class MyAuth
{

    /**
     * Namespace de las cookies y el hash de clave que se va a encriptar
     * Recordar que si se cambian, se deben actualizar las claves en la bd.
     */ 
    protected static $_clave_sesion = 'backend_kumbiaphp';

    /**
     * Realiza el proceso de autenticación de un usuario en el sistema.
     * @param  string  $user      
     * @param  string  $pass      
     * @param  boolean $encriptar 
     * @return boolean             
     */
    public static function autenticar($user, $pass, $encriptar = TRUE)
    {
        $pass = $encriptar ? self::hash($pass) : $pass;
        $auth = new Auth('class: usuarios',
                        'login: ' . $user,
                        'clave: ' . $pass,
                        "activo: 1");
        if ($auth->authenticate()) {
            if (Input::post('recordar')) {
                self::setCookies($user, $pass);
            } else {
                self::deleteCookies();
            }
        }
        return self::es_valido();
    }

    /**
     * Verifica que un usuario haya iniciado sesion en la app.
     * 
     * @return boolean
     */
    public static function es_valido()
    {
        return Auth::is_valid();
    }

    /**
     * Cierra la sesion de un usuario en la app.
     * 
     */
    public static function cerrar_sesion()
    {
        Auth::destroy_identity();
        self::deleteCookies();
    }

    /**
     * Crea una encriptacion de la clave para el usuario.
     * 
     * Usada para la verificación al loguear y cuando se crea un user en la bd.
     * 
     * @param  string $pass 
     * @return string       
     */
    public static function hash($pass)
    {
        return crypt($pass, self::$_clave_sesion);
    }

    /**
     * Verfica si existen cookies para un usuario.
     * 
     * @return boolean
     */
    public static function cookiesActivas()
    {
        return isset($_COOKIE[md5(self::$_clave_sesion)]) && is_array(self::getCookies());
    }

    /**
     * Establece las cookies para un user.
     * 
     * @param string $user 
     * @param string $pass 
     */     
    public static function setCookies($user, $pass)
    {
        setcookie(md5(self::$_clave_sesion), serialize(array(
                    'login' => $user,
                    'clave' => $pass
                )), time() + 60 * 60 * 24 * 30);
    }

    /**
     * Obtiene las cookies de un usuario.
     * 
     * @return array|NULL
     */
    public static function getCookies()
    {
        if (isset($_COOKIE[md5(self::$_clave_sesion)])) {
            return unserialize($_COOKIE[md5(self::$_clave_sesion)]);
        } else {
            return NULL;
        }
    }

    /**
     * Elimina los cookies que un usuario tenga guardadas.
     */
    public static function deleteCookies()
    {
        setcookie(md5(self::$_clave_sesion),'',time()- 1);
        unset($_COOKIE[md5(self::$_clave_sesion)]);
    }

}

