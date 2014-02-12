<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cobro
 // Autor:       - Ing. Zulheymar Rodr�guez
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_cobro, sfc_cobrofactura y sfc_instpagocob
 // Fecha:       - 03/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_cobranza
{
 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
function sigesp_sfc_c_cobranza()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
	$this->io_datastore= new class_datastore();
}

function uf_select_cobro($ls_numcob,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cobro
		// Parameters:  - $ls_numcob( Codigo del Cobro).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_cobro_cliente
		            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' ";

		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				}
		}
		return $lb_valido;
}

function uf_guardar_cobro($ls_codcli,$ls_numcob,$ls_descob,$ls_feccob,$ld_moncob,$ls_codusu,$ls_estcob,$ls_especial,$ld_montoret,$ls_codtie,$ls_totalret,$as_codcaj,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_cobro.
	// Parameters:  - $ls_codcli( Codigo del cliente).
	//		     - $ls_numcob(N�mero del Cobro).
	//		     - $ls_descob. (Descripci�n del Cobro).
	//		     - $ls_feccob.(Fecha del Cobro).
	//		     - $ls_moncob.(Monto del Cobro).
	//		     -$ls_codusu.(Codigo de Usuario de la Caja):
	//		     -$ls_estcob. (Estatus del Cobro A->Anulado, E->Emitido,C->Cancelado, P->Procesado).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_cobro($ls_numcob,$ls_codtie);
	$ld_moncob=$this->funsob->uf_convertir_cadenanumero($ld_moncob); /* convierte cadena en numero */
	if ($ld_montoret=='' or $ls_totalret=='')
	{
	$ld_montoret=0;
	$ls_totalret=0;
	}else{
	$ld_montoret=$this->funsob->uf_convertir_cadenanumero($ld_montoret);
	$ls_totalret=$this->funsob->uf_convertir_cadenanumero($ls_totalret);
	}
	$ls_feccob=$this->io_funcion->uf_convertirdatetobd($ls_feccob);
	$ls_numcarta='0';
	if(!$lb_existe)
	{
        $ls_cadena= "INSERT INTO sfc_cobro (codemp,codcli,numcob,descob,feccob,moncob,codusu,estcob,esppag,numcarta,codtiend,montoret,totalret,codcaj) VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numcob."','".$ls_descob."','".$ls_feccob."',".$ld_moncob.",'".$ls_codusu."','".$ls_estcob."','".$ls_especial."','".$ls_numcarta."','".$ls_codtie."','".$ld_montoret."','".$ls_totalret."','".$as_codcaj."')";
		/*print 'maestro';*/
		//print $ls_cadena;
		$this->io_msgc="Registro Incluido!!!";
		$ls_evento="INSERT";
		}
	else
	{
		$ls_cadena= "UPDATE sfc_cobro
		             SET codcli='".$ls_codcli."', descob='".$ls_descob."', feccob='".$ls_feccob."', moncob=".$ld_moncob.",
					 codusu='".$ls_codusu."', estcob='".$ls_estcob."',esppag='".$ls_especial."',totalret='".$ls_totalret."' WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."';";

		$this->io_msgc="Registro Actualizado!!!";
		$ls_evento="UPDATE";

	}
 	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_cobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_sql->message;
   	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;

			if($ls_evento=="INSERT")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Inserto el cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
			if($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			}
		}

	}
	if($ls_evento=="INSERT")
			{
			$ls_cadena="SELECT * FROM sfc_cobro WHERE sfc_cobro.numcob<'".$ls_numcob."' AND sfc_cobro.codcli='".$ls_codcli."'
			AND (sfc_cobro.estcob<>'A' AND sfc_cobro.estcob<>'P')";
			//print $ls_cadena;
			$rs_datauni2=$this->io_sql->select($ls_cadena);
			if($row2=$this->io_sql->fetch_row($rs_datauni2))
			{
			$la_prod=$this->io_sql->obtener_datos($rs_datauni2);
			$this->io_datastore->data=$la_prod;
			$totrow2=$this->io_datastore->getRowCount("numcob");
			for($z2=1;$z2<=$totrow2;$z2++)
				{
				$ls_cod=$this->io_datastore->getValue("numcob",$z2);
				$lb_existe2=$this->uf_actualizar_cobrostatus($ls_cod,'C',$ls_codtie,$aa_seguridad,'');
				}
			}
			}
	return $lb_valido;
}

function uf_guardar_cobrocliente($ls_codcli,$ls_numcob,$ls_descob,$ls_feccob,$ld_moncob,$ls_codusu,$ls_estcob,$ls_especial,$ld_montoret,$ls_codtie,$ls_totalret,$as_codcaj,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_cobrocliente.
	// Parameters:  - $ls_codcli( Codigo del cliente).
	//		     - $ls_numcob(N�mero del Cobro).
	//		     - $ls_descob. (Descripci�n del Cobro).
	//		     - $ls_feccob.(Fecha del Cobro).
	//		     - $ls_moncob.(Monto del Cobro).
	//		     -$ls_codusu.(Codigo de Usuario de la Caja):
	//		     -$ls_estcob. (Estatus del Cobro A->Anulado, E->Emitido,C->Cancelado, P->Procesado).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_cobro($ls_numcob,$ls_codtie);
	$ld_moncob=$this->funsob->uf_convertir_cadenanumero($ld_moncob); /* convierte cadena en numero */
	if ($ld_montoret=='' or $ls_totalret=='')
	{
	$ld_montoret=0;
	$ls_totalret=0;
	}else{
	$ld_montoret=$this->funsob->uf_convertir_cadenanumero($ld_montoret);
	$ls_totalret=$this->funsob->uf_convertir_cadenanumero($ls_totalret);
	}
	$ls_feccob=$this->io_funcion->uf_convertirdatetobd($ls_feccob);

	if(!$lb_existe)
	{
        $ls_cadena= "INSERT INTO sfc_cobro_cliente (codemp,codcli,numcob,descob,feccob,moncob,codusu,estcob,esppag,codtiend,montoret,totalret,cod_caja) " .
        		"VALUES ('".$ls_codemp."',".$ls_codcli.",'".$ls_numcob."','".$ls_descob."','".$ls_feccob."',".$ld_moncob.",'".$ls_codusu."','".$ls_estcob."','".$ls_especial."','".$ls_codtie."','".$ld_montoret."','".$ls_totalret."','".$as_codcaj."')";

		/*print 'maestro';*/
		//print $ls_cadena;
		$this->io_msgc="Registro Incluido!!!";
		$ls_evento="INSERT";
		}
	else
	{
		$ls_cadena= "UPDATE sfc_cobro_cliente
		             SET codcli=".$ls_codcli.", descob='".$ls_descob."', feccob='".$ls_feccob."', moncob=".$ld_moncob.",
					 codusu='".$ls_codusu."', estcob='".$ls_estcob."',esppag='".$ls_especial."',totalret='".$ls_totalret."' " .
					 "WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND codtiend='".$ls_codtie."' AND codcli=".$ls_codcli." ;";

		$this->io_msgc="Registro Actualizado!!!";
		$ls_evento="UPDATE";

	}

 	//$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_cobrocliente".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		//$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_sql->message;
   	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;

			if($ls_evento=="INSERT")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Inserto el cobro_cliente ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el cobro_cliente ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			//$this->io_sql->commit();
		}
		else
		{
		//	$this->io_sql->rollback();
			if($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			}
		}

	}
	if($ls_evento=="INSERT" and $lb_valido)
			{
			$ls_cadena="SELECT * FROM sfc_cobro_cliente co WHERE co.numcob<'".$ls_numcob."' AND co.codcli=".$ls_codcli."
			AND (co.estcob<>'A' AND co.estcob<>'P')";
			//print $ls_cadena;
			$rs_datauni2=$this->io_sql->select($ls_cadena);
			if($row2=$this->io_sql->fetch_row($rs_datauni2))
			{
			$la_prod=$this->io_sql->obtener_datos($rs_datauni2);
			$this->io_datastore->data=$la_prod;
			$totrow2=$this->io_datastore->getRowCount("numcob");
			for($z2=1;$z2<=$totrow2;$z2++)
				{
				$ls_cod=$this->io_datastore->getValue("numcob",$z2);
				$lb_existe2=$this->uf_actualizar_cobrostatus($ls_cod,'C',$ls_codtie,$aa_seguridad,'');
				}
			}
			}
	return $lb_valido;
}

function uf_delete_cobro($ls_numcob,$ls_codtie,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_cobro.
	// Parameters:  - $ls_numcob (número del Cobro).
	// Descripcion: - Funcion que elimina un Cobro.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_cobro($ls_numcob,$ls_codtie);
	if($lb_existe)
	{
	    	$ls_cadena= " DELETE FROM sfc_cobro_cliente WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND codtiend='".$ls_codtie."' ";
			$this->io_msgc="Registro Eliminado!!!";

			//$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_cadena);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_msgc="Error en metodo uf_delete_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_sql->message;
				print $this->io_sql->message;
				}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino el cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					//$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->is_msgc="Registro No Eliminado!!!";
					$this->io_sql->rollback();
				}

			}
	}
	else
	{
		$lb_valido=1;
		$this->io_msg->message("El Registro no Existe");
	}
	return $lb_valido;
}

function uf_select_facturacobro($ls_numcob,$ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_facturacobro.
		// Parameters:  - $ls_numcob( N�mero del Cobro).
		//		      -$ls_numfactura (N�mero de la Factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];

		//$ls_cadena="SELECT * FROM sfc_cobrofactura
		//            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numfac='".$ls_numfac."' ORDER BY numfac ASC;";

		$ls_cadena="SELECT * FROM sfc_dt_cobrocliente
		            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numfac='".$ls_numfac."' ORDER BY numfac ASC;";

		$rs_datauni=$this->io_sql->select($ls_cadena);
        //print $ls_cadena;
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_facturacobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{


			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				}
		}
		return $lb_valido;
}

function uf_select_detcobro($ls_numcob)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detcobro.
		// Parameters:  - $ls_numcob( N�mero del Cobro).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_dt_cobrocliente
		            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."';";

		$rs_datauni=$this->io_sql->select($ls_cadena);
       	if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				}
		}
		return $lb_valido;
}

function uf_select_detfactura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( N�mero de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_dt_cobrocliente
		            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
        //print $ls_cadena;
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{


			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				//$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_select_factura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_factura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_actualizar_factura($ls_numfac,$ls_estfaccon,$aa_seguridad)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_actualizar_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		//		      -$ls_estfaccon
		// Descripcion: - Funcion que busca un registro en la tabla sfc_factura y actualiza su estatus.
		//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_factura($ls_numfac);
	$ls_cadena= "UPDATE sfc_factura SET estfaccon='".$ls_estfaccon."' WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
	//$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_actualizar_factura".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_sql->message;
	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el estatus de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			//$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
			if ($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			}//($lb_existe)
		}//($li_numrows>0)

	}// ($li_numrows==false)&&($this->io_sql->message!="")
return $lb_valido;
}

function uf_select_nota($ls_numnot)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND numnot='".$ls_numnot."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_select_nota_cobro($ls_numcob)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_nota_cobro
	// Parameters:  - $ls_numcob( Codigo del cobro acosiado).
	// Descripcion: - Funcion que busca un registro en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////
    $ls_codemp=$this->datoemp["codemp"];
	$ls_cadena="SELECT numnot FROM sfc_nota
	            WHERE codemp='".$ls_codemp."' AND nro_documento='".$ls_numcob."' AND tipnot='CXP' ;";

	$rs_datauni=$this->io_sql->select($ls_cadena);
	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_nota_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_datauni))
		{
			$lb_valido=true;
		}
		else
		{
			$lb_valido=false;
			$this->io_msgc="Registro no encontrado";
		}
	}
	return $lb_valido;
}

function uf_actualizar_nota($ls_numnot,$ls_estnot,$aa_seguridad)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_actualizar_nota
		// Parameters:  - $ls_numfac( Codigo de la factura).
		//		      -$ls_estfaccon
		// Descripcion: - Funcion que busca un registro en la tabla sfc_factura y actualiza su estatus.
		//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_nota($ls_numnot);
	$ls_cadena= "UPDATE sfc_nota SET estnota='".$ls_estnot."' WHERE codemp='".$ls_codemp."' AND numnot='".$ls_numnot."';";

	//$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_actualizar_nota".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_sql->message;
	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo la Nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			//$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
			if ($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			}//($lb_existe)
		}//($li_numrows>0)

	}// ($li_numrows==false)&&($this->io_sql->message!="")
return $lb_valido;
}

function uf_guardar_detcobro($ls_codtie,$ls_codcli,$ls_numcob,$ls_numfac,$ls_tipcancel,$ls_moncancel,$ls_monto,$ls_numfacasoc,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_detcobro
	// Parameters:  - $ls_codcli( Codigo del cliente).
	//		      - $ls_numfac (Numero de la factura).
	//		      - $ls_numcob( numero de cobro).
	//		      - $ls_tipcancel (tipo de cancelacion).
	//		      - $ls_moncancel(monto a cancelar).
	//		      -$ls_monto (monto por cancelar).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////

	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_facturacobro($ls_numcob,$ls_numfac);
	$ls_moncancel=$this->funsob->uf_convertir_cadenanumero($ls_moncancel); /* convierte cadena en numero */
	$ls_montoxcancel=$this->funsob->uf_convertir_cadenanumero($ls_monto);
	if ($ls_numfac==$ls_numfacasoc)
	{
		if ($ls_moncancel==$ls_montoxcancel)
		{
		$lb_existe_fac=$this->uf_actualizar_factura($ls_numfac,'C',$aa_seguridad);
		$lb_existe_not=$this->uf_actualizar_nota($ls_numfac,'C',$aa_seguridad);
		}
		else
		{
		 $lb_existefac=$this->uf_actualizar_factura($ls_numfac,'P',$aa_seguridad);
		 $lb_existe_not=$this->uf_actualizar_nota($ls_numfac,'P',$aa_seguridad);
		}
	}
	else
	{
		if ($ls_moncancel==$ls_montoxcancel)
		{
			$lb_existefactura=$this->uf_select_factura($ls_numfacasoc);
			if ($lb_existefactura)
				{
					$lb_existe_fac=$this->uf_actualizar_factura($ls_numfacasoc,'C',$aa_seguridad);
				}
			$lb_existe_not=$this->uf_actualizar_nota($ls_numfac,'C',$aa_seguridad);
		}
		else
		{
			$lb_existefactura=$this->uf_select_factura($ls_numfacasoc);
			if ($lb_existefactura)
				{
					$lb_existefac=$this->uf_actualizar_factura($ls_numfac,'P',$aa_seguridad);
					$lb_existe_not=$this->uf_actualizar_nota($ls_numfac,'P',$aa_seguridad);
				 }
		}
	}
	  if ($lb_existe)
	    {
			$ls_cadena= "UPDATE sfc_dt_cobrocliente SET codcli=".$ls_codcli.", tipcancel='".$ls_tipcancel."', moncancel=".$ls_moncancel.", montoxcancel=".$ls_montoxcancel.",codtiend='".$ls_codtie."' WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numfac='".$ls_numfac."';";
			$ls_evento="UPDATE";
		}
	  else
	  {
        $ls_cadena= "INSERT INTO sfc_dt_cobrocliente (codemp,codcli,numcob,numfac,tipcancel,moncancel,montoxcancel,codtiend) VALUES ('".$ls_codemp."',".$ls_codcli.",'".$ls_numcob."','".$ls_numfac."','".$ls_tipcancel."',".$ls_moncancel.",".$ls_montoxcancel.",'".$ls_codtie."')";
		/*print 'detcobro';
		print $ls_cadena;*/
		$ls_evento="INSERT";
	    }

	//print '<br>'.$ls_cadena;
    //$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_detcobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		//$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_sql->message;
   	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;
			if($ls_evento=="INSERT")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Inserto el Cobro la Factura ".$ls_numfac.", para el Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el Cobro la Factura ".$ls_numfac.", para el Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		    //$this->io_sql->commit();
		}
		else
		{
			//$this->io_sql->rollback();
			if ($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			} //$lb_existe
		} //$li_numrows>0
	} //($li_numrows==false)&&($this->io_sql->message!="")
	return $lb_valido;
}

function uf_delete_detcobro($ls_numcob,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_detcobro
	// Parameters:  - $ls_numcob (numero del Cobro).
	// Descripcion: - Funcion que elimina un cobro.
	//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_detcobro($ls_numcob);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_dt_cobrocliente
							  WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'";
				//$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_sql->message;
					print $this->io_sql->message;
					}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino los detalles del Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						//$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}

				}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}

function uf_delete_nota_cobro($ls_numcob,$ls_codtie,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_nota_cobro.
	// Parameters:  - $ls_numcob( Codigo del cobro).
	//		      - $ls_codtie(Codigo de la tienda).
	//		      - $aa_seguridad(arreglo de seguridad).
	// Descripcion: - Funcion que elimina(si existe), las notas de generadas por cobros.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];

	$lb_valido = true;
	$lb_existe=$this->uf_select_cobro($ls_numcob,$ls_codtie);
	if($lb_existe){

		if($this->uf_select_nota_cobro($ls_numcob)){

			$ls_cadena= "DELETE FROM sfc_nota ".
					"WHERE codemp='".$ls_codemp."' AND nro_documento='".$ls_numcob."' " .
					"AND tipnot='CXP' AND codtiend='".$ls_codtie."';";

			$this->io_msgc="Registro Eliminado!!!";
			$ls_evento="DELETE";

			$li_numrows=$this->io_sql->execute($ls_cadena);


			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->is_msgc="Error en metodo uf_delete_nota_cobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				$this->io_sql->rollback();
				print $this->io_sql->message;
				print $this->io_sql->message;
	       	}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Elimino la nota Asociada al Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               ////////////////////////////
					//$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
					if($lb_existe)
					{
						$lb_valido=0;
						$this->io_msgc="No elimino el registro";

					}
					else
					{
						$lb_valido=false;
						$this->io_msgc="Registro No Eliminado!!!";

					}
				}

			}

		}else{
			$lb_valido = true;
		}

	}

	return $lb_valido;

}// FIN FUNCTION uf_delete_nota_cobro

function uf_actualizar_cobrostatus($ls_numcob,$ls_estcob,$ls_codtie,$aa_seguridad,$ls_obsAnul)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_actualizar_cobrostatus.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//		      - $ls_estcob(Estado del Cobro).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cobro($ls_numcob,$ls_codtie);
		if(!$lb_existe)
		{
		}
		else
		{
                        $fechaanu = date('Y/m/d');
                        $cadenaAnular = ($ls_obsAnul=="")?"":",obsanu     ='".$ls_obsAnul."', fecanu='$fechaanu', codusu = '".$_SESSION["la_logusr"]."'";
                        
			$ls_cadena= "UPDATE sfc_cobro_cliente ".
			            "SET estcob='".$ls_estcob."' $cadenaAnular ".
						" WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND codtiend='".$ls_codtie."';";
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

      	//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_cobrostatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_sql->message;
			print $this->io_sql->message;
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el status del Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ////////////////////////////
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";

				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";

				}
			}

		}
	return $lb_valido;
}

function uf_actualizar_dfacturatatus($ls_numfac,$ls_estcob,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_actualizar_dfacturatatus.
	// Parameters:- $ls_numfac(N�mero de la factura)
	//		    -$ls_estcob (Estado del detalle del cobro factura
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];

			$ls_cadena= "UPDATE sfc_dt_cobrocliente ".
			            "SET tipcancel='".$ls_estcob."' ".
						"WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_cobrostatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_sql->message;
			print $this->io_sql->message;
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el status de Cobro la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ////////////////////////////
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";

				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";

				}
			}

		}
	return $lb_valido;
}

function uf_calcular_cobrocliente_devolucion($ls_numfac,$ls_codcli,$ls_codtiend,&$li_montocobrado)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_nota_cobro
	// Parameters:  - $ls_numcob( Codigo del cobro acosiado).
	// Descripcion: - Funcion que busca un registro en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////
    $ls_codemp=$this->datoemp["codemp"];
	$ls_cadena="SELECT SUM(moncancel) as moncancel FROM sfc_dt_cobrocliente
	            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtiend."' AND numfac='".$ls_numfac."' AND codcli='".$ls_codcli."' AND estcobdev='N' " .
	            "AND numcob in (SELECT numcob from sfc_cobro_cliente where estcob<>'A' and codemp='".$ls_codemp."' AND codtiend='".$ls_codtiend."');";
 //print $ls_cadena."<br>";

	$rs_datauni=$this->io_sql->select($ls_cadena);
	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_nota_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_datauni))
		{
			$lb_valido=true;
			$li_montocobrado=$row["moncancel"];
			//print $li_montocobrado."COBRADO<br>";

		}
		else
		{
			$lb_valido=false;
			$this->io_msgc="Registro no encontrado";
		}
	}
	return $lb_valido;
}

function uf_actualizar_estcobcli_devolucion($ls_numfac,$ls_codcli,$ls_codtiend)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_nota_cobro
	// Parameters:  - $ls_numcob( Codigo del cobro acosiado).
	// Descripcion: - Funcion que busca un registro en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////
    $ls_codemp=$this->datoemp["codemp"];
	$ls_cadena="UPDATE sfc_dt_cobrocliente set estcobdev='A'
	            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtiend."' AND numfac='".$ls_numfac."' AND codcli='".$ls_codcli."' ;";
 //print $ls_cadena."<br>";

	$rs_datauni=$this->io_sql->execute($ls_cadena);
	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_actualizar_estcobcli_devolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_datauni))
		{
			$lb_valido=true;

			//print "ACT COBRO<br>";

		}
		else
		{
			$lb_valido=false;
			$this->io_msgc="Registro no encontrado";
		}
	}
	return $lb_valido;
}

}/*FIN DE LA CLASE */
?>
