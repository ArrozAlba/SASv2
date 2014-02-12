<?php 
session_start(); 
require_once("sigesp_config.php");
require_once("shared/class_folder/class_sql.php");
require_once("cfg/class_folder/sigesp_cfg_c_empresa.php");
require_once("shared/class_folder/sigesp_include.php");
require_once("shared/class_folder/class_sql.php");
require_once("shared/class_folder/class_funciones.php");
require_once("shared/class_folder/class_mensajes.php");
$io_conect = new sigesp_include();
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
		
		if ($operacion=="SELECT")
		   {
			$posicion=$_POST["cmbdb"];
			//Realizo la conexion a la base de datos
			if($posicion=="")
			  {
			
			  }
			else
			  {
				$_SESSION["ls_nombrelogico"] = $empresa["nombre_logico"][$posicion];
				$_SESSION["ls_database"] = $empresa["database"][$posicion];
				$_SESSION["ls_hostname"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor"]   = strtoupper($empresa["gestor"][$posicion]);	
				$_SESSION["ls_port"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo"]     = $empresa["logo"][$posicion];	
				$_SESSION["gi_posicion"] = $posicion;	
				$conn=$io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
				if($conn)
				{
				$io_empresa = new sigesp_cfg_c_empresa($conn);
				$obj_sql=new class_sql($conn);
				$ls_sql="SELECT * FROM sigesp_empresa ";
				$result=$obj_sql->select($ls_sql);
				if($result===false)
				{
					$msg->message("No pudo conectar a la tabla empresa en la base de datos, contacte al administrador del sistema");				
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
				$_SESSION['sigesp_sitioweb']=$_SESSION["la_empresa"]["dirvirtual"];
				$_SESSION['sigesp_servidor']=$_SESSION["ls_hostname"];
				$_SESSION['sigesp_usuario']=$_SESSION["ls_login"];
				$_SESSION['sigesp_clave']=$_SESSION["ls_password"];
				$_SESSION['sigesp_basedatos']=$_SESSION["ls_database"];
				$_SESSION['sigesp_gestor']=$_SESSION["ls_gestor"];
				
				$_SESSION['sigesp_servidor_apr']=$_SESSION["ls_hostname"];
				$_SESSION['sigesp_usuario_apr']=$_SESSION["ls_login"];
				$_SESSION['sigesp_clave_apr']=$_SESSION["ls_password"];
				$_SESSION['sigesp_basedatos_apr']=$_SESSION["ls_database"];
				$_SESSION['sigesp_gestor_apr']=$_SESSION["ls_gestor"];

				//$a=$_SESSION["la_empresa"];
				print "<script language=JavaScript>";
				print "location.href='sigesp_inicio_sesion.php'" ;
				print "</script>";
			}
		}
		
	}
	else
	{ 
		$operacion="";
		$arr=array_keys($_SESSION);	
		$li_count=count($arr);
		for($i=0;$i<$li_count;$i++)
		{
			$col=$arr[$i];
			unset($_SESSION["$col"]);
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
        <p align="center" class="Estilo6" >INGRESO AL SISTEMA</p>
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
                    <td><div align="right"><span class="Estilo8">Base de Datos: </span></div></td>
                    <td height="27">
                    <div class="selectBox">
	
	<?php
   	$li_total = count($empresa["database"]);
    ?>
   
    <select name="cmbdb" onChange="javascript:selec();">
		<option value="0">Seleccione...</option>
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
        <option value="<?php echo $i;?>" <?php echo $selected; ?>>
                        <?php
				echo $empresa["nombre_logico"][$i] ;
			  ?>
                        </option>
                        <?php
		}
		?>
	</select>
</div>                    </td>
                  </tr>
                  <tr>
                    <td><div align="right"><span class="Estilo8">Empresa: </span></div></td>
                    <td height="27"><div class="selectBox"><input name="OPERACION" type="hidden" id="OPERACION" value="<?php $_REQUEST["OPERACION"] ?>">
                      <select name="cmbempresa">
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
                      </select></div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="27"><div id="buttom-box" class="Estilo8">
                      <a href="#" onClick="javascript:uf_selempresa();" class="button_aceptar"></a>
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
function selec()
{
	f=document.form1;
	f.OPERACION.value="SELECT";
	f.action="sigesp_conexion.php";
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
		f.action="sigesp_conexion.php";
		f.submit();
	}
}
</script>
</html>
