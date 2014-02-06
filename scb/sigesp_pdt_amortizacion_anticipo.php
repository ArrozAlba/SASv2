<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }   
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Programaciones de pago a Proveedores</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
	<input name="monto1" type="hidden" id="monto1" >  
    <input name="monto2" type="hidden" id="monto2" > 
</p>
  <br>
  <div align="center">   
	<p><br>
<?php
    
   if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];	
		$provbene=$_POST["provbene"];
		$tipproben=$_POST["tipproben"];
		$ls_montochq=$_POST["montochq"];
		$cuenta_bco=$_POST["sc_cuenta"];
		$cta_banco=$_POST["ctaban"];
		$codban=$_POST["codban"];
		$documento=$_POST["docum"];
		$montret=$_POST["montret"];			
	}
	else
	{
		$ls_operacion="NUEVO";
		$provbene=$_GET["provbene"]; 
		$tipproben=$_GET["tipproben"];
		$ls_montochq=$_GET["montochq"];
		$cuenta_bco=$_GET["sc_cuenta"];
		$cta_banco=$_GET["ctaban"];
		$codban=$_GET["codban"];
		$documento=$_GET["docum"];
		$montret=$_GET["montret"];
		$ls_mov_operacion=$_GET["mov_operacion"];
		$ldec_monobjret=$_GET["montobjret"]; 
		$ld_fecha=$_GET["fecha"];
		$ls_desmov=$_GET["dencon"];
		$ls_codconmov=$_GET["codmov"];
		$ls_desproben=$_GET["desproben"];
		$ls_mov_operacion=$_GET["mov_operacion"];
		$ls_chevau=$_GET["chevau"];
		$ls_estmov=$_GET["estmov"];
		$ls_codfuefin=$_GET["fuente"];
		$ls_valido=false;
	}
	require_once("class_funciones_banco.php");
	$io_scb= new class_funciones_banco();
    $io_scb->uf_load_seguridad("SCB","sigesp_scb_p_emision_chq.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
	
    function uf_print(&$totrow, $provbene, $tipproben)
	{
		require_once("../shared/class_folder/grid_param.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in		    = new sigesp_include();
		$con	    = $in->uf_conectar();
		$io_msg	    = new class_mensajes();
		$io_sql	    = new class_sql($con);
		$io_funcion = new class_funciones();		
		$arr	    = $_SESSION["la_empresa"];		
		$ls_codemp  = $arr["codemp"];
		$grid = new grid_param();		
		//----------------------------------------------------------------------------------------				
		if ($tipproben=="P")
		{
			$cod_prov=$provbene;
			$ced_bene='----------';
		}
		else
		{
			$cod_prov='----------';
			$ced_bene=$provbene;
		}
		//------------------------------------------------------------------------------------------				
		$title[1]="Documento";   		
		$title[2]="Total Anticipo";
		$title[3]="Saldo";
		$title[4]="Amortización"; 
		$grid1="grid";
		
		$ls_sql= "  SELECT scb_movbco_anticipo.codemp, scb_movbco_anticipo.codban, scb_movbco_anticipo.ctaban, 
						   scb_movbco_anticipo.numdoc, scb_movbco_anticipo.codope, scb_movbco_anticipo.estmov, 
						   scb_movbco_anticipo.codamo, scb_movbco_anticipo.monamo, scb_movbco_anticipo.monsal, 
						   scb_movbco_anticipo.montotamo, scb_movbco_anticipo.sc_cuenta,
						   scb_movbco.cod_pro, scb_movbco.ced_bene, scb_banco.nomban,
						   (SELECT  rpc_proveedor.sc_cuenta
							  FROM  rpc_proveedor 
							 WHERE  rpc_proveedor.codemp=scb_movbco.codemp
							   AND  rpc_proveedor.cod_pro=scb_movbco.cod_pro)  as cta_pro,  
							(SELECT rpc_beneficiario.sc_cuenta
							  FROM  rpc_beneficiario 
							 WHERE  rpc_beneficiario.codemp=scb_movbco.codemp
							   AND  rpc_beneficiario.ced_bene=scb_movbco.ced_bene)  as cta_bene  
					  FROM scb_movbco_anticipo
					  JOIN scb_movbco ON (scb_movbco.codemp = scb_movbco_anticipo.codemp
									 AND  scb_movbco.codban = scb_movbco_anticipo.codban
									 AND  scb_movbco.ctaban = scb_movbco_anticipo.ctaban
									 AND  scb_movbco.numdoc = scb_movbco_anticipo.numdoc
									 AND  scb_movbco.codope = scb_movbco_anticipo.codope
									 AND  scb_movbco.estmov = scb_movbco_anticipo.estmov)
					  JOIN scb_banco ON (scb_banco.codemp=scb_movbco_anticipo.codemp
                                    AND  scb_banco.codban=scb_movbco_anticipo.codban)
					  WHERE scb_movbco_anticipo.codemp='".$ls_codemp."'
						AND scb_movbco_anticipo.estmov='C'
						AND scb_movbco.estant='1'
						AND scb_movbco.cod_pro='".$cod_prov."'
						AND scb_movbco.ced_bene='".$ced_bene."'
						AND scb_movbco_anticipo.monsal>0"; 
						
						
		$rs_data=$io_sql->select($ls_sql);
		if(($rs_data===false))
		{
			//$io_msg->message("Error en select");
		}
		else
		{
			$totrow=$io_sql->num_rows($rs_data); 
			$ls_monamo    = 0;
			$z=0;			
			if ($totrow>0)
			{
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_codban     = $row["codban"];
					$ls_cta       = $row["ctaban"];
					$ls_montotant = $row["montotamo"];
					$ls_saldo     = $row["monsal"];
					$ls_numdoc     = $row["numdoc"];
					$ls_codamo     = $row["codamo"];
					$ls_codope     = $row["codope"];
					$ls_estmov     = $row["estmov"];
					$ls_scgcta     = trim($row["sc_cuenta"]);
					$ls_montoamo2  = $row["monamo"];
					$ls_codpro     = $row["cta_pro"];   
					$ls_cedbene     = $row["cta_bene"];    
					$z++;
					$object[$z][1]="<input name=txtbnumdoc".$z." type=text id=txtnumdoc value='".$ls_numdoc."' class=sin-borde  size=20 maxlength=20 style=text-align:center readonly>
					                <input name=txtcodamo".$z." type=hidden id=txtcodamo value='".$ls_codamo."' readonly>
									<input name=txtcodban".$z." type=hidden id=txtcodban value='".$ls_codban."' readonly>
									<input name=txtcta".$z." type=hidden id=txtcta value='".$ls_cta."' readonly>
									<input name=txtcodope".$z." type=hidden id=txtcodope value='".$ls_codope."' readonly>
									<input name=txtestmov".$z." type=hidden id=txtestmov value='".$ls_estmov."' readonly>
									<input name=txtsccta".$z." type=hidden id=txtsccta value='".$ls_scgcta."' readonly>
									<input name=txtmontamo2".$z." type=hidden id=txtmontamo2 value='".$ls_montoamo2 ."' readonly>
									<input name=txtcodpro".$z." type=hidden id=txtcodpro value='".$ls_codpro."' readonly>
									<input name=txtcedbene".$z." type=hidden id=txtcedbene value='".$ls_cedbene."' readonly>";				  		
				    $object[$z][2]="<input type=text name=txtmontotant".$z." value='".number_format($ls_montotant,2,",",".")."' id=txtmontotantp class=sin-borde readonly style=text-align:right size=20 maxlength=20>";	
				    $object[$z][3]="<input type=text name=txtmonsal".$z." value='".number_format($ls_saldo,2,",",".")."' id=txtmonsal class=sin-borde readonly style=text-align:right size=10 maxlength=10>";
				    $object[$z][4]="<input type=text name=txtmonamor".$z." value='".$ls_monamo."' id=txtmonamor class=sin-borde onKeyPress=return(ue_formatonumero(this,'.',',',event)); style=text-align:right size=10 maxlength=10>";								
				}// fin del while
			}
			else
			{      $z=1;
				   $object[1][1]="<input name=txtbnumdoc".$z." type=text id=txtnumdoc value='' class=sin-borde  size=20 maxlength=20 readonly>
				                  <input name=txtcodamo".$z." type=hidden id=txtcodamo value='' readonly>
								  <input name=txtcodban".$z." type=hidden id=txtcodban value='' readonly>
								  <input name=txtcta".$z." type=hidden id=txtcta value='' readonly>
								  <input name=txtcodope".$z." type=hidden id=txtcodope value='' readonly>
								  <input name=txtestmov".$z." type=hidden id=txtestmov value='' readonly>
								  <input name=txtsccta".$z." type=hidden id=txtsccta value='' readonly>
								  <input name=txtmontamo2".$z." type=hidden id=txtmontamo2 value='' readonly>
								  <input name=txtcodpro".$z." type=hidden id=txtcodpro value='' readonly>
								  <input name=txtcedbene".$z." type=hidden id=txtcedbene value='' readonly>";				  		
				   $object[1][2]="<input type=text name=txtmontotant".$z." value='' id=txtmontotantp class=sin-borde readonly style=text-align:right size=15 maxlength=15>";	
				   $object[1][3]="<input type=text name=txtmonsal".$z." value='' id=txtmonsal class=sin-borde readonly style=text-align:right size=10 maxlength=10>";
				   $object[1][4]="<input type=text name=txtmonamor".$z." value='' id=txtmonamor class=sin-borde onKeyPress=return(ue_formatonumero(this,'.',',',event)); style=text-align:left size=10 maxlength=10>";								
			}   
			  
			$grid->makegrid($totrow,$title,$object,400,'Amortización de Anticipos',$grid1);
			print "</table>";		
		}		
	}// fin de la funcion print
	 
	if($ls_operacion=="NUEVO")
	{
		uf_print($totrow, $provbene, $tipproben);
	}
	
	if($ls_operacion=="GUARDAR")
	{    
	     require_once("../shared/class_folder/class_mensajes.php");
		 $io_msg		= new class_mensajes();
		 require_once("sigesp_scb_c_emision_chq.php");
		 $io_emision = new sigesp_scb_c_emision_chq();
		 $ls_valido=true; 
		 $totrow=$io_scb->uf_obtenervalor("total","");
		 $ls_valido=true;
		 //------informaciòn del cheque en emision de cheque-----------------------------------------------
		 $provbene=$_GET["provbene"]; 
		 $tipproben=$_GET["tipproben"];
		 $ls_montochq=$_GET["montochq"];// monto del cheque
		 $ls_montochq=str_replace(".","",$ls_montochq);
		 $ls_montochq=str_replace(",",".",$ls_montochq);
		 $cuenta_bco=$_GET["sc_cuenta"];// cuenta contable del banco
		 $cta_banco=$_GET["ctaban"];//cuenta bancaria
		 $codban=$_GET["codban"];//codigo del banco
		 $documento=$_GET["docum"];// numero del documento
		 $montret=$_GET["montret"];	// monto retenido 
		 $montret=str_replace(",",".",$montret);		
		 $ldec_monobjret=$_GET["montobjret"]; // monto objeto de retencion
		 $ldec_monobjret=str_replace(".","",$ldec_monobjret);	
		 $ldec_monobjret=str_replace(",",".",$ldec_monobjret);	
		 $ls_mov_operacion=$_GET["mov_operacion"];// tipo de movieminto
		 $ld_fecha=$_GET["fecha"];// fecha de creaciòn del cheque;
		 $ls_desmov=$_GET["dencon"];// denominaciòn del movimiento;
		 $ls_codconmov=$_GET["codmov"];// codigo del movimiento;
		 $ls_chevau=$_GET["chevau"];//el nuemro del voucher
		 $ls_estmov=$_GET["estmov"];// estatus del cheque;
		 $ls_codfuefin=$_GET["fuente"];// codigo de la fuente de financiamiento		 
		 if ($tipproben=="P")
		 {
			$ls_codpro=$provbene;
			$ls_cedbene='----------';
		 }
		 else
		 {
			$ls_codpro='----------';
			$ls_cedbene=$provbene;
		 }
		 $ls_desproben=$_GET["desproben"];// nombre del proveedor o beneficiario
		//-----------------------------------------------------------------------------------------------------------	
		$ls_docant='---------------';
		$ls_montoam=0;
		$ls_valido=$in_classmovbanco->uf_guardar_automatico2($codban,$cta_banco, $documento,
                                                                $ls_mov_operacion,$ld_fecha,$ls_desmov,
															    $ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,																                                                                $ls_montochq,$ldec_monobjret,$montret,																                                                                $ls_chevau,$ls_estmov,0,1,$tipproben,
																'SCBBCH','',"N",$tipproben,$ls_codfuefin,'2',
																$ls_docant,$ls_montoam);//Cheque Amortizado
		  if ($ls_valido)
		  {
				$in_classmovbanco->io_sql->commit();
				$ls_estdoc="C";
				?>
				<script language="javascript">
					f=opener.document.form1;					
					f.status_doc.value='C';//Cambio estatus a actualizable
				</script>	
				<?php
		  }	
		  else
		  {
				$in_classmovbanco->io_sql->rollback();
				$ls_estdoc="N";
				$io_msg->message("No se realizo la amortización, se debe amortizar solo un Anticipo");	
		  }				
	     if ($ls_valido)
		 {
			
			 for ($i=1;$i<=$totrow;$i++)
			 {   
				$ls_codban    = $io_scb->uf_obtenervalor("txtcodban".$i,"");
				$ls_cta       = $io_scb->uf_obtenervalor("txtcta".$i,"");
				$ls_montotant = $io_scb->uf_obtenervalor("txtmontotant".$i,"");
				$ls_saldo     = $io_scb->uf_obtenervalor("txtmonsal".$i,"");
				$ls_numdoc    = $io_scb->uf_obtenervalor("txtbnumdoc".$i,"");
				$ls_codamo    = $io_scb->uf_obtenervalor("txtcodamo".$i,"");
				$ls_codope    = $io_scb->uf_obtenervalor("txtcodope".$i,"");
				$ls_estmov    = $io_scb->uf_obtenervalor("txtestmov".$i,"");
				$ls_scgcta    = $io_scb->uf_obtenervalor("txtsccta".$i,"");	// cuenta contable del anticipo
				$ls_monamo    = $io_scb->uf_obtenervalor("txtmonamor".$i,"");
				$ls_monamo    =str_replace(".","",$ls_monamo);
				$ls_monamo    =str_replace(",",".",$ls_monamo);
				$ls_saldo    = $io_scb->uf_obtenervalor("txtmonsal".$i,"");
				$ls_saldo    =str_replace(".","",$ls_saldo);
				$ls_saldo    =str_replace(",",".",$ls_saldo);
				$ls_monamo2    = $io_scb->uf_obtenervalor("txtmontamo2".$i,"");	
				$ls_ctapro    = $io_scb->uf_obtenervalor("txtcodpro".$i,"");//cuenta conable del proveedor
				$ls_ctabene    = $io_scb->uf_obtenervalor("txtcedbene".$i,"");	//cuenta conatbel del beneficiario			
				//-------------------------------------------------------------------------------------------------------
				if ($ls_monamo>0)
				{  
					if ($ls_monamo>=$ls_montochq)
					{
						$io_msg->message("El monto de amortización debe ser Menor al monto Total del Cheque.... ");	   
					}
					if ($ls_monamo>$ls_saldo)
					{    
						$io_msg->message("El monto de amortización debe ser Menor al Saldo del Anticipo.... ");	   
					}
					else
					{
						$ls_monamoaux=$ls_monamo2+$ls_monamo; 
						$ls_saldo=$ls_saldo-$ls_monamo;	
						$saldo_cheque=0;
						$saldo_cheque= $ls_montochq-$ls_monamo;																	    					
						if ($ls_monamo>0)
						{				
							$ls_valido= $io_emision->uf_guardar_anticipos($ls_codban, $ls_cta, $ls_numdoc, $ls_codope, $ls_estmov,
																		  $ls_codamo, $ls_scgcta, $ls_monamoaux, $ls_saldo);  
							
							?>
							<script language="javascript">
								f=opener.document.form1;
								ldec_temp1=<? print $saldo_cheque?>;
								ldec_temp2=<? print $saldo_cheque?>;	
																			
								document.form1.monto1.value=ldec_temp1;
								document.form1.monto2.value=ldec_temp2;
								
								valor=  document.form1.monto1.value;	
								valor2=	document.form1.monto2.value;							
								
								f.totalchq.value=uf_convertir(valor);
								f.txtmonobjret.value=uf_convertir(valor2);									
							</script>	
							<?php
							$ls_valido=$in_classmovbanco->uf_guardar_automatico2($codban,$cta_banco, $documento,
                                                                $ls_mov_operacion,$ld_fecha,$ls_desmov,
															    $ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,																                                                                $ls_montochq,$ldec_monobjret,$montret,																                                                                $ls_chevau,$ls_estmov,0,1,$tipproben,
																'SCBBCH','',"C",$tipproben,$ls_codfuefin,'2',
																$ls_numdoc,$ls_monamo);//Cheque Amortizado	
						}									   
					}//fin de un else
				}
				else
				{
					$io_msg->message("El monto de amortización debe ser Mayor a Cero...");	  
				}	
			 } // fin del for
			 
			 if ($ls_valido)
			 {
				if ($ls_ctapro!="")
				{
					$scg_probene=$ls_ctapro;
					$ls_desmov="Cuentas por pagar Proveedor";
					$ls_desmov2="Anticipo a Proveedores";
				}
				else
				{
					$scg_probene=$ls_ctabene;
					$ls_desmov="Cuentas por pagar Beneficiario";
					$ls_desmov2="Anticipo a Beneficiarios";
				}				
				$ls_valido=$io_emision->uf_contable_anticipo($codban, $cta_banco, 
															   $documento, "CH", 
																"N", $ls_scgcta, 
																"H",'00000',$documento,
																$ls_desmov2, 'SCBBCH',
																$ls_monamo,$ldec_monobjret);// asiento para el anticipo
				$ls_desmov3="Banco ";
				$ls_montonab=$ls_montochq-$ls_monamo;
			    $ls_valido=$io_emision->uf_contable_anticipo($codban, $cta_banco, 
															   $documento, "CH", 
																"N", $cuenta_bco, 
																"H",'00000',$documento,
																$ls_desmov3, 'SCBBCH',
																$ls_montonab,$ldec_monobjret);//asiento para el banco
						
			 }//fin del if asiento para el proveedor o beneficiario	
			 
		 }// fin del if
		 	  
	}//fin del if guardar
	if($ls_valido)
	{  
		
		uf_print($totrow, $provbene, $tipproben);
	}	
		
?></p>
   <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>          
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="435"><div align="right"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" class="sin-borde">Aceptar</a></div> </td>
        <td width="28">&nbsp;</td>
        <td width="87"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="cancelar" width="15" height="15" class="sin-borde">Cancelar</a></td>
        <td width="50">&nbsp;</td>
      </tr>
    </table>
</div>
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
<input name="status_doc" type="hidden" id="status_doc" value="<?php print $ls_estdoc;?>">  
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function ue_aceptar()
  {
    f=document.form1;
	total=f.total.value;
	f.operacion.value="GUARDAR";		 
	f.action="sigesp_pdt_amortizacion_anticipo.php?total=<?PHP print $totrow;?>&provbene=<?PHP print $provbene;?>&tipproben=<?PHP print $tipproben;?>&montochq=<?PHP print $ls_montochq;?>&sc_cuenta=<?PHP print $cuenta_bco;?>&ctaban=<?PHP print $cta_banco;?>&codban=<?PHP print $codban;?>&docum=<?PHP print $documento;?>&montret=<?PHP print $montret;?>&montobjret=<?PHP print $ldec_monobjret?>&fecha=<?PHP print $ld_fecha?>&mov_operacion=<?PHP print $ls_mov_operacion?>&dencon=<?PHP print $ls_desmov?>&codmov=<?PHP print $ls_codconmov;?>&chevau=<?PHP print $ls_chevau;?>&estmov=<?PHP print $ls_estmov;?>&fuente=<?PHP print $ls_codfuefin;?>";
	valor=f.txtmonamor.value;
	if (confirm("¿ Esta seguro de realizar la amortización por el monto de "+valor+" ?"))
	{
		f.submit();
	}
	 
  } 
	
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtnumsolpag.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
		}
	
	}

	  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
	function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
  }
  
  function ue_cancelar()
  {
    f=document.form1;
    estatus=f.status_doc.value;
	opener.document.form1.status_doc.value=estatus;
  	close();
  }
  
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>