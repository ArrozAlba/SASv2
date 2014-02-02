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
class MyConfig
{

    /**
     * Actualiza la configuracion de los archivos .ini de la app.
     * 
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public static function save($file)
    {
        $html = PHP_EOL;
        $vars = Config::get($file);
        foreach ($vars as $seccion => $datas) {
            $html .="[$seccion]" . PHP_EOL;
            foreach ($datas as $variable => $valor) {
                if ( in_array($valor , array('On', 'Off')) || is_numeric($valor) ){
                    $html .= "$variable = $valor" . PHP_EOL;                    
                }else{
                    $valor = h($valor);
                    $html .= "$variable = \"$valor\"" . PHP_EOL;                    
                }
            }
            $html .= PHP_EOL;
        }
        return file_put_contents(APP_PATH . "config/$file.ini", $html);
    }

}
