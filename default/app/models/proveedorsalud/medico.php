<?php
/**
 * infoalex
 * @category
 * @package     Models
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class Medico extends ActiveRecord {
    
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
  /*      $this->belongs_to('empresa');
        $this->belongs_to('ciudad');
        $this->has_many('usuario');

        $this->validates_presence_of('sucursal', 'message: Ingresa el nombre de la sucursal');        
        $this->validates_presence_of('direccion', 'message: Ingresa la dirección de la sucursal.');
        $this->validates_presence_of('ciudad_id', 'message: Indica la ciudad de ubicación de la sucursal.');
    */            
    }  
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionMedico($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'medico.*';
        $join = '';
        $condicion ="medico.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoMedico($order='order.rif.asc', $page='', $empresa=null) {
        $columns = 'medico.*';
        $join = '';        
        //$conditions 
        $order = $this->get_order($order, 'medico', array('medico'=>array('ASC'=>'medico.rmpss ASC, medico.rif ASC, medico.nombre1', 
                                                                        'DESC'=>'medico.rmpss DESC, medico.rif ASC, medico.nombre1'),
                                                            'nombre2,apellido1, apellido2, correo_electronico'));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order", "page: $page");            
        }
    }
    /**
     * Método para obtener medicos
     * @return obj
     */
   public function obtener_medicos($medico) {
        if ($medico != '') {
            $medico = stripcslashes($medico);
            $res = $this->find('columns: nombre1,apellido1', "nombre1 like '%{$medico}%' or apellido1 like '%{$medico}%'");
            if ($res) {
                foreach ($res as $medico) {
                    $medicos[] = $medico->nombre1.' '.$medico->apellido1;
                }
                return $medicos;
            }
        }
        return array('no hubo coincidencias');
    }        
    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setMedico($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Medico($data);
        //Se verifica si contiene una data adicional para autocargar
        if ($optData) {
            $obj->dump_result_self($optData);
        }   
        if($method!='delete') {
            //$obj->ciudad_id = Ciudad::setCiudad($obj->ciudad)->id;        
        }
        $rs = $obj->$method();
        
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        $this->rif = Filter::get($this->rif, 'string');
           
        $conditions = "rif = '$this->rif'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe un Medico registrada con el mismo RIF.');
            return 'cancel';
        }
        
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
        if($this->id == 1) { //Para no eliminar la información de sucursal
            DwMessage::warning('Lo sentimos, pero esta sucursal no se puede eliminar.');
            return 'cancel';
        }
    }
    
}
