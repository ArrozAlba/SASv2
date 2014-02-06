<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_codper=trim($_GET["codper"]);
	$ls_codperenc=trim($_GET["codperenc"]);
	$ls_nomper=trim($_GET["nomper"]);
	$ls_nomperenc=trim($_GET["nomperenc"]);
	$ls_codnomenc=trim($_GET["codnomenc"]);	

   //--------------------------------------------------------------
   function uf_print($as_codper, $as_codperenc, $as_codnomenc)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	  Description: Función que obtiene e imprime los conceptos a pagar por encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("sigesp_sno_c_evaluador.php");
		$io_evaluador=new sigesp_sno_c_evaluador();			
		require_once("sigesp_sno_c_calcularencargaduria.php");
		$io_calenc=new sigesp_sno_c_calcularencargaduria();	
						
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$lb_valido=true;
		$lb_hay=false;	
		
		if ($as_codnomenc==$ls_codnom) // caso de encargadurias dentro de la misma nómina. Se muestran las diferencias de Conceptos
		{
			
			print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100>Código Concepto</td>";
			print "<td width=320>Denominación</td>";
			print "<td width=110>Monto Cargo Encargado</td>";
			print "<td width=110>Monto Cargo Actual</td>";
			print "<td width=110>Diferencia</td>";
			print "</tr>";
			$ls_sql="SELECT sno_conceptopersonal.codconc, sno_concepto.nomcon, sno_concepto.forcon,".
					"   sno_concepto.valmincon,  sno_concepto.valmaxcon, sno_concepto.sigcon".
					"  FROM sno_conceptopersonal, sno_concepto ".
					" WHERE sno_conceptopersonal.codemp='".$ls_codemp."' ".
					"   AND sno_conceptopersonal.codnom='".$ls_codnom."' ".
					"   AND sno_conceptopersonal.codper='".$as_codper."' ".	
					"   AND sno_conceptopersonal.aplcon ='1'".				
					"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
					"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
					"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ".
					"   AND sno_concepto.conperenc = '1'";
												
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			}
			else
			{
				$lb_hay=$rs_data->RecordCount();
				$lb_valido=$io_evaluador->uf_crear_personalnomina($as_codper);
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_codcon=$rs_data->fields["codconc"];				
					$ls_nomcon=$rs_data->fields["nomcon"];
					$ls_forcon=$rs_data->fields["forcon"];
					$ld_valmincon=$rs_data->fields["valmincon"];
					$ld_valmaxcon=$rs_data->fields["valmaxcon"];			
					$ls_sigcon=trim($rs_data->fields["sigcon"]);
					$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codcon;					
					if ($lb_valido)
					{
					
						$lb_valido=$io_evaluador->uf_evaluar($as_codper,$ls_forcon,$ld_valcon);
						if($lb_valido)
						{
							if($ad_valmincon>0)//verifico el minimo del concepto 
							{
								if($ld_valcon<$ld_valmincon)
								{
									$ld_valcon=$ld_valmincon;
								}
							}
							if($ad_valmaxcon>0)//verifico el maximo del concepto
							{
								if($ld_valcon>$ld_valmaxcon)
								{
									$ld_valcon=$ld_valmaxcon;
								}
							}
							
						}
						
						$lb_valido=$io_calenc->uf_buscar_concepto_encargado($as_codperenc, $ls_codcon,$ld_valconenc);
						
						$ld_dif=abs($ld_valcon - $ld_valconenc);
						
						 
						if (($ls_sigcon=='A')||($ls_sigcon=='B'))
						{
							$ld_totdif=$ld_totdif + $ld_dif;	
						}
						else if (($ls_sigcon=='D')||($ls_sigcon=='P')||($ls_sigcon=='E'))
						{
							$ld_totdif=$ld_totdif - $ld_dif;
							$ld_valconenc=$ld_valconenc * -1;
							$ld_valcon=$ld_valcon * -1;
							$ld_dif=$ld_dif * -1;
						}
								
						print "<tr class=celdas-blancas>";
						print "<td align='center'>".$ls_codcon."</td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td align='right'>".number_format ($ld_valcon,2,",",".")."</td>";
						print "<td align='right'>".number_format ($ld_valconenc,2,",",".")."</td>";
						print "<td align='right'>".number_format ($ld_dif,2,",",".")."</td>";
						print "</tr>";			
					}	
					$rs_data->MoveNext();
				}
			}
				
			if (($lb_hay>0)&&($lb_valido))
			{
				print "<tr class=celdas-blancas>";
				print "<td></td>";			
				print "<td align='right' colspan=3><B>TOTAL A PAGAR EN CONCEPTO RESUMEN ENCARGADURIA</B></td>";
				print "<td align='right'>".number_format($ld_totdif,2,",",".")."</td>";
				print "</tr>";
			}
			elseif ($lb_valido)
			{
				print("<script language=JavaScript>");
				print(" alert('No hay conceptos asociados a encargadurias.');");
				print(" close();");
				print("</script>");
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrió un error en el cálculo de la Encargaduría.');");
				print(" close();");
				print("</script>");
			}
			$io_sql->free_result($rs_data);
		}
		else //caso de encargadurias es entre nóminas diferentes. Se muestran las Conceptos a Pagar
		{
			
			print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100>Código Concepto</td>";
			print "<td width=320>Denominación</td>";
			print "<td width=110>Monto Cargo Encargado</td>";
			print "</tr>";
			$ls_sql="SELECT sno_conceptopersonal.codconc, sno_concepto.nomcon, sno_concepto.forcon,".
					"   sno_concepto.valmincon,  sno_concepto.valmaxcon, sno_concepto.sigcon ".
					"  FROM sno_conceptopersonal, sno_concepto ".
					" WHERE sno_conceptopersonal.codemp='".$ls_codemp."' ".
					"   AND sno_conceptopersonal.codnom='".$ls_codnom."' ".
					"   AND sno_conceptopersonal.codper='".$as_codper."' ".				
					"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
					"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
					"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ".
					"   AND sno_concepto.conperenc = '1'";								
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			}
			else
			{
				$ld_totdif=0;
				$lb_hay=$rs_data->RecordCount();
				$lb_valido=$io_evaluador->uf_crear_personalnomina($as_codper);
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_codcon=$rs_data->fields["codconc"];
					$ls_nomcon=$rs_data->fields["nomcon"];
					$ls_forcon=$rs_data->fields["forcon"];					
					$ld_valmincon=$rs_data->fields["valmincon"];
					$ld_valmaxcon=$rs_data->fields["valmaxcon"];			
					$ls_sigcon=trim($rs_data->fields["sigcon"]);					
					$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codcon;				
					
					if ($lb_valido)
					{
					
						$lb_valido=$io_evaluador->uf_evaluar($as_codper,$ls_forcon,$ld_valcon);
						if($lb_valido)
						{
							if($ad_valmincon>0)//verifico el minimo del concepto 
							{
								if($ld_valcon<$ld_valmincon)
								{
									$ld_valcon=$ld_valmincon;
								}
							}
							if($ad_valmaxcon>0)//verifico el maximo del concepto
							{
								if($ld_valcon>$ld_valmaxcon)
								{
									$ld_valcon=$ld_valmaxcon;
								}
							}
							
						}
						
						if (($ls_sigcon=='A')||($ls_sigcon=='B'))
						{
							$ld_totdif=$ld_totdif + $ld_valcon;
						}
						else if (($ls_sigcon=='D')||($ls_sigcon=='P')||($ls_sigcon=='E'))
						{
							$ld_totdif=$ld_totdif - $ld_valcon;
							$ld_valcon=$ld_valcon * -1;
							
						}
								
						print "<tr class=celdas-blancas>";
						print "<td align='center'>".$ls_codcon."</td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td align='right'>".number_format ($ld_valcon,2,",",".")."</td>";						
						print "</tr>";			
					}	
					$rs_data->MoveNext();
				}
			}
				
			if (($lb_hay>0)&&($lb_valido))
			{
				print "<tr class=celdas-blancas>";
				print "<td align='right' colspan=2><B>TOTAL A PAGAR</B></td>";
				print "<td align='right'>".number_format($ld_totdif,2,",",".")."</td>";
				print "</tr>";
			}
			elseif ($lb_valido)
			{
				print("<script language=JavaScript>");
				print(" alert('No hay conceptos asociados a encargadurias.');");
				print(" close();");
				print("</script>");
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrió un error en el cálculo de la Encargaduría.');");
				print(" close();");
				print("</script>");
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
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>C&aacute;lculo de Enargadur&iacute;a</title>
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
</p>
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="600" height="20" colspan="2" class="titulo-ventana">Conceptos a Pagar por Enargadur&iacute;a</td>
    </tr>
  </table>
<br>
    <table width="600" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="160" height="22"><div align="right">C&oacute;digo Personal</div></td>
        <td width="417"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="16" style="text-align:center" value="<?php print ($ls_codper);?>" readonly>     <input name="txtnomper" type="text" id="txtnomper" size="50" style="text-align:left" value="<?php print ($ls_nomper);?>" class="sin-borde" readonly>    
        </div></td>
      </tr> 
	  <tr>
        <td width="160" height="22"><div align="right">C&oacute;digo Personal Encargado</div></td>
        <td width="417"><div align="left">
          <input name="txtcodperenc" type="text" id="txtcodperenc" size="16" style="text-align:center" value="<?php print ($ls_codperenc);?>" readonly>     <input name="txtnomperenc" type="text" id="txtnomperenc" size="50" style="text-align:left" value="<?php print ($ls_nomperenc);?>" class="sin-borde" readonly>    
        </div></td>
      </tr>      
</table>
  <br>
  <?php
  	 uf_print($ls_codper, $ls_codperenc, $ls_codnomenc);   
  ?>
  <br>

</div>
</body>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>

</html>
