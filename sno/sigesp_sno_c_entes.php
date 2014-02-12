<?php
class sigesp_sno_c_entes
{
	

	function sigesp_sno_c_entes()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Function: sigesp_sno_c_entes
		// Access: public (sigesp_sno_d_entes)
		// Description: Constructor de la Clase
		// Creado Por: Lic. Edgar A. Quintero
		// Fecha Creación: 27/01/2009 								
		// Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();
		require_once("../shared/class_folder/sigesp_conexiones.php");
		$this->io_conexiones=new conexiones();	
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();				
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		

	}


	function uf_consulta_ente($codente,$ente,$criterio){
				
				//////////////////////////////////////////////////////////////////////////////
				// Function: uf_consulta_ente
				// Access: private
				// Arguments: $codente  // código de ente
				//            $ente     //Nombre del ente
				// $criterio  //Criterio de Busqueda 
				// Returns: Un arreglo con los Datos de la consulta
				// Description: Función que retorna los registros de una consulta de entes
				// Creado Por: Lic. Edgar A. Quintero
				// Fecha Creación: 27/01/2009								
				// Fecha Última Modificación : 
				//////////////////////////////////////////////////////////////////////////////
				
				if($_SESSION["ls_gestor"] == 'POSTGRES'){$postgres_ilike = 'I';}
				
				switch($criterio){
						  
					  	case "por_codigo":
							$sql_criterio = " WHERE codigo_ente='".$codente."'";
							break;
						
						case "por_ultimo":
							$sql_criterio = " ORDER BY id_ente DESC LIMIT 1";
							break;
									 
					   case "por_listado":
							$sql_criterio = " WHERE codigo_ente ".$postgres_ilike."LIKE('%".$codente."%') AND descripcion_ente ".$postgres_ilike."LIKE('%".$ente."%') ORDER BY codigo_ente";
							break;
				}
										   
				$query_rs = "SELECT * FROM sno_entes".$sql_criterio;
				return $this->io_conexiones->conexion($query_rs,'arreglo','<b>CLASE:</b> Concepto <br><b>METODO:</b> uf_consulta_entes');	
	
				
	
	}//end function uf_consulta_ente
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_modifica_ente($codente,$ente,$porcentaje){
	
				//////////////////////////////////////////////////////////////////////////////
				// Function: uf_modificar_ente
				// Access: private
				// Arguments: $codente  // código de ente
				//            $ente     //Nombre del ente
				//            $porcentaje //Porcentaje de retencion del pago al ente
				// 
				// Returns: Un arreglo con la validación y el mensaje
				// Description: Función que modifica un registro en la tabla sno_entes
				// Creado Por: Lic. Edgar A. Quintero
				// Fecha Creación: 29/01/2009								
				// Fecha Última Modificación : 
				//////////////////////////////////////////////////////////////////////////////					
				
					$query_rs = sprintf("UPDATE sno_entes 
					                     SET descripcion_ente='%s',porcentaje_ente='%s' 
										 WHERE codigo_ente='%s' AND codemp='".$this->ls_codemp."' ",
									$ente,
									$porcentaje,
									$codente
								);        											   
		   			 $lb_valido = $this->io_conexiones->conexion($query_rs,'','<b>CLASE:</b> Concepto <br><b>METODO:</b> uf_consulta_entes','update','codigo_ente');	
					 
					 					 				
					$mensajex = 'El ente ha sido modificado exitosamente';					
					return array('valido'=>$lb_valido,'mensaje'=>$mensajex);
					
				
	
	}//end function uf_modifica_ente
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_ente($codente,$ente,$porcentaje){
	
				//////////////////////////////////////////////////////////////////////////////
				// Function: uf_insertar_ente
				// Access: private
				// Arguments: $codente  // código de ente
				//            $ente     //Nombre del ente
				//            $porcentaje //Porcentaje de retencion del pago al ente
				// 
				// Returns: Un arreglo con la validación y el mensaje
				// Description: Función que inserta un registro en la tabla sno_entes
				// Creado Por: Lic. Edgar A. Quintero
				// Fecha Creación: 29/01/2009								
				// Fecha Última Modificación : 
				//////////////////////////////////////////////////////////////////////////////
				
	
				$existe = $this->uf_consulta_ente($codente,'','por_codigo');
		        $ultimo = $this->uf_consulta_ente('','','por_ultimo');
				$ultimo = $ultimo['fila']['id_ente'];
				$ultimo++;
				if (!$existe['cantidad']){		
				
					$query_rs = sprintf("INSERT INTO sno_entes(codemp,id_ente,codigo_ente,descripcion_ente,porcentaje_ente) VALUES ('".$this->ls_codemp."','%s','%s','%s','%s')",														
											$ultimo,
											$codente,
											$ente,
											$porcentaje					
										);								
					$lb_valido = $this->io_conexiones->conexion($query_rs,'','<b>CLASE:</b> Concepto <br><b>METODO:</b> uf_consulta_entes','insert','codigo_ente');
					
					$mensajex = 'El ente ha sido insertado exitosamente';					
					return array('valido'=>$lb_valido,'mensaje'=>$mensajex);
					
				}else{
				
					$mensajex = "ERROR: Ese Código ya existe !";					
					return array('valido'=>false,'mensaje'=>$mensajex);
				}
	
	}
	
	function uf_eliminar_ente($codente){
	
				//////////////////////////////////////////////////////////////////////////////
				// Function: uf_eliminar_ente
				// Access: private
				// Arguments: $codente  // código de ente				
				// Description: Función que elimina un registro en la tabla sno_entes
				// Creado Por: Lic. Edgar A. Quintero
				// Fecha Creación: 29/01/2009								
				// Fecha Última Modificación : 
				//////////////////////////////////////////////////////////////////////////////
				
	
				$existe = $this->uf_consulta_ente($codente,'','por_codigo');
		        
				if ($existe['cantidad']){		
				
					$query_rs = "DELETE FROM sno_entes WHERE codigo_ente='".$codente."' AND codemp='".$this->ls_codemp."' ";						
					$lb_valido = $this->io_conexiones->conexion($query_rs,'','<b>CLASE:</b> Concepto <br><b>METODO:</b> uf_consulta_entes','insert','codigo_ente');
					
					$mensajex = 'El ente ha sido eliminado exitosamente';					
					return array('valido'=>$lb_valido,'mensaje'=>$mensajex);
					
				}else{
				
					$mensajex = "ERROR: El Código no existe !";					
					return array('valido'=>false,'mensaje'=>$mensajex);
				}
	
	}
	
		
}// end function sigesp_sno_c_entes
?>
