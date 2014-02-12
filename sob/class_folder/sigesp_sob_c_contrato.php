<?Php
class sigesp_sob_c_contrato
{
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $io_msg;
 var $io_funnum;
 var $io_connect;

function sigesp_sob_c_contrato()
{

	require_once("../shared/class_folder/sigesp_include.php");
	$io_siginc=new sigesp_include();
	$this->io_connect=$io_siginc->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$this->io_sql=new class_sql($this->io_connect);	
	require_once("../shared/class_folder/class_funciones.php");
	$this->io_function=new class_funciones();
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];
	require_once ("sigesp_sob_c_funciones_sob.php");
	$this->io_funnum= new sigesp_sob_c_funciones_sob(); 
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funsob=   new sigesp_sob_c_funciones_sob();	
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	$this->io_intspg=new class_sigesp_int_spg();		
}
//*******************************************************************************************************//
//										Funciones para llenar los combos                                 //
//*******************************************************************************************************//
	function uf_llenarcombo_tipocontrato(&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_tipocontrato	 //
	 //	Access:  public
	 //	Returns: arreglo con los tipos de contrato
	 //	Description: Funcion que permite llenar el combo de tipos de contrato, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 20/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT codtco,nomtco,destco
				 FROM sob_tipocontrato
				 ORDER BY codtco ASC";		
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_llenarcombo_tipocontrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_llenarcombo_unidadtiempo(&$aa_datos)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_unidadtiempo
	 //	Access:  public
	 //	Returns: arreglo con unidades de tiempo
	 //	Description: Funcion que permite llenar el combo de unidades de tiempo, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 20/03/2006
     // Autor: Ing. Laura Cabré
	 ////////////////////////////////
	 //////////////////////////////////////////////
	
		$lb_valido=false;
		$ls_sql=" SELECT u.coduni,u.nomuni 
				  FROM sob_unidad u,sob_tipounidad t
				  WHERE t.tipper='1' AND t.codtun=u.codtun
				  ORDER BY u.coduni ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_llenarcombo_unidadtiempo".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			  	$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_datos=$this->io_sql->obtener_datos($rs_data);				
			}else
			{
				$aa_datos="";
			}			
		}			 				
			
		return $lb_valido;
	}
	
	function uf_llenarcombo_prefijos(&$aa_datos)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_prefijos
	 //	Access:  public
	 //	Returns: arreglo con prefijos de codigo de contrato
	 //	Description: Funcion que permite llenar el combo deprefijos, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 27/05/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////// //////////////////////////////////////////////
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT DISTINCT precon 
				FROM sob_contrato 
				WHERE codemp='".$ls_codemp."' AND precon <>''";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_llenarcombo_prefijos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			  	$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_datos=$this->io_sql->obtener_datos($rs_data);				
			}else
			{
				$aa_datos="";
			}			
		}			 				
			
		return $lb_valido;
	}
	
	
	
//*******************************************************************************************************//
//							Funciones relacionadas con las Asignaciones                                  //
//*******************************************************************************************************//

function uf_select_asignacion ($as_codasi,&$aa_asignacion)
{
 	//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_asignacion
	 //	Access:  public
	 //	Returns: arreglo con datos de la asignacion
	 //	Description: Funcion que permite obtener todos los datos asociados a una 
	 //				  asignacion determinada
	 // Fecha: 22/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=" SELECT a.puncueasi,a.fecasi,p.nompro as contasi,a.monparasi,a.basimpasi,a.montotasi,o.codobr,o.desobr,e.desest,m.denmun,pa.denpar,co.nomcom as nomcom,o.dirobr
				  FROM sob_asignacion a, sob_obra o, rpc_proveedor p, sigesp_estados e, sigesp_municipio m, sigesp_parroquia pa,sigesp_comunidad co
				  WHERE a.codemp='".$ls_codemp."'AND a.codasi='".$as_codasi."' AND a.cod_pro=p.cod_pro AND a.codemp=p.codemp AND a.codobr=o.codobr
				  AND a.codemp=o.codemp AND o.codpai=co.codpai AND o.codest=co.codest AND o.codmun=co.codmun AND o.codpar=co.codpar AND o.codcom=co.codcom
				  AND o.codpai=pa.codpai AND o.codest=pa.codest AND o.codmun=pa.codmun AND o.codpar=pa.codpar AND o.codpai=m.codpai AND o.codest=m.codest AND o.codmun=m.codmun AND o.codpai=e.codpai AND o.codest=e.codest";
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_asignacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_asignacion=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
}	

function uf_select_cargoasignacion ($as_codasi,&$aa_cargos)
{
 	//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_cargoasignacion
	 //	Access:  public
	 //	Returns: arreglo con datos de los cargos de una asignacion
	 //	Description: Funcion que permite obtener todos los cargos asociados a una
	 //				  asignacion determinada
	 // Fecha: 24/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=" SELECT c.dencar as dencar,c.formula as formula 
				  FROM sob_cargoasignacion ca, sigesp_cargos c
				  WHERE ca.codemp='0001' AND c.codemp='0001' AND codasi='".$as_codasi."' and ca.codcar=c.codcar";
	//	print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_cargoasignacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_cargos=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
}	

//*******************************************************************************************************//
//							Funciones relacionadas con los Contratos                                     //
//*******************************************************************************************************//

function uf_select_contrato ($as_codcontrato,&$aa_data)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_contrato
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de verificar si existe o no el contrato
		//  Fecha:          22/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT c.*,a.codobr,tc.nomtco,p.nompro as nompro 
				FROM sob_contrato c, sob_tipocontrato tc,rpc_proveedor p,sob_asignacion a 
				WHERE c.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND codcon='".$as_codcontrato."' AND c.codtco=tc.codtco AND c.codasi=a.codasi AND a.cod_pro=p.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				$lb_valido=true;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
				$lb_valido=0;
			}
		}
		return $lb_valido;
	}	
	
	function uf_select_montocontrato ($as_codcontrato,&$ad_monto)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_montocontrato
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar el monto de un contrato
		//  Fecha:          13/06/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT c.monto
				FROM sob_contrato c
				WHERE c.codemp='".$ls_codemp."'AND codcon='".$as_codcontrato."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				$lb_valido=true;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ad_monto=$aa_data["monto"][1];
			}
			else
			{
				$ad_monto=0;
				$lb_valido=0;
			}
		}
		return $lb_valido;
	}	
		
	function uf_guardar_contrato(&$as_codcon ,$as_codasi,$ad_monto,$af_feccon, $af_fecinicon,$ad_placon, $as_placonuni,$ad_mulcon,$ad_tiemulcon,$as_mulconuni,
								 $as_lapgarcon,$as_lapgaruni,$as_codtco ,$ad_monmaxcon,$ad_pormaxcon,$as_estcon,$as_obscon,$ad_fecfincon,$ls_precon,$aa_seguridad)
	{ 
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_contrato
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar el contrato.
		//  Fecha:          22/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		//require_once("../shared/class_folder/class_funciones_db.php");
	    //$io_funcdb=new class_funciones_db($this->io_connect);
		$ls_codemp=$this->la_empresa["codemp"];
		//$ls_idcodcon=$io_funcdb->uf_generar_codigo(true,$ls_codemp,"sob_contrato","id_codcon");
		$lb_valido=false;		
		$ad_monto=$this->io_funnum->uf_convertir_cadenanumero($ad_monto);
		$ad_mulcon=$this->io_funnum->uf_convertir_cadenanumero($ad_mulcon);
		$ad_monmaxcon=$this->io_funnum->uf_convertir_cadenanumero($ad_monmaxcon);
		$ad_pormaxcon=$this->io_funnum->uf_convertir_cadenanumero($ad_pormaxcon);
		$ad_placon=$this->io_funnum->uf_convertir_cadenanumero($ad_placon);
		if ($ad_tiemulcon=="")
			$ad_tiemulcon=0;
		if($as_lapgarcon=="")
			$as_lapgarcon=0;
			$_SESSION["fechacomprobante"]=$af_feccon;	
		$this->io_keygen->uf_verificar_numero_generado("SOB","sob_contrato","codcon","SOBCON",12,"","","",&$as_codcon);
		$ls_sql="INSERT INTO sob_contrato (codemp, codcon ,codasi,monto, feccon,fecinicon, placon,placonuni, mulcon, tiemulcon, mulreuni,lapgarcon,".
				"                          lapgaruni,  codtco,monmaxcon, pormaxcon,  obscon,  porejefiscon, porejefincon, monejefincon,estcon,fecfincon,precon,estapr)
		         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codasi."','".$ad_monto."','".$af_feccon."','".$af_fecinicon."','".$ad_placon."','".$as_placonuni."',".
				 "       '".$ad_mulcon."','".$ad_tiemulcon."','".$as_mulconuni."','".$as_lapgarcon."','".$as_lapgaruni."','".$as_codtco."','".$ad_monmaxcon."',".
				 "       '".$ad_pormaxcon."','".$as_obscon."',0,0,0,1,'".$ad_fecfincon."','".$ls_precon."',0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_guardar_contrato(&$as_codcon ,$as_codasi,$ad_monto,$af_feccon, $af_fecinicon,$ad_placon, $as_placonuni,$ad_mulcon,$ad_tiemulcon,
													  $as_mulconuni,$as_lapgarcon,$as_lapgaruni,$as_codtco ,$ad_monmaxcon,$ad_pormaxcon,$as_estcon,$as_obscon,
													  $ad_fecfincon,$ls_precon,$aa_seguridad);
			}
			else
			{
				print "Error en metodo uf_guardar_contrato".$this->io_sql->message;//$this->io_function->uf_convertirmsg($this->io_sql->message);
			}
		}
		else
		{
				$this->uf_validar_cuentas($as_codasi);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Contrato ".$as_codcon.", correspondiente a la Asignacion ".$as_codasi.", asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;			
		}
		unset($_SESSION["fechacomprobante"]);		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_codasi)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5,estcla, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sob_cuentasasignacion.codemp ".
				"			AND spg_cuentas.codestpro1 = sob_cuentasasignacion.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sob_cuentasasignacion.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sob_cuentasasignacion.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sob_cuentasasignacion.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sob_cuentasasignacion.codestpro5 ".
				"           AND spg_cuentas.estcla=sob_cuentasasignacion.estcla".
				"			AND spg_cuentas.spg_cuenta = sob_cuentasasignacion.spg_cuenta) AS existe ".		
				"  FROM sob_cuentasasignacion  ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codasi='".$as_codasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$lb_existe=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_existe))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_monto=$row["monto"];
				$li_existe=$row["existe"];
				$estprog[0]=$row["codestpro1"];
				$estprog[1]=$row["codestpro2"];
				$estprog[2]=$row["codestpro3"];
				$estprog[3]=$row["codestpro4"];
				$estprog[4]=$row["codestpro5"];
				$estprog[5]=$row["estcla"];
				$lb_valido=$this->io_intspg->uf_spg_saldo_select($ls_codemp, $estprog, $ls_spg_cuenta, &$ls_status, &$adec_asignado, 
				                                           &$adec_aumento,&$adec_disminucion,&$adec_precomprometido,
													   	   &$adec_comprometido,&$adec_causado,&$adec_pagado);
				$li_disponibilidad=($adec_asignado-($adec_comprometido+$adec_precomprometido)+$adec_aumento-$adec_disminucion);
			 	if($li_existe>0)
				{
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						$this->io_msg->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
					}
				}
				else
				{
					$lb_existe = false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
				}
			$this->io_sql->free_result($rs_data);	
			}
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_update_contrato($as_codcon ,$as_codasi,$ad_monto,$af_feccon, $af_fecinicon,$ad_placon, $as_placonuni,$ad_mulcon,$ad_tiemulcon,$as_mulconuni,
								$as_lapgarcon,$as_lapgaruni,$as_codtco ,$ad_monmaxcon,$ad_pormaxcon,$as_estcon,$as_obscon,$ad_fecfincon,$ls_precon,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_contrato
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar el contrato. La funcion no incluye
		//                  la verificacion del estado del contrato, aspecto que debe ser revisado
		//					antes de realizar cualquier actualización.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funnum->uf_convertir_cadenanumero($ad_monto);
		$ad_mulcon=$this->io_funnum->uf_convertir_cadenanumero($ad_mulcon);
		$ad_monmaxcon=$this->io_funnum->uf_convertir_cadenanumero($ad_monmaxcon);
		$ad_pormaxcon=$this->io_funnum->uf_convertir_cadenanumero($ad_pormaxcon);		
		$ad_placon=$this->io_funnum->uf_convertir_cadenanumero($ad_placon);
		if ($ad_tiemulcon=="")
			$ad_tiemulcon=0;
		if($as_lapgarcon=="")
			$as_lapgarcon=0;			
		$ls_sql="UPDATE sob_contrato 
					 SET codasi='".$as_codasi."',monto='".$ad_monto."',
					 feccon='".$af_feccon."',fecinicon='".$af_fecinicon."',placon='".$ad_placon."',
					 placonuni='".$as_placonuni."',mulcon='".$ad_mulcon."',tiemulcon='".$ad_tiemulcon."',mulreuni='".$as_mulconuni."',
					 lapgarcon='".$as_lapgarcon."',lapgaruni='".$as_lapgaruni."',codtco='".$as_codtco."',
					 monmaxcon='".$ad_monmaxcon."',pormaxcon='".$ad_pormaxcon."',obscon='".$as_obscon."',estcon='6',fecfincon='".$ad_fecfincon."',precon='".$ls_precon."'
					 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_contrato->".$this->io_sql->message;
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Contrato ".$as_codcon.", correspondiente a la Asignacion ".$as_codasi.", asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	function uf_delete_contrato ($as_codcon,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:       uf_delete_contrato
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar la cabecera de un contrato. La funcion no incluye
		//                  la verificacion del estado del contrato, aspecto que debe ser revisado
		//					antes de realizar cualquier eliminación.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_contrato
					WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo uf_delete_contrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////$this->io_sql->commit();
		}
		return $lb_valido;		
	}
	
	function uf_select_estadoactual ($as_codcon,&$aa_data)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_estadoactual
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el estado actual del contrato
		//	Description:	Funcion que se encarga de devolver el estadode un
		//                  contrato y retorna el arreglo con las caracteristicas pertinentes
		//  Fecha:          28/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porejefiscon,porejefincon,monejefincon,estcon 
				FROM sob_contrato
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select estadoactual".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}	
	
	
	function uf_update_fechasreales($as_codcon ,$as_fecha,$ad_tipofecha,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_fechasreales
		//  Access:			public
		//  Arguments:		as_codcon:codigo del contrato,ad_fecha: fecha con la cual se hara la actualizacion,
		//					as_tipofecha: campo a actualizar("inicio" o "finalizacion")
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las fechas reales de ejecucion del
		//					contrato, segun las actas creadas para el mismo
		//  Fecha:          22/06/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		if($ad_tipofecha=="inicio")
		{
			$ls_sql="UPDATE sob_contrato 
					SET fecinireacon='$ad_fecha'
					WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";		
		}
		elseif($ad_tipofecha=="finalizacion")
		{
			$ls_sql="UPDATE sob_contrato 
				SET fecfinreacon='$ad_fecha'
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";	
		}
		else
		{print "campo tipofecha no valido";}
	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_contrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la fecha de $ad_tipofecha del Contrato ".$as_codcon." a $as_fecha, asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;
		}		
		return $lb_valido;
	}	
	
	
//*******************************************************************************************************//
//							Funciones relacionadas con las Retenciones                                   //
//*******************************************************************************************************//
	
	function uf_select_retenciones ($as_codcon,&$aa_data,&$ai_rows)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_retenciones
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el arreglo de retenciones si existen registros en bd
		//	Description:	Funcion que se encarga de verificar si existen registros para un
		//                  contrato y retorna el arreglo con los mismos
		//  Fecha:          25/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT rc.codcon, rc.codded,d.dended as dended,d.sc_cuenta as cuenta, d.monded as deducible, rc.codemp
				FROM sob_retencioncontrato rc, sigesp_deducciones d
				WHERE rc.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND rc.codded=d.codded AND rc.codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_retencion ($as_codcon,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_retencion
		//  Access:			public
		//	Returns:		Boolean,arreglo. Retorna true si la retencion buscada existe en bd
		//	Description:	Funcion que se encarga de verificar si existe una retencion específica asociada a una
		//                  obra 
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  codret
				 FROM sob_retencioncontrato
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select_retencion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;
	}
	
	function uf_guardar_retenciones($as_codcon ,$as_codded,$aa_seguridad)             
    { 
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_retenciones
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar una retencion.
		//  Fecha:          23/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_retencioncontrato ( codemp, codcon,codded )
		         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codded."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la Retención ".$as_codded.",del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	function uf_update_retenciones($as_codcon,$aa_retencionesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_retenciones
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las retenciones del 
		//					contrato que han sido modificadas.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$this->uf_select_retenciones ($as_codcon,$la_retencionesviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) &&  ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_retenciones($as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) &&  ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_retenciones($as_codcon,$la_retencionesviejas["codded"][$li_j],$aa_seguridad);
			}
		}			
		return $lb_valido;
	}	
	
	function uf_delete_retenciones($as_codcon,$as_codret,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:       uf_delete_retenciones
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar una retencion asociada a un contrato.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_retencioncontrato
					WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codded='".$as_codret."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_retencion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el detalle de la Retención ".$as_codret.", del Contrato ".$as_codcon." asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;	
	}
	
	
//*******************************************************************************************************//
//							Funciones relacionadas con las Condiciones                                   //
//*******************************************************************************************************//
	
	function uf_guardar_condiciones($as_codcon ,$as_codcondicion,$af_fecha,$ad_monto,$ad_porcentaje,$aa_seguridad)             
    { 
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_condiciones
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar una condicion de pago del contrato.
		//  Fecha:          23/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funnum->uf_convertir_cadenanumero($ad_monto);
		$ad_porcentaje=$this->io_funnum->uf_convertir_cadenanumero($ad_porcentaje);
		$ls_sql="";
		$ls_sql="INSERT INTO sob_condicionpagocontrato (codemp,codcon,codconpag,fecconpag,monto,porconpag)
		         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codcondicion."','".$af_fecha."','".$ad_monto."','".$ad_porcentaje."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_condiciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la condicion de pago ".$as_codcondicion." de monto ".$ad_monto.", para el Contrato ".$as_codcon." asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	function uf_select_condiciones ($as_codcon,&$aa_data,&$ai_rows)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_condiciones
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el arreglo de condiciones si existen registros en bd
		//	Description:	Funcion que se encarga de verificar si existen registros para un
		//                  contrato y retorna el arreglo con los mismos
		//  Fecha:          25/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT cp.codcon,cp.codconpag,cp.fecconpag,cp.monto,cp.porconpag, cp.codemp
				FROM sob_condicionpagocontrato cp,sob_contrato c
				WHERE c.codemp='".$ls_codemp."' AND cp.codemp='".$ls_codemp."' AND c.codcon=cp.codcon AND c.codcon='".$as_codcon."'
				ORDER BY cp.codconpag ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select condiciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_condicion ($as_codcon,$as_codconpag)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_condicion
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true si la condicion de pago buscada existe en bd
		//	Description:	Funcion que se encarga de verificar si existe una condicion de pago asociada a un contrato
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  codconpag
				 FROM sob_condicionpagocontrato
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codconpag='".$as_codconpag."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select_condicion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;
	}
	
	function uf_update_condiciones($as_codcon,$aa_condicionesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_condiciones
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las condiciones de pago del 
		//					contrato que han sido modificadas.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$this->uf_select_condiciones ($as_codcon,$la_condicionesviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_condicionesviejas["codemp"][$li_j] == $ls_codemp) && (trim($la_condicionesviejas["codcon"][$li_j]) == trim($as_codcon)) &&  (trim($la_condicionesviejas["codconpag"][$li_j]) == trim($aa_condicionesnuevas["codconpag"][$li_i])) )
				{
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_condiciones($as_codcon,$aa_condicionesnuevas["codconpag"][$li_i],$aa_condicionesnuevas["fecconpag"][$li_i],$aa_condicionesnuevas["monconpag"][$li_i],$aa_condicionesnuevas["porconpag"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_condicionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_condicionesviejas["codcon"][$li_j] == $as_codcon) &&  ($la_condicionesviejas["codconpag"][$li_j] == $aa_condicionesnuevas["codconpag"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_condiciones($as_codcon,$la_condicionesviejas["codconpag"][$li_j],$aa_seguridad);
			}
		}			
		return $lb_valido;
	}	
	
	function uf_delete_condiciones($as_codcon,$as_codconpag,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:       uf_delete_condiciones
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar una condicion de pago asociada a un contrato.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_condicionpagocontrato
					WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codconpag='".$as_codconpag."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_condicionpago".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el detalle de la Condicion de pago ".$as_codconpag.", del Contrato ".$as_codcon." asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;	
	
	}
	

//*******************************************************************************************************//
//							Funciones relacionadas con las Garantias                                     //
//*******************************************************************************************************//
	
	function uf_guardar_garantias($as_codcon ,$as_codgar,$as_desgar,$aa_seguridad)             
    { 
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_garantias
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar una garantia de pago del contrato.
		//  Fecha:          23/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_garantiacontrato ( codemp, codcon,codgar,desgar )
		         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codgar."','".$as_desgar."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_garantias".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la Garantia ".$as_codgar.": ".$as_desgar.", del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	function uf_select_garantias ($as_codcon,&$aa_data,&$ai_rows)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_garantias
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el arreglo de garantias si existen registros en bd
		//	Description:	Funcion que se encarga de verificar si existen registros para un
		//                  contrato y retorna el arreglo con los mismos
		//  Fecha:          25/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT g.codgar, g.desgar, g.codemp, g.codcon
				FROM sob_garantiacontrato g, sob_contrato c
				WHERE c.codemp='".$ls_codemp."' AND g.codemp='".$ls_codemp."' AND c.codcon=g.codcon and c.codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select garantias".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
				$lb_valido=true;
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_garantia ($as_codcon,$as_codgar)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_garantia
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true si la garantia buscada existe en bd
		//	Description:	Funcion que se encarga de verificar si existe una garantia de pago asociada a un
		//                  contrato
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  codgar
				 FROM sob_garantiacontrato
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codgar='".$as_codgar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select_garantia".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;
	}
	
    function uf_update_garantias($as_codcon,$aa_garantiasnuevas,$ai_totalfilas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_garantias
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las garantias de pago del 
		//					contrato que han sido modificadas.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$this->uf_select_garantias ($as_codcon,$la_garantiasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			$lb_update=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_garantiasviejas["codemp"][$li_j] == $ls_codemp) && (trim($la_garantiasviejas["codcon"][$li_j]) == trim($as_codcon)) &&  (trim($la_garantiasviejas["codgar"][$li_j]) == trim($aa_garantiasnuevas["codgar"][$li_i])) )
				{
					
					if ($la_garantiasviejas["desgar"][$li_j] != $aa_garantiasnuevas["desgar"][$li_i])
					{
						$lb_update=true;
					}
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_garantias($as_codcon,$aa_garantiasnuevas["codgar"][$li_i],$aa_garantiasnuevas["desgar"][$li_i],$aa_seguridad);
			}
			if($lb_update)
			{
				$lb_valido=$this->uf_update_descripciongarantia ($as_codcon,$aa_garantiasnuevas["codgar"][$li_i],$aa_garantiasnuevas["desgar"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_garantiasviejas["codemp"][$li_j] == $ls_codemp) && ($la_garantiasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_garantiasviejas["codgar"][$li_j] == $aa_garantiasnuevas["codgar"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_garantias($as_codcon,$la_garantiasviejas["codgar"][$li_j],$aa_seguridad);
			}
		}			
		return $lb_valido;
	}	
	
	function uf_update_descripciongarantia ($as_codcon,$as_codgar,$as_desga,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_descripciongarantia
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar la descripcion de una garantía
		//                  de pago asociada a un contrato.
		//  Fecha:          28/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_garantiacontrato
				SET desgar='".$as_desgar."'
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codgar='".$as_codgar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_descripciongarantia ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la descripción del detalle de la Garantia ".$as_codgar.": ".$as_desgar.", del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	  
	function uf_delete_garantias($as_codcon,$as_codgar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:       uf_delete_garantias
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar una garantias de pago asociada a un contrato.
		//  Fecha:          27/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_garantiacontrato
					WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codgar='".$as_codgar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_garantia".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el detalle de la garantia ".$as_codgar.", del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;	
	
	}
//*******************************************************************************************************//
//							Funciones relacionadas con el estado del contrato                            //
//*******************************************************************************************************//


function uf_update_estado($as_codcon,$ai_estado,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el estado del contrato
	//  Fecha:          24/03/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;	
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_contrato 
				 SET estcon='".$ai_estado."'
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";		
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_estadocontrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
			$ls_estado=$this->io_funsob->uf_convertir_numeroestado($ai_estado);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estado del Contrato ".$as_codcon." a ".$ls_estado.", Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
	}		
	return $lb_valido;
}	

function uf_select_estado ($as_codcon,&$estado)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar el estado del contrato
	//  Fecha:          24/03/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT estcon
			 FROM sob_contrato
			 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select estadocontrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$estado=$la_row["estcon"];
			$lb_valido=true;
		}		
	}
	return $lb_valido;
}

//*******************************************************************************************************//
//							Funciones relacionadas con el ultimo acta asociada al contrato               //
//*******************************************************************************************************//


function uf_update_ultimoacta($as_codcon,$ai_tipact,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_ultimoacta
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el ultimo acta asociada al contrato
	//  Fecha:          25/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_contrato 
				 SET ultactcon='".$ai_tipact."'
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";		
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_ultimoacta".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
			$ls_acta=$this->io_funsob->uf_convertir_numerotipoacta ($ai_tipact);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el campo Ultima Acta del Contrato ".$as_codcon." a ".$ls_acta.", Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
	}		
	return $lb_valido;
}
}
?>