<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Clase que gestiona los accesos al sistema
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class Acceso extends ActiveRecord {
    
    /**
     * Constante para definir el acceso como entrada
     */
    const ENTRADA = 1;
    
    /**
     * Constante para definir el acceso como salida
     */
    const SALIDA = 2;
       
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('usuario');
    }
    
    /**
     * Método para registrar un acceso
     * @param string $tipo Tipo de acceso acceso/salida
     * @param int $usuario Usuario que accede
     * @param string $ip  Dirección ip
     */
    public static function setAcceso($tipo, $usuario) {
        $usuario = Filter::get($usuario, 'numeric');        
        $obj = new Acceso();
        $obj->usuario_id = $usuario;
        $obj->ip = DwUtils::getIp();
        $obj->tipo_acceso = ($tipo==Acceso::ENTRADA) ? 1 : 2;
        $obj->create();
    }     
    
     /**
     * Método para listar los accesos de los usuario     
     * @return ActiveRecord
     */
    public function getListadoAcceso($usuario=NULL, $estado='todos', $order='', $page=0) {
        $columns = 'acceso.*, usuario.login, persona.nombre1, persona.apellido1';
        $join = 'INNER JOIN usuario ON usuario.id = acceso.usuario_id ';        
        $join.= 'INNER JOIN persona ON persona.id = usuario.persona_id ';
        $conditions = (empty($usuario)) ? "usuario.id > '1'" : "usuario.id=$usuario";
        
        $order = $this->get_order($order, 'acceso.fecha_registro', array(  'fecha'       =>array( 
                                                                                                'ASC'=>'acceso.fecha_registro ASC, persona.nombre1 ASC, persona.apellido1 ASC',
                                                                                                'DESC'=>'acceso.fecha_registro DESC, persona.nombre1 ASC, persona.apellido1 ASC'), 
                                                                          'nombre'      =>array(
                                                                                                'ASC'=>'persona.nombre1 ASC, persona.apellido1 ASC, acceso.fecha_registro DESC', 
                                                                                                'DESC'=>'persona.nombre1 DESC, persona.apellido1 DESC, acceso.fecha_registro DESC'),
                                                                          'apellido'    =>array(
                                                                                                'ASC'=>'persona.nombre1 ASC, persona.apellido1 ASC, acceso.fecha_registro DESC', 
                                                                                                'DESC'=>'persona.nombre1 DESC, persona.apellido1 DESC, acceso.fecha_registro DESC'),
                                                                           'ip',
                                                                           'tipo_acceso'=>array(
                                                                                                'ASC'=>'acceso.tipo_acceso ASC, acceso.fecha_registro DESC, persona.nombre1 ASC, persona.apellido1 ASC', 
                                                                                                'DESC'=>'acceso.tipo_acceso DESC, acceso.fecha_registro DESC, persona.nombre1 DESC, persona.apellido1 DESC')) );
        
        if($estado != 'todos') {
            $conditions.= ($estado!=self::ENTRADA) ? " AND acceso.tipo_acceso = ".self::ENTRADA : " AND acceso.tipo_acceso = ".self::SALIDA;
        } 
        
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
        } 
        
    }
    
    /**
     * Método para buscar accesos
     */
    public function getAjaxAcceso($field, $value, $order='', $page=0) {
        $value = Filter::get($value, 'string');
        if( strlen($value) <= 2 OR ($value=='none') ) {
            return NULL;
        }
        $columns = 'acceso.*, IF(acceso.tipo_acceso='.self::ENTRADA.', "Entrada", "Salida") AS new_tipo, usuario.login, persona.nombre, persona.apellido';
        $join = 'INNER JOIN usuario ON usuario.id = acceso.usuario_id ';        
        $join.= 'INNER JOIN persona ON persona.id = usuario.persona_id ';
        $conditions = "usuario.id > '1'";//Por el super usuario "error"
        
        $order = $this->get_order($order, 'acceso.registrado_at', array(  'fecha'       =>array( 
                                                                                                'ASC'=>'acceso.fecha_registro ASC, persona.nombre1 ASC, persona.apellido1 ASC',
                                                                                                'DESC'=>'acceso.fecha_registro DESC, persona.nombre1 ASC, persona.apellido1 ASC'), 
                                                                          'nombre'      =>array(
                                                                                                'ASC'=>'persona.nombre1 ASC, persona.apellido1 ASC, acceso.fecha_registro DESC', 
                                                                                                'DESC'=>'persona.nombre1 DESC, persona.apellido1 DESC, acceso.fecha_registro DESC'),
                                                                          'apellido'    =>array(
                                                                                                'ASC'=>'persona.nombre1 ASC, persona.apellido1 ASC, acceso.fecha_registro DESC', 
                                                                                                'DESC'=>'persona.nombre1 DESC, persona.apellido1 DESC, acceso.fecha_registro DESC'),
                                                                           'ip',
                                                                           'tipo_acceso'=>array(
                                                                                                'ASC'=>'acceso.tipo_acceso ASC, acceso.fecha_registro DESC, persona.nombre1 ASC, persona.apellido1 ASC', 
                                                                                                'DESC'=>'acceso.tipo_acceso DESC, acceso.fecha_registro DESC, persona.nombre1 DESC, persona.apellido1 DESC')) );
        
        //Defino los campos habilitados para la búsqueda por seguridad
        $fields = array('fecha', 'nombre', 'apellido', 'tipo_acceso',  'ip');
        if(!in_array($field, $fields)) {
            $field = 'nombre';
        }  
        
        if($field=='fecha') {
            $conditions.= " AND DATE(acceso.fecha_registro) =  '$value'";
        } else if($field=='tipo_acceso') {            
            $conditions.= " HAVING new_tipo LIKE '%$value%'";
        } else {
            $conditions.= " AND $field LIKE '%$value%'";
        }
        
        if($page) {
            return $this->paginated_by_sql("SELECT $columns FROM $this->source $join WHERE $conditions ORDER BY $order", "page: $page");
        } else {
            return $this->find_all_by_sql("SELECT $columns FROM $this->source $join WHERE $conditions ORDER BY $order", "order: $order");
        }  
    }
    
}
?>
