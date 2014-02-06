<?php
//session_id('8675309');
session_start();
require_once("class_funciones_activos.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_include.php");
$io_activos = new class_funciones_activos();

$li_len1=0;
$li_len2=0;
$li_len3=0;
$li_len4=0;
$li_len5=0;
$ls_titulo="";
$lb_valido  = $io_activos->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$la_empresa   = $_SESSION["la_empresa"];
$li_estmodest = $la_empresa["estmodest"];
$ls_codemp    = $la_empresa["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <?php 
		if($li_estmodest==1)
		{
	?>
	  <tr>
	    <td height="22" colspan="10" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestarias </td>
      </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
        <td align="right"><?php print $la_empresa["nomestpro1"];?></td>
        <td><?php
		$li_estmodest  = $la_empresa["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
			$ls_codscg	= $_POST["txtcuentascg"]."%";
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			$ls_estpro4="00";
			$ls_estpro5="00";
			$ls_estcla=$_POST["estcla"];
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro3="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("codestpro2",$_GET))
			{
				$ls_estpro2=$_GET["codestpro2"];
			}
			if(array_key_exists("codestpro3",$_GET))
			{
				$ls_estpro3=$_GET["codestpro3"];
			}
			if(array_key_exists("estcla",$_GET))
			{
				$ls_estcla=$_GET["estcla"];
			}
		}
		?>
          <input name="codestpro1" type="text" id="codestpro1" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" style="text-align:center " readonly value="<?php print $ls_estpro1;?>">          </td>
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
        <td width="48" align="right"><?php print $la_empresa["nomestpro2"];?>        </td>
        <td width="48"><div align="left">
          <input name="codestpro2" type="text" id="codestpro2"  size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td width="53" align="right"><?php print $la_empresa["nomestpro3"];?></td>
        <td width="35">
          
          <div align="left">
            <input name="codestpro3" type="text" id="codestpro3" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
          </div></td>
        <td width="54">&nbsp;</td>
        <td width="32">&nbsp;</td>
        <td width="49">&nbsp;</td>
        <td width="142">&nbsp;</td>
	  </tr>
	  <?php 
	  }
	  else
	  {
	  ?>
      <tr>
        <td align="right"><?php print $la_empresa["nomestpro1"];?></td>
        <td><?php
		$li_estmodest  = $la_empresa["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
			$ls_codscg	= $_POST["txtcuentascg"]."%";
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			$ls_estpro4=$_POST["codestpro4"];
			$ls_estpro5=$_POST["codestpro5"];
			$ls_estcla=$_POST["estcla"];
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro2="";
			$ls_estpro4="";
			$ls_estpro5="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("codestpro2",$_GET))
			{
				$ls_estpro2=$_GET["codestpro2"];
			}
			if(array_key_exists("codestpro3",$_GET))
			{
				$ls_estpro3=$_GET["codestpro3"];
			}
			if(array_key_exists("codestpro4",$_GET))
			{
				$ls_estpro4=$_GET["codestpro4"];
			}
			if(array_key_exists("codestpro5",$_GET))
			{
				$ls_estpro5=$_GET["codestpro5"];
			}
			if(array_key_exists("estcla",$_GET))
			{
				$ls_estcla=$_GET["estcla"];
			}
		}
		?>
        <input name="codestpro1" type="text" id="codestpro1" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" style="text-align:center " readonly value="<?php print $ls_estpro1;?>"></td>
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
        <td align="right"><?php print $la_empresa["nomestpro2"];?> </td>
        <td><div align="left">
            <input name="codestpro2" type="text" id="codestpro2" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td align="right"><?php print $la_empresa["nomestpro3"];?></td>
        <td><div align="left">
            <input name="codestpro3" type="text" id="codestpro3" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
        </div></td>
        <td><div align="right"><?php print $la_empresa["nomestpro4"];?></div></td>
        <td><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" size="<?php print ($li_len4+10); ?>" maxlength="<?php print $li_len4; ?>" style="text-align:center " readonly value="<?php print $ls_estpro4;?>">
        </div></td>
        <td><div align="right"><?php print $la_empresa["nomestpro5"];?></div></td>
        <td><div align="left">
          <input name="codestpro5" type="text" id="codestpro5" size="<?php print ($li_len5+10); ?>" maxlength="<?php print $li_len5; ?>" style="text-align:center " readonly value="<?php print $ls_estpro5;?>">
        </div></td>
      </tr>
	  <?php 
	   }
	  ?>
      <tr>
        <td align="right" width="94">Codigo</td>
        <td width="143"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="9"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="9"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="8"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center>Presupuestaria</td>";
print "<td style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
print "<td style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
print "<td style=text-align:center>".$la_empresa["nomestpro3"]."</td>";
if($li_estmodest==2)
{
	print "<td style=text-align:center>".$la_empresa["nomestpro4"]."</td>";
	print "<td style=text-align:center>".$la_empresa["nomestpro5"]."</td>";
}
print "<td style=text-align:center>Estatus</td>";
print "<td style=text-align:center>Denominación</td>";
print "<td style=text-align:center>Contable</td>";
print "<td style=text-align:center>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{

	$ls_codestpro1 = str_pad($ls_estpro1,25,0,0);
	$ls_codestpro2 = str_pad($ls_estpro2,25,0,0);
	$ls_codestpro3 = str_pad($ls_estpro3,25,0,0);
	if($li_estmodest==2)
	{
		$ls_codestpro4 = str_pad($ls_estpro4,25,0,0);
	    $ls_codestpro5 = str_pad($ls_estpro5,25,0,0);

		$ls_sql  =  "SELECT spg_cuenta,denominacion,estcla,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,sc_cuenta,status,
		                    (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
					"  FROM spg_cuentas ".
					" WHERE codemp = '".$ls_codemp."'".
					"   AND spg_cuenta like '408".$ls_codigo."'".
					"   AND denominacion like '".$ls_denominacion."'".
					"   AND sc_cuenta like '".$ls_codscg."'".
					"   AND codestpro1 like '%".$ls_codestpro1."%'".
					"   AND codestpro2 like '%".$ls_codestpro2."%'".
					"   AND codestpro3 like '%".$ls_codestpro3."%'".
					"   AND codestpro4 like '%".$ls_codestpro4."%'".
					"   AND codestpro5 like '%".$ls_codestpro5."%' ".
					"   AND estcla like '%".$ls_estcla."%' ".
					" ORDER BY spg_cuenta";
	}
	else
	{
		$ls_sql    ="SELECT spg_cuenta,denominacion,estcla,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,sc_cuenta,status,
		                    (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
					"  FROM spg_cuentas ".
					" WHERE codemp = '".$ls_codemp."'".
					"   AND spg_cuenta like '408".$ls_codigo."'".
					"   AND denominacion like '".$ls_denominacion."'".
					"   AND sc_cuenta like '".$ls_codscg."'".
					"   AND codestpro1 = '".$ls_codestpro1."'".
					"   AND codestpro2 = '".$ls_codestpro2."'".
					"   AND codestpro3 like '%".$ls_codestpro3."%'".
					"   AND estcla like '%".$ls_estcla."%' ".
					" ORDER BY spg_cuenta";
	}
	 $rs_data = $io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	 if ($rs_data===false)
	    {
	      $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
 	 else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while ($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_spgcta    = trim($row["spg_cuenta"]);
				      $ls_denctaspg = ltrim($row["denominacion"]);
				      $ls_estcla    = $row["estcla"];
					  $ls_estatus   = "";
					  switch($ls_estcla)
					  {
						case "A":
							$ls_estatus="Acción";
							break;
						case "P":
							$ls_estatus="Proyecto";
							break;
					  }
					  $ls_codestpro1 = substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
					  $ls_codestpro2 = substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
					  $ls_codestpro3 = substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
					  $ls_codestpro4 = substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
					  $ls_codestpro5 = substr($row["codestpro5"],(strlen($row["codestpro5"])-$li_len5),$li_len5);
					  $ls_scgcta     = trim($row["sc_cuenta"]);
					  $ls_status     = $row["status"];
					  $ld_disspgcta  = $row["disponible"];
				      if ($ls_status=="S") 
						 {
						   echo "<tr class=celdas-blancas>";
						   echo "<td>".$ls_spgcta."</td>";
						 }
					  elseif($ls_status=="C") 
						 {
						   echo "<tr class=celdas-azules>";
						   if ($li_estmodest=="1")
						      {
						        echo "<td><a href=\"javascript:aceptar('$ls_spgcta','$ls_denctaspg');\">".$ls_spgcta."</a></td>";						   
							  }
						   elseif($li_estmodest=="2")
						      {
						        echo "<td><a href=\"javascript:aceptar_programa('$ls_spgcta','$ls_denctaspg');\">".$ls_spgcta."</a></td>";						   
							  }
					     }
					  echo "<td  align=left>".$ls_codestpro1."</td>";
					  echo "<td  align=left>".$ls_codestpro2."</td>";
					  echo "<td  align=left>".$ls_codestpro3."</td>";
 					  if ($li_estmodest=="2")
					     {
						   echo "<td  align=left>".$ls_codestpro4."</td>";
						   echo "<td  align=left>".$ls_codestpro5."</td>";						 
						 }	
					  echo "<td  align=left>".$ls_estatus."</td>";
					  echo "<td  align=left>".$ls_denctaspg."</td>";
					  echo "<td  align=center>".$ls_scgcta."</td>";
					  echo "<td  align=right width=119>".number_format($ld_disspgcta,2,",",".")."</td>";
					  echo "</tr>";			
				    }
			  unset($row);
			  $io_sql->free_result($rs_data);
			  $io_sql->close();
			}
	     else
		    {
			  $io_msg->message("No se han creado Cuentas Presupuestarias !!!");
			}
	   }
   }
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(as_spgcta,as_denctaspg)
  {
    opener.document.form1.txtctaspg.value    = as_spgcta;
	opener.document.form1.txtdenctaspg.value = as_denctaspg;
	close();
  }
  function aceptar_programa(as_spgcta,as_denctaspg)
  {
    opener.document.form1.txtcuenta.value		= as_spgcta;
	opener.document.form1.txtdenominacion.value = as_denctaspg;
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_public_ctasspg.php";
	  f.submit();
  }
	function uf_cambio_estprog1()
	{
		f=document.form1;
		f.action="sigesp_cat_public_ctasspg.php";
		f.operacion.value="est1";
		f.submit();
	}
	function uf_cambio_estprog2()
	{
		f=document.form1;
		f.action="sigesp_cat_public_ctasspg.php";
		f.operacion.value="est2";
		f.submit();
	}
</script>
</html>