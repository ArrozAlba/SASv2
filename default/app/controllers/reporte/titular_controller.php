<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la visualización de los reportes de las acciones en el sistema
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('beneficiarios/titular');

class TitularController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Titular';
    }
        
 /**
     * Método para listar
     */
    public function listar($formato='html') { 
        $titular = new Titular();
        $this->titulares = $titular->getListadoTitularreporte();
        $this->page_module = 'Titulares del sistema ';
        $this->page_format = $formato;     
    }
        
}

