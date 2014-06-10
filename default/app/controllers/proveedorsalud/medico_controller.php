<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los Medicos de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('proveedorsalud/medico');

class MedicoController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Proveedores de Salud';
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
        $medico = new Medico();        
        $this->medicos = $medico->getListadoMedico($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Medicos';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('medico')) {
            if(Medico::setMedico('create', Input::post('medico'))) {
                DwMessage::valid('El medico se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Medico';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_medico', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $medico = new Medico();
        if(!$medico->getInformacionMedico($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('medico') && DwSecurity::isValidKey(Input::post('medico_id_key'), 'form_key')) {
            if(Medico::setMedico('update', Input::post('medico'))){
                DwMessage::valid('El medico se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->medico = $medico;
        $this->page_title = 'Actualizar medico';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_medico', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $medico = new Medico();
        if(!$medico->getInformacionMedico($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Medico::setMedico('delete', array('id'=>$medico->id))) {
                DwMessage::valid('El medico se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Este medico no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}