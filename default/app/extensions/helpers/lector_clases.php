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
/**
 * Lee clases en archivos php , y obtiene los metodos definidos en las mismas.
 */
class LectorClases {

    protected static $_clase = NULL;
    protected static $_metodos_publicos = array();
    protected static $_metodos_protegidos = array();
    protected static $_metodos_privados = array();
    private static $_archivo = NULL;

    /**
     * Lee un archivo php y obtiene la clase y los metodos definidos en el mismo.
     * 
     * @param  string $dir archivo a escanear
     * @return array
     */
    public static function leerArchivo($dir) {
        if (!file_exists($dir))
            throw new KumbiaException('No existe el archivo en el directorio ' . $dir);
        self::$_archivo = file_get_contents($dir);
        self::$_archivo = preg_replace('/(\/\*)(.*?)(\*\/)/ms', '', self::$_archivo);
        self::$_archivo = preg_replace('/(\/\/).*/', '', self::$_archivo);
        self::obtenerClase();
        self::obtenerMetodosPublicos();
        self::obtenerMetodosProtegidos();
        self::obtenerMetodosPrivados();
        self::$_metodos_publicos = array_diff(self::$_metodos_publicos, self::$_metodos_protegidos, self::$_metodos_privados);
        return self::getEstructuraCompleta();
    }

    /**
     * Obtiene el nombre de la clase
     * 
     */
    protected static function obtenerClase() {
        preg_match('/class\s+?(.+?)\s/', self::$_archivo, $array);
        self::$_clase = $array[1];
    }

    /**
     * Obtiene los metodos publicos de la clase
     */
    protected static function obtenerMetodosPublicos() {
        if (preg_match_all('/function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_publicos = $array[1];
        }
    }

    /**
     * Obtiene los metodos protegidos de la clase.
     */
    protected static function obtenerMetodosProtegidos() {
        if (preg_match_all('/protected\s+?function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_protegidos = $array[1];
        }
    }

    /**
     * Obtiene los metodos privados de la clase.
     */
    protected static function obtenerMetodosPrivados() {
        if (preg_match_all('/private\s+?function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_privados = $array[1];
        }
    }

    /**
     * Devuelve el nombre de la clase.
     * @return string
     */
    public static function getClase() {
        return self::$_clase;
    }

    /**
     * devuelve los metodos publicos de la clase
     * @return array
     */
    public static function getMetodosPublicos() {
        return self::$_metodos_publicos;
    }

    /**
     * devuelve los metodos protegidos de la clase
     * @return array
     */
    public static function getMetodosProtegidos() {
        return self::$_metodos_protegidos;
    }

    /**
     * devuelve los metodos privados de la clase
     * @return array
     */
    public static function getMetodosPrivados() {
        return self::$_metodos_privados;
    }

    /**
     * Devuelve toda la data del controlador actual.
     * @return array 
     */
    public static function getEstructuraCompleta() {
        return array(
            'clase' => self::$_clase,
            'metodos_publicos' => self::$_metodos_publicos,
            'metodos_protegidos' => self::$_metodos_protegidos,
            'metodos_privados' => self::$_metodos_privados
        );
    }

}

