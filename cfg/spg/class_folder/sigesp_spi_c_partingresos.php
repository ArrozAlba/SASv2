<?php
class sigesp_spi_c_partingresos
{
	 var $int_scg;
	 var $dat;
	 var $msg;
	 var $fun;
	 var $int_spi;
	 var $is_msg_error;
	 var $io_seguridad;
	 var $io_sql;
	 
	function sigesp_spi_c_partingresos()
	{
		require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_conect          = new sigesp_include();
        $conn               = $io_conect->uf_conectar ();
		$this->msg          = new class_mensajes();
		$this->fun          = new class_funciones();
		$this->msg          = new class_mensajes();
		$this->int_scg      = new class_sigesp_int_scg();	
		$this->int_spi      = new class_sigesp_int_spi();
		$this->dat          = $_SESSION["la_empresa"];
		$this->io_seguridad = new sigesp_c_seguridad();
	    $this->io_chkrel    = new sigesp_c_check_relaciones($conn);
	    $this->io_sql       = new class_sql($conn );
		$this->ls_database  = $_SESSION["ls_database"];
		$this->ls_gestor    = $_SESSION["ls_gestor"];
	}

function uf_valida_cuenta($as_cuenta ,$as_cuenta_scg)
{
		
	$ls_programa ="";$ls_Status="";
	$ls_spi_cuenta=trim($as_cuenta);
	$ls_formato=trim($this->dat["formplan"]);
	$ls_formato_spi=trim($this->dat["formspi"]);
	$ls_cuenta_pad=$this->int_scg->uf_pad_cuenta_plan(&$ls_formato,$ls_spi_cuenta);

	$li_len_cta=strlen($ls_cuenta_pad);
	$li_len_formato=strlen($ls_formato);
	
	if($li_len_cta!=$li_len_formato)
	{
		$this->msg->message("Cuentas no poseen el formato del plan unico");
		return false;
	}
	
	$li_len_ctaspi=strlen($ls_spi_cuenta);
	$li_len_formato_spi=strlen(str_replace('-','',$ls_formato_spi));
	
	if($li_len_ctaspi!=$li_len_formato_spi)
	{
		$this->msg->message("Formato de presupuesto ".$ls_formato_spi." no corresponde al de la cuenta introducida ".$ls_spi_cuenta);
		return false;
	}
	if($li_len_cta<$li_len_ctaspi)
	{
		$ls_NextCuenta = $ls_cuenta_pad;
	}
	else
	{
		$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_cuenta_pad);			
	}
	
	$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_NextCuenta,&$as_denominacion);
	if(!$lb_valido)
	{
	/*	$this->msg->message("La Cuenta no Existe en el Plan Unico de Cuentas de Recursos y Egresos");
		return false;*/
		$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_spi_cuenta,&$as_denominacion);
	}
	if(substr($ls_spi_cuenta,0,1)!=trim($this->dat["ingreso_p"]))
	{
		$this->msg->message("Las Cuentas de Ingreso deben comenzar con ".$this->dat["ingreso_p"]);
		return false;
	}

	// Verifico si es de nivel apropiado

	$li_nivel=$this->int_spi->uf_spi_obtener_nivel($ls_spi_cuenta);
	
	if($li_nivel <= 1)
	{
		$this->msg->message("Las Cuentas de Nivel 'Partida' no son Validas");
		return false;
	}
	
	if($li_nivel<= 2)
	{
		$this->msg->message("Las Cuentas de Nivel 'Genericas' no son Validas");
		return false;
	}	
		
//	*-- Verifico que si no hay cuentas con movimientos de nivel superior
	$li_nivel =$this->int_spi->uf_spi_obtener_nivel($ls_spi_cuenta);
	
	if($li_nivel > 1)
	{
		$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_spi_cuenta);
		
		do 
		{
			if($this->int_spi->uf_spi_select_cuenta($this->dat["codemp"], $ls_NextCuenta,&$as_status,&$as_denominacion, $as_cuenta_scg))
			{
				if($as_status=="C")
				{
					$this->msg->message("Existen cuentas de nivel superior con Movimiento");
					return false;
				}
			}
			$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_NextCuenta);

		$li_nivel=$this->int_spi->uf_spi_obtener_nivel($ls_NextCuenta);
		}while( $li_nivel > 1);
	}
	return true;
}//uf_valida_cuenta

function uf_procesar_cuentas($ls_cuentaspi,$ls_dencuentaspi,$ls_cuentascg,
						     $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
							 $ls_codestpro5,$ls_estcla,$aa_security)
{
		$lb_valido=true;				
		//Tomo los valores anteriores de la cuenta y denominacion.
		if($this->uf_spi_select_cuenta($this->dat["codemp"],$ls_cuentaspi,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
		                               $ls_codestpro4,$ls_codestpro5,$ls_estcla))
		{
				$lb_valido=$this->uf_spi_update_cuenta($ls_cuentaspi,$ls_dencuentaspi,$ls_cuentascg,
													   $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
													   $ls_codestpro5,$ls_estcla);
				if($lb_valido)
				{
					$ls_ctapu="";
					$ls_denctapu="";
					$ls_ctaplan="";
					$this->is_msg_error="Denominación Actualizada";
					$disabled="";
					$this->int_spi->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_desc_event="Actualizo la cuenta ".$ls_spi_cuenta." asociada a la cuenta contable ".$ls_scg_cuenta; 
					$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
					////////////////////////////////         SEGURIDAD               //////////////////////////////
					
				}
				else
				{
					$this->is_msg_error="Error ".$this->int_spi->io_sql->message;
				}
		}
		else
		{ 
			 $lb_valido = $this->uf_spi_insert_cuenta($ls_cuentaspi,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
													  $ls_codestpro5,$ls_estcla);
			 if (!$lb_valido)
			 {
			    $this->int_spi->io_sql->rollback();
  			    $this->is_msg_error="Error al guardar cuenta ".$ls_cuentaspi;
 
			 }
			 else
			 {
				 /////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_desc_event="Inserto la cuenta ".$ls_cuentaspi.", asociada a la cuenta contable ".$ls_cuentascg; 
				//////////////////////////////         SEGURIDAD               /////////////////////////////
				$this->int_spi->io_sql->commit();
				$this->is_msg_error="Registro guardado";
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
				////////////////////////////////         SEGURIDAD               //////////////////////////////

			 }
	
		}
		return $lb_valido;
	}

function uf_procesar_delete_cuenta($as_cuenta_spi,$as_dencuentaspi,$as_cuenta_scg,$lb_existe,
                                  $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								  $as_codestpro5,$as_estcla,$aa_security)
{
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$ls_condicion = " AND column_name ='spi_cuenta' AND table_name <>'spi_cuentas'";//Nombre del o los campos que deseamos buscar.
	$ls_mensaje   = "";                             //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	$lb_tiene     = $this->uf_check_relaciones($ls_codemp,$ls_condicion,'spi_cuentas_estructuras',$as_cuenta_spi,$ls_mensaje,
	                                           $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla);
	$lb_existe=$this->int_spi->uf_spi_select_cuenta($ls_codemp, $as_cuenta_spi, &$ls_status, &$ls_denominacion, $as_cuenta_scg );
	if($lb_existe)
	{
		if($lb_tiene==1)
		{	
			$this->msg->message("Existen movimientos asociados a la cuenta ".$as_cuenta_spi." cuya cuenta Contable es ".$as_cuenta_scg);
			return false;
		}
		else
		{			
				$ls_cuenta_cero = $this->int_spi->uf_spi_cuenta_sin_cero($as_cuenta_spi); 
				$li_total_rows = $this->int_spi->uf_spi_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero);									
				if($li_total_rows > 1)
				{	
				   $lb_valido=false;
				}
				else 
				{ 
					$lb_valido = $this->uf_spi_delete_cuenta_estructura($ls_codemp, $as_cuenta_spi,$as_codestpro1,$as_codestpro2,
					                                                    $as_codestpro3,$as_codestpro4,$as_codestpro5,
							                                            $as_estcla);   
					$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_cuenta_cero);
					$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel($ls_NextCuenta);
						do 
						{	
							if($this->int_spi->uf_spi_select_cuenta($ls_codemp,$ls_NextCuenta, &$ls_status, &$ls_denominacion, $as_cuenta_scg ))
							{  	
								 $ls_PadNextCuenta =$this->int_spi->uf_spi_padcuenta_plan($this->dat["formplan"] , $ls_NextCuenta);				
								 $this->int_scg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
																
									if($li_Nivel > 1)
									{	
										 $ls_cuenta_ref = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
									}
									else	
									{ 	
										 $ls_cuenta_ref = "             ";
									}
									$ls_cuenta_cero = $this->int_spi->uf_spi_cuenta_recortar_next($ls_NextCuenta);
									$li_total_rows2= $this->int_spi->uf_spi_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero);

									if($li_total_rows2 > 1)
									{	
										//$this->msg->message("Existen cuentas de nivel inferior ... no se puede eliminar.");				
									}
									else
									{	
										$lb_valido = $this->uf_spi_delete_cuenta_estructura($ls_codemp,$ls_NextCuenta,
										                                                    $as_codestpro1,$as_codestpro2,
																						    $as_codestpro3,$as_codestpro4,$as_codestpro5,
																						    $as_estcla);   
										
									}
										  
							} 
							if ($li_Nivel > 1)
							{ 
								$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
								$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel( $ls_NextCuenta );										
							}
							else
							{ 	
								$li_Nivel = 0 ;
							}
						}while( $li_Nivel >= 1);
						
				}
					if($lb_valido)
					{
					   $this->int_spi->io_sql->commit();
					   $this->is_msg_error="Registro Eliminado";
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_desc_event="Elimino la cuenta ".$as_cuenta_spi." asociada a la cuenta contable ".$as_cuenta_scg;
						$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
					}
					else
					{
					   $this->int_spi->io_sql->rollback();
					   $this->is_msg_error;
					   //$this->is_msg_error=$this->int_spg->is_msg_error;
					}
			}
	} 
	return $lb_valido;
}

function uf_load_plan_cuenta_ingreso($as_codemp)
{
  $ls_sql= "SELECT * FROM spi_cuentas WHERE codemp='".$as_codemp."'";
 
  $rs_unidad = $this->io_sql->select($ls_sql);
  if ($row=$this->io_sql->fetch_row($rs_unidad))
	 {
	   $lb_valido=true;
	 }
  else
	 {
	    $lb_valido=false;
	 }
  return $lb_valido;
}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_update_cuenta($as_spi_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_update_cuenta
		//		   Access: public 
		//       Argument: as_spi_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_sc_cuenta // Cuenta contable
		//	  Description: Este método actualiza una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ls_sql="UPDATE spi_cuentas_estructuras  ".
				"   SET spi_cuenta='".$as_spi_cuenta."',codestpro1='".$as_codestpro1."',codestpro2='".$as_codestpro2."', ".
				" codestpro3='".$as_codestpro3."',codestpro4='".$as_codestpro4."',codestpro5='".$as_codestpro5."',estcla='".$as_estcla."' ".
			 	" WHERE codemp='".$ls_codemp."'  ".
				"   AND spi_cuenta='".$as_spi_cuenta."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_update_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	} // end function 

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spi_insert_cuenta($as_spi_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_insert_cuenta
		//		   Access: public 
		//       Argument: as_spi_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_sc_cuenta // Cuenta contable
		//       		   as_status // Estatus de la Cuenta
		//       		   as_nivel // nivel de la Cuenta
		//       		   as_referencia // Cuenta de Referencia
		//	  Description: Este método inserta una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ls_sql=" INSERT INTO spi_cuentas_estructuras(codemp,spi_cuenta,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) 
		           values ('".$ls_codemp."','".$as_spi_cuenta."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."')"; 
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_spi_c_partingresos MÉTODO->uf_spi_insert_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
    } // end function uf_spi_insert_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_contable($as_cuentaspi,&$as_cuentascg)
	{
	  $data=$_SESSION["la_empresa"];
	  $ls_codemp=$data["codemp"];
	  $as_cuentaspi=trim($as_cuentaspi);
	  $ls_sql= "SELECT * FROM spi_cuentas WHERE codemp='".$ls_codemp."' and spi_cuenta='".$as_cuentaspi."'";

	  $rs_data=$this->io_sql->select($ls_sql);
	   if ($rs_data===false)
		 {
		   $lb_valido=false;
		   $this->is_msg_error="CLASE->sigesp_spi_c_partingresos; METODO->uf_buscar_contable; ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message);
		 } 
	  else
		 {
			while(!$rs_data->EOF)
			{
			 $as_cuentascg = $rs_data->fields["sc_cuenta"]; 
			 $rs_data->MoveNext();
			} 
			$lb_valido=false;
		 }
	  return $lb_valido;
	}
	
  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_select_cuenta($as_codemp,$as_spi_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_select_cuenta
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_spi_cuenta // Cuenta
		//	  Description: Verifica si existe o no la cuenta y retorna informacion de la cuenta
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql="SELECT *".
				  "  FROM spi_cuentas_estrucutras ".
		   		  " WHERE codemp='".$as_codemp."' ".
				  " AND trim(spi_cuenta)= '".rtrim($as_spi_cuenta)."'".
				  " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'".
				  " AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'AND estcla='".$as_estcla."'" ;
		
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_spi_c_partingresos MÉTODO->uf_spi_select_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	}	// end function uf_spi_select_cuenta

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_check_relaciones($as_codemp,$as_condicion,$as_tabla_maestro,$as_value,$as_mensaje,
                             $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							 $as_estcla)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
//	       Arguments: 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo.
//        $as_codemp:  Código de la Empresa.
//     $as_condicion:  Cadena sql que completará la búsqueda del campo.
//         $as_valor:  Valor de búsqueda en la data contenida en la(s) Tabla(s).
//       $as_mensaje:  Mensaje que será presentado al usuario una vez terminada la búsqueda.
//           Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si el campo posee relaciones asociadas a otras tablas para poder ser eliminado.
// Fecha de Creación:  09/11/2006       Fecha Última Actualización:10/11/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;
  $lb_tiene  = 0;
  $as_codestpro1 = str_pad($as_codestpro1,25,0,0);
  $as_codestpro2  = str_pad($as_codestpro2,25,0,0);
  $as_codestpro3  = str_pad($as_codestpro3,25,0,0);
  $as_codestpro4  = str_pad($as_codestpro4,25,0,0);
  $as_codestpro5  = str_pad($as_codestpro5,25,0,0); 
  $rs_data   = $this->uf_select_table_estructuras($as_condicion,$as_tabla_maestro,&$lb_valido);
  if ($lb_valido)
     {
        while ($row=$this->io_sql->fetch_row($rs_data))	 
	          {
				
				$rs_codemp = $this->uf_select_table_estructuras($as_condicion,$as_tabla_maestro,&$lb_valido);//Verificamos que la tabla posea el código de la empresa.
				 
				if ($row_codemp=$this->io_sql->fetch_row($rs_codemp))
				   {
				      $ls_table_name  = $row["table_name"]; 
					  $ls_column_name  = $row["column_name"]; 
					  if($ls_column_name=='codestpro5')
					  {
					    $ls_column_name='spi_cuenta';
					  }
				      $ls_sql   = "SELECT codemp FROM $ls_table_name WHERE codemp='".$as_codemp."' ".
									 " AND $ls_column_name ='".$as_value."'".
									 " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'".
				                     " AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'AND estcla='".$as_estcla."'";
						//print $ls_sql."<br>";
						 $rs_datos = $this->io_sql->select($ls_sql);
						 if ($rs_datos===false)
							{
							  $this->is_msg_error="ERROR en uf_check_relaciones()".$this->fun->uf_convertirmsg($this->io_sql->message);			
							} 
						 else
							{
							  if ($row_data=$this->io_sql->fetch_row($rs_datos))
								 { 
								   //$lb_tiene = true;
								    $lb_tiene = 1;
								   if (!empty($as_mensaje))
									  {
										$this->is_msg_error = $as_mensaje;
									  }
								   else
									  {
										$this->is_msg_error="El registro no puede ser eliminado, posee registros asociados a otras tablas !!!";  
									  }
								   $this->io_sql->free_result($rs_datos);
								   break;
								 }
								 else
								 { 
								    $lb_tiene = 0;
								 }
							}
					}
			  }
	 }
  return $lb_tiene;
}
//----------------------------------------------------------------------------------------------------------------------------

function uf_select_table_names($as_condicion,$as_tabla_maestro,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_table_names
//	          Access:  public
//	        Arguments 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo
//                     que viene proporcionado como parametro.
//     $as_condicion:  String que completa la sentencia sql, donde debe escribirse el campo de busqueda(Ejm: codemp='".$as_codemp."').
//        $lb_valido:  Variable booleana que devolverá si fueron encontradas o no Tablas con ese nombre de campo.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga encontrar el nombre de todas aquellas Tablas que posean el o los campos definidos
//                     por la variable $as_condicion dentro de su estructura, y luego ser vaciadas en un resulset, la función devuelve 
//                     $lb_valido=true si y solo si encuentra tablas con dicho(s) campo(s).
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  09/11/2006       Fecha Última Actualización:10/11/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;  
  switch ($this->ls_gestor)
  {
		case "MYSQLT":
			 $ls_sql = " SELECT DISTINCT TABLE_NAME AS table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE TABLE_SCHEMA='".$this->ls_database."' ".$as_condicion." AND TABLE_NAME<>'".$as_tabla_maestro."'";
			  break;
		case "POSTGRES":
			 $ls_sql = " SELECT DISTINCT table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE table_catalog='".$this->ls_database."' ".$as_condicion." AND table_name<>'".$as_tabla_maestro."'"; 
 			 break;
		case "INFORMIX":
		   $ls_sql= "SELECT systables.tabname AS table_name, syscolumns.colname AS column_name  FROM syscolumns, systables ".
					" WHERE syscolumns.tabid = systables.tabid ".
					" AND UPPER(systables.tabname)<>UPPER('".$as_tabla_maestro."') ".
					" ".$as_condicion." ";	
		break;
  }
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_msg->is_msg_error="ERROR en uf_select_table_names()".$this->fun->uf_convertirmsg($this->io_sql->message);	
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data); 
	   if ($li_numrows>0)
	      {
		    $lb_valido = true;
		  }
	 }
return $rs_data;
}

function uf_select_table_names2(&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_table_names2
//	          Access:  public
//	        Arguments 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo
//                     que viene proporcionado como parametro.
//     $as_condicion:  String que completa la sentencia sql, donde debe escribirse el campo de busqueda(Ejm: codemp='".$as_codemp."').
//        $lb_valido:  Variable booleana que devolverá si fueron encontradas o no Tablas con ese nombre de campo.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga encontrar el nombre de todas aquellas Tablas que posean el o los campos definidos
//                     por la variable $as_condicion dentro de su estructura, y luego ser vaciadas en un resulset, la función devuelve 
//                     $lb_valido=true si y solo si encuentra tablas con dicho(s) campo(s).
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  09/11/2006       Fecha Última Actualización:10/11/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false; 
  switch ($this->ls_gestor)
  {
		case "MYSQLT":
			 $ls_sql = " SELECT DISTINCT TABLE_NAME AS table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE TABLE_SCHEMA='".$this->ls_database."' AND column_name='spi_cuenta'";
			  break;
		case "POSTGRES":
			 $ls_sql = " SELECT DISTINCT table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE table_catalog='".$this->ls_database."' AND column_name='spi_cuenta'"; 
 			 break;
		case "INFORMIX":
		   $ls_sql= "SELECT systables.tabname AS table_name, syscolumns.colname AS column_name  FROM syscolumns, systables ".
					" WHERE syscolumns.tabid = systables.tabid ".
					" AND UPPER(systables.tabname)<>UPPER('".$as_tabla_maestro."') ".
					" ".$as_condicion." ";	
		break;
  }
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_msg->is_msg_error="ERROR en uf_select_table_names()".$this->fun->uf_convertirmsg($this->io_sql->message);	
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data); 
	   if ($li_numrows>0)
	      {
		    $lb_valido = true;
		  }
	 }
return $rs_data;
}

function uf_select_table_estructuras($as_condicion,$as_tabla_maestro,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_table_estrcuturas
//	          Access:  public
//	        Arguments 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo
//                     que viene proporcionado como parametro.
//     $as_condicion:  String que completa la sentencia sql, donde debe escribirse el campo de busqueda(Ejm: codemp='".$as_codemp."').
//        $lb_valido:  Variable booleana que devolverá si fueron encontradas o no Tablas con ese nombre de campo.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga encontrar todas aquellas Tablas que posean estructuras, la función devuelve 
//                     $lb_valido=true si y solo si encuentra tablas con dicho(s) campo(s).
//     Elaborado Por: 
// Fecha de Creación:  27/11/2008     Fecha Última Actualización:
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;  
  switch ($this->ls_gestor)
  {
		case "MYSQLT":
			 $ls_sql = " SELECT DISTINCT TABLE_NAME AS table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE TABLE_SCHEMA='".$this->ls_database."' ".$as_condicion." AND TABLE_NAME<>'".$as_tabla_maestro."'";
			  break;
		case "POSTGRES":
			 $ls_sql = " SELECT DISTINCT table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   " WHERE table_catalog='".$this->ls_database."' ".
				       " AND column_name='codestpro5'  ";
			 break;
		case "INFORMIX":
		   $ls_sql= "SELECT systables.tabname AS table_name, syscolumns.colname AS column_name  FROM syscolumns, systables ".
					" WHERE syscolumns.tabid = systables.tabid ".
					" AND UPPER(systables.tabname)<>UPPER('".$as_tabla_maestro."') ".
					" ".$as_condicion." ";	
		break;
  }
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_msg->is_msg_error="ERROR en uf_select_table_names()".$this->fun->uf_convertirmsg($this->io_sql->message);	
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data); 
	   if ($li_numrows>0)
	      {
		    $lb_valido = true;
		  }
	 }
return $rs_data;
}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_delete_cuenta_estructura($as_codemp, $as_spi_cuenta,
	                                         $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							                 $as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_delete_cuenta_estructura
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_spi_cuenta // Cuenta
		//	  Description: Borra de la tabla maestra la cuenta de ingreso asociada a una estructura en spi_cuentas_estructura
		//	      Returns: un boolean 
		//	   Creado Por: 
		// Modificado Por: 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_codestpro1 = str_pad($as_codestpro1,25,0,0);
        $as_codestpro2  = str_pad($as_codestpro2,25,0,0);
        $as_codestpro3  = str_pad($as_codestpro3,25,0,0);
        $as_codestpro4  = str_pad($as_codestpro4,25,0,0);
        $as_codestpro5  = str_pad($as_codestpro5,25,0,0); 
		$ls_sql="DELETE FROM spi_cuentas_estructuras ".
			 	" WHERE codemp='".$as_codemp."' ".
				"   AND spi_cuenta ='".$as_spi_cuenta."'".
		        " AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'".
			    " AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'AND estcla='".$as_estcla."'";
		$li_rows = $this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_delete_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	}	// end function uf_spi_delete_cuenta_estructura
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>