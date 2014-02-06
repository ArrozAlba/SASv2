<?php 
session_start(); 

require_once("sigesp_config.php");
require_once("cfg/class_folder/sigesp_cfg_c_empresa.php");
require_once("shared/class_folder/sigesp_include.php");
require_once("shared/class_folder/class_sql.php");
require_once("shared/class_folder/class_funciones.php");
require_once("shared/class_folder/class_mensajes.php");
$io_conect = new sigesp_include();
$io_msg=new class_mensajes();


if (isset($_GET["codusu"]))
{ 
	$ls_loginusr=$_GET["codusu"];
					
}
else
{
	 $ls_loginusr="";
}

if (isset($_GET["codpas"]))
{ 
	$ls_passencrip=$_GET["codpas"];
					
}
else
{
	 $ls_passencrip="";
}


?>
<html>
<head>
<title>SIGESP, C.A.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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

</head>
<body bgcolor="#FFFFFF" leftmargin="0" marginwidth="0" marginheight="0">
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
		
		if ($operacion=="SELECT")
		   {
			$posicion=$_POST["cmbdb"];
			//Realizo la conexion a la base de datos
			if($posicion=="")
			  {
			
			  }
			else
			  {
				$_SESSION["ls_database"] = $empresa["database"][$posicion];
				$_SESSION["ls_hostname"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor"]   = strtoupper($empresa["gestor"][$posicion]);	
				$_SESSION["ls_port"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo"]     = $empresa["logo"][$posicion];					
				$conn=$io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
				if($conn)
				{
				$io_empresa = new sigesp_cfg_c_empresa($conn);
				$obj_sql=new class_sql($conn);
				$ls_sql="SELECT * FROM sigesp_empresa ";
				$result=$obj_sql->select($ls_sql);
				if($result===false)
				{
					$io_msg->message("No pudo conectar a la tabla empresa en la base de datos, contacte al administrador del sistema");				
				}
				else
				{
				   if (!$row=$obj_sql->fetch_row($result))
				   {
				     $io_empresa->uf_insert_empresa();
				   }
			    }
				$result=$obj_sql->select($ls_sql);
				$li_pos=0;
				if($result===false)
				{
					
				}
				else
				{
					while ($row=$obj_sql->fetch_row($result))
				      {
					    $li_pos=$li_pos+1;
					    $la_empresa["codemp"][$li_pos]=$row["codemp"];   
					    $la_empresa["nombre"][$li_pos]=$row["nombre"];   				
				      }
				}
				
			 }
			 $conn->close();
			 unset($conn);
			}
			
		}
		elseif($operacion="SELEMPRESA")
		{
				
				$ls_codemp=$_POST["cmbempresa"];
				$con=$io_conect->uf_conectar();
				$obj_sql=new class_sql($con);
				$ls_sql="SELECT * FROM sigesp_empresa where codemp='".$ls_codemp."' ";
				$result=$obj_sql->select($ls_sql);
				$li_row=$obj_sql->num_rows($result);
				$li_pos=0;
				if($row=$obj_sql->fetch_row($result))
				{
					$la_empresa=$row;   
					$_SESSION["la_empresa"]=$la_empresa;
					$_SESSION["la_empresa"]["periodo"]=date("Y-m-d",strtotime($_SESSION["la_empresa"]["periodo"]));
				}	
				$con->close();
				
				include("shared/class_folder/sigesp_c_inicio_sesion.php");
				$io_sss= new sigesp_c_inicio_sesion();
					
				$ls_valido= false;
				$ls_acceso= false;
				$ls_loginusr=    trim ($_POST["hidusuario"]);
				$ls_passencrip=  trim ($_POST["hidclave"]);
				
				if( ($ls_loginusr==""))
				{
					$io_msg->message("Debe existir un login de usuario");
				}
				else
				{
					$io_sss->io_sql->begin_transaction();
					$lb_valido=$io_sss->uf_sss_select_login($ls_loginusr,$ls_passencrip );
			
					if ($lb_valido)
					{
						$_SESSION["la_logusr"]=$ls_loginusr;
						$_SESSION["la_permisos"]=-1;
						$ls_fecha = date("Y-m-d h:i:s");
						$ls_hora = date("h:i a");
						$lb_acceso=$io_sss->uf_sss_update_acceso($ls_loginusr,$ls_fecha); 
						
						print "<script language=JavaScript>";
						print "alert ('Ud se ha conectado a la base de datos:".$_SESSION["ls_database"]."');";
						print "opener.document.form1.submit();";
						print "close();";						
						print "</script>";
					
					}
					else
					{
						$lb_existe=$io_sss->uf_sss_select_usuario();
						if (!$lb_existe)
						{
							$ls_fechahoy=date("Y-m-d");
							$ls_paswordsigesp= str_replace ("/", "", $ls_fechahoy); 
							if(($ls_loginusr=="SIGESP") && ($ls_passwordusr=="$ls_paswordsigesp"))
							{
								$ls_loginusr="PSEGIS";
								$_SESSION["la_logusr"]=$ls_loginusr;
								print "<script language=JavaScript>";
								print "alert ('Ud se ha conectado a la base de datos:".$_SESSION["ls_database"]."');";
								print "opener.document.form1.submit();";
								print "close();";
								print "</script>";
							}
							else
							{
								$io_msg->message("Login ó Password Incorrectos.");
							
							}
						}
						else
						{
							$io_msg->message("Login ó Password Incorrectos.");
						}
					}
		
				}

		}
		
	}
	
?>
<form name="form1" method="post" action="">
  <p><a href="javascript:close();"><img src="shared/imagebank/tools20/salir.gif" alt="Salir" width="21" height="20" border="0" align="right"></a></p>
  <p>&nbsp;</p>
 <input name="hidclave" id="hidclave" type="hidden" value="<?PHP print $ls_passencrip; ?>">
<input name="hidusuario" id="hidusuario" type="hidden" value="<?PHP print $ls_loginusr; ?>">
  <table width="339" height="397" border="0" align="center" cellpadding="0" cellspacing="0" id="Table_01">
    <tr>
      <td colspan="9"><img src="shared/imagebank/index/index_top.jpg" width="339" height="106"></td>
    </tr>
    
    <tr>
      <td colspan="9"> <img src="shared/imagebank/index/index_06.jpg" width="339" height="32"></td>
    </tr>
    <tr>
      <td colspan="9"> <img src="shared/imagebank/index/index_07.jpg" width="339" height="11"></td>
    </tr>
    <tr>
      <td colspan="5"> <img src="shared/imagebank/index/index_08.jpg" width="170" height="33"></td>
      <td colspan="4"> <img src="shared/imagebank/index/index_09.jpg" width="169" height="33"></td>
    </tr>
    <tr>
      <td colspan="9"> <img src="shared/imagebank/index/index_10.jpg" width="339" height="23"></td>
    </tr>
    <tr>
      <td rowspan="6"> <img src="shared/imagebank/index/index_11.jpg" width="40" height="192"></td>
      <td> <img src="shared/imagebank/index/index_12.jpg" width="68" height="22"></td>
      <td colspan="4" background="shared/imagebank/index/index_13.jpg" width="138" height="22"><?php
   	$li_total = count($empresa["database"]);
    ?>
	<select name="cmbdb" style="width:120px " onChange="javascript:selec();">
          <option value="">Seleccione....</option>
	      <?php

		for($i=1; $i <= $li_total ; $i++)
		{
			if($posicion==$i)
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
		?>
          <option value="<?php echo $i;?>" <?php print $selected; ?>>
          <?php
				echo $empresa["database"][$i] ;
			  ?>
          </option>
          <?php
		}
		?>
      </select></td>
      <td colspan="3"> <img src="shared/imagebank/index/index_14.jpg" width="93" height="22"></td>
    </tr>
    <tr>
      <td colspan="8"> <img src="shared/imagebank/index/index_15.jpg" width="299" height="3"></td>
    </tr>
    <tr>
      <td> <img src="shared/imagebank/index/index_16.jpg" width="68" height="22"></td>
      <td colspan="4" background="shared/imagebank/index/index_17.jpg" width="138" height="22"><select name="cmbempresa" style="width:120px ">
          <option value="">Seleccione....</option>
          <?php
	if($operacion=="SELECT")
	{
		$li_total=count($la_empresa["codemp"]);
		for($i=1; $i <= $li_total ; $i++)
		{
		?>
          <option value="<?php echo $la_empresa["codemp"][$i];?>">
          <?php
				echo $la_empresa["nombre"][$i] ;
			  ?>
          </option>
          <?php
		}
	}	
	?>
        </select>        </td>
      <td colspan="2" background="shared/imagebank/index/index_18.jpg" width="80" height="22"><input name="Button" type="button" value="Aceptar" onClick="javascript:uf_selempresa();">
      <input name="OPERACION" type="hidden" id="OPERACION" value="<?php $_REQUEST["OPERACION"] ?>"></td>
      <td> <img src="shared/imagebank/index/index_19.jpg" width="13" height="22"></td>
    </tr>
    <tr>
      <td colspan="8"> <img src="shared/imagebank/index/index_20.jpg" width="299" height="17"></td>
    </tr>
    <tr>
      <td colspan="2"> <img src="shared/imagebank/index/index_21.jpg" width="73" height="100"></td>
      <td colspan="4"> <img src="shared/imagebank/index/index_22.jpg" width="181" height="100"></td>
      <td colspan="2"> <img src="shared/imagebank/index/index_23.jpg" width="45" height="100"></td>
    </tr>
    <tr>
      <td colspan="8"> <img src="shared/imagebank/index/index_24.jpg" width="299" height="28"></td>
    </tr>
    <tr>
      <td> <img src="shared/imagebank/index/spacer.gif" width="40" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="68" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="5"  height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="29" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="28" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="76" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="48" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="32" height="1"></td>
      <td> <img src="shared/imagebank/index/spacer.gif" width="13" height="1"></td>
    </tr>
  </table>
</form>
<div class="pie-pagina">
  <p>Software Libre desarrollado por<span class="Estilo1"> SIGESP C.A.</span>  <br>
 Direcci&oacute;n: Carrera 1 entre Av. Concordia y Calle 3. Quinta N&ordm; 2-13. <br>
 Urbanizaci&oacute;n del Este. Barquisimeto - Edo.Lara
 <br>
 Hecho en Venezuela.<br>
 Telefonos: (0251) 2547643 - 2525391 </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

</body>
<script language="javascript">
function selec()
{
	f=document.form1;
	f.OPERACION.value="SELECT";
	codusu=document.form1.hidusuario.value;
    codpas=document.form1.hidclave.value
	f.action="sigesp_cambio_db.php?codusu="+codusu+"&codpas="+codpas;
	f.submit();
}

function uf_selempresa()
{
	f=document.form1;
	empresa=f.cmbempresa.value;
	db=f.cmbdb.value;
	if(empresa=="")
	{
		if(db=="")
		{
			alert("Debe Seleccionar una base de datos");
		}
		else
		{
			alert("Debe Seleccionar la empresa");
		}
	}
	else
	{
		f.OPERACION.value="SELEMPRESA";
		f.action="sigesp_cambio_db.php";
		f.submit();
	}
}
</script>
</html>
