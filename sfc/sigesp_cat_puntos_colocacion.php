<?php
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
require_once("class_folder/sigesp_sfc_class_utilidades.php");
$io_utilidad = new sigesp_sfc_class_utilidades();
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("class_folder/sigesp_sfc_c_cliente.php");
$io_conect      = new sigesp_include();//Instanciando la Sigesp_Include.
$conn           = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_funciondb   = new class_funciones_db($conn);
$io_msg         = new class_mensajes();
$io_cliente     = new sigesp_sfc_c_cliente();
$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cliente.php";

$la_seguridad["empresa"]=$ls_empresa;
$la_seguridad["logusr"]=$ls_logusr;
$la_seguridad["sistema"]=$ls_sistema;
$la_seguridad["ventanas"]=$ls_ventanas;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Puntos de Colocaci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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

.styleMonto1 {

	color:#990000;
	cursor:text;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;}

.styleMonto2 {
    border:none;
	color: #003399;
	cursor:text;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;}
-->
</style></head>

<body>
<!--
/************************************************************************************************************************/
/********************************   INICIO DEL FORMULARIO ***************************************************************/
/************************************************************************************************************************/-->
<?php 
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion  = $_POST["operacion"];
	$ls_codptocol  = $_POST["txtcodptocol"];
	$ls_codpai     = $_POST["codpai"];
	$ls_codest     = $_POST["codest"];
	$ls_codmun     = $_POST["codmun"];
	$ls_codpar     = $_POST["codpar"];
	$ls_codcli     = $_POST["txtcodcli"];
	$ls_cedcli     = $_POST["txtrifcli"];	
	$ls_nomcli     = $_POST["txtnomcli"];	
	$ls_razonptocol= $_POST["txtrazpto"];
	$ls_dir_ptocol = $_POST["txtdirpto"];	
	$ls_ptoref     = $_POST["txtptorefdir"];	
	$ls_telffijo   = $_POST["txttelffijopto"];
	$ls_telffax    = $_POST["txtfaxpto"];
	$ls_movilpto   = $_POST["txtmovilpto"];
	$ls_observacion= $_POST["txtobsptocol"];
	$ls_nombrecontacto=$_POST["txtnompercon"];
	$ls_cedulacontacto=$_POST["txtcedpercon"];
	$ls_emailcontacto =$_POST["txtemailcon"];
	$ls_telf_contacto =$_POST["txttelffijcon"];
	$ls_movil_contacto=$_POST["txttelfmovcon"];
	$ls_estatus    = $_POST["hidstatus"];
}
else
{
	$ls_operacion = "";
	$ls_codptocol = "";
	$ls_codpai    = "";
	$ls_codest    = "";
	$ls_codmun    = "";
	$ls_codpar    = "";
	$ls_codcli    = $_GET["codcli"];
	$ls_cedcli    = $_GET["cedcli"];	
	$ls_nomcli    = $_GET["nomcli"];	
	$ls_razonptocol= "";
	$ls_dir_ptocol = "";	
	$ls_ptoref     = "";	
	$ls_telffijo   = "";
	$ls_telffax    = "";
	$ls_movilpto   = "";
	$ls_observacion= "";
	$ls_nombrecontacto="";
	$ls_cedulacontacto="";
	$ls_emailcontacto ="";
	$ls_telf_contacto ="";
	$ls_movil_contacto="";
	$ls_estatus    = "t";
}	

if($ls_operacion=="NUEVO")
{
	$ls_codpai     = "";
	$ls_codest     = "";
	$ls_codmun     = "";
	$ls_codpar     = "";
	$ls_estatus    = "t";
	$ls_codptocol = $io_funciondb->uf_generar_codigo(true,$_SESSION["la_empresa"]["codemp"],'sfc_puntocolocacion','codptocol');
	if (empty($ls_codptocol))
	{
	 	  $io_msg->message($io_funciondb->is_msg_error);
    }	
	$ls_codcli     = $_POST["txtcodcli"];
	$ls_cedcli     = $_POST["txtrifcli"];	
	$ls_nomcli     = $_POST["txtnomcli"];	
	$ls_razonptocol= "";
	$ls_dir_ptocol = "";	
	$ls_ptoref     = "";	
	$ls_telffijo   = "";
	$ls_telffax    = "";
	$ls_movilpto   = "";
	$ls_observacion= "";
	$ls_nombrecontacto="";
	$ls_cedulacontacto="";
	$ls_emailcontacto ="";
	$ls_telf_contacto ="";
	$ls_movil_contacto="";
}
elseif($ls_operacion=="GUARDAR")
{
	$lb_valido=$io_cliente->uf_guardar_ptos_colocacion($_SESSION["la_empresa"]["codemp"],$ls_codptocol,$ls_codcli,$ls_razonptocol,$ls_dir_ptocol,$ls_ptoref,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_telffijo,
					   $ls_telffax,$ls_movilpto,$ls_observacion,$ls_nombrecontacto,$ls_cedulacontacto,$ls_emailcontacto,$ls_telf_contacto,$ls_movil_contacto,'t',$la_seguridad);
	if($lb_valido==true)
	{
		$io_cliente->io_sql->commit();
		$io_msg->message("El registro fue guardado");
	}
	else
	{
		$io_cliente->io_sql->rollback();
		$io_msg->message($io_cliente->io_msgc);
	}	
}
elseif($ls_operacion=="ELIMINAR")
{
	$lb_valido=$io_cliente->uf_eliminar_ptos_colocacion($_SESSION["la_empresa"]["codemp"],$ls_codptocol,$ls_codcli,$la_seguridad);
	if($lb_valido==true)
	{
		$io_cliente->io_sql->commit();
		$ls_estatus='f';
		$io_msg->message("El registro fue eliminado");
	}
	else
	{
		$io_cliente->io_sql->rollback();
		$io_msg->message($io_cliente->io_msgc);
	}	
}
?>
<form name="form1" method="post" action="">
<table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
     	 	<td colspan="5" class="titulo-celda">Definici&oacute;n de puntos de Colocaci&oacute;n del Cliente:<br><?php print $ls_cedcli." - ".$ls_nomcli;?></td>
    	</tr>
    	<tr bgcolor="#F6FCF5">
    	  <td width="27"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo-off.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></td>
  	      <td width="34"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></td>
  	      <td width="30"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></td>
  	      <td width="30"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></td>
  	      <td width="439"><a href="javascript:close();"><img src="../shared/imagebank/tools20/salir.gif" alt="Cerrar" title="Cerrar" width="20" height="20" border="0"></a></td>
   	  </tr>
  </table>
	 <br>
	 <table width="560"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
       <tr>
         <td width="134" height="20" ><input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_estatus;?>">
           <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion;?>">
           <input name="txtcodcli" type="hidden" id="txtcodcli" value="<?php print $ls_codcli;?>">
           <input name="txtrifcli" type="hidden" id="txtrifcli" value="<?php print $ls_cedcli;?>" size="20" readonly>
           <input name="txtnomcli" type="hidden" id="txtnomcli" value="<?php print $ls_nomcli;?>" size="40" readonly>
 <td width="424" ><input name="codpai" type="hidden" id="codpai" value="<?php print $ls_codpai;?>">
				  <input name="codest" type="hidden" id="codest" value="<?php print $ls_codest;?>">
				  <input name="codmun" type="hidden" id="codmun" value="<?php print $ls_codmun;?>">
	    <input name="codpar" type="hidden" id="codpar" value="<?php print $ls_codpar;?>"></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Codigo</div>
         <td height="24" ><label>
           <input name="txtcodptocol" type="text" id="txtcodptocol" value="<?php print $ls_codptocol;?>" style="text-align:center" readonly>
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Punto de Entrega        
         </div>
         <td height="24" ><label>
         <input name="txtrazpto" type="text" id="txtrazpto" size="65" maxlength="255" value="<?php print $ls_razonptocol;?>">
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Direcci&oacute;n       
         </div>
         <td height="24" ><label>
         <input name="txtdirpto" type="text" id="txtdirpto" size="65" maxlength="255" value="<?php print $ls_dir_ptocol;?>">
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Punto de Referencia       
         </div>
         <td height="24" ><label>
         <input name="txtptorefdir" type="text" id="txtptorefdir" size="65" maxlength="255" value="<?php print $ls_ptoref;?>">
         </label></td>
       </tr>
	    <tr>
         <td height="24" align="right">Pais</td>
         <td height="24"><span class="style6">
           <?php
                   $ls_sql="SELECT codpai ,despai FROM sigesp_pais
					         ORDER BY despai ASC";
				    $lb_valest=$io_utilidad->uf_datacombo($ls_sql,$la_pais);			

					if($lb_valest)
				     {
					   $io_datastore->data=$la_pais;
					   $li_totalfilas=$io_datastore->getRowCount("codpai");
				     }
					 else
					 	$li_totalfilas=0;
				    ?>
           <select name="cmbpais" size="1" id="cmbpais" onChange="javascript:ue_llenarcmb();">
             <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpai",$li_i);
					 $ls_despai=$io_datastore->getValue("despai",$li_i);
					 if ($ls_codigo==$ls_codpai)
					 {
						  print "<option value='$ls_codigo' selected>$ls_despai</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_despai</option>";
					 }
					}
	                ?>
           </select>
         </span></td>
       </tr>
       <tr>
         <td height="24" align="right">Estado</td>
         <td height="24"><span class="style6">
           <?php
			       $ls_sql="SELECT codest ,desest FROM sigesp_estados
					   WHERE codpai='".$ls_codpai."' ORDER BY codest ASC";
				       $lb_valest=$io_utilidad->uf_datacombo($ls_sql,$la_estado);

					if($lb_valest)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
					 else
					 	$li_totalfilas=0;
				    ?>
           <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmb();">
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
         </span></td>
       </tr>
       <tr>
         <td height="24" align="right">Municipio</td>
         <td height="24">
	 	   <span class="style6">
           <?php
					$lb_valmun=false;
					if($ls_codest=="")
					{
						$lb_valmun=false;
					}
					else
					{
						 $ls_sql="SELECT codmun ,denmun
                                  FROM sigesp_municipio
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valmun=$io_utilidad->uf_datacombo($ls_sql,&$la_municipio);
					}
					if($lb_valmun)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}
			?>
           <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmb();">
             <option value="">Seleccione...</option>
             <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					}
	        ?>
           </select>
         </span> </td>
       </tr>
       <tr>
         <td height="24" align="right">Parroquia</td>
         <td height="24"><span class="style6">
           <?php
				$lb_valpar=false;
			    if($ls_codmun=="")
					{
						$lb_valpar=false;
					}
					else
					 {
						 $ls_sql="SELECT codpar,denpar
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valpar=$io_utilidad->uf_datacombo($ls_sql,&$la_parroquia);
					 }

					if($lb_valpar)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}
					else{$li_totalfilas=0;}
			    ?>
           <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmb();">
             <option value="">Seleccione...</option>
             <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					}
	            ?>
           </select>
         </span> </td>
       </tr>
       
       <tr>
         <td height="24" ><div align="right">Tel&eacute;fono Fijo</div></td>
         <td height="24" ><input name="txttelffijopto" type="text" id="txttelffijopto" onKeyPress="return(validaCajas(this,'t',event,254))" size="25" maxlength="100" value="<?php print $ls_telffijo;?>"></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Tel&eacute;fono Fax</div></td>
		 <td height="24" ><input name="txtfaxpto" type="text" id="txtfaxpto" size="25" onKeyPress="return(validaCajas(this,'t',event,254))" maxlength="100" value="<?php print $ls_telffax;?>"></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Tel&eacute;fono Movil</div></td>
		 <td height="24" ><input name="txtmovilpto" type="text" id="txtmovilpto" size="25" onKeyPress="return(validaCajas(this,'t',event,254))" maxlength="100" value="<?php print $ls_movilpto;?>"></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Observaci&oacute;n</div></td>
         <td height="24" ><label>
           <input name="txtobsptocol" type="text" id="txtobsptocol" size="65" maxlength="100" value="<?php print $ls_observacion;?>">
         </label></td>
       </tr>
       <tr>
         <td height="13" >   </td>    
         <td >&nbsp;</td>
       </tr>
       <tr>
         <td height="13" colspan="2" class="titulo-celda" > Datos de Contacto</td>
       <tr>
         <td height="13" > </td>      
         <td >&nbsp;</td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Nombre y Apellido</div></td>
         <td height="24" ><label>
           <input name="txtnompercon" type="text" id="txtnompercon" size="65" maxlength="50" value="<?php print $ls_nombrecontacto;?>">
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">C&eacute;dula</div></td>
         <td height="24" ><input name="txtcedpercon" type="text" id="txtcedpercon" size="20" maxlength="10" value="<?php print $ls_cedulacontacto;?>"></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Email</div></td>
         <td height="24" ><label>
           <input name="txtemailcon" type="text" id="txtemailcon" size="65" maxlength="100" value="<?php print $ls_emailcontacto;?>" >
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Telefono Fijo</div></td>
         <td height="24" ><label>
           <input name="txttelffijcon" type="text" id="txttelffijcon" onKeyPress="return(validaCajas(this,'t',event,254))" size="25" maxlength="100" value="<?php print $ls_telf_contacto;?>">
         </label></td>
       </tr>
       <tr>
         <td height="24" ><div align="right">Telefono Movil</div></td>
         <td height="24" ><label>
           <input name="txttelfmovcon" type="text" id="txttelfmovcon" onKeyPress="return(validaCajas(this,'t',event,254))" size="25" maxlength="100" value="<?php print $ls_movil_contacto;?>">
         </label></td>
       </tr>
       <tr>
         <td height="13" >       
         <td >&nbsp;</td>
       </tr>
  </table>	 
</form>

</body>
<script language="javascript">

function ue_llenarcmb()
{
	f=document.form1;
	ls_estatus=f.hidstatus.value;
	if(ls_estatus=='t')
	{
		f.action="sigesp_cat_puntos_colocacion.php";
		f.codpai.value=f.cmbpais.value;
		f.codest.value=f.cmbestado.value;
		f.codmun.value=f.cmbmunicipio.value;
		f.codpar.value=f.cmbparroquia.value;	
		f.operacion.value="";	
		f.submit();
	}
	else
	{
         alert("El punto de colocación se encuentra en estatus inactivo, no puede editar el registro");
	}
}

function ue_nuevo()
{
	f=document.form1;
	f.action="sigesp_cat_puntos_colocacion.php";
	f.operacion.value="NUEVO";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	ls_estatus=f.hidstatus.value;
	if(ls_estatus=='t')
	{
		if(uf_verificar_campos())
		{	    
			f.action="sigesp_cat_puntos_colocacion.php";
			f.operacion.value="GUARDAR";
			f.submit();
		}
	}
	else
	{
         alert("El punto de colocación se encuentra en estatus inactivo, no puede editar el registro");
	}
}

function ue_eliminar()
{
	f=document.form1;
	ls_estatus=f.hidstatus.value;
	if(ls_estatus=='t')
	{
		f.action="sigesp_cat_puntos_colocacion.php";
		f.operacion.value="ELIMINAR";
		f.submit();
	}
	else
	{
         alert("El punto de colocación se encuentra en estatus inactivo, no puede editar el registro");
	}
}
function uf_verificar_campos()
{
	f=document.form1;
	ls_codptocol=f.txtcodptocol.value;
	if(ls_codptocol=="")
	{
		alert("Debe presionar Nuevo para generar un numero para el punto de colocación");
		return false;
	}
	ls_razptocol=f.txtrazpto.value;
	if(ls_razptocol=="")
	{
		alert("Debe indicar el nombre(Razón Social) del Punto de Colocación");
		f.txtrazpto.focus=true;
		return false;
	}
	ls_dirptocol=f.txtdirpto.value;
	if(ls_dirptocol=="")
	{
		alert("Debe indicar la dirección del Punto de Colocación");
		f.txtdirpto.focus=true;
		return false;
	}
	ls_ptorefdir=f.txtptorefdir.value;
	if(ls_ptorefdir=="")
	{
		alert("Debe indicar un punto de referencia del Punto de Colocación");
		f.txtptorefdir.focus=true;
		return false;		
	}
	ls_codpai   =f.cmbpais.value;
	ls_codest   =f.cmbestado.value;
	ls_codmun   =f.cmbmunicipio.value;
	ls_codpar   =f.cmbparroquia.value;
	if(ls_codpai=="---")
	{
		alert("Debe indicar el Pais");
		f.cmbpais.focus=true;
		return false;		
	}
	if(ls_codest=="")
	{
		alert("Debe indicar el Estado");
		f.cmbestado.focus=true;
		return false;		
	}
	if(ls_codmun=="")
	{
		alert("Debe indicar el Municipio");
		f.cmbmunicipio.focus=true;
		return false;		
	}
	if(ls_codpar=="")
	{
		alert("Debe indicar la Parroquia");
		f.cmbparroquia.focus=true;
		return false;		
	}
	ls_telfijopto=f.txttelffijopto.value; 
	if(ls_telfijopto=="")
	{
		alert("Debe indicar un telefono fijo del Punto de Colocación");
		f.txttelffijopto.focus=true;
		return false;
	}
	ls_telfaxpto =f.txtfaxpto.value;
	if(ls_telfaxpto=="")
	{
		alert("Debe indicar un telefono fax del Punto de Colocación");
		f.txtfaxpto.focus=true;
		return false;
	}
	ls_movilpto =f.txtmovilpto.value;
	if(ls_movilpto=="")
	{
		alert("Debe indicar un telefono movil del Punto de Colocación");
		f.txtmovilpto.focus=true;
		return false;
	}
	ls_observacion=f.txtobsptocol.value;
	//Datos del contacto
	ls_nombrecont=f.txtnompercon.value;
	if(ls_nombrecont=="")
	{
		alert("Debe indicar una persona de contacto para el Punto de Colocación");
		f.txtnompercon.focus=true;
		return false;
	}
	ls_cedulacont=f.txtcedpercon.value;
	if(ls_cedulacont=="")
	{
		alert("Debe indicar la cedula de la persona de contacto para el Punto de Colocación");
		f.txtcedpercon.focus=true;
		return false;
	}	
	ls_emailcont =f.txtemailcon.value;
	if(ls_emailcont=="")
	{
		alert("Debe indicar el email de la persona de contacto para el Punto de Colocación");
		f.txtemailcon.focus=true;
		return false;
	}	
	ls_telffijocon=f.txttelffijcon.value;
	if(ls_telffijocon=="")
	{
		alert("Debe indicar un telefono fijo de la persona de contacto para el Punto de Colocación");
		f.txttelffijcon.focus=true;
		return false;
	}	
	ls_telfmovcon =f.txttelfmovcon.value;	
	if(ls_telfmovcon=="")
	{
		alert("Debe indicar un telefono movil de la persona de contacto para el Punto de Colocación");
		f.txttelfmovcon.focus=true;
		return false;
	}
	return true;
}

function ue_buscar()
{
	f=document.form1;
	ls_codcli=f.txtcodcli.value;
	pagina="sigesp_cat_puntos_col.php?codcli="+ls_codcli;
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
}
</script>
</html>