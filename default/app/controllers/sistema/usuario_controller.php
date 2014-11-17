<?php
/**
 * S.A.S
 *
 * Descripcion: Modelo para el manejo de beneficiarioes
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Grupo SAS IuTEP (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP / E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve) 
 */

Load::models('beneficiarios/titular', 'config/sucursal', 'sistema/usuario_clave');

class UsuarioController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de usuarios';
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
        
        $usuario = new Usuario();            
        $usuarios = $usuario->getAjaxUsuario($field, $value, $order, $page);        
        if(empty($usuarios->items)) {
            DwMessage::info('No se han encontrado registros');
        }
        $this->usuarios = $usuarios;
        $this->order = $order;
        $this->field = $field;
        $this->value = $value;
        $this->page_title = 'Búsqueda de usuarios del sistema';        
    }
    
    /**
     * Método para listar
     */
    public function listar($order='order.id.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $usuario = new Usuario();
        $this->usuarios = $usuario->getListadoUsuario('todos', $order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de usuarios del sistema';
    }
    
    /**
     * Método para agregar

    public function agregar() {
        if(Input::hasPost('titular') && Input::hasPost('usuario')) {
            ActiveRecord::beginTrans();
            //Guardo la titular
            $titular = titular::settitular('create', Input::post('titular'));
            if($titular) {
                if(Usuario::setUsuario('create', Input::post('usuario'), array('titular_id'=>$titular->id, 'repassword'=>Input::post('repassword'), 'tema'=>'default'))) {
                    ActiveRecord::commitTrans();
                    DwMessage::valid('El usuario se ha creado correctamente.');
                    return DwRedirect::toAction('listar');
                }
            }
            else {
                ActiveRecord::rollbackTrans();
            }            
        }
        $this->page_title = 'Agregar usuario';
    }
    */
        /**
     * Método para agregar
     */
    public function agregar() {
         if(Input::hasPost('usuario') && Input::hasPost('usuario_clave')) {
            ActiveRecord::beginTrans();
            //Guardo usuario
            $usuario = Usuario::setUsuario('create', Input::post('usuario'));
            if($usuario) {
                if(UsuarioClave::setClave('create', Input::post('usuario_clave'), array('usuario_id'=>$usuario->id))) {
                    ActiveRecord::commitTrans();
                    DwMessage::valid('El Usuario se ha creado correctamente.');
                    return DwRedirect::toAction('listar');
                }
            } else {
                ActiveRecord::rollbackTrans();
            }
             
        }
        $this->page_title = 'Agregar Usuario';
        
    }
    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_usuario', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $usuario = new Usuario();
        if(!$usuario->getInformacionUsuario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        if(Input::hasPost('usuario')) {
            if(DwSecurity::isValidKey(Input::post('usuario_id_key'), 'form_key')) {
                ActiveRecord::beginTrans();
                //Guardo la titular
                $titular = titular::settitular('update', Input::post('titular'), array('id'=>$usuario->titular_id));
                if($titular) {
                    if(Usuario::setUsuario('update', Input::post('usuario'), array('titular_id'=>$titular->id, 'repassword'=>Input::post('repassword'), 'id'=>$usuario->id, 'login'=>$usuario->login))) {
                        ActiveRecord::commitTrans();
                        DwMessage::valid('El usuario se ha actualizado correctamente.');
                        return DwRedirect::toAction('listar');
                    }
                } else {
                    ActiveRecord::rollbackTrans();
                } 
            }
        }        
        $this->temas = DwUtils::getFolders(dirname(APP_PATH).'/public/css/backend/themes/');
        $this->usuario = $usuario;
        $this->page_title = 'Actualizar usuario';
        
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
        if(!$id = DwSecurity::isValidKey($key, 'shw_usuario', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $usuario = new Usuario();
        if(!$usuario->getInformacionUsuario($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        $estado = new EstadoUsuario();
        $this->estados = $estado->getListadoEstadoUsuario($usuario->id);
        
        $acceso = new Acceso();
        $this->accesos = $acceso->getListadoAcceso($usuario->id, 'todos', 'order.fecha.desc');
        
        $this->usuario = $usuario;
        $this->page_title = 'Información del usuario';
        
    }
    
    /**
     * Método para subir imágenes
     */
    public function upload() {     
        $upload = new DwUpload('fotografia', 'img/upload/titulares/');
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

