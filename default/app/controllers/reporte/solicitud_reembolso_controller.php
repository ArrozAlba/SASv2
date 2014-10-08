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

Load::models('solicitudes/solicitud_servicio', 'solicitudes/hreembolso');

class SolicitudReembolsoController extends BackendController {
    

    /**
     * Método para crear reporte de listado de todos los titulares
     */
    public function listado($formato='html') { 
        $reembolso = new Hreembolso();
        $reembolsos= $reembolso->getListadoReembolsoReporte();
        $this->reembolsos  = $reembolsos;
        $this->page_module = 'Reembolsos cdel sistema ';
        $this->page_format = $formato;
        $this->page_title = 'Listado de titulares del sistema';  
    }
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Titular';
    }
        
    /**
     * Método para crear reporte de listado los titulares  una vez pasados por la busqueda 
     */
    public function listar($field='titular', $value='none', $order='order.titular.asc', $formato='html'){
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;
        $value = strtoupper($value);
        $reembolso = new Hreembolso();
        $reembolsos = $reembolso->getListadoReembolsoFiltrado($field, $value, $order);        
        $this->reembolsos = $reembolsos;
        $this->page_module = 'Rembolsos ';
        $this->page_format = $formato;
        $this->page_title = 'Rembolsos';        
    }       

 
}

