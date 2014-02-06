<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);

$la_empresa       = $_SESSION["la_empresa"];
$ls_codemp        = $la_empresa["codemp"];
$li_estmodest     = $la_empresa["estmodest"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;
$li_size4 = $li_loncodestpro4+10;
$li_size5 = $li_loncodestpro5+10;

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_estcla     = $_POST["hidtipestpro"];
	 $ls_codunieje  = $_POST["codigo"];
	 $ls_denunieje  = $_POST["denominacion"];
	 $ls_codestpro1 = $_POST["codestpro1"];
	 $ls_codestpro2 = $_POST["codestpro2"];
	 $ls_codestpro3 = $_POST["codestpro3"];
	 if ($li_estmodest==2)
	    {
		 $ls_codestpro4 = $_POST["codestpro4"];
		 $ls_codestpro5 = $_POST["codestpro5"];
	    }
	$ls_coduniadm  = $_POST["coduniadm"];
	$ls_estuac     = $_POST["estuac"];
	$ls_denestpro1 = $_POST["denestpro1"];
}
else
{
	$ls_operacion  = "";
	$ls_codunieje  = "";
	$ls_denunieje  = "";
	$ls_codestpro1 = $_GET["codestpro1"];
	$ls_codestpro2 = "";
	$ls_codestpro3 = "";
	$ls_coduniadm  = $_GET["coduniadm"];
	$ls_estuac     = $_GET["estuac"];
	$ls_denestpro1 = "";
	$ls_denestpro1 = $_GET["denestpro1"];
	$ls_estcla     = $_GET["hidestcla"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Ejecutoras</title>
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
  <p align="center">&nbsp;</p>
  	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Unidades Ejecutoras
        <input name="hidtipestpro" type="hidden" id="hidtipestpro" value="<?php echo $ls_estcla ?>"></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="111" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="451" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominacion</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="75" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro1"];?></div></td>
        <td height="22"><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php echo $ls_codestpro1?>" size="<?php echo $li_size1 ?>" maxlength="<?php echo $li_loncodestpro1 ?>" readonly>
          <a href="javascript:catalogo_estpro1();"></a>
          <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php echo $ls_denestpro1; ?>" size="53" readonly>          
        </div>       </td>
      </tr>
      <tr>
        <td height="22"><div align="right"> <?php print $_SESSION["la_empresa"]["nomestpro2"];?></div>         </td>
        <td height="22"><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" size="<?php echo $li_size2 ?>" maxlength="<?php echo $li_loncodestpro2 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
          <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">    <?php print $_SESSION["la_empresa"]["nomestpro3"];?></div>      </td>
        <td height="22"><div align="left">
          <input name="codestpro3" type="text" id="codestpro3" size="<?php echo $li_size3 ?>" maxlength="<?php echo $li_loncodestpro3 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
          <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
        </div></td>
      </tr>
	  <?php
	  if($li_estmodest==2)
	  {
	  ?>
	  <tr>
        <td height="22"><div align="right"> <?php print $_SESSION["la_empresa"]["nomestpro4"];?></div>         </td>
        <td height="22"><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" size="<?php echo $li_size4 ?>" maxlength="<?php echo $li_loncodestpro4 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a>
          <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right"> <?php print $_SESSION["la_empresa"]["nomestpro5"];?></div>         </td>
        <td height="22"><div align="left">
          <input name="codestpro5" type="text" id="codestpro5" size="<?php echo $li_size5 ?>" maxlength="<?php echo $li_loncodestpro5 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a>
          <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
        </div></td>
      </tr>
	  <?php
	  }
	  ?>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();">
          <input name="coduniadm" type="hidden" id="coduniadm" value="<?php echo $ls_coduniadm;?>">
          <input name="estuac" type="hidden" id="estuac" value="<?php echo $ls_estuac;?>">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
</div>
<p align="center">
  <?php
print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>C&oacute;digo </td>";
print "<td width=200 style=text-align:center>Denominaci&oacute;n</td>";
print "<td width=50 style=text-align:center>Emite Req.</td>";
print "<td width=100 style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
print "<td width=100 style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
print "<td width=100 style=text-align:center>".$la_empresa["nomestpro3"]."</td>";
if ($la_empresa["estmodest"]==2)
   {
	 print "<td width=50 style=text-align:center>".$la_empresa["nomestpro4"]."</td>";
	 print "<td width=50 style=text-align:center>".$la_empresa["nomestpro5"]."</td>"; 
   }
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sqlaux = "";
	 if ($ls_estuac!='C')
	    {
		  $ls_sqlaux = " AND spg_unidadadministrativa.coduniadmsig='".$ls_coduniadm."' AND spg_unidadadministrativa.coduniadm like '%".$ls_codunieje."%'";
	      if (!empty($ls_codestpro1)) 
		     {
			   $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
			   $ls_sqlaux     = $ls_sqlaux." AND spg_dt_unidadadministrativa.codestpro1='".$ls_codestpro1."'";
			 }
	      if (!empty($ls_codestpro2)) 
		     {
			   $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
			   $ls_sqlaux     = $ls_sqlaux." AND spg_dt_unidadadministrativa.codestpro2='".$ls_codestpro2."'";
			 }
	      if (!empty($ls_codestpro3)) 
		     {
			   $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
			   $ls_sqlaux     = $ls_sqlaux." AND spg_dt_unidadadministrativa.codestpro3='".$ls_codestpro3."'";
			 }
	      if (!empty($ls_codestpro4)) 
		     {
			   $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);
			   $ls_sqlaux     = $ls_sqlaux." AND spg_dt_unidadadministrativa.codestpro4='".$ls_codestpro4."'";
			 }
	      if (!empty($ls_codestpro5)) 
		     {
			   $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
			   $ls_sqlaux     = $ls_sqlaux." AND spg_dt_unidadadministrativa.codestpro5='".$ls_codestpro5."'";
			 }
		}
	 
	 $ls_sql = "SELECT spg_unidadadministrativa.coduniadm,
	                   spg_unidadadministrativa.denuniadm,
					   spg_unidadadministrativa.estemireq,
					   spg_dt_unidadadministrativa.codestpro1,
					   spg_dt_unidadadministrativa.codestpro2,
					   spg_dt_unidadadministrativa.codestpro3,
					   spg_dt_unidadadministrativa.codestpro4,
					   spg_dt_unidadadministrativa.codestpro5,
					   spg_dt_unidadadministrativa.estcla,
					   spg_ep1.denestpro1,
					   spg_ep2.denestpro2,
					   spg_ep3.denestpro3,
					   spg_ep4.denestpro4,
					   spg_ep5.denestpro5,
					   spg_unidadadministrativa.coduniadmsig
			      FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5
			     WHERE spg_unidadadministrativa.codemp='".$ls_codemp."' 
				   AND spg_unidadadministrativa.denuniadm like '%".$ls_denunieje."%' $ls_sqlaux
				   AND spg_ep1.codestpro1<>'-------------------------'
				   AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp
				   AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm
			       AND spg_ep2.codemp=spg_ep1.codemp
				   AND spg_ep2.codestpro1=spg_ep1.codestpro1
				   AND spg_ep2.estcla=spg_ep1.estcla
				   AND spg_ep3.codemp=spg_ep2.codemp
				   AND spg_ep3.codestpro1=spg_ep2.codestpro1
				   AND spg_ep3.codestpro2=spg_ep2.codestpro2
				   AND spg_ep3.estcla=spg_ep2.estcla
				   AND spg_ep4.codemp=spg_ep3.codemp
				   AND spg_ep4.codestpro1=spg_ep3.codestpro1
				   AND spg_ep4.codestpro2=spg_ep3.codestpro2
				   AND spg_ep4.codestpro3=spg_ep3.codestpro3
				   AND spg_ep4.estcla=spg_ep3.estcla
				   AND spg_ep5.codemp=spg_ep4.codemp
				   AND spg_ep5.codestpro1=spg_ep4.codestpro1
				   AND spg_ep5.codestpro2=spg_ep4.codestpro2
				   AND spg_ep5.codestpro3=spg_ep4.codestpro3
				   AND spg_ep5.codestpro4=spg_ep4.codestpro4
				   AND spg_ep5.estcla=spg_ep4.estcla
				   AND spg_dt_unidadadministrativa.codemp=spg_ep1.codemp
				   AND spg_dt_unidadadministrativa.codestpro1=spg_ep1.codestpro1 
				   AND spg_dt_unidadadministrativa.estcla=spg_ep1.estcla
				   AND spg_dt_unidadadministrativa.codemp=spg_ep2.codemp
				   AND spg_dt_unidadadministrativa.codestpro2=spg_ep2.codestpro2 
				   AND spg_dt_unidadadministrativa.codestpro1=spg_ep2.codestpro1 
				   AND spg_dt_unidadadministrativa.estcla=spg_ep2.estcla
 				   AND spg_dt_unidadadministrativa.codemp=spg_ep3.codemp
				   AND spg_dt_unidadadministrativa.codestpro1=spg_ep3.codestpro1 
			       AND spg_dt_unidadadministrativa.codestpro2=spg_ep3.codestpro2 
				   AND spg_dt_unidadadministrativa.codestpro3=spg_ep3.codestpro3 
				   AND spg_dt_unidadadministrativa.estcla=spg_ep3.estcla
				   AND spg_dt_unidadadministrativa.codemp=spg_ep4.codemp
				   AND spg_dt_unidadadministrativa.codestpro1=spg_ep4.codestpro1 
			       AND spg_dt_unidadadministrativa.codestpro2=spg_ep4.codestpro2 
				   AND spg_dt_unidadadministrativa.codestpro3=spg_ep4.codestpro3 
				   AND spg_dt_unidadadministrativa.codestpro4=spg_ep4.codestpro4 
				   AND spg_dt_unidadadministrativa.estcla=spg_ep4.estcla
				   AND spg_dt_unidadadministrativa.codemp=spg_ep5.codemp
				   AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1 
			       AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2 
				   AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3 
				   AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4 
			       AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5
				   AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla
			     ORDER BY spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,
				          spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,
						  spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.estcla ASC";//print $ls_sql;
 	
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
					   $ls_codestpro1 = trim(substr($row["codestpro1"],-$li_loncodestpro1));
					   $ls_codestpro2 = trim(substr($row["codestpro2"],-$li_loncodestpro2));
					   $ls_codestpro3 = trim(substr($row["codestpro3"],-$li_loncodestpro3));
					   $ls_codestpro4 = trim(substr($row["codestpro4"],-$li_loncodestpro4));
					   $ls_codestpro5 = trim(substr($row["codestpro5"],-$li_loncodestpro5));
					   $ls_denestpro1 = $row["denestpro1"];
					   $ls_denestpro2 = $row["denestpro2"];
					   $ls_denestpro3 = $row["denestpro3"];
					   $ls_denestpro4 = $row["denestpro4"];
					   $ls_denestpro5 = $row["denestpro5"]; 
					   $ls_estcla     = $row["estcla"]; 
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Proyecto';
						  }
					   elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
					   elseif($ls_estcla!='-')
					      {
						    $ls_denestcla='Sector';
						  }
					   $ls_codunieje = $row["coduniadm"];
					   $ls_denunieje = $row["denuniadm"];
					   $li_estemireq = $row["estemireq"];
					   if ($li_estemireq=='1')
					      {
						    $ls_denemireq = 'SI';
						  }
					   elseif($li_estemireq=='0')
					      {
						    $ls_denemireq = 'NO';
						  }
					   echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$li_estemireq','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_coduniadm','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5');\">".$ls_codunieje."</a></td>";
					   echo "<td width=200 style=text-align:left>".$ls_denunieje."</td>";
					   echo "<td width=50 style=text-align:center>".$ls_denemireq."</td>";
					   echo "<td width=100 style=text-align:center>".$ls_codestpro1."</td>";
					   echo "<td width=100 style=text-align:center>".$ls_codestpro2."</td>";
					   echo "<td width=100 style=text-align:center>".$ls_codestpro3."</td>";
					   if ($li_estmodest=='2')
					      {
					        echo "<td width=100 style=text-align:center>".$ls_codestpro4."</td>";
					        echo "<td width=100 style=text-align:center>".$ls_codestpro5."</td>";
						  }
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
</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno,estreq,codest1,codest2,codest3,codest4,codest5,coduniadmsig,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5)
  {
    opener.document.form1.txtcoduniadm.value=codigo;
    opener.document.form1.txtdenuniadm.value=deno;
	opener.document.form1.codestpro2.value=codest2;
	opener.document.form1.codestpro3.value=codest3;
	opener.document.form1.denestpro2.value=denestpro2;
	opener.document.form1.denestpro3.value=denestpro3;
	if("<?php echo $li_estmodest?>"==2)
	{
		opener.document.form1.codestpro4.value=codest4;
		opener.document.form1.codestpro5.value=codest5;
		opener.document.form1.denestpro4.value=denestpro4;
		opener.document.form1.denestpro5.value=denestpro5;
	}	
	close();
  }
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_uel.php";
	f.submit();
}
  
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	ls_estcla  = f.hidtipestpro.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	ls_estcla  = f.hidtipestpro.value; 
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3==""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estpro.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function catalogo_estpro4()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	denestpro3 = f.denestpro3.value;
	ls_estcla  = f.hidtipestpro.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!=""))
	{
		pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3");
	}
}

function catalogo_estpro5()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	denestpro3 = f.denestpro3.value;
	codestpro4 = f.codestpro4.value;
	denestpro4 = f.denestpro4.value;
	codestpro5 = f.codestpro5.value;
	ls_estcla  = f.hidtipestpro.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!="")&&(codestpro5==""))
	{
		pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estprograma.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=710,height=400,resizable=yes,location=no");
	}
}
</script>
</html>
