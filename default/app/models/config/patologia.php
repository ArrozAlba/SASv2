<?php
/**
 * infoalex
 * @category
 * @package     Models
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class Patologia extends ActiveRecord {
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_many('solicitud_servicio_patologia');
    }  
        
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionPatologia($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'patologia.*';
        $join = '';
        $condicion ="patologia.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoPatologia($order='order.nombre.asc', $page='', $empresa=null) {
        $columns = 'patologia.*';
        $join = '';        
        $conditions ="activo=TRUE ";
        $order = $this->get_order($order, 'patologia', array('patologia'=>array('ASC'=>'patologia.nombre ASC, patologia.observacion ASC','DESC'=>'patologia.nombre DESC, patologia.observacion ASC'),'observacion'));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "conditions: $conditions", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order", "conditions: $conditions", "page: $page");            
        }
    }
    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setPatologia($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Patologia($data);
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
     * Método para obtener patologias
     * @return obj
     */
   public function obtener_patologias($patologia) {
        if ($patologia != '') {
            $patologia = stripcslashes($patologia);
            $res = $this->find('columns: id,descripcion', "descripcion like '%{$patologia}%' AND activo=TRUE" );
            if ($res) {
                foreach ($res as $patologia) {
                    $patologias[] = array('id'=>$patologia->id,'value'=>$patologia->descripcion);
                }
                return $patologias;
            }
        }
        return array('no hubo coincidencias');
    }
    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        $this->descripcion = Filter::get($this->codigo, 'string');
        $this->descripcion = Filter::get($this->descripcion, 'string');
        $this->observacion = Filter::get($this->observacion, 'string');
           
        $conditions = "descripcion = '$this->descripcion'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe una patologia registrada con el mismo nombre.');
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
