<?php
/**
 * infoalex
 *
 * @category
 * @package     Models Reembolso
 * @subpackage
 * @author      ALEXIS BORGES TUAALEXIS@GMAIL.COM
 * @copyright    
 */
class Reembolso extends ActiveRecord {
    
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
    public function getInformacionReembolso($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'reembolso.*';
        $join = '';
        $condicion = "reembolso.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoReembolso($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'reembolso.*';
        $join = '';        
        $conditions = "";
        $order = $this->get_order($order, 'reembolso', array('reembolso'=>array('ASC'=>'reembolso.descripcion ASC, reembolso.tipo_reembolso ASC',
                                                                              'DESC'=>'reembolso.descripcion DESC, reembolso.tipo_reembolso ASC',
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
    public static function setReembolso($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Reembolso($data);
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


     public function getListadoRegistroReembolso($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $conditions = "a.estado_solicitud = 'R' ";
        $order = $this->get_order($order, 'a', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }




    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {

    }
    
}
