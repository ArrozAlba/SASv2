<?Php
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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_conciliacion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Conciliacion Bancaria</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js" ></script>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_grid	= new grid_param();
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
   }
else
   {
	 $ls_operacion = "";	
	 $ld_fecha	   = date("d/m/Y");
   }

$object = uf_cargar_conciliacion(&$li_total);
$grid="Grid";
$title[1]="";    $title[2]="Banco";  $title[3]="Banco";    $title[4]="Cuenta"; $title[5]="Mes/Año";    $title[6]="Saldo Libro"; $title[7]="Saldo Banco";    
	
function uf_cargar_conciliacion($li_row)
{
  global $ls_conect;
  require_once("../shared/class_folder/class_sql.php");
  $io_sql = new class_sql($ls_conect);
  $li_row = 0;
  $ls_sql = "SELECT scb_conciliacion.codban,scb_banco.nomban,scb_conciliacion.ctaban,scb_ctabanco.dencta,scb_conciliacion.mesano,
                    scb_conciliacion.salseglib,scb_conciliacion.salsegbco,scb_conciliacion.estcon
			   FROM scb_banco, scb_ctabanco, scb_conciliacion
			  WHERE scb_conciliacion.estcon=1
			    AND scb_banco.codemp=scb_ctabanco.codemp 
				AND scb_banco.codban=scb_ctabanco.codban				
				AND scb_banco.codemp=scb_conciliacion.codemp
				AND scb_banco.codban=scb_conciliacion.codban 
				AND scb_ctabanco.codemp=scb_conciliacion.codemp
				AND scb_ctabanco.codban=scb_conciliacion.codban
				AND scb_ctabanco.ctaban=scb_conciliacion.ctaban";
				$ls_sql=$ls_sql." AND scb_conciliacion.ctaban IN (SELECT codintper ".
								"					 FROM sss_permisos_internos ".
								"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
								"				    UNION ".
								"				   SELECT codintper ".
								"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
								"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) ".
								"                   ORDER BY  scb_conciliacion.codban,scb_conciliacion.ctaban,scb_conciliacion.mesano                               ";
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	   print $io_sql->message;
	   return false;
	 }
  else
	 {
	   while(!$rs_data->EOF)
			{
			  $li_row++;
			  $ls_codban 	  = $rs_data->fields["codban"];
			  $ls_ctaban	  = $rs_data->fields["ctaban"];
			  $ls_nomban	  = $rs_data->fields["nomban"];
			  $ls_dencta	  = $rs_data->fields["dencta"];
			  $ls_mesano	  = $rs_data->fields["mesano"];
			  $ldec_salseglib = $rs_data->fields["salseglib"];
			  $ldec_salsegbco = $rs_data->fields["salsegbco"];
			  $ls_estcon      = $rs_data->fields["estcon"];
				
			  $object[$li_row][1]="<input type=radio name=chksel  id=chksel value=$li_row class=sin-borde style=width:15px;height:15px>";		
			  $object[$li_row][2]="<input type=text name=txtcodban".$li_row."      value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
			  $object[$li_row][3]="<input type=text name=txtnomban".$li_row."      value='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=55 maxlength=100>";
			  $object[$li_row][4]="<input type=text name=txtctaban".$li_row."      value='".$ls_ctaban."' class=sin-borde readonly style=text-align:center size=27 maxlength=26>";
			  $object[$li_row][5]="<input type=text name=txtmesano".$li_row."      value='".$ls_mesano."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
			  $object[$li_row][6]="<input type=text name=txtsalseglib".$li_row."   value='".number_format($ldec_salseglib,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
			  $object[$li_row][7]="<input type=text name=txtsalsegbco".$li_row."   value='".number_format($ldec_salsegbco,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
			  $rs_data->MoveNext();
			}
	   if ($li_row==0)
		  {
		    $li_row=1;
			$object[$li_row][1]="<input type=radio name=chksel  id=chksel value=$li_row style=width:15px;height:15px>";		
			$object[$li_row][2]="<input type=text name=txtcodban".$li_row."     value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
			$object[$li_row][3]="<input type=text name=txtnomban".$li_row."     value='' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
			$object[$li_row][4]="<input type=text name=txtctaban".$li_row."     value='' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
			$object[$li_row][5]="<input type=text name=txtmesano".$li_row."     value='' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
			$object[$li_row][6]="<input type=text name=txtsalseglib".$li_row."  value='' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
			$object[$li_row][7]="<input type=text name=txtsalsegbco".$li_row."  value='' class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
		  }
	   $io_sql->free_result($rs_data);
	 }
  return $object;	 
}
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
      <td  height="22" colspan="2" align="center">Conciliacion Bancaria </td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr style="visibility:hidden">
      <td colspan="2" style="text-align:left">Reporte en
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
        
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="50" colspan="2" align="center">
        <br>
      <?php $io_grid->makegrid($li_total,$title,$object,400,'Conciliacion ',$grid);?></td>
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
function ue_imprimir()
{
  f				 = document.form1;
  li_total       = f.total.value;
  ls_tiporeporte = f.cmbbsf.value;
  li_imprimir    = f.imprimir.value;
  if (li_imprimir=='1')
  {
    if (li_total>1)
       {
	     for (i=0;i<li_total;i++)
	         {
		       if (f.chksel[i].checked)	
				  {
				    li_temp=i+1;
					ls_mesano	   = eval("f.txtmesano"+li_temp+".value");
					ls_codban	   = eval("f.txtcodban"+li_temp+".value");
					ls_ctaban	   = eval("f.txtctaban"+li_temp+".value");
					ls_nomban	   = eval("f.txtnomban"+li_temp+".value");
					ldec_salseglib = eval("f.txtsalseglib"+li_temp+".value");
					ldec_salseglib = uf_convertir_monto(ldec_salseglib);
					ldec_salsegbco = eval("f.txtsalsegbco"+li_temp+".value");
					ldec_salsegbco = uf_convertir_monto(ldec_salsegbco);
					if (ls_mesano!="")
					   {
						 pagina="reportes/sigesp_scb_rpp_conciliacion_pdf.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&mesano="+ls_mesano+"&salseglib="+ldec_salseglib+"&salsegbco="+ldec_salsegbco+"&nomban="+ls_nomban+"&tiporeporte="+ls_tiporeporte;
						 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
					   }
		          }
	         }
       }
    else
       {
	     if (f.chksel.checked)	
		    {
			  li_temp=1;
			  ls_mesano=eval("f.txtmesano"+li_temp+".value");
			  ls_codban=eval("f.txtcodban"+li_temp+".value");
			  ls_ctaban=eval("f.txtctaban"+li_temp+".value");
			  ls_nomban=eval("f.txtnomban"+li_temp+".value");
			  ldec_salseglib=eval("f.txtsalseglib"+li_temp+".value");
			  ldec_salseglib=uf_convertir_monto(ldec_salseglib);
			  ldec_salsegbco=eval("f.txtsalsegbco"+li_temp+".value");
			  ldec_salsegbco=uf_convertir_monto(ldec_salsegbco);
			  if (ls_mesano!="")
			     {
				   pagina="reportes/sigesp_scb_rpp_conciliacion_pdf.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&mesano="+ls_mesano+"&salseglib="+ldec_salseglib+"&salsegbco="+ldec_salsegbco+"&nomban="+ls_nomban+"&tiporeporte="+ls_tiporeporte;
				   window.open(pagina,"catalogo","menubar=yes,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			     }
		    }  	
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
			//alert(ls_long);


  //  return false; 
   }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
