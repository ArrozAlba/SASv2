<?php
/**
 * infoalex
 *
 * @category
 * @package     Models Cobertura
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */

class Cobertura extends ActiveRecord {
    
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
    public function getInformacionCobertura($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'cobertura.*';
        $join = '';
        $condicion = "cobertura.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoCobertura($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'cobertura.*';
        $join = '';        
        $conditions = "";
        $order = $this->get_order($order, 'cobertura', array('cobertura'=>array('ASC'=>'cobertura.descripcion ASC, cobertura.tipo_cobertura ASC', 'DESC'=>'cobertura.descripcion DESC, cobertura.tipo_cobertura ASC',),'descripcion', 'tipo_cobertura', 'monto_cobertura','fecha_inicio', 'fecha_fin', 'observacion'));
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
    public static function setCobertura($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Cobertura($data);
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
       $this->descripcion= strtoupper($this->descripcion);
       $this->observacion= strtoupper($this->observacion);
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
    }
    
}