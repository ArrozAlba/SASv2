<?php
class sigesp_sob_class_obra
{
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

function sigesp_sob_class_obra()
{

	require_once("../shared/class_folder/sigesp_include.php");
	$io_siginc=new sigesp_include();
	$io_connect=$io_siginc->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$this->io_sql=new class_sql($io_connect);	
	require_once("../shared/class_folder/class_funciones.php");
	$this->io_function=new class_funciones();
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funsob=   new sigesp_sob_c_funciones_sob();
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();
}

	function uf_llenarcombo_tenencia(&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_tenencia	 //
	 //	Access:  public
	 //	Returns: arreglo con los tipos de tenencia
	 //	Description: Funcion que permite llenar el combo de tenencias, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 08/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT *
				 FROM sob_tenencia 
				 ORDER BY codten ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_llenarcombo_pais(&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_pais
	 //	Access:  public
	 //	Returns: arreglo con paises
	 //	Description: Funcion que permite llenar el combo de paises, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 16/06/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT codpai AS codpai,despai AS despai
				 FROM sigesp_pais
				 ORDER BY codpai ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_llenarcombo_pais".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_llenarcombo_estado($as_codpai,&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_estado	 
	 //	Access:  public
	 //	Returns: arreglo con estados
	 //	Description: Funcion que permite llenar el combo de estados, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 08/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT codest AS codest,desest AS desest
				 FROM sigesp_estados
				 WHERE codpai='$as_codpai' ORDER BY codest ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_llenarcombo_municipio($as_codestado,&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_municipio
	 //	Access:  public
	 //	Returns: arreglo con municipios
	 //	Description: Funcion que permite llenar el combo de municipios, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 09/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codmun as codmun,denmun as denmun
				 FROM sigesp_municipio
				 WHERE codpai='058' AND codest='".$as_codestado."'
				 ORDER BY codmun ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_llenarcombo_parroquia($as_codestado,$as_codmunicipio,&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_parroquia
	 //	Access:  public
	 //	Returns: arreglo con parroquias
	 //	Description: Funcion que permite llenar el combo de parroquias, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 09/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codpar as codpar,denpar as denpar
				 FROM sigesp_parroquia
				 WHERE codpai='058' AND codest='".$as_codestado."' AND codmun='".$as_codmunicipio."'
				 ORDER BY codmun ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
		
	function uf_llenarcombo_comunidad($as_codestado,$as_codmunicipio,$as_codparroquia,&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_comunidad
	 //	Access:  public
	 //	Returns: arreglo con comunidades
	 //	Description: Funcion que permite llenar el combo de comunidad, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 09/03/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codcom as codcom,nomcom as nomcom
				 FROM sigesp_comunidad
				 WHERE codpai='058' AND codest='".$as_codestado."' AND codmun='".$as_codmunicipio."' AND codpar='".$as_codparroquia."'
				ORDER BY codcom ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_obra ($as_codobra,&$aa_data)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_obra
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de verificar si existe o no la obra
		//  Fecha:          13/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT o.*,e.desest,m.denmun,pa.denpar,co.nomcom as nomcom
				  FROM sob_obra o, sigesp_estados e, sigesp_municipio m, sigesp_parroquia pa,sigesp_comunidad co
				  WHERE o.codemp='".$ls_codemp."' AND o.codobr='".$as_codobra."' AND o.codest=co.codest AND o.codmun=co.codmun 
				  AND o.codpar=co.codpar AND o.codcom=co.codcom  AND o.codest=pa.codest AND o.codmun=pa.codmun
				  AND o.codpar=pa.codpar AND o.codest=m.codest AND o.codmun=m.codmun AND o.codest=e.codest";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_select_obra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data=array();
			}
		}
		return $lb_valido;
	}
	
	function uf_select_pais (&$as_codpai)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_pais
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar el codigo de Venezuela
		//  Fecha:          13/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codpai 
				FROM sigesp_pais 
				WHERE despai='VENEZUELA'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select pais".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$as_codpai=$aa_data["codpai"][1];
			}
			else
			{
				$as_codpai="";
			}
		}
		return $lb_valido;
	}
	
	
	function uf_guardar_obra(&$as_codobr,$as_codten,$as_codtipest,$as_codest,$as_codmun,$as_codpar,$as_codcom,$as_codsiscon,$as_codpro,$as_codtob,$as_desobr,
							 $as_dirobr,$as_obsobr,$as_resobr,$ad_feciniobr,$ad_fecfinobr,$ai_monto,$ad_feccreobr,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_obra
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar la obra.
		//  Fecha:          13/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_monto=$ai_monto;
		$ai_monto=str_replace(",","-",$ai_monto);
		$ai_monto=str_replace(".","",$ai_monto);
		$ai_monto=str_replace("-",".",$ai_monto);
		$lb_valpais=$this->uf_select_pais($ls_codpai);
		$this->io_keygen->uf_verificar_numero_generado("SOB","sob_obra","codobr","SOBASI",6,"","","",&$as_codobr);
		$ls_sql="INSERT INTO sob_obra (codemp,codobr,codpai,codest,codmun,codpar,codcom,codten,codtipest,codsiscon,desobr,dirobr,obsobr,resobr,feciniobr,fecfinobr,monto,codtob,codpro,feccreobr,staobr)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$ls_codpai."','".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_codcom."','".$as_codten."','".$as_codtipest."','".$as_codsiscon."','".$as_desobr."','".$as_dirobr."','".$as_obsobr."','".$as_resobr."','".$ad_feciniobr."','".$ad_fecfinobr."','".$ai_monto."','".$as_codtob."','".$as_codpro."','".$ad_feccreobr."',1)";					
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_guardar_obra(&$as_codobr,$as_codten,$as_codtipest,$as_codest,$as_codmun,$as_codpar,$as_codcom,$as_codsiscon,
													   $as_codpro,$as_codtob,$as_desobr,$as_dirobr,$as_obsobr,$as_resobr,$ad_feciniobr,$ad_fecfinobr,
													   $ai_monto,$ad_feccreobr,$aa_seguridad);
				}
				else
				{
					$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_obra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
					//print "Error en metodo uf_guardar_obra".$this->io_function->uf_convertirmsg($this->io_sql->message);
				}
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Obra ".$as_codobr.", de monto ".$ls_monto." Asociada a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_valido=true;				
		
		}		
		return $lb_valido;
	}
	
	function uf_update_obra($as_codobr,$as_codten,$as_codtipest,$as_codest,$as_codmun,$as_codpar,$as_codcom,$as_codsiscon,$as_codpro,$as_codtob,$as_desobr,$as_dirobr,$as_obsobr,$as_resobr,$ad_feciniobr,$ad_fecfinobr,$ai_monto,$ad_feccreobr,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_obra
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar la obra.
		//  Fecha:          15/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_monto=$ai_monto;
		$ai_monto=str_replace(",","-",$ai_monto);
		$ai_monto=str_replace(".","",$ai_monto);
		$ai_monto=str_replace("-",".",$ai_monto);
		$ls_sql="UPDATE sob_obra 
					 SET codest='".$as_codest."',codmun='".$as_codmun."',codpar='".$as_codpar."',
					 codcom='".$as_codcom."',codten='".$as_codten."',codtipest='".$as_codtipest."',
					 codsiscon='".$as_codsiscon."',desobr='".$as_desobr."',dirobr='".$as_dirobr."',
					 obsobr='".$as_obsobr."',resobr='".$as_resobr."',feciniobr='".$ad_feciniobr."',
					 fecfinobr='".$ad_fecfinobr."',monto='".$ai_monto."',codtob='".$as_codtob."',
					 codpro='".$as_codpro."',feccreobr='".$ad_feccreobr."'
					 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";		
		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_obra".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Obra ".$as_codobr.", de monto ".$ls_monto." Asociada a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$this->io_sql->commit();
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	function uf_delete_obra ($as_codobr,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:       uf_delete_obra
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar la cabecera de una obra
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_obra
					WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo uf_delete_obra".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Obra ".$as_codobr." Asociada a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
		}
		return $lb_valido;		
	}
	
	function uf_select_partidas ($as_codobr,&$aa_data,&$ai_rows)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_partidas
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el arreglo de partidas si existen registros en bd
		//	Description:	Funcion que se encarga de verificar si existen partidas para una
		//                  obra y retorna el arreglo con los mismos
		//  Fecha:          15/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  po.codemp,po.codpar,po.codobr, p.nompar,u.nomuni,p.prepar,po.canparobr
				 FROM sob_partidaobra po, sob_partida p, sob_unidad u
				 WHERE po.codemp='".$ls_codemp."' AND po.codobr='".$as_codobr."' AND po.codpar=p.codpar AND p.coduni=u.coduni ORDER BY po.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_partida ($as_codobr,$as_partida)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_partida
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true si la partida existen en bd
		//	Description:	Funcion que se encarga de verificar si existe una partida asociada a una
		//                  obra 
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  codpar
				 FROM sob_partidaobra
				 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpar='".$as_partida."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select_partida".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;
	}
	
	function uf_guardar_dtpartidas($as_codobr,$as_codpar,$as_canpar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_dtpartidas
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar las partidas de la obra.
		//  Fecha:          14/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_partidaobra (codemp,codobr,codpar,canparobr)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codpar."','".$as_canpar."')";		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el detalle de la Partida de codigo ".$as_codpar.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$lb_valido=true;
		}		
		return $lb_valido;
	}	
	
	
	function uf_delete_dtpartidas ($as_codobr,$as_codpar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:    - uf_delete_dtpartidas
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar las partidas de la obra.
		//  Fecha:          15/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_partidaobra
					WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el detalle de la Partida de Codigo ".$as_codpar.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
	
	function uf_update_dtpartidas($as_codobr,$aa_partidasnuevas,$ai_totalfilas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_dtpartidas
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las partidas de la obra que han sido modificadas.
		//  Fecha:          15/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$this->uf_select_partidas ($as_codobr,$la_partidasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			$lb_update=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codobr"][$li_j] == $as_codobr) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
				{
					
					if($la_partidasviejas["canparobr"][$li_j] != $aa_partidasnuevas["canpar"][$li_i])
					{
						$lb_update=true;	  
					}
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_dtpartidas($as_codobr,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["canpar"][$li_i],$aa_seguridad);
			}
			if($lb_update)
			{
			  $lb_valido=$this->uf_update_cantidadpartida($as_codobr,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["canpar"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codobr"][$li_j] == $as_codobr) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_dtpartidas($as_codobr,$la_partidasviejas["codpar"][$li_j],$aa_seguridad);
			}
		}			
		return $lb_valido;
	}
	
function uf_update_cantidadpartida ($as_codobr,$as_codpar,$ad_cantidad,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_cantidadpartida
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar la cantidad de una partida asociada a una obra.
		//  Fecha:          21/04/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_partidaobra
				SET canparobr='".$ad_cantidad."'
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_cantidadpartida ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad del detalle de la partida ".$as_codpar.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	
	function uf_select_fuentesfinanciamiento ($as_codobr,&$aa_data,&$ai_rows)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_fuentesfinanciamiento
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true y el arreglo de fuentes de financiamiento si existen registros en bd
		//	Description:	Funcion que se encarga de verificar si existen fuentes de financiamiento para una
		//                  obra y retorna el arreglo con los mismos
		//  Fecha:          15/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  ffo.codemp,ffo.codobr,ffo.codfuefin, ff.denfuefin,ffo.monto
				 FROM sob_fuentefinanciamientoobra ffo, sigesp_fuentefinanciamiento ff
				 WHERE ffo.codemp='".$ls_codemp."' AND ffo.codobr='".$as_codobr."' AND ffo.codfuefin=ff.codfuefin ORDER BY ffo.codfuefin ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$rs_datos=$rs_data;
		if($rs_data===false)
		{
			
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_datos);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_fuentefinanciamiento($as_codobr,$as_codfuefin)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_fuentefinanciamiento
		// Access:			public
		//	Returns:		Boolean,arreglo. Retorna true si la fuente de financiamiento existe en bd
		//	Description:	Funcion que se encarga de verificar si existe una fuente de financiamiento asociada a una
		//                  obra 
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  codfuefin
				 FROM sob_fuentefinanciamientoobra
				 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codfuefin='".$as_codfuefin."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select_fuentefinanciamiento".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;
	}
	
	
	function uf_guardar_dtfuentesfinanciamiento($as_codobr,$as_codfuefin,$ad_monto,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_dtfuentesfinanciamiento
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar las fuentes de financiamiento de la obra.
		//  Fecha:          14/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=str_replace(",","-",$ad_monto);
		$ad_monto=str_replace(".","",$ad_monto);
		$ad_monto=str_replace("-",".",$ad_monto);
		$ls_sql="INSERT INTO sob_fuentefinanciamientoobra (codemp,codobr,codfuefin,monto)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codfuefin."','".$ad_monto."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_dtfuentesfinanciamiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de Fuente de Financiamiento de codigo ".$as_codfuefin.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	function uf_delete_dtfuentesfinanciamiento ($as_codobr,$as_codfuefin,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//  Function:    - uf_delete_dtfuentesfinanciamiento
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar una fuente de financiamiento asociada a una obra.
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_fuentefinanciamientoobra
					WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codfuefin='".$as_codfuefin."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_dtfuentefinanciamiento".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el detalle de la Fuente de Financiamiento ".$as_codfuefin.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
	
	function uf_update_dtfuentesfinanciamiento($as_codobr,$aa_fuentesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_dtfuentesfinanciamiento
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar las fuentesde financiamiento de la obra que han sido modificadas.
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$this->uf_select_fuentesfinanciamiento ($as_codobr,$la_fuentesviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;		
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			$lb_update=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_fuentesviejas["codemp"][$li_j] == $ls_codemp) && ($la_fuentesviejas["codobr"][$li_j] == $as_codobr) &&  ($la_fuentesviejas["codfuefin"][$li_j] == $aa_fuentesnuevas["codfuefin"][$li_i]))
				{
					if ($la_fuentesviejas["monto"][$li_j] != $aa_fuentesnuevas["monfuefin"][$li_i])
					{
						$lb_update=true;
					}
				
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_dtfuentesfinanciamiento($as_codobr,$aa_fuentesnuevas["codfuefin"][$li_i],$aa_fuentesnuevas["monfuefin"][$li_i],$aa_seguridad);
			}
			if	($lb_update)
			{
				$lb_valido=$this->uf_update_montofuentefinanciamiento ($as_codobr,$aa_fuentesnuevas["codfuefin"][$li_i],$aa_fuentesnuevas["monfuefin"][$li_i],$aa_seguridad);
			}		
			
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_fuentesviejas["codemp"][$li_j] == $ls_codemp) && ($la_fuentesviejas["codobr"][$li_j] == $as_codobr) &&  ($la_fuentesviejas["codfuefin"][$li_j] == $aa_fuentesnuevas["codfuefin"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_dtfuentesfinanciamiento($as_codobr,$la_fuentesviejas["codfuefin"][$li_j],$aa_seguridad);
				
			}			
		}			
		return $lb_valido;
	}	
	
	function uf_update_montofuentefinanciamiento ($as_codobr,$as_codfuefin,$ad_monto,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_montofuentefinanciamiento
		//  Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar el monto de una fuente de 
		//                   financiamiento asociada a una obra.
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=str_replace(",","-",$ad_monto);
		$ad_monto=str_replace(".","",$ad_monto);
		$ad_monto=str_replace("-",".",$ad_monto);
		$ls_sql="UPDATE sob_fuentefinanciamientoobra
				SET monto='".$ad_monto."'
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codfuefin='".$as_codfuefin."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_montofuentefinanciamiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el monto del detalle de la Fuente de Financiamiento ".$as_codfuefin.", de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$this->io_sql->commit();
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	
	
	function uf_tieneasignacion ($as_codobr)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_tieneasignacion
		// Access:			public
		//	Returns:		Boolean, Retorna true si la obra tiene una asignacion, 
		//                  de lo contrario retorna falso
		//	Description:	Funcion que se encarga de determinar si una obra tiene o no asociada 
		//                  una asignacion
		//  Fecha:          16/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=" SELECT codobr
				 FROM sob_asignacion
				 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_tieneasignacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}		
		}		
		return $lb_valido;	
	}	

//*******************************************************************************************************//
//							Funciones relacionadas con el estado de la obra                              //
//*******************************************************************************************************//

function uf_update_estado($as_codobr,$ai_estado,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el estado de la obra
	//  Fecha:          24/03/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_obra
				 SET staobr='".$ai_estado."'
				 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";		
	
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_estadoobra".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
			$ls_estado=$this->io_funsob->uf_convertir_numeroestado($ai_estado);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estado de la Obra ".$as_codobr.", a ".$ai_estado." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$this->io_sql->commit();
			$lb_valido=true;
	}		
	return $lb_valido;
}	

function uf_select_estado ($as_codobr,&$estado)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de retornar el estado de la obra
	//  Fecha:          30/03/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT staobr
			 FROM sob_obra
			 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select estado obra".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$estado=$la_row["staobr"];
			$lb_valido=true;
		}		
	}
	return $lb_valido;
}

function uf_contabilizado ($as_sql)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_contabilizado
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          21/08/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql=$as_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en uf_contabilizado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$li_estado=$la_row["estspgscg"];
		}		
	}	
	return $li_estado;
}
}
?>
