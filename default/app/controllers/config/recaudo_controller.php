<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las recaudoes de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('config/recaudo');

class RecaudoController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Configuraciones';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='order.nombre.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $recaudo = new Recaudo();        
        $this->recaudos = $recaudo->getListadoRecaudo($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Recaudos';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('recaudo')) {
            if(Recaudo::setRecaudo('create', Input::post('recaudo'))) {
                DwMessage::valid('El recaudo se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Recaudo';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_recaudo', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $recaudo = new Recaudo();
        if(!$recaudo->getInformacionRecaudo($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('recaudo') && DwSecurity::isValidKey(Input::post('recaudo_id_key'), 'form_key')) {
            if(Recaudo::setRecaudo('update', Input::post('recaudo'))){
                DwMessage::valid('La recaudo se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->recaudo = $recaudo;
        $this->page_title = 'Actualizar recaudo';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_recaudo', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $recaudo = new Recaudo();
        if(!$recaudo->getInformacionRecaudo($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Recaudo::setRecaudo('delete', array('id'=>$recaudo->id))) {
                DwMessage::valid('El recaudo se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta recaudo no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}