<?php
class sigesp_spg_c_eliminar_comprobantes
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;	
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
	function sigesp_spg_c_eliminar_comprobantes()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");	
		require_once("../shared/class_folder/class_funciones.php");		
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		/*require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");*/
	
		$this->io_function=new class_funciones();			
		$this->io_fecha=new class_fecha();
		$this->io_include=new sigesp_include();	
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();		
		$this->is_msg_error="";		
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$this->li_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	
	function uf_buscar_comprobantes(&$ao_object,&$ai_totrows,$as_fecdesde, $as_fechasta)
	{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_comprobantes
		//		   Access: public
		//	    Arguments: 
		//	      Returns: 
		//	  Description: función que busca los comprobante de ejecuciòn financiera y de modificaciones presupuestari
		//     Creado por: Ing. Jennifer Rivero
		//     Fecha de Creaciòn: 22/10/2008	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio1="";
		$ls_criterio2="";
		if (!empty($as_fecdesde))
		{
		   if (!empty($as_fechasta))
		   {
		        $as_fecdesde=$this->io_function->uf_convertirdatetobd($as_fecdesde);
				$as_fechasta=$this->io_function->uf_convertirdatetobd($as_fechasta);
				$ls_criterio1=" AND sigesp_cmp.fecha between '".$as_fecdesde."' AND '".$as_fechasta."'";
				$ls_criterio2=" AND sigesp_cmp_md.fecha between '".$as_fecdesde."' AND '".$as_fechasta."'";
			}
		}
		$ls_sql=" SELECT sigesp_cmp.comprobante, sigesp_cmp.procede,                    ".
				        "        sigesp_cmp.fecha, sigesp_cmp.descripcion, sigesp_cmp.cod_pro,  ".
						"        sigesp_cmp.ced_bene, sigesp_cmp.total, sigesp_cmp.codban, sigesp_cmp.ctaban,  ".
						"	     (SELECT  rpc_proveedor.nompro                                  ".
						"		    FROM rpc_proveedor                                          ".
						"		   WHERE rpc_proveedor.codemp='".$this->li_codemp."'            ".                     
						"		     AND rpc_proveedor.cod_pro=sigesp_cmp.cod_pro) as nompro,   ".
						"	     (SELECT rpc_beneficiario.nombene                               ".     
						"		    FROM rpc_beneficiario                                       ".
						"		   WHERE rpc_beneficiario.codemp='".$this->li_codemp."'         ".
						"		     AND rpc_beneficiario.ced_bene=sigesp_cmp.ced_bene) as nombene ".
						"   FROM sigesp_cmp                                                     ".
						"  WHERE  sigesp_cmp.procede                                            ".
						"   LIKE '%SPGCMP%'                                                     ".
						"    AND  sigesp_cmp.comprobante NOT IN (SELECT  spg_dt_cmp.comprobante ".
						"                                          FROM spg_dt_cmp              ".
						"                                         WHERE  spg_dt_cmp.procede LIKE '%SPG%') ".
						"    AND sigesp_cmp.comprobante NOT IN (SELECT scg_dt_cmp.comprobante ".
						"                                        FROM scg_dt_cmp WHERE scg_dt_cmp.procede LIKE '%SPG%')".
						$ls_criterio1. 
						"   UNION                                                               ".
						"  SELECT sigesp_cmp_md.comprobante, sigesp_cmp_md.procede,             ".
						"         sigesp_cmp_md.fecha, sigesp_cmp_md.descripcion, sigesp_cmp_md.cod_pro, ".
						"         sigesp_cmp_md.ced_bene, sigesp_cmp_md.total,".
						"         '---' as codban, '-------------------------' as ctaban,                ".
						"	   (SELECT  rpc_proveedor.nompro                                             ".
						"		  FROM rpc_proveedor                                                     ".
						"		 WHERE rpc_proveedor.codemp='".$this->li_codemp."'              ".                                
						"		   AND rpc_proveedor.cod_pro=sigesp_cmp_md.cod_pro) as nompro,  ".
						"	   (SELECT rpc_beneficiario.nombene                                 ".
						"		  FROM rpc_beneficiario                                         ".
						"		 WHERE rpc_beneficiario.codemp='".$this->li_codemp."'           ". 
						"		   AND rpc_beneficiario.ced_bene=sigesp_cmp_md.ced_bene) as nombene  ".
						"  FROM sigesp_cmp_md                       ".
						" WHERE sigesp_cmp_md.procede LIKE '%SPG%'  ".
						"   AND sigesp_cmp_md.comprobante NOT IN (SELECT spg_dtmp_cmp.comprobante  ".
						"                                           FROM spg_dtmp_cmp WHERE spg_dtmp_cmp.procede LIKE '%SPG%')  ".
						"   AND sigesp_cmp_md.comprobante NOT IN (SELECT scg_dt_cmp.comprobante ".
						"                                        FROM scg_dt_cmp WHERE scg_dt_cmp.procede LIKE '%SPG%')".
						$ls_criterio2.
						"  ORDER BY comprobante "; 
			$rs_data = $this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->Eliminar Comprobantes sin detalles MÉTODO->uf_buscar_comprobantes ERROR->"
				                      .$this->io_function->uf_convertirmsg($this->io_sql->message));			
			}
			else
		    {
			   $li_numrows=$this->io_sql->num_rows($rs_data);
			   if($li_numrows > 0)
			   {
					while($row=$this->io_sql->fetch_row($rs_data))
					{	
					    $ai_totrows=$ai_totrows+1;
						$ls_comprobante=rtrim($row["comprobante"]);
						$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
						$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
						$ls_procede=rtrim($row["procede"]);
						$ls_descripcion=rtrim($row["descripcion"]);	
						$ls_monto=rtrim($row["total"]);
						$ls_monto=number_format($ls_monto,2,",",".");
						$ls_codban=rtrim($row["codban"]);
						$ls_ctaban=rtrim($row["ctaban"]);						
						$ao_object[$ai_totrows][1] = "<input type=checkbox name=selusu".$ai_totrows." id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' >
						<input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>
						<input name=txtcodban".$ai_totrows." type=hidden id=txtcodban".$ai_totrows." value='".$ls_codban."' readonly>
						<input name=txtctaban".$ai_totrows." type=hidden id=txtctaban".$ai_totrows." value='".$ls_ctaban."' readonly>";
						$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
						$ao_object[$ai_totrows][3] ="<input name=txtprocede".$ai_totrows." type=text id=txtprocede".$ai_totrows." value='".$ls_procede."' class=sin-borde style=text-align:center size=15 maxlength=12>";
						$ao_object[$ai_totrows][4] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
						$ao_object[$ai_totrows][5] = "<input type=text name=txtdescomp".$ai_totrows."  value='".$ls_descripcion."'   class=sin-borde readonly style=text-align:center size=60 maxlength=10 >";
						$ao_object[$ai_totrows][6] = "<input type=text name=txtmonto".$ai_totrows."  value='".$ls_monto."'   class=sin-borde readonly style=text-align:right size=20 maxlength=10>";					
													 
					}
					$this->io_sql->free_result($rs_data);
			   }
			   else
			   {
					$ai_totrows=$ai_totrows+1;
					$ao_object[$ai_totrows][1]=  "<input type=checkbox name=selusu".$ai_totrows." id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' >
					<input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>
					<input name=txtcodban".$ai_totrows." type=hidden id=txtcodban".$ai_totrows." value='' readonly>
						<input name=txtctaban".$ai_totrows." type=hidden id=txtctaban".$ai_totrows." value='' readonly>";
					$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
					$ao_object[$ai_totrows][3] = "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." value='' class=sin-borde style=text-align:center size=15 maxlength=12>";
					$ao_object[$ai_totrows][4] = "<input type=text name=txtfecha".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
					$ao_object[$ai_totrows][5] = "<input type=text name=txtdescomp".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=60 maxlength=10 >";
					$ao_object[$ai_totrows][6] = "<input type=text name=txtmonto".$ai_totrows."  value=''   class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
					
					 
			   }
		}//else	
	return $lb_valido;		
}// fin de uf_buscar_comprobantes
//--------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
    function uf_eliminar_comprobantes($as_comprobante, $as_procede, $as_fecha, $as_codban, $as_ctaban)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_comprobantes
		//		   Access: public
		//	    Arguments: 
		//	      Returns: 
		//	  Description: función que Elimina los comprobantes sin detalles
		//     Creado por: Ing. Jennifer Rivero
		//     Fecha de Creaciòn: 23/10/2008	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
     	if ($as_procede=="SPGCMP")
		{
			$ls_sql=" DELETE FROM sigesp_cmp WHERE      codemp='".$this->li_codemp."'".
			        "                          AND comprobante='".$as_comprobante."'".
					"                          AND       fecha='".$as_fecha."'".
					"                          AND      codban='".$as_codban."'".
					"                          AND      ctaban='".$as_ctaban."';";
		}
		else
		{
			$ls_sql=" DELETE FROM sigesp_cmp_md WHERE      codemp='".$this->li_codemp."'".
			        "                             AND comprobante='".$as_comprobante."'".
					"                             AND       fecha='".$as_fecha."';";					
		}	
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=false;
			$this->io_msg->message("Error al eliminar comprobantes");
		}	
		return $lb_valido;	
	}// fin uf_eliminar_comprobantes
//---------------------------------------------------------------------------------------------------------------------------------------

}// fin de la calse sigesp_spg_c_eliminar_comprobantes
?>