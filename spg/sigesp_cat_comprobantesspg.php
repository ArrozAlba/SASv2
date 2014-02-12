<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$fun=new class_funciones();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
$SQL=new class_sql($con);
$SQL_cmp=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante=$_POST["txtdocumento"];
	$ls_fecdesde=$_POST["txtfechadesde"];
	$ls_fechasta=$_POST["txtfechahasta"];	
	$ls_procedencia=$_POST["procede"];
	$ls_provben	= $_POST["txtprovbene"];
	$ls_tipo=$_POST["tipo"];
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
}
else
{
	$ls_operacion="";
	if(array_key_exists("tipocat",$_GET))
	{
		$ls_tipocat=$_GET["tipocat"];
	}
	else
	{
		$ls_tipocat="";
	}
	$ls_procedencia='N';
	$ls_fecdesde='01/'.date("m/Y");
	$ls_fechasta=date("d/m/Y");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones_gasto.js"></script>
<title>Catalogo de Comprobantes</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
</p>
  <table width="565" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Comprobantes</td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="94" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Comprobante</td>
        <td colspan="2"><div align="left">
          <!-- <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        -->
		  <input name="txtdocumento" type="text" id="txtdocumento">
        </div></td>
			<?php
			if(array_key_exists("procede",$_POST))
			{
				$ls_procede_ant=$_POST["procede"];
				$sel_N="";
			}
			else
			{
				$ls_procede_ant="N";
				$sel_N="selected";
			}
			uf_cargar_procedencias($SQL);
			$li_rowcount=$ds_procedencias->getRowCount("procede");
			
			?>
			<td width="95" align="right">Fecha </td>
            <td align="left"><div align="right">Desde
        </div></td>
            <td align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center" onBlur="valFecha(document.form1.txtfechadesde)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fecdesde;?>" size="18" maxlength="10" datepicker="true" ></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td width="113" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>		</td>
        <td width="85" align="left"><input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="" size="14" maxlength="10"></td>
        <td><div align="right"></div></td>
        <td width="40"><div align="right">Hasta </div></td>
        <td width="136" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta" style="text-align:center" onBlur="valFecha(document.form1.txtfechahasta)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fechasta;?>" size="18" maxlength="10" datepicker="true" > </td>
      </tr>
      <tr>
        <td height="10"><div align="right">Procedencia</div></td>
        <td colspan="3" align="left" ><select name="procede" id="select">
          <option value="N" "<?php print $sel_N?>" >Ninguno</option>
          <?php
		  	for($li_i=1;$li_i<=$li_rowcount;$li_i++)
			{
				$ls_procede=$ds_procedencias->getValue("procede",$li_i);
				if($ls_procede_ant==$ls_procede)
				{
				?>
          <option value="<?php print $ls_procede;?>" selected><?php print $ls_procede;?></option>
          <?php
				}
				else
				{
				?>
          <option value="<?php print $ls_procede;?>"><?php print $ls_procede;?></option>
          <?php
				}
			}
			?>
        </select></td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="15"><div align="left"></div></td>
        <td colspan="5"><div align="left">
          <table width="72" border="0" align="right" cellpadding="0" cellspacing="0" class="letras-peque&ntilde;as">
            <tr>
              <td width="28"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></td>
              <td width="44"><a href="javascript: ue_search();">Buscar</a></td>
              </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <?php

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT * FROM sigesp_procedencias";
	$data=$sql->select($ls_sql);
	if($row=$sql->fetch_row($data))
	{
		$data=$sql->obtener_datos($data);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds_procedencias->data=$data;
		
	}	
}

function uf_select_provbene($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);

	if($row=$sql->fetch_row($data))
	{
		$ls_provbene=$row[$ls_campo];
		
	}	
	else
	{
		$ls_provbene="";
	}
	$sql->free_result($data);
	return $ls_provbene;
}

if($ls_procedencia=='N')
{
	$ls_procedencia="";
}
if(($ls_tipocat=="")||($ls_tipocat=="repcompdes")||($ls_tipocat=="repcomphas")||($ls_tipocat=="rep_ejecucion"))
{
	print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
		print "<td>Comprobante</td>";
		print "<td>Descripcion Comprobante</td>";
		print "<td>Procede</td>";
		print "<td>Fecha</td>";
		print "<td>Proveedor</td>";
		print "<td>Beneficiario</td>";
		print "<td>Nombre Prov./Benef.</td>";
		print "<td>Monto</td>";
	print "</tr>";
}
elseif(($ls_tipocat=="rep_proc_des")||($ls_tipocat=="rep_proc_has"))
{
	print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
		print "<td>Procede</td>";
		print "<td>Comprobante</td>";
		print "<td>Descripcion Comprobante</td>";
		print "<td>Fecha</td>";
		print "<td>Proveedor</td>";
		print "<td>Beneficiario</td>";
		print "<td>Nombre Prov./Benef.</td>";
		print "<td>Monto</td>";
	print "</tr>";
}
if($ls_operacion=="BUSCAR")
{
		/*$ls_order=" ORDER BY fecha ASC ,comprobante ASC ,procede ASC ";
		$ls_sql="SELECT distinct a.procede,a.codemp,a.comprobante,a.fecha,a.ced_bene,a.cod_pro,a.descripcion,a.tipo_destino,a.total
		 		 FROM sigesp_cmp a
		  		 WHERE a.codemp='".$as_codemp."' AND a.comprobante like '%".$ls_comprobante."%' AND
					   a.procede like '%".$ls_procedencia."%'" ;		
		if((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
		{
			$ls_fecdesde=substr($ls_fecdesde,6,4)."-".substr($ls_fecdesde,3,2)."-".substr($ls_fecdesde,0,2);
			$ls_fechasta=substr($ls_fechasta,6,4)."-".substr($ls_fechasta,3,2)."-".substr($ls_fechasta,0,2);
			$ls_sql=$ls_sql." AND a.fecha>='".$ls_fecdesde."' AND a.fecha<='".$ls_fechasta."'";
		}
		if(($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND cod_pro like '".$ls_provben."'";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND ced_bene like'".$ls_provben."'";
		}*/
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',spg_dt_cmp.codestpro1,spg_dt_cmp.codestpro2,spg_dt_cmp.codestpro3,spg_dt_cmp.codestpro4,spg_dt_cmp.codestpro5,spg_dt_cmp.estcla) IN (SELECT CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||spg_dt_cmp.codestpro1||spg_dt_cmp.codestpro2||spg_dt_cmp.codestpro3||spg_dt_cmp.codestpro4||spg_dt_cmp.codestpro5||spg_dt_cmp.estcla IN (SELECT codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
        $ls_cadena="";
		$ls_fecdesde=substr($ls_fecdesde,6,4)."-".substr($ls_fecdesde,3,2)."-".substr($ls_fecdesde,0,2);
		$ls_fechasta=substr($ls_fechasta,6,4)."-".substr($ls_fechasta,3,2)."-".substr($ls_fechasta,0,2);
	    $ls_fecdesde=$fun->uf_convertirdatetobd($ls_fecdesde); 
		$ls_fechasta=$fun->uf_convertirdatetobd($ls_fechasta); 
		if(($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_cadena=" AND cod_pro like '".$ls_provben."' ";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_cadena=" AND ced_bene like'".$ls_provben."' ";
		}
		$ls_order=" ORDER BY fecha ASC ,comprobante ASC ,procede ASC ";
		/*$ls_sql=" SELECT distinct MAX(sigesp_cmp.procede) AS procede, MAX(sigesp_cmp.codemp) AS codemp, ".
		        "                 MAX(sigesp_cmp.comprobante) AS comprobante, ".
                "                 MAX(sigesp_cmp.fecha) AS fecha, MAX(sigesp_cmp.ced_bene) AS ced_bene, ".
                "                 MAX(sigesp_cmp.descripcion) AS descripcion, sigesp_cmp.tipo_destino, ".
				"                 SUM(spg_dt_cmp.monto) as total, sigesp_cmp.codban, sigesp_cmp.ctaban ".
				" FROM  sigesp_cmp, spg_dt_cmp   ".
				" WHERE sigesp_cmp.codemp='".$as_codemp."' AND sigesp_cmp.comprobante like '%".$ls_comprobante."%' AND ".
				"       sigesp_cmp.procede like '%".$ls_procedencia."%' AND sigesp_cmp.fecha>='".$ls_fecdesde."' AND ".
				"	    sigesp_cmp.fecha<='".$ls_fechasta."'  ".$ls_cadena."  AND sigesp_cmp.codemp=spg_dt_cmp.codemp AND ".
				" 	    sigesp_cmp.comprobante=spg_dt_cmp.comprobante AND ".
				"       sigesp_cmp.procede=spg_dt_cmp.procede AND     ".
				" 	    sigesp_cmp.fecha=spg_dt_cmp.fecha            ";*/
		$ls_sql=" SELECT distinct sigesp_cmp.procede AS procede, ".
                "                  sigesp_cmp.codemp AS codemp, ".
                "                  sigesp_cmp.comprobante AS comprobante,". 
                "                  sigesp_cmp.fecha AS fecha, ".
                "                  sigesp_cmp.ced_bene AS ced_bene,".
                "                  sigesp_cmp.cod_pro AS cod_pro,".
                "                  sigesp_cmp.descripcion AS descripcion, ".
                "                  sigesp_cmp.tipo_destino AS tipo_destino,".
				"                  sigesp_cmp.codban AS codban, ". 
                "                  sigesp_cmp.ctaban AS ctaban, ".
                "                  SUM(spg_dt_cmp.monto) as total,".
				"                  sigesp_cmp.estrenfon, sigesp_cmp.codfuefin ".
				" FROM  sigesp_cmp, spg_dt_cmp   ".
				" WHERE sigesp_cmp.codemp='".$as_codemp."' AND sigesp_cmp.comprobante like '%".$ls_comprobante."' AND ".
				"       sigesp_cmp.procede like '%".$ls_procedencia."%' AND sigesp_cmp.fecha>='".$ls_fecdesde."' AND ".
				"	    sigesp_cmp.fecha<='".$ls_fechasta."'  ".$ls_cadena."  AND sigesp_cmp.codemp=spg_dt_cmp.codemp AND ".
				" 	    sigesp_cmp.comprobante=spg_dt_cmp.comprobante AND ".
				"       sigesp_cmp.procede=spg_dt_cmp.procede AND     ".
				" 	    sigesp_cmp.fecha=spg_dt_cmp.fecha            ".$ls_sql_seguridad." ".
				" GROUP BY sigesp_cmp.procede,".
                " sigesp_cmp.codemp, ".
                " sigesp_cmp.comprobante, ".
                " sigesp_cmp.fecha, ".
                " sigesp_cmp.ced_bene,".
                " sigesp_cmp.cod_pro,".
                " sigesp_cmp.descripcion,".
                " sigesp_cmp.tipo_destino,".
				" sigesp_cmp.codban,".
                " sigesp_cmp.ctaban,".
				" sigesp_cmp.estrenfon, ".
				" sigesp_cmp.codfuefin";						
		$ls_sql=$ls_sql.$ls_order;
		$rs_cmp=$SQL_cmp->select($ls_sql);
		$data=$rs_cmp;
		if (!empty($data))
		{
			if($row=$SQL_cmp->fetch_row($rs_cmp))
			{
				$data=$SQL_cmp->obtener_datos($rs_cmp);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$ds->data=$data;
				$totrow=$ds->getRowCount("comprobante");
				for($z=1;$z<=$totrow;$z++)
				{
					$ls_comprobante=$data["comprobante"][$z];
					$ls_descripcion=$data["descripcion"][$z];
					$ls_procedencia=$data["procede"][$z];
					$ls_fecha=$data["fecha"][$z];
					$ls_fecha=substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);
					$ls_prov=$data["cod_pro"][$z];
					$ls_bene=$data["ced_bene"][$z];
					$ls_codban=$data["codban"][$z];
					$ls_ctaban=$data["ctaban"][$z];
					//$ls_provbene=$data["nombre"][$z];
					$ls_tip=$data["tipo_destino"][$z];
					$ls_estrenfon=$data["estrenfon"][$z];// estatus para las redencion de fondos
					$ls_codfuefin=$data["codfuefin"][$z];
					$ldec_monto=number_format($data["total"][$z],2,",",".");
					if($ls_tip=="-")
					{
						$ls_tip="-";
						$ls_prov="----------";
						$ls_bene="----------";
						$ls_provbene="Ninguno";
					}
					elseif($ls_tip=="B")
					{
						$ls_tip="B";
						$ls_provbene= uf_select_provbene($SQL,"SELECT nombene FROM rpc_beneficiario WHERE ced_bene='".$ls_bene."'","nombene");
						$ls_provbene= $ls_provbene.uf_select_provbene($SQL,"SELECT apebene FROM rpc_beneficiario WHERE ced_bene='".$ls_bene."'","apebene");
					}
					elseif($ls_tip="P")
					{
						$ls_tip="P";
						$ls_provbene= uf_select_provbene($SQL,"SELECT nompro FROM rpc_proveedor WHERE cod_pro='".$ls_prov."'","nompro");
					}
					else
					{
						$ls_tip="-";
						$ls_prov="----------";
						$ls_bene="----------";
						$ls_provbene="Ninguno";
					}
						if($ls_tipocat=="")
						{
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript: uf_aceptar('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene','$ls_codban','$ls_ctaban','$ls_estrenfon','$ls_codfuefin');\">".$ls_comprobante."</a></td>";
							print "<td>".$ls_descripcion."</td>";
							print "<td>".$ls_procedencia."</td>";				
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_bene."</td>";
							print "<td>".$ls_provbene."</td>";
							print "<td>".$ldec_monto."</td>";					
							print "</tr>";		
					  }		
					  if($ls_tipocat=="repcompdes")
					  {
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript: uf_aceptar_repcompdes('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene');\">".$ls_comprobante."</a></td>";
							print "<td>".$ls_descripcion."</td>";
							print "<td>".$ls_procedencia."</td>";				
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_bene."</td>";	
							print "<td>".$ls_provbene."</td>";					
							print "<td>".$ldec_monto."</td>";													
							print "</tr>";		
					  }
					  if($ls_tipocat=="repcomphas")
					  {
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript: uf_aceptar_repcomphas('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene');\">".$ls_comprobante."</a></td>";
							print "<td>".$ls_descripcion."</td>";
							print "<td>".$ls_procedencia."</td>";				
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_bene."</td>";				
							print "<td>".$ls_provbene."</td>";
							print "<td>".$ldec_monto."</td>";										
							print "</tr>";		
					  }
					  if($ls_tipocat=="rep_proc_des")
					  {
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript: uf_aceptar_rep_proc_des('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene');\">".$ls_procedencia."</a></td>";
							print "<td>".$ls_comprobante."</td>";				
							print "<td>".$ls_descripcion."</td>";
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_bene."</td>";				
							print "<td>".$ls_provbene."</td>";					
							print "<td>".$ldec_monto."</td>";										
							print "</tr>";		
					  }
					  if($ls_tipocat=="rep_proc_has")
					  {
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript: uf_aceptar_rep_proc_has('$ls_comprobante','$ls_descripcion','$ls_procedencia','$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene');\">".$ls_procedencia."</a></td>";
							print "<td>".$ls_comprobante."</td>";				
							print "<td>".$ls_descripcion."</td>";
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_bene."</td>";				
							print "<td>".$ls_provbene."</td>";
							print "<td>".$ldec_monto."</td>";										
							print "</tr>";		
					  }
					  if($ls_tipocat=="rep_ejecucion")
					  {
							print "<tr class=celdas-blancas>";
							print "<td><a href=\"javascript:uf_aceptar_rep_ejecucion('$ls_comprobante','$ls_descripcion','$ls_procedencia',
											   '$ls_fecha','$ls_tip','$ls_prov','$ls_bene','$ls_provbene');\">".$ls_comprobante."</a></td>";
							print "<td>".$ls_descripcion."</td>";				
							print "<td>".$ls_procedencia."</td>";
							print "<td>".$ls_prov."</td>";
							print "<td>".$ls_fecha."</td>";
							print "<td>".$ls_bene."</td>";				
							print "<td>".$ls_provbene."</td>";
							print "<td>".$ldec_monto."</td>";										
							print "</tr>";		
					  }
				}
			$SQL_cmp->free_result($rs_cmp);	
			}
			else
			{
				if(!empty($ls_procedencia))
				{
					?>
					<script language="JavaScript">
					alert("No se han creado Comprobantes asociados a la procedencia seleccionada \n ó no poseen detalles presupuestarios                   relacionados.....");
					//close();
					</script>
					<?php
				}
				else
				{
				?>
				<script language="JavaScript">
				alert("No se han creado Comprobantes Presupuestarios.....");
				//close();
				</script>
				<?php
				}
			}
		}
		else
		{
				?>
				<script language="JavaScript">
				alert("No se han creado Comprobantes Presupuestarios.....");
				//close();
				</script>
				<?php
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

  function uf_aceptar(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene,ls_codban,ls_ctaban,estrenfon,codfuefin)
  {
   	f=opener.document.form1;
	f.txtcomprobante.value=comprobante;
	f.txtdesccomp.value=descripcion;
	f.txtproccomp.value=procede;
	f.txtfecha.value=fecha;
	f.txtdesproben.value=ls_provbene;
	f.txtctaban.value=ls_ctaban;
	f.txtcodban.value=ls_codban;
	f.txtfuente.value=codfuefin;
	f.existe.value="C";
	f.chrenfon.disabled=""; 
	if (estrenfon==1)
	{
		f.chrenfon.checked=true;
	}
	else
	{
		f.chrenfon.checked=false;
	}	
	if(tipo=="P")
	{
		f.tipo[0].checked=true;
		f.txtprovbene.value=prov;
	}
	else if(tipo=="B")
	{
		f.tipo[1].checked=true;
		f.txtprovbene.value=bene;
	}
	else
	{
		f.tipo[2].checked=true;
		f.txtprovbene.value=prov;
	}
	parametros="";
	estmod=<? print $_SESSION["la_empresa"]["estmodest"]?>;
	parametros=parametros+"&comprobante="+comprobante+"&fecha="+fecha+"&procede="+procede+"&estmod="+estmod+"&codban="+ls_codban+"&ctaban="+ls_ctaban;
	if (parametros!="")
	{
		divgrid = opener.document.getElementById("detallespres");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
		ajax.onreadystatechange=function()
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}					
			}// fin del if
		}// fin del la funcion ajax
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=LOADPRESUPUESTO"+parametros);
	}// fin de parametros
	
	setTimeout(close,500);	
		/*f.operacion.value="CARGAR_DT";
		f.action="sigesp_spg_p_comprobante.php";
		f.submit();
		close();*/
  }

  function uf_aceptar_repcompdes(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
  {
		f=opener.document.form1;
		f.txtcompdes.value=comprobante;
		f.txtcompdes.readOnly=true;
		close();
  }
  
   function uf_aceptar_repcomphas(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtcomphas.value=comprobante;
		f.txtcomphas.readOnly=true;
		close(); 
   }
   
   function uf_aceptar_rep_proc_des(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtprocdes.value=procede;
		f.txtprocdes.readOnly=true;
		close(); 
   }
   
   function uf_aceptar_rep_proc_has(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtprochas.value=procede;
		f.txtprochas.readOnly=true;
		close(); 
   }
   function uf_aceptar_rep_ejecucion(comprobante,descripcion,procede,fecha,tipo,prov,bene,ls_provbene)
   {
		f=opener.document.form1;
		f.txtcomprobante.value=comprobante;
		f.txtcomprobante.readOnly=true;
		f.txtprocede.value=procede;
		f.txtprocede.readOnly=true;
		f.txtfecha.value=fecha;
		f.txtfecha.readOnly=true;
		close(); 
   }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_comprobantesspg.php?tipocat=<?php print $ls_tipocat; ?>";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_prov.php","_blank","width=502,height=350");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_bene.php","_blank","width=502,height=350");
		}
	}
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
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
	

	  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
