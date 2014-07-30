<?php
/**
 * 
 *
 * Clase para el manejo de SMS's
 *
 * @package     Libs
 * @author      Javier León
 * @copyright   Copyright (c) 2014 Arroz del Alba
 */

class DwSms {

    /*
     * Metodo para enviar sms
     */
     
    public static function enviar_sms($destinatario, $contenido) {
       system( '/usr/bin/gammu -c /etc/gammu-smsdrc --sendsms EMS ' . escapeshellarg( $destinatario ) . ' -text ' . escapeshellarg( $contenido ) ); 
//        $contenido= "Sr. ".$nombre." ".$apellido." Su solicitud ha sido aprobada Aprobada con el codigo: ".$cod;
//        system( '/usr/bin/gammu -c /etc/gammu-smsdrc --sendsms EMS ' . escapeshellarg( $destinatario ) . ' -text ' . escapeshellarg( $contenido ) ); 
    }
?>
