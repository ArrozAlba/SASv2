<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("sigesp_cxp_c_aprobacionnotas.php");
	$io_aprobacion=new sigesp_cxp_c_aprobacionnotas('../../');
	// tipo de SEP si es de BIENES  de SERVICIOS
	$ls_tipo=$io_funciones_cxp->uf_obtenervalor("tiponota","--");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	// numero de sep
	$ls_numncnd=$io_funciones_cxp->uf_obtenervalor("numncnd","");
	$ls_numsol=$io_funciones_cxp->uf_obtenervalor("numsol","");
	// codigo de unidad ejecutora
	$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
	// fecha(registro) de inicio de busqueda
	$ld_fecregdes=$io_funciones_cxp->uf_obtenervalor("fecregdes","");
	// fecha(registro) de fin de busqueda
	$ld_fecreghas=$io_funciones_cxp->uf_obtenervalor("fecreghas","");
	// codigo de proveedor/beneficiario
	$ls_proben=$io_funciones_cxp->uf_obtenervalor("proben","");
	// tipo proveedor/beneficiario
	$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_cxp->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			$ls_titulo="Notas de Debito/ Credito";
			uf_print_notas($ls_numncnd,$ls_numrecdoc,$ls_numsol,$ls_tipo,$ld_fecregdes,$ld_fecreghas,$ls_tipproben,$ls_proben,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_notas($as_numncnd,$as_numrecdoc,$as_numsol,$as_tipo,$ad_fecregdes,$ad_fecreghas,$as_tipproben,$as_proben,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_notas
		//		   Access: private
		//		 Argument: as_numsol        // Numero de la solicitud de ejecucion presupuestaria
		//                 as_tipo          // Indica si es de Bienes o de servicios
		//                 as_coduniadm     // Codigo de la Unidad Ejecutora
		//                 ad_fecregdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipproben     // Tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Mtodo que impirme el grid de las solicitudes a ser aprobadas o para reversar la aprovacin
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("class_funciones_cxp.php");
		$io_cxp= new class_funciones_cxp();
		global $io_grid, $io_funciones_cxp, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lo_title[1]="<input type=checkbox name=chkall id=chkall onClick='javascript:ue_chequear_all();'>";
		$lo_title[2]="Numero de Nota";
		$lo_title[3]="Numero de Recepcion";
		$lo_title[4]="Estatus de Aprobacion";
		$lo_title[5]="Proveedor/Beneficiaro";
		$lo_title[6]="Monto";

		//$lo_title[8]="Monto";
		if(($as_tipo=="--")||($as_tipo=='T'))
		{
			$as_tipo="";
		}	
		else
		{
			$as_tipo=" AND cxp_sol_dc.codope='".$as_tipo."' ";
		}
		$ad_fecregdes=$io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$io_funciones->uf_convertirdatetobd($ad_fecreghas);
		if($as_proben!="")
		{
			if($as_tipproben=='P')
			{
				$ls_aux_sql=	" AND cxp_sol_dc.cod_pro='".$as_proben."' ";
			}
			else
			{
				$ls_aux_sql=	" AND cxp_sol_dc.ced_bene='".$as_proben."' ";
			}
		}
		else
		{	
			$ls_aux_sql=	" ";
		}
		if($as_numsol!="")
		{
			$ls_aux_sql=	$ls_aux_sql." AND cxp_sol_dc.numsol like '%".$as_numsol."%' ";
		}
		if($as_numrecdoc!="")
		{
			$ls_aux_sql=	$ls_aux_sql." AND cxp_sol_dc.numrecdoc like '%".$as_numrecdoc."%' ";
		}
		if($as_numncnd!="")
		{
			$ls_aux_sql=	$ls_aux_sql." AND cxp_sol_dc.numdc like '%".$as_numncnd."%' ";
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_aux=" CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene) ";
		}
		else
		{
			$ls_aux=" (rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene) ";
		}
		$ls_sql="SELECT cxp_sol_dc.*,".
				"       (CASE cxp_sol_dc.cod_pro WHEN '----------' THEN ".$ls_aux." ELSE rpc_proveedor.nompro END) as nombre,".
				"		(SELECT count(cxp_dc_spg.numrecdoc) ".
				"		   FROM cxp_dc_spg ".
				"		  WHERE cxp_sol_dc.codemp=cxp_dc_spg.codemp ".
				"			AND cxp_sol_dc.numsol=cxp_dc_spg.numsol ".
				"			AND cxp_sol_dc.numrecdoc=cxp_dc_spg.numrecdoc ".
				"			AND cxp_sol_dc.codtipdoc=cxp_dc_spg.codtipdoc ".
				"			AND cxp_sol_dc.codope=cxp_dc_spg.codope ".
				"			AND cxp_sol_dc.numdc=cxp_dc_spg.numdc ".
				"			AND cxp_sol_dc.cod_pro=cxp_dc_spg.cod_pro".
				"			AND cxp_sol_dc.ced_bene=cxp_dc_spg.ced_bene) as rowspg,".
				"		(SELECT count(cxp_dc_scg.numrecdoc) ".
				"		   FROM cxp_dc_scg ".
				"		  WHERE cxp_sol_dc.codemp=cxp_dc_scg.codemp ".
				"			AND cxp_sol_dc.numsol=cxp_dc_scg.numsol ".
				"			AND cxp_sol_dc.numrecdoc=cxp_dc_scg.numrecdoc ".
				"			AND cxp_sol_dc.codtipdoc=cxp_dc_scg.codtipdoc ".
				"			AND cxp_sol_dc.codope=cxp_dc_scg.codope ".
				"			AND cxp_sol_dc.numdc=cxp_dc_scg.numdc ".
				"			AND cxp_sol_dc.cod_pro=cxp_dc_scg.cod_pro".
				"			AND cxp_sol_dc.ced_bene=cxp_dc_scg.ced_bene) as rowscg".
				"  FROM cxp_sol_dc,rpc_proveedor,rpc_beneficiario ".
				" WHERE cxp_sol_dc.codemp='".$ls_codemp."'".
				"   AND cxp_sol_dc.fecope BETWEEN '".$ad_fecregdes."' AND '".$ad_fecreghas."' ".$ls_aux_sql."".
				"   AND cxp_sol_dc.cod_pro=rpc_proveedor.cod_pro".
				"   AND cxp_sol_dc.ced_bene=rpc_beneficiario.ced_bene ".
				"   AND cxp_sol_dc.codemp=rpc_proveedor.codemp".
				"   AND cxp_sol_dc.codemp=rpc_beneficiario.codemp".
				"   AND cxp_sol_dc.estapr='".$as_tipooperacion."' ".$as_tipo; 
		
		$rs_data=$io_aprobacion->io_sql->select($ls_sql);
		$li_fila=0;
		if($rs_data!=false)
		{
			while($row=$io_aprobacion->io_sql->fetch_row($rs_data))
			{
				$lb_imprimir=true;
				$ls_numsol=$row["numsol"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_numncnd=$row["numdc"];		
				$ls_codope=$row["codope"];		
				$ls_codtipdoc=$row["codtipdoc"];			
				$ld_fecregsol=$io_funciones->uf_formatovalidofecha($row["fecope"]);
				$ls_estsol=$row["estnotadc"];
				$ls_estapro=$row["estapr"];
				$li_rowspg=$row["rowspg"];
				$li_rowscg=$row["rowscg"];
				$ls_proben=utf8_encode($row["nombre"]);
				$li_monto=number_format($row["monto"],2,',','.');
				if($ls_estapro==0)
				{
					$ls_estatus="No Aprobada";
				}
				else
				{
					$ls_estatus="Aprobada";
				}
				if($li_rowspg>=1)
				{
					$lb_valido=$io_cxp->uf_verificar_cierre_spg("../../",$ls_estciespg);
					if($ls_estciespg=="1")
					{
						$lb_imprimir=false;
					}
				}
				if($li_rowscg>=1)
				{
					$lb_valido=$io_cxp->uf_verificar_cierre_scg("../../",$ls_estciescg);
					if($ls_estciescg=="1")
					{
						$lb_imprimir=false;
					}
				}
				if($lb_imprimir)
				{
					$li_fila=$li_fila + 1;
					$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
					$lo_object[$li_fila][2]="<input type=text name=txtnumncnd".$li_fila." id=txtnumncnd".$li_fila." class=sin-borde style=text-align:center   size=18 value='".$ls_numncnd."' readonly>"; 
					$lo_object[$li_fila][3]="<input type=text name=txtnumrecdoc".$li_fila." id=txtnumrecdoc".$li_fila." class=sin-borde style=text-align:center   size=18 value='".$ls_numrecdoc."' readonly>"; 
					$lo_object[$li_fila][4]="<input type=text name=txtnumsol".$li_fila."    id=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=18 value='".$ls_numsol."'    readonly>".
											"<input type=hidden name=txtfecregsol".$li_fila." id=txtfecregsol".$li_fila."  value='".$ld_fecregsol."'>".
											"<input type=hidden name=txtcodope".$li_fila." id=txtcodope".$li_fila." value='".$ls_codope."'>".
											"<input type=hidden name=txtcodtipdoc".$li_fila." id=txtcodtipdoc".$li_fila." value='".$ls_codtipdoc."' >";
					$lo_object[$li_fila][5]="<input type=text name=txtproben".$li_fila."    id=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=25 value='".$ls_proben."'    readonly>"; 
					$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     id=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=22 value='".$li_monto."' 	  readonly>";
				}
				$li_total=$li_fila;
			}
		}
		if($li_fila==0)
		{
			$io_aprobacion->io_mensajes->message("No se encontraron resultados");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
			$lo_object[$li_fila][2]="<input type=text name=txtnumncnd".$li_fila." id=txtnumncnd".$li_fila." class=sin-borde style=text-align:center   size=18 value='' readonly>"; 
			$lo_object[$li_fila][3]="<input type=text name=txtnumrecdoc".$li_fila." id=txtnumrecdoc".$li_fila." class=sin-borde style=text-align:center   size=18 value='' readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtnumsol".$li_fila."    id=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=18 value=''    readonly>".
									"<input type=hidden name=txtfecregsol".$li_fila." id=txtfecregsol".$li_fila."  value=''>".
									"<input type=hidden name=txtcodope".$li_fila." id=txtcodope".$li_fila." value=''>".
									"<input type=hidden name=txtcodtipdoc".$li_fila." id=txtcodtipdoc".$li_fila." value='' >";
			$lo_object[$li_fila][5]="<input type=text name=txtproben".$li_fila."    id=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=25 value=''    readonly>"; 
			$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     id=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=22 value='' 	  readonly>";
			$li_total=0;
		}

		$io_grid->make_gridscroll($li_fila,$lo_title,$lo_object,725,"Notas de Debito / Credito","gridsolicitudes",150);
		print "<input name=totrow type=hidden id=totrow value=$li_total>";
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
?>