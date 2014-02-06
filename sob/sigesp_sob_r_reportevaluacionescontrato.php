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
<title>Reporte de Valuaciones por Contrato</title>
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
    <td height="30"><span class="cd-menu"><span class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40" class="sin-borde"></span></span></td>
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
		$ls_fecinicon=$_POST["txtfecinicon"];
		$ls_cmbfecinicon=$_POST["cmbfecinicon"]; 
        $ls_fecfincon=$_POST["txtfecfincon"];
		$ls_cmbfecfincon=$_POST["cmbfecfincon"]; 
		$ls_feccondesde =$_POST["txtfeccondesde"];  
		$ls_fecconhasta=$_POST["txtfecconhasta"];  
		$ls_fecinival=$_POST["txtfecinival"];
		$ls_fecfinval=$_POST["txtfecfinval"];   
		$ls_cmbfecinival=$_POST["cmbfecinival"];
		$ls_cmbfecfinval=$_POST["cmbfecfinval"];   	
   		$ls_consulta=$_POST["consulta"];  
		$ls_desobr=$_POST["txtdesobr"];
		$ls_hidlista1=$_POST["hidlista1"];
		$ls_hidlista2=$_POST["hidlista2"];
		$ls_hidlista3=$_POST["hidlista3"];
		$ls_hidlista4=$_POST["hidlista4"];
		$ls_tituloencabezado=$_POST["txttituloencabezado"];
		$ls_codcon=$_POST["txtcodcon"];
		$ls_codval=$_POST["txtcodval"];
		$ls_moncon=$_POST["txtmoncon"];
		if ($ls_moncon=="")
			$ls_moncon="0,00";
		$ls_cmbmoncon=$_POST["cmbmoncon"];		
	}
	else
	{
		$ls_operacion="";
		$ls_tituloencabezado="";		
        $ls_fecinicon="";
        $ls_fecfincon="";
		$ls_cmbfecinicon="";
        $ls_cmbfecfincon="";  
		$ls_fecinival="";
        $ls_fecfinval="";
		$ls_cmbfecinival="";
        $ls_cmbfecfinval="";		
		$ls_moncon="0,00";		
		$ls_consulta="";  
		$ls_desobr="";
		$la_parametro[1][1]="";
		$ls_codcon="";
		$ls_codval="";
		$ls_cmbmoncon="";
		$ls_feccondesde="";  
		$ls_fecconhasta="";
		
		$ls_hidlista1="Código de la Obra?o.codobr-3?Descripcion de la Obra?o.desobr-3?
							Monto del Contrato?con.monto-3?Fecha de Registro?con.feccon-3?Fecha de Inicio del Contrato?con.fecinicon-3?Fecha de Fin del Contrato?
							con.fecfincon-3";
		$ls_hidlista2="Código del Contrato?con.codcon-3";
		$ls_hidlista3="Código de la Valuación?val.codval-4?Fecha de Inicio?val.fecinival-4?Fecha de Fin?val.fecfinval-4?
							Observación?val.obsval-4?Amortización?val.amoval-4?Amortización Total a la Fecha?
							val.amototval-4?Restante por Amortizar a la Fecha?val.amoresval-4?Observación de la Amortización?val.obsamoval-4
							?Base Imponible?val.basimpval-4?Total Retenciones?val.totreten-4?Monto Total de la Valuacion?val.montotval-4
							?Monto por Partidas Valuadas?val.subtotpar-4";
		$ls_hidlista4="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_buscar")
	{		
		if($io_funsob->uf_convertir_cadenanumero($ls_moncon)==0)
			$ls_moncon="";		
		$la_parametro[1][1]="con.codcon";
		$la_parametro[1][2]=$ls_codcon;
		$la_parametro[1][3]=" like ";
		$la_parametro[1][4]=3;
		$la_parametro[2][1]="o.desobr";
		$la_parametro[2][2]=$ls_desobr;
		$la_parametro[2][3]=" like ";
		$la_parametro[2][4]=3;
        $la_parametro[3][1]="con.fecfincon";
		$la_parametro[3][2]=$io_funcion->uf_convertirdatetobd($ls_fecfincon);
		$la_parametro[3][3]=$ls_cmbfecfincon;
		$la_parametro[3][4]=3;		
		$la_parametro[4][1]="con.fecinicon";
		$la_parametro[4][2]=$io_funcion->uf_convertirdatetobd($ls_fecinicon);
		$la_parametro[4][3]=$ls_cmbfecinicon;
		$la_parametro[4][4]=3;		
		$la_parametro[5][1]="con.monto";
		$la_parametro[5][2]=$ls_moncon;
		$la_parametro[5][3]=$ls_cmbmoncon;
		$la_parametro[5][4]=3;		
		$la_parametro[6][1]="val.codval";
		$la_parametro[6][2]=$ls_codval;
		$la_parametro[6][3]=" like ";
		$la_parametro[6][4]=4;
		$la_parametro[7][1]="val.fecinival";
		$la_parametro[7][2]=$io_funcion->uf_convertirdatetobd($ls_fecinival);
		$la_parametro[7][3]=$ls_cmbfecinival;
		$la_parametro[7][4]=4;
		$la_parametro[8][1]="val.fecfinval";
		$la_parametro[8][2]=$io_funcion->uf_convertirdatetobd($ls_fecfinval);
		$la_parametro[8][3]=$ls_cmbfecfinval;
		$la_parametro[8][4]=4;
		$la_parametro[9][1]="con.feccon";
		$la_parametro[9][2]=$io_funcion->uf_convertirdatetobd($ls_feccondesde);
		$la_parametro[9][3]=">=";
		$la_parametro[9][4]=3;
		$la_parametro[10][1]="con.feccon";
		$la_parametro[10][2]=$io_funcion->uf_convertirdatetobd($ls_fecconhasta);
		$la_parametro[10][3]="<=";
		$la_parametro[10][4]=3;
		
		
//-------------------------------------Campos a ser mostrados en el reporte---------------------------------------//
		$ls_cadena=$_POST["hiddata1"];	
		$ls_cadena=$ls_cadena."-con.precon-3";
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
		$la_tabla[2][1]="sob_asignacion asi";
		$la_tabla[2][2]="asi.codemp=o.codemp AND asi.codobr=o.codobr";
		$la_tabla[2][3]="0";
		$la_tabla[2][4]=1;
        $la_tabla[3][1]="sob_contrato con";
		$la_tabla[3][2]="con.codemp=asi.codemp AND con.codasi=asi.codasi";
		$la_tabla[3][3]="0";
		$la_tabla[3][4]=2;
		$la_tabla[4][1]="sob_valuacion val";
		$la_tabla[4][2]="val.codemp=con.codemp AND val.codcon=con.codcon";
		$la_tabla[4][3]="0";
		$la_tabla[4][4]=3;		
		
		$ls_cadena=$io_reporte->uf_evalconsulta($la_salida,$li_index,$la_tabla,4,$la_parametro,10);
		$ls_cadena=$ls_cadena." ORDER BY con.codcon";
		//print"------------CADENA------------$ls_cadena";
		$lb_valido=$io_reporte->uf_obtenerdata ($ls_cadena,$la_data);
		if($lb_valido===true)
		{
			$la_tituloscontrato=$io_reporte->uf_titulos("CONTRATO",$la_data);		
			$la_titulosvaluacion=$io_reporte->uf_titulos("VALUACION",$la_data);		
			//print "----------OBRA--------------";
			//print_r($la_tituloscontrato);
			//print "----------PARTIDA--------------";
			//print_r($la_titulosvaluacion);
			$li_filas=(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
			for($li_i=1;$li_i<=$li_filas;$li_i++)
			{
				$la_data["codcon"][$li_i]=$la_data["precon"][$li_i].$la_data["codcon"][$li_i];
			}			
			if(array_key_exists("monto",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["monto"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["monto"][$li_i]);
				}
			}
			if(array_key_exists("amoval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["amoval"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["amoval"][$li_i]);
				}
			}
			if(array_key_exists("amototval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["amototval"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["amototval"][$li_i]);
				}
			}
			if(array_key_exists("amoresval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["amoresval"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["amoresval"][$li_i]);
				}
			}
			if(array_key_exists("basimpval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["basimpval"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["basimpval"][$li_i]);
				}
			}
			if(array_key_exists("montotval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["montotval"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["montotval"][$li_i]);
				}
			}
			if(array_key_exists("subtotpar",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["subtotpar"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["subtotpar"][$li_i]);
				}
			}
			
			if(array_key_exists("totreten",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["totreten"][$li_i]=$io_funsob->uf_convertir_numerocadena($la_data["totreten"][$li_i]);
				}
			}
			if(array_key_exists("feccon",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["feccon"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["feccon"][$li_i]);
				}
			}
			if(array_key_exists("fecfincon",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecfincon"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecfincon"][$li_i]);
				}
			}
			if(array_key_exists("fecinicon",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecinicon"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecinicon"][$li_i]);
				}
			}			
			if(array_key_exists("fecinival",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecinival"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecinival"][$li_i]);
				}
			}
			if(array_key_exists("fecfinval",$la_data))
			{
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$la_data["fecfinval"][$li_i]=$io_funcion->uf_convertirfecmostrar($la_data["fecfinval"][$li_i]);
				}
			}			
			$la_acumulado["campo"][1]="montotval";
			$la_acumulado["titulo"][1]="Monto Total Valuaciones:";
			//$la_acumulado["acumulado"][1]=0;
			$_SESSION["data"]=$la_data;
			$_SESSION["tituloscabecera"]=$la_tituloscontrato;
			$_SESSION["titulosdetalle"]=$la_titulosvaluacion;
			$_SESSION["tituloencabezado"]=$ls_tituloencabezado;
			$_SESSION["fechadesde"]=$ls_feccondesde;
			$_SESSION["fechahasta"]=$ls_fecconhasta;
			$_SESSION["orientacion"]="landscape" ;
			$_SESSION["titulodetalle"]="VALUACIONES";
			$_SESSION["acumulado"]=$la_acumulado;
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
<table width="675" height="643" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
	  
	
        <td width="675" height="641"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Reporte de Valuaciones por Contrato </td>
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
              <td height="19" colspan="3" ><span class="Estilo1">&nbsp;&nbsp;&nbsp;Datos del Contrato  </span></td>
            </tr>
            <tr>
              <td height="73" colspan="3" align="left" ><table width="572" height="117" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
               <tr>
                 <td height="21"><div align="right">Fecha de Registro </div></td>
                 <td colspan="4" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desde
                     <input name="txtfeccondesde" type="text" id="txtfeccondesde"  style="text-align:left" value="<? print $ls_feccondesde ?>" size="11" maxlength="10" datepicker="true" readonly="true" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasta
    <input name="txtfecconhasta" type="text" id="txtfecconhasta"  style="text-align:left" value="<? print $ls_fecconhasta ?>" size="11" maxlength="10" datepicker="true" readonly="true"></td>
                 <td >&nbsp;</td>
               </tr>
               <tr>
                 <td width="116" height="21"><div align="right">C&oacute;digo</div></td>
                 <td colspan="5" ><input name="txtcodcon" type="text" id="txtcodcon" value="<? print $ls_codcon?>" size="12" maxlength="12"></td>
               </tr>
               <tr>
                 <td height="21"><div align="right">Obra</div></td>
                 <td colspan="5" ><input name="txtdesobr" id="txtdesobr" type="text" value="<? print $ls_desobr?>" size="57" maxlength="254"></td>
                 </tr>
               <tr>
                 <td height="21"><div align="right">Fecha Inicio </div></td>
                 <td >
                   <?
				  	$la_combodata=array('texto'=>(array("Mayor o igual a","Menor o igual a","Igual a")),'valor'=>(array(">=","<=","=")));
				  ?>
                   <select name="cmbfecinicon" id="cmbfecinicon">
                     <option value="">Seleccione</option>
                     <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbmonto== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                   </select>
                 </td>
                 <td width="58"><div align="left">
                     <input name="txtfecinicon" type="text" id="txtfecinicon"  style="text-align:left" value="<? print $ls_fecinicon ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                 </div></td>
                 <td width="61"><div align="right">Fecha Fin</div></td>
                 <td width="112">
                   <select name="cmbfecfincon" id="cmbfecfincon">
                     <option value="">Seleccione</option>
                     <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbmonto== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                   </select>
                 </td>
                 <td><input name="txtfecfincon" id="txtfecfincon" type="text"  style="text-align:left" value="<? print $ls_fecfincon ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
               </tr>
                <tr>
                  <td height="21"><div align="right">Monto</div></td>
                  <td width="148" ><select name="cmbmoncon" size="1" id="select">
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
                  <td colspan="3"><input name="txtmoncon" type="text" id="txtmoncon"  style="text-align:right" value="<? print $ls_moncon ?>" size="22" maxlength="21" onKeyPress="return(currencyFormat(this,'.',',',event))"></td>
                  <td width="61">&nbsp;</td>
                </tr>
              </table>              </td>
            </tr>
			<tr>
              <td height="22" colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td height="18" colspan="3"><span class="Estilo1">&nbsp;&nbsp;&nbsp;&nbsp;Datos de la Valuaci&oacute;n </span></td>
            </tr>
            <tr>
              <td height="8" colspan="3" align="left"><table width="572" height="48" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td width="115" height="21" align="right">C&oacute;digo</td>
                  <td width="150"><input name="txtcodval" type="text" id="txtcodval" value="<? print $ls_codval?>" size="3" maxlength="3"></td>
                  <td width="57">&nbsp;</td>
                  <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td height="21"><div align="right">Fecha Inicio </div></td>
                  <td >
                    <?
				  	$la_combodata=array('texto'=>(array("Mayor o igual a","Menor o igual a","Igual a")),'valor'=>(array(">=","<=","=")));
				  ?>
                    <select name="cmbfecinival" id="cmbfecinival">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbmonto== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td><div align="left">
                      <input name="txtfecinival" type="text" id="txtfecinival"  style="text-align:left" value="<? print $ls_fecinival ?>" size="11" maxlength="10"   datepicker="true" readonly="true">
                  </div></td>
                  <td width="63"><div align="right">Fecha Fin</div></td>
                  <td width="113">
                    <select name="cmbfecfinval" id="cmbfecfinval">
                      <option value="">Seleccione</option>
                      <?Php
				   		
				   	for($li_i=0;$li_i<3;$li_i++)
					{
						if($ls_cmbmonto== $la_combodata["valor"][$li_i])
							print "<option selected value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
						else
							print "<option value='".$la_combodata["valor"][$li_i]."'>".$la_combodata['texto'][$li_i]."</option>";
					}
				   ?>
                    </select>
                  </td>
                  <td width="58"><input name="txtfecfinval" id="txtfecfinval" type="text"  style="text-align:left" value="<? print $ls_fecfinval ?>" size="11" maxlength="10" datepicker="true"  readonly="true"></td>
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
              <td height="128" colspan="3"><table width="572" height="128" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="9" colspan="4" align="right"><div align="left"><span class="Estilo1">&nbsp;Campos del Contrato </span></div></td>
                  </tr>
                <tr>
                  <td width="54" align="right">&nbsp;
				  </td>
                  <td width="197" align="left"><span class="Estilo1">No Mostrar </span></td>
                  <td width="59" height="9" align="center">&nbsp;</td>
                  <td align="left"><span class="Estilo1"> Mostrar </span> </td>
                  </tr>
                <tr>
                  <td colspan="2" rowspan="4" align="right" valign="top">
				   <select name="lst1" id="lst1" size="6" multiple style="width: 200px ">
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
                  <td width="59" height="16" align="center">&nbsp;</td>
                  <td rowspan="4" align="left" valign="top">
				   <select name="lst2" id="lst2" size="6" multiple style="width: 200px ">
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
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="Submit" value=">>" onClick="javascript:ue_cargarlista(document.form1.lst1,document.form1.lst2,'')"></td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="Submit2" value="<<" onClick="javascript:ue_cargarlista(document.form1.lst2,document.form1.lst1,'con.codcon-3')"></td>
                </tr>
                <tr>
                  <td height="28" align="center">&nbsp;</td>
                </tr>
              </table>              </td>
            </tr>
            <tr>
              <td height="13" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="41" colspan="3"><table width="572" height="145" border="0" align="center" cellpadding="0" cellspacing="2" class="formato-blanco">
                <tr>
                  <td height="9" colspan="4" align="right"><div align="left"><span class="Estilo1">&nbsp;Campos de las Valuaciones</span></div></td>
                  </tr>
                <tr>
                  <td width="54" align="right">&nbsp; </td>
                  <td width="197" align="left"><span class="Estilo1">No Mostrar </span></td>
                  <td width="59" height="9" align="center">&nbsp;</td>
                  <td align="left"><span class="Estilo1"> Mostrar </span> </td>
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
                  <td rowspan="4" align="left" valign="top">
                    <select name="lst4" id="lst4" size="7" multiple style="width: 200px ">
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
                </tr>
                <tr>
                  <td height="4" align="center"><input type="button" name="Submit3" value=">>" onClick="javascript:ue_cargarlista(document.form1.lst3,document.form1.lst4)"></td>
                </tr>
                <tr>
                  <td height="10" align="center"><input type="button" name="Submit22" value="<<" onClick="javascript:ue_cargarlista(document.form1.lst4,document.form1.lst3)"></td>
                </tr>
                <tr>
                  <td height="35" align="center">&nbsp;</td>
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
	<input type="hidden" name="hidlista1" id="hidlista1" >
	<input type="hidden" name="hidlista2" id="hidlista2" >
	<input type="hidden" name="hidlista3" id="hidlista3" >
	<input type="hidden" name="hidlista4" id="hidlista4" >
</form>
</body>

<script language="JavaScript">

  	function ue_buscar()
		{
            f=document.form1;
			f.operacion.value="ue_buscar";			
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
			/*if(valido=ue_valida_null(f.txttituloencabezado,"Título del Reporte"))
			{
				if(valido=ue_valida_null(f.txtfeccreobrdesde,"Inicio de rango de Fecha de Registro"))
				{
					if(valido=ue_valida_null(f.txtfeccreobrhasta,"Finalización de rango de Fecha de Registro"))
					{
						if(f.lst4.options.length==0)
						{
							alert("Debe seleccionar al menos un campo de la Partida para mostrar en el reporte!!!");
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
								if(f.txtprepar.value!="" && f.txtprepar.value!="0,00" && f.txtprepar.value!="0,0" && f.txtprepar.value!="0," && f.txtprepar.value!="0" && f.txtprepar.value!="00" && f.txtprepar.value!="000" && f.txtprepar.value!=",00"   && valido)
								{
									alert
									if(f.cmbprepar.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para el Monto de la Obra!!!");
										valido=false;
									}
								}
								if(f.txtcanpar.value!="" && f.txtcanpar.value!="0,00" && f.txtcanpar.value!="0,0" && f.txtcanpar.value!="0," && f.txtcanpar.value!="0" && f.txtcanpar.value!="00" && f.txtcanpar.value!="000" && f.txtcanpar.value!=",00"   && valido)
								{
									alert
									if(f.cmbcanpar.value=="")
									{
										alert("Debe seleccionar una opción de búsqueda para el Monto de la Obra!!!");
										valido=false;
									}
								}
							}
						}
					}
				}
			}*/
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