<?php
class sigesp_snorh_c_sane
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_sno;
	var $io_fecha;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_sane()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_sane
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 								Fecha Última Modificación : 
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
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha= new class_fecha();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_sane
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_isr)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_ingreso($as_codperdes,$as_codperhas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_ingreso
		//         Access: public (desde la clase sigesp_snorh_r_sane_ingreso)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='1'";

		$ls_sql="SELECT nacper, cedper, nomper, apeper, fecnacper, fecingper, sexper, cenmedper, ".
				"		(SELECT SUM(sueper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_nomina.espnom = '0'  ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom) as sueldo, ".
				"		(SELECT COUNT(codper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_nomina.espnom = '0'  ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom) as total ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_ingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ls_nomper=$row["nomper"];
				$li_pos=strpos($ls_nomper," ");
				if($li_pos>0)
				{
					$ls_prinom=substr($ls_nomper,0,$li_pos);
					$ls_segnom=substr($ls_nomper,$li_pos+1,1);
				}
				else
				{
					$ls_prinom=$ls_nomper;
					$ls_segnom="";
				}
				$ls_apeper=$row["apeper"];
				$li_pos=strpos($ls_apeper," ");
				if($li_pos>0)
				{
					$ls_priape=substr($ls_apeper,0,$li_pos);
					$ls_segape=substr($ls_apeper,$li_pos+1,1);
				}
				else
				{
					$ls_priape=$ls_apeper;
					$ls_segape="";
				}
				$ls_apenomper=substr(trim($ls_priape." ".$ls_segape." ".$ls_prinom." ".$ls_segnom),0,26);
				$ls_apenomper=str_replace("ñ","/",$ls_apenomper);
				$ls_apenomper=str_replace("Ñ","/",$ls_apenomper);				
				$ld_fecnacper=$this->io_funciones->uf_convertirfecmostrar($row["fecnacper"]);
				$ld_fecnacper=str_replace("/","",$ld_fecnacper);
				$ls_sexper=$row["sexper"];
				if($ls_sexper=="M") // Masculino
				{
					$ls_sexper="1";
				}
				else // Femenino
				{
					$ls_sexper="2";
				}
				$ld_fecingper=$this->io_funciones->uf_convertirfecmostrar($row["fecingper"]);
				$ld_fecingper=str_replace("/","",$ld_fecingper);
				$li_suesem=0;
				$li_sueldo=$row["sueldo"];
				$li_total=$row["total"];
				if($li_total>0)
				{
					$li_suesem=($li_sueldo/$li_total);
					$li_suesem=(($li_suesem*12)/52);
				}
				$li_suesem=round($li_suesem,0);
							
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena.str_pad($ls_apenomper,26," ");
				$ls_cadena=$ls_cadena.$ld_fecnacper;
				$ls_cadena=$ls_cadena.$ls_sexper;
				$ls_cadena=$ls_cadena.$ld_fecingper;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena."2"; // Código de Salario
				$ls_cadena=$ls_cadena.str_pad($li_suesem,7,"0",0);
				$ls_cadena=$ls_cadena."8226"; // Código de Ocupación
				$ls_cadena=$ls_cadena.str_pad($row["cenmedper"],3," ");
				$ls_cadena=$ls_cadena."    ";
				$ls_cadena=$ls_cadena."1"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Ingresos (Forma 14-02) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_ingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_retiro($as_codperdes,$as_codperhas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_retiro
		//         Access: public (desde la clase sigesp_snorh_r_sane_retiro)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal retirado del ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='3'";

		$ls_sql="SELECT codper, nacper, cedper, nomper, apeper, fecegrper, cauegrper ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_retiro ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ld_fecegrper=$this->io_funciones->uf_convertirfecmostrar($row["fecegrper"]);
				$ld_fecegrper=str_replace("/","",$ld_fecegrper);
				$ls_cauegrper=$row["cauegrper"];
				switch($ls_cauegrper)
				{
					case "D": // Despido
						$ls_cauegrper="1";
						break;
					case "R": // Renuncia
						$ls_cauegrper="2";
						break;
					case "J": // Jubilado
						$ls_cauegrper="3";
						break;
					case "P": // Pensionado
						$ls_cauegrper="4";
						break;
					case "T": // Traslado
						$ls_cauegrper="5";
						break;
					case "F": // Fallecido
						$ls_cauegrper="6";
						break;
				}
				
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena."                                   ";
				$ls_cadena=$ls_cadena.$ld_fecegrper;
				$ls_cadena=$ls_cadena."                 ";
				$ls_cadena=$ls_cadena.$ls_cauegrper;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena."13"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Retiros (Forma 14-03) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_retiro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_salario($as_codperdes,$as_codperhas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_salario
		//         Access: public (desde la clase sigesp_snorh_r_sane_salario)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='1'";

		$ls_sql="SELECT nacper, cedper, ".
				"		(SELECT SUM(sueper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"    		AND sno_nomina.espnom = '0' ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom) as sueldo, ".
				"		(SELECT COUNT(codper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_nomina.espnom = '0' ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom) as total ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_salario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$li_suesem=0;
				$li_sueldo=$row["sueldo"];
				$li_total=$row["total"];
				if($li_total>0)
				{
					$li_suesem=($li_sueldo/$li_total);
					$li_suesem=(($li_suesem*12)/52);
				}
				$li_suesem=round($li_suesem,0);
							
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena."                                             ";
				$ls_cadena=$ls_cadena."2"; // Código de Salario
				$ls_cadena=$ls_cadena.str_pad($li_suesem,7,"0",0);
				$ls_cadena=$ls_cadena."          ";
				$ls_cadena=$ls_cadena."33"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Cambio de Salario (Forma 14-10) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_salario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_centromedico($as_codperdes,$as_codperhas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_centromedico
		//         Access: public (desde la clase sigesp_snorh_r_sane_centromedico)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='1'";

		$ls_sql="SELECT cedper, nacper, cenmedper ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_centromedico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena."                                                         ";
				$ls_cadena=$ls_cadena.str_pad($row["cenmedper"],3,"0",0);
				$ls_cadena=$ls_cadena."   ";
				$ls_cadena=$ls_cadena."43"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Cambio de Centro Médico (Forma 14-02) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_centromedico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_modificacion($as_codperdes,$as_codperhas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_modificacion
		//         Access: public (desde la clase sigesp_snorh_r_sane_modificacion)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='1'";

		$ls_sql="SELECT codper, nacper, cedper, nomper, apeper, fecnacper, sexper ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_modificacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ls_nomper=$row["nomper"];
				$li_pos=strpos($ls_nomper," ");
				if($li_pos>0)
				{
					$ls_prinom=substr($ls_nomper,0,$li_pos);
					$ls_segnom=substr($ls_nomper,$li_pos+1,1);
				}
				else
				{
					$ls_prinom=$ls_nomper;
					$ls_segnom="";
				}
				$ls_apeper=$row["apeper"];
				$li_pos=strpos($ls_apeper," ");
				if($li_pos>0)
				{
					$ls_priape=substr($ls_apeper,0,$li_pos);
					$ls_segape=substr($ls_apeper,$li_pos+1,1);
				}
				else
				{
					$ls_priape=$ls_apeper;
					$ls_segape="";
				}
				$ls_apenomper=substr(trim($ls_priape." ".$ls_segape." ".$ls_prinom." ".$ls_segnom),0,26);
				$ls_apenomper=str_replace("ñ","/",$ls_apenomper);
				$ls_apenomper=str_replace("Ñ","/",$ls_apenomper);				
				$ld_fecnacper=$this->io_funciones->uf_convertirfecmostrar($row["fecnacper"]);
				$ld_fecnacper=str_replace("/","",$ld_fecnacper);
				$ls_sexper=$row["sexper"];
				if($ls_sexper=="M") // Masculino
				{
					$ls_sexper="1";
				}
				else // Femenino
				{
					$ls_sexper="2";
				}							
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena.str_pad($ls_apenomper,26," ");
				$ls_cadena=$ls_cadena.$ld_fecnacper;
				$ls_cadena=$ls_cadena.$ls_sexper;
				$ls_cadena=$ls_cadena."                            ";
				$ls_cadena=$ls_cadena."53"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Modificación de Datos (Forma 14-02) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_modificacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_reposos($as_codperdes,$as_codperhas,$ad_fecdes,$ad_fechas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_reposos
		//         Access: public (desde la clase sigesp_snorh_r_sane_ingreso)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   ad_fecdes // fecha Desde del reposo
		//	  			   ad_fechas // Fecha Hasta del Reposo		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecdes))
		{
			$ls_criterio= " AND sno_permiso.feciniper>='".$ad_fecdes."'";
		}
		if(!empty($ad_fechas))
		{
			$ls_criterio= $ls_criterio." AND sno_permiso.feciniper<='".$ad_fechas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		$ls_sql="SELECT sno_personal.nacper, sno_personal.cedper, sno_permiso.feciniper, sno_permiso.fecfinper ".
				"  FROM sno_personal, sno_permiso ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND sno_permiso.tipper = 2 ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_permiso.codemp ".
				"   AND sno_personal.codper = sno_permiso.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_reposos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($row["feciniper"]);
				$ld_fecfinper=$this->io_funciones->uf_convertirfecmostrar($row["fecfinper"]);
				$ld_inicio="01/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
				$ld_fin="28/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
				$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
				$ld_hasta=mktime(0,0,0,substr($ld_fin,3,2),substr($ld_fin,0,2),substr($ld_fin,6,4));
				while($ld_desde<=$ld_hasta)
				{
					$ld_fecha=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					if(strftime('%w',$ld_fecha)==1)
					{
						break;
					}
					$ld_inicio=$this->io_sno->uf_suma_fechas($ld_inicio,1);
					$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
				}
				if ($this->io_fecha->uf_comparar_fecha($ld_inicio,$ld_feciniper))
				{
					$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($row["feciniper"]);
				}
				else
				{
					$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($ld_inicio);
				}
				if(substr($ld_feciniper,3,2)!=substr($ld_fecfinper,3,2))
				{
					$ld_inicio="01/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
					$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					$ld_hasta=mktime(0,0,0,substr($ld_fecfinper,3,2),substr($ld_fecfinper,0,2),substr($ld_fecfinper,6,4));
					while(($ld_desde<=$ld_hasta)&&(substr($ld_desde,3,2)!=substr($ld_hasta,3,2)))
					{
						$ld_fecha=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
						if(strftime('%w',$ld_fecha)==0)
						{
							$ld_fecfinper=$ld_inicio;
						}
						$ld_inicio=$this->io_sno->uf_suma_fechas($ld_inicio,1);
						$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					}
				}
				$ld_feciniper=str_replace("/","",$ld_feciniper);
				$ld_fecfinper=str_replace("/","",$ld_fecfinper);
				$ld_feciniper=substr($ld_feciniper,0,4);
				$ld_fecfinper=substr($ld_fecfinper,0,4);
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena."        ";
				$ls_cadena=$ls_cadena.$ld_feciniper;
				$ls_cadena=$ls_cadena.$ld_fecfinper; // Código de Ocupación
				$ls_cadena=$ls_cadena."                                               ";
				$ls_cadena=$ls_cadena."23"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Reposos (Forma 14-10) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_reposos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_permisos($as_codperdes,$as_codperhas,$ad_fecdes,$ad_fechas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_permisos
		//         Access: public (desde la clase sigesp_snorh_r_sane_permisos)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   ad_fecdes // fecha Desde del reposo
		//	  			   ad_fechas // Fecha Hasta del Reposo		  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ivss y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecdes))
		{
			$ls_criterio= " AND sno_permiso.feciniper>='".$ad_fecdes."'";
		}
		if(!empty($ad_fechas))
		{
			$ls_criterio= $ls_criterio." AND sno_permiso.feciniper<='".$ad_fechas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		$ls_sql="SELECT sno_personal.nacper, sno_personal.cedper, sno_permiso.feciniper, sno_permiso.fecfinper ".
				"  FROM sno_personal, sno_permiso ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND sno_permiso.remper = 0 ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_permiso.codemp ".
				"   AND sno_personal.codper = sno_permiso.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_permisos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_nombrearchivo=$as_ruta."/mov".$ls_mes.$ls_ano.".txt";
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
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nacper=$row["nacper"];
				if($ls_nacper=="V") // es Venezolano
				{
					$ls_nacper="1";
				}
				else  // es Extranjero
				{
					$ls_nacper="2";				
				}
				$ls_cedper=str_replace(".","",$row["cedper"]);
				$ls_cedper=substr(trim($ls_cedper),0,8);
				$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($row["feciniper"]);
				$ld_fecfinper=$this->io_funciones->uf_convertirfecmostrar($row["fecfinper"]);
				$ld_inicio="01/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
				$ld_fin="28/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
				$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
				$ld_hasta=mktime(0,0,0,substr($ld_fin,3,2),substr($ld_fin,0,2),substr($ld_fin,6,4));
				while($ld_desde<=$ld_hasta)
				{
					$ld_fecha=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					if(strftime('%w',$ld_fecha)==1)
					{
						break;
					}
					$ld_inicio=$this->io_sno->uf_suma_fechas($ld_inicio,1);
					$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
				}
				if ($this->io_fecha->uf_comparar_fecha($ld_inicio,$ld_feciniper))
				{
					$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($row["feciniper"]);
				}
				else
				{
					$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($ld_inicio);
				}
				if(substr($ld_feciniper,3,2)!=substr($ld_fecfinper,3,2))
				{
					$ld_inicio="01/".substr($ld_feciniper,3,2)."/".substr($ld_feciniper,6,4);
					$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					$ld_hasta=mktime(0,0,0,substr($ld_fecfinper,3,2),substr($ld_fecfinper,0,2),substr($ld_fecfinper,6,4));
					while(($ld_desde<=$ld_hasta)&&(substr($ld_desde,3,2)!=substr($ld_hasta,3,2)))
					{
						$ld_fecha=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
						if(strftime('%w',$ld_fecha)==0)
						{
							$ld_fecfinper=$ld_inicio;
						}
						$ld_inicio=$this->io_sno->uf_suma_fechas($ld_inicio,1);
						$ld_desde=mktime(0,0,0,substr($ld_inicio,3,2),substr($ld_inicio,0,2),substr($ld_inicio,6,4));
					}
				}
				$ld_feciniper=str_replace("/","",$ld_feciniper);
				$ld_fecfinper=str_replace("/","",$ld_fecfinper);
				$ld_feciniper=substr($ld_feciniper,0,4);
				$ld_fecfinper=substr($ld_fecfinper,0,4);
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg;
				$ls_cadena=$ls_cadena."  ";
				$ls_cadena=$ls_cadena.$ls_nacper;
				$ls_cadena=$ls_cadena.str_pad($ls_cedper,8,"0",0);
				$ls_cadena=$ls_cadena."        ";
				$ls_cadena=$ls_cadena.$ld_feciniper;
				$ls_cadena=$ls_cadena.$ld_fecfinper; // Código de Ocupación
				$ls_cadena=$ls_cadena."                                               ";
				$ls_cadena=$ls_cadena."23"; // Código del Movimiento
				$ls_cadena=$ls_cadena."\r\n";
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
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al SANE de Permisos (Forma 14-10) ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}		
		return $lb_valido;
	}// end function uf_gendisk_permisos
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>