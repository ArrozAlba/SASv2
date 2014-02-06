<?php
session_start();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$io_msg=new class_mensajes();
$fun=new class_funciones();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
$ls_gestor = $_SESSION["ls_gestor"];
function uf_buscar($io_sql,$ls_sql,$ls_campo)
{
	$ls_valor="";
	$rs_result=$io_sql->select($ls_sql);
	if($rs_result===false)
	{

	}
	else
	{
		if($row=$io_sql->fetch_row($rs_result))
		{
			$ls_valor=trim($row[$ls_campo]);
		}
	}	
	return $ls_valor;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias</title>
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
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <div align="center">
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center"><?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
		}
		else
		{
			$ls_operacion="";
            $ls_codigo="";
			$ls_denominacion="";
		}
		if  (array_key_exists("estclades",$_GET))
		{
		  $ls_estclades=$_GET["estclades"];
		}
		else
		{
		  $ls_estclades="";
		}	
		if  (array_key_exists("estclahas",$_GET))
		{
		  $ls_estclahas=$_GET["estclahas"];
		}
		else
		{
		  $ls_estclahas="";
		}
		if(array_key_exists("codestpro1",$_GET))
		{
		    $ls_codestpro1  = $_GET["codestpro1"];
		}
		else
		{
		   $ls_codestpro1  = "";
		}
		if(array_key_exists("codestpro2",$_GET))
		{
		    $ls_codestpro2  = $_GET["codestpro2"];
		}
		else
		{
		   $ls_codestpro2  = "";
		}
		if(array_key_exists("codestpro3",$_GET))
		{
		    $ls_codestpro3  = $_GET["codestpro3"];
		}
		else
		{
		   $ls_codestpro3  = "";
		}
		if(array_key_exists("codestpro1h",$_GET))
		{
		    $ls_codestpro1h  = $_GET["codestpro1h"];
		}
		else
		{
		   $ls_codestpro1h  = "";
		}
		if(array_key_exists("codestpro2h",$_GET))
		{
		    $ls_codestpro2h  = $_GET["codestpro2h"];
		}
		else
		{
		   $ls_codestpro2h  = "";
		}
		if(array_key_exists("codestpro3h",$_GET))
		{
		    $ls_codestpro3h  = $_GET["codestpro3h"];
		}
		else
		{
		   $ls_codestpro3h  = "";
		}
		$ls_codestpro4="00";
		$ls_codestpro5="00";
		$ls_codestpro4h="00";
		$ls_codestpro5h="00";
		if($li_estmodest==2)
		{
			if(array_key_exists("codestpro4",$_GET))
			{
				$ls_codestpro4  = $_GET["codestpro4"];
			}
			else
			{
			   $ls_codestpro4  = "";
			}
			if(array_key_exists("codestpro5",$_GET))
			{
				$ls_codestpro5  = $_GET["codestpro5"];
			}
			else
			{
			   $ls_codestpro5  = "";
			}
			if(array_key_exists("codestpro4h",$_GET))
			{
				$ls_codestpro4h  = $_GET["codestpro4h"];
			}
			else
			{
			   $ls_codestpro4h  = "";
			}
			if(array_key_exists("codestpro5h",$_GET))
			{
				$ls_codestpro5h  = $_GET["codestpro5h"];
			}
			else
			{
			   $ls_codestpro5h  = "";
			}
		}
		?>
        Cat&aacute;logo de Cuentas Presupuestaria
        <input name="operacion" type="hidden" id="operacion"></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td width="122" height="13">&nbsp;</td>
        <td width="341" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2"><input name="nombre" type="text" id="nombre" size="72" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
<?php
echo "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>C&oacute;digo</td>";
echo "<td style=text-align:center width=450>Denominaci&oacute;n</td>";
echo "</tr>";
if($ls_operacion=="BUSCAR")
  {
	if($li_estmodest==1)
	{ 
		$ls_codestpro1  = $fun->uf_cerosizquierda(trim($ls_codestpro1),25);
		$ls_codestpro2  = $fun->uf_cerosizquierda(trim($ls_codestpro2),25);
		$ls_codestpro3  = $fun->uf_cerosizquierda(trim($ls_codestpro3),25);
		$ls_codestpro4  = $fun->uf_cerosizquierda(trim($ls_codestpro4),25);
		$ls_codestpro5  = $fun->uf_cerosizquierda(trim($ls_codestpro5),25);
		$ls_codestpro1h  = $fun->uf_cerosizquierda(trim($ls_codestpro1h),25);
		$ls_codestpro2h  = $fun->uf_cerosizquierda(trim($ls_codestpro2h),25);
		$ls_codestpro3h  = $fun->uf_cerosizquierda(trim($ls_codestpro3h),25);
		$ls_codestpro4h  = $fun->uf_cerosizquierda(trim($ls_codestpro4h),25);
		$ls_codestpro5h  = $fun->uf_cerosizquierda(trim($ls_codestpro5h),25);
		if (strtoupper($ls_gestor)=="MYSQLT")
		{
			if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!="")&&($ls_codestpro1h!="")&&($ls_codestpro2h!="")&&($ls_codestpro3h!="")&&($ls_estclades!="")&&($ls_estclahas!=""))
			{
			   $ls_codestprodes=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estclades;
			   $ls_codestprohas=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h.$ls_estclahas;
			   $ls_cad=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad="";
			}
		}
		else
		{
			if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!="")&&($ls_codestpro1h!="")&&($ls_codestpro2h!="")&&($ls_codestpro3h!="")&&($ls_estclades!="")&&($ls_estclahas!=""))
			{
			   $ls_codestprodes=trim($ls_codestpro1).trim($ls_codestpro2).trim($ls_codestpro3).trim($ls_codestpro4).trim($ls_codestpro5).trim($ls_estclades);
			   $ls_codestprohas=trim($ls_codestpro1h).trim($ls_codestpro2h).trim($ls_codestpro3h).trim($ls_codestpro4h).trim($ls_codestpro5h).trim($ls_estclahas);
			   $ls_cad=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad="";
			}
		}
	}
	else
	{
			$ls_cad="";
			if($ls_codestpro1!=""&&$ls_codestpro1!="**"&&$ls_codestpro1!="0000000000000000000000000")
			{
				$ls_codestpro1  = $fun->uf_cerosizquierda(trim($ls_codestpro1),25);
			}
			else
			{
				$ls_codestpro1  = uf_buscar($io_sql,"SELECT MIN(codestpro1) as codestpro1 FROM spg_ep1 WHERE " ,"codestpro1");
			}
			if($ls_codestpro2!=""&&$ls_codestpro2!="**"&&$ls_codestpro2!="0000000000000000000000000")
			{
				$ls_codestpro2  = $fun->uf_cerosizquierda(trim($ls_codestpro2),25);
			}
			else
			{
				$ls_codestpro2  = uf_buscar($io_sql,"SELECT MIN(codestpro2) as codestpro2 FROM spg_ep2 WHERE codestpro1='$ls_codestpro1' AND estcla='$ls_estclades' ","codestpro2");
			}			
			if($ls_codestpro3!=""&&$ls_codestpro3!="**"&&$ls_codestpro3!="0000000000000000000000000")
			{
				$ls_codestpro3  = $fun->uf_cerosizquierda(trim($ls_codestpro3),25);
			}
			else
			{
				$ls_codestpro3  = uf_buscar($io_sql,"SELECT MIN(codestpro3) as codestpro3 FROM spg_ep3 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2' AND estcla='$ls_estclades' ","codestpro3");
			}	
			if($ls_codestpro4!=""&&$ls_codestpro4!="**"&&$ls_codestpro4!="0000000000000000000000000")
			{
				//$ls_cad=$ls_cad.",codestpro4";				
				$ls_codestpro4  = $fun->uf_cerosizquierda(trim($ls_codestpro4),25);
			}
			else
			{
				$ls_codestpro4  = uf_buscar($io_sql,"SELECT MIN(codestpro4) as codestpro4 FROM spg_ep4 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2' AND codestpro3='$ls_codestpro3' AND estcla='$ls_estclades' " ,"codestpro4");
			}
			if($ls_codestpro5!=""&&$ls_codestpro5!="**"&&$ls_codestpro5!="0000000000000000000000000")
			{
				//$ls_cad=$ls_cad.",codestpro5";				
				$ls_codestpro5  = $fun->uf_cerosizquierda(trim($ls_codestpro5),25);
			}
			else
			{
				$ls_codestpro5  = uf_buscar($io_sql,"SELECT MIN(codestpro5) as codestpro5 FROM spg_ep5 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2' AND codestpro3='$ls_codestpro3' AND codestpro4='$ls_codestpro4' AND estcla='$ls_estclades' " ,"codestpro5");
			}
			
			if($ls_codestpro1h!=""&&$ls_codestpro1h!="**"&&$ls_codestpro1h!="0000000000000000000000000")
			{
				$ls_codestpro1h  = $fun->uf_cerosizquierda(trim($ls_codestpro1h),25);
			}
			else
			{
				$ls_codestpro1h  = uf_buscar($io_sql,"SELECT MAX(codestpro1) as codestpro1 FROM spg_ep1 " ,"codestpro1");
			}
			if($ls_codestpro2h!=""&&$ls_codestpro2h!="**"&&$ls_codestpro2h!="0000000000000000000000000")
			{
				$ls_codestpro2h  = $fun->uf_cerosizquierda(trim($ls_codestpro2h),25);
			}
			else
			{
				$ls_codestpro2h  = uf_buscar($io_sql,"SELECT MAX(codestpro2) as codestpro2 FROM spg_ep2 WHERE codestpro1='$ls_codestpro1h'" ,"codestpro2");
			}			
			if($ls_codestpro3h!=""&&$ls_codestpro3h!="**"&&$ls_codestpro3h!="0000000000000000000000000")
			{
				$ls_codestpro3h  = $fun->uf_cerosizquierda(trim($ls_codestpro3h),25);
			}
			else
			{
				$ls_codestpro3h  = uf_buscar($io_sql,"SELECT MAX(codestpro3) as codestpro3 FROM spg_ep3 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h' AND estcla='$ls_estclahas' " ,"codestpro3");
			}	
			if($ls_codestpro4h!=""&&$ls_codestpro4h!="**"&&$ls_codestpro4h!="0000000000000000000000000")
			{
				$ls_codestpro4h  = $fun->uf_cerosizquierda(trim($ls_codestpro4h),25);
			}
			else
			{
				$ls_codestpro4h  = uf_buscar($io_sql,"SELECT MAX(codestpro4) as codestpro4 FROM spg_ep4 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h' AND codestpro3='$ls_codestpro3h' AND estcla='$ls_estclahas'" ,"codestpro4");
			}
			if($ls_codestpro5h!=""&&$ls_codestpro5h!="**"&&$ls_codestpro5h!="0000000000000000000000000")
			{
				$ls_codestpro5h  = $fun->uf_cerosizquierda(trim($ls_codestpro5h),25);
			}
			else
			{
				$ls_codestpro5h  = uf_buscar($io_sql,"SELECT MAX(codestpro5) as codestpro5 FROM spg_ep5 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h' AND codestpro3='$ls_codestpro3h' AND codestpro4='$ls_codestpro4h' AND estcla='$ls_estclahas'" ,"codestpro5");
			}				
			$ls_codestprodes=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estclades;
			$ls_codestprohas=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h.$ls_estclahas;
			
			if (strtoupper($ls_gestor)=="MYSQLT")
		 	{
				$ls_cad=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
		
	}	
	  $ls_sql = " SELECT DISTINCT trim(spg_cuenta) as spg_cuenta,max(denominacion) as denspgcta
	                FROM spg_cuentas
		   	       WHERE codemp = '".$as_codemp."' 
			         AND spg_cuenta like '".$ls_codigo."' 
				     AND UPPER(denominacion) like '".strtoupper($ls_denominacion)."' $ls_cad
			       GROUP BY spg_cuenta
				   ORDER BY spg_cuenta";
	  $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		 }
      else
	     {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while(!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
			           $ls_spgcta    = $rs_data->fields["spg_cuenta"];
			           $ls_denspgcta = rtrim($rs_data->fields["denspgcta"]);
					   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_spgcta','$ls_denspgcta');\">".$ls_spgcta."</a></td>";
				       echo "<td style=text-align:left title='".$ls_denspgcta."' width=450>".$ls_denspgcta."</td>";
					   echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas Presupuestarias !!!");  
			  }
		 }
   }
print "</table>";
?>
<input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>"></div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(cuenta,deno)
{
  opener.document.form1.txtcuentahas.value=cuenta;
  opener.document.form1.txtcuentahas.readOnly=true;
  close();
}

function ue_search()
{
  f=document.form1;
  estmodest=f.estmodest.value;
  f.operacion.value="BUSCAR";
  if (estmodest==1)
	 {
	   f.action="sigesp_cat_ctasrephas.php?codestpro1=<?php print $ls_codestpro1;?>&codestpro2=<?php print $ls_codestpro2;?>&codestpro3=<?php print $ls_codestpro3;?>&codestpro1h=<?php print $ls_codestpro1h;?>&codestpro2h=<?php print $ls_codestpro2h;?>&codestpro3h=<?php print $ls_codestpro3h;?>&estclades=<?php print $ls_estclades?>&estclahas=<?php print $ls_estclahas?>";
     }
  else
	 {
	   f.action="sigesp_cat_ctasrephas.php?codestpro1=<?php print $ls_codestpro1;?>&codestpro2=<?php print $ls_codestpro2;?>&codestpro3=<?php print $ls_codestpro3;?>&codestpro4=<?php print $ls_codestpro4;?>&codestpro5=<?php print $ls_codestpro5;?>&codestpro1h=<?php print $ls_codestpro1h;?>&codestpro2h=<?php print $ls_codestpro2h;?>&codestpro3h=<?php print $ls_codestpro3h;?>&codestpro4h=<?php print $ls_codestpro4h;?>&codestpro5h=<?php print $ls_codestpro5h;?>&estclades=<?php print $ls_estclades?>&estclahas=<?php print $ls_estclahas?>";
     }	  
  f.submit();
}
</script>
</html>