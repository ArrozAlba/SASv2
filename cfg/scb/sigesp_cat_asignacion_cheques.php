<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Asignaci&oacute;n de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
</style></head>

<body>
<br>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql		= new class_sql($ls_conect);
$io_msg		= new class_mensajes();
$io_grid	= new grid_param();
$la_emp     = $_SESSION["la_empresa"];
if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_chequera.php";
	$la_security[1]=$la_emp;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $li_totrowche = $li_totrowusu = 0;
	 $ls_numche    = $_POST["hidnumche"];
	 $ls_codusu    = $_POST["hidcodusu"];
	 $ls_usuasiche = $_POST["hidusuasiche"];
	 $ls_cheemi    = $_POST["hidcheemi"];
   }
else
   {
	 $ls_operacion = "";	
     $li_totrowche = $_GET["hidtotrowche"];
	 if($li_totrowche<=320)
	 {
	     $ls_numche    = $_GET["hidnumche"];
		 $ls_codusu    = $_GET["hidcodusu"];
		 $ls_usuasiche = $_GET["hidusuasiche"];
		 $ls_cheemi    = $_GET["hidcheemi"];
		 
		 $la_cheques   = split(';',$ls_numche);
		 $la_usuarios  = split(';',$ls_codusu);
		 $la_emitidos  = split(';',$ls_cheemi);
		 $la_usuasiche = split(';',$ls_usuasiche);
	 }
     else
     {
         $ls_codusu    = $_GET["hidcodusu"];
		 $la_usuarios  = split(';',$ls_codusu);
		 $ls_usuasiche = "";
		 $ls_cheemi    = "";
     }
	 
	 $as_codban    = $_GET["codban"];
	 $as_ctaban = $_GET["ctaban"];
	 $as_chequera    = $_GET["chequera"];

     $li_totrowusu = $_GET["hidtotrowusu"];
   }

?>
<form name="form1" method="post" action="">
<table width="358" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="22" colspan="4" class="titulo-celda"><input name="hidtotrowche" type="hidden" id="hidtotrowche" value="<?php echo $li_totrowche ?>">
      Asignaci&oacute;n de Cheques      </td>
  </tr>
  <tr>
    <td width="76" height="13">&nbsp;</td>
    <td width="70" height="13">&nbsp;</td>
    <td width="86" height="13">&nbsp;</td>
    <td width="186" height="13">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><strong>Usuario</strong></td>
    <td height="22" colspan="2"><label>
      <select name="cmbcodusu" id="cmbcodusu" style="width:100px">
        <option value="-">---seleccione---</option>
      <?php
	    for ($li_i=0;$li_i<$li_totrowusu-1;$li_i++) 
		    {
			  $ls_codusu = $la_usuarios[$li_i];
			  print "<option value='$ls_codusu'>$ls_codusu</option>";
			}
	  ?>
	  </select>
    </label></td>
    <td height="22">&nbsp;</td>
  </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" colspan="4" style="text-align:right"><a href="javascript:uf_procesar_asignacion();"><img src="../../shared/imagebank/tools20/ejecutar.gif" alt="Procesar Asignaci&oacute;n..." width="20" height="20" border="0">Procesar Asignaci&oacute;n</a></td>
    </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
  </tr>
</table>
<p align="center">
  <?php
$title[1]="Cheque";$title[2]="Usuario";$title[3]="Asignar"; 
$grid="grid";

require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");	
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");
$io_mensajes  = new class_mensajes();	
$in           = new sigesp_include();
$con          = $in->uf_conectar();
$io_sql       = new class_sql($con);
$io_funciones = new class_funciones();


if ($ls_operacion=="")
   {
	if($li_totrowche<=320)
    {
         for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
	     {
		   $ls_numche        = $la_cheques[$li_i-1];
		   $ls_usuasiche     = $la_usuasiche[$li_i-1];
		   $lb_checked       = $la_emitidos[$li_i-1]; 
		   
		   $object[$li_i][1] = "<input type=text name=txtnumcheasi".$li_i."  value='".$ls_numche."'    id=txtnumcheasi".$li_i."  class=sin-borde style=text-align:center size=30 maxlength=15 readonly>";
		   $object[$li_i][2] = "<input type=text name=txtcodusuasi".$li_i."  value='".$ls_usuasiche."' id=txtcodusuasi".$li_i."  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
		   if ($lb_checked=='true')
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=0 id=chkasi".$li_i." class=sin-borde checked disabled>";
			  }
		   elseif(!empty($ls_usuasiche))
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=1 id=chkasi".$li_i." class=sin-borde onClick='javascript:uf_asignar_usuario($li_i);' checked>";
			  }
		   else
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=1 id=chkasi".$li_i." class=sin-borde onClick='javascript:uf_asignar_usuario($li_i);'>";
			  }
		   
		 } // fin del for
    }
	else
	{ 
		require_once("sigesp_scb_c_chequera.php");
		$in_classchequera=new sigesp_scb_c_chequera($la_security);
		$in_classchequera->uf_buscar_cheques($as_codban,$as_ctaban,$as_chequera,&$aa_cheques);

		$li_i=0; 
		for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
		{ 
			$ls_numcheque    = $aa_cheques[$li_i]["numche"]; 
			$ls_codusuche    = $aa_cheques[$li_i]["codusu"];
			$li_status       = $aa_cheques[$li_i]["estche"];   
			if($li_status==1)
			{
				$lb_checked="checked";
			}
			else
			{
				$lb_checked="";
			}
			$object[$li_i][1] = "<input type=text name=txtnumcheasi".$li_i."  value='".$ls_numcheque."'    id=txtnumcheasi".$li_i."  class=sin-borde style=text-align:center size=30 maxlength=15 readonly>";
			$object[$li_i][2] = "<input type=text name=txtcodusuasi".$li_i."  value='".$ls_codusuche."' id=txtcodusuasi".$li_i."  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
			if ($lb_checked=='checked')
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=0 id=chkasi".$li_i." class=sin-borde checked disabled>";
			  }
			elseif(!empty($ls_codusuche))
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=1 id=chkasi".$li_i." class=sin-borde onClick='javascript:uf_asignar_usuario($li_i);' checked>";
			  }
			else
			  {
				$object[$li_i][3] = "<input type=checkbox name=chkasi".$li_i." value=1 id=chkasi".$li_i." class=sin-borde onClick='javascript:uf_asignar_usuario($li_i);'>";
			  }
	   }// fin del for
    }
     $io_grid->makegrid($li_totrowche,$title,$object,520,'Asignaci&oacute;n de Cheques a Usuarios',$grid);
   }
unset($la_cheques,$la_usuarios,$la_emitidos,$la_usuasiche);
?>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function uf_asignar_usuario(li_fila)
{
  f   = document.form1;
  fop = opener.document.form1;
  if (eval("fop.chk"+li_fila+".checked"==true))
     {
	   eval("f.chkasi"+li_fila+".checked=true");
	   eval("f.chkasi"+li_fila+".readOnly=true");
	 }
  else
     {
	   ls_codusu = f.cmbcodusu.value;
	   if (ls_codusu!='-')
		  {
		    if (eval("f.chkasi"+li_fila+".checked==true"))
			   {
			     eval("f.txtcodusuasi"+li_fila+".value=ls_codusu");
			   }
			else
			   {
			     eval("f.txtcodusuasi"+li_fila+".value=''");  
			   }
		  }
	   else
		  {
		    if (eval("f.chkasi"+li_fila+".checked==true"))
			   {
			     eval("f.chkasi"+li_fila+".checked=false")
				 alert("Por Favor seleccione un Usuario !!!");
			   }
			else
			   {
			     eval("f.txtcodusuasi"+li_fila+".value=''");
			   }
		  }
	 }
}

function uf_procesar_asignacion()
{
  li_totrowche = document.form1.hidtotrowche.value;
  lb_valido    = true;
  for (li_z=1;li_z<=li_totrowche;li_z++)
      {
	    if (eval("document.form1.chkasi"+li_z+".checked==false")) 
		   {
		     lb_valido=false;
			 break;
		   }
	  }
  if (lb_valido)
     {
	   for (li_i=1;li_i<=li_totrowche;li_i++)
		   {
			 ls_codusuasi = eval("document.form1.txtcodusuasi"+li_i+".value");
			 eval("opener.document.form1.txtcodusuche"+li_i+".value=ls_codusuasi");
		   }
	   close();
	 }
  else
     {
	   alert("Debe asignar la Totalidad de los Cheques !!!");
	 }	 
}
</script>
</html>