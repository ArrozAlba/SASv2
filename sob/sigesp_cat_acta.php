<?php 
session_start();
$la_datemp=$_SESSION["la_empresa"];
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="a.codact";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Actas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
	color: #006699#006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<?php print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<?php print $ls_orden;?>">
<?php 

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();


if(array_key_exists("operacion",$_POST))
{
	$ls_tipoacta=$_POST["cmbtipoacta"];
	$ls_operacion=$_POST["operacion"];
	$ls_codcon="%".$_POST["txtcodcon"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
}
else
{
	$ls_operacion="";
	$ls_tipoacta=$_GET["tipoacta"];
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
		<?Php
		if($ls_tipoacta=="s1")
		{
		?>
     	 	<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas</td>
		<?php 
		}
		elseif($ls_tipoacta==1)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Inicio</td>
		<?php 
		}
		elseif($ls_tipoacta==2)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Finalización</td>
		<?php 
		}
		elseif($ls_tipoacta==3)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Reanudación</td>
		<?php 
		}
		elseif($ls_tipoacta==4)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Paralización</td>
		<?php 
		}
		elseif($ls_tipoacta==5)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Prórroga</td>
		<?php 
		}
		elseif($ls_tipoacta==6)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Recepción Provisional</td>
		<?php 
		}
		elseif($ls_tipoacta==7)
		{
		?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Actas de Recepción Definitiva</td>
		<?php 
		}
		?>
    	</tr>
	 </table>
	 <br>
	 <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="26">&nbsp;</td>
        <td><div align="right">Tipo de Acta </div></td>
        <td>
		<?Php			
			$la_data["value"][2]="1";
			$la_data["etiq"][2]="Inicio";
			$la_data["value"][3]="2";
			$la_data["etiq"][3]="Finalización";
			$la_data["value"][4]="3";
			$la_data["etiq"][4]="Recepción Provisional";
			$la_data["value"][5]="4";
			$la_data["etiq"][5]="Recepción Definitiva";
			$la_data["value"][6]="5";
			$la_data["etiq"][6]="Paralización";
			$la_data["value"][7]="6";
			$la_data["etiq"][7]="Reanudación";
			$la_data["value"][8]="7";
			$la_data["etiq"][8]="Prórroga";
			$io_datastore->data=$la_data;
		?>			
         <select name="cmbtipoacta" size="1" id="cmbtipoacta">
                <option value="s1">Seleccione...</option>
                <?Php
					for($li_i=2;$li_i<=8;$li_i++)
					{
						 $ls_value=$io_datastore->getValue("value",$li_i);
						 $ls_etiqueta=$io_datastore->getValue("etiq",$li_i);
						 if ($ls_value==$ls_tipoacta)
						 {
							  print "<option value='$ls_value' selected>$ls_etiqueta</option>";
						 }
						 else
						 {
							  print "<option value='$ls_value'>$ls_etiqueta</option>";
						 }
					} 
	            ?>
              </select>
              <input name="hidtipoacta" type="hidden" id="hidtipoacta" value="<?php print $ls_tipoacta ?>">
		</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="52" height="26"><div align="right"></div></td>
        <td width="196"><div align="right">C&oacute;digo del Contrato</div></td>
        <td><input name="txtcodcon" id="txtcodcon" type="text" size="15" maxlength="12" >          
        <div align="right"></div>          <div align="right"></div></td>
        <td width="219">&nbsp;</td>
      </tr>
      <tr>
        <td height="27"><div align="right"></div></td>
        <td height="27"><div align="right">Descripcion de la Obra </div></td>
        <td>
          <div align="left">
            <input name="txtdesobr" id="txtdesobr" type="text" size="30">
          </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>	
	<br>
<?php 

if($ls_operacion=="BUSCAR")
{//1
	$ls_codemp=$la_datemp["codemp"];
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
	if($ls_tipoacta=="s1")
	{
		$ls_sql="SELECT a.codcon,c.precon,a.tipact,a.codact,a.fecact,a.fecrecact,a.feciniact,a.fecfinact,o.desobr,a.cedinsact,".
				"       a.cedresact,a.estact,ag.cod_pro,ag.cod_pro_ins,a.obsact,a.motact,a.nomresact,a.civresact,a.civinsact,".
				"       (SELECT ".$ls_cadena." FROM sno_personal WHERE sno_personal.cedper=a.cedinsact) AS nominsact".
				"  FROM sob_acta a, sob_asignacion ag, sob_obra o,sob_contrato c".
				" WHERE a.codemp='".$ls_codemp."'".
				"   AND a.codemp=ag.codemp".
				"   AND ag.codemp=o.codemp".
				"   AND a.codemp=c.codemp".
				"   AND a.codcon=c.codcon".
				"   AND c.codasi=ag.codasi".
				"   AND ag.codobr=o.codobr".
				"   AND a.codcon like '".$ls_codcon."'".
				"   AND o.desobr like '".$ls_desobr."'".
				" ORDER BY $ls_campo $ls_orden";			
	}
	else
	{
		$ls_sql="SELECT a.codcon,c.precon,a.codact,a.tipact,a.fecact,a.fecrecact,a.feciniact,a.fecfinact,o.desobr,a.cedinsact,".
				"       a.cedresact,a.estact,ag.cod_pro,ag.cod_pro_ins,a.obsact,a.motact,a.nomresact,a.civresact,a.civinsact,".
				"       (SELECT ".$ls_cadena." FROM sno_personal WHERE sno_personal.cedper=a.cedinsact) AS nominsact".
				"  FROM sob_acta a, sob_asignacion ag, sob_obra o,sob_contrato c".
				" WHERE a.codemp='".$ls_codemp."'".
				"   AND a.codemp=ag.codemp".
				"   AND ag.codemp=o.codemp".
				"   AND a.tipact='".$ls_tipoacta."'".
				"   AND a.codemp=c.codemp".
				"   AND a.codcon=c.codcon".
				"   AND c.codasi=ag.codasi".
				"   AND ag.codobr=o.codobr".
				"   AND a.codcon like '".$ls_codcon."'".
				"   AND o.desobr like '".$ls_desobr."'".
				" ORDER BY $ls_campo $ls_orden";			
	}	
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
		print $io_sql->message;
	}
	else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("codact");
/*			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_sql="SELECT nomsup,civ 
						FROM rpc_supervisores
						WHERE codemp='".$ls_codemp."' AND cedsup='".$data["cedinsact"][$li_i]."'";
				$io_sql=new class_sql($io_connect);
				$rs_datasup=$io_sql->select($ls_sql);
				if($rs_datasup===false)
				{
					$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
					print $is_msg_error;
				}
				else
				{
					if($row=$io_sql->fetch_row($rs_datasup))
					{
						$datasup=$io_sql->obtener_datos($rs_datasup);
						$data["nominsact"][$li_i]=$datasup["nomsup"][1];
						$data["civinsact"][$li_i]=$datasup["civ"][1];
																
					}//7
					$io_sql=new class_sql($io_connect);
					$ls_sql="SELECT nomsup,civ 
						FROM rpc_supervisores
						WHERE codemp='".$ls_codemp."' AND cedsup='".$data["cedresact"][$li_i]."'";
					$rs_datasup=$io_sql->select($ls_sql);
					if($rs_datasup===false)
					{//8
						$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
						print $is_msg_error;
					}//8
					else
					{//9
						if($row=$io_sql->fetch_row($rs_datasup))
						{//10
							$datasup=$io_sql->obtener_datos($rs_datasup);
							$data["nomresact"][$li_i]=$datasup["nomsup"][1];
							$data["civresact"][$li_i]=$datasup["civ"][1];						
						}//10
					}//9	
				}//6								
			}//4		
*/			
			print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><a href=javascript:ue_ordenar('a.tipact','BUSCAR');><font color=#FFFFFF>Tipo Acta</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.codcon','BUSCAR');><font color=#FFFFFF>Cód. Contrato</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Descripción de la Obra</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('ag.cod_pro_ins','BUSCAR');><font color=#FFFFFF>Inspector</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.cedresact','BUSCAR');><font color=#FFFFFF>Residente</font></a></td>";	
			print "<td><a href=javascript:ue_ordenar('a.estact','BUSCAR');><font color=#FFFFFF>Est. del Acta</font></a></td>";				
			print "</tr>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{//11
				print "<tr class=celdas-blancas align=center>";
				$ls_codact=$data["codact"][$li_z];
				$ls_codcon=$data["codcon"][$li_z];
				$ls_desobr=$data["desobr"][$li_z];
				$ls_estact=$io_funsob->uf_convertir_numeroestado($data["estact"][$li_z]);
				$ls_fecact=$io_funcion->uf_convertirfecmostrar($data["fecact"][$li_z]);
				if($data["feciniact"][$li_z]!="")
					$ls_feciniact=$io_funcion->uf_convertirfecmostrar($data["feciniact"][$li_z]);
				else
					$ls_feciniact="";
				if($data["fecfinact"][$li_z]!="")
					$ls_fecfinact=$io_funcion->uf_convertirfecmostrar($data["fecfinact"][$li_z]);
				else
					$ls_fecfinact="";
				if($data["fecrecact"][$li_z]!="")
					$ls_fecrecact=$io_funcion->uf_convertirfecmostrar($data["fecrecact"][$li_z]);
				else
					$ls_fecrecact="";
				$ls_cedinsact=$data["cedinsact"][$li_z];
				$ls_cedresact=$data["cedresact"][$li_z];
				$ls_nominsact=$data["nominsact"][$li_z];
				$ls_civinsact=$data["civinsact"][$li_z];
				$ls_nomresact=$data["nomresact"][$li_z];
				$ls_civresact=$data["civresact"][$li_z];
				$ls_codpro=$data["cod_pro"][$li_z];
				$ls_codproins=$data["cod_pro_ins"][$li_z];
				$ls_obsact=$data["obsact"][$li_z];
				$li_tipact=$data["tipact"][$li_z];
				$ls_precon=$data["precon"][$li_z];
				$ls_motact=$data["motact"][$li_z];
				$ls_tipact=$io_funsob->uf_convertir_numerotipoacta ($data["tipact"][$li_z]);
				print "<td><a href=\"javascript: aceptar('$ls_codact','$ls_codcon','$ls_desobr','$ls_estact','$ls_fecact',
				'$ls_feciniact','$ls_fecfinact','$ls_cedinsact','$ls_cedresact','$ls_nominsact','$ls_civinsact','$ls_nomresact','$ls_civresact','$ls_codpro','$ls_codproins','$ls_obsact','$ls_fecrecact','$li_tipact','$ls_precon','$ls_motact');\">".$ls_tipact."</a></td>";
				print "<td>".$ls_precon.$ls_codcon."</td>";
				print "<td>".$ls_desobr."</td>";
				print "<td>".$ls_nominsact."</td>";				
				print "<td>".$ls_nomresact."</td>";	
				print "<td>".$ls_estact."</td>";												
				print "</tr>";			
			}//12
			print "</table>";
		}//3
		else
		  {
			$io_msg->message("No se han creado Actas de Inicio que cumplan con estos parámetros de búsqueda");
			print $io_funcion->uf_convertirmsg($io_sql->message);
		  }
		$io_sql->free_result($rs_data);
		
	}//2
}//1
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codact,ls_codcon,ls_desobr,ls_estact,ls_fecact,ls_feciniact,ls_fecfinact,ls_cedinsact,ls_cedresact,ls_nominsact,ls_civinsact,ls_nomresact,ls_civresact,ls_codpro,ls_codproins,ls_obsact,ls_fecrecact,li_tipact,ls_precon,ls_motact)  
  {
    //opener.form1.cmbtipoacta.value=li_tipact;
	//opener.form1.submit();
	opener.ue_cargaracta(ls_codact,ls_codcon,ls_desobr,ls_estact,ls_fecact,ls_feciniact,ls_fecfinact,ls_cedinsact,ls_cedresact,ls_nominsact,ls_civinsact,ls_nomresact,ls_civresact,ls_codpro,ls_codproins,ls_obsact,ls_fecrecact,li_tipact,ls_precon,ls_motact);
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_acta.php";
	  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
