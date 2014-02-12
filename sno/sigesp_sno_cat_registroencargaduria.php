<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_codenc,$as_codper,$as_codperenc,$as_estenc,$ai_subnomina,$as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public		
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_tipnom=$_SESSION["la_nomina"]["tipnom"];
		$ls_criterio="";		
		if ($as_tipo=="REVERSO")
		{
			$ls_criterio=" AND sno_encargaduria.estenc ='1' ";
			 
		}
		elseif ($as_tipo=="REGISTRO")
		{
			$ls_criterio=" AND sno_encargaduria.codperenc like '".$as_codperenc."' ";
		}
		
		print "<table width=620 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=70>Código</td>";
		print "<td width=70>Fecha Inicio</td>";
		print "<td width=70>Fecha Finalizacion</td>";
		print "<td width=210>Personal / Personal Encargado</td>";		
		print "<td width=200>Observación</td>";		
		print "</tr>";		
		
		$ls_sql="SELECT  sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personal,nomper, sno_personal.apeper,  ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, sno_personalnomina.codunirac, sno_subnomina.dessubnom,sno_unidadadmin.desuniadm, ".
				"		sno_personalnomina.prouniadm,  sno_personalnomina.codcar,  sno_personalnomina.coddep, ".
				"       (SELECT desnom FROM sno_nomina                 ".
				"         WHERE sno_nomina.codemp=sno_encargaduria.codemp             ".
				"           AND sno_nomina.codnom=sno_encargaduria.codnomperenc) AS desnomenc, ".			
				"       (SELECT srh_departamento.coddep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab, ".			
				"		(SELECT nomper FROM sno_personal ".
				"		   WHERE sno_encargaduria.codemp = sno_personal.codemp ".
				"			 AND sno_encargaduria.codperenc = sno_personal.codper) as nomperenc, ".				
				"		(SELECT apeper FROM sno_personal ".
				"		   WHERE sno_encargaduria.codemp = sno_personal.codemp ".
				"			 AND sno_encargaduria.codperenc = sno_personal.codper) as apeperenc, ".				
				"   sno_encargaduria.codenc, sno_encargaduria.fecinienc, sno_encargaduria.fecfinenc, ".
				"   sno_encargaduria.codper, sno_encargaduria.codperenc, sno_encargaduria.codnomperenc, ".
				"   sno_encargaduria.estenc, sno_encargaduria.obsenc,sno_encargaduria.estsuspernom ".	
				"  FROM sno_personalnomina, sno_encargaduria,sno_unidadadmin,sno_personal,sno_subnomina ".			
				" 	WHERE sno_encargaduria.codemp = '".$ls_codemp."'".
				"   AND sno_encargaduria.codnom = '".$ls_codnom."' ".				
				"   AND sno_encargaduria.codenc like '".$as_codenc."' ".
				"   AND sno_encargaduria.codper like '".$as_codper."' ".
				"   AND sno_encargaduria.estenc like '".$as_estenc."' ".$ls_criterio.							
				"   AND sno_personalnomina.codemp = sno_encargaduria.codemp ".
				"   AND sno_personalnomina.codnom = sno_encargaduria.codnom ".
				"   AND sno_personalnomina.codper = sno_encargaduria.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"  ORDER BY sno_encargaduria.codenc";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codenc=$row["codenc"];
				$ls_obsenc=trim($row["obsenc"]);
				$ld_fecinienc=$io_funciones->uf_convertirfecmostrar($row["fecinienc"]);				
				$ld_fecfinenc=$io_funciones->uf_convertirfecmostrar($row["fecfinenc"]);
				if ($ld_fecfinenc=='01/01/1900')
				{
					$ld_fecfin='S/F';
				}	
				else
				{
					$ld_fecfin=$ld_fecfinenc;
				}
				$ls_codperenc=$row["codperenc"];
				$ls_codnomperenc=$row["codnomperenc"];
				$ls_nomperenc=$row["nomperenc"]." ".$row["apeperenc"];
				$ls_estenc=$row["estenc"];	
				if ($ls_estenc=='1')			
				{
					$ls_estenc='ACTIVA';
				}
				else
				{
					$ls_estenc='FINALIZADA';
				}
				$ls_codper=$row["codper"];				
				$ls_nomper=$row["nomper"]." ".$row["apeper"];				
				$ls_codsubnom=$row["codsubnom"];
				$ls_dessubnom=$row["dessubnom"];
				$ls_codasicar=$row["codasicar"];
				$ls_denasicar=$row["denasicar"];
				$ls_codcar=$row["codcar"];
				$ls_descar=$row["descar"];
				$ls_codtab=$row["codtab"];
				$ls_destab=$row["destab"];
				$ls_codgra=$row["codgra"];
				$ls_codpas=$row["codpas"];
				$ls_codunirac=$row["codunirac"];
								
				$ls_coduniadm=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];			
				$ls_desuniadm=$row["desuniadm"];				
				$ls_coddep=$row["coddep"];
				$ls_dendep=$row["dendep"];
				$ls_desnomenc=$row["desnomenc"];
				$ls_estsuspernom=$row["estsuspernom"];
				
				print "<tr class=celdas-blancas>";
				switch ($as_tipo)
				{
					case "REGISTRO" :
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codenc','$ls_obsenc','$ld_fecinienc','$ld_fecfinenc','$ls_codperenc','$ls_codnomperenc','$ls_nomperenc','$ls_estenc','$ls_codper','$ls_nomper','$ls_codsubnom' ,'$ls_dessubnom', '$ls_codasicar','$ls_denasicar', '$ls_codcar','$ls_descar', '$ls_codtab', '$ls_destab','$ls_codgra', '$ls_codpas','$ls_coduniadm','$ls_desuniadm','$ls_coddep', '$ls_dendep','$ls_codunirac', '$li_tipnom','$ai_subnomina','$ls_estsuspernom');\">".$ls_codenc."</a></td>";
						
					break;
					
					case "REVERSO" :
						print "<td align='center'><a href=\"javascript: aceptarreverso('$ls_codenc','$ls_obsenc','$ld_fecinienc','$ld_fecfinenc','$ls_codperenc','$ls_codnomperenc','$ls_nomperenc','$ls_estenc','$ls_codper','$ls_nomper','$ls_desnomenc','$ls_estsuspernom');\">".$ls_codenc."</a></td>";
						
					break;
					
					case "REPCODENCDES" :
						print "<td align='center'><a href=\"javascript: aceptarrepcodencdes('$ls_codenc');\">".$ls_codenc."</a></td>";
						
					break;
					
					case "REPENCHAS" :
						print "<td align='center'><a href=\"javascript: aceptarrepcodenchas('$ls_codenc');\">".$ls_codenc."</a></td>";
						
					break;
				}
				print "<td align='center'>".$ld_fecinienc."</td>";
				print "<td align='center'>".$ld_fecfin."</td>";
				print "<td>".$ls_nomper.' / '.$ls_nomperenc."</td>";
				print "<td>".$ls_obsenc."</td>";
				print "</tr>";	
					
					
					
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Registro de Encargaduría</title>
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

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print ($ls_tipo); ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Registro de Encargaduría</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="115" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="379"><div align="left">
          <input name="txtcodenc" type="text" id="txtcodenc" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Personal </div></td>
        <td><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="10" maxlength="100">
          <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="50" maxlength="100" readOnly>
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Personal Encargado</div></td>
        <td><div align="left">
          <input name="txtcodperenc" type="text" id="txtcodperenc" size="10" maxlength="100">
          <a href="javascript: ue_buscarpersonal_encargado();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtnomperenc" type="text" class="sin-borde" id="txtnomperenc" size="50" maxlength="100" readOnly>
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Estatus</div></td>
        <td colspan="3">
          
            <div align="left">
              <select name="cmbestenc" id="cmbestenc">
                <option value="1" selected>Activa</option>
                <option value="2">Finalizada</option>
              </select>
            </div></td>
        </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();	
	$li_subnomina=$io_fun_nomina->uf_obtenervalor_get("subnom","0");
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codenc="%".$_POST["txtcodenc"]."%";
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_codperenc="%".$_POST["txtcodperenc"]."%";
		$ls_estatus=$_POST["cmbestenc"];
		uf_print($ls_codenc,$ls_codper,$ls_codperenc,$ls_estatus,$li_subnomina,$ls_tipo);
	}
	
	unset($io_fun_nomina);	
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_codenc,ls_obsenc,ld_fecinienc,ld_fecfinenc,ls_codperenc,ls_codnomperenc,ls_nomperenc,ls_estenc,ls_codper,ls_nomper,ls_codsubnom ,ls_dessubnom, ls_codasicar,ls_denasicar, ls_codcar,ls_descar,ls_codtab, ls_destab,ls_codgra,ls_codpas,ls_coduniadm,ls_desuniadm,ls_coddep,ls_dendep,ls_codunirac,li_tipnom,li_subnomina,ls_estsuspernom)
{
	opener.document.form1.txtcodenc.value=ls_codenc;
	opener.document.form1.txtcodenc.readOnly=true;
    opener.document.form1.txtobsenc.value=ls_obsenc;
	opener.document.form1.txtestenc.value=ls_estenc;
	opener.document.form1.txtfecinienc.value=ld_fecinienc;
	opener.document.form1.txtfecfinenc.value=ld_fecfinenc;	
	opener.document.form1.txtcodperenc.value=ls_codperenc;
	opener.document.form1.txtnomperenc.value=ls_nomperenc;
	opener.document.form1.cmbnomina.value=ls_codnomperenc;	
	opener.document.form1.txtcodper.value=ls_codper;
	opener.document.form1.txtnomper.value=ls_nomper;
	if(opener.document.form1.rac.value=="0")
	{
    	opener.document.form1.txtcodcar.value=ls_codcar;
    	opener.document.form1.txtdescar.value=ls_descar;		
		if ((li_tipnom=="3")||(li_tipnom=="4"))
		{
			opener.document.form1.txtgrado.value=ls_grado;
		}
	}
	else
	{
		if ((li_tipnom!="3")&&(li_tipnom!="4"))
		{
			opener.document.form1.txtcodtab.value=ls_codtab;
			opener.document.form1.txtdestab.value=ls_destab;
			opener.document.form1.txtcodgra.value=ls_codgra;
			opener.document.form1.txtcodpas.value=ls_codpas;
			opener.document.form1.txtcodasicar.value=ls_codasicar;
		    opener.document.form1.txtdenasicar.value=ls_denasicar;
		}
		else
		{
			opener.document.form1.txtgrado.value=ls_grado;
			opener.document.form1.txtcodasicar.value=ls_codasicar;
		    opener.document.form1.txtdenasicar.value=ls_denasicar;
		}
	}	
    opener.document.form1.txtcoduniadm.value=ls_coduniadm;
    opener.document.form1.txtdesuniadm.value=ls_desuniadm;	
	opener.document.form1.txtcoddep.value=ls_coddep;
    opener.document.form1.txtdendep.value=ls_dendep;   
	if(li_subnomina==1)
	{
    	opener.document.form1.txtcodsubnom.value=ls_codsubnom;
    	opener.document.form1.txtdessubnom.value=ls_dessubnom;
	}
    
	if((opener.document.form1.rac.value=="1")&&(opener.document.form1.codunirac.value=="1"))
	{
	    opener.document.form1.txtcodunirac.value=ls_codunirac;
	} 	
	
	if (ls_estsuspernom=='1')
	{
		opener.document.form1.chksuspernom.checked=true;
	}
	else
	{
		opener.document.form1.chksuspernom.checked=false;
	}
	
	opener.document.form1.existe.value="TRUE";		
	opener.document.form1.operacion.value="BUSCAR";
  	opener.document.form1.action="sigesp_sno_p_registrarencargaduria.php";
  	opener.document.form1.submit();
	close();
}



function aceptarreverso(ls_codenc,ls_obsenc,ld_fecinienc,ld_fecfinenc,ls_codperenc,ls_codnomperenc,ls_nomperenc,ls_estenc,ls_codper,ls_nomper,ls_desnomenc,ls_estsuspernom)
{
	opener.document.form1.txtcodenc.value=ls_codenc;
	opener.document.form1.txtcodenc.readOnly=true;
    opener.document.form1.txtobsenc.value=ls_obsenc;
	opener.document.form1.txtestenc.value=ls_estenc;
	opener.document.form1.txtfecinienc.value=ld_fecinienc;
	opener.document.form1.txtfecfinenc.value=ld_fecfinenc;	
	opener.document.form1.txtcodper.value=ls_codper;
	opener.document.form1.txtnomper.value=ls_nomper;
	opener.document.form1.txtcodperenc.value=ls_codperenc;
	opener.document.form1.txtnomperenc.value=ls_nomperenc;
	opener.document.form1.txtcodnomenc.value=ls_codnomperenc;
	opener.document.form1.txtdesnomenc.value=ls_desnomenc;	
	opener.document.form1.txtestsuspernom.value=ls_estsuspernom;	 	
	close();
}

function aceptarrepcodencdes(ls_codenc)
{
	opener.document.form1.txtcodencdes.value=ls_codenc;		
	close();
}

function aceptarrepcodenchas(ls_codenc)
{
	opener.document.form1.txtcodenchas.value=ls_codenc;		
	close();
}


function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=catencargaduria1","_blank1","catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonal_encargado()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=catencargaduria2","_blank2","catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_registroencargaduria.php?tipo=<?PHP print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
