<?php 
class sigesp_class_scb
{
var $ls_sql;
var $is_msg_error;
var $ds_cargos;
var $ds_comprobantes;	


		function sigesp_class_scb($conn)
		{
		  $this->io_sql= new class_sql($conn);
		  require_once("../shared/class_folder/class_mensajes.php");
		  require_once("../shared/class_folder/class_funciones.php");
		  $this->io_msg          = new class_mensajes();
		  $this->io_funcion      = new class_funciones();
	   	  $this->ds_comprobantes = new class_datastore();
		  $this->ls_codemp       = $_SESSION["la_empresa"]["codemp"];
		}
		

function uf_load_dt_presupuestarios($as_procedencia,$as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido)
{
switch ($as_procedencia)
  {
   case 'SOCCOC':
        $rs_data=$this->uf_load_dtorden_compra($as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido);       
		break;
   case 'SOCCOS':
   		$rs_data=$this->uf_load_cargos_sos($as_numordserv,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido);               
        break;
   case 'SEPSPC':
        $rs_data=$this->uf_load_cargos_sep($as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido);
        break;
  }
return $rs_data;
}

function uf_rd_down_recdoc_cargos($as_procedencia)
{
switch ($as_procedencia)
  {
   case 'SOCCOC':
        $this->uf_rd_down_recdoc_spg_soccoc2($as_numordcom);       
        break;
   case 'SOCCOS':
   		$this->uf_rd_down_recdoc_spg_soccos2($as_numordserv);
        break;
   case 'SEPSPC':
        $this->uf_rd_down_recdoc_spg_sepspc2($as_numsep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_spgcuenta);
        break;
  }
}


function uf_load_comprobantes_positivos($as_tipodestino,$as_codpro,$as_cedbene,$as_fechahasta)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_comprobantes_positivos
//	          Access:  public
//          Arguments  
//   $as_tipodestino:  Variable que define el destino del comprobante (P) => Proveedor , (B)=>Beneficiario.
//        $as_codpro:  Código del Proveedor para realizar la búsqueda.
//       $as_cedbene:  Cédula del Beneficiario para realizar la búsqueda.
//    $as_fechahasta:  Fecha hasta el cual se buscarán los comprobates, para este caso, se usa la fecha en la que se esta registrando la Recepción de Documento.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.             
//       Description:  Función que se encarga de extraer todos aquellos comprobantes asociados a un proveedor y/o beneficiario en estatus 'CS'=>Compromiso simple hasta la fecha y con monto positivo.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  03/02/2006        Fecha Última Actualización:22/05/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	 $ls_codcompromete="CS";
	 $ls_sql="SELECT DISTINCT PCM.procede as procedencia, PCM.comprobante as comprobante, PCM.fecha as fecha,                ".
			 "                PCM.descripcion as descripcion, PCM.total as total                                             ".
			 "FROM   sigesp_cmp PCM, spg_dt_cmp PMV                                                                          ".
			 "WHERE (PCM.procede=PMV.procede                AND PCM.comprobante=PMV.comprobante AND PCM.fecha=PMV.fecha) AND ".
             "       PCM.tipo_destino='".$as_tipodestino."' AND PCM.cod_pro='".$as_codpro."'    AND                          ".
			 "       PCM.ced_bene='".$as_cedbene."'         AND PMV.operacion='".$ls_codcompromete."' AND                    ".
			 "       PCM.fecha <= '".$as_fechahasta."'      AND PMV.monto > 0                                                ".
			 " ORDER BY PCM.comprobante ASC";
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
		{
  		  $lb_valido=false;
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_comprobantes_positivos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
     else
	    {
	      if ($row=$this->io_sql->fetch_row($rs_data))
		     {
			   $datos=$this->io_sql->obtener_datos($rs_data);
			   $this->ds_comprobantes->data=$datos;
		       $this->io_sql->free_result($rs_data);
			   $lb_valido=true;
			 }
	      else
		     {
			   $lb_valido=false;
		     }
 	   }		
return $lb_valido;
}


function uf_load_monto_ajustes($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$lb_valido)
{
	$ls_codcompromete="CS";
	$monto=0;
    $ls_sql=" SELECT sum(PMV.monto) AS Monto ".
	        " FROM spg_dt_cmp PMV, sigesp_cmp PCM ".
	        " WHERE PCM.procede=PMV.procede                AND PCM.comprobante=PMV.comprobante       AND ".
            "       PCM.fecha=PMV.fecha                    AND PMV.procede_doc='".$as_procedencia."' AND ".
			"       PMV.documento='".$as_comprobante."'    AND PMV.operacion='".$ls_codcompromete."' AND ".
			"       PCM.tipo_destino='".$as_tipodestino."' AND PCM.cod_pro='".$as_codpro."'          AND ".
			"       PCM.ced_bene='".$as_cedbene."'         AND (PMV.monto < 0)";
	 //print $ls_sql."<br>";		
	 $rs_cmp=$this->io_sql->select($ls_sql);
	 if ($rs_cmp===false)
		{
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_montoajustes; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
		}
	 else
		{
		    $lb_valido=true;
			if ($row=$this->io_sql->fetch_row($rs_cmp))
			   {
			     if(array_key_exists("Monto",$row))
				 	$monto=$row["Monto"];
				 else
				 	$monto=0;
			   }  
		}
	return $monto;
}


function uf_load_monto_causados($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$lb_valido)
{
	$ls_operacion1='CG';
	$ls_operacion2='CP';
	$monto=0;
	$ls_sql="SELECT  sum(PMV.monto) AS Monto ".
	        "FROM    spg_dt_cmp PMV, sigesp_cmp PCM ".
			"WHERE   PCM.Procede=PMV.Procede             AND PCM.Comprobante=PMV.Comprobante       AND ".
    	    "        PCM.fecha=PMV.fecha                 AND PMV.procede_doc='".$as_procedencia."' AND ".
			"        PMV.documento='".$as_comprobante."' AND PMV.operacion='".$ls_operacion1."'    OR  ".
			"        PMV.operacion='".$ls_operacion2."'  AND PCM.tipo_destino='".$as_tipodestino."' AND".
        	"        PCM.cod_pro='".$as_codpro."'        AND PCM.ced_bene='".$as_cedbene."'";
    $rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_montocausados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	     if(array_key_exists("Monto",$row))
				 	$monto=$row["Monto"];
				  else
				  	$monto=0;
			 }  
		}
	return $monto;
}


function uf_load_monto_anulados($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$lb_valido) 
{
       $ls_operacion1='CG';
	   $ls_operacion2='CP';
	   $gestor=$_SESSION["ls_gestor"];
	   if ($gestor=="MYSQL")
	      {
	        $ls_str_1="CONCAT(PMV.codestpro1,PMV.codestpro2,PMV.codestpro3,PMV.codestpro4,PMV.codestpro5, ".
			          "        PMV.spg_cuenta,PMV.procede,PMV.comprobante) ";
			$ls_str_2="CONCAT(PMV1.codestpro1,PMV1.codestpro2,PMV1.codestpro3,PMV1.codestpro4,PMV1.codestpro5,PMV1.spg_cuenta,".
			          "PMV1.procede_doc,PMV1.documento) ";
		  }
	   else
	      {
	        $ls_str_1=" PMV.codestpro1||PMV.codestpro2||PMV.codestpro3||PMV.codestpro4||PMV.codestpro5||PMV.spg_cuenta||PMV.procede||PMV.comprobante ";
	        $ls_str_2=" PMV1.codestpro1||PMV1.codestpro2||PMV1.codestpro3||PMV1.codestpro4||PMV1.codestpro5||PMV1.spg_cuenta||PMV1.procede_doc||PMV1.documento ";		  
	     }
	   $ls_sql="SELECT sum(PMV.monto) AS Monto ".
	           "FROM   sigesp_cmp PCM, spg_dt_cmp PMV ".
			   "WHERE  PCM.tipo_destino='".$as_tipodestino."' AND  PCM.cod_pro='".$as_codpro."'         AND".
               "       PCM.ced_bene='".$as_cedbene."'         AND  PCM.procede=PMV.procede              AND".
			   "       PCM.comprobante=PMV.comprobante        AND  PCM.fecha=PMV.fecha                  AND".
			   "       PMV.procede_doc='".$as_procedencia."'  AND  PMV.documento='".$as_comprobante."'  AND".
	           "       (PMV.operacion='".$ls_operacion1."'    OR   PMV.operacion='".$ls_operacion2."')  AND".
	           "       ".$ls_str_1." IN (SELECT ".$ls_str_2." ".
               "	        FROM  sigesp_cmp PCM1, spg_dt_cmp PMV1           ".
			   "            WHERE PCM1.tipo_destino='".$as_tipodestino."' AND".
               "                  PCM1.cod_pro='".$as_codpro."'           AND".
               "                  PCM1.ced_bene='".$as_cedbene."'         AND".
			   "                  PCM1.procede=PMV1.procede               AND".
			   "                  PCM1.comprobante=PMV1.comprobante       AND".
			   "                  PCM1.fecha=PMV1.fecha                   AND".
			   "                  (PMV1.operacion='".$ls_operacion1."'    OR ".
			   "                  PMV1.operacion='".$ls_operacion2."'))      ";
	$rs_cmp=$this->io_sql->select($ls_sql);
	$monto=0;
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_buscar_anulados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  print $this->io_sql->message;
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   	if(array_key_exists("Monto",$row))					 
				 	$monto=$row["Monto"];
				else
					$monto=0;
			 }  
		}
	return $monto;
}


function uf_load_monto_recepciones($as_comprobante,$as_procedencia,&$lb_valido)
{
    $ls_estatus='R';
	$ls_sql="SELECT sum(XPG.monto) AS monto ".
	        "FROM   cxp_rd_spg XPG,cxp_rd XRD ".
			"WHERE  XRD.cod_pro=XPG.cod_pro               AND XRD.ced_bene=XPG.ced_bene   AND ".
			"       XRD.codtipdoc=XPG.codtipdoc           AND XRD.numrecdoc=XPG.numrecdoc AND ".
			"       XPG.procede_doc='".$as_procedencia."' AND XPG.numdoccom='".$as_comprobante."' AND ".
			"       XRD.estprodoc='".$ls_estatus."'";
	$rs_data=$this->io_sql->select($ls_sql);

	if ($rs_data===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_monto_recepciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_data))
			 {
		  	   $monto=$row["monto"];
			 }  
		}
	return $monto;
}

function uf_load_monto_ordenespago_directa($as_comprobante,$as_procedencia,&$lb_valido)
{
	$monto=0;
	$ls_sql="SELECT sum(a.monto) as monto
			 FROM   scb_movbco_spgop a,scb_movbco b
			 WHERE  a.codemp=b.codemp AND a.numdoc=b.numdoc AND a.codope=b.codope AND a.codban=b.codban AND a.ctaban=b.ctaban
			 AND    a.procede_doc='".$as_procedencia."' AND a.documento='".$as_comprobante."'";
	$rs_data=$this->io_sql->select($ls_sql);

	if ($rs_data===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_monto_ordenespago_directa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_data))
			 {
		  	   $monto=$row["monto"];
			 }  
		}
	return $monto;
}

function uf_load_monto_cargos($as_comprobante,$as_procedencia,&$lb_valido)
{
	$ls_estatus='R';//Recibido...
	$monto=0;
	$ls_sql="SELECT sum(XDC.MonRet) AS Monto     ".
	        "FROM   cxp_rd_cargos XDC,cxp_rd XRD ".
			"WHERE  (XRD.numrecdoc=XDC.numrecdoc AND XRD.codtipdoc = XDC.codtipdoc AND ".
			"        XRD.cod_pro=XDC.cod_pro     AND XRD.ced_bene=XDC.ced_bene)    AND ".
			"       (XDC.procede_doc='".$as_procedencia."' AND XDC.numdoccom='".$as_comprobante."') AND ".
			"       (XRD.estprodoc = '".$ls_estatus."')";
			
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_monto_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   if(array_key_exists("Monto",$row))
				 	$monto=$row["Monto"];
			   else
			   		$monto=0;
			 }  
		}
	return $monto;
}


function uf_load_solicitudes($as_comprobante,$as_procedencia)
{
    $lb_valido=false;
	$ls_sql="SELECT *                                                                             ".
	        "FROM   cxp_rd_spg XPG, cxp_rd XRD                                                    ".
			"WHERE  (XRD.cod_pro = XPG.cod_pro             AND XRD.ced_bene = XPG.ced_bene    AND ".
			"       XRD.codtipdoc = XPG.codtipdoc          AND XRD.numrecdoc = XPG.numrecdoc) AND ".
            "       (XPG.procede_doc='".$as_procedencia."' AND XPG.numdoccom='".$as_comprobante."')";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
		{
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_solicitudes; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
		}
	 else
		{
		  $li_numrows = $this->io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
		       $lb_valido=true; 
			 }
		}
	return $lb_valido;
}


function uf_load_acumulado_solicitudes($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,&$lb_valido)
{
  $monto=0;
  $ls_sql=" SELECT  SUM(ds.monto) AS Solicitado ".
          " FROM    cxp_rd rd, cxp_dt_solicitudes ds".
		  " WHERE   rd.numrecdoc='".$as_numrecdoc."' AND rd.codtipdoc='".$as_codtipdoc."' AND".
          "        rd.cod_pro='".$as_codpro."'       AND rd.ced_bene='".$as_cedbene."'    AND".
		  "        rd.numrecdoc=ds.numrecdoc         AND rd.codtipdoc=ds.codtipdoc"; 
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_acumulado_solicitudes; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   if(array_key_exists("Solicitado",$row))
				 	$monto=$row["Solicitado"];
			   else
			   		$monto=0;
			 }  
		}
	return $monto;
}

function uf_load_dtcomprobante($as_comprobante,$as_procedencia,$as_fecha,&$lb_valido)
{
	 $ls_operacion='CS';
	 $ls_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha); 
	 $ls_sql="SELECT procede, comprobante, fecha, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, 
	 			     spg_cuenta, sum(monto) AS monto, estcla 
   	            FROM spg_dt_cmp 
			   WHERE procede='".$as_procedencia."' 
			     AND comprobante='".$as_comprobante."' 
				 AND fecha='".$ls_fecha."'
				 AND operacion='".$ls_operacion."'
               GROUP BY procede, comprobante, fecha, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, procede_doc, 
			            codemp, spg_cuenta";
	 //print $ls_sql;
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
		{
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_dtcomprobante; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
		}
	 else
		{
		    $lb_valido=true;
		}
	return $rs_data;
}//Fin de la funcion detalle...


function uf_rddc_ajustes($as_procedencia,$as_comprobante,$as_tipodestino,$as_codpro,$as_cedbene,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$lb_valido)
{
	 $ls_operacion ='CS';
	 $monto=0;
	 $ls_sql="SELECT sum(spg_dt_cmp.monto) AS Monto ".
	         "  FROM sigesp_cmp, spg_dt_cmp ".
			 " WHERE sigesp_cmp.codemp='".$this->ls_codemp."'
			     AND spg_dt_cmp.procede_doc='".$as_procedencia."'
			     AND spg_dt_cmp.documento='".$as_comprobante."'
			     AND spg_dt_cmp.operacion='".$ls_operacion."'
				 AND sigesp_cmp.tipo_destino='".$as_tipodestino."'
				 AND sigesp_cmp.cod_pro='".$as_codpro."'
				 AND sigesp_cmp.ced_bene='".$as_cedbene."'
				 AND spg_dt_cmp.codestpro1='".$as_codestpro1."'
				 AND spg_dt_cmp.codestpro2='".$as_codestpro2."'
				 AND spg_dt_cmp.codestpro3='".$as_codestpro3."'
				 AND spg_dt_cmp.codestpro4='".$as_codestpro4."'
				 AND spg_dt_cmp.codestpro5='".$as_codestpro5."'
				 AND spg_dt_cmp.spg_cuenta='".$as_spgcuenta."'
				 AND spg_dt_cmp.estcla='".$as_estcla."'
				 AND spg_dt_cmp.monto<0	 
			     AND sigesp_cmp.codemp=spg_dt_cmp.codemp 
				 AND sigesp_cmp.procede=spg_dt_cmp.procede 
				 AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante 
				 AND sigesp_cmp.fecha=spg_dt_cmp.fecha";
	
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rddc_ajustes; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   	if(array_key_exists("Monto",$row))
				 	$monto=$row["Monto"];
				else
					$monto=0;
			 }  
		}
	return $monto;
}

function uf_rddc_causados($as_procedencia,$as_comprobante,$as_tipodestino,$as_codpro,$as_cedbene,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$lb_valido)
{
	$ls_operacion1='CP';	
	$ls_operacion2='CG';
	$monto=0;
	$ls_sql="SELECT sum(spg_dt_cmp.monto) AS Monto
	           FROM spg_dt_cmp, sigesp_cmp
			  WHERE sigesp_cmp.codemp='".$this->ls_codemp."'
			    AND sigesp_cmp.tipo_destino='".$as_tipodestino."' 
				AND sigesp_cmp.cod_pro='".$as_codpro."'
				AND sigesp_cmp.ced_bene='".$as_cedbene."'			    
				AND spg_dt_cmp.procede_doc='".$as_procedencia."'  
				AND spg_dt_cmp.documento='".$as_comprobante."'
				AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')
				AND spg_dt_cmp.codestpro1='".$as_codestpro1."'
				AND spg_dt_cmp.codestpro2='".$as_codestpro2."'
				AND spg_dt_cmp.codestpro3='".$as_codestpro3."'
				AND spg_dt_cmp.codestpro4='".$as_codestpro4."'
				AND spg_dt_cmp.codestpro5='".$as_codestpro5."'
				AND spg_dt_cmp.spg_cuenta='".$as_spgcuenta."'
				AND spg_dt_cmp.estcla='".$as_estcla."'
				AND sigesp_cmp.codemp=spg_dt_cmp.codemp
				AND sigesp_cmp.procede=spg_dt_cmp.procede 
				AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante 
				AND sigesp_cmp.fecha=spg_dt_cmp.fecha";
			
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  echo $this->io_sql->message;
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rddc_causados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   if(array_key_exists("Monto",$row))
			   		$monto=$row["Monto"];
			   	else
			   		$monto=0;
			 }  
		}
	return $monto;
}

function uf_rddc_anulados($as_procedencia,$as_comprobante,$as_tipodestino,$as_codpro,$as_cedbene,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$lb_valido)
{
	$ls_operacion1='CP';	
	$ls_operacion2='CG';
	$monto=0;
	$ls_sql="SELECT sum(spg_dt_cmp.monto) AS Monto 
	           FROM sigesp_cmp, spg_dt_cmp
			  WHERE sigesp_cmp.codemp='".$this->ls_codemp."'
			    AND spg_dt_cmp.procede_doc='".$as_procedencia."'  
			    AND spg_dt_cmp.documento='".$as_comprobante."'
			    AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."') 
				AND spg_dt_cmp.codestpro1='".$as_codestpro1."'
				AND spg_dt_cmp.codestpro2='".$as_codestpro2."'  
				AND spg_dt_cmp.codestpro3='".$as_codestpro3."'
				AND spg_dt_cmp.codestpro4='".$as_codestpro4."'  
				AND spg_dt_cmp.codestpro5='".$as_codestpro5."'
				AND spg_dt_cmp.spg_cuenta='".$as_spgcuenta."'
				AND spg_dt_cmp.estcla='".$as_estcla."'
				AND sigesp_cmp.tipo_destino='".$as_tipodestino."'
				AND sigesp_cmp.cod_pro='".$as_codpro."'
				AND  sigesp_cmp.ced_bene='".$as_cedbene."'
			    AND sigesp_cmp.procede=spg_dt_cmp.procede			
			    AND sigesp_cmp.procede=spg_dt_cmp.procede
			    AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante
				AND sigesp_cmp.fecha=spg_dt_cmp.fecha";
			
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rddc_anulados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   if(array_key_exists("Monto",$row))
				 $monto=$row["Monto"];
			   else
			   	 $monto=0;	
			 }  
		}
	return $monto;
}


function uf_rddc_recdoc($as_procedencia,$as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$lb_valido)
{
    $ls_estatus='R';
    $monto=0;
	$ls_codestpro=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	$ls_sql="SELECT sum(cxp_rd_spg.monto) AS Monto
	           FROM cxp_rd, cxp_rd_spg
			  WHERE cxp_rd.codemp='".$this->ls_codemp."' 
                AND cxp_rd_spg.procede_doc='".$as_procedencia."'
				AND cxp_rd_spg.numrecdoc='".$as_comprobante."'
				AND cxp_rd_spg.codestpro='".$ls_codestpro."'
				AND cxp_rd_spg.spg_cuenta='".$as_spgcuenta."'
				AND cxp_rd_spg.estcla='".$as_estcla."'
				AND cxp_rd.estprodoc='".$ls_estatus."'
				AND cxp_rd.codemp=cxp_rd_spg.codemp
				AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro
				AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene
				AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc
				AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc";
						
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rddc_recdoc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	   if(array_key_exists("Monto",$row))
				 $monto=$row["Monto"];
				else
					$monto=0;
			 }  
		}
	return $monto;
}

function uf_rddc_recdoc_cargos($as_procedencia,$as_numrecdoc,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$lb_valido)
{
    $ls_estatus='R';
    $monto=0;
	$ls_sql="SELECT sum(cxp_rd_cargos.monret) AS Monto
	           FROM cxp_rd_cargos, cxp_rd
			  WHERE cxp_rd.codemp='".$this->ls_codemp."'
			    AND cxp_rd_cargos.procede_doc='".$as_procedencia."'
				AND cxp_rd_cargos.numrecdoc='".$as_numrecdoc."'
				AND cxp_rd_cargos.codestpro1='".$as_codestpro1."'
				AND cxp_rd_cargos.codestpro2='".$as_codestpro2."'
				AND cxp_rd_cargos.codestpro3='".$as_codestpro3."'
				AND cxp_rd_cargos.codestpro4='".$as_codestpro4."'
				AND cxp_rd_cargos.codestpro5='".$as_codestpro5."'
				AND cxp_rd_cargos.estcla='".$as_estcla."'
				AND cxp_rd_cargos.spg_cuenta='".$as_spgcuenta."'
				AND cxp_rd.estprodoc='".$ls_estatus."'
                AND cxp_rd.codemp=cxp_rd_cargos.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_cargos.numrecdoc
				AND cxp_rd.codtipdoc=cxp_rd_cargos.codtipdoc
				AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro
				AND cxp_rd.ced_bene=cxp_rd_cargos.ced_bene";
	
	$rs_cmp=$this->io_sql->select($ls_sql);
	if ($rs_cmp===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rddc_recdoc_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	 else
		{
   		  $lb_valido=true;
		  if ($row=$this->io_sql->fetch_row($rs_cmp))
			 {
		  	  	if(array_key_exists("Monto",$row))
					$monto=$row["Monto"];
				else
					$monto=0;
			 }  
		}
	return $monto;
}

function uf_load_dtorden_compra($as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido)
{
    $lb_valido=false;
	$ls_sql=" SELECT * FROM soc_cuentagasto WHERE numordcom='".$as_comprobante."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {	
	     $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_dtorden_compra; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	     $lb_valido=false;
	   }
	 else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			  $lb_valido=true; 
			}
	   }
return $rs_data;
}

function uf_select_cargos_sep($as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta)
{
    $lb_existe=false;
	$ls_sql="SELECT  codcar,monobjret,monret ".
	        "FROM    sep_solicitudcargos     ".
			"WHERE   numsol='".$as_comprobante."'    AND codestpro1='".$as_codestpro1."' AND ".
			"        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
			"        codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' AND ".
			"        spg_cuenta='".$as_spgcuenta."'"; 
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {	
	     $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_select_cargos_sep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	 else
	   {
	     if ($row=$this->io_sql->fetch_row($rs_data))
			{
			  $lb_existe=true; 
			}
	   }
return $lb_existe;
}


function uf_rd_ajusta_spg($as_tipo,$as_codpro,$as_cedbene,$as_fechahasta,$as_codtipdoc,
					      $as_procede,$as_comprobante,$as_feccomp,$ad_cargos)
{
	  $lb_valido=$this->uf_rd_ajusta_spg_down_cargos($as_procede,$as_comprobante,$as_codpro,$ad_cargos);
return $lb_valido;
}

function uf_rd_ajusta_spg_down_cargos($as_procedencia,$as_numdoc,$as_codpro,&$ld_cargo)
{
switch ($as_procedencia)
  {
   case 'SOCCOC':
		$ls_sql=" SELECT soc_solicitudcargos.* ".
		        "   FROM soc_solicitudcargos,soc_ordencompra,sigesp_cargos".
				"  WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."'
				     AND soc_ordencompra.numordcom='".$as_numdoc."' 
					 AND soc_ordencompra.cod_pro='".$as_codpro."'
					 AND (soc_ordencompra.estcondat='B' OR soc_ordencompra.estcondat='-' OR soc_ordencompra.estcondat='')
					 AND soc_solicitudcargos.codemp=soc_ordencompra.codemp
					 AND soc_solicitudcargos.numordcom=soc_ordencompra.numordcom
					 AND soc_solicitudcargos.codemp=sigesp_cargos.codemp					 
			 	     AND soc_solicitudcargos.codcar=sigesp_cargos.codcar";
		break; 
   case 'SEPSPC':
		  $ls_sql=" SELECT sep_solicitudcargos.* ".
		          "   FROM sep_solicitudcargos, sep_solicitud, sigesp_cargos".
				  "  WHERE sep_solicitud.codemp='".$this->ls_codemp."' 
					   AND sep_solicitudcargos.numsol='".$as_numdoc."'
					   AND sep_solicitud.cod_pro='".$as_codpro."'
				       AND sep_solicitudcargos.codemp=sep_solicitud.codemp
					   AND sep_solicitudcargos.numsol=sep_solicitud.numsol 
					   AND sep_solicitudcargos.codemp=sigesp_cargos.codemp
					   AND sep_solicitudcargos.codcar=sigesp_cargos.codcar";
		break; 
	case 'SOCCOS':
		$ls_sql=" SELECT soc_solicitudcargos.* ".
		        "   FROM soc_solicitudcargos,soc_ordencompra ord,sigesp_cargos car".
				"  WHERE ord.codemp='".$this->ls_codemp."' 
				     AND ord.numordcom='".$as_numdoc."' 
					 AND ord.cod_pro='".$as_codpro."' 
					 AND ord.estcondat='S'
					 AND soc_solicitudcargos.codemp=ord.codemp
					 AND soc_solicitudcargos.numordcom=ord.numordcom 
				     AND soc_solicitudcargos.codemp=car.codemp
					 AND soc_solicitudcargos.codcar=car.codcar";
		break; 	
   }
   $rs_data=$this->io_sql->select($ls_sql);
   if ($rs_data===false)
	  {
		$lb_valido=false; 
	    $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_rd_ajusta_spg_down_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  	print ($this->io_sql->message);
	  }
   else
      {
	    if ($row=$this->io_sql->fetch_row($rs_data))
		   {
		     $ld_cargo=$row["monret"];
		   }
 	    $lb_valido=true;
	  }
return $lb_valido; 
 }
 
function uf_load_total_cargos_soc($as_codemp,$as_numrecdoc,&$ad_monto)
{
  $ls_sql=" SELECT COALESCE(SUM(monret),0) AS cargos".
          " FROM soc_solicitudcargos ".
		  " WHERE codemp='".$as_codemp."' AND numordcom='".$as_numrecdoc."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if($rs_data===false)
    {
	  $lb_valido=false;
	  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_total_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
  else
    {
	  if ($row=$this->io_sql->fetch_row($rs_data))
	     {
		   if(array_key_exists("cargos",$row))
		   		$ad_monto=$row["cargos"];
		   	else
		   		$ad_monto=0;
		 }
	  else
	     {
		   $ad_monto=0;
		 }	 
	  $lb_valido=true;
	}
return $lb_valido;
}

function uf_load_total_cargos_sep($as_codemp,$as_numsol,&$ad_monto)
{
  $ls_sql=" SELECT COALESCE(SUM(monret),0) AS cargos".
          " FROM sep_solicitudcargos ".
		  " WHERE codemp='".$as_codemp."' AND numsol='".$as_numsol."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if($rs_data===false)
    {
	  $lb_valido=false;
	  $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_total_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
  else
    {
	  if ($row=$this->io_sql->fetch_row($rs_data))
	     {
		   if(array_key_exists("cargos",$row))
		   		$ad_monto=$row["cargos"];
		   	else
		   		$ad_monto=0;
		 }
	  else
	     {
		   $ad_monto=0;
		 }	 
	  $lb_valido=true;
	}
return $lb_valido;
}

function uf_load_cuenta_contable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido)
{
  $ls_sql=" SELECT sc_cuenta ".
          " FROM spg_cuentas ".
		  " WHERE codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
		  "       codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' AND spg_cuenta='".$as_spgcuenta."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_cuenta_contable; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   $lb_valido=true;
	 }
return $rs_data;
}

function uf_load_solicitudes_pago($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_solicitudes_pago
//	          Access:  public
//          Arguments  
//     $ls_numrecdoc:  Número de la Recepción de Documento.
//     $ls_codtipdoc:  Código del Tipo de Documento.
//        $as_codpro:  Código del Proveedor para realizar la búsqueda.
//       $as_cedbene:  Cédula del Beneficiario para realizar la búsqueda.
//            Returns		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.             
//       Description:  Función que se encarga de localizar solicitudes de pago asociadas a una Recepción de Documento.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  22/05/2006        Fecha Última Actualización:22/05/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $lb_valido=false;
  $ls_sql=" SELECT * FROM cxp_dt_solicitudes ".
          " WHERE codemp='".$as_codemp."' AND numrecdoc = '".$as_numrecdoc."' AND codtipdoc = '".$as_codtipdoc."' AND ".
		  " cod_pro = '".$as_codpro."' AND ced_bene = '".$as_cedbene."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_CLASS_SCB; METODO->uf_load_solicitudes_pago; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    $lb_valido=true;
		  }
	 }
return $lb_valido;
}
}//Fin de la Clase CXP...
?> 