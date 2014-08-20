<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que gestiona todos los estados de los usuarios
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)  
 */

class UsuarioPregunta extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('usuario');
    }
      

}
