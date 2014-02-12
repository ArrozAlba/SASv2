<?php
class sigesp_sno_c_metodo_banco_1
{
	var $io_mensajes;
	var $io_metbanco;
	var $io_sno;
	var $ls_codemp;
	var $ls_nomemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_metodo_banco_1()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_metodo_banco_1
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("sigesp_snorh_c_metodobanco.php");
		$this->io_metbanco=new sigesp_snorh_c_metodobanco();
   		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$this->ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$this->ls_siglas=$_SESSION["la_empresa"]["titulo"];
	}// end function sigesp_sno_c_metodo_banco_1
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod($as_ruta,$ac_nroperi,$ad_fdesde,$ad_fhasta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod
		//		   Access: public 
		//	    Arguments: ac_nroperi  // codigo del periodo
		//                 ad_fdesde   // fecha desde
		//                 ad_fhasta   // fecha hasta
		//                 aa_ds_banco // arreglo (datastore) datos banco      
		//	  Description: genera el archivo txt a disco para  el banco BOD para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ldec_MontoPrevio=0;
		$ldec_MontoAcumulado=0;
		$li_NroDebitosPrev=0;
		$li_NroCreditosPrev=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_desperi="";
		$li_count=$rs_data->RecordCount();
		if ($li_count > 0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{	//Numero de cuenta del empleado
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				//Monto total a cancelar 
				$ldec_neto = $rs_data->fields["monnetres"]; //debo verificar si en el ds ya viene modificado la coma decimal
				$ldec_neto = ($ldec_neto*100);  
				$ldec_neto = $this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				//cedula del empleado
				$ls_cedemp = $this->io_funciones->uf_rellenar_der($rs_data->fields["cedper"]," ",15);
				$ls_cadena = $ls_cedemp.$ls_codcueban.$ldec_neto.$ls_desperi."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
				$li_NroCreditosPrev = ($li_NroCreditosPrev + 1);
                               $rs_data->MoveNext();
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_metodo_banco_banesco_paymul($as_ruta,$rs_data,$ad_fecproc,$adec_montot,$as_codcueban,$as_ref)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banesco_paymul
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 adec_montot // Monto total
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//	  Description: genera el archivo txt a disco para  el banco Banesco Paymul para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/b_paymul.txt";
		$ls_numope=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP","1","I");
		$ls_numope=intval($this->io_funciones->uf_trim($ls_numope), 10);
		$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP",$ls_numope+1,"I");
		if($lb_valido)
		{		
			$ls_numref=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF","1","I");
			$ls_numref=intval($this->io_funciones->uf_trim($ls_numref),10);
			if ($as_ref==1)
			{
				$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF",$ls_numref+1,"I");
			}
		}
		if($lb_valido)
		{		
			$ls_numref=$this->io_funciones->uf_cerosizquierda($ls_numref,8);					 
			$ls_numope=substr($ls_numope,0,9); 
			$ls_numope=str_pad($ls_numope,11,"0",1);
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
		}
		if($lb_valido)
		{		
			// Registro de control (Datos Fijos)
			$ls_cadena="HDR"."BANESCO        "."E"."D  95B"."PAYMUL"."P"."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de encabezado
			$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
			$ad_fecproc=str_replace("-","",$ad_fecproc);
			$ls_cadena = "01".$this->io_funciones->uf_rellenar_der("SAL"," ",35).$this->io_funciones->uf_rellenar_der("9"," ",3).$this->io_funciones->uf_rellenar_der($ls_numope," ",35).$this->io_funciones->uf_cerosderecha($ad_fecproc,14)."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de debito
			$ls_rifempresa=str_replace("-","",$_SESSION["la_empresa"]["rifemp"]);
			$ls_rif=$this->io_funciones->uf_rellenar_der($ls_rifempresa," ",17);
			$ldec_montot=$adec_montot;           
			$ldec_montot=($ldec_montot*100);  
			$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($as_codcueban),0,34), " ", 34);
			$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
			$ls_nomemp=$this->io_funciones->uf_rellenar_der(substr(rtrim($ls_nomemp),0,35)," ",35);
			$ls_banesco=$this->io_funciones->uf_rellenar_der("BANESCO"," ",11);
			$li_nrodebitos=1;
			$ls_numref=$this->io_funciones->uf_rellenar_der($ls_numref," ",30);
			$ls_cadena="02".$ls_numref.$ls_rif.$ls_nomemp.$ldec_montot."VEF"." ".$as_codcueban.$ls_banesco.$ad_fecproc."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo, $ls_cadena)===FALSE)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}	
		if($lb_valido)
		{		
			//Registro de credito
			$li_nrocreditos=0;
			$li_numrecibo=0;
			$li_count=$rs_data->RecordCount();//$aa_ds_banco->getRowCount("codcueban");
			//for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
		        while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_numrecibo=$li_numrecibo+1;
				$ls_codcueban=$rs_data->fields["codcueban"]; //Numero de cuenta del empleado
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($ls_codcueban),0,30)," ",30);
				$li_numrecibo=$this->io_funciones->uf_cerosizquierda($li_numrecibo,8);
				$li_numrecibo=$this->io_funciones->uf_rellenar_der($li_numrecibo," ", 30);
				$ldec_neto=$rs_data->fields["monnetres"];  //debo verificar si en el ds ya viene modificado la coma decimal
				$ldec_neto=number_format($ldec_neto,2,".","");
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				$ls_nacper=$rs_data->fields["nacper"];   //Nacionalidad
				$ls_cedper=$rs_data->fields["cedper"];   //cedula del personal
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_nacper.$ls_cedper," ",17);
				$ls_apeper=$rs_data->fields["apeper"];
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper." ".$ls_nomper, " ", 70);
				$ls_const=$this->io_funciones->uf_rellenar_der("BANSVECA", " ", 11);
				$ls_space= $this->io_funciones->uf_rellenar_der(""," ",3); // (3)
				$ls_spacedir=$this->io_funciones->uf_rellenar_der(""," ",70);  //direccion (70)
				$ls_spacetel=$this->io_funciones->uf_rellenar_der(""," ",25);              //telefono (25)
				$ls_spacecicon=$this->io_funciones->uf_rellenar_der(""," ",17);                    //C.I. persona contacto  (17)
				$ls_spacenomcon=$this->io_funciones->uf_rellenar_der(""," ",35);  //Nombre persona contacto (35)
				$ls_spaceficha=$this->io_funciones->uf_rellenar_der(""," ",30);       //Ficha del personal (30)
				$ls_spaceubic=$this->io_funciones->uf_rellenar_der(""," ",21);                //Ubicacion Geografica (21)
				$li_nrocreditos=$li_nrocreditos + 1;
				$ls_cadena="03".$li_numrecibo.$ldec_neto."VEF".$ls_codcueban.$ls_const.$ls_space.$ls_cedper.$ls_personal.
							$ls_spacedir.$ls_spacetel.$ls_spacecicon.$ls_spacenomcon." ".$ls_spaceficha."  ".$ls_spaceubic."42 "."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido = false;
				}
                             $rs_data->MoveNext();		
			} 
		 
		}
		if($lb_valido)
		{		
			//Registro de totales
			$li_nrodebitos=$this->io_funciones->uf_cerosizquierda($li_nrodebitos,15);
			$li_nrocreditos=$this->io_funciones->uf_cerosizquierda($li_nrocreditos,15);
			$ls_cadena="06".$li_nrodebitos.$li_nrocreditos.$ldec_montot."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if ($lb_valido)
		{
			@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
			$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
		}
		else
		{
			@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
			$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
		}	
		return $lb_valido;		
    }// end function uf_metodo_banco_banesco_paymul
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_metodo_banco_banesco_paymul_terceros($as_ruta,$rs_data,$ad_fecproc,$adec_montot,$as_codcueban,$as_ref)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banesco_paymul
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 adec_montot // Monto total
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//	  Description: genera el archivo txt a disco para  el banco Banesco Paymul para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/b_paymul.txt";
		$ls_numope=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP","1","I");
		$ls_numope=intval($this->io_funciones->uf_trim($ls_numope), 10);
		$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP",$ls_numope+1,"I");
		if($lb_valido)
		{		
			$ls_numref=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF","1","I");
			$ls_numref=intval($this->io_funciones->uf_trim($ls_numref),10);
			if ($as_ref==1)
			{
				$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF",$ls_numref+1,"I");
			}
		}
		if($lb_valido)
		{		
			$ls_numref=$this->io_funciones->uf_cerosizquierda($ls_numref,8);					 
			$ls_numope=substr($ls_numope,0,9); 
			$ls_numope=str_pad($ls_numope,11,"0",1);
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
		}
		if($lb_valido)
		{		
			// Registro de control (Datos Fijos)
			$ls_cadena="HDR"."BANESCO        "."E"."D  95B"."PAYMUL"."P"."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de encabezado
			$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
			$ad_fecproc=str_replace("-","",$ad_fecproc);
			$ls_cadena = "01".$this->io_funciones->uf_rellenar_der("SCV"," ",35).$this->io_funciones->uf_rellenar_der("9"," ",3).$this->io_funciones->uf_rellenar_der($ls_numope," ",35).$this->io_funciones->uf_cerosderecha($ad_fecproc,14)."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de debito
			$ls_rifempresa=str_replace("-","",$_SESSION["la_empresa"]["rifemp"]);
			$ls_rif=$this->io_funciones->uf_rellenar_der($ls_rifempresa," ",17);
			$ldec_montot=$adec_montot;           
			$ldec_montot=($ldec_montot*100);  
			$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($as_codcueban),0,34), " ", 34);
			$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
			$ls_nomemp=$this->io_funciones->uf_rellenar_der(substr(rtrim($ls_nomemp),0,35)," ",35);
			$ls_banesco=$this->io_funciones->uf_rellenar_der("BANESCO"," ",11);
			$li_nrodebitos=1;
			$ls_numref=$this->io_funciones->uf_rellenar_der($ls_numref," ",30);
			$ls_cadena="02".$ls_numref.$ls_rif.$ls_nomemp.$ldec_montot."VEF"." ".$as_codcueban.$ls_banesco.$ad_fecproc."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo, $ls_cadena)===FALSE)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}	
		if($lb_valido)
		{		
			//Registro de credito
			$li_nrocreditos=0;
			$li_numrecibo=0;
			$li_count=$rs_data->RecordCount();//$aa_ds_banco->getRowCount("codcueban");
			//for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
		        while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_numrecibo=$li_numrecibo+1;
				$ls_codcueban=$rs_data->fields["codcueban"]; //Numero de cuenta del empleado
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($ls_codcueban),0,30)," ",30);
				$li_numrecibo=$this->io_funciones->uf_cerosizquierda($li_numrecibo,8);
				$li_numrecibo=$this->io_funciones->uf_rellenar_der($li_numrecibo," ", 30);
				$ldec_neto=$rs_data->fields["monnetres"];  //debo verificar si en el ds ya viene modificado la coma decimal
				$ldec_neto=number_format($ldec_neto,2,".","");
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				$ls_nacper=$rs_data->fields["nacper"];   //Nacionalidad
				$ls_cedper=$rs_data->fields["cedper"];   //cedula del personal
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_nacper.$ls_cedper," ",17);
				$ls_apeper=$rs_data->fields["apeper"];
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper." ".$ls_nomper, " ", 70);
				$ls_const=$this->io_funciones->uf_rellenar_der($rs_data->fields["codban"], " ", 11);
				$ls_space= $this->io_funciones->uf_rellenar_der(""," ",3); // (3)
				$ls_spacedir=$this->io_funciones->uf_rellenar_der(""," ",70);  //direccion (70)
				$ls_spacetel=$this->io_funciones->uf_rellenar_der(""," ",25);              //telefono (25)
				$ls_spacecicon=$this->io_funciones->uf_rellenar_der(""," ",17);                    //C.I. persona contacto  (17)
				$ls_spacenomcon=$this->io_funciones->uf_rellenar_der(""," ",35);  //Nombre persona contacto (35)
				$ls_spaceficha=$this->io_funciones->uf_rellenar_der(""," ",30);       //Ficha del personal (30)
				$ls_spaceubic=$this->io_funciones->uf_rellenar_der(""," ",21);                //Ubicacion Geografica (21)
				$li_nrocreditos=$li_nrocreditos + 1;
				$ls_cadena="03".$li_numrecibo.$ldec_neto."VEF".$ls_codcueban.$ls_const.$ls_space.$ls_cedper.$ls_personal.
							$ls_spacedir.$ls_spacetel.$ls_spacecicon.$ls_spacenomcon." ".$ls_spaceficha."  ".$ls_spaceubic."425"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido = false;
				}
                             $rs_data->MoveNext();		
			} 
		 
		}
		if($lb_valido)
		{		
			//Registro de totales
			$li_nrodebitos=$this->io_funciones->uf_cerosizquierda($li_nrodebitos,15);
			$li_nrocreditos=$this->io_funciones->uf_cerosizquierda($li_nrocreditos,15);
			$ls_cadena="06".$li_nrodebitos.$li_nrocreditos.$ldec_montot."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if ($lb_valido)
		{
			@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
			$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
		}
		else
		{
			@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
			$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
		}	
		return $lb_valido;		
    }// end function uf_metodo_banco_banesco_paymul
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_confederado($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_confederado
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Cofederado para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}			
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_neto = $rs_data->fields["monnetres"];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
			    $ls_cadena=$ls_codcueban.substr($ldec_neto, 0, 10).".".substr($ldec_neto, 11)."+"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_confederado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_casa_propia_2003($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casa_propia_2003
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Casa Propia 2003 para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/transfer.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_nomper=trim($rs_data->fieldsa["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_izq(($ls_nomper." ".$ls_apeper)," ",40);
				$ls_personal=trim($ls_personal);
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban,"0",10);
			    $ls_codcueban=substr($ls_codcueban,0,10);
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban,"0",10);
				$ls_tipcuebanper=$rs_data->fields["tipcuebanper"];								
				$ldec_neto=$rs_data->fields["monnetres"];
				$li_neto_int=substr($ldec_neto,0,17);
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos+1,2);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.".".$li_neto_dec);
			    $ls_cadena = $ls_cedper.",".$ls_personal.",".$ls_tipcuebanper.",".$ls_codcueban.",".$ldec_montot.","."0"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_casa_propia_2003
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_caribe($as_ruta,$rs_data,$adec_montot,$ad_fecproc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casa_propia_2003
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 adec_montot // monto toal a depositar
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco Caribe para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/carga.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Registro de Cabecera
			$ldec_montot=$adec_montot*100;
			$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,20);
			$li_dia=substr($ad_fecproc,0,2);
			$li_mes=substr($ad_fecproc,3,2);
			$li_ano=substr($ad_fecproc,6,4);
			$ld_fecproc=$li_dia.$li_mes.$li_ano;
			$ls_cadena=$ld_fecproc."/".intval($li_count,10)."/".$ldec_montot."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			//Registro de Credito
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ldec_neto = $rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,20);
				//$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				//$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_cadena="C"."/".$ls_codcueban."/".$ls_cedper."/".$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_caribe
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banfoandes($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banfoandes
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco Banfoandes para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/banfoandes.txt";
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$ld_fecproc=$li_dia.$li_mes.$li_ano;
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}

				// Registro de la cabecera (Datos Fijos)
				$ldec_montot=$adec_montot;           
				$ldec_montot=($ldec_montot*100);  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,17);
				$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			        $as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 20);
				$cant_registros=str_pad($li_count,4,0,"LEFT");
				if($lb_valido)
				{		
				    $ls_cadena=$as_codcueban.$li_ano.$li_mes.$li_dia.$ldec_montot.$cant_registros."\r\n";
							  
				}
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
			    $ls_codcueban=trim($ls_codcueban);
			    $ls_codcueban=substr($ls_codcueban,0,20);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10); 
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto, 12);
				$ls_cadena="0651".$ldec_neto.$ls_codcueban.$ls_cedper."00000000"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
 				$rs_data->MoveNext();		
			} 
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_banfoandes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banfoandes_ipsfa($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banfoandes_ipsfa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco Banfoandes para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/banfoandes.txt";
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$ld_fecproc=$li_dia.$li_mes.$li_ano;
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}

				// Registro de la cabecera (Datos Fijos)
				$ldec_montot=$adec_montot;           
				$ldec_montot=($ldec_montot*100);  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,17);
				$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			        $as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 20);
				$cant_registros=str_pad($li_count,4,0,"LEFT");
				if($lb_valido)
				{		
				    $ls_cadena=$as_codcueban.$li_ano.$li_mes.$li_dia.$ldec_montot.$cant_registros."\r\n";
							  
				}
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
			    $ls_codcueban=trim($ls_codcueban);
			    $ls_codcueban=substr($ls_codcueban,0,20);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9); 
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto, 12);
				$ls_cadena="0651".$ldec_neto.$ls_codcueban."000".$ls_cedper."00000000"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
 				$rs_data->MoveNext();		
			} 
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_banfoandes_ipsfa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod_version_3($as_ruta,$rs_data,$ad_fecproc,$as_codmetban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod_version_3
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // c�digo del m�todo a banco
		//	  Description: genera el archivo txt a disco para  el banco BOD_version_3 para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,12);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 12);
				$ldec_neto=$rs_data->fields["monnetres"];
				$li_neto_int=$ldec_neto;
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_rellenar_izq($ldec_montot,"0",12);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper," ",15); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",30);
				$ls_codper=$this->io_funciones->uf_rellenar_der(substr($rs_data->fields["codper"],0,10)," ",10);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ld_fecproc=$li_ano.$li_mes.$li_dia;
				$ls_cadena='"'.$ls_codempnom.'","'.$ls_codcueban.'","'.$ls_cedper.'","'.$ls_personal.'",'.$ldec_montot.','.$ld_fecproc.','.
				           '"C"'.',"'.$ls_codper.'"'."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
                               $rs_data->MoveNext();	
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod_version_3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod_viejo($as_ruta,$rs_data,$ad_fecproc,$as_codmetban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod_viejo
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // c�digo del m�todo a banco
		//	  Description: genera el archivo txt a disco para  el banco BOD_version_3 para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 08/05/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomiter.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,10);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban,"0",10);
				$ldec_neto=$rs_data->fields["monnetres"];
				$li_neto_int=$ldec_neto;
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_rellenar_izq($ldec_montot,"0",12);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",30);
				$ls_codper=$this->io_funciones->uf_rellenar_der(substr($rs_data->fields["codper"],0,10)," ",10);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ld_fecproc=$li_ano.$li_mes.$li_dia;
				$ls_cadena=$ls_codempnom.",".$ls_codcueban.",".$ls_cedper.",".$ls_personal.",".$ldec_montot.",".$ld_fecproc.","."C".",".$ls_codper."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			        $rs_data->MoveNext();		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod_viejo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banesco($as_ruta,$rs_data,$ad_fecproc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banesco
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco BANESCO para pago de nomina
		//	   Creado Por: Ing. Maria Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_space="         "; // 9 espacios
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$rs_data->RecordCount();//$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Cabecera del archivo
			$ls_cadena="NACIONALIDAD".$ls_space."CEDULA".$ls_space."CUENTA".$ls_space."SUELDO"."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			//Registro de Detalle
			while((!$rs_data->EOF)&&($lb_valido))
			//for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ldec_neto=$rs_data->fields["monnetres"]; 
				$ldec_montot=number_format($ldec_neto*100,0,"","");  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
			    $ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cadena=$ls_nacper.$ls_space.$ls_cedper.$ls_space.$ls_codcueban.$ls_space.$ldec_montot."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_banesco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_canarias($as_ruta,$rs_data,$ad_fhasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_canarias
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fhasta // fecha donde termina el per�odo
		//	  Description: genera el archivo txt a disco para  el banco CANARIAS para pago de nomina
		//	   Creado Por: Ing. Maria Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_dia=substr($ad_fhasta,8,2);
		$li_mes=substr($ad_fhasta,5,2);
		$li_ano=substr($ad_fhasta,0,4);
		$ls_nombrearchivo=$as_ruta."/nomina".$li_dia.$li_mes.$li_ano.".txt";
		//Registro tipo E
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto = $rs_data->fields["monnetres"];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,11);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],9);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();	
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_canarias
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_caracas($as_ruta,$rs_data,$adec_montot,$as_codcueban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_caracas
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   adec_montot // Monto Total a depositar
		//	    		   as_codcueban // c�digo cuenta bancaria a debitar
		//	  Description: genera el archivo txt a disco para  el banco CARACAS para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				//$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,11);
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,11);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 11);
				//$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$ldec_neto = $rs_data->fields["monnetres"];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
				$ls_space="    ";
				$ls_cadena="NC".$ls_codcueban.$ldec_neto.$ls_space."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();	
			}
			//Resumen del deposito
			$as_codcueban=substr($as_codcueban,0,11);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 11);
			$adec_montot=round($adec_montot,2); 
			$adec_montot=($adec_montot*100);  
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot,13);
			$ls_cadena="ND".$as_codcueban.$adec_montot.$ls_space."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_caracas
	//-----------------------------------------------------------------------------------------------------------------------------------/*
	
	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_caroni($as_ruta,$rs_data)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_caroni
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	  Description: genera el archivo txt a disco para  el banco CARONI para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{				
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 12);
				$ldec_neto = $rs_data->fields["monnetres"];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,10);				
				$ls_nacper=$this->io_funciones->uf_rellenar_izq($rs_data->fields["nacper"]," ",1);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],10);				
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_apeper=$rs_data->fields["apeper"];
				$ls_nombre=$this->io_funciones->uf_rellenar_der($ls_apeper.", ".$ls_nomper," ",30);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_nombre.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();				
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_caroni
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_casapropia($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casapropia
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codcuenta // c�digo de cuenta a debitar
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el Banco Casa Propia para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Registro del deposito
			$ls_rifemp=str_replace("-","",$this->ls_rifemp);
			$ls_siglas=substr($this->ls_siglas,0,7);
			$li_numdebprev=1;
			$li_numcreprev=$li_count;
			$li_numcreprev=$this->io_funciones->uf_cerosizquierda($li_numcreprev,5);
			$ldec_totdep=$adec_montot;
			$ldec_monacu=$ldec_totdep;
			$ldec_monacu=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,12); 
			$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcuenta));
			$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban," ",25);
			$li_dia=substr($ad_fecproc,0,2);
			$li_mes=substr($ad_fecproc,3,2);
			$li_ano=substr($ad_fecproc,6,4);
			$ld_fecha=$li_ano.$li_mes.$li_dia;
			$ls_cadena=$ls_rifemp.$ls_siglas.$li_numcreprev.$ls_codcueban.$ld_fecha.$ldec_monacu."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			$li_numcreprev=0;
			$li_numcreprev=0;
			//Registro tipo E
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100, 12);
				$ls_cadena=$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
				$li_numcreprev = $li_numcreprev + 1;
				$rs_data->MoveNext();	
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
	}// end function metodo_banco_casapropia
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_central($as_ruta,$rs_data,$as_codcueban,$adec_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_central
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codcuenta // c�digo de cuenta a debitar
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco CENTRAL para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomdes.txt";
		//Registro tipo E
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			$li_i=1;
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,13);
				$ls_tipcueban=$rs_data->fields["tipcuebanper"];
				switch ($ls_tipcueban)
				{
					case "C":
						  $ls_tipocuenta="C";  //Corriente
						  break;
						  
					case "A":
						  $ls_tipocuenta="H";  //Ahorro
						  break;	
						  
					case "L":
						  $ls_tipocuenta="H";  //Activos Liquidos
						  break;	
						  
					default:	
						 $ls_tipocuenta="H";  
						 break;	    
				}
				$li_consecutivo=$this->io_funciones->uf_cerosizquierda($li_i, 8);
				$ls_cadena="A".$ls_tipocuenta."202".$ls_codcueban.$li_consecutivo.$ldec_neto."0506"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();
				$li_i++;					
			} 
			//Registro tipo T
			$li_consecutivo=$this->io_funciones->uf_cerosizquierda($li_i, 8);
			$as_codcueban=substr($as_codcueban,0,10);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 10);
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot*100,13);
            $ls_cadena="AC402".$as_codcueban.$li_consecutivo.$adec_montot."0506"."\r\n";	
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_central
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_del_sur_eap($as_ruta,$rs_data,$ad_fhasta,$as_codmetban)  
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_del_sur_eap
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fhasta // Fecha hasta del per�odo
		//	    		   as_codmetban // C�digo del M�todo a banco
		//	  Description: genera el archivo txt a disco para  el banco del_sur_eap para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(trim(substr($ls_codempnom,0,4)),4);	
		$ls_numconvenio=$this->io_funciones->uf_cerosizquierda(trim(substr($ls_numconvenio,0,8)),8);	
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{				
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto = $rs_data->fields["monnetres"];
				$ldec_neto=$this->io_funciones->uf_cerosizquierda(($ldec_neto*100),10);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(trim($rs_data->fields["cedper"]),10);
				$ls_nomper=rtrim($rs_data->fields["nomper"]);
				$ls_apeper=rtrim($rs_data->fields["apeper"]);
				$ls_nombre=substr($ls_nomper." ".$ls_apeper,0,30);
				$ls_nombre=$this->io_funciones->uf_rellenar_der($ls_nombre," ",30);
				$ls_cadena=$ls_codempnom.$ls_cedper.$ls_codcueban.$ldec_neto."C".$ls_numconvenio.$ls_nombre."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}				
				$rs_data->MoveNext();					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_del_sur_eap
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_biv_version_2($as_ruta,$rs_data,$as_codmetban,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_eap_micasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   as_codmetban // c�digo de m�todo a banco
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco industrial de venezuela version_2 para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codofinom=substr($ls_codofinom,0,3);
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				if ($aa_ds_banco->data["tipcuebanper"][$li_i]=="A")
				{
					$ls_tipcuebanper = "2";
				}
				else
				{
					$ls_tipcuebanper = "1";
				}
				//$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,12);
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				$ldec_neto = $rs_data->fields["monnetres"];
				$li_neto_int=substr($ldec_neto,0,10);
				$li_pos=strpos($ldec_neto, ".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_cerosderecha($ldec_montot,12);				
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper," ",10); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_nomper=$this->io_funciones->uf_rellenar_der($ls_nomper," ",15);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_apeper=$this->io_funciones->uf_rellenar_der($ls_apeper," ",15);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cadena=$ls_tipcuebanper.$ls_nacper.$ls_cedper.$ls_apeper.$ls_nomper.$ls_codofinom."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();				
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function metodo_banco_biv_version_2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_central_v1($as_ruta,$rs_data,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_eap_micasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   as_codmetban // c�digo de m�todo a banco
		//	    		   adec_montot // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco CENTRAL version 1 para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomdes.txt";
		//Registro tipo E
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_neto=$rs_data->fields["monnetres"]*100;  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
				$li_reg=$this->io_funciones->uf_cerosizquierda($li_i,8);
				$ls_cadena = "AC202".$ls_codcueban.$li_reg.$ldec_neto."0506"."\r\n";			
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();					
			} 
			if($lb_valido)
			{
				//Registro tipo T
				$ls_codcueban=substr($as_codcuenta,0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($adec_montot*100,13);
				$ls_cadena="AC402".$ls_codcueban.$li_reg.$ldec_totdep."0506"."\r\n";	
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			} 
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_central_v1
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 28/08/2006 								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			   	$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=trim($rs_data->fields["nacper"]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",8); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",35);
				$ldec_neto=($rs_data->fields["monnetres"]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ls_personal.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial_02($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial_02
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 28/08/2006 								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			        $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=trim($rs_data->fields["nacper"]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",9); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=substr($ls_nomper.", ".$ls_apeper,0,30);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_personal," ",30);
				$ldec_neto=($rs_data->fields["monnetres"]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",13);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ls_personal.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();			
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial_02
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial_03($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial_03
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/06/2007 								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=rtrim($rs_data->fields["codcueban"]);
			        $ls_codcueban=str_replace("-","",$ls_codcueban);
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=rtrim($rs_data->fields["nacper"]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",8); 
				$ls_nomper=rtrim($rs_data->fields["nomper"]);
				$ls_apeper=rtrim($rs_data->fields["apeper"]);
				$ls_personal=substr($ls_nomper." ".$ls_apeper,0,32);
				$ls_personal=str_pad($ls_personal,32," ");
				$ldec_neto=($rs_data->fields["monnetres"]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_personal.$ls_codcueban.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}	
				$rs_data->MoveNext();	
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial_03
	//-----------------------------------------------------------------------------------------------------------------------------------   
	function uf_metodo_banco_provincial_altamira($as_ruta,$rs_data,$as_metodo,$as_codcueban,$adec_montot,$ad_fecproc)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial_altamira
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 05/06/2008 								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/NOMINA.txt";
		//Chequea si existe el archivo.
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}		
			if($lb_valido)
			{		
				// Registro de la cabecera (Datos Fijos)
				$ldec_montot=$adec_montot;           
				$ldec_montot=($ldec_montot*100);  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,17);
				$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
			    $ad_fecproc=str_replace("-","",$ad_fecproc);
				$ls_rifempresa=str_replace("-","",$_SESSION["la_empresa"]["rifemp"]);
			    $ls_rif=$this->io_funciones->uf_rellenar_izq($ls_rifempresa," ",10);
				$ls_refdebcre="        ";
				$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
			    $ls_nomemp=$this->io_funciones->uf_rellenar_der(substr(rtrim($ls_nomemp),0,27)," ",27);				
				$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			    $as_codcueban=substr($as_codcueban,12,8); 
				$cant_registros=str_pad($li_count,7,0,"LEFT");
				$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_metodo,"0",$ls_codempnom,
				                                                          $ls_codofinom,$ls_tipcuecre,
																		  $ls_tipcuedeb,$ls_numconvenio);
				if ($ls_numconvenio=="")
				{
				 	$ls_numconvenio="XXXX";
				}
				if ($ls_codofinom=="")
				{
					$ls_codofinom="XXXX";
				}
				
				$ls_disponible_C="                                              ";
				if($lb_valido)
				{		
				    $ls_cadena="01"."01".$ls_numconvenio.$ls_codofinom."00".$ls_tipcuedeb.$as_codcueban.$cant_registros.
					           $ldec_montot."VEB".$ad_fecproc.$ls_rif.$ls_refdebcre.$ls_nomemp.$ls_disponible_C."\r\n";
							  
				}
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				///----------registro individual obligatorio-------------------------------------------------------------------
				while((!$rs_data->EOF)&&($lb_valido))
			   	 {
				    $ls_tipo_registro="02";
					$l_codban="0108";
					$ls_dig_cheq="00";
					$ls_codcueban=rtrim($rs_data->fields["codcueban"]);
			    		$ls_codcueban=str_replace("-","",$ls_codcueban);
					$li_inicio=strlen($ls_codcueban)-12;
					$ls_codcueban=substr($ls_codcueban,$li_inicio,8);
					$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban," ",8);
					
					$ls_tipcta=rtrim($rs_data->fields["tipcuebanper"]);
					if($ls_tipcta=="A")
					{
						$ls_tipo="01";
					}
					else
					{
						$ls_tipo="02";
					}
					$ls_nacper=rtrim($rs_data->fields["nacper"]);
					$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"]);
					$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9);
					$referencia=$ls_nacper.$ls_cedper;
					$referencia=str_pad($referencia,16," ");
					$ls_nomper=rtrim($rs_data->fields["nomper"]);
					$ls_apeper=rtrim($rs_data->fields["apeper"]);
					$ls_personal=substr($ls_apeper.", ".$ls_nomper,0,40);
					$ls_personal=str_pad($ls_personal,40," ");
					$ldec_neto=($rs_data->fields["monnetres"]*100);
					$otros_datos="                              ";					
					$resultado="00";
					$refdebcre="        ";	
					$ls_disponible_IO="               ";						
					$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",17);				
					$ls_cadena=$ls_tipo_registro.$l_codban.$ls_codofinom.$ls_dig_cheq.$ls_tipo.$ls_codcueban.
					           $referencia.$li_neto_int.$ls_personal.$otros_datos.$resultado.$refdebcre.$ls_disponible_IO."\r\n";
					if ($ls_creararchivo)
					{
						if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
						$lb_valido=false;
					}
					$rs_data->MoveNext();		
			    }//fin  del for				
			    //----------------------fin del regitro obligatorio----------------------------------------------------		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
		
	}// end function uf_metodo_banco_provincial_altamira
//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_metodo_banco_provincial_BBVAcash($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial_BBVAcash
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creaci�n: 19/02/2009								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/banco_provincial.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad($ls_codcueban,20,"0",0);
				$ls_nacper=trim($rs_data->fields["nacper"]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=substr($ls_apeper." ".$ls_nomper,0,30);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_personal," ",30);
				$ldec_neto=($rs_data->fields["monnetres"]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",14);
				$ls_relleno=str_pad(' ',6," ",0);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_personal.$ls_codcueban.$li_neto_int.$ls_relleno."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();			
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_provincial_BBVAcash
	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_corp_banca($as_ruta,$rs_data,$adec_montot,$as_codperi,$as_perides,$as_perihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_corp_banca
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Corp Banca para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 14/05/2008 								
		// Modificado Por: 										Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=rtrim($rs_data->fields["codcueban"]);				
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
				$tamano=strlen($ls_codcueban);
				if ($tamano>10)
				 {
				   $ls_codcueban=substr($ls_codcueban,$tamano-10,$tamano);				
				 }
				$ls_codcueban=str_pad($ls_codcueban,12,"0","left");
				$ls_nacper=rtrim($rs_data->fields["nacper"]);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",15); 
				$ls_nomper=rtrim($rs_data->fields["nomper"]);
				$ls_apeper=rtrim($rs_data->fields["apeper"]);
				$ls_personal=substr($ls_apeper.", ".$ls_nomper,0,70);
				$ls_personal=str_pad($ls_personal,70," ");
				$ldec_neto=($rs_data->fields["monnetres"]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);				
				$ls_ano1=substr($as_perides,2,2);
				$ls_mes1=substr($as_perides,5,2);
				$ls_dia1=substr($as_perides,8,2);
				$ls_ano2=substr($as_perihas,2,2);
				$ls_mes2=substr($as_perihas,5,2);
				$ls_dia2=substr($as_perihas,8,2);
				$ls_cadena=$ls_cedper.$ls_codcueban.$li_neto_int.$as_codperi.$ls_dia1.
				           $ls_mes1.$ls_ano1.$ls_dia2.$ls_mes2.$ls_ano2."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();	
			}
		
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_corp_banca
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_deltesoro($as_ruta,$rs_data,$ad_fecproc,$as_codmetban)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_deltesoro
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codmetban // C�digo del Metodo
		//	  Description: genera el archivo txt a disco para  el banco idel Tesoro para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 12/06/2008 								
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=substr($ls_codempnom,0,4);
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");  
				$ldec_neto=number_format($ldec_neto*100,0,"","");  
				$ldec_neto=$this->io_funciones->uf_cerosderecha($ldec_neto,15);
				$ls_cadena=$ls_codempnom.$li_dia.$li_mes.$li_ano.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_deltesoro
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_deltesoro_2008($as_ruta,$rs_data,$ad_fecproc,$as_codmetban,$adec_montot,$as_codcueban)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_deltesoro_2008
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // fecha de procesamiento
		//                 as_codmetban // c�digo del m�todo a banco
		//	    		   adec_montot // monto total a ser aplicado
		//                 as_codcueban // c�digo de cuenta de banco
		//	  Description: genera el archivo txt a disco para  el banco idel Tesoro para pago de nomina, seg�n instrutivo
		//                 este m�todo contiene datos en la cabecera y el detalle.
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 02/10/2005 								
		// Modificado Por: 											Fecha �ltima Modificaci�n : /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=substr($ls_codempnom,0,4);
		$li_diapp=substr($ad_fecproc,0,2);
		$li_mespp=substr($ad_fecproc,3,2);
		$li_anopp=substr($ad_fecproc,8,2);
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			/// PARA LA CABECERA DEL ARCHIVO
			$ls_codempnom=str_pad($ls_codempnom,4,"0","left"); // se completa hasta cuatro digitos	
			$ls_rif=$this->ls_rifemp;
			$ls_rif=str_replace("-","",$ls_rif);
			$ls_rif=str_pad($ls_rif,15," ");
			$adec_montot=number_format($adec_montot,2,".","");  
			$adec_montot=number_format($adec_montot*100,0,"","");  
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot,15);
			$ld_fecreg=date("d/m/Y");
			$ld_anoreg=substr($ld_fecreg,8,2);
			$ld_mesreg=substr($ld_fecreg,3,2);
			$ld_diareg=substr($ld_fecreg,0,2);
			$ld_fecreg=$ld_anoreg.$ld_mesreg.$ld_diareg;
			$li_totreg=str_pad($li_count,5,"0","left");
			$ld_fecpago=$li_anopp.$li_mespp.$li_diapp;
			$ls_nrocuebanemp=$as_codcueban;
			$ls_nrocuebanemp=$this->io_funciones->uf_trim(str_replace("-","",$ls_nrocuebanemp));
			$ls_nrocuebanemp=$this->io_funciones->uf_cerosizquierda($ls_nrocuebanemp,20);
			//$ls_codnom=$aa_ds_banco->data["codnom"][1];	
			$ls_codnom=$rs_data->fields["codnom"];	
			$ls_codnom=str_pad($ls_codnom,10,"0","left");
			$ls_cabecera='H'.$ls_codempnom.$ls_codnom.$ls_nrocuebanemp.$ls_rif.$ld_fecreg.$ld_fecpago.$li_totreg.$adec_montot."\r\n";
			
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cabecera)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			/// PARA EL DETALLE DEL ARCHIVO
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_tipreg='D';
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
				$ls_cedula=$ls_nacper.$ls_cedper;
				$ls_cedula=str_pad($ls_cedula,15," ");
				$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");  
				$ldec_neto=number_format($ldec_neto*100,0,"","");  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				
				$ls_cadena=$ls_tipreg.$ls_codcueban.$ls_cedula.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_deltesoro_2008
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>


