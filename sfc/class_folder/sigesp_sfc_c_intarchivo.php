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
   var $io_msg;
   var $ls_directorio;
   var $zip;
   var $io_sql;
   var $is_archivo;
   

   function sigesp_sfc_c_intarchivo($ls_ruta){
     
	 require_once("../shared/class_folder/class_mensajes.php");
	 require_once("../shared/class_folder/sigesp_include.php"); 
	 require_once("../shared/class_folder/class_sql.php");
	 include("class_folder/createzip.php");
     $this->ls_directorio=$ls_ruta;
	 //$this->zip = new createzip();
	 $this->io_msg=new class_mensajes();
	 $io_include = new sigesp_include();
	 $io_connect = $io_include->uf_conectar();		
	 $this->io_sql= new class_sql($io_connect);
   }


   function crear_archivo($as_codigo){

       $ls_fecha=date('dmY');
	   $this->is_archivo=$this->ls_directorio."\ ".$as_codigo.$ls_fecha.".txt";
       $this->io_archivo = fopen($this->is_archivo, "a");
   }


   function escribir_archivo($ls_contenido){

       fwrite($this->io_archivo,$ls_contenido);
   }

   function cerrar_archivo(){

       fclose($this->io_archivo);
   }

   function leer_archivo($ls_archivo){

       $ls_contenido="";
	    
       $this->io_archivo = fopen($ls_archivo, "r");
       $ls_contenido = fread($this->io_archivo, filesize($ls_archivo));
       fclose($this->io_archivo);

       return $ls_contenido;
   }
   
   function procesar_archivo_comprimido($ls_archivo){

       $lb_valido=true;
	   
	   $lb_valido=$this->descomprimir($ls_archivo);
	   
	   if($lb_valido){
	      
		 $la_archivos=$this->scanDirectories("arc_procesados");
		 $this->io_sql->begin_transaction();
		 for($z=0;$z<count($la_archivos);$z++){
		   
		   $ls_contenido=$this->leer_archivo($la_archivos[$z]);
		   $li_numrows=$this->io_sql->execute($ls_contenido);
		   if(($li_numrows==false)&&($this->io_sql->message!="")){
			    $lb_valido=false;
				$this->io_sql->rollback();
				$this->io_msg->message ("Error al procesar el archivo ".$la_archivos[$z]);
				break;
			}
			else{
			  if($li_numrows>0){
			     $this->io_msg->message ("Fue procesado exitosamente el archivo ".$la_archivos[$z]); 
		       }
			   else{
			      $this->io_sql->rollback();
				  $this->io_msg->message ("Error al procesar el archivo ".$la_archivos[$z]); 
				  break;
			   }
			
			}
			   
		 }
	     $this->io_sql->commit(); 
	   } 
		
       return $lb_valido;
   }
   
   
   function descomprimir($ls_archivo) {
     $lb_valido=true;
	 
	 if ($this->zip->open($ls_archivo) === TRUE) {
	   $this->zip->extractTo($this->ls_directorio);
       $this->zip->close();
       
     } else {
       $this->io_msg->message ("Error al descomprimir el archivo. Funcion Descomprimir!!"); 
	   $lb_valido=false;
	   
     }
	 
	 return $lb_valido;
   }
   
 
   
   function scanDirectories($rootDir, $allData=array()) {
    // set filenames invisible if you want
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
    // run through content of root directory
    $dirContent = scandir($rootDir);
    foreach($dirContent as $key => $content) {
        // filter all files not accessible
        $path = $rootDir.'/'.$content;
        if(!in_array($content, $invisibleFileNames)) {
            // if content is file & readable, add to array
            if(is_file($path) && is_readable($path)) {
                // save file name with path
                $allData[] = $path;
            // if content is a directory and readable, add path and name
            }elseif(is_dir($path) && is_readable($path)) {
                // recursive callback to open new directory
                $allData = $this->scanDirectories($path, $allData);
            }
        }
    }
    return $allData;
   }
   
   function leer_archivoin(){

       $ls_contenido = fread($this->io_archivo, filesize($this->is_archivo));
       return $ls_contenido;
   }
   
   function crear_zip($ruta,$contenido){
   
     $ls_fecha=date('dmY');
	 $la_archivo=array("CONTABILIDAD_PRESUPUESTO/CIERRE".$ls_fecha.".txt"=>$contenido);
     $this->zip->createzip($la_archivo,$ruta);
   }
   
   
}
?>
