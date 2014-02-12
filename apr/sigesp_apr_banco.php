<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_apr.php");
	$io_fun_apr=new class_funciones_apr();
	$io_fun_apr->uf_load_seguridad("APR","sigesp_apr_banco.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="resultado";
	@mkdir($ls_ruta,0755);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Traslado de Saldos y Movimientos en Tr&aacute;nsito</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 15px}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Transferir" width="20" height="20" border="0"></a><a href="javascript: ue_descargar('<?PHP print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" alt="Salir" width="20" height="20" border="0"></a><a href="../apr/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php 
require_once("sigesp_apr_salctabco.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_conect    = new sigesp_include();//Instanciando la Sigesp_Include.
$conn         = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql       = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_salctabco = new sigesp_apr_salctabco();//Instanciando la Clase Sigesp Definiciones.
$io_msg       = new class_mensajes();

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
     $ls_cuenta    = $_POST["cmbcuentas"];
	 $ls_fecfin    = $_POST["txtfecfin"];
	 $ls_fecini    = $_POST["txtfecini"];
	 if(array_key_exists("chkmovtran",$_POST))
		$lb_chk=true;
	 else
		$lb_chk=false;
   }
else
   {
     $ls_operacion = "NUEVO";
     $ls_banco     = "";
	 $ls_fecfin    = "";
	 $ls_fecini    = "";
	 $lb_chk       = false;
   }
if (array_key_exists("cmbbancos",$_POST))
   {
	 $ls_banco = trim($_POST["cmbbancos"]);
   }
else
   {
     $ls_banco = "-";
   }
if (empty($ls_banco) || $ls_banco=='-')
   {
     $ls_disabled = "disabled";
   }
else
   {
     $ls_disabled = "";
   }
$arremp    = $_SESSION["la_empresa"];
$ls_codemp = $arremp["codemp"];

if ($ls_operacion=="PROCESAR")
   {
	 $lb_valido=$io_salctabco->uf_trasladar_saldos($ls_fecfin,$ls_fecini,$ls_banco,$ls_cuenta,$lb_chk);
	 if ($lb_valido)
	    {
		  $io_msg->message("Los movimientos fueron trasladados satisfactoriamente !!!");
		  $io_salctabco->io_sql_destino->commit();
	    }
	 else
	    {
		  $io_msg->message("Ocurrio un error, revise los archivos de texto en la carpeta 'resultados'");
		  $io_salctabco->io_sql_destino->rollback();
	    }
   }
?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_apr->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'"); 
	unset($io_fun_apr);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="542" height="330" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="517" height="328"><div align="center">
        <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
          <tr class="titulo-celdanew">
            <td height="22" colspan="2">Traslado de Saldos y Movimientos en Tr&aacute;nsito </td>
          </tr>
          <tr>
            <td width="122" height="13" >&nbsp;</td>
            <td width="346" height="13" >&nbsp;</td>
          </tr>
          <tr>
            <td height="22" colspan="2" ><strong>Cuentas Disponibles </strong>
              <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>"></td>
            </tr>
          <tr>
            <td height="22" colspan="2" ><div align="center">
              <table width="448" border="0" align="left" class="formato-blanco">
                <tr>
                  <td width="81"><div align="right">Banco</div></td>
                  <td width="355"><label>
                    <div align="left">
                      <select name="cmbbancos" id="cmbbancos" style="width:200px " onChange="javascript:uf_cambio_banco();">
                        <option value="-">---seleccione---</option>
                        <?php
						  $rs_bancos = $io_salctabco->uf_select_banco($ls_codemp);
						  while ($row=$io_sql->fetch_row($rs_bancos))
						        {
						          $ls_codban = trim($row["codban"]);
						          $ls_nomban = $row["nomban"];
						          if ($ls_codban==$ls_banco)
							         {
								       echo "<option value='$ls_codban' selected>$ls_nomban</option>";
							         }
						          else
							         {
								       echo "<option value='$ls_codban'>$ls_nomban</option>";
							         }
						        } 
					      $io_sql->free_result($rs_bancos);
					  ?>
                      </select>
                    </div>
                  </label></td>
                  </tr>
                <tr>
                  <td><div align="right">Cuenta</div></td>
                  <td><label>
                    <div align="left">
				      <select name="cmbcuentas" id="cmbcuentas" style="width:200px " <?php echo $ls_disabled ?>>
                        <option value="-">---seleccione---</option>
					<?php
					$rs_cuentas = $io_salctabco->uf_select_cuentas($ls_codemp,$ls_banco);
					while ($row=$io_sql->fetch_row($rs_cuentas))
		                  {
		                    $ls_codban = $row["codban"];
		                    $ls_ctaban = $row["ctaban"];
		                    if ($ls_ctaban==$ls_cuenta)
			                   {
				                 echo "<option value='$ls_ctaban' selected>$ls_ctaban</option>";
			                   }
		                    else
			                   {
				 				 echo "<option value='$ls_ctaban'>$ls_ctaban</option>";
			                   }
		                  }  
					$io_sql->free_result($rs_cuentas);
					?>
                      </select>
                      <input name="hidcodbanco" type="hidden" id="hidcodbanco" value="<?php print $ls_banco ?>">
                    </div>
                  </label></td>
                  </tr>
              </table>
            </div></td>
            </tr>
          <tr>
            <td height="13" colspan="2" >&nbsp;</td>
            </tr>
          <tr>
            <td height="22" colspan="2" ><div align="left"><strong>Datos a Transferir </strong></div></td>
            </tr>
          <tr>
            <td height="22" colspan="2"><div align="center">
              <table width="447" border="0" align="left" class="formato-blanco">
                <tr>
                  <td width="307"><label>
				  <?php
				  if($lb_chk==true)
				  {
				  ?>
                    <input name="chkmovtran" type="checkbox" id="chkmovtran" value="1" checked="checked">
				  <?PHP
				  }
				  	else
					{
					?>
					<input name="chkmovtran" type="checkbox" id="chkmovtran" value="1">
					<?php
					}
					?>
                  Movimientos en Tr&aacute;nsito. </label></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Fecha Final Periodo Anterior 
                    <label>
                    <input name="txtfecfin" type="text" id="txtfecfin" size="15" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" style="text-align:center" value="<?PHP print $ls_fecfin?>">
                    </label></td>
                </tr>
                <tr>
                  <td>Fecha Inicial Nuevo Periodo&nbsp; 
                    <input name="txtfecini" type="text" id="txtfecini" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" size="15" datepicker="true" style="text-align:center" value="<?PHP print $ls_fecini?>"></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr> 
            <td height="22" colspan="2">&nbsp;</td>
            </tr>
        </table>
      </div></td>
    </tr>
  </table>
  </div>
    </table>
  </div>
</form>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
f = document.form1;

function ue_descargar(ruta)
{
	window.open("sigesp_apr_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}

function uf_cambio_banco()
{ 
    f.hidcodbanco.value = '-';
	ls_codban = f.cmbbancos.value;
	if (ls_codban!="" && ls_codban!='-')
	   {
     	 f.hidcodbanco.value   = ls_codban;
		 f.cmbcuentas.disabled = false;
	     f.action              = "sigesp_apr_banco.php";
	     f.submit();
	   }
	else
	   {
	     f.hidcodbanco.value = '-';
		 f.cmbcuentas.value = '-';
		 f.cmbcuentas.disabled = true;
	   }
}

function ue_procesar()
{
	f=document.form1;
	ls_banco=f.cmbbancos.value;
	ls_cuenta=f.cmbcuentas.value;
	ls_fecfin=f.txtfecfin.value;
	ls_fecini=f.txtfecini.value;
	if (ls_banco!="-")
	   {
	     if (ls_cuenta!="-")
		    {
		      if (ls_fecfin!="")
				 {
				   f.operacion.value="PROCESAR";
				   f.submit();
				 }
			  else
				 {
				   alert("Por Favor Indique Fecha Final del Periodo Anterior !!!");
				   f.txtfecfin.focus();
				 }
			}
	     else
		    {
			  alert("Por Favor seleccione una Cuenta Bancaria !!!");
			}
	   }
	else
	   {
	     alert("Por Favor seleccione un Banco !!!");
	   }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>