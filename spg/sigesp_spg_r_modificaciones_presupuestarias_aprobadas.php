<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
require_once("class_funciones_gasto.php");
$io_fungas  = new class_funciones_gasto();

$ls_reporte = $io_fungas->uf_select_config("SPG","REPORTE","MODIFICACION_PRESUPUESTARIA_APROBADA","sigesp_spg_rpp_modificaciones_presupuestarias_aprobadas.php","C");	

if($_SESSION["la_empresa"]["titulo"]=='ABAE')
{
 	$ls_reporte="sigesp_spg_rpp_modificaciones_presupuestarias_aprobadas_abae.php";
}
unset($io_fungas);

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat         = $_SESSION["la_empresa"];
	$ls_empresa  = $dat["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "SPG";
	$ls_ventanas = "sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Reporte de Modificaciones Presupuestarias</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Presupuesto de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr> 
        <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" title="Imprimir"></a></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if(array_key_exists("ckbrect",$_POST))
{
	if($_POST["ckbrect"]==1)
	{
		$ckbrect   = "checked" ;	
		$ls_ckbrect = 1;
	}
	else
	{
		$ls_ckbrect = 0;
		$ckbrect="";
	}
}
else
{
  $ls_ckbrect=0;
  $ckbrect="";
}	

if(array_key_exists("ckbtras",$_POST))
{
	if($_POST["ckbtras"]==1)
	{
		$ckbtras   = "checked" ;	
		$ls_ckbtras = 1;
	}
	else
	{
		$ls_ckbtras = 0;
		$ckbtras="";
	}
}
else
{
  $ls_ckbtras=0;
  $ckbtras="";
}	

if(array_key_exists("ckbinsu",$_POST))
{
	if($_POST["ckbinsu"]==1)
	{
		$ckbinsu   = "checked" ;	
		$ls_ckbinsu = 1;
	}
	else
	{
		$ls_ckbinsu = 0;
		$ckbinsu="";
	}
}
else
{
  $ls_ckbinsu=0;
  $ckbinsu="";
}	

if(array_key_exists("ckbcre",$_POST))
{
	if($_POST["ckbcre"]==1)
	{
		$ckbcre   = "checked" ;	
		$ls_ckbcre = 1;
	}
	else
	{
		$ls_ckbcre = 0;
		$ckbcre="";
	}
}
else
{
  $ls_ckbcre=0;
  $ckbcre="";
}	
if (array_key_exists("txtcomprobante",$_POST))
   {
     $ls_comprobante=$_POST["txtcomprobante"];	   
   }
else
   {
     $ls_comprobante="";
   }
if (array_key_exists("txtprocede",$_POST))
   {
     $ls_procede=$_POST["txtprocede"];	   
   }
else
   {
     $ls_procede="";
   }
   
if (array_key_exists("txtfecha",$_POST))
   {
     $ldt_fecha=$_POST["txtfecha"];	   
   }
else
   {
     $ldt_fecha="";
   }
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
if (array_key_exists("txtfecdes",$_POST)) 
   {
     $ldt_fecdes=$_POST["txtfecdes"];
   }
else
   {
     $ldt_fecdes="01/01/".$li_ano;
   }
if (array_key_exists("txtfechas",$_POST)) 
   {
     $ldt_fechas=$_POST["txtfechas"];
   }
else
   {
     $ldt_fechas=date("d/m/Y");
   }
   
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
		print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
		print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
		print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
		print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
		print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
		print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
		print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
		
	}
	else
	{
		
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>


  <input type="hidden" id="titempresa" value=<?php echo $_SESSION["la_empresa"]["titulo"]; ?>>
  <table width="540" height="21" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="586" colspan="2" class="titulo-ventana">Modificaciones Presupuestarias Aprobadas </td>
    </tr>
  </table>
  <table width="540" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="544"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td colspan="3" align="center"><div align="left"><strong> Reporte en</strong>
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
           <table width="480" height="59" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
			<tr class="titulo-celdanew">
			<td height="13" colspan="8" valign="top"><strong>Modificaciones Presupuestarias </strong></td>
            </tr>
			<tr>
			  <td height="19" colspan="2"><div align="right">
			    <input name="ckbrect" type="checkbox" id="ckbrect" value="1" checked <?php print $ckbrect ?>>
			  </div></td>
			  <td width="123" height="19"><div align="left">Rectificaciones</div></td>
			  <td height="19" colspan="2"></td>
			  <td width="61" height="19"><div align="right">
			    <input name="ckbtras" type="checkbox" id="ckbtras" value="1" checked <?php print $ckbtras ?>>
			  </div></td>
			  <td width="169" height="19" colspan="2"><div align="left">Traspaso</div></td>
		     </tr>
		    <tr class="formato-blanco">
		      <td height="22" colspan="2"><div align="right">
                <input name="ckbinsu" type="checkbox" id="ckbinsu" value="1" checked <?php print $ckbinsu ?>>
</div></td>
		      <td height="22"><div align="left">Insubsistencias</div></td>
		      <td height="22" colspan="2"></td>
		      <td height="22"><div align="right">
		        <input name="ckbcre" type="checkbox" id="ckbcre" value="1" checked <?php print $ckbcre ?>>
		      </div></td>
		      <td height="22" colspan="2"><div align="left">Credito Adicionales </div></td>
	         </tr>
        </table>
      </div>        </td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><strong><span class="style14">
        <input name="hidcodesp" type="hidden"  id="hidcodesp2" value="<?php print $ls_codigoesp ?>">        
        <input name="hidrango" type="hidden" id="hidrango">
      </span></strong></div>
      <strong><span class="style14">
      <input name="hidreporte" type="hidden" id="hidreporte" value="<?php print $ls_reporte;?>">
      </span></strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left"></div>        <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left">
        <table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="6"><strong>Intervalos de Fechas </strong></td>
            </tr>
          <tr>
            <td width="97" height="22"><div align="right">Desde</div></td>
            <td width="121" colspan="2"><div align="left">
              <input name="txtfecdes" type="text" id="txtfecdes"  style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
            <td width="20">&nbsp;</td>
            <td width="81"><div align="right">Hasta</div></td>
            <td width="165"><div align="left">
              <input name="txtfechas" type="text" id="txtfechas"  style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fechas ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="480" height="52" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
        <!--DWLayoutTable-->
        <tr class="titulo-celdanew">
          <td height="13" colspan="4" valign="top">Comprobante</td>
        </tr>
        <tr class="formato-blanco">
          <td width="147" height="37"><div align="center">
              <input name="txtcomprobante" type="text" id="txtcompdes2" value="<?php print $ls_comprobante ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="144" height="37"><div align="center">
              <input name="txtprocede" type="text" id="txtcompdes" value="<?php print $ls_procede ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="148"><div align="center">
              <input name="txtfecha" type="text" id="txtfecha" value="<?php print $ldt_fecha ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="39"><div align="left"><a href="javascript:catalogo_comprobante();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total2" value="<?php print $totrow;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }  
        }
}

function ue_showouput()
{
	f=document.form1;
	li_imprimir = f.imprimir.value;
	ls_reporte  = f.hidreporte.value;
	if(li_imprimir==1)
	{
		if(f.ckbrect.checked==true)
		{
		  ckbrect=1;
		}
		else
		{
		 ckbrect=0;
		}
		if(f.ckbtras.checked==true)
		{
		  ckbtras=1;
		}
		else
		{
		 ckbtras=0;
		}
		if(f.ckbinsu.checked==true)
		{
		  ckbinsu=1;
		}
		else
		{
		 ckbinsu=0;
		}
		if(f.ckbcre.checked==true)
		{
		  ckbcre=1;
		}
		else
		{
		 ckbcre=0;
		}
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		txtfechas = f.txtfechas.value;
		txtcomprobante  = f.txtcomprobante.value;
		txtprocede  = f.txtprocede.value;
		txtfecha = f.txtfecha.value;
		tipoformato=f.cmbbsf.value;
		if( (txtfecdes=="")||(txtfechas=="")|| ( (ckbrect==0)&&(ckbtras==0)&&(ckbinsu==0)&&(ckbcre==0) ) ) 
		{
		   alert("Por Favor Seleccionar todos los parametros de busqueda");
		}
		else                                                          
		{
			pagina="reportes/"+ls_reporte+"?ckbrect="+ckbrect+"&txtcomprobante="+txtcomprobante
					+"&txtfecha="+txtfecha+"&txtprocede="+txtprocede+"&ckbtras="+ckbtras+"&ckbinsu="+ckbinsu
					+"&ckbcre="+ckbcre+"&txtfecdes="+txtfecdes+"&txtfechas="+txtfechas+"&tipoformato="+tipoformato;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}
function catalogo_comprobante()
{
	   pagina="sigesp_cat_comprobantes_modificaciones.php?";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
}
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>