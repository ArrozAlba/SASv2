<?php
class sigesp_sno_c_cierre_periodo3
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_prestamo;
	var $io_cuota;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_cierre_periodo3()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_cierre_periodo3
		//		   Access: public (sigesp_sno_c_cierre_periodo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("sigesp_sno_c_prestamo.php");
        $this->io_prestamo=new sigesp_sno_c_prestamo();
		require_once("sigesp_sno_c_prestamocuotas.php");
        $this->io_cuota=new sigesp_sno_c_prestamocuotas();		
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	}// end function sigesp_sno_c_cierre_periodo3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_prestamo_cierre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_prestamo_cierre 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que actualiza los prestamos al momento del cierre del periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 15/02/2006          Fecha última Modificacion : 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_cuota->uf_verificar_cuota_cobrada();
		if($lb_valido)
		{
			$lb_valido=$this->io_cuota->uf_cancelar_cuotas();
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_prestamo->uf_update_amortizados();
		}
		if($lb_valido)
		{
	       $lb_valido=$this->io_prestamo->uf_cancelar_prestamos();
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_prestamo->uf_suspender_prestamos();
 		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_prestamo->uf_activar_prestamos();		
        }
		return $lb_valido;
	}// end function uf_actualizar_prestamo_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_limpiar_constantes()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_constantes 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función  que actualiza el monto del concepto y lo reinicializa en cero 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006         
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon = 0, ".
				"       montopcon = 0 ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codcons IN (SELECT codcons FROM sno_constante ".
				"                    WHERE codemp='".$this->ls_codemp."' ".
				"					   AND codnom='".$this->ls_codnom."' ".
				"					   AND reicon=1)";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_limpiar_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_limpiar_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_limpiar_concepto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_concepto 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función  que actualiza el monto del concepto y lo reinicializa en cero 
	    //     Creado por: Ing. Yesenia Moreno	
	    // Fecha Creación: 01/08/2008         
		// Modificado Por: 											Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_conceptopersonal ".
				"   SET aplcon = 0 ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codconc IN (SELECT codconc FROM sno_concepto ".
				"                    WHERE codemp='".$this->ls_codemp."' ".
				"					   AND codnom='".$this->ls_codnom."' ".
				"					   AND frevarcon=1)";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_limpiar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_limpiar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_limpiar_proyectopersonal()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_proyectopersonal 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función  que actualiza los días de los proyectos los coloca en cero
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 10/07/2007        
		// Modificado Por: 											Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_proyectopersonal ".
				"   SET totdiaper = 0, ".
				"   	totdiames = 0, ".
				"   	pordiames = 0  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_limpiar_proyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_limpiar_proyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_periodo($as_codperi,&$as_codperi_next,&$as_codperi_prev)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//                 as_codperi_next //  codigo del periodo siguiente
		//                 as_codperi_prev  // codigo del periodo previo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que obtiene los  periodos siguientes y previos
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006         
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_sql="UPDATE sno_periodo ".
                "   SET cerper=1 ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
		   $lb_valido=false;
		   $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_actualizar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($ls_perresnom<>"000") 
			{
				$as_codperi_next=$ls_perresnom;
				$as_codperi_prev=true;
			}
			else
			{
				$as_codperi_next=$this->io_funciones->uf_rellenar_izq((intval($ls_peractnom)+1),0,3);
				$as_codperi_prev=false;		
			}			
			$ls_sql="SELECT codperi ".
					"  FROM sno_periodo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND cerper=0 ".
					"ORDER BY codperi DESC";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_actualizar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
			   if($row=$this->io_sql->fetch_row($rs_data))
			   {
					$ls_codperi=$this->io_funciones->uf_rellenar_izq($row["codperi"],0,3);
					if(intval($as_codperi_next)>intval($ls_codperi))
					{
						$as_codperi_next="000";
					}
			   }
			   $ls_sql="UPDATE sno_nomina ".
					   "   SET peractnom='".$as_codperi_next."' ".
					   " WHERE codemp='".$this->ls_codemp."' ".
					   "   AND codnom='".$this->ls_codnom."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_actualizar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		return $lb_valido;
	}// end function uf_actualizar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_salida($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_salida 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que elimina el periodos actual y el siguiente de tabla sno_salida para proceder al 
		//                 cierre del mismo.
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006         
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_salida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_eliminar_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_eliminar_salida
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_prenomina($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_prenomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que elimina el periodos actual y el siguiente de tabla sno_prenomina para proceder al 
		//                 cierre del mismo.
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006         
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_prenomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_eliminar_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_eliminar_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_resumen($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_resumen 
		//	    Arguments: as_codperi // codigo del periodo
		//	               as_codperi_next // codigo del periodo siguiente
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que elimina el periodos actual y el siguiente de tabla sno_resumen para proceder al 
		//                 cierre del mismo.
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006         
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_resumen ".
				" WHERE codemp='". $this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_eliminar_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
        return $lb_valido;
	}// end function uf_eliminar_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_limpiar_periodo($as_codperi,$as_codperi_next)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function:    uf_limpiar_periodo 
		//	     Arguments:    $as_codperi_next // codigo del periodo siguiente
		//                     $as_codperi  // codigo de periodo     
		//	       Returns:	   $lb_valido true si es correcto la funcion o false en caso contrario
		//	   Description:    Función  que elimina el periodo actual y siguiente de la tabla sno_salida y 
		//                     actualiza la tabla sno_resumen con los montos en cero(0.0000)
	    //     Creado por :    Ing. Yozelin Barragán
	    // Fecha Creación :    08/02/2006      
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->uf_eliminar_salida($as_codperi);
		if($lb_valido)
		{
		   $lb_valido=$this->uf_eliminar_prenomina($as_codperi);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_eliminar_resumen($as_codperi);
		}
	   return $lb_valido;
	}// end function uf_limpiar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existe_hpersonalnomina($as_codperi,$as_anocur)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existe_hpersonalnomina
		//	    Arguments: as_codperi // codigo del periodo 
		//	               as_anocur  // año en curso
		//        Returns: li_total devuelve la cantidad de registros que existen en la busquueda 
		//	  Description: Función que retorna cuantos existen en la tabla personalnomina
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 13/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	$li_total=0;
		$ls_sql="SELECT count(codper) AS total ".
                "  FROM sno_hpersonalnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$as_anocur."' ".
				"   AND codperi='".$as_codperi."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_existe_hpersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_total=$row["total"];
			}
		}
		return $li_total;
	}// end function uf_existe_hpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_salida($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_delete_salida 
		//	     Arguments:  $as_codperi // codigo del periodo
		//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
		//	   Description:  Función que elimina el periodos de tabla  sno_salida para proceder al cierre del mismo
	    //     Creado por :  Ing. Yozelin Barragán
	    // Fecha Creación :  17/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_salida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_prenomina($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prenomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla sno_prenomina para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 20/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_prenomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_resumen($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	      Function:  uf_delete_resumen 
		//	     Arguments:  $as_codperi // codigo del periodo
		//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
		//	   Description:  Función que elimina el periodos de tabla sno_resumen para proceder al cierre del mismo
	    //     Creado por :  Ing. Yozelin Barragán
	    // Fecha Creación :  17/02/2006    
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_resumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_prestamoperiodo($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prestamoperiodo 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_prestamoperiodo para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_prestamosperiodo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_prestamoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_prestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_prestamoamortizado($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prestamoamortizado 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_prestamoperiodo para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 01/12/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_prestamosamortizado ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_prestamoamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_prestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_prestamos($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prestamos 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla uf_delete_prestamos para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_prestamos ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_prestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_prestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_tipoprestamo($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:   uf_delete_tipoprestamo 
		//	     Arguments:  $as_codperi // codigo del periodo
		//	       Returns:	 $lb_valido true si es correcto el delete o false en caso contrario
		//	   Description:  Función que elimina el periodos de tabla sno_prestamo para proceder al cierre del mismo
	    //     Creado por :  Ing. Yozelin Barragán
	    // Fecha Creación :  17/02/2006    
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_tipoprestamo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_tipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_tipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_constantepersonal($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constantepersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla sno_constantepersonal para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006       
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_constantepersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_constante($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constante 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla sno_constante para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_constante ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_constante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_constante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_conceptovacacion($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptovacacion 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_conceptovacacion para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006      
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_conceptovacacion ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_conceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_conceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_conceptopersonal($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptopersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//        Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_conceptopersonal para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_conceptopersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_primaconcepto($as_codperi) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_primaconcepto 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_concepto para proceder abrir  el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_primaconcepto ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_concepto($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconcepto 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_concepto para proceder abrir  el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_concepto ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_vacacpersonal($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_vacacpersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_vacacpersonal para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_vacacpersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper IN (SELECT codper FROM sno_personalnomina ".
				"                    WHERE codemp='".$this->ls_codemp."'".
				"                      AND codnom='".$this->ls_codnom."')";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_vacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_vacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_proyectopersonal($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_proyectopersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_proyectopersonal para proceder abrir el mismo
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 10/07/2007  
		// Modificado Por: 												Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_proyectopersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_proyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_proyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_personalpension($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_personalpension 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_personalpension para proceder abrir el mismo
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 07/05/2008  
		// Modificado Por: 											Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_personalpension ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_personalpension ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_personalpension
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_encargaduria()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_encargaduria
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_encargaduria para proceder abrir el mismo
	    //     Creado por: Ing. María Beatriz
	    // Fecha Creación: 02/01/2009    
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_encargaduria ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_personalnomina($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_personalnomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_personalnomina para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_personalnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			print ($this->io_sql->message);
		}
		return $lb_valido;
	}// end function uf_delete_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_codigounicorac($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_codigounicorac
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla sno_asignacioncargo para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006      
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_codigounicorac ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_codigounicorac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_asignacioncargo($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_asignacioncargo 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla sno_asignacioncargo para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006      
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_asignacioncargo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_primadocentepersonal($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cargo 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla historica sno_hcargo para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_primadocentepersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_primadocentepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_primadocentepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

    //-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_cargo($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cargo 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla historica sno_hcargo para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_cargo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_unidadadmin($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_unidadadmin 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_unidadadmin para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_unidadadmin ".
                " WHERE codemp='".$this->ls_codemp."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_unidadadmin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_unidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_proyecto($as_codperi)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_proyecto 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_proyecto para proceder abrir el mismo
	    //     Creado por: Ing. Yesenia Moreno	
	    // Fecha Creación: 10/07/2007 
		// Modificado Por: 												Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_proyecto ".
                " WHERE codemp='".$this->ls_codemp."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_primagrado($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_primagrado 
		//		   Access: private
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina las primagrado para proceder abrir el mismo
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 11/03/2006          Fecha última Modificacion : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="DELETE ".
                "  FROM sno_primagrado ".
                " WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_grado($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_grado 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_grado para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_grado ".
                " WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_tabla($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tabla 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Función que elimina el periodos de tabla  sno_tabla para proceder abrir el mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_tabulador ".
                " WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_periodo($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_delete_periodo 
		//	     Arguments:  $as_codperi // codigo del periodo
		//	       Returns:	 $lb_valido true si es correcto el delete o false en caso contrario
		//	   Description:  Función que elimina el periodos de tabla  sno_periodo para proceder abrir el mismo
	    //     Creado por :  Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hperiodo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_subnomina($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:   uf_delete_subnomina 
		//	     Arguments:  $as_codperi // codigo del periodo
		//	       Returns:	 $lb_valido true si es correcto el delete o false en caso contrario
		//	   Description:  Función que elimina el periodos de tabla sno_subnomina para proceder abrir el mismo
	    //     Creado por :  Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_subnomina ".
                " WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_delete_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		    $lb_valido=true;
		}
		return $lb_valido;
	}// end function uf_delete_subnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_periodo($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto los delete o false en caso contrario
		//	  Description: Función que elimina el periodos de todas las tablas para proceder abrir un período 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->uf_delete_salida($as_codperi);
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_prenomina($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_resumen($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_prestamoperiodo($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_prestamoamortizado($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_prestamos($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_tipoprestamo($as_codperi);
		}
        if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_constantepersonal($as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_constante($as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_conceptovacacion($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_conceptopersonal($as_codperi);
		}  
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_primaconcepto($as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_concepto($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_vacacpersonal($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_proyectopersonal($as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_encargaduria();
		} 		 
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_personalpension($as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_primadocentepersonal($as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_personalnomina($as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_cargo($as_codperi);
		}  
		//if($lb_valido) 
		//{
		  // $lb_valido=$this->uf_delete_unidadadmin($as_codperi);
		//} 
		//if($lb_valido) 
		//{
		  // $lb_valido=$this->uf_delete_proyecto($as_codperi);
		//} 
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_codigounicorac($as_codperi);
		} 
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_asignacioncargo($as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_primagrado($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_grado($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_tabla($as_codperi);
		} 
        /*if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_periodo($as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->uf_delete_subnomina($as_codperi);
		}*/ 
		return $lb_valido;
	}// end function uf_eliminar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tabla($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tabla 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_tabla de la tabla historica sno_htabla 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tabulador (codemp,codnom,codtab,destab,maxpasgra) ".
                "     SELECT codemp,codnom,codtab,destab,maxpasgra ".
                "       FROM sno_htabulador ".	
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_grado($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_insert_grado 
		//	     Arguments: $adt_anocurnom // año en curso de la nomina 
		//                  $as_codperi_abrir // codigo del periodo abrir
		//	       Returns:	$lb_valido true si es correcto el insert o false en caso contrario
		//	   Description: Función que inserta los datos en la tabla sno_grado de la tabla historica sno_hgrado
	    //     Creado por : Ing. Yozelin Barragán.
	    // Fecha Creación : 10/02/2006    
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_grado (codemp, codnom, codtab, codgra, codpas, monsalgra, moncomgra) ".
                "     SELECT codemp,codnom,codtab,codgra,codpas,monsalgra,moncomgra ".
                "       FROM sno_hgrado ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primagrado($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_insert_primagrado 
		//		    Access: private
		//	     Arguments: adt_anocurnom // año en curso de la nomina 
		//                  as_codperi_abrir // codigo del periodo abrir
		//	       Returns:	lb_valido true si es correcto el insert o false en caso contrario
		//	   Description: Función que inserta los datos en la tabla sno_primagrado de la tabla historica sno_hprimagrado
	    //      Creado por: Ing. Yesenia Moreno
	    //  Fecha Creación: 11/03/2006          Fecha última Modificacion : 10/02/2006  Hora :08:42 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql=" INSERT INTO sno_primagrado(codemp, codnom, codtab, codpas, codgra, codpri, despri, monpri) ".
                "      SELECT codemp, codnom, codtab, codpas, codgra, codpri, despri, monpri ".
                "        FROM sno_hprimagrado ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"         AND codnom='".$this->ls_codnom."' ".
				"         AND anocur='".$adt_anocurnom."' ".
				"         AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primasdocentes($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadadmin 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_unidadadmin de la tabla historica sno_hunidadadmin 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO sno_primasdocentes (codemp,codpridoc,despridoc,valpridoc,tippridoc) ".
                "      SELECT codemp,codpridoc,despridoc,valpridoc,tippridoc ".
                "        FROM sno_hprimasdocentes ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"         AND anocur='".$adt_anocurnom."' ".
				"		  AND codperi='".$as_codperi_abrir."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_primasdocentes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_unidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primadocentepersonal($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadadmin 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_unidadadmin de la tabla historica sno_hunidadadmin 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO sno_primadocentepersonal (codemp,codper,codnom,codpridoc) ".
                "      SELECT codemp,codper,codnom,codpridoc ".
                "        FROM sno_hprimadocentepersonal ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ".
				"         AND anocur='".$adt_anocurnom."' ".
				"		  AND codperi='".$as_codperi_abrir."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_primadocentepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_unidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidadadmin($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadadmin 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_unidadadmin de la tabla historica sno_hunidadadmin 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO sno_unidadadmin (codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,codprouniadm,estcla) ".
                "      SELECT codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,codprouniadm,estcla ".
                "        FROM sno_hunidadadmin ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ".
				"         AND anocur='".$adt_anocurnom."' ".
				"		  AND codperi='".$as_codperi_abrir."' ".
				"		  AND minorguniadm NOT IN (SELECT minorguniadm FROM sno_unidadadmin ".
                "             					    WHERE sno_unidadadmin.codemp = sno_hunidadadmin.codemp ".
                "             						  AND sno_unidadadmin.minorguniadm = sno_hunidadadmin.minorguniadm ".
                "             						  AND sno_unidadadmin.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
                "             						  AND sno_unidadadmin.uniuniadm = sno_hunidadadmin.uniuniadm ".
                "             						  AND sno_unidadadmin.depuniadm = sno_hunidadadmin.depuniadm ".
                "             						  AND sno_unidadadmin.prouniadm = sno_hunidadadmin.prouniadm) ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_unidadadmin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_unidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proyecto($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proyecto 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_proyecto de la tabla historica sno_hproyecto
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 10/07/2007  
		// Modificado Por: 												Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO sno_proyecto (codemp, codproy, nomproy, estproproy,estcla) ".
                "      SELECT codemp, codproy, nomproy, estproproy,estcla ".
                "        FROM sno_hproyecto ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ".
				"         AND anocur='".$adt_anocurnom."' ".
				"		  AND codperi='".$as_codperi_abrir."' ".
				"		  AND codproy NOT IN (SELECT codproy FROM sno_proyecto ".
                "             				   WHERE sno_proyecto.codemp = sno_hproyecto.codemp ".
                "             					 AND sno_proyecto.codproy = sno_hproyecto.codproy) ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargo($adt_anocurnom,$as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargo 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_cargo de la tabla historica sno_hcargo 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_cargo (codemp,codnom,codcar,descar) ".
                "     SELECT codemp,codnom,codcar,descar ".
                "       FROM sno_hcargo ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalnomina($adt_anocurnom,$as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalnomina 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_personalnomina de la tabla historica sno_hpersonalnomina 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_personalnomina (codemp, codnom, codper,codsubnom, codtab, codasicar, ".
                 "                                codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, ".
				 "                                depuniadm, prouniadm, pagbanper,codban, codcueban, tipcuebanper, ".
				 "                                codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, ".
				 "                                quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, ".
				 "                                fecsusper,cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, ".
				 "								  catjub, codclavia, codunirac, pagtaqper, fecascper, grado, descasicar, coddep,salnorper, estencper) ".
                 "     SELECT codemp,codnom,codper,codsubnom,codtab,codasicar,codgra,codpas,sueper,horper,minorguniadm,ofiuniadm, ".
                 "            uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban,tipcuebanper,codcar,fecingper,staper,cueaboper, ".
				 "            fecculcontr,codded,codtipper,quivacper,codtabvac,sueintper,pagefeper,sueproper,codage,fecegrper, ".
				 "            fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,conjub,catjub,codclavia, codunirac, pagtaqper, fecascper, grado, descasicar, coddep,salnorper,estencper ".
                 "       FROM sno_hpersonalnomina ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalpension($adt_anocurnom,$as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalpension 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_personalpension de la tabla historica sno_hpersonalpension 
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 07/05/2008  
		// Modificado Por: 										Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_personalpension (codemp, codnom, codper, suebasper, pritraper, pridesper, prianoserper, ".
				 "								  prinoascper, priespper, priproper, subtotper, porpenper, monpenper) ".
                 "     SELECT codemp, codnom, codper, suebasper, pritraper, pridesper, prianoserper, ".
				 "			  prinoascper, priespper, priproper, subtotper, porpenper, monpenper".
                 "       FROM sno_hpersonalpension ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_personalpension ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_personalpension
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_asignacioncargo($adt_anocurnom,$as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_asignacioncargo 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_personalnomina de la tabla historica sno_hpersonalnomina 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 02/03/2006      
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_asignacioncargo (codemp, codnom, codasicar, denasicar, claasicar, codtab, codpas, codgra, ".
		         "                                 codded, codtipper, numvacasicar, numocuasicar, codproasicar, minorguniadm,".
				 "                                 ofiuniadm, uniuniadm, depuniadm, prouniadm, estcla, grado) ".
                 "     SELECT codemp,codnom,codasicar,denasicar,claasicar,codtab,codpas,codgra,codded,codtipper, ".
                 "            numvacasicar,numocuasicar,codproasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm, ".
				 "            estcla, grado ".
                 "       FROM sno_hasignacioncargo ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "        AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "        AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_codigounicorac($adt_anocurnom,$as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_codigounicorac
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_codigounicorac de la tabla historica sno_hcodigounicorac 
	    //     Creado por: Ing. María Beatriz Unda.
	    // Fecha Creación: 04/11/2008      
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_codigounicorac (codemp, codnom, codasicar, codunirac, estcodunirac) ".
                 "     SELECT codemp,codnom,codasicar,codunirac,estcodunirac ".
                 "       FROM sno_hcodigounicorac ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "        AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "        AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_codigounicorac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_codigounicorac
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proyectopersonal($adt_anocurnom,$as_codperi_abrir)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proyectopersonal 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos de la tabla sno_proyectopersonal en la tabla historica sno_hproyectopersonal 
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 10/02/2006   
		// Modificado Por: 														Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_proyectopersonal(codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames) ".
                "     SELECT codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames ".
                "       FROM sno_hproyectopersonal ".
                " 	   WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"		 AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_proyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_proyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_vacacpersonal($adt_anocurnom,$as_codperi_abrir)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_vacacpersonal 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos de la tabla sno_vacacpersonal en la tabla historica sno_hvacacpersonal 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_vacacpersonal(codemp, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, ".
                "              				   stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, ".
                "              				   dianorvac, quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, ".
                "              				   cod_1, nro_dias_1, Monto_1, periodo_2, cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, ".
                "              				   nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, Monto_4, periodo_5, cod_5, ".
                "              				   nro_dias_5, Monto_5, diapervac,pagpersal) ".
                "     SELECT codemp,codper,codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,sueintvac,diabonvac,".
				"			 obsvac,diapenvac,persalvac,peringvac,dianorvac,quisalvac,quireivac,diaadivac,diaadibon,diafer,sabdom,".
				"			 periodo_1,cod_1,nro_dias_1,Monto_1,periodo_2,cod_2,nro_dias_2,Monto_2,periodo_3,cod_3,nro_dias_3, ".
                "            Monto_3,periodo_4,cod_4,nro_dias_4,Monto_4,periodo_5,cod_5,nro_dias_5,Monto_5, diapervac,pagpersal ".
                "       FROM sno_hvacacpersonal ".
                " 	   WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"		 AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ".
				"		 AND codper NOT IN (SELECT codper FROM sno_vacacpersonal) ";  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_vacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_vacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_concepto($adt_anocurnom,$as_codperi_abrir)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_concepto 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_concepto de la tabla historica sno_hconcepto 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_concepto (codemp, codnom, codconc, nomcon, titcon, sigcon, forcon, ".
                 "                          glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, cueconcon, ".
                 "                          aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, ".
                 "                          cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, ".
                 "                          codprov, cedben, conprenom, sueintvaccon, aplarccon, conprocon,repacucon,repconsunicon,".
				 "							consunicon,estcla,intingcon, spi_cuenta, poringcon, quirepcon, asifidper, asifidpat, frevarcon, persalnor,aplresenc,conperenc,codente) ".
                 "     SELECT codemp,codnom,codconc,nomcon,titcon,sigcon,forcon,glocon,acumaxcon,valmincon,valmaxcon,concon, ".
				 "            cueprecon,cueconcon,aplisrcon,sueintcon,intprocon,codpro,forpatcon,cueprepatcon,cueconpatcon, ".
				 "            titretempcon,titretpatcon,valminpatcon,valmaxpatcon,codprov,cedben,conprenom,sueintvaccon, ".
				 "			  aplarccon, conprocon,repacucon,repconsunicon,consunicon,estcla,intingcon, spi_cuenta, poringcon, quirepcon, ".
				 "			  asifidper, asifidpat, frevarcon, persalnor,aplresenc,conperenc,codente ".
                 " 		 FROM sno_hconcepto ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primaconcepto($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primaconcepto 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_primaconcepto de la tabla historica sno_hprimaconcepto 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 20/02/2006
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_primaconcepto (codemp, codnom, codconc, anopri, valpri) ".
                "     SELECT codemp,codnom,codconc,anopri,valpri ".
                "       FROM sno_hprimaconcepto ".
                " 	   WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_conceptopersonal de la tabla historica sno_hconceptopersonal 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006    
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptopersonal (codemp, codnom, codper, codconc, aplcon, valcon,acuemp, acuiniemp, acupat, acuinipat) ".
                "     SELECT codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat ".
                "       FROM sno_hconceptopersonal ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptovacacion($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptovacacion 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_conceptovacacion de la tabla historica sno_hconceptovacacion 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006  
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptovacacion (codemp, codnom, codconc, forsalvac, acumaxsalvac, ".
                "                                  minsalvac, maxsalvac, consalvac, forpatsalvac, minpatsalvac, ".
                "                                  maxpatsalvac, forreivac, acumaxreivac, minreivac, maxreivac, conreivac, ".
                "                                  forpatreivac, minpatreivac, maxpatreivac) ".
                "     SELECT codemp,codnom,codconc,forsalvac,acumaxsalvac,minsalvac,maxsalvac,consalvac,forpatsalvac,minpatsalvac, ".
				"            maxpatsalvac,forreivac,acumaxreivac,minreivac,maxreivac,conreivac,forpatreivac,minpatreivac,maxpatreivac ".
                "       FROM sno_hconceptovacacion ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_conceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_conceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constante($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_insert_constante 
		//	     Arguments: $adt_anocurnom // año en curso de la nomina 
		//                  $as_codperi_abrir // codigo del periodo abrir
		//	       Returns:	$lb_valido true si es correcto el insert o false en caso contrario
		//	   Description: Función que inserta los datos en la tabla sno_constante de la tabla historica sno_hconstante 
	    //     Creado por : Ing. Yozelin Barragán.
	    // Fecha Creación : 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_constante (codemp, codnom, codcons, nomcon, unicon, equcon,topcon, valcon, reicon, tipnumcon,conespseg,esttopmod,conperenc) ".
                "     SELECT codemp,codnom,codcons,nomcon,unicon,equcon,topcon,valcon,reicon,tipnumcon,conespseg,esttopmod, ".
				"            conperenc".
                "       FROM sno_hconstante ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_constante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_constante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantepersonal($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantepersonal 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_constantepersonal de la tabla historica sno_hconstantepersonal
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_constantepersonal (codemp, codnom, codper, codcons, moncon, montopcon) ".
                 "     SELECT codemp,codnom,codper,codcons,moncon,montopcon ".
                 "       FROM sno_hconstantepersonal ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipoprestamo($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipoprestamo 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_tipoprestamo de la tabla historica sno_htipoprestamo
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_tipoprestamo (codemp, codnom, codtippre, destippre) ".
                 "     SELECT codemp,codnom,codtippre,destippre ".
                 "       FROM sno_htipoprestamo ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "		  AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_tipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_tipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamos($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamos 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo  abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_prestamos de la tabla historica sno_hprestamos 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_prestamos (codemp,codnom,codper,codtippre,numpre,codconc,monpre,numcuopre,perinipre,monamopre,stapre,fecpre, obsrecpre, obssuspre, tipcuopre) ".
                 " 	   SELECT codemp,codnom,codper,codtippre,numpre,codconc,monpre,numcuopre,perinipre,monamopre,stapre,fecpre, obsrecpre, obssuspre, tipcuopre ".
                 "       FROM sno_hprestamos ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "		  AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_prestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_prestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamoperiodo($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamoperiodo 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_prestamoperiodo de la tabla historica sno_hprestamoperiodo 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_prestamosperiodo (codemp,codnom,codper,numpre,codtippre,numcuo,percob,feciniper,fecfinper,moncuo,estcuo) ".
                 "     SELECT codemp,codnom,codper,numpre,codtippre,numcuo,percob,feciniper,fecfinper,moncuo,estcuo ".
                 " 		 FROM sno_hprestamosperiodo ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "		  AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_prestamoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_prestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamoamortizado($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamoamortizado 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_prestamoperiodo de la tabla historica sno_hprestamoperiodo 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_prestamosamortizado (codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo) ".
                 "     SELECT codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo ".
                 " 		 FROM sno_hprestamosamortizado ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "		  AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_prestamoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_prestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo  abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos de la tabla sno_resumen en la tabla historica sno_hresumen 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen (codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,segquires,monnetres,notres) ".
                "     SELECT codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,segquires,monnetres,notres ".
                " 		FROM sno_hresumen ".
                " 	   WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND anocur='".$adt_anocurnom."' ".
				"		 AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_salida($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_salida 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_salida de la tabla historica sno_hsalida 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 10/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_salida (codemp, codnom, codperi, codper, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
                 " 	   SELECT codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal, priquisal, segquisal ".
                 " 		 FROM sno_hsalida ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prenomina($adt_anocurnom,$as_codperi_abrir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prenomina 
		//	    Arguments: adt_anocurnom // año en curso de la nomina 
		//                 as_codperi_abrir // codigo del periodo abrir
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Función que inserta los datos en la tabla sno_prenomina de la tabla historica sno_hprenomina 
	    //     Creado por: Ing. Yozelin Barragán.
	    // Fecha Creación: 20/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_prenomina (codemp, codnom, codper, codperi, codconc, tipprenom, valprenom, valhis) ".
                 "     SELECT codemp,codnom,codper,codperi,codconc,tipprenom,valprenom,valhis ".
                 " 		 FROM sno_hprenomina ".
                 " 		WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ".
				 "        AND anocur='".$adt_anocurnom."' ".
				 "		  AND codperi='".$as_codperi_abrir."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo3 MÉTODO->uf_insert_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_periodo_abrir($as_codperi_abrir)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_periodo_abrir 
		//	    Arguments: as_codperi // codigo del periodo abrir 
		//	      Returns: lb_valido true si el insert se hizo correctamente o false en caso contrario
		//	  Description: Función que se encarga de insertar en las tablas normales el periodo a brir  de los historicos
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006          
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=$this->uf_insert_tabla($ldt_anocurnom,$as_codperi_abrir); 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_grado($ldt_anocurnom,$as_codperi_abrir); 
		}   
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_primagrado($ldt_anocurnom,$as_codperi_abrir); 
		}   
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_unidadadmin($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_proyecto($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_cargo($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_asignacioncargo($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_codigounicorac($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_personalnomina($ldt_anocurnom,$as_codperi_abrir); 
		}
		/*/if($lb_valido)
		{
			$lb_valido=$this->uf_insert_primasdocentes($ldt_anocurnom,$as_codperi_abrir); 
		}/*/
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_primadocentepersonal($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_personalpension($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_proyectopersonal($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_vacacpersonal($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_concepto($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_primaconcepto($ldt_anocurnom,$as_codperi_abrir);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_conceptopersonal($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_conceptovacacion($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_constante($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_constantepersonal($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_tipoprestamo($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_prestamos($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_prestamoperiodo($ldt_anocurnom,$as_codperi_abrir); 
		}  
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_prestamoamortizado($ldt_anocurnom,$as_codperi_abrir); 
		}  
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_resumen($ldt_anocurnom,$as_codperi_abrir); 
		} 
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_salida($ldt_anocurnom,$as_codperi_abrir); 
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_prenomina($ldt_anocurnom,$as_codperi_abrir);
		}
		return $lb_valido;
	}// end function uf_insert_periodo_abrir
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_procesar_historico($as_codperi_abrir,$as_codperi_actual)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_procesar_historico 
		//	    Arguments: as_codperi_abrir // codigo del periodo abrir 
		//                 as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se enacarga de reversar el proceso de historicos del periodo abrir 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->uf_eliminar_periodo($as_codperi_abrir);
		if($lb_valido)
		{
		  $lb_valido=$this->uf_insert_periodo_abrir($as_codperi_abrir); 
		}
		return  $lb_valido;
	}// end function uf_reversar_procesar_historico
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>