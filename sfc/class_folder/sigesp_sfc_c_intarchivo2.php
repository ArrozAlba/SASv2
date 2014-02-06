<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_intarchivo{

   var $io_archivo;
   var $ls_directorio;

   function sigesp_sfc_c_intarchivo($ls_ruta){

     $this->ls_directorio=$ls_ruta;
   }


   function crear_archivo($as_codigo){

       $ls_fecha=date('dmY');
       $ls_archivo=$this->ls_directorio."/".$as_codigo.".txt";
       $this->io_archivo = fopen($ls_archivo, "a");
   }


   function escribir_archivo($ls_contenido){

       fwrite($this->io_archivo,$ls_contenido);
   }

   function cerrar_archivo(){

       fclose($this->io_archivo);
   }

   function leer_archivo(){

       $ls_contenido="";

       $ls_archivo=$this->ls_directorio."/".$as_codigo.".txt";
       $this->io_archivo = fopen($ls_archivo, "r");
       $ls_contenido = fread($archivo, filesize($ls_archivo));
       fclose($this->io_archivo);

       return $ls_contenido;
   }
}
?>
