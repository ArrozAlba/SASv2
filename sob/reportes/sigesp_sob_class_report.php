<?php
class sigesp_sob_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->rs_data="";
		$this->rs_data_detalle="";
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$this->ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$this->ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$this->ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$this->ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	
	}// end function sigesp_sob_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_obra($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_obra
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_obra.codobr, sob_obra.desobr,sob_obra.dirobr,sob_obra.obsobr,sob_obra.resobr,sob_obra.feciniobr, ".
				"       sob_obra.fecfinobr,sob_obra.monto,sob_obra.feccreobr,sob_tenencia.nomten,sob_tipoestructura.nomtipest, ".
				"       sob_sistemaconstructivo.nomsiscon,sob_propietario.nompro,sob_tipoobra.nomtob,sob_obra.staobr, ".
				"		sigesp_pais.despai, sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, ".
				"		sigesp_comunidad.nomcom ".
				"  FROM sob_obra ".
				" INNER JOIN sob_tenencia  ".
				"    ON sob_obra.codemp='".$this->ls_codemp."'  ".
				"   AND sob_obra.codobr='".$as_codobr."'  ".
				"   AND sob_obra.codten=sob_tenencia.codten ".
				" INNER JOIN sob_tipoestructura ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_tipoestructura.codemp ".
				"   AND sob_obra.codtipest=sob_tipoestructura.codtipest ".
				" INNER JOIN sob_sistemaconstructivo ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_sistemaconstructivo.codemp ".
				"   AND sob_obra.codsiscon=sob_sistemaconstructivo.codsiscon ".
				" INNER JOIN sob_propietario ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codemp=sob_propietario.codemp ".
				"   AND sob_obra.codpro=sob_propietario.codpro ".
				" INNER JOIN sob_tipoobra ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ". 
				"   AND sob_obra.codemp=sob_tipoobra.codemp ".
				"   AND sob_obra.codtob=sob_tipoobra.codtob ".
				" INNER JOIN sigesp_pais ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_pais.codpai ".
				" INNER JOIN sigesp_estados ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_estados.codpai ".
				"   AND sob_obra.codest=sigesp_estados.codest ".
				" INNER JOIN sigesp_municipio ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_municipio.codpai ".
				"   AND sob_obra.codest=sigesp_municipio.codest ".
				"   AND sob_obra.codmun=sigesp_municipio.codmun ".
				" INNER JOIN sigesp_parroquia ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_parroquia.codpai ".
				"   AND sob_obra.codest=sigesp_parroquia.codest ".
				"   AND sob_obra.codmun=sigesp_parroquia.codmun ".
				"   AND sob_obra.codpar=sigesp_parroquia.codpar ".
				" INNER JOIN sigesp_comunidad ".
				"    ON sob_obra.codemp='".$this->ls_codemp."' ". 
				"   AND sob_obra.codobr='".$as_codobr."' ".
				"   AND sob_obra.codpai=sigesp_comunidad.codpai ".
				"   AND sob_obra.codest=sigesp_comunidad.codest ".
				"   AND sob_obra.codmun=sigesp_comunidad.codmun ".
				"   AND sob_obra.codpar=sigesp_comunidad.codpar ".
				"   AND sob_obra.codcom=sigesp_comunidad.codcom ".
				" WHERE sob_obra.codemp='".$this->ls_codemp."' ".
				"   AND sob_obra.codobr='".$as_codobr."' ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_obra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_select_obra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_partidas($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_partidas
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las partidas de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_partidaobra.codpar,sob_partida.nompar,sob_unidad.nomuni,sob_partida.prepar,sob_partidaobra.canparobr ".
				"  FROM sob_partidaobra   ".
				" INNER JOIN (sob_partida ".
				" 				INNER JOIN sob_unidad ".
				"    			   ON sob_partida.codemp='".$this->ls_codemp."' ".
				"  				  AND sob_partida.codemp=sob_unidad.codemp ".
				"  				  AND sob_partida.coduni=sob_unidad.coduni) ".
				"    ON sob_partidaobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_partidaobra.codobr='".$as_codobr."' ".
				"   AND sob_partidaobra.codemp=sob_partida.codemp ".
				"   AND sob_partidaobra.codpar=sob_partida.codpar ".
				" WHERE sob_partidaobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_partidaobra.codobr='".$as_codobr."' ".
				" ORDER BY sob_partidaobra.codpar ASC";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_partidas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_partidas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fuentesfinancimiento($as_codobr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_partidas
		//         Access: public 
		//	    Arguments: as_codobr     // Còdigo de la Obra
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las fuentes de financimiento de una obra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_fuentefinanciamientoobra.codfuefin, sigesp_fuentefinanciamiento.denfuefin,sob_fuentefinanciamientoobra.monto ".
				"  FROM sob_fuentefinanciamientoobra  ".
				" INNER JOIN sigesp_fuentefinanciamiento  ".
				"    ON sob_fuentefinanciamientoobra.codemp='".$this->ls_codemp."' ".
				"   AND sob_fuentefinanciamientoobra.codobr='".$as_codobr."'".
				"   AND sob_fuentefinanciamientoobra.codemp = sigesp_fuentefinanciamiento.codemp ".
				"   AND sob_fuentefinanciamientoobra.codfuefin = sigesp_fuentefinanciamiento.codfuefin ".
				" WHERE sob_fuentefinanciamientoobra.codemp='".$this->ls_codemp."'  ".
				"   AND sob_fuentefinanciamientoobra.codobr='".$as_codobr."'  ".
				" ORDER BY sob_fuentefinanciamientoobra.codfuefin ASC";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_fuentesfinancimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>