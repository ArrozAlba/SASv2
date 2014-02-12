<?php
class sigesp_soc_c_aprobacion_orden_compra
{
  function sigesp_soc_c_aprobacion_orden_compra($as_path)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_aprobacion_orden_compra
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 29/05/2007 								Fecha Última Modificación : 29/05/2007 
	////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$io_include			= new sigesp_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
  }

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ordenes_compra($as_numordcom,$as_codpro,$ad_fecdes,$ad_fechas,$as_tipordcom,$as_tipope)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenes_compra
		//		   Access: public
		//		 Argument: 
		//   $as_numordcom //Número de la Orden de Compra.
		//      $as_codpro //Código del Proveedor asociado a la Orden de Compra.
		//      $ad_fecdes //Fecha a partir del cual comenzará la búsqueda de las Ordenes de Compra. 
		//      $ad_fechas //Fecha hasta el cual comenzará la búsqueda de las Ordenes de Compra. 
		//   $as_tipordcom //Tipo de la Orden de Compra B=Bienes , S=Servicios.
		//      $as_tipope //Tipo de la Operación a ejecutar A=Aprobacion, R=Reverso de la Aprobación.
		//	  Description: Función que busca las ordenes de compra que esten dispuestas para Aprobacion/Reverso.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 16/05/2007								Fecha Última Modificación : 16/05/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ls_straux = "";
		
        if (!empty($as_numordcom))
		   {
		     $ls_straux = " AND soc_ordencompra.numordcom LIKE '%".$as_numordcom."%'";
		   } 
		if (!empty($as_codpro))
		   {  
		     $ls_straux = $ls_straux." AND soc_ordencompra.cod_pro LIKE '%".$as_codpro."%'";
		   }
		if (!empty($ad_fecdes) && !empty($ad_fechas))
		   {  
		     $ld_fecdes = $this->io_funciones->uf_convertirdatetobd($ad_fecdes);
			 $ld_fechas = $this->io_funciones->uf_convertirdatetobd($ad_fechas);
			 $ls_straux = $ls_straux." AND soc_ordencompra.fecordcom BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."'";
		   }
		if ($as_tipordcom!='-')
		   {  
		     $ls_straux = $ls_straux." AND soc_ordencompra.estcondat='".$as_tipordcom."'";
		   }
		if ($as_tipope=='A')//Aprobacion
		   {  
		     $ls_straux = $ls_straux." AND soc_ordencompra.estapro='0'";
		   }
		elseif($as_tipope=='R')//Reverso.
		   {
			 $ls_straux = $ls_straux." AND soc_ordencompra.estapro='1'";
		   }
		$ls_sql ="SELECT soc_ordencompra.numordcom,soc_ordencompra.fecordcom,".
		         "       soc_ordencompra.estcondat,soc_ordencompra.montot,   ".
				 "       soc_ordencompra.obscom,soc_ordencompra.fecaprord,   ".
				 "       soc_ordencompra.cod_pro,rpc_proveedor.nompro        ".
				 "  FROM soc_ordencompra, rpc_proveedor                      ".
		         " WHERE soc_ordencompra.codemp='".$this->ls_codemp."'       ".
				 "   AND soc_ordencompra.numordcom<>'000000000000000'  		 ".
				 "   AND soc_ordencompra.estcom='1'                          ".
				 "   $ls_straux											     ".
				 "   AND rpc_proveedor.codemp=soc_ordencompra.codemp   		 ".
				 "   AND rpc_proveedor.cod_pro=soc_ordencompra.cod_pro 		 ".
				 " ORDER BY soc_ordencompra.numordcom ASC              		 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_orden_compra.php->MÉTODO->uf_load_ordenes_compra.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_ordenes_compra
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($ai_totrows,$as_tipope,$ad_fecope,$aa_seguridad)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_guardar
	//		   Access: public
	//		 Argument: 
	//     $ai_totrows //Total de elementos cargados en el Grid de la Ordenes de Compra.
	//      $as_tipope //Tipo de la Operación a realizar A=Aprobación, R=Reverso de Aprobación.
	//      $ad_fecope //Fecha en la cual se ejecuta la Operación.
	//   $aa_seguridad //Arreglo de seguridad cargado de la informacion de usuario y pantalla.
	//	  Description: Función que recorre el grid de las ordenes de compra que esten dispuestas para Aprobacion/Reverso.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creación: 16/05/2007								Fecha Última Modificación : 16/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $lb_valido = true;
	  $this->io_sql->begin_transaction();
	  for ($i=1;$i<=$ai_totrows;$i++)
		  {
			if (array_key_exists("chk".$i,$_POST))
			   {
				 $ls_numordcom = $_POST["txtnumord".$i];
				 $ls_tipordcom = $_POST["txttipordcom".$i];
				 $ls_codpro    = $_POST["hidcodpro".$i];
				 $lb_valido    = $this->uf_update_estatus_aprobacion($ls_numordcom,$ls_codpro,$as_tipope,$ls_tipordcom,$ad_fecope,$aa_seguridad);
				 if (!$lb_valido)
					{
					  break;
					}
			   }
		  }
	   if ($lb_valido)
		  {
			$this->io_sql->commit();
			$this->io_mensajes->message("Operación realizada con Éxito !!!");
		    $this->io_sql->close();
		  }
	   else 
		  {
			$this->io_sql->rollback();
			$this->io_mensajes->message("Error Operación !!!");
		    $this->io_sql->close();
		  }
	}// end function uf_guardar

	function uf_update_estatus_aprobacion($as_numordcom,$as_codpro,$as_tipope,$as_tipordcom,$ad_fecope,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_aprobacion
	//		   Access: public
	//		 Argument: 
	//   $as_numordcom //Número de la Orden de Compra.
	//      $as_codpro //Código del Proveedor asociado a la Orden de Compra.
	//      $as_tipope //Tipo de la Operación a ejecutar A=Aprobacion, R=Reverso de la Aprobación.
	//   $as_tipordcom //Tipo de la Orden de Compra B=Bienes , S=Servicios.
	//      $ad_fecope //Fecha en la cual se ejecuta la Operación.
	//   $aa_seguridad //Arreglo de seguridad cargado de la informacion de usuario y pantalla.
	//	  Description: Función que recorre el grid de las ordenes de compra que esten dispuestas para Aprobacion/Reverso.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creación: 02/06/2007								Fecha Última Modificación : 02/06/2007
	//////////////////////////////////////////////////////////////////////////////
	
  	  $lb_valido    = true;
	  $ls_tipordcom = "";
	  if ($as_tipope=='A')
		 {
		   $li_aprest = 1;//Colocar en Aprobada
		   $li_estapr = 0;//Cuando este en No Aprobada.
		   $ad_fecope = $this->io_funciones->uf_convertirdatetobd($ad_fecope);
		 }
	  elseif($as_tipope=='R')
		 {
		   $ad_fecope = '1900-01-01';
		   $li_aprest = 0;//Colocar en No Aprobada.
		   $li_estapr = 1;//Cuando este Aprobada.
		 }
	  if ($as_tipordcom=='Bienes')
		 {
		   $ls_tipordcom = 'B';
		 }
	  elseif($as_tipordcom=='Servicios')
		 {
		   $ls_tipordcom = 'S';
		 }
	  $ls_nomusu = $aa_seguridad["logusr"];
	  $ls_sql    = "UPDATE soc_ordencompra
					   SET estapro='".$li_aprest."', fecaprord='".$ad_fecope."', codusuapr = '".$ls_nomusu."'
					 WHERE codemp='".$this->ls_codemp."'
					   AND numordcom='".$as_numordcom."'
					   AND cod_pro='".$as_codpro."'
					   AND estcondat='".$ls_tipordcom."'
					   AND estapro='".$li_estapr."'
					   AND estcom='1'";//print $ls_sql;
	  $rs_data   = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false;
		   $this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_orden_compra; METODO->uf_update_estatus_aprobacion;ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));	
		 }
	  else
		 {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Actualizó el Estatus de la Orden de Compra ".$as_numordcom." en ".$li_aprest." del proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 }
	  return $lb_valido;
	}// end function uf_update_estatus_aprobacion
}
?>