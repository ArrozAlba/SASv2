<?php
/**
 * S.A.S
 *
 * Descripcion: Controlador que se encarga de la gestión de los beneficiarioes del sistema 
 * @category    
 * @package     Controllers 
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve)
 */

Load::models('beneficiarios/beneficiario','personas/persona', 'sistema/usuario','config/discapacidad', 'beneficiarios/titular', 'beneficiarios/discapacidad_beneficiario');

class beneficiarioController extends BackendController {
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Beneficiarios';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para buscar
     */
    public function buscar($field='nombre1', $value='none', $order='order.id.asc', $page=1) {        
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;
        
        $beneficiario = new beneficiario();            
        $beneficiarioes = $beneficiario->getAjaxbeneficiario($field, $value, $order, $page);        
        if(empty($beneficiarioes->items)) {
            DwMessage::info('No se han encontrado registros');
        }
        $this->beneficiarios = $beneficiarios;
        $this->order = $order;
        $this->field = $field;
        $this->value = $value;
        $this->page_title = 'Búsqueda de beneficiarios del sistema';        
    }
    /** Metodo para excluir  a un beneficiario **/
    public function excluir($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'excluir_bene', 'int')) {
            return DwRedirect::toAction('listar');
        }
        $beneficiario = new beneficiario();
        $bene = $beneficiario->getInformacionbeneficiario($id);
        if(!$bene) {            
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        if(Input::hasPost('beneficiario')) {
            $es = "0";
            if(beneficiario::setEBeneficiario('update', Input::post('beneficiario'), array('estado_beneficiario'=>$es))){
                DwMessage::valid('¡El beneficiario se ha Excluido con Exito!.');
                return DwRedirect::toAction('listar');
            }       
        } 
        $this->page_title = 'Excluir Beneficiario';
        $this->beneficiarios = $bene;
    }

/**
     * Método para obtener beneficiarios
     */
    public function getBeneficiarios(){
       View::response('view'); 
       $this->titular_id=Input::post('titular_id');
    }    
    
/**
     * Método para obtener beneficiarios
     */
    
        //accion que busca en los beneficiarios y devuelve el json con los datos
    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los estados si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $beneficiarios = Load::model('beneficiarios/beneficiario')->obtener_beneficiariosautocomplete($busqueda);
            die(json_encode($beneficiarios)); // solo devolvemos los datos, sin template ni vista
            //json_encode nos devolverá el array en formato json ["aragua","carabobo","..."]
        }
    }
    /**
     * Método para listar
     */
    public function listar($order='order.id.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $beneficiario = new beneficiario();

        $this->beneficiarios = $beneficiario->getListadobeneficiario('todos', $order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de beneficiarios del sistema';
    }
    /**
     * Método para agregar
     */
    public function agregar($key) {
        if(!$id = DwSecurity::isValidKey($key, 'shw_titular', 'int')) {
            return DwRedirect::toAction('listar');
        }
        //El id del titular que tendra a los beneficiarios asociados
        $this->idtitular = $id;
        $beneficiario = new beneficiario();
        $discapacidad = new Discapacidad();
        $titular = new Titular();
        $this->titulares = $titular->getInformacionTitular($id);
        $this->bene = $beneficiario->getListadoBeneTitular($id);
        if(Input::hasPost('beneficiario')) {
            ActiveRecord::beginTrans();
            //Guardo beneficiario
            $benefici = beneficiario::setbeneficiario('create', Input::post('beneficiario'));
                if($benefici){
                    if (DiscapacidadBeneficiario::setDiscapacidadBeneficiario(Input::post('discapacidad'),$benefici->id)){
                        ActiveRecord::commitTrans();
                        DwMessage::valid('El beneficiario se ha creado correctamente.');
                        return DwRedirect::toAction('agregar/'.$key);
                    }
                  else{
                     ActiveRecord::rollbackTrans();
                  }
                }
        }
        $this->page_title = 'Agregar beneficiario';
        $this->discapacidad = $discapacidad->getListadoDiscapacidad();
    }
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'editar_bene', 'int')) {
            return DwRedirect::toAction('titular/listar');
        }
        $beneficiario = new beneficiario();
        $discapacidad_bene = new DiscapacidadBeneficiario();
        $discapacidad = new Discapacidad();
        $this->discapacidad = $discapacidad->getListadoDiscapacidad();
        $this->discapacidad_bene = $discapacidad_bene->getDiscapacidadBeneficiario($id);
        if(!$beneficiario->getInformacionbeneficiario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('titular/listar');
        }
        if(Input::hasPost('beneficiario')) {
            $beneficiario = beneficiario::setEBeneficiario('update', Input::post('beneficiario'), Input::post('metodo')) ;
            if($beneficiario){
                DwMessage::valid('El Beneficiario se ha actualizado correctamente.');
                return DwRedirect::toAction('../titular/listar');
            }
        }
        $this->beneficiario = $beneficiario;
        $this->page_title = 'Actualizar beneficiario';
    }
    /**
     * Método para inactivar/reactivar
     */
    public function estado($tipo, $key) {
        if(!$id = DwSecurity::isValidKey($key, $tipo.'_usuario', 'int')) {
            return DwRedirect::toAction('listar');
        } 
        
        $usuario = new Usuario();
        if(!$usuario->getInformacionUsuario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }
        if($tipo == 'reactivar' && $usuario->estado_usuario == EstadoUsuario::ACTIVO) {
            DwMessage::info('El usuario ya se encuentra activo.');
            return DwRedirect::toAction('listar');
        } else if($tipo == 'bloquear' && $usuario->estado_usuario == EstadoUsuario::BLOQUEADO) {
            DwMessage::info('El usuario ya se encuentra bloqueado.');
            return DwRedirect::toAction('listar');
        }  
        
        if(Input::hasPost('estado_usuario')) {            
            if(EstadoUsuario::setEstadoUsuario($tipo, Input::post('estado_usuario'), array('usuario_id'=>$usuario->id))) { 
                ($tipo=='reactivar') ? DwMessage::valid('El usuario se ha reactivado correctamente!') : DwMessage::valid('El usuario se ha bloqueado correctamente!');
                return DwRedirect::toAction('listar');
            }
        }  
        
        $this->page_title = ($tipo=='reactivar') ? 'Reactivación de usuario' : 'Bloqueo de usuario';
        $this->usuario = $usuario;
    }
    
    /**
     * Método para ver
     */
    public function ver($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'shw_beneficiario', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $beneficiario = new beneficiario();
        if(!$beneficiario->getInformacionbeneficiario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        //$estado = new EstadoUsuario();
        //$this->estados = $estado->getListadoEstadoUsuario($usuario->id);
        
        //$acceso = new Acceso();
        //$this->accesos = $acceso->getListadoAcceso($usuario->id, 'todos', 'order.fecha.desc');
        
        $this->beneficiario = $beneficiario;
        $this->page_title = 'Información del beneficiario';
    }
    
    /**
     * Método para subir imágenes
     */
    public function upload() {     
        $upload = new DwUpload('fotografia', 'img/upload/personas/');
        $upload->setAllowedTypes('png|jpg|gif|jpeg');
        $upload->setEncryptName(TRUE);
        $upload->setSize(170, 200, TRUE);
        if(!$data = $upload->save()) { //retorna un array('path'=>'ruta', 'name'=>'nombre.ext');
            $data = array('error'=>$upload->getError());
        }
        sleep(1);//Por la velocidad del script no permite que se actualize el archivo
        View::json($data);
    }
    
}
