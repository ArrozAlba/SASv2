<?php
class sigesp_scb_c_chequera
{
	var $io_sql;
	var $is_msg_error;
	var $fun;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	var $dat;
	var $fec;
	
	function sigesp_scb_c_chequera($aa_security)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_fecha.php");
		$this->fec=new class_fecha();
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->fun=new class_funciones();
		$this->is_empresa = $aa_security[1];
		$this->is_sistema = $aa_security[2];
		$this->is_logusr  = $aa_security[3];	
		$this->is_ventana = $aa_security[4];
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->dat=$_SESSION["la_empresa"];	
	}

	function uf_select_chequera($as_codemp,$as_codban,$as_ctaban,$as_chequera)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_chequera
		//		   Access: private
		//	    Arguments: 
		//      $as_codemp = Código de la Empresa.
		//      $as_codban = Código de la Entidad Bancaria.
		//      $as_ctaban = Número de Registro de la Cuenta Bancaria.
		//    $as_chequera = Número de Identificación de la Chequera.
		//	      Returns: lb_existe: True si existe la Chequera ó False por el contrario.
		//	  Description: Función que verifica la existencia del numero de chequera en ese Banco para ese Cuenta.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/03/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_existe = false;
		$ls_sql = "SELECT numchequera 
					 FROM scb_cheques 
					WHERE codemp='".$as_codemp."'
					  AND codban='".$as_codban."'
					  AND ctaban='".$as_ctaban."'
					  AND numchequera='".$as_chequera."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido = false;
			 $this->is_msg_error = "Error:CLASS->sigesp_scb_c_chequera;Método:uf_select_chequera();.".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
		   }
		else
		   {
			 if ($row=$this->io_sql->fetch_row($rs_data))
				{
				  $lb_existe = true;
				}
		   }
		return $lb_existe;
	}//Function uf_select_chequera.

	function uf_select_cheques($as_codban,$as_ctaban,$as_cheque,$as_chequera,$as_status)
	{
	  $ls_codemp = $this->dat["codemp"];
	  $ls_cadena = "SELECT estche 
	                  FROM scb_cheques 
				     WHERE codemp='".$ls_codemp."' 
				       AND codban='".$as_codban."' 
					   AND ctaban='".$as_ctaban."' 
					   AND numche='".$as_cheque."'";
	  $rs_data=$this->io_sql->select($ls_cadena);
	  if ($rs_data===false)
		 {
		   $this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->io_sql->message);
		   $lb_valido=false;
		   $as_status=0;
		 }
	  else
		 {
		   if ($row=$this->io_sql->fetch_row($rs_data))
			  {
			    $lb_valido=true;
			    $as_status=$row["estche"];
			    $this->is_msg_error="Numero de Cheque ya existe";
			  }
		   else
			  {
			    $lb_valido=false;
			    $as_status=0;
			    $this->is_msg_error="No encontro registro";
			  }
		   $this->io_sql->free_result($rs_data);
	     }
	  return $lb_valido;
	}
	
    function uf_guardar_cheques($as_codemp,$as_codban,$as_ctaban,$as_tipcta,$as_chequera,$as_numche,$as_codusu,$ai_estche,$ai_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_cheques
		//		   Access: private
		//	    Arguments: 
		//      $as_codemp = Código de la Empresa.
		//      $as_codban = Código de la Entidad Bancaria.
		//      $as_ctaban = Número de Registro de la Cuenta Bancaria.
		//      $as_tipcta = Identificación del Tipo de la Cuenta (Ahorro,Corriente).
		//    $as_chequera = Número de Identificación de la Chequera.
		//      $as_numche = Número del Cheque asociado a la Chequera.
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que registra los cheques asociados para una Chequera de un Banco para una Cuenta Bancaria.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/03/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido = true;
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO scb_cheques(codemp,codban,ctaban,numche,estche,numchequera,codusu,orden) 
								 VALUES('".$as_codemp."','".trim($as_codban)."','".trim($as_ctaban)."','".$as_numche."',".$ai_estche.",'".$as_chequera."','".$as_codusu."',".$ai_orden.")";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido = false;
			 $this->is_msg_error="Error en metodo uf_guardar_cheques".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
		   }
		else 
		   {
			 $ls_descripcion = "Inserto el cheque ".$as_numche." perteneciente a la chequera ".$as_chequera." para el banco ".$as_codban." y la cuenta ".$as_ctaban.", asociado al usuario : ".$as_codusu." y en la posicion : ".$ai_orden;
			 $lb_valido      = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,'INSERT',$this->is_logusr,$this->is_ventana,$ls_descripcion);
		   }
		return $lb_valido;
	}//Function uf_guardar_cheques.

	function uf_update_chequera($as_codemp,$as_codban,$as_ctaban,$as_chequera,$as_numche,$as_codusu,$ai_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_chequera
		//		   Access: private
		//	    Arguments: 
		//      $as_codemp = Código de la Empresa.
		//      $as_codban = Código de la Entidad Bancaria.
		//      $as_ctaban = Número de Registro de la Cuenta Bancaria.
		//      $as_tipcta = Identificación del Tipo de la Cuenta (Ahorro,Corriente).
		//    $as_chequera = Número de Identificación de la Chequera.
		//      $as_numche = Número del Cheque asociado a la Chequera.
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que actualiza la asignacion de los cheques e inserta los cheques que hayan sido agregados 
		//                 posterior a su creacion.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/03/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido = true;
		$ls_estche = "";
		$this->io_sql->begin_transaction();
		$lb_existe = $this->uf_select_cheques($as_codban,$as_ctaban,$as_numche,$as_chequera,$ls_estche);
		if ($lb_existe)
		   {
		     $ls_sql = "UPDATE scb_cheques 
			               SET codusu = '".rtrim($as_codusu)."', orden = '".$ai_orden."'
						 WHERE codemp = '".$as_codemp."'
						   AND codban = '".$as_codban."'
						   AND ctaban = '".trim($as_ctaban)."'
						   AND numchequera = '".trim($as_chequera)."'
						   AND numche = '".trim($as_numche)."'
						   AND estche = '0'";
		   	 $ls_descripcion = "Actualizó la Chequera ".$as_chequera." Reasignando el Cheque Nro:".$as_numche.", con el Usuario : ".$as_codusu;
		   }
		else
		   {
		     $ls_sql = "INSERT INTO scb_cheques(codemp,codban,ctaban,numche,estche,numchequera,codusu,orden) 
						    VALUES('".$as_codemp."','".trim($as_codban)."','".trim($as_ctaban)."','".$as_numche."',0,
							       '".trim($as_chequera)."','".rtrim($as_codusu)."',".$ai_orden.")";
		   	 $ls_descripcion = "Actualizó la Chequera ".$as_chequera." Insertando el Cheque Nro:".$as_numche.", perteneciente al banco ".$as_codban." y la cuenta ".$as_ctaban.", asociado al usuario : ".$as_codusu." y en la posicion : ".$ai_orden;
		   }
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido = false;
			 $this->is_msg_error="Error en metodo uf_guardar_cheques".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
		   }
		else 
		   {
			 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,'UPDATE',$this->is_logusr,$this->is_ventana,$ls_descripcion);
		   }
		return $lb_valido;
	}//Function uf_update_chequera.

	
	function uf_delete_cheques($as_chequera,$as_codban,$as_ctaban,$as_cheque)
	{
		$lb_valido=false;
		$lb_existe=$this->uf_select_cheques($as_codban,$as_ctaban,$as_cheque,$as_chequera,&$li_status);
		$ls_codemp=$this->dat["codemp"];
		
		if(($lb_existe))
		{
			$ls_cadena= " DELETE FROM scb_cheques WHERE codemp='".$ls_codemp."' AND ctaban='".$as_ctaban."' AND codban='".$as_codban."' AND numche='".$as_cheque."' AND numchequera='".$as_chequera."' AND estche<>1" ;
			$this->is_msg_error="Registro Eliminado";		
			$this->io_sql->begin_transaction();
			$li_numrows=$this->io_sql->execute($ls_cadena);
			if($li_numrows===false)
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->is_msg_error="Error en metodo uf_delete_cheques ".$this->fun->uf_convertirmsg($this->io_sql->message);
				print $this->is_msg_error;
			}
			else
			{
				$lb_valido=true;
				$this->io_sql->commit();
				$ls_evento="DELETE";
				$ls_descripcion="Elimino el cheque ".$as_cheque." perteneciente a la chequera  ".$as_chequera." del banco ".$as_codban." y la cuenta ".$as_ctaban ;
				$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			}
		}
		return $lb_valido;
	}	
	
	
	function uf_cargar_cheques($as_codban,$as_ctaban,$as_chequera,$ls_codemp,&$li_total,&$object,&$li_totrowusu,&$object_usu)
	{
		$li_x = $li_i = 0;
		$li_total=0;
		  $ls_sql="SELECT numchequera, numche, max(estche) as estche, max(codusu) as codusu
				     FROM scb_cheques
				    WHERE codemp='".$ls_codemp."'
				      AND codban='".$as_codban."' 
				      AND ctaban='".$as_ctaban."' 
					  AND numchequera='".$as_chequera."'
				    GROUP BY codemp,numchequera,numche,orden
                    ORDER BY orden ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error=$this->fun->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_x++;
				$ls_numcheque = trim($row["numche"]);
				$ls_codusuche = trim($row["codusu"]);
			    $li_status    = $row["estche"];
				if($li_status==1)
				{
					$lb_chk="checked";
				}
				else
				{
					$lb_chk="";
				}
				//Object que contiene los objetos y valores	iniciales del grid.	
				 $object[$li_x][1] = "<input type=text name=txtnumrefche".$li_x."   value='".$ls_numcheque."' id=txtnumrefche".$li_x." class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
				 $object[$li_x][2] = "<input type=text name=txtcodusuche".$li_x."   value='".$ls_codusuche."' id=txtcodusuche".$li_x." class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
				 $object[$li_x][3] = "<input name=chk".$li_x." type=checkbox id=chk".$li_x." class=sin-borde  onClick='return false;' ".$lb_chk.">";
				 $object[$li_x][4] = "<a href=javascript:uf_delete_dt('".$li_x."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
			}
			if($li_x==0)
			{
				$li_x=1;
			    $object[$li_x][1] = "<input type=text name=txtnumrefche".$li_x."   value='' id=txtnumrefche".$li_x." class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
			    $object[$li_x][2] = "<input type=text name=txtcodusuche".$li_x."   value='' id=txtcodusuche".$li_x." class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
			    $object[$li_x][3] = "<input name=chk".$li_x." type=checkbox id=chk".$li_x." class=sin-borde  onClick='return false;'>";
			    $object[$li_x][4] = "<a href=javascript:uf_delete_dt('".$li_x."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
			}
			$li_total=$li_x;
			
			 $this->io_sql->free_result($rs_data);

			 $ls_sql = "SELECT DISTINCT scb_cheques.codusu, sss_usuarios.nomusu, sss_usuarios.apeusu 
					      FROM scb_cheques, sss_usuarios
					     WHERE scb_cheques.codemp='".$ls_codemp."'
						   AND scb_cheques.codban='".$as_codban."' 
						   AND scb_cheques.ctaban='".$as_ctaban."' 
						   AND scb_cheques.numchequera='".$as_chequera."'
						   AND scb_cheques.codemp=sss_usuarios.codemp
						   AND scb_cheques.codusu=sss_usuarios.codusu
					     ORDER BY scb_cheques.codusu, sss_usuarios.nomusu, sss_usuarios.apeusu ASC";
			 $rs_data = $this->io_sql->select($ls_sql);
			 if ($rs_data===false)
		        {
		          $this->is_msg_error=$this->fun->uf_convertirmsg($this->io_sql->message);
			      return false;
		        }
		     else
		        {
				  while ($row=$this->io_sql->fetch_row($rs_data))
			            {
  						  $li_i++;
	  				      $ls_codusu = $row["codusu"];
				          $ls_nomusu = $row["nomusu"];
				          $ls_apeusu = $row["apeusu"];
			  			  $object_usu[$li_i][1] = "<input type=text  name=txtcodusu".$li_i."  value='".$ls_codusu."'  id=txtcodusu".$li_i."  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
						  $object_usu[$li_i][2] = "<input type=text  name=txtnomusu".$li_i."  value='".$ls_nomusu."'  id=txtnomusu".$li_i."  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
						  $object_usu[$li_i][3] = "<input type=text  name=txtapeusu".$li_i."  value='".$ls_apeusu."'  id=txtapeusu".$li_i."  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
						  $object_usu[$li_i][4] = "<a href=javascript:uf_delete_dt_usu('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
		                }
			  	  $li_i++;
				  $object_usu[$li_i][1] = "<input type=text  name=txtcodusu".$li_i."  value=''  id=txtcodusu".$li_i."  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
				  $object_usu[$li_i][2] = "<input type=text  name=txtnomusu".$li_i."  value=''  id=txtnomusu".$li_i."  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
				  $object_usu[$li_i][3] = "<input type=text  name=txtapeusu".$li_i."  value=''  id=txtapeusu".$li_i."  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
				  $object_usu[$li_i][4] = "<a href=javascript:uf_delete_dt_usu('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
				  $li_totrowusu = $li_i;
				  $this->io_sql->free_result($rs_data);
				}		
		} //  fin del else		
	}
	
	function uf_validar_cheque($as_codemp,$as_codban,$as_ctaban,$as_numche)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cheque
		//		   Access: private
		//	    Arguments: 
		//      $as_codemp = Código de la Empresa.
		//      $as_codban = Código de la Entidad Bancaria.
		//      $as_ctaban = Número de Registro de la Cuenta Bancaria.
		//      $as_numche = Número del Cheque asociado a la Chequera.
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que verifica si existe el cheques asociado para un Banco para una Cuenta Bancaria.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/03/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe = false;
		$ls_sql = "SELECT numche 
					 FROM scb_cheques 
					WHERE codemp = '".$as_codemp."' 
					  AND codban = '".$as_codban."' 
					  AND ctaban = '".$as_ctaban."'
					  AND numche = '".trim($as_numche)."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido = false;
			 $this->is_msg_error = "Error:CLASS->sigesp_scb_c_chequera;Método:uf_validar_cheque();.".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
		   }
		else
		   {
			 if ($row=$this->io_sql->fetch_row($rs_data))
				{
				  $lb_existe = true;
				}
		   }
		return $lb_existe;
	}//Function uf_validar_cheque.
	
	function uf_buscar_cheques($as_codban,$as_ctaban,$as_chequera,&$aa_cheques)
	{
		$li_x = $li_i = 0;
		$li_total=0;
		$ls_codemp = $this->dat["codemp"];
		  $ls_sql="SELECT numchequera, numche, max(estche) as estche, max(codusu) as codusu
					 FROM scb_cheques
					WHERE codemp='".$ls_codemp."'
					  AND codban='".$as_codban."' 
					  AND ctaban='".$as_ctaban."' 
					  AND numchequera='".$as_chequera."'
					GROUP BY codemp,numchequera,numche,orden
					ORDER BY orden ASC"; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
		   $this->is_msg_error = "Error:CLASS->sigesp_scb_c_chequera;Método:uf_buscar_cheques();.".$this->fun->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			  while ($row=$this->io_sql->fetch_row($rs_data))
				{
				  	$li_i++;
				  	$aa_cheques[$li_i]["numchequera"]=$row["numchequera"];  
					$aa_cheques[$li_i]["numche"]=$row["numche"]; 
					$aa_cheques[$li_i]["estche"]=$row["estche"]; 
					$aa_cheques[$li_i]["codusu"]=$row["codusu"]; 
				}
		}
		$this->io_sql->free_result($rs_data);
	}
}
?>