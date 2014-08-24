<?php
/**
 * infoalex
 * @category
 * @package     Models
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class Discapacidad extends ActiveRecord {
    
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_many('discapacidad_titular');  
        $this->has_many('discapacidad_beneficiario'); 
    }  
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionDiscapacidad($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'discapacidad.*';
        $join = '';
        $condicion ="discapacidad.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoDiscapacidad($order='order.nombre.asc', $page='', $empresa=null) {
        $columns = 'discapacidad.id as iddiscapacidad, discapacidad.nombre as discapacidad';
        $join = '';
        $order = $this->get_order($order, 'discapacidad', array('discapacidad'=>array('ASC'=>'discapacidad.id ASC',
                                                                              'DESC'=>'discapacidad.id DESC'),
                                                            'observacion'));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order", "page: $page");            
        }
    }
    
    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setDiscapacidad($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Discapacidad($data);
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
     * Método para obtener discapacidades
     * @return obj
     */
   public function obtener_discapacidades($discapacidad) {
        if ($discapacidad != '') {
            $discapacidad = stripcslashes($discapacidad);
            $res = $this->find('columns: nombre', "nombre like '%{$discapacidad}%'");
            if ($res) {
                foreach ($res as $discapacidad) {
                    $discapacidades[] = $discapacidad->nombre;
                }
                return $discapacidades;
            }
        }
        return array('no hubo coincidencias');
    }
    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        $this->nombre = Filter::get($this->nombre, 'string');
        $this->observacion = Filter::get($this->observacion, 'string');
           
        $conditions = "nombre = '$this->nombre'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe una discapacidad registrada con el mismo nombre');
            return 'cancel';
        }
        
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
      /*  if($this->id == 1) { //Para no eliminar la información de sucursal
            DwMessage::warning('Lo sentimos, pero esta discapacidad no se puede eliminar.');
            return 'cancel';
        }*/
    }
    
}
