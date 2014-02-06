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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_programacionreporte.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_totrows,$ls_operacion,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$la_reporte,$io_fun_nomina,$ls_reporte;

		$ls_reporte=$io_fun_nomina->uf_obtenervalor("cmbreporte","0406");
		$ls_titletable="Programación de Reportes";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Cod.";
		$lo_title[2]="Denominación";
		$lo_title[3]="Núm. Cargos";
		$lo_title[4]="Núm. Cargos a Personal Femenino";
		$lo_title[5]="Núm. Cargos a Pesonal Masculino";	
		$lo_title[6]="Núm. Cargos Vancantes";		
		$lo_title[7]="Asignación";
		$lo_title[8]="Distribución";
		$lo_title[9]="Meses";
		$la_reporte[0]="";
		$la_reporte[1]="";
		$la_reporte[2]="";
		$la_reporte[3]="";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]=" ";
		$aa_object[$ai_totrows][2]=" ";
		$aa_object[$ai_totrows][3]=" ";
		$aa_object[$ai_totrows][4]=" ";
		$aa_object[$ai_totrows][5]=" ";
		$aa_object[$ai_totrows][6]=" ";
		$aa_object[$ai_totrows][7]=" ";
		$aa_object[$ai_totrows][8]=" ";
		$aa_object[$ai_totrows][9]=" ";
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables($ai_i)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codded,$ls_codtipper,$li_numcar,$li_numcarf, $li_numcarm, $li_numcarv,
		       $li_totasi,$li_dismonasi,$li_monene,$li_monfeb,$li_monmar;
   		global $li_monabr,$li_monmay,$li_monjun,$li_monjul,$li_monago,$li_monsep,$li_monoct,$li_monnov,$li_mondic;
		global $li_carene,$li_carfeb,$li_carmar,$li_carabr,$li_carmay,$li_carjun,$li_carjul,$li_carago,$li_carsep;
		global $li_caroct,$li_carnov,$li_cardic;
		global $li_carenef,$li_carfebf,$li_carmarf,$li_carabrf,$li_carmayf,$li_carjunf,$li_carjulf,$li_caragof,$li_carsepf;
		global $li_caroctf,$li_carnovf,$li_cardicf;
		global $li_carenem,$li_carfebm,$li_carmarm,$li_carabrm,$li_carmaym,$li_carjunm,$li_carjulm,$li_caragom,$li_carsepm;
		global $li_caroctm,$li_carnovm,$li_cardicm;
		
		$ls_codded=$_POST["txtcodded".$ai_i];
		$ls_codtipper=$_POST["txtcodtipper".$ai_i];
		$li_numcar=$_POST["txtnumcar".$ai_i];
		$li_numcarf=$_POST["txtnumcarf".$ai_i];
		$li_numcarm=$_POST["txtnumcarm".$ai_i];
		$li_numcarv=$_POST["txtnumcarv".$ai_i];
		$li_totasi=$_POST["txttotasi".$ai_i];
		$li_dismonasi=str_pad($_POST["cmbdismonasi".$ai_i],1,"0");
		$li_monene=$_POST["txtmonene".$ai_i];
		$li_monfeb=$_POST["txtmonfeb".$ai_i];
		$li_monmar=$_POST["txtmonmar".$ai_i];
		$li_monabr=$_POST["txtmonabr".$ai_i];
		$li_monmay=$_POST["txtmonmay".$ai_i];
		$li_monjun=$_POST["txtmonjun".$ai_i];
		$li_monjul=$_POST["txtmonjul".$ai_i];
		$li_monago=$_POST["txtmonago".$ai_i];
		$li_monsep=$_POST["txtmonsep".$ai_i];
		$li_monoct=$_POST["txtmonoct".$ai_i];
		$li_monnov=$_POST["txtmonnov".$ai_i];
		$li_mondic=$_POST["txtmondic".$ai_i];
		$li_carene=$_POST["txtcarene".$ai_i];
		$li_carfeb=$_POST["txtcarfeb".$ai_i];
		$li_carmar=$_POST["txtcarmar".$ai_i];
		$li_carabr=$_POST["txtcarabr".$ai_i];
		$li_carmay=$_POST["txtcarmay".$ai_i];
		$li_carjun=$_POST["txtcarjun".$ai_i];
		$li_carjul=$_POST["txtcarjul".$ai_i];
		$li_carago=$_POST["txtcarago".$ai_i];
		$li_carsep=$_POST["txtcarsep".$ai_i];
		$li_caroct=$_POST["txtcaroct".$ai_i];
		$li_carnov=$_POST["txtcarnov".$ai_i];
		$li_cardic=$_POST["txtcardic".$ai_i];
		///-------------------------------------
		$li_carenef=$_POST["txtcarenef".$ai_i];
		$li_carfebf=$_POST["txtcarfebf".$ai_i];
		$li_carmarf=$_POST["txtcarmarf".$ai_i];
		$li_carabrf=$_POST["txtcarabrf".$ai_i];
		$li_carmayf=$_POST["txtcarmayf".$ai_i];
		$li_carjunf=$_POST["txtcarjunf".$ai_i];
		$li_carjulf=$_POST["txtcarjulf".$ai_i];
		$li_caragof=$_POST["txtcaragof".$ai_i];
		$li_carsepf=$_POST["txtcarsepf".$ai_i];
		$li_caroctf=$_POST["txtcaroctf".$ai_i];
		$li_carnovf=$_POST["txtcarnovf".$ai_i];
		$li_cardicf=$_POST["txtcardicf".$ai_i];
		
		$li_carenem=$_POST["txtcarenem".$ai_i];
		$li_carfebm=$_POST["txtcarfebm".$ai_i];
		$li_carmarm=$_POST["txtcarmarm".$ai_i];
		$li_carabrm=$_POST["txtcarabrm".$ai_i];
		$li_carmaym=$_POST["txtcarmaym".$ai_i];
		$li_carjunm=$_POST["txtcarjunm".$ai_i];
		$li_carjulm=$_POST["txtcarjulm".$ai_i];
		$li_caragom=$_POST["txtcaragom".$ai_i];
		$li_carsepm=$_POST["txtcarsepm".$ai_i];
		$li_caroctm=$_POST["txtcaroctm".$ai_i];
		$li_carnovm=$_POST["txtcarnovm".$ai_i];
		$li_cardicm=$_POST["txtcardicm".$ai_i];
		//--------------------------------------
		$txtnumcarf=$_POST["txtnumcarf".$ai_i];
		$txtnumcarm=$_POST["txtnumcarm".$ai_i];
		$txtnumcarv=$_POST["txtnumcarv".$ai_i];
		$li_totasi=str_replace(".","",$li_totasi);
		$li_totasi=str_replace(",",".",$li_totasi);
		$li_monene=str_replace(".","",$li_monene);
		$li_monene=str_replace(",",".",$li_monene);
		$li_monfeb=str_replace(".","",$li_monfeb);
		$li_monfeb=str_replace(",",".",$li_monfeb);
		$li_monmar=str_replace(".","",$li_monmar);
		$li_monmar=str_replace(",",".",$li_monmar);
		$li_monabr=str_replace(".","",$li_monabr);
		$li_monabr=str_replace(",",".",$li_monabr);
		$li_monmay=str_replace(".","",$li_monmay);
		$li_monmay=str_replace(",",".",$li_monmay);
		$li_monjun=str_replace(".","",$li_monjun);
		$li_monjun=str_replace(",",".",$li_monjun);
		$li_monjul=str_replace(".","",$li_monjul);
		$li_monjul=str_replace(",",".",$li_monjul);
		$li_monago=str_replace(".","",$li_monago);
		$li_monago=str_replace(",",".",$li_monago);
		$li_monsep=str_replace(".","",$li_monsep);
		$li_monsep=str_replace(",",".",$li_monsep);
		$li_monoct=str_replace(".","",$li_monoct);
		$li_monoct=str_replace(",",".",$li_monoct);
		$li_monnov=str_replace(".","",$li_monnov);
		$li_monnov=str_replace(",",".",$li_monnov);
		$li_mondic=str_replace(".","",$li_mondic);
		$li_mondic=str_replace(",",".",$li_mondic);		
   }
   //--------------------------------------------------------------
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
<title >Programaci&oacute;n de Reporte</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_programacionreporte.php");
	$io_progreporte=new sigesp_snorh_c_programacionreporte();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,$li_totrows);
			if($io_progreporte->uf_select_programacionreporte($ls_reporte)===false)
			{
				switch($ls_reporte)
				{
					case "0406":
						$lb_valido=$io_progreporte->uf_insert_programacionreporte0406($ls_reporte);
						break;

					case "0506":
						$lb_valido=$io_progreporte->uf_insert_programacionreporte0506($ls_reporte);
						break;

					case "0711":
						$lb_valido=$io_progreporte->uf_insert_programacionreporte0711($ls_reporte);
						break;

					case "0712":
						$lb_valido=$io_progreporte->uf_insert_programacionreporte0712($ls_reporte);
						break;					
				}
			}
			$lb_valido=$io_progreporte->uf_load_programacionreporte($ls_reporte,$li_totrows,$lo_object);
			$io_fun_nomina->uf_seleccionarcombo("0406-0506-0711-0712",$ls_reporte,$la_reporte,4);
			break;

		case "GUARDAR":
			$li_totalfilas=$_POST["totalfilas"];
			$ls_codrep=$_POST["cmbreporte"];
			$lb_valido=true;
	       	$io_progreporte->io_sql->begin_transaction();
			for($li_i=1;($li_i<=$li_totalfilas)&&($lb_valido);$li_i++)
			{
				uf_load_variables($li_i);
				$li_numcarv=$li_numcar-abs($li_numcarf+$li_numcarm);
				if ($li_numcarv>=0)
				{
					$lb_valido=$io_progreporte->uf_update_programacionreporte($ls_reporte,$ls_codded,$ls_codtipper,$li_numcar,
												$li_numcarf,$li_numcarm,$li_numcarv,
												$li_totasi,$li_dismonasi,$li_monene,$li_monfeb,$li_monmar,$li_monabr,$li_monmay,
												$li_monjun,$li_monjul,$li_monago,$li_monsep,$li_monoct,$li_monnov,$li_mondic,
												$li_carene,$li_carfeb,$li_carmar,$li_carabr,$li_carmay,$li_carjun,$li_carjul,
												$li_carago,$li_carsep,$li_caroct,$li_carnov,$li_cardic,
												$li_carenef,$li_carfebf,$li_carmarf,$li_carabrf,
												$li_carmayf,$li_carjunf,$li_carjulf,$li_caragof,
												$li_carsepf,$li_caroctf,$li_carnovf,$li_cardicf,
												$li_carenem,$li_carfebm,$li_carmarm,$li_carabrm,
												$li_carmaym,$li_carjunm,$li_carjulm,$li_caragom,
												$li_carsepm,$li_caroctm,$li_carnovm,$li_cardicm,$la_seguridad);
				}
				else
				{
					$io_progreporte->io_mensajes->message("El Número de Vacante no puede ser negativo.");
				}
			}
			if($lb_valido)
			{
				$io_progreporte->io_mensajes->message("La Programación de Reporte Fué Actualizada.");
				$io_progreporte->io_sql->commit();
			}
			else
			{
				$io_progreporte->io_mensajes->message("Ocurrio un error al Actualizar la Programación de Reporte.");
				$io_progreporte->io_sql->rollback();
			}
			$lb_valido=$io_progreporte->uf_load_programacionreporte($ls_codrep,$li_totrows,$lo_object);
			$io_fun_nomina->uf_seleccionarcombo("0406-0506-0711-0712",$ls_reporte,$la_reporte,4);
			break;
			
		case "ELIMINAR":
			$li_totalfilas=$_POST["totalfilas"];
			$ls_codrep=$_POST["cmbreporte"];
			$lb_valido=$io_progreporte->uf_delete_programacionreporte($ls_reporte,$la_seguridad);
			$io_fun_nomina->uf_seleccionarcombo("0406-0506-0711-0712",$ls_reporte,$la_reporte,4);
			$lb_valido=$io_progreporte->uf_load_programacionreporte($ls_codrep,$li_totrows,$lo_object);
			break;
	}
	$io_progreporte->uf_destructor();
	unset($io_progreporte);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif"  title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="680" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Programaci&oacute;n de Reporte </td>
        </tr>
        <tr>
          <td width="329" height="22"><div align="right"></div></td>
          <td width="535">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Reporte</div></td>
          <td><div align="left">
            <select name="cmbreporte" id="cmbreporte" onChange="javascript: ue_cambiarreporte()">
              <option value="0406" <?php print $la_reporte[0]; ?>>RECURSOS HUMANOS - 0406 </option>
              <option value="0506" <?php print $la_reporte[1]; ?>>RECURSOS HUMANOS - 0506 </option>
              <option value="0711" <?php print $la_reporte[2]; ?>>RECURSOS HUMANOS POR CARGO - 0711 </option>
              <option value="0712" <?php print $la_reporte[3]; ?>>PERSONAL JUBILADO, PENSIONADO Y ASIGNACIÓN A SOBREVIVIENTE - 0712 </option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td>
		  	<input name="operacion" type="hidden" id="operacion">
		  </td>
        </tr>
        <tr>
          <td colspan="2">
		  	<div align="center">
			    <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
				?>
			  </div>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
			</td>		  
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_cambiarreporte()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_snorh_p_programacionreporte.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		valor="";
		li_totalasig=0;
		li_totalcargo=0;
		li_registro=0;
		li_totalene=0;
		li_totalfeb=0;
		li_totalmar=0;
		li_totalabr=0;
		li_totalmay=0;
		li_totaljun=0;
		li_totaljul=0;
		li_totalago=0;
		li_totalsep=0;
		li_totaloct=0;
		li_totalnov=0;
		li_totaldic=0;
		li_cargoene=0;
		li_cargofeb=0;
		li_cargomar=0;
		li_cargoabr=0;
		li_cargomay=0;
		li_cargojun=0;
		li_cargojul=0;
		li_cargoago=0;
		li_cargosep=0;
		li_cargooct=0;
		li_cargonov=0;
		li_cargodic=0;
		valido=true;
		li_total=f.totalfilas.value;
		for(li_i=1;(li_i<=li_total)&&(valido);li_i++)
		{
			codded=ue_validarvacio(eval("f.txtcodded"+li_i+".value"));
			codigo=ue_validarvacio(eval("f.txtcodigo"+li_i+".value"));
			descripcion=ue_validarvacio(eval("f.txtdescripcion"+li_i+".value"));
			if(codded!=valor)
			{
				valor=codded;
				if(li_registro>0)
				{
					li_totalasig=uf_convertir(li_totalasig);
					li_totalene=uf_convertir(li_totalene);
					li_totalfeb=uf_convertir(li_totalfeb);
					li_totalmar=uf_convertir(li_totalmar);
					li_totalabr=uf_convertir(li_totalabr);
					li_totalmay=uf_convertir(li_totalmay);
					li_totaljun=uf_convertir(li_totaljun);
					li_totaljul=uf_convertir(li_totaljul);
					li_totalago=uf_convertir(li_totalago);
					li_totalsep=uf_convertir(li_totalsep);
					li_totaloct=uf_convertir(li_totaloct);
					li_totalnov=uf_convertir(li_totalnov);
					li_totaldic=uf_convertir(li_totaldic);
					eval("f.txttotasi"+li_registro+".value='"+li_totalasig+"'");
					eval("f.txtmonene"+li_registro+".value='"+li_totalene+"'");
					eval("f.txtmonfeb"+li_registro+".value='"+li_totalfeb+"'");
					eval("f.txtmonmar"+li_registro+".value='"+li_totalmar+"'");
					eval("f.txtmonabr"+li_registro+".value='"+li_totalabr+"'");
					eval("f.txtmonmay"+li_registro+".value='"+li_totalmay+"'");
					eval("f.txtmonjun"+li_registro+".value='"+li_totaljun+"'");
					eval("f.txtmonjul"+li_registro+".value='"+li_totaljul+"'");
					eval("f.txtmonago"+li_registro+".value='"+li_totalago+"'");
					eval("f.txtmonsep"+li_registro+".value='"+li_totalsep+"'");
					eval("f.txtmonoct"+li_registro+".value='"+li_totaloct+"'");
					eval("f.txtmonnov"+li_registro+".value='"+li_totalnov+"'");
					eval("f.txtmondic"+li_registro+".value='"+li_totaldic+"'");

					eval("f.txtnumcar"+li_registro+".value='"+li_totalcargo+"'");					
					eval("f.txtcarene"+li_registro+".value='"+li_cargoene+"'");
					eval("f.txtcarfeb"+li_registro+".value='"+li_cargofeb+"'");
					eval("f.txtcarmar"+li_registro+".value='"+li_cargomar+"'");
					eval("f.txtcarabr"+li_registro+".value='"+li_cargoabr+"'");
					eval("f.txtcarmay"+li_registro+".value='"+li_cargomay+"'");
					eval("f.txtcarjun"+li_registro+".value='"+li_cargojun+"'");
					eval("f.txtcarjul"+li_registro+".value='"+li_cargojul+"'");
					eval("f.txtcarago"+li_registro+".value='"+li_cargoago+"'");
					eval("f.txtcarsep"+li_registro+".value='"+li_cargosep+"'");
					eval("f.txtcaroct"+li_registro+".value='"+li_cargooct+"'");
					eval("f.txtcarnov"+li_registro+".value='"+li_cargonov+"'");
					eval("f.txtcardic"+li_registro+".value='"+li_cargodic+"'");
				}
				li_registro=li_i;
				li_totalasig=0;
				li_totalcargo=0;
				li_totalene=0;
				li_totalfeb=0;
				li_totalmar=0;
				li_totalabr=0;
				li_totalmay=0;
				li_totaljun=0;
				li_totaljul=0;
				li_totalago=0;
				li_totalsep=0;
				li_totaloct=0;
				li_totalnov=0;
				li_totaldic=0;
				li_cargoene=0;
				li_cargofeb=0;
				li_cargomar=0;
				li_cargoabr=0;
				li_cargomay=0;
				li_cargojun=0;
				li_cargojul=0;
				li_cargoago=0;
				li_cargosep=0;
				li_cargooct=0;
				li_cargonov=0;
				li_cargodic=0;
			}
			else
			{
				li_cargototal=0;
				totcar=ue_validarvacio(eval("f.txtnumcar"+li_i+".value"));
				carene=ue_validarvacio(eval("f.txtcarene"+li_i+".value"));
				carfeb=ue_validarvacio(eval("f.txtcarfeb"+li_i+".value"));
				carmar=ue_validarvacio(eval("f.txtcarmar"+li_i+".value"));
				carabr=ue_validarvacio(eval("f.txtcarabr"+li_i+".value"));
				carmay=ue_validarvacio(eval("f.txtcarmay"+li_i+".value"));
				carjun=ue_validarvacio(eval("f.txtcarjun"+li_i+".value"));
				carjul=ue_validarvacio(eval("f.txtcarjul"+li_i+".value"));
				carago=ue_validarvacio(eval("f.txtcarago"+li_i+".value"));
				carsep=ue_validarvacio(eval("f.txtcarsep"+li_i+".value"));
				caroct=ue_validarvacio(eval("f.txtcaroct"+li_i+".value"));
				carnov=ue_validarvacio(eval("f.txtcarnov"+li_i+".value"));
				cardic=ue_validarvacio(eval("f.txtcardic"+li_i+".value"));

				li_montototal=0;
				totasi=ue_validarvacio(eval("f.txttotasi"+li_i+".value"));
				while(totasi.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					totasi=totasi.replace(".","");
				}
				totasi=totasi.replace(",",".");
				monene=ue_validarvacio(eval("f.txtmonene"+li_i+".value"));
				while(monene.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monene=monene.replace(".","");
				}
				monene=monene.replace(",",".");
				monfeb=ue_validarvacio(eval("f.txtmonfeb"+li_i+".value"));
				while(monfeb.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monfeb=monfeb.replace(".","");
				}
				monfeb=monfeb.replace(",",".");
				monmar=ue_validarvacio(eval("f.txtmonmar"+li_i+".value"));
				while(monmar.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monmar=monmar.replace(".","");
				}
				monmar=monmar.replace(",",".");
				monabr=ue_validarvacio(eval("f.txtmonabr"+li_i+".value"));
				while(monabr.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monabr=monabr.replace(".","");
				}
				monabr=monabr.replace(",",".");
				monmay=ue_validarvacio(eval("f.txtmonmay"+li_i+".value"));
				while(monmay.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monmay=monmay.replace(".","");
				}
				monmay=monmay.replace(",",".");
				monjun=ue_validarvacio(eval("f.txtmonjun"+li_i+".value"));
				while(monjun.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monjun=monjun.replace(".","");
				}
				monjun=monjun.replace(",",".");
				monjul=ue_validarvacio(eval("f.txtmonjul"+li_i+".value"));
				while(monjul.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monjul=monjul.replace(".","");
				}
				monjul=monjul.replace(",",".");
				monago=ue_validarvacio(eval("f.txtmonago"+li_i+".value"));
				while(monago.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monago=monago.replace(".","");
				}
				monago=monago.replace(",",".");
				monsep=ue_validarvacio(eval("f.txtmonsep"+li_i+".value"));
				while(monsep.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monsep=monsep.replace(".","");
				}
				monsep=monsep.replace(",",".");
				monoct=ue_validarvacio(eval("f.txtmonoct"+li_i+".value"));
				while(monoct.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monoct=monoct.replace(".","");
				}
				monoct=monoct.replace(",",".");
				monnov=ue_validarvacio(eval("f.txtmonnov"+li_i+".value"));
				while(monnov.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					monnov=monnov.replace(".","");
				}
				monnov=monnov.replace(",",".");
				mondic=ue_validarvacio(eval("f.txtmondic"+li_i+".value"));
				while(mondic.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					mondic=mondic.replace(".","");
				}
				mondic=mondic.replace(",",".");

				li_montototal=parseFloat(monene)+parseFloat(monfeb)+parseFloat(monmar)+parseFloat(monabr)+parseFloat(monmay)+parseFloat(monjun);
				li_montototal=parseFloat(li_montototal)+parseFloat(monjul)+parseFloat(monago)+parseFloat(monsep)+parseFloat(monoct)+parseFloat(monnov)+parseFloat(mondic);
				li_montototal=uf_redondear(li_montototal,2);
				if(parseFloat(li_montototal)!=parseFloat(totasi))
				{
					li_montototal=uf_convertir(li_montototal);
					totasi=uf_convertir(totasi);
					alert("La suma de los meses no coincide con el monto asignado. Para la Denominacion "+codigo+" "+descripcion+". Monto Total Meses "+li_montototal+" Monto Asignado "+totasi);
					valido=false;
				}
				else
				{
					li_cargototal=0;
					li_cargototal=parseFloat(carene)+parseFloat(carfeb)+parseFloat(carmar)+parseFloat(carabr)+parseFloat(carmay)+parseFloat(carjun);
					li_cargototal=parseFloat(li_cargototal)+parseFloat(carjul)+parseFloat(carago)+parseFloat(carsep)+parseFloat(caroct)+parseFloat(carnov)+parseFloat(cardic);
					if(parseFloat(li_cargototal)!=parseFloat(totcar))
					{
						alert("La suma de los cargos de los meses no coincide con el total de cargos. Para la Denominacion "+codigo+" "+descripcion+". Monto Total Cargos "+li_cargototal+" Total de Cargos "+totcar);
						valido=false;
					}
					else
					{
						li_totalasig=li_totalasig+parseFloat(totasi);
						li_totalene=li_totalene+parseFloat(monene);
						li_totalfeb=li_totalfeb+parseFloat(monfeb);
						li_totalmar=li_totalmar+parseFloat(monmar);
						li_totalabr=li_totalabr+parseFloat(monabr);
						li_totalmay=li_totalmay+parseFloat(monmay);
						li_totaljun=li_totaljun+parseFloat(monjun);
						li_totaljul=li_totaljul+parseFloat(monjul);
						li_totalago=li_totalago+parseFloat(monago);
						li_totalsep=li_totalsep+parseFloat(monsep);
						li_totaloct=li_totaloct+parseFloat(monoct);
						li_totalnov=li_totalnov+parseFloat(monnov);
						li_totaldic=li_totaldic+parseFloat(mondic);

						li_totalcargo=li_totalcargo+parseFloat(totcar);
						li_cargoene=li_cargoene+parseFloat(carene);
						li_cargofeb=li_cargofeb+parseFloat(carfeb);
						li_cargomar=li_cargomar+parseFloat(carmar);
						li_cargoabr=li_cargoabr+parseFloat(carabr);
						li_cargomay=li_cargomay+parseFloat(carmay);
						li_cargojun=li_cargojun+parseFloat(carjun);
						li_cargojul=li_cargojul+parseFloat(carjul);
						li_cargoago=li_cargoago+parseFloat(carago);
						li_cargosep=li_cargosep+parseFloat(carsep);
						li_cargooct=li_cargooct+parseFloat(caroct);
						li_cargonov=li_cargonov+parseFloat(carnov);
						li_cargodic=li_cargodic+parseFloat(cardic);
					}
				}
			}
		}
		if(valido)
		{
			li_totalasig=uf_convertir(li_totalasig);
			li_totalene=uf_convertir(li_totalene);
			li_totalfeb=uf_convertir(li_totalfeb);
			li_totalmar=uf_convertir(li_totalmar);
			li_totalabr=uf_convertir(li_totalabr);
			li_totalmay=uf_convertir(li_totalmay);
			li_totaljun=uf_convertir(li_totaljun);
			li_totaljul=uf_convertir(li_totaljul);
			li_totalago=uf_convertir(li_totalago);
			li_totalsep=uf_convertir(li_totalsep);
			li_totaloct=uf_convertir(li_totaloct);
			li_totalnov=uf_convertir(li_totalnov);
			li_totaldic=uf_convertir(li_totaldic);
			eval("f.txttotasi"+li_registro+".value='"+li_totalasig+"'");
			eval("f.txtmonene"+li_registro+".value='"+li_totalene+"'");
			eval("f.txtmonfeb"+li_registro+".value='"+li_totalfeb+"'");
			eval("f.txtmonmar"+li_registro+".value='"+li_totalmar+"'");
			eval("f.txtmonabr"+li_registro+".value='"+li_totalabr+"'");
			eval("f.txtmonmay"+li_registro+".value='"+li_totalmay+"'");
			eval("f.txtmonjun"+li_registro+".value='"+li_totaljun+"'");
			eval("f.txtmonjul"+li_registro+".value='"+li_totaljul+"'");
			eval("f.txtmonago"+li_registro+".value='"+li_totalago+"'");
			eval("f.txtmonsep"+li_registro+".value='"+li_totalsep+"'");
			eval("f.txtmonoct"+li_registro+".value='"+li_totaloct+"'");
			eval("f.txtmonnov"+li_registro+".value='"+li_totalnov+"'");
			eval("f.txtmondic"+li_registro+".value='"+li_totaldic+"'");
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_p_programacionreporte.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_snorh_p_programacionreporte.php";
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
	location.href = "sigespwindow_blank.php";
}

function ue_actualizar_cargos(codigo)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	li_totalcargos=0;
	for(li_i=1;li_i<=li_total;li_i++)
	{
		codded=ue_validarvacio(eval("f.txtcodded"+li_i+".value"));
		if (codded==codigo)
		{
			codtipper=ue_validarvacio(eval("f.txtcodtipper"+li_i+".value"));
			numcar=ue_validarvacio(eval("f.txtnumcar"+li_i+".value"));
			if(codtipper=="0000")
			{
				li_registro=li_i;
			}
			else
			{
				li_totalcargos=li_totalcargos+parseFloat(numcar);
			}
		}
	}
	eval("f.txtnumcar"+li_registro+".value="+li_totalcargos);
}

function ue_actualizar_cargosm(codigo)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	li_totalcargos=0;
	li_valor=0;
	li_pos=0;
	li_valor1=0;
	for(li_i=1;li_i<=li_total;li_i++)
	{
		codded=ue_validarvacio(eval("f.txtcodded"+li_i+".value"));
		if (codded==codigo)
		{
			codtipper=ue_validarvacio(eval("f.txtcodtipper"+li_i+".value"));
			numcar=ue_validarvacio(eval("f.txtnumcar"+li_i+".value"));
			numcarm=ue_validarvacio(eval("f.txtnumcarm"+li_i+".value"));
			if (numcar<numcarm)
			{
				li_valor=1;
				li_pos=li_i;
			}				
			if(codtipper=="0000")
			{
				li_registro=li_i;
				li_valor=0;
			    li_pos=0;
			}
			else
			{
				li_totalcargos=li_totalcargos+parseFloat(numcarm);
			}
		}			
	}
	if (li_valor==0)
	{
		eval("f.txtnumcarm"+li_registro+".value="+li_totalcargos);		
	}
	else
	{
		eval("f.txtnumcarm"+li_pos+".value=0");
		alert("El Número de Cargos Masculino no puede ser mayor al Número total de Cargos");
	}	
	
}

function ue_actualizar_cargosf(codigo)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	li_totalcargos=0;
	li_valor=0;
	li_pos=0;
	for(li_i=1;li_i<=li_total;li_i++)
	{
		codded=ue_validarvacio(eval("f.txtcodded"+li_i+".value"));
		if (codded==codigo)
		{
			codtipper=ue_validarvacio(eval("f.txtcodtipper"+li_i+".value"));
			numcar=ue_validarvacio(eval("f.txtnumcar"+li_i+".value"));
			numcarf=ue_validarvacio(eval("f.txtnumcarf"+li_i+".value"));
			if (numcar<numcarf)
			{
				li_valor=1;
				li_pos=li_i;
			}
			if(codtipper=="0000")
			{
				li_registro=li_i;
				li_valor=0;
			    li_pos=0;
			}
			else
			{
				li_totalcargos=li_totalcargos+parseFloat(numcarf);
			}
		}		
	}
	if (li_valor==0)
	{
		eval("f.txtnumcarf"+li_registro+".value="+li_totalcargos);
	}
	else
	{
		eval("f.txtnumcarf"+li_pos+".value=0");
		alert("El Número de Cargos Femenino no puede ser mayor al Número total de Cargos");
	}
}

function ue_actualizar_asignacion(codigo)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	li_totalasig=0;
	for(li_i=1;li_i<=li_total;li_i++)
	{
		codded=ue_validarvacio(eval("f.txtcodded"+li_i+".value"));
		if (codded==codigo)
		{
			codtipper=ue_validarvacio(eval("f.txtcodtipper"+li_i+".value"));
			totasi="";
			totasi=ue_validarvacio(eval("f.txttotasi"+li_i+".value"));
			while(totasi.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				totasi=totasi.replace(".","");
			}
			totasi=totasi.replace(",",".");
			if(codtipper=="0000")
			{
				li_registro=li_i;				
			}
			else
			{
				li_totalasig=li_totalasig+parseFloat(totasi);
			}
		}
	}
	li_totalasig=uf_convertir(li_totalasig);
	eval("f.txttotasi"+li_registro+".value='"+li_totalasig+"'");
}

function ue_actualizar_meses(li_row)
{
	f=document.form1;
	monasi=ue_validarvacio(eval("f.txttotasi"+li_row+".value"));
	dismonasi=ue_validarvacio(eval("f.cmbdismonasi"+li_row+".value"));
	if(dismonasi==0)
	{
		while(monasi.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monasi=monasi.replace(".","");
		}
		monasi=monasi.replace(",",".");
		monmensual=(monasi/12);
		monmensual=uf_redondear(monmensual,2);
		monmensual=uf_convertir(monmensual);
		while(monmensual.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monmensual=monmensual.replace(".","");
		}
		monmensual=monmensual.replace(",",".");
		monultmes=(monasi-(monmensual*11));
		monultmes=uf_redondear(monultmes,2);
				
		monmensual=uf_convertir(monmensual);
		monultmes=uf_convertir(monultmes);
		eval("f.txtmonene"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonfeb"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonmar"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonabr"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonmay"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonjun"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonjul"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonago"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonsep"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonoct"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonnov"+li_row+".value='"+monmensual+"'");
		eval("f.txtmondic"+li_row+".value='"+monultmes+"'");
	}
	else
	{
		eval("f.txtmonene"+li_row+".value='0'");
		eval("f.txtmonfeb"+li_row+".value='0'");
		eval("f.txtmonmar"+li_row+".value='0'");
		eval("f.txtmonabr"+li_row+".value='0'");
		eval("f.txtmonmay"+li_row+".value='0'");
		eval("f.txtmonjun"+li_row+".value='0'");
		eval("f.txtmonjul"+li_row+".value='0'");
		eval("f.txtmonago"+li_row+".value='0'");
		eval("f.txtmonsep"+li_row+".value='0'");
		eval("f.txtmonoct"+li_row+".value='0'");
		eval("f.txtmonnov"+li_row+".value='0'");
		eval("f.txtmondic"+li_row+".value='0'");
	}
}

function uf_abrir_meses(li_row)
{
	f=document.form1;
	codigo=ue_validarvacio(eval("f.txtcodigo"+li_row+".value"));
	denominacion=ue_validarvacio(eval("f.txtdescripcion"+li_row+".value"));
	codded=ue_validarvacio(eval("f.txtcodded"+li_row+".value"));
	codtipper=ue_validarvacio(eval("f.txtcodtipper"+li_row+".value"));
	numcar=ue_validarvacio(eval("f.txtnumcar"+li_row+".value"));
	monasi=ue_validarvacio(eval("f.txttotasi"+li_row+".value"));
	dismonasi=ue_validarvacio(eval("f.cmbdismonasi"+li_row+".value"));
	if(dismonasi==0)
	{
		while(monasi.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monasi=monasi.replace(".","");
		}
		monasi=monasi.replace(",",".");
		monmensual=(monasi/12);
		monmensual=uf_redondear(monmensual,2);
		monmensual=uf_convertir(monmensual);
		while(monmensual.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monmensual=monmensual.replace(".","");
		}
		monmensual=monmensual.replace(",",".");
		monultmes=(monasi-(monmensual*11));
		monultmes=uf_redondear(monultmes,2);
		monmensual=uf_convertir(monmensual);
		monultmes=uf_convertir(monultmes);
		monasi=uf_convertir(monasi);
		
		eval("f.txtmonene"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonfeb"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonmar"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonabr"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonmay"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonjun"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonjul"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonago"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonsep"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonoct"+li_row+".value='"+monmensual+"'");
		eval("f.txtmonnov"+li_row+".value='"+monmensual+"'");
		eval("f.txtmondic"+li_row+".value='"+monultmes+"'");
	}
	monene=ue_validarvacio(eval("f.txtmonene"+li_row+".value"));
	monfeb=ue_validarvacio(eval("f.txtmonfeb"+li_row+".value"));
	monmar=ue_validarvacio(eval("f.txtmonmar"+li_row+".value"));
	monabr=ue_validarvacio(eval("f.txtmonabr"+li_row+".value"));
	monmay=ue_validarvacio(eval("f.txtmonmay"+li_row+".value"));
	monjun=ue_validarvacio(eval("f.txtmonjun"+li_row+".value"));
	monjul=ue_validarvacio(eval("f.txtmonjul"+li_row+".value"));
	monago=ue_validarvacio(eval("f.txtmonago"+li_row+".value"));
	monsep=ue_validarvacio(eval("f.txtmonsep"+li_row+".value"));
	monoct=ue_validarvacio(eval("f.txtmonoct"+li_row+".value"));
	monnov=ue_validarvacio(eval("f.txtmonnov"+li_row+".value"));
	mondic=ue_validarvacio(eval("f.txtmondic"+li_row+".value"));
	carene=ue_validarvacio(eval("f.txtcarene"+li_row+".value"));
	carfeb=ue_validarvacio(eval("f.txtcarfeb"+li_row+".value"));
	carmar=ue_validarvacio(eval("f.txtcarmar"+li_row+".value"));
	carabr=ue_validarvacio(eval("f.txtcarabr"+li_row+".value"));
	carmay=ue_validarvacio(eval("f.txtcarmay"+li_row+".value"));
	carjun=ue_validarvacio(eval("f.txtcarjun"+li_row+".value"));
	carjul=ue_validarvacio(eval("f.txtcarjul"+li_row+".value"));
	carago=ue_validarvacio(eval("f.txtcarago"+li_row+".value"));
	carsep=ue_validarvacio(eval("f.txtcarsep"+li_row+".value"));
	caroct=ue_validarvacio(eval("f.txtcaroct"+li_row+".value"));
	carnov=ue_validarvacio(eval("f.txtcarnov"+li_row+".value"));
	cardic=ue_validarvacio(eval("f.txtcardic"+li_row+".value"));
	//----------------------------------------------------------
	carenef=ue_validarvacio(eval("f.txtcarenef"+li_row+".value"));
	carfebf=ue_validarvacio(eval("f.txtcarfebf"+li_row+".value"));
	carmarf=ue_validarvacio(eval("f.txtcarmarf"+li_row+".value"));
	carabrf=ue_validarvacio(eval("f.txtcarabrf"+li_row+".value"));
	carmayf=ue_validarvacio(eval("f.txtcarmayf"+li_row+".value"));
	carjunf=ue_validarvacio(eval("f.txtcarjunf"+li_row+".value"));
	carjulf=ue_validarvacio(eval("f.txtcarjulf"+li_row+".value"));
	caragof=ue_validarvacio(eval("f.txtcaragof"+li_row+".value"));
	carsepf=ue_validarvacio(eval("f.txtcarsepf"+li_row+".value"));
	caroctf=ue_validarvacio(eval("f.txtcaroctf"+li_row+".value"));
	carnovf=ue_validarvacio(eval("f.txtcarnovf"+li_row+".value"));
	cardicf=ue_validarvacio(eval("f.txtcardicf"+li_row+".value"));
	
	carenem=ue_validarvacio(eval("f.txtcarenem"+li_row+".value"));
	carfebm=ue_validarvacio(eval("f.txtcarfebm"+li_row+".value"));
	carmarm=ue_validarvacio(eval("f.txtcarmarm"+li_row+".value"));
	carabrm=ue_validarvacio(eval("f.txtcarabrm"+li_row+".value"));
	carmaym=ue_validarvacio(eval("f.txtcarmaym"+li_row+".value"));
	carjunm=ue_validarvacio(eval("f.txtcarjunm"+li_row+".value"));
	carjulm=ue_validarvacio(eval("f.txtcarjulm"+li_row+".value"));
	caragom=ue_validarvacio(eval("f.txtcaragom"+li_row+".value"));
	carsepm=ue_validarvacio(eval("f.txtcarsepm"+li_row+".value"));
	caroctm=ue_validarvacio(eval("f.txtcaroctm"+li_row+".value"));
	carnovm=ue_validarvacio(eval("f.txtcarnovm"+li_row+".value"));
	cardicm=ue_validarvacio(eval("f.txtcardicm"+li_row+".value"));
	//----------------------------------------------------------
	
	pagina="sigesp_snorh_pdt_mesesprogreporte.php?codigo="+codigo+"&codded="+codded+"&codtipper="+codtipper+"&monasi="+monasi+"&dismonasi="+dismonasi;
	pagina=pagina+"&numcar="+numcar+"&denominacion="+denominacion+"&monene="+monene+"&monfeb="+monfeb+"&monmar="+monmar+"&monabr="+monabr+"";
	pagina=pagina+"&monmay="+monmay+"&monjun="+monjun+"&monjul="+monjul+"&monago="+monago+"&monsep="+monsep+"&monoct="+monoct+"";
	pagina=pagina+"&monnov="+monnov+"&mondic="+mondic+"&carene="+carene+"&carfeb="+carfeb+"&carmar="+carmar+"&carabr="+carabr;
	pagina=pagina+"&carmay="+carmay+"&carjun="+carjun+"&carjul="+carjul+"&carago="+carago+"&carsep="+carsep+"&caroct="+caroct+"";
	pagina=pagina+"&carnov="+carnov+"&cardic="+cardic+"&row="+li_row;
	
	pagina=pagina+"&carenef="+carenef+"&carfebf="+carfebf+"&carmarf="+carmarf+"&carabrf="+carabrf;
	pagina=pagina+"&carmayf="+carmayf+"&carjunf="+carjunf+"&carjulf="+carjulf+"&caragof="+caragof+"&carsepf="+carsepf+"&caroctf="+caroctf+"";
	pagina=pagina+"&carnovf="+carnovf+"&cardicf="+cardicf;
	
	pagina=pagina+"&carenem="+carenem+"&carfebm="+carfebm+"&carmarm="+carmarm+"&carabrm="+carabrm;
	pagina=pagina+"&carmaym="+carmaym+"&carjunm="+carjunm+"&carjulm="+carjulm+"&caragom="+caragom+"&carsepm="+carsepm+"&caroctm="+caroctm+"";
	pagina=pagina+"&carnovm="+carnovm+"&cardicm="+cardicm;
	
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=700,left=50,top=50,location=no,resizable=no");
}

function uf_redondear(num, dec)
{ 
	num = parseFloat(num); 
	dec = parseFloat(dec); 
	dec = (!dec ? 2 : dec); 
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}

</script> 
</html>