<?php
/**
 * @category
 * @package     Models
 * @subpackage
 * @author      Alexis Borges
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)  
 */
class SolicitudServicioPatologia extends ActiveRecord {        
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('patologia');
    }    
    /**
     * Método para registrar los privilegios a los perfiles
     */
    public static function setSolServicioPatolgia($datos,$idsolicitud){
        $obj = new SolicitudServicioPatologia();
        $obj->begin();
        if(!empty($datos)){
            foreach($datos as $value) {                 
                $obj->patologia_id = $value;
                $obj->solicitud_servicio_id = $idsolicitud;
                if($obj->exists("patologia_id=$obj->patologia_id AND solicitud_servicio_id=$obj->solicitud_servicio_id")){
                    continue;
                }
                if(!$obj->create()) {            
                    $obj->rollback();
                    return FALSE;
                }
            }
        }
        $obj->commit();
        return TRUE;
    }
    
    public  function getInformacionSolicitudServicioPatologia($id, $order='solicitud_servicio_patologia.id') {
        $id = Filter::get($id, 'numeric');
        $columnas = 'solicitud_servicio_patologia.* , P.* ';
        $join= 'INNER JOIN patologia as P ON (P.id = solicitud_servicio_patologia.patologia_id) ';
        $condicion = "solicitud_servicio_patologia.solicitud_servicio_id = '$id'"; 

        // return $this->find("columns: $columnas", "join: $join", "conditions: $condicion", "order: $order");
        return $this->find("columns: $columnas", "conditions: $condicion", "join: $join", "order: $order");
    } 




}
?>