<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

	function uf_limpiarvariables()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_metodo,$ls_vidautil,$ls_valres,$ls_ctadep,$ls_ctacon,$ls_status;
   		global $ls_codestpro1,$ls_denestpro1,$ls_codestpro2,$ls_denestpro2,$ls_codestpro3,$ls_denestpro3;
		global $ls_codestpro4,$ls_denestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla;
		
		$ls_metodo="";
		$ls_vidautil="";
		$ls_valres="";
		$ls_ctadep="";
		$ls_ctacon="";
		$ls_status="";
		$ls_codestpro1="";
		$ls_denestpro1="";
		$ls_codestpro2="";
		$ls_denestpro2="";
		$ls_codestpro3="";
		$ls_denestpro3="";
		$ls_codestpro4="";
		$ls_denestpro4="";
		$ls_codestpro5="";
		$ls_denestpro5="";
		$ls_estcla="";
    }
	function uf_llenar_combo(&$la_metodo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_llenar_combo
	//	Access:    public
	//	Arguments:
	//  la_metodo // arreglo de valores que puede tomar el combo.
	//	Description:  Esta funcion carga el arreglo del combo de metodo de depreciacion.
	//              
	//////////////////////////////////////////////////////////////////////////////		

		global $io_sql;
		
		$ls_sql="SELECT * FROM saf_metodo";
		$result=$io_sql->select($ls_sql);
		$li_pos=0;
		while($row=$io_sql->fetch_row($result))
		{
			$li_pos=$li_pos+1;
			$la_metodo["codmetdep"][$li_pos]=$row["codmetdep"];   
			$la_metodo["denmetdep"][$li_pos]=$row["denmetdep"];   
		}
	}


	function uf_pintar_combo($la_metodo,$ls_metodo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintar_combo
	//	Access:    public
	//	Arguments:
	//  la_metodo // arreglo de valores que puede tomar el combo.
	//  ls_metodo // item seleccionado.
	//	Description:  Esta funcion carga el combo de metodo de depreciacion manteniendo la seleccion.
	//              
	//////////////////////////////////////////////////////////////////////////////		
		
		print "<select name='cmbmetodos' id='select' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($la_metodo["codmetdep"]);
		for($i=1; $i <= $li_total ; $i++)
		{
			if($la_metodo["codmetdep"][$i]==$ls_metodo)
			{
				print "<option value='".$la_metodo["codmetdep"][$i]."' selected>".$la_metodo["denmetdep"][$i]."</option>";
			}
			else
			{
				print "<option value='".$la_metodo["codmetdep"][$i]."'>".$la_metodo["denmetdep"][$i]."</option>";
			}
		}
		print"</select>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Depreciaci&oacute;n</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("sigesp_saf_c_activo.php");
	$io_saf= new sigesp_saf_c_activo();
	require_once("class_funciones_activos.php");
	$io_activos= new class_funciones_activos();
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$lb_valido=$io_activos->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);

	
	$la_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_modalidad= $_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
				$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];
				$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];
				$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];
			break;
			
		case "2": // Modalidad por Presupuesto
				$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];
				$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];
				$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];
				$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];
				$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];
			break;
	}
	$ls_sistema="SAF";
	$ls_ventanas="sigesp_saf_d_depreciacion.php";

	$la_seguridad["empresa"]=$la_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	uf_llenar_combo($la_metodo);

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		if(array_key_exists("codact",$_GET))
		{
			$ls_codact=$_GET["codact"];
			$ls_sql= "SELECT saf_activo.*,".
					 "       (SELECT denestpro1 FROM spg_ep1".
					 "		   WHERE saf_activo.codemp=spg_ep1.codemp".
					 "           AND saf_activo.codestpro1=spg_ep1.codestpro1 ".
					 "           AND saf_activo.estcla=spg_ep1.estcla) AS denestpro1,".
					 "       (SELECT denestpro2 FROM spg_ep2".
					 "         WHERE saf_activo.codemp=spg_ep2.codemp".
					 "           AND saf_activo.codestpro1=spg_ep2.codestpro1".
					 "           AND saf_activo.codestpro2=spg_ep2.codestpro2".
					 "           AND saf_activo.estcla=spg_ep2.estcla) AS denestpro2,".
					 "       (SELECT denestpro3 FROM spg_ep3".
					 "         WHERE saf_activo.codemp=spg_ep3.codemp".
					 "           AND saf_activo.codestpro1=spg_ep3.codestpro1".
					 "           AND saf_activo.codestpro2=spg_ep3.codestpro2".
					 "           AND saf_activo.codestpro3=spg_ep3.codestpro3".
					 "           AND saf_activo.estcla=spg_ep3.estcla) AS denestpro3,".
					 "       (SELECT denestpro4 FROM spg_ep4".
					 "         WHERE saf_activo.codemp=spg_ep4.codemp".
					 "           AND saf_activo.codestpro1=spg_ep4.codestpro1".
					 "           AND saf_activo.codestpro2=spg_ep4.codestpro2".
					 "           AND saf_activo.codestpro3=spg_ep4.codestpro3".
					 "           AND saf_activo.codestpro4=spg_ep4.codestpro4".
					 "           AND saf_activo.estcla=spg_ep4.estcla) AS denestpro4,".
					 "       (SELECT denestpro5 FROM spg_ep5".
					 "         WHERE saf_activo.codemp=spg_ep5.codemp".
					 "           AND saf_activo.codestpro1=spg_ep5.codestpro1".
					 "           AND saf_activo.codestpro2=spg_ep5.codestpro2".
					 "           AND saf_activo.codestpro3=spg_ep5.codestpro3".
					 "           AND saf_activo.codestpro4=spg_ep5.codestpro4".
					 "           AND saf_activo.codestpro5=spg_ep5.codestpro5".
					 "           AND saf_activo.estcla=spg_ep5.estcla) AS denestpro5".
					 "  FROM saf_activo".
					 " WHERE codemp= '".$la_codemp."'".
					 "   AND codact= '".$ls_codact."'";
			$result=$io_sql->select($ls_sql);

			if($row=$io_sql->fetch_row($result))
			{
				$lb_valido=true;
				$ls_metodo=  $row["codmetdep"];
				$ls_vidautil=$row["vidautil"];
				$ls_valres=  $row["cossal"];
				$ls_ctadep=  $row["spg_cuenta_dep"];
				$ls_ctacon=  $row["sc_cuenta"];
				$ls_vidautil= number_format($ls_vidautil,2,",",".");
				$ls_valres= number_format($ls_valres,2,",",".");
				$ls_codestpro1=  $row["codestpro1"];
				$ls_codestpro2=  $row["codestpro2"];
				$ls_codestpro3=  $row["codestpro3"];
				$ls_codestpro4=  $row["codestpro4"];
				$ls_codestpro5=  $row["codestpro5"];
				$ls_denestpro1=  $row["denestpro1"];
				$ls_denestpro2=  $row["denestpro2"];
				$ls_denestpro3=  $row["denestpro3"];
				$ls_denestpro4=  $row["denestpro4"];
				$ls_denestpro5=  $row["denestpro5"];
				$ls_estcla=  $row["estcla"];
				$ls_codestpro1=substr($ls_codestpro1,(25-$li_len1),$li_len1);
				$ls_codestpro2=substr($ls_codestpro2,(25-$li_len2),$li_len2);
				$ls_codestpro3=substr($ls_codestpro3,(25-$li_len3),$li_len3);
				$ls_codestpro4=substr($ls_codestpro4,(25-$li_len4),$li_len4);
				$ls_codestpro5=substr($ls_codestpro5,(25-$li_len5),$li_len5);		
			}
			else
			{
				$lb_valido=false;
			}
		
		}
		else
		{
			$ls_codact="";
		
		}
	}
	if ($ls_operacion=="GUARDAR")
	{
		$ls_valido= false;
		$ls_codemp=  $io_activos->uf_obtenervalor("txtcodemp","");
		$ls_codact=  $io_activos->uf_obtenervalor("txtcodact","");
		$ls_metodo=  $io_activos->uf_obtenervalor("cmbmetodos","");
		$ls_vidautil=$io_activos->uf_obtenervalor("txtviduti","");
		$ls_valres=  $io_activos->uf_obtenervalor("txtvalres","");
		$ls_ctadep=  $io_activos->uf_obtenervalor("txtctaspg","");
		$ls_ctacon=  $io_activos->uf_obtenervalor("txtctacon","");
		$ls_codestpro1=  $io_activos->uf_obtenervalor("txtcodestpro1","");
		$ls_codestpro2=  $io_activos->uf_obtenervalor("txtcodestpro2","");
		$ls_codestpro3=  $io_activos->uf_obtenervalor("txtcodestpro3","");
		$ls_codestpro4=  $io_activos->uf_obtenervalor("txtcodestpro4","0000000000000000000000000");
		$ls_codestpro5=  $io_activos->uf_obtenervalor("txtcodestpro5","0000000000000000000000000");
		$ls_denestpro1=  $io_activos->uf_obtenervalor("txtdenestpro1","");
		$ls_denestpro2=  $io_activos->uf_obtenervalor("txtdenestpro2","");
		$ls_denestpro3=  $io_activos->uf_obtenervalor("txtdenestpro3","");
		$ls_denestpro4=  $io_activos->uf_obtenervalor("txtdenestpro4","");
		$ls_denestpro5=  $io_activos->uf_obtenervalor("txtdenestpro5","");
		$ls_estcla=  $io_activos->uf_obtenervalor("estcla","");
		$ls_valres=    str_replace(".","",$ls_valres);
		$ls_valres=    str_replace(",",".",$ls_valres);
		$ls_vidautil=  str_replace(".","",$ls_vidautil);
		$ls_vidautil=  str_replace(",",".",$ls_vidautil);
		if (($ls_metodo=="---")||($ls_ctadep=="")||($ls_ctacon=="")||($ls_vidautil==0))
		{
			$io_msg->message("Debe completar los datos");
		}
		else
		{
			$lb_valido=$io_saf->uf_saf_update_depreciacion($ls_codemp,$ls_codact,$ls_metodo,$ls_vidautil,$ls_valres,$ls_ctadep,
														   $ls_ctacon,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
														   $ls_codestpro5,$ls_estcla,$la_seguridad);
			if($lb_valido)
			{
				$io_msg->message("El registro fue actualizado con exito");
				uf_limpiarvariables();
				print "<script language='javascript'>";					
				print "close();";					
				print "</script>";					
			}	
			else
			{
				$io_msg->message("El registro no pudo ser actualizado");
			}
		
		}
	}

?>
<div align="center">
  <table width="596" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="588" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="3" class="titulo-ventana">Depreciaci&oacute;n</td>
  </tr>
  <tr class="formato-blanco">
    <td width="111" height="19">&nbsp;</td>
    <td colspan="2"><input name="txtcodemp" type="hidden" id="txtempresa2" value="<?php print $la_codemp?>">
        <input name="txtcodact" type="hidden" id="txtnombrevie2" value="<?php print $ls_codact?>">
        <input name="hidestmodest" type="hidden" id="hidestmodest" value="<?php print $ls_modalidad; ?>"></td>
  </tr> 
  <tr class="formato-blanco">
    <td height="19"><div align="right">M&eacute;todo</div></td>
    <td height="22" colspan="2">
<?php uf_pintar_combo($la_metodo,$ls_metodo);?>
<input name="hidstatus" type="hidden" id="hidstatus"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="18"><div align="right">Vida Util </div></td>
    <td height="22" colspan="2"><input name="txtviduti" type="text" id="txtviduti" value="<?php print $ls_vidautil?>" size="8" maxlength="4" style="text-align:center " onKeyPress="return(ue_formatonumero(this,'.',',',event));">
    A&ntilde;os</td>
  </tr>
  <tr class="formato-blanco">
    <td height="18"><div align="right">Valor de Rescate </div></td>
    <td width="371" height="22"><input name="txtvalres" type="text" id="txtvalres" value="<?php print $ls_valres?>" size="20" style="text-align:right " onKeyPress="return(ue_formatonumero(this,'.',',',event));"></td>
    <td width="82">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="13" colspan="3"><div align="center"><strong>Cuentas para registrar la depreciacion del activo </strong></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28" colspan="3"><div align="center">
      <table width="578" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td align="right"><?php print $ls_nomestpro1; ?></td>
          <td height="22"><div align="left">
            <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1; ?>" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>">
            <a href="javascript: ue_buscarcodestpro('1');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1; ?>" size="50">
            <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
          </div></td>
        </tr>
        <tr>
          <td align="right"><?php print $ls_nomestpro2; ?></td>
          <td height="22"><div align="left">
            <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center" value="<?php print $ls_codestpro2; ?>" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>">
            <a href="javascript: ue_buscarcodestpro('2');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2; ?>" size="50">
          </div></td>
        </tr>
        <tr>
          <td align="right"><?php print $ls_nomestpro3; ?></td>
          <td height="22"><div align="left">
            <input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center" value="<?php print $ls_codestpro3; ?>" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>">
            <a href="javascript: ue_buscarcodestpro('3');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3; ?>" size="50">
          </div></td>
        </tr>
		<?php
			if($ls_modalidad==2)
			{
		 ?>
        <tr>
          <td align="right"><?php print $ls_nomestpro4; ?></td>
          <td height="22"><div align="left">
            <input name="txtcodestpro4" type="text" id="txtcodestpro4" style="text-align:center" value="<?php print $ls_codestpro4; ?>" size="<?php print ($li_len4+10); ?>" maxlength="<?php print $li_len4; ?>">
            <a href="javascript: ue_buscarcodestpro('4');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4; ?>" size="50">
          </div></td>
        </tr>
        <tr>
          <td align="right"><?php print $ls_nomestpro5; ?></td>
          <td height="22"><div align="left">
            <input name="txtcodestpro5" type="text" id="txtcodestpro5" style="text-align:center" value="<?php print $ls_codestpro5; ?>" size="<?php print ($li_len5+10); ?>" maxlength="<?php print $li_len5; ?>">
            <a href="javascript: ue_buscarcodestpro('5');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5; ?>" size="50">
          </div></td>
        </tr>
		<?php 
			}
		?>
        <tr>
          <td width="103"><div align="right">Presupuestario</div></td>
          <td width="473" height="22"><div align="left">
              <input name="txtctaspg" type="text" id="txtctaspg" value="<?php print $ls_ctadep?>" size="25" style="text-align:center ">
              <a href="javascript: ue_buscarspg();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdenctaspg" type="text" class="sin-borde" id="txtdenctaspg" size="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="24"><div align="right">Contabilidad</div></td>
          <td height="22"><div align="left">
              <input name="txtctacon" type="text" id="txtctacon" value="<?php print $ls_ctacon?>" size="25" style="text-align:center ">
              <a href="javascript: ue_buscarscg();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" size="50" readonly>
          </div></td>
        </tr>
      </table>
      </div></td>
    </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right"></div></td>
    <td colspan="2"><div align="right"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools/grabar.gif" alt="Guardar" width="20" height="20" border="0">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0">Cancelar</a> </div></td>
    </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_buscarcodestpro(ls_nivel)
{
	f=document.form1;
	ls_estcla=f.estcla.value;
	switch(ls_nivel)
	{
		case"1":
			window.open("sigesp_cat_public_estpro1.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
		break;
		case"2":
			ls_codestpro1=f.txtcodestpro1.value;
			if(ls_codestpro1!="")
			{
				window.open("sigesp_cat_public_estpro2.php?codestpro1="+ls_codestpro1+"&estcla="+ls_estcla,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar el nivel anterior");
			}
		break;
		case"3":
			ls_codestpro1=f.txtcodestpro1.value;
			ls_codestpro2=f.txtcodestpro2.value;
			if(ls_codestpro2!="")
			{
				window.open("sigesp_cat_public_estpro3.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&estcla="+ls_estcla,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
			}
			else
			{
				if((ls_codestpro1=="")&&(ls_codestpro2==""))
				{
					window.open("sigesp_cat_public_estpro3.php?tipo=completo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
				}
				else
				{
					alert("Debe seleccionar el nivel anterior");
				}
			}
		break;
		case"4":
			ls_codestpro1=f.txtcodestpro1.value;
			ls_codestpro2=f.txtcodestpro2.value;
			ls_codestpro3=f.txtcodestpro3.value;
			if(ls_codestpro3!="")
			{
				window.open("sigesp_cat_public_estpro4.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&estcla="+ls_estcla,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar el nivel anterior");
			}
		break;
		case"5":
			ls_codestpro1=f.txtcodestpro1.value;
			ls_codestpro2=f.txtcodestpro2.value;
			ls_codestpro3=f.txtcodestpro3.value;
			ls_codestpro4=f.txtcodestpro4.value;
			if(ls_codestpro4!="")
			{
				window.open("sigesp_cat_public_estpro5.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&estcla="+ls_estcla,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
			}
			else
			{
					if((ls_codestpro1=="")&&(ls_codestpro2=="")&&(ls_codestpro3=="")&&(ls_codestpro4==""))
					{
						window.open("sigesp_cat_public_estpro5.php?tipo=completo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
					}
					else
					{
						alert("Debe seleccionar el nivel anterior");
					}
			}
		break;
	}
}
function ue_buscarspg()
{
	f=document.form1;
	ls_modalidad=f.hidestmodest.value;
	ls_estcla=f.estcla.value;
	if(ls_modalidad==2)
	{
		ls_codestpro1=f.txtcodestpro1.value;
		ls_codestpro2=f.txtcodestpro2.value;
		ls_codestpro3=f.txtcodestpro3.value;
		ls_codestpro4=f.txtcodestpro4.value;
		ls_codestpro5=f.txtcodestpro5.value;
		if(ls_codestpro5!="")
		{
			window.open("sigesp_cat_public_ctasspg.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar los niveles");
		}
	}
	else
	{
		ls_codestpro1=f.txtcodestpro1.value;
		ls_codestpro2=f.txtcodestpro2.value;
		ls_codestpro3=f.txtcodestpro3.value;
		if(ls_codestpro3!="")
		{
			window.open("sigesp_cat_public_ctasspg.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&estcla="+ls_estcla+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar los niveles");
		}
	}
}
function ue_buscarscg()
{
	window.open("sigesp_cat_ctasscg.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_rotulacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_guardar()
{
	f=document.form1;
	ls_ctacon=f.txtctacon.value;
	ls_ctacon=ls_ctacon.substr(0,3);
	if(ls_ctacon=="225")
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_depreciacion.php";
		f.submit();
	}
	else
	{
		alert("La cuenta contable debe ser del grupo de las '225'");
	}
}

function ue_cancelar()
{
	window.close();
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas()
{
	if (event.keyCode==39)
	{
		event.returnValue = false;
	}
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

</script> 
</html>