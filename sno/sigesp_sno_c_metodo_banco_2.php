<?php
class sigesp_sno_c_metodo_banco_2
{
	var $io_mensajes;
	var $io_funciones;
	var $io_sno;
	var $io_metbanco;
	var $ls_codemp;
	var $ls_nomemp;
	var $ls_rifemp;
	var $ls_siglas;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_metodo_banco_2()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_metodo_banco_2
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_mensajes.php");  //clase de mensajes al usuario
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");  //Para el uso de funciones
		$this->io_funciones=new class_funciones();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("sigesp_snorh_c_metodobanco.php");
		$this->io_metbanco=new sigesp_snorh_c_metodobanco();
   		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$this->ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$this->ls_siglas=$_SESSION["la_empresa"]["titulo"];
	}// end function sigesp_sno_c_metodo_banco_2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_eap_micasa($as_ruta,$rs_data)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_eap_micasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	  Description: genera el archivo txt a disco para  el banco eap_micasa para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
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
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,11);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,11);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,11);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],9);
				//$ls_nacper=$aa_ds_banco->getValue("nacper",$li_i);
				$ls_nacper=$rs_data->fields["nacper"];
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
    }// end function uf_metodo_banco_eap_micasa
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_fondo_comun($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_fondo_comun
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c�digo de m�todo a banco 
		//                 as_desope // descripci�n de operaci�n
		//	  Description: genera el archivo txt a disco para  el banco Fondo Comun para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 05/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_nrodebitos=1; // se inicializa en uno, por que solo hay un registro y no es variable
		$li_nrocreditos=0;
		$ls_mondeb=0;
		$ls_moncre=0;
		$ldec_monto=0;
		$lb_valido=false;		
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ldec_codproc=$this->io_sno->uf_select_config("SNO","GEN_DISK","FONDO COMUN COD PROCESO","1","I");
		$ldec_codproc=$this->io_funciones->uf_cerosizquierda(intval($this->io_funciones->uf_trim($ldec_codproc),10),12);	
		$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","FONDO COMUN COD PROCESO",$ldec_codproc+1,"I");
		if($lb_valido)
		{
			$ls_codempnom="";
			$ls_codofinom="";
			$ls_tipcuedeb="";
			$ls_tipcuecre="";
			$ls_numconvenio="";
			$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
			$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,6),6);	
			$ls_tipcuecre=$this->io_funciones->uf_cerosizquierda($ls_tipcuecre,2);	
			$ls_tipcuedeb=$this->io_funciones->uf_cerosizquierda($ls_tipcuedeb,2);	
			$ldec_codproc=$this->io_funciones->uf_cerosizquierda($ldec_codproc,12);	
		}
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
			if($lb_valido)
			{
				//Registro de Encabezado
				$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
				$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 22);
				$ls_fecha=date("Ymd"); //Fecha de elaboracion
				$ls_hora=date("his"); //Hora de elaboracion
				$ls_fecapl="00000000"; //Fecha de aplicacion
				$ls_horapl="000000";   // Hora de aplicacion
				$ls_codser="000001";   // Codigo de Servicio
				$ls_numcuecre="0000000000000000000000"; //Numero de Cuenta de Credito
				$ls_constante="000000000000000000000000000000000000000000000000";
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ld_fecproc=$li_ano.$li_mes.$li_dia;
				$ls_cadena="000000".$ls_fecha.$ls_hora.$ld_fecproc."090000".$ls_fecapl.$ls_horapl.$ls_codempnom.$ls_codser." ".
						   $ls_tipcuedeb.$as_codcueban." ".$ls_tipcuecre.$ls_numcuecre.$ldec_codproc.$ls_constante."\r\n";
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
				$as_desope=$this->io_funciones->uf_rellenar_der($as_desope," ",40);
				$ldec_moncre=0;
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_codcueban=$rs_data->fields["codcueban"];
					$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
					$ldec_neto=$rs_data->fields["monnetres"];
					$ldec_moncre=$ldec_moncre+$ldec_neto;
					$ldec_neto=($ldec_neto*100);  
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
					$ls_nacper=$rs_data->fields["nacper"];
					$ls_cedper=$rs_data->fields["cedper"];
					$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);
					$ls_serser="00001" ;     //serial de servicio
					$ls_numcuo="00000";      //numero de cuotas
					$ls_numref= "0000000000"; //numero de referencia
					$ls_cargo="0";          // aplicar cargo
					$ls_codrech="000";        // Codigo de rechazo
					$ls_desrech=$this->io_funciones->uf_rellenar_der(""," ",40); //Descripcion del rechazo
					$ls_relleno="000000000";  //valor fijo de relleno
					$li_nrocreditos=$li_nrocreditos+1;
					$li_contador=$this->io_funciones->uf_cerosizquierda($li_i,6);
					$ls_cadena=$li_contador." ".$ls_tipcuecre.$ls_codcueban.$ls_nacper.$ls_cedper.$ls_serser.$ls_numcuo.
									 $ls_numref.$ldec_neto."C"."0".$as_desope.$ls_cargo.$ls_codrech.$ls_desrech.$ls_relleno."\r\n";
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
				//Registro de Totales
				$ls_nomemp=str_pad(substr($_SESSION["la_empresa"]["nombre"],0,39),39," ");
				$li_canreg=$li_nrodebitos + $li_nrocreditos;  //Cantidad de registros
				$li_canreg=$this->io_funciones->uf_cerosizquierda($li_canreg,6);
				$ldec_mondeb=0; 
				$ldec_mondeb=($ldec_mondeb*100);  
				$ldec_mondeb=$this->io_funciones->uf_cerosizquierda($ldec_mondeb,15);
				$ldec_moncre=($ldec_moncre*100);
				$ldec_moncre=$this->io_funciones->uf_cerosizquierda($ldec_moncre,15);
				$li_nrodebitos=$this->io_funciones->uf_rellenar_der($li_nrodebitos,"0",6);
				$li_nrocreditos=$this->io_funciones->uf_rellenar_der($li_nrocreditos,"0",6);
				$ls_ceros=$this->io_funciones->uf_cerosizquierda("0",76);
				$ls_cadena="999999"." ".$ls_nomemp.$li_canreg.$ldec_mondeb.$ldec_moncre.$li_nrodebitos.$li_nrocreditos.$ls_ceros."\r\n";
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
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;		
    }// end function uf_metodo_banco_fondo_comun
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_industrial($as_ruta,$rs_data)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_industrial
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	  Description: genera el archivo txt a disco para  el banco industrial para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_constante="0";
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
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
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,13);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],11);
				$ls_constante=$this->io_funciones->uf_cerosizquierda($ls_constante,13);
				$ls_cadena="770".$ls_codcueban.$ls_cedper.$ldec_neto.$ls_constante."\r\n";
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
    }// end function uf_metodo_banco_industrial
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_mercantil($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_mercantil
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codcuenta // c�digo de cuenta
		//	    		   adec_montot // total a depositar
		//	  Description: genera el archivo txt a disco para  el Banco Mercantil para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/bsf0000w.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,85);
				$ldec_monpre=(substr($ls_cad_previa,26,13)/100);
				$ldec_monacu=round($ldec_monpre+$adec_montot,2);
			}
			else
			{
				//Registro Cabecera (D�bito)
				$li_filads=$li_count;
				$ldec_totdep=$adec_montot;
				$ldec_totdep=round($ldec_totdep,2);
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep*100,13);
			    $as_codcuenta=$this->io_funciones->uf_trim(str_replace("-","",$as_codcuenta));
				$li_inicio=strlen($as_codcuenta)-10;
				$as_codcuenta=substr($as_codcuenta,$li_inicio,10);
				$as_codcuenta=$this->io_funciones->uf_cerosizquierda($as_codcuenta,12);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ls_blancos="00000000000000";
				$ls_cadena="640".$as_codcuenta."785"."00000000".$ldec_totdep."0000000000000"."001050".$li_ano.$li_mes.$li_dia.$ls_blancos."\r\n";
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
			//Registro tipo E
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,12),12);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,13);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($rs_data->fields["cedper"],0,8),8);
				$ls_cadena="770".$ls_codcueban."222".$ls_cedper.$ldec_neto."0000000000000"."001050"."0000000000000000000000"."\r\n";
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
			//*-Si estoy acumulando reemplazo la cabecera con la informacion acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=substr($ls_cad_previa,39,41);
				$ls_cadena=substr($ls_cad_previa,0,26).$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,13).$ls_reemplazar."\r\n";//.$ls_reemplazar;
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
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
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacu));
				}
				unset($new_archivo);
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
	}// end function metodo_banco_mercantil
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_mi_casa($as_ruta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_mi_casa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	  Description: genera el archivo txt a disco para  el banco Mi Casa para pago de nomina
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
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,10);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],9);
				$ls_nacper=$rs_data->fields["nacper"];
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
    }// end function uf_metodo_banco_mi_casa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_provincial_guanare($as_ruta,$rs_data,$ad_fecproc)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial_guanare
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco provincial guanare para pago de nomina
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
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,17);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",15); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper." ".$ls_apeper," ",94);
				$ls_cadena="02".$ls_codcueban.$ls_nacper.$ls_cedper.$ldec_neto.$ls_personal."*"."\r\n";
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
	}// end function uf_metodo_banco_provincial_guanare
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_provincial($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcuenta // c�digo de cuenta a debitar
		//                 adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el Banco Provincial para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_rifemp=$this->io_funciones->uf_rellenar_izq(str_replace("-","",$this->ls_rifemp)," ",10);
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,150);
				$ldec_monpre=(substr($ls_cad_previa,31,17)/100);
				$li_countregprev=(substr($ls_cad_previa,24,7));
				$ldec_monacu=($ldec_monpre + $adec_montot);
				$li_countregacum=$li_countregprev+$li_count;
			}
			else
			{	//Registro Cabecera (D�bito)
				$ls_codcueban=substr($as_codcuenta,0,20);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban," ",20);
				$ldec_totdep=$adec_montot*100;  
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep,17);
				$li_contador=$this->io_funciones->uf_cerosizquierda($li_count,7);		
				$ls_fecha=date("Ymd");
				$ls_disponible="                                              ";
				$ls_cadena="01"."01".$ls_codcueban.$li_contador.$ldec_totdep."VEB".$ls_fecha.$ls_rifemp."        ".$this->ls_nomemp.$ls_disponible."\r\n";
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
			//Registro tipo E
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban," ",20);
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,17);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",15);
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper.", ".$ls_nomper," ",40);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_otrosdatos=$this->io_funciones->uf_rellenar_der(" "," ",30);
				$ls_referencia=$this->io_funciones->uf_rellenar_der(" "," ",8);
				$ls_disponible=$this->io_funciones->uf_rellenar_der(" "," ",15);
				$ls_cadena="02".$ls_codcueban.$ls_nacper.$ls_cedper.$ldec_neto.$ls_personal.$ls_otrosdatos."00".$ls_referencia.$ls_disponible."\r\n";
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
			//*-Si estoy acumulando reemplazo la cabecera con la informacion acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=substr($ls_cad_previa,48,102);
				$ldec_montoacumulado=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,17);
				$li_reg_acumulado=$this->io_funciones->uf_cerosizquierda($li_countregacum,7);
				$ls_cadena=substr($ls_cad_previa,0,24).$li_reg_acumulado.$ldec_montoacumulado.$ls_reemplazar;
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
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
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacu));
				}
				unset($new_archivo);
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
	}// end function metodo_banco_provincial
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_sofitasa($as_ruta,$rs_data,$ad_fecproc,$as_codmetban)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_sofitasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // c�digo del m�todo a banco
		//	  Description: genera el archivo txt a disco para  el banco Sofitasa para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_dia=substr($ad_fecproc,0,2); 
		$ls_mes=substr($ad_fecproc,3,2); 
		$ls_ano=substr($ad_fecproc,8,2); 
		$ls_nombrearchivo=$as_ruta."/sofitasa.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,5),5);	
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
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,12);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($rs_data->fields["cedper"],10);
				$ls_cadena=$ls_codempnom.$ls_codcueban.$ls_cedper.$ls_dia.$ls_mes.$ls_ano.$ldec_neto."0"."\r\n";
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
    }// end function uf_metodo_banco_sofitasa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_caroni_v_2($as_ruta,$rs_data,$ad_fecproc)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_sofitasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco caroni version_2 para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
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
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosderecha($ls_codcueban,20);
				$ldec_neto=$rs_data->fields["monnetres"];
				$li_neto_int=substr($ldec_neto,0,17);
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,10);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10); 
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper.", ".$ls_nomper," ",40);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_personal.$ls_codcueban.$ldec_montot."\r\n";
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
	}// end function metodo_banco_caroni_v_2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuela($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_deposito=0;
		$li_total_personal=0;
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/venezuela.txt";
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			//Registro de Cabecera
			$ldec_totdep=$adec_montot;
			$ldec_totdep=round($ldec_totdep,2);  //redondea a 2 decimales
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,90);
				$ldec_monpre=substr($ls_cad_previa,71,13)/100;
				$ldec_monacu=$ldec_monpre+$ldec_totdep;
				$li_total_deposito=$li_total_deposito+$ldec_monacu;
			}
			else
			{
				//Registro Cabecera (D�bito)
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			    $ls_codcueban=$as_codcuenta;
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$li_total_deposito=$li_total_deposito+$ldec_totdep;
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep*100,13);
				$ls_cadena="H".$ls_nombre.$ls_codcueban."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdep."03291"." "."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),10);
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=number_format($ldec_neto,2,".","");
				$li_total_personal=$li_total_personal+$ldec_neto;
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=str_pad($ldec_neto,11,"0",0);
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der(substr($ls_apeper.", ".$ls_nomper,0,40)," ",40);
				$ls_codtipcueban="";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($rs_data->fields["tipcuebanper"]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos l�quidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				$ls_cadena=$ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban.$ls_personal.$ls_cedper."003291  "."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();			
			}
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,13)."03291"." "."\r\n";
				$ls_cadena=substr_replace($ls_cad_previa,$ls_reemplazar,71);
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				$li_total_personal=0;
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
					$li_monto=substr($ls_cadena,21,11)/100;
					$li_total_personal=$li_total_personal+$li_monto;
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
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacu));
				}
				unset($new_archivo);
			}
			if($lb_valido)
			{
				$li_total_personal=number_format($li_total_personal*100,0,"","");
				$li_total_deposito=number_format($li_total_deposito*100,0,"","");
				if(strval($li_total_personal)!=strval($li_total_deposito))
				{
					$this->io_mensajes->message("El Monto de la Cabecera Difiere de la suma del Personal Total Personal = ".$li_total_personal." Total Cabecera = ".$li_total_deposito);
					$lb_valido=@unlink("$ls_nombrearchivo");
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
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_venezuela
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuelaespecial($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuelaespecial
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 02/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_deposito=0;
		$li_total_personal=0;
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/venezuel.txt";
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			//Registro de Cabecera
			$ldec_totdep=$adec_montot;
			$ldec_totdep=number_format($ldec_totdep,2,".","");  //redondea a 2 decimales
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,90);
				$ldec_monpre=substr($ls_cad_previa,71,13)/100;
				$ldec_monacu=$ldec_monpre+$ldec_totdep;
				$li_total_deposito=$li_total_deposito+$ldec_monacu;
			}
			else
			{
				//Registro Cabecera (D�bito)
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			    $ls_codcueban=$as_codcuenta;
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$li_total_deposito=$li_total_deposito+$ldec_totdep;
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep*100,13);
				$ls_cadena="H".$ls_nombre.$ls_codcueban."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdep."03291"." "."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),10);
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=number_format($ldec_neto,2,".","");
				$li_total_personal=$li_total_personal+$ldec_neto;
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=str_pad($ldec_neto,11,"0",0);
				$ls_nomper= strtoupper(trim($rs_data->fields["nomper"]));
				$ls_apeper= strtoupper(trim($rs_data->fields["apeper"]));
				$ls_personal=$this->io_funciones->uf_rellenar_der(substr($ls_apeper." ".$ls_nomper,0,40)," ",40);
				$ls_personal= htmlentities($ls_personal);				
				$ls_personal=str_replace("&ntilde;", "N", $ls_personal);
				$ls_personal=str_replace("&Ntilde;", "N", $ls_personal); 
				$ls_personal=str_replace("Ñ","N",$ls_personal);
				$ls_personal=str_replace("ñ","N",$ls_personal);			
				$ls_personal=str_replace("&acute;", "", $ls_personal); 
				$ls_personal=str_replace("&aacute;", "A", $ls_personal);
				$ls_personal=str_replace("&eacute;", "E", $ls_personal); 
				$ls_personal=str_replace("&iacute;", "I", $ls_personal); 
				$ls_personal=str_replace("&oacute;", "O", $ls_personal); 
				$ls_personal=str_replace("&uacute;", "U", $ls_personal); 
				$ls_personal=str_replace("&uuml;", "U", $ls_personal); 			  
				$ls_personal=str_replace("Ã‘","N",$ls_personal);
				$ls_personal=str_replace("Á","A",$ls_personal);
				$ls_personal=str_replace("É","E",$ls_personal);
				$ls_personal=str_replace("Í","I",$ls_personal);
				$ls_personal=str_replace("Ó","O",$ls_personal); 
				$ls_personal=str_replace("Ú","U",$ls_personal);							
				$ls_personal=str_replace(",","",$ls_personal);
				$ls_personal=str_replace(".","",$ls_personal);				
				$ls_codtipcueban="";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($rs_data->fields["tipcuebanper"]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos l�quidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				$ls_cadena=$ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban.$ls_personal.$ls_cedper."003291  "."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
				$rs_data->MoveNext();		
			}
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,10)."00000"." "."\r\n";
				$ls_cadena=substr_replace($ls_cad_previa,$ls_reemplazar,71);
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				$li_total_personal=0;
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
					$li_monto=substr($ls_cadena,21,11)/100;
					$li_total_personal=$li_total_personal+$li_monto;
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
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacu));
				}
				unset($new_archivo);
			}
			if($lb_valido)
			{
				$li_total_personal=number_format($li_total_personal*100,0,"","");
				$li_total_deposito=number_format($li_total_deposito*100,0,"","");
				if(strval($li_total_personal)!=strval($li_total_deposito))
				{
					$this->io_mensajes->message("El Monto de la Cabecera Difiere de la suma del Personal Total Personal = ".$li_total_personal." Total Cabecera = ".$li_total_deposito);
					$lb_valido=@unlink("$ls_nombrearchivo");
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
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_venezuelaespecial
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuela_sng($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela_sng
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela_SNG para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/venezuel.txt";
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
			$ldec_monpre=0;
			$ldec_monacu=0;
			//Registro de Cabecera
			$ldec_totdep = round($adec_montot, 2);
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,90);
				$ldec_monpre=substr($ls_cad_previa,71,13);
				$ldec_monacu=$ldec_monpre+$ldec_totdep;
			}
			else
			{
				//Registro Cabecera (D�bito)
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcuenta));
				$ls_codcueban=substr($ls_codcueban,0,20);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 20);
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$ldec_totdep=number_format($ldec_totdep,2,".","");
				$ldec_totdep=($ldec_totdep*100);
				$ldec_totdep=number_format($ldec_totdep,0,"","");
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep,13);
				$ls_cadena="H".$ls_nombre.$ls_codcueban."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdep."03291"." "."\r\n";
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
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=number_format($ldec_neto,2,".","");
				$ldec_neto=($ldec_neto*100);
				$ldec_neto=number_format($ldec_neto,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,11);
				$ls_nomper=$this->io_funciones->uf_trim($rs_data->fields["nomper"]);
				$ls_apeper=$this->io_funciones->uf_trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der(substr($ls_apeper.", ".$ls_nomper,0,40)," ",40);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);
				$ls_codtipcueban="";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($rs_data->fields["tipcuebanper"]); 
				if ($ls_tipcuebanper == "C")
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A")
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L")
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				$ls_cadena=$ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban.$ls_personal.$ls_cedper."003291"."  "."\r\n";
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
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda(($ldec_monacu*100),13)."03291"." "."\r\n";
				$ls_cadena=substr_replace($ls_cad_previa,$ls_reemplazar,71);
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
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
					$this->io_mensajes->message("Listado adicionado  "." Monto Acumulado: ".round($ldec_monacu));
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
	}// end function uf_metodo_banco_venezuela_sng
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_lara($as_ruta,$rs_data,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_lara
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//				   $as_codcuenta // C�digo de cuenta donde se hace el d�bito
		//	  Description: genera el archivo txt a disco para  el Banco Lara para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/08/2006 								
		// Modificado Por: 												Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$rs_data->RecordCount();
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,80);
				$ldec_monpre=(substr($ls_cad_previa,26,18)/100);
				$li_countregprev=(substr($ls_cad_previa,44,5));
				$ldec_monacu=($ldec_monpre + $adec_montot);
				$li_countregacum=$li_countregprev+$li_count;
			}
			else
			{	//Registro Cabecera (D�bito)
				$ls_codcueban=str_replace("-","",$as_codcuenta);
				$ls_nomina="0000000000";
				$ls_codcueban=substr($ls_codcueban,0,9);
				$ldec_totdep=$adec_montot*100;  
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep,18);
				$li_contador=$this->io_funciones->uf_cerosizquierda($li_count,5);		
				$ls_fecha=date("dm").substr(date("Y"),2,2);
				$ls_cadena="T".$ls_nomina.$ls_codcueban.$ls_fecha.$ldec_totdep.$li_contador.substr($this->ls_nomemp,0,30)."\r\n";
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
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,9),9);
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper." ".$ls_nomper," ",30);
				$ls_codcueban=trim($rs_data->fields["codcueban"]);
			    $ls_codcueban=substr(str_replace("-","",$ls_codcueban),0,9);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,0,9);
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,14);
				$ls_disponible=$this->io_funciones->uf_rellenar_der(" "," ",17);
				$ls_cadena="E".$ls_cedper.$ls_personal.$ls_codcueban.$ldec_neto.$ls_disponible."\r\n";
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
			//*-Si estoy acumulando reemplazo la cabecera con la informacion acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=substr($ls_cad_previa,49,30);
				$ldec_montoacumulado=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,18);
				$li_reg_acumulado=$this->io_funciones->uf_cerosizquierda($li_countregacum,5);
				$ls_cadena=substr($ls_cad_previa,0,26).$ldec_montoacumulado.$li_reg_acumulado.$ls_reemplazar;
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
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
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacu));
				}
				unset($new_archivo);
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
	}// end function uf_metodo_banco_lara
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banpro($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banpro
		//		   Access: public 
		//	    Arguments: as_ruta // ruta donde se va a guardar el archivo
		//				   aa_ds_banco // arreglo (datastore) datos banco 
		//				   ad_fecproc // Fecha de procesamiento
		//				   as_codcuenta // C�digo de cuenta donde se hace el d�bito
		//				   adec_montot // Monto total a debitar
		//	  Description: genera el archivo txt a disco para BANPRO para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/03/2007 								
		// Modificado Por: 												Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecha=str_replace("/","-",$ad_fecproc);
		$ls_nombrearchivo=$as_ruta."/nomina".$ld_fecha.".txt";
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
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_cedper=substr(str_replace(".","",$ls_cedper),0,9);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$rs_data->fields["codcueban"]));
				$ls_codcueban=substr($ls_codcueban,0,20);
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,15);
				$ls_cadena=$ls_cedper.$ls_codcueban.$ldec_neto."\r\n";
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
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=substr($as_codcueban,0,20);
			$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban,20);
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot*100,15);
			$ls_cadena="000000000".$as_codcueban.$adec_montot."\r\n";
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
	}// end function uf_metodo_banpro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_metodo_banco_banfotran($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot,$as_codmetban)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banfotran
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 adec_montot // Monto total
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//	  Description: genera el archivo txt a disco para  el banco BANFOANDES BANFOTRAN
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 02/05/2007					
		// Modificado Por: 										Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/banfotran.txt";
		$li_count=$rs_data->RecordCount();
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$ls_numconvenio=$this->io_funciones->uf_cerosizquierda(substr($ls_numconvenio,0,8),8);
		if($li_count>0)
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
				// Registro de encabezado
				$as_codcueban=str_replace("-","",trim($as_codcueban));
				$as_codcueban=str_pad(substr($as_codcueban,0,20),20,"0",0);
				$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
				$ad_fecproc=str_replace("/","",$ad_fecproc);
				$ad_fecproc=str_replace("-","",$ad_fecproc);
				$ldec_montot=($adec_montot*100);
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,17);
				$ldec_totreg=$this->io_funciones->uf_cerosizquierda($li_count,4);
				$ls_cadena = $as_codcueban.$ad_fecproc.$ldec_montot.$ldec_totreg."\r\n";
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
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ldec_neto=$rs_data->fields["monnetres"];  //debo verificar si en el ds ya viene modificado la coma decimal
					$ldec_neto=number_format($ldec_neto*100,0,"","");
					//$ldec_neto=($ldec_neto*100);
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,12);
					$ls_codcueban=$rs_data->fields["codcueban"]; //Numero de cuenta del empleado
					$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$ls_codcueban=$this->io_funciones->uf_rellenar_izq(substr(trim($ls_codcueban),0,20),"0",20);
					$ls_cedper=$rs_data->fields["cedper"];   //cedula del personal
					$ls_cedper=$this->io_funciones->uf_trim(str_replace(".","",$ls_cedper));
					$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0", 10);
					$ls_cadena=$ls_codempnom.$ldec_neto.$ls_codcueban.$ls_cedper.$ls_numconvenio."\r\n";
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
    }// end function uf_metodo_banco_banfotran
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuela_pagotaquilla($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela_pagotaquilla
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/venezuela_taquilla.txt";
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
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
			$ls_rifemp=str_replace("-","",$this->ls_rifemp);
			$ls_rifemp=str_pad($ls_rifemp,10,"0",0);
			$ls_nomemp=substr($_SESSION["la_empresa"]["nombre"],0,40);
			$ls_nomemp=str_pad($ls_nomemp,40," ");
			$as_codcuenta=str_replace("-","",$as_codcuenta);
			$as_codcuenta=str_replace(" ","",$as_codcuenta);
			$as_codcuenta=substr($as_codcuenta,0,20);
			$as_codcuenta=str_pad($as_codcuenta,20,"0",0);
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_cedper=substr(str_replace(".","",$ls_cedper),0,10);
				$ls_cedper=str_pad($ls_cedper,10,"0",0);
				$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda(round($ldec_neto*100),15);
				$ls_nombre=$rs_data->fields["apeper"]." ".$rs_data->fields["nomper"];
				$ls_nombre=strtolower($ls_nombre);
				$ls_nombre=str_replace(".","",$ls_nombre);
				$ls_nombre=str_replace(",","",$ls_nombre);
				$ls_nombre=str_replace("�","",$ls_nombre);
				$ls_nombre=str_replace("�","a",$ls_nombre);
				$ls_nombre=str_replace("�","e",$ls_nombre);
				$ls_nombre=str_replace("�","i",$ls_nombre);
				$ls_nombre=str_replace("�","o",$ls_nombre);
				$ls_nombre=str_replace("�","u",$ls_nombre);
				$ls_nombre=strtoupper($ls_nombre);
				$ls_nombre=str_pad($ls_nombre,40," ");
				$ls_cadena="G".$ls_rifemp.$ls_nomemp.$as_codcuenta."V".$ls_cedper.$ls_nombre."9117".$ldec_neto."\r\n";
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
	}// end function uf_metodo_banco_venezuela_pagotaquilla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_metodo_banco_mercantilonline($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot,$as_codmetban)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_mercantilonline
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 adec_montot // Monto total
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//	  Description: genera el archivo txt a disco para  el banco MERCANTIL ONLINE
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 17/10/2007					
		// Modificado Por: 										Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/mercantil_online.txt";
		$li_count=$rs_data->RecordCount();
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$ls_numconvenio=$this->io_funciones->uf_cerosizquierda(substr($ls_numconvenio,0,6),6);
		$li_numlote=$this->io_sno->uf_select_config("SNO","GEN_DISK","MERCANIL_ONLINE","1","I");
		$li_numlote=intval($this->io_funciones->uf_trim($li_numlote));
		$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","MERCANIL_ONLINE",$li_numlote+1,"I");
		if($li_count>0)
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
				// Registro de encabezado
				$ls_tipreg="00";
				$ls_rifemp=$this->io_funciones->uf_rellenar_izq(str_replace("-","",$this->ls_rifemp),"0",11);
				$ls_desnom=str_pad(substr($aa_ds_banco->data["desnom"][1],0,20),20," ");
				$ls_banco="105";
				$ls_moneda="VEF";
			    $as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
				$li_inicio=strlen($as_codcueban)-10;
				$as_codcueban=substr($as_codcueban,$li_inicio,10);
				$ldec_montot=number_format($adec_montot,2,".","");
				$ldec_montot=($adec_montot*100);
				$ldec_montot=number_format($adec_montot,0,"","");
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
				$ldec_totreg=$this->io_funciones->uf_cerosizquierda($li_count,5);
				$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
				$ad_fecproc=str_replace("/","",$ad_fecproc);
				$ad_fecproc=str_replace("-","",$ad_fecproc);
				$ls_cadena = $ls_tipreg.$ls_numconvenio.$ls_rifemp.$ls_desnom.$li_numlote.$ls_banco.$ls_moneda.$as_codcueban.$ldec_montot.$ldec_totreg.$ad_fecproc."\r\n";
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
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$li_tipregistro="01";
					$ls_nacper=$rs_data->fields["nacper"]; //Nacionalidad del Personal
					$ls_cedper=$rs_data->fields->data["cedper"];   //cedula del personal
					$ls_cedper=$this->io_funciones->uf_trim(str_replace(".","",$ls_cedper));
					$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0", 10);
					$ls_nomper=rtrim($rs_data->fields["nomper"]);   //Nombre del personal
					$ls_apeper=rtrim($rs_data->fields["apeper"]);   //Apellido del personal
					$ls_empleado=$ls_apeper." ".$ls_nomper;
					$ls_empleado=str_pad(substr($ls_empleado,0,60),60," ");
					$ls_formapago="1";
					$ls_banco="105";
					$ls_codcueban=$rs_data->fields["codcueban"]; //Numero de cuenta del empleado
					$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$li_inicio=strlen($as_codcueban)-10;
					$ls_codcueban=substr($ls_codcueban,$li_inicio,10);
					$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");  //
					$ldec_neto=$ldec_neto*100;
					$ldec_neto=number_format($ldec_neto,0,"","");
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
					$ls_cadena=$li_tipregistro.$ls_nacper.$ls_cedper.$ls_empleado.$ls_formapago.$ls_banco.$ls_codcueban.$ldec_neto.$ldec_neto."\r\n";
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
    }// end function uf_metodo_banco_mercantilonline
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuela_prepagoabono($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_depositoabono=0;
		$lb_valido=true;
		$ls_nombrearchivo1=$as_ruta."/venezuela_abono.txt";
		$ls_nombrearchivo2=$as_ruta."/venezuela_prepago.txt";
		$li_count=$rs_data->RecordCount();
		$ls_rifemp=$this->io_funciones->uf_rellenar_izq(str_replace("-","",$this->ls_rifemp)," ",10);
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo1"))
			{
				$ls_creararchivo1=fopen("$ls_nombrearchivo1","a+"); // abrimos el archivo que ya existe
				$lb_adicionado1=true;
			}
			else
			{
				$ls_creararchivo1=@fopen("$ls_nombrearchivo1","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado1=false;
			}
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo2"))
			{
				$ls_creararchivo2=fopen("$ls_nombrearchivo2","a+"); // abrimos el archivo que ya existe
				$lb_adicionado2=true;
			}
			else
			{
				$ls_creararchivo2=@fopen("$ls_nombrearchivo2","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado2=false;
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//Registro de Cabecera Archivo 1
			//$ldec_totdepabono=number_format($aa_ds_banco->data["totalabono"][1],2,".","");
			$ldec_totdepabono=number_format($rs_data->fields["totalabono"],2,".","");
			if ($lb_adicionado1)
			{
				$ls_cad_previaabono=fgets($ls_creararchivo1,90);
				$ldec_monpreabono=substr($ls_cad_previaabono,71,13)/100;
				$ldec_monacuabono=$ldec_monpreabono+$ldec_totdepabono;
				$li_total_depositoabono=$li_total_depositoabono+$ldec_monacuabono;
			}
			else
			{
				//Registro Cabecera (D�bito)
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			    $ls_codcueban=$as_codcuenta;
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad(substr($ls_codcueban,0,20),20," ",0);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$li_total_deposito=$li_total_deposito+$ldec_totdep;
				$ldec_totdepabono=$this->io_funciones->uf_cerosizquierda($ldec_totdepabono*100,13);
				$ls_cadena="H".$ls_nombre.$ls_codcueban."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdepabono."03291"." "."\r\n";
				if ($ls_creararchivo1)
				{
					if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo1);
					$lb_valido=false;
				}		
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//Registro de Cabecera Archivo 2
			$ldec_totdepprepago=number_format($rs_data->fields["totalprepago"],2,".","");
			if ($lb_adicionado2)
			{
				$ls_cad_previaprepago=fgets($ls_creararchivo2,129);
				$ldec_monpreprepago=substr($ls_cad_previaprepago,71,13)/100;
				$ldec_monacuprepago=$ldec_monpreprepago+$ldec_totdepprepago;
				$li_total_depositoprepago=$li_total_depositoprepago+$ldec_monacuprepago;
				$ldec_cantidadprevia=substr($ls_cad_previaprepago,121,7);
			}
			else
			{
				//Registro Cabecera (D�bito)
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			    $ls_codcueban=$as_codcuenta;
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad(substr($ls_codcueban,0,20),20," ",0);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$li_total_deposito=$li_total_deposito+$ldec_totdep;
				$ldec_totdepprepago=$this->io_funciones->uf_cerosizquierda($ldec_totdepprepago*100,13);
				$li_cantidad=str_pad($rs_data->fields["nroprepago"],7,"0",0);
				$ls_cadena="H".$ls_nombre."00000000000000000000"."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdepprepago."03291"."   "."0102"."000000000000001".$ls_rifemp.$li_cantidad."P"."\r\n";
				if ($ls_creararchivo2)
				{
					if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo2);
					$lb_valido=false;
				}		
			}
			$li_totprepago=0;
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=trim($this->io_funciones->uf_trim($rs_data->fields["cedper"]));
				$ls_cedperaux=$ls_cedper;
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),10);
				$ls_codcueban=$rs_data->fields["codcueban"];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcuebanaux=$ls_codcueban;
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=number_format($ldec_neto,2,".","");
				$li_total_personal=$li_total_personal+$ldec_neto;
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=str_pad($ldec_neto,11,"0",0);
				$ls_nomper=rtrim($rs_data->fields["nomper"]);
				$ls_apeper=rtrim($rs_data->fields["apeper"]);
				$ls_personal=ltrim(rtrim(substr($ls_apeper.", ".$ls_nomper,0,40)));
				
				$ls_personal=strtolower($ls_personal);
				$ls_personal=str_replace(".","",$ls_personal);
				$ls_personal=str_replace(",","",$ls_personal);
				$ls_personal=str_replace("�","n",$ls_personal);
				$ls_personal=str_replace("�","a",$ls_personal);
				$ls_personal=str_replace("�","e",$ls_personal);
				$ls_personal=str_replace("�","i",$ls_personal);
				$ls_personal=str_replace("�","o",$ls_personal);
				$ls_personal=str_replace("�","u",$ls_personal);

				$ls_personal=str_replace("�","N",$ls_personal);
				$ls_personal=str_replace("�","A",$ls_personal);
				$ls_personal=str_replace("�","E",$ls_personal);
				$ls_personal=str_replace("�","I",$ls_personal);
				$ls_personal=str_replace("�","O",$ls_personal);
				$ls_personal=str_replace("�","U",$ls_personal);
				$ls_personal=strtoupper($ls_personal);

				$ls_personal=str_pad($ls_personal,40," ");
				$ls_codtipcueban="";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($rs_data->fields["tipcuebanper"]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos l�quidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				if($ls_codcuebanaux==$ls_cedperaux)
				{
					$li_totprepago++;
					$ls_totprepago=str_pad($ls_totprepago,15,"0",0);

					$ls_cadena="1"."00000000000000000000".$ldec_neto."    ".$ls_personal.$ls_cedper."003291"."0102".$ls_totprepago.$ls_rifemp."        "."\r\n";
					if ($ls_creararchivo2)
					{
						if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo2);
						$lb_valido=false;
					}
				}
				else
				{
					$ls_cadena=$ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban.$ls_personal.$ls_cedper."003291  "."\r\n";
					if ($ls_creararchivo1)
					{
						if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo1);
						$lb_valido=false;
					}
				}
				$rs_data->MoveNext();		
			}
			// para el archivo 1
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado1))
			{
				$ls_reemplazar=$ldec_monacuabono=$this->io_funciones->uf_cerosizquierda($ldec_monacuabono*100,13)."03291"." "."\r\n";
				$ls_cadena=substr_replace($ls_cad_previaabono,$ls_reemplazar,71);
				$new_archivo=file("$ls_nombrearchivo1"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo1); //cerramos la conexi�n y liberamos la memoria
				if (file_exists("$ls_nombrearchivo1"))
				{
					if(@unlink("$ls_nombrearchivo1")===false)//Borrar el archivo de texto existente para crearlo nuevo.
					{
						$lb_valido=false;
					}
					else
					{
						$ls_creararchivo1=@fopen("$ls_nombrearchivo1","a+");
					}
				}
				if($ls_creararchivo1)
				{
					if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo1);
					$lb_valido=false;
				}		
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				$li_total_personal=0;
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
					$li_monto=substr($ls_cadena,21,11)/100;
					$li_total_personal=$li_total_personal+$li_monto;
					if ($ls_creararchivo1)
					{
						if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo1);
						$lb_valido=false;
					}		
				}
				if ($lb_valido)
				{
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacuabono));
				}
				unset($new_archivo);
			}
			// para el archivo 2
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado2))
			{
				$ldec_cantidadprevia=intval($ldec_cantidadprevia);
				$li_cantidad=intval($aa_ds_banco->data["nroprepago"][1]);
				$li_cantidad=$ldec_cantidadprevia+$li_cantidad;
				$li_cantidad=str_pad($li_cantidad,7,"0",0);
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda($ldec_monacuprepago*100,13)."03291"."   "."0102"."000000000000001".$ls_rifemp.$li_cantidad."P"."\r\n";
				$ls_cadena=substr_replace($ls_cad_previaprepago,$ls_reemplazar,71);
				$new_archivo=file("$ls_nombrearchivo2"); //creamos el array con las lineas del archivo
				@fclose($ls_creararchivo2); //cerramos la conexi�n y liberamos la memoria
				if(file_exists("$ls_nombrearchivo2"))
				{
					if(@unlink("$ls_nombrearchivo2")===false)//Borrar el archivo de texto existente para crearlo nuevo.
					{
						$lb_valido=false;
					}
					else
					{
						$ls_creararchivo2=@fopen("$ls_nombrearchivo2","a+");
					}
				}
				if($ls_creararchivo2)
				{
					if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo2);
					$lb_valido=false;
				}		
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				$li_total_personal=0;
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
					$li_monto=substr($ls_cadena,21,11)/100;
					$li_total_personal=$li_total_personal+$li_monto;
					if ($ls_creararchivo2)
					{
						if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo2);
						$lb_valido=false;
					}		
				}
				if ($lb_valido)
				{
					$this->io_mensajes->message("Listado adicionado  Monto Acumulado: ".round($ldec_monacuprepago));
				}
				unset($new_archivo);
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("los archivos fueron creados.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar los archivoa por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_venezuela
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_federal($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope,$ad_fdesde,$ad_fhasta,
	                                 $adec_montot,$as_tipquincena) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_fondo_comun
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c�digo de m�todo a banco 
		//                 as_desope // descripci�n de operaci�n
		//	  Description: genera el archivo txt a disco para  el banco Fondo Comun para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 05/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_nrodebitos=1; // se inicializa en uno, por que solo hay un registro y no es variable
		$li_nrocreditos=0;
		$ls_mondeb=0;
		$ls_moncre=0;
		$ldec_monto=0;
		$lb_valido=false;		
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,3),3);	
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
			if($lb_valido)
			{
				//Registro de Encabezado
				$as_identificacion="001";//USO INTERNO
				$as_campolibre1="00000";//USO INTERNO
				$ai_totalreg=str_pad(($li_count+1),7,"0",0);
				$as_campolibre2="0000";//USO INTERNO
				$as_mespago=substr($ad_fecproc,3,2);
				$as_dia=substr($ad_fecproc,0,2);
				$as_quincena="00";
				//switch($aa_ds_banco->data["tippernom"][1])
				switch($rs_data->fields["tippernom"])
				{
					case "0": // Semanal
						$as_quincena="03";
					break;
					case "1": // Quincenal
						if(intval($as_dia)<=15)
						{
							$as_quincena="01";
						}
						else
						{
							$as_quincena="02";
						}
					break;
					case "2": // Mensual
						$as_quincena=str_pad($as_tipquincena,2,"0",0);
					break;
				}
				$as_codigemp=$ls_codempnom;//FALTA
				$as_codigemp=substr($as_codigemp,3,3);
				$adec_montot=number_format($adec_montot,2,".","");
				$adec_montot=number_format(($adec_montot*100),0,"","");
				$adec_montot=str_pad($adec_montot,13,"0",0);
				$as_montocred=$adec_montot;
				$as_montodeb=$adec_montot;
				$as_formatcuenta="N";//USO INTERNO
				$as_campolibre3="0000000000000000000";//USO INTERNO
				$this->ls_rifemp=str_replace("-","",$this->ls_rifemp);
				$ld_fecha=str_replace("-","",$ad_fecproc);
				$ld_fecha=str_replace("/","",$ld_fecha);
				$ls_cadena=$as_identificacion.$as_campolibre1.$ai_totalreg.$as_campolibre2.$as_mespago.$as_quincena.$ls_codempnom.$as_montocred.$as_montodeb.$ld_fecha.$as_formatcuenta.$as_campolibre3."\r\n";
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
				$as_desope=$this->io_funciones->uf_rellenar_der($as_desope," ",40);
				$ldec_moncre=0;
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_identificacion="770";
					$ls_codcueban=$rs_data->fields["codcueban"];
					$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
					$ls_campolibre4="0000";
					$ls_campolibre5="000000000000000000000000000000000";
					$ls_nacper=$rs_data->fields["nacper"];
					$ls_cedper=$rs_data->fields["cedper"];
					$ls_cedper=str_pad($ls_cedper,10,"0",0);
					$ldec_neto=$rs_data->fields["monnetres"];
					$ldec_neto=($ldec_neto*100);  
					$ldec_moncre=$ldec_moncre+$ldec_neto;
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
					$ls_cadena=$ls_identificacion.$ls_codcueban.$ls_campolibre4.$as_mespago.$as_quincena.$ls_codempnom.$ldec_neto.$ls_campolibre5."\r\n";
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
				//PARA EL D�BITO
				$ls_identificacion="670";
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ls_cadena=$ls_identificacion.$ls_codcueban.$ls_campolibre4.$as_mespago.$as_quincena.$ls_codempnom.$adec_montot.$ls_campolibre5;				
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
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;		
    }// end function uf_metodo_banco_federal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_agricola($as_ruta,$rs_data) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_agricola
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c�digo de m�todo a banco 
		//                 as_desope // descripci�n de operaci�n
		//	  Description: genera el archivo txt a disco para  el banco Fondo Comun para pago de nomina
		//	   Creado Por: Ing. Yesenia Moreno 
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por:															Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_nombrearchivo=$as_ruta."/agricola.txt";
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
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_cedper=str_pad(trim($rs_data->fields["cedper"]),14,"0",0);
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,12),12);
				$ldec_neto=number_format($rs_data->fields["monnetres"],2,".","");
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=number_format($ldec_neto,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ldec_neto."0";
				if($li_i<$li_count)
				{
					$ls_cadena=$ls_cadena."\r\n";
				}
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
    }
	// end function uf_metodo_banco_agricola
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	//-----------------------------------------------------------------------------------------------------------------------------------
/*
function uf_metodo_banco_agricola($as_ruta,$ad_ds_banco,$ad_fecproc,$as_codcuenta,$adec_montot)
	{
 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // Código de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. María Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_deposito=0;
		$li_total_personal=0;
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/agricola.txt";
		$li_count=$ad_ds_banco->getRowCount("codcueban");
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			//Registro de Cabecera
			$ldec_totdep=$adec_montot;
			$ldec_totdep=round($ldec_totdep,2);  //redondea a 2 decimales
			
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
			    //$ls_nacper      = $ad_ds_banco->data["nacper"][$li_i];
				
				
				$ls_cedper      = $this->io_funciones->uf_trim($ad_ds_banco->data["cedper"][$li_i]);
				$ls_nacper      = $this->io_metbanco->uf_nacionalidad($ls_cedper);
				$ls_cedper      = $this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),14);
				$ls_codcueban   = trim($ad_ds_banco->data["codcueban"][$li_i]);
			    $ls_codcueban   = $this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ldec_neto      = $ad_ds_banco->data["monnetres"][$li_i];  
				$ldec_neto      = round($ldec_neto,2);
				$li_total_personal=$li_total_personal+$ldec_neto;
				$ldec_neto      = number_format($ldec_neto*100,0,"","");
				$ldec_neto      = str_pad($ldec_neto,15,"0",0);
				$ls_nomper      = trim($ad_ds_banco->data["nomper"][$li_i]);
				$ls_apeper      = trim($ad_ds_banco->data["apeper"][$li_i]);
				$ls_personal    = $this->io_funciones->uf_rellenar_der(substr($ls_apeper." ".$ls_nomper,0,40)," ",40);
				$ls_codtipcueban= "";
				$ls_tipcuebanper= $this->io_funciones->uf_trim($ad_ds_banco->data["tipcuebanper"][$li_i]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos líquidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ldec_neto."0"."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
						
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }
*/
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banagricola($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codcuenta,$adec_montot,$ls_mesdes)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // Código de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. María Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_fecha.php");
	    $io_fecha=new class_fecha();
		
		$li_total_deposito= 0;
		$li_total_personal= 0;
		$lb_valido        = true;
		$ldec_monpre      = 0;
		$ldec_monacu      = 0;
		$ls_nombrearchivo = $as_ruta."/fidei_agricola.txt";
		$li_count         = $aa_ds_banco->getRowCount("cedper");
		$ls_ano2          = $_SESSION["la_empresa"]["periodo"];
		$ls_ano           = substr($ls_ano2,0,4);
		$ls_final_mes     = $io_fecha->uf_last_day($ls_mesdes,$ls_ano);
		$ls_final_mes     = $io_fecha->uf_convert_date_to_db($ls_final_mes);
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=false;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			//Registro de Cabecera
			$ldec_totdep=$adec_montot;
			$ldec_totdep=round($ldec_totdep,2);  //redondea a 2 decimales
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,90);
				$ldec_monpre  =substr($ls_cad_previa,71,13)/100;
				$ldec_monacu  =$ldec_monpre+$ldec_totdep;
				$li_total_deposito=$li_total_deposito+$ldec_monacu;
			}
			else
			{
				//Registro Cabecera (Débito)
				$ls_nombre     =$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			    $ls_codcueban  =$as_codcuenta;
			    $ls_codcueban  =$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban  =$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$arr_date = getdate();
				$li_dia   = $arr_date['mday'];
				$li_mes   = $arr_date['mon'];
				$li_ano   = $arr_date['year'];
								
				$li_mes   = $this->io_funciones->uf_cerosizquierda($li_mes,2);
				$li_dia   = $this->io_funciones->uf_cerosizquierda($li_dia,2);
				
				$li_total_deposito = $li_total_deposito+$ldec_totdep;
				$ldec_totdep       = round($ldec_totdep,2);
				
				$ldec_totdep       = $this->io_funciones->uf_cerosizquierda($ldec_totdep*100,15);
				$li_countt         = $li_count;
				$li_countt         = $this->io_funciones->uf_cerosizquierda($li_countt,5);
				$ls_codfideicomiso = "0000";
				$ls_cadena         = $ls_codfideicomiso.$li_ano.$li_mes.$li_dia.$li_countt.$ldec_totdep."\r\n";
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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_cedper     = $this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper     = $this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),12);
				$ls_codcueban  = $aa_ds_banco->data["codcueban"][$li_i];
			    $ls_codcueban  = $this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban  = $this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ldec_neto     = $aa_ds_banco->data["monnetres"][$li_i];  
				$ldec_neto     = round($ldec_neto,2);
				$ldec_neto     = number_format($ldec_neto*100,0,"","");
				$ldec_neto     = str_pad($ldec_neto,11,"0",0);
				
				$ls_nomper     = trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper     = trim($aa_ds_banco->data["apeper"][$li_i]);
				
				$ld_sueintper  = trim($aa_ds_banco->data["sueintper"][$li_i]);
				$ld_fecing     = $aa_ds_banco->data["fecingper"][$li_i];
				$ld_dialab     = $aa_ds_banco->data["dias"][$li_i];
				$ls_fecha      = substr($ld_fecing,8,2)."-".substr($ld_fecing,5,2)."-".substr($ld_fecing,0,4);	
				$ls_mesing     = substr($ld_fecing,5,2);	
								
				$ls_personal   = $ls_apeper." ".$ls_nomper;
				$ls_codtipcueban = "";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($aa_ds_banco->data["tipcuebanper"][$li_i]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos líquidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				
				/////  CALCULO PARA EL FIDEICOMISO  //////////
				
				$ld_saldia   = $ld_sueintper/30;//1
				$ld_fracbv   = ($ld_saldia*(40/360));//3			
				$ld_frabfa   = (($ld_saldia+$ld_fracbv)*90)/360;
				$ld_salint   = ($ld_saldia+$ld_fracbv+$ld_frabfa);
				$li_diabas   = 5; //CONDIFICONAL
				
				if($ls_mesing===$ls_mesdes)
				{ 
					  $ld_aemp    = $ld_dialab/360;	
					  $ld_aemp    = number_format($ld_aemp,0,"","");
					  
					  if($ld_aemp>=2)
					  {
						  $ld_aemp    = $ld_aemp-1;
						  $ld_aemp    = $ld_aemp*2;
						  $ld_aemp    = $ld_aemp+$li_diabas;
						  $li_dia_108 = $ld_aemp;
					  }
					  else
					  {
						  $li_dia_108 = 5; //CONDIFICONAL
					  }
				}
				else
				{
					  $li_dia_108 = 5; //CONDIFICONAL
				}
				
				$ld_total     = $ld_salint*$li_dia_108;	
				$ld_total     = round($ld_total,2);
				$ldec_monacu  = $ldec_monacu + $ld_total;	
				$ld_sumtotal  = $ld_sumtotal + $ld_total;		
				$li_total_personal= $li_total_personal+$ld_total;
				
				$ld_total     = $this->io_funciones->uf_cerosizquierda($ld_total*100,15);
				
				/////  CALCULO PARA EL FIDEICOMISO  //////////
				///$ls_cadena=$ls_cedper.$ls_personal. $ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban..."003291  "."\r\n";
                $ls_codtipcueban="00000000000000000000";

                $ls_cadena=$ls_cedper.$ls_personal.$ld_total.$ls_codtipcueban."\r\n";

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
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
					
			}
			
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_banagricola
	//-----------------------------------------------------------------------------------------------------------------------------------

	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_federal_consolidado($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope,$ad_fdesde,$ad_fhasta, $adec_montot,$as_tipquincena) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_federal_consolidado
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c�digo de m�todo a banco 
		//                 as_desope // descripci�n de operaci�n
		//	  Description: genera el archivo txt a disco para  el Banco Federal
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 12/11/2008 								
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_nrodebitos=1; // se inicializa en uno, por que solo hay un registro y no es variable
		$li_nrocreditos=0;
		$ls_mondeb=0;
		$ls_moncre=0;
		$ldec_monto=0;
		$lb_valido=false;		
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,3),3);	
		$li_count=$rs_data->RecordCount();
		if(($li_count>0)&&($lb_valido))
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
			$ldec_mondebpre=0;
			$ldec_moncrepre=0;
			$ldec_mondebacu=0;
			$ldec_moncreacu=0;
			$li_totalreg=0;
			//Registro de Encabezado
				$as_identificacion="001";//USO INTERNO
				$as_campolibre1="00000";//USO INTERNO
				$ai_totalreg=str_pad(($li_count+1),7,"0",0);
				$as_campolibre2="0000";//USO INTERNO
				$as_mespago=substr($ad_fecproc,3,2);
				$as_dia=substr($ad_fecproc,0,2);
				$as_quincena="00";
				//switch($aa_ds_banco->data["tippernom"][1])
				switch($rs_data->fields["tippernom"])
				{
					case "0": // Semanal
						$as_quincena="03";
						break;
					case "1": // Quincenal
						if(intval($as_dia)<=15)
						{
							$as_quincena="01";
						}
						else
						{
							$as_quincena="02";
						}
						break;
					case "2": // Mensual
						$as_quincena=str_pad($as_tipquincena,2,"0",0);
						break;
				}
			$ldec_totdep = round($adec_montot, 2);
			$adec_montot=number_format($adec_montot,2,".","");
			$adec_montot=number_format(($adec_montot*100),0,"","");
			$adec_montot=str_pad($adec_montot,13,"0",0);
			if ($lb_adicionado)
			{
				$ls_cad_previa=fgets($ls_creararchivo,81);
				$ldec_mondebpre=substr($ls_cad_previa,26,13);
				$ldec_mondebacu=number_format(($ldec_mondebpre/100),2,'.','')+$ldec_totdep;
				$ldec_moncrepre=substr($ls_cad_previa,39,13);
				$ldec_moncreacu=number_format(($ldec_moncrepre/100),2,'.','')+$ldec_totdep;
				$li_totalregpre=substr($ls_cad_previa,8,7);
				$li_totalregacu=$li_totalregpre+$li_count+1;
			}
			else
			{
				if($lb_valido)
				{
					
					$as_codigemp=$ls_codempnom;//FALTA
					$as_codigemp=substr($as_codigemp,3,3);					
					$as_montocred=$adec_montot;
					$as_montodeb=$adec_montot;
					$as_formatcuenta="N";//USO INTERNO
					$as_campolibre3="0000000000000000000";//USO INTERNO
					$this->ls_rifemp=str_replace("-","",$this->ls_rifemp);
					$ld_fecha=str_replace("-","",$ad_fecproc);
					$ld_fecha=str_replace("/","",$ld_fecha);
					$ls_cadena=$as_identificacion.$as_campolibre1.$ai_totalreg.$as_campolibre2.$as_mespago.$as_quincena.$ls_codempnom.$as_montocred.$as_montodeb.$ld_fecha.$as_formatcuenta.$as_campolibre3."\r\n";
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
			}
			if($lb_valido)
			{
				$as_desope=$this->io_funciones->uf_rellenar_der($as_desope," ",40);
				$ldec_moncre=0;
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_identificacion="770";
					$ls_codcueban=$rs_data->fields["codcueban"];
					$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
					$ls_campolibre4="0000";
					$ls_campolibre5="000000000000000000000000000000000";
					$ls_nacper=$rs_data->fields["nacper"];
					$ls_cedper=$rs_data->fields["cedper"];
					$ls_cedper=str_pad($ls_cedper,10,"0",0);
					$ldec_neto=$rs_data->fields["monnetres"];
					$ldec_neto=($ldec_neto*100);  
					$ldec_moncre=$ldec_moncre+$ldec_neto;
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
					$ls_cadena=$ls_identificacion.$ls_codcueban.$ls_campolibre4.$as_mespago.$as_quincena.$ls_codempnom.$ldec_neto.$ls_campolibre5."\r\n";
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
				//PARA EL D�BITO
				$ls_identificacion="670";
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ls_cadena=$ls_identificacion.$ls_codcueban.$ls_campolibre4.$as_mespago.$as_quincena.$ls_codempnom.$adec_montot.$ls_campolibre5."\r\n";				
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
			//*-Si estoy acumulando reemplazo la cabecera con la informaci�n acumulada
			if (($lb_valido)&&($lb_adicionado))
			{
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda($li_totalregacu,7);				
				$ls_cad_previa=substr_replace($ls_cad_previa,$ls_reemplazar,8,7);
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda(($ldec_mondebacu*100),13);				
				$ls_cad_previa=substr_replace($ls_cad_previa,$ls_reemplazar,26,13);
				$ls_reemplazar=$this->io_funciones->uf_cerosizquierda(($ldec_moncreacu*100),13);				
				$ls_cadena=substr_replace($ls_cad_previa,$ls_reemplazar,39,13)."\r\n";				
				$new_archivo=file("$ls_nombrearchivo"); //creamos el array con las lineas del archivo
				
				@fclose($ls_creararchivo); //cerramos la conexi�n y liberamos la memoria
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
				$li_numlin=count($new_archivo); //contamos los elementos del array, es decir el total de lineas
				for($li_i=1;(($li_i<$li_numlin)&&($lb_valido));$li_i++)
				{
					$ls_cadena=$new_archivo[$li_i];
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
					$this->io_mensajes->message("Listado adicionado  "." Numero de registros agregados: ".$li_count. " Numero total de registros ".$li_totalregacu);
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
    }// end function uf_metodo_banco_federal_consolidado
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_provincial_pensiones($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial_pensiones
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcuenta // c�digo de cuenta a debitar
		//                 adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el Banco Provincial para pago de nomina
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/01/2009 										
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_rifemp=$this->io_funciones->uf_rellenar_izq(str_replace("-","",$this->ls_rifemp)," ",10);
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
			//Registro tipo E
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcueban=substr($rs_data->fields["codcueban"],0,20);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban," ",20);
				$ldec_neto=$rs_data->fields["monnetres"]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,17);
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",15);
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper.", ".$ls_nomper," ",40);
				$ls_nacper=$rs_data->fields["nacper"];
				$ls_otrosdatos=$this->io_funciones->uf_rellenar_der(" "," ",30);
				$ls_referencia=$this->io_funciones->uf_rellenar_der(" "," ",8);
				$ls_disponible=$this->io_funciones->uf_rellenar_der(" "," ",15);
				$ls_cadena="02".$ls_codcueban.$ls_nacper.$ls_cedper.$ldec_neto.$ls_personal.$ls_otrosdatos."00".$ls_referencia.$ls_disponible."\r\n";
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
	}// end function metodo_banco_provincial_pensiones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_venezuela_pensiones($as_ruta,$rs_data,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_venezuela_pensiones
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//                 ad_fecproc   // fecha de procesamiento
		//                 as_codcuenta   // C�digo de cuenta a debitar
		//                 adec_montot   // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco Venezuela para pago de nomina
		//	   Creado Por: Ing. Mar�a Roa
		// Fecha Creaci�n: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha �ltima Modificaci�n : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_deposito=0;
		$li_total_personal=0;
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo1=$as_ruta."/venezuela_10digitos.txt";
		$ls_nombrearchivo2=$as_ruta."/venezuela_20digitos.txt";
		$li_count=$rs_data->RecordCount();
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo1"))
			{
				$ls_creararchivo1=fopen("$ls_nombrearchivo1","a+"); // abrimos el archivo que ya existe				
			}
			else
			{
				$ls_creararchivo1=@fopen("$ls_nombrearchivo1","a+"); //creamos y abrimos el archivo para escritura
			
			}

			if (file_exists("$ls_nombrearchivo2"))
			{
				$ls_creararchivo2=fopen("$ls_nombrearchivo2","a+"); // abrimos el archivo que ya existe
				
			}
			else
			{
				$ls_creararchivo2=@fopen("$ls_nombrearchivo2","a+"); //creamos y abrimos el archivo para escritura
				
			}

			//Registro de Cabecera
			
				$ls_nombre=$this->io_funciones->uf_rellenar_der(substr($this->ls_nomemp,0,40)," ",40);
			        $ls_codcueban=$as_codcuenta;
			        $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,8,2);
				$li_total_deposito=$li_total_deposito+$ldec_totdep;
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($ldec_totdep*100,13);
				$ls_cadena="H".$ls_nombre.$ls_codcueban."01".$li_dia."/".$li_mes."/".$li_ano.$ldec_totdep."03291"." "."\r\n";
				/*if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}*/		
			$lb_titulo1=false;
			$lb_titulo2=false;
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_cedper=$this->io_funciones->uf_trim($rs_data->fields["cedper"]);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(substr($ls_cedper,0,10),10);
				$ls_codcueban=$rs_data->fields["codcueban"];
			        $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				if (strlen($ls_codcueban)==10)
				{
					if($lb_titulo1===false)
					{					
						if ($ls_creararchivo1)
						{
							if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
							{
								$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
								$lb_valido = false;
							}
							else
							{
								$lb_titulo1=true;
							}
						}
						else
						{
							$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo1);
							$lb_valido = false;
						}
					}		
				}
				else
				{
					if($lb_titulo2===false)
					{					
						if ($ls_creararchivo2)
						{
							if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
							{
								$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
								$lb_valido = false;
							}
							else
							{
								$lb_titulo2=true;
							}
						}
						else
						{
							$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo2);
							$lb_valido = false;
						}
					}		
				}
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda(substr($ls_codcueban,0,20),20);
				$ldec_neto=$rs_data->fields["monnetres"];  
				$ldec_neto=number_format($ldec_neto,2,".","");
				$li_total_personal=$li_total_personal+$ldec_neto;
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=str_pad($ldec_neto,11,"0",0);
				$ls_nomper=trim($rs_data->fields["nomper"]);
				$ls_apeper=trim($rs_data->fields["apeper"]);
				$ls_personal=$this->io_funciones->uf_rellenar_der(substr($ls_apeper.", ".$ls_nomper,0,40)," ",40);
				$ls_codtipcueban="";
				$ls_tipcuebanper = $this->io_funciones->uf_trim($rs_data->fields["tipcuebanper"]); 
				if ($ls_tipcuebanper == "C")// cuenta corriente
				{
					$ls_tipcuebanper = "0";
					$ls_codtipcueban = "0770";
				}
				if ($ls_tipcuebanper == "A") // cuenta de ahorro
				{
					$ls_tipcuebanper = "1";
					$ls_codtipcueban = "1770";
				}
				if ($ls_tipcuebanper == "L") // fondo de activos l�quidos
				{
					$ls_tipcuebanper = "2";
					$ls_codtipcueban = "1770";
				}
				$ls_cadena=$ls_tipcuebanper.$ls_codcueban.$ldec_neto.$ls_codtipcueban.$ls_personal.$ls_cedper."003291  "."\r\n";
				if (strlen($ls_codcueban)==10)
				{
					if ($ls_creararchivo1)
					{
						if (@fwrite($ls_creararchivo1,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo1);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo1);
						$lb_valido=false;
					}
				}
				else
				{
					if ($ls_creararchivo2)
					{
						if (@fwrite($ls_creararchivo2,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo2);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo2);
						$lb_valido=false;
					}
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
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_venezuela_pensiones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_fonz03_militar($as_ruta,$rs_data) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_fonz03_militar
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c�digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c�digo de m�todo a banco 
		//                 as_desope // descripci�n de operaci�n
		//	  Description: genera el archivo txt a disco para  el banco Fondo Comun para pago de nomina
		//	   Creado Por: Ing. Maria Beatriz Unda
		// Fecha Creaci�n: 28/01/2009								
		// Modificado Por:		             Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_nombrearchivo=$as_ruta."/fonz03.txt";		
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
			if($lb_valido)
			{
				$li_i=0;
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$li_i=$li_i+1;
					$ldec_neto=$rs_data->fields["monnetres"];					
					$ldec_neto=($ldec_neto*100);  
					$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,18);
					$ls_cedper=$rs_data->fields["cedper"];
					//$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);
					$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
					$ls_relleno=str_pad("0",71,"0",0);
					if($li_i!=$li_count)
					{
						$ls_cadena="2911".$ls_cedper."000"."AFAN"."0000000000".$ldec_neto.$ls_relleno."0"."\r\n";
					}
					else
					{
						$ls_cadena="2911".$ls_cedper."000"."AFAN"."0000000000".$ldec_neto.$ls_relleno."0";
					}
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
    }// end function uf_metodo_fonz03_militar
	//-----------------------------------------------------------------------------------------------------------------------------------
	  function uf_metodo_fonz03($as_ruta,$rs_data) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_fonz03_militar
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codcueban // c?digo de la cuenta bancaria a debitar 
		//                 as_codmetban // c?digo de m?todo a banco 
		//                 as_desope // descripci?n de operaci?n
		//	  Description: genera el archivo txt a disco para  el banco Fondo Comun para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci?n: 28/01/2009								
		// Modificado Por:		             Fecha ?ltima Modificaci?n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_nombrearchivo=$as_ruta;
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
				$ls_cedper=str_pad(trim($rs_data->fields["cedper"]),9,"0",0);				
				$ldec_neto=number_format(abs($rs_data->fields["monto"]),2,".","");
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=number_format($ldec_neto,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,18);
				$ls_relleno1=str_pad("0",3,"0",0);
				$ls_concepto=trim($rs_data->fields["codconc"]);
				switch ($ls_concepto)
				{
					case "0000020007":
						$ls_nomconc="APU6";
					break;
					
					case "0000020014":
						$ls_nomconc="ACU6";
					break;
					
					case "0000020003":
						$ls_nomconc="AHV6";
					break;
					
					case "0000020005":
						$ls_nomconc="AHV6";
					break;
					
					case "0000020008":
						$ls_nomconc="PES6";
					break;
				}
				
				$ls_relleno2=str_pad("0",10,"0",0);
				$ls_relleno3=str_pad("0",72,"0",0);
				$ls_cadena="2911".$ls_cedper.$ls_relleno1.$ls_nomconc.$ls_relleno2.$ldec_neto.$ls_relleno3."\r\n";
				
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
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;		
    }// end function uf_metodo_fonz03
//--------------------------------------------------------------------------------------------------------------------------------------
	
}
?>
