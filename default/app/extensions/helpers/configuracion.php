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
class Configuracion {

    protected static $_archivo_ini = NULL;
    protected static $_configuracion = array();

    public static function leer() {
        self::$_archivo_ini = APP_PATH . "config/config.ini";
        self::$_configuracion = parse_ini_file(self::$_archivo_ini, FALSE);

        foreach (self::$_configuracion as $variable => $valor) {
            if ($valor == 1) {
                self::$_configuracion[$variable] = 'On';
            } elseif (empty($valor)) {
                self::$_configuracion[$variable] = 'Off';
            }
        }

        return self::$_configuracion;
        //return self::$_configuracion = parse_ini_file(self::$_archivo_ini, FALSE, INI_SCANNER_RAW);
    }

    public static function set($variable, $valor) {
        self::$_configuracion["$variable"] = $valor;
    }

    public static function guardar() {
        $html = <<<TEXTO
;; Configuracion de Aplicacion

; Explicación de la Configuración:

; name: Es el nombre de la aplicación
; timezone: Es la zona horaria que usará el framework
; production: Indica si esta en producción
; database: base de datos a utilizar
; dbdate: Formato de Fecha por defecto de la Applicación
; debug: muestra los errores en pantalla (On|off)
; log_exceptions: muestra las excepciones en pantalla (On|off)
; charset: codificacion de caracteres
; cache_driver: driver para la cache (file, sqlite, memsqlite)
; metadata_lifetime: Tiempo de vida de la metadata cacheada
; locale: Localicazion
; routes: para activar los routes.ini


; ¡¡¡ ADVERTENCIA !!!
; Cuando se efectua el cambio de production=Off, a production=On, es necesario eliminar
; el contenido del directorio de cache de la aplicacion para que se renueve
; la metadata

TEXTO;

        $html .= "[application]" . PHP_EOL;
        foreach (self::$_configuracion as $variable => $valor) {
            if ( in_array($valor , array('On', 'Off')) || is_numeric($valor) ){
                    $html .= "$variable = $valor" . PHP_EOL;                    
                }else{
                    $valor = h($valor);
                    $html .= "$variable = \"$valor\"" . PHP_EOL;                    
                }
        }
        return file_put_contents(self::$_archivo_ini, $html);
    }

}
