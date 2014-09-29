
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
class beneficiario extends ActiveRecord {
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
      $this->has_one('titular');
      $this->has_many('discapacidad_beneficiario');
    }
   /**
     * Método que devuelve el inner join con el estado_usuario
     * @return string
     */
    /**
     * Método para oobtener_beneficiarios
     * @return obj
     */
   public function obtener_beneficiarios($beneficiario) {
        if ($beneficiario != '') {
            $beneficiario = stripcslashes($beneficiario);
            $res = $this->find_all_by_sql(" select beneficiario.id,beneficiario.titular_id,beneficiario.persona_id,beneficiario.nombre1,beneficiario.apellido1,cast(beneficiario.cedula as integer) from titular,beneficiario where beneficiario.titular_id in (select id from titular) and beneficiario.cedula like '%{$beneficiario}%'");
            
            if ($res) {
                foreach ($res as $beneficiario) {
                    $beneficiarios[] = array('id'=>$beneficiario->id,'value'=>$beneficiario->cedula,'idnombre'=>$beneficiario->nombre1.' '.$beneficiario->nombre2.' '.$beneficiario->apellido1.' '.$beneficiario->apellido2);
                }
                return $beneficiarios;
            }
        }
        return array('no hubo coincidencias');
    }
    /**
     * Método para setear un Objeto
     * @param string    $method     Método a ejecutar (create, update)
     * @param array     $data       Array para autocargar el objeto
     * @param array     $optData    Array con con datos adicionales para autocargar
     */
    public static function setbeneficiario($method, $data=array(), $optData=array()) {
        $obj = new beneficiario($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        //Creo otro objeto para comparar si existe
        $old = new beneficiario($data);
        //$check = $old->_getbeneficiarioRegistrado('find_first');
        $check = false;
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
     * Método para listar beneficiarioes
     * @return obj
     */

    public function getListadobeneficiario($estado, $order='', $page=0) {
        $columns = 'beneficiario.*';        
        $conditions = "";//Por el super usuario
        if($page) {
            return $this->paginated("columns: $columns", "page: $page");
        } else {
            return $this->find("columns: $columns");
        }  
    }

       public function getListBeneficiario() {
        return $this->find_all_by_sql("select beneficiario.id,beneficiario.titular_id, beneficiario.nombre1,beneficiario.apellido1, cast(beneficiario.cedula as float) from beneficiario");


    }
    public function buscar($titular_id){
        return $this->find_all_by_sql("select beneficiario.id,beneficiario.titular_id,(beneficiario.nombre1 || ' ' || beneficiario.apellido1) as nombrefull, cast(beneficiario.cedula as integer) from beneficiario where  beneficiario.titular_id = '{$titular_id}'");
    }    

    /**
     * Método para verificar si un beneficiaro ya se encuentra registrada
     * @return obj
     */
    protected function _getbeneficiarioRegistrado($method='count') {
        $conditions = "cedula = '$this->cedula'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($method != 'count' && $method !='find_first') {
            $method = 'count';
        }
        return $this->$method("conditions: $conditions");
    }

    /**
     * Método para obtener la información de un beneficiario solo la informacion basica a traves de su id
     * @return type
     */
    public function getInformacionbeneficiario($beneficiario) {
        $beneficiario = Filter::get($beneficiario, 'int');
        if(!$beneficiario) {
            return NULL;
        }
        $columns = 'beneficiario.*';
        $condicion = "beneficiario.id = $beneficiario";        
        return $this->find_first("columns: $columns", "conditions: $condicion");
    } 
    //editar el beneficiario
    public static function setEBeneficiario($method, $data=array(), $optData=array()) {
        $obj = new beneficiario($data);
        if(!empty($optData)) {
            $obj->dump_result_self($optData);
        }
        $method = 'update';
        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }
/**
* Callback que se ejecuta antes de guardar/modificar
*/
  public function before_save() {
    // validar aqui lo del tipo de beneficiario ;) 
      $paren = Filter::get($this->parentesco_id, 'numeric');
      $titu = Filter::get($this->titular_id, 'numeric');
      $parti = Filter::get($this->participacion, 'numeric');
      $columns = 'beneficiario.participacion, beneficiario.parentesco_id ';
      $conditions = "titular_id = $titu ";
      $bene = $this->find("columns: $columns", "conditions: $conditions");

      if($_POST["metodo"]!="editar"){
      $acum = 0;
      foreach($bene as $bn):
        if (($bn->parentesco_id==$paren)&&($bn->parentesco_id==2)){
            DwMessage::error('Ya existe el benficiario Esposo(a) agregado.');
            return 'cancel';
        }
        elseif (($bn->parentesco_id==$paren)&&($bn->parentesco_id==3)) {
            DwMessage::error('Ya existe el benficiario Concubino(a) agregado.');
            return 'cancel';
        }
        elseif (($bn->parentesco_id==$paren)&&($bn->parentesco_id==4)) {
            DwMessage::error('Ya existe el benficiario Madre agregado.');
            return 'cancel';
        }
        elseif (($bn->parentesco_id==$paren)&&($bn->parentesco_id==5)) {
            DwMessage::error('Ya existe el benficiario Padre agregado.');
            return 'cancel';
        }
        elseif ( (($bn->parentesco_id==2)&&($paren==3))||( ($paren==2)&&($bn->parentesco_id==3) ) ) {
            DwMessage::error('Ya existe un Esposo(a), no puede agregar un Concubino(a) o Visceversa.');
            return 'cancel';
        }
        //evitar que añada un beneficiario (esposa, o concubino) si ha excluido recientemente uno 
        $bn->fecha_exclusion;
        $actual = date("Y-m-d");
        $datetime1 = new DateTime($bn->fecha_exclusion);
        $datetime2 = new DateTime($actual);
        $intervalo = $datetime1->diff($datetime2, $absolute=true);
        $mes = $intervalo->format('%a');
        if(((($bn->parentesco_id==2)&&($paren==3))||(($paren==2)&&($bn->parentesco_id==3))) || ($mes <=6)){
          DwMessage::error('No puede Cargar, Esposo(a) o Concubino(a) hasta que haya trasncurrido 6 meses desde la exclusión del anterior.');
            return 'cancel';
        }
        $acum = $acum + $bn->participacion;
      endforeach;

      if($acum+$parti>100){
        $this->participacion = 100-$acum;
      }
      if($paren==4){
        $this->sexo='F';
      }elseif ($paren==5) {
        $this->sexo='M';
      }
      //espos@ 2
      //madre 4 padre 5 concubino 3 
      if(($acum>=100)&&($parti>0)) {
            DwMessage::error('Lo sentimos, pero ya has agotado la cobertura de la poliza de vida asignada a tus beneficiarios.');
            return 'cancel';
      }
      //no AGREGAR BENEFICIARIO S EXTERNOS SI NO HAY DISPONIBLIDAD DE LA PARTICIPACION EN LA POLIZA DE VIDA
      if(($acum>=100)&&($this->beneficiario_tipo_id=="2") ) {
        DwMessage::error('No puedes agregar beneficiarios externos ya que has agotado la participación en la poliza de vida.');
        return 'cancel';
      }

      //validar lo de los hijos mayores de 18 sean externos 
        $fecha = $this->fecha_nacimiento;
        $ano  = substr($fecha,0,4);
        $anoac = date("Y");
        if(($anoac-$ano >18)&&($this->parentesco_id=="1")){
            $this->beneficiario_tipo_id = "2";
        }
      $this->fecha_inclusion = date("y-m-d");
      } //cierre del if de metodo distindo de edicion

      //fecha inclusion

      //guardar en mayusculas todo
      $this->nombre1 = strtoupper($this->nombre1);
      $this->nombre2 = strtoupper($this->nombre2);
      $this->apellido1 = strtoupper($this->apellido1);
      $this->apellido2 = strtoupper($this->apellido2);
      $this->observacion = strtoupper($this->observacion);
      $this->direccion = strtoupper($this->direccion);
      $this->correo_electronico = strtoupper($this->correo_electronico);
      $this->motivo_exclusion = strtoupper($this->motivo_exclusion);
      $this->motivo_reactivacion = strtoupper($this->motivo_reactivacion);
    }    

//------ Listado de todos los beneficiarios de un titular en especificoooo ----- 16/07/2014 
    public function getListadoBeneTitular($titular){
       return $this->find_all_by_sql("SELECT DATE_PART('year', now()) - DATE_PART('year', beneficiario.fecha_nacimiento) as edad, beneficiario.cedula, beneficiario.nombre1, beneficiario.nombre2, beneficiario.apellido1, beneficiario.fecha_nacimiento, beneficiario.nacionalidad, beneficiario.apellido2, beneficiario.sexo, beneficiario.participacion, beneficiario.estado_beneficiario, beneficiario.id, beneficiario_tipo.descripcion, parentesco.id as idparentesco, parentesco.descripcion as parentesco FROM beneficiario INNER JOIN beneficiario_tipo ON beneficiario.beneficiario_tipo_id = beneficiario_tipo.id INNER JOIN parentesco ON beneficiario.parentesco_id = parentesco.id WHERE (beneficiario.titular_id = $titular) and (beneficiario.estado_beneficiario='1')");
    }
}
?>
