<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 echo "<script language=JavaScript>";
	 echo "close();";
	 echo "opener.document.form1.submit();";
	 echo "</script>";		
   }
$la_empresa		  = $_SESSION["la_empresa"];
$li_estmodest     = $la_empresa["estmodest"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];
$li_size1         = $li_loncodestpro1+10;

      
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");

$la_empresa = $_SESSION["la_empresa"];
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codunieje  = $_POST["hidcodunieje"];
	 $ls_codestpro1 = $_POST["codestpro1"];
     $ls_orden      = $_POST["orden"];
	 $ls_campoorden = $_POST["campoorden"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codunieje  = $_GET["hidcodunieje"];	
	 $ls_codestpro1 = "";
     $ls_orden      = "ASC";
	 $ls_campoorden = "spg_dt_unidadadministrativa.codestpro1";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Estructuras Presupuestarias</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="formulario" id="formulario" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="562" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="21" colspan="2"><input name="orden" type="hidden" id="orden" value="<?php echo $ls_orden; ?>">
          <input name="campoorden" type="hidden" id="campoorden" value="<?php echo $ls_campoorden; ?>">
          <input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo Estructura Presupuestaria
        <input name="hidcodunieje" type="hidden" id="hidcodunieje" value="<?php echo $ls_codunieje ?>"></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="129" height="22" style="text-align:right"><?php echo $la_empresa["nomestpro1"]?></td>
        <td width="431" height="22" style="text-align:left"><input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php echo $ls_codestpro1 ?>" size="<?php echo $li_size1 ?>" maxlength="<?php echo $li_loncodestpro1 ?>"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></td>
      </tr>
  </table>
	 <div align="center"><br>
<?php
echo "<table width=720 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro1')>".$la_empresa["nomestpro1"]."</td>";
echo "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro2')>".$la_empresa["nomestpro2"]."</td>";
echo "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro3')>".$la_empresa["nomestpro3"]."</td>";
if ($li_estmodest==2)
   {
     echo "<td width=160 height=21 style=text-align:center onClick=ue_orden('spg_dt_unidadadministrativa.codestpro4')>".$la_empresa["nomestpro4"]."</td>"; 
     echo "<td width=160 height=21 style=text-align:center onClick=ue_orden('spg_dt_unidadadministrativa.codestpro5')>".$la_empresa["nomestpro5"]."</td>";
   }
echo "<td width=40 height=21 style=text-align:center>Tipo</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codunieje))
	    {
		  $ls_codunieje = str_pad($ls_codunieje,10,0,0);
		}
	 
	 $ls_sql="SELECT spg_unidadadministrativa.coduniadm,
	                 spg_unidadadministrativa.denuniadm,
	                 spg_dt_unidadadministrativa.codestpro1, 
	                 spg_dt_unidadadministrativa.codestpro2, 
					 spg_dt_unidadadministrativa.codestpro3,
					 spg_ep1.denestpro1,spg_ep1.estint,spg_ep1.sc_cuenta,
					 spg_ep2.denestpro2,
					 spg_ep3.denestpro3,
					 spg_ep4.denestpro4,
					 spg_ep5.denestpro5,
					 spg_dt_unidadadministrativa.codestpro4, 
					 spg_dt_unidadadministrativa.codestpro5, 
					 spg_dt_unidadadministrativa.estcla 
	            FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 
			   WHERE spg_unidadadministrativa.codemp='".$la_empresa["codemp"]."'
                 AND spg_unidadadministrativa.coduniadm='".$ls_codunieje."'
				 AND spg_dt_unidadadministrativa.codestpro1 like '%".$ls_codestpro1."%'
				 AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp
				 AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm
				 AND spg_dt_unidadadministrativa.codemp=spg_ep1.codemp
				 AND spg_dt_unidadadministrativa.estcla=spg_ep1.estcla
				 AND spg_dt_unidadadministrativa.codestpro1=spg_ep1.codestpro1
				 AND spg_dt_unidadadministrativa.codemp=spg_ep2.codemp
				 AND spg_dt_unidadadministrativa.estcla=spg_ep2.estcla
				 AND spg_dt_unidadadministrativa.codestpro1=spg_ep2.codestpro1
				 AND spg_dt_unidadadministrativa.codestpro2=spg_ep2.codestpro2
				 AND spg_dt_unidadadministrativa.codemp=spg_ep3.codemp
				 AND spg_dt_unidadadministrativa.estcla=spg_ep3.estcla
				 AND spg_dt_unidadadministrativa.codestpro1=spg_ep3.codestpro1
				 AND spg_dt_unidadadministrativa.codestpro2=spg_ep3.codestpro2
				 AND spg_dt_unidadadministrativa.codestpro3=spg_ep3.codestpro3
				 AND spg_dt_unidadadministrativa.codemp=spg_ep4.codemp
				 AND spg_dt_unidadadministrativa.estcla=spg_ep4.estcla
				 AND spg_dt_unidadadministrativa.codestpro1=spg_ep4.codestpro1
				 AND spg_dt_unidadadministrativa.codestpro2=spg_ep4.codestpro2
				 AND spg_dt_unidadadministrativa.codestpro3=spg_ep4.codestpro3
				 AND spg_dt_unidadadministrativa.codestpro4=spg_ep4.codestpro4
				 AND spg_dt_unidadadministrativa.codemp=spg_ep5.codemp
				 AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla
				 AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1
				 AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2
				 AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3
				 AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4
				 AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5 
		 	   ORDER BY $ls_campoorden $ls_orden";
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
			   while ($row=$io_sql->fetch_row($rs_data))
			         {
					   echo "<tr class=celdas-blancas>";
					   $ls_codunieje  = $row["coduniadm"];
					   $ls_denunieje  = $row["denuniadm"];
					   $ls_codestpro1 = $row["codestpro1"];
					   $ls_codestpro2 = $row["codestpro2"];
					   $ls_codestpro3 = $row["codestpro3"];
					   $ls_codestpro4 = $row["codestpro4"];
					   $ls_codestpro5 = $row["codestpro5"];
					   $ls_denestpro1 = $row["denestpro1"];
					   $ls_denestpro2 = $row["denestpro2"];
					   $ls_denestpro3 = $row["denestpro3"];
					   $ls_denestpro4 = $row["denestpro4"];
					   $ls_denestpro5 = $row["denestpro5"];
					   $ls_estcla     = $row["estcla"];
					   $ls_estint     = $row["estint"];
					   $ls_cuentaint  = $row["sc_cuenta"];
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Proyecto';
						  }
					   elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
					   if ($li_estmodest==2)
					      {
					        $ls_denestcla = $_SESSION["la_empresa"]["nomestpro1"];
						  }
					   echo "<td width=160 style=text-align:center title='".$ls_denestpro1."'><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estint','$ls_cuentaint');\">".trim(substr($ls_codestpro1,-$li_loncodestpro1))."</a></td>";
					   echo "<td width=160 style=text-align:center title='".$ls_denestpro2."'><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estint','$ls_cuentaint');\">".trim(substr($ls_codestpro2,-$li_loncodestpro2))."</a></td>";
					   echo "<td width=160 style=text-align:center title='".$ls_denestpro3."'><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estint','$ls_cuentaint');\">".trim(substr($ls_codestpro3,-$li_loncodestpro3))."</a></td>";
					   if ($li_estmodest==2)
					      {
					        echo "<td width=160 style=text-align:center title='".$ls_denestpro4."'><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estint','$ls_cuentaint');\">".trim(substr($ls_codestpro4,-$li_loncodestpro4))."</a></td>";
					        echo "<td width=160 style=text-align:center title='".$ls_denestpro5."'><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estint','$ls_cuentaint');\">".trim(substr($ls_codestpro5,-$li_loncodestpro5))."</a></td>";
						  }
					   echo "<td width=40 style=text-align:center>".$ls_denestcla."</td>";
			           echo "</tr>";
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se han definido registros !!!");
			 }
	    }
}
echo "</table>";
?>
</div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codunieje,ls_denunieje,ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_estcla,ls_estint,ls_cuentaint)
  {
	close();
    opener.aceptar(ls_codunieje,ls_denunieje,ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_estcla,ls_estint,ls_cuentaint);
  }
  
  function ue_search()
  {
  f=document.formulario;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cxp_cat_estructuras.php";
  f.submit();
  }
</script>
</html>