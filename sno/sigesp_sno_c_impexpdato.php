<?php
class sigesp_sno_c_impexpdato
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $io_personalnomina;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_impexpdato()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_impexpdato
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 
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
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal= new sigesp_snorh_c_personal();
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
	}// end function sigesp_sno_c_impexpdato
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personal);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_exportardatos($as_codarch,$as_codconcep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_exportardatos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	    Arguments: as_codarch   // Código del Tipo del Archivo a Exportar
		//	    		   as_codconcep  // Código de los Conceptos
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se exportó correctamente la información al txt ó False si hubo algún error
		//	  Description: Funcion que exporta la información de los conceptos seleccionados a un txt
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 14/10/2008 								Fecha Última Modificación :		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_ruta="txt/general";
		$lo_archivo="";
		$ls_tipo="";		
		$lb_valido=$this->uf_crear_archivo($ls_ruta,$lo_archivo,$ls_tipo);
		if($lb_valido)
		{
			$arr_codconc=split(",",$as_codconcep); 
			$li_totcodconc=count($arr_codconc);
			
			for ($i=0;$i<($li_totcodconc-1);$i++)
			{
				$ls_codconcep=trim($arr_codconc[$i]);				
				
				$lb_valido=$this->uf_load_conceptos($ls_codconcep,$lo_archivo,$ls_ruta,$as_codarch);
			}
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("El archivo fue exportado.");
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al exportar la información.");
		}
		
		return $lb_valido;
	}// end function uf_exportardatos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos($as_codconcep,$ao_archivo,$as_ruta,$as_codarch)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos		
		//	    Arguments: as_codconcep  // Código de los Conceptos	
		//                 ao_archivo    // Archivo donde se exportara la información
		//                 as_ruta       // Ruta donde se guardar el archivo txt
		//                 as_codarch    // Código del archivo de texto para exportar los datos
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se exportó correctamente la información al txt ó False si hubo algún error
		//	  Description: Funcion que exporta la información de los conceptos seleccionados a un txt
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 14/10/2008 								Fecha Última Modificación :		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_nombrearchivo=$as_ruta.'/Conceptos_Exportados_'.$ls_peractnom.".txt";
		
		$ls_sql= "SELECT sno_salida.codper, sno_salida.codconc, sno_salida.valsal ".
				 " FROM sno_salida, sno_conceptopersonal ".
				 " WHERE sno_salida.codemp= '".$this->ls_codemp."' ".
				 " AND sno_salida.codconc='".$as_codconcep."' ".
				 " AND sno_salida.codnom='".$ls_codnom."' ".
				 " AND sno_salida.codemp=sno_conceptopersonal.codemp ".				
				 " AND sno_salida.codconc=sno_conceptopersonal.codconc ".
				 " AND sno_salida.codnom=sno_conceptopersonal.codnom ".
				 " AND sno_salida.codper=sno_conceptopersonal.codper ".
				 " ORDER BY sno_salida.codper,sno_salida.codconc";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Archivo txt MÉTODO->uf_load_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$this->uf_load_configuracion_campos($as_codarch,$ai_totrows,$ao_object);
			
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_valsal=$row["valsal"];
				$ls_codconc=$row["codconc"];	
				$ls_cadena="";
				$longitud=0;
				for($li_z=1;($li_z<=$ai_totrows);$li_z++)
				{
					$li_codcam=$ao_object["codcam"][$li_z];
					$ls_descam=$ao_object["descam"][$li_z];
					$li_inicam=$ao_object["inicam"][$li_z];
					$li_loncam=$ao_object["loncam"][$li_z];
					$ls_cricam=ltrim(rtrim($ao_object["cricam"][$li_z]));
					$ls_edicam=$ao_object["edicam"][$li_z];
					$ls_clacam=$ao_object["clacam"][$li_z];
					$ls_actcam=$ao_object["actcam"][$li_z];
					$ls_tabrelcam=$ao_object["tabrelcam"][$li_z];
					$ls_iterelcam=$ao_object["iterelcam"][$li_z];
					$ls_tipcam=$ao_object["tipcam"][$li_z];
					
					if ($ls_iterelcam=='moncon')
					{
						$ls_campo=$ls_valsal;
					}
					elseif ($ls_iterelcam=='codcons')
					{
						$ls_campo=$ls_codconc;
					}
					elseif ($ls_iterelcam=='codper')
					{
						$ls_campo=$ls_codper;
					}
					
										
					if($ls_tipcam=="N")
					{
						$ls_campo=number_format($ls_campo,2,".","");
					}
					if($ls_cricam!="")
					{
						if($ls_tipcam=="N")
						{
							$ls_cricam=str_replace("campo",$ls_campo,$ls_cricam);
							$ls_campo=$this->io_eval->uf_evaluar_formula($ls_cricam,$ls_campo);
						}
						else
						{
							$ls_campo="'".ltrim(rtrim($ls_campo))."'";
							$ls_cricam=str_replace("campo",$ls_campo,$ls_cricam);
							$ls_campo=@eval(" return $ls_cricam;");
						}
					}
					
					if ($li_z!=1)
					{
						$ls_aux="";
						$longitud=$ao_object["inicam"][$li_z-1]+$ao_object["loncam"][$li_z-1];
						$ls_campo=str_pad($ls_campo,$li_loncam,' ');
						$li_relleno=$ao_object["inicam"][$li_z]-$longitud;
						$ls_aux=str_pad($ls_aux,$li_relleno,' ','left');												
						$ls_campo=$ls_aux.$ls_campo;
					}
					else
					{
						$ls_campo=str_pad($ls_campo,$li_loncam,' ');
					}
					
					$ls_cadena=$ls_cadena.$ls_campo;
					
				}//fin del for
				
				$ls_cadena=$ls_cadena."\r\n";
				if ($ao_archivo)
				{
					if (@fwrite($ao_archivo,$ls_cadena)===false)//Escritura
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
					
			}//fin del while
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;		 
	
	}// end function uf_load_conceptos
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 
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
			$this->io_mensajes->message("CLASE->Importar/Exportar Datos MÉTODO->uf_crear_archivo ERROR->No Se pudo crear el archivo."); 
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
		//	   Creado Por: Ing. Yesenia Moreno
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
		//	   Creado Por: Ing. Yesenia Moreno
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
			$this->io_mensajes->message("CLASE->Importar/Exportar Datos MÉTODO->uf_abrir_archivo ERROR->el archivo no existe."); 
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
		//	   Creado Por: Ing. Yesenia Moreno
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
		//	   Creado Por: Ing. Yesenia Moreno
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
				$ls_cricam=ltrim(rtrim($ao_object["cricam"][$li_z]));
				$ls_edicam=$ao_object["edicam"][$li_z];
				$ls_clacam=$ao_object["clacam"][$li_z];
				$ls_actcam=$ao_object["actcam"][$li_z];
				$ls_tabrelcam=$ao_object["tabrelcam"][$li_z];
				$ls_iterelcam=$ao_object["iterelcam"][$li_z];
				$ls_tipcam=$ao_object["tipcam"][$li_z];
				$ao_title[$li_z]=$ls_descam;
				$ls_readonly="readonly";
				$ls_formato="onKeyUp='javascript: ue_validarcomillas(this);'";
				if($ls_edicam=="1")
				{
					$ls_readonly="";
				}
				$ls_campo=substr($ao_archivo[$li_i],$li_inicam,$li_loncam);
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,".","");
				}
				if($ls_cricam!="")
				{
					if($ls_tipcam=="N")
					{
						$ls_cricam=str_replace("campo",$ls_campo,$ls_cricam);
						$ls_campo=$this->io_eval->uf_evaluar_formula($ls_cricam,$ls_campo);
					}
					else
					{
						$ls_campo="'".ltrim(rtrim($ls_campo))."'";
						$ls_cricam=str_replace("campo",$ls_campo,$ls_cricam);
						$ls_campo=@eval(" return $ls_cricam;");
					}
				}
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,",",".");
					$ls_formato="onKeyPress=return(ue_formatonumero(this,'.',',',event)) style='text-align:right'";
					$li_loncam=15;
				}
				$ao_campos[$li_fila][$li_z]="<input name=txtcampo".$li_fila.$li_z." type=text id=txtcampo".$li_fila.$li_z." class=sin-borde maxlength=".$li_loncam." value='".$ls_campo."' ".$ls_formato." ".$ls_readonly.">".
										    "<input type=hidden name=txtclacam".$li_fila.$li_z." id=txtclacam".$li_fila.$li_z." value='".$ls_clacam."'>".
										    "<input type=hidden name=txtactcam".$li_fila.$li_z." id=txtactcam".$li_fila.$li_z." value='".$ls_actcam."'>".
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
	function uf_procesarimportardatos($as_codarch,$as_codcons,$as_acumon,$ai_nrofilas,$aa_seguridad)
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/11/2007 								Fecha Última Modificación : 
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
			$lb_valido=$this->uf_load_personalconstante($as_codcons,$as_acumon,$ai_nrofilas,$li_totrows,$aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La información de las constantes fue Actualizada.");
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al Actualizar la información de las constantes");
		}
		return $lb_valido;
	}// end function uf_procesarimportardatos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalconstante($as_codcons,$as_acumon,$ai_nrofilas,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalconstante
		//		   Access: private
		//	    Arguments: as_codcons // Código de la constantes
		//				   ai_nrofilas // Nro de filas a actualizar
		//				   ai_totrow // total de filas 
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si actualizó correctamente ó falso si ocurro algún error
		//	  Description: Funcion que actualiza el valor de una constante según lo cargado en los txt
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_nombrearchivo="txt/general/errores_importar_constante.txt";
		if (file_exists("$ls_nombrearchivo"))
		{
			unlink ("$ls_nombrearchivo");//Borrar el archivo de texto existente para crearlo nuevo.		
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
		}
		else
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
		}
		for($li_i=1;($li_i<=$ai_nrofilas);$li_i++)
		{
			$li_fin=$ai_totrow+1;
			$li_chksel=$_POST["chksel".$li_i.$li_fin];
			if($li_chksel==1)
			{
				$la_updates="";
				$la_filtros="";
				$li_filtros=0;
				$li_updates=0;
				$lb_contante=false;
				$li_filtros++;
				$la_filtros[$li_filtros]="codemp='".$this->ls_codemp."'";
				$li_filtros++;
				$la_filtros[$li_filtros]="codnom='".$this->ls_codnom."'";
				for($li_z=1;($li_z<=$ai_totrow);$li_z++)
				{
					$ls_campo=$_POST["txtcampo".$li_i.$li_z];
					$ls_clacam=$_POST["txtclacam".$li_i.$li_z];
					$ls_actcam=$_POST["txtactcam".$li_i.$li_z];
					$ls_tabrelcam=$_POST["txttabrelcam".$li_i.$li_z];
					$ls_iterelcam=$_POST["txtiterelcam".$li_i.$li_z];
					$ls_tipcam=$_POST["txttipcam".$li_i.$li_z];
					if($ls_tipcam=="N")
					{
						$ls_campo=str_replace(".","",$ls_campo);
						$ls_campo=str_replace(",",".",$ls_campo);
					}
					else
					{
						$ls_campo="'".$ls_campo."'";
					}
					if($ls_clacam=="1")
					{
						$li_filtros++;
						$la_filtros[$li_filtros]=$ls_iterelcam."=".$ls_campo;
					}
					if($ls_actcam=="1")
					{
						$li_updates++;
						if (($ls_iterelcam=="moncon") && ($as_acumon=='1'))
						{
							$la_updates[$li_updates]=$ls_iterelcam."=(moncon+".$ls_campo.")";
						}
						else
						{
							$la_updates[$li_updates]=$ls_iterelcam."=".$ls_campo;
						}
						
					}
					
					if($ls_iterelcam=="codcons")
					{
						$lb_contante=true;
						
						
					}
					if($ls_iterelcam=="codper")
					{
						$lb_existe=$this->uf_buscar_personalnomina($ls_campo);
						if(!$lb_existe)	
						{
							$ls_cadena="La persona ".$ls_campo." no existe en la nomina ".$this->ls_codnom."\r\n";					
							if ($ls_creararchivo)  //Chequea que el archivo este abierto
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							
								}							

							}
							
						}
					}
				}
				if($lb_contante==false)
				{
					$li_filtros++;
					$la_filtros[$li_filtros]="codcons='".$as_codcons."'";
					
				}
				$ls_sql="UPDATE sno_constantepersonal SET ";	
				// CARGAMOS LOS CAMPOS A ACTUALIZAR 	
				for($li_z=1;($li_z<=$li_updates);$li_z++)
				{
					$ls_update=$la_updates[$li_z];
					$ls_sql=$ls_sql." ".$ls_update." ";
					if($li_z<$li_updates)
					{
						$ls_sql=$ls_sql.", ";
					}
				}	
				$ls_sql=$ls_sql." WHERE ";
				// CARGAMOS LOS FILTROS DE LA SENTENCIA 
				for($li_z=1;($li_z<=$li_filtros);$li_z++)
				{
					$ls_filtro=$la_filtros[$li_z];
					if($li_z>1)
					{
						$ls_sql=$ls_sql." AND ";
					}
					$ls_sql=$ls_sql." ".$ls_filtro." ";
				}
				$lb_valido=$this->uf_update_constantepersonal($ls_sql,$aa_seguridad);	
				
			}
		}
		return $lb_valido;
	}// end function uf_load_personalconstante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_constantepersonal($as_sql,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_constantepersonal
		//		   Access: private
		//	    Arguments: as_sql // sentencia sql que se va a ejecutar
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_row=$this->io_sql->execute($as_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Datos MÉTODO->uf_update_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_sql=str_replace("'","",$as_sql);
			$ls_descripcion =" Ejecuto la sentencia ".$ls_sql." en la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////								
		}
		return $lb_valido;
	}// end function uf_update_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardatos_ipasme($as_arctxt,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardatos_ipasme
		//		   Access: public (sigesp_sno_p_ipasme_importar)
		//	    Arguments: as_arctxt  // Archivo txt que se desea importar
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se importó correctamente la información al sistema ó False si hubo algún error
		//	  Description: Funcion que importa la información de un txt al sistema
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_ruta="a:/";
		$ls_nombrearchivo=$ls_ruta.$as_arctxt;
		$lb_valido=$this->uf_abrir_archivo($ls_nombrearchivo,$lo_archivo);
		if($lb_valido)
		{
			$lb_valido=$this->uf_verificar_data_ipasme($lo_archivo);
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_data_ipasme($lo_archivo,$aa_seguridad);
			}
			unset($lo_archivo);
		}
		
		if($lb_valido)
		{
			$this->io_mensajes->message("La información fue Importada.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("Ocurrio un error al importar la información");
		}
		return $lb_valido;
	}// end function uf_importardatos_ipasme
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_data_ipasme($ao_archivo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_data_ipasme
		//		   Access: private
		//	    Arguments: ao_archivo // conexión del archivo que se desea leer
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=count($ao_archivo);
		for($li_i=0;($li_i<$li_total)&&($lb_valido);$li_i++)
		{
			$la_personal=split(":",$ao_archivo[$li_i]);			
			$ls_cedper=$la_personal[1];
			$lb_existe=$this->io_personal->uf_select_personal("cedper",$ls_cedper);
			if($lb_existe===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("El personal de cédula ".$ls_cedper.". No existe."); 
			}
		}
		return $lb_valido;
	}// end function uf_verificar_data_ipasme
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_data_ipasme($ao_archivo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_data_ipasme
		//		   Access: private
		//	    Arguments: ao_archivo // conexión del archivo que se desea leer
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=count($ao_archivo);
		for($li_i=0;($li_i<$li_total)&&($lb_valido);$li_i++)
		{
			$la_personal=split(":",$ao_archivo[$li_i]);			
			$ls_cedper=$la_personal[1];
			$ld_fechavenc=$la_personal[6];
			$li_montogiro=$la_personal[7];
			$ls_clase=$la_personal[9];
			$ls_concepto=$la_personal[10];
			$ls_codper="";
			$ls_codconc="";
	        $ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
			if($this->io_fecha->uf_comparar_fecha($ld_fechavenc,$ld_fechasper))
			{
				$lb_valido=$this->io_personal->uf_load_codigopersonal($ls_cedper,$ls_codper);
				if($lb_valido)
				{
					switch($ls_clase)
					{
						case "01": // Hipotecario
							switch($ls_concepto)
							{
								case "01": // Hipotecario Vivienda
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO VIVIENDA IPAS","XXXXXXXXXX","C");
									break;
	
								case "02": // Hipotecario LPH
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO LPH IPAS","XXXXXXXXXX","C");
									break;
	
								case "03": // Hipotecario Hipoteca
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO HIPOTECA IPAS","XXXXXXXXXX","C");
									break;
	
								case "05": // Hipotecario Construcción
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO CONSTRUCCION IPAS","XXXXXXXXXX","C");
									break;
	
								case "06": // Hipotecario Ampliación
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO AMLIACION IPAS","XXXXXXXXXX","C");
									break;
	
								case "07": // Hipotecario Especial
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO ESPECIAL IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
	
						case "02": // Personal
							switch($ls_concepto)
							{
								case "08": // Personal
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PERSONAL IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
	
						case "03": // Turistico
							switch($ls_concepto)
							{
								case "09": // Turisticos
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO TURISTICOS IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
							
						case "04": // Proveeduria
							switch($ls_concepto)
							{
								case "10": // Proveeduria
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PROVEEDURIA IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
							
						case "05": // Asistencial
							switch($ls_concepto)
							{
								case "11": // Asistencial
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO ASISTENCIALES IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
							
						case "06": // Vehiculo
							switch($ls_concepto)
							{
								case "12": // Vehiculos
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VEHICULOS IPAS","XXXXXXXXXX","C");
									break;
							}
							break;
							
						case "07": // Comercial
							switch($ls_concepto)
							{
								case "13": // Comerciales
									$ls_codconc=$this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO COMERCIALES IPAS","XXXXXXXXXX","C");	
									break;
							}
							break;
					}	
					$ls_sql="UPDATE sno_conceptopersonal ".
							"   SET aplcon=1 ".
							" WHERE codemp='".$this->ls_codemp."'".
							"   AND codnom='".$this->ls_codnom."'".
							"   AND codconc='".$ls_codconc."'".
							"   AND codper='".$ls_codper."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar MÉTODO->uf_importar_data_ipasme ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Se le aplico el concepto ".$ls_codconc." asociado al personal ".$ls_codper." en la nómina ".$this->ls_codnom;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////								
					}
					if($lb_valido)
					{
						$ls_sql="UPDATE sno_constantepersonal ".
								"   SET moncon=".$li_montogiro." ".
								" WHERE codemp='".$this->ls_codemp."'".
								"   AND codnom='".$this->ls_codnom."'".
								"   AND codcons='".$ls_codconc."'".
								"   AND codper='".$ls_codper."'";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->Importar MÉTODO->uf_importar_data_ipasme ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						}
						else
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_evento="UPDATE";
							$ls_descripcion ="Se se actualizó el monto de la constante ".$ls_codconc." asociado al personal ".$ls_codper." en la nómina ".$this->ls_codnom;
							$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////								
						}
					}
				}
			}
		}
		return $lb_valido;
	}// end function uf_importar_data_ipasme
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_personalnomina($as_codper)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_personalnomina
		//		   Access: private
		//	    Arguments: as_codper // código del personal		
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un personal este en la tabla sno_personalnomina.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/01/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		
		$ls_sql="  SELECT codper ".
			"  FROM sno_personalnomina ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codper=".$as_codper." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar MÉTODO->uf_buscar_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
}//end function uf_buscar_personalnomina

	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
