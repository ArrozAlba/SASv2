<?php
/**
 * infoalex
 *
 * @category
 * @package     Models SolicitudMedicina
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class SolicitudMedicina extends ActiveRecord {
    
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
    }  
    
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionSolicitudMedicina($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'solicitud_medicina.*';
        $join = '';
        $condicion = "solicitud_medicina.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoSolicitudMedicina($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'solicitud_medicina.*';
        $join = '';        
        $conditions = "";
        $order = $this->get_order($order, 'solicitud_medicina', array('solicitud_medicina'=>array('ASC'=>'solicitud_medicina.descripcion ASC, solicitud_medicina.tipo_solicitud_medicina ASC',
                                                                              'DESC'=>'solicitud_medicina.descripcion DESC, solicitud_medicina.tipo_solicitud_medicina ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columns", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "order: $order", "page: $page");            
        }
    }
    
    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setSolicitudMedicina($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new SolicitudMedicina($data);
        //Se verifica si contiene una data adicional para autocargar
        if ($optData) {
            $obj->dump_result_self($optData);
        }   
        
        /*if($method!='delete') {
            $obj->ciudad_id = Ciudad::setCiudad($obj->ciudad)->id;        
        }*/
        $rs = $obj->$method();
        
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        /* 
        $this->slug = DwUtils::getSlug($this->sucursal); 
        $this->direccion = Filter::get($this->direccion, 'string');
        $this->telefono = Filter::get($this->telefono, 'numeric');
        $this->celular = Filter::get($this->celular, 'numeric');
        $this->fax = Filter::get($this->fax, 'numeric');        
        
        $conditions = "sucursal = '$this->sucursal' AND ciudad_id = $this->ciudad_id AND empresa_id = $this->empresa_id";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe una sucursal registrada con el mismo nombre y ciudad.');
            return 'cancel';
        }
   */     
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
        /*if($this->id == 1) { //Para no eliminar la información de sucursal
            DwMessage::warning('Lo sentimos, pero esta sucursal no se puede eliminar.');
            return 'cancel';
        }*/
    }
    
}