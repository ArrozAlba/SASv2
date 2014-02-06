<?php
class sigesp_sno_c_importarprestamos
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_prestamo;
	var $io_personalnomina;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_importarprestamos()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_importarprestamos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo= new sigesp_sno_c_prestamo();
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina= new sigesp_sno_c_personalnomina();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("../shared/class_folder/evaluate_formula.php");
		$this->io_eval=new evaluate_formula();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	}// end function sigesp_sno_c_importarprestamos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_prestamo);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_crear_archivo($as_ruta,&$ao_archivo,&$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_archivo
		//		   Access: private
		//	    Arguments: as_ruta // Ruta donde se debe crear el archivo
		//	    		   ao_archivo // conexión del archivo que se desea crear
		//	    		   as_tipo // tipo de archivo que se quiere crear
		// 	      Returns: lb_valido True si se creo el archivo ó False si no se creo
		//	  Description: Funcion que crea un archivo de texto dada una ruta 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_nombrearchivo=$as_ruta.'/Conceptos_Exportados_'.$ls_peractnom.'.txt';
		$as_tipo="C";
		if (file_exists("$ls_nombrearchivo"))
		{
			unlink ("$ls_nombrearchivo");//Borrar el archivo de texto existente para crearlo nuevo.
			$ao_archivo=@fopen("$ls_nombrearchivo","a+");
		}
		else
		{
			$ao_archivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
		}
		if (file_exists("$ls_nombrearchivo")===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_crear_archivo ERROR->No Se pudo crear el archivo."); 
		}		
		return $lb_valido;
	}// end function uf_crear_archivo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardatos($as_arctxt,$as_codarch,&$ao_title,&$ao_campos,&$ai_nrofilas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardatos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	    Arguments: as_arctxt  // Archivo txt que se desea importar
		//				   as_codarch // Código de Archivo
		//				   ao_title // Arreglo de Titulos
		//				   ao_campos // Arreglo de Campos
		//				   ai_nrofilas // Número de Filas
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se importó correctamente la información al sistema ó False si hubo algún error
		//	  Description: Funcion que importa la información de un txt al sistema
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_nombrearchivo=$as_arctxt;
		$lb_valido=$this->uf_abrir_archivo($ls_nombrearchivo,$lo_archivo);
		$li_totrows=0;
		$lo_object="";
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_configuracion_campos($as_codarch,&$li_totrows,&$lo_object);
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_archivotxt_campos($lo_archivo,$li_totrows,$lo_object,&$ao_title,&$ao_campos,&$ai_nrofilas);
			}
			unset($lo_archivo);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La información fue Importada.");
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al importar la información");
		}
		return $lb_valido;
	}// end function uf_importardatos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_abrir_archivo($as_nombrearchivo,&$ao_archivo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_abrir_archivo
		//		   Access: private
		//	    Arguments: as_nombrearchivo // Ruta donde se debe abrir el archivo
		//	    		   ao_archivo // conexión del archivo que se desea abrir
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if (file_exists("$as_nombrearchivo"))
		{
			$ao_archivo=@file("$as_nombrearchivo");
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_abrir_archivo ERROR->el archivo no existe."); 
		}
		return $lb_valido;
	}// end function uf_abrir_archivo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_campos($as_codarch,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_campos
		//		   Access: privates
		//	    Arguments: as_codarch  // código del archivo txt
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un archivo txt
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 12/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codarch, codcam, descam, inicam, loncam, edicam, clacam, actcam, tabrelcam, iterelcam, cricam, tipcam ".
				"  FROM sno_archivotxtcampo".
				" WHERE sno_archivotxtcampo.codemp='".$this->ls_codemp."'".	
				" AND codarch = '".$as_codarch."' ".	
				" ORDER BY sno_archivotxtcampo.codcam,inicam "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Archivo txt MÉTODO->uf_load_configuracion_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$li_codcam=$row["codcam"];
				$ls_descam=$row["descam"];
				$li_inicam=$row["inicam"];
				$li_loncam=$row["loncam"];
				$ls_cricam=$row["cricam"];
				$ls_edicam=$row["edicam"];
				$ls_clacam=$row["clacam"];
				$ls_actcam=$row["actcam"];
				$ls_tabrelcam=$row["tabrelcam"];
				$ls_iterelcam=$row["iterelcam"];
				$ls_tipcam=$row["tipcam"];
				$ao_object["codcam"][$ai_totrows]=$li_codcam;
				$ao_object["descam"][$ai_totrows]=$ls_descam;
				$ao_object["inicam"][$ai_totrows]=$li_inicam;
				$ao_object["loncam"][$ai_totrows]=$li_loncam;
				$ao_object["cricam"][$ai_totrows]=$ls_cricam;
				$ao_object["edicam"][$ai_totrows]=$ls_edicam;
				$ao_object["clacam"][$ai_totrows]=$ls_clacam;
				$ao_object["actcam"][$ai_totrows]=$ls_actcam;
				$ao_object["tabrelcam"][$ai_totrows]=$ls_tabrelcam;
				$ao_object["iterelcam"][$ai_totrows]=$ls_iterelcam;
				$ao_object["tipcam"][$ai_totrows]=$ls_tipcam;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_configuracion_campos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_archivotxt_campos($ao_archivo,$ai_totrows,$ao_object,&$ao_title,&$ao_campos,&$ai_nrofilas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_archivotxt_campos
		//		   Access: private
		//	    Arguments: ao_archivo // conexión del archivo que se desea leer
		//	    		   ai_totrows  // Total de filas del arreglo de campos
		//	    		   ao_object  // arreglo de campos
		//				   ao_title // Arreglo de Titulos
		//				   ao_campos // Arreglo de Campos
		//				   ai_nrofilas // Número de Filas
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que carga un archivo txt según la ruta y la configuración dada
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_fila=0;
		$li_total=count($ao_archivo);
		for($li_i=0;($li_i<$li_total);$li_i++)
		{
			$li_fila++;
			for($li_z=1;($li_z<=$ai_totrows);$li_z++)
			{
				$li_codcam=$ao_object["codcam"][$li_z];
				$ls_descam=$ao_object["descam"][$li_z];
				$li_inicam=$ao_object["inicam"][$li_z];
				$li_loncam=$ao_object["loncam"][$li_z];				
				$ls_tabrelcam=$ao_object["tabrelcam"][$li_z];
				$ls_iterelcam=$ao_object["iterelcam"][$li_z];
				$ls_tipcam=$ao_object["tipcam"][$li_z];
				$ao_title[$li_z]=$ls_descam;
				$ls_readonly="readonly";
				$ls_formato="onKeyUp='javascript: ue_validarcomillas(this);'";
				
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,".","");
				}
				
				$ls_campo=substr($ao_archivo[$li_i],$li_inicam,$li_loncam);
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,",",".");
					$ls_formato="onKeyPress=return(ue_formatonumero(this,'.',',',event)) style='text-align:right'";
					$li_loncam=15;
				}
				else if($ls_tipcam=="E")
				{
					$ls_campo=intval($ls_campo);
					$ls_formato=" style='text-align:right'";
				}
				$ao_campos[$li_fila][$li_z]="<input name=txtcampo".$li_fila.$li_z." type=text id=txtcampo".$li_fila.$li_z." class=sin-borde maxlength=".$li_loncam." value='".$ls_campo."' ".$ls_formato." ".$ls_readonly.">".
										   "<input type=hidden name=txttipcam".$li_fila.$li_z." id=txttipcam".$li_fila.$li_z." value='".$ls_tipcam."'>".
										 	"<input type=hidden name=txttabrelcam".$li_fila.$li_z." id=txttabrelcam".$li_fila.$li_z." value='".$ls_tabrelcam."'>".
										 	"<input type=hidden name=txtiterelcam".$li_fila.$li_z." id=txtiterelcam".$li_fila.$li_z." value='".$ls_iterelcam."'>";
			}
			$ao_title[$li_z]=" ";
			$ao_campos[$li_fila][$li_z]="<input type=checkbox name=chksel".$li_fila.$li_z." id=chksel".$li_fila.$li_z." value=1 style=width:15px;height:15px checked>";		
		}
		$ai_nrofilas=$li_i;
		return $lb_valido;
	}// end function uf_importar_data
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarimportardatos($as_codarch,$ai_nrofilas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarimportardatos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	    Arguments: as_codarch // Código de Archivo
		//				   as_codcons // Código de la constantes
		//				   ai_nrofilas // total de filas 
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se importó correctamente la información al sistema ó False si hubo algún error
		//	  Description: Funcion que importa la información de un txt al sistema
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$li_totrows=0;
		$lo_object="";
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_configuracion_campos($as_codarch,&$li_totrows,&$lo_object);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_personalprestamo($ai_nrofilas,$li_totrows,$aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("Los prestamos fueron insertados.");
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al procesar los prestamos");
		}
		return $lb_valido;
	}// end function uf_procesarimportardatos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalprestamo($ai_nrofilas,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalprestamo
		//		   Access: private
		//	    Arguments: as_codcons // Código de la constantes
		//				   ai_nrofilas // Nro de filas a actualizar
		//				   ai_totrow // total de filas 
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si actualizó correctamente ó falso si ocurro algún error
		//	  Description: Funcion que actualiza el valor de una constante según lo cargado en los txt
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codper="";
		$ls_codtippre="";
		$li_numpre="";
		$ls_codconc="";
		$ls_stapre="1";
		$li_monpre="";
		$li_numcuopre="";
		$ls_perinipre="";
		$li_monamopre=0;
		$ls_fecpre="";
		$ls_tipcuopre="0";
		$li_moncuo=0;
		$li_sueper=0;
		$ls_obsrecpre="";
		$lb_ok=true;
		$lb_ok2=true;
		$ls_nombrearchivo="txt/general/errores_importar_prestamos.txt";
		if (file_exists("$ls_nombrearchivo"))
		{
			unlink ("$ls_nombrearchivo");//Borrar el archivo de texto existente para crearlo nuevo.		
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
		}
		else
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
		}
		$entro=0;
		for($li_i=1;($li_i<=$ai_nrofilas);$li_i++)
		{
			$li_fin=$ai_totrow+1;
			$li_chksel=$_POST["chksel".$li_i.$li_fin];
			if($li_chksel==1)
			{
				for($li_z=1;($li_z<=$ai_totrow);$li_z++)
				{
					$ls_campo=$_POST["txtcampo".$li_i.$li_z];
					$ls_tabrelcam=$_POST["txttabrelcam".$li_i.$li_z];
					$ls_iterelcam=$_POST["txtiterelcam".$li_i.$li_z];
					$ls_tipcam=$_POST["txttipcam".$li_i.$li_z];
					if($ls_tipcam=="N")
					{
						$ls_campo=str_replace(".","",$ls_campo);
						$ls_campo=str_replace(",",".",$ls_campo);
					}			
					
					
									
					if($ls_iterelcam=="monpre")
					{
						$li_monpre=$ls_campo;
						
					}
					if($ls_iterelcam=="numcuopre")
					{
						$li_numcuopre=$ls_campo;
						
					}
					if($ls_iterelcam=="fecpre")
					{
						$ls_fecpre=substr($ls_campo,0,4).'-'.substr($ls_campo,4,2).'-'.substr($ls_campo,6,2);
						
					}
					if($ls_iterelcam=="obsrecpre")
					{
						$ls_obsrecpre=$ls_campo;
						
					}
					if($ls_iterelcam=="obsrecpre")
					{
						$ls_stapre=$ls_campo;
						
					}
					if($ls_iterelcam=="codper")
					{
						$ls_codper=$ls_campo;
						$ls_codper=str_pad($ls_codper,10,0,"left");
						$lb_existe=$this->uf_buscar_personalnomina($ls_codper,$li_sueper);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							//print "PERSONAL QUE NO EXISTE:   ".$ls_codper."<br>";
							$ls_cadena="La persona ".$ls_codper." no existe en la nomina ".$this->ls_codnom."\r\n";					
							if ($ls_creararchivo)  //Chequea que el archivo este abierto
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							
								}							

							}
							
						}
						else
						{
							$lb_ok2=true;
						}
					}
					if($ls_iterelcam=="codtippre")
					{
						$ls_codtippre=$ls_campo;
						$ls_codtippre=str_pad($ls_codtippre,10,0,"left");
						$lb_existe=$this->uf_buscar_tipoprestamo($ls_codtippre);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							$ls_cadena="El tipo de prestamo ".$ls_codtipre." no existe en la nomina ".$this->ls_codnom."\r\n";			
							//print "PRESTAMO QUE NO EXISTE:   ".$ls_codtippre."<br>";
							if ($ls_creararchivo)  //Chequea que el archivo este abierto
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							
								}							

							}
							
						}
						else
						{
							$lb_ok2=true;
						}
					}
					if($ls_iterelcam=="codconc")
					{					
						
						$ls_codconc=$ls_campo;
						$ls_codconc=str_pad($ls_codconc,10,0,"left");
						$lb_existe=$this->uf_buscar_concepto($ls_codconc);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							$ls_cadena="El concepto ".$ls_codconc." no existe en la nomina ".$this->ls_codnom."\r\n";					
							//print "CONCEPTO QUE NO EXISTE:   ".$ls_codconc."<br><br>";
							if ($ls_creararchivo)  //Chequea que el archivo este abierto
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							
								}							

							}
							
						}
						else
						{
							$lb_ok2=true;
						}
					}
					
				}
				
				
				

				if(($ls_codper=="")||($ls_codtippre=="")||($ls_codconc=="")||($li_monpre=="")||
				   ($li_numcuopre=="")||(fecpre==""))
				{
					//print "DATOS VACIOS <br><br>";
					$ls_cadena="Debe llenar todos los campos: \r\n";	
					$ls_cadena=$ls_cadena."Codigo de Persona: ".$ls_codper."\r\n";	
					$ls_cadena=$ls_cadena."Codigo Tipo de Prestamo: ".$ls_codtippre."\r\n";	
					$ls_cadena=$ls_cadena."Codigo Concepto: ".$ls_codconc."\r\n";
					$ls_cadena=$ls_cadena."Monto del Prestamo: ".$li_monpre."\r\n";	
					$ls_cadena=$ls_cadena."Numero de Cuotas: ".$li_numcuopre."\r\n";	
					$ls_cadena=$ls_cadena."Fecha del Prestamo: ".$ls_fecpre."\r\n\r\n";					
					if ($ls_creararchivo)  //Chequea que el archivo este abierto
					{
						if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					
						}							

					}
					$lb_ok=false;
				}
				else
				{
					$lb_ok=true;
				}
				
				
				
				if(($lb_ok==true)&&($lb_ok2==true))
				{
					//$entro++;
					$li_moncuo=round($li_monpre/$li_numcuopre,2);
					$li_numpre=$this->uf_buscar_numero_prestamo_personal($ls_codper);
					$ls_perinipre=$this->uf_buscar_periodo_prestamo($ls_fecpre);
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		        	$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
					
					$lb_valido=$this->uf_insert_prestamo($ls_codper,$ls_codtippre,$li_numpre,$ls_codconc,$ls_stapre,
					                                     $li_monpre,$li_numcuopre,$ls_perinipre,$li_monamopre,
														 $ld_fecdesper,$ld_fechasper,$li_sueper,$li_moncuo,$ls_tipcuopre,
														 $ls_fecpre,$ls_obsrecpre,$aa_seguridad);
														 
				}
				
				
			}
		}
		//print $entro;
		return $lb_valido;
	}// end function uf_load_personalprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------		
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_personalnomina($as_codper,&$ai_sueper)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_personalnomina
		//		   Access: private
		//	    Arguments: as_codper // código del personal		
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un personal este en la tabla sno_personalnomina.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ai_sueper=0;
		$ls_sql="  SELECT codper, sueper ".
			"  FROM sno_personalnomina ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_buscar_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$ai_sueper=$row["sueper"];
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_personalnomina
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_tipoprestamo($as_codtippre)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_tipoprestamo
		//		   Access: private
		//	    Arguments: as_codtippre // código del tipo de prestamo		
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un tipo de prestamos este en la tabla sno_tipoprestamo.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		
		$ls_sql="  SELECT codtippre ".
			"  FROM sno_tipoprestamo ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codtippre='".$as_codtippre."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_buscar_tipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_tipoprestamo
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_concepto($as_codconc)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_concepto
		//		   Access: private
		//	    Arguments: as_codconc// código del concepto
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un tipo de prestamos este en la tabla sno_concepto.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		
		$ls_sql="  SELECT codconc ".
			"  FROM sno_concepto ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codconc='".$as_codconc."' ";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_buscar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_insert_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,$ai_monpre,$ai_numcuopre,$as_perinipre,
								$ai_monamopre,$ad_fecdesper,$ad_fechasper,$ai_sueper,$ai_moncuo,$as_tipcuopre,
								$ad_fecpre,$as_obsrecpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_codconc  // Código del Concepto
		//				   ai_stapre  // Estatus del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // Número de Cuotas
		//				   as_perinipre  // Período Inicial
		//				   ai_monamopre  // Monto Amortizado 
		//				   ad_fecdesper  // Fecha Desde Periodo de Inicio del Prestamo
		//				   ad_fechasper  // Fecha Hasta Periodo de Inicio del Prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_moncuo  // Monto de la cuota mensual
		//				   as_configuracion  // Configuración del prestamo si es por monto ó por cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el prestamo del personal
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_prestamos(codemp,codnom,codper,codtippre,numpre,codconc,stapre,monpre,numcuopre,perinipre,monamopre,fecpre,tipcuopre,obsrecpre)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$as_codtippre."',".$ai_numpre.",'".$as_codconc."',".
				" ".$ai_stapre.",".$ai_monpre.",".$ai_numcuopre.",'".$as_perinipre."',".$ai_monamopre.",'".$ad_fecpre."','".$as_tipcuopre."','".$as_obsrecpre."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_insert_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_mensajes->message("ERROR-> Revise el archivo de errores.");
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Prestamo nro ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			if($lb_valido)
			{	
				$ls_configuracion=trim($this->io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C"));
			
				$lb_valido = $this->io_prestamo->uf_generar_cuotas($as_codper,$as_codtippre,$ai_numpre,$ai_monpre,$ai_numcuopre,$as_perinipre,$ad_fecdesper,
							  			$ad_fechasper,$ai_sueper,$ai_moncuo,$ls_configuracion,$as_tipcuopre,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido = $this->io_prestamo->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El prestamo fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Error al registrar el prestamo.");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_prestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_numero_prestamo_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_numero_prestamo_personal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que trae el número del prestamo del personal
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT MAX(numpre) AS numero ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".		
				"   AND codper='".$as_codper."' ";
				
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		$li_numpre= $la_datos["numero"][0]+1;
		return $li_numpre;
	}// end function uf_buscar_numero_prestamo_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_periodo_prestamo($ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_periodo_prestamo
		//		   Access: private
		//	    Arguments: ad_fecha  // fecha del prestamo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que trae el periodo inicial del prestamo
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_codperi="000";
		$ls_sql="SELECT codperi ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".		
				"   AND '".$ad_fecha."' BETWEEN fecdesper AND  fechasper "; 
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos MÉTODO->uf_buscar_periodo_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codperi=$row["codperi"];
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_codperi;
	}// end function uf_buscar_periodo_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
