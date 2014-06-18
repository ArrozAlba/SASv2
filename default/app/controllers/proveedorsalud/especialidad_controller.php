<?php
/**
 * Alexis
 *
 * Descripcion: Controlador que se encarga de la gestión de los Especialidades de la empresa
 *
 * @category    
 * @package     Controllers 
 * @author      
 * @copyright   infoalex
 */

Load::models('proveedorsalud/especialidad');

class EspecialidadController extends BackendController {
    
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
        $especialidad = new Especialidad();        
        $this->especialidades = $especialidad->getListadoEspecialidad($order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Especialidades';
    }
     /**
     * Método para obtener especialidades
     */
    
        //accion que busca en las especialidades y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $especialidades = Load::model('proveedorsalud/especialidad')->obtener_especialidades($busqueda);
            die(json_encode($especialidades)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }        
    /**
     * Método para agregar
     */
    public function agregar() {
    //    $empresa = Session::get('empresa', 'config');
        if(Input::hasPost('especialidad')) {
            if(Especialidad::setEspecialidad('create', Input::post('especialidad'))) {
                DwMessage::valid('La especialidad se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }            
        } 
        $this->page_title = 'Agregar Especialidad';
    }
    
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_especialidad', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $especialidad = new Especialidad();
        if(!$especialidad->getInformacionEspecialidad($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        if(Input::hasPost('especialidad') && DwSecurity::isValidKey(Input::post('especialidad_id_key'), 'form_key')) {
            if(Especialidad::setEspecialidad('update', Input::post('especialidad'))){
                DwMessage::valid('La especialidad se ha actualizado correctamente!');
                return DwRedirect::toAction('listar');
            }
        } 
        //$this->ciudades = Load::model('params/ciudad')->getCiudadesToJson();
        $this->especialidad = $especialidad;
        $this->page_title = 'Actualizar especialidad';        
    }
    
    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'del_especialidad', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $especialidad = new Especialidad();
        if(!$especialidad->getInformacionEspecialidad($id)) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }                
        try {
            if(Especialidad::setEspecialidad('delete', array('id'=>$especialidad->id))) {
                DwMessage::valid('La especialidad se ha eliminado correctamente!');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Esta especialidad no se puede eliminar porque se encuentra relacionada con otro registro.');
        }
        
        return DwRedirect::toAction('listar');
    }
}
