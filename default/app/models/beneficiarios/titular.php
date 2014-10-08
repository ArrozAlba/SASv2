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
        $this->has_many('discapacidad_titular');
    }
   /**
     * Método que devuelve el inner join con el estado_usuario
     * @return string
     */
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
    //editar el titular
    public static function setETitular($method, $data=array(), $optData=array()) {
        $obj = new Titular($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        $method = 'update';
        

        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }

    public function getListadotitular($estado, $order='', $page=0) {
        $columns = 'titular.*, titular.id as idtitular, sucursal.*, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';       
        $join= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON departamento.sucursal_id = sucursal.id';

        $order = $this->get_order($order, 'nombre1', array(                        
            'nombre1' => array(
                'ASC'=>'titular.nombre1 ASC, titular.apellido1 ASC', 
                'DESC'=>'titular.nombre1 DESC, titular.apellido1 DESC'
            ),
            'apellido1' => array(
                'ASC'=>'titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'titular.apellido1 DESC, titular.nombre1 DESC'
            ),
            'cedula' => array(
                'ASC'=>'titular.cedula ASC, titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'titular.cedula DESC, titular.apellido1 DESC, titular.nombre1 DESC'
            ),
            'nomina' => array(
                'ASC'=>'tipoempleado.nombre ASC, titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'tipoempleado.nombre DESC, titular.apellido1 DESC, titular.nombre1 DESC'
            ),
             'departamento' => array(
                'ASC'=>'departamento.nombre ASC, titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'departamento.nombre DESC, titular.apellido1 DESC, titular.nombre1 DESC'
            ),
        ));
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order");
        }  
    }
    public function getListadoTitularReporte() {
         $columns = 'titular.*, titular.id as idtitular, sucursal.*, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';       
        $join= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON departamento.sucursal_id = sucursal.id';
        $join.= ' ORDER BY titular.cedula ';
        return $this->find("columns: $columns", "join: $join");
       
        /*$columns = 'titular.*, titular.id as idtitular, sucursal.*, tipoempleado.id, tipoempleado.nombre as tipoe, departamento.id, departamento.nombre as departamento';       
        $join= 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON departamento.sucursal_id = sucursal.id';

            return $this->find("columns: $columns", "join: $join");*/
          
    }
    /**
     * Método para obtener titulares
     * @return obj
     */
   public function obtener_titulares($titular) {
        if ($titular != '') {
            $titular = stripcslashes($titular);
            $res = $this->find_all_by_sql(" select titular.id,titular.nombre1,titular.apellido1,cast(titular.cedula as integer) from titular where titular.cedula like '%{$titular}%'");
            if ($res){
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
        $columns = 'municipio.nombre as municipio, municipio.id as idmunicipio, parroquia.nombre as parroquia, parroquia.id as idparroquia,  estado.nombre as esta2, estado.id as idestado, pais.nombre as pais, pais.id as idpais, titular.*, titular.id as idtitular, titular.estado as estado_titular, profesion.id idprofesion,  profesion.nombre as profesion, tipoempleado.id idtipoempleado, tipoempleado.nombre as tipoe, departamento.id as iddepartamento, departamento.nombre as departamento, sucursal.id as idsucursal, sucursal.sucursal, cargo.id idcargo, cargo.nombre ';
        $join = 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';
        $join.= 'INNER JOIN profesion ON  titular.profesion_id = profesion.id ';
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON  departamento.sucursal_id = sucursal.id ';
        $join.= 'INNER JOIN pais ON  titular.pais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  titular.estado_id = estado.id ';
        $join.= 'INNER JOIN municipio ON  titular.municipio_id = municipio.id ';
        $join.= 'INNER JOIN parroquia ON  titular.parroquia_id = parroquia.id ';
        $join.= 'INNER JOIN cargo ON cargo.id = titular.cargo_id ';
        $condicion = "titular.id = $titular";        
        return $this->find_first("columns: $columns", "join: $join", "conditions: $condicion");
    }

// Funcion para tomar los paises y estado para la direccion de habitacion :S
    public function getInformacionDireccionTitular($titular) {
        $titular = Filter::get($titular, 'int');
        if(!$titular) {
            return NULL;
        }
        $columns = 'parroquia.nombre as hparroquia, parroquia.id as idhparroquia ,estado.nombre as hestado, estado.id as idhestado, pais.nombre as hpais, pais.id as idhpais, titular.id ';
        $join= 'INNER JOIN pais ON  titular.hpais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  titular.hestado_id = estado.id ';
        $join.= 'INNER JOIN parroquia ON  titular.hparroquia_id = parroquia.id ';
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
        $join= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';
        $join.= 'INNER JOIN sucursal ON sucursal.id = departamento.sucursal_id ';
        $join.= 'INNER JOIN pais ON  titular.pais_id = pais.id ';
        $join.= 'INNER JOIN estado ON  titular.estado_id = estado.id ';
        $join.= 'INNER JOIN municipio ON  titular.municipio_id = municipio.id ';
        $join.= 'INNER JOIN cargo ON cargo.id = titular.cargo_id ';
        $condicion = "titular.id = $titular";        
        return $this->find_first("columns: $columns", "join: $join", "conditions: $condicion");
    }
    /**
     * Método para buscar Titular
     */
    public function getAjaxTitular($field, $value, $order='', $page=0) {
        $value = Filter::get($value, 'string');
        if( strlen($value) < 1 OR ($value=='none') ) {
            return NULL;
        }
        if($field=='apellido'){ $field ='apellido1';}
        if($field=='nomina'){ $field ='tipoempleado.nombre';}
        if($field=='departamento'){ $field ='departamento.nombre';}
        if($field=='sucursal'){ $field ='sucursal.sucursal';}

        $columns = 'titular.*, titular.id as idtitular, tipoempleado.id as idtipoempleado, tipoempleado.nombre as nomina, departamento.id, departamento.nombre as departamento, sucursal.* ';
        $join = 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';   
        $join.= 'INNER JOIN sucursal  ON  departamento.sucursal_id = sucursal.id ';
        //$conditions = "";//Por el super usuario
        
        $order = $this->get_order($order, 'nombre1', array(                        
            'nombre1' => array(
                'ASC'=>'titular.nombre1 ASC, titular.apellido1 ASC', 
                'DESC'=>'titular.nombre1 DESC, titular.apellido1 DESC'
            ),
            'apellido1' => array(
                'ASC'=>'titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'titular.apellido1 DESC, titular.nombre1 DESC'
            ),
            'cedula' => array(
                'ASC'=>'titular.cedula ASC, titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'titular.cedula DESC, titular.apellido1 DESC, titular.nombre1 DESC'
            ),
            'nomina' => array(
                'ASC'=>'tipoempleado.nombre ASC, titular.apellido1 ASC, titular.nombre1 ASC', 
                'DESC'=>'tipoempleado.nombre DESC, titular.apellido1 DESC, titular.nombre1 DESC'
            ),
        ));
        
        //Defino los campos habilitados para la búsqueda
        $fields = array('cedula', 'nombre1', 'apellido1','tipoempleado.nombre', 'departamento.nombre','sucursal.sucursal');
        if(!in_array($field, $fields)) {
            $field = 'nombre1';
        }        
        if(! ($field=='sucursal' && $value=='todas') ) {
          $conditions= " $field LIKE '%$value%'";
        } 

        if($page) {
            return $this->paginated("columns: $columns", "join: $join","conditions: $conditions",  "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join","conditions: $conditions", "order: $order");
        }  
        //"conditions: $conditions",
    }
    /**
     * Método para buscar Titular y enviarlo al reporte
     */
    public function getListadoTitularFiltrado($field, $value, $order='') {
        $value = Filter::get($value, 'string');
        if( strlen($value) < 1 OR ($value=='none') ) {
            return NULL;
        }
        if($field=='apellido'){ $field ='apellido1';}
        if($field=='nomina'){ $field ='tipoempleado.nombre';}
        if($field=='departamento'){ $field ='departamento.nombre';}
        if($field=='sucursal'){ $field ='sucursal.sucursal';}

        $columns = 'titular.*, titular.id as idtitular, tipoempleado.id as idtipoempleado, tipoempleado.nombre as nomina, departamento.id, departamento.nombre as departamento, sucursal.* ';
        $join = 'INNER JOIN tipoempleado  ON  titular.tipoempleado_id = tipoempleado.id ';   
        $join.= 'INNER JOIN departamento  ON  titular.departamento_id = departamento.id ';   
        $join.= 'INNER JOIN sucursal  ON  departamento.sucursal_id = sucursal.id ';
        $order ='titular.cedula ';
        //$conditions = "";//Por el super usuario

        //Defino los campos habilitados para la búsqueda
        $fields = array('cedula', 'nombre1', 'apellido1','tipoempleado.nombre', 'departamento.nombre','sucursal.sucursal');
        if(!in_array($field, $fields)) {
            $field = 'nombre1';
        }        
        if(! ($field=='sucursal' && $value=='todas') ) {
          $conditions= " $field LIKE '%$value%'";
        } 

        return $this->find("columns: $columns", "join: $join","conditions: $conditions", "order: $order");
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
        $this->celular = Filter::get($this->celular, 'numeric');
        $this->telefono = Filter::get($this->telefono, 'numeric');
        $this->cargo_id = Filter::get($this->cargo_id, 'numeric'); 
        $this->observacion = Filter::get($this->observacion, 'string');
        $this->nombre1 = strtoupper($this->nombre1);
        $this->nombre2 = strtoupper($this->nombre2);
        $this->apellido1 = strtoupper($this->apellido1);
        $this->apellido2 = strtoupper($this->apellido2);
        $this->observacion = strtoupper($this->observacion);
        $this->direccion = strtoupper($this->direccion);
        $this->motivo_exclusion = strtoupper($this->motivo_exclusion);
        $this->motivo_reactivacion = strtoupper($this->motivo_reactivacion);
        $this->correo_electronico = strtoupper($this->correo_electronico);
        $a = $this->estado_id = Filter::get($this->estado_id, 'numeric');
        $this->municipio_id = Filter::get($this->municipio_id, 'numeric');

        //creando contraseñaa...

     //   $ced = substr($this->cedula, )

        //validando correo electronico
        if($this->correo_electronico!=''){ 
            $valEmail = Validate::mail($this->correo_electronico);
            if(!$valEmail){
                DwMessage::error('El campo Email no es correcto');
                return 'cancel';
            }
        }
        //validando fecha nacimiento
        $fecha = $this->fecha_nacimiento;
        $ano  = substr($fecha, 0,4);
        $anoac = date("Y");
        if(($ano == $anoac)|| ($anoac-$ano <18)){
             DwMessage::error('La fecha de nacimiento ingresada no es correcta, verifica e intenta de nuevo');
            return 'cancel';
        }
        //validand cantidad de nros del telefono y celular 
        if (strlen($this->celular)>1 && (strlen($this->celular)<11)) {
            DwMessage::error('Faltan números al telefono Movil (celular) ');
                return 'cancel';
        }
        if (strlen($this->telefono)>1 && (strlen($this->telefono)<11) ){
            DwMessage::error('Faltan números al telefono Fijo');
                return 'cancel';
        }
    }

}
?>
