<?Php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Recepci&oacute;n de Documento</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
</head>

<body>
<?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_msg=new class_mensajes();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$io_fun=new class_funciones(); 

$la_emp=$_SESSION["la_empresa"];

	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
	   }
	else
	   {
		$ls_operacion="";
	   }

   if  (array_key_exists("txtnumrec",$_POST))
	{
  	  $ls_numrec=$_POST["txtnumrec"];	  
    }
   else
	{
	  $ls_numrec="";
	}

   if  (array_key_exists("cmbconcepto",$_POST))
	{
  	  $ls_concepto=$_POST["cmbconcepto"];	  
    }
   else
	{
	  $ls_concepto="";
	}

   if  (array_key_exists("txtnombre",$_POST))
	{
  	  $ls_nomproben=$_POST["txtnombre"];	  
    }
   else
	{
	  $ls_nomproben="";
	}

   if  (array_key_exists("txtfecdes",$_POST))
	{
  	  $ls_fecdes =$_POST["txtfecdes"];	  
    }
   else
	{
	  $ls_fecdes="";
	}

   if  (array_key_exists("txtfechas",$_POST))
	{
  	  $ls_fechas =$_POST["txtfechas"];	  
    }
   else
	{
	  $ls_fechas="";
	}
	
    if  (array_key_exists("txtfecdes",$_POST))
	{
  	  $ls_fecemi =$_POST["txtfecdes"];	  
    }
   else
	{
	  $ls_fecemi="";
	}

   if  (array_key_exists("txtfechas",$_POST))
	{
  	  $ls_fechat =$_POST["txtfechas"];	  
    }
   else
	{
	  $ls_fechat="";
	}

if (array_key_exists("txtcodproben",$_POST))
   {
     $ls_codproben=$_POST["txtcodproben"];	  
   }
else
   {
     $ls_codproben="";
   }
if (array_key_exists("prov",$_POST))
   {
     $ls_prov=$_POST["prov"];
   }
else
   {
     $ls_prov="";
   }	
if (empty($ls_operacion))
   { 
      $array_fecha=getdate();
	  $ls_dia=$array_fecha["mday"];
	  $ls_mes=$array_fecha["mon"];
	  $ls_ano=$array_fecha["year"];
	  $ls_fecha=$io_fun->uf_cerosizquierda($ls_dia,2)."/".$io_fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
  	  $ls_fecemi=$ls_fecha;
      $ls_fechat=$ls_fecha;
   }
?>
<form name="form1" method="post" action="">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr class="titulo-celdanew">
    <td height="22" colspan="5"><div align="center">Cat&aacute;logo de Recepci&oacute;n de Documento </div></td>
    </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td width="182" height="22" colspan="-1">&nbsp;</td>
    <td width="121" height="22" colspan="-1">&nbsp;</td>
    <td width="248" height="22" colspan="-1">&nbsp;</td>
    <td width="29" height="22" colspan="-1">&nbsp;</td>
  </tr>
  <tr>
    <td width="118" height="22"><div align="right">N&ordm; de Recepcion </div></td>
    <td height="22" colspan="-1"><input name="txtnumrec" type="text" id="txtnumrec" value="<? print $ls_numrec ?>" size="20" maxlength="15" style="text-align:center ">
        <input name="operacion" type="hidden" id="operacion"></td>
    <td height="22" colspan="-1"><p align="right"><strong>Fecha Recepci&oacute;n&nbsp;&nbsp;</strong></p>
      </td>
    <td height="22"><p align="left">Desde      
      <input name="txtfecdes" type="text" id="txtfecdes" onBlur="valFecha(document.form1.txtfecdes)" value="<? print $ls_fecdes ?>" size="12" maxlength="10" datepicker="true">
      &nbsp;&nbsp;&nbsp;
Hasta 

<input name="txtfechas" type="text" id="txtfechas" onBlur="valFecha(document.form1.txtfechas)" value="<? print $ls_fechas ?>" size="12" maxlength="10" datepicker="true">
    </p>      </td>
    </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td height="22" colspan="2"><input name="prov" type="radio" value="T" checked style="border-color:#FFFFFF ">
      Todas
      <input name="prov" type="radio" value="P" style="border-color:#FFFFFF ">
      Proveedor <input name="prov" type="radio" value="B" style="border-color:#FFFFFF ">
      Beneficiario</td>
    <td height="22" colspan="-1">&nbsp;      </td>
    <td height="22" colspan="-1">&nbsp;</td>
  </tr>
  <tr>
    <td height="22"><div align="right">C&oacute;digo/C&eacute;dula</div></td>
    <td height="22" colspan="-1"><input name="txtcodproben" type="text" id="txtcodproben" value="<? print $ls_codproben ?>" size="24" maxlength="10" style="text-align:center "></td>
    <td height="22" colspan="-1"><div align="right">Nombre</div></td>
    <td height="22" colspan="-1"><input name="txtnombre" type="text" id="txtnombre" value="<? print $ls_nomproben ?>" size="30" maxlength="50" style="text-align:left "></td>
    <td height="22" colspan="-1">&nbsp;</td>
  </tr>
  <tr>
    <td height="22"><div align="right">Estatus</div></td>
    <td height="22" colspan="-1"><select name="cmbestatus" id="cmbestatus">
        <option value="T" selected>Todas</option>
        <option value="E">Emitida</option>
        <option value="C">Contabilizada</option>
        <option value="A">Anulada</option>
        <option value="S">Programaci&oacute;n de Pago</option>
        <option value="P">Pagada</option>
    </select></td>
    <td height="22" colspan="-1">Concepto</td>
    <td height="22" colspan="-1">
      <?php
         $ls_sql=" SELECT * ".
		         " FROM cxp_clasificador_rd ".
				 " ORDER BY codcla ASC";
         $rs=$io_sql->select($ls_sql);
      ?>   
      <select name="cmbconcepto" id="cmbconcepto">
     <option value="T">Seleccione un Concepto</option>
     <?Php
      while($row=$io_sql->fetch_row($rs))
	  {
		 $ls_codcla=$row["codcla"];
		 $ls_dencla=$row["dencla"];
		 if ($ls_codcla==$ls_concepto)
		 {
		    print "<option value='$ls_codcla' selected>$ls_dencla</option>";
	     }
		 else
		 {
			 print "<option value='$ls_codcla'>$ls_dencla</option>";
	     }
	  } 
    ?>
      </select></td>
    <td height="22" colspan="-1">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" colspan="5" align="center" ><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar Recepcion de Documentos </a></div></td>
  </tr>
</table>
<p align="center">
  <?Php
print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celdanew>";
print "<td>Nro de Recepción</td>";
print "<td>Proveedor</td>";
print "<td>Fecha de Registro</td>";
print "<td>Estatus</td>";
print "<td>Monto</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
{    
	 $ls_fecemi  =$_POST["txtfecdes"];
	 $ls_fechat  =$_POST["txtfechas"];
     if ((empty($ls_fecemi) && (!empty($ls_fechat))) || (empty($ls_fechat) && (!empty($ls_fecemi))))
        {
          $io_msg->message('Tipee la Fecha Faltante !!!'); 
        }
     else
        {
     	  $ls_numrec  ="%".$_POST["txtnumrec"]."%";
          if (!empty($ls_fecemi) && !empty($ls_fecemi))
             {
		       $ls_fecemi  =$_POST["txtfecdes"];
    	       $ls_fecdes  =$io_fun->uf_convertirdatetobd($ls_fecemi);
               $ls_fechat  =$_POST["txtfechas"];
               $ls_fechas  =$io_fun->uf_convertirdatetobd($ls_fechat);
             }
	  	  else
		     {		 
    	       $ls_fecdes="";
               $ls_fechas="";
             }
        $ls_concepto="%".$_POST["cmbconcepto"]."%";  
        if ($ls_concepto=="%T%")
           {         
             $ls_concepto="%%";
           }
        $ls_nombre  ="%".$_POST["txtnombre"]."%";  
        $ls_status ="%".$_POST["cmbestatus"]."%";     
        if ($ls_status=="%T%") 
           {
   	         $ls_estatus="%%";
           }
        else
           {
             $ls_estatus=$ls_status;
           } 
	    $ls_prov=$_POST["prov"];  
        if ($ls_prov=="P")    
           {
	         $ls_codpro="%".$_POST["txtpro"]."%";
             $ls_cedbene='----------';
		   }
   	    else
        {
	      if ($ls_prov=="B")    
    	     {
               $ls_codpro='----------';
			   $ls_beneficiario="%".$_POST["txtben"]."%";
	         }       
        }        
  	    $ls_codemp=$la_emp["codemp"];
        if (!($ls_nombre=="%%") && ($ls_prov=="T")) 
           {
             $lb_valido=true;  
             $io_msg->message('Escoja Si la Busqueda del Nombre es por Proveedor o Beneficiario !!!'); 
             $lb_valido=false;  
           }
        else
           {
             $lb_valido=true;  
           }   
        if ($lb_valido)
           {
      	     if ($ls_prov=="P")    
        	    {
           	      $ls_strfecha="";
				  if (!empty($ls_fecdes) && !empty($ls_fechas))
           	         {   
            	       $ls_strfecha=" AND C.fecemisol BETWEEN '".$ls_fecdes."'  AND C.fecemisol<='".$ls_fechas."' ";
					 }
				  $ls_sql=" SELECT * ".
				          " FROM   cxp_rd C, rpc_proveedor P  ".
                          " WHERE  C.codemp='".$ls_codemp."'          AND C.codemp=P.codemp                  AND ".
                          "        C.numrecdoc like '".$ls_numrec."'  AND C.estprodoc like '".$ls_estatus."' AND ".
                          "        P.cod_pro like '".$ls_codpro."' AND C.cod_pro=P.cod_pro                   AND ".
                          "        P.nompro like '".$ls_nombre."'     AND codcla like '".$ls_concepto."'";
                   $ls_sql=$ls_sql.$ls_srtfecha;
		         }
              elseif($ls_prov=="B")
                 {
				   $ls_strfecha="";
				   if (!empty($ls_fecdes) && !empty($ls_fechas))
					  {   
					    $ls_strfecha=" AND C.fecemisol BETWEEN '".$ls_fecdes."'  AND C.fecemisol<='".$ls_fechas."' ";
				      }
                    $ls_sql=" SELECT * ".
				            " FROM   cxp_rd C, rpc_beneficiario B  ".
                            " WHERE  C.codemp='".$ls_codemp."'              AND C.codemp=B.codemp                  AND ".
                            "        C.numrecdoc like '".$ls_numrec."'      AND C.estprodoc like '".$ls_estatus."' AND ".
                            "        C.ced_bene like '".$ls_beneficiario."' AND C.ced_bene=B.ced_bene              AND ".
                            "        B.nombene like '".$ls_nombre."'        AND codcla like '".$ls_concepto."'";
                        $ls_sql=$ls_sql.$ls_srtfecha;
			      }
               else
                  {
				    $ls_strfecha="";
				    if (!empty($ls_fecdes) && !empty($ls_fechas))
					   {   
					     $ls_strfecha=" AND fecregdoc BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."' ORDER BY fecregdoc ASC";
				       }
                    $ls_sql=" SELECT * ".
				            " FROM   cxp_rd ".
                            " WHERE  codemp='".$ls_codemp."'          AND numrecdoc like '".$ls_numrec."' AND ".
                            "        estprodoc like '".$ls_estatus."' AND codcla like '".$ls_concepto."'";                                            
                    $ls_sql=$ls_sql.$ls_strfecha;
				  }
             }
           }
         }     
	    $rs=$io_sql->select($ls_sql);
		$data=$rs;
        if ($row=$io_sql->fetch_row($rs))
	    {
	      $data=$io_sql->obtener_datos($rs);
		  $arrcols=array_keys($data);
		  $totcol=count($arrcols);
		  $io_ds->data=$data;
		  $totrow=$io_ds->getRowCount("numrecdoc");
		  for ($z=1;$z<=$totrow;$z++)
		  {			 
             print "<tr class=celdas-blancas>";
             $ls_numrec   =$data["numrecdoc"][$z];   
             $ls_tiprec   =$data["codtipdoc"][$z];   
             //*****************************************************************************************************             
             $ls_sql=" SELECT dentipdoc ".
			         " FROM   cxp_documento ".
					 " WHERE  codtipdoc='".$ls_tiprec."'";                        
             $rs=$io_sql->select($ls_sql);
             if($row=$io_sql->fetch_row($rs))
	         {
 		         $ls_denrec=$row["dentipdoc"];
 		     }
             //****************************************************************************************************
             $ls_codclasrec   =$data["codcla"][$z]; 
			 $ls_sql= " SELECT dencla FROM cxp_clasificador_rd WHERE  codcla='".$ls_codclasrec."'";                        
             $rs=$io_sql->select($ls_sql);
             if($row=$io_sql->fetch_row($rs))
	         {
 		         $ls_dencla=$row["dencla"];				
 		     }
			 //****************************************************************************************************      
             $ls_denconrec=$data["dencondoc"][$z];    
             $ls_fecha    =$data["fecemidoc"][$z];    
             $ls_fecemirec=substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);						
             $ls_fechas=$data["fecregdoc"][$z];    
             $ls_fecregrec=substr($ls_fechas,8,2)."/".substr($ls_fechas,5,2)."/".substr($ls_fechas,0,4);						
             $ls_fec   =$data["fecvendoc"][$z];    
             $ls_fecvenrec=substr($ls_fec,8,2)."/".substr($ls_fec,5,2)."/".substr($ls_fec,0,4);					
             $ld_totrec=$data["montotdoc"][$z]; 
			 $ld_dedrec=$data["mondeddoc"][$z];    
			 $ld_carrec=$data["moncardoc"][$z];    
			 $subtotalcar=$ld_totrec-$ld_carrec;   
             $subtotalded=$ld_totrec+$ld_dedrec; 
             $subtotal   =$subtotalded-$subtotalcar;
             $subtotalrec =number_format($subtotal,2,",",".");    
             $ld_montotrec=number_format($ld_totrec,2,",",".");    
             $ld_mondedrec=number_format($ld_dedrec,2,",",".");    
             $ld_moncarrec=number_format($ld_carrec,2,",",".");    
             $ls_numrefrec=$data["numref"][$z];       
             $ls_procede=$data["procede"][$z];                     
			 $li_impuesto=$data["estimpmun"][$z];                     
			 $ls_estatus=$data["estprodoc"][$z];                     
			 if($ls_estatus=="R")
             {
                  $ls_estprorec="Recibida";
             }
             if($ls_estatus=="A")
             {
                  $ls_estprorec="Anulada";
             }
             if ($ls_estatus=="E")
                {
                  $ls_estprorec="Emitida en Solicitud";
                }
             if ($ls_estatus=="C")
                {
                  $ls_estprorec="Contabilizada";
                }
			 $ls_estlibrec=$data["estlibcom"][$z];    
             $ls_estapro  =$data["estaprord"][$z];    
             $ls_fecapro  =$data["fecaprord"][$z];    
             $ls_usuapro  =$data["usuaprord"][$z];   
             $ls_tippro   =$data["tipproben"][$z];                 
             if($ls_tippro=="P")
             {
                $ls_codpro=$data["cod_pro"][$z];
                $ls_sql=" SELECT nompro,rifpro ".
                        " FROM   rpc_proveedor ".
                        " WHERE (codemp='".$ls_codemp."' AND cod_pro='".$ls_codpro."')";                        

                $rs=$io_sql->select($ls_sql);
   	            if($row=$io_sql->fetch_row($rs))
	            {
 		            $ls_nombre=$row["nompro"];
                    $ls_rif=$row["rifpro"];  
 		        }
             }
             else
             {
                $ls_codpro=$data["ced_bene"][$z];
                $ls_sql=" SELECT nombene ".
                        " FROM   rpc_beneficiario ".
                        " WHERE (codemp='".$ls_codemp."' AND ced_bene='".$ls_codpro."')";                        

                $rs=$io_sql->select($ls_sql);
   	            if($row=$io_sql->fetch_row($rs))
	            {
 		            $ls_nombre=$row["nombene"];  
 		        }
             }
			 print "<td width=120  style=text-align:center><a href=\"javascript: aceptar('$ls_numrec','$ls_tiprec','$ls_denrec','$ls_tippro','$ls_codpro','$ls_nombre','$ls_rif','$ls_fecemirec','$ls_fecregrec','$ls_fecvenrec','$ls_numrefrec','$ls_denconrec','$ls_numrefrec','$subtotalrec','$ld_montotrec','$ld_mondedrec','$ld_moncarrec','$ls_codclasrec','$ls_estlibrec','$ls_procede','$li_impuesto','$ls_estprorec');\">".$ls_numrec."</a></td>";
             print "<td width=300  style=text-align:left>".$ls_nombre."</td>";			
			 print "<td width=150  style=text-align:center>".$ls_fecregrec."</td>";			
             print "<td width=150  style=text-align:center>".$ls_estprorec."</td>";			
			 print "<td width=150  style=text-align:right>".$ld_montotrec."</td>"; 			              
			 print "</tr>";			
		 }
	   }
print "</table>";
?>
</p>
</form>      
</body>
<script language="JavaScript"> 
function aceptar(ls_numrec,ls_tiprec,ls_denrec,ls_tippro,ls_codpro,ls_nombre,ls_rif,ls_fecemirec,ls_fecregrec,ls_fecvenrec,ls_numrefrec,ls_denconrec,ls_numrefrec,subtotalrec,ld_montotrec,ld_mondedrec,ld_moncarrec,ls_codclasrec,li_libro,procedencia,li_impuesto,ls_estatus)
{
	opener.document.form1.txtnumdocumento.value  =ls_numrec;  
	opener.document.form1.txttipodoc.value       =ls_tiprec;
	opener.document.form1.txtdentipdoc.value     =ls_denrec;
	opener.document.form1.txtrif.value           =ls_rif;
	if (ls_tippro=="P")
	   {    
	     opener.document.form1.estprov[0].checked=true;
	   }
    else
	   { 
         opener.document.form1.estprov[1].checked=true;
	   }
    opener.document.form1.txtcodproben.value     =ls_codpro;
    opener.document.form1.txtnombre.value        =ls_nombre;
    opener.document.form1.txtfecemision.value    =ls_fecemirec;
	opener.document.form1.txtfecvencimiento.value=ls_fecregrec;
    opener.document.form1.txtfecregistro.value   =ls_fecvenrec;
	opener.document.form1.txtconcepto.value      =ls_denconrec;
	opener.document.form1.txtnumcontrol.value    =ls_numrefrec;  
    opener.document.form1.txtsubtotal.value      =subtotalrec;   
    opener.document.form1.txtmontototal.value    =ld_montotrec;   
	opener.document.form1.txtmontoded.value      =ld_mondedrec;   
	opener.document.form1.txtmontootroscred.value=ld_moncarrec;   
	opener.document.form1.cmbconcepto.value      =ls_codclasrec;  
	opener.document.form1.txtestatus.value       =ls_estatus;  
	if (li_libro==1)
	   {
	     opener.document.form1.chklibro.checked=true;   
	   }
    else
	   {
		 opener.document.form1.chklibro.checked=false;   
	   }
	if (li_impuesto==1)
	   {
	     opener.document.form1.chkimpuesto.checked=true;   
	   }
    else
	   {
		 opener.document.form1.chkimpuesto.checked=false;   
	   }
	opener.document.form1.hidprocede.value=procedencia;
	if (procedencia!='CXPRCD' || procedencia!='')
	   {
	     opener.document.form1.btnotroscreditos.disabled=true;
	   }
	opener.document.form1.estatus.value="GRABADO";
	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
    close();
  }
function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_saf_cat_recepciones.php";
	  f.submit();
  }

function valSep(oTxt)
		{ 
			var bOk = false; 
			var sep1 = oTxt.value.charAt(2); 
			var sep2 = oTxt.value.charAt(5); 
			bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
			bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
			return bOk; 
		} 		
		function finMes(oTxt)
		{ 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			var nAno = parseInt(oTxt.value.substr(6), 10); 
			var nRes = 0; 
			switch (nMes)
			{ 
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
		function valDia(oTxt)
		{ 
		   var bOk = false; 
		   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
		   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
		   return bOk; 
		} 		
		function valMes(oTxt)
		{ 
			var bOk = false; 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
			return bOk; 
		} 		
		function valAno(oTxt)
		{ 
			var bOk = true; 
			var nAno = oTxt.value.substr(6); 
			bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
			if (bOk)
			{ 
			 for (var i = 0; i < nAno.length; i++)
			 { 
			   bOk = bOk && esDigito(nAno.charAt(i)); 
			 } 
			} 
		 return bOk; 
		 } 		
		 function valFecha(oTxt)
		 { 
			var bOk = true; 
				if (oTxt.value != "")
				{ 
				  bOk = bOk && (valAno(oTxt)); 
				  bOk = bOk && (valMes(oTxt)); 
				  bOk = bOk && (valDia(oTxt)); 
				  bOk = bOk && (valSep(oTxt)); 
				  if (!bOk)
				  { 
				   alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005)"); 
				   oTxt.value = "01/01/1900"; 
				   oTxt.focus(); 
				  } 
				}
		}
   function esDigito(sChr)
   {   
      var sCod = sChr.charCodeAt(0); 
      return ((sCod > 47) && (sCod < 58)); 
   }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>