<?php
/**
 * @category
 * @package     Models
 * @subpackage
 * @author      Alexis Borges
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)  
 */
class SolicitudServicioPatologia extends ActiveRecord {
    //Se desabilita el logger para no llenar el archivo de "basura"
        
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
}
?>