<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("sigesp_spg_c_mod_presupuestarias.php");
require_once("sigesp_spg_c_buscar_programado.php");
$io_buscar= new sigesp_spg_c_buscar_programado();
$io_validacion=new sigesp_spg_c_mod_presupuestarias();
$io_fecha=new class_fecha();
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$fun    = new class_funciones();
$io_sql = new class_sql($con);
$arr=$_SESSION["la_empresa"];
$li_estmodest  = $arr["estmodest"];
$as_codemp=$arr["codemp"];

$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
$ls_estvaldis = $_SESSION["la_empresa"]["estvaldis"];
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="opera" type="hidden" id="opera" value="<? print $ls_opera; ?>">
  </p>
  <br>
  <div align="center">
    <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria</td>
      </tr>
      <tr>
        <td width="120" height="13" align="right">&nbsp;</td>
        <td width="117" height="13">&nbsp;</td>
        <td width="461" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro1"];?></td>
        <td height="22" colspan="2"><?php
		$li_estmodest  = $arr["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion    = $_POST["operacion"];
			$ls_codigo       = $_POST["codigo"]."%";
			$ls_denominacion = "%".$_POST["nombre"]."%";
			$ls_codscg	     = $_POST["txtcuentascg"]."%";
			$ls_estpro1      = $_POST["codestpro1"];
			$ls_estpro2      = $_POST["codestpro2"];
			$ls_estpro3		 = $_POST["codestpro3"];
			if((array_key_exists("codestpro4",$_POST)))
			{
				$ls_estpro4=$_POST["codestpro4"];
			}
			if(array_key_exists("codestpro5",$_POST))
			{
				$ls_estpro5=$_POST["codestpro5"];
			}
            $ls_opera  = $_GET["opera"];

			if(array_key_exists("estcla",$_POST))
			{
				$ls_estcla=$_POST["estcla"];
			}
			
			if((array_key_exists("opeaumdis",$_POST)))
			{
				$ls_opeaumdis=$_POST["opeaumdis"];
			}
			
			if((array_key_exists("procede",$_POST)))
			{
				$ls_procede=$_POST["procede"];
			}
			
			
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
			if(array_key_exists("hicodest2",$_GET))
			{
				$ls_estpro2=$_GET["hicodest2"];
			}
			if(array_key_exists("hicodest3",$_GET))
			{
				$ls_estpro3=$_GET["hicodest3"];
			}
			if(array_key_exists("hicodest4",$_GET))
			{
				$ls_estpro4=$_GET["hicodest4"];
			}
			if(array_key_exists("hicodest5",$_GET))
			{
				$ls_estpro5=$_GET["hicodest5"];
			}			
			if(array_key_exists("opera",$_GET))
			{
				$ls_opera=$_GET["opera"];
			}
			else
			{
				$ls_opera="";
			}
			
            if(array_key_exists("opeaumdis",$_GET))
			{
				$ls_opeaumdis=$_GET["opeaumdis"];
			}
			else
			{
				$ls_opeaumdis = "";
			}
			
			if(array_key_exists("procede",$_GET))
			{
				$ls_procede=$_GET["procede"];
			}
			else
			{
				$ls_procede="";
			}
			
			if(array_key_exists("estcla",$_GET))
			{
				$ls_estcla=$_GET["estcla"];
			}
		}
		?>
        <input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center " readonly value="<?php print $ls_estpro1;?>">
        <span class="Estilo1">
        <input name="estcla"  type="hidden" id="estcla" value="<? print $ls_estcla; ?>">
        </span></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro2"];?></td>
        <td height="22" colspan="2"><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center " readonly value="<?php print $ls_estpro2;?>"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro3"];?></td>
        <td height="22" colspan="2"><input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center " readonly value="<?php print $ls_estpro3;?>"></td>
      </tr>
      <?php
	  if ($li_estmodest=='2')
	     {
	  ?>
	  <tr>
        <td height="22" align="right"><?php print $arr["nomestpro4"];?></td>
        <td height="22" colspan="2"><input name="codestpro4" type="text" id="codestpro4" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center " readonly value="<?php print $ls_estpro4;?>"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro5"];?></td>
        <td height="22" colspan="2"><input name="codestpro5" type="text" id="codestpro5" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center " readonly value="<?php print $ls_estpro5;?>"></td>
      </tr>
	  <?php
	    }
	  ?>
      <tr>
        <td height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2" style="text-align:left"><input name="codigo" type="text" id="codigo" size="22" maxlength="20" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php	
$ls_estmodprog=$arr["estmodprog"];// si esta configurado para realizar la modificaciòn al monto programado

$ls_estmodape=$arr["estmodape"];// si la apertura fue mensual o trimestral

print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
if($li_estmodest==2)
{
	print "<td>".$arr["nomestpro4"]."</td>";
	print "<td>".$arr["nomestpro5"]."</td>";
}
print "<td>Denominación</td>";
print "<td>Contable</td>";
if ($ls_estmodprog==0)
{
	print "<td>Disponible</td>";
}
else
{
	$fecha = date("d/m/Y");
	$ano=substr($arr["periodo"],0,4);
	$mes1=substr($fecha,3,2);
	if ($ls_estmodape==0)
	{
		switch ($mes1) 
		{
				case "01":
					$mes_="Enero";											        
				break;
				case "02":
					$mes_="Febrero";					
				break;
				case "03":
					$mes_="Marzo";					
				break;
				case "04":
					$mes_="Abril";						
				break;
				case "05":
					$mes_="Mayo";						
				break;
				case "06":
					$mes_="Junio";						
				break;
				case "07":
					$mes_="Julio";							
				break;
				case "8":
					$mes_="Agosto";					
				break;
				case "9":
					$mes_="Septiembre";					
				break;
				case "10":
					$mes_="Octubre";						
				break;
				case "11":
					$mes_="Noviembre";					
				break;
				case "12":
					$mes_="Diciembre";					
				break;
			}
		print "<td>Disponible $mes_</td>";
	}
	else
	{
		switch ($mes1) 
		{
				case "01":
					$mes_="Enero-Marzo";											        
				break;
				case "02":
					$mes_="Enero-Marzo";						
				break;
				case "03":
					$mes_="Enero-Marzo";					
				break;
				case "04":
					$mes_="Abril-Junio";						
				break;
				case "05":
					$mes_="Abril-Junio";						
				break;
				case "06":
					$mes_="Abril-Junio";					
				break;
				case "07":
					$mes_="Julio-Septiembre";							
				break;
				case "8":
					$mes_="Julio-Septiembre";					
				break;
				case "9":
					$mes_="Julio-Septiembre";						
				break;
				case "10":
					$mes_="Octubre-Diciembre";						
				break;
				case "11":
					$mes_="Octubre-Diciembre";					
				break;
				case "12":
					$mes_="Octubre-Diciembre";				
				break;
			}
		print "<td>Disponible Trimestre $mes_</td>";
	
	}
}

print "</tr>";
if($ls_operacion=="BUSCAR")
{
	    $ls_estmodprog=$arr["estmodprog"];// si esta configurado para realizar la modificaciòn al monto programado
	
		if($ls_opera=="DI")
		{
	 		 $ls_cadena="AND spg_cuenta like '498%' ";
		}
		else
		{
	  	$ls_cadena=" ";
		}
			$li_validacion = "";
			$ls_ctaspgrec  = "";
			$ls_ctaspgced  = "";
			$ls_cadenaval  = "";
			$ls_cuentas    = "";
		
			$io_validacion->uf_obtener_validacion_spg($as_codemp,$li_validacion,$ls_ctaspgrec,$ls_ctaspgced);
			if($li_validacion == 1)
			{
				if(($ls_procede == "SPGTRA")&&($ls_opeaumdis == "AU"))
				{
					$ls_cuentas = str_replace(",","",$ls_ctaspgrec);
					$la_cuenta  = str_split($ls_cuentas,3);
					$li_total   = count($la_cuenta);
					for($i=0;$i<$li_total;$i++)
					{
						if ($i ==0)
						{
							$ls_cadenaval = " AND substr(spg_cuenta,1,3) IN('".$la_cuenta[$i]."'";
						}
						 else
						{
							$ls_cadenaval = $ls_cadenaval.",'".$la_cuenta[$i]."'";
						}
					}
					$ls_cadenaval =  $ls_cadenaval.")";
				}
				elseif(($ls_procede == "SPGTRA")&&($ls_opeaumdis == "DI"))
				{
					$ls_cuentas = str_replace(",","",$ls_ctaspgced);
					$la_cuenta  = str_split($ls_cuentas,3);
					$li_total   = count($la_cuenta);
					for($i=0;$i<$li_total;$i++)
					{
					   if ($i ==0)
					   {
						$ls_cadenaval = " AND substr(spg_cuenta,1,3) IN('".$la_cuenta[$i]."'";
					   }
					   else
					   {
						$ls_cadenaval = $ls_cadenaval.",'".$la_cuenta[$i]."'";
					   }
					}// fin del for
					$ls_cadenaval =  $ls_cadenaval.")";
				}		 
            }
   	    $ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$as_codemp."','SPG','".$ls_logusr."',codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG') ";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$as_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT distinct codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG' UNION SELECT distinct sss_permisos_internos_grupo.codemp||'SPG'||codusu||codintper
		                       FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru ) ";
		}
	if ($ls_estmodprog==0)// se utiliza el monto asignado	
	{	
		if($li_estmodest==2)
		{
			$ls_estpro1=$fun->uf_cerosizquierda($ls_estpro1,25);
			$ls_estpro2=$fun->uf_cerosizquierda($ls_estpro2,25);
			$ls_estpro3=$fun->uf_cerosizquierda($ls_estpro3,25);
			$ls_estpro4=$fun->uf_cerosizquierda($ls_estpro4,25);
			$ls_estpro5=$fun->uf_cerosizquierda($ls_estpro5,25);
			$ls_sql =" SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
						" FROM  spg_cuentas ".
						" WHERE codemp = '".$as_codemp."' AND  spg_cuenta like '".$ls_codigo."' AND ".
						"       denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' AND ".
						"       codestpro1 = '".$ls_estpro1."' AND codestpro2 = '".$ls_estpro2."' AND  ".
						"       codestpro3 = '".$ls_estpro3."' AND codestpro4 = '".$ls_estpro4."' AND  ".
						"       codestpro5 = '".$ls_estpro5."' AND estcla='".$ls_estcla."'  ".$ls_cadena." ".$ls_sql_seguridad.$ls_cadenaval.
						" ORDER BY spg_cuenta";
	    }
		else
		{
	    $ls_estpro1=$fun->uf_cerosizquierda($ls_estpro1,25);
	    $ls_estpro2=$fun->uf_cerosizquierda($ls_estpro2,25);
	    $ls_estpro3=$fun->uf_cerosizquierda($ls_estpro3,25);
		$ls_sql =" SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
		         " FROM  spg_cuentas ".
		 		 " WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."' AND ".
				 "       denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' AND ".
				 "       codestpro1 = '".$ls_estpro1."' AND codestpro2 = '".$ls_estpro2."' AND ".
				 "       codestpro3 = '".$ls_estpro3."' AND estcla='".$ls_estcla."' ".$ls_cadena." ".$ls_sql_seguridad.$ls_cadenaval.
				 " ORDER BY spg_cuenta";
	}
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
		      while($row=$io_sql->fetch_row($rs_data))
			  { 
				     $ls_spgcta     = trim($row["spg_cuenta"]);
					 $ls_denctaspg  = $row["denominacion"];
					 $ls_codestpro1 = substr($row["codestpro1"],-$li_loncodestpro1);
					 $ls_codestpro2 = substr($row["codestpro2"],-$li_loncodestpro2);
				     $ls_codestpro3 = substr($row["codestpro3"],-$li_loncodestpro3);
					 if ($li_estmodest==2)
				        {
						  $ls_codestpro4 = substr($row["codestpro4"],-$li_loncodestpro4);
					      $ls_codestpro5 = substr($row["codestpro5"],-$li_loncodestpro5);
				        }
				     $ls_scgcta = trim($row["sc_cuenta"]);
				     $ls_status = $row["status"];
				     $ld_mondis = $row["disponible"];
				     if ($li_estmodest==2)
				        {
					      if ($ls_status=="S")
					         {
							   print "<tr class=celdas-blancas>";
							   print "<td>".$ls_spgcta."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro1."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro2."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro3."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro4."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro5."</td>";
							   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
							   print "<td  width=30 align=center>".$ls_scgcta."</td>";
							   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					         }
					      else
					         {
					 	       print "<tr class=celdas-azules>";
						       print "<td align=center><a href=\"javascript:aceptar_programa('$ls_spgcta','$ls_denctaspg','$ls_scgcta',
							   '$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5',
							   '$ls_status','$ld_mondis','$ls_scgcta','$ls_estvaldis');\">".$ls_spgcta."</a></td>";
							   print "<td  width=30 align=center>".$ls_codestpro1."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro2."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro3."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro4."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro5."</td>";
							   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
							   print "<td  width=30 align=center>".$ls_scgcta."</td>";
							   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";				
					         }
					      print "</tr>";			
				        }
				      else
				        {
					      if ($ls_status=="S")
					         {
							   print "<tr class=celdas-blancas>";
							   print "<td align=center>".$ls_spgcta."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro1."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro2."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro3."</td>";
							   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
							   print "<td  width=30 align=center>".$ls_scgcta."</td>";
							   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					         }
					      else
					         {
							   print "<tr class=celdas-azules>";
							   print "<td align=center><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg','$ls_scgcta','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_status','$ld_mondis','$ls_scgcta','$ls_estvaldis');\">".$ls_spgcta."</a></td>";
							   print "<td  width=30 align=center>".$ls_codestpro1."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro2."</td>";
							   print "<td  width=30 align=center>".$ls_codestpro3."</td>";
							   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
							   print "<td  width=30 align=center>".$ls_scgcta."</td>";
							   print "<td  width=30 align=center>".number_format($ld_mondis,2,",",".")."</td>";				
					         }
					      print "</tr>";			
				       }
			
			       }
			  $io_sql->free_result($rs_data);
			  $io_sql->close();
		    }
		 	else
		 	{ 
				  ?>
			  		<script language="JavaScript">
		 	  		alert("No se han creado Cuentas.....");
			  		close();
		 	  		</script>
		 	  	<?php
	  	 	}
	   }
	}
	else
	{
		if($li_estmodest==2)
		{
			$ls_estpro1=str_pad($ls_estpro1,25,"0",0);
			$ls_estpro2=str_pad($ls_estpro2,25,"0",0);
			$ls_estpro3=str_pad($ls_estpro3,25,"0",0);
			$ls_estpro4=str_pad($ls_estpro4,25,"0",0);
			$ls_estpro5=str_pad($ls_estpro5,25,"0",0);		
		}
		else
		{
			$ls_estpro1=str_pad($ls_estpro1,25,"0",0);
			$ls_estpro2=str_pad($ls_estpro2,25,"0",0);
			$ls_estpro3=str_pad($ls_estpro3,25,"0",0);
			$ls_estpro4=str_pad("0",25,"0",0);
			$ls_estpro5=str_pad("0",25,"0",0);	
		}
		$ls_estmodape=$arr["estmodape"];// si la apertura fue mensual o trimestral
		$fecha = date("d/m/Y");
		$ano=substr($arr["periodo"],0,4);
		$mes=substr($fecha,3,2);
		if ($ls_estmodape==0)
		{			
			switch ($mes) 
			{
				case "01":
					$ls_mes="enero";
				break;
				case "02":
					$ls_mes="febrero";
				break;
				case "03":
					$ls_mes="marzo";
				break;
				case "04":
					$ls_mes="abril";
				break;
				case "05":
					$ls_mes="mayo";
				break;
				case "06":
					$ls_mes="junio";
				break;
				case "07":
					$ls_mes="julio";
				break;
				case "8":
					$ls_mes="agosto";
				break;
				case "9":
					$ls_mes="septiembre";
				break;
				case "10":
					$ls_mes="octubre";
				break;
				case "11":
					$ls_mes="noviembre";
				break;
				case "12":
					$ls_mes="diciembre";
				break;
			}
			$fecIni=$ano."-".$mes."-01";
			$fecFin=$fun->uf_convertirdatetobd($fecha);
		}
		else
		{
			switch ($mes) 
			{
				case "01":
					$ls_mes="marzo";
					$fecIni=$ano."-01-01";// primer mes del I Trimestre
					$diames=$io_fecha->uf_last_day(3,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;							        
				break;
				case "02":
					$ls_mes="marzo";
					$fecIni=$ano."-01-01";
					$diames=$io_fecha->uf_last_day(3,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
				case "03":
					$ls_mes="marzo";
					$fecIni=$ano."-01-01";
					$diames=$io_fecha->uf_last_day(3,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
				case "04":
					$ls_mes="junio";
					$fecIni=$ano."-04-01";// primer mes del II Trimestre
					$diames=$io_fecha->uf_last_day(6,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;	
				break;
				case "05":
					$ls_mes="junio";
					$fecIni=$ano."-04-01";
					$diames=$io_fecha->uf_last_day(6,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;	
				break;
				case "06":
					$ls_mes="junio";
					$fecIni=$ano."-04-01";
					$diames=$io_fecha->uf_last_day(6,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;	
				break;
				case "07":
					$ls_mes="septiembre";
					$fecIni=$ano."-07-01";// primer mes del III Trimestre
					$diames=$io_fecha->uf_last_day(9,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;		
				break;
				case "8":
					$ls_mes="septiembre";
					$fecIni=$ano."-07-01";
					$diames=$io_fecha->uf_last_day(9,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
				case "9":
					$ls_mes="septiembre";
					$fecIni=$ano."-07-01";
					$diames=$io_fecha->uf_last_day(9,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
				case "10":
					$ls_mes="diciembre";
					$fecIni=$ano."-10-01";// primer mes del IV Trimestre
					$diames=$io_fecha->uf_last_day(12,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;	
				break;
				case "11":
					$ls_mes="diciembre";
					$fecIni=$ano."-10-01";
					$diames=$io_fecha->uf_last_day(9,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
				case "12":
					$ls_mes="diciembre";
					$fecIni=$ano."-10-01";
					$diames=$io_fecha->uf_last_day(9,$ano);// ultimo mes del I trimestre
				    $fechaFinal=$diames;
				break;
			}
			
			$ls_fecfin=""; 
			$li_pos=strpos($fechaFinal,"/");
			$li_pos2=strpos($fechaFinal,"-");
			if(($li_pos==2)||($li_pos2==2))
			{
				 $fecFin=(substr($fechaFinal,5,4)."-".str_pad(substr($fechaFinal,3,1),2,"0",0)."-".substr($fechaFinal,0,2)); 
			}
		}	
		if($ls_opera=="DI")
		{
		  $ls_cadena="AND spg_cuentas.spg_cuenta like '498%' ";
		}
		else
		{
		  $ls_cadena=" ";
		}	
		$ls_sql="    select trim(spg_cuentas.spg_cuenta) as spg_cuenta, spg_cuentas.denominacion, spg_cuentas.status,".
		        "           spg_cuentas.codestpro1,  ".
				"			spg_cuentas.codestpro2, spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5, ".
				"			spg_cuentas.estcla, spg_cuentas.$ls_mes as programado, spg_cuentas.spg_cuenta, spg_cuentas.sc_cuenta".				
				"			from spg_cuentas ".
				"           where codemp = '".$as_codemp."' ".
				"           and  spg_cuenta like '".$ls_codigo."'  ".
				"           and denominacion like '".$ls_denominacion."' ".
				"           and sc_cuenta like '".$ls_codscg."'  ".
				"           and codestpro1 = '".$ls_estpro1."' ".
				"           and codestpro2 = '".$ls_estpro2."'  ".
				"           and codestpro3 = '".$ls_estpro3."'  ".
				"           and codestpro4 = '".$ls_estpro4."'   ".
				"           and codestpro5 = '".$ls_estpro5."'  ".
				"           and spg_cuentas.estcla='".$ls_estcla."'".$ls_cadena.$ls_sql_seguridad.$ls_cadenaval.
				 " ORDER BY spg_cuentas.spg_cuenta";
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
		      			while($row=$io_sql->fetch_row($rs_data))
			        	{ 
				     	 $ls_spgcta     = $row["spg_cuenta"];
						 $ls_denctaspg  = $row["denominacion"];
					 	 $ls_codestpro1 = $row["codestpro1"];
					 	 $ls_codestpro2 = $row["codestpro2"];
				     	 $ls_codestpro3 = $row["codestpro3"];
					     if ($li_estmodest==2)
				         {
						  	$ls_codestpro4 = $row["codestpro4"];
					      	$ls_codestpro5 = $row["codestpro5"];
				         }
						 else
						 {
						 	$ls_codestpro4 = str_pad(0,25,"0",0);
					      	$ls_codestpro5 = str_pad(0,25,"0",0);
						 }
				     	 $ls_scgcta = $row["sc_cuenta"];
				         $ls_status = $row["status"];
						 $ls_estcla= $row["estcla"];
						 
						 $ls_aumento="";
						 $ls_opera="aumento";
						 $ls_aumento=$io_buscar->uf_buscar_monto($ls_opera,$fecIni,$fecFin, $ls_codestpro1,$ls_codestpro2,
						                                    $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spgcta);
						 $ls_disminucion="";
						 $ls_opera="disminucion";
						 $ls_disminucion=$io_buscar->uf_buscar_monto($ls_opera,$fecIni,$fecFin, $ls_codestpro1,$ls_codestpro2,
						                                    $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spgcta);
						 $ls_comprometer="";
						 $ls_opera="comprometer";
						 $ls_comprometer=$io_buscar->uf_buscar_monto($ls_opera,$fecIni,$fecFin, $ls_codestpro1,$ls_codestpro2,
						                                    $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spgcta);
						 $ls_precomprometer="";
						 $ls_opera="precomprometer";
						 $ls_precomprometer=$io_buscar->uf_buscar_monto($ls_opera,$fecIni,$fecFin, $ls_codestpro1,$ls_codestpro2,
						                                    $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spgcta);
															
						 $ld_mondis =($row["programado"]+$ls_aumento)-($ls_disminucion+$ls_comprometer+$ls_precomprometer);						
				         if ($li_estmodest==2)
				         {
					      		$ls_codestpro1 = substr($ls_codestpro1,18,2);
						 		$ls_codestpro2 = substr($ls_codestpro2,4,2);
					      		$ls_codestpro3 = substr($ls_codestpro3,1,2);
					      		$ls_codestpro4 = substr($ls_codestpro4,0,2);
					      		$ls_codestpro5 = substr($ls_codestpro5,0,2);
					      		if ($ls_status=="S")
					         	{
							   		print "<tr class=celdas-blancas>";
							   		print "<td>".$ls_spgcta."</td>";
								    print "<td  width=30 align=left>".$ls_codestpro1."</td>";
							  		print "<td  width=30 align=left>".$ls_codestpro2."</td>";
							   		print "<td  width=30 align=left>".$ls_codestpro3."</td>";
							   		print "<td  width=30 align=left>".$ls_codestpro4."</td>";
							   		print "<td  width=30 align=left>".$ls_codestpro5."</td>";
							  		print "<td  width=30 align=left>".$ls_denctaspg."</td>";
							   		print "<td  width=30 align=center>".$ls_scgcta."</td>";
							   		print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					        	}
					      		else
					            {
								   print "<tr class=celdas-azules>";
								   print "<td><a href=\"javascript:aceptar_programa('$ls_spgcta','$ls_denctaspg','$ls_scgcta',
								   '$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5',
								   '$ls_status','$ld_mondis','$ls_scgcta','$ls_estvaldis');\">".$ls_spgcta."</a></td>";
								   print "<td  width=30 align=left>".$ls_codestpro1."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro2."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro3."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro4."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro5."</td>";
								   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
								   print "<td  width=30 align=center>".$ls_scgcta."</td>";
								   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";				
					         	}
					     		print "</tr>";			
				        	}
				         else
				         {
					      	if ($ls_status=="S")
					         {
								   print "<tr class=celdas-blancas>";
								   print "<td>".$ls_spgcta."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro1."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro2."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro3."</td>";
								   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
								   print "<td  width=30 align=center>".$ls_scgcta."</td>";
								   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					         }
					         else
					         {
								   print "<tr class=celdas-azules>";
								   print "<td><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg','$ls_scgcta','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_status','$ld_mondis','$ls_scgcta','$ls_estvaldis');\">".$ls_spgcta."</a></td>";
								   print "<td  width=30 align=left>".$ls_codestpro1."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro2."</td>";
								   print "<td  width=30 align=left>".$ls_codestpro3."</td>";
								   print "<td  width=30 align=left>".$ls_denctaspg."</td>";
								   print "<td  width=30 align=center>".$ls_scgcta."</td>";
								   print "<td  width=30 align=center>".number_format($ld_mondis,2,",",".")."</td>";					
					         }
					         print "</tr>";			
				         }
			
			       }
			        	$io_sql->free_result($rs_data);
			        	$io_sql->close();
		            }
		 			else
		 			{ 
				  		?>
			  			<script language="JavaScript">
		 	  			alert("No se han creado Cuentas.....");
			  			close();
		 	  			</script>
		 	  			<?php
	  	 			}// fin del else
	   			}// fin del else
	}// fin del else
}
print "</table>";
?>
</div>
    <input name="opeaumdis" type="hidden" id="opeaumdis" value="<? print $ls_opeaumdis; ?>">
    <input name="procede" type="hidden" id="procede" value="<? print $ls_procede; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  //-------------------------------------------------------------------------------------
  		function lTrim(sStr)
		{
			 while (sStr.charAt(0) == " ")
		     sStr = sStr.substr(1, sStr.length - 1);
			 return sStr;
		}	 
		
		function rTrim(sStr)
		{
			 while (sStr.charAt(sStr.length - 1) == " ")
		     sStr = sStr.substr(0, sStr.length - 1);
			 return sStr;
		}
		function allTrim(sStr){
		  return rTrim(lTrim(sStr));
		}
  //------------------------------------------------------------------------------------
  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status,disponible,scgcta,estvaldis)
  {
		
		opener.document.form1.txtcuenta.value=cuenta;
		opener.document.form1.txtdenominacion.value=deno;
		ls_opener = opener.document.form1.id;
		if (ls_opener=='sigesp_w_regdt_presupuesto_2')
		{
			disponible=rTrim(disponible);
			if ((disponible>0) || (estvaldis==0))
			{
				opener.document.form1.txtscg.value=scgcta;
				close();
			}
			else
			{
				alert("La Partida, No tiene Disponibilidad Presupuestaria !!!");
			}
		}
		else
		{				
			close();		
		}
  }
  function aceptar_programa(cuenta,deno,scgcuenta,codest1,codest2,codest3,codest4,codest5,status,disponible,scgcta,estvaldis)
  {
	opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	ls_opener = opener.document.form1.id;
		if (ls_opener=='sigesp_w_regdt_presupuesto_2')
		{
			disponible=rTrim(disponible);
			if ((disponible>0) || (estvaldis==0))
			{
				opener.document.form1.txtscg.value=scgcta;
				close();
			}
			else
			{
				alert("La Partida, No tiene Disponibilidad Presupuestaria !!!");
			}
		}
		else
		{				
			close();		
		}
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspg.php?opera=<?php print $ls_opera ?>";
	  f.submit();
  }
  function uf_cambio_estprog1()
  {
	f=document.form1;
	f.action="sigesp_cat_ctasspg.php";
	f.operacion.value="est1";
	f.submit();
  }
  function uf_cambio_estprog2()
  {
	f=document.form1;
	f.action="sigesp_cat_ctasspg.php";
	f.operacion.value="est2";
	f.submit();
  }
</script>
</html>