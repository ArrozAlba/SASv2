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

class EstadoUsuario extends ActiveRecord {

    /**
     * Constante para definir el estado Activo
     */
    const COD_ACTIVO = 1;
    /**
     * Constante para definir el estado Bloqueado
     */
    const COD_BLOQUEADO = 2;
    /**
     * Constante para describir el estado Activo
     */
    const ACTIVO = 'Activo';
    /**
     * Constante para describir el estado Bloqueado
     */
    const BLOQUEADO = 'Bloqueado';

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('usuario');        
    }

    /**
     * Método para obtener el estado de un usuarios
     * @param int $usuario
     * @return string
     */
    public function getEstadoUsuario($usuario) {
        $usuario = Filter::get($usuario, 'numeric');
        $condicion = "usuario_id = '$usuario'";
        $sql = $this->find_first('columns: estado_usuario', "conditions: $condicion", 'order: id DESC');
        if($sql) {
            return ($sql->estado_usuario == self::COD_ACTIVO) ? self::ACTIVO : self::BLOQUEADO;
        }
        return false;
    }

    /**
     * Método para listar todos los estados del usuario
     * @param int $usuario 
     * @param int $pag Número de la página. Si es mayor que 0 se utiliza el paginador
     * @return EstadoUsuario
     */
    public function getListadoEstadoUsuario($usuario, $page=0) {
        $usuario = Filter::get($usuario,'numeric');
        $sql = "SELECT id, estado_usuario, descripcion FROM estado_usuario WHERE usuario_id = '$usuario' ORDER BY id DESC";
        return ($page) ? $this->paginated_by_sql($sql, "page: $page") : $this->find_all_by_sql($sql);        
        return false;
    } 
    
    /**
     * Método para registrar un estado a un usuario
     */
    public static function setEstadoUsuario($accion, $data, $optData=NULL) {
        $accion = strtolower($accion);
        $obj = new EstadoUsuario($data);
        if($optData) {            
            $obj->dump_result_self($optData);
        }
        //Verifico el estado actual
        $actual = $obj->getEstadoUsuario($obj->usuario_id); 
        //Verifico las acciones
        if($accion == 'registrar') {
            $obj->estado_usuario = self::COD_ACTIVO;        
        } else if( ($accion == 'bloquear') && ($actual == self::ACTIVO or !$actual) ) {                        
            $obj->estado_usuario = self::COD_BLOQUEADO;                    
        } else if( ($accion == 'reactivar') && ($actual!= self::ACTIVO) ) {            
            $obj->estado_usuario = self::COD_ACTIVO;                            
        } else {                     
            return false;
        }        
        return $obj->create();
    }
    
    /**
     * Callback que se ejecuta antes de crear un registro
     */
    public function before_create() {
        $this->descripcion = Filter::get($this->descripcion, 'string');
    }
    
    /**
     * Callback que se ejecuta desupés de crear un registro
     */
    public function after_create() {
        //Obtengo el usuario por la relación definida en el initialize
        $usuario = $this->getUsuario();
        if($this->estado_usuario == self::COD_ACTIVO) {
            DwAudit::debug("Se activa el acceso al usuario $usuario->login. Motivo: $this->descripcion");
        } else if($this->estado_usuario == self::COD_BLOQUEADO) {
            DwAudit::debug("Se bloquea el acceso al sistema al usuario $usuario->login. Motivo: $this->descripcion");
        }
        
    }
      
}
