<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
$io_include		 = new sigesp_include();
$ls_conect 		 = $io_include->uf_conectar();
$io_msg    		 = new class_mensajes();
$ls_codemp 		 = $_SESSION["la_empresa"]["codemp"];
$io_sql    		 = new class_sql($ls_conect);
$io_funcion 	 = new class_funciones();
$ds_procedencias = new class_datastore();

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT procede FROM sigesp_procedencias";
	$data=$sql->select($ls_sql);
	if ($row=$sql->fetch_row($data))
	   {
		 $data=$sql->obtener_datos($data);
		 $arrcols=array_keys($data);
		 $totcol=count($arrcols);
		 $ds_procedencias->data=$data;
	   }	
}

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion	 = $_POST["operacion"];
	 $ls_documento	 = "%".$_POST["txtdocumento"]."%";
	 $ls_fecdesde	 = $_POST["txtfechadesde"];
	 $ls_fechasta	 = $_POST["txtfechahasta"];	
	 $ls_procedencia = $_POST["procede"];
	 $ls_provben	 = "%".$_POST["txtprovbene"]."%";
	 $ls_estmov	     = $_POST["estmov"];
	 $ls_tipo		 = $_POST["tipo"];
   }
else
   {
	 $ls_operacion="";
	 $ls_estmov="-";
   }

$lb_sel  = "";
$lb_selN = "";
$lb_selC = "";
$lb_selL = "";
$lb_selA = "";
$lb_selO = "";
if ($ls_estmov=="-")
   {
     $lb_sel="selected";
   }
if ($ls_estmov=="N")
   {
	 $lb_selN="selected";
   }
if ($ls_estmov=="C")
   {
	 $lb_selC="selected";
   }
if ($ls_estmov=="L")
   {
	 $lb_selL="selected";
   }
if ($ls_estmov=="A")
   {
	 $lb_selA="selected";
   }
if ($ls_estmov=="O")
   {
	 $lb_selO="selected";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobantes de Retenci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <p><br>
  </p>
  <div align="center">
    <table width="531" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="5" style="text-align:center"><input name="operacion" type="hidden" id="operacion" >
        Cat&aacute;logo de Comprobantes de Retenci&oacute;n</td>
      </tr>
      <tr>
        <td width="92" height="13" align="right">&nbsp;</td>
        <td width="205" height="13">&nbsp;</td>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Documento</td>
        <td height="22"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        
        </div></td>
			<?php
			if(array_key_exists("procede",$_POST))
			{
			$ls_procede_ant=$_POST["procede"];
			$sel_N="";
			}
			else
			{
			$ls_procede_ant="N";
			$sel_N="selected";
			}
			uf_cargar_procedencias($io_sql);
			$li_rowcount=$ds_procedencias->getRowCount("procede");
			?>
			<td width="38" height="22" align="right"><strong>Fecha</strong> </td>
            <td height="22" style="text-align:right">Desde</td>
            <td height="22" align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  size="14" maxlength="10"  onKeyPress="currencyDate(this);" datepicker="true"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td height="22" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"></a>		</td>
        <td height="22">&nbsp;</td>
        <td width="41" height="22" style="text-align:right">Hasta</td>
        <td width="153" height="22" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta" size="14" maxlength="10"  style="text-align:center"   onKeyPress="currencyDate(this);" datepicker="true"> </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo/C&eacute;dula</td>
        <td height="22" colspan="4" align="left" ><input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="" size="14" maxlength="10">
        <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
        <label>
        <input name="txtdesproben" type="text" class="sin-borde" id="txtdesproben" style="text-align:left" size="50" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Procedencia</td>
        <td height="22" colspan="2" align="left" ><select name="procede" id="select">
          <option value="N" "<?php print $sel_N?>" >Ninguno</option>
          <?php
		  	for($li_i=1;$li_i<=$li_rowcount;$li_i++)
			{
				$ls_procede=$ds_procedencias->getValue("procede",$li_i);
				if($ls_procede_ant==$ls_procede)
				{
				?>
          <option value="<?php print $ls_procede?>" selected><?php print $ls_procede?></option>
          <?php
				}
				else
				{
				?>
          <option value="<?php print $ls_procede?>"><?php print $ls_procede?></option>
          <?php
				}
			}
			?>
        </select></td>
        <td height="22" style="text-align:right">Estatus</td>
        <td height="22"><div align="left">
          <select name="estmov" id="estmov">
              <option value="-" <?php print $lb_sel;?>>Ninguno</option>
			  <option value="N" <?php print $lb_selN;?>>No Contabilizado</option>
              <option value="C" <?php print $lb_selC;?>>Contabilizado</option>
              <option value="L" <?php print $lb_selL;?>>No Contabilizable</option>
              <option value="A" <?php print $lb_selA;?>>Anulado</option>
              <option value="O" <?php print $lb_selO;?>>Original</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td height="13" colspan="5" style="text-align:right">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="5" style="text-align:right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" longdesc="Buscar Comprobantes de Retención...">Buscar</a></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>
<?php
print "<table width=531 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Documento</td>";
print "<td>Retención</td>";
print "<td>Fecha</td>";
print "<td>Periodo</td>";
print "<td>Proveedor</td>";
print "<td>Nombre Proveedor</td>";
print "<td>RIF</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret 
		        FROM scb_cmp_ret 
			   WHERE codemp='".$ls_codemp."' AND codret='0000000003'";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
		{
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
	 else
	    {
	      $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_documento = $row["numcom"];
					  $ls_codret	= $row["codret"];
					  $ls_fecha     = $row["fecrep"];
					  $ls_fecha     = $io_funcion->uf_convertirfecmostrar($ls_fecha);
					  $ls_perfiscal = $row["perfiscal"];
					  $ls_sujret	= $row["codsujret"];
					  $ls_nomsujret = $row["nomsujret"];
					  $ls_rif       = $row["rif"];
					  $ls_direccion = $row["dirsujret"];
					  $ls_estcmpret = $row["estcmpret"];
					  print "<tr class=celdas-blancas>";
					  print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codret','$ls_fecha','$ls_perfiscal','$ls_sujret','$ls_nomsujret','$ls_rif','$ls_direccion','$ls_estcmpret');\">".$ls_documento."</a></td>";
					  print "<td>".$ls_codret."</td>";
					  print "<td>".$ls_fecha."</td>";				
					  print "<td>".$ls_perfiscal."</td>";
					  print "<td>".$ls_sujret."</td>";
					  print "<td align=left>".$ls_nomsujret."</td>";	
					  print "<td>".$ls_rif."</td>";	
					  print "</tr>";			
					}
			 }
		  else
		     {
			   $io_msg->message("No se han Registrado Comprobantes de Retención !!!");
			 } 
		}
   }
print "</table>";
?></td>
      </tr>
    </table>
    </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function uf_aceptar(ls_documento,ls_codret,ls_fecha,ls_perfiscal,ls_sujret,ls_nomsujret,ls_rif,ls_direccion,ls_estcmpret)
  {
   	f=opener.document.form1;
	f.txtcomprobante.value=ls_documento;
	ls_mes=ls_perfiscal.substr(4,2);
	ls_agno=ls_perfiscal.substr(0,4);
	f.mes.value=ls_mes;
	f.agno.value=ls_agno;
	f.txtcodigo.value=ls_sujret;
	f.txtnombre.value=ls_nomsujret;
	f.txtrif.value=ls_rif;
	f.txtdireccion.value=ls_direccion;
	f.txtcodret.value=ls_codret;
	f.operacion.value="CARGAR_DT";
	f.estcmpret.value=ls_estcmpret;
	f.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_cmp_ret.php";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_prov.php","_blank","width=502,height=350");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_bene.php","_blank","width=502,height=350");
		}
	}
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
		}
	
	}

   
   function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
	function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
  }
   
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
