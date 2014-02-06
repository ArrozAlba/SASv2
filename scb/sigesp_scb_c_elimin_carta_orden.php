<?php 
class sigesp_scb_c_elimin_carta_orden
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
	function sigesp_scb_c_elimin_carta_orden($aa_security)
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
		$this->la_security  = $aa_security; 	
	}//Fin del constructor

	function uf_cargar_cartas_filtradas($ls_codemp,$ls_tipo,$ld_fechadesde,$ld_fechahasta,$ls_documento,$ls_numcarta)
	{		
		////////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_cargar_cartas_filtradas
		//  Access:		public
		//	Returns:	Boolean Retorna la data si proceso correctamente
		//	Description: Funcion que se encarga de obtener las carta orden emitidas por cancelaciones a 
		//				 proveedores o beneficiarios que esten en status de no contabilizada y los retorna en un array de objects
		//				 los cuales seran enviados a la clase grid_param para su muestra en pantalla. 
		//	Autor:       Ing. Laura Cabré
		//  Fecha:       18 de Ocubre del 2006
		////////////////////////////////////////////////////////////////////////////////	
		if ($ls_tipo=='P')
		   {
	  	     $ls_straux = " AND tipo_destino='P'";
		   }
		elseif($ls_tipo=='B')
		   {
		     $ls_straux = " AND tipo_destino='B'";
		   }     
			 
		$ls_sql = "SELECT numdoc, cod_pro, ced_bene, nomproben, fecmov, estmov, monto,
			              conmov , codban, ctaban, numcarord, estcon
			         FROM scb_movbco 
			        WHERE codemp='".$ls_codemp."' $ls_straux 
			 	      AND estmov='N' 
					  AND codope='ND' 
			 	      AND estbpd='T' 
					  AND numdoc like '%".$ls_documento."%' 
					  AND numcarord like '%".$ls_numcarta."%'";
		
		if(($ld_fechadesde!="") && ($ld_fechadesde!="01/01/1900"))
		{
			$ld_fechadesde=$this->fun->uf_convertirdatetobd($ld_fechadesde);
			$ls_sql = $ls_sql." AND fecmov>='$ld_fechadesde'";
		}
		
		if(($ld_fechahasta!="") && ($ld_fechahasta!="01/01/1900"))
		{
			$ld_fechahasta=$this->fun->uf_convertirdatetobd($ld_fechahasta);
			$ls_sql = $ls_sql." AND fecmov<='$ld_fechahasta'";
		}
		$ls_sql=$ls_sql." AND scb_movbco.ctaban IN (SELECT codintper ".
						"					 FROM sss_permisos_internos ".
						"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"				    UNION ".
						"				   SELECT codintper ".
						"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
						"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$ls_sql = $ls_sql." ORDER BY numdoc asc";	
		$rs_solicitudes	=$this->SQL->select($ls_sql);
		//print $ls_sql;
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
	
	function uf_procesar_eliminacion($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_provbene,$ls_tipo,$ls_estprosol,$ls_estprogpago,$ls_numcarta)
	{
		////////////////////////////////////////////////////////////////////////////////
		//
		//	Function:	uf_procesar_eliminacion
		// 					
		//  Access:		public
		//
		//	Returns:	Boolean Retorna si proceso correctamente
		//
		//	Description:	Funcion que se encarga de procesar la eliminacion de la Carta Orden
		//					actualizando el estatus de la programación(tabla: scb_prog_pago a 'P') y el de la solicitud (tabla:cxp_solicitudes a 'S'),
		//					luego elimino el detalle del pago en cxp_sol_banco donde se asocia el numero del cheque a las slicitudes de pago canceladas 
		//					o abonadas parcialmente, y por ultimo eliminando todos los registros generados durante el movimiento bancario.(SPG,SCG,etc)
		//               
		////////////////////////////////////////////////////////////////////////////////
		
		$li_total=0; $li_x=0;
		$ls_codemp=$this->dat["codemp"];
		$lb_valido=false;
		$ls_sql="SELECT * 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND estmov='N'";
		$rs_solbanco=$this->SQL->select($ls_sql);		
		if(($rs_solbanco===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error en select cxp_sol_banco, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_solbanco))
			{
				$lb_valido=true;
				$this->ds_solbanco->data=$this->SQL->obtener_datos($rs_solbanco);
				$this->SQL->free_result($rs_solbanco);
				$li_total=$this->ds_solbanco->getRowCount("numdoc");
				for($li_i=1;$li_i<=$li_total;$li_i++)			
				{
					$ls_numsol=$this->ds_solbanco->getValue("numsol",$li_i);
					$ls_sql="UPDATE scb_prog_pago SET estmov='".$ls_estprogpago."' WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
					$li_progpago=$this->SQL->execute($ls_sql);		
					if(($li_progpago===false))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;
					}
					$ls_sql="UPDATE cxp_solicitudes SET estprosol='".$ls_estprosol."' WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
					$li_progpago=$this->SQL->execute($ls_sql);		
					if(($li_progpago===false))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;
					}
					
				}
				if($li_total>0)
				{
					$this->ds_solbanco->resetds("numdoc");//Blanqueo la instancia del datastore,.
				}
				if($lb_valido)//eliminacion registro de solbanco
				{
					$ls_sql="DELETE FROM cxp_sol_banco WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='ND' AND numdoc='".$ls_numdoc."'";
					$li_delsolbanco=$this->SQL->execute($ls_sql);		
					if(($li_delsolbanco===false))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
							$lb_valido=true;
					}
				}
				
				if($lb_valido)//Eliminacion de lo detalles de gasto
				{
					$ls_sql="DELETE FROM scb_movbco_spg WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='ND' AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detgasto=$this->SQL->execute($ls_sql);		
					if(($li_detgasto==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;											
					}
				}//fin delete gasto
				
				if($lb_valido)//Eliminacion de lo detalles de contabilidad
				{
					$ls_sql="DELETE FROM scb_movbco_scg WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='ND' AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detcontable=$this->SQL->execute($ls_sql);		
					if(($li_detcontable==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;						
					}
				}//fin delete contable
				
				if($lb_valido)//Eliminacion de lo detalles de contabilidad
				{
					$ls_sql="DELETE FROM scb_movbco_spi WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='ND' AND numdoc='".$ls_numdoc."' AND estmov='N'";
					$li_detingreso=$this->SQL->execute($ls_sql);		
					if(($li_detingreso==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;						
					}
				}//fin delete ingreso
				if($lb_valido)//Eliminacion del detalle de movimiento de banco
				{
				
					$ls_sql="DELETE FROM scb_dt_movbco WHERE codemp='".$ls_codemp."' 
							AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
							AND codope='ND' AND numdoc='".$ls_numdoc."' 
							AND estmov='N' ";
					

					$li_detingreso=$this->SQL->execute($ls_sql);		
					if(($li_detingreso==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;
					}
				}//fin delete detalle del movimiento banco
				if($lb_valido)//Eliminacion del detalle de movimiento de banco
				{
				
					$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento WHERE codemp='".$ls_codemp."' 
							AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
							AND codope='ND' AND numdoc='".$ls_numdoc."' 
							AND estmov='N' ";
					

					$li_detingreso=$this->SQL->execute($ls_sql);		
					if(($li_detingreso==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;
					}
				}//fin delete detalle del movimiento banco
			
				if($lb_valido)//Eliminacion del movimiento de banco
				{
				
					$ls_sql="DELETE FROM scb_movbco WHERE codemp='".$ls_codemp."' 
							AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
							AND codope='ND' AND estbpd='T' AND numdoc='".$ls_numdoc."' 
							AND estmov='N' AND numcarord='$ls_numcarta'";
					

					$li_detingreso=$this->SQL->execute($ls_sql);		
					if(($li_detingreso==false)&&($this->SQL->message!=""))
					{
						$lb_valido=false;
						print $this->SQL->message;
					}
					else
					{
						$lb_valido=true;
					   ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion="Elimino la carta orden numero ".$ls_numcarta." con numero de documento ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
						////////////////////////////////////////////////////////////////////////////////////////////////////////////

					}
				}//fin delete movimiento banco
			}
		}
		return $lb_valido;
	}//Fin de uf_procesar_eliminacion	
	
}
?>