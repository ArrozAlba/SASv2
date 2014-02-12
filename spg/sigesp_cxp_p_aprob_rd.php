<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Aprobaci&oacute;n de Recepciones de Documentos</title>
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
.Estilo1 {font-size: 16px}
.Estilo2 {font-size: 36px}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menupro.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  </p>
  <?php
  require_once("../shared/class_folder/sigesp_include.php");
  require_once("../shared/class_folder/class_mensajes.php");
  require_once("../shared/class_folder/class_datastore.php");
  require_once("../shared/class_folder/class_sql.php");
  require_once("../shared/class_folder/class_funciones.php");
  require_once("../shared/class_folder/grid_param.php");
  require_once("class_folder/sigesp_cxp_c_aprob.php");
  require_once("../shared/class_folder/class_fecha.php");
        
  $io_in=new sigesp_include();
  $con=$io_in->uf_conectar();
  $io_msg=new class_mensajes();
  $io_ds=new class_datastore();
  $io_sql=new class_sql($con);
  $io_fun=new class_funciones(); 
  $io_grid= new grid_param();
  $int_sol=new sigesp_cxp_c_aprob();  
  $io_fecha=new class_fecha();

  $la_emp=$_SESSION["la_empresa"];

  $arr=$_SESSION["la_empresa"];

  $primera=true;   

  global $object;
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="CXP";
	$ls_ventanas="sigesp_cxp_p_aprob_rd.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos             =$_POST["permisos"];
			$la_accesos["leer"]     =$_POST["leer"];			
			$la_accesos["incluir"]  =$_POST["incluir"];			
			$la_accesos["cambiar"]  =$_POST["cambiar"];
			$la_accesos["eliminar"] =$_POST["eliminar"];
			$la_accesos["imprimir"] =$_POST["imprimir"];
			$la_accesos["anular"]   =$_POST["anular"];
			$la_accesos["ejecutar"] =$_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";		
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if (array_key_exists("operacion",$_POST))
    {
	   $ls_operacion=$_POST["operacion"];
	   $totrow      =$_POST["totrows"];
    }
  else
    {
	   $ls_operacion=""; 
	   $totrow=1;      
    }

if  (array_key_exists("txtnumrec",$_POST))
	{
  	  $ls_numrec=$_POST["txtnumrec"];	  
    }
   else
	{
	  $ls_numrec="";
	}   

   if  (array_key_exists("txtnombre",$_POST))
	{
  	  $ls_nombre=$_POST["txtnombre"];	  
    }
   else
	{
	  $ls_nombre="";
	}

   if  (array_key_exists("txtfecdes",$_POST))
	{
  	  $ls_fecemi=$_POST["txtfecdes"];	  
    }
   else
	{
	  $ls_fecemi="";
	}

   if  (array_key_exists("txtfechas",$_POST))
	{
  	  $ls_fechat=$_POST["txtfechas"];	  
    }
   else
	{
	  $ls_fechat="";
	}

  if  (array_key_exists("txtfecdes",$_POST))
	{
  	  $ls_fecdes=$_POST["txtfecdes"];	  
    }
   else
	{
	  $ls_fecdes="";
	}

   if  (array_key_exists("txtfechas",$_POST))
	{
  	  $ls_fechas=$_POST["txtfechas"];	  
    }
   else
	{
	  $ls_fechas="";
	}

   if(array_key_exists("txtcodproben",$_POST))
	{
  	  $ls_codproben = $_POST["txtcodproben"];	  
    }
   else
	{
	  $ls_codproben = "";
	}

    if(array_key_exists("prov",$_POST))
	 {
	   $ls_prov=$_POST["prov"];
     }
    else
	 {
	   $ls_prov="T";
	 }	
    if ($ls_prov=='T')
	   {
	     $ls_todas        = 'checked';
	     $ls_proveedor    = '';
	     $ls_bene         = '';
	   }
	elseif($ls_prov=='P')
	   {
	     $ls_todas        = '';
	     $ls_proveedor    = 'checked';
	     $ls_bene         = '';
	   }
	else
	  {
	    $ls_todas        = '';
		$ls_proveedor    = '';
		$ls_bene         = 'checked';
	  }
    if(array_key_exists("txtfecapro",$_POST))
	 {
       $ls_fecapro=$_POST["txtfecapro"];
     }
    else
	 {
	   $ls_fecapro="";
	 }	

    if  (array_key_exists("evento",$_POST))
		{
		  $ls_evento = $_POST["evento"];		 	 
		}
	else
		{
		  $ls_evento = "INSERT"; // Tiene dos eventos INSERT o UPDATE
		}	
		
    if  (array_key_exists("rdapro",$_POST))
		{
		  $li_rdapro = $_POST["rdapro"];		 	 
		}
	else
		{
		  $li_rdapro = 0; 
		}			
		
    /*Titulos de la tabla*/
	$title[1]="<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	
	$title[2]="Nro de Recepción"; 
	$title[3]="Proveedor o Beneficiario"; 
	$title[4]="Estatus de Aprobación";  
	$title[5]="Fecha de Aprobación"; 
	$title[6]="Monto";  
	$grid="grid_aprorecdoc";	
 
    if($ls_operacion=="")
    {
      $array_fecha=getdate();
	  $ls_dia    =$array_fecha["mday"];
	  $ls_mes    =$array_fecha["mon"];
	  $ls_ano    =$array_fecha["year"];
	  $ls_fecha  =$io_fun->uf_cerosizquierda($ls_dia,2)."/".$io_fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
  	  $ls_fecemi =$ls_fecha;
      $ls_fechat =$ls_fecha;
      $ls_fecapro=$ls_fecha;
	  
	  for ($z=1;$z<=5;$z++)
      {//2
		  $object[$z][1]=""; 					  	      
   	      $object[$z][2]="<input type=text      name=txtnumrecdoc".$z."  value='' style=text-align:center  class=sin-borde  readonly>";
		  $object[$z][3]="<input type=text      name=txtnompro".$z."     value='' style=text-align:center  class=sin-borde  readonly>";
		  $object[$z][4]="<input type=text      name=txtestapro".$z."    value='' style=text-align:center  class=sin-borde  readonly>";    
		  $object[$z][5]="<input type=text      name=txtfecapro".$z."    value='' style=text-align:center  class=sin-borde  readonly>";    
		  $object[$z][6]="<input type=text      name=txtmonto".$z."      value='' style=text-align:right   class=sin-borde  readonly>";    
	  }//2
	  $totrow=1;  
    }
  ?>
<form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celdanew">
        <td height="22" colspan="4">Aprobaci&oacute;n de Recepci&oacute;n de Documentos</td>
    </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Aprobaci&oacute;n</div></td>
        <td height="22"><input name="txtfecapro" type="text" id="txtfecapro" value="<?php print $ls_fecapro ?>" size="15" maxlength="10" style="text-align:center" readonly></td>
        <td height="22"><div align="right">Aprobaci&oacute;n</div></td>
        <td height="22"><?php
		 if ($li_rdapro ==0)
	        {
		       $ls_apro="checked";
	           $ls_reapro="";
            }  
            else
            {
			   if ($li_rdapro ==1)
	           {   
		          $ls_apro="";
	              $ls_reapro="checked";
			   }
	        }
	    ?>
        <input name="rdapro" type="radio" value="0" <?php print $ls_apro ?> ></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div><div align="left">
          </div>          <div align="right">
        </div>          <div align="right">&nbsp;
          
          </div></td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Reverso Aprobaci&oacute;n</div></td>
        <td height="22"><input name="rdapro" type="radio" value="1"  <?php print $ls_reapro ?> ></td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="22" colspan="4">Criterios de Búsqueda</td>
    </tr>
      <tr>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="107" height="22"><div align="right">N&ordm; de Recepci&oacute;n&nbsp;</div></td>
        <td width="235" height="22"><input name="txtnumrec" type="text" id="txtnumrec" value="<?php print $ls_numrec ?>" size="20" maxlength="15" style="text-align:center">        </td>
        <td width="117" height="22"><div align="right">Fecha&nbsp;Desde&nbsp;</div></td>
        <td width="289"><input name="txtfecdes" type="text" id="txtfecdes" value="<?php print $ls_fecemi ?>" size="13" maxlength="10" datepicker="true" onKeyPress="currencyDate(this);" style="text-align:left">
&nbsp;&nbsp;Hasta&nbsp;
<input name="txtfechas" type="text" id="txtfechas"  value="<?php print $ls_fechat ?>" size="13" maxlength="10" datepicker="true" onKeyPress="currencyDate(this);" style="text-align:left" ></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="3"><input name="prov" type="radio" value="T" checked style="border-color:#FFFFFF" onClick="uf_limpiar()" <?php print $ls_todas ?>>
          Todas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="prov" type="radio" value="P" style="border-color:#FFFFFF" onClick="uf_limpiar()" <?php print $ls_proveedor ?>>
        Proveedor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="prov" type="radio" value="B" style="border-color:#FFFFFF" onClick="uf_limpiar()" <?php print $ls_bene ?>>        
        Beneficiario</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo/C&eacute;dula&nbsp;</div></td>
        <td height="22" colspan="3"><input name="txtcodproben" type="text" id="txtcodproben" value="<?php print $ls_codproben ?>" style="text-align:center"> 
          &nbsp;<a href="javascript:catalogo_proveedor();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Proveedores / Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nombre ?>" size="70" maxlength="70">          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="13" colspan="4" align="center"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Recepciones</a></div></td>
      </tr>
      <tr>
        <td height="22" colspan="4" align="center">
          <div align="center">
            <p align="center">
              <?php	
      if ($ls_operacion=="BUSCAR")//1
	  {   				
		$ls_codemp=$la_emp["codemp"];
        $ls_fecemi  =$_POST["txtfecdes"];
	    $ls_fechat  =$_POST["txtfechas"];
		
        $li_rdapro =$_POST["rdapro"];	
	
        if ((($ls_fecemi=="") && (!($ls_fechat==""))) || (($ls_fechat=="") && (!($ls_fecemi==""))))
        {
           $io_msg->message('Tipee la Otra Fecha !!!'); 
        }
        else
        {
     	   $ls_numrec  ="%".$_POST["txtnumrec"]."%";
        
           if((!($ls_fecemi=="")) && (!($ls_fecemi=="")))
           {
		     $ls_fecemi  =$_POST["txtfecdes"];
    	     $ls_fecdes  =$io_fun->uf_convertirdatetobd($ls_fecemi);

             $ls_fechat  =$_POST["txtfechas"];
             $ls_fechas  =$io_fun->uf_convertirdatetobd($ls_fechat);
          }
                   
          $ls_prov=$_POST["prov"];  
      
	      if($ls_prov=="P")    
          {
	        $ls_proveedor="%".$_POST["txtcodproben"]."%";
          }
   	      else
          {
	         if($ls_prov=="B")    
    	     {
                $ls_beneficiario="%".$_POST["txtcodproben"]."%";
	         }       
          }        

  	      $ls_codemp=$la_emp["codemp"];
               
			if($ls_prov=="P")    
			{
				  if(($ls_fecdes=="") && ($ls_fechas==""))
				  { 
					  $ls_sql=" SELECT   * ".
							  " FROM     cxp_rd c, rpc_proveedor p  ".
							  " WHERE   (c.codemp='".$ls_codemp."'          AND c.codemp=P.codemp                  AND ".
							  "          c.numrecdoc like '".$ls_numrec."'  AND                                        ".
							  "          p.cod_pro like '".$ls_proveedor."' AND c.cod_pro=P.cod_pro                AND ".
							  "          c.estaprord = ".$li_rdapro."       AND c.estprodoc = 'R'                     )";
				  }
				  else
				  {
					 $ls_sql=" SELECT * ".
							 " FROM   cxp_rd c, rpc_proveedor p  ".
							 " WHERE (c.codemp='".$ls_codemp."'          AND c.codemp=p.codemp                  AND ".
							 "        c.numrecdoc like '".$ls_numrec."'  AND c.cod_pro=p.cod_pro                AND ".
							 "        p.cod_pro like '".$ls_proveedor."' AND                                        ".
							 "        c.fecregdoc>='".$ls_fecdes."'      AND c.fecregdoc<='".$ls_fechas."'      AND ".
							 "        c.estaprord = ".$li_rdapro."       AND c.estprodoc = 'R' AND c.tipproben='P' )";
				  }
			}
	 	 if ($ls_prov=="B")
			{
				if(($ls_fecdes=="") && ($ls_fechas==""))
				{ 
				   $ls_sql=" SELECT * ".
						   " FROM   cxp_rd c, rpc_beneficiario b  ".
						   " WHERE (c.codemp='".$ls_codemp."'              AND c.codemp=B.codemp                  AND ".
						   "        c.numrecdoc like '".$ls_numrec."'      AND                                        ".
						   "        c.ced_bene like '%".$ls_beneficiario."%' AND c.ced_bene=b.ced_bene              AND ".
						   "        c.estaprord = ".$li_rdapro."           AND c.estprodoc = 'R'                     )";
				}
				else
				{
				   $ls_sql=" SELECT * ".
						   " FROM   cxp_rd c, rpc_beneficiario b  ".
						   " WHERE (c.codemp='".$ls_codemp."'              AND c.codemp=b.codemp                  AND ".
						   "        c.numrecdoc like '".$ls_numrec."'      AND  ".
						   "        c.ced_bene like '%".$ls_beneficiario."%' AND c.ced_bene=b.ced_bene              AND ".
						   "        b.nombene like '%".$ls_nombre."%'        AND c.fecregdoc BETWEEN '".$ls_fecdes."' AND ".
						   "        '".$ls_fechas."'                       AND  ".
						   "        c.estaprord = ".$li_rdapro."           AND c.estprodoc = 'R'                     )";
				}
			}
					  
			if($ls_prov=="T")
			{
				  if(($ls_fecdes=="") && ($ls_fechas==""))
				  { 
					 $ls_sql=" SELECT * FROM cxp_rd                                                            ".
							 " WHERE (codemp='".$ls_codemp."'      AND numrecdoc like '".$ls_numrec."' AND ".
							 "        estaprord = ".$li_rdapro."   AND estprodoc = 'R'                    )";
				  }
				  else
				  {
					 $ls_sql=" SELECT * FROM cxp_rd                                                       ".
							 " WHERE (codemp='".$ls_codemp."'      AND  numrecdoc like '".$ls_numrec."' AND ".
							 "        fecregdoc>='".$ls_fecdes."'  AND  fecregdoc<='".$ls_fechas."'     AND ".
							 "        estaprord = ".$li_rdapro."   AND  estprodoc = 'R'                    )";
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
			     $ls_numrecdoc = $data["numrecdoc"][$z];				                   
                 $ls_fecha     = $data["fecaprord"][$z];
  		         $ls_fecha     = substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);						
				 $ld_monto     = $data["montotdoc"][$z];
				 $ls_estatus   = $data["estaprord"][$z];
				 $ls_tiproben  = $data["tipproben"][$z];
                 $ls_nomproben = "";
				 if ($ls_tiproben=="P")
                    {
                      $ls_codpro = $data["cod_pro"][$z];                     
                      $int_sol->uf_load_datos_proben($ls_codemp,$ls_codpro,'rpc_proveedor','cod_pro','nompro AS nombre','',$ls_nomproben,$as_apeproben);
					}
                  else
                    {
                      $ls_cedbene = $data["ced_bene"][$z];
                      $int_sol->uf_load_datos_proben($ls_codemp,$ls_cedbene,'rpc_beneficiario','ced_bene','nombene AS nombre',',apebene AS apellido',$ls_nomproben,$as_apeproben);
                    }
                 if ($ls_estatus==0)
                    {
                      $ls_estapro="No Aprobada";
                    }
                 else
                    {
                      $ls_estapro="Aprobada";
                    }
			      $ld_monto=number_format($ld_monto,2,',','.');
				  $object[$z][1]="<input type=checkbox  name=chkaprob".$z."         value=0               style=text-align:center  class=sin-borde>"; 					  
				  $object[$z][2]="<input type=text      name=txtnumrecdoc".$z."     value='$ls_numrecdoc' style=text-align:center  class=sin-borde  readonly>";
				  $object[$z][3]="<input type=text      name=txtnompro".$z."        value='$ls_nomproben' style=text-align:center  class=sin-borde  readonly>";
				  $object[$z][4]="<input type=text      name=txtestapro".$z."       value='$ls_estapro'   style=text-align:center  class=sin-borde  readonly>";    
				  $object[$z][5]="<input type=text      name=txtfecapro".$z."       value='$ls_fecha'     style=text-align:center  class=sin-borde  readonly>";    
				  $object[$z][6]="<input type=text      name=txtmonto".$z."         value='$ld_monto'     style=text-align:right   class=sin-borde  readonly>";    
			   
			}//End del For...
	   $io_sql->free_result($rs);
	   $io_sql->close();	   
   } 
   else
   {
   	    for ($z=1;$z<=5;$z++)
		{//2
			 $object[1][1]=""; 					  
			 $object[1][2]="<input type=text      name=txtnumrecdoc".$z."   value=''  style=text-align:center  class=sin-borde  readonly>";
			 $object[1][3]="<input type=text      name=txtnompro".$z."      value=''  style=text-align:center  class=sin-borde  readonly>";
			 $object[1][4]="<input type=text      name=txtestapro".$z."     value=''  style=text-align:center  class=sin-borde  readonly>";    
			 $object[1][5]="<input type=text      name=txtfecapro".$z."     value=''  style=text-align:center  class=sin-borde  readonly>";    
			 $object[1][6]="<input type=text      name=txtmonto".$z."       value=''  style=text-align:right   class=sin-borde  readonly>";  		
		}//2
		$totrow=1; 
        $io_msg->message('No Hay Datos que Mostrar !!!'); 	    
   }
}


if ($ls_operacion=="VALFECHA")
   {
     $arre=$_SESSION["la_empresa"];
     $ls_empresa=$arre["codemp"];
     $li_rdapro =$_POST["rdapro"];		
	 $ls_fechat =$_POST["txtfechas"];	
     $lb_valido=true;
     $lb_valido=$io_fecha->uf_comparar_fecha($ls_fechat,$ls_fecapro);
     if ($lb_valido)
        { 
          $io_msg->message('La Fecha de Búsqueda no Puede se Mayor a la Fecha de Aprobación !!!'); 	               
		  $ls_fechat=$ls_fecapro;
		  for ($z=1;$z<=5;$z++)
		      {
			    $object[1][1]=""; 					  
			    $object[1][2]="<input type=text      name=txtnumrecdoc".$z."   value=''  style=text-align:center  class=sin-borde  readonly>";
			    $object[1][3]="<input type=text      name=txtnompro".$z."      value=''  style=text-align:center  class=sin-borde  readonly>";
			    $object[1][4]="<input type=text      name=txtestapro".$z."     value=''  style=text-align:center  class=sin-borde  readonly>";    
			    $object[1][5]="<input type=text      name=txtfecapro".$z."     value=''  style=text-align:center  class=sin-borde  readonly>";    
			    $object[1][6]="<input type=text      name=txtmonto".$z."       value=''  style=text-align:right   class=sin-borde  readonly>";  		
		      }
		$totrow=1; 
    }
}


if ($ls_operacion=="GUARDAR")
{//1
   $ls_codemp=$la_emp["codemp"];
   $lb_valido=true;

   $lb_valido=$io_fecha->uf_valida_fecha_periodo($ls_fecapro,$ls_codemp);
   if(!$lb_valido)
   {
        $io_msg->message($io_fecha->is_msg_error);           
        $lb_valido=false;
   }
   else
   {//2  
       $ls_fila=$totrow;
       $row=0;
       $y=0;
       for ($i=1;$i<=$totrow;$i++)
       {//3	
     	   if (array_key_exists("chkaprob".$i,$_POST))
           {	
              $y=$y+1;                          
	          $ls_fecha=$io_fun->uf_convertirdatetobd($ls_fecapro);
    	      $lr_datosgrid["numero"][$y]=$_POST["txtnumrecdoc".$i];  
        	  $ls_estatus=$_POST["txtestapro".$i];           
	          if($li_rdapro==0)
              {
                $ls_estapro=1;
              }
              else
              { 
                $ls_estapro=0;
              }            
	          $lr_datosgrid["estatus"][$y]=$ls_estapro;                      
    	      $lr_datosgrid["fecha"][$y]=$ls_fecha;                                        
        	  $row=$row+1;                         	        		                   
          }//4
        }//3
      //}//6
      if(($totrow>=1) && ($row>=1))
      {      
	     $lb_valido=$int_sol->ue_update_estatus_aprob_recdoc($lr_datosgrid,$row,$la_seguridad);     
		 if ($lb_valido)  
		 {
			 for ($z=1;$z<=5;$z++)
			 {//2
				  $object[$z][1]="<input type=checkbox  name=chkaprob".$z."      value=0   style=text-align:center  class=sin-borde>"; 					  
				  $object[$z][2]="<input type=text      name=txtnumrecdoc".$z."  value=''  style=text-align:center  class=sin-borde  readonly>";
				  $object[$z][3]="<input type=text      name=txtnompro".$z."     value=''  style=text-align:center  class=sin-borde  readonly>";
				  $object[$z][4]="<input type=text      name=txtestapro".$z."    value=''  style=text-align:center  class=sin-borde  readonly>";    
				  $object[$z][5]="<input type=text      name=txtfecapro".$z."    value=''  style=text-align:center  class=sin-borde  readonly>";    
				  $object[$z][6]="<input type=text      name=txtmonto".$z."      value=''  style=text-align:right   class=sin-borde  readonly>";    
			 }//2
			 $totrow=1; 
		 }
      }
   }    
}   
?>
              <span>
              <?php 
              $io_grid->makegrid($totrow,$title,$object,765,'Recepciones de Documentos',$grid); 
              ?>
              </span>
              <input name="operacion"   type="hidden" id="operacion"  value="<?php print $ls_operacion;?>">
              <input name="totrows"     type="hidden" id="totrows"     value="<?php print $totrow?>">
              <input name="rowselec"    type="hidden" id="rowselec"    value="<?php print $ls_seleccionado ?>">
              <input name="evento"      type="hidden" id="evento"      value="<?php print $ls_evento ?>">
</p>
        </div>          </td>
      </tr>
  </table>
    <p>&nbsp;</p>
  <p align="center">&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
function ue_guardar()
  {          
	 f=document.form1;
	 li_incluir=f.incluir.value;
     li_cambiar=f.cambiar.value;
	 evento    = f.evento.value;
		
	 if( ((evento=="INSERT")&&(li_incluir==1)) || ((evento=="UPDATE")&&(li_cambiar==1)) )
     {    	 
		 f.operacion.value="GUARDAR";
		 f.action="sigesp_cxp_p_aprob_rd.php";
		 f.submit();
	 }
	 else
	 {
		 alert("No tiene permiso para realizar esta operacion");
	 }
  }

 function valida_null(field,mensaje)
 {
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
   }
 }	

function esDigito(sChr)
{     
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}
        /*Fin de la Funcion ue_eliminar()*/
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
  

function uf_change_radio()
{
     f=document.form1;
     f.txtcodproben.value="";
     f.txtnombre.value="";
}

function ue_search()
{
     f=document.form1;
	 li_leer=f.leer.value;
	 if (li_leer==1)
	    {		
		  f.operacion.value="BUSCAR";
		  f.action="sigesp_cxp_p_aprob_rd.php";
		  f.submit();
	    }
	 else
	    {
		  alert("No tiene permiso para realizar esta operacion");
	    }
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
   
function catalogo_proveedor()
{
	document.form1.operacion.value="";			
	if (document.form1.prov[1].checked)
	   {          	
		 pagina="sigesp_cxp_cat_pro.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
	if (document.form1.prov[2].checked)
	   { 		    	
		 pagina="sigesp_cxp_cat_ben.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
} 

    function uf_select_all()
    {
	  f=document.form1;	  
	  total=f.totrows.value;
	  sel_all=f.chkall.value;
	  li_sel=0;
	  li_row=0;
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chkaprob"+i+".checked=true")
			li_sel=li_sel+1;
		  }		  
	   }
    } 
	
    function uf_limpiar()
    {
	   document.form1.txtcodproben.value="";
	   document.form1.txtnombre.value="";  
    }
	
	function uf_select_all()
    {
	  f=document.form1;	  
	  total=f.totrows.value;
	  sel_all=f.chkall.value;
	  li_sel=0;
	  li_row=0;
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chkaprob"+i+".checked=true")
			li_sel=li_sel+1;
		  }		  
	   }
    } 
	
	function valfecha()
    {  
	 f=document.form1;		
	 document.form1.operacion.value="VALFECHA";
	 document.form1.action="sigesp_cxp_p_aprob_rd.php";
	 document.form1.submit();      
    }
	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
