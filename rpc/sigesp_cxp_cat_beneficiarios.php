<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Beneficiarios</title>
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
<!--script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script-->
<body>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 $ls_cedula="%".$_POST["txtcedula"]."%";
		 $ls_nombre="%".$_POST["txtnombre"]."%";
	   }
	else
	   {
		$ls_operacion="";
	   }
?>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4">Cat&aacute;logo de Beneficiarios
        <input name="operacion" type="hidden" id="operacion"></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22" style="text-align:right">C&eacute;dula</td>
        <td width="139" height="22"><input name="txtcedula" type="text" id="txtcedula">        </td>
        <td width="58" height="22"><div align="right">Apellido</div></td>
        <td width="237" height="22"><input name="txtapellido" type="text" id="txtapellido" size="25"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22"><input name="txtnombre" type="text" id="nombre"></td>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><?php
		/*Llenar Combo Banco*/
		$ls_sql=" SELECT  codban,nomban ".
		        " FROM    scb_banco ".
				" WHERE   codemp='".$ls_codemp."' ".
				" ORDER BY codban ASC";
		$rs_banco=$io_sql->select($ls_sql);
		?>  
        <select name="cmbbanco" id="cmbbanco" style="width:150px">
        <option value="000">---seleccione---</option>
        <?php
		while ($row=$io_sql->fetch_row($rs_banco))
  			  {
			    $ls_codban=$row["codban"];
			    $ls_nomban=$row["nomban"];
			    if ($ls_codban==$ls_banco)
			 	   {
					 print "<option value='$ls_codban' selected>$ls_nomban</option>";
				   }
			    else
				   {
					 print "<option value='$ls_codban'>$ls_nomban</option>";
				   }
			  } 
	  $io_sql->free_result($rs_banco);
	  ?>
          </select>
	    </td>
  </tr>
    <tr>
	  <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Beneficiario</a></div></td>
   </tr>
</table> 
<p align="center">
<?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>C&eacute;dula</td>";
echo "<td style=text-align:center width=400>Nombre</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	$ls_cedbene = $_POST["txtcedula"];
	$ls_nombene = $_POST["txtnombre"];
	$ls_apebene = $_POST["txtapellido"];
	$ls_codban  = $_POST["cmbbanco"];
	if ($ls_codban=="000")
	   {  
	     $ls_codban = "";	
	   } 
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
    $ls_sql=" SELECT TRIM(ced_bene) as ced_bene,nombene,apebene,TRIM(sc_cuenta) as scgcta
	            FROM rpc_beneficiario
               WHERE codemp='".$ls_codemp."'
			     AND ced_bene like '%".$ls_cedbene."%'
				 AND nombene like '%".$ls_nombene."%'
				 AND apebene like '%".$ls_apebene."%'
				 AND codban like '%".$ls_codban."%'
				 AND ced_bene<>'----------'
               ORDER BY ced_bene ASC";

      $rs_data = $io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	  if ($rs_data===false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		 }
      else
	     {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while (!$rs_data->EOF)
				      {
					    echo "<tr class=celdas-blancas>";
						$ls_cedben = trim($rs_data->fields["ced_bene"]);
						$ls_nomben = $rs_data->fields["nombene"];
						$ls_apeben = $rs_data->fields["apebene"];
						$ls_scgcta = $rs_data->fields["scgcta"];						
						echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedben','$ls_nomben','$ls_apeben','$ls_scgcta');\">".$ls_cedben."</a></td>";
			            echo "<td style=text-align:left   width=400>".$ls_nomben."</td>";
						echo "</tr>";
						$rs_data->MoveNext();						
                      }
              }
           else
		      {
			    $io_msg->message("No se han Definido Beneficiarios !!!");
			  }
		 }
   }
echo "</table>";
?>
  </p>
</form>      

</body>
<script language="JavaScript">
  function aceptar(cedula,nombre,apellido,sc_cuenta)
  {
    li_principal=(opener.document.form1.hidcatalogo.value); //Para decidir que campos vamos a rellenar para cada llamado del catalogo.
	if (li_principal==1)
	   {
	     opener.document.form1.txtcodproben.value = cedula;
	     opener.document.form1.txtnombre.value    = nombre;
	     opener.document.form1.hidcodcuenta.value = sc_cuenta;
	   }
	else
	   {
	    buscar=opener.document.form1.hidrangocodigos.value;
        if (buscar=="1")
           {
	         opener.document.form1.txtcodigo1.value=cedula;
           }
        else
           {
	         opener.document.form1.txtcodigo2.value=cedula;   
           }
	   }   
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cxp_cat_beneficiarios.php";
	  f.submit();
  }
</script>
</html>