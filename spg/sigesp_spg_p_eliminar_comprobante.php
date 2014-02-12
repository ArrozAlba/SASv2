<?php 
session_start(); 
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	require_once("class_funciones_gasto.php");
	$io_fun_gasto=new class_funciones_gasto();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_p_eliminar_comprobante.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	///referente al grid------------------------------------------------
	$ls_titletable="Comprobantes sin Detalles Presupuestarios ";
	$li_widthtable=550;
	$ls_nametable="grid";
	$lo_title[1]="Todos <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";
	$lo_title[2]="Nro. de Comprobante";
	$lo_title[3]="Procedencia";
	$lo_title[4]="Fecha de Emisión";
	$lo_title[5]="Descripción";
	$lo_title[6]="Monto";
	$li_totrows=$io_fun_gasto->uf_obtenervalor("totalfilas",0);
	//-----------------------------------------------------------------------
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
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
</script><title>Eliminar Comprobantes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="styleshee t" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<style type="text/css">
<!--
.Estilo2 {font-size: 15px}
-->
</style>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;	
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo4 {color: #6699CC}

-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <table width="798" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="1220" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
    </tr>
	  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Contabilidad Presupuestaria de Gasto</td>
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
      <td height="20" class="toolbar">&nbsp;</td>
    </tr>
    <tr>	  
      <td height="20" class="toolbar">
	  <a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Procesar" alt="Transferir personal..." name="Transferir" width="20" height="20" border="0" id="Transferir"> </a><a href="javascript: ue_guardar();"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a> <img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></td>
    </tr>
  </table>
  <p>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_funciones.php");	
	require_once("../shared/class_folder/grid_param.php");
	require_once("sigesp_spg_c_eliminar_comprobantes.php");
	$io_include = new sigesp_include();
	$io_connect= $io_include->uf_conectar();
	$io_sql=new class_sql($io_connect);
	$io_msg=new class_mensajes();
	$io_function=new class_funciones();
    $io_fecha=new class_fecha();	
	$io_class_grid=new grid_param();
	$io_eliminar= new sigesp_spg_c_eliminar_comprobantes();
	
	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input type=checkbox name=selusu".$ai_totrows." id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");'><input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>
		<input name=txtcodban".$ai_totrows." type=hidden id=txtcodban".$ai_totrows." value='' readonly>
		<input name=txtctaban".$ai_totrows." type=hidden id=txtctaban".$ai_totrows." value='' readonly>";
		$aa_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$aa_object[$ai_totrows][3] = "<input name=txtprocede".$ai_totrows." type=text id=txtprocede".$ai_totrows." class=sin-borde  readonly style=text-align:center value='' size=15 maxlength=12>";
		$aa_object[$ai_totrows][4] = "<input type=text name=txtfecha".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
		$aa_object[$ai_totrows][5] = "<input type=text name=txtdescomp".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
		$ao_object[$ai_totrows][6] = "<input type=text name=txtmonto".$ai_totrows."  value=''   class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";		
   }

	if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];	  
	  $ls_fechasta=$_POST["txtfecdesde"];
	  $ls_fecdesde=$_POST["txtfechasta"];
	}
	else
	{
	  $ls_operacion="";
	  $ls_comprobate="";
	  $ls_fechasta="";
	  $ls_fecdesde="";
	}
	if($ls_operacion=="BUSCAR")
    {
		$li_totrows=0;
		$ls_fechasta=$_POST["txtfechasta"];
	    $ls_fecdesde=$_POST["txtfecdesde"];
		$lb_valido=$io_eliminar->uf_buscar_comprobantes($lo_object,$li_totrows,$ls_fecdesde,$ls_fechasta);		
	}
	
	if ($ls_operacion=="PROCESAR")
	{
		for($li_i=1;$li_i<=$li_totrows;$li_i++)
		{
			$ls_selusu=$_POST["txtselusu".$li_i];			
			if ($ls_selusu==1)
			{
			    $ls_comprobante=$_POST["txtcomprobante".$li_i];
				$ls_procede=$_POST["txtprocede".$li_i];
				$ls_fecha=$_POST["txtfecha".$li_i];
				$ls_codban=$_POST["txtcodban".$li_i];
				$as_ctaban=$_POST["txtctaban".$li_i];
				$lb_valido=$io_eliminar->uf_eliminar_comprobantes($ls_comprobante, $ls_procede, $ls_fecha, $ls_codban, $as_ctaban);
			}					
		}//fin del for
		if ($lb_valido)
		{
			$io_msg->message("Se realizo la eliminación con Exito");
		}
		$li_totrows=0;
		uf_agregarlineablanca($lo_object,1);
	}
	
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
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
 ?>
  </p>
  <table width="798" height="224" border="0" align="center">
    <tr>
      <td width="777"><table width="516" height="145" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
<tr>
              <td height="24" colspan="3" class="titulo-ventana"><div align="center">Eliminar Comprobantes sin Detalles Presupuestarios </div></td>
            </tr>
            <tr>
              <td height="18" colspan="3"><span class="Estilo2"></span></td>
            </tr>
            <tr>
              <td width="215" height="21"><div align="right">Fecha de Emisi&oacute;n Desde</div></td>
              <td width="299" colspan="2"><input name="txtfecdesde" type="text" id="txtfecdesde" size="15" maxlength="10" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" datepicker="true" value="<? print $ls_fecdesde; ?>"></td>
            </tr>
            <tr>  
              <td height="21"><div align="right">Fecha de Emisi&oacute;n Hasta</div></td>
              <td colspan="2"><input name="txtfechasta" type="text" id="txtfechasta" size="15" maxlength="10" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" datepicker="true" value="<? print $ls_fechasta; ?>"></td>
            </tr>
            <tr>
              <td height="13" colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="13" colspan="3"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  width="20" height="20" border="0">Buscar Comprobantes </a></td>
            </tr>
            <tr>
              <td height="13" colspan="3"><span class="toolbar"><a href="javascript: ue_buscar();"></a></span></td>
            </tr>			
            <tr>
              <td height="22" colspan="3"><div align="center">
                <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
                <input name="fila" type="hidden" id="fila">
                <a href="javascript: ue_showouput();"></a>
				<?php
				require_once("../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				if(empty($lo_object))
				{
				 uf_agregarlineablanca($lo_object,1);
				}
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
			   ?>
				</div>
				 <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
			    </td>
            </tr>
        </table>
      </td>
    </tr>
  </table>
  </form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);

function ue_buscar()
{
    f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_p_eliminar_comprobante.php";
	f.submit();

}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_procesar()
{
     f=document.form1;
	 if (f.totalfilas.value==0)
	 {
	  alert("No ha seleccionado solicitudes por procesar !!!");
	 }
	 else
	 {
	   if (confirm("¿Esta seguro que desea Eliminar el o los comprobantes?"))
	   {
			f.operacion.value = "PROCESAR";
			f.action="sigesp_spg_p_eliminar_comprobante.php";
			f.submit();
		}
	 }  
}

function cambiar_valor (li_i)
{
	f=document.form1;
	sel= eval ('document.form1.selusu'+li_i);	
	if (sel.checked)
	{
		selpro = eval ('document.form1.txtselusu'+li_i);	
		selpro.value = '1';
	}	
	else
	{
		selpro = eval ('document.form1.txtselusu'+li_i);	
		selpro.value = '0';
	}	
}

function uf_select_all()
{
	  f=document.form1;	  
	  total=f.totalfilas.value; 
	  sel_all=f.chkall.value;
	   if(f.chkall.checked==true)
	  {
		  for(i=1;i<=total;i++)	
		  {
			eval("f.selusu"+i+".checked=true");
			selpro = eval ('document.form1.txtselusu'+i);	
		    selpro.value = '1';				
		  }		 
	  }
	  else
	  {
	  	for(i=1;i<=total;i++)	
		  {
			eval("f.selusu"+i+".checked=false");			
		    selpro.value = '0';			
		  }	
	  }		  
}
//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}
</script>
</html>