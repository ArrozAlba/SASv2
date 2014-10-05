<?php
/**
 * infoalex
 * @category
 * @package     Models SolicitudServicioFactura
 * @subpackage
 * @author      Alexis borges
 * @copyright   Copyright (c) 2014 UPTP - (PNFI Team) (https://github.com/ArrozAlba/SASv2)
 */
class SolicitudServicioFactura extends ActiveRecord {
   //funcion para guardar en la bd las solicitudes y facturas 
    public static function setSolicitudServicioFactura($idfactura, $idsolicitud){
        $obj = new SolicitudServicioFactura();
        $obj->begin();
        $j=0;
        while($j<count($idfactura)){ 
            $obj->factura_id = $idfactura;
            $obj->solicitud_servicio_id = $idsolicitud;
            if($obj->exists("factura_id='$obj->factura_id' AND solicitud_servicio_id='$obj->solicitud_servicio_id'")){
                    continue;
                }
                if(!$obj->create()) {            
                    $obj->rollback();
                    return FALSE;
                }
        $j++;
        }
        $obj->commit();
        return TRUE;
    }
}
