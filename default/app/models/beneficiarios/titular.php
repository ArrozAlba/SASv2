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

class Titular extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
		//$this->belongs_to('persona');
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
        //$check = false;
        if($check) { //Si existe
            if(empty($obj->cedula)) {
                $obj->cedula = $old->cedula; //Asigno el id del encontrado al nuevo
            } else { //Si se actualiza y existe otro con la misma información
                if($obj->id != $old->id) {
                    DwMessage::info('Lo sentimos, pero ya existe una persona registrada con el mismo número de cédula');
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


    public static function setETitular($method, $data=array(), $optData=array()) {
        $obj = new Titular($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        $method = 'update';
        //Creo otro objeto para comparar si existe
       /* $old = new Titular($data);
        $check = $old->_getTitularRegistrado('find_first');
        //$check = false;
        if($check) { //Si existe
            if(empty($obj->cedula)) {
                $obj->cedula = $old->cedula; //Asigno el id del encontrado al nuevo
            } else { //Si se actualiza y existe otro con la misma información
                if($obj->id != $old->id) {
                    DwMessage::info('Lo sentimos, pero ya existe una persona registrada con el mismo número de cédula');
                    return FALSE;
                }
            }
            if($method=='create') { //Si se crea la persona, pero ya está registrada la actualizo
                $method = 'update';
            }
        }*/
        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método para listar Titulares
     * @return obj
     */

    public function getListadotitular($estado, $order='', $page=0) {
        $columns = 'titular.*, titular.id as idtitular, sucursal.*, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';       
        $join= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON departamento.sucursal_id = sucursal.id';

       // $conditions = "";//Por el super usuario
     
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join");
        }  
    }

    /**
     * Método para obtener titulares
     * @return obj
     */
   public function obtener_titulares($titular) {
        if ($titular != '') {
            $titular = stripcslashes($titular);
            $res = $this->find_all_by_sql("
                select titular.id,titular.persona_id,persona.nombre1,persona.apellido1,cast(persona.cedula as integer) 
from titular,persona where persona.cedula like '%{$titular}%' 
and titular.persona_id = persona.id");
            
            if ($res) {
                foreach ($res as $titular) {
                    $titulares[] = array('id'=>$titular->id,'value'=>$titular->cedula,'idnombre'=>$titular->nombre1.' '.$titular->nombre2.' '.$titular->apellido1.' '.$titular->apellido2);
                }
                return $titulares;
            }
        }
        return array('No hubo coincidencias');
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
     * Método para obtener la información de un usuario
     * @return type
     */
    public function getInformacionTitular($titular) {
        $titular = Filter::get($titular, 'int');
        if(!$titular) {
            return NULL;
        }
        $columns = 'municipio.nombre as municipio, municipio.id as idmunicipio, parroquia.nombre as parroquia, parroquia.id as idparroquia,  estado.nombre as estado, estado.id as idestado, pais.nombre as pais, pais.id as idpais, titular.*, titular.id as idtitular,  profesion.id, profesion.nombre as profesion, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';
        $join= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';
        $join.= 'INNER JOIN profesion ON  titular.profesion_id = profesion.id ';
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN pais ON  titular.pais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  titular.estado_id = estado.id ';
        $join.= 'INNER JOIN municipio ON  titular.municipio_id = municipio.id ';
        $join.= 'INNER JOIN parroquia ON  titular.parroquia_id = parroquia.id ';
        $condicion = "titular.id = $titular";        
        return $this->find_first("columns: $columns", "join: $join", "conditions: $condicion");
    }

// Funcion para tomar los paises y estado para la direccion de habitacion :S
    public function getInformacionDireccionTitular($titular) {
        $titular = Filter::get($titular, 'int');
        if(!$titular) {
            return NULL;
        }
        $columns = 'parroquia.nombre as hparroquia, estado.nombre as hestado, estado.id as idhestado,  pais.nombre as hpais, pais.id as idhpais, titular.*, persona.id';
        $join= 'INNER JOIN persona ON persona.id = titular.persona_id ';        
        $join.= 'INNER JOIN pais ON  persona.hpais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  persona.hestado_id = estado.id ';
        $join.= 'INNER JOIN parroquia ON  persona.hparroquia_id = parroquia.id ';

        $condicion = "titular.id = $titular";        
        return $this->find_first("columns: $columns", "join: $join", "conditions: $condicion");
    }


// --------Informacion para los datos de la upsa donde trabaja el titular----------
    public function getInformacionLaboralTitular($titular) {
        $titular = Filter::get($titular, 'int');
        if(!$titular) {
            return NULL;
        }
        $columns = 'municipio.nombre as municipios, municipio.id as idmunicipio, estado.nombre as estados, estado.id as idestado, pais.nombre as paiss, pais.id as idpais,  departamento.id, departamento.nombre as departamento, sucursal.sucursal, sucursal.direccion, cargo.nombre as cargo';
        $join= 'INNER JOIN persona ON persona.id = titular.persona_id ';        
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON sucursal.id = departamento.sucursal_id ';
        $join.= 'INNER JOIN pais ON  persona.pais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  persona.estado_id = estado.id ';
        $join.= 'INNER JOIN municipio ON  persona.municipio_id = municipio.id ';
        $join.= 'INNER JOIN cargo ON cargo.id = titular.cargo_id ';
        $condicion = "titular.id = $titular";        
        return $this->find_first("columns: $columns", "join: $join", "conditions: $condicion");
    }
    /**
     * Método para buscar Titular
     */
    public function getAjaxTitular($field, $value, $order='', $page=0) {
        $value = Filter::get($value, 'string');
        if( strlen($value) <= 2 OR ($value=='none') ) {
            return NULL;
        }
        $columns = 'titular.*, persona.*, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';
        $join= 'INNER JOIN persona ON persona.id = titular.persona_id ';        
        $join.= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';   
        //$conditions = "";//Por el super usuario
        
        $order = $this->get_order($order, 'nombre1', array(                        
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
            )
        ));
        
        //Defino los campos habilitados para la búsqueda
        $fields = array('cedula', 'nombre1', 'apellido1', 'tipoe', 'departamento', );
        if(!in_array($field, $fields)) {
            $field = 'nombre1';
        }        
        if(! ($field=='sucursal' && $value=='todas') ) {
          $conditions.= " AND $field LIKE '%$value%'";
        }        
        if($page) {
            return $this->paginated("columns: $columns", "join: $join","conditions: $conditions",  "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join","conditions: $conditions", "order: $order");
        }  
        //"conditions: $conditions",
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
