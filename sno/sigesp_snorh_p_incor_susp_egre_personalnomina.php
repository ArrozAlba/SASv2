<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_incor_susp_egre_personalnomina.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Incorporar, Suspender Personal en Lote de la Nomina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
}
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../swap/js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
	<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
      </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("sigesp_snorh_c_incor_susp_egre_personal.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones.php");
	$io_ds_per=new class_datastore();
	$io_msg=new class_mensajes();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_sql=new class_sql($io_connect);
	$io_grid=new grid_param();
	$io_c_incorsuspegr= new sigesp_snorh_c_incor_susp_egre_personal();
	$io_function=new class_funciones();
	$ds=null;

if( array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
   $ls_operacion="";
}	

if(array_key_exists("cmbnomina",$_POST))
{
  $ls_cmbnomina=$_POST["cmbnomina"];
}
else
{
  $ls_cmbnomina="s1";
}			

if(array_key_exists("cmbcauegrper",$_POST))
{
  $ls_cmbcauegrper=$_POST["cmbcauegrper"];
}
else
{
  $ls_cmbcauegrper="s1";
}			

if(array_key_exists("txtfecsusper",$_POST))
{
  $ldt_fecsusper=$_POST["txtfecsusper"];
}
else
{
  $ldt_fecsusper="";
}			

//Radio Button  Status del Personal
if  (array_key_exists("staper",$_POST))
	{
	  $ls_staper=$_POST["staper"];
    }
else
	{
	  $ls_staper="";
	}	
?>
<p>&nbsp;</p>
<div align="center">
<form name="form1" method="post" action="">
		<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>

  <table width="550" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="583" valign="top">
		  <p>&nbsp;</p>
		  <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="5"><div align="center">Incorporar, Suspender Personal  en Lote de la Nomina</div></td>
              </tr>
              <tr >
                <td height="22">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td width="147" height="22"><div align="right" >
                    <p>Nomina</p>
                </div></td>
                <td colspan="4"><div align="left" >
				 <?php
				  $rs_data=0;
				  $lb_valido=$io_c_incorsuspegr->uf_llenarcombo_nomina($rs_data);
			     ?>
                  <select name="cmbnomina" id="cmbnomina" onChange="uf_cargargrid()">
					 <option value="s1">Seleccione una Nomina</option>
					 <?php
					 if($lb_valido)
					 {
						  while($row=$io_sql->fetch_row($rs_data))
						  {
							  $ls_codnom=$row["codnom"];
							  $ls_desnom=$row["desnom"];
							  if ($ls_codnom==$ls_cmbnomina)
							  {
								print "<option value='$ls_codnom' selected>$ls_desnom</option>";
							  }
							  else
							  {
							   print "<option value='$ls_codnom'>$ls_desnom</option>";
							  }
						 } 
					}	 
			      ?>
                  </select>
</div></td>
              </tr>
              <tr >
                <td height="22"><div align="right">Estatus</div></td>
                <td width="21">
				<?php
					if(($ls_staper=="4")||($ls_staper==""))
					{
					   $ls_incorporar="checked";
					   $ls_suspender="";
					}  
					else
					{   
					   $ls_incorporar="";
					   $ls_suspender="checked";
					}
					
				?>		 
                  <div align="right">
                    <input name="staper" type="radio" value="4"  onClick="uf_change_radio()" <?php print $ls_incorporar ?>>
                  </div></td>
                <td width="52">Incorporar</td>
                <td width="20"><input name="staper" type="radio" value="1" onClick="uf_change_radio()" <?php print $ls_suspender ?>></td>
                <td width="245"><div align="left">Suspender</div>                  <div align="right">
                  </div>                  <div align="left"></div></td>
              </tr>
              <tr>
                <td height="22">			     
                  <?php
				  if($ls_staper=="1")
				  {
				    $ls_susp="Fecha de Suspensión";
				 }
				 else
				 {
				    $ls_susp="Fecha de Incorporación";
				 }
				?>                  
                  <div align="right">
                    <input name="txtsusp" type="text" class="sin-borde" style="text-align:right" id="txtsusp" value="<?php print $ls_susp; ?>" size="23" maxlength="23">                  
                </div></td>
                <td colspan="4">
                  <div align="left">
                    <input name="txtfecsusper" type="text" id="txtfecsusper" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ldt_fecsusper ; ?>" size="15" maxlength="15" datepicker="true">
</div></td>
              </tr>
            <tr>
              <td height="22" colspan="5"><div align="right"></div>                <div align="center">
                <?php	
/*************************************************Titulos de la tabla*******************************************************************/
  
  $title[1]="Seleccionado";   $title[2]="Personal";   $title[3]="C&eacute;dula";  $title[4]="Apellido";  $title[5]="Nombre"; $ls_nombre="grid";
  
/***********************************************************" "************************************************************************/	
if ($ls_operacion=="")
{
   $li_total=0;
   $object="";
   $io_grid->makegrid($li_total,$title,$object,200,'Personal',$ls_nombre);     
}
/***********************************************************CARGAR************************************************************************/	
	if ($ls_operacion=="CARGAR")
	{
      $rs_data=0;
      $ls_staper=$_POST["staper"];	
	  $lb_valido=$io_c_incorsuspegr->uf_load_grid_personalnomina($ls_staper,$ls_cmbnomina,$rs_data);
	  if($lb_valido)
	  {
		  if($rs_data===false)
		  {
			$this->io_msg->message("Ventana-> Ventana Incorporaci&oacute;n del Personal M&Eacute;TODO->Operacion Nuevo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  }
		  else
		  {
			   if($row=$io_sql->fetch_row($rs_data))
			   {
				   $data=$io_sql->obtener_datos($rs_data);
				   $io_ds_per->data=$data;
				   $li_num=$io_ds_per->getRowCount("codper");
				   $li_totnum=$li_num;
				   for($i=1;$i<=$li_num;$i++)
				   { 
						$ls_codper=$data["codper"][$i];  
						$ls_cedper=$data["cedper"][$i];
						$ls_apeper=$data["apeper"][$i];
						$ls_nomper=$data["nomper"][$i];
						$li_staper=$data["staper"][$i];
						
						$check="<input type=checkbox name=chkselec".$i." id=chkselec value=1 class=sin-borde >";
						$object[$i][1]=$check;
						$object[$i][2]="<input type=text name=txtcodper".$i." value=$ls_codper class=sin-borde  size=10 style=text-align:center readonly>";
						$object[$i][3]="<input type=text name=txtcedper".$i." value=$ls_cedper class=sin-borde  size=15 style=text-align:center readonly>";
						$object[$i][4]="<input type=text name=txtapeper".$i." value=$ls_apeper class=sin-borde  size=15 style=text-align:center readonly>";
						$object[$i][5]="<input type=text name=txtnomper".$i." value=$ls_nomper class=sin-borde  size=15 style=text-align:center readonly>";
				   }//for   
				   $io_grid->makegrid($li_totnum,$title,$object,200,'Personal',$ls_nombre);   
			   }
			   else
			   {
				   $li_total=0;
				   $object="";
				   $io_grid->makegrid($li_total,$title,$object,200,'Personal',$ls_nombre);     
			  }   
		   }  
		}
		else
		{
			$this->io_msg->message("Ventana-> Ventana Incorporaci&oacute;n del Personal M&Eacute;TODO->Operacion Nuevo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
	}
/*********************************************************** PROCESAR ****************************************************************/	
if($ls_operacion=="PROCESAR")
{  
   $li_totnum=$_POST["totnum"];
   $li_num=$li_totnum;
   if($ls_staper==4)
   {
	 $ls_staper=1;   
   }
   elseif($ls_staper==1)
   {
	  $ls_staper=4; 
   }
   for($i=1;$i<=$li_num;$i++)
   { 
		$ls_codper=$_POST["txtcodper".$i];  
		$ls_cedper=$_POST["txtcedper".$i];
		$ls_apeper=$_POST["txtapeper".$i];
		$ls_nomper=$_POST["txtnomper".$i];
		if(array_key_exists("chkselec".$i,$_POST))
		{
			if($_POST["chkselec".$i]==1)
			{
			   $ldt_fecsusper=$io_function->uf_convertirdatetobd($ldt_fecsusper);
			   $lb_valido=$io_c_incorsuspegr->uf_update_status_personalnomina($ls_cmbnomina,$ls_staper,$ldt_fecsusper,$ls_codper,
			                                                                   $la_seguridad);
			}
		}
   }//for 
	if($lb_valido)
	{  
	  $io_msg->message(" Registro Actualizado ");
      $rs_data=0;
      $ls_staper=$_POST["staper"];	
	  $lb_valido=$io_c_incorsuspegr->uf_load_grid_personalnomina($ls_staper,$ls_cmbnomina,$rs_data);
	  if($lb_valido)
	  {
		  if($rs_data===false)
		  {
			$this->io_msg->message("Ventana-> Ventana Incorporaci&oacute;n del Personal M&Eacute;TODO->Operacion Nuevo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  }
		  else
		  {	 
			   if($row=$io_sql->fetch_row($rs_data))
			   {	  
				   $data=$io_sql->obtener_datos($rs_data);
				   $io_ds_per->data=$data;
				   $li_num=$io_ds_per->getRowCount("codper");
				   $li_totnum=$li_num;
				   for($i=1;$i<=$li_num;$i++)
				   { 	   
						$ls_codper=$data["codper"][$i];  
						$ls_cedper=$data["cedper"][$i];
						$ls_apeper=$data["apeper"][$i];
						$ls_nomper=$data["nomper"][$i];
						$li_staper=$data["staper"][$i];
						
						$check="<input type=checkbox name=chkselec".$i." id=chkselec value=1 class=sin-borde >";
						$object[$i][1]=$check;
						$object[$i][2]="<input type=text name=txtcodper".$i." value=$ls_codper class=sin-borde  size=10 style=text-align:center readonly>";
						$object[$i][3]="<input type=text name=txtcedper".$i." value=$ls_cedper class=sin-borde  size=15 style=text-align:center readonly>";
						$object[$i][4]="<input type=text name=txtapeper".$i." value=$ls_apeper class=sin-borde  size=15 style=text-align:center readonly>";
						$object[$i][5]="<input type=text name=txtnomper".$i." value=$ls_nomper class=sin-borde  size=15 style=text-align:center readonly>";
				   }//for   
				   $io_grid->makegrid($li_totnum,$title,$object,200,'Personal',$ls_nombre);   
			   }
			   else
			   {
				   $li_total=0;
				   $object="";
				   $io_grid->makegrid($li_total,$title,$object,200,'Personal',$ls_nombre);     
			  }   
		   }  
		}
		else
		{
			$this->io_msg->message("Ventana-> Ventana Incorporaci&oacute;n del Personal M&Eacute;TODO->Operacion Nuevo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
	  
	}	
	else
	{
	   $io_msg->message(" El registro no se pudo actualizar ");
	   $li_total=0;
	   $object="";
	   $io_grid->makegrid($li_total,$title,$object,200,'Personal',$ls_nombre);     
	}  
}
/****************************************************************************************************************************************/	
?>
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right"></div></td>
              <td colspan="4"><div align="left">
              </div></td>
            </tr>
            <tr>
              <td height="22" colspan="5"><div align="right"></div>                
  	            <div align="center">
  	              <input name="botsel" type="button" class="boton" id="botsel" onClick="javascript: uf_seleccionar_todos()" value="Seleccionar Todos">
  	              <input name="botdes" type="button" class="boton" id="botdes" onClick="javascript: uf_deseleccionar_todos()" value="Deseleccionar Todos">
  	            </div></td>
            </tr>
            <tr>
              <td height="21"><input name="operacion" type="hidden" id="operacion">
              <input name="totnum" type="hidden" id="totnum" value="<?php print $li_totnum;?>"></td>
              <td colspan="4"><div align="left">
              </div></td>
            </tr>
          </table>
        <p align="center">&nbsp;        </p></td>
      </tr>
  </table>
  <div align="justify"></div>
</form>
</div>
</body>
<script language="javascript">

function uf_change_radio()
{
	f=document.form1;
	f.operacion.value ="CARGAR";
	f.action="sigesp_snorh_p_incor_susp_egre_personalnomina.php";
	f.submit();
}		

function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_snorh_p_incor_susp_egre_personalnomina.php";
	f.submit();
}

function uf_seleccionar_todos()
{
	  f=document.form1;
	  total=f.totnum.value;
	  for(i=1;i<=total;i++)	
	  {
		eval("f.chkselec"+i+".checked=true")
	  }
}

function uf_deseleccionar_todos()
{
	  f=document.form1;
	  total=f.totnum.value;
	  for(i=1;i<=total;i++)	
	  {
		eval("f.chkselec"+i+".checked=false")
	  }
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		if(f.txtfecsusper.value=="")
		{
		   alert(" Debe completar la fecha de suspension.... ");
		}
		else
		{
			f.operacion.value ="PROCESAR";
			f.action="sigesp_snorh_p_incor_susp_egre_personalnomina.php";
			f.submit();
		}	
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
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

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>
