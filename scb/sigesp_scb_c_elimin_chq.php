<?php 
class sigesp_scb_c_elimin_chq
{
	var $SQL;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	var $ds_solbanco;
	var $la_security;
	function sigesp_scb_c_elimin_chq($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->ds_solbanco=new class_datastore();
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];
	
		$this->is_empresa   = $aa_security["empresa"];
		$this->is_sistema   = $aa_security["sistema"];
		$this->is_logusr    = $aa_security["logusr"];	
		$this->is_ventana   = $aa_security["ventanas"];
		$this->io_seguridad = new sigesp_c_seguridad();
	}//Fin del constructor

	function uf_cargar_cheques($ls_codemp,$ls_tipo)
	{		
		////////////////////////////////////////////////////////////////////////////////
		//
		//	Function:	uf_cargar_cheques
		// 					
		//  Access:		public
		//
		//	Returns:	Boolean Retorna si proceso correctamente
		//
		//	Description:	Funcion que se encarga de obtener los cheques emitidos por cancelaciones a 
		//					proveedores o beneficiarios que esten en status de no contabilizada y los retorna en un array de objects
		//					los cuales seran enviados a la clase grid_param para su muestra en pantalla.
		//               
		////////////////////////////////////////////////////////////////////////////////
	
	
		
		if($ls_tipo=='P')
		{
		$ls_sql="SELECT  a.numdoc as numdoc,a.cod_pro as cod_pro,a.ced_bene as ced_bene,a.nomproben as nomproben,a.fecmov as fecmov,a.conmov as conmov,a.estmov as estmov,a.monto as monto,a.conmov as conmov,a.codban as codban,a.ctaban as ctaban
				 FROM    scb_movbco a
				 WHERE   a.codemp='".$ls_codemp."'  AND a.estmov='N' AND a.codope='CH' AND ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) 
				 ORDER BY a.numdoc asc";
		}
		else
		{
		$ls_sql="SELECT  a.numdoc as numdoc,a.cod_pro as cod_pro,a.ced_bene as ced_bene,a.nomproben as nomproben,a.fecmov as fecmov,a.conmov as conmov,a.estmov as estmov,a.monto as monto,a.conmov as conmov,a.codban as codban,a.ctaban as ctaban
				 FROM    scb_movbco a
				 WHERE   a.codemp='".$ls_codemp."'  AND a.estmov='N' AND a.codope='CH' AND ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
				 ORDER BY a.numdoc asc";
		}
		
		$rs_solicitudes	=$this->SQL->select($ls_sql);
	
		if($rs_solicitudes===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_cargar_solicitudes".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
			$data="";
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_solicitudes))
			{
				$data=$this->SQL->obtener_datos($rs_solicitudes);		
			}
			else
			{
				$data="";
				$this->is_msg_error="No encontro registros";
			}
			$this->SQL->free_result($rs_solicitudes);
		}
		return $data;
	}//Fin uf_cargar_solicitudes
	
	function uf_cargar_cheques_filtrados($ls_codemp,$ls_tipo,$ld_fechadesde,$ld_fechahasta,$ls_documento,&$lb_valido)
	{		
		////////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_cargar_cheques_filtrados
		//  Access:		public
		//	Returns:	Boolean Retorna la data si proceso correctamente
		//	Description:	Funcion que se encarga de obtener los cheques emitidos por cancelaciones a 
		//					proveedores o beneficiarios que esten en status de no contabilizada y los retorna en un array de objects
		//					los cuales seran enviados a la clase grid_param para su muestra en pantalla. Esta es una versión del metodo
		//					anterior pero con algunos filtros de búsqueda.
		//	         Autor: Ing. Laura Cabré
		//           Fecha: 17 de Ocubre del 2006
		//  Modificado Por: Ing. Néstor Falcón.     Fecha Última Modificación: 19/07/2007.
		////////////////////////////////////////////////////////////////////////////////
	
	    $lb_valido = true;
		$ls_straux = "";
        if (!empty($ls_documento))
		   {
		     $ls_straux = " AND numdoc = '".$ls_documento."'";
		   }
		switch ($ls_tipo){
		  case 'P':
		    $ls_straux = $ls_straux." AND tipo_destino='P'";
		  break;
		  case 'B':
		    $ls_straux = $ls_straux." AND tipo_destino='B'";
		  break;
		}	

		if (($ld_fechadesde!="") && ($ld_fechadesde!="01/01/1900"))
		   {
			 $ld_fechadesde=$this->fun->uf_convertirdatetobd($ld_fechadesde);
			 $ls_straux = $ls_straux." AND fecmov>='$ld_fechadesde'";
		   }
		
		if (($ld_fechahasta!="") && ($ld_fechahasta!="01/01/1900"))
		   {
			 $ld_fechahasta=$this->fun->uf_convertirdatetobd($ld_fechahasta);
			 $ls_straux = $ls_straux." AND fecmov<='$ld_fechahasta'";
	 	   }
		$ls_sql = "SELECT numdoc, cod_pro, ced_bene, nomproben, fecmov, conmov, estmov, monto, codban, ctaban, estcon
				     FROM scb_movbco 
				    WHERE codemp='".$ls_codemp."' 
					  AND (estmov='N' OR estmov='L') 
					  AND codope='CH' $ls_straux AND ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
					ORDER BY numdoc ASC";
		$rs_data = $this->SQL->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $this->is_msg_error="CLASS->sigesp_scb_c_elim_chq.php;Método->uf_cargar_cheques_filtrados".$this->fun->uf_convertirmsg($this->SQL->message);
		   }
		return $rs_data;
	}//Fin uf_cargar_solicitudes
	
	
	function uf_procesar_eliminacion($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_provbene,$ls_tipo,$ls_estprosol,$ls_estprogpago)
	{
		////////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_procesar_eliminacion
		//  Access:		public
		//	Returns:	Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de procesar la eliminacion del cheque 
		//					actualizando el estatus de la programación(tabla: scb_prog_pago a 'P') y el de la solicitud (tabla:cxp_solicitudes a 'S'),
		//					luego elimino el detalle del pago en cxp_sol_banco donde se asocia el numero del cheque a las solicitudes de pago canceladas 
		//					o abonadas parcialmente, y por ultimo eliminando todos los registros generados durante el movimiento bancario.(SPG,SCG,etc)
		////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$li_total=0; $li_x=0;
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT numsol 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND estmov='N'";
				 
		$rs_solbanco=$this->SQL->select($ls_sql);
		if($rs_solbanco===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en select cxp_sol_banco, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message."<br>";
		}
		else
		{
			$li_numrows = $this->SQL->num_rows($rs_solbanco);
			if ($li_numrows>0)
			   {
			     while($row=$this->SQL->fetch_row($rs_solbanco))
				      {
					    $ls_numsol = $row["numsol"];
					  	$ls_sql    = "UPDATE scb_prog_pago SET estmov='P' 
						               WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
					    $li_progpago = $this->SQL->execute($ls_sql);
					    if ($li_progpago===false)
					       {
						     $lb_valido=false;
						     print $this->SQL->message."<br>";
					       }
					    if ($lb_valido)
						   {
						     $ls_sql = "UPDATE cxp_solicitudes SET estprosol='S' WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
					         $li_progpago=$this->SQL->execute($ls_sql);
				 	         if ($li_progpago===false)
								{
								  $lb_valido=false;
								  print $this->SQL->message."<br>";
								}
						   }
					    if ($lb_valido)
						   {
						     $ls_sql = "DELETE FROM cxp_sol_banco WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='CH' AND numdoc='".$ls_numdoc."'";
					         $li_delsolbanco=$this->SQL->execute($ls_sql);
					         if ($li_delsolbanco===false)
								{
									$lb_valido=false;
									print $this->SQL->message."<br>";
								}
						   }
					  }
			   }
			
			 if ($lb_valido)//Eliminacion de lo detalles de gasto
				{
					$ls_sql="DELETE FROM scb_movbco_spg WHERE codemp='".$ls_codemp."' 
					AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='CH' AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detgasto=$this->SQL->execute($ls_sql);
					if($li_detgasto===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
				}//fin delete gasto
				
				if($lb_valido)//Eliminacion de lo detalles de contabilidad
				{
					$ls_sql="DELETE FROM scb_movbco_scg WHERE codemp='".$ls_codemp."' 
					AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='CH' AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detcontable=$this->SQL->execute($ls_sql);
					if($li_detcontable===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
				}//fin delete contable
				
				if($lb_valido)//Eliminacion de lo detalles de contabilidad
				{
					$ls_sql="DELETE FROM scb_movbco_spi WHERE codemp='".$ls_codemp."' 
					AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='CH' 
					AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detingreso=$this->SQL->execute($ls_sql);
					if($li_detingreso===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
				}//fin delete ingreso
				
				if($lb_valido)//Eliminacion de lo detalles de fuente de financiamiento
				{
					$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento WHERE codemp='".$ls_codemp."' 
					AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='CH' 
					AND numdoc='".$ls_numdoc."' AND (estmov='N' OR estmov='L')";
					$li_detingreso=$this->SQL->execute($ls_sql);
					if($li_detingreso===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
				}//fin delete ingreso
				//---------ELIMINACION DE ANTICIPOS------------------------------------------------
				if($lb_valido)//Eliminacion del movimiento de banco
				{
					$ls_sql=" DELETE FROM scb_movbco_anticipo ".
					        "  WHERE codemp='".$ls_codemp."' ". 
					        "   AND codban='".$ls_codban."' ".
							"   AND ctaban='".$ls_ctaban."' ". 
					        "   AND codope='CH' ".
							"   AND numdoc='".$ls_numdoc."' ".
							"   AND (estmov='N')";
					$li_detingreso=$this->SQL->execute($ls_sql);
					if($li_detingreso===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
					else
					{
						///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion="Elimino el movimiento bancario de anticipos de operacion CH numero "
						                .$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
						$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,
						                                                                $this->is_sistema,
																						$ls_evento,
																						$this->is_logusr,
																						$this->is_ventana,
																						$ls_descripcion);
						////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}
				}//fin delete movimiento banco de anticipos
				//-------------------------SI EL CHEQUE ES DE AMORTIZACIÓN---------------------------------------
				if($lb_valido)
				{
					$ls_sql=" SELECT docant, monamo  FROM scb_movbco ".
							"  WHERE codemp='".$ls_codemp."'". 
							"    AND codban='".$ls_codban."' ".
							"    AND ctaban='".$ls_ctaban."' ". 
							"    AND codope='CH' ".
							"    AND numdoc='".$ls_numdoc."' ".
							"    AND (estmov='N')";
					$rs_data=$this->SQL->select($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$this->is_msg_error="Error en select scb_movbco ".$this->fun->uf_convertirmsg($this->SQL->message);
						print $this->SQL->message."<br>";
					}
					else
					{
					    $li_filas=0;
						$li_filas = $this->SQL->num_rows($rs_data);
						$row=$this->SQL->fetch_row($rs_data);
						for ($i=1;$i<=$li_filas;$i++)
						{
							$ls_docant = $row["docant"];
							$ls_monamo = $row["monamo"];
							$ls_sql=" UPDATE scb_movbco_anticipo".
									"    SET monamo=monamo-".$ls_monamo.",".
									"        monsal=monsal+".$ls_monamo.
									"  WHERE numdoc='".$ls_docant."'";
							$rs_update=$this->SQL->execute($ls_sql);
							if($rs_update===false)
							{
								$lb_valido=false;
								print $this->SQL->message."<br>";
							}
							else
							{
								////////////////////Parametros de seguridad/////////////////////////////////////////////////
								$ls_evento="UPDATE";
								$ls_descripcion="Se Actualizo la amortizacion ".$ls_docant;
								$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,
																								$this->is_sistema,
																								$ls_evento,
																								$this->is_logusr,
																								$this->is_ventana,
																								$ls_descripcion);
								/////////////////////////////////////////////////////////////////////////////////////////////
							}
						}//fin del for
					}// fin del else
				}//fin del if
				//-----------------------------------------------------------------------------------------------
				if($lb_valido)//Eliminacion del movimiento de banco
				{
					$ls_sql="DELETE FROM scb_movbco WHERE codemp='".$ls_codemp."' 
					AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					AND codope='CH' AND numdoc='".$ls_numdoc."' AND (estmov='N' OR estmov='L')";
					$li_detingreso=$this->SQL->execute($ls_sql);
					if($li_detingreso===false)
					{
						$lb_valido=false;
						print $this->SQL->message."<br>";
					}
					else
					{
						///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion="Elimino el movimiento bancario de operacion CH numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
						$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
						////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}
					if ($lb_valido)
					{
					  $lb_valido = $this->uf_liberar_cheque($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc);
					}
				}//fin delete movimiento banco
		}
		return $lb_valido;
	}//Fin de uf_procesar_eliminacion	
    
	function uf_liberar_cheque($as_codemp,$as_codban,$as_ctaban,$as_numche)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_liberar_cheque
		//       Access: public
		//	    Returns: Boolean Retorna si procesó correctamente.
		//	Description: Funcion que se encarga de actualizar el estatus Emitido (1) a Disponible ->(0), 
		//               al cheque perteneciente a esa chequera del banco y la cuenta que vienen por parametro.	
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido = true;
		$ls_sql = "UPDATE scb_cheques 
					  SET estche=0 
					WHERE codemp='".$as_codemp."'
					  AND codban='".$as_codban."'
					  AND ctaban='".$as_ctaban."'
					  AND numche='".$as_numche."'";
		$rs_data = $this->SQL->execute($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $this->is_msg_error="Error: CLASS->sigesp_scb_c_elimin_chq.php; Método->uf_liberar_cheque();, ".$this->fun->uf_convertirmsg($this->SQL->message);
		   }
		return $lb_valido;
	}//function uf_liberar_cheque.

}
?>