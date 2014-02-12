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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_r_reporteasignacionesobra.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$la_datemp=$_SESSION["la_empresa"];//uf_decodificardata
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Reporte de Asignaciones por Obras</title>
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
   		$ls_codest=$_POST["cmbestado"];
		$ls_codmun=$_POST["cmbmunicipio"];
		$ls_codpar=$_POST["cmbparroquia"];
		$ls_codcom=$_POST["cmbcomunidad"];				
		$ls_consulta=$_POST["consulta"];  
		$ls_desobr=$_POST["txtdesobr"];
		$ls_hidlista1=$_POST["hidlista1"];
		$ls_hidlista2=$_POST["hidlista2"];
		$ls_hidlista3=$_POST["hidlista3"];
		$ls_hidlista4=$_POST["hidlista4"];
		$ls_tituloencabezado=$_POST["txttituloencabezado"];
		$ls_codobr=$_POST["txtcodobr"];
		$ls_codasi=$_POST["txtcodasi"];
		$ls_puncueasi=$_POST["txtpuncueasi"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_nompro=$_POST["txtnompro"];
		//$ls_codproins=$_POST["txtcodproins"];
		//$ls_nomproins=$_POST["txtnomproins"];		
		$ls_montotasi=$_POST["txtmontotasi"];
		if ($ls_montotasi=="")
			$ls_montotasi="0,00";
		$ls_cmbmontotasi=$_POST["cmbmontotasi"];	
		$ls_codpai=$_POST["cmbpais"];	
	}
	else
	{
		$ls_operacion="";
		$ls_tituloencabezado="";
		$ls_feccreobrdesde=""; 
		$ls_feccreobrhasta=""; 
        $ls_feciniobr="";
        $ls_fecfinobr="";  
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codcom="";		
		$ls_monto="0,00";
		$ls_parfeccreobr=""; 
        $ls_parfeciniobr=""; 
        $ls_parfecfinobr="";
		$ls_consulta="";  
		$ls_desobr="";
		$la_parametro[1][1]="";
		$ls_codobr="";
		$ls_codasi="";
		$ls_puncueasi="";
		$ls_codpro="";
		$ls_nompro="";
		//$ls_codproins="";
		//$ls_nomproins="";		
		$ls_montotasi="0,00";
		$ls_cmbmontotasi="";
		$ls_codpai="001";
		
		$ls_hidlista1="Descripción de la Obra?o.desobr-1?Estado (Ubicación)?e.desest-2?
							Fecha de Registro de la Obra?o.feccreobr-1?Fecha de Finalización de la Obra?o.fecfinobr-1?Fecha de Inicio de la Obra?
							o.feciniobr-1?Monto Total de la Obra?o.monto-1?Municipio?m.denmun-3?Parroquia?p.denpar-4";
		$ls_hidlista2="Código de la Obra?o.codobr-1";
		$ls_hidlista3="Código de la Asignación?a.codasi-6?Punto de Cuenta?a.puncueasi-6?Empresa Contratista?pro.nompro-7?
							Fecha de la Asignación?a.fecasi-6?Monto Parcial?a.monparasi-6?Base Imponible?
							a.basimpasi-6?Monto Total?a.montotasi-6";
		$ls_hidlista4="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_imprimir")
	{		
		if($io_funsob->uf_convertir_cadenanumero($ls_montotasi)==0)
			$ls_montotasi="";		
		$la_parametro[1][1]="o.feccreobr";
		$la_parametro[1][2]=$io_funcion->uf_convertirdatetobd($ls_feccreobrdesde);
		$la_parametro[1][3]=">=";
		$la_parametro[1][4]=1;
		$la_parametro[2][1]="o.feciniobr";
		$la_parametro[2][2]=$io_funcion->uf_convertirdatetobd($ls_feciniobr);
		$la_parametro[2][3]=$ls_parfeciniobr;
		$la_parametro[2][4]=1;
        $la_parametro[3][1]="o.fecfinobr";
		$la_parametro[3][2]=$io_funcion->uf_convertirdatetobd($ls_fecfinobr);
		$la_parametro[3][3]=$ls_parfecfinobr;
		$la_parametro[3][4]=1;		
		$la_parametro[4][1]="o.codobr";
		$la_parametro[4][2]="%".$ls_codobr."%";
		$la_parametro[4][3]=" like ";
		$la_parametro[4][4]=1;		
		$la_parametro[5][1]="a.codasi";
		$la_parametro[5][2]="%".$ls_codasi."%";
		$la_parametro[5][3]=" like ";
		$la_parametro[5][4]=6;		
		$la_parametro[6][1]="o.codest";
		$la_parametro[6][2]=$ls_codest;
		$la_parametro[6][3]="=";
		$la_parametro[6][4]=1;
		$la_parametro[7][1]="o.codmun";
		$la_parametro[7][2]=$ls_codmun;
		$la_parametro[7][3]="=";
		$la_parametro[7][4]=1;
		$la_parametro[8][1]="o.codpar";
		$la_parametro[8][2]=$ls_codpar;
		$la_parametro[8][3]="=";
		$la_parametro[8][4]=1;
		$la_parametro[9][1]="o.codcom";
		$la_parametro[9][2]=$ls_codcom;
		$la_parametro[9][3]="=";
		$la_parametro[9][4]=1;
		$la_parametro[10][1]="a.puncueasi";
		$la_parametro[10][2]="%".$ls_puncueasi."%";
		$la_parametro[10][3]=" like ";
		$la_parametro[10][4]=6;	
        $la_parametro[11][1]="a.cod_pro";
		$la_parametro[11][2]="%".$ls_codpro."%";
		$la_parametro[11][3]=" like ";
		$la_parametro[11][4]=6;
		$la_parametro[12][1]="o.desobr";
		$la_parametro[12][2]="%".$ls_desobr."%";
		$la_parametro[12][3]=" like ";
		$la_parametro[12][4]=1;
		$la_parametro[13][1]="o.feccreobr";
		$la_parametro[13][2]=$io_funcion->uf_convertirdatetobd($ls_feccreobrhasta);
		$la_parametro[13][3]="<=";
		$la_parametro[13][4]=1;
		$la_parametro[14][1]="a.montotasi";
		$la_parametro[14][2]=$io_funsob->uf_convertir_cadenanumero($ls_montotasi);
		$la_parametro[14][3]=$ls_cmbmontotasi;
		$la_parametro[14][4]=6;
		
		
//-------------------------------------Campos a ser mostrados en el reporte---------------------------------------//
		$ls_cadena=$_POST["hiddata1"];		
		$la_arreglo1=$io_funsob-> uf_decodificardata("-",$ls_cadena,$li_index1);
		$ls_cadena=$_POST["hiddata2"];		
		$la_arreglo2=$io_funsob-> uf_decodificardata("-",$ls_cadena,$li_index2);
		$la_salida=$io_funsob->uf_array_merge($la_arreglo1,$la_arreglo2,$li_index1,$li_index2);
		$li_index=$li_index1+$li_index2;
		//print "$ls_cadena \n";
		/*print"-----------Arre1----------->";
		print_r($la_arreglo1);
		print "---Arre2-------------------------------------";
		print_r($la_arreglo2);
		print "--salida-------------------------------";
		print_r($la_salida);*/		
		$la_tabla[1][1]="sob_obra o";
		$la_tabla[1][2]="o.codemp=$ls_codemp" ;
		$la_tabla[1][3]="0";
		$la_tabla[1][4]=0;
		$la_tabla[2][1]="sigesp_estados e";
		$la_tabla[2][2]="o.codest=e.codest ";
		$la_tabla[2][3]="0";
		$la_tabla[2][4]=1;
        $la_tabla[3][1]="sigesp_municipio m";
		$la_tabla[3][2]="e.codest=m.codest AND o.codmun=m.codmun";
		$la_tabla[3][3]="0";
		$la_tabla[3][4]=2;
		$la_tabla[4][1]="sigesp_parroquia p";
		$la_tabla[4][2]="m.codmun=p.codmun AND o.codpar=p.codpar";
		$la_tabla[4][3]="0";
		$la_tabla[4][4]=3;
		$la_tabla[5][1]="sigesp_comunidad c";
		$la_tabla[5][2]="p.codpar=c.codpar AND o.codcom=c.codcom";
		$la_tabla[5][3]="0";
		$la_tabla[5][4]=4;
		$la_tabla[6][1]="sob_asignacion a";
		$la_tabla[6][2]="o.codemp=a.codemp AND a.codobr=o.codobr";
		$la_tabla[6][3]="0";
		$la_tabla[6][4]=1;					
		$la_tabla[7][1]="rpc_proveedor pro";
		$la_tabla[7][2]="o.codemp=pro.codemp AND pro.cod_pro=a.cod_pro ";
		$la_tabla[7][3]="0";
		$la_tabla[7][4]="6";
		
		$ls_cadena=$io_reporte->uf_evalconsulta($la_salida,$li_index,$la_tabla,7,$la_parametro,14);
		$ls_cadena=$ls_cadena." ORDER BY o.codobr";
		//print"------------CADENA------------$ls_cadena";
		$lb_valido=$io_reporte->uf_obtenerdata ($ls_cadena,$la_data);
		if($lb_valido===true)
		{
			$la_titulosobra=$io_reporte->uf_titulos("OBRA",$la_data);		
			$la_titulospartida=$io_reporte->uf_titulos("ASIGNACION",$la_data);		
			/*print "----------OBRA--------------";
			print_r($la_titulosobra);
			print "----------PARTIDA--------------";
			print_r($la_titulospartida);*/
			$li_filas=(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
			if(array_key_exists("monto",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["monto"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["monto"][$li_i]);
				}
			}
			if(array_key_exists("monparasi",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["monparasi"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["monparasi"][$li_i]);
				}
			}
			if(array_key_exists("basimpasi",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["basimpasi"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["basimpasi"][$li_i]);
				}
			}
			if(array_key_exists("montotasi",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["montotasi"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["montotasi"][$li_i]);
				}
			}
			if(array_key_exists("feccreobr",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["feccreobr"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["feccreobr"][$li_i]);
				}
			}
			if(array_key_exists("fecfinobr",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecfinobr"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecfinobr"][$li_i]);
				}
			}
			if(array_key_exists("feciniobr",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["feciniobr"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["feciniobr"][$li_i]);
				}
			}			
			if(array_key_exists("fecasi",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecasi"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecasi"][$li_i]);
				}
			}			
			
			$_SESSION["data"]=$la_data;
			$_SESSION["tituloscabecera"]=$la_titulosobra;
			$_SESSION["titulosdetalle"]=$la_titulospartida;
			$_SESSION["tituloencabezado"]=$ls_tituloencabezado;
			$_SESSION["fechadesde"]=$ls_feccreobrdesde;
			$_SESSION["fechahasta"]=$ls_feccreobrhasta;
			$_SESSION["orientacion"]="landscape" ;
			$_SESSION["titulodetalle"]="ASIGNACIONES";
			?>
				<script language="javascript">
					var pagina='sigesp_sob_r_plantillapdf.php';
					window.open(pagina,'catalogo','menubar=no,toolbar=no,scrollbars=yes,width=900,height=700,resizable=yes,top=20,left=30');			
				</script>
			<?
		}
		elseif($lb_valido===0)
		{
			$io_msg->message("No se han creado registros que cumplan con esos parámetros de búsqueda!!!");
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

<table width="675" height="819" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
	  
	
        <td width="675" height="817"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Reporte de Asignaciones por Obras </td>
            </tr>
            <tr>
              <td height="20" colspan="3" ><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
              <input name="consulta" type="hidden" id="consulta2"  value="<? print $ls_consulta?>">              </td>
            </tr>
            <tr>
              <td width="29" height="19" >&nbsp;</td>
              <td width="132" ><div align="right">T&iacute;tulo del Reporte </div></td>
              <td width="471" ><input name="txttituloencabezado" type="text" id="txttituloencabezado" value="<? print $ls_tituloencabezado?>" size="82" maxlength="100"></td>
            </tr>
            <tr>
              <td height="19" colspan="3" ><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos Generales de la Obra </span></td>
            </tr>
            <tr>
              <td height="73" colspan="3" align="left" ><table width="572" height="94" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
               <tr>
                 <td height="21"><div align="right">Fecha de Registro </div></td>
                 <td colspan="4" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desde
                     <input name="txtfeccreobrdesde" type="text" id="txtfeccreobrdesde"  style="text-align:left" value="<? print $ls_feccreobrdesde ?>" size="11" maxlength="10" datepicker="true" readonly="true" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasta
    <input name="txtfeccreobrhasta" type="text" id="txtfeccreobrhasta"  style="text-align:left" value="<? print $ls_feccreobrhasta ?>" size="11" maxlength="10" datepicker="true" readonly="true"></td>
                 <td >&nbsp;</td>
               </tr>
               <tr>
                 <td width="156" height="21"><div align="right">C&oacute;digo</div></td>
                 <td colspan="5" ><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codobr?>" size="15" maxlength="12"></td>
               </tr>
               <tr>
                 <td height="21"><div align="right">Descripci&oacute;n</div></td>
                 <td colspan="5" ><input name="txtdesobr" id="txtdesobr" type="text" value="<? print $ls_desobr?>" size="57" maxlength="254"></td>
                 </tr>
                <tr>
                  <td height="21"><div align="right">Fecha Inicio </div></td>
                  <td width="108" >
				 <select name="cmbparfeciniobr" size="1" id="cmbparfeciniobr">
                    <?
				   if($ls_parfeciniobr=="")
				    {
				  ?>
                    <option value="" selected>Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				   }
				   else
				   {
				    if($ls_parfeciniobr==">=")
					{
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=" selected>Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				    }
					else
					{
					 if($ls_parfeciniobr=="<=")
					  {
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=" selected>Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				     }
					 else
					 {
					 if($ls_parfeciniobr=="=")
					  {
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=" selected>igual a</option>
                    <?
				      }
					 }
				    }
				  } 
				  ?>
                  </select>
				  
				  
				  
				 </td>
                  <td width="58"><div align="left">
                    <input name="txtfeciniobr" type="text" id="txtfeciniobr"  style="text-align:left" value="<? print $ls_feciniobr ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                  </div></td>
                  <td width="61"><div align="right">Fecha Fin</div></td>
                  <td width="112">
				  
				   <select name="cmbparfecfinobr" size="1" id="cmbparfecfinobr">
                    <?
				   if($ls_parfecfinobr=="")
				    {
				  ?>
                    <option value="" selected>Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				   }
				   else
				   {
				    if($ls_parfecfinobr==">=")
					{
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=" selected>Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				    }
					else
					{
					 if($ls_parfecfinobr=="<=")
					  {
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=" selected>Menor o igual a</option>
                    <option value="=">igual a</option>
                    <?
				     }
					 else
					 {
					 if($ls_parfecfinobr=="=")
					  {
				   ?>
                    <option value="">Seleccione</option>
                    <option value=">=">Mayor o igual a</option>
                    <option value="<=">Menor o igual a</option>
                    <option value="=" selected>igual a</option>
                    <?
				      }
					 }
				    }
				  } 
				  ?>
                  </select>				  
				  
				  </td>
                  <td width="61"><input name="txtfecfinobr" id="txtfecfinobr" type="text"  style="text-align:left" value="<? print $ls_fecfinobr ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
                </tr>
              </table>              </td>
            </tr>
			<tr>
              <td height="22" colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td height="18" colspan="3"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Ubicaci&oacute;n Geogr&aacute;fica </span></td>
            </tr>
            <tr>
              <td height="8" colspan="3" align="left"><table width="572" height="71" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
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
                  <td width="135" height="21"><div align="right">Estado</div></td>
                  <td width="142"><span class="style6">
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
                    <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:document.form1.submit()">
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
                    <input name="hidestado" type="hidden" id="hidestado" value="<? print $ls_codest ?>">
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
                    <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:document.form1.submit()">
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
                    <input name="hidmunicipio" type="hidden" id="hidmunicipio" value="<? print $ls_codmun ?>"></td>
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
                    <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:document.form1.submit()">
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
                    <input name="hidparroquia" type="hidden" id="hidparroquia" value="<? print $ls_codpar ?>"></td>
                  <td width="84" ><div align="right">Comunidad</div></td>
                  <td width="199" ><?Php
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
                    <select name="cmbcomunidad" size="1" id="cmbcomunidad" >
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
                    <input name="hidcomunidad" type="hidden" id="hidcomunidad" value="<? print $ls_codcom ?>"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="8" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="20" colspan="3"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos de las Asignaciones </span></td>
            </tr>
            <tr>
              <td height="8" colspan="3" align="left"><table width="572" height="73" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="137" height="18"><div align="right">C&oacute;digo</div></td>
                  <td width="130" ><input name="txtcodasi" type="text" id="txtcodasi"  style="text-align:left" value="<? print $ls_codasi ?>" size="15" maxlength="12">                    
                    </td>
                  <td colspan="2" >Punto de Cuenta
                    <input name="txtpuncueasi" type="text" id="txtpuncueasi" value="<? print $ls_puncueasi?>" size="4" maxlength="4"></td>
                  </tr>
                <tr>
                  <td height="24"><div align="right">Empresa Contratista </div></td>
                  <td colspan="3" ><input name="txtcodpro" type="text" id="txtcodpro" value="<? print $ls_codpro?>" size="10" maxlength="10">
                    <span class="toolbar"><a href="javascript:ue_catcontratista();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></span>
                    <input name="txtnompro" type="text" id="txtnompro" readonly="true" value="<? print $ls_nompro?>" size="60" maxlength="254" class="sin-borde"></td>
                  </tr>
                <!--tr>
                  <td><div align="right">Empresa Inspectora </div></td>
                  <td colspan="3" ><input name="txtcodproins" type="text" id="txtcodproins" value="<? print $ls_codproins?>" size="10" maxlength="10">
                    <span class="toolbar"><a href="javascript:ue_catinspector();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
                    <input name="txtnomproins" type="text" id="txtnomproins" readonly="true" value="<? print $ls_nomproins?>" size="60" maxlength="254" class="sin-borde">
                    </span></td>
                </tr-->
                <tr>
                  <td height="21"><div align="right">Monto</div></td>
                  <td >			  
				  
				  <select name="cmbmontotasi" size="1" id="select">
                   <option value="">Seleccione</option>
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
                  <td width="164">
				  <?php if($ls_montotasi=="") $ls_montotasi="0,00";?>
				  <input name="txtmontotasi" type="text" id="txtmontotasi"  style="text-align:right" value="<? print $ls_montotasi ?>" size="22" maxlength="21" onKeyPress="return(validaCajas(this,'d',event,21)) "  onBlur="javascript:ue_getformat(this)" ></td>
                  <td width="129">&nbsp;</td>
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
              <td height="161" colspan="3"><table width="572" height="167" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="9" colspan="5" align="right"><div align="left"><span class="Estilo1">&nbsp;Campos de la Obra </span></div></td>
                  </tr>
                <tr>
                  <td width="54" align="right">&nbsp;
				  </td>
                  <td width="197" align="left"><span class="Estilo1">No Mostrar </span></td>
                  <td width="59" height="9" align="center">&nbsp;</td>
                  <td colspan="2" align="left"><span class="Estilo1"> Mostrar </span> </td>
                  </tr>
                <tr>
                  <td colspan="2" rowspan="4" align="right" valign="top">
				   <select name="lst1" id="lst1" size="9" multiple style="width: 200px ">
				  <?Php
				  		if($ls_hidlista1!="")
						{
							$la_datalista1=$io_funsob->uf_decodificardata("?",$ls_hidlista1,$li_filas,"2");
							//print "datalista";
							//print_r($la_datalista1);
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								print "<option value='".$la_datalista1[$li_i][2]."'>".$la_datalista1[$li_i][1]."</option>";
							}
							
						}
					?>
					</select>				
					</td>
                  <td width="59" height="26" align="center">&nbsp;</td>
                  <td width="216" rowspan="4" align="left" valign="top">
				   <select name="lst2" id="lst2" size="9" multiple style="width: 200px " onChange="javascript:ue_habilitar_deshabilitar_botones(this,document.form1.btn3,document.form1.btn4)">
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
					</select></td>
                  <td width="32" align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="Submit" value=">>" onClick="javascript:ue_cargarlista(document.form1.lst1,document.form1.lst2,'')"></td>
                  <td width="32" align="left" valign="top"><input name="btn3" type="button" value="&#8593;"  id="btn3"  onClick="javascript:ue_moveritem(document.form1.lst2,'arriba',this,document.form1.btn4)"  style="width:20px; height:20px " ></td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="Submit2" value="<<" onClick="javascript:ue_cargarlista(document.form1.lst2,document.form1.lst1,'o.codobr-1')"></td>
                  <td width="32" align="left" valign="top"><input type="button" name="btn4" id="btn4" value="&#8595;"  onClick="javascript:ue_moveritem(document.form1.lst2,'abajo',document.form1.btn3,this)" style="width:20px; height:20px "></td>
                </tr>
                <tr>
                  <td height="57" align="center">&nbsp;</td>
                  <td width="32" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>              </td>
            </tr>
            <tr>
              <td height="13" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="41" colspan="3"><table width="572" height="145" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="9" colspan="5" align="right"><div align="left"><span class="Estilo1">&nbsp;Campos de las Asignaciones </span></div></td>
                  </tr>
                <tr>
                  <td width="54" align="right">&nbsp; </td>
                  <td width="197" align="left"><span class="Estilo1">No Mostrar </span></td>
                  <td width="59" height="9" align="center">&nbsp;</td>
                  <td colspan="2" align="left"><span class="Estilo1"> Mostrar </span> </td>
                </tr>
                <tr>
                  <td colspan="2" rowspan="4" align="right" valign="top">
                    <select name="lst3" id="lst3" size="7" multiple style="width: 200px ">
                      <?Php
				  		if($ls_hidlista3!="")
						{
							$la_datalista3=$io_funsob->uf_decodificardata("?",$ls_hidlista3,$li_filas,"2");
							//print "datalista";
							//print_r($la_datalista1);
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								print "<option value='".$la_datalista3[$li_i][2]."'>".$la_datalista3[$li_i][1]."</option>";
							}
							
						}
					?>
                      </select>
                  </td>
                  <td width="59" height="26" align="center">&nbsp;</td>
                  <td width="217" rowspan="4" align="left" valign="top">
                    <select name="lst4" id="lst4" size="7" multiple style="width: 200px " onChange="javascript:ue_habilitar_deshabilitar_botones(this,document.form1.btn5,document.form1.btn6)">
                      <?Php
				  		if($ls_hidlista4!="")
						{
							$la_datalista4=$io_funsob->uf_decodificardata("?",$ls_hidlista4,$li_filas,"2");
							//print "datalista";
							//print_r($la_datalista1);
							for($li_i=1;$li_i<=$li_filas;$li_i++)
							{
								print "<option value='".$la_datalista4[$li_i][2]."'>".$la_datalista4[$li_i][1]."</option>";
							}
							
						}
					?>
                      </select>
                  </td>
                  <td width="31" align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="Submit3" value=">>" onClick="javascript:ue_cargarlista(document.form1.lst3,document.form1.lst4)"></td>
                  <td width="32" align="left" valign="top"><input name="btn5" type="button" value="&#8593;"  id="btn5"  onClick="javascript:ue_moveritem(document.form1.lst4,'arriba',this,document.form1.btn6)"  style="width:20px; height:20px " ></td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="Submit22" value="<<" onClick="javascript:ue_cargarlista(document.form1.lst4,document.form1.lst3)"></td>
                  <td width="32" align="left" valign="top"><input type="button" name="btn6" id="btn6" value="&#8595;"  onClick="javascript:ue_moveritem(document.form1.lst4,'abajo',document.form1.btn5,this)" style="width:20px; height:20px "></td>
                </tr>
                <tr>
                  <td height="35" align="center">&nbsp;</td>
                  <td width="31" align="left" valign="top">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="8" colspan="3">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<input type="hidden" name="hiddata1" id="hiddata1">
	<input type="hidden" name="hiddata2" id="hiddata2">
	<input type="hidden" name="hidlista1" id="hidlista1" value="<?php print $ls_hidlista1 ?> ">
	<input type="hidden" name="hidlista2" id="hidlista2" value="<?php print $ls_hidlista2 ?> ">
	<input type="hidden" name="hidlista3" id="hidlista3" value="<?php print $ls_hidlista3 ?> ">
	<input type="hidden" name="hidlista4" id="hidlista4" value="<?php print $ls_hidlista4 ?> ">
</form>
</body>

<script language="JavaScript">

  	function ue_imprimir()
		{
            f=document.form1;
			li_imprimir=f.imprimir.value;
			if (li_imprimir==1)
			{

				f.operacion.value="ue_imprimir";			
				//f.action="sigesp_sob_r_reporteobra.php";
				data=new Array();
				/*funcion para obtener los campos destindos a generar el reporte*/
				data=ue_obtenerarreglocampos(f.lst2,false);
				tira=ue_codificardata(data,"-",false);
				f.hiddata1.value=tira;
				data=ue_obtenerarreglocampos(f.lst4,false);
				tira=ue_codificardata(data,"-",false);
				f.hiddata2.value=tira;
				/*Funciones para mantener actualizados las listas*/
				data=ue_obtenerarreglocampos(f.lst1,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista1.value=tira;
				data=ue_obtenerarreglocampos(f.lst2,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista2.value=tira;
				data=ue_obtenerarreglocampos(f.lst3,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista3.value=tira;
				data=ue_obtenerarreglocampos(f.lst4,true);
				tira=ue_codificardata(data,"?",true);
				f.hidlista4.value=tira;
				valido=true;
				if(valido=ue_valida_null(f.txttituloencabezado,"Título del Reporte"))
				{
				
					if(valido=ue_valida_null(f.txtfeccreobrdesde,"Inicio de rango de Fecha de Registro"))
					{
						if(valido=ue_valida_null(f.txtfeccreobrhasta,"Finalización de rango de Fecha de Registro"))
						{
							if(f.lst4.options.length==0)
							{
								alert("Debe seleccionar al menos un campo para mostrar en el reporte!!!");
								valido=false;
							}
							else
							{
								if(valido=ue_comparar_intervalo("txtfeccreobrdesde","txtfeccreobrhasta","Seleccione un rango de Fechas de Registro válido!!!"))
								{
									if(f.txtfeciniobr.value!="")
									{
										if(f.cmbparfeciniobr.value=="")
										{
											alert("Debe seleccionar una opción de búsqueda para la Fecha de Inicio de la Obra!!!");
											valido=false;
										}
									}
									if(f.txtfecfinobr.value!="" && valido)
									{
										if(f.cmbparfecfinobr.value=="")
										{
											alert("Debe seleccionar una opción de búsqueda para la Fecha de Finalización de la Obra!!!");
											valido=false;
										}
									}
									if(f.txtmontotasi.value!="0,00" && valido)
									{
										
										if(f.cmbmontotasi.value=="")
										{
											alert("Debe seleccionar una opción de búsqueda para el Monto de la Asignación!!!");
											valido=false;
										}
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
		
		function ue_catcontratista()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_contratista.php";
			popupWin(pagina,"catalogo",520,200);
		    //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
		}		
		
		function ue_catinspector()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_inspectora.php";
			popupWin(pagina,"catalogo",520,200);
		    //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
		}		
/***********************************************************************************************************************************/
      	function ue_cargarinspectora(codp,nomp)
		{
			f=document.form1;
			f.txtcodproins.value=codp;
            f.txtnomproins.value=nomp;
		}		
		
		function ue_cargarcontratista(codp,nomp,rep)
		{
			f=document.form1;
			f.txtcodpro.value=codp;
            f.txtnompro.value=nomp;
		}		
		

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>