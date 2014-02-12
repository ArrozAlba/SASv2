<?php
class sigesp_scb_class_report
{

	var $SQL;
	var $dat_emp;
	var $fun;
	var $io_msg;
	var $SQL_aux;
	var $ds_disponibilidad;
	var $ds_documentos;
	var $ds_data;
	var $ds_reporte_final;
	var $io_fecha;
	var $ds_bancos;
	var $io_validacion;
	function sigesp_scb_class_report($conn)
	{

	  require_once("../../shared/class_folder/class_sql.php");	
	  require_once("../../shared/class_folder/class_fecha.php");  
  	  require_once("../../shared/class_folder/class_validacion.php");  
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->fun = new class_funciones();
	  $this->io_validacion=new class_validacion();
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->SQL= new class_sql($conn);
	  $this->SQL_aux= new class_sql($conn);
	  $this->io_msg= new class_mensajes();
	  $this->io_fecha=new class_fecha();		
	  $this->dat_emp=$_SESSION["la_empresa"];
	  $this->ds_disponibilidad=new class_datastore();
	  $this->ds_documentos=new class_datastore();
	  $this->ds_reporte_final=new class_datastore();
	  $this->ds_bancos=new class_datastore();
	  $this->ds_data=new class_datastore();
	}


	function uf_cargar_bancos($object_bancos,$li_row) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//
	//	Metodo: uf_cargar_bancos
	//
	//	Access:  public
	//
	//	Arguments:

	//	Returns:		
	//  $object_bancos=  Arreglo de los bancos para enviarlo a la clase grid_param
	//
	//	Description:  Función que se encarga de seleccionar los   bancos y retornarlos en un arreglo de object
	//
	//////////////////////////////////////////////////////////////////////////////
	  
	  $ls_codemp=$this->dat_emp["codemp"];
	  $li_row=0;
		
	  $ls_sql="SELECT codban,nomban 
	  		   FROM scb_banco 
			   WHERE  codemp='".$ls_codemp."'
			   ORDER BY codban ASC";
	 
	   $rs_bancos=$this->SQL->select($ls_sql);
	   
	   if (($rs_bancos===false))
	   {
			$lb_valido=false;
			$this->is_msg_error="Error en select bancos,".$this->fun->uf_convertirmsg($this->SQL->message);
	   }
	   else
	   {
		   while($row=$this->SQL->fetch_row($rs_bancos))
		   {
				$li_row=$li_row+1;
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				$object_bancos[$li_row][1]="<input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px>";		
				$object_bancos[$li_row][2]="<input type=text name=txtcodban".$li_row."   value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
				$object_bancos[$li_row][3]="<input type=text name=txtnomban".$li_row."   value='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
		   }
		   if($li_row==0)
		   {
				$li_row=1;
				$ls_codban="";
				$ls_nomban="";
				$object_bancos[$li_row][1]="<input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px>";		
				$object_bancos[$li_row][2]="<input type=text name=txtcodban".$li_row."   value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
				$object_bancos[$li_row][3]="<input type=text name=txtnomban".$li_row."   value='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
		   }
		   $this->SQL->free_result($rs_bancos);
	   }
	   
	   //return $rs_proveedor;         
		 
	}//fin de uf_cargar_bancos
 	function uf_find_bancos($ls_codban,$ls_ctaban) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo: uf_cargar_bancos
	//	Access:  public
	//	Arguments:
	//	Returns:		
	//  $object_bancos=  Arreglo de los bancos para enviarlo a la clase grid_param
	//	Description:  Función que se encarga de seleccionar los   bancos y retornarlos en un arreglo de object
	//////////////////////////////////////////////////////////////////////////////
	  
	  $ls_codemp=$this->dat_emp["codemp"];
	  $li_row=0;
	  $ls_aux="";	  
	  if($ls_codban!="")
	  {	$ls_aux=" AND a.codban='".$ls_codban."'";}
	  if($ls_ctaban!="")	
  	  {	$ls_aux=" AND b.ctaban='".$ls_ctaban."'";}
	  
	  $ls_sql="SELECT a.codban,a.nomban ,b.ctaban,b.dencta
	  		   FROM scb_banco a,scb_ctabanco b
			   WHERE  a.codemp='".$ls_codemp."' AND a.codemp=b.codemp AND a.codban=b.codban".$ls_aux."
			   ORDER BY a.codban ASC,b.ctaban ASC";
	 
	   $rs_bancos=$this->SQL->select($ls_sql);
	   
	   if (($rs_bancos===false))
	   {
			$lb_valido=false;
			$this->is_msg_error="Error en select bancos,".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->is_msg_error;
	   }
	   else
	   {
		   $lb_valido=true;
		   while($row=$this->SQL->fetch_row($rs_bancos))
		   {
		   		$this->ds_bancos->insertRow("codban",$row["codban"]);
				$this->ds_bancos->insertRow("nomban",$row["nomban"]);
				$this->ds_bancos->insertRow("ctaban",$row["ctaban"]);
				$this->ds_bancos->insertRow("dencta",$row["dencta"]);
		   }
	   }
	   return $lb_valido;
	}//fin de uf_find_bancos

function uf_cargar_documentos($as_codope,$ad_fecdesde,$ad_fechasta,$as_codban,$as_ctaban,$as_codconcep,$as_estmov,$as_orden,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////
//
//	Function: uf_cargar_documentos
//
//	Arguments:
//			  -$as_codope=Codigo de la operacion a buscar ( Adicionalmente se maneja T para el caso de mostrar todos lo tipos de operación)
//			  -$ad_fecdesde=Fehca inicio rango de busqueda	
//			  -$ad_fechasta=Fehca final  rango de busqueda	
//			  -$as_codban=Codigo del banco
//			  -$as_ctaban=Cuenta bancaria
//			  -$as_codconcep=conepto del movimiento
//			  -$as_orden=Columan de ordenamiento del reporte
//
//  Description: Metodo que se encarga de retornar los documentos filtrados segun los param,etros 
//				 de busqaueda enviados
///////////////////////////////////////////////////////////////////////////////////////////////
		
	$ls_codemp = $this->dat_emp["codemp"];
	$lb_valido = false;
	$ls_straux = "";	
	if(!empty($ad_fecdesde))
	{
		$ld_fecdesde = $this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ls_straux   = " AND a.fecmov>='".$ld_fecdesde."' ";
	}
	if(!empty($ad_fechasta))
	{
		$ld_fechasta = $this->fun->uf_convertirdatetobd($ad_fechasta);
		$ls_straux   = $ls_straux." AND a.fecmov<='".$ld_fechasta."' ";
	}	
	if(!empty($as_codban))
	{
		$ls_straux = $ls_straux." AND a.codban='".$as_codban."' ";
	}	
	if(!empty($as_ctaban))
	{
		$ls_straux = $ls_straux." AND a.ctaban='".$as_ctaban."' ";
	}	
	if((!empty($as_codope))&&($as_codope!='T'))
	{
		$ls_straux = $ls_straux." AND a.codope='".$as_codope."' ";
	}
	if($as_codconcep!='---')
	{
		if(!empty($as_codconcep))
		{
			$ls_straux = $ls_straux." AND a.codconmov='".$as_codconcep."' ";
		}
	}
	if (!empty($as_estmov) && ($as_estmov!='-')) 
	   {
		 $ls_straux = $ls_straux. " AND a.estmov='".$as_estmov."'";
	   }

	$ls_ordaux = "";
	if (!empty($as_orden))
	{
	  $ls_ordaux = str_replace("M.",',a.',$as_orden);
	}
	
	$ls_sql = " SELECT a.codban as codban,c.nomban as nomban, trim(a.ctaban) as ctaban, a.codope as codope,".
			  "        (a.monto - a.monret) as monto,a.estmovint as estmovint,                             ".
			  "        a.fecmov as fecmov,a.nomproben as nomproben,a.numdoc as numdoc,a.estmov as estmov,  ".
			  "        a.conmov as conmov,a.estbpd as estbpd,a.numcarord as numcarord                      ".
			  "  FROM  scb_movbco a,scb_ctabanco b,scb_banco c                                             ".
			  " WHERE  a.codemp='".$ls_codemp."'                                                           ".
			  "   AND  a.codope<>'OP'                                                                      ".
			  "        $ls_straux                                                                          ".
			  "   AND  a.codban=b.codban                                                                   ".
			  "   AND  a.ctaban=b.ctaban                                                                   ".
			  "   AND  a.codban=c.codban                                                                   ".
			  "   AND  a.codemp=b.codemp                                                                   ".
			  "   AND  a.codemp=c.codemp	 														       ".
			  "   AND  a.ctaban IN (SELECT codintper ".
			  "					 			  FROM sss_permisos_internos ".
			  "				    			 WHERE codusu='".$_SESSION["la_logusr"]."' ".
			  "					 		     UNION ".
			  "				   				SELECT codintper ".
			  "				     			  FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			  "			   					 WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)	".	
			  " ORDER BY c.nomban,a.ctaban,a.fecmov $ls_ordaux";
	$rs_data = $this->SQL->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
	   }
	else
	   {
		  $li_numrows = $this->SQL->num_rows($rs_data);
		  if ($li_numrows>0)
			 {
			   $lb_valido=true; 
			 }
	   }
    return $rs_data;
}
	
	function uf_cargar_documentos_op($as_codope,$ad_fecdesde,$ad_fechasta,$as_codban,$as_ctaban,$as_codconcep,$as_orden)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//	Function: uf_cargar_documentos
		//
		//	Arguments:
		//			  -$as_codope=Codigo de la operacion a buscar ( Adicionalmente se maneja T para el caso de mostrar todos lo tipos de operación)
		//			  -$ad_fecdesde=Fehca inicio rango de busqueda	
		//			  -$ad_fechasta=Fehca final  rango de busqueda	
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$as_codconcep=conepto del movimiento
		//			  -$as_orden=Columan de ordenamiento del reporte
		//
		//  Description: Metodo que se encarga de retornar los documentos filtrados segun los param,etros 
		//				 de busqaueda enviados
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		if(!empty($ad_fecdesde))
		{
			$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
			$ls_aux=" AND a.fecmov>='".$ld_fecdesde."' ";
		}
		if(!empty($ad_fechasta))
		{
			$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
			$ls_aux=$ls_aux." AND a.fecmov<='".$ld_fechasta."' ";
		}	
		if(!empty($as_codban))
		{
			$ls_aux=$ls_aux." AND a.codban='".$as_codban."' ";
		}	
		if(!empty($as_ctaban))
		{
			$ls_aux=$ls_aux." AND a.ctaban='".$as_ctaban."' ";
		}	
		if((!empty($as_codope))&&($as_codope!='T'))
		{
			$ls_aux=$ls_aux." AND a.codope='".$as_codope."' ";
		}
		if($as_codconcep!='---')
		{
			if(!empty($as_codconcep))
			{
				$ls_aux=$ls_aux." AND a.codconmov='".$as_codconcep."' ";
			}
		}
		if($as_orden=='D')//Documento
		{
			$ls_aux=$ls_aux." ORDER BY a.numdoc";
		}
		if($as_orden=='C')//Cuenta
		{
			$ls_aux=$ls_aux." ORDER BY a.ctaban";
		}
		if($as_orden=='F')//Fecha
		{
			$ls_aux=$ls_aux." ORDER BY a.fecmov";
		}
		if($as_orden=='B')//Banco
		{
			$ls_aux=$ls_aux." ORDER BY a.codban";
		}
		if($as_orden=='O')//Operacion
		{
			$ls_aux=$ls_aux." ORDER BY a.codope";
		}
		
		$ls_sql="SELECT a.codban as codban,c.nomban as nomban, a.ctaban as ctaban, a.codope as codope,(a.monto - a.monret) as monto,a.estmovint as estmovint,a.fecmov as fecmov,a.nomproben as nomproben,a.numdoc as numdoc,a.estmov as estmov,a.conmov as conmov
				 FROM    scb_movbco a,scb_ctabanco b,scb_banco c
				 WHERE   a.codban=b.codban AND a.ctaban=b.ctaban AND a.codban=c.codban 
				 AND     a.codemp=b.codemp AND  a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.ctaban IN (SELECT codintper ".
								"					 FROM sss_permisos_internos ".
								"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
								"				    UNION ".
								"				   SELECT codintper ".
								"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
								"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)".$ls_aux;
		$rs_documentos=$this->SQL->select($ls_sql);
		if($rs_documentos===false)
		{
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_documentos))
			{
				$data=$this->SQL->obtener_datos($rs_documentos);
				$this->ds_documentos->data=$data;
				$lb_valido=true;
			}	
		}
	
	}

	function uf_cargar_chq_voucher($ls_numdoc,$ls_voucher,$ls_codban,$ls_ctaban)
	{
	
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT * 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND chevau='".$ls_voucher."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'";
	
		$rs_data=$this->SQL->select($ls_sql);
		
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
				$data=$this->SQL->obtener_datos($rs_data);
			}
			else
			{
				$data=array();
			}
			$this->SQL->free_result($rs_data);
		}
		return $data;
	}
	
	function uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban)
	{
	
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_solicitudes="";
		$i=0;
		$ls_sql="SELECT * 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'";
	
		$rs_data=$this->SQL->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$i=$i+1;
				if($i==1)
				{
					$ls_solicitudes=$row["numsol"];
				}
				else
				{
					$ls_solicitudes=$ls_solicitudes."-".$row["numsol"];
				}				
			}
			$this->SQL->free_result($rs_data);
		}
		return $ls_solicitudes;
	}
	
	function uf_select_data($io_sql,$ls_cadena,$ls_campo)
	{
		$data=$io_sql->select($ls_cadena);
		
		if($row=$io_sql->fetch_row($data))
		{
			$ls_result=$row[$ls_campo];
			
		}	
		else
		{
			$ls_result="";
		}
		$io_sql->free_result($data);
		return $ls_result;
	}

	function uf_cargar_dt_scg($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_estmov)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$dt_scg=array();
		
		$y=0;
		
		if(!empty($as_codope))
		{$ls_cad=" AND codope='".$as_codope."'";}
		else
		{$ls_cad="";}
		
		$ls_sql="SELECT   scg_cuenta,debhab,monto,desmov
				 FROM     scb_movbco_scg
				 WHERE    codemp='".$ls_codemp ."' AND numdoc ='".$as_numdoc."' and codban='".$as_codban."' and ctaban='".$as_ctaban."' AND estmov='".$as_estmov."' ".$ls_cad."	
				 ORDER BY debhab asc,scg_cuenta asc";
 
		$rs_scg=$this->SQL->select($ls_sql);		

		if(($rs_scg===false))
		{
			$lb_valido=false;		
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_scg))
			{
				$y=$y+1;
				$dt_scg["scg_cuenta"][$y]=$row["scg_cuenta"];
				$dt_scg["debhab"][$y]=$row["debhab"];
				$dt_scg["monto"][$y]=$row["monto"];
				$dt_scg["desmov"][$y]=$row["desmov"];			
			}			
			$this->SQL->free_result($rs_scg);
		}
		return $dt_scg;
	
	}
	
	function uf_cargar_dt_spg($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_estmov,&$lb_acceso_a_estructuras)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_acceso_a_estructuras=true;//inicializo en true por si el documento es sin afectacion presupuestaria
		$dt_spg=array();
		$y=0;
		if(!empty($as_codope))
		{$ls_cad=" AND codope='".$as_codope."'";}
		else
		{$ls_cad="";}
		$ls_sql="SELECT codestpro,spg_cuenta,monto, CASE (SELECT codintper FROM sss_permisos_internos 
														   WHERE codusu='nbarraez' AND scb_movbco_spg.codestpro||scb_movbco_spg.estcla=codintper  
														   UNION 
														  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos 
														   WHERE codusu='nbarraez' AND scb_movbco_spg.codestpro||scb_movbco_spg.estcla=codintper 
														     AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) WHEN codestpro||estcla THEN true ELSE false END acceso
				   FROM scb_movbco_spg
				  WHERE codemp='".$ls_codemp ."'
				    AND numdoc ='".$as_numdoc."' 
					AND codban='".$as_codban."' 
					AND ctaban='".$as_ctaban."' 
					AND estmov='".$as_estmov."' ".$ls_cad."	
				  ORDER BY codestpro,spg_cuenta asc";
		$rs_spg=$this->SQL->select($ls_sql);		
		if ($rs_spg===false)
		   {
			 $lb_valido=false;		
		   }
		else
		   {
			 $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			 $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			 $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			 $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			 $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			 while($row=$this->SQL->fetch_row($rs_spg))
			 {
				    $y=$y+1;
					$lb_acceso=$row["acceso"];
					if($lb_acceso=='t')
					{
						$dt_spg["spg_cuenta"][$y]=$row["spg_cuenta"];
						$ls_codestpro = $row["codestpro"];
						$ls_codestpro1 = substr(substr($ls_codestpro,0,25),-$li_loncodestpro1);
						$ls_codestpro2 = substr(substr($ls_codestpro,25,25),-$li_loncodestpro2);
						$ls_codestpro3 = substr(substr($ls_codestpro,50,25),-$li_loncodestpro3);
						$ls_codestpre  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
						if ($_SESSION["la_empresa"]["estmodest"]==2)
						   {
							 $ls_codestpro4 = substr(substr($ls_codestpro,50,25),-$li_loncodestpro4);
							 $ls_codestpro5 = substr($ls_codestpro,-$li_loncodestpro5);
							 $ls_codestpre  = $ls_codestpre.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						   }
						$dt_spg["estpro"][$y] = $ls_codestpre;
						$dt_spg["monto"][$y]=$row["monto"];				
					}
					else//no tiene acceso a la estrucura rompo el ciclo y retorno el acceso en falso para no imprimir el documento
					{
						$lb_acceso_a_estructuras=false;
						empty($dt_spg);
						break;
					}						
			 }			
			 $this->SQL->free_result($rs_spg);
		   }
		return $dt_spg;	
	}
	
	function uf_cargar_dt_spg_op($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_estmov)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$dt_spg=array();
		$y=0;
		if(!empty($as_codope))
		{$ls_cad=" AND codope='".$as_codope."'";}
		else
		{$ls_cad="";}
		$ls_sql="SELECT   codestpro,spg_cuenta,monto,desmov
				 FROM     scb_movbco_spgop
				 WHERE    codemp='".$ls_codemp ."' AND numdoc ='".$as_numdoc."' and codban='".$as_codban."' and ctaban='".$as_ctaban."' AND estmov='".$as_estmov."' ".$ls_cad."	
				 ORDER BY codestpro,spg_cuenta asc";
				 
		$rs_spg=$this->SQL->select($ls_sql);		

		if(($rs_spg===false))
		{
			$lb_valido=false;		
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_spg))
			{
				$y=$y+1;
				$dt_spg["spg_cuenta"][$y]=$row["spg_cuenta"];
				$dt_spg["estpro"][$y]=substr($row["codestpro"],0,20)."-".substr($row["codestpro"],20,6)."-".substr($row["codestpro"],26,3);
				$dt_spg["monto"][$y]=$row["monto"];
				$dt_spg["desmov"][$y]=$row["desmov"];
			}			
			$this->SQL->free_result($rs_spg);
		}
		return $dt_spg;
	
	}

	function uf_cargar_dt_spi($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_estmov)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$dt_spi=array();
		if(!empty($as_codope))
		{$ls_cad=" AND codope='".$as_codope."'";}
		else
		{$ls_cad="";}
		$ls_sql="SELECT spi_cuenta,monto,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5
				   FROM scb_movbco_spi
				  WHERE codemp='".$ls_codemp ."' 
				    AND numdoc ='".$as_numdoc."' 
					AND codban='".$as_codban."' 
					AND ctaban='".$as_ctaban."' 
					AND estmov='".$as_estmov."' ".$ls_cad."	
				  ORDER BY spi_cuenta ASC";
		$rs_data = $this->SQL->select($ls_sql);		
		if ($rs_data===false)
		   {
			 $lb_valido=false;		
		   }
		else
		   {
			 $dt_spi = $rs_data->GetRows();
			 $this->SQL->free_result($rs_data);
		   }
		return $dt_spi;	
	}

	function uf_find_pagos($as_tipproben,$as_proben_d,$as_proben_h,$ad_fecdesde,$ad_fechasta,$ls_codban,$ls_ctaban,$as_tiprep,$as_orden='CH',$as_operacion='T')
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	//	Function:	   uf_find_pagos
	// Access:			public
	//	Returns:			Boolean Retorna si encontro o no errores en la consulta
	//	Description:	Funcion que se encarga de llenar el datastore con los datos de los pagos realizados
	///////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido    = true;
	$ls_operacion = $ls_aux = $ls_sqlaux = "";
	$ls_codemp    = $this->dat_emp["codemp"];
	if (!empty($ad_fecdesde) && !empty($ad_fechasta))
	   {
		 $ld_fecini = $this->fun->uf_convertirdatetobd($ad_fecdesde);
		 $ld_fecfin = $this->fun->uf_convertirdatetobd($ad_fechasta);
		 $ls_sqlaux = " AND BRH.fecmov BETWEEN '".$ld_fecini."' AND '".$ld_fecfin."'";
	   }
	
	if ((!empty($ls_codban))&&(!empty($ls_ctaban)))
	   {
		 $ls_sqlaux = $ls_sqlaux." AND BRH.codban='".$ls_codban."' AND trim(BRH.ctaban) = '".trim($ls_ctaban)."'";
	   }
	if ($as_orden=='CH')//Documento
	   {
		 $ls_aux=" ORDER BY BRH.numdoc";
	   }
	elseif($as_orden=='F')//Fecha
	   {
		 $ls_aux=" ORDER BY BRH.fecmov";
	   }
	elseif($as_orden=='S')//Solicitud
	   {
		 $ls_aux=" ORDER BY numsol";
	   }
	elseif($as_orden=='C')//Cuenta
	   {
		 $ls_aux=" ORDER BY BRH.ctaban";
	   }	
	elseif($as_orden=='M')//Monto
	   {
		 $ls_aux=" ORDER BY BRH.monto";
	   }
	elseif($as_orden=='P')//Monto
	   {
		 $ls_aux=" ORDER BY BRH.nomproben";
	   }
	if ($as_operacion=='T')
	   {
	     $ls_operacion="(BRH.codope='CH' OR (BRH.codope='ND' AND BRH.procede='SNOCNO') OR (BRH.codope='ND' AND BRH.estbpd='T'))";
	   }
	elseif($as_operacion=='CH')
	   {	
	     $ls_operacion="BRH.codope='CH'";
	   }
	elseif($as_operacion=='ND')
	   {	
	     $ls_operacion="BRH.codope='ND' AND BRH.procede='SNOCNO'";	    
	   }
	elseif($as_operacion=='CO')
	   {	
	     $ls_operacion="BRH.codope='ND' AND BRH.estbpd='T'";	    
	   }
	else
		$ls_operacion="";
	
	if ($as_tiprep=='G')
	   {
	     $ls_sql = "SELECT DISTINCT BRH.ctaban as ctaban,BRH.numdoc as numdoc,BRH.nomproben as nomproben,BRH.codope,
						   BRH.estbpd,BRH.monret,COALESCE(XSC.numsol,' ') as numsol, BRH.fecmov as fecmov,
						   (BRH.monto-BRH.monret) as monto, BRH.estmov, COALESCE(XSC.monto,0) as monsol, BRH.conmov as conmov
					  FROM scb_movbco BRH 
					  LEFT OUTER JOIN cxp_sol_banco XSC
					    ON (BRH.numdoc=XSC.numdoc AND BRH.codban=XSC.codban AND BRH.ctaban=XSC.ctaban AND BRH.codope=XSC.codope AND BRH.estmov=XSC.estmov)
					 WHERE BRH.codemp='".$ls_codemp."' AND $ls_operacion $ls_sqlaux AND BRH.ctaban IN (SELECT codintper ".
								"					 FROM sss_permisos_internos ".
								"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
								"				    UNION ".
								"				   SELECT codintper ".
								"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
								"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
					$ls_aux";
	   }
	else
	   {
		 if ($as_tipproben=='P')
		    {
			  $ls_sqlaux = $ls_sqlaux." AND trim(BRH.cod_pro) BETWEEN '".trim($as_proben_d)."' AND '".trim($as_proben_h)."' AND BRH.tipo_destino='P'";
			}
		 elseif($as_tipproben=='B')
		    {
			  $ls_sqlaux = $ls_sqlaux." AND trim(BRH.ced_bene) BETWEEN '".trim($as_proben_d)."' AND '".trim($as_proben_h)."' AND BRH.tipo_destino='B'";
			}
         $ls_sql = "SELECT DISTINCT BRH.ctaban as ctaban, BRH.numdoc as numdoc, BRH.nomproben as nomproben, BRH.codope, 
						   BRH.estbpd, BRH.monret, COALESCE(XSC.numsol,' ') as numsol, BRH.fecmov as fecmov, 
						   (BRH.monto-BRH.monret) as monto, BRH.estmov, COALESCE(XSC.monto,0) as monsol, BRH.conmov as conmov
					  FROM scb_movbco BRH 
					  LEFT OUTER JOIN cxp_sol_banco XSC
					    ON (BRH.numdoc=XSC.numdoc AND BRH.codban=XSC.codban AND BRH.ctaban=XSC.ctaban AND BRH.codope=XSC.codope AND BRH.estmov=XSC.estmov)
					 WHERE BRH.codemp='".$ls_codemp."'
					   AND $ls_operacion $ls_sqlaux AND BRH.ctaban IN (SELECT codintper ".
								"					 FROM sss_permisos_internos ".
								"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
								"				    UNION ".
								"				   SELECT codintper ".
								"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
								"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
					 $ls_aux";
	   }
	$rs_data=$this->SQL->select($ls_sql);
	if ($rs_data===false)
	   {
	     return false;
       }
	return $rs_data;
    }

	function uf_cargar_movimientos($ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_codope,$as_orden="D")
	{
		$ls_codemp=$this->dat_emp["codemp"];
		$ds_movimientos=new class_datastore();
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ld_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ld_fechasta);
		if(!empty($ls_codope))
		{
			$ls_cad=" AND a.codope='".$ls_codope."' ";
		}
		else
		{
			$ls_cad="";
		}
		if(!empty($ls_codban))
		{
			$ls_cadban=" AND a.codban='".$ls_codban."' ";
		}
		else
		{
			$ls_cadban="";
		}
		if(!empty($ls_ctaban))
		{
			$ls_cadcta=" AND a.ctaban='".$ls_ctaban."' ";
		}
		else
		{
			$ls_cadcta="";
		}
		
		if($as_orden=='D')//Documento
		{
			$ls_aux=" ORDER BY a.numdoc";
		}
		elseif($as_orden=='BN')//Beneficiario
		{
			$ls_aux=" ORDER BY a.nomproben";
		}
		elseif($as_orden=='F')//Fecha
		{
			$ls_aux=" ORDER BY a.fecmov";
		}
		elseif($as_orden=='B')//Banco
		{
			$ls_aux=" ORDER BY a.codban";
		}
		elseif($as_orden=='O')//Operacion
		{
			$ls_aux=" ORDER BY a.codope";
		}
		elseif($as_orden=='M')//Operacion
		{
			$ls_aux=" ORDER BY a.monto";
		}
		$ls_sql="SELECT a.codban,a.ctaban,a.numdoc,a.fecmov,a.conmov,a.monto,a.monret,a.nomproben,b.nomban,c.dencta,d.nomtipcta,a.estmov ,a.codope
				   FROM scb_movbco a,scb_banco b,scb_ctabanco c,scb_tipocuenta d 
				  WHERE a.codemp='".$ls_codemp."' ".$ls_cad.$ls_cadban.$ls_cadcta." AND a.ctaban=c.ctaban AND a.codban=c.codban AND a.codban=b.codban AND c.codtipcta=d.codtipcta  AND fecmov between '".$ld_fecdesde."' AND '".$ld_fechasta."'
				    AND a.ctaban IN (SELECT codintper ".
								"					 FROM sss_permisos_internos ".
								"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
								"				    UNION ".
								"				   SELECT codintper ".
								"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
								"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) $ls_aux";
	
		$rs_data=$this->SQL->select($ls_sql);

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ds_movimientos->insertRow("codban",$ls_codban);
				$ls_ctaban=$row["ctaban"];
				$ds_movimientos->insertRow("ctaban",$ls_ctaban);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_nomproben=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_nomproben);
				$ld_fecmov=$row["fecmov"];
				$ds_movimientos->insertRow("fecmov",$ld_fecmov);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_conmov=$row["conmov"];
				$ds_movimientos->insertRow("conmov" ,$ls_conmov);	
				$ls_nomban=$row["nomban"];
				$ds_movimientos->insertRow("nomban" ,$ls_nomban);	
				$ls_dencta=$row["dencta"];
				$ds_movimientos->insertRow("dencta" ,$ls_dencta);	
				$ls_tipcta=$row["nomtipcta"];
				$ds_movimientos->insertRow("nomtipcta" ,$ls_tipcta);
				$ls_estmov=$row["estmov"];
				$ds_movimientos->insertRow("estmov" ,$ls_estmov);	
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope" ,$ls_codope);	
			}
			$this->SQL->free_result($rs_data);
		}
				
		return $ds_movimientos->data;
	}
	
	function uf_obtener_mov_conciliacion($ls_mesano,$ls_codban,$ls_ctaban,$ldec_salseglib,$ldec_salsegbco)
	{
		$io_fecha=new class_fecha();
		$ds_mov=new class_datastore();
		$ds_movimientos=new class_datastore();
		$ls_codemp=$this->dat_emp["codemp"];
		$ld_fechasta=$io_fecha->uf_last_day(substr($ls_mesano,0,2),substr($ls_mesano,2,4));
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ld_fechasta);
		$ld_fecdesde="01/".substr($ls_mesano,0,2)."/".substr($ls_mesano,2,4);
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ld_fecdesde);

		$ls_sql="SELECT * 
				 FROM scb_movbco
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND fecmov <='".$ld_fechasta."' AND (estreglib=' ' OR (estreglib<>' ' AND feccon<>'".$ld_fecdesde."')) ".
				" AND ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
				 
		$rs_data=$this->SQL->select($ls_sql);	
	 
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ds_mov->insertRow("codban",$ls_codban);
				$ls_ctaban=$row["ctaban"];
				$ds_mov->insertRow("ctaban",$ls_ctaban);
				$ls_numdoc=$row["numdoc"];
				$ds_mov->insertRow("numdoc",$ls_numdoc);
				$ls_nomproben=$row["nomproben"];
				$ds_mov->insertRow("nomproben",$ls_nomproben);
				$ld_fecmov=$row["fecmov"];
				$ds_mov->insertRow("fecmov",$ld_fecmov);
				$ldec_monto=$row["monto"];
				$ds_mov->insertRow("monto",$ldec_monto);
				$ls_conmov=$row["conmov"];
				$ds_mov->insertRow("conmov" ,$ls_conmov);	
				$ls_estmov=$row["estmov"];
				$ds_mov->insertRow("estmov" ,$ls_estmov);	
			}
			$this->SQL->free_result($rs_data);
		}		
		//$ldec_saldo_ant=$this->uf_calcular($ds_movimientos->data,$ls_mesano);
		$ldec_saldo_ant=$this->uf_calcular_saldolibro($ls_codban,$ls_ctaban,$ld_fechasta);

		if(abs($ldec_saldo_ant-$ldec_salseglib)>0.01)
		{
			$this->io_msg->message("Vuelva a modulo conciliación ya que hay movimientos no registrados");
			return false;
		}
		else
		{
			$this->io_msg->message("Todo Bien");
		}
		
			$ls_sql= "SELECT '01' as tipo, '-' as suma, numdoc , nomproben, fecmov , monto-monret as monto, codope  
					  FROM scb_movbco
					  WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
					        estreglib=''  AND ((feccon > '".$ld_fecdesde."'  ) OR (feccon='1900-01-01')) AND 
					       (((codope='CH' or codope='ND' or codope='RE') and estmov<>'A') or ((codope='DP' or codope='NC') and estmov='A')) ".
					"	AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";


		
		$rs_data= $this->SQL->select($ls_sql);

		if($rs_data===false)
		{
			print $this->SQL->message;
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}


			$ls_sql= "SELECT '02' as tipo, '+' as suma, numdoc, nomproben, fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND estreglib=''
					  AND ((feccon > '".$ld_fecdesde."' ) OR (feccon='1900-01-01'))
					  AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
		
		$rs_data= $this->SQL->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}
			
		
		// No Registradas en Libros
		
		   $ls_sql = "SELECT 'A1' as tipo, '+' as suma, numdoc, conmov as nomproben,fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
					  AND  feccon='".$ld_fecdesde."' AND estreglib='A' AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR 
					  ((codope='DP' OR codope='NC') AND estmov='A')) ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  "; 
		
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
		
		
		
		$ls_sql="SELECT 'A2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monto-monret as monto, codope 
				 FROM  scb_movbco
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				 AND feccon='".$ld_fecdesde."' AND estreglib='A' 
				 AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A'))".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
			
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
		
		
		
		// Error Libro
		$ls_sql="SELECT 'B1' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
			
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
				
		
		$ls_sql="SELECT 'B2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND  (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) ".
				" AND ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
		
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
				
		// Error Banco
		$ls_sql="SELECT 'C1' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monmov as monto, codope 
				 FROM scb_errorconcbco 
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ld_fecdesde."' AND esterrcon='C' AND 
				 (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
			
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
		
		$ls_sql="SELECT 'C2' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monmov as monto, codope 
				 FROM  scb_errorconcbco 
		 		 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ld_fecdesde."' and esterrcon='C' AND 
				 (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ";
		
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}		
				
		return $ds_movimientos->data;
	
	}
			
	function uf_calcular($data,$ls_mesano)		 
	{
		$ds_mov=new class_datastore();	
		$ds_mov->data=$data;
		$li_total=$ds_mov->getRowCount("numdoc");
		$ldec_CreditosTmp=0;
		$ldec_CreditosTmpNeg=0;
		$ldec_DebitosTmp=0;
		$ldec_DebitosTmpNeg=0;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_codope=$ds_mov->getValue("codope",$li_i);
			$ls_estmov=$ds_mov->getValue("estmov",$li_i);
			$ldec_monto=$ds_mov->getValue("monto",$li_i);
			if((($ls_codope=='CH')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov<>'A'))
			{
				$ldec_CreditosTmp=$ldec_CreditosTmp+$ldec_monto;
			}
			if((($ls_codope=='CH')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov=='A'))
			{
				$ldec_CreditosTmpNeg=$ldec_CreditosTmpNeg+$ldec_monto;
			}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov<>'A'))
			{
				$ldec_DebitosTmp=$ldec_DebitosTmp+$ldec_monto;
			}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov=='A'))
			{
				$ldec_DebitosTmpNeg=$ldec_DebitosTmpNeg+$ldec_monto;
			}
		}
		$ldec_DebitosAnt = $ldec_DebitosTmp-$ldec_DebitosTmpNeg;
		$ldec_CreditosAnt = $ldec_CreditosTmp-$ldec_CreditosTmpNeg;
		$ldec_SaldoAnterior = $ldec_DebitosAnt - $ldec_CreditosAnt;
				
		return round($ldec_SaldoAnterior,2);	
	
	}
	
	function uf_calcular_saldolibro($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	    :  uf_calcular_saldolibro
	//
	//	Return	    :  ldec_saldo
	//
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los movimientos registrdos en libro
	///////////////////////////////////////////////////////////////////////////// 
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;
	
	$ls_codemp = $this->dat_emp["codemp"];	
	
	$ld_fecha = $this->fun->uf_convertirdatetobd($ad_fecha);
		
	$ls_sql="SELECT monhab,mondeb,(mondeb - monhab) As saldo
			 FROM ( SELECT COALESCE( SUM(monto - monret),0) As monhab
				   FROM  scb_movbco 
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	  			   (codope='RE' OR codope='ND' OR codope='CH') AND  estmov<>'A' AND estmov<>'O' AND (estreglib<>'A' or (estreglib='A' AND estcon=1)) AND codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."') D,
				 ( SELECT COALESCE( SUM(monto - monret),0) As mondeb
				   FROM scb_movbco
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	 			   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND (estreglib<>'A' or (estreglib='A' AND estcon=1)) AND codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."') H ";
													
		
	$rs_saldos=$this->SQL->select($ls_sql);
		
		if(($rs_saldos==false)&&($this->SQL->message!=""))
		{
			print "Saldolibro".$this->SQL->message;
		}
		else
		{
		
			if($row=$this->SQL->fetch_row($rs_saldos))
			{
				$ldec_mondeb=$row["mondeb"];
				$ldec_monhab=$row["monhab"];
				$ldec_saldo=$row["saldo"];
				
				if(is_null($ldec_saldo))
				{	
					$ldec_saldo=0;
				}
				if( (is_null($ldec_monto_debe) )&&($ldec_monto_haber>0) )
				{
					$ldec_saldo=$ldec_monto_haber;
				} 
				if( (is_null($ldec_monto_haber))&&($ldec_monto_debe>0) )
				{
					$ldec_saldo=$ldec_monto_debe;
				}
			}
			$this->SQL->free_result($rs_saldos);			
		}			
	
	return  $ldec_saldo;
	}
	
	function uf_cargar_documentos_transito($as_periodo,$as_codban,$as_ctaban,$as_orden)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//	Function: uf_cargar_documentos
		//
		//	Arguments:
		//            -$as_periodo=Periodo a buscar 
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$as_orden=Columan de ordenamiento del reporte
		//
		//  Description: Metodo que se encarga de retornar los documentos en transito para el bancoi y cuenta en el periodo 
		//  Fecha de Creacion: 26/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		$ld_fecha="01/".$as_periodo;
		$ld_fecultimo=$this->io_fecha->uf_last_day(substr($as_periodo,0,2),substr($as_periodo,3,4));
		$ld_fecultimo=$this->fun->uf_convertirdatetobd($ld_fecultimo);
		if($as_orden=='D')//Documento
		{
			$ls_aux=$ls_aux." ORDER BY numdoc";
		}
		if($as_orden=='C')//Cuenta
		{
			$ls_aux=$ls_aux." ORDER BY ctaban";
		}
		if($as_orden=='F')//Fecha
		{
			$ls_aux=$ls_aux." ORDER BY fecmov";
		}
		if($as_orden=='B')//Banco
		{
			$ls_aux=$ls_aux." ORDER BY codban";
		}
		if($as_orden=='O')//Operacion
		{
			$ls_aux=$ls_aux." ORDER BY codope";
		}
		$ls_sql=" SELECT numdoc,codope,codban,ctaban,fecmov,conmov,nomproben,estmov, 
						 (monto-monret) as monto, estbpd,estcon,estimpche,monobjret,cod_pro,ced_bene,chevau,feccon,estreglib
				  FROM   scb_movbco
				  WHERE  fecmov <= '".$ld_fecultimo."' AND ctaban='".$as_ctaban."' AND codban='".$as_codban."' AND trim(estreglib)= '' AND
						 (feccon='1900-01-01' OR feccon>'".$ld_fecultimo."') AND estcon=0 ".
					" AND ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) ".$ls_aux;  
		
		$rs_documentos=$this->SQL->select($ls_sql);
		
		if($rs_documentos===false)
		{
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_documentos))
			{
				$data=$this->SQL->obtener_datos($rs_documentos);
				$this->ds_documentos->data=$data;
				$lb_valido=true;
			}	
		}
	
	}
	
	function uf_cargar_documentos_conciliados($as_fecdesde,$as_fechasta,$as_codban,$as_ctaban,$as_sql_aux,$as_orden)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//	Function: uf_cargar_documentos_conciliados
		//
		//	Arguments:
		//            -$as_periodo=Periodo a buscar 
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$as_orden=Columan de ordenamiento del reporte
		//
		//  Description: Metodo que se encarga de retornar los documentos en transito para el bancoi y cuenta en el periodo 
		//  Fecha de Creacion: 26/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($as_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($as_fechasta);
		if($as_orden=='D')//Documento
		{
			$ls_aux=$ls_aux." ORDER BY numdoc";
		}
		if($as_orden=='C')//Cuenta
		{
			$ls_aux=$ls_aux." ORDER BY ctaban";
		}
		if($as_orden=='F')//Fecha
		{
			$ls_aux=$ls_aux." ORDER BY fecmov";
		}
		if($as_orden=='B')//Banco
		{
			$ls_aux=$ls_aux." ORDER BY codban";
		}
		if($as_orden=='O')//Operacion
		{
			$ls_aux=$ls_aux." ORDER BY codope";
		}
		$ls_sql=" SELECT numdoc,codope,codban,ctaban,fecmov,conmov,nomproben,estmov, 
						 (monto-monret) as monto, estbpd,estcon,estimpche,monobjret,cod_pro,ced_bene,chevau,feccon,estreglib
				  FROM   scb_movbco
				  WHERE  fecmov between '".$ld_fecdesde."' AND '".$ld_fechasta."' AND ctaban='".$as_ctaban."' AND codban='".$as_codban."' AND trim(estreglib)= '' 
				  AND estcon=1 ".$as_sql_aux.
				" AND ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ".$ls_aux;  
		$rs_documentos=$this->SQL->select($ls_sql);
		
		if($rs_documentos===false)
		{
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_documentos))
			{
				$data=$this->SQL->obtener_datos($rs_documentos);
				$this->ds_documentos->data=$data;
				$lb_valido=true;
			}	
		}
	
	}
	
	 function uf_scb_reportes_presupuesto_x_banco($adt_fecdes,$adt_fechas,$as_ctaspg_desde,$as_ctaspg_hasta,$as_codban,$as_ctaban,$as_ckbfec,$as_ckbpro,$as_ckbdoc,$as_ckbbene)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scb_reportes_presupuesto_x_banco
	 //     Argumentos :    $adt_fecdes   //   fecha desde
	 //                     $adt_fechas   //   fecha hasta 
	 //                     $ls_spg_cuenta_desde  //  cuenta desde
	 //                     $ls_spg_cuenta_hasta   // cuenta  hasta
	 //                     $as_codban  // codigo del banco
	 //                     $as_ctaban  // cuenta del banco
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida las operaciones por especificas 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/09/2006          Fecha última Modificacion :      Hora :
  	 //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_cadena="";
	  $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
	  $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	  $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	  $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	  $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	  
	  $this->ds_reporte_final->resetds("numdoc");
	  $adt_fecdes=$this->fun->uf_convertirdatetobd($adt_fecdes);
	  $adt_fechas=$this->fun->uf_convertirdatetobd($adt_fechas);
	  if($as_ckbfec==1) { $ls_cadena=" ORDER BY b.codestpro,b.spg_cuenta,a.fecha"; }
	  if($as_ckbdoc==1) { if($ls_cadena!=""){$ls_cadena=$ls_cadena.",b.codestpro,b.spg_cuenta,a.numdoc";}else{$ls_cadena=" ORDER BY b.codestpro,b.spg_cuenta,a.numdoc";} }
	  if($as_ckbpro==1) { if($ls_cadena!=""){$ls_cadena=$ls_cadena.",b.codestpro,b.spg_cuenta,b.procede";}else{$ls_cadena=" ORDER BY b.codestpro,b.spg_cuenta,a.procede";} }
	  if($as_ckbbene==1) { if($ls_cadena!=""){$ls_cadena=$ls_cadena.",b.codestpro,b.spg_cuenta,a.nomproben";}else{$ls_cadena=" ORDER BY b.codestpro,b.spg_cuenta,a.nomproben";} }
	  if(($as_ckbfec!=1) && ($as_ckbdoc!=1) && ($as_ckbpro!=1) && ($as_ckbbene!=1))
	  	$ls_cadena= " ORDER BY b.codestpro,b.spg_cuenta";
	  $ls_aux="";
	  if((!empty($as_ctaspg_desde))&&(!empty($as_ctaspg_hasta)))
	  {
	    $ls_aux=" AND b.spg_cuenta between '".$as_ctaspg_desde."' AND '".$as_ctaspg_hasta."' ";
      }
	  $ls_sql=" SELECT a.numdoc as numdoc,a.codban as codban,a.ctaban as ctaban,a.codope as codope,a.fecha as fecha,
					   a.conmov as conmov,a.cod_pro as cod_pro,a.ced_bene as ced_bene,a.nomproben as nomproben,
					   a.tipo_destino as tipo_destino,sum(b.monto) as monto,b.codestpro,b.spg_cuenta,
					   c.denominacion
				FROM scb_movbco a,scb_movbco_spg b,spg_cuentas c 
				WHERE a.codemp=b.codemp 
				  AND a.codemp='".$_SESSION["la_empresa"]["codemp"]."' 
				  AND a.codban='".$as_codban."' 
				  AND a.ctaban='".$as_ctaban."'
				  AND a.estmov='C' 
				  AND a.fecha between '".$adt_fecdes."' AND '".$adt_fechas."' ".$ls_aux."
				  AND a.codemp=b.codemp 
				  AND a.codban=b.codban	
				  AND a.ctaban=b.ctaban 
				  AND a.numdoc=b.numdoc	
				  AND a.codope=b.codope 
				  AND a.estmov=b.estmov				   
				  AND c.codemp=b.codemp 
				  AND SUBSTR(b.codestpro,1,25)=c.codestpro1 
				  AND SUBSTR(b.codestpro,26,25)=c.codestpro2 
				  AND SUBSTR(b.codestpro,51,25)=c.codestpro3 
 				  AND SUBSTR(b.codestpro,76,25)=c.codestpro4  
				  AND SUBSTR(b.codestpro,101,25)=c.codestpro5 
				  AND b.spg_cuenta=c.spg_cuenta ".
				" AND a.ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  
				GROUP BY a.numdoc,a.codban,a.ctaban,a.codope,a.fecha,a.conmov,a.cod_pro,a.ced_bene,
				       a.nomproben,a.tipo_destino, b.codestpro,b.spg_cuenta,c.denominacion ".$ls_cadena;
	   $rs_data=$this->SQL->select($ls_sql);
	  if($rs_data===false)
	  {
		   $this->io_msg->message("CLASE->sigesp_scb_class_report;MÉTODO->uf_scb_reportes_presupuesto_x_banco;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
			$lb_valido = false;
	  }
      else
      {
			
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $lb_valido=true;
			   $ls_codope       = $row["codope"];
			   $ls_documento    = $row["numdoc"];
			   $ldt_fecha       = $this->fun->uf_formatovalidofecha($row["fecha"]);
			   $ldt_fecha       = $this->fun->uf_convertirfecmostrar($ldt_fecha);
			   $ls_descripcion  = $row["conmov"]; 
			   $ld_monto		= $row["monto"]; 
			   $ls_codban		= $row["codban"]; 
			   $ls_cod_pro		= $row["cod_pro"]; 
			   $ls_ced_bene		= $row["ced_bene"]; 
			   $ls_nomproben	= $row["nomproben"]; 
			   $ls_tipo_destino = $row["tipo_destino"]; 
			   $ls_codestpro1 = substr(substr($row["codestpro"],0,25),-$li_loncodestpro1);
			   $ls_codestpro2 = substr(substr($row["codestpro"],25,25),-$li_loncodestpro2);
			   $ls_codestpro3 = substr(substr($row["codestpro"],50,25),-$li_loncodestpro3);
			   if ($li_estmodest==2)
				  {
					$ls_codestpro4 = substr(substr($row["codestpro"],75,25),-$li_loncodestpro4);
					$ls_codestpro5 = substr(substr($row["codestpro"],100,25),-$li_loncodestpro5);
					$ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;	
				  }
			   else
				  {
				    $ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				  }
			   $ls_spg_cuenta=trim($row["spg_cuenta"]);
			   $ls_denominacion=$row["denominacion"];  
			   $this->ds_reporte_final->insertRow("codope",$ls_codope);
			   $this->ds_reporte_final->insertRow("numdoc",$ls_documento);
			   $this->ds_reporte_final->insertRow("fecha",$ldt_fecha);
			   $this->ds_reporte_final->insertRow("conmov",$ls_descripcion);
			   $this->ds_reporte_final->insertRow("monto",$ld_monto);					 
			   $this->ds_reporte_final->insertRow("codban",$ls_codban);
			   $this->ds_reporte_final->insertRow("cod_pro",$ls_cod_pro);
			   $this->ds_reporte_final->insertRow("ced_bene",$ls_ced_bene);
			   $this->ds_reporte_final->insertRow("nomproben",$ls_nomproben);
			   $this->ds_reporte_final->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->ds_reporte_final->insertRow("codestpro",$ls_programatica);
			   $this->ds_reporte_final->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->ds_reporte_final->insertRow("denominacion",$ls_denominacion);
			}//while   
		    $this->SQL->free_result($rs_data);   
	  }//else
       return  $lb_valido;
	 }//fin uf_spg_reportes_operacion_por_banco
	 
	 function uf_cargar_cheques_custodia_entregados($ls_fechades,$ls_fechahas,$ls_codban,$ls_ctaban,$ls_probendesde,$ls_probenhasta,$ls_tipo_destino,$ls_tiprep)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//	Function: uf_cargar_documentos
		//
		//	Arguments:
		//			  -$as_codope=Codigo de la operacion a buscar ( Adicionalmente se maneja T para el caso de mostrar todos lo tipos de operación)
		//			  -$ad_fecdesde=Fehca inicio rango de busqueda	
		//			  -$ad_fechasta=Fehca final  rango de busqueda	
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$as_codconcep=conepto del movimiento
		//			  -$as_orden=Columan de ordenamiento del reporte
		//
		//  Description: Metodo que se encarga de retornar los documentos filtrados segun los param,etros 
		//				 de busqaueda enviados
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		$this->ds_documentos->reset_ds();
		if(!empty($ls_codban))
		{
			$ls_aux=$ls_aux." AND codban='".$ls_codban."' ";
		}	
		if(!empty($ls_ctaban))
		{
			$ls_aux=$ls_aux." AND ctaban='".$ls_ctaban."' ";
		}
		if($ls_tipo_destino=='P')				
		{
			$ls_aux=$ls_aux." AND tipo_destino='P' AND cod_pro between '".$ls_probendesde."' AND '".$ls_probenhasta."' ";
		}
		if($ls_tipo_destino=='B')				
		{
			$ls_aux=$ls_aux." AND tipo_destino='B' AND ced_bene between '".$ls_probendesde."' AND '".$ls_probenhasta."' ";
		}
		if (!empty($ls_fechades) && !empty($ls_fechahas))
		   {
		     $ld_fecdesde = $this->fun->uf_convertirdatetobd($ls_fechades);
			 $ld_fechasta = $this->fun->uf_convertirdatetobd($ls_fechahas);
		   }
		if ($ls_tiprep=='C')
		   {
			 $ls_aux = $ls_aux." AND (estimpche=1 AND emicheproc=0) AND (estmov<>'A' AND estmov<>'O') AND fecmov BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."'"; 						
		   }
		elseif($ls_tiprep=='E')
		   {
		     $ls_aux = $ls_aux." AND (estimpche=1 AND emicheproc=1) AND (estmov<>'A' AND estmov<>'O') AND emichefec BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."'"; 						
		   }
		$ls_sql="SELECT codban,ctaban,codope,(monto - monret) as monto_total,fecmov,nomproben,numdoc,estmov,conmov,emicheproc,emichefec,emicheced,emichenom
				   FROM scb_movbco 
				  WHERE codemp='".$ls_codemp."' 
				    AND codope='CH' ".
				"   AND ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ".$ls_aux;
		$rs_documentos=$this->SQL->select($ls_sql);
		if($rs_documentos===false)
		{
			$lb_valido=false;
		}
		else
		{
		   $lb_valido=true;
			while($row=$this->SQL->fetch_row($rs_documentos))
			{
			   $this->ds_documentos->insertRow("codope",$row["codope"]);
 			   $this->ds_documentos->insertRow("numdoc",$row["numdoc"]);
			   $this->ds_documentos->insertRow("codban",$row["ctaban"]);
			   $this->ds_documentos->insertRow("monto",$row["monto_total"]);
			   $ld_fecmov=$row["fecmov"];
			   $ld_fecmov=$this->fun->uf_convertirfecmostrar($ld_fecmov);
			   $ld_fecvenc=$this->io_validacion->RelativeDate($ld_fecmov,90);
			   $this->ds_documentos->insertRow("fecmov",$ld_fecmov);
			   $this->ds_documentos->insertRow("fecvenc",$ld_fecvenc);
			   $ld_fecentrega=$this->fun->uf_convertirfecmostrar($row["emichefec"]);
			   $this->ds_documentos->insertRow("emichefec",$ld_fecentrega);
			   $this->ds_documentos->insertRow("emicheced",$row["emicheced"]);
			   $this->ds_documentos->insertRow("emichenom",$row["emichenom"]);
			   $this->ds_documentos->insertRow("emicheproc",$row["emicheproc"]);
			   $ls_estmov=$row["estmov"];
			   switch($ls_estmov){
			    case 'C':
					$ls_estmov='Contabilizado';
					break;
			    case 'N':
					$ls_estmov='No Contabilizado';
					break;
			    case 'L':
					$ls_estmov='No Contabilizable';
					break;
				case 'A':
					$ls_estmov='Anulado';
					break;					
				case 'O':
					$ls_estmov='Original';
					break;										
			   }
 			   $this->ds_documentos->insertRow("estmov",$ls_estmov);
			   $this->ds_documentos->insertRow("nomproben",$row["nomproben"]);	
			}
			$this->SQL->free_result($rs_documentos); 	
		}
		return $lb_valido;	
	}
	 
 function uf_cargar_documentos_relacion($arr_documentos,$arr_fechas,$arr_operaciones,$ls_codban,$ls_ctaban)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//	Function: uf_cargar_documentos
	//
	//	Arguments:
	//			  -$as_codope=Codigo de la operacion a buscar ( Adicionalmente se maneja T para el caso de mostrar todos lo tipos de operación)
	//			  -$ad_fecdesde=Fehca inicio rango de busqueda	
	//			  -$ad_fechasta=Fehca final  rango de busqueda	
	//			  -$as_codban=Codigo del banco
	//			  -$as_ctaban=Cuenta bancaria
	//			  -$as_codconcep=conepto del movimiento
	//			  -$as_orden=Columan de ordenamiento del reporte
	//
	//  Description: Metodo que se encarga de retornar los documentos filtrados segun los param,etros 
	//				 de busqaueda enviados
	///////////////////////////////////////////////////////////////////////////////////////////////
		
	$ls_codemp=$this->dat_emp["codemp"];
	$lb_valido = true;
	$ls_aux="";	
	$this->ds_documentos->reset_ds();
	$li_totdoc=count($arr_documentos);
	$li_totfec=count($arr_fechas);				
	for($li_i=0;$li_i<$li_totdoc;$li_i++)		
	{
		$ld_fecmov=$this->fun->uf_convertirdatetobd($arr_fechas[$li_i]);
		$ls_numdoc=$arr_documentos[$li_i];
		$ls_codope=$arr_operaciones[$li_i];
		if($_SESSION["ls_gestor"]=='MYSQLT')
		{
			$ls_nomproben="CASE WHEN a.cod_pro='----------' THEN CONCAT(ben.nombene,' ',ben.apebene)
							    ELSE prov.nompro
		    			   END as nomproben";
		}
		elseif($_SESSION["ls_gestor"]=='MYSQLT' || $_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_nomproben="CASE WHEN a.cod_pro='----------' THEN (ben.nombene||' '||ben.apebene)
							    ELSE prov.nompro
		    			   END as nomproben";
		}
		
		if ($this->uf_check_tipo_cartaorden($ls_numdoc,$ls_codban,$ls_ctaban)) 
		   {
		     $ls_sql=" SELECT b.codban,b.ctaban,b.codope,a.monsolpag as monto_total,b.fecmov,".$ls_nomproben.",b.numdoc,
					  		  b.estmov,b.estbpd,b.numcarord
					     FROM scb_dt_movbco a,scb_movbco b,rpc_proveedor prov,rpc_beneficiario ben
					    WHERE b.codope='ND' AND b.codemp='".$ls_codemp."'
						  AND b.codban='".$ls_codban."' AND b.ctaban='".$ls_ctaban."' AND b.fecmov='".$ld_fecmov."'
						  AND b.numdoc='".$ls_numdoc."' AND a.codemp=b.codemp AND a.codban=b.codban
						  AND a.ctaban=b.ctaban AND a.codope=b.codope AND a.numdoc=b.numdoc
						  AND a.cod_pro=prov.cod_pro AND a.ced_bene=ben.ced_bene ".
					"     AND b.ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  
					    ORDER BY b.fecmov,b.numdoc ASC ";
		   }
		else
		   {
		     $ls_sql=" SELECT  codban,ctaban,codope,(monto - monret) as monto_total,fecmov,nomproben,numdoc,  ".
				     "         estmov,estbpd,numcarord  													  ".
					 "   FROM  scb_movbco                                                                     ".
					 "  WHERE  codope='".$ls_codope."' AND codemp='".$ls_codemp."'                            ".
					 "    AND  codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND fecmov='".$ld_fecmov."'".
					 "    AND  numdoc='".$ls_numdoc."'                                                        ".
					 "    AND ctaban IN (SELECT codintper ".
					 "					 FROM sss_permisos_internos ".
					 "				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					 "				    UNION ".
					 "				   SELECT codintper ".
					 "				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					 "					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ".
					 "  ORDER BY fecmov,numdoc ASC                                                            ";
		   }
		$rs_data = $this->SQL->select($ls_sql);
		if ($rs_data===false)
		   {
		     $lb_valido=false;
		   }
		else
		   {
		     $lb_valido=true;
			 while($row=$this->SQL->fetch_row($rs_data))
				  { 
				    $this->ds_documentos->insertRow("numdoc",$ls_numdoc);
				    $this->ds_documentos->insertRow("fecmov",$arr_fechas[$li_i]);
				    $this->ds_documentos->insertRow("monto",$row["monto_total"]);
				    $this->ds_documentos->insertRow("codope",$row["codope"]);
				    $this->ds_documentos->insertRow("estmov",$row["estmov"]);
				    $this->ds_documentos->insertRow("nomproben",$row["nomproben"]);
				    $this->ds_documentos->insertRow("estbpd",$row["estbpd"]);
					$this->ds_documentos->insertRow("numcarord",$row["numcarord"]);
				  }
			  $this->SQL->free_result($rs_data); 	
		   }
		}
		return $lb_valido;	
	}

	function uf_check_tipo_cartaorden($as_numdoc,$as_codban,$as_ctaban)
	{
	  /*--------------------------------------------------------------
		Function:	    uf_select_cartaorden
		Description:	Funcion que se buscar los datos de una carta orden especifica
		Fecha: 26/12/2006
		Autor: Ing. Laura Cabre
	               
	----------------------------------------------------------------------------*/
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_sql="SELECT a.numdoc,b.numdoc
				   FROM scb_movbco a,scb_dt_movbco b
				  WHERE a.codemp='".$ls_codemp."' 
				    AND a.codemp=b.codemp AND a.codban=b.codban AND a.numdoc='$as_numdoc' 
				    AND a.codban='$as_codban' AND a.ctaban='$as_ctaban' 
			 	    AND a.codope='ND' AND a.ctaban=b.ctaban  AND a.codope=b.codope AND a.numdoc=b.numdoc";	
		$rs_data=$this->SQL->select($ls_sql);
		
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error uf_check_tipo_cartaorden, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			   $lb_existe=true;
			}
			else
			{
			   $lb_existe=false;
			}
			$this->SQL->free_result($rs_data);
		}
		return $lb_existe;	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_cargar_cheques_caducados ($ad_fecdes,$ad_fechas,$as_codban,$as_ctaban,$ad_feccad,&$ls_dias)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_cargar_cheques_caducados		
		//	Arguments:
		//			  -$as_fecdes=Fecha de inicio de emisión de cheque
		//			  -$as_fechas=Fehca de finalizacion de emisión de cheques
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$ad_feccad=Fecha tope de caducidad
		//  Description: Metodo que se encarga de retornar los cheques caducados según los parámetros
		//Realizado Por: Ing. María Beatriz Unda
		//        Fecha: 25/08/2008		
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		$this->ds_data->reset_ds();
		
		if (!empty($ad_fecdes))
		{
		     $ad_fecdes = $this->fun->uf_convertirdatetobd($ad_fecdes);			 
		}
		if (!empty($ad_fechas))
		{
		     $ad_fechas = $this->fun->uf_convertirdatetobd($ad_fechas);			 
		}
		
		$ls_sql="SELECT numdoc,codban,ctaban,monto,fecmov,nomproben
				   FROM scb_movbco 
				  WHERE codemp='".$ls_codemp."' 
				    AND estmov <> 'A' 
					AND estmov <> 'O'
				    AND fecmov BETWEEN '".$ad_fecdes."' AND '".$ad_fechas."'
				    AND codope='CH' 
					AND codban='".$as_codban."'
					AND ctaban='".$as_ctaban."' ".
				"   AND ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  
				ORDER BY fecmov";
					
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
		   $lb_valido=true;
		   $this->uf_select_dias_caducidad($ls_codemp,$ls_dias);
		   if ($ls_dias!=0)
		   {		   
				while($row=$this->SQL->fetch_row($rs_data))
				{
				  $ld_fecmov=$row["fecmov"];
				  $ld_fecmov=$this->fun->uf_convertirfecmostrar($ld_fecmov);
				  $ls_fecha_caducidad=$this->io_fecha->suma_fechas($ld_fecmov,$ls_dias);
				  if ($this->io_fecha->uf_comparar_fecha($ls_fecha_caducidad,$ad_feccad))
				  {
					   $this->ds_data->insertRow("codban",$row["codban"]);
					   $this->ds_data->insertRow("ctaban",$row["ctaban"]);
					   $this->ds_data->insertRow("numdoc",$row["numdoc"]);
					   $this->ds_data->insertRow("monto",$row["monto"]);							   
					   $this->ds_data->insertRow("fecmov",$ld_fecmov);
					   $this->ds_data->insertRow("feccad",$ls_fecha_caducidad);
					   $this->ds_data->insertRow("nomproben",$row["nomproben"]);	
				  }
				}
				$this->SQL->free_result($rs_data); 	
			}
			
		}
		return $lb_valido;	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 function uf_select_dias_caducidad($as_codemp,&$as_dias)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_select_dias_caducidad		
		//	Arguments:
		//			  -$as_codemp=Código de la Empresa
		//			  -$as_dias=Dias de Caducidad de los Cheques de la empresa
		//  Description: Metodo que se encarga de retornar el múmero de día de caducidad de los cheques de la empresa
		//Realizado Por: Ing. María Beatriz Unda
		//        Fecha: 25/08/2008		
		///////////////////////////////////////////////////////////////////////////////////////////////
		$as_dias=0;	
			$ls_sql="SELECT diacadche
				   FROM sigesp_empresa 
				  WHERE codemp='".$as_codemp."' ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
		   $lb_valido=true;
		  
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$as_dias=intval ($row["diacadche"]);
			}
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------------
     function select_anticipos_amortizacion($as_cod_prodes, $as_cod_prohas ,$as_cedbebdes, $as_cedbebhas,
	                                        $as_fecdes, $as_fechas, $ls_orden)
	 {
	    ////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: select_anticipos_amortizacion		
		//	Arguments:
		//			 
		//  Description: metodo para mostrar los anticipos amortizados
		//Realizado Por: Ing. Jennifer Rivero
		//        Fecha: 08/10/2008	
		///////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_order="";
		$as_fecdes = $this->fun->uf_convertirdatetobd($as_fecdes);
		$as_fechas = $this->fun->uf_convertirdatetobd($as_fechas);	
		if (!empty($ls_orden))
		{
			if ($ls_orden=="F")
			{
				$ls_order=" fecmov";
			}
			else
			{
				$ls_order=" cod_pro, ced_bene";
			}
		}
		$ls_sql="select scb_movbco.codban, scb_movbco.ctaban, scb_movbco.numdoc, scb_movbco.codope, scb_movbco.estmov, 
					   scb_movbco.cod_pro, scb_movbco.ced_bene,'ANTICIPO' as tipo, scb_movbco.estant,
					   scb_movbco.fecmov, scb_movbco_scg.debhab, scb_movbco_scg.monto, scb_movbco_scg.scg_cuenta, 
					   scb_movbco_anticipo.monsal,scb_movbco.conmov,scb_movbco_scg.estmov, 
					   (select rpc_proveedor.nompro from rpc_proveedor 
												   where rpc_proveedor.codemp= scb_movbco.codemp
													 and rpc_proveedor.cod_pro= scb_movbco.cod_pro) as nompro,
					   (select rpc_beneficiario.nombene from rpc_beneficiario 
												   where rpc_beneficiario.codemp= scb_movbco.codemp
													 and rpc_beneficiario.ced_bene= scb_movbco.ced_bene) as nombene
				
				  from scb_movbco
				  left join scb_movbco_scg on (scb_movbco_scg.codemp = scb_movbco.codemp
									 and  scb_movbco_scg.codban = scb_movbco.codban
							 and  scb_movbco_scg.ctaban = scb_movbco.ctaban
							 and  scb_movbco_scg.numdoc = scb_movbco.numdoc
							 and  scb_movbco_scg.codope = scb_movbco.codope
							 and  scb_movbco_scg.estmov = scb_movbco.estmov)
				 left join scb_movbco_anticipo on (scb_movbco_anticipo.codemp = scb_movbco_scg.codemp
								 and  scb_movbco_anticipo.codban = scb_movbco_scg.codban
							 and  scb_movbco_anticipo.ctaban = scb_movbco_scg.ctaban
							 and  scb_movbco_anticipo.numdoc = scb_movbco_scg.numdoc
							 and  scb_movbco_anticipo.codope = scb_movbco_scg.codope
							 and  scb_movbco_anticipo.estmov = scb_movbco_scg.estmov
							 and  scb_movbco_anticipo.sc_cuenta = scb_movbco_scg.scg_cuenta) 
				  where scb_movbco.cod_pro between '".$as_cod_prodes."' and '".$as_cod_prohas."' 
                   and scb_movbco.ced_bene  between '".$as_cedbebdes."' and '".$as_cedbebhas."' 
				   and scb_movbco.fecmov between '".$as_fecdes."' and '".$as_fechas."'
				   and scb_movbco.estant='1'
				   and scb_movbco_scg.debhab='D' 
				 UNION
				 select scb_movbco.codban, scb_movbco.ctaban, scb_movbco.numdoc, scb_movbco.codope, scb_movbco.estmov, 
					scb_movbco.cod_pro, scb_movbco.ced_bene, 'AMORTIZACION' as tipo, scb_movbco.estant,
					scb_movbco.fecmov, scb_movbco_scg.debhab, scb_movbco_scg.monto,
					scb_movbco_scg.scg_cuenta,scb_movbco_anticipo.monsal,scb_movbco.conmov,scb_movbco_scg.estmov,
					(select rpc_proveedor.nompro from rpc_proveedor 
												   where rpc_proveedor.codemp= scb_movbco.codemp
													 and rpc_proveedor.cod_pro= scb_movbco.cod_pro) as nompro,
						(select rpc_beneficiario.nombene from rpc_beneficiario 
												   where rpc_beneficiario.codemp= scb_movbco.codemp
													 and rpc_beneficiario.ced_bene= scb_movbco.ced_bene) as nombene
				  from scb_movbco 
				  left join scb_movbco_scg on (scb_movbco_scg.codemp = scb_movbco.codemp
							 and  scb_movbco_scg.codban = scb_movbco.codban
							 and  scb_movbco_scg.ctaban = scb_movbco.ctaban
							 and  scb_movbco_scg.numdoc = scb_movbco.numdoc
							 and  scb_movbco_scg.codope = scb_movbco.codope
							 and  scb_movbco_scg.estmov = scb_movbco.estmov) 
				join scb_movbco_anticipo on (scb_movbco_anticipo.numdoc = scb_movbco.docant
                         and  scb_movbco_anticipo.sc_cuenta = scb_movbco_scg.scg_cuenta)   
				 where scb_movbco.cod_pro between '".$as_cod_prodes."' and '".$as_cod_prohas."' 
                   and scb_movbco.ced_bene  between '".$as_cedbebdes."' and '".$as_cedbebhas."' 
				   and scb_movbco.fecmov between '".$as_fecdes."' and '".$as_fechas."'
				   and scb_movbco.estant='2'
				   and scb_movbco_scg.debhab='H'  
				 order by ".$ls_order.", estant";  
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
		   $lb_valido=true;
		   while($row=$this->SQL->fetch_row($rs_data))
		   {
		      	$this->ds_data->insertRow("numdoc",$row["numdoc"]);
				$this->ds_data->insertRow("fecha",$row["fecmov"]);
				$this->ds_data->insertRow("tipo",$row["tipo"]);
				$this->ds_data->insertRow("concepto",$row["conmov"]);	
				$this->ds_data->insertRow("debhab",$row["debhab"]);	
				$this->ds_data->insertRow("monto",$row["monto"]);	
				$this->ds_data->insertRow("saldo",$row["monsal"]);
				$this->ds_data->insertRow("codpro",$row["cod_pro"]);
				$this->ds_data->insertRow("cedbene",$row["ced_bene"]);
				$this->ds_data->insertRow("nompro",$row["nompro"]);	
				$this->ds_data->insertRow("nombene",$row["nombene"]);
				$this->ds_data->insertRow("estmov",$row["estmov"]);									   
						
			}// fin del while
			$this->SQL->free_result($rs_data); 			
		}// fin del else
		return $lb_valido;
	 }// fin select_anticipos_amortizacion
//-------------------------------------------------------------------------------------------------------------------------------------
}// fin de la clase class_report
?>