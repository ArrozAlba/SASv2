<?php
/**
 * S.A.S
 *
 * Descripcion: Modelo para el manejo de titulares
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León (jel1284@gmail.com)
 * @copyright   Copyright (c) 2014 UPTP / E.M.S. Arroz del Alba S.A. (http://autogestion.arrozdelalba.gob.ve) 
 */

//Load::models('sistema/usuario', 'personas/persona');

class Titular extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
		$this->belongs_to('persona');
      //  $this->has_one('usuario');
      //  $this->has_one('persona');

    }
   /**
     * Método que devuelve el inner join con el estado_usuario
     * @return string
     */
//    public static function getInnerEstado() {
//        return "INNER JOIN (SELECT usuario_id, CASE estado_usuario WHEN ".EstadoUsuario::COD_ACTIVO." THEN '".EstadoUsuario::ACTIVO."' WHEN ".EstadoUsuario::COD_BLOQUEADO." THEN '".EstadoUsuario::BLOQUEADO."' ELSE 'INDEFINIDO' END AS estado_usuario, descripcion FROM (SELECT * FROM estado_usuario ORDER BY estado_usuario.id DESC ) AS estado_usuario GROUP BY estado_usuario.usuario_id,estado_usuario.estado_usuario, descripcion) AS estado_usuario ON estado_usuario.usuario_id = usuario.id ";        
//    }
        
    /**
     * Método para setear un Objeto
     * @param string    $method     Método a ejecutar (create, update)
     * @param array     $data       Array para autocargar el objeto
     * @param array     $optData    Array con con datos adicionales para autocargar
     */
    public static function setTitular($method, $data=array(), $optData=array()) {
        $obj = new Titular($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        //Creo otro objeto para comparar si existe
        $old = new Titular($data);
        $check = $old->_getTitularRegistrado('find_first');
        if($check) { //Si existe
            if(empty($obj->id)) {
                $obj->id = $old->id; //Asigno el id del encontrado al nuevo
            } else { //Si se actualiza y existe otro con la misma información
                if($obj->id != $old->id) {
                    DwMessage::info('Lo sentimos, pero ya existe una persona registrada con el mismo número de identificación');
                    return FALSE;
                }
            }
            if($method=='create') { //Si se crea la persona, pero ya está registrada la actualizo
                $method = 'update';
            }
        }
        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método para listar titulares
     * @return obj
     */

    public function getListadoTitular($estado, $order='', $page=0) {
        $columns = 'titular.tipoempleado_id, titular.persona_id, titular.fecha_ingreso, titular.profesion_id,titular.departamento_id, 
					titular.cargo_id,persona.cedula, persona.nombre1, persona.apellido1';
        $join= 'INNER JOIN titular ON persona.cedula = titular.persona_id ';
//        $join.= 'INNER JOIN persona ON titular.titular_persona_id = titular.persona_id ';        
//        $join.= 'LEFT JOIN sucursal ON sucursal.id = usuario.sucursal_id ';
        $conditions = "persona.id > '2'";//Por el super usuario
                
        $order = $this->get_order($order, 'nombre1', array(                        
            'titular' => array(
                'ASC'=>'titular.persona_id ASC, persona.nombre1 ASC, persona.apellido1 DESC', 
                'DESC'=>'titular.persona_id DESC, persona.nombre1 DESC, persona.apellido1 DESC'
            ),
            'nombre1' => array(
                'ASC'=>'persona.nombre1 ASC, persona.apellido1 DESC', 
                'DESC'=>'persona.nombre1 DESC, persona.apellido1 DESC'
            ),
            'apellido1' => array(
                'ASC'=>'persona.apellido1 ASC, persona.nombre1 ASC', 
                'DESC'=>'persona.apellido1 DESC, persona.nombre1 DESC'
            ),
            'cedula' => array(
                'ASC'=>'persona.cedula ASC, persona.apellido1 ASC, persona.nombre1 ASC', 
                'DESC'=>'persona.cedula DESC, persona.apellido1 DESC, persona.nombre1 DESC'
            ),            
//            'sucursal' => array(
//                'ASC'=>'sucursal.sucursal ASC, persona.apellido1 ASC, persona.nombre1 ASC', 
//                'DESC'=>'sucursal.sucursal DESC, persona.apellido1 DESC, persona.nombre1 DESC'
//            ),
        ));         
        
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
        }  
    }

    /**
     * Método para verificar si una persona ya se encuentra registrada
     * @return obj
     */
    protected function _getTitularRegistrado($method='count') {
        $conditions = "cedula = '$this->cedula'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($method != 'count' && $method !='find_first') {
            $method = 'count';
        }
        return $this->$method("conditions: $conditions");
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     */
    public function before_save() {
        $this->tipoempleado_id = Filter::get($this->tipoempleado_id, 'numeric');
        $this->fecha_ingreso = Filter::get($this->fecha_ingreso, 'string'); 
        $this->profesion_id = Filter::get($this->profesion_id, 'numeric');
        $this->departamento_id = Filter::get($this->departamento_id, 'numeric');
        $this->cargo_id = Filter::get($this->cargo_id, 'numeric'); 
        $this->observacion = Filter::get($this->observacion, 'string');
    }    
}
?>
