<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cotizacion
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_cotizacion.
 // Fecha:       - 16/02/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_cotizacion
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_cotizacion()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /** Se toma la funcion de convertir cadena a caracteres **/
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_mensajes.php");

	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$this->io_msg=new class_mensajes();
	$io_datastore=new class_datastore();
}


function uf_select_cotizacion($ls_numcot)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cotizacion
		// Parameters:  - $ls_numcot( Codigo de la cotizacion).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_sql="SELECT * FROM sfc_cotizacion
		            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
	//	print $ls_sql;
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cotizacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
function uf_imprimir_cotizacion($ls_numcot,&$ls_sql)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cotizacion
		// Parameters:  - $ls_numcot( Codigo de la cotizacion).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_sql=" SELECT caj.nomusu,um.denunimed,a.denart,pro.codtiend,cli.codcli,cli.cedcli,cli.razcli,cli.dircli, " .
  	    		"cli.telcli,cli.celcli,cot.numcot,cot.codusu, cot.feccot,cot.obscot,cot.monto,dcot.codart, " .
  	    		"dcot.cancot,dcot.precot,dcot.impcot,dcot.codtiend,tie.dentie,tie.riftie, tie.dirtie,tie.teltie " .
  	    		"FROM sfc_producto pro,sfc_cliente cli,sfc_cotizacion cot,sfc_detcotizacion dcot,sfc_tienda tie, " .
  	    		"sfc_cajero caj,sim_unidadmedida um,sim_articulo a WHERE " .
  	    		"pro.codemp=cli.codemp AND pro.codemp=cot.codemp AND pro.codtiend=cot.codtiend AND " .
  	    		"pro.codart=dcot.codart AND pro.codemp=dcot.codemp AND pro.codtiend=dcot.codtiend AND " .
  	    		"pro.codtiend=tie.codtiend AND pro.codemp=tie.codemp AND pro.codemp=caj.codemp AND " .
  	    		"pro.codtiend=caj.codtiend AND pro.codart=a.codart AND pro.codemp=a.codemp AND " .
  	    		"cli.codcli=cot.codcli AND cli.codemp=cot.codemp AND cli.codemp=dcot.codemp AND " .
  	    		"cli.codemp=tie.codemp AND cli.codemp=caj.codemp AND cli.codemp=a.codemp AND " .
  	    		"cot.numcot=dcot.numcot AND cot.codtiend=dcot.codtiend AND cot.codemp=dcot.codemp AND " .
  	    		"cot.codtiend=tie.codtiend AND cot.codemp=tie.codemp AND cot.codtiend=caj.codtiend AND " .
  	    		"cot.codemp=caj.codemp AND cot.codemp=a.codemp AND dcot.codtiend=tie.codtiend AND " .
  	    		"dcot.codemp=tie.codemp AND dcot.codtiend=caj.codtiend AND dcot.codemp=caj.codemp AND " .
  	    		"dcot.codart=a.codart AND dcot.codemp=a.codemp AND tie.codtiend=caj.codtiend AND " .
  	    		"tie.codemp=caj.codemp AND tie.codemp=a.codemp AND caj.codemp=a.codemp AND cot.codusu=caj.codusu " .
  	    		"AND cot.numcot ilike '".$ls_numcot."' AND um.codunimed=a.codunimed AND cot.codtiend ilike '".$ls_codtie."' ORDER BY cot.codcli ASC;";
				
				
		/*$ls_sql=" SELECT caj.nomusu,um.denunimed,a.denart,pro.codtiend,cli.codcli,cli.cedcli,cli.razcli,cli.dircli, " .
  	    		"cli.telcli,cli.celcli,cot.numcot,cot.codusu, cot.feccot,cot.obscot,cot.monto,dcot.codart, " .
  	    		"dcot.cancot,dcot.precot,dcot.impcot,dcot.codtiend,tie.dentie,tie.riftie, tie.dirtie,tie.teltie " .
  	    		"FROM sfc_producto pro,sfc_cliente cli,sfc_cotizacion cot,sfc_detcotizacion dcot,sfc_tienda tie, " .
  	    		"sfc_cajero caj,sim_unidadmedida um,sim_articulo a WHERE " .
  	    		"cot.numcot = dcot.numcot AND cot.codemp = dcot.codemp " .
                "AND cot.codusu = caj.codusu AND cot.codtiend = caj.codtiend AND cot.codemp = caj.codemp " .
                "AND cli.codcli = cot.codcli AND cli.codemp = cot.codemp " .
                "AND pro.codart = dcot.codart AND pro.codemp = dcot.codemp AND pro.codtiend = dcot.codtiend " .
                "AND cot.codtiend=tie.codtiend AND cot.codemp=tie.codemp " .
                "AND dcot.codart = a.codart AND dcot.codemp = a.codemp " .
                "AND um.codunimed = a.codunimed  " .
  	    		"AND cot.numcot ilike '".$ls_numcot."' AND um.codunimed=a.codunimed AND cot.codtiend ilike '".$ls_codtie."' ORDER BY cot.codcli ASC;";*/
	//	print $ls_sql;
	
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cotizacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
function uf_guardar_cotizacion($ls_codcli,$ls_numcot,$ls_codusu,$ls_feccot,$ls_obscot,$ld_monto,$ls_estcot,$la_detalles,$li_filasconcepto,$sub_total,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_cotizacion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numcot.
		//			    - $ls_codusu( c�digo del usuario).
		//				- $ls_feccot.
		//				- $ls_obscot.
		//              - $ld_monto( monto a pagar).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_mensajes.php");
		$io_msg=new class_mensajes();
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$m=time() - 1800;		
		$hora=date("h:i:s",$m);
		$lb_existe=$this->uf_select_cotizacion($ls_numcot);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
		$ls_feccot=$this->io_funcion->uf_convertirdatetobd_hora($ls_feccot,$hora);
		if(!$lb_existe)
		{
            $ls_sql= "INSERT INTO sfc_cotizacion (codemp,codcli,numcot,codusu,feccot,obscot,monto,estcot,codtiend) VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numcot."','".$ls_codusu."','".$ls_feccot."','".$ls_obscot."',".$ld_monto.",'".$ls_estcot."','".$ls_codtie."')";
			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_sql= "UPDATE sfc_cotizacion
			             SET codcli='".$ls_codcli."', codusu='".$ls_codusu."', feccot='".$ls_feccot."', estcot='".$ls_estcot."', obscot='".$ls_obscot."', monto='".$ld_monto."'
						 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
       	$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_sql);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_cotizacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{		
			if($li_numrows>0)
			{
				$lb_valido=$this->uf_update_detallescotizaciones($ls_numcot,$la_detalles,$li_filasconcepto,$aa_seguridad);
				if ($lb_valido)
				{
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				$this->io_sql->commit();	
				$io_msg->message (utf8_decode("Cotización Nro. ".$ls_numcot." Se ha guardado exitosamente!!!"));
				$lb_valido_repcot=$this->uf_imprimir_cotizacion($ls_numcot,&$ls_sql);
				if ($lb_valido_repcot==true)
				{
				  $sub_total="prueba";
				?>			
				 <script language="JavaScript">
					var ls_sql="<?php print $ls_sql; ?>";
					var sub_total="<?php print $sub_total; ?>";
					pagina="reportes/sigesp_sfc_rep_cotizacion.php?sql="+ls_sql+"&total="+sub_total;
					popupWin(pagina,"catalogo",580,700);
				 </script>			
				<?php
				}
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
unset($_POST["lo q sea"]);
print("<script language=JavaScript>");
print(" location.href='sigesp_sfc_d_cotizacion.php';");
print("</script>");
	}

function uf_update_cotizacionstatus($ls_numcot,$ls_estcot,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_cotizacion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numcot.
		//			    - $ls_codusu( c�digo del usuario).
		//				- $ls_feccot.
		//				- $ls_obscot.
		//              - $ld_monto( monto a pagar).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$lb_existe=$this->uf_select_cotizacion($ls_numcot);
        if ($ls_numcot=='0000000000000000000000000')
		{
			$lb_existe=$this->uf_select_cotizacion($ls_numcot);
			if (!$lb_existe)
			{
				$ls_sql= "INSERT INTO sfc_cotizacion (codemp,codcli,numcot,codusu,feccot,obscot,monto,estcot,codtiend) VALUES ('0001','1','0000000000000000000000000','SIGESP                        ','2008-01-01 00:00:00','NO APLICA',0,'E','".$ls_codtie."')";
				$li_numrows=$this->io_sql->execute($ls_sql);
				  if(($li_numrows==false)&&($this->io_sql->message!=""))
				 {
					$lb_valido=false;			
					$this->io_sql->rollback();			
				 }
				 else
				 {
					if($li_numrows>0)
					{
						$lb_valido=true;
					}		 
				 }	
				$ls_estcot='E';
			 }
		}

		$ls_sql= "UPDATE sfc_cotizacion ".
					 "SET estcot='".$ls_estcot."' ".
					 "WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
		$this->io_msgc="Registro Actualizado!!!";
		$ls_evento="UPDATE";
		$li_numrows=$this->io_sql->execute($ls_sql);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
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
		else
		{		
			$lb_valido=true;
			if($ls_evento=="INSERT")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
}

function uf_update_cotizacionstatusfactura($ls_numcot,$ls_estcot,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_cotizacion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numcot.
		//			    - $ls_codusu( c�digo del usuario).
		//				- $ls_feccot.
		//				- $ls_obscot.
		//              - $ld_monto( monto a pagar).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$lb_existe=$this->uf_select_cotizacion($ls_numcot);

        if ($ls_numcot=='0000000000000000000000000') $ls_estcot='E';

			$ls_sql= "UPDATE sfc_cotizacion ".
			             "SET estcot='".$ls_estcot."' ".
						 "WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
			//	print $ls_sql;
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";

       // print $ls_sql;
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_sql);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_cotizacionstatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Insertó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp." Tienda ".$ls_codtie;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp." Tienda ".$ls_codtie;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
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

function uf_delete_cotizacion($ls_numcot,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_cotizacion
	// Parameters:  - $ls_numcot.
	// Descripcion: - Funcion que elimina una cotizacion.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$lb_existe=$this->uf_select_cotizacion($ls_numcot);

	if($lb_existe)
	{
	    	$ls_sql= " DELETE FROM sfc_cotizacion WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."'";

			$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_sql);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				//print "LO QUE SEA";
				$this->io_msgc="Error en metodo uf_delete_cotizacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				//print $ls_sql;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
					$this->io_msgc="Registro Eliminado!!!";
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
/******************************************************************************************************************************/
/******************  COTIZACION DETALLES **************************************************************************************/
/******************************************************************************************************************************/
function uf_select_detcotizacion($ls_numcot)
{
    //////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_detcotizacion
	// Parameters:  - $ls_numcot( Codigo de la cotizacion).
	// Descripcion: - Funcion que busca un registro en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////

    $ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$ls_sql="SELECT * FROM sfc_detcotizacion
	            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
	$rs_datauni=$this->io_sql->select($ls_sql);

	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_detcotizacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

function uf_select_detproducto($ls_numcot,$ls_codpro)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detproducto
		// Parameters:  - $ls_codpro( Codigo del producto).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_sql="SELECT * FROM sfc_detcotizacion
		            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."' AND codart='".$ls_codpro."';";
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

function uf_guardar_detcotizacion($ls_numcot,$ls_codpro,$ls_cancot,$ls_precot,$ls_impcot,$ls_codalm,$cod_pro,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_detcotizacion
	// Parameters:  - $ls_codcli( Codigo del cliente).
	//			    - $ls_numcot.
	//			    - $ls_codpro( c�digo del producto).
	//				- $ls_cancot (cantidad de producto).
	//				- $ls_precot(precio del producto).
	//              - $ld_impcot( impuesto al producto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	//$lb_existe_cot=$this->uf_select_detcotizacion($ls_numcot);
	$lb_existe=$this->uf_select_detproducto($ls_numcot,$ls_codpro);
	$ls_cancot=$this->funsob->uf_convertir_cadenanumero($ls_cancot); /* convierte cadena en numero */
	$ls_precot=$this->funsob->uf_convertir_cadenanumero($ls_precot); /* convierte cadena en numero */
	$ls_impcot=$this->funsob->uf_convertir_cadenanumero($ls_impcot); /* convierte cadena en numero */



	  if ($lb_existe)
	    {
			$ls_sql= "UPDATE sfc_detcotizacion SET cancot=".$ls_cancot.", precot=".$ls_precot.
			            ", impcot=".$ls_impcot.",cod_pro='".$cod_pro."', codalm='".$ls_codalm."' WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codart='".$ls_codpro."' AND numcot='".$ls_numcot."';";
		//print $ls_sql;
		$this->io_msgc="Registro Actualizado!!!";
		$ls_evento="UPDATE";
		}
	  else
		{
            $ls_sql= "INSERT INTO sfc_detcotizacion (codemp,numcot,codart,cancot,precot,impcot,codalm,codtiend,cod_pro) VALUES ('".
			            $ls_codemp."','".$ls_numcot."','".$ls_codpro."',".$ls_cancot.",".$ls_precot.
						",".$ls_impcot.",'".$ls_codalm."','".$ls_codtie."','".$cod_pro."')";
 		//print $ls_sql;
		$this->io_msgc="Registro Incluido!!!";
		$ls_evento="INSERT";
	    }

   // print $ls_sql;
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);


	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_detcotizacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_msgc;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$ls_descripcion ="Insertó el Producto ".$ls_codpro." de la Cotizacion ".$ls_numcot." Asociada a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Producto ".$ls_codpro." de la Cotizacion ".$ls_numcot." Asociada a la Empresa ".$ls_codemp;
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
			if ($lb_existe)
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


function uf_delete_detcotizacion($ls_numcot,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_cotizacion
	// Parameters:  - $ls_numcot.
	// Descripcion: - Funcion que elimina una cotizacion.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$lb_existe=$this->uf_select_detcotizacion($ls_numcot);

	if($lb_existe)
	{
	    	$ls_sql= " DELETE FROM sfc_detcotizacion
						  WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."'";

			$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_sql);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();

				$this->io_msgc="Error en metodo uf_delete_detcotizacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				//print $ls_sql;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimin� los detalles de la Cotizacion ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
					$this->io_msgc="Registro Eliminado!!!";
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


/*****************************************************************************************************/
/*********************** SELECT DETALLES COTIZACION **************************************************/
/*****************************************************************************************************/
function uf_select_detallescot ($ls_numcot,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallescot                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:          25/03/2006                                                         */
	 /*	Autor:          GERARDO CORDERO		                                                */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_sql="SELECT  *
				 FROM sfc_detcotizacion
				 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."'AND numcot='".$ls_numcot."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select detallescot".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}
		}
		return $lb_valido;
	}
  /*****************************************************************************************/
  /*********************** GUARDAR DETALLES COTIZACION **************************************/
  /*****************************************************************************************/
	function uf_guardar_detallescot($ls_numcot,$ls_codpro,$ls_cancot,$ls_precot,$ls_impcot,$ls_codalm,$ls_desalm,$cod_pro,$aa_seguridad)
    {
	/***************************************************************************************/
	/*	Function:	    uf_change_estatus_asi                                              */
	/* Access:			public                                                             */
	/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
	/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
	/*  Fecha:          25/03/2006                                                         */
	/*	Autor:          GERARDO CORDERO		                                               */
	/***************************************************************************************/
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_cancot=$this->funsob->uf_convertir_cadenanumero($ls_cancot); /* convierte cadena en numero */
	$ls_precot=$this->funsob->uf_convertir_cadenanumero($ls_precot); /* convierte cadena en numero */
	$ls_impcot=$this->funsob->uf_convertir_cadenanumero($ls_impcot); /* convierte cadena en numero */
	$ls_sql= "INSERT INTO sfc_detcotizacion (codemp,numcot,codart,cancot,precot,impcot,codalm,codtiend,cod_pro) VALUES ('".$ls_codemp."','".$ls_numcot."','".$ls_codpro."',".$ls_cancot.",".$ls_precot.",".$ls_impcot.",'".$ls_codalm."','".$ls_codtie."','".$cod_pro."')";	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_cotizacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_msgc;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($li_row>0)
		{
			$ls_evento="INSERT";
			$ls_descripcion ="Inserto el producto ".$ls_codpro.", Detalle de la Cotización ".$ls_numcot." Asociado a la Empresa ".$ls_codemp;
			   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			$lb_valido=true;
		}
		else
		{
			$this->io_sql->rollback();
		}
	}
	return $lb_valido;
}
  /*****************************************************************************************/
  /*********************** BORRAR DETALLES COTIZACION **************************************/
  /*****************************************************************************************/
function uf_delete_detallescot($ls_numcot,$ls_codpro,$aa_seguridad)
{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sql= "DELETE FROM sfc_detcotizacion WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."' AND codart='".$ls_codpro."';";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();

			//$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

			print"Error en metodo eliminar_detallescot".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			//*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� los Dettales de la Cotizacion ".$ls_numcot.", Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			//********************************************/
			$lb_valido=true;
			//$this->io_sql->commit();
		}
		return $lb_valido;

	}
  /*****************************************************************************************/
  /*********************** UPDATE DETALLES COTIZACION **************************************/
  /*****************************************************************************************/
	function uf_update_detallescot($ls_numcot,$ls_codpro,$ls_cancot,$ls_precot,$ls_impcot,$ls_codalm,$ls_desalm,$cod_pro,$aa_seguridad)
	 {

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_cancot=$this->funsob->uf_convertir_cadenanumero($ls_cancot); /* convierte cadena en numero */
		$ls_precot=$this->funsob->uf_convertir_cadenanumero($ls_precot); /* convierte cadena en numero */
		$ls_impcot=$this->funsob->uf_convertir_cadenanumero($ls_impcot); /* convierte cadena en numero */

		$ls_sql="UPDATE sfc_detcotizacion
				SET  cancot=".$ls_cancot.",cod_pro='".$cod_pro."', precot=".$ls_precot.", impcot=".$ls_impcot.", codalm='".$ls_codalm."'
				WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codart='".$ls_codpro."' AND numcot='".$ls_numcot."';";


		$this->io_sql->begin_transaction();
	//	print($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
		/***********************************************************************************************************/
		$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_detallescot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		/************************************************************************************************************/


			/*print "Error en metodo uf_update_detallescot ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	*/
		}
		else
		{
			if($li_row>0)
			{
				//*************    SEGURIDAD    **************
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Cotizacion ".$ls_numcot.", Detalle de la Cotizacion ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				//**********************************************
				//$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	  }
  /*****************************************************************************************/
  /*********************** UPDATE ARREGLO DE DETALLES COTIZACION **************************************/
  /*****************************************************************************************/
	function uf_update_detallescotizaciones($ls_numcot,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		require_once("class_folder/sigesp_sfc_c_secuencia.php");
		$io_function=new class_funciones();
		$lb_valido=false;
		$lb_update=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$lb_valido=$this->uf_select_detallescot($ls_numcot,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numcot"][$li_j]==$ls_numcot && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i])
				{
				  if($la_detallesviejos["cancot"][$li_j] != $aa_detallesnuevos["canpro"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}

			}
			if (!$lb_existe)
			 {
			   $ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
			   $ls_cancot=$aa_detallesnuevos["canpro"][$li_i];
			   $ls_precot=$aa_detallesnuevos["preuni"][$li_i];
			   $ls_impcot=$aa_detallesnuevos["impcot"][$li_i];
			   $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
			   $ls_desalm=$aa_detallesnuevos["desalm"][$li_i];
			   $cod_pro=$aa_detallesnuevos["codprov"][$li_i];
			   if ($ls_codalm=='' or strlen($ls_codalm)<10)
			   {
			   if ($ls_codalm=='')
			   {
			   $ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codtie,10);
			   }else{
			   $ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codalm,10);
			   }
			   }
			   $lb_valido=$this->uf_guardar_detallescot($ls_numcot,$ls_codpro,$ls_cancot,$ls_precot,$ls_impcot,$ls_codalm,$ls_desalm,$cod_pro,$aa_seguridad);
			 }
			if ($lb_update)
			 {
			   $ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
			   $ls_cancot=$aa_detallesnuevos["canpro"][$li_i];
			   $ls_precot=$aa_detallesnuevos["preuni"][$li_i];
			   $ls_impcot=$aa_detallesnuevos["impcot"][$li_i];
			   $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
			   $ls_desalm=$aa_detallesnuevos["desalm"][$li_i];
			   $cod_pro=$aa_detallesnuevos["codprov"][$li_i];
			   if ($ls_codalm=='' or strlen($ls_codalm)<10)
			   {
			   if ($ls_codalm=='')
			   {
			   $ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codtie,10);
			   }else{
			   $ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codalm,10);
			   }
			   }
			   $lb_valido=$this->uf_update_detallescot($ls_numcot,$ls_codpro,$ls_cancot,$ls_precot,$ls_impcot,$ls_codalm,$desalm,$cod_pro,$aa_seguridad);
			 }
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]=$ls_codemp && $la_detallesviejos["numcot"][$li_j]=$ls_numcot && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_detallescot($ls_numcot,$la_detallesviejos["codart"][$li_j],$aa_seguridad);			
			}
		}
	return $lb_valido;
	}
}/*FIN DE LA CLASE */
?>
