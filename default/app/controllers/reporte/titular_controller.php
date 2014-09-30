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
     * Método para crear reporte de listado de todos los titulares
     */
    public function listar($formato='html') { 
        $titular = new Titular();
        $this->titulares = $titular->getListadoTitularReporte();
        $this->page_module = 'Titulares del sistema ';
        $this->page_format = $formato;
        $this->page_title = 'Listado de titulares del sistema';  
    }

    /**
     * Método para crear reporte de listado los titulares  una vez pasados por la busqueda 
     */
    public function listado($field='nombre1', $value='none', $order='order.id.asc', $formato='html'){
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;
        $value = strtoupper($value);
        $titular = new Titular();
        $titulares = $titular->getListadoTitularFiltrado($field, $value, $order);        
        $this->titulares = $titulares;
        $this->page_module = 'Titulares del sistema ';
        $this->page_format = $formato;
        $this->page_title = 'Búsqueda de titulares del sistema';        
    }       
}

