<?php
class sigesp_soc_c_analisis_cotizacion
{
	function sigesp_soc_c_analisis_cotizacion()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function:  sigesp_soc_c_analisis_cotizacion
		//	Description:  Constructor de la Clase
		//////////////////////////////////////////////////////////////////////////////
		global $ls_empresa;
		global $io_include;
		global $io_conexion;	
		global $io_sql;
		global $io_mensajes;
		global  $io_funciones;
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");

		$io_include          = new sigesp_include();
		$io_conexion		 = $io_include->uf_conectar();		
		$this->io_sql        = new class_sql($io_conexion);				
		$this->io_mensajes   = new class_mensajes();	
		$this->io_funciones  = new class_funciones();	
		$this->io_seguridad  = new sigesp_c_seguridad();
		$this->io_dscuentas  = new class_datastore();
		$this->io_dscargos   = new class_datastore();
		$this->ls_codemp     = $_SESSION["la_empresa"]["codemp"];
		$this->io_keygen     = new sigesp_c_generar_consecutivo(); 	
	}//Fin del constructor de la clase	


//---------------------------------------------------------------------------------------------------------------------------------------
function uf_insert_update($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_update
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  inserta o actualiza el analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 09/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_catalogo=$_POST["catalogo"];
		$lb_valido=false;
		if($ls_catalogo=="T")// En caso de que el analisis venga de un catalogo, se hace update 
		{
			if($this->uf_estado_analisis() != 1)//Si no ha sido aprobada
			{
				$this->io_sql->begin_transaction();
				$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("R",$aa_seguridad);
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_cotizacion(0,$aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=	$this->uf_update_analisis($aa_seguridad);	
				}				
				if($lb_valido)
				{
					$ls_numanacot=$_POST["txtnumero"];
					$lb_valido=$this->uf_update_cotizacion_analisis($aa_seguridad,$ls_numanacot);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_items_analisis_cotizacion($aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("P",$aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_cotizacion(1,$aa_seguridad);
				}

				if($lb_valido)
				{
					$this->io_sql->commit();
					$this->io_mensajes->message("El Análisis de Cotizaciones fue actualizado");
				}
				else
				{
					$this->io_sql->rollback();
					$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser actualizado");
				}
			}
			else
			{
				$this->io_mensajes->message("El Análisis de Cotización no puede ser modificado ya que fue Aprobado");
			}
		}
		else //En caso de que sea un nuevo analisis, se inserta
		{
			$this->io_sql->begin_transaction();
			$ls_numanacot = $_POST["txtnumero"];
			$ls_numsolaux = $ls_numanacot;
			$lb_valido    =	$this->uf_insert_analisis($aa_seguridad,$ls_numanacot);
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cotizacion_analisis($aa_seguridad,$ls_numanacot);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_items_analisis_cotizacion($aa_seguridad,$ls_numanacot);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("P",$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_cotizacion(1,$aa_seguridad);
			}
			
			if($lb_valido)
			{
				if($ls_numsolaux!=$ls_numanacot)
				{
					$this->io_mensajes->message("Se Asigno el Numero al Análisis de Cotización: ".$ls_numanacot);
				}
				$this->io_sql->commit();
				$this->io_mensajes->message("El Análisis de Cotizaciones fue registrado");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser registrado");
			}
		}
		//return true;
		return $lb_valido;
	}// fin de uf_insert_update
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_update_estatus_solicitud_cotizacion($ai_estatus,$aa_seguridad) 
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Funcion: uf_update_estatus_solicitud_cotizacion
		//		   Acceso: public
		//     Parametros: $ai_estatus-->R libera, P asocia
		//		  return : true o false
		//    Description: Metodo que libera o asocia las solicitudes de cotizaciones a los analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 23/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numsolcot=$this->uf_select_solicitud_cotizacion();
		$ls_sql="UPDATE soc_sol_cotizacion 
				SET estcot='$ai_estatus'
				WHERE codemp='$this->ls_codemp'
				AND numsolcot='$ls_numsolcot'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_estatus_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
		  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		  $ls_evento="UPDATE";
		  $ls_descripcion ="Actualizo es estatus del analisis de cotizacion ".$ls_numsolcot."  al valor $ai_estatus, Asociado a la empresa $this->ls_codemp";
		  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}	
		return $lb_valido;				
	}//fin de uf_update_estatus_solicitud_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_update_estatus_cotizacion($ai_estatus,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Funcion: uf_update_estatus_cotizacion
		//		   Acceso: public
		//     Parametros: $ai_estatus-->0 libera, 1 asocia
		//		  return : true o false
		//    Description: Metodo que libera o asocia las cotizaciones a los analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_cotizaciones=$this->uf_select_cotizacion_analisis();
		$li_totalcotizaciones=count($la_cotizaciones);
		for($li_i=1;(($li_i<=$li_totalcotizaciones) &&($lb_valido));$li_i++)
		{
			$ls_codpro=$la_cotizaciones[$li_i]["cod_pro"];
			$ls_numcot=$la_cotizaciones[$li_i]["numcot"];
			$ls_sql="UPDATE soc_cotizacion 
						SET estcot=$ai_estatus
						WHERE codemp='$this->ls_codemp'
						AND cod_pro='$ls_codpro'
						AND numcot='$ls_numcot'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->f_update_estatus_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo es estatus de la cotizacion ".$ls_numcot."  al valor $ai_estatus, Asociado a la empresa $this->ls_codemp";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}	
		return $lb_valido;				
	}//fin de uf_update_estatus_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_update_analisis($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_analisis
		//		   Access: public
		// 		   return: True si se actualizo correctamente
		//    Description: Metodo que actualiza la cabecera del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_fecanacot=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecha"]);
		$ls_estanacot=0;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		$ls_obsanacot=$_POST["txtobservacion"];
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numsolcot=$_POST["txtnumsol1"];
		$ls_sql="UPDATE soc_analisicotizacion 
				 SET fecanacot='$ls_fecanacot',codusu='$ls_codusu',estana=$ls_estanacot,obsana='$ls_obsanacot',tipsolcot='$ls_tipsolcot',numsolcot='$ls_numsolcot'
				 WHERE 
				 codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Análisis de Cotizacion ".$ls_numanacot.", asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}//fin de funcion uf_update_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_update_cotizacion_analisis($aa_seguridad,$as_numanacot)
{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cotizacion_analisis
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  actualiza la asociacion de las cotizaciones a un  analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_delete_cotizacion_analisis($aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_cotizacion_analisis($aa_seguridad,$as_numanacot);
		}
		return $lb_valido;		
	}//uf_update_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_update_items_analisis_cotizacion($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  actualiza el detalle del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 11/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numanacot=$_POST["txtnumero"];
		$li_totalitems=$_POST["totalitems"];
		for($li_i=1;($li_i<=$li_totalitems)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodproselec".$li_i];
			if($ls_codpro!="")//Se chequea si se le asigno un proveedor a este item, con la finalidad de permitir que el usuario tenga la libertad de no incluir
								// algunos items dentro del analisis de cotizacion (actualmente esta deshabilitada esta opcion  en una funcion javascript en el guardar)
			{
				$ls_coditem=$_POST["txtcoditem".$li_i];
				$ls_numcot=$_POST["txtnumcotsele".$li_i];			
				$ls_obsanacot=$_POST["txtobservacion".$li_i];
				
				if($ls_tipsolcot=="B")//En caso de que sean bienes actualizo el detalle en la tabla soc_dtac_bienes
				{
					$ls_sql="UPDATE soc_dtac_bienes
							SET numcot='$ls_numcot', cod_pro='$ls_codpro', estsel=0, ordpro=$li_i, obsanacot='$ls_obsanacot'
							WHERE
							codemp='$this->ls_codemp' AND numanacot='$ls_numanacot' AND codart='$ls_coditem'"; 	
					$ls_item="Bien";
				}
				elseif($ls_tipsolcot=="S")//En caso de que sean servicios actualizo el detalle en la tabla soc_dtac_servicios
				{
					$ls_sql="UPDATE soc_dtac_servicios
							SET numcot='$ls_numcot', cod_pro='$ls_codpro', estsel=0, ordpro=$li_i, obsanacot='$ls_obsanacot'
							WHERE
							codemp='$this->ls_codemp' AND numanacot='$ls_numanacot' AND codser='$ls_coditem'"; 	
					$ls_item="Servicio";
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Actualizo el $ls_item ".$ls_coditem." al Analisis de Cotizacion ".$ls_numanacot.
										 " Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}//fin funcion uf_update_items_analisis_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_insert_analisis($aa_seguridad,&$as_numanacot)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_analisis
		//		   Access: public
		//		return	:  True si se inserto correctamente
		//   Description: Metodo que guarda la cabecera del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_analisicotizacion','numanacot','SOCANA',15,"","","",$as_numanacot);
		$lb_valido=true;
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_fecanacot=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecha"]);
		$ls_estanacot=0;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		$ls_obsanacot=$_POST["txtobservacion"];
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numsolcot=$_POST["txtnumsol1"];
		$ls_sql="INSERT INTO soc_analisicotizacion 
				 (codemp,numanacot,fecanacot,codusu,estana,obsana,tipsolcot,numsolcot)
				 VALUES
				 ('$this->ls_codemp','$as_numanacot','$ls_fecanacot','$ls_codusu',$ls_estanacot,'$ls_obsanacot','$ls_tipsolcot','$ls_numsolcot')";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
			 	$this->uf_insert_analisis($aa_seguridad,$as_numanacot);
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{						
		  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		  $ls_evento="INSERT";
		  $ls_descripcion ="Insertó el Análisis de Cotizacion $as_numanacot, asociado a la empresa ".$this->ls_codemp;
		  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		  /////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}//fin de funcion uf_insert_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_insert_cotizacion_analisis($aa_seguridad,$as_numanacot)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cotizacion_analisis
		//		   Access: public
		//		return	: True si se inserto correctamente
		//   Description: Metodo que guarda las cotizaciones asociadas a un analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		for($li_i=1;(($li_i<$li_totcotizaciones)&& ($lb_valido));$li_i++)
		{
			$ls_numcot=$_POST["txtnumcot".$li_i];
			$ls_codpro=$_POST["txtcodpro".$li_i];
			$ls_sql="INSERT INTO soc_cotxanalisis
					 (codemp,numanacot,numcot,cod_pro)
					 VALUES
					 ('$this->ls_codemp','$as_numanacot','$ls_numcot','$ls_codpro')";
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_cotizacion_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 

			}
			else
			{						
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Se asocio la Cotizacion $ls_numcot, al analisis de cotización $as_numanacot, para la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
	}//fin de funcion uf_insert_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_insert_items_analisis_cotizacion($aa_seguridad,$as_numanacot)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//    Description: Metodo que  inserta el detalle del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 15/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido     = true; 
		$ls_tipsolcot  = $_POST["txttipsolcot1"];
		$li_totalitems = $_POST["totalitems"];
		for($li_i=1;($li_i<=$li_totalitems)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodproselec".$li_i];
			if($ls_codpro!="")//Se chequea si se le asigno un proveedor a este item, con la finalidad de permitir que el usuario tenga la libertad de no incluir
								// algunos items dentro del analisis de cotizacion (actualmente esta deshabilitada esta opcion  en una funcion javascript en el guardar)
			{
				$ls_coditem   = $_POST["txtcoditem".$li_i];
				$ls_numcot    = $_POST["txtnumcotsele".$li_i];			
				$ls_obsanacot = $_POST["txtobservacion".$li_i];				
				if($ls_tipsolcot=="B")//En caso de que sean bienes guardo el detalle en la tabla soc_dtac_bienes
				{
					$ls_item="Bien";
					$ls_sql="INSERT INTO soc_dtac_bienes (codemp, numanacot, codart, numcot, cod_pro, estsel, ordpro, obsanacot) VALUES ('".$this->ls_codemp."','".$as_numanacot."','".$ls_coditem."','".$ls_numcot."','".$ls_codpro."',0,".$li_i.",'".$ls_obsanacot."')"; 	
				}
				elseif($ls_tipsolcot=="S")//En caso de que sean servicios guardo el detalle en la tabla soc_dtac_servicios
				{
					$ls_sql="INSERT INTO soc_dtac_servicios
							(codemp, numanacot, codser, numcot, cod_pro, estsel, ordpro, obsanacot)
							VALUES
							('$this->ls_codemp','$as_numanacot','$ls_coditem','$ls_numcot','$ls_codpro',0,$li_i,'$ls_obsanacot')";
					$ls_item="Servicio";
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
				 	$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó el $ls_item $ls_coditem al Analisis de Cotizacion ".$as_numanacot.
										 ", asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}//fin funcion uf_insert_items_analisis_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_delete($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  elimina el analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//if(true)
		if($this->uf_estado_analisis() != 1)//Si no ha sido aprobada
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("R",$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_cotizacion(0,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_items_analisis_cotizacion($aa_seguridad);
			}			
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_cotizacion_analisis($aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=	$this->uf_delete_analisis($aa_seguridad);				
			}
						
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_mensajes->message("El Análisis de Cotizaciones fue eliminado");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser eliminado");
			}
		}
		else
		{
			$this->io_mensajes->message("El Análisis de Cotización no puede ser eliminado ya que fue Aprobado");
			$lb_valido=false;
		}		
		return $lb_valido;
	}// fin de uf_delete
//----------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_delete_items_analisis_cotizacion($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  elimina el detalle del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 015/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numanacot=$_POST["txtnumero"];
		if($ls_tipsolcot=="B")//En caso de que sean bienes guardo el detalle en la tabla soc_dtac_bienes
		{
			$ls_sql="DELETE FROM soc_dtac_bienes
					WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'"; 	
			$ls_item="Bienes";
		}
		elseif($ls_tipsolcot=="S")//En caso de que sean servicios guardo el detalle en la tabla soc_dtac_servicios
		{
			$ls_sql="DELETE FROM soc_dtac_servicios
					WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
			$ls_item="Servicios";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino  $ls_item asociados al Analisis de Cotizacion $ls_numanacot de la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}			
		return $lb_valido;
	}//fin funcion uf_delete_items_analisis_cotizacion
//----------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_delete_cotizacion_analisis($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cotizacion_analisis
		//		   Access: public
		//		return	:  True si se elimino correctamente
		//   Description: Metodo que elimina las cotizaciones asociadas a un analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];		
		$ls_sql="DELETE FROM soc_cotxanalisis
				 WHERE
				 codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_cotizacion_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Se elimino la asociacion de todas las cotizaciones asociadas al analisis $ls_numanacot, para la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}	
		return $lb_valido;
	}//fin de funcion uf_delete_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_delete_analisis($aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_analisis
		//		   Access: public
		//		return	:  True si se elimino correctamente
		//   Description: Metodo que elimina la cabecera del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];		
		$ls_sql="DELETE FROM soc_analisicotizacion 
				 WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino el Análisis de Cotizacion ".$ls_numanacot.", asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}//fin de funcion uf_delete_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_select_cotizacion($as_numcot,$as_codpro,$as_numsolcot,&$la_cotizacion,&$la_dt_cotizacion)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_numsol--->Numero de Solicitud de Cotizacion
		//		return	:		Arreglo con datos de la cotizacion, arreglo con los bienes/servicios 
		//	  Description: Metodo que  imprime la informacion de una cotizacion en particular
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 28/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cotizacion=array();
		$la_dt_cotizacion=array();
		$lb_valido=false;				
		$ls_sql= "SELECT c.feccot, c.obscot, c.monsubtot, c.monimpcot, c.montotcot, c.diaentcom, c.forpagcom, c.poriva, s.tipsolcot 
					FROM soc_cotizacion c, soc_sol_cotizacion s  
					WHERE c.codemp='$this->ls_codemp' 
					  AND c.numsolcot='$as_numsolcot' 
					  AND c.numcot='$as_numcot' 
					  AND c.cod_pro='$as_codpro' 
					  AND c.codemp=s.codemp 
					  AND c.numsolcot=s.numsolcot";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$la_cotizacion=$row;									
				$lb_valido=true;			
			}			
		}
		if($lb_valido)
		{
			$this->uf_select_items($as_numcot,$as_codpro,$row["tipsolcot"],$la_dt_cotizacion);
		}	
	}//fin de uf_select_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_select_items($as_numcot,$as_codpro,$as_tipsolcot,&$aa_items)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_tipsolcot--->Si la cotizacion es de bienes o servicios
		//		return	:		arreglo con los bienes/servicios 
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;				
		if($as_tipsolcot=='B')//Si la solicitud es de Bienes
		{
			$ls_sql= "SELECT d.codart as codigo, d.canart as cantidad, d.preuniart as preciouni, d.moniva as iva, d.monsubart as subtotal, d.montotart
						     as total, a.denart as denominacion
						FROM soc_dtcot_bienes d, siv_articulo a
					   WHERE d.codemp='$this->ls_codemp' AND d.numcot='$as_numcot' AND  d.cod_pro='$as_codpro'
						AND d.codemp=a.codemp AND  a.codart=d.codart  
						ORDER BY d.orden";					
		}
		elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
		{
			
			$ls_sql= "SELECT d.codser as codigo, d.canser as cantidad, d.monuniser as preciouni, d.moniva as iva, d.monsubser as subtotal,
						     d.montotser as total, a.denser as denominacion
						FROM soc_dtcot_servicio d, soc_servicios a 
					   WHERE d.codemp='$this->ls_codemp' 
					     AND d.numcot='$as_numcot'
						 AND d.cod_pro='$as_codpro' 
						 AND d.codemp=a.codemp 
						 AND a.codser=d.codser 
					   ORDER BY d.orden";		
		}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_select_items  ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				$li_i=0;
				while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
				{
					$li_i++;
					$aa_items[$li_i][1]=$row["codigo"];
					$aa_items[$li_i][2]="<div align=left>".$row["denominacion"]."</div>";
					$aa_items[$li_i][3]="<div align=right>".number_format($row["cantidad"],2,",",".")."</div>";		
					$aa_items[$li_i][4]="<div align=right>".number_format($row["preciouni"],2,",",".")."</div>";	
					$aa_items[$li_i][5]="<div align=right>".number_format($row["subtotal"],2,",",".")."</div>";
					$aa_items[$li_i][6]="<div align=right>".number_format($row["iva"],2,",",".")."</div>";
					$aa_items[$li_i][7]="<div align=right>".number_format($row["total"],2,",",".")."</div>";		
				}																
			}		
	}  //Fin funcion uf_select_items
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_select_items_cotizacion(&$aa_items)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_cotizacion
		//		   Access: public
		//		   return:arreglo con los bienes/servicios de la cotizacion dada
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;		
		//Tomando los datos del querystring
		$as_tipsolcot=$_GET["tipsolcot"];
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_nompro=$_GET["nompro".$li_i];
			$ls_numcot=$_GET["numcot".$li_i];
			if($as_tipsolcot=='B')//Si la solicitud es de Bienes
			{
				$ls_sql= "SELECT a.denart as denominacion, d.montotart as monto
							FROM soc_dtcot_bienes d, siv_articulo a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codart=d.codart";					
			}
			elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
			{
				
				$ls_sql= "SELECT a.denser as denominacion, d.montotser as monto
							FROM soc_dtcot_servicio d, soc_servicios a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codser=d.codser";		
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_select_items_cotizacion".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				
				while($row=$this->io_sql->fetch_row($rs_data))
				{					
					$aa_items[$row["denominacion"]][$ls_nompro]=$row["monto"];									
				}
			}	
		}	
		
	}  //Fin funcion uf_select_items_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_analisis_cualitativo(&$la_arre2)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_analisis_cualitativo
		//		   Access: public
		//		   return:arreglo con los calificadores de un conjunto de proveedores dados
		//	  Description: Metodo que  devuelve los calificadores de un conjunto de proveedores dados
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_arre1=array();
		$la_arre2=array();		
		$lb_valido=true;		
		//Tomando los datos del querystring
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		$ls_proveedores="(";
		$ls_parentesis="";
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)//Construyendo la consulta sql;
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_parentesis=$ls_parentesis.")";
			$ls_proveedores=$ls_proveedores."'".$ls_codpro."'";
			if($li_i<$li_totalcotizaciones)
				$ls_proveedores=$ls_proveedores.",";				
		}
		$ls_proveedores=$ls_proveedores.")";
		
		$ls_sql="SELECT DISTINCT codclas FROM rpc_clasifxprov c WHERE cod_pro IN $ls_proveedores
					AND codemp='$this->ls_codemp'  AND status=0 AND codclas IN ";
					
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_sql=$ls_sql."(SELECT codclas FROM rpc_clasifxprov  WHERE cod_pro='$ls_codpro' ";
			if($li_i<$li_totalcotizaciones)			
			 $ls_sql=$ls_sql."AND codclas IN ";
		}
		$ls_sql=$ls_sql.$ls_parentesis;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{			
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{					
				$li_i++;
				$la_arre1[$li_i]=$row["codclas"];									
			}
		}
		if(($lb_valido) && ($li_totcalificadores=count($la_arre1))>0)//Si existen calificadores en comun y no ocurrio ningun error, se buscan los valores 
		{																				//de cada calificador por proveedor
				for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
				{
					$ls_codpro=$_GET["codpro".$li_i];
					$ls_nompro=$_GET["nompro".$li_i];
					$la_calificadores=array();
					for($li_j=1; $li_j<=$li_totcalificadores;$li_j++)
					{ 
							$ls_codclas=$la_arre1[$li_j];
							$ls_sql="SELECT c.denclas, cp.nivstatus 
									   FROM rpc_clasifxprov cp, rpc_clasificacion c
									  WHERE c.codemp='$this->ls_codemp' 
										AND cp.cod_pro='$ls_codpro' 
										AND cp.codclas='$ls_codclas' 
										AND c.codemp=cp.codemp 
										AND cp.codclas=c.codclas";
										
							$rs_data=$this->io_sql->select($ls_sql);
							if($rs_data===false)
							{
								$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
								$lb_valido=false;	
							}
							else
							{			
								while($row=$this->io_sql->fetch_row($rs_data))
								{					
									
									switch($row["nivstatus"])
									{
										case "0":
											$la_calificadores[$row["denclas"]]="Ninguno";
										break;
										case "1":
											$la_calificadores[$row["denclas"]] ="Bueno";
										break;
										case "2":
											$la_calificadores[$row["denclas"]] ="Regular";
										break;
										case "3":
											$la_calificadores[$row["denclas"]]="Malo";
										break;
									}
									
								}
							}
					}
					$la_arre2[$ls_nompro]=$la_calificadores;					
				}
		}
	}  //Fin funcion uf_analisis_cualitativo
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_analisis_cualitativo_items(&$aa_items)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_analisis_cualitativo_items 
		//		   Access: public
		//		   return: arreglo con los calificadores de los bienes/servicios por cotizacion
		//	  Description: Metodo que  devuelve los calificadores de los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 23/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;		
		//Tomando los datos del querystring
		$as_tipsolcot=$_GET["tipsolcot"];
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_nompro=$_GET["nompro".$li_i];
			$ls_numcot=$_GET["numcot".$li_i];
			if($as_tipsolcot=='B')//Si la solicitud es de Bienes
			{
				$ls_sql= "SELECT a.denart as denominacion,d.nivcalart AS calificacion
							FROM soc_dtcot_bienes d, siv_articulo a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codart=d.codart";					
			}
			elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
			{
				
				$ls_sql= "SELECT a.denser as denominacion, d.nivcalser AS calificacion
							FROM soc_dtcot_servicio d, soc_servicios a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codser=d.codser";		
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_analisis_cualitativo_items".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				
				while($row=$this->io_sql->fetch_row($rs_data))
				{					
					switch($row["calificacion"])
					{
						case "E":
							$ls_calificacion="Excelente";
						break;
						case "B":
							$ls_calificacion="Bueno";
						break;
						case "R":
							$ls_calificacion="Regular";
						break;
						case "M":
							$ls_calificacion="Malo";
						break;
						case "P":
							$ls_calificacion="Muy Malo";
						break;
					}
					
					$aa_items[$row["denominacion"]][$ls_nompro]=$ls_calificacion;									
				}
			}	
		}	
		
	}  //Fin funcion uf_analisis_cualitativo_items	
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_proveedores_item($as_tipsolcot, $as_numsolcot,$as_coditem,&$aa_proveedores)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_proveedores_item 
		//		   Access: public
		//		   return: arreglo con los proveedores que cotizaron un determinado bien/servicio
		//	  Description: Metodo que  devuelve los proveedores que cotizaron un determinado bien/servicio
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 28/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;		
		if($as_tipsolcot=='B')//Si la solicitud es de Bienes
		{
			$ls_sql= "SELECT c.numcot , c.cod_pro, r.nompro, d.canart as cantidad, d.preuniart as preciounitario, d.moniva, d.montotart as montototal,
						d.nivcalart as calidad
						FROM rpc_proveedor r, soc_cotizacion c, soc_dtcot_bienes d
						WHERE c.codemp='$this->ls_codemp' 
						  AND c.numsolcot='$as_numsolcot' 
						  AND d.codart='$as_coditem'
						  AND c.codemp=d.codemp 
						  AND d.codemp=r.codemp 
						  AND c.cod_pro=d.cod_pro 
						  AND c.cod_pro=r.cod_pro 
						  AND c.numcot=d.numcot";					
		}
		elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
		{
			
			$ls_sql= "SELECT c.numcot, c.cod_pro, r.nompro, d.canser as cantidad, d.monuniser as preciounitario, d.moniva, d.montotser as montototal,
						d.nivcalser as calidad 
						FROM rpc_proveedor r, soc_cotizacion c, soc_dtcot_servicio d
						WHERE c.codemp='$this->ls_codemp' 
						  AND c.numsolcot='$as_numsolcot' 
						  AND d.codser='$as_coditem'
						  AND c.codemp=d.codemp 
						  AND d.codemp=r.codemp 
						  AND c.cod_pro=d.cod_pro 
						  AND c.cod_pro=r.cod_pro 
						  AND c.numcot=d.numcot";		
		}
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_proveedores_item".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=1;
			while($row=$this->io_sql->fetch_row($rs_data))
			{					
				$aa_proveedores[$li_i]=$row;
				$li_i++;									
			}
		}		
		
	}  //Fin funcion uf_proveedores_items
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_select_cotizacion_analisis()
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion_analisis
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];				
		$ls_sql= "SELECT cxa.numcot, cxa.cod_pro
				  FROM soc_cotxanalisis cxa
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$ls_numanacot'";		
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cotizacion_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_proveedores[$li_i]=$row;					
			}																
		}
		return $aa_proveedores;
	}//fin de uf_select_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_select_solicitud_cotizacion()
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_cotizacion
		//		   Access: public
		//		  return :	campo que contiene la solicitud asociada a una cotizacion especifica
		//	  Description: Metodo que  devuelve la solicitud asociada a una cotizacion especifica
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 22/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];				
		$ls_sql= "SELECT DISTINCT c.numsolcot
				  FROM soc_cotxanalisis cxa, soc_cotizacion c
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$ls_numanacot'
          		  AND c.codemp=cxa.codemp AND c.cod_pro=cxa.cod_pro AND c.numcot=cxa.numcot";		
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_solicitud_cotizacion()".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$ls_solicitud=$row["numsolcot"];					
			}																
		}
		return $ls_solicitud;
	}//fin de uf_select_solicitud_cotizacion()
//---------------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_estado_analisis()
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_estado_analisis
		//		   Access: public
		//		  return : estado del analisis de cotizacion
		//    Description: Metodo que  retorna el estado del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 12/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];
		$ls_sql= "SELECT estana 
				 FROM soc_analisicotizacion
				 WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_estado_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$ls_estado=$row["estana"];				
			}		
		}
		return $ls_estado;
	}//fin de uf_estado_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
}
?>