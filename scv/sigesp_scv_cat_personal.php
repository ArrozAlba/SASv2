<?php
session_start();

	if (array_key_exists("totrow",$_GET))
	{
		$li_linea=$_GET["totrow"];
	}
	else
	{
		if(array_key_exists("hidlinea",$_POST))
		{
			$li_linea=$_POST["hidlinea"];
		}
		else
		{
			$li_linea="";
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
   	// Función que obtiene que tipo de operación se va a ejecutar
   	// NUEVO, GUARDAR, ó ELIMINAR
   	function uf_obteneroperacion()
   	{
		if(array_key_exists("operacion",$_POST))
		{
			$operacion=$_POST["operacion"];
		}
		else
		{
			$operacion="NUEVO";
		}
   		return $operacion; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	// Función que le da formato a los valore numéricos que vienen de la BD
	// parametro de entrada = Valor númerico que se desa formatear
	// parametro de retorno = valor numérico formateado
   	function uf_formatonumerico($as_valor)
   	{
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 0;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	// Función que obtiene e imprime los resultados de la busqueda
	function uf_imprimirresultados($as_codper, $as_cedper, $as_nomper, $as_apeper, $ai_linea)
   	{
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_sql.php");
   		require_once("../shared/class_folder/class_funciones.php");
		
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		$io_msg=new class_mensajes();
		$io_sql=new class_sql($con);
		$ds=new class_datastore();
		$fun=new class_funciones();				
       	$emp=$_SESSION["la_empresa"];
        $ls_codemp=$emp["codemp"];

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=100>Cédula</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "<td width=280>Nómina</td>";
		print "<td width=60>Clasif.</td>";
		print "</tr>";
		/*
		$ls_sql="SELECT MAX(sno_nomina.desnom) as desnom,MAX(sno_nomina.codnom) as codnom,(CASE sno_nomina.racnom WHEN 1 THEN sno_asignacioncargo.denasicar ELSE sno_cargo.descar END) AS cargo,".
				"       codclavia,sno_personalnomina.codper,".
				"(SELECT nomper FROM sno_personal".
				"   WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,".
				"(SELECT apeper FROM sno_personal".
				"   WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,".
				"(SELECT cedper FROM sno_personal".
				"   WHERE sno_personal.codper=sno_personalnomina.codper) as cedper".
				"  FROM sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo,sno_personal".
				" WHERE sno_personalnomina.codper LIKE '".$as_codper."'".
				"   AND sno_personal.cedper LIKE '".$as_cedper."'".
				"   AND sno_personal.nomper LIKE '".$as_nomper."'".
				"   AND sno_personal.apeper LIKE '".$as_apeper."'".
				"   AND sno_nomina.espnom=0".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom".
				"   AND sno_personalnomina.codper = sno_personal.codper".
				"   AND sno_personalnomina.codemp = sno_cargo.codemp".
				"   AND sno_personalnomina.codnom = sno_cargo.codnom".
				"   AND sno_personalnomina.codcar = sno_cargo.codcar".
				"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
				"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
				"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar".
				"   AND sno_personalnomina.staper ='1' ".
				"   AND sno_personal.cedper IN (SELECT ced_bene".
				"                                 FROM rpc_beneficiario".
				"                                WHERE rpc_beneficiario.codemp=sno_personal.codemp".
				"                                  AND rpc_beneficiario.ced_bene=sno_personal.cedper)".
				" GROUP BY sno_personalnomina.codper,sno_nomina.racnom,sno_asignacioncargo.denasicar,sno_cargo.descar,codclavia".
				" ORDER BY sno_personalnomina.codper,codclavia";
		*/
		$ls_sql="SELECT  * ".
				"  FROM sno_personal".
				" WHERE cedper like '".$as_cedper."' and nomper like '%".$as_nomper."%' and nomper like '%".$as_apeper."%' 	";

		$rs_per=$io_sql->select($ls_sql);
		if($row=$io_sql->fetch_row($rs_per))
		{
			$data=$io_sql->obtener_datos($rs_per);
			$ds->data=$data;
			$li_rows=$ds->getRowCount("codper");
			for($li_index=1;$li_index<=$li_rows;$li_index++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codper=$data["codper"][$li_index];
				$ls_cedper=$data["cedper"][$li_index];
				$ls_nomper=$data["nomper"][$li_index];
				$ls_apeper=$data["apeper"][$li_index];				
				$ls_cargo=$data["cargo"][$li_index];				
				$ls_codclavia=$data["codclavia"][$li_index];			
				$ls_codnom=$data["codnom"][$li_index];			
				$ls_desnom=$data["desnom"][$li_index];			
				
				print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper',".
					  "               '$ls_cargo','$ls_codclavia','$ls_codnom','$ai_linea');\">".$ls_codper."</a></td>";
				print "<td>".$ls_cedper."</td>";
				print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
				print "<td>".$ls_desnom."</td>";
				print "<td style='text-align:center' >".$ls_codclavia."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No hay nada que reportar");
		}

		print "</table>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Personal</title>
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
    <input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Personal </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">C&eacute;dula</div></td>
        <td height="22"><input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10"></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60"></td>
      </tr>
      <tr>
        <td><div align="right">Apellido</div></td>
        <td height="22"><div align="left">
          <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	$ls_operacion=uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_imprimirresultados($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $li_linea);
	}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_codper,ls_cedper,ls_nomper,ls_apeper,ls_cargo,ls_codclavia,ls_codnom,li_linea)
{
	opener.document.form1.txtcodben.value=ls_codper;
	opener.document.form1.txtnomben.value=ls_nomper+" "+ls_apeper;
	opener.document.form1.txtcedben.value=ls_cedper;
	opener.document.form1.txtcarper.value=ls_cargo;
	opener.document.form1.txtcodclavia.value=ls_codclavia;
	opener.document.form1.txtcodnom.value=ls_codnom;
	close();

}
function aceptar_II(ls_codper,ls_cedper,ls_nomper,ls_apeper,ls_cargo,ls_codclavia,li_linea)
{
		lb_valido=true;
		for(li_i=1; li_i<=li_linea;li_i++)
		{
			ls_codpergrid=eval("opener.document.form1.txtcodper"+li_i+".value");
			if(ls_codpergrid==ls_codper)
			{
				lb_valido=false;
				break;
			}
		}
		if(lb_valido)
		{
			opener.document.form1.operacion.value="AGREGARPERSONAL"
			obj=eval("opener.document.form1.txtcodper"+li_linea+"");
			obj.value=ls_codper;
			obj=eval("opener.document.form1.txtnomper"+li_linea+"");
			obj.value=ls_nomper+" "+ls_apeper;
			obj=eval("opener.document.form1.txtcedper"+li_linea+"");
			obj.value=ls_cedper;
			obj=eval("opener.document.form1.txtcodcar"+li_linea+"");
			obj.value=ls_cargo;
			obj=eval("opener.document.form1.txtcodclavia"+li_linea+"");
			obj.value=ls_codclavia;
			opener.document.form1.submit();
			close();
		}
		else
		{
			alert("La persona ya esta en el movimiento");
		}
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_scv_cat_personal.php";
  	f.submit();
}
</script>
</html>
