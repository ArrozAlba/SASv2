<?php 
session_start(); 
require_once("sigesp_config.php");
require_once("shared/class_folder/sigesp_include.php");
require_once("shared/class_folder/class_sql.php");
require_once("shared/class_folder/class_funciones.php");
require_once("shared/class_folder/class_mensajes.php");
$io_include       = new sigesp_include();
$io_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($io_conect);
$msg=new class_mensajes();
//$obj = new sigesp_include();
?>
<html>
<head>
<title>Sistema Administrativo HUAYRA -**- C.V.A.L -**-</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/principal.css"/>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--

input,select,textarea,text{font-family:Tahoma, Verdana, Arial;font-size:11px;}
body {background-color: #EAEAEA; font-family: Tahoma, Verdana, Arial;	font-size: 10px;color: #000000;}
.boton{border-right:1px outset #FFFFFF;border-top:1px outset #CCCCCC;border-left:1px outset #CCCCCC;border-bottom:1px outset #FFFFFF;font-weight:bold;cursor:pointer;color: #666666;background-color:#CCCCCC;font-family: Tahoma, Verdana, Arial;	font-size: 11px;}
.pie-pagina{
	color: #898989;
	text-align: center;
	background-color: #EAEAEA;
}
.Estilo1 {color: #FF0000}
-->
</style>

<link href="shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo8 {font-size: 12px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; }
-->
</style>
</head>
<body leftmargin="0" marginwidth="0" marginheight="0" class="fondo_contenido_capa1">
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<?php
	if(array_key_exists("OPERACION",$_POST))
	{
		$operacion=$_POST["OPERACION"];
		$ls_codtienda=$_POST["cmbtienda"];
		$ls_codcaja=$_POST["cmbcaja"];
	}
	else
	{
		$operacion=="";
		$ls_codtienda="";
		$ls_codcaja="";
	}
	if($operacion=="PROCESAR")
	{
		$ls_codusu=$_SESSION["la_logusr"];
		if($ls_codcaja!="" && $ls_codcaja!="T")
		{
			$ls_select=" ,p.codemp,p.cod_caja,p.descripcion_caja,p.precot,p.prefac,p.predev,p.sercot,p.serfac,p.serdev,p.sernot, ".
				   "		 p.sercon,p.formalibre,p.precob,p.sercob,p.preped,p.serped ,p.preordent,p.serordent,p.formalibreordent,c.codusu ";
			$ls_from=" ,sfc_caja p,sfc_cajero c ";
			$ls_where=" AND c.codusu='".$ls_codusu."' AND p.cod_caja='".$ls_codcaja."' AND p.codtiend=c.codtiend AND p.cod_caja=c.cod_caja AND p.codtiend=t.codtiend  ";
		}
		elseif($ls_codcaja=="T")
		{
			$ls_select=" ";
			$ls_from="  ";
			$ls_where=" ";			
		}		
		else
		{
			$ls_select=" ";
			$ls_from="  ";
			$ls_where=" ";
		}
		$ls_cadena="SELECT t.* ".$ls_select.
				   "  FROM sfc_tienda t ".$ls_from.
				   " WHERE t.codtiend='".$ls_codtienda."' ".$ls_where;
		$rs_data=$io_sql->select($ls_cadena);
		if($row=$io_sql->fetch_row($rs_data))
		{
			   $_SESSION["ls_codtienda"] = $row["codtiend"];
			   $_SESSION["ls_nomtienda"] = $row["dentie"];
			   $_SESSION["ls_codcaj"]	 = $ls_codcaja;
			   $_SESSION["ls_item"]  	 = $row["item"];
			   $_SESSION["ls_precot"]	 = $row["precot"];
			   $_SESSION["ls_prefac"]	 = $row["prefac"];
			   $_SESSION["ls_predev"]	 = $row["predev"];
			   $_SESSION["ls_precob"]	 = $row["precob"];
			   $_SESSION["ls_sercot"] 	 = $row["sercot"];
			   $_SESSION["ls_serfac"]	 = $row["serfac"];
			   $_SESSION["ls_serdev"]	 = $row["serdev"];
			   $_SESSION["ls_sernot"]	 = $row["sernot"];
			   $_SESSION["ls_sercob"]	 = $row["sercob"];
			   $_SESSION["ls_codest"]	 = $row["codest"];
			   $_SESSION["ls_codmun"]    = $row["codmun"];
			   $_SESSION["ls_codpar"]    = $row["codpar"];
			   $_SESSION["ls_coduniad"]  = $row["coduniadm"];
			   $_SESSION["ls_formalibre"]= $row["formalibre"];
			   $_SESSION["ls_spicuenta"] = $row["spi_cuenta"];
			   $_SESSION["ls_preordent"] = $row["preordent"];
			   $_SESSION["ls_formalibreordent"]=$row["formalibreordent"];
			   $_SESSION["ls_serordent"] = $row["serordent"];
			   if ( $_SESSION["ls_formalibre"]=='S')
			   {
					$_SESSION["ls_sercon"]=$row["sercon"];
			   }
			   print "<script language=JavaScript>";
			   print "	opener.location.href='index_modules_comercializacion.php';";
			   print "  close();";
			   print "</script>";				
		}
		else
		{
			$msg->message("No tiene acceso al sistema segun los parametros. \\n Si no se han creado las cajas indique al administrador del Sistema");
		}
	}
?>
<form name="form1" method="post" action="">
  <table width="581" height="401" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="43" valign="top"><div align="center" class="estilo_titulo">HUAYRA</div></td>
    </tr>
    <tr>
      <td width="649" height="221" valign="top" class="fondo"><p>&nbsp;</p>
        <p align="center" class="Estilo6" >Seleccione el Punto de Venta a Utilizar </p>
        <table width="348" border="0" align="center" cellpadding="0" cellspacing="0" class="fondo_contenido">
            
            <tr align="right" valign="top" class="formato-blanco">
              <td width="329" height="206" valign="middle"><label></label>
                <table width="348" border="0" align="center" cellpadding="0" cellspacing="0">
                  
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="right"><span class="Estilo8">Sucursal: </span></div></td>
                    <td height="27">                   
						<select name="cmbtienda" onChange="javascript:ue_cambiar_caja();">
							<option value="" selected>Seleccione...</option>
							<?php
							$ls_sql="SELECT * FROM sfc_tienda ORDER BY codtiend";	
							$rs_data_tienda=$io_sql->select($ls_sql);
							while($row=$io_sql->fetch_row($rs_data_tienda))
							{
								$ls_tienda=$row["codtiend"];
								if($ls_tienda==$ls_codtienda )
								{
									$selected="selected";
								}
								else
								{
									$selected="";
								}
								print "<option value='".$ls_tienda."' ".$selected.">".$row["dentie"]."</option>";		
							}
							?>
						</select>
                   </td>
                  </tr>
                  <tr>
                    <td><div align="right"><span class="Estilo8">Caja:</span></div></td>
                    <td height="27"><div><span class="selectBox"><input name="OPERACION" type="hidden" id="OPERACION" value="<?php $_REQUEST["OPERACION"] ?>">
                      </span>
                        <select name="cmbcaja" onChange="javascript:ue_seleccionar_caja();">
                          <option value="">Seleccione....</option>
						  <option value="T">Todas</option>
                          <?php
							$ls_sql="SELECT * FROM sfc_caja WHERE codtiend='".$ls_codtienda."' ORDER BY cod_caja";	
							$rs_data_caja=$io_sql->select($ls_sql);
							while($row=$io_sql->fetch_row($rs_data_caja))
							{
								$ls_caja=$row["cod_caja"];
								$ls_dencaja=$row["descripcion_caja"];
								if($ls_caja==$ls_codcaja )
								{
									print "<option value='".$ls_caja."' selected>".$ls_dencaja."</option>";
								}
								else
								{
									print "<option value='".$ls_caja."'>".$ls_dencaja."</option>";
								}
							}							
						?>
                        </select>
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27"><div id="buttom-box" class="Estilo8">
                      <a href="#" onClick="javascript:uf_procesar();" class="button_aceptar"></a>
                      <!--<input name="Button" type="button" value="Aceptar" onClick="javascript:uf_selempresa();">-->
                    </div></td>
                  </tr>
              </table></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </td>
    </tr>
  </table>
  <label></label>
</form>
<div class="pie-pagina">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

</body>
<script language="javascript">
function ue_cambiar_caja()
{
	f=document.form1;
	ls_tienda=f.cmbtienda.value;
	if(ls_tienda!="")
	{
		f.OPERACION.value="CAMBIAR_CAJA";
		f.action="sigesp_cat_tienda_caja.php";
		f.submit();
	}
}

function ue_seleccionar_caja()
{
	f=document.form1;
	ls_tienda=f.cmbtienda.value;
	ls_caja=f.cmbcaja.value;
	if(ls_tienda!="" && ls_caja!="")
	{
		f.OPERACION.value="PROCESAR";
		f.action="sigesp_cat_tienda_caja.php";
		f.submit();
	}
}

function uf_procesar()
{
	f=document.form1;
	ls_tienda=f.cmbtienda.value;
	if(ls_tienda!="")
	{
		if(confirm("El no seleccionar la caja solo le dara acceso al modulo de Inventario, desea Conitnuar?"))
		{
			f.OPERACION.value="PROCESAR";
			f.action="sigesp_cat_tienda_caja.php";
			f.submit();
		}
	}
}
</script>
</html>
