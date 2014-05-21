<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las profesiones de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('solicitudes/solicitud_medicina');

class SolicitudMedicinaController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Solicitudes';
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
        $solicitud_medicina = new SolicitudMedicina();        
        $this->solicitud_medicinas = $solicitud_medicina->getListadoSolicitudMedicina($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Solicitudes de medicinas';
    }
    
    /**
     * Método para agregar
     */
    public function agregar() {
        $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('solicitud_medicina')) {
            if(SolicitudMedicina::setSolicitudMedicina('create', Input::post('solicitud_medicina'), array('empresa_id'=>$empresa->id, 'ciudad'=>Input::post('ciudad')))) {
                DwMessage::valid('La solicitud de medicina se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->personas = Load::model('beneficiarios/titular')->getTitularesToJson();
        $this->page_title = 'Agregar Solicitud de medicina';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_solicitud_medicina', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $solicitud_medicina = new SolicitudMedicina();
        if(!$solicitud_medicina->getInformacionSolicitudMedicina($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('solicitud_medicina') && DwSecurity::isValidKey(Input::post('solicitud_medicina_id_key'), 'form_key')) {
            if(SolicitudMedicina::setSolicitudMedicina('update', Input::post('solicitud_medicina'), array('id'=>$id))){
                DwMessage::valid('La solicitud de medicina se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        $this->solicitud_medicina = $solicitud_medicina;
        $this->page_title = 'Actualizar solicitud medicina';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_solicitud_medicina', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $solicitud_medicina = new SolicitudMedicina();
        if(!$solicitud_medicina->getInformacionSolicitudMedicina($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(SolicitudMedicina::setSolicitudMedicina('delete', array('id'=>$solicitud_medicina->id))) {
                DwMessage::valid('La solicitud de medicina se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta solicitud de medicina no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
