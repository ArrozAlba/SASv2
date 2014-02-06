<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_vistas_db
  // Description : Clase que posee la creación de las vistas 
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_vistas_db
{
    var $is_msg_error;
    var $io_database;
    function class_vistas_db($conn)//Constructor de la clase.
	{
	  require_once("class_funciones.php");
	  require_once("class_mensajes.php");
	  $this->io_sql       = new class_sql($conn);
	  $this->io_funcion   = new class_funciones(); 
	  $this->io_msg   = new class_mensajes(); 
	  $this->io_database  = $_SESSION["ls_database"];
	  $this->ls_gestor    = $_SESSION["ls_gestor"];
	} // end contructor    
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------   
	function uf_crear_vista_01()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 01
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;	   
			   $ls_sql= " CREATE VIEW cierre_contableaportes_contable AS
                          SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion,
						         sum(abs(sno_salida.valsal)) AS total, sno_concepto.codprov, sno_concepto.cedben,
								 sno_concepto.codconc, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                            FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                            WHERE sno_salida.valsal <> 0
							  AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')
							  AND sno_concepto.intprocon= '1'
							  AND spg_cuentas.status = 'C'
							  AND spg_ep1.estint = 0 
							  AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							  AND spg_ep1.estcla = sno_concepto.estcla
							  AND sno_personalnomina.codemp = sno_salida.codemp 
							  AND sno_personalnomina.codnom = sno_salida.codnom 
							  AND sno_personalnomina.codper = sno_salida.codper 
							  AND sno_salida.codemp = sno_concepto.codemp 
							  AND sno_salida.codnom = sno_concepto.codnom 
							  AND sno_salida.codconc = sno_concepto.codconc 
							  AND sno_personalnomina.codemp = sno_unidadadmin.codemp
							  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
							  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm
							  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							  AND spg_cuentas.codemp = sno_concepto.codemp 
							  AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							  AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							  AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							  AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							  AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							  AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
							  AND sno_concepto.estcla = spg_cuentas.estcla
							 GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov,
							          sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                               UNION 
                            SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total, 
							        sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc, sno_salida.codemp, 
									sno_salida.codnom, sno_salida.codperi
                              FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
							       spg_cuentas, scg_cuentas, spg_ep1
                             WHERE sno_salida.valsal <> 0
							   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							   AND sno_concepto.intprocon= '0'
							   AND spg_cuentas.status= 'C'
							   AND spg_ep1.estint = 0 
							   AND spg_ep1.codestpro1= substr(sno_unidadadmin.codprouniadm, 1, 25)
							   AND spg_ep1.estcla = sno_unidadadmin.estcla
							   AND sno_personalnomina.codemp = sno_salida.codemp 
							   AND sno_personalnomina.codnom = sno_salida.codnom 
							   AND sno_personalnomina.codper = sno_salida.codper 
							   AND sno_salida.codemp = sno_concepto.codemp 
							   AND sno_salida.codnom = sno_concepto.codnom 
							   AND sno_salida.codconc = sno_concepto.codconc 
							   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
							   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							   AND spg_cuentas.codemp = sno_concepto.codemp 
							   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							   AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							   AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							   AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							   AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
							   AND sno_unidadadmin.estcla = spg_cuentas.estcla
                            GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov, 
							         sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	          
			       
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	echo $this->io_sql->message;
				$this->io_msg->message("Problemas al ejecutar uf_crear_vista_01");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_01

//-------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_vista_02()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 02
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	  		   $ls_sql= " CREATE OR REPLACE VIEW cierre_contableaportes_contable_int AS 
                          SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total,
						        sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc, sno_salida.codemp, 
								sno_salida.codnom, sno_salida.codperi
                           FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
						        spg_cuentas, scg_cuentas, spg_ep1
                          WHERE sno_salida.valsal <> 0
						    AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							AND sno_concepto.intprocon = '1' 
							AND spg_cuentas.status= 'C' 
							AND spg_ep1.estint = 1 
							AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_concepto.estcla
							AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							AND sno_personalnomina.codemp = sno_salida.codemp 
							AND sno_personalnomina.codnom = sno_salida.codnom
							AND sno_personalnomina.codper = sno_salida.codper 
							AND sno_salida.codemp = sno_concepto.codemp 
							AND sno_salida.codnom = sno_concepto.codnom 
							AND sno_salida.codconc = sno_concepto.codconc 
							AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
							AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_concepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					      GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov, sno_concepto.cedben, 
					               sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                             UNION 
                      SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total, 
					         sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc, sno_salida.codemp, 
							 sno_salida.codnom, sno_salida.codperi
                        FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
						     spg_cuentas, scg_cuentas, spg_ep1
                       WHERE sno_salida.valsal <> 0
					     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')
						 AND sno_concepto.intprocon = '0'
						 AND spg_cuentas.status = 'C'
						 AND spg_ep1.estint = 1 
						 AND spg_ep1.codestpro1 = substr(sno_unidadadmin.codprouniadm, 1, 25)
						 AND spg_ep1.estcla = sno_unidadadmin.estcla
						 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
						 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
						 AND sno_personalnomina.codemp = sno_salida.codemp 
						 AND sno_personalnomina.codnom = sno_salida.codnom 
						 AND sno_personalnomina.codper = sno_salida.codper 
						 AND sno_salida.codemp = sno_concepto.codemp 
						 AND sno_salida.codnom = sno_concepto.codnom 
						 AND sno_salida.codconc = sno_concepto.codconc 
						 AND sno_personalnomina.codemp = sno_unidadadmin.codemp
						 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
						 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
						 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
						 AND spg_cuentas.codemp = sno_concepto.codemp 
						 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
						 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                     GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov,
					          sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi; ";	          
			     
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_02");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_02
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_03()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 03
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW cierre_contableaportes_contable_proy AS 
                          SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total, 
						         sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc, sno_salida.codemp,
								 sno_salida.codnom, sno_salida.codperi
                            FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas,
							     scg_cuentas, spg_ep1
                           WHERE sno_salida.valsal <> 0
						     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							 AND sno_concepto.intprocon = '1'
							 AND sno_concepto.conprocon = '0'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 0 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_concepto.estcla
							 AND sno_personalnomina.codemp = sno_salida.codemp
							 AND sno_personalnomina.codnom = sno_salida.codnom 
							 AND sno_personalnomina.codper = sno_salida.codper 
							 AND sno_salida.codemp = sno_concepto.codemp 
							 AND sno_salida.codnom = sno_concepto.codnom 
							 AND sno_salida.codconc = sno_concepto.codconc 
							 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
							 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_concepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
							 AND sno_concepto.estcla = spg_cuentas.estcla
                        GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov, 
						         sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                           UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total,
					       sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc,
						   sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE sno_salida.valsal <> 0
					  AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
					  AND sno_concepto.intprocon = '0'
					  AND sno_concepto.conprocon = '0'
					  AND spg_cuentas.status= 'C'
					  AND spg_ep1.estint = 0 
					  AND spg_ep1.codestpro1= substr(sno_unidadadmin.codprouniadm, 1, 25) 
					  AND spg_ep1.estcla = sno_unidadadmin.estcla
					  AND sno_personalnomina.codemp = sno_salida.codemp 
					  AND sno_personalnomina.codnom = sno_salida.codnom 
					  AND sno_personalnomina.codper = sno_salida.codper 
					  AND sno_salida.codemp = sno_concepto.codemp 
					  AND sno_salida.codnom = sno_concepto.codnom 
					  AND sno_salida.codconc = sno_concepto.codconc 
					  AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_concepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					  AND sno_unidadadmin.estcla = spg_cuentas.estcla
                   GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov,
				         sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi; ";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_03");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_03
//------------------------------------------------------------------------------------------------------------------------------------
///----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_vista_04()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 04
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= "CREATE OR REPLACE VIEW cierre_contableaportes_contable_proy_intcom AS 
                         SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total,
						        sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc, sno_salida.codemp, 
								sno_salida.codnom, sno_salida.codperi
                           FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, 
						        scg_cuentas, spg_ep1
                          WHERE sno_salida.valsal <> 0
						    AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							AND sno_concepto.intprocon = '1'
						    AND sno_concepto.conprocon = '0'
						    AND spg_cuentas.status = 'C'
							AND spg_ep1.estint = 1 
							AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_concepto.estcla
							AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							AND sno_personalnomina.codemp = sno_salida.codemp
							AND sno_personalnomina.codnom = sno_salida.codnom
							AND sno_personalnomina.codper = sno_salida.codper 
							AND sno_salida.codemp = sno_concepto.codemp 
							AND sno_salida.codnom = sno_concepto.codnom 
							AND sno_salida.codconc = sno_concepto.codconc 
							AND sno_personalnomina.codemp = sno_unidadadmin.codemp
							AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
							AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_concepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
                         GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov, 
						       sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                           UNION 
                     SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_salida.valsal)) AS total, 
					        sno_concepto.codprov, sno_concepto.cedben, sno_concepto.codconc,
						    sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                       FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					        spg_cuentas, scg_cuentas, spg_ep1
                      WHERE sno_salida.valsal <> 0
					    AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
						AND sno_concepto.intprocon = '0'
					    AND sno_concepto.conprocon = '0'
						AND spg_cuentas.status = 'C'
						AND spg_ep1.estint = 1 
						AND spg_ep1.codestpro1 = substr(sno_unidadadmin.codprouniadm, 1, 25) 
						AND spg_ep1.estcla = sno_unidadadmin.estcla
						AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
						AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
						AND sno_personalnomina.codemp = sno_salida.codemp
						AND sno_personalnomina.codnom = sno_salida.codnom 
						AND sno_personalnomina.codper = sno_salida.codper 
						AND sno_salida.codemp = sno_concepto.codemp 
						AND sno_salida.codnom = sno_concepto.codnom 
						AND sno_salida.codconc = sno_concepto.codconc 
						AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
						AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
						AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm
						AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
						AND spg_cuentas.codemp = sno_concepto.codemp 
						AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
						AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                    GROUP BY sno_concepto.codconc, scg_cuentas.sc_cuenta, sno_concepto.codprov,
					         sno_concepto.cedben, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi; ";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_04");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_04
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_05()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 05
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW contabilizar_aportes_scg_ajuste AS 
                          SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total, 
						         sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp, 
								 sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                            FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_hsalida.valsal <> 0
						     AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
							 AND sno_hconcepto.intprocon = '1'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 0 
							 AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_hconcepto.estcla
							 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi
							 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							 AND sno_hsalida.codemp = sno_hconcepto.codemp 
							 AND sno_hsalida.codnom = sno_hconcepto.codnom 
							 AND sno_hsalida.anocur = sno_hconcepto.anocur 
							 AND sno_hsalida.codperi = sno_hconcepto.codperi 
							 AND sno_hsalida.codconc = sno_hconcepto.codconc 
							 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm
							 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_hconcepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							 AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
							 AND sno_hconcepto.estcla = spg_cuentas.estcla
                          GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
						           sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom,
								   sno_hsalida.anocur, sno_hsalida.codperi
                               UNION 
                         SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total, 
						        sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp, 
								sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                           FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, 
						        spg_cuentas, scg_cuentas, spg_ep1
                          WHERE sno_hsalida.valsal <> 0
						    AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
							AND sno_hconcepto.intprocon = '0' 
							AND spg_cuentas.status = 'C' 
							AND spg_ep1.estint = 0 
							AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_hunidadadmin.estcla
							AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							AND sno_hsalida.codemp = sno_hconcepto.codemp 
							AND sno_hsalida.codnom = sno_hconcepto.codnom 
							AND sno_hsalida.anocur = sno_hconcepto.anocur 
							AND sno_hsalida.codperi = sno_hconcepto.codperi 
							AND sno_hsalida.codconc = sno_hconcepto.codconc 
							AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_hconcepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
							AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
							AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
							AND sno_hunidadadmin.estcla = spg_cuentas.estcla
                         GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov,
						          sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, 
								  sno_hsalida.anocur, sno_hsalida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_05");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_05
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_06()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 06
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= "   CREATE OR REPLACE VIEW contabilizar_aportes_scg_ajuste_int AS 
                            SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
							       sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp,
								   sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                              FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
							       spg_cuentas, scg_cuentas, spg_ep1
                             WHERE sno_hsalida.valsal <> 0
							   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
							   AND sno_hconcepto.intprocon = '1'
							   AND spg_cuentas.status = 'C'
							   AND spg_ep1.estint = 1 
							   AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							   AND spg_ep1.estcla = sno_hconcepto.estcla
							   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp
							   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							   AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							   AND sno_hsalida.codemp = sno_hconcepto.codemp 
							   AND sno_hsalida.codnom = sno_hconcepto.codnom 
							   AND sno_hsalida.anocur = sno_hconcepto.anocur 
							   AND sno_hsalida.codperi = sno_hconcepto.codperi 
							   AND sno_hsalida.codconc = sno_hconcepto.codconc 
							   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp
							   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							   AND spg_cuentas.codemp = sno_hconcepto.codemp 
							   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
							   AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
							   AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							   AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							   AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							   AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
                           GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
						            sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom,
									sno_hsalida.anocur, sno_hsalida.codperi
                                UNION 
                          SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
							     sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp, 
								 sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                            FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_hsalida.valsal <> 0
						     AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
							 AND sno_hconcepto.intprocon = '0'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 1 
							 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_hunidadadmin.estcla
							 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							 AND sno_hsalida.codemp = sno_hconcepto.codemp 
							 AND sno_hsalida.codnom = sno_hconcepto.codnom 
							 AND sno_hsalida.anocur = sno_hconcepto.anocur 
							 AND sno_hsalida.codperi = sno_hconcepto.codperi 
							 AND sno_hsalida.codconc = sno_hconcepto.codconc 
							 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_hconcepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
							 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                        GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov,
						         sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, 
								 sno_hsalida.anocur, sno_hsalida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_06");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_06
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_07()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 07
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= "  CREATE OR REPLACE VIEW contabilizar_conceptos_scg_ajuste AS 
                           SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total, 
						          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                             FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
							      spg_cuentas, scg_cuentas, spg_ep1
                           WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
						     AND sno_hsalida.valsal <> 0
							 AND sno_hconcepto.intprocon = '1'
							 AND spg_cuentas.status = 'C' 
							 AND spg_ep1.estint = 0 
							 AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_hconcepto.estcla
							 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							 AND sno_hsalida.codemp = sno_hconcepto.codemp 
							 AND sno_hsalida.codnom = sno_hconcepto.codnom 
							 AND sno_hsalida.anocur = sno_hconcepto.anocur 
							 AND sno_hsalida.codperi = sno_hconcepto.codperi 
							 AND sno_hsalida.codconc = sno_hconcepto.codconc 
							 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom
							 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_hconcepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
							 AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1 
							 AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
							 AND sno_hconcepto.estcla = spg_cuentas.estcla
                       GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
					            sno_hsalida.anocur, sno_hsalida.codperi
                           UNION 
                       SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total, 
					          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                         FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
						      spg_cuentas, scg_cuentas, spg_ep1
                        WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
						  AND sno_hsalida.valsal <> 0
						  AND sno_hconcepto.intprocon = '0'
						  AND spg_cuentas.status = 'C'
						  AND spg_ep1.estint = 0 
						  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
						  AND spg_ep1.estcla = sno_hunidadadmin.estcla
						  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
						  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom
						  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
						  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
						  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
						  AND sno_hsalida.codemp = sno_hconcepto.codemp 
						  AND sno_hsalida.codnom = sno_hconcepto.codnom 
						  AND sno_hsalida.anocur = sno_hconcepto.anocur 
						  AND sno_hsalida.codperi = sno_hconcepto.codperi 
						  AND sno_hsalida.codconc = sno_hconcepto.codconc 
						  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
						  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
						  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
						  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
						  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
						  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
						  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
						  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
						  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
						  AND spg_cuentas.codemp = sno_hconcepto.codemp 
						  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
						  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
						  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						  AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						  AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						  AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						  AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
						  AND sno_hunidadadmin.estcla = spg_cuentas.estcla
                      GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
					           sno_hsalida.anocur, sno_hsalida.codperi
                          UNION 
                      SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_hsalida.valsal) AS total,
					         sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                        FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
						     spg_cuentas, scg_cuentas, spg_ep1
                       WHERE (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') 
					     AND sno_hsalida.valsal <> 0
						 AND sno_hconcepto.sigcon = 'E'
						 AND sno_hconcepto.intprocon = '1'
						 AND spg_cuentas.status = 'C'
						 AND spg_ep1.estint = 0 
						 AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
						 AND spg_ep1.estcla = sno_hconcepto.estcla
						 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
						 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
						 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
						 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
						 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
						 AND sno_hsalida.codemp = sno_hconcepto.codemp 
						 AND sno_hsalida.codnom = sno_hconcepto.codnom 
						 AND sno_hsalida.anocur = sno_hconcepto.anocur 
						 AND sno_hsalida.codperi = sno_hconcepto.codperi 
						 AND sno_hsalida.codconc = sno_hconcepto.codconc 
						 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
						 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
						 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
						 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
						 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
						 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
						 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
						 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
						 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
						 AND spg_cuentas.codemp = sno_hconcepto.codemp 
						 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
						 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
						 AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
						 AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
						 AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
						 AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
						 AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
						 AND sno_hconcepto.estcla = spg_cuentas.estcla
                   GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
				            sno_hsalida.anocur, sno_hsalida.codperi
                       UNION 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_hsalida.valsal) AS total, 
				          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                     FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') 
					  AND sno_hsalida.valsal <> 0
					  AND sno_hconcepto.sigcon = 'E'
					  AND sno_hconcepto.intprocon = '0'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_hunidadadmin.estcla
					  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					  AND sno_hsalida.codemp = sno_hconcepto.codemp 
					  AND sno_hsalida.codnom = sno_hconcepto.codnom 
					  AND sno_hsalida.anocur = sno_hconcepto.anocur 
					  AND sno_hsalida.codperi = sno_hconcepto.codperi 
					  AND sno_hsalida.codconc = sno_hconcepto.codconc 
					  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_hconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					  AND sno_hunidadadmin.estcla = spg_cuentas.estcla
                 GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom,
				       sno_hsalida.anocur, sno_hsalida.codperi
                     UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total,
				        sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                   FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas
                  WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
				    AND sno_hsalida.valsal <> 0
				    AND sno_hconcepto.intprocon = '0'
				    AND sno_hconcepto.sigcon = 'B'
					AND scg_cuentas.status = 'C'
				    AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					AND sno_hsalida.codemp = sno_hconcepto.codemp 
					AND sno_hsalida.codnom = sno_hconcepto.codnom 
					AND sno_hsalida.anocur = sno_hconcepto.anocur 
					AND sno_hsalida.codperi = sno_hconcepto.codperi 
					AND sno_hsalida.codconc = sno_hconcepto.codconc
					AND scg_cuentas.codemp = sno_hconcepto.codemp 
					AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon
                  GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                      UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_hsalida.valsal) AS total, 
				         sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                   FROM sno_hpersonalnomina, sno_hsalida, sno_hconcepto, scg_cuentas
                  WHERE (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3') 
				    AND sno_hsalida.valsal <> 0
					AND scg_cuentas.status = 'C'
					AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					AND sno_hsalida.codemp = sno_hconcepto.codemp 
					AND sno_hsalida.codnom = sno_hconcepto.codnom 
					AND sno_hsalida.anocur = sno_hconcepto.anocur 
					AND sno_hsalida.codperi = sno_hconcepto.codperi 
					AND sno_hsalida.codconc = sno_hconcepto.codconc 
					AND scg_cuentas.codemp = sno_hconcepto.codemp
					AND scg_cuentas.sc_cuenta = sno_hconcepto.cueconcon
               GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur,
			         sno_hsalida.codperi; ";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_07");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_07
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_08()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 08
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= "  CREATE OR REPLACE VIEW contabilizar_conceptos_scg_ajuste_int AS 
                           SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total, 
						          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                             FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
							      spg_cuentas, scg_cuentas, spg_ep1
                            WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
							  AND sno_hsalida.valsal <> 0
							  AND sno_hconcepto.intprocon = '1'
							  AND spg_cuentas.status = 'C'
							  AND spg_ep1.estint = 1 
							  AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							  AND spg_ep1.estcla = sno_hconcepto.estcla
							  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							  AND sno_hsalida.codemp = sno_hconcepto.codemp 
							  AND sno_hsalida.codnom = sno_hconcepto.codnom 
							  AND sno_hsalida.anocur = sno_hconcepto.anocur 
							  AND sno_hsalida.codperi = sno_hconcepto.codperi 
							  AND sno_hsalida.codconc = sno_hconcepto.codconc 
							  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
							  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							  AND spg_cuentas.codemp = sno_hconcepto.codemp 
							  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
							  AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
							  AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							  AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							  AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							  AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
                         GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
						          sno_hsalida.anocur, sno_hsalida.codperi
                            UNION 
                         SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total,
						        sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                           FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, 
						        spg_cuentas, scg_cuentas, spg_ep1
                          WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
						    AND sno_hsalida.valsal <> 0
							AND sno_hconcepto.intprocon = '0'
							AND spg_cuentas.status = 'C'
							AND spg_ep1.estint = 1 
							AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_hunidadadmin.estcla
							AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
							AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
							AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
							AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
							AND sno_hpersonalnomina.codper = sno_hsalida.codper 
							AND sno_hsalida.codemp = sno_hconcepto.codemp 
							AND sno_hsalida.codnom = sno_hconcepto.codnom 
							AND sno_hsalida.anocur = sno_hconcepto.anocur 
							AND sno_hsalida.codperi = sno_hconcepto.codperi 
							AND sno_hsalida.codconc = sno_hconcepto.codconc 
							AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
							AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
							AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
							AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
							AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
							AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm
							AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
							AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
							AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_hconcepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
							AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                     GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
				            sno_hsalida.anocur, sno_hsalida.codperi
					 UNION
					  SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_hsalida.valsal) AS total,
					         sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                        FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto,
						     spg_cuentas, scg_cuentas, spg_ep1
                       WHERE (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') 
					     AND sno_hsalida.valsal <> 0
						 AND sno_hconcepto.sigcon = 'E'
						 AND sno_hconcepto.intprocon = '1'
						 AND spg_cuentas.status = 'C'
						 AND spg_ep1.estint = 1 
						 AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
						 AND spg_ep1.estcla = sno_hconcepto.estcla
						 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
						 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
						 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
						 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
						 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
						 AND sno_hsalida.codemp = sno_hconcepto.codemp 
						 AND sno_hsalida.codnom = sno_hconcepto.codnom 
						 AND sno_hsalida.anocur = sno_hconcepto.anocur 
						 AND sno_hsalida.codperi = sno_hconcepto.codperi 
						 AND sno_hsalida.codconc = sno_hconcepto.codconc 
						 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
						 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
						 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
						 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
						 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
						 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
						 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
						 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
						 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
						 AND spg_cuentas.codemp = sno_hconcepto.codemp 
						 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
						 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
						 AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
						 AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
						 AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
						 AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
						 AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5						 
                   GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
				            sno_hsalida.anocur, sno_hsalida.codperi
                       UNION 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_hsalida.valsal) AS total, 
				          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                     FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2') 
					  AND sno_hsalida.valsal <> 0
					  AND sno_hconcepto.sigcon = 'E'
					  AND sno_hconcepto.intprocon = '0'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_hunidadadmin.estcla
					  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					  AND sno_hsalida.codemp = sno_hconcepto.codemp 
					  AND sno_hsalida.codnom = sno_hconcepto.codnom 
					  AND sno_hsalida.anocur = sno_hconcepto.anocur 
					  AND sno_hsalida.codperi = sno_hconcepto.codperi 
					  AND sno_hsalida.codconc = sno_hconcepto.codconc 
					  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_hconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
					  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5					  
                 GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom,
				       sno_hsalida.anocur, sno_hsalida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_08");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_08
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_09()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 09
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= "   CREATE OR REPLACE VIEW contableaporte_contable_historico AS 
                            SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
							       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
								   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                              FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
							       spg_cuentas, scg_cuentas, spg_ep1
                             WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
							   AND sno_thconcepto.intprocon = '1'
							   AND spg_cuentas.status= 'C' 
							   AND spg_ep1.estint = 0 
							   AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							   AND spg_ep1.estcla = sno_thconcepto.estcla
							   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
							   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
							   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
							   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
							   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
							   AND sno_thsalida.codemp = sno_thconcepto.codemp 
							   AND sno_thsalida.codnom = sno_thconcepto.codnom 
							   AND sno_thsalida.anocur = sno_thconcepto.anocur 
							   AND sno_thsalida.codperi = sno_thconcepto.codperi 
							   AND sno_thsalida.codconc = sno_thconcepto.codconc 
							   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
							   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
							   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
							   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi
							   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
							   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
							   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm
							   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
							   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
							   AND spg_cuentas.codemp = sno_thconcepto.codemp 
							   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
							   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							   AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
							   AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							   AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							   AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							   AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
							   AND sno_thconcepto.estcla = spg_cuentas.estcla
                        GROUP BY spg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
						         sno_thsalida.anocur, sno_thsalida.codperi
                              UNION 
                        SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
							   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                          FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto,
						       spg_cuentas, scg_cuentas, spg_ep1
                         WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
						   AND sno_thconcepto.intprocon = '0'
						   AND spg_cuentas.status = 'C'
						   AND spg_ep1.estint = 0 
						   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
						   AND spg_ep1.estcla = sno_thunidadadmin.estcla
						   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
						   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
						   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
						   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
						   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
						   AND sno_thsalida.codemp = sno_thconcepto.codemp 
						   AND sno_thsalida.codnom = sno_thconcepto.codnom 
						   AND sno_thsalida.anocur = sno_thconcepto.anocur 
						   AND sno_thsalida.codperi = sno_thconcepto.codperi 
						   AND sno_thsalida.codconc = sno_thconcepto.codconc 
						   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
						   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
						   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
						   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
						   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm
						   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
						   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
						   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
						   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
						   AND spg_cuentas.codemp = sno_thconcepto.codemp 
						   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
						   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
						   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						   AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						   AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						   AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						   AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
						   AND sno_thunidadadmin.estcla = spg_cuentas.estcla
                    GROUP BY spg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom,
					         sno_thsalida.anocur, sno_thsalida.codperi; ";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_09");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_09
//------------------------------------------------------------------------------------------------------------------------------------	
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_10()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 10
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW contableaporte_contable_historico_int AS 
                          SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						         'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp,
								  sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                            FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
						     AND sno_thconcepto.intprocon = '1'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 1 
							 AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_thconcepto.estcla
							 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
							 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
							 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
							 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
							 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
							 AND sno_thsalida.codemp = sno_thconcepto.codemp
							 AND sno_thsalida.codnom = sno_thconcepto.codnom 
							 AND sno_thsalida.anocur = sno_thconcepto.anocur 
							 AND sno_thsalida.codperi = sno_thconcepto.codperi 
							 AND sno_thsalida.codconc = sno_thconcepto.codconc 
							 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
							 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
							 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
							 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
							 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm
							 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
							 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
							 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
							 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_thconcepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
							 AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
                         GROUP BY spg_cuentas.scgctaint, sno_thsalida.codemp, sno_thsalida.codnom,
						          sno_thsalida.anocur, sno_thsalida.codperi
                              UNION 
                         SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta,
						        'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
								 sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                           FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
						        spg_cuentas, scg_cuentas, spg_ep1
                          WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
						    AND sno_thconcepto.intprocon = '0'
						    AND spg_cuentas.status = 'C'
							AND spg_ep1.estint = 1
							AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_thunidadadmin.estcla
							AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
							AND sno_thpersonalnomina.codnom = sno_thsalida.codnom
							AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
							AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
							AND sno_thpersonalnomina.codper = sno_thsalida.codper 
							AND sno_thsalida.codemp = sno_thconcepto.codemp 
							AND sno_thsalida.codnom = sno_thconcepto.codnom 
							AND sno_thsalida.anocur = sno_thconcepto.anocur 
							AND sno_thsalida.codperi = sno_thconcepto.codperi 
							AND sno_thsalida.codconc = sno_thconcepto.codconc 
							AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
							AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
							AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
							AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
							AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
							AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
							AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
							AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm
							AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_thconcepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
							AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                      GROUP BY spg_cuentas.scgctaint, sno_thsalida.codemp, sno_thsalida.codnom, 
					         sno_thsalida.anocur, sno_thsalida.codperi;  ";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_10");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_10
//----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_11()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 11
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW contableaportes_contable AS 
                          SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
								 sno_salida.codnom, sno_salida.codperi
                            FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_salida.valsal <> 0
						     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							 AND sno_concepto.intprocon = '1'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 0 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_concepto.estcla
							 AND sno_personalnomina.codemp = sno_salida.codemp
							 AND sno_personalnomina.codnom = sno_salida.codnom 
							 AND sno_personalnomina.codper = sno_salida.codper 
							 AND sno_salida.codemp = sno_concepto.codemp 
							 AND sno_salida.codnom = sno_concepto.codnom 
							 AND sno_salida.codconc = sno_concepto.codconc 
							 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
							 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_concepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
							 AND sno_concepto.estcla = spg_cuentas.estcla
                        GROUP BY spg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                             UNION 
                        SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
							   sno_salida.codnom, sno_salida.codperi
                          FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
						       spg_cuentas, scg_cuentas, spg_ep1
                         WHERE sno_salida.valsal <> 0
						   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
						   AND sno_concepto.intprocon = '0'
						   AND spg_cuentas.status = 'C'
						   AND spg_ep1.estint = 0 
						   AND spg_ep1.codestpro1 = substr(sno_unidadadmin.codprouniadm, 1, 25) 
						   AND spg_ep1.estcla = sno_unidadadmin.estcla
						   AND sno_personalnomina.codemp = sno_salida.codemp 
						   AND sno_personalnomina.codnom = sno_salida.codnom 
						   AND sno_personalnomina.codper = sno_salida.codper 
						   AND sno_salida.codemp = sno_concepto.codemp 
						   AND sno_salida.codnom = sno_concepto.codnom 
						   AND sno_salida.codconc = sno_concepto.codconc 
						   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
						   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
						   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
						   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
						   AND spg_cuentas.codemp = sno_concepto.codemp 
						   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
						   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
						   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						   AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						   AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						   AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						   AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
						   AND sno_unidadadmin.estcla = spg_cuentas.estcla
                    GROUP BY spg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_11");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_11
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_12()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 12
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW contableaportes_contable_intcom AS 
                          SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
								  sno_salida.codnom, sno_salida.codperi
                            FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_salida.valsal <> 0
						     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							 AND sno_concepto.intprocon = '1'
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 1 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla= sno_concepto.estcla
							 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
							 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
							 AND sno_personalnomina.codemp = sno_salida.codemp 
							 AND sno_personalnomina.codnom = sno_salida.codnom 
							 AND sno_personalnomina.codper = sno_salida.codper 
							 AND sno_salida.codemp = sno_concepto.codemp 
							 AND sno_salida.codnom = sno_concepto.codnom 
							 AND sno_salida.codconc = sno_concepto.codconc 
							 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
							 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm
							 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_concepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
                        GROUP BY spg_cuentas.scgctaint, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                           UNION 
                        SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
							   sno_salida.codnom, sno_salida.codperi
                          FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
						       spg_cuentas, scg_cuentas, spg_ep1
                         WHERE sno_salida.valsal <> 0
						  AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
						  AND sno_concepto.intprocon = '0'
						  AND spg_cuentas.status = 'C'
						  AND spg_ep1.estint = 1 
						  AND spg_ep1.codestpro1 = substr(sno_unidadadmin.codprouniadm, 1, 25) 
						  AND spg_ep1.estcla = sno_unidadadmin.estcla
						  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
						  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
						  AND sno_personalnomina.codemp = sno_salida.codemp 
						  AND sno_personalnomina.codnom = sno_salida.codnom 
						  AND sno_personalnomina.codper = sno_salida.codper 
						  AND sno_salida.codemp = sno_concepto.codemp 
						  AND sno_salida.codnom = sno_concepto.codnom 
						  AND sno_salida.codconc = sno_concepto.codconc 
						  AND sno_personalnomina.codemp = sno_unidadadmin.codemp
						  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
						  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
						  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
						  AND spg_cuentas.codemp = sno_concepto.codemp 
						  AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
						  AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
						  AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
						  AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
						  AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
						  AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                      GROUP BY spg_cuentas.scgctaint, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_12");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_12
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_13()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 13
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	    	   $ls_sql= " CREATE OR REPLACE VIEW contableaportes_contable_proyecto AS 
                          SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
								 sno_salida.codnom, sno_salida.codperi
                            FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_salida.valsal <> 0
						     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							 AND sno_concepto.intprocon= '1'
							 AND spg_cuentas.status = 'C'
							 AND sno_concepto.conprocon = '0'
							 AND spg_ep1.estint = 0 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_concepto.estcla
							 AND sno_personalnomina.codemp = sno_salida.codemp 
							 AND sno_personalnomina.codnom = sno_salida.codnom 
							 AND sno_personalnomina.codper = sno_salida.codper 
							 AND sno_salida.codemp = sno_concepto.codemp 
							 AND sno_salida.codnom = sno_concepto.codnom 
							 AND sno_salida.codconc = sno_concepto.codconc 
							 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
							 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
							 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							 AND spg_cuentas.codemp = sno_concepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
							 AND sno_concepto.estcla = spg_cuentas.estcla
                        GROUP BY spg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                            UNION 
                        SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
						       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
							    sno_salida.codnom, sno_salida.codperi
                          FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
						       spg_cuentas, scg_cuentas, spg_ep1
                         WHERE sno_salida.valsal <> 0
						    AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
							AND sno_concepto.intprocon = '0'
							AND spg_cuentas.status = 'C'
							AND sno_concepto.conprocon = '0'
							AND spg_ep1.estint = 0 
							AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
							AND spg_ep1.estcla = sno_unidadadmin.estcla
							AND sno_personalnomina.codemp = sno_salida.codemp 
							AND sno_personalnomina.codnom = sno_salida.codnom 
							AND sno_personalnomina.codper = sno_salida.codper 
							AND sno_salida.codemp = sno_concepto.codemp 
							AND sno_salida.codnom = sno_concepto.codnom 
							AND sno_salida.codconc = sno_concepto.codconc 
							AND sno_personalnomina.codemp = sno_unidadadmin.codemp
							AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
							AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
							AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
							AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
							AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
							AND spg_cuentas.codemp = sno_concepto.codemp 
							AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
							AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
							AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
							AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
							AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
							AND sno_unidadadmin.estcla= spg_cuentas.estcla
                       GROUP BY spg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	          
			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_13");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_13
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_vista_14()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 14
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	   switch ($this->ls_gestor)
	   {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)),3) ";
				$ls_operacion="CONVERT( 'D' USING utf8) as operacion";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				$ls_operacion="CAST('D' AS char(1)) as operacion";
				break;			
		}
		$ls_sql= " CREATE OR REPLACE VIEW contableaportes_contable_proyecto_dt AS 
                          SELECT scg_cuentas.sc_cuenta, ".$ls_operacion.", sum(abs(sno_salida.valsal)) AS total, 
								 $ls_cadena AS montoparcial, 
								 max(sno_concepto.codprov) AS codprov, max(sno_concepto.cedben) AS cedben, 
								 sno_concepto.codconc, sno_proyectopersonal.codper, sno_proyecto.codproy, 
								 max(scg_cuentas.denominacion) AS denoconta, max(sno_proyectopersonal.pordiames) AS pordiames,
								 sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                            FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, 
							     spg_cuentas, scg_cuentas, spg_ep1
                           WHERE sno_salida.valsal <> 0
						     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')
							 AND sno_concepto.conprocon = '1' 
							 AND spg_cuentas.status = 'C'
							 AND spg_ep1.estint = 0 
							 AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
							 AND spg_ep1.estcla = sno_proyecto.estcla 
							 AND sno_proyectopersonal.codemp = sno_salida.codemp 
							 AND sno_proyectopersonal.codnom = sno_salida.codnom
							 AND sno_proyectopersonal.codper = sno_salida.codper 
							 AND sno_salida.codemp = sno_concepto.codemp 
							 AND sno_salida.codnom = sno_concepto.codnom 
							 AND sno_salida.codconc = sno_concepto.codconc 
							 AND sno_proyectopersonal.codemp = sno_proyecto.codemp 
							 AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
							 AND spg_cuentas.codemp = sno_concepto.codemp 
							 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
							 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
							 AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
							 AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
							 AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
							 AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
							 AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
							 AND sno_proyecto.estcla = spg_cuentas.estcla
                        GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, sno_proyecto.codproy, 
						         scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                        ORDER BY sno_proyectopersonal.codper, sno_concepto.codconc, 
						         sno_proyecto.codproy, scg_cuentas.sc_cuenta;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_14");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_14
//-----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_15()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 15
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    switch ($this->ls_gestor)
	    {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)),3) ";
				$ls_operacion="CONVERT( 'D' USING utf8) as operacion";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				$ls_operacion="CAST('D' AS char(1)) as operacion";
				break;			
	    }
	    $ls_sql= "     CREATE OR REPLACE VIEW contableaportes_contable_proyecto_dt_intcom AS 
                       SELECT scg_cuentas.sc_cuenta, ".$ls_operacion.", sum(abs(sno_salida.valsal)) AS total, 
					          $ls_cadena AS montoparcial, max(sno_concepto.codprov) AS codprov,
						      max(sno_concepto.cedben) AS cedben, sno_concepto.codconc, 
						      sno_proyectopersonal.codper, sno_proyecto.codproy, max(scg_cuentas.denominacion) AS denoconta, 
						      max(sno_proyectopersonal.pordiames) AS pordiames, sno_salida.codemp, sno_salida.codnom,
						      sno_salida.codperi
                         FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto,
						      spg_cuentas, scg_cuentas, spg_ep1
                        WHERE sno_salida.valsal <> 0
						  AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')
						  AND sno_concepto.conprocon = '1'
						  AND spg_cuentas.status= 'C'
						  AND spg_ep1.estint = 1 
						  AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
						  AND spg_ep1.estcla = sno_proyecto.estcla
						  AND spg_ep1.estcla = sno_proyecto.estcla
						  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
						  AND sno_proyectopersonal.codemp = sno_salida.codemp 
						  AND sno_proyectopersonal.codnom = sno_salida.codnom 
						  AND sno_proyectopersonal.codper = sno_salida.codper 
						  AND sno_salida.codemp = sno_concepto.codemp 
						  AND sno_salida.codnom = sno_concepto.codnom 
						  AND sno_salida.codconc = sno_concepto.codconc 
						  AND sno_proyectopersonal.codemp = sno_proyecto.codemp 
						  AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
						  AND spg_cuentas.codemp = sno_concepto.codemp 
						  AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
						  AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
						  AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
						  AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
						  AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
						  AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
                      GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, sno_proyecto.codproy, 
					        scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      ORDER BY sno_proyectopersonal.codper, sno_concepto.codconc,
					        sno_proyecto.codproy, scg_cuentas.sc_cuenta;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_15");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_15
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_16()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 16
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= " CREATE OR REPLACE VIEW contableaportes_contable_proyecto_historico AS 
                   SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
				          'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp,
						  sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                     FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto,
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')
					  AND sno_thconcepto.intprocon = '1'
					  AND sno_thconcepto.conprocon = '0'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_thconcepto.estcla
					  AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					  AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					  AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					  AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					  AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					  AND sno_thsalida.codemp = sno_thconcepto.codemp 
					  AND sno_thsalida.codnom = sno_thconcepto.codnom 
					  AND sno_thsalida.anocur = sno_thconcepto.anocur
					  AND sno_thsalida.codperi = sno_thconcepto.codperi 
					  AND sno_thsalida.codconc = sno_thconcepto.codconc 
					  AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
					  AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					  AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					  AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					  AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					  AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					  AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					  AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					  AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_thconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
					  AND sno_thconcepto.estcla = spg_cuentas.estcla
             GROUP BY spg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
			          sno_thsalida.anocur, sno_thsalida.codperi
                   UNION 
             SELECT spg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
			        'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp,
					 sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
              FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
			       spg_cuentas, scg_cuentas, spg_ep1
             WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
			   AND sno_thconcepto.intprocon = '0'
			   AND sno_thconcepto.conprocon = '0'
			   AND spg_cuentas.status = 'C'
			   AND spg_ep1.estint = 0 
			   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
			   AND spg_ep1.estcla = sno_thunidadadmin.estcla
			   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
			   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
			   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
			   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
			   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
			   AND sno_thsalida.codemp = sno_thconcepto.codemp 
			   AND sno_thsalida.codnom = sno_thconcepto.codnom 
			   AND sno_thsalida.anocur = sno_thconcepto.anocur 
			   AND sno_thsalida.codperi = sno_thconcepto.codperi
			   AND sno_thsalida.codconc = sno_thconcepto.codconc 
			   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
			   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
			   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
			   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
			   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
			   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
			   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
			   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm
			   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
			   AND spg_cuentas.codemp = sno_thconcepto.codemp 
			   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
			   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
			   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
			   AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
			   AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
			   AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
			   AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
			   AND sno_thunidadadmin.estcla = spg_cuentas.estcla
           GROUP BY spg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
		            sno_thsalida.anocur, sno_thsalida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_16");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_16
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_17()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 17
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= "  CREATE OR REPLACE VIEW contableaportes_contable_proyecto_historico_int AS 
                    SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
					       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                      FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
					   AND sno_thconcepto.intprocon = '1'
					   AND sno_thconcepto.conprocon= '0'
					   AND spg_cuentas.status= 'C'
					   AND spg_ep1.estint = 1 
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_thconcepto.estcla
					   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					   AND sno_thsalida.codemp = sno_thconcepto.codemp 
					   AND sno_thsalida.codnom = sno_thconcepto.codnom 
					   AND sno_thsalida.anocur = sno_thconcepto.anocur 
					   AND sno_thsalida.codperi = sno_thconcepto.codperi 
					   AND sno_thsalida.codconc = sno_thconcepto.codconc 
					   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_thconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
                 GROUP BY spg_cuentas.scgctaint, sno_thsalida.codemp, sno_thsalida.codnom, 
				          sno_thsalida.anocur, sno_thsalida.codperi
                       UNION 
                 SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
				        'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                   FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto,
				        spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4') 
				    AND sno_thconcepto.intprocon = '0'
				    AND sno_thconcepto.conprocon = '0'
					AND spg_cuentas.status = 'C'
					AND spg_ep1.estint = 1 
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					AND spg_ep1.estcla = sno_thunidadadmin.estcla
					AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					AND sno_thsalida.codemp = sno_thconcepto.codemp 
					AND sno_thsalida.codnom = sno_thconcepto.codnom 
					AND sno_thsalida.anocur = sno_thconcepto.anocur 
					AND sno_thsalida.codperi = sno_thconcepto.codperi 
					AND sno_thsalida.codconc = sno_thconcepto.codconc 
					AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_thconcepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                 GROUP BY spg_cuentas.scgctaint, sno_thsalida.codemp, sno_thsalida.codnom, 
				 sno_thsalida.anocur, sno_thsalida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_17");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_17
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_18()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 18
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= "  CREATE OR REPLACE VIEW contableaportes_contable_proyecto_intcom AS 
                    SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
					       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						   sno_salida.codnom, sno_salida.codperi
                      FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE sno_salida.valsal <> 0
					   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')
					   AND sno_concepto.intprocon = '1'
					   AND spg_cuentas.status = 'C'
					   AND sno_concepto.conprocon = '0'
					   AND spg_ep1.estint = 1 
					   AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla= sno_concepto.estcla
					   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					   AND sno_personalnomina.codemp = sno_salida.codemp 
					   AND sno_personalnomina.codnom = sno_salida.codnom 
					   AND sno_personalnomina.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_concepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
					   AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
                  GROUP BY spg_cuentas.scgctaint, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                       UNION 
                  SELECT spg_cuentas.scgctaint AS cuenta, max(scg_cuentas.denominacion) AS denoconta, 
				         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					     spg_cuentas, scg_cuentas, spg_ep1
                   WHERE sno_salida.valsal <> 0
				     AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4') 
					 AND sno_concepto.intprocon = '0'
					 AND spg_cuentas.status = 'C'
					 AND sno_concepto.conprocon= '0'
					 AND spg_ep1.estint = 1 
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_unidadadmin.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                   GROUP BY spg_cuentas.scgctaint, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;  ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_18");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_18
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_19()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 19
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						  sno_salida.codnom, sno_salida.codperi
                     FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
					  AND sno_salida.valsal <> 0
					  AND sno_concepto.intprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_concepto.estcla
					  AND sno_personalnomina.codemp = sno_salida.codemp 
					  AND sno_personalnomina.codnom = sno_salida.codnom 
					  AND sno_personalnomina.codper = sno_salida.codper 
					  AND sno_salida.codemp = sno_concepto.codemp 
					  AND sno_salida.codnom = sno_concepto.codnom
					  AND sno_salida.codconc = sno_concepto.codconc 
					  AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_concepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					  AND sno_concepto.estcla = spg_cuentas.estcla
                    GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                        UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					       'D' AS operacion, sum(sno_salida.valsal) AS total, 
						   sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1')
					   AND sno_salida.valsal <> 0
					   AND sno_concepto.intprocon= '0'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_unidadadmin.estcla
					   AND sno_personalnomina.codemp = sno_salida.codemp 
					   AND sno_personalnomina.codnom = sno_salida.codnom 
					   AND sno_personalnomina.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_concepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					   AND sno_unidadadmin.estcla = spg_cuentas.estcla
                    GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                       UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						   sno_salida.codnom, sno_salida.codperi
                       FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas
                      WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
					    AND sno_salida.valsal <> 0
					    AND sno_concepto.intprocon = '0'
						AND scg_cuentas.status = 'C'
						AND sno_concepto.sigcon = 'B'
						AND sno_personalnomina.codemp = sno_salida.codemp 
						AND sno_personalnomina.codnom = sno_salida.codnom 
						AND sno_personalnomina.codper = sno_salida.codper 
						AND sno_salida.codemp = sno_concepto.codemp 
						AND sno_salida.codnom = sno_concepto.codnom 
						AND sno_salida.codconc = sno_concepto.codconc 
						AND scg_cuentas.codemp = sno_concepto.codemp 
						AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon
                    GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                        UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					       'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						   sno_salida.codnom, sno_salida.codperi
                      FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas
                     WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
					   AND sno_salida.valsal <> 0
					   AND scg_cuentas.status = 'C'
					   AND sno_personalnomina.codemp = sno_salida.codemp 
					   AND sno_personalnomina.codnom = sno_salida.codnom 
					   AND sno_personalnomina.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND scg_cuentas.codemp = sno_concepto.codemp 
					   AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon
                    GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                       UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion,
					       'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						   sno_salida.codnom, sno_salida.codperi
                      FROM sno_personalnomina, sno_unidadadmin, sno_salida, 
					       sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
					   AND sno_salida.valsal <> 0
					   AND sno_concepto.intprocon= '1'
					   AND sno_concepto.sigcon = 'E'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_concepto.estcla
					   AND sno_personalnomina.codemp = sno_salida.codemp 
					   AND sno_personalnomina.codnom = sno_salida.codnom 
					   AND sno_personalnomina.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_concepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					   AND sno_concepto.estcla= spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                       UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					     spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon= '0'
					 AND sno_concepto.sigcon = 'E'
					 AND spg_cuentas.status = 'C'
					 AND spg_ep1.estint = 0 
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla= sno_unidadadmin.estcla
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					 AND sno_unidadadmin.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_19");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_19
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_20()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 20
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= "  CREATE OR REPLACE VIEW contableconceptos_contable_historico AS 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                      FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas,
					       scg_cuentas, spg_ep1
                      WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					    AND sno_thsalida.valsal <> 0
						AND sno_thconcepto.intprocon = '1'
						AND spg_cuentas.status = 'C'
						AND spg_ep1.estint = 0 
						AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
						AND spg_ep1.estcla = sno_thconcepto.estcla
						AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
						AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
						AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
						AND sno_thpersonalnomina.codperi = sno_thsalida.codperi
						AND sno_thpersonalnomina.codper = sno_thsalida.codper 
						AND sno_thsalida.codemp = sno_thconcepto.codemp 
						AND sno_thsalida.codnom = sno_thconcepto.codnom 
						AND sno_thsalida.anocur = sno_thconcepto.anocur
					    AND sno_thsalida.codperi = sno_thconcepto.codperi 
						AND sno_thsalida.codconc = sno_thconcepto.codconc 
						AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
						AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
						AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
						AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi
						AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
						AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
						AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
						AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm
						AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
						AND spg_cuentas.codemp = sno_thconcepto.codemp 
						AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
						AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
						AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
						AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
						AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
						AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
						AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
						AND sno_thconcepto.estcla = spg_cuentas.estcla
                    GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
					         sno_thsalida.anocur, sno_thsalida.codperi
                       UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                      FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					   AND sno_thsalida.valsal <> 0
					   AND sno_thconcepto.intprocon = '0'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_thunidadadmin.estcla
					   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					   AND sno_thsalida.codemp = sno_thconcepto.codemp 
					   AND sno_thsalida.codnom = sno_thconcepto.codnom 
					   AND sno_thsalida.anocur = sno_thconcepto.anocur
					   AND sno_thsalida.codperi = sno_thconcepto.codperi
					   AND sno_thsalida.codconc = sno_thconcepto.codconc 
					   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm
					   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_thconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					   AND sno_thunidadadmin.estcla = spg_cuentas.estcla
                   GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				            sno_thsalida.anocur, sno_thsalida.codperi
                       UNION 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'H' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						  sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                     FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto,
					      spg_cuentas, scg_cuentas
                     WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
					   AND sno_thsalida.valsal <> 0
					   AND sno_thconcepto.sigcon = 'E'
					   AND sno_thconcepto.intprocon = '1'
					   AND spg_cuentas.status = 'C'
					   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					   AND sno_thsalida.codemp = sno_thconcepto.codemp 
					   AND sno_thsalida.codnom = sno_thconcepto.codnom 
					   AND sno_thsalida.anocur = sno_thconcepto.anocur 
					   AND sno_thsalida.codperi = sno_thconcepto.codperi 
					   AND sno_thsalida.codconc = sno_thconcepto.codconc 
					   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
					   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur
					   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_thconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
					   AND sno_thconcepto.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				           sno_thsalida.anocur, sno_thsalida.codperi
                       UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'H' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,  
						 sno_thsalida.anocur, sno_thsalida.codperi
                    FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					     spg_cuentas, scg_cuentas
                   WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				     AND sno_thsalida.valsal <> 0 
					 AND sno_thconcepto.sigcon = 'E' 
					 AND sno_thconcepto.intprocon = '0'
					 AND spg_cuentas.status= 'C'
					 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
					 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					 AND sno_thsalida.codemp = sno_thconcepto.codemp 
					 AND sno_thsalida.codnom = sno_thconcepto.codnom 
					 AND sno_thsalida.anocur = sno_thconcepto.anocur 
					 AND sno_thsalida.codperi = sno_thconcepto.codperi 
					 AND sno_thsalida.codconc = sno_thconcepto.codconc 
					 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm
					 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_thconcepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					 AND sno_thunidadadmin.estcla = spg_cuentas.estcla
              GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
			         sno_thsalida.codperi
                   UNION 
             SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion,
			        'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom, 
					sno_thsalida.anocur, sno_thsalida.codperi
               FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas
              WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
			    AND sno_thsalida.valsal <> 0
				AND sno_thconcepto.intprocon= '0'
				AND sno_thconcepto.sigcon = 'B' 
				AND scg_cuentas.status = 'C'
				AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
				AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				AND sno_thpersonalnomina.codper = sno_thsalida.codper 
				AND sno_thsalida.codemp = sno_thconcepto.codemp
				AND sno_thsalida.codnom = sno_thconcepto.codnom 
				AND sno_thsalida.anocur = sno_thconcepto.anocur 
				AND sno_thsalida.codperi = sno_thconcepto.codperi
				AND sno_thsalida.codconc = sno_thconcepto.codconc 
				AND scg_cuentas.codemp = sno_thconcepto.codemp 
				AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon
            GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
			       sno_thsalida.anocur, sno_thsalida.codperi
                UNION 
            SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
			       'H' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
				   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
             FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas
            WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3') 
			  AND sno_thsalida.valsal <> 0
			  AND scg_cuentas.status = 'C'
			  AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
			  AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
			  AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
			  AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
			  AND sno_thpersonalnomina.codper = sno_thsalida.codper 
			  AND sno_thsalida.codemp = sno_thconcepto.codemp 
			  AND sno_thsalida.codnom = sno_thconcepto.codnom 
			  AND sno_thsalida.anocur = sno_thconcepto.anocur 
			  AND sno_thsalida.codperi = sno_thconcepto.codperi 
			  AND sno_thsalida.codconc = sno_thconcepto.codconc 
			  AND scg_cuentas.codemp = sno_thconcepto.codemp 
			  AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon
           GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi;";	
							 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_20");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_20
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_21()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 21
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_historico_int AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						  sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                     FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					  AND sno_thsalida.valsal <> 0
					  AND sno_thconcepto.intprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_thconcepto.estcla
					  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					  AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
					  AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					  AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					  AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					  AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					  AND sno_thsalida.codemp = sno_thconcepto.codemp 
					  AND sno_thsalida.codnom = sno_thconcepto.codnom 
					  AND sno_thsalida.anocur = sno_thconcepto.anocur 
					  AND sno_thsalida.codperi = sno_thconcepto.codperi 
					  AND sno_thsalida.codconc = sno_thconcepto.codconc 
					  AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
					  AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					  AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					  AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					  AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					  AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					  AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					  AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					  AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm
					  AND spg_cuentas.codemp = sno_thconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					  AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
                GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				         sno_thsalida.anocur, sno_thsalida.codperi
                    UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				        'D' AS operacion, sum(sno_thsalida.valsal) AS total, 
						sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                   FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
				        spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
				    AND sno_thsalida.valsal <> 0
					AND sno_thconcepto.intprocon = '0'
					AND spg_cuentas.status = 'C'
					AND spg_ep1.estint = 1 
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					AND spg_ep1.estcla = sno_thunidadadmin.estcla
					AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					AND sno_thpersonalnomina.codperi = sno_thsalida.codperi
					AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					AND sno_thsalida.codemp = sno_thconcepto.codemp 
					AND sno_thsalida.codnom = sno_thconcepto.codnom 
					AND sno_thsalida.anocur = sno_thconcepto.anocur 
					AND sno_thsalida.codperi = sno_thconcepto.codperi 
					AND sno_thsalida.codconc = sno_thconcepto.codconc 
					AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_thconcepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				      sno_thsalida.anocur, sno_thsalida.codperi
				UNION
				   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'H' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
						  sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                     FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto,
					      spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
					   AND sno_thsalida.valsal <> 0
					   AND sno_thconcepto.sigcon = 'E'
					   AND sno_thconcepto.intprocon = '1'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 1 
					   AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_thunidadadmin.estcla
					   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					   AND sno_thsalida.codemp = sno_thconcepto.codemp 
					   AND sno_thsalida.codnom = sno_thconcepto.codnom 
					   AND sno_thsalida.anocur = sno_thconcepto.anocur 
					   AND sno_thsalida.codperi = sno_thconcepto.codperi 
					   AND sno_thsalida.codconc = sno_thconcepto.codconc 
					   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
					   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur
					   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_thconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 					   
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5					   
                  GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				           sno_thsalida.anocur, sno_thsalida.codperi
                       UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'H' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,  
						 sno_thsalida.anocur, sno_thsalida.codperi
                    FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					     spg_cuentas, scg_cuentas,  spg_ep1
                   WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				     AND sno_thsalida.valsal <> 0 
					 AND sno_thconcepto.sigcon = 'E' 
					 AND sno_thconcepto.intprocon = '0'
					 AND spg_cuentas.status= 'C'
					 AND spg_ep1.estint = 1 
					 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_thunidadadmin.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
					 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					 AND sno_thsalida.codemp = sno_thconcepto.codemp 
					 AND sno_thsalida.codnom = sno_thconcepto.codnom 
					 AND sno_thsalida.anocur = sno_thconcepto.anocur 
					 AND sno_thsalida.codperi = sno_thconcepto.codperi 
					 AND sno_thsalida.codconc = sno_thconcepto.codconc 
					 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm
					 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_thconcepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon					 
					 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5					 
              GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
			         sno_thsalida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_21");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_21
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_vista_22()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 22
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_intercom AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						  sno_salida.codnom, sno_salida.codperi
                     FROM sno_personalnomina, sno_unidadadmin, sno_salida, 
					      sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
					  AND sno_salida.valsal <> 0
					  AND sno_concepto.intprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_concepto.estcla
					  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					  AND sno_personalnomina.codemp = sno_salida.codemp 
					  AND sno_personalnomina.codnom = sno_salida.codnom 
					  AND sno_personalnomina.codper = sno_salida.codper 
					  AND sno_salida.codemp = sno_concepto.codemp 
					  AND sno_salida.codnom = sno_concepto.codnom 
					  AND sno_salida.codconc = sno_concepto.codconc 
					  AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_concepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					  AND sno_concepto.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					     spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon = '0'
					 AND spg_cuentas.status= 'C' 
					 AND spg_ep1.estint = 1 
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla= sno_unidadadmin.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					 AND sno_unidadadmin.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     UNION 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion,
				          'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						   sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
					     spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon = '1'
					 AND sno_concepto.sigcon = 'E'
					 AND spg_cuentas.status = 'C'
					 AND spg_ep1.estint = 1
					 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_concepto.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					 AND sno_concepto.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
					     spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				    AND sno_salida.valsal <> 0
				   AND sno_concepto.intprocon = '0'
				   AND sno_concepto.sigcon = 'E'
				   AND spg_cuentas.status = 'C'
				   AND spg_ep1.estint = 1 
				   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				   AND spg_ep1.estcla = sno_unidadadmin.estcla
				   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				   AND sno_personalnomina.codemp = sno_salida.codemp 
				   AND sno_personalnomina.codnom = sno_salida.codnom 
				   AND sno_personalnomina.codper = sno_salida.codper 
				   AND sno_salida.codemp = sno_concepto.codemp 
				   AND sno_salida.codnom = sno_concepto.codnom 
				   AND sno_salida.codconc = sno_concepto.codconc 
				   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
				   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
				   AND spg_cuentas.codemp = sno_concepto.codemp 
				   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
				   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta
				   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				   AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				   AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				   AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				   AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				   AND sno_unidadadmin.estcla = spg_cuentas.estcla
             GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_22");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_22
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_23()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 23
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	   
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_salida.valsal) AS total, 
						  sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, 
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1')
					  AND sno_salida.valsal <> 0
					  AND sno_concepto.intprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND sno_concepto.conprocon = '0'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_concepto.estcla
					  AND sno_personalnomina.codemp = sno_salida.codemp 
					  AND sno_personalnomina.codnom = sno_salida.codnom 
					  AND sno_personalnomina.codper = sno_salida.codper 
					  AND sno_salida.codemp = sno_concepto.codemp 
					  AND sno_salida.codnom = sno_concepto.codnom 
					  AND sno_salida.codconc = sno_concepto.codconc 
					  AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					  AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm
					  AND spg_cuentas.codemp = sno_concepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					  AND sno_concepto.estcla = spg_cuentas.estcla
                 GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto,
					     spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon = '0'
					 AND spg_cuentas.status = 'C'
					 AND sno_concepto.conprocon= '0'
					 AND spg_ep1.estint = 0
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_unidadadmin.estcla
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
					 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					 AND sno_unidadadmin.estcla = spg_cuentas.estcla
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                         UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						 sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas
                   WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon= '0'
					 AND scg_cuentas.status = 'C'
					 AND sno_concepto.sigcon = 'B'
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND scg_cuentas.codemp = sno_concepto.codemp 
					 AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon
                  GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     UNION 
                  SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				         'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						  sno_salida.codnom, sno_salida.codperi
                    FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas
                    WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
					 AND sno_salida.valsal <> 0
					 AND scg_cuentas.status = 'C' 
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND scg_cuentas.codemp = sno_concepto.codemp 
					 AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon
                 GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				        'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
						sno_salida.codnom, sno_salida.codperi
                   FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				    AND sno_salida.valsal <> 0
					AND sno_concepto.intprocon= '1'
					AND sno_concepto.sigcon = 'E'
					AND spg_cuentas.status = 'C'
					AND spg_ep1.estint = 0
					AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					AND spg_ep1.estcla = sno_concepto.estcla
					AND sno_personalnomina.codemp = sno_salida.codemp 
					AND sno_personalnomina.codnom = sno_salida.codnom 
					AND sno_personalnomina.codper = sno_salida.codper 
					AND sno_salida.codemp = sno_concepto.codemp 
					AND sno_salida.codnom = sno_concepto.codnom 
					AND sno_salida.codconc = sno_concepto.codconc 
					AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_concepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					AND sno_concepto.estcla = spg_cuentas.estcla
               GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                    UNION 
                SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				       'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
					   sno_salida.codnom, sno_salida.codperi
                  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                 WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				   AND sno_salida.valsal <> 0
				   AND sno_concepto.intprocon = '0'
				   AND sno_concepto.sigcon = 'E'
				   AND spg_cuentas.status = 'C'
				   AND sno_concepto.conprocon = '0'
				   AND spg_ep1.estint = 0 
				   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				   AND spg_ep1.estcla = sno_unidadadmin.estcla
				   AND sno_personalnomina.codemp = sno_salida.codemp 
				   AND sno_personalnomina.codnom = sno_salida.codnom 
				   AND sno_personalnomina.codper = sno_salida.codper 
				   AND sno_salida.codemp = sno_concepto.codemp 
				   AND sno_salida.codnom = sno_concepto.codnom 
				   AND sno_salida.codconc = sno_concepto.codconc 
				   AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
				   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
				   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
				   AND spg_cuentas.codemp = sno_concepto.codemp 
				   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
				   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				   AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				   AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				   AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				   AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				   AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				   AND sno_unidadadmin.estcla = spg_cuentas.estcla
                GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_23");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_23
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_crear_vista_24()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 24
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    switch ($this->ls_gestor)
	    {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;			
	    }
	    $ls_sql= "  CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_dt AS 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_salida.valsal) AS total, 
						   $ls_cadena AS montoparcial, sno_proyectopersonal.codper, sno_proyectopersonal.codproy, 
						   max(scg_cuentas.denominacion) AS denominacion, max(sno_proyectopersonal.pordiames) AS pordiames,
						   sno_concepto.codconc, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1')
					   AND sno_salida.valsal <> 0
					   AND sno_concepto.conprocon = '1'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_proyecto.estcla
					   AND sno_proyectopersonal.codemp = sno_salida.codemp 
					   AND sno_proyectopersonal.codnom = sno_salida.codnom 
					   AND sno_proyectopersonal.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND sno_proyectopersonal.codemp = sno_proyecto.codemp 
					   AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
					   AND spg_cuentas.codemp = sno_concepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
					   AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
                    GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, sno_proyectopersonal.codproy,
					       scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                        UNION 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_salida.valsal) AS total,
					       $ls_cadena AS montoparcial, sno_proyectopersonal.codper, sno_proyectopersonal.codproy,
						   max(scg_cuentas.denominacion) AS denominacion, 
						   max(sno_proyectopersonal.pordiames) AS pordiames, sno_concepto.codconc, 
						   sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
					   AND sno_salida.valsal <> 0
					   AND sno_concepto.conprocon = '1'
					   AND sno_concepto.sigcon = 'E'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_proyecto.estcla
					   AND sno_proyectopersonal.codemp = sno_salida.codemp 
					   AND sno_proyectopersonal.codnom = sno_salida.codnom 
					   AND sno_proyectopersonal.codper = sno_salida.codper 
					   AND sno_salida.codemp = sno_concepto.codemp 
					   AND sno_salida.codnom = sno_concepto.codnom 
					   AND sno_salida.codconc = sno_concepto.codconc 
					   AND sno_proyectopersonal.codemp = sno_proyecto.codemp
					   AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
					   AND spg_cuentas.codemp = sno_concepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
					   AND sno_proyecto.estcla= spg_cuentas.estcla
                    GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, 
					         sno_proyectopersonal.codproy, scg_cuentas.sc_cuenta, 
							 sno_salida.codemp, sno_salida.codnom, sno_salida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_24");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_24
//------------------------------------------------------------------------------------------------------------------------------------	
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_25()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 25
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    switch ($this->ls_gestor)
	    {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;			
	    }
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_dt_historico AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_thsalida.valsal) AS total, 
				          $ls_cadena AS montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, 
				          max(scg_cuentas.denominacion) AS denominacion, max(sno_thproyectopersonal.pordiames) AS pordiames,
						  sno_thconcepto.codconc, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur,
						   sno_thsalida.codperi
                     FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto,
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					  AND sno_thsalida.valsal <> 0
					  AND sno_thconcepto.conprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_thproyecto.estcla
					  AND sno_thproyectopersonal.codemp = sno_thsalida.codemp 
					  AND sno_thproyectopersonal.codnom = sno_thsalida.codnom 
					  AND sno_thproyectopersonal.anocur = sno_thsalida.anocur 
					  AND sno_thproyectopersonal.codperi = sno_thsalida.codperi 
					  AND sno_thproyectopersonal.codper = sno_thsalida.codper 
					  AND sno_thsalida.codemp = sno_thconcepto.codemp
					  AND sno_thsalida.codnom = sno_thconcepto.codnom 
					  AND sno_thsalida.anocur = sno_thconcepto.anocur 
					  AND sno_thsalida.codperi = sno_thconcepto.codperi 
					  AND sno_thsalida.codconc = sno_thconcepto.codconc 
					  AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp 
					  AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom 
					  AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur 
					  AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi 
					  AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy 
					  AND spg_cuentas.codemp = sno_thconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_thproyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_thproyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_thproyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_thproyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
					  AND sno_thproyecto.estcla= spg_cuentas.estcla
               GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy, 
			            scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
						sno_thsalida.anocur, sno_thsalida.codperi
                     UNION 
               SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_thsalida.valsal) AS total,
			           $ls_cadena AS montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, 
					   max(scg_cuentas.denominacion) AS denominacion, max(sno_thproyectopersonal.pordiames) AS pordiames, 
					   sno_thconcepto.codconc, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                 FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto,
				      spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				  AND sno_thsalida.valsal <> 0
				  AND sno_thconcepto.sigcon= 'E'
				  AND sno_thconcepto.conprocon = '1'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 0 
				  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla = sno_thproyecto.estcla
				  AND sno_thproyectopersonal.codemp = sno_thsalida.codemp 
				  AND sno_thproyectopersonal.codnom = sno_thsalida.codnom 
				  AND sno_thproyectopersonal.anocur = sno_thsalida.anocur 
				  AND sno_thproyectopersonal.codperi = sno_thsalida.codperi
				  AND sno_thproyectopersonal.codper = sno_thsalida.codper 
				  AND sno_thsalida.codemp = sno_thconcepto.codemp 
				  AND sno_thsalida.codnom = sno_thconcepto.codnom 
				  AND sno_thsalida.anocur = sno_thconcepto.anocur 
				  AND sno_thsalida.codperi = sno_thconcepto.codperi 
				  AND sno_thsalida.codconc = sno_thconcepto.codconc 
				  AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp 
				  AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom 
				  AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur 
				  AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi 
				  AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy 
				  AND spg_cuentas.codemp = sno_thconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
				  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_thproyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_thproyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_thproyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_thproyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
				  AND sno_thproyecto.estcla = spg_cuentas.estcla
               GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy, 
			            scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
						sno_thsalida.anocur, sno_thsalida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_25");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_25
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_26()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 26
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    switch ($this->ls_gestor)
	    {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;			
	    }
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_dt_historico_int AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_thsalida.valsal) AS total, 
				          $ls_cadena AS montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, 
				          max(scg_cuentas.denominacion) AS denominacion, max(sno_thproyectopersonal.pordiames) AS pordiames,
						  sno_thconcepto.codconc, sno_thsalida.codemp, sno_thsalida.codnom, 
						  sno_thsalida.anocur, sno_thsalida.codperi
                     FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto,
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					  AND sno_thsalida.valsal <> 0
					  AND sno_thconcepto.conprocon = '1'
					  AND spg_cuentas.status= 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_thproyecto.estcla
					  AND spg_ep1.estcla = sno_thproyecto.estcla
					  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					  AND sno_thproyectopersonal.codemp = sno_thsalida.codemp 
					  AND sno_thproyectopersonal.codnom = sno_thsalida.codnom 
					  AND sno_thproyectopersonal.anocur = sno_thsalida.anocur
					  AND sno_thproyectopersonal.codperi = sno_thsalida.codperi 
					  AND sno_thproyectopersonal.codper = sno_thsalida.codper 
					  AND sno_thsalida.codemp = sno_thconcepto.codemp 
					  AND sno_thsalida.codnom = sno_thconcepto.codnom 
					  AND sno_thsalida.anocur = sno_thconcepto.anocur 
					  AND sno_thsalida.codperi = sno_thconcepto.codperi 
					  AND sno_thsalida.codconc = sno_thconcepto.codconc 
					  AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp 
					  AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom 
					  AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur 
					  AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi 
					  AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy 
					  AND spg_cuentas.codemp = sno_thconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_thproyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_thproyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_thproyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_thproyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
               GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy, 
			            scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
						sno_thsalida.anocur, sno_thsalida.codperi
                    UNION 
               SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_thsalida.valsal) AS total,
			          $ls_cadena AS montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy,
					  max(scg_cuentas.denominacion) AS denominacion, max(sno_thproyectopersonal.pordiames) AS pordiames, 
					  sno_thconcepto.codconc, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                 FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				  AND sno_thsalida.valsal <> 0
				  AND sno_thconcepto.sigcon = 'E'
				  AND sno_thconcepto.conprocon = '1'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 1 
				  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla = sno_thproyecto.estcla
				  AND spg_ep1.estcla = sno_thproyecto.estcla
				  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				  AND sno_thproyectopersonal.codemp = sno_thsalida.codemp 
				  AND sno_thproyectopersonal.codnom = sno_thsalida.codnom 
				  AND sno_thproyectopersonal.anocur = sno_thsalida.anocur
				  AND sno_thproyectopersonal.codperi = sno_thsalida.codperi 
				  AND sno_thproyectopersonal.codper = sno_thsalida.codper 
				  AND sno_thsalida.codemp = sno_thconcepto.codemp 
				  AND sno_thsalida.codnom = sno_thconcepto.codnom 
				  AND sno_thsalida.anocur = sno_thconcepto.anocur 
				  AND sno_thsalida.codperi = sno_thconcepto.codperi 
				  AND sno_thsalida.codconc = sno_thconcepto.codconc 
				  AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp 
				  AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom 
				  AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur 
				  AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi 
				  AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy 
				  AND spg_cuentas.codemp = sno_thconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
				  AND substr(sno_thproyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_thproyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_thproyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_thproyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_thproyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
          GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy,
		           scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur,
				   sno_thsalida.codperi ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_26");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_26
//------------------------------------------------------------------------------------------------------------------------------------
 //-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_27()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 27
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    switch ($this->ls_gestor)
	    {
	   		case "MYSQLT":
				$ls_cadena=" ROUND((SUM(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_salida.valsal))*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;			
	    }
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_dt_int AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_salida.valsal) AS total, 
				          $ls_cadena AS montoparcial, sno_proyectopersonal.codper, sno_proyectopersonal.codproy, 
				          max(scg_cuentas.denominacion) AS denominacion, max(sno_proyectopersonal.pordiames) AS pordiames, 
				          sno_concepto.codconc, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                     FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto,
					      spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
					  AND sno_salida.valsal <> 0 
					  AND sno_concepto.conprocon = '1'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla= sno_proyecto.estcla
					  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					  AND spg_cuentas.scgctaint= scg_cuentas.sc_cuenta 
					  AND sno_proyectopersonal.codemp = sno_salida.codemp 
					  AND sno_proyectopersonal.codnom = sno_salida.codnom
					  AND sno_proyectopersonal.codper = sno_salida.codper 
					  AND sno_salida.codemp = sno_concepto.codemp 
					  AND sno_salida.codnom = sno_concepto.codnom 
					  AND sno_salida.codconc = sno_concepto.codconc
					  AND sno_proyectopersonal.codemp = sno_proyecto.codemp 
					  AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
					  AND spg_cuentas.codemp = sno_concepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					  AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
					  AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
                 GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, sno_proyectopersonal.codproy, 
				          scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, 'H' AS operacion, sum(sno_salida.valsal) AS total, 
				        $ls_cadena AS montoparcial, sno_proyectopersonal.codper, sno_proyectopersonal.codproy, 
						max(scg_cuentas.denominacion) AS denominacion, max(sno_proyectopersonal.pordiames) AS pordiames, 
						sno_concepto.codconc, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                   FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				    AND sno_salida.valsal <> 0
					AND sno_concepto.conprocon = '1'
					AND sno_concepto.sigcon = 'E' 
					AND spg_cuentas.status = 'C'
					AND spg_ep1.estint = 1 
					AND substr(sno_proyecto.estproproy, 1, 25) = spg_ep1.codestpro1
					AND spg_ep1.estcla = sno_proyecto.estcla 
					AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					AND sno_proyectopersonal.codemp = sno_salida.codemp 
					AND sno_proyectopersonal.codnom = sno_salida.codnom 
					AND sno_proyectopersonal.codper = sno_salida.codper 
					AND sno_salida.codemp = sno_concepto.codemp 
					AND sno_salida.codnom = sno_concepto.codnom 
					AND sno_salida.codconc = sno_concepto.codconc 
					AND sno_proyectopersonal.codemp = sno_proyecto.codemp 
					AND sno_proyectopersonal.codproy = sno_proyecto.codproy 
					AND spg_cuentas.codemp = sno_concepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					AND substr(sno_proyecto.estproproy, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_proyecto.estproproy, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_proyecto.estproproy, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_proyecto.estproproy, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_proyecto.estproproy, 101, 25) = spg_cuentas.codestpro5
					AND sno_proyecto.estcla = spg_cuentas.estcla
               GROUP BY sno_proyectopersonal.codper, sno_concepto.codconc, sno_proyectopersonal.codproy, 
			            scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_27");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_27
//-----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_28()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 28
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_historico AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp,
						   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                    FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
				     AND sno_thsalida.valsal <> 0
					 AND sno_thconcepto.intprocon= '1'
					 AND sno_thconcepto.conprocon= '0'
					 AND spg_cuentas.status = 'C'
					 AND spg_ep1.estint = 0 
					 AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla= sno_thconcepto.estcla
					 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					 AND sno_thsalida.codemp = sno_thconcepto.codemp 
					 AND sno_thsalida.codnom = sno_thconcepto.codnom 
					 AND sno_thsalida.anocur = sno_thconcepto.anocur 
					 AND sno_thsalida.codperi = sno_thconcepto.codperi 
					 AND sno_thsalida.codconc = sno_thconcepto.codconc 
					 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_thconcepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon
					 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					 AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
					 AND sno_thconcepto.estcla = spg_cuentas.estcla
                GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
				         sno_thsalida.anocur, sno_thsalida.codperi
                     UNION 
                SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion,
				       'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, 
					   sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
				    AND sno_thsalida.valsal <> 0
					AND sno_thconcepto.intprocon = '0'
					AND sno_thconcepto.conprocon = '0'
					AND spg_cuentas.status= 'C'
					AND spg_ep1.estint = 0 
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				    AND spg_ep1.estcla = sno_thunidadadmin.estcla
					AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					AND sno_thsalida.codemp = sno_thconcepto.codemp 
					AND sno_thsalida.codnom = sno_thconcepto.codnom 
					AND sno_thsalida.anocur = sno_thconcepto.anocur 
					AND sno_thsalida.codperi = sno_thconcepto.codperi 
					AND sno_thsalida.codconc = sno_thconcepto.codconc 
					AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm
					AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_thconcepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					AND sno_thunidadadmin.estcla = spg_cuentas.estcla
               GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom,
			            sno_thsalida.anocur, sno_thsalida.codperi 
                    UNION 
               SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion,
			          sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,
					  sno_thsalida.anocur, sno_thsalida.codperi
                 FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				  AND sno_thsalida.valsal <> 0
				  AND sno_thconcepto.sigcon = 'E'
				  AND sno_thconcepto.intprocon = '1'
				  AND sno_thconcepto.conprocon = '0'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 0 
				  AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla= sno_thconcepto.estcla
				  AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
				  AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				  AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				  AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				  AND sno_thpersonalnomina.codper = sno_thsalida.codper
				  AND sno_thsalida.codemp = sno_thconcepto.codemp 
				  AND sno_thsalida.codnom = sno_thconcepto.codnom 
				  AND sno_thsalida.anocur = sno_thconcepto.anocur 
				  AND sno_thsalida.codperi = sno_thconcepto.codperi 
				  AND sno_thsalida.codconc = sno_thconcepto.codconc 
				  AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
				  AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
				  AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
				  AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
				  AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
				  AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
				  AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
				  AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
				  AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
				  AND spg_cuentas.codemp = sno_thconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
				  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				  AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
				  AND sno_thconcepto.estcla = spg_cuentas.estcla
               GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
			            sno_thsalida.codperi			   
                   UNION 
              SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion, 
			         sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,
					 sno_thsalida.anocur, sno_thsalida.codperi
                FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
               WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2')
			     AND sno_thsalida.valsal <> 0
				 AND sno_thconcepto.sigcon = 'E'
				 AND sno_thconcepto.intprocon= '0'
				 AND sno_thconcepto.conprocon = '0'
				 AND spg_cuentas.status= 'C'
				 AND spg_ep1.estint = 0 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				 AND spg_ep1.estcla = sno_thunidadadmin.estcla
				 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
				 AND sno_thsalida.codemp = sno_thconcepto.codemp 
				 AND sno_thsalida.codnom = sno_thconcepto.codnom 
				 AND sno_thsalida.anocur = sno_thconcepto.anocur 
				 AND sno_thsalida.codperi = sno_thconcepto.codperi 
				 AND sno_thsalida.codconc = sno_thconcepto.codconc 
				 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
				 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
				 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
				 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
				 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
				 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
				 AND spg_cuentas.codemp = sno_thconcepto.codemp 
				 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
				 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				 AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				 AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				 AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				 AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				 AND sno_thunidadadmin.estcla = spg_cuentas.estcla
              GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                 UNION 
               SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'D' AS operacion, 
			          sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
					  sno_thsalida.codperi
                FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas
               WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
			     AND sno_thsalida.valsal <> 0
				 AND sno_thconcepto.sigcon = 'B'
				 AND scg_cuentas.status = 'C'
				 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
				 AND sno_thsalida.codemp = sno_thconcepto.codemp 
				 AND sno_thsalida.codnom = sno_thconcepto.codnom 
				 AND sno_thsalida.anocur = sno_thconcepto.anocur 
				 AND sno_thsalida.codperi = sno_thconcepto.codperi 
				 AND sno_thsalida.codconc = sno_thconcepto.codconc 
				 AND scg_cuentas.codemp = sno_thconcepto.codemp 
				 AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon
          GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi
                UNION 
          SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion, 
		         sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur,
				 sno_thsalida.codperi
           FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas
          WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3') 
		    AND sno_thsalida.valsal <> 0
			AND scg_cuentas.status = 'C'
			AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
			AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
			AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
			AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
			AND sno_thpersonalnomina.codper = sno_thsalida.codper 
			AND sno_thsalida.codemp = sno_thconcepto.codemp 
			AND sno_thsalida.codnom = sno_thconcepto.codnom 
			AND sno_thsalida.anocur = sno_thconcepto.anocur 
			AND sno_thsalida.codperi = sno_thconcepto.codperi 
			AND sno_thsalida.codconc = sno_thconcepto.codconc 
			AND scg_cuentas.codemp = sno_thconcepto.codemp 
			AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon
       GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_28");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_28
//--------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_29()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 29
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_historico_int AS 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
					      'D' AS operacion, sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,
					       sno_thsalida.anocur, sno_thsalida.codperi
                      FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, 
					       spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') 
					   AND sno_thsalida.valsal <> 0
					   AND sno_thconcepto.intprocon= '1'
					   AND sno_thconcepto.conprocon = '0'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 1 
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_thconcepto.estcla
					   AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					   AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
					   AND sno_thpersonalnomina.codper = sno_thsalida.codper 
					   AND sno_thsalida.codemp = sno_thconcepto.codemp 
					   AND sno_thsalida.codnom = sno_thconcepto.codnom 
					   AND sno_thsalida.anocur = sno_thconcepto.anocur 
					   AND sno_thsalida.codperi = sno_thconcepto.codperi 
					   AND sno_thsalida.codconc = sno_thconcepto.codconc 
					   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
					   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
					   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
					   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
					   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
					   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
					   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
					   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
					   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_thconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
					   AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
              GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
			           sno_thsalida.anocur, sno_thsalida.codperi
                 UNION 
             SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'D' AS operacion, 
			        sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
					sno_thsalida.codperi
               FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
               WHERE (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1')
			     AND sno_thsalida.valsal <> 0
				 AND sno_thconcepto.intprocon = '0'
				 AND sno_thconcepto.conprocon = '0'
				 AND spg_cuentas.status = 'C'
				 AND spg_ep1.estint = 1 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				 AND spg_ep1.estcla = sno_thunidadadmin.estcla
				 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
				 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
				 AND sno_thsalida.codemp = sno_thconcepto.codemp 
				 AND sno_thsalida.codnom = sno_thconcepto.codnom 
				 AND sno_thsalida.anocur = sno_thconcepto.anocur 
				 AND sno_thsalida.codperi = sno_thconcepto.codperi 
				 AND sno_thsalida.codconc = sno_thconcepto.codconc 
				 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp
				 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur
				 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
				 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
				 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
				 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
				 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
				 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
				 AND spg_cuentas.codemp = sno_thconcepto.codemp 
				 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				 AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				 AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				 AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				 AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
           GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, 
		            sno_thsalida.anocur, sno_thsalida.codperi
			   UNION
		    SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion,
			          sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,
					  sno_thsalida.anocur, sno_thsalida.codperi
                 FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') 
				  AND sno_thsalida.valsal <> 0
				  AND sno_thconcepto.sigcon = 'E'
				  AND sno_thconcepto.intprocon = '1'
				  AND sno_thconcepto.conprocon = '0'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 1 
				  AND substr(sno_thconcepto.codpro, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla= sno_thconcepto.estcla
				  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
				  AND sno_thpersonalnomina.codemp = sno_thsalida.codemp
				  AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				  AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				  AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				  AND sno_thpersonalnomina.codper = sno_thsalida.codper
				  AND sno_thsalida.codemp = sno_thconcepto.codemp 
				  AND sno_thsalida.codnom = sno_thconcepto.codnom 
				  AND sno_thsalida.anocur = sno_thconcepto.anocur 
				  AND sno_thsalida.codperi = sno_thconcepto.codperi 
				  AND sno_thsalida.codconc = sno_thconcepto.codconc 
				  AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
				  AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
				  AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
				  AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
				  AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
				  AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
				  AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
				  AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
				  AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
				  AND spg_cuentas.codemp = sno_thconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 				 
				  AND substr(sno_thconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_thconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_thconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_thconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_thconcepto.codpro, 101, 25) = spg_cuentas.codestpro5				 
               GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, 
			            sno_thsalida.codperi			   
                   UNION 
              SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion, 
			         sum(sno_thsalida.valsal) AS total, sno_thsalida.codemp, sno_thsalida.codnom,
					 sno_thsalida.anocur, sno_thsalida.codperi
                FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas, spg_ep1
               WHERE (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2')
			     AND sno_thsalida.valsal <> 0
				 AND sno_thconcepto.sigcon = 'E'
				 AND sno_thconcepto.intprocon= '0'
				 AND sno_thconcepto.conprocon = '0'
				 AND spg_cuentas.status= 'C'
				 AND spg_ep1.estint = 1 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				 AND spg_ep1.estcla = sno_thunidadadmin.estcla
				 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
				 AND sno_thpersonalnomina.codemp = sno_thsalida.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thsalida.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thsalida.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thsalida.codperi 
				 AND sno_thpersonalnomina.codper = sno_thsalida.codper 
				 AND sno_thsalida.codemp = sno_thconcepto.codemp 
				 AND sno_thsalida.codnom = sno_thconcepto.codnom 
				 AND sno_thsalida.anocur = sno_thconcepto.anocur 
				 AND sno_thsalida.codperi = sno_thconcepto.codperi 
				 AND sno_thsalida.codconc = sno_thconcepto.codconc 
				 AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp 
				 AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom 
				 AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur 
				 AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi 
				 AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm 
				 AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm 
				 AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm 
				 AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm 
				 AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm 
				 AND spg_cuentas.codemp = sno_thconcepto.codemp 
				 AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon 				 
				 AND substr(sno_thunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				 AND substr(sno_thunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				 AND substr(sno_thunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				 AND substr(sno_thunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				 AND substr(sno_thunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5				
          GROUP BY scg_cuentas.sc_cuenta, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.anocur, sno_thsalida.codperi; ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_29");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_29
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_30()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 30
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW contableconceptos_contable_proyecto_intercom AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				          'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, sno_salida.codnom,
						   sno_salida.codperi
                    FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
				     AND sno_salida.valsal <> 0
					 AND sno_concepto.intprocon = '1'
					 AND spg_cuentas.status = 'C'
					 AND sno_concepto.conprocon = '0'
					 AND spg_ep1.estint = 1
					 AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_concepto.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND sno_personalnomina.codemp = sno_salida.codemp 
					 AND sno_personalnomina.codnom = sno_salida.codnom 
					 AND sno_personalnomina.codper = sno_salida.codper 
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_concepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					 AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
                 GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				       'D' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp, 
					   sno_salida.codnom, sno_salida.codperi
                   FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') 
				    AND sno_salida.valsal <> 0
					AND sno_concepto.intprocon = '0'
					AND spg_cuentas.status = 'C'
					AND sno_concepto.conprocon = '0'
					AND spg_ep1.estint = 1 
					AND spg_ep1.codestpro1 = substr(sno_unidadadmin.codprouniadm, 1, 25) 
					AND spg_ep1.estcla= sno_unidadadmin.estcla
					AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					AND sno_personalnomina.codemp = sno_salida.codemp 
					AND sno_personalnomina.codnom = sno_salida.codnom 
					AND sno_personalnomina.codper = sno_salida.codper 
					AND sno_salida.codemp = sno_concepto.codemp 
					AND sno_salida.codnom = sno_concepto.codnom 
					AND sno_salida.codconc = sno_concepto.codconc 
					AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
					AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_concepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
                 GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                      UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 
				        'H' AS operacion, sum(sno_salida.valsal) AS total, sno_salida.codemp,
						 sno_salida.codnom, sno_salida.codperi
                   FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
                  WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
				    AND sno_salida.valsal <> 0
					AND sno_concepto.intprocon = '1'
					AND sno_concepto.sigcon = 'E'
					AND spg_cuentas.status= 'C'
					AND spg_ep1.estint = 1 
					AND substr(sno_concepto.codpro, 1, 25) = spg_ep1.codestpro1
					AND spg_ep1.estcla = sno_concepto.estcla
					AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					AND sno_personalnomina.codemp = sno_salida.codemp
					AND sno_personalnomina.codnom = sno_salida.codnom 
					AND sno_personalnomina.codper = sno_salida.codper 
					AND sno_salida.codemp = sno_concepto.codemp 
					AND sno_salida.codnom = sno_concepto.codnom 
					AND sno_salida.codconc = sno_concepto.codconc 
					AND sno_personalnomina.codemp = sno_unidadadmin.codemp
					AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm
					AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
					AND spg_cuentas.codemp = sno_concepto.codemp 
					AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
					AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta
					AND substr(sno_concepto.codpro, 1, 25) = spg_cuentas.codestpro1
					AND substr(sno_concepto.codpro, 26, 25) = spg_cuentas.codestpro2
					AND substr(sno_concepto.codpro, 51, 25) = spg_cuentas.codestpro3
					AND substr(sno_concepto.codpro, 76, 25) = spg_cuentas.codestpro4
					AND substr(sno_concepto.codpro, 101, 25) = spg_cuentas.codestpro5
					AND sno_concepto.estcla = spg_cuentas.estcla
              GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
                    UNION 
              SELECT scg_cuentas.sc_cuenta AS cuenta, max(scg_cuentas.denominacion) AS denominacion, 'H' AS operacion, 
			         sum(sno_salida.valsal) AS total, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi
               FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, scg_cuentas, spg_ep1
               WHERE (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3') 
			     AND sno_salida.valsal <> 0
				 AND sno_concepto.intprocon = '0'
				 AND sno_concepto.sigcon = 'E' 
				 AND spg_cuentas.status = 'C'
				 AND sno_concepto.conprocon = '0'
				 AND spg_ep1.estint = 1 
				 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				 AND spg_ep1.estcla = sno_unidadadmin.estcla
				 AND spg_ep1.estcla= sno_unidadadmin.estcla
				 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				 AND sno_personalnomina.codemp = sno_salida.codemp 
				 AND sno_personalnomina.codnom = sno_salida.codnom 
				 AND sno_personalnomina.codper = sno_salida.codper 
				 AND sno_salida.codemp = sno_concepto.codemp 
				 AND sno_salida.codnom = sno_concepto.codnom 
				 AND sno_salida.codconc = sno_concepto.codconc 
				 AND sno_personalnomina.codemp = sno_unidadadmin.codemp 
				 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
				 AND spg_cuentas.codemp = sno_concepto.codemp 
				 AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon
				 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				 AND substr(sno_unidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				 AND substr(sno_unidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				 AND substr(sno_unidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				 AND substr(sno_unidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				 AND substr(sno_unidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				 AND sno_unidadadmin.estcla = spg_cuentas.estcla
            GROUP BY scg_cuentas.sc_cuenta, sno_salida.codemp, sno_salida.codnom, sno_salida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_30");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_30
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_31()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 31
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW load_aportes_scg_normales_ajuste_proyecto AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
				          sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, 
						  sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                     FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
					  AND sno_hconcepto.intprocon = '1'
					  AND sno_hconcepto.conprocon = '0'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 0 
					  AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_hconcepto.estcla
					  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					  AND sno_hsalida.codemp = sno_hconcepto.codemp 
					  AND sno_hsalida.codnom = sno_hconcepto.codnom 
					  AND sno_hsalida.anocur = sno_hconcepto.anocur 
					  AND sno_hsalida.codperi = sno_hconcepto.codperi 
					  AND sno_hsalida.codconc = sno_hconcepto.codconc 
					  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_hconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
					  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					  AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
					  AND sno_hconcepto.estcla = spg_cuentas.estcla
               GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
			            sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                   UNION 
               SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
			          sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp, 
					  sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                 FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
				  AND sno_hconcepto.intprocon = '0'
				  AND sno_hconcepto.conprocon = '0'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 0 
				  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla = sno_hunidadadmin.estcla
				  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
				  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
				  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
				  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
				  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
				  AND sno_hsalida.codemp = sno_hconcepto.codemp 
				  AND sno_hsalida.codnom = sno_hconcepto.codnom 
				  AND sno_hsalida.anocur = sno_hconcepto.anocur 
				  AND sno_hsalida.codperi = sno_hconcepto.codperi 
				  AND sno_hsalida.codconc = sno_hconcepto.codconc 
				  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp
				  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
				  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
				  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
				  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
				  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
				  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
				  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
				  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
				  AND spg_cuentas.codemp = sno_hconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
				  AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
				  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				  AND sno_hunidadadmin.estcla = spg_cuentas.estcla
             GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
			        sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_31");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_31
//------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_32()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 32
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW load_aportes_scg_normales_ajuste_proyecto_int AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
				          sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, sno_hsalida.codemp,
				          sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                    FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
				     AND sno_hconcepto.intprocon = '1'
					 AND sno_hconcepto.conprocon = '0'
					 AND spg_cuentas.status = 'C'
					 AND spg_ep1.estint = 1 
					 AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_hconcepto.estcla
					 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					 AND sno_hsalida.codemp = sno_hconcepto.codemp 
					 AND sno_hsalida.codnom = sno_hconcepto.codnom 
					 AND sno_hsalida.anocur = sno_hconcepto.anocur 
					 AND sno_hsalida.codperi = sno_hconcepto.codperi 
					 AND sno_hsalida.codconc = sno_hconcepto.codconc 
					 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_hconcepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
					 AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
             GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
			          sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, 
					  sno_hsalida.anocur, sno_hsalida.codperi
                   UNION 
             SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(abs(sno_hsalida.valsal)) AS total,
			        sno_hconcepto.codprov, sno_hconcepto.cedben, sno_hconcepto.codconc, 
					sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
               FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
               WHERE (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4') 
			     AND sno_hconcepto.intprocon = '0'
				 AND sno_hconcepto.conprocon = '0'
				 AND spg_cuentas.status = 'C'
				 AND spg_ep1.estint = 1 
				 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				 AND spg_ep1.estcla = sno_hunidadadmin.estcla
				 AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				 AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta
				 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
				 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
				 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur
				 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
				 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
				 AND sno_hsalida.codemp = sno_hconcepto.codemp 
				 AND sno_hsalida.codnom = sno_hconcepto.codnom 
				 AND sno_hsalida.anocur = sno_hconcepto.anocur 
				 AND sno_hsalida.codperi = sno_hconcepto.codperi 
				 AND sno_hsalida.codconc = sno_hconcepto.codconc 
				 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
				 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
				 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
				 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
				 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
				 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
				 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm
				 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
				 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
				 AND spg_cuentas.codemp = sno_hconcepto.codemp 
				 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprepatcon
				 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				 AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				 AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				 AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				 AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
           GROUP BY sno_hconcepto.codconc, scg_cuentas.sc_cuenta, sno_hconcepto.codprov, 
		            sno_hconcepto.cedben, sno_hsalida.codemp, sno_hsalida.codnom, 
					sno_hsalida.anocur, sno_hsalida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_32");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_32
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_33()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 33
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= "  CREATE OR REPLACE VIEW load_conceptos_scg_normales_ajuste_proyecto AS 
                    SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total,
					       sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                      FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                     WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
					   AND sno_hsalida.valsal <> 0
					   AND sno_hconcepto.intprocon = '1'
					   AND sno_hconcepto.conprocon= '0'
					   AND spg_cuentas.status = 'C'
					   AND spg_ep1.estint = 0 
					   AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					   AND spg_ep1.estcla = sno_hconcepto.estcla
					   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi
					   AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					   AND sno_hsalida.codemp = sno_hconcepto.codemp 
					   AND sno_hsalida.codnom = sno_hconcepto.codnom 
					   AND sno_hsalida.anocur = sno_hconcepto.anocur 
					   AND sno_hsalida.codperi = sno_hconcepto.codperi 
					   AND sno_hsalida.codconc = sno_hconcepto.codconc
					   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					   AND spg_cuentas.codemp = sno_hconcepto.codemp 
					   AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
					   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					   AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					   AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					   AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					   AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					   AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5
					   AND sno_hconcepto.estcla = spg_cuentas.estcla
                 GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                        UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total,
				        sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                   FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                   WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
				     AND sno_hsalida.valsal <> 0
					 AND sno_hconcepto.intprocon = '0'
					 AND sno_hconcepto.conprocon = '0'
					 AND spg_cuentas.status = 'C'
					 AND spg_ep1.estint = 0 
					 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
					 AND spg_ep1.estcla = sno_hunidadadmin.estcla
					 AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
					 AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					 AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					 AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					 AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					 AND sno_hsalida.codemp = sno_hconcepto.codemp 
					 AND sno_hsalida.codnom = sno_hconcepto.codnom 
					 AND sno_hsalida.anocur = sno_hconcepto.anocur 
					 AND sno_hsalida.codperi = sno_hconcepto.codperi
					 AND sno_hsalida.codconc = sno_hconcepto.codconc 
					 AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					 AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom
					 AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					 AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					 AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					 AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					 AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					 AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
					 AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					 AND spg_cuentas.codemp = sno_hconcepto.codemp 
					 AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
					 AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta 
					 AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
					 AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
					 AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
					 AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
					 AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
					 AND sno_hunidadadmin.estcla = spg_cuentas.estcla
               GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
			             sno_hsalida.anocur, sno_hsalida.codperi;";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_33");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_33
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_crear_vista_34()
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 34
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    
	    $ls_sql= " CREATE OR REPLACE VIEW load_conceptos_scg_normales_ajuste_proyecto_int AS 
                   SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total, 
				          sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                     FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, spg_cuentas, scg_cuentas, spg_ep1
                    WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
					  AND sno_hsalida.valsal <> 0
					  AND sno_hconcepto.intprocon = '1'
					  AND sno_hconcepto.conprocon= '0'
					  AND spg_cuentas.status = 'C'
					  AND spg_ep1.estint = 1 
					  AND substr(sno_hconcepto.codpro, 1, 25) = spg_ep1.codestpro1
					  AND spg_ep1.estcla = sno_hconcepto.estcla
					  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
					  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
					  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp
					  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
					  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
					  AND sno_hsalida.codemp = sno_hconcepto.codemp 
					  AND sno_hsalida.codnom = sno_hconcepto.codnom 
					  AND sno_hsalida.anocur = sno_hconcepto.anocur 
					  AND sno_hsalida.codperi = sno_hconcepto.codperi 
					  AND sno_hsalida.codconc = sno_hconcepto.codconc 
					  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp 
					  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
					  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
					  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
					  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
					  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
					  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
					  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm
					  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
					  AND spg_cuentas.codemp = sno_hconcepto.codemp 
					  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
					  AND substr(sno_hconcepto.codpro, 1, 25) = spg_cuentas.codestpro1
					  AND substr(sno_hconcepto.codpro, 26, 25) = spg_cuentas.codestpro2
					  AND substr(sno_hconcepto.codpro, 51, 25) = spg_cuentas.codestpro3
					  AND substr(sno_hconcepto.codpro, 76, 25) = spg_cuentas.codestpro4
					  AND substr(sno_hconcepto.codpro, 101, 25) = spg_cuentas.codestpro5					  
                 GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
				       sno_hsalida.anocur, sno_hsalida.codperi
                      UNION 
                 SELECT scg_cuentas.sc_cuenta AS cuenta, 'D' AS operacion, sum(sno_hsalida.valsal) AS total, 
				        sno_hsalida.codemp, sno_hsalida.codnom, sno_hsalida.anocur, sno_hsalida.codperi
                  FROM sno_hpersonalnomina, sno_hunidadadmin, sno_hsalida, sno_hconcepto, 
				       spg_cuentas, scg_cuentas, spg_ep1
                WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') 
				  AND sno_hsalida.valsal <> 0
				  AND sno_hconcepto.intprocon = '0'
				  AND sno_hconcepto.conprocon = '0'
				  AND spg_cuentas.status = 'C'
				  AND spg_ep1.estint = 1 
				  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_ep1.codestpro1
				  AND spg_ep1.estcla = sno_hunidadadmin.estcla
				  AND spg_ep1.sc_cuenta = spg_cuentas.scgctaint
				  AND spg_cuentas.scgctaint = scg_cuentas.sc_cuenta 
				  AND sno_hpersonalnomina.codemp = sno_hsalida.codemp 
				  AND sno_hpersonalnomina.codnom = sno_hsalida.codnom 
				  AND sno_hpersonalnomina.anocur = sno_hsalida.anocur 
				  AND sno_hpersonalnomina.codperi = sno_hsalida.codperi 
				  AND sno_hpersonalnomina.codper = sno_hsalida.codper 
				  AND sno_hsalida.codemp = sno_hconcepto.codemp 
				  AND sno_hsalida.codnom = sno_hconcepto.codnom 
				  AND sno_hsalida.anocur = sno_hconcepto.anocur 
				  AND sno_hsalida.codperi = sno_hconcepto.codperi 
				  AND sno_hsalida.codconc = sno_hconcepto.codconc 
				  AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp
				  AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom 
				  AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur 
				  AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi 
				  AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm 
				  AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm 
				  AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm 
				  AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm 
				  AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm 
				  AND spg_cuentas.codemp = sno_hconcepto.codemp 
				  AND spg_cuentas.spg_cuenta = sno_hconcepto.cueprecon
				  AND substr(sno_hunidadadmin.codprouniadm, 1, 25) = spg_cuentas.codestpro1
				  AND substr(sno_hunidadadmin.codprouniadm, 26, 25) = spg_cuentas.codestpro2
				  AND substr(sno_hunidadadmin.codprouniadm, 51, 25) = spg_cuentas.codestpro3
				  AND substr(sno_hunidadadmin.codprouniadm, 76, 25) = spg_cuentas.codestpro4
				  AND substr(sno_hunidadadmin.codprouniadm, 101, 25) = spg_cuentas.codestpro5
				GROUP BY scg_cuentas.sc_cuenta, sno_hsalida.codemp, sno_hsalida.codnom, 
				sno_hsalida.anocur, sno_hsalida.codperi;  ";	
								 			        
	   if (!empty($ls_sql))
		{	
		 	$li_row=$this->io_sql->execute($ls_sql);
		 	if($li_row===false)
		 	{ 
			 	$this->io_msg->message("Problemas al ejecutar uf_crear_vista_34");
			 	$lb_valido=false;
		 	}
		} 
	   return $lb_valido;
	}// fin de uf_crear_vista_34
//-----------------------------------------------------------------------------------------------------------------------------------

    function uf_crear_vista_35()
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 35
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_sql    = "CREATE  VIEW calculo_conceptospersonal AS 
					 SELECT sno_concepto.codemp, sno_concepto.codnom, sno_conceptopersonal.codper, sno_concepto.codconc, sno_concepto.nomcon, 
						  sno_concepto.titcon, sno_concepto.sigcon, sno_concepto.forcon, sno_concepto.glocon, sno_concepto.acumaxcon, sno_concepto.valmincon, 
						  sno_concepto.valmaxcon, sno_concepto.concon, sno_concepto.cueprecon, sno_concepto.cueconcon, sno_concepto.aplisrcon, 
						  sno_concepto.sueintcon, sno_concepto.intprocon, sno_concepto.codpro, sno_concepto.forpatcon, sno_concepto.cueprepatcon, 
						  sno_concepto.cueconpatcon, sno_concepto.titretempcon, sno_concepto.titretpatcon, sno_concepto.valminpatcon, sno_concepto.valmaxpatcon, 
						  sno_concepto.codprov, sno_concepto.cedben, sno_concepto.conprenom, sno_concepto.sueintvaccon, sno_concepto.aplarccon, 
						  sno_conceptopersonal.aplcon, sno_conceptopersonal.valcon, sno_conceptopersonal.acuemp, sno_conceptopersonal.acuiniemp, 
						  sno_conceptopersonal.acupat, sno_conceptopersonal.acuinipat, sno_concepto.quirepcon, sno_concepto.persalnor
					   FROM sno_conceptopersonal, sno_concepto
					  WHERE (sno_conceptopersonal.aplcon = 1 OR sno_concepto.glocon = 1) 
						AND sno_conceptopersonal.codemp = sno_concepto.codemp 
						AND sno_conceptopersonal.codnom = sno_concepto.codnom 
						AND sno_conceptopersonal.codconc = sno_concepto.codconc;";	
	  $rs_data   = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 { 
		   $this->io_msg->message("Problemas al ejecutar uf_crear_vista_35");
		   $lb_valido=false;
		 }
	  return $lb_valido;
	}// fin de uf_crear_vista_35

    function uf_crear_vista_36()
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 36
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_sql    = "CREATE  VIEW calculo_personal AS 
					SELECT sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personalnomina.codper, sno_personalnomina.sueper, sno_personalnomina.sueproper, sno_personalnomina.horper, 
						 sno_personalnomina.staper, sno_personalnomina.fecculcontr, sno_personal.nivacaper, 
						 sno_personal.fecingper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.sexper, 
						 sno_personal.numhijper, sno_personal.anoservpreper, sno_personal.fecnacper, sno_personal.fecingadmpubper, 
						 sno_personalnomina.codtabvac, sno_personal.cajahoper, sno_personal.porcajahoper,  sno_personalpension.suebasper, sno_personalpension.priespper, 
						 sno_personalpension.pritraper, sno_personalpension.priproper, sno_personalpension.prianoserper, sno_personalpension.pridesper, 
						 sno_personalpension.porpenper, sno_personalpension.prinoascper, sno_personalpension.monpenper, sno_personalpension.subtotper, 
						 sno_personalnomina.codded, sno_personalnomina.codtipper, sno_personalnomina.codcladoc, sno_personalnomina.codescdoc, sno_personalnomina.fecingper as fecingnom, 				
						(SELECT sno_fideicomiso.capantcom  
						   FROM sno_fideicomiso 
						  WHERE sno_fideicomiso.codemp = sno_personal.codemp 
							AND sno_fideicomiso.codper = sno_personal.codper) AS capantcom,   				
						 (SELECT sno_personalpension.tipjub  
							FROM sno_personalpension 
						   WHERE sno_personalpension.codemp = sno_personalnomina.codemp 
							 AND sno_personalpension.codper = sno_personalnomina.codper 
							 AND sno_personalpension.codnom = sno_personalnomina.codnom) AS tipjub,  				
						 (SELECT sno_clasificacionobrero.suemin  
							FROM sno_clasificacionobrero 
						   WHERE sno_clasificacionobrero.codemp = sno_personalnomina.codemp 
							 AND sno_clasificacionobrero.grado = sno_personalnomina.grado) AS suemingra  
							FROM sno_personalnomina 
							LEFT JOIN sno_personalpension 
							  ON sno_personalnomina.codemp = sno_personalpension.codemp 
							 AND sno_personalnomina.codnom = sno_personalpension.codnom 
							 AND sno_personalnomina.codper = sno_personalpension.codper,  
							   sno_personal 
					  WHERE sno_personalnomina.codemp=sno_personal.codemp 
						AND sno_personalnomina.codper=sno_personal.codper;";	
	  $rs_data   = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 { 
		   $this->io_msg->message("Problemas al ejecutar uf_crear_vista_36");
		   $lb_valido=false;
		 }
	  return $lb_valido;
	}// fin de uf_crear_vista_36

    function uf_crear_vista_37()
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Creación de la vista numero 37
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_sql    = "CREATE  VIEW calculo_personaltabulador AS 
					SELECT sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personalnomina.codper, sno_personalnomina.codtab, sno_personalnomina.codgra, sno_personalnomina.codpas, 
						 sno_grado.monsalgra, sno_grado.moncomgra, 
						 (SELECT SUM(CASE WHEN sno_primagrado.monpri IS NULL THEN 0 ELSE sno_primagrado.monpri END) 
							FROM sno_primagrado 
							 WHERE sno_primagrado.codemp = sno_grado.codemp 
							 AND sno_primagrado.codtab = sno_grado.codtab 
							 AND sno_primagrado.codpas = sno_grado.codpas 
							 AND sno_primagrado.codgra = sno_grado.codgra 
							 GROUP BY sno_primagrado.codemp, sno_primagrado.codtab, sno_primagrado.codpas, sno_primagrado.codgra) AS monto_primas 
					  FROM sno_personalnomina, sno_grado 
					 WHERE sno_personalnomina.codemp=sno_grado.codemp 
					   AND sno_personalnomina.codnom=sno_grado.codnom 
					   AND sno_personalnomina.codtab=sno_grado.codtab 
					   AND sno_personalnomina.codgra=sno_grado.codgra 
					   AND sno_personalnomina.codpas=sno_grado.codpas;";	
	  $rs_data   = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 { 
		   $this->io_msg->message("Problemas al ejecutar uf_crear_vista_37");
		   $lb_valido=false;
		 }
	  return $lb_valido;
	}// fin de uf_crear_vista_37
} // end class_vistas_db
?>