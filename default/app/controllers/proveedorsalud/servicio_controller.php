<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los servicioes de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      
 * @copyright   infoalex
 */

Load::models('proveedorsalud/servicio');

class ServicioController extends BackendController {
    
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
        $servicio = new servicio();        
        $this->servicios = $servicio->getListadoservicio($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de servicios';
    }
     /**
     * Método para obtener servicios
     */
    
        //accion que busca en las servicioes y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $servicios = Load::model('proveedorsalud/servicio')->obtener_servicios($busqueda);
            die(json_encode($servicios)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }        
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('servicio')) {
            if(servicio::setservicio('create', Input::post('servicio'))) {
                DwMessage::valid('El servicio se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar servicio';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_servicio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $servicio = new servicio();
        if(!$servicio->getInformacionservicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('servicio') && DwSecurity::isValidKey(Input::post('servicio_id_key'), 'form_key')) {
            if(servicio::setservicio('update', Input::post('servicio'))){
                DwMessage::valid('La servicio se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->servicio = $servicio;
        $this->page_title = 'Actualizar servicio';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_servicio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $servicio = new servicio();
        if(!$servicio->getInformacionservicio($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(servicio::setservicio('delete', array('id'=>$servicio->id))) {
                DwMessage::valid('El servicio se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta servicio no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
