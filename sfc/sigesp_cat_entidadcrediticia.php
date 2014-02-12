<?php
session_start();

if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 

$la_datemp=$_SESSION["la_empresa"];
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="cod_pro";
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
<title>Cat&aacute;logo de Entidades Crediticias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_denominacion="%".$_POST["denominacion"]."%";
	//$ls_telefono="%".$_POST["telefono"]."%";
	$ls_codest=$_POST["cmbestado"];
	$ls_codesta="%".$_POST["cmbestado"]."%";
	
}
else
{
	$ls_operacion="";
	$ls_denominacion="";
	//$ls_telefono="";
	$ls_codest="";
	$ls_codesta="";
	
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
	
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Entidades Crediticias</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <tr>
        <td width="67" height="30"><div align="right">Estado</div></td>
        <td width="431"><div align="left"><span class="style6">
          <?php
	       
		    $ls_sql="SELECT codest ,desest,codpai 
                       FROM sigesp_estados
                      WHERE codpai='058' ORDER BY codest ASC";
					
			$lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_estado);
			
				    
			if($lb_valest)
			 {
			   $io_datastore->data=$la_estado;
			   $li_totalfilas=$io_datastore->getRowCount("codest");
			 }
			 else
			   $li_totalfilas=0;
					 
		  ?>
          <select name="cmbestado" size="1" id="cmbestado">
            <option value="">Seleccione...</option>
            <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					} 
	        ?>
          </select>
        </span></div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	
	
 
<?php
if($ls_operacion=="BUSCAR")
{
//$ls_cxc="CXC";
//print $ls_cxc;
//SELECT DISTINCT sfc_entidadcrediticia.cod_entidad,sfc_entidadcrediticia.denominacion,sfc_entidadcrediticia.direccion,".
			//" FROM sfc_entidadcrediticia ".	" WHERE sfc_entidadcrediticia.denominacion like '".$ls_denominacion."'";
$ls_cadena=" SELECT *  
               FROM  sfc_entidadcrediticia ".
			" WHERE  codemp='".$ls_codemp."'  
			    and  sfc_entidadcrediticia.denominacion ilike '".$ls_denominacion."' 
			    and  codest ilike '".$ls_codesta."' 
		   order by  cod_entidad ASC";
		 //  print $ls_cadena;   
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>Codigo</font></td>";
					print "<td><font color=#FFFFFF>Nombre </font></td>";
					print "<td><font color=#FFFFFF>Direccion</font></td>";
					print "<td><font color=#FFFFFF>Telefono</font></td>";
					print "<td><font color=#FFFFFF>Email</font></td>";
					print "<td><font color=#FFFFFF>Direccion Web</font></td>";
					$la_entidad=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_entidad;
					$totrow=$io_data->getRowCount("cod_entidad");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$id_entidad=$io_data->getValue("id_entidad",$z);
						$cod_entidad=$io_data->getValue("cod_entidad",$z);
		                $denominacion=$io_data->getValue("denominacion",$z);
						$direccion=$io_data->getValue("direccion",$z);
						$telefono=$io_data->getValue("telefono",$z);
						$email=$io_data->getValue("email",$z);
						$paginaweb=$io_data->getValue("paginaweb",$z);
						$codest=$io_data->getValue("codest",$z);
						$codpai=$io_data->getValue("codpai",$z);
						$codmun=$io_data->getValue("codmun",$z);
						$codpar=$io_data->getValue("codpar",$z);
						
						print "<td><a href=\"javascript: aceptar('$id_entidad','$cod_entidad','$denominacion','$direccion','$telefono','$email','$paginaweb','$codest','$codpai','$codmun','$codpar');\">".$cod_entidad."</a></td>";
						print "<td align=left>".$denominacion."</td>";
						print "<td align=left>".$direccion."</td>";
						print "<td align=left>".$telefono."</td>";
						print "<td align=left>".$email."</td>";
						print "<td align=left>".$paginaweb."</td>";
						/*print "<td align=left>".$codpai."</td>";
						print "<td align=left>".$codest."</td>";
						print "<td align=left>".$codmun."</td>";
						print "<td align=left>".$codpar."</td>";*/
						print "</tr>";							
					}
					/*print $codcli."/".$numnot."/".$dennot."/".$tipnot."/".$fecnot.$monto;*/
				}
				else
				{
					$io_msg->message("No se han registrado Entidades Crediticias");
				}
		}
}
print "</table>";
?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(id_entidad,cod_entidad,denominacion,direccion,telefono,email,paginaweb,codest,codpai,codmun,codpar)
  {
    
	opener.ue_cargarentidad(id_entidad,cod_entidad,denominacion,direccion,telefono,email,paginaweb,codest,codpai,codmun,codpar);
	close();
  }  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_entidadcrediticia.php";
	  f.submit();  
  } 
</script>
</html>
