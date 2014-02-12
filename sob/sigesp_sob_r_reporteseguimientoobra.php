<?Php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_r_reporteseguimientoobra.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Reporte de Seguimiento de Obras</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/reportes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #006699}
.style6 {color: #000000}


-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="19" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><!--a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a--><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><!--a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a--><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>


<?Php

	
	require_once("class_folder/sigesp_sob_c_reportes.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once ("class_folder/sigesp_sob_c_reportes.php");
	$io_reporte=new sigesp_sob_c_reportes();
    $io_funsob= new sigesp_sob_c_funciones_sob(); 
	$io_funcion=new class_funciones();	
	$io_datastore=new class_datastore();
    $io_msg=new class_mensajes();
	$ls_codemp=$la_datemp["codemp"];

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_feccreobrdesde=$_POST["txtfeccreobrdesde"];
		$ls_feccreobrhasta=$_POST["txtfeccreobrhasta"];
		$ls_feciniobr=$_POST["txtfeciniobr"];
		$ls_parfeciniobr=$_POST["cmbparfeciniobr"]; 
        $ls_fecfinobr=$_POST["txtfecfinobr"];
		$ls_parfecfinobr=$_POST["cmbparfecfinobr"];   
        $ls_codpro=$_POST["txtcodpro"]; 
        $ls_nompro=$_POST["txtnompro"];
		$ls_resobr=$_POST["txtresobr"];
		$ls_codest=$_POST["cmbestado"];
		$ls_codmun=$_POST["cmbmunicipio"];
		$ls_codpar=$_POST["cmbparroquia"];
		$ls_codcom=$_POST["cmbcomunidad"];
		$ls_codfuefin=$_POST["txtcodfuefin"];
		$ls_denfuefin=$_POST["txtdenfuefin"];  
		$ls_parmonto=$_POST["cmbparmonto"];
		$ls_monto=$_POST["txtmonto"];  
		if($ls_monto=="")
			$ls_monto="0,00";
		$ls_consulta=$_POST["consulta"];  
		$ls_desobr=$_POST["txtdesobr"];
		$ls_hidlista1=$_POST["hidlista1"];
		$ls_hidlista2=$_POST["hidlista2"];
		$ls_hidlista3=$_POST["hidlista3"];
		$ls_hidobra=$_POST["hidobra"];
		$ls_hidanticipo=$_POST["hidanticipo"];
		$ls_hidcontrato=$_POST["hidcontrato"];
		$ls_hidvaluacion=$_POST["hidvaluacion"];
		$ls_hidasignacion=$_POST["hidasignacion"];
		$ls_hidactas=$_POST["hidactas"];
		$ls_tituloencabezado=$_POST["txttituloencabezado"];
		$ls_scroll=$_POST["hidscroll"];
		$ls_hidtabla=$_POST["hidtabla"];
		$ls_codins=$_POST["txtcodins"];
		$ls_nomins=$_POST["txtnomins"];
		$ls_feccondesde=$_POST["txtfeccondesde"];
		$ls_fecconhasta=$_POST["txtfecconhasta"];
		$ls_codcontrato=$_POST["txtcodcon"];
		$ls_precon=$_POST["txtprecon"];
		$ls_fecinicon=$_POST["txtfecinicon"];
		$ls_cmbfecinicon=$_POST["cmbfecinicon"];
		$ls_fecfincon=$_POST["txtfecfincon"];
		$ls_cmbfecfincon=$_POST["cmbfecfincon"];
		$ls_moncon=$_POST["txtmoncon"];
		$ls_cmbprepar=$_POST["cmbmoncon"];
		$ls_cmbestcon=$_POST["cmbestcon"];
		$ls_nomcontratista=$_POST["txtnomcontratista"];
		$ls_codcontratista=$_POST["txtcodcontratista"];
		$ls_codpai=$_POST["cmbpais"];
	}
	else
	{
		$ls_codpai="001";
		$ls_operacion="";
		$ls_tituloencabezado="";
		$ls_feccreobrdesde=""; 
		$ls_feccreobrhasta=""; 
        $ls_feciniobr="";
        $ls_fecfinobr="";  
        $ls_codpro=""; 
        $ls_nompro="";
		$ls_resobr="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codcom="";
		$ls_codfuefin="";
		$ls_denfuefin="";  
		$ls_parmonto="";
		$ls_cmbprepar="";
		$ls_monto="0,00";
		$ls_parfeccreobr=""; 
        $ls_parfeciniobr=""; 
        $ls_parfecfinobr="";
		$ls_consulta="";  
		$ls_desobr="";
		$ls_scroll=0;
		$ls_hidtabla="";
		$ls_codins="";
		$ls_nomins="";
		$ls_feccondesde="";
		$ls_fecconhasta="";
		$ls_codcontrato="";
		$ls_precon="";
		$ls_fecinicon="";
		$ls_cmbfecinicon="";
		$ls_fecfincon="";
		$ls_cmbfecfincon="";
		$ls_moncon="0,00";
		$ls_cmbmoncon="";
		$ls_cmbestcon="";
		$ls_nomcontratista="";
		$ls_codcontratista="";
		$la_parametro[1][1]="";		
		$ls_hidlista1="";/*"Código de la Obra?o.codobr-1?Comunidad?c.nomcom-5?Descripción de la Obra?o.desobr-1?Dirección de la Obra?o.dirobr-1?Estado (Ubicación)?e.desest-2?
							Fecha de Registro de la Obra?o.feccreobr-1?Fecha de Finalización de la Obra?o.fecfinobr-1?Fecha de Inicio de la Obra?
							o.feciniobr-1?Monto Total de la Obra?o.monto-1?Municipio?m.denmun-3?Organismo Ejecutor?pro.nompro as orgejec-7?
							Parroquia?p.denpar-4?Responsable de la Obra?o.resobr-1?Sistema Constructivo?sc.nomsiscon-11?Tenencia de la Tierra
							?t.nomten-8?Tipo de Estructura?te.nomtipest-10?Tipo de Obra?tob.nomtob-9";*/
		$ls_hidlista2="Código (Contrato)?con.codcon,con.precon-1";
		$ls_hidlista3="Campos de la Obra?hidobra?
					Campos de la Asignación?hidasignacion?
					Campos del Contrato?hidcontrato?
					Campos del Anticipo?hidanticipo?
					Campos de la Valuación?hidvaluacion?
					Campos de las Actas?hidactas";
		$ls_hidobra="Código (Obra)?o.codobr-3?
					Descripción (Obra)?o.desobr-3?
					Fecha de Registro (Obra)?o.feccreobr-3?
					Fecha de Inicio (Obra)?o.feciniobr-3?
					Fecha de Finalización (Obra)?o.fecfinobr-3?
					Monto (Obra)?o.monto as montoobra-3?
					Organismo Ejecutor (Obra)?pro.nompro as orgejec-10?
					Responsable (Obra)?o.resobr-3?
					Tenencia de la Tierra (Obra)?t.nomten-11?
					Sistema Constructivo (Obra)?sc.nomsiscon-14?
					Tipo (Obra)?tob.nomtob-12?
					Tipo de Estructura (Obra)?te.nomtipest-13?
					Estado (Obra)?e.desest-4?
					Municipio (Obra)?m.denmun-5?
					Parroquia (Obra)?p.denpar-6?
					Comunidad (Obra)?c.nomcom-7?
					Dirección (Obra)?o.dirobr-3?
					Fuentes de Financiamiento (Obra)?fuentesfinanciamiento";
					
		$ls_hidasignacion="Empresa Contratista (Asignación)?asi.cod_pro-2?
							Empresa Inspectora (Asignación)?asi.cod_pro_ins-2";	
		$ls_hidcontrato="Tipo de Contrato (Contrato)?tcon.nomtco-17?
						 Fecha de Registro (Contrato)?con.feccon-1?
						 Fecha de Inicio (Contrato)?con.fecinicon-1?
						 Fecha de Fin (Contrato)?con.fecfincon-1?
						 Plazo de Duración (Contrato)?con.placon,con.placonuni-1?
						 Monto (Contrato)?con.monto as montocontrato-1?
						 Monto Límite (Contrato)?con.monmaxcon-1?
						 Observación (Contrato)?con.obscon-1?
						 Status (Contrato)?con.estcon-1";
						 
		$ls_hidanticipo="Monto Anticipado a la Fecha (Anticipo)?anticipo";
		
		$ls_hidvaluacion=" % Ejecución Física a la Fecha (Valuación)?ejecucionfisica?
							% Ejecución Financiera a la Fecha (Valuación)?ejecucionfinanciera?
							% Amortización Anticipos a la Fecha (Valuación)?amortizacionanticipo";
		$ls_hidactas="Fecha Real de Inicio (Actas)?con.fecinireacon-1?
					Fecha Real de Finalización (Actas)?con.fecfinreacon-1?
					Fecha de Paralización (Actas)?fechaparalizacion?
					Motivo de Paralización (Actas)?motivoparalizacion?
					Fecha de Reanudación (Actas)?fechareanudacion?
					Fecha de Finalización en Prórroga (Actas)?fechaprorroga?
					Motivo de Prórroga (Actas)?motivoprorroga";
		
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_imprimir")
	{
		if(($io_funsob->uf_convertir_cadenanumero($ls_monto))==0)
			$ls_monto="";			
		if(($io_funsob->uf_convertir_cadenanumero($ls_moncon))==0)
			$ls_moncon="";			
		
		$la_parametro[1][1]="o.feccreobr";
		$la_parametro[1][2]=$io_funcion->uf_convertirdatetobd($ls_feccreobrdesde);
		$la_parametro[1][3]=">=";		
		$la_parametro[1][4]=3;
		$la_parametro[2][1]="o.feccreobr";
		$la_parametro[2][2]=$io_funcion->uf_convertirdatetobd($ls_feccreobrhasta);
		$la_parametro[2][3]="<=";
		$la_parametro[2][4]=3;		
		$la_parametro[3][1]="o.feciniobr";
		$la_parametro[3][2]=$io_funcion->uf_convertirdatetobd($ls_feciniobr);
		$la_parametro[3][3]=$ls_parfeciniobr;
		$la_parametro[3][4]=3;		
        $la_parametro[4][1]="o.fecfinobr";
		$la_parametro[4][2]=$io_funcion->uf_convertirdatetobd($ls_fecfinobr);
		$la_parametro[4][3]=$ls_parfecfinobr;
		$la_parametro[4][4]=3;		
		$la_parametro[5][1]="o.codpro";
		$la_parametro[5][2]="%".$ls_codpro."%";
		$la_parametro[5][3]=" like ";
		$la_parametro[5][4]=3;		
		$la_parametro[6][1]="o.resobr";
		$la_parametro[6][2]="%".$ls_resobr."%";
		$la_parametro[6][3]=" like ";
		$la_parametro[6][4]=3;	
		$la_parametro[7][1]="o.codest";
		$la_parametro[7][2]=$ls_codest;
		$la_parametro[7][3]="=";
		$la_parametro[7][4]=3;
		$la_parametro[8][1]="o.codmun";
		$la_parametro[8][2]=$ls_codmun;
		$la_parametro[8][3]="=";
		$la_parametro[8][4]=3;
		$la_parametro[9][1]="o.codpar";
		$la_parametro[9][2]=$ls_codpar;
		$la_parametro[9][3]="=";
		$la_parametro[9][4]=3;
		$la_parametro[10][1]="o.codcom";
		$la_parametro[10][2]=$ls_codcom;
		$la_parametro[10][3]="=";
		$la_parametro[10][4]=3;
		$la_parametro[11][1]="fo.codfuefin";
		$la_parametro[11][2]=$ls_codfuefin;
		$la_parametro[11][3]="=";
		$la_parametro[11][4]=9;		
		$la_parametro[12][1]="o.monto";
		$la_parametro[12][2]=$io_funsob->uf_convertir_cadenanumero($ls_monto);
		$la_parametro[12][3]=$ls_parmonto;
		$la_parametro[12][4]=3;       
		$la_parametro[13][1]="o.desobr";
		$la_parametro[13][2]="%".$ls_desobr."%";
		$la_parametro[13][3]=" like ";
		$la_parametro[13][4]=3;		
		$la_parametro[14][1]="asi.cod_pro";
		$la_parametro[14][2]="%".$ls_codcontratista."%";
		$la_parametro[14][3]=" like ";
		$la_parametro[14][4]=2;
		$la_parametro[15][1]="asi.cod_pro_ins";
		$la_parametro[15][2]="%".$ls_codins."%";
		$la_parametro[15][3]=" like ";
		$la_parametro[15][4]=2;
		$la_parametro[16][1]="con.feccon";
		$la_parametro[16][2]=$io_funcion->uf_convertirdatetobd($ls_feccondesde);
		$la_parametro[16][3]=">=";
		$la_parametro[16][4]=1;
		$la_parametro[17][1]="con.feccon";
		$la_parametro[17][2]=$io_funcion->uf_convertirdatetobd($ls_fecconhasta);
		$la_parametro[17][3]="<=";
		$la_parametro[17][4]=1;
		$la_parametro[18][1]="con.codcon";
		$la_parametro[18][2]="%".$ls_codcontrato."%";
		$la_parametro[18][3]=" like ";
		$la_parametro[18][4]=1;
		$la_parametro[19][1]="con.precon";
		$la_parametro[19][2]="%".$ls_precon."%";
		$la_parametro[19][3]=" like ";
		$la_parametro[19][4]=1;
		$la_parametro[20][1]="con.fecinicon";
		$la_parametro[20][2]=$io_funcion->uf_convertirdatetobd($ls_fecinicon);
		$la_parametro[20][3]=$ls_cmbfecinicon;
		$la_parametro[20][4]=1;
		$la_parametro[21][1]="con.fecfincon";
		$la_parametro[21][2]=$io_funcion->uf_convertirdatetobd($ls_fecfincon);
		$la_parametro[21][3]=$ls_cmbfecfincon;
		$la_parametro[21][4]=1;
		$la_parametro[22][1]="con.monto";
		$la_parametro[22][2]=$io_funsob->uf_convertir_cadenanumero($ls_moncon);
		$la_parametro[22][3]=$ls_cmbprepar;
		$la_parametro[22][4]=1;
		$la_parametro[23][1]="con.estcon";
		$la_parametro[23][2]=$ls_cmbestcon;
		$la_parametro[23][3]="=";
		$la_parametro[23][4]=1;
		/*/$la_parametro[24][1]="o.monto";
		$la_parametro[24][2]=$io_funsob->uf_convertir_cadenanumero($ls_monto);
		$la_parametro[24][3]=$ls_cmbprepar;
		$la_parametro[24][4]=3;/*/       	
		
		
//-------------------------------------Campos a ser mostrados en el reporte---------------------------------------//
		$ls_cadena=$_POST["hiddata"];
		$la_arreglo=explode("-",$ls_cadena);		
		$la_camposespeciales=array();
		$la_camposcadena=array();
		$la_arregloaux=array();
		for ($li_i=0;$li_i<count($la_arreglo);$li_i++)
		{
			if($la_arreglo[$li_i]=="ejecucionfisica" || 
				$la_arreglo[$li_i]=="ejecucionfinanciera" || 
				$la_arreglo[$li_i]=="anticipo" || 
				$la_arreglo[$li_i]=="amortizacionanticipo" || 
				$la_arreglo[$li_i]=="fuentesfinanciamiento" ||
				$la_arreglo[$li_i]=="fechaparalizacion" ||
				$la_arreglo[$li_i]=="motivoparalizacion" ||
				$la_arreglo[$li_i]=="fechareanudacion" ||
				$la_arreglo[$li_i]=="fechaprorroga" ||
				$la_arreglo[$li_i]=="motivoprorroga")
			{
				array_push($la_camposespeciales,$la_arreglo[$li_i]);				
			}
			else
			{
				array_push($la_camposcadena,$la_arreglo[$li_i]);
			}			
			if(strlen($la_arreglo[$li_i])>2)
			{
				array_push($la_arregloaux,$la_arreglo[$li_i]);
			}
		}	
		$ls_cadena=implode("-",$la_camposcadena);		
		$la_salida=$io_funsob-> uf_decodificardata("-",$ls_cadena,$li_index);	
		
		$la_tabla[1][1]="sob_contrato con";
		$la_tabla[1][2]="con.codemp=$ls_codemp AND con.estcon<>3" ;
		$la_tabla[1][3]="0";
		$la_tabla[1][4]=0;
		$la_tabla[2][1]="sob_asignacion asi";
		$la_tabla[2][2]="asi.codasi=con.codasi";
		$la_tabla[2][3]="0";
		$la_tabla[2][4]=1;
		$la_tabla[3][1]="sob_obra o";
		$la_tabla[3][2]="asi.codobr=o.codobr";
		$la_tabla[3][3]="0";
		$la_tabla[3][4]=2;
		$la_tabla[4][1]="sigesp_estados e";
		$la_tabla[4][2]="o.codest=e.codest AND e.codpai='001'";
		$la_tabla[4][3]="0";
		$la_tabla[4][4]=3;
		$la_tabla[5][1]="sigesp_municipio m";
		$la_tabla[5][2]="o.codest=m.codest  AND  o.codmun=m.codmun AND m.codpai='001'";
		$la_tabla[5][3]="0";
		$la_tabla[5][4]=4;
		$la_tabla[6][1]="sigesp_parroquia p";
		$la_tabla[6][2]="o.codest=p.codest AND o.codmun=p.codmun AND o.codpar=p.codpar AND p.codpai='001'";
		$la_tabla[6][3]="0";
		$la_tabla[6][4]=5;
		$la_tabla[7][1]="sigesp_comunidad c";
		$la_tabla[7][2]="o.codest=c.codest AND o.codmun=c.codmun AND o.codpar=c.codpar AND o.codcom=c.codcom AND c.codpai='001'";
		$la_tabla[7][3]="0";
		$la_tabla[7][4]=6;
		$la_tabla[8][1]="rpc_proveedor prov";
		$la_tabla[8][2]="asi.cod_pro=prov.cod_pro AND asi.cod_pro_ins=prov.codpro";
		$la_tabla[8][3]="0";
		$la_tabla[8][4]="2";
		$la_tabla[9][1]="sob_fuentefinanciamientoobra fo";
		$la_tabla[9][2]="fo.codobr=o.codobr";
		$la_tabla[9][3]="0";
		$la_tabla[9][4]=3;	
		$la_tabla[10][1]="sob_propietario pro";
		$la_tabla[10][2]="o.codemp=pro.codemp AND pro.codpro=o.codpro";
		$la_tabla[10][3]="0";
		$la_tabla[10][4]="3";		
		$la_tabla[11][1]="sob_tenencia t";
		$la_tabla[11][2]="o.codten=t.codten";
		$la_tabla[11][3]="0";
		$la_tabla[11][4]="3";		
		$la_tabla[12][1]="sob_tipoobra tob";
		$la_tabla[12][2]="o.codemp=tob.codemp AND o.codtob=tob.codtob";
		$la_tabla[12][3]="0";
		$la_tabla[12][4]="3";		
		$la_tabla[13][1]="sob_tipoestructura te";
		$la_tabla[13][2]="o.codemp=te.codemp AND o.codtipest=te.codtipest";
		$la_tabla[13][3]="0";
		$la_tabla[13][4]="3";
		$la_tabla[14][1]="sob_sistemaconstructivo sc";
		$la_tabla[14][2]="o.codemp=sc.codemp AND o.codsiscon=sc.codsiscon";
		$la_tabla[14][3]="0";
		$la_tabla[14][4]="3";
		$la_tabla[15][1]="sob_valuacion val";
		$la_tabla[15][2]="con.codemp=val.codemp AND con.codcon=val.codcon";
		$la_tabla[15][3]="0";
		$la_tabla[15][4]="1";
		$la_tabla[16][1]="sob_anticipo ant";
		$la_tabla[16][2]="ant.codemp=con.codemp AND con.codcon=ant.codcon";
		$la_tabla[16][3]="0";
		$la_tabla[16][4]="1";
		$la_tabla[17][1]="sob_tipocontrato tcon";
		$la_tabla[17][2]="con.codtco=tcon.codtco";
		$la_tabla[17][3]="0";
		$la_tabla[17][4]="1";
		
		
		
		$ls_cadena=$io_reporte->uf_evalconsulta($la_salida,$li_index,$la_tabla,17,$la_parametro,23);
		$ls_cadena=$ls_cadena." ORDER BY con.codcon ASC";
		$lb_valido=$io_reporte->uf_obtenerdata ($ls_cadena,$la_data);
    	//--------------Obteniendo las columnas del reporte en el orden solicitado------------------//
		if($lb_valido)
		{
			$la_keys=array_keys($la_data);
			$la_keysaux=array();
			for($li_i=0;$li_i<count($la_keys);$li_i++)
			{
				if($la_keys[$li_i]!="precon" && $la_keys[$li_i]!="placonuni")
				{
					array_push($la_keysaux,$la_keys[$li_i]);
				}
			}
			$la_keysfinal=array();
			for ($li_i=0;$li_i<count($la_camposespeciales);$li_i++)
			{
				switch($la_camposespeciales[$li_i])
				{
					case "anticipo":
						$ls_campo="totalanticipo";
					break;
					case "ejecucionfisica":
						$ls_campo="totalejecfisic";
					break;
					case "amortizacionanticipo":
						$ls_campo="amortizacionanticipo";
					break;
					case "fuentesfinanciamiento":
						$ls_campo="fuentesfinanciemiento";
					break;
					case "ejecucionfinanciera":
						$ls_campo="totalejecfin";
					break;
					default:
						$ls_campo=$la_camposespeciales[$li_i];						
				}
				$li_fila=array_search($la_camposespeciales[$li_i],$la_arregloaux);			
				for($li_k=0;$li_k<=count($la_keysaux);$li_k++)
				{			
					if($li_k==$li_fila)
					{
						array_push($la_keysfinal,$ls_campo);
						for($li_h=$li_k;$li_h<count($la_keysaux);$li_h++)
							array_push($la_keysfinal,$la_keysaux[$li_h]);		
						break;
					}
					else
					{
						array_push($la_keysfinal,$la_keysaux[$li_k]);					
					}				
				}			
				$la_keysaux=$la_keysfinal;
				$la_keysfinal=array();			
			}
		}
		//-----------------------------data adicional-------------------------------------------------//
		if($lb_valido)
		{
			$li_filas=count($la_camposespeciales);
			$li_filasdata=(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
			for($li_i=0;$li_i<$li_filas;$li_i++)		
			{
				$ls_calculo=$la_camposespeciales[$li_i];			
				switch($ls_calculo)
				{
					case "anticipo":
						include_once("class_folder/sigesp_sob_c_anticipo.php");
						$io_anticipo=new sigesp_sob_c_anticipo();
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							$ls_codcon=$la_data["codcon"][$li_j];
							$lb_valido=$io_anticipo->uf_calcular_montoanticipo($ls_codcon,$ld_montoanticipo);
							if($lb_valido)
							{
								if($ld_montoanticipo!=0)
									$la_data["totalanticipo"][$li_j]=$io_funsob->uf_convertir_numerocadena($ld_montoanticipo);		
								else
									$la_data["totalanticipo"][$li_j]="---";	
							}	
						}
					break;
					case "amortizacionanticipo":
						include_once("class_folder/sigesp_sob_c_anticipo.php");
						include_once("class_folder/sigesp_sob_c_valuacion.php");
						$io_anticipo=new sigesp_sob_c_anticipo();					
						$io_valuacion=new sigesp_sob_c_valuacion();				
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							$ls_codcon=$la_data["codcon"][$li_j];
							$lb_valido=$io_anticipo->uf_calcular_montoanticipo($ls_codcon,$ld_montoanticipo);
							if($lb_valido && $ld_montoanticipo!=0)
							{
								$lb_valido=$io_valuacion->uf_amortizacion_anticipo($ls_codcon,$ld_amortizacion);
								if($lb_valido)
								{
									$ld_poramortizacion=$ld_amortizacion*100/$ld_montoanticipo;
									$la_data["amortizacionanticipo"][$li_j]=$io_funsob->uf_convertir_numerocadena($ld_poramortizacion);
								}
							}
							elseif($ld_montoanticipo==0)
							{
								$la_data["amortizacionanticipo"][$li_j]="---";
							}
						}					
					break;
					case "ejecucionfinanciera":
						include_once("class_folder/sigesp_sob_c_contrato.php");
						include_once("class_folder/sigesp_sob_c_valuacion.php");
						$io_contrato=new sigesp_sob_c_contrato();	
						$io_valuacion=new sigesp_sob_c_valuacion();						
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							$ls_codcon=$la_data["codcon"][$li_j];
							if(array_key_exists("montocontrato",$la_data))
							{
								$ld_monto=$la_data["montocontrato"][$li_j];
								$la_data["montocontrato"][$li_j]=$io_funsob->uf_convertir_numerocadena($la_data["montocontrato"][$li_j]);
							}
							else
							{
								$lb_valido=$io_contrato->uf_select_montocontrato($ls_codcon,$ld_monto);
								if(!$lb_valido)
									$ld_monto=0;								
							}
							$lb_valido=$io_valuacion->uf_ejecucion_financiera($ls_codcon,$ld_ejecucionfinanciera);
							//print "---->$ls_codcon=$ld_ejecucionfinanciera<----";
							if($lb_valido)
							{
								$ld_porejecfin=$ld_ejecucionfinanciera*100/$ld_monto;
								//if($ld_porejecfin!=0)
									$la_data["totalejecfin"][$li_j]=$io_funsob->uf_convertir_numerocadena($ld_porejecfin);						
								/*else
									$la_data["totalejecfin"][$li_j]="---";					*/	
							}
						}				
					break;
					case "ejecucionfisica":
						include_once("class_folder/sigesp_sob_c_valuacion.php");
						if(array_key_exists("codasi",$la_data))
						{
							$lb_codasi=true;
						}
						else
						{
							$lb_codasi=false;
							include_once("class_folder/sigesp_sob_c_contrato.php");
							$io_contrato=new sigesp_sob_c_contrato();	
						}						
						$io_valuacion=new sigesp_sob_c_valuacion();				
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							if($lb_codasi)
							{
								$ls_codasi=$la_data["codasi"][$li_j];
							}
							else
							{
								$ls_codcon=$la_data["codcon"][$li_j];
								$lb_valido=$io_contrato->uf_select_contrato($ls_codcon,$la_datacontrato);
								$ls_codasi=$la_datacontrato["codasi"][1];
							}
							
							$lb_valido=$io_valuacion->uf_select_asignacionpartidaobra($ls_codasi,$la_datapartidas);
							$ld_totalejecfisica=0;
							if($lb_valido)
							{
								$li_filasdatapartidas=(count($la_datapartidas, COUNT_RECURSIVE) / count($la_datapartidas)) - 1;
								
								for($li_k=1;$li_k<=$li_filasdatapartidas;$li_k++)
								{
									$ld_totalejecfisica=$ld_totalejecfisica+($la_datapartidas["canasipareje"][$li_k]*(100/$li_filasdatapartidas)/$la_datapartidas["canparobrasi"][$li_k]);
								}
								
								$la_data["totalejecfisic"][$li_j]=$io_funsob->uf_convertir_numerocadena($ld_totalejecfisica);								
							}
						}				
					break;
					
					case "fuentesfinanciamiento":
						include_once("class_folder/sigesp_sob_class_obra.php");
						$io_obra=new sigesp_sob_class_obra();
						if(!array_key_exists("codobr",$la_data))
						{
							include_once("class_folder/sigesp_sob_c_contrato.php");
							$io_contrato=new sigesp_sob_c_contrato();
							$lb_obra=false;
						}
						else
							$lb_obra=true;
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							if($lb_obra)
							{
								$ls_codobra=$la_data["codobr"][$li_j];
							}
							else
							{
								$io_contrato->uf_select_contrato($la_data["codcon"][$li_j],$la_datacontrato);
								$ls_codobra=$la_datacontrato["codobr"][1];
							}							
							$lb_valido=$io_obra->uf_select_fuentesfinanciamiento ($ls_codobra,$la_dataobra,$li_filasobra);
							if($lb_valido)
							{						
								$ls_fuentes="";
								for($li_k=1;$li_k<=$li_filasobra;$li_k++)
								{
									$ls_fuentes=$ls_fuentes.$la_dataobra["denfuefin"][$li_k];
									if($li_k+1<=$li_filasobra)
										$ls_fuentes=$ls_fuentes.", ";
								}
									$la_data["fuentesfinanciemiento"][$li_j]=$ls_fuentes;								
							}	
						}
					break;			
					default:
						require_once("class_folder/sigesp_sob_c_acta.php");
						$io_acta=new sigesp_sob_c_acta;
						for($li_j=1;$li_j<=$li_filasdata;$li_j++)
						{
							$ls_codcon=$la_data["codcon"][$li_j];						
							if($ls_calculo=="fechaparalizacion" || $ls_calculo=="motivoparalizacion")
							{
								$la_dataacta=array();
								$lb_valido=$io_acta->uf_select_actas($ls_codcon,5,$la_dataacta);
								if($ls_calculo=="fechaparalizacion" )
								{
									if($lb_valido===true)
									{
										$la_data["fechaparalizacion"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_dataacta["fecfinact"][1]);
									}
									else
									{
										$la_data["fechaparalizacion"][$li_j]="---";
									}
								}
								else
								{
									if($lb_valido===true)
									{
										$la_data["motivoparalizacion"][$li_j]=$la_dataacta["motact"][1];
									}
									else
									{
										$la_data["motivoparalizacion"][$li_j]="---";
									}
								}
							}
							elseif($ls_calculo=="fechaprorroga" || $ls_calculo=="motivoprorroga")
							{
								$la_dataacta=array();
								$lb_valido=$io_acta->uf_select_actas($ls_codcon,7,$la_dataacta);
								if($ls_calculo=="fechaprorroga" )
								{
									if($lb_valido===true)
									{
										$la_data["fechaprorroga"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_dataacta["fecfinact"][1]);
									}
									else
									{
										$la_data["fechaprorroga"][$li_j]="---";
									}
								}
								else
								{
									if($lb_valido===true)
									{
										$la_data["motivoprorroga"][$li_j]=$la_dataacta["motact"][1];
									}
									else
									{
										$la_data["motivoprorroga"][$li_j]="---";
									}
								}
							}
							else
							{
								$la_dataacta=array();
								$lb_valido=$io_acta->uf_select_actas($ls_codcon,6,$la_dataacta);
								if($lb_valido===true)
								{
									$la_data["fechareanudacion"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_dataacta["feciniact"][1]);
								}
								else
								{
									$la_data["fechareanudacion"][$li_j]="---";
								}
							}
					
						}						
				} 
			}
			$lb_placon=array_key_exists("placon",$la_data);
			$lb_codpro=array_key_exists("cod_pro",$la_data);			
			$lb_codproins=array_key_exists("cod_pro_ins",$la_data);
			if($lb_codpro || $lb_codproins)
			{
				include_once("class_folder/sigesp_sob_c_supervisores.php");
				$io_proveedores=new sigesp_sob_c_supervisores();
			}
			$lb_estcon=array_key_exists("estcon",$la_data);	
			$lb_feccreobr=array_key_exists("feccreobr",$la_data);	
			$lb_feciniobr=array_key_exists("feciniobr",$la_data);	
			$lb_fecfinobr=array_key_exists("fecfinobr",$la_data);	
			$lb_montoobra=array_key_exists("montoobra",$la_data);	
			$lb_feccon=array_key_exists("feccon",$la_data);	
			$lb_fecinicon=array_key_exists("fecinicon",$la_data);	
			$lb_fecfincon=array_key_exists("fecfincon",$la_data);
			$lb_monmaxcon=array_key_exists("monmaxcon",$la_data);		
			$lb_fecinireacon=array_key_exists("fecinireacon",$la_data);				
			$lb_fecfinreacon=array_key_exists("fecfinreacon",$la_data);
			$lb_feciniact=array_key_exists("feciniact",$la_data);	
			
				
			for($li_j=1;$li_j<=$li_filasdata;$li_j++)
			{
				if($lb_placon)
				{
					$la_data["placon"][$li_j]=$io_funsob->uf_convertir_numerocadena($la_data["placon"][$li_j])." ".$io_funsob->uf_convertir_letraunidad($la_data["placonuni"][$li_j]);
				}
				if($lb_codpro)
				{
					$ls_codcont=$la_data["cod_pro"][$li_j];
					$lb_valido=$io_proveedores->uf_select_proveedor ($ls_codcont,$la_dataproveedor);
					if($lb_valido)
					{
						$la_data["cod_pro"][$li_j]=$la_dataproveedor["nompro"][1];
					}
				}
				if($lb_codproins)
				{
					$ls_codinsp=$la_data["cod_pro_ins"][$li_j];
					$lb_valido=$io_proveedores->uf_select_proveedor ($ls_codinsp,$la_dataproveedor);
					if($lb_valido)
					{
						$la_data["cod_pro_ins"][$li_j]=$la_dataproveedor["nompro"][1];
					}
				}	
				if($lb_estcon)
				{
					$la_data["estcon"][$li_j]=$io_funsob->uf_convertir_numeroestado($la_data["estcon"][$li_j]);
				}	
				
				$la_data["codcon"][$li_j]=$la_data["precon"][$li_j].$la_data["codcon"][$li_j];
				
				if($lb_feccreobr)
				{
					$la_data["feccreobr"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["feccreobr"][$li_j]);
				}
				if($lb_feciniobr)
				{
					$la_data["feciniobr"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["feciniobr"][$li_j]);
				}
				if($lb_fecfinobr)
				{
					$la_data["fecfinobr"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["fecfinobr"][$li_j]);
				}
				if($lb_montoobra)
				{
					$la_data["montoobra"][$li_j]=$io_funsob->uf_convertir_numerocadena($la_data["montoobra"][$li_j]);
				}
				if($lb_feccon)
				{
					$la_data["feccon"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["feccon"][$li_j]);
				}
				if($lb_fecinicon)
				{
					$la_data["fecinicon"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["fecinicon"][$li_j]);
				}
				if($lb_feciniact)
				{
					$la_data["feciniact"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["feciniact"][$li_j]);
				}
				if($lb_fecfincon)
				{
					$la_data["fecfincon"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["fecfincon"][$li_j]);
				}
				if($lb_monmaxcon)
				{
					$la_data["monmaxcon"][$li_j]=$io_funsob->uf_convertir_numerocadena($la_data["monmaxcon"][$li_j]);
				}
				if($lb_fecinireacon)
				{
					if($la_data["fecinireacon"][$li_j]=="0000-00-00")
						$la_data["fecinireacon"][$li_j]="---";
					else
					$la_data["fecinireacon"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["fecinireacon"][$li_j]);
				}
				if($lb_fecfinreacon)
				{
					if($la_data["fecfinreacon"][$li_j]=="0000-00-00")
						$la_data["fecfinreacon"][$li_j]="---";
					else
					$la_data["fecfinreacon"][$li_j]=$io_funcion->uf_convertirfecmostrar($la_data["fecfinreacon"][$li_j]);
				}			
			}		
			//print_r($la_data);
	
//----------------------------------------------------------------------------------------------------------------------------------//		
			$_SESSION["keys"]=$la_keysaux;
			$la_titulos=$io_reporte->uf_titulos("SEGUIMIENTOOBRA",$la_data);	
			//print"---------";
			//print_r($la_titulos);			
			$_SESSION["data"]=$la_data;
			$_SESSION["titulosdetalle"]=$la_titulos;
			$_SESSION["tituloencabezado"]=$ls_tituloencabezado;
			$_SESSION["fechadesde"]=$ls_feccondesde;
			$_SESSION["fechahasta"]=$ls_fecconhasta;
			$_SESSION["orientacion"]="landscape" ;			
			if(array_key_exists("tituloscabecera",$_SESSION))
				unset($_SESSION["tituloscabecera"]);
			?>
				<script language="javascript">
					var pagina='sigesp_sob_r_plantillapdf.php';
					window.open(pagina,'catalogo','menubar=no,toolbar=no,scrollbars=yes,width=900,height=700,resizable=yes,top=20,left=30');			
				</script>
			<?
		}
		elseif($lb_valido===0)
		{
			$io_msg->message("No hay registros que cumplan con esos parámetros de búsqueda!!!");
		}		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		
	}
	elseif($ls_operacion=="ue_eliminar")
	{
			
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

<table width="825" height="891" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
	  
	
        <td width="823" height="889"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Reporte de Seguimiento de Obras </td>
            </tr>
            <tr>
              <td height="19" colspan="3" ><input name="operacion" type="hidden" id="operacion">
              <input name="consulta" type="hidden" id="consulta2"  value="<? print $ls_consulta?>">              </td>
            </tr>
            <tr>
              <td width="32" height="19" >&nbsp;</td>
              <td width="144" ><div align="right">T&iacute;tulo del Reporte </div></td>
              <td width="612" ><input name="txttituloencabezado" type="text" id="txttituloencabezado" value="<? print $ls_tituloencabezado?>" size="82" maxlength="100"></td>
            </tr>
            <tr>
              <td height="19" colspan="3" ><span class="Estilo1">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos del Contrato</span></td>
            </tr>
            <tr>
              <td colspan="3" align="left" ><table width="638" height="140" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="21"><div align="right">Fecha de Registro </div></td>
                  <td width="41" valign="middle" ><div align="right">Desde </div></td>
                  <td width="55" valign="middle" ><input name="txtfeccondesde" type="text" id="txtfeccondesde"  style="text-align:left" value="<? print $ls_feccondesde ?>" size="11" maxlength="10" datepicker="true" readonly="true" ></td>
                  <td colspan="2" valign="middle" ><div align="right"></div>
                      <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasta
                          <input name="txtfecconhasta" type="text" id="txtfecconhasta"  style="text-align:left" value="<? print $ls_fecconhasta ?>" size="11" maxlength="10" datepicker="true" readonly="true">
                    </div></td>
                  <td valign="middle" >&nbsp;</td>
                  <td width="86" >&nbsp;</td>
                </tr>
                <tr>
                  <td width="200" height="21"><div align="right">Prefijo</div></td>
                  <td colspan="2" ><input name="txtprecon" type="text" id="txtprecon" value="<? print $ls_precon?>" size="20" maxlength="20"></td>
                  <td ><div align="right"></div></td>
                  <td ><div align="right"></div></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td height="21"><div align="right">C&oacute;digo</div></td>
                  <td colspan="2" ><input name="txtcodcon" type="text" id="txtcodcon2" value="<? print $ls_codcontrato?>" size="12" maxlength="12"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Fecha Inicio </div></td>
                  <td colspan="2" >
				    <?
				  	$la_combodata=array('texto'=>(array("Mayor o igual a","Menor o igual a","Igual a")),'valor'=>(array(">=","<=","=")));
				  ?>
                    <select name="cmbfecinicon" id="select">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbfecinicon== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td width="81"><div align="left">
                      <input name="txtfecinicon" type="text" id="txtfecinicon"  style="text-align:left" value="<? print $ls_fecinicon ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                  </div></td>
                  <td width="57"><div align="right">Fecha Fin</div></td>
                  <td width="100">
                    <select name="cmbfecfincon" id="select2">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbfecfincon== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td><input name="txtfecfincon" id="txtfecfincon2" type="text"  style="text-align:left" value="<? print $ls_fecfincon ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Monto</div></td>
                  <td colspan="2" ><select name="cmbmoncon" size="1" id="select3">
                      <option value=""><font color="#FF3399" >Seleccione</font></option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbprepar== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                  </select></td>
                  <td colspan="3"><input name="txtmoncon" type="text" id="txtmoncon2"  style="text-align:right" value="<? print $ls_moncon ?>" size="22" maxlength="21" onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Estado de Ejecuci&oacute;n </div></td>
                  <td colspan="6" >
                    <?
				  	$la_comboestados=array('texto'=>(array("Emitido","Contabilizado","Iniciado","Paralizado","Finalizado","Prórroga","Paralizado en Prórroga")),
											'valor'=>(array(1,5,10,7,8,9,11)));
				  ?>  
                    <select name="cmbestcon" id="cmbestcon" >
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<7;$li_i++)
					{
						if($ls_cmbestcon==$la_comboestados["valor"][$li_i])
							print "<option selected value='".$la_comboestados["valor"][$li_i]."'>".$la_comboestados['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_comboestados["valor"][$li_i]."'>".$la_comboestados['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                </tr>
              </table>              </td>
            </tr>
			<tr>
              <td height="13" colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td height="18" colspan="3"><span class="Estilo1">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos de la Asignaci&oacute;n </span></td>
            </tr>
            <tr>
              <td height="56" colspan="3" align="left"><table width="638" height="54" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="172" height="24"><div align="right">Empresa Contratista </div></td>
                  <td width="458" ><input name="txtcodcontratista" type="text" id="txtcodcontratista" value="<? print $ls_codcontratista?>" size="10" maxlength="10">
                      <span class="toolbar"><a href="javascript:ue_catcontratista();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></span>
                      <input name="txtnomcontratista" type="text" id="txtnomcontratista" readonly="true" value="<? print $ls_nomcontratista?>" size="60" maxlength="254" class="sin-borde"></td>
                </tr>              
                <tr>
                  <td height="24"><div align="right">Empresa Inspectora </div></td>
                  <td ><input name="txtcodins" type="text" id="txtcodins" value="<? print $ls_codins?>" size="10" maxlength="10">
                      <span class="toolbar"><a href="javascript:ue_catinspectora();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></span>
                      <input name="txtnomins" type="text" id="txtnomins" readonly="true" value="<? print $ls_nomins?>" size="60" maxlength="254" class="sin-borde"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="8" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="20" colspan="3"><span class="Estilo1">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos de la Obra </span></td>
            </tr>
            <tr>
              <td height="115" colspan="3" align="left"><table width="638" height="113" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="21"><div align="right">Fecha de Registro </div></td>
                  <td colspan="4" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desde
                      <input name="txtfeccreobrdesde" type="text" id="txtfeccreobrdesde2"  style="text-align:left" value="<? print $ls_feccreobrdesde ?>" size="11" maxlength="10" datepicker="true" readonly="true" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasta
      <input name="txtfeccreobrhasta" type="text" id="txtfeccreobrhasta2"  style="text-align:left" value="<? print $ls_feccreobrhasta ?>" size="11" maxlength="10" datepicker="true" readonly="true"></td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td width="174" height="21"><div align="right">Descripci&oacute;n</div></td>
                  <td colspan="5" ><input name="txtdesobr" id="txtdesobr2" type="text" value="<? print $ls_desobr?>" size="57" maxlength="254"></td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Fecha Inicio </div></td>
                  <td width="120" >
                  
                    <select name="cmbparfeciniobr" size="1" id="select6">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_parfeciniobr== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td width="64"><div align="left">
                      <input name="txtfeciniobr" type="text" id="txtfeciniobr2"  style="text-align:left" value="<? print $ls_feciniobr ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                  </div></td>
                  <td width="68"><div align="right">Fecha Fin</div></td>
                  <td width="105">
                    <select name="cmbparfecfinobr" size="1" id="select7">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_parfecfinobr== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td width="91"><input name="txtfecfinobr" id="txtfecfinobr2" type="text"  style="text-align:left" value="<? print $ls_fecfinobr ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
                </tr>
                <tr>
                  <td height="20"><div align="right">Organismo Ejecutor </div></td>
                  <td colspan="5" ><input name="txtcodpro" type="text" id="txtcodpro2"  style="text-align:left" value="<? print $ls_codpro ?>" size="4" maxlength="4" >
                      <a href="javascript:ue_catpropietario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                      <input name="txtnompro" type="text" id="txtnompro2"  style="text-align:left" class="sin-borde" value="<? print $ls_nompro ?>" size="50" maxlength="100" readonly="true" ></td>
                </tr>
                <tr>
                  <td height="18"><div align="right">Responsable</div></td>
                  <td colspan="5" ><input name="txtresobr" type="text" id="txtresobr2"  style="text-align:left" value="<? print $ls_resobr ?>" size="57" maxlength="50"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="101" colspan="3" align="left"><table width="638" height="71" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="21"><div align="right">Pa&iacute;s</div></td>
                  <td colspan="3"><span class="style6">
                    <?Php
				    $ls_sql="SELECT codpai ,despai
                             FROM sigesp_pais
                             ORDER BY codpai ASC";
				    $lb_valido=$io_reporte->uf_datacombo($ls_sql,$la_pais);
					 if($lb_valido)
				     {
					   $io_datastore->data=$la_pais;
					   $li_totalfilas=$io_datastore->getRowCount("codpai");
				     }
				    ?>
                    <select name="cmbpais" size="1" id="cmbpais" onChange="javascript:document.form1.submit()">
                      <option value="">Seleccione...</option>
                      <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpai",$li_i);
					 $ls_desest=$io_datastore->getValue("despai",$li_i);
					 if ($ls_codigo==$ls_codpai)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					} 
	                ?>
                    </select>
                    <input name="hidpais" type="hidden" id="hidpais" value="<? print $ls_codpai?>">
                  </span></td>
                </tr>
                <tr>
                  <td width="168" height="21"><div align="right">Estado</div></td>
                  <td width="166"><span class="style6">
                    <?Php
				    $ls_sql="SELECT codest ,desest 
                             FROM sigesp_estados
                             WHERE codpai='$ls_codpai' ORDER BY codest ASC";
				    $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_estado);
					
				    if($lb_valido)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
					 else
					 {
					 	$li_totalfilas=0;
					 }
				    ?>
                    <select name="cmbestado" size="1" id="select8" onChange="javascript:ue_llenarcmb();">
                      <option value="">Seleccione...</option>
                      <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					} 
	                ?>
                    </select>
                    <input name="hidestado" type="hidden" id="hidestado2" value="<? print $ls_codest ?>">
                  </span></td>
                  <td>
                    <div align="right"></div>
                    <div align="right"><span class="style6">Municipio </span></div></td>
                  <td><?Php
					if($ls_codest=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codmun ,denmun 
                                  FROM sigesp_municipio
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_municipio);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}										
			    ?>
                      <select name="cmbmunicipio" size="1" id="select9" onChange="javascript:ue_llenarcmb();">
                        <option value="">Seleccione...</option>
                        <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					} 
	            ?>
                      </select>
                      <input name="hidmunicipio" type="hidden" id="hidmunicipio2" value="<? print $ls_codmun ?>"></td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Parroquia</div></td>
                  <td valign="top"><?Php
			    if($ls_codmun=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codpar ,denpar 
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_parroquia);
					 } 	
					
					if($lb_valido)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}										
					else{$li_totalfilas=0;}
			    ?>
                      <select name="cmbparroquia" size="1" id="select10" onChange="javascript:ue_llenarcmb();">
                        <option value="">Seleccione...</option>
                        <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					} 
	            ?>
                      </select>
                      <input name="hidparroquia" type="hidden" id="hidparroquia2" value="<? print $ls_codpar ?>"></td>
                  <td width="67" ><div align="right">Comunidad</div></td>
                  <td width="225" ><?Php
			    if($ls_codpar=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codcom ,nomcom
                                  FROM sigesp_comunidad
                                  WHERE codest='".$ls_codest."' AND codmun='".$ls_codmun."' AND codpar='".$ls_codpar."' ORDER BY codcom ASC";
				         $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_comunidad);
					 } 	
					
					if($lb_valido)
					{
						$io_datastore->data=$la_comunidad;
						$li_totalfilas=$io_datastore->getRowCount("codcom");
					}										
					else{$li_totalfilas=0;}
			    ?>
                      <select name="cmbcomunidad" size="1" id="select11" >
                        <option value="">Seleccione...</option>
                        <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codcom",$li_i);
						 $ls_nomcom=$io_datastore->getValue("nomcom",$li_i);
						 if ($ls_codigo==$ls_codcom)
						 {
							  print "<option value='$ls_codigo' selected>$ls_nomcom</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_nomcom</option>";
						 }
					} 
	            ?>
                      </select>
                      <input name="hidcomunidad" type="hidden" id="hidcomunidad2" value="<? print $ls_codcom ?>"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="62" colspan="3" align="left"><table width="638" height="45" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="169" height="20"><div align="right">Fuente de Financiamiento </div></td>
                  <td colspan="3" ><input name="txtcodfuefin" type="text" id="txtcodfuefin"  style="text-align:left" value="<? print $ls_codfuefin ?>" size="4" maxlength="4" >
                      <a href="javascript:ue_catfuentesfin();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                      <input name="txtdenfuefin" type="text" id="txtdenfuefin"  style="text-align:left" class="sin-borde" value="<? print $ls_denfuefin ?>" size="50" maxlength="100" readonly="true" ></td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Monto Total de la Obra </div></td>
                  <td width="129" >
                    <select name="cmbparmonto" size="1" id="select12">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_parmonto== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td width="183"><input name="txtmonto" type="text" id="txtmonto"  style="text-align:right" value="<? print $ls_monto ?>" size="22" maxlength="21" onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
                  <td width="145">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="8" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="8" colspan="3"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Campos del Reporte </span></td>
            </tr>
            <tr>
              <td height="41" colspan="3"><table width="756" height="167" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr class="formato-blanco">
                  <td width="201" align="right">&nbsp;
				  </td>
                  <td width="11" align="left">&nbsp;</td>
                  <td width="236" align="left"><input name="txtlista" type="text" class="sin-borde" id="txtlista" readonly="true"
				   value="<?
				   if($ls_hidtabla=="hidobra") 
				   		print"Campos de la Obra";
					elseif($ls_hidtabla=="hidasignacion")
						print"Campos de la Asignación";
					elseif($ls_hidtabla=="hidcontrato")
						print"Campos del Contrato";
					elseif($ls_hidtabla=="hidanticipo")
						print"Campos del Anticipo";
					elseif($ls_hidtabla=="hidvaluacion")
						print"Campos de la Valuación";
					elseif($ls_hidtabla=="hidactas")
						print"Campos de las Actas";
					else print"";  
				   
				   ?>"
				  
				  ></td>
                  <td width="32" height="9" align="center">&nbsp;</td>
                  <td colspan="2" align="left">Mostrar</td>
                  </tr>
                <tr>
                  <td rowspan="4" align="right" valign="top">
				  <select name="lst3" size="10" id="lst3" style="width: 200px " onChange="javascript: ue_cargar_listacondicional(this,document.form1.lst2)">
                    <?Php
				  		if($ls_hidlista3!="")
						{
							$la_datalista3=$io_funsob->uf_decodificardata("?",$ls_hidlista3,$li_filas,"2");
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								print "<option value='".$la_datalista3[$li_i][2]."'>".$la_datalista3[$li_i][1]."</option>";								
							}
							
						}
					?>
			     </select>
					</td>
                  <td colspan="2" rowspan="4" align="right" valign="top">				  
				  <select name="lst1" id="lst1" size="10" multiple style="width: 238px ">
                    <?Php
				  		if($ls_hidlista1!="")
						{
							$la_datalista1=$io_funsob->uf_decodificardata("?",$ls_hidlista1,$li_filas,"2");
							//print "datalista";
							//print_r($la_datalista1);
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								if($la_datalista1[$li_i][2]!="")
									print "<option value='".$la_datalista1[$li_i][2]."'>".$la_datalista1[$li_i][1]."</option>";
							}							
						}
					?>
                  </select></td>
                  <td width="32" height="26" align="center">&nbsp;</td>
                  <td width="238" rowspan="4" align="left" valign="top">
				   <select name="lst2" id="lst2" size="10" multiple style="width: 238px " onChange="javascript:ue_habilitar_deshabilitar_botones(this,document.form1.btn3,document.form1.btn4)">
				  <?Php
				  		if($ls_hidlista2!="")
						{
							$la_datalista2=$io_funsob->uf_decodificardata("?",$ls_hidlista2,$li_filas,"2");
							//print "datalista";
							//print_r($la_datalista1);
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								print "<option value='".$la_datalista2[$li_i][2]."'>".$la_datalista2[$li_i][1]."</option>";
							}
							
						}
					?>
					</select>
					</td>
                  <td width="22" align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="btn1" value=">>" id="btn1"  onClick="javascript:ue_cargarlistaespecial(document.form1.lst1,document.form1.lst2,'')"></td>
                  <td width="22" align="left" valign="top"><input name="btn3" type="button" value="&#8593;"  id="btn3"  onClick="javascript:ue_moveritem(document.form1.lst2,'arriba',this,document.form1.btn4)"  style="width:20px; height:20px " > </td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="btn2" id="btn2" value="<<"   onClick="javascript:ue_cargarlistaespecial(document.form1.lst2,document.form1.lst1,'con.codcon,con.precon-1')"></td>
                  <td width="22" align="left" valign="top"><input type="button" name="btn4" id="btn4" value="&#8595;"  onClick="javascript:ue_moveritem(document.form1.lst2,'abajo',document.form1.btn3,this)" style="width:20px; height:20px "></td>
                </tr>
                <tr>
                  <td height="72" align="center">&nbsp;</td>
                  <td width="22" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>              </td>
            </tr>
            <tr>
              <td height="8" colspan="3">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>	 
  </table>
    <input type="hidden" name="hiddata" id="hiddata">
	<input type="hidden" name="hidlista1" id="hidlista1" value="<? print $ls_hidlista1?>">
	<input type="hidden" name="hidlista2" id="hidlista2" value="<? print $ls_hidlista2?>">
	<input type="hidden" name="hidlista3" id="hidlista3" value="<? print $ls_hidlista3?>">
	<input type="hidden" name="hidobra" id="hidobra" value="<? print $ls_hidobra?>">
	<input type="hidden" name="hidasignacion" id="hidasignacion" value="<? print $ls_hidasignacion?>">
	<input type="hidden" name="hidcontrato" id="hidcontrato" value="<? print $ls_hidcontrato?>">
	<input type="hidden" name="hidvaluacion" id="hidvaluacion" value="<? print $ls_hidvaluacion?>">
	<input type="hidden" name="hidanticipo" id="hidanticipo" value="<? print $ls_hidanticipo?>">
	<input type="hidden" name="hidactas" id="hidactas" value="<? print $ls_hidactas?>">
	<input type="hidden" name="hidscroll" id="hidscroll" value="<? print $ls_scroll?>">			
	<input type="hidden" name="hidtabla" id="hidtabla" value="<? print $ls_hidtabla?>">			
</form>

	 <?PHP  
	  	print"<script>";
		print "scrollTo(0,document.form1.hidscroll.value);";
		print "</script>";
	  
	  ?>		
</body>

<script language="JavaScript">

function ue_catcontratista()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_contratista.php";
	popupWin(pagina,"catalogo",580,700);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catinspectora()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_inspectora.php";
	popupWin(pagina,"catalogo",530,700);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}
  	
	
function ue_imprimir()
		{
            f=document.form1;
			li_imprimir=f.imprimir.value;
			if (li_imprimir==1)
			{

				f.hidscroll.value=0;
				f.operacion.value="ue_imprimir";			
				data=new Array();
				data=ue_obtenerarreglocampos(f.lst2,false);
				tira=ue_codificardata(data,"-",false);
				f.hiddata.value=tira;
				data=ue_obtenerarreglocampos(f.lst1,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista1.value=tira;
				data=ue_obtenerarreglocampos(f.lst2,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista2.value=tira;
				valido=true;
				if(valido=ue_valida_null(f.txttituloencabezado,"Título del Reporte"))
				{
					if(valido=ue_valida_null(f.txtfeccondesde,"Inicio de rango de Fecha de Registro del Contrato"))
					{
						if(valido=ue_valida_null(f.txtfecconhasta,"Finalización de rango de Fecha de Registro del Contrato"))
						{
							if(valido=ue_comparar_intervalo("txtfeccondesde","txtfecconhasta","Seleccione un rango de Fechas de Registro válido!!!"))
							{
								if(f.txtfecinicon.value!="")
								{
									if(f.cmbfecinicon.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para la Fecha de Inicio del Contrato!!!");
										valido=false;
									}
								}
								if(valido && f.txtfecfincon.value!="")
								{
									if(f.cmbfecfincon.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para la Fecha de Fin del Contrato!!!");
										valido=false;
									}
								}
								if(valido && f.txtmoncon.value!="" && f.txtmoncon.value!="0,00" && f.txtmoncon.value!="0,0" && f.txtmoncon.value!="0," && f.txtmoncon.value!="0" && f.txtmoncon.value!="00" && f.txtmoncon.value!="000" && f.txtmoncon.value!=",00" )
								{
									if(f.cmbmoncon.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para el monto del Contrato!!!");
										valido=false;
									}
								}
								if(valido)
									valido=ue_comparar_intervalo("txtfeccondesde","txtfecconhasta","Seleccione un rango de Fechas de Registro válido!!!")
								if(valido && f.txtfeciniobr.value!="")
								{
									if(f.cmbparfeciniobr.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para la Fecha de Inicio de la Obra!!!");
										valido=false;
									}
								}
								if(valido && f.txtfecfinobr.value!="")
								{
									if(f.cmbparfecfinobr.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para la Fecha de Finalización de la Obra!!!");
										valido=false;
									}
								}
								if(valido &&  f.txtmonto.value!="" && f.txtmonto.value!="0,00" && f.txtmonto.value!="0,0" && f.txtmonto.value!="0," && f.txtmonto.value!="0" && f.txtmonto.value!="00" && f.txtmonto.value!="000" && f.txtmonto.value!=",00")
								{
									if(f.cmbparmonto.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para el monto de la Obra!!!");
										valido=false;
									}
								}							
							}
						}
					}
				}		
				
				if (valido)
				{
					f.submit();
				}			
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}



		
		
		function ue_nuevo()
		{
		 	location.href=location;
		}


		/*
***********************************************************************************************************************************/        
		
		function ue_catfuentesfin()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_fuentefinan.php";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
		}
		
		function ue_catpropietario()
        {
	        f=document.form1;
	        f.operacion.value="";			
	        pagina="sigesp_cat_organismo.php";
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
        }
/***********************************************************************************************************************************/
        function ue_cargarpropietario(cod,nom,tel,dir,nrp,fax,ema)
		{
			f=document.form1;
			f.txtcodpro.value=cod;
            f.txtnompro.value=nom;
		}
		
		function ue_cargarfuente(codigo,denominacion)
		{
		    f=document.form1;
			f.txtcodfuefin.value=codigo;
            f.txtdenfuefin.value=denominacion;
		}
		function ue_llenarcmb()
        {
	        f=document.form1;
	        f.action="sigesp_sob_r_reporteobra.php";
	        f.operacion.value="";
	        f.submit();
        }
     
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>