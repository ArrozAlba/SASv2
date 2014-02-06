<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_sep_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de presupuesto de  gasto y el sistema de compra.                      //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
require_once("../shared/class_folder/class_sql.php");  
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
class sigesp_scb_c_elimin_anulado
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_solicitud;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	function sigesp_scb_c_elimin_anulado()
	{
		$this->io_function = new class_funciones() ;
		$this->io_include  = new sigesp_include();
		$this->io_connect  = $this->io_include->uf_conectar();
		$this->io_sql      = new class_sql($this->io_connect);		
		$this->dts_empresa = $_SESSION["la_empresa"];
	}

    // MOVIMIENTOS BANCARIOS
	function uf_select_banco_contabilizar( $as_operacion_bco, &$arr_object ,&$ai_total_record,$as_estatus,$as_numdoc,$ad_fecha )
	{	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function :	uf_select_banco_contabilizar
		//       Access :	public
		//   Argumentos :   $as_operacion_bco->operacion movimiento de banco 
		//                  &$arr_object-> arreglo de objetos pantalla pintar 
		//                  &$ai_total_record->total de registros por valor 
		//                  $as_estatus->estatus de los movimientos a consultar
		//	    Returns :	movimiento contabilizado boolean
		//	Description :	Método que obtiene todas aquellos movimientos de banco en estatus 
		//                  para su contabilizacion
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_row=0;
        $lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_aux="";
		if($ad_fecha!="")
		{
			$ld_fecha=$this->io_function->uf_convertirdatetobd($ad_fecha);			
			$ls_aux=" AND fecmov='".$ld_fecha."' ";
		}
		if($as_numdoc!="")
		{
			$ls_aux=$ls_aux." AND numdoc like '%".$as_numdoc."%' ";
		}
		$ls_mysql  = " SELECT codban, ctaban, estmov, numdoc, fecmov, conmov, estcon ".
                     "   FROM scb_movbco ".
					 "  WHERE codemp='".$ls_codemp."' AND estmov='".$as_estatus."' AND codope='".$as_operacion_bco."' AND monto=0 ".$ls_aux;
		$ls_mysql=$ls_mysql."AND ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 

		$rs_data=$this->io_sql->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	        print "ERROR->".$this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_row++;
				$ls_codban = $row["codban"];
				$ls_ctaban = $row["ctaban"];
				$ls_estmov = $row["estmov"];
				$ls_numdoc = $row["numdoc"];
				$ls_fecmov = $this->io_function->uf_formatovalidofecha($row["fecmov"]);
				$ls_fecmov = $this->io_function->uf_convertirfecmostrar($ls_fecmov);
				$ls_conmov = $row["conmov"];
				$ls_estcon = $row["estcon"];
				$arr_object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px >";		
				$arr_object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=17 maxlength=15><input name=estcon".$li_row."  type=hidden  value=".$ls_estcon.">";
				$arr_object[$li_row][3] = "<input type=text     name=txtfecmov".$li_row." value='".$ls_fecmov."' class=sin-borde readonly style=text-align:center size=12 maxlength=10>";
				$arr_object[$li_row][4] = "<input type=text     name=txtconmov".$li_row." value='".$ls_conmov."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>".
     				                      "<input type=hidden   name=txtcodban".$li_row." value='".$ls_codban."'>".
	   				                      "<input type=hidden   name=txtctaban".$li_row." value='".$ls_ctaban."'>".
										  "<input type=hidden   name=txtestmov".$li_row." value='".$ls_estmov."'>";
										  
			}
			if($li_row==0)
			{
				$li_total=1;
				for($li_row=1;$li_row<=$li_total;$li_row++)
				{
					$arr_object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px onClick='return false;'>";		
					$arr_object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."       value='' class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
					$arr_object[$li_row][3] = "<input type=text     name=txtfecmov".$li_row."       value='' class=sin-borde readonly style=text-align:left size=12 maxlength=10>";
					$arr_object[$li_row][4] = "<input type=text     name=txtconmov".$li_row."       value='' class=sin-borde readonly style=text-align:center size=60 maxlength=60>".
     				                          "<input type=hidden   name=txtcodban".$li_row."       value='' >".
	   				                          "<input type=hidden   name=txtctaban".$li_row."       value='' >".
										      "<input type=hidden   name=txtestmov".$li_row."       value='' >";
					
				}
				$li_row=$li_total;
			}
		    $this->io_sql->free_result($rs_data);					
		}
		$ai_total_record = $li_row;		
		return $lb_valido;
	}  // fin function 
	
} // end class
?>
