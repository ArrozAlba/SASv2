<?php
class sigesp_spg_c_estprog3
{
var $is_msg_error;
	
		function sigesp_spg_c_estprog3($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	      require_once("../../shared/class_folder/class_funciones.php");
          require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("class_folder/sigesp_spg_c_estprog4.php");
		  $this->io_estpro4    = new sigesp_spg_c_estprog4($conn);
		  $this->io_seguridad  = new sigesp_c_seguridad();		  
		  $this->io_funcion    = new class_funciones();
		  $this->io_sql        = new class_sql($conn);
		  $this->io_msg        = new class_mensajes();		
		}

function uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$ls_estcla) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_select_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = " SELECT * FROM spg_ep3                                                          ".
               "  WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
               "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND estcla='".$ls_estcla."'     ";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_spg_select_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if($li_numrows>0)
		 {
		   $lb_valido=true;
		   $this->io_sql->free_result($rs_data);
		 }
	 }
  return $lb_valido;
}

function uf_select_dt_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estcla) 
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_dt_fuefin
	//	          Access:  public
	// 	        Arguments   
	//        $as_codemp:  Código de la Empresa.
	//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
	//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
	//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
	//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido = false;
	$ls_sql=" SELECT codfuefin".
			"   FROM spg_dt_fuentefinanciamiento".
			"  WHERE codemp='".$as_codemp."'".
			"    AND codestpro1='".$as_codestpro1."'".
			"    AND codestpro2='".$as_codestpro2."'".
			"    AND codestpro3='".$as_codestpro3."'".
			"    AND estcla='".$as_estcla."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_select_dt_fuefin; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_codfuefin=$row["codfuefin"];
			if($ls_codfuefin=="--")
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
	}
	return $lb_valido;
}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_fuefin
		//		   Access: private
		//	    Arguments: as_codemp  
		//				   as_codestpro1 
		//				   as_codestpro2 
		//				   as_codestpro3 
		//				   as_estcla     
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el detalle de la fuente de financiamiento por defecto
		//	   Creado Por: Ing. Luis Lang
		// Fecha Creación: 23/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codestpro4 = $this->io_funcion ->uf_cerosizquierda('',25);
		$ls_codestpro5 = $this->io_funcion ->uf_cerosizquierda('',25);
		$ls_sql="INSERT INTO spg_dt_fuentefinanciamiento (codemp, codfuefin, codestpro1, codestpro2, codestpro3, codestpro4,".
				"	                                      codestpro5, estcla)".
				"	  VALUES ('".$as_codemp."','--','".$as_codestpro1."','".$as_codestpro2."',".
				" 			  '".$as_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$as_estcla."')"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			$lb_valido=false;
			$this->is_msg_error = "CLASE->SIGESP_SPG_C_ESTPROG4; METODO->uf_insert_dt_fuefin ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------


function uf_spg_insert_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro3,$as_codfuefin,$ai_estmodest,
                               $as_estcla,$as_chkrecuadi,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_insert_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_denestpro3:  Denominación del código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

	  if($as_codfuefin=='')
	  {
	  		$as_codfuefin='--';
	  }
	if (!array_key_exists('session_activa',$_SESSION))
    {	 
	  $ls_sql = " INSERT INTO spg_ep3 (codemp,codestpro1,codestpro2,codestpro3,denestpro3,codfuefin,estcla,estreradi) ".
	            " VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_denestpro3."','".$as_codfuefin."','".$as_estcla."','".$as_chkrecuadi."')";
	}
	else
	{
	  $ls_sql = " INSERT INTO spg_ep3 (codemp,codestpro1,codestpro2,codestpro3,denestpro3,estcla,estreradi) ".
	            " VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_denestpro3."','".$as_estcla."','".$as_chkrecuadi."')";
	}
	  $this->io_sql->begin_transaction();
	  $rs_data = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)		     
		 {
		   $lb_valido          = false;
 	       $this->is_msg_error = "CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_spg_insert_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	  else
		 {
		   $lb_valido= true;
		   if ($ai_estmodest=='1')
		   {
				$as_codestpro4 = $this->io_funcion ->uf_cerosizquierda('',25);
				$lb_existe=$this->io_estpro4->uf_spg_select_estprog4($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_estcla);
				if(!$lb_existe)
				{
				   $lb_valido = $this->io_estpro4->uf_spg_insert_estprog4($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,'NINGUNO',$ai_estmodest,$as_estcla,$aa_seguridad);
			       if (!$lb_valido)
				   {
					 $lb_valido = false;
				     $this->is_msg_error = "CLASE->SIGESP_SPG_C_ESTPROG4; METODO->uf_spg_insert_estprog4(Insert Nivel 4 Default); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				   }
				 }
				if (array_key_exists('session_activa',$_SESSION))
				{	 
					$lb_existe=$this->uf_select_dt_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estcla);
					if(!$lb_existe)
					{
						$lb_valido=$this->uf_insert_dt_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estcla);
					}
				}
			  }
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento      = "INSERT";
		   $ls_descripcion = "Insertó en SPG Nuevo Estructura Presupuestaria/programatica ".$as_denestpro3." y el estatus de recursos adicionales ".$as_chkrecuadi." con codigo ".$as_codestpro3." asociado al Nivel 1 con ".$as_codestpro1." y con el Nivel 2 a ".$as_codestpro2;
		   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}

function uf_spg_update_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro3,$as_codfuefin,$as_estcla,$as_chkrecuadi,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_update_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o Programática, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
 if($as_codfuefin=='')
	  {
	  		$as_codfuefin='--';
	  }
	if (array_key_exists('session_activa',$_SESSION))
    {	 
		$ls_sql=" UPDATE spg_ep3".
		  		"    SET denestpro3='".$as_denestpro3."' , estreradi='".$as_chkrecuadi."' ".
        	    "  WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
		   		"        codestpro3='".$as_codestpro3."' AND estcla='".$as_estcla."'  ";
	}
	else
	{
		$ls_sql=" UPDATE spg_ep3".
		  		"    SET denestpro3='".$as_denestpro3."' , codfuefin='".$as_codfuefin."', estreradi='".$as_chkrecuadi."' ".
        	    "  WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
		   		"        codestpro3='".$as_codestpro3."' AND estcla='".$as_estcla."'  ";
	}
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_spg_update_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido = true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "UPDATE";
	   $ls_descripcion = " Actualizo el codigo ".$as_codestpro3." y el estatus de recursos adicionales ".$as_chkrecuadi." en spg_ep3 asociado al codigo ".$as_codestpro1."en spg_ep1 y al codigo ".$as_codestpro2." en la tabla spg_ep2";
	   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
}

function uf_spg_delete_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_denestpro,$ai_estmodest,$ls_estcla,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_delete_estprog3
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o 
//                     Programática, la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

	$lb_valido = false;
	$lb_tiene  = false;
	$lb_existe = $this->uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$ls_estcla);
	if ($lb_existe)
	   {
		 $lb_valdelete = true;
		 
		 if ($ai_estmodest=='1')
		    {
			  $as_codestpro4 = $this->io_funcion ->uf_cerosizquierda('',25);
			  $as_codestpro5 = $this->io_funcion ->uf_cerosizquierda('',25);
				if (array_key_exists('session_activa',$_SESSION))
				{	 
					  $lb_detalle=$this->uf_select_cuenta_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$ls_estcla);
					  if(!$lb_detalle)
					  {
							$lb_valido=$this->uf_delete_cuenta_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$ls_estcla);
					  }
				}
			  $lb_valdelete = $this->uf_delete_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$ls_estcla,$aa_seguridad);	
			}
	     else
		    {
	          $lb_tiene  = $this->uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3);
			}
		 if (($lb_valdelete) && (!$lb_tiene))
	        {
		      $ls_sql = " DELETE FROM spg_ep3 ".
		                "  WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
		                "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND estcla='".$ls_estcla."'    ";
			  $this->io_sql->begin_transaction();
              $rs_data=$this->io_sql->execute($ls_sql);
              if ($rs_data===false)
	             {
	               $lb_valido=false;
 	               $this->is_msg_error="CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_spg_delete_estprog3; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	             }
              else
	             {
	               $lb_valido=true;
	               /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	               $ls_evento      = "DELETE";
		           $ls_descripcion = "Eliminó en el Codigo Programatico ".$as_codestpro3." en spg_ep3 asociado a ".$as_codestpro1." en spg_ep1 y a ".$as_codestpro2." en spg_ep2";
		           $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		           $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		           $aa_seguridad["ventanas"],$ls_descripcion);
		           /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	             }
 	        }	  		 
       }
return $lb_valido;
}
function uf_delete_cuenta_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
{

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cuenta_fuefin
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM spg_dt_fuentefinanciamiento ".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codfuefin='--'".
				"    AND codestpro1='".$as_codestpro1."'".
				"    AND codestpro2='".$as_codestpro2."'".
				"    AND codestpro3='".$as_codestpro3."'".
				"    AND codestpro4='".$as_codestpro4."'".
				"    AND codestpro5='".$as_codestpro5."'".
				"    AND estcla='".$as_estcla."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
            $this->is_msg_error="CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_delete_cuenta_fuefin; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	}
function uf_select_cuenta_fuefin($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla) 
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_cuenta_fuefin
	//	          Access:  public
	// 	        Arguments   
	//        $as_codemp:  Código de la Empresa.
	//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
	//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
	//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no el tercer codigo de tercer nivel, 
	//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido = false;
	$ls_sql=" SELECT codfuefin".
			"   FROM spg_cuenta_fuentefinanciamiento".
			"  WHERE codemp='".$as_codemp."'".
			"    AND codfuefin='--'".
			"    AND codestpro1='".$as_codestpro1."'".
			"    AND codestpro2='".$as_codestpro2."'".
			"    AND codestpro3='".$as_codestpro3."'".
			"    AND codestpro4='".$as_codestpro4."'".
			"    AND codestpro5='".$as_codestpro5."'".
			"    AND estcla='".$as_estcla."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_select_cuenta_fuefin; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
	}
	return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Clasificación. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = false;
  $ls_sql  = "SELECT * FROM spg_cuentas                                                      ".
             " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
             "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'     ";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	  {
		$lb_valido=false;
        $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
		else
		   {
			 $ls_sql = "SELECT codestpro3                                                              ".
			           "  FROM spg_ep4                                                                 ".
					   " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
			           "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."'     ";
			 $rs_data = $this->io_sql->select($ls_sql);
             if ($rs_data===false)
	            {
		          $lb_valido=false;
                  $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	            }
	         else
	            {
		          if ($row=$this->io_sql->fetch_row($rs_data))
		             {
					   $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
					   $lb_valido=true;
	 	             }
	            }
		   }
	  }
	return $lb_valido;	
}

function uf_delete_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$ls_estcla,$aa_seguridad)
{
	$lb_valido = false;
	$lb_existe = $this->uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$ls_estcla);
	if ($lb_existe)
	   {
		$as_codestpro4 = $this->io_funcion ->uf_cerosizquierda('',25);
		$as_codestpro5 = $this->io_funcion ->uf_cerosizquierda('',25);
		$lb_valido          = $this->uf_verificar_movimientos($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5);
		$lb_relacion_otros  = $this->uf_spg_check_relaciones("spg_dt_unidadadministrativa","*"," codemp='".$as_codemp."' AND spg_dt_unidadadministrativa.codestpro1='".$as_codestpro1."' AND spg_dt_unidadadministrativa.codestpro2='".$as_codestpro2."' AND spg_dt_unidadadministrativa.codestpro3='".$as_codestpro3."' AND spg_dt_unidadadministrativa.codestpro4='".$as_codestpro4."'  AND spg_dt_unidadadministrativa.codestpro5='".$as_codestpro5."' AND spg_dt_unidadadministrativa.estcla='".$ls_estcla."'" );
		$lb_relacion_otros2 = $this->uf_spg_check_relaciones("spg_cuentas","*"," codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'  AND estcla='".$ls_estcla."'" );				
		if ((!$lb_valido)&&(!$lb_relacion_otros2)&&(!$lb_relacion_otros))
		   {
			 $ls_sql  = "DELETE FROM spg_ep5                                                               ".
			            " WHERE  codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
					    "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
					    "        codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro4."'  AND estcla='".$ls_estcla."'";
						
			 $rs_data = $this->io_sql->execute($ls_sql);				
		     if ($rs_data===false)
			    {
				  $lb_valido=false;
				  $this->is_msg_error="Error Eliminando spg_ep5,".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			    }
			 else
			    {
				  $lb_valido      = true;
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				  $ls_evento      = "DELETE";
				  $ls_descripcion = "Elimino el codigo 00 asociado al codigo ".$as_codestpro1.",".$as_codestpro2.",".$as_codestpro3.",00,00 en la tabla spg_epg5";
				  $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				  $aa_seguridad["ventanas"],$ls_descripcion);
				  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				  $ls_sql    = "DELETE FROM spg_ep4 ".
				               " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
							   "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
							   "      codestpro4='".$as_codestpro4."' AND estcla='".$ls_estcla."'";
				  $rs_data = $this->io_sql->execute($ls_sql);
				  if ($rs_data===false)
				     {
					   $lb_valido=false;
					   $this->is_msg_error="Error Eliminando en spg_ep4,".$io_funcion->uf_convertirmsg($this->io_sql->message);						
				     }
				  else
				     {
					   $lb_valido       = true;
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					   $ls_evento      = "DELETE";
					   $ls_descripcion = "Elimino el codigo 00 asociado al codigo ".$as_codestpro1.",".$as_codestpro2.",".$as_codestpro3.",00 en la tabla spg_epg4";
					   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					   $aa_seguridad["ventanas"],$ls_descripcion);
					   /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
					 }						
			    }	
		   }
		else
		   {
			 $lb_valido=false;
		   	 $this->is_msg_error="Error al eliminar Codigo programatico, hay registros asociados a la estructura !!!";						
		   }
	   }
	return $lb_valido;
}

function uf_spg_check_relaciones($as_tabla,$as_campo,$as_where)
{
	$lb_valido = false;
	$ls_sql    = "SELECT ".$as_campo." FROM ".$as_tabla." WHERE ".$as_where;
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {  
	     $lb_valido          = false;
		 $this->is_msg_error = "No existe el codigo programatico".$io_funcion->uf_convertirmsg($this->io_sql->message);		
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
	   		  $this->io_sql->free_result($rs_data);
			}	
	   }	
   return $lb_valido;
}

function uf_verificar_movimientos($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
	$ls_sql = "SELECT * FROM spg_dt_cmp ".
	          " WHERE codemp='".$as_codemp."'        AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			  "       codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."'  AND codestpro5='".$as_codestpro5."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
	   }
	else
	   {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El codigo programatico posee movimientos relacionados";
		   }	
		else
		   {
			 $lb_valido=false;
		   }
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;
}

function uf_insert_niveles_adicionales($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_evento,$as_descripcion)
{
	$lb_valido            = false;
	$as_codestprog4       = "00";
	$as_codestprog5       = "00";
	$as_denestadicionales = "Ninguno";
	$lb_valido            =$this->uf_spg_insert_estprog4($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_denestadicionales,&$as_evento,&$as_descripcion);
	if ($lb_valido)
	   {
	     $lb_valido=$this->uf_spg_insert_estprog5($as_codestprog1,$as_codestprog2,$as_codestprog3,$as_codestprog4,$as_codestprog5,$as_denestadicionales,&$as_evento,&$as_descripcion);
	   }
	return $lb_valido;
}
}
?>
