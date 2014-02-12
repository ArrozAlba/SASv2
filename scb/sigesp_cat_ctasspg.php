<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_funciones.php");
$in           = new sigesp_include();
$con   		  = $in->uf_conectar();
$int_scg      = new class_sigesp_int_scg();
$msg          = new class_mensajes();
$fun          = new class_funciones();
$io_sql       = new class_sql($con);
$arr          = $_SESSION["la_empresa"];
$as_codemp    = $arr["codemp"];
$as_estmodest = $arr["estmodest"];
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
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="10" align="right"><div align="center">Cat&aacute;logo de Cuentas Presupuestarias de Gasto</div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><?php print $arr["nomestpro1"];?></td>
        <td><?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"];
			$ls_denominacion=$_POST["nombre"];
			$ls_codscg	= $_POST["txtcuentascg"];
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			if($as_estmodest==1)
			{
				$ls_estpro4="00";
				$ls_estpro5="00";
			}
			else
			{
				$ls_estpro4=$_POST["codestpro4"];
				$ls_estpro5=$_POST["codestpro5"];
			}						
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro2="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("hicodest2",$_GET))
			{
				$ls_estpro2=$_GET["hicodest2"];
			}
			if(array_key_exists("hicodest3",$_GET))
			{
				$ls_estpro3=$_GET["hicodest3"];
			}
			if($as_estmodest==1)
			{
				$ls_estpro4="00";
				$ls_estpro5="00";
			}
			else
			{
				if(array_key_exists("hicodest4",$_GET))
				{
					$ls_estpro4=$_GET["hicodest4"];
				}
				if(array_key_exists("hicodest5",$_GET))
				{
					$ls_estpro5=$_GET["hicodest5"];
				}
			}	
		}
		?>
		  <div align="left"></div>
		  <div align="left">
		  <?php if($as_estmodest==2){$li_size=3;$li_maxlength=2;$ls_estpro1=substr($ls_estpro1,-2);}else{$li_size=22;$li_maxlength=20;}?>
            <input name="codestpro1" type="text" id="codestpro1" size="<?php print $li_size;?>" maxlength="<?php print $li_max_length;?>" style="text-align:center " readonly value="<?php print $ls_estpro1;?>">
          </div></td>
        <td width="76" align="right"><?php print $arr["nomestpro2"];?>        </td>
        <td width="59"><div align="left">
		<?php if($as_estmodest==2){$li_size=3;$li_maxlength=2;$ls_estpro2=substr($ls_estpro2,-2);}else{$li_size=8;$li_maxlength=6;}?>
          <input name="codestpro2" type="text" id="codestpro2"  size="<?php print $li_size;?>" maxlength="<?php print $li_max_length;?>" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td width="50" align="right"><?php print $arr["nomestpro3"];?></td>
        <td width="58"><div align="left">
		<?php if($as_estmodest==2){$li_size=3;$li_maxlength=2;$ls_estpro3=substr($ls_estpro3,-2);}else{$li_size=4;$li_maxlength=3;}?>
          <input name="codestpro3" type="text" id="codestpro3" size="<?php print $li_size;?>" maxlength="<?php print $li_max_length;?>" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
        </div></td>
        <td width="39"><div align="right"><?php if($as_estmodest==2){ print $arr["nomestpro4"];}?></div>        </td>
        <td width="31"><div align="left">
		 <?php if($as_estmodest==2){ ?>
          <input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_estpro4;?>" size="3" maxlength="2" style="text-align:center " readonly>
         <?php }?>
		</div></td>
        <td width="54"><div align="right"><?php if($as_estmodest==2){print $arr["nomestpro5"];}?></div>
        <label></label></td>
        <td width="38"><div align="left">
		 <?php if($as_estmodest==2){ ?>
          <input name="codestpro5" type="text" id="codestpro5" value="<?php print $ls_estpro5;?>" size="3" maxlength="2" style="text-align:center " readonly>
         <?php }?>
		</div></td>
      </tr>
      <tr>
        <td align="right" width="76">Codigo</td>
        <td width="117"><div align="left">
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



print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
if($as_estmodest==2)
{ 
	print "<td>".$arr["nomestpro4"]."</td>";
	print "<td>".$arr["nomestpro5"]."</td>";	
}
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
$ls_sql_aux="";
if($ls_operacion=="BUSCAR")
{
	if($as_estmodest==2)
	{ 
		$ls_sql_aux=" AND codestpro4='".$ls_estpro4."' ";
		$ls_sql_aux=$ls_sql_aux." AND codestpro5='".$ls_estpro5."' ";
	}
	$ls_sql = "SELECT spg_cuenta,denominacion,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
	          "       sc_cuenta,status,disponible,                                                   ".
	          "       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible    ".
	          "  FROM spg_cuentas                                                                    ".
			  " WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."%'                ".
			  "   AND denominacion like '%".$ls_denominacion."%' AND sc_cuenta like '".$ls_codscg."%'".
			  "   AND codestpro1='".$fun->uf_cerosizquierda($ls_estpro1,20)."'                       ".
			  "   AND codestpro2='".$fun->uf_cerosizquierda($ls_estpro2,6)."'                        ".
			  "   AND codestpro3='".$fun->uf_cerosizquierda($ls_estpro3,3)."' ".$ls_sql_aux."
				ORDER BY spg_cuenta";
	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data==false)
	   {
		 $msg->message($fun->uf_convertirmsg($io_sql->message));
	   }
	else
	   {
		$li_numrows = $io_sql->num_rows($rs_data); 
		if ($li_numrows>0)
		   {
			 while ($row=$io_sql->fetch_row($rs_data))
			       {
     				 $cuenta	   = $row["spg_cuenta"];
					 $denominacion = $row["denominacion"];
					 $codest1	   = $row["codestpro1"];
					 $codest2	   = $row["codestpro2"];
					 $codest3	   = $row["codestpro3"];
					 $codest4	   = $row["codestpro4"];
					 $codest5	   = $row["codestpro5"];
					 $scgcuenta    = $row["sc_cuenta"];
					 $status       = $row["status"];
					 $disponible   = $row["disponible"];
					 if ($status=="S")
						{
						  print "<tr class=celdas-blancas>";
						  print "<td>".$cuenta."</td>";
						  print "<td  align=left>".$codest1."</td>";
						  print "<td  align=left>".$codest2."</td>";
						  print "<td  align=left>".$codest3."</td>";
						  if ($as_estmodest==2)
							 {
						 	   print "<td  align=left>".substr($codest1,-2)."</td>";
							   print "<td  align=left>".substr($codest2,-2)."</td>";
							   print "<td  align=left>".substr($codest3,-2)."</td>";
							   print "<td  align=left>".$codest4."</td>";
							   print "<td  align=left>".$codest5."</td>";
							 }
						  else
							 {
							   print "<td  align=left>".$codest1."</td>";
							   print "<td  align=left>".$codest2."</td>";
							   print "<td  align=left>".$codest3."</td>";
							 }
						  print "<td  align=left>".$denominacion."</td>";
						  print "<td  align=center>".$scgcuenta."</td>";
						  print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
				        }
				     else
				        {
						  print "<tr class=celdas-azules>";
						  print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$codest1','$codest2','$codest3','$codest4','$codest5','$status');\">".$cuenta."</a></td>";
						  print "<td  align=left>".$codest1."</td>";
					 	  print "<td  align=left>".$codest2."</td>";
						  print "<td  align=left>".$codest3."</td>";
						  if ($as_estmodest==2)
						     {
							   print "<td  align=left>".substr($codest1,-2)."</td>";
							   print "<td  align=left>".substr($codest2,-2)."</td>";
							   print "<td  align=left>".substr($codest3,-2)."</td>";
							   print "<td  align=left>".$codest4."</td>";
							   print "<td  align=left>".$codest5."</td>";
							 }
					      else
							 {
						       print "<td  align=left>".$codest1."</td>";
							   print "<td  align=left>".$codest2."</td>";
					 	       print "<td  align=left>".$codest3."</td>";
					         }
				 	      print "<td  align=left>".$denominacion."</td>";
				 	      print "<td  align=center>".$scgcuenta."</td>";
					      print "<td  align=center>".number_format($disponible,2,",",".")."</td>";		
						}
				      print "</tr>";			
			       }
			    $io_sql->free_result($rs_cta);
			    $io_sql->close();
		   }
		else
		   {
			 $msg->message("No se han creado Cuentas de gasto para la programatica seleccionada");
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

  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,codest4,codest5,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
  //	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspg.php";
	  f.submit();
  }	
</script>
</html>
