<?php
	session_start();	
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_comp.php");
	$io_funciones_comp=new class_funciones_comp();
	// proceso a ejecutar
	$ls_proceso=$io_funciones_comp->uf_obtenervalor("proceso","");
	// total de filas de lso detalles
	$li_totaldetalle=$io_funciones_comp->uf_obtenervalor("totaldetalles","1"); 
	// total de filas de lso detalles contables
	$li_totaldetallecont=$io_funciones_comp->uf_obtenervalor("totaldetallescont","1");
	 
	$as_comprobante=$io_funciones_comp->uf_obtenervalor("comprobante","");
	
	$ad_fecha=$io_funciones_comp->uf_obtenervalor("fecha","1900-01-01");
	
	$as_procede=$io_funciones_comp->uf_obtenervalor("procede","");
	
	$li_estmodest=$io_funciones_comp->uf_obtenervalor("estmod","");

	$ls_codban=$io_funciones_comp->uf_obtenervalor("codban","---");

	$ls_ctaban=$io_funciones_comp->uf_obtenervalor("ctaban","-------------------------");
	
	switch($ls_proceso)
	{
		case "AGREGARDETALLES":		
			uf_pintar_detallepre($li_totaldetalle,$li_totaldetallecont);			
		break;
		
		case "LOADPRESUPUESTO":
		    $ls_codemp=$_SESSION["la_empresa"]["codemp"];				    
			uf_load_comprobante($ls_codemp,$as_comprobante,$ad_fecha,$as_procede,$ls_codban,$ls_ctaban);			
		break;
	}
	
	function uf_pintar_detallepre($as_totalfila, $as_totalfilacont)
	{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintar_detallepre
		//		   Access: private
		//	    Arguments: $as_totalfila  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los detalles presupuesatrios
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/11/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 global $io_grid, $io_funciones_comp, $io_solicitud, $li_estmodest, $io_comp, $li_comprobante, $li_fecha;
		 $ls_empresa=$_SESSION["la_empresa"]["codemp"];
		 // fecha delcomprobante
		 $li_fecha=$io_funciones_comp->uf_obtenervalor("fecha","1900-01-01");
		 // numero del comprobante 
		 $li_comprobante=$io_funciones_comp->uf_obtenervalor("comprobante","");	  
		  // modalidad 1- > 3 niveles, 2-> 5 niveles
	      $li_estmodest=$io_funciones_comp->uf_obtenervalor("estmodest","1");
		 // Titulos del Grid de Bienes
		  $title[1]="Cuenta";   
		  if($li_estmodest==1)
		  {
			   $title[2]="Imputaci&oacute;n Presupuestaria";
			   $li_size=32;
			   $li_maxlength=29;
			   $li_sizedoc=30;
			   $li_maxlengthdoc=30;
			   $li_sizedes=40;
			   $li_maxlengthdes=254;
		  }
		  else
		  { 
		   		$title[2]="Programatico";
				$li_size=40;
			    $li_maxlength=33;
			    $li_sizedoc=37;
			    $li_maxlengthdoc=15;
			    $li_sizedes=41;
			    $li_maxlengthdes=254;     
		  }     
		  $title[3]="Documento";    
		  $title[4]="Descripci&oacute;n";   
		  $title[5]="Procede"; 
		  $title[6]="Operaci&oacute;n";     
		  $title[7]="Monto";  
		  $title[8]="Edici&oacute;n";
		  $grid1="grid_SPG";
		  
		  $title2[1]="Cuenta";   
		  $title2[2]="Documento";
		  $title2[3]="Descripci&oacute;n";
		  $title2[4]="Procede";
		  $title2[5]="D/H";   
		  $title2[6]="Monto";   
		  $title2[7]="Edici&oacute;n";
		  
		  $montopre=0;
		  $montodeb=0;
		  $montohab=0;
		  $filavacia=0;
		  $object[1][1]="";
		  $object[1][2]=""; 
		  $object[1][3]="";
		  $object[1][4]="";
		  $object[1][5]="";
		  $object[1][6]="";
		  $object[1][7]="";		
		  $object[1][8]="";
		  for ($li_fila=1;$li_fila<=$as_totalfila;$li_fila++)
		      {	
				$ls_cuenta		 = trim($io_funciones_comp->uf_obtenervalor("txtcuenta".$li_fila,""));
				$ls_programatica = trim($io_funciones_comp->uf_obtenervalor("txtprogramtico".$li_fila,""));
				$ls_documento	 = trim($io_funciones_comp->uf_obtenervalor("txtdocumento".$li_fila,""));
				$ls_descripcion  = trim($io_funciones_comp->uf_obtenervalor("txtdescripcion".$li_fila,""));
				$ls_procede		 = trim($io_funciones_comp->uf_obtenervalor("txtprocede".$li_fila,""));				
				$ls_operacion	 = trim($io_funciones_comp->uf_obtenervalor("txtoperacion".$li_fila,""));
				$ls_monto		 = trim($io_funciones_comp->uf_obtenervalor("txtmonto".$li_fila,""));
				$ls_estcla		 = trim($io_funciones_comp->uf_obtenervalor("txtestcla".$li_fila,""));
				$ls_scgcta		 = trim($io_funciones_comp->uf_obtenervalor("scgcta".$li_fila,""));
				$ls_monto2       = str_replace(".","",$ls_monto);
				$ls_monto2       = str_replace(",",".",$ls_monto2);	
				$montopre=$montopre+$ls_monto2;
				if ($ls_operacion=="D")
				{
					$montodeb=$montodeb+$ls_monto2; 
				}
			if($ls_cuenta != "")
			{					
			$object[$li_fila][1]="<input type=text name=txtcuenta".$li_fila." id=txtcuenta".$li_fila." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$li_fila][2]="<input type=text name=txtprogramatico".$li_fila." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=30 maxlength=$li_maxlength>
			<input type=hidden name=txtestcla".$li_fila." id=txtestcla".$li_fila." value='".$ls_estcla."'>
			<input type=hidden name=txtscgcta".$li_fila." id=txtscgcta".$li_fila." value='".$ls_scgcta."'>"; 
			$object[$li_fila][3]="<input type=text name=txtdocumento".$li_fila." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$li_fila][4]="<input type=text name=txtdescripcion".$li_fila." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left>";
			$object[$li_fila][5]="<input type=text name=txtprocede".$li_fila." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[$li_fila][6]="<input type=text name=txtoperacion".$li_fila." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[$li_fila][7]="<input type=text name=txtmonto".$li_fila." value='".$ls_monto."' class=sin-borde readonly style=text-align:right>";		
			$object[$li_fila][8] ="<a href=javascript:uf_delete_dt_presupuesto(".($li_fila).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";			
			}
			else
			{
			 $filavacia++;
			}
			///------------//////////////----------------------/////////////////------------------------------------////////////			
		  }// fin del for
	   /////para el detalle contable---------------------------------------------------------------------------------------
	      $object2[1][1]="";
		  $object2[1][2]=""; 
		  $object2[1][3]="";
		  $object2[1][4]="";
		  $object2[1][5]="";
		  $object2[1][6]="";
		  $object2[1][7]="";		
		  for ($li_fila2=1;$li_fila2<$as_totalfilacont;$li_fila2++)	
		  {	  			  
		  		$ls_sc_cuenta = trim($io_funciones_comp->uf_obtenervalor("txtcontable".$li_fila2,""));
				$ls_documento = trim($io_funciones_comp->uf_obtenervalor("txtdocscg".$li_fila2,""));
				$ls_desdoc    = trim($io_funciones_comp->uf_obtenervalor("txtdesdoc".$li_fila2,""));
				$ls_procdoc   = trim($io_funciones_comp->uf_obtenervalor("txtprocdoc".$li_fila2,""));
				$ls_debhab	  = trim($io_funciones_comp->uf_obtenervalor("txtdebhab".$li_fila2,""));
				$ldec_monto   = trim($io_funciones_comp->uf_obtenervalor("txtmontocont".$li_fila2,""));
				$ldec_monto2  = str_replace(".","",$ldec_monto);
				$ldec_monto2  = str_replace(",",".",$ldec_monto2);
				
				if ($ls_debhab=="D")
				   {				     
					 $montodeb=$montodeb+$ldec_monto2; 
			 	   }
				else
				   {
				     $montohab=$montohab+$ldec_monto2;
				   }			  
					$object2[$li_fila2][1]="<input type=text name=txtcontable".$li_fila2."  id=txtcontable".$li_fila2."  value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
					$object2[$li_fila2][2]="<input type=text name=txtdocscg".$li_fila2."    id=txtdocscg".$li_fila2."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
					$object2[$li_fila2][3]="<input type=text name=txtdesdoc".$li_fila2."    id=txtdesdoc".$li_fila2."    value='".$ls_desdoc."'    class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254 title='".$ls_desdoc."'>";
					$object2[$li_fila2][4]="<input type=text name=txtprocdoc".$li_fila2."   id=txtprocdoc".$li_fila2."   value='".$ls_procdoc."'   class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
					$object2[$li_fila2][5]="<input type=text name=txtdebhab".$li_fila2."    id=txtdebhab".$li_fila2."    value='".$ls_debhab."'    class=sin-borde readonly style=text-align:center size=3  maxlength=1>"; 
					$object2[$li_fila2][6]="<input type=text name=txtmontocont".$li_fila2." id=txtmontocont".$li_fila2." value='".$ldec_monto."'   class=sin-borde readonly style=text-align:right  size=22 maxlength=28>";
					$object2[$li_fila2][7] ="<a href=javascript:uf_delete_dt_contable(".($li_fila2).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
		}// fin del for
	   
		//$as_totalfila=$as_totalfila-$filavacia;		  
	    $io_grid->makegrid($as_totalfila,$title,$object,840,"Detalle Presupuestario","gridpresup");		  
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";		
		print "   <td width='650' align='right'> Total Presupuestario:</td>";	
		print "   <td><input type=text name=txtmontopre  style='text-align:right' class=contorno value='". number_format($montopre,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28></td>";
		print "    </tr>";
		print "    <tr>";	
		print " 	  <td height='22' align='left'><a href='javascript:uf_agregar_dtcon();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Contable' width='20' height='20' border='0'>Agregar Detalle Contable</a></td>";		
		print "    </tr>";
		print "  </table>";
		
		$li_fila2=$li_fila2-1;
		if($object2[1][1] != "")
		{	
		 $io_grid->makegrid($li_fila2,$title2,$object2,840,"Detalle contable","gridcontable");
		} 
		
		
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";		
		print "   <td width='650' align='right'> Debe:</td>";	
		print "   <td><input type=text name=txtdebe  style='text-align:right' class=contorno value='".number_format($montodeb,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28 ></td>";		
		print "    </tr>";	
		
		print "   <tr>";		
		print "   <td width='650' align='right'> Haber:</td>";	
		print "   <td><input type=text name=txthaber  style='text-align:right' class=contorno value='".number_format($montohab,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28 ></td>";		
		print "    </tr>";	
		$diferencia=0;
		$diferencia=$montodeb-$montohab;
		print "   <tr>";		
		print "   <td width='650' align='right'> Diferencia:</td>";	
		print "   <td><input type=text name=txtdiferencia  style='text-align:right' class=contorno value='".number_format($diferencia,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28></td>";		
		print "    </tr>";
			
		print "  </table>";	  
	}// fin de uf_pintar_detallepre
///////----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_comprobante($as_codemp,$as_comprobante,$ad_fecha,$as_procede,$as_codban,$as_ctaban)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobante()
		//		   Access: private
		//	    Arguments: $as_totalfila  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los detalles presupuesatrios
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 03/12/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid,$li_estmodest,$ls_loncodestpro1;$ls_loncodestpro2;$ls_loncodestpro3;$ls_loncodestpro4;$ls_loncodestpro5;
		
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	    $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	    $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	    $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	    $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		
		$title[1]="Cuenta";   
		if($li_estmodest==1)
		{
			 $title[2]="Imputaci&oacute;n Presupuestaria";
			 $li_size=32;
			 $li_maxlength=29;
			 $li_sizedoc=30;
			 $li_maxlengthdoc=30;
			 $li_sizedes=40;
			 $li_maxlengthdes=254;
		}
		else
		{ 
		   	$title[2]="Programatico";
			$li_size=40;
			$li_maxlength=33;
			$li_sizedoc=37;
			$li_maxlengthdoc=15;
			$li_sizedes=41;
			$li_maxlengthdes=254;     
		}     
		$title[3]="Documento";    
		$title[4]="Descripci&oacute;n";   
		$title[5]="Procede"; 
		$title[6]="Operaci&oacute;n";     
		$title[7]="Monto";  
		$title[8]="Edici&oacute;n";		
		require_once("sigesp_spg_c_comp.php");
	    $io_comp=new sigesp_spg_c_comp();
		$rs_data = $io_comp->uf_load_dt_comprobante($as_codemp,$as_comprobante,$ad_fecha,$as_procede,$as_codban,$as_ctaban);
		$li_fila=0;		
		$montopre=0;		
		while($row=$io_comp->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;			
			$ls_cuenta=$row["spg_cuenta"];
			$ls_codest1=substr($row["codest1"],-$ls_loncodestpro1);
			$ls_codest2=substr($row["codest2"],-$ls_loncodestpro2);
			$ls_codest3=substr($row["codest3"],-$ls_loncodestpro3);
			$ls_codest4=substr($row["codest4"],-$ls_loncodestpro4);
			$ls_codest5=substr($row["codest5"],-$ls_loncodestpro5);
			if ($li_estmodest==1)
			{
				$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;
			}
			else
			{
				$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;
			}
		    $ls_documento=$row["documento"];
			$ls_descripcion=$row["descripcion"];
			$ls_procede=$row["procede_doc"];				
			$ls_operacion=$row["operacion"];
			$ls_monto=$row["monto"];
			$montopre=$montopre+$ls_monto;
			if ($ls_operacion=="D")
			{
				$montodeb=$montodeb+$ls_monto;
			}	
			$ls_estcla=$row["estcla"];
			$ls_scgcta=$row["scg_cuenta"];
			//$ls_monto=number_format($ls_monto,2,",",".");
			
			$object[$li_fila][1]="<input type=text name=txtcuenta".$li_fila." id=txtcuenta".$li_fila." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$li_fila][2]="<input type=text name=txtprogramatico".$li_fila." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=30 maxlength=$li_maxlength>
			<input type=hidden name=txtestcla".$li_fila." id=txtestcla".$li_fila." value='".$ls_estcla."'>
			<input type=hidden name=txtscgcta".$li_fila." id=txtscgcta".$li_fila." value='".$ls_scgcta."'>"; 
			$object[$li_fila][3]="<input type=text name=txtdocumento".$li_fila." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$li_fila][4]="<input type=text name=txtdescripcion".$li_fila." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left>";
			$object[$li_fila][5]="<input type=text name=txtprocede".$li_fila." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[$li_fila][6]="<input type=text name=txtoperacion".$li_fila." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[$li_fila][7]="<input type=text name=txtmonto".$li_fila." value='".number_format($ls_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
			$object[$li_fila][8] ="<a href=javascript:uf_delete_dt_presupuesto(".($li_fila).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";		
		}// fin del while
		$io_grid->makegrid($li_fila,$title,$object,840,"Detalle Presupuestario","gridpresup");		  
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";		
		print "   <td width='650' align='right'> Total Presupuestario:</td>";	
		print "   <td><input type=text name=txtmontopre  style='text-align:right' class=contorno value='".number_format($montopre,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28></td>";
		print "    </tr>";
		print "    <tr>";	
		print " 	  <td height='22' align='left'><a href='javascript:uf_agregar_dtcon();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Contable' width='20' height='20' border='0'>Agregar Detalle Contable</a></td>";		
		print "    </tr>";
		print "  </table>";		
		/////-------------------------detalle contable---------------------------------------------------------------------------
		$rs_data2 = $io_comp->uf_load_contable($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban);
		$montodeb=0;
		$montohab=0;
		$li_fila2=0;
		$title2[1]="Cuenta";   
		$title2[2]="Documento";
		$title2[3]="Descripci&oacute;n";
		$title2[4]="Procede";
		$title2[5]="D/H";   
		$title2[6]="Monto";   
		$title2[7]="Edici&oacute;n";
		$row2=0;
		while($row2=$io_comp->io_sql->fetch_row($rs_data2))	  
		{
			$li_fila2=$li_fila2+1;
			$ls_sc_cuenta=$row2["sc_cuenta"];
			$ls_documento=$row2["documento"];
			$ls_desdoc=$row2["descripcion"];
			$ls_procdoc=$row2["procede_doc"];
			$ls_debhab=$row2["debhab"];
			$ldec_monto=$row2["monto"];
			if ($ls_debhab=="D")
			{
				$montodeb=$montodeb+$ldec_monto;
			}
			else
			{
				$montohab=$montohab+$ldec_monto;
			}			  
			$object2[$li_fila2][1]="<input type=text name=txtcontable".$li_fila2." id=txtcontable".$li_fila2." value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[$li_fila2][2]="<input type=text name=txtdocscg".$li_fila2." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[$li_fila2][3]="<input type=text name=txtdesdoc".$li_fila2." value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[$li_fila2][4]="<input type=text name=txtprocdoc".$li_fila2." value='".$ls_procdoc."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[$li_fila2][5]="<input type=text name=txtdebhab".$li_fila2." value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[$li_fila2][6]="<input type=text name=txtmontocont".$li_fila2." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28>";
			$object2[$li_fila2][7] ="<a href=javascript:uf_delete_dt_contable(".($li_fila2).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
		}// fin del while
		
		if ($li_fila2==0)
		{
			$li_fila2=1;
			$object2[$li_fila2][1]="<input type=text name=txtcontable".$li_fila2." id=txtcontable".$li_fila2." value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[$li_fila2][2]="<input type=text name=txtdocscg".$li_fila2." value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[$li_fila2][3]="<input type=text name=txtdesdoc".$li_fila2." value='' title='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[$li_fila2][4]="<input type=text name=txtprocdoc".$li_fila2." value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[$li_fila2][5]="<input type=text name=txtdebhab".$li_fila2." value='' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[$li_fila2][6]="<input type=text name=txtmontocont".$li_fila2." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=28>";
			$object2[$li_fila2][7] ="<a href=javascript:uf_delete_dt_contable(".($li_fila2).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
		}
		$io_grid->makegrid($li_fila2,$title2,$object2,840,"Detalle contable","gridcontable");		
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";		
		print "   <td width='650' align='right'> Debe:</td>";	
		print "   <td><input type=text name=txtdebe  style='text-align:right' class=contorno value='".number_format($montodeb,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28 ></td>";		
		print "    </tr>";	
		
		print "   <tr>";		
		print "   <td width='650' align='right'> Haber:</td>";	
		print "   <td><input type=text name=txthaber  style='text-align:right' class=contorno value='".number_format($montohab,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28 ></td>";		
		print "    </tr>";	
		$diferencia=0;
		$diferencia=$montodeb-$montohab;
		print "   <tr>";		
		print "   <td width='650' align='right'> Diferencia:</td>";	
		print "   <td><input type=text name=txtdiferencia  style='text-align:right' class=contorno value='".number_format($diferencia,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28></td>";		
		print "    </tr>";
			
		print "  </table>";	  
	}///fin de uf_load_comprobante()
///-----------------------------------------------------------------------------------------------------------------------------------------
?>