<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_disponibilidad.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_banco->uf_select_config("SCB","REPORTE","DISPONIBILIDAD_FINANCIERA","sigesp_scb_rpp_disponibilidad_pdf.php","C");//print $ls_reporte;

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Disponibilidad Financiera</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir('<?php echo $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();

require_once("../shared/class_folder/sigesp_include.php");
$sig_inc=new sigesp_include();
$con=$sig_inc->uf_conectar();

$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ld_fecha=$_POST["txtfecha"];
}
else
{
	$ls_operacion="";	
	$ld_fecha=date("d/m/Y");
}

   uf_cargar_bancos(&$object_bancos,&$li_total);
   $title[1]="Todos<input name=chkall type=checkbox id=chkall value=1 style=width:15px;height:15px class=sin-borde onClick=javascript:uf_select_all();>";    $title[2]="Banco";     $title[3]="Nombre Banco"; 
   $grid="grid";

	function uf_cargar_bancos($object_bancos,$li_row) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo: uf_cargar_bancos
	//	Access:  public
	//	Returns:		
	//  $object_bancos=  Arreglo de los bancos para enviarlo a la clase grid_param
	//	Description:  Función que se encarga de seleccionar los   bancos y retornarlos en un arreglo de object
	//
	//////////////////////////////////////////////////////////////////////////////
	  
	  $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	  $li_row=0;
	  global $con;
	  require_once("../shared/class_folder/class_sql.php");	
	  $SQL=new class_sql($con);	

	  $ls_sql="SELECT codban,nomban 
	             FROM scb_banco 
			    WHERE codemp = '".$ls_codemp."'
				  AND codban <> '---'
			    ORDER BY codban ASC";
	 
	   $rs_bancos=$SQL->select($ls_sql);
	   
	   if (($rs_bancos===false))
	   {
			$lb_valido=false;			
	   }
	   else
	   {
		   while($row=$SQL->fetch_row($rs_bancos))
		   {
				$li_row=$li_row+1;
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				$object_bancos[$li_row][1]="<div align=center><input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px></div>";		
				$object_bancos[$li_row][2]="<input type=text name=txtcodban".$li_row."   value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
				$object_bancos[$li_row][3]="<input type=text name=txtnomban".$li_row."   value='".$ls_nomban."'  title='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=70 maxlength=70>";
		   }
		   if($li_row==0)
		   {
				$li_row=1;
				$ls_codban="";
				$ls_nomban="";
				$object_bancos[$li_row][1]="<div align=center><input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px></div>";		
				$object_bancos[$li_row][2]="<input type=text name=txtcodban".$li_row."   value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
				$object_bancos[$li_row][3]="<input type=text name=txtnomban".$li_row."   value='".$ls_nomban."'  title='".$ls_nomban."'  class=sin-borde readonly style=text-align:left size=70 maxlength=70>";
		   }
		   $SQL->free_result($rs_bancos);
	   }
	   //return $rs_proveedor;         
	}//fin de uf_cargar_bancos
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="439" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="58"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="2" align="center">Disponibilidad Financiera <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" colspan="2" style="text-align:left">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>      </td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:center"><table width="253" align="center" class="formato-catalogo">
        <tr>
          <td width="121"><label>
            <input name="opcion" type="radio" class="sin-borde" value="A" checked>
            Acumulada</label></td>
            <td width="122"><input name="opcion" type="radio" class="sin-borde" value="D">
            Detallada</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Fecha</td>
      <td width="379" height="22" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha"  style="text-align:left" value="<?php print $ld_fecha;?>" size="12" maxlength="10" onKeyPress="currencyDate(this);"  datepicker="true">     </td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="50" colspan="2" align="center">
      <?php $io_grid->makegrid($li_total,$title,$object_bancos,400,'Bancos ',$grid);?></td>
    </tr>
  </table>
</table>
<input name="total" type="hidden" id="total" value="<?php print $li_total;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir(ls_reporte)
{
  f				 = document.form1;
  ld_fecha  	 = f.txtfecha.value
  li_total  	 = f.total.value;
  ls_bancos 	 = "";
  ls_tiporeporte = f.cmbbsf.value;
  li_imprimir    = f.imprimir.value;
  if (li_imprimir=='1')
     {
       for (li_i=1;li_i<=li_total;li_i++)
  		   {
   	         ls_codban = eval("f.txtcodban"+li_i+".value");
  	         if (eval("f.chksel"+li_i+".checked==true"))
	            {
		          if (ls_bancos.length>0)
	 	             {
			           ls_bancos=ls_bancos+"-"+ls_codban;
  		             }
				  else
					 {
					   ls_bancos=ls_codban;
					 }
				}
 		   }
        if (ls_bancos.length>0)
           {
	         if (f.opcion[0].checked)
	            {
		          pagina="reportes/"+ls_reporte+"?fecha="+ld_fecha+"&bancos="+ls_bancos+"&tiporeporte="+ls_tiporeporte;  	
		          window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	            }
	         else
	            {
		          pagina="reportes/sigesp_scb_rpp_disponibilidad_det_pdf.php?fecha="+ld_fecha+"&bancos="+ls_bancos+"&tiporeporte="+ls_tiporeporte;  
		          window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	            }
	       }
	    else
		   {
			 alert("Debe seleccionar al menos un banco");
		   }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}


function uf_catalogoprov()
{
    f=document.form1;
    f.operacion.value="BUSCAR";
    pagina="sigesp_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

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


function uf_select_all()
{
	  f=document.form1;
	  total=f.total.value;
	  sel_all=f.chkall.value;
	  ls_fecha=f.txtfecha.value;
	  if(f.chkall.checked)
	  {
		  for(i=1;i<=total;i++)	
		  {
			eval("f.chksel"+i+".checked=true");
		  }		  
	 }
	 else
	 {
		 for(i=1;i<=total;i++)	
		  {
			eval("f.chksel"+i+".checked=false");
		  }		  
	 }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>