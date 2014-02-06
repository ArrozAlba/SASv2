<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Reporte de Contratos</title>
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
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	
	</td>
  </tr>
  <tr>
    <td height="19" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<!--?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?-->
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
		$ls_tituloencabezado=$_POST["txttituloencabezado"];
	}
	else
	{
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
		$ls_monto="0,00";
		$ls_parfeccreobr=""; 
        $ls_parfeciniobr=""; 
        $ls_parfecfinobr="";
		$ls_consulta="";  
		$ls_desobr="";
		$la_parametro[1][1]="";		
		$ls_hidlista1="Código de la Obra?o.codobr-1?Comunidad?c.nomcom-5?Descripción de la Obra?o.desobr-1?Dirección de la Obra?o.dirobr-1?Estado (Ubicación)?e.desest-2?
							Fecha de Registro de la Obra?o.feccreobr-1?Fecha de Finalización de la Obra?o.fecfinobr-1?Fecha de Inicio de la Obra?
							o.feciniobr-1?Monto Total de la Obra?o.monto-1?Municipio?m.denmun-3?Organismo Ejecutor?pro.nompro-7?
							Parroquia?p.denpar-4?Responsable de la Obra?o.resobr-1?Sistema Constructivo?sc.nomsiscon-11?Tenencia de la Tierra
							?t.nomten-8?Tipo de Estructura?te.nomtipest-10?Tipo de Obra?tob.nomtob-9";
		$ls_hidlista2="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_buscar")
	{
		if(($io_funsob->uf_convertir_cadenanumero($ls_monto))==0)
			$ls_monto="";		
		
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
		$la_parametro[4][1]="o.codpro";
		$la_parametro[4][2]="%".$ls_codpro."%";
		$la_parametro[4][3]=" like ";
		$la_parametro[4][4]=1;
		$la_parametro[5][1]="o.resobr";
		$la_parametro[5][2]="%".$ls_resobr."%";
		$la_parametro[5][3]=" like ";
		$la_parametro[5][4]=1;
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
		$la_parametro[10][1]="o.monto";
		$la_parametro[10][2]=$io_funsob->uf_convertir_cadenanumero($ls_monto);
		$la_parametro[10][3]=$ls_parmonto;
		$la_parametro[10][4]=1;
        $la_parametro[11][1]="fo.codfuefin";
		$la_parametro[11][2]=$ls_codfuefin;
		$la_parametro[11][3]="=";
		$la_parametro[11][4]=6;
		$la_parametro[12][1]="o.desobr";
		$la_parametro[12][2]="%".$ls_desobr."%";
		$la_parametro[12][3]=" like ";
		$la_parametro[12][4]=1;
		$la_parametro[13][1]="o.feccreobr";
		$la_parametro[13][2]=$io_funcion->uf_convertirdatetobd($ls_feccreobrhasta);
		$la_parametro[13][3]="<=";
		$la_parametro[13][4]=1;
		
//-------------------------------------Campos a ser mostrados en el reporte---------------------------------------//
		$ls_cadena=$_POST["hiddata"];
		//print "$ls_cadena \n";
		$la_salida=$io_funsob-> uf_decodificardata("-",$ls_cadena,$li_index);
//		print_r($la_salida);
		
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
		$la_tabla[6][1]="sob_fuentefinanciamientoobra fo";
		$la_tabla[6][2]="fo.codobr=o.codobr";
		$la_tabla[6][3]="0";
		$la_tabla[6][4]=1;					
		$la_tabla[7][1]="sob_propietario pro";
		$la_tabla[7][2]="o.codemp=pro.codemp AND pro.codpro=o.codpro";
		$la_tabla[7][3]="0";
		$la_tabla[7][4]="1";
		$la_tabla[8][1]="sob_tenencia t";
		$la_tabla[8][2]="o.codten=t.codten";
		$la_tabla[8][3]="0";
		$la_tabla[8][4]="1";
		$la_tabla[9][1]="sob_tipoobra tob";
		$la_tabla[9][2]="o.codemp=tob.codemp AND o.codtob=tob.codtob";
		$la_tabla[9][3]="0";
		$la_tabla[9][4]="1";
		$la_tabla[10][1]="sob_tipoestructura te";
		$la_tabla[10][2]="o.codemp=te.codemp AND o.codtipest=te.codtipest";
		$la_tabla[10][3]="0";
		$la_tabla[10][4]="1";
		$la_tabla[11][1]="sob_sistemaconstructivo sc";
		$la_tabla[11][2]="o.codemp=sc.codemp AND o.codsiscon=sc.codsiscon";
		$la_tabla[11][3]="0";
		$la_tabla[11][4]="1";
		
		$ls_cadena=$io_reporte->uf_evalconsulta($la_salida,$li_index,$la_tabla,11,$la_parametro,13);
		$lb_valido=$io_reporte->uf_obtenerdata ($ls_cadena,$la_data);
		if($lb_valido===true)
		{
			$la_titulos=$io_reporte->uf_titulos("OBRA",$la_data);		
			$li_filas=(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
			if(array_key_exists("monto",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["monto"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["monto"][$li_i]);
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
			$_SESSION["data"]=$la_data;
			$_SESSION["titulos"]=$la_titulos;
			$_SESSION["tituloencabezado"]=$ls_tituloencabezado;
			$_SESSION["fechadesde"]=$ls_feccreobrdesde;
			$_SESSION["fechahasta"]=$ls_feccreobrhasta;
			?>
				<script language="javascript">
					var pagina='sigesp_sob_r_plantillapdf.php';
					window.open(pagina,'catalogo','menubar=no,toolbar=no,scrollbars=yes,width=900,height=700,resizable=yes,top=20,left=30');			
				</script>
			<?
		}
		elseif($lb_valido===0)
		{
			$io_msg->message("No se han creado Obras que cumplan con esos parámetros de búsqueda!!!");
		}			
	}
	elseif($ls_operacion=="ue_guardar")
	{
		
	}
	elseif($ls_operacion=="ue_eliminar")
	{
			
	}
	
?>
<table width="675" height="610" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
	  
	
        <td width="675" height="608"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Reporte de Contratos </td>
            </tr>
            <tr>
              <td colspan="3" ><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
              <input name="consulta" type="hidden" id="consulta2"  value="<? print $ls_consulta?>">
              </td>
            </tr>
            <tr>
              <td width="29" height="19" >&nbsp;</td>
              <td width="132" ><div align="right">T&iacute;tulo del Reporte </div></td>
              <td width="471" ><input name="txttituloencabezado" type="text" id="txttituloencabezado" value="<? print $ls_tituloencabezado?>" size="82" maxlength="100"></td>
            </tr>
            <tr>
              <td height="19" colspan="3" ><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos Generales</span></td>
            </tr>
            <tr>
              <td height="85" colspan="3" align="left" ><table width="572" height="113" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
               <tr>
                 <td height="21"><div align="right">Descripci&oacute;n</div></td>
                 <td colspan="5" ><input name="txtdesobr" id="txtdesobr" type="text" value="<? print $ls_desobr?>" size="57" maxlength="254"></td>
                 </tr>
               <tr>
                  <td width="156" height="21"><div align="right">Fecha de Registro </div></td>
                  <td colspan="4" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desde
				    <input name="txtfeccreobrdesde" type="text" id="txtfeccreobrdesde"  style="text-align:left" value="<? print $ls_feccreobrdesde ?>" size="11" maxlength="10" datepicker="true" readonly="true" >
				    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasta<input name="txtfeccreobrhasta" type="text" id="txtfeccreobrhasta"  style="text-align:left" value="<? print $ls_feccreobrhasta ?>" size="11" maxlength="10" datepicker="true" readonly="true"></td>
                  <td >&nbsp;</td>
               </tr>
                <tr>
                  <td height="21"><div align="right">Fecha Inicio </div></td>
                  <td width="108" ><select name="cmbparfeciniobr" size="1" id="cmbparfeciniobr">
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
                  </select></td>
                  <td width="58"><div align="left">
                    <input name="txtfeciniobr" type="text" id="txtfeciniobr"  style="text-align:left" value="<? print $ls_feciniobr ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                  </div></td>
                  <td width="61"><div align="right">Fecha Fin</div></td>
                  <td width="112"><select name="cmbparfecfinobr" size="1" id="cmbparfecfinobr">
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
                  </select></td>
                  <td width="61"><input name="txtfecfinobr" id="txtfecfinobr" type="text"  style="text-align:left" value="<? print $ls_fecfinobr ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
                </tr>                <tr>
                  <td height="20"><div align="right">Organismo Ejecutor </div></td>
                  <td colspan="5" ><input name="txtcodpro" type="text" id="txtcodpro"  style="text-align:left" value="<? print $ls_codpro ?>" size="4" maxlength="4" readonly="true">
                      <a href="javascript:ue_catpropietario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                      <input name="txtnompro" type="text" id="txtnompro"  style="text-align:left" class="sin-borde" value="<? print $ls_nompro ?>" size="50" maxlength="100" readonly="true" ></td>
                </tr>
                <tr>
                  <td height="18"><div align="right">Responsable</div></td>
                  <td colspan="5" ><input name="txtresobr" type="text" id="txtresobr"  style="text-align:left" value="<? print $ls_resobr ?>" size="57" maxlength="50"></td>
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
              <td height="8" colspan="3" align="left"><table width="572" height="44" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="135" height="21"><div align="right">Estado</div></td>
                  <td width="142"><span class="style6">
                    <?Php
				    $ls_sql="SELECT codest ,desest 
                             FROM sigesp_estados
                             WHERE codpai='001' ORDER BY codest ASC";
				    $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_estado);
					
				    if($lb_valido)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
				    ?>
                    <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmb();">
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
                                  WHERE codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_municipio);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}										
			    ?>
                    <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmb();">
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
                                  WHERE codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valido=$io_reporte->uf_datacombo($ls_sql,&$la_parroquia);
					 } 	
					
					if($lb_valido)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}										
					else{$li_totalfilas=0;}
			    ?>
                    <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmb();">
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
              <td height="20" colspan="3"><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos Financieros </span></td>
            </tr>
            <tr>
              <td height="8" colspan="3" align="left"><table width="572" height="45" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="137" height="20"><div align="right">Fuente de Financiamiento </div></td>
                  <td colspan="3" ><input name="txtcodfuefin" type="text" id="txtcodfuefin"  style="text-align:left" value="<? print $ls_codfuefin ?>" size="4" maxlength="4" readonly="true">                    <a href="javascript:ue_catfuentesfin();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                    <input name="txtdenfuefin" type="text" id="txtdenfuefin"  style="text-align:left" class="sin-borde" value="<? print $ls_denfuefin ?>" size="50" maxlength="100" readonly="true" ></td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Monto Total de la Obra </div></td>
                  <td width="130" >
				  <select name="cmbparmonto" size="1" id="cmbparmonto">
				  <?
				   if($ls_parmonto=="")
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
				    if($ls_parmonto==">=")
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
					 if($ls_parmonto=="<=")
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
					 if($ls_parmonto=="=")
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
                  <td width="164"><input name="txtmonto" type="text" id="txtmonto"  style="text-align:right" value="<? print $ls_monto ?>" size="22" maxlength="21" onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
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
              <td height="164" colspan="3"><table width="572" height="167" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="54" align="right">&nbsp;
				  </td>
                  <td width="197" align="left"><span class="Estilo1">No Mostrar </span></td>
                  <td width="59" height="9" align="center">&nbsp;</td>
                  <td align="left"><span class="Estilo1"> Mostrar </span> </td>
                  </tr>
                <tr>
                  <td colspan="2" rowspan="4" align="right" valign="top">
				   <select name="lst1" id="lst1" size="10" multiple style="width: 200px ">
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
                  <td rowspan="4" align="left" valign="top">
				   <select name="lst2" id="lst2" size="10" multiple style="width: 200px ">
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
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="Submit" value=">>" onClick="javascript:ue_cargarlista(document.form1.lst1,document.form1.lst2)"></td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="Submit2" value="<<" onClick="javascript:ue_cargarlista(document.form1.lst2,document.form1.lst1)"></td>
                </tr>
                <tr>
                  <td height="72" align="center">&nbsp;</td>
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
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<input type="hidden" name="hiddata" id="hiddata">
	<input type="hidden" name="hidlista1" id="hidlista1" >
	<input type="hidden" name="hidlista2" id="hidlista2" >
</form>
</body>

<script language="JavaScript">

  	function ue_buscar()
		{
            f=document.form1;
			f.operacion.value="ue_buscar";			
			f.action="sigesp_sob_r_reporteobra.php";
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
				if(valido=ue_valida_null(f.txtfeccreobrdesde,"Inicio de rango de Fecha de Registro"))
				{
					if(valido=ue_valida_null(f.txtfeccreobrhasta,"Finalización de rango de Fecha de Registro"))
					{
						if(f.lst2.options.length==0)
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
								if(f.txtmonto.value!="" && f.txtmonto.value!="0,00" && f.txtmonto.value!="0,0" && f.txtmonto.value!="0," && f.txtmonto.value!="0" && f.txtmonto.value!="00" && f.txtmonto.value!="000" && f.txtmonto.value!=",00"   && valido)
								{
									alert
									if(f.cmbparmonto.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para el Monto de la Obra!!!");
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

/*

		
		
		function ue_nuevo()
		{
		 
		}


		function ue_guardar()
		{
   	     
		}					
					
	    function ue_eliminar()
	    {
		
    	}
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
        function ue_imprimir()
        {
	        f=document.form1;
	        f.operacion.value="";
			var consulta=f.consulta.value;
	        pagina="sigesp_sob_rpp_obras_pdf.php?consulta="+consulta;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
        }		
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>