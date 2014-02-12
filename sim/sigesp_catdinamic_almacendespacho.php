<?php
session_start();
  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=str_replace(".",",",$as_valor);
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$as_valor = substr($as_valor,0,$li_poscoma+3);
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Articulos Disponibles por Almac&eacute;n </title>
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
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

	if (array_key_exists("linea",$_GET))
	{
		$li_linea=  $_GET["linea"];
		$ls_codart= $_GET["codart"];
		$ls_sql="SELECT * FROM sim_articulo".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND codart = '".$ls_codart."'";
				
		$rs_cta=$io_sql->select($ls_sql);
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$ls_denart= $row["denart"];
		}
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

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=  $_POST["operacion"];
		$ls_codalm=		$_POST["hidalmacen"];
		$ls_codart=		$_POST["txtcodart"];
		$ls_denart=		$_POST["txtdenart"];
		$li_existencia=	$_POST["hidexistencia"];
	}
	else
	{
		$ls_operacion="";
	
	}

?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="422" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="420">          <input name="txtdenart" type="text" class="sin-borde2" id="txtdenart" value="<?php print $ls_denart ?>" size="40">
            <input name="txtcodart" type="hidden" id="txtcodart" value="<?php print $ls_codart ?>">
    </tr>
  </table>  
  <table width="422" height="21" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="414" colspan="2" class="titulo-celda">Productos Disponibles por Almac&eacute;n </td>
    </tr>
  </table>    
  <?php
	print "<table width=422 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Nombre Fiscal</td>";
	print "<td>Existencia Actual (Detal)</td>";
	print "</tr>";
	if($ls_operacion=="")
	{
		$ls_sql="SELECT sim_articuloalmacen.*,".
				"      (SELECT nomfisalm FROM sim_almacen".
				"        WHERE sim_articuloalmacen.codalm=sim_almacen.codalm) AS nomfisalm".
				"  FROM sim_articuloalmacen".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND codart = '".$ls_codart."'".
				" ORDER BY codalm";
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		$li_i=0;		
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("codalm");
			$li_existencia=$data["existencia"];
			for($z=1;$z<=$totrow;$z++)
			{
				//$li_existencia=0;
				$li_existenciaaux=$data["existencia"][$z];
				if($li_existenciaaux!=0)
				{
					$li_existencia=$li_existenciaaux;
					$li_existenciaaux=uf_formatonumerico($li_existenciaaux);
				}
				if ($li_existenciaaux!=0)
				{
					$li_i=$li_i +1;
					print "<tr class=celdas-blancas>";
					$ls_codalm=    $data["codalm"][$z];
					$ls_nomfisalm= $data["nomfisalm"][$z];
					print "<td><a href=\"javascript: aceptar('$ls_codalm','$li_existencia','$li_linea');\">".$ls_codalm."</a></td>";
					print "<td>".$data["nomfisalm"][$z]."</td>";
					print "<td>".$li_existenciaaux."</td>";
					print "</tr>";			
				}
			}
		}
		if($li_i==0)
		{
			$io_msg->message("No hay registros");
		}

		print "</table>";
	}
	if($ls_operacion=="BUSCAR")
	{
		if(array_key_exists("hidexistencia",$_POST))
		{
			$li_existencia= $_POST["hidexistencia"];
		}
			$ls_sql="SELECT * FROM sim_config";
			$li_exec=$io_sql->select($ls_sql);
			if($row=$io_sql->fetch_row($li_exec))
			{
				$ls_metodo=$row["metodo"];
			}
			$ls_metodo=trim($ls_metodo);
			if($ls_metodo=="FIFO")
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE  codart='". $ls_codart ."'".
						" AND codalm='". $ls_codalm ."'".
						" AND opeinv='ENT' AND numdocori NOT IN".
						" (SELECT numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov";
				$rs_data=$io_sql->select($ls_sql);
			}
	
			if($ls_metodo=="LIFO")
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE  codart='". $ls_codart ."'".
						" AND codalm='". $ls_codalm ."'".
						" AND opeinv='ENT' AND numdocori NOT IN".
						" (SELECT numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
				$rs_data=$io_sql->select($ls_sql);
			}	
			if($ls_metodo=="CPP")
			{
				$ls_sql="SELECT Avg(cosart) as cosart".
						" FROM sim_dt_movimiento".
						" WHERE  codart='". $ls_codart ."'".
						" AND codalm='". $ls_codalm ."'".
						" AND opeinv='ENT' AND codprodoc<>'REV' AND numdocori NOT IN".
						" (SELECT numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" GROUP BY nummov".
                        " ORDER BY nummov DESC";
				$rs_data=$io_sql->select($ls_sql);
			}	
			
			if($ls_metodo!="CPP")
			{
				$lb_break=false;
				while(($row=$io_sql->fetch_row($rs_data))&&(!$lb_break))
				{
					$li_preuniart=$row["cosart"];
					$ls_numdocori=$row["numdocori"];
					$li_preuniart=uf_formatonumerico($li_preuniart);
/*					$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN canart ELSE -canart END) total FROM sim_dt_movimiento".
							" WHERE codart='". $ls_codart ."'".
							" AND codalm='". $ls_codalm ."'".
							" AND numdocori='". $ls_numdocori ."'".
							" AND numdocori NOT IN".
							"  (SELECT numdocori FROM sim_dt_movimiento".
							"    WHERE opeinv ='REV')".
							" ORDER BY nummov";
//					print $ls_sql;		
					$li_exec1=$io_sql->select($ls_sql);
					if($row1=$io_sql->fetch_row($li_exec1))
					{
						$li_existencia=$row1["total"];
/*						if ($li_existencia > 0)
						{
							$lb_break=true;
							$io_sql->free_result($li_exec1);
						}
						
					}  //fin  if($row=$io_sql->fetch_row($li_exec))
	
*/				}
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
					$ls_numdocori="";
					$li_preuniart=$row["cosart"];
					$li_preuniart=uf_formatonumerico($li_preuniart);
				}
			}
			print "<script language= javascript>";
			print "obj=eval(opener.document.form1.txtcodalm". $li_linea .");";
			print "obj.value='". $ls_codalm ."';";
			print "obj1=eval(opener.document.form1.hidexistencia". $li_linea .");";
			print "obj1.value=". $li_existencia .";";
			print "obj2=eval(opener.document.form1.txtpreuniart". $li_linea .");";
			print "obj2.value='". $li_preuniart ."';";
			print "obj3=eval(opener.document.form1.hidnumdocori". $li_linea .");";
			print "obj3.value='". $ls_numdocori ."';";
			print "close();";
			print "</script>";
			
	} // fin if($ls_operacion=="buscar")
?>
      <input name="hidalmacen" type="hidden" id="hidalmacen" value="<?php print $ls_codalm ?>">
      <input name="hidexistencia" type="hidden" id="hidexistencia" value="<?php print $li_existencia ?>"></td>

</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codalm,li_existencia,li_linea)
	{
		f= document.form1;
		f.hidalmacen.value= ls_codalm;
		f.hidexistencia.value= li_existencia;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_almacendespacho.php";
		f.submit();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_almacen.php";
		f.submit();
	}
</script>
</html>
