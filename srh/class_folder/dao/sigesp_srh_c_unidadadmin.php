<?php

class sigesp_srh_c_unidadadmin
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_unidadadmin($path)
	{   require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		
		
	}
	
	
	
function uf_srh_buscar_unidadadmin($as_denuniadm, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_unidadadmin
		//         Access: private
		//      Argumento: $as_coduniadm  // codigo de la uniad administrativa
		//				   $as_denuniadm  // denominacion de la uniad administrativa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una unidad administrativa para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 15/05/2008							Fecha Última Modificación: 15/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		if ($as_tipo=='1')
		{
		  $ls_coddestino="txtcoduniadm1";
		  $ls_dendestino="";
		}
		elseif ($as_tipo=='2')  
		{
		  $ls_coddestino="txtcoduniadm2";
		  $ls_dendestino="";
		}
		elseif ($as_tipo=='3')  
		{
		  $ls_coddestino="txtcoduniadm";
		  $ls_dendestino="txtdenuniadm";
		}
		
		
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM sno_unidadadmin".
				" WHERE desuniadm like '".$as_denuniadm."' ".
			   " ORDER BY minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm";
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->unidadadmin MÉTODO->uf_srh_buscar_unidadadmin( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    $ls_coduniadm= ($row["minorguniadm"].'-'.$row["ofiuniadm"].'-'.$row["uniuniadm"].'-'.$row["depuniadm"].'-'.$row["prouniadm"]);
				$ls_denuniadm= htmlentities ($row["desuniadm"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$ls_coduniadm);
					
									
					$cell = $row_->appendChild($dom->createElement('cell')); 
					$cell->appendChild($dom->createTextNode($ls_coduniadm." ^javascript:aceptar(\"$ls_coduniadm\", \"$ls_coddestino\", \"$ls_denuniadm\", \"$ls_dendestino\");^_self"));
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(htmlentities($row['desuniadm'])));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function uf_srh_buscar_unidadadmin
	

}// end   class sigesp_srh_c_unidadadmin
?>