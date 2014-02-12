<?php
session_start();
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $ls_codpro    = $_POST["txtprov"];
   }
else
   {
     $ls_operacion = "";
 	 $ls_codpro    = $_GET["txtprov"];
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Calificación por Proveedor</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
<br>
  <table width="700" height="22" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="496" height="22" colspan="2" class="titulo-celda"><input name="txtprov" type="hidden" id="txtprov" value= "<?php echo $ls_codpro ?>">
      Cat&aacute;logo de Calificaci&oacute;n por Proveedor</td>
    </tr>
  </table>
<p align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();

$ls_sql=" SELECT rpc_clasifxprov.codclas,
                 rpc_clasificacion.denclas,
                 rpc_clasifxprov.status,
				 rpc_clasifxprov.nivstatus,
				 rpc_niveles.codniv,
				 rpc_niveles.desniv, 
		         rpc_niveles.monmincon,
				 rpc_niveles.monmaxcon,
				 rpc_clasifxprov.monfincon
            FROM rpc_clasificacion, rpc_clasifxprov, rpc_niveles
		   WHERE rpc_clasificacion.codemp='".$_SESSION["la_empresa"]["codemp"]."'
			 AND cod_pro= '".$ls_codpro."'
		     AND rpc_clasificacion.codemp=rpc_clasifxprov.codemp
		     AND rpc_clasificacion.codclas=rpc_clasifxprov.codclas
			 AND rpc_clasifxprov.codemp=rpc_niveles.codemp
			 AND rpc_clasifxprov.codniv=rpc_niveles.codniv";

  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	 }
  else
	 {
	   $li_numrows = $io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
		  {
		    echo "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			echo "<tr class=titulo-celda>";
			echo "<td width=20  style=text-align:center>C&oacute;digo</td>";
			echo "<td width=110 style=text-align:center>Denominaci&oacute;n</td>";
			echo "<td width=110 style=text-align:center>Estatus</td>"; 	 
			echo "<td width=110 style=text-align:center>Nivel del Estatus</td>";
			echo "<td width=20  style=text-align:center>Código Nivel de Clasificación</td>";
		    echo "<td width=110 style=text-align:center>Descripción del Nivel</td>";
			echo "<td width=110 style=text-align:center>Monto Mínimo de Contratación</td>";
			echo "<td width=110 style=text-align:center>Monto Máximo de Contratación</td>";
			echo "</tr>";  
			while($row=$io_sql->fetch_row($rs_data))
			     {
				   echo "<tr class=celdas-blancas>";
				   $ls_codcal = $row["codclas"];
				   $ls_dencal = $row["denclas"];
				   $li_estcal = $row["status"];
				   $li_nivcal = $row["nivstatus"];
				   if ($li_estcal==0)
				      {
					    $ls_estcal = "Activa";
				      }
				   elseif($li_estcal==1)  
				      {
					    $ls_estcal = "No Activa";
				      }
				   if ($li_nivcal==0)
				      {
					    $ls_nivcal="Ninguno";
				      }
				   elseif($li_nivcal==1)
				      {
					    $ls_nivcal="Bueno";
				      }
				   elseif($li_nivcal==2)
				      {
					    $ls_nivcal="Regular";
				      }
				   elseif($li_nivcal==3)
				      {
					    $ls_nivcal="Malo";
				      }  	        
				   $ls_codniv    = $row["codniv"];
				   $ls_desniv    = $row["desniv"];
				   $ld_monmincon = number_format($row["monmincon"],2,',','.');
				   $ld_monmaxcon = number_format($row["monmaxcon"],2,',','.');
				   $ld_monfincon = number_format($row["monfincon"],2,',','.');			
				   echo "<td width=20  style=text-align:center><a href=\"javascript: aceptar('$ls_codcal','$ls_dencal','$li_estcal','$li_nivcal','$ls_codniv','$ls_desniv','$ld_monmincon','$ld_monmaxcon','$ld_monfincon');\">".$ls_codcal."</a></td>";
				   echo "<td width=110 style=text-align:left>".$ls_dencal."</td>";
				   echo "<td width=110 style=text-align:left>".$ls_estcal."</td>";
				   echo "<td width=110 style=text-align:left>".$ls_nivcal."</td>";
				   echo "<td width=20  style=text-align:center>".$ls_codniv."</td>";
				   echo "<td width=110 style=text-align:lef>".$ls_desniv."</td>";
				   echo "<td width=110 style=text-align:right>".$ld_monmincon."</td>";
				   echo "<td width=110 style=text-align:right>".$ld_monmaxcon."</td>";
				   echo "</tr>";			 
				 }
		    echo "</table>";
			$io_sql->free_result($rs_data);
		  }
       else
	      {
		    $io_msg->message("No se han creado Calificaciones Por Proveedor !!!");
		  }
	 }
?>
</p>
</form>
<br>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,estatus,nivel,codigoniv,descripcion,montomin,montomax,ld_monfincon)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=denominacion;
	if (estatus==0)
	   {
	     opener.document.form1.cmbestatus[0].selected=true;
	   }
	else
	   {
	     opener.document.form1.cmbestatus[1].selected=true;	   
	   }
	if (nivel==0)
	   {
	     opener.document.form1.cmbnivestatus[0].selected=true;
	   }
	else
	if (nivel==1)
	   {
	     opener.document.form1.cmbnivestatus[1].selected=true;	   
	   }       
	if (nivel==2)
	   {
	     opener.document.form1.cmbnivestatus[2].selected=true;
	   }
	else
	if (nivel==3)
	   {
	     opener.document.form1.cmbnivestatus[3].selected=true;	   
	   }
	   
	  opener.document.form1.txtcodniv.value=codigoniv;
	  opener.document.form1.txtdesniv.value=descripcion; 
	  opener.document.form1.txtmontomin.value=montomin;
	  opener.document.form1.txtmontomax.value=montomax;
	  opener.document.form1.txtmonfincon.value=ld_monfincon	   
	close();
  }
</script>
</html>