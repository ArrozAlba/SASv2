<?php 

class sigesp_c_logs {


	function uf_sigesp_log($texto_log,$as_ruta){
		
			   $fp = @fopen($as_ruta,"a");
			   @fwrite($fp,date("j/n/Y")." - ".date("h:i s a").':    '.$texto_log."\r\n");	   
			   @fclose($fp); 

	}



}// fin de la clase sigesp_c_logs


?>
