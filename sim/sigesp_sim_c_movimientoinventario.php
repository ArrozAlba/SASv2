<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");

class sigesp_sim_c_movimientoinventario
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	function sigesp_sim_c_movimientoinventario()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
	}
	
	function uf_sim_select_movimiento($as_nummov,$as_fecmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un componente en la tabla de  sim_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_movimiento  ".
				  " WHERE nummov='".$as_nummov."'".
				  "   AND fecmov='".$as_fecmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_sim_select_movimiento


	function uf_sim_insert_movimiento(&$as_nummov,$ad_fecmov,$as_nomsol,$as_codusu,$aa_seguridad, $as_codtiend)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//                 $as_nomsol    // nombre del solicitante
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//                 $as_codtiend  // Codigo de la tienda 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  sim_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_emp=true;
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_tabla="sim_movimiento";
		$ls_columna="nummov";
		$as_nummov=$this->uf_generar_codigo($ls_emp,$ls_empresa,$ls_tabla,$ls_columna);
		
		$ls_sql="INSERT INTO sim_movimiento (codemp, nummov, fecmov, nomsol, codusu, codtiend)".
				" VALUES ('".$ls_empresa."','".$as_nummov."','".$ad_fecmov."','".$as_nomsol."','".$as_codusu."', '".$as_codtiend."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_insert_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			print $this->io_sql->message;
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	/*			$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sim_insert_movimiento
	 
	function uf_sim_select_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codart    // codigo de articulo
		//                 $as_codalm    // codigo de almacen
		//                 $as_opeinv    // codigo de operacion de inventario
		//                 $as_codprodoc // codigo de procedencia del documento
		//                 $as_numdoc    // numero de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica los detalles asociados a un  movimientos  en la tabla de  sim_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND nummov='". $as_nummov ."'".
				"   AND fecmov='". $ad_fecmov ."'".
				"   AND codart='". $as_codart ."'".
				"   AND codalm='". $as_codalm ."'".
				"   AND opeinv='". $as_opeinv ."'".
				"   AND codprodoc='". $as_codprodoc ."'".
				"   AND numdoc='". $as_numdoc ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_select_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_sim_select_dt_movimiento

	function uf_sim_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc,
										 $ai_canart,$ai_cosart,$as_promov,$as_numdocori,$ai_candesart,$ad_fecdesart,$aa_seguridad, $as_codtiend,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa					$ai_canart    // cantidad de articulos
		//                 $as_nummov    // numero de movimiento				$ai_cosart    // costo del articulo
		//                 $ad_fecmov    // fecha de movimiento					$as_promov    // procedencia del documento
		//                 $as_codart    // codigo de articulo					$as_numdocori // numero de documento original
		//                 $as_codalm    // codigo de almacen					$as_numdoc    // numero de documento
		//                 $as_opeinv    // codigo de operacion de inventario	$ad_fecdesart // fecha de el ultimo despacho del articulo
		//                 $as_codprodoc // codigo de procedencia del documento	$aa_seguridad // arreglo de registro de seguridad	
		//                 $ai_candesart // cantidad de articulos que restan por despachar
		//                 $as_codtiend  // codigo de la tienda
		//                 $as_codpro    // codigo del proveedor
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de movimiento generado en cualquiera de los procesos de inventario,
		//				   en la tabla de  sim_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sim_dt_movimiento (codemp,nummov,fecmov,codart,codalm,opeinv,codprodoc,numdoc,canart,cosart,promov,".
				"                               numdocori,candesart,fecdesart, codtiend, cod_pro)".
				"  VALUES ('".$as_codemp."','".$as_nummov."','".$ad_fecmov."','".$as_codart."','".$as_codalm."','".$as_opeinv."',".
				"          '".$as_codprodoc."','".$as_numdoc."',".$ai_canart.",".$ai_cosart.",'".$as_promov."','".$as_numdocori."',".
				"          ".$ai_candesart.",'".$ad_fecdesart."', '".$as_codtiend."', '".$as_codpro."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_insert_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			print $this->io_sql->message;
		}
		else
		{
				$lb_valido=true;
		}
		
		return $lb_valido;

	} // end function uf_sim_insert_dt_movimiento

    function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna)
	{ 
		//////////////////////////////////////////////////////////////////////////////////////////
		//	Function :  uf_generar_codigo
		//	  Access :  public
		//	Arguments:
		//           ab_empresa   // Si usara el campo empresa como filtro      
		//           as_codemp    // codigo de la empresa
		//           as_tabla     // Nombre de la tabla 
		//           as_campo     // nombre del campo que desea incrementar
		//           ai_length    // longitud del campo
		//	  Returns:	ls_codigo   // representa el codigo incrementado o generado
		//	Description:  Este m�todo genera el numero consecutivo del c�digo de
		//                cualquier tabla deseada
		///////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=$this->fun->uf_select_table($as_tabla);
		if ($lb_existe)
		   {
			  $lb_existe=$this->fun->uf_select_column($as_tabla,$as_columna);
			  if ($lb_existe)
			  {
				   $li_longitud=$this->fun->uf_longitud_columna_char($as_tabla,$as_columna) ;
				   if ($ab_empresa)
				   {	
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE codemp='".$as_codemp."' ORDER BY ".$as_columna." DESC";		
						  $rs_funciondb=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_funciondb))
						  { 
							  $codigo=$row[$as_columna];
							  settype($codigo,'int');                             // Asigna el tipo a la variable.
							  $codigo = $codigo + 1;                              // Le sumo uno al entero.
							  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
							  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						  }
						  else
						  {
							  $codigo="1";
							  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						  }
					}	
					else
					{
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE ".$as_columna." <>'0000000APERTURA'  ORDER BY ".$as_columna." DESC";		
						  $rs_funciondb=$this->io_sql->select($ls_sql);

						  if ($row=$this->io_sql->fetch_row($rs_funciondb))
						  { 
							   $codigo=$row[$as_columna];
							   settype($codigo,'int');                                          // Asigna el tipo a la variable.
							   $codigo = $codigo + 1;                                           // Le sumo uno al entero.
							   settype($codigo,'string');                                       // Lo convierto a varchar nuevamente.
							   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud); 
						   }   
						   else
						   {
							   $codigo="1";
							   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						   }
					}// SI NO TIENE CODIGO DE EMPRESA
				}
				else
				{
					$ls_codigo="";
					$this->is_msg_error="No existe el campo" ;
				}
		 }
		 else
		{
			$ls_codigo="";
			$this->is_msg_error="No existe la tabla	" ;
		}
	    return $ls_codigo;
	 } // end function

  function uf_sim_select_tienda($as_codemp, $as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tienda
		//         Access: public 
		//      Argumento: $as_codemp    // numero de movimiento
		//                 $as_codalm    // fecha de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un componente en la tabla de  sim_movimiento
		//	   Creado Por: Erlinda Tovar
		// Fecha Creaci�n: 13/08/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codtiend = '0000';
		$ls_sql = "SELECT codtiend FROM sim_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codalm='".$as_codalm."' LIMIT 1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_select_tienda ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codtiend=$row[codtiend];
			}
			else
			{
				$lb_valido=false;
				$ls_codtiend = '0000';
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_codtiend;
	} // end function uf_sim_select_tienda
	
	function uf_sim_select_movimiento_doc($as_numordcom,$as_numconrec,$as_fecmov,$as_opeinv,$as_nummov,$as_codpro,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un componente en la tabla de  sim_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_dt_movimiento  ".
				  " WHERE nummov='".$as_nummov."' ".
				  "   AND fecmov='".$as_fecmov."' ".
				  "   AND numdoc='".$as_numordcom."' ".
				  "   AND numdocori='".$as_numconrec."' ".
				  "   AND opeinv='".$as_opeinv."' ".
				  "   AND cod_pro='".$as_codpro."' ".
				  "   AND codtiend='".$as_codtie."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sim_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_sim_select_movimiento_doc

	
} // end class sigesp_sim_c_movimientoinventario
?>


					
