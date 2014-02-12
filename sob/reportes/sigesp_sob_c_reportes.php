<?Php
/***************************************************************************************/
/*	Clase:	        Reporte Obra                                                       */    
/*  Fecha:          25/03/2006                                                         */        
/*	Autor:          GERARDO CORDERO		                                               */     
/***************************************************************************************/
class sigesp_sob_c_reportes
{
 var $io_funcsob;
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

function sigesp_sob_c_reportes()
{

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	//require_once("../../shared/class_folder/class_funciones.php");
	//require_once("../../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);	
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];

}

function uf_titulos($as_tiporeporte,$aa_campos)
{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_campos_a_mostrar
	 //	Access:  public
	 //	Returns: -
	 //	Parametros: as_tiporeporte: tipo de reporte a mostrar				
	 //	Descripcion: funcion que se encarga de retornar un arreglo con los titulos campos a ser mostrados
	 // Fecha: 01/06/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	 switch($as_tiporeporte)
	 {
	 	case "OBRA":
			$la_titulos["titulo"][1]="Obra";
			$la_titulos["campo"][1]="codobr";
			$la_titulos["titulo"][2]="Comunidad";
			$la_titulos["campo"][2]="nomcom";
			$la_titulos["titulo"][3]="Descripción";
			$la_titulos["campo"][3]="desobr";
			$la_titulos["titulo"][4]="Dirección";
			$la_titulos["campo"][4]="dirobr";
			$la_titulos["titulo"][5]="Edo.(Ubic)";
			$la_titulos["campo"][5]="desest";
			$la_titulos["titulo"][6]="Registro";
			$la_titulos["campo"][6]="feccreobr";
			$la_titulos["titulo"][7]="Finalización";
			$la_titulos["campo"][7]="fecfinobr";
			$la_titulos["titulo"][8]="Inicio";
			$la_titulos["campo"][8]="feciniobr";
			$la_titulos["titulo"][9]="Monto";
			$la_titulos["campo"][9]="monto";
			$la_titulos["titulo"][10]="Municipio";
			$la_titulos["campo"][10]="denmun";
			$la_titulos["titulo"][11]="Organismo Ejecutor";
			$la_titulos["campo"][11]="orgejec";
			$la_titulos["titulo"][12]="Parroquia";
			$la_titulos["campo"][12]="denpar";			
			$la_titulos["titulo"][13]="Responsable";
			$la_titulos["campo"][13]="resobr";
			$la_titulos["titulo"][14]="Sistema Constructivo";
			$la_titulos["campo"][14]="nomsiscon";
			$la_titulos["titulo"][15]="Tenencia";
			$la_titulos["campo"][15]="nomten";
			$la_titulos["titulo"][16]="Estructura";
			$la_titulos["campo"][16]="nomtipest";
			$la_titulos["titulo"][17]="Tipo";
			$la_titulos["campo"][17]="nomtob";		
			$li_filastitulos = 17;
		break;			
		case "PARTIDA":
			$la_titulos["titulo"][1]="Código";
			$la_titulos["campo"][1]="codpar";
			$la_titulos["titulo"][2]="C. COVENIN";
			$la_titulos["campo"][2]="codcovpar";
			$la_titulos["titulo"][3]="Categoría";
			$la_titulos["campo"][3]="descatpar";
			$la_titulos["titulo"][4]="Unidad";
			$la_titulos["campo"][4]="nomuni";
			$la_titulos["titulo"][5]="Descripción";
			$la_titulos["campo"][5]="nompar";
			$la_titulos["titulo"][6]="Precio";
			$la_titulos["campo"][6]="prepar";
			$la_titulos["titulo"][7]="Cantidad";
			$la_titulos["campo"][7]="canparobr";
			$li_filastitulos = 7;
		break;
		
		case "ASIGNACION":
			$la_titulos["titulo"][1]="Asignación";
			$la_titulos["campo"][1]="codasi";
			$la_titulos["titulo"][2]="Pto de Cta.";
			$la_titulos["campo"][2]="puncueasi";
			$la_titulos["titulo"][3]="Contratista";
			$la_titulos["campo"][3]="nompro";
			$la_titulos["titulo"][4]="Fecha";
			$la_titulos["campo"][4]="fecasi";
			$la_titulos["titulo"][5]="Monto Parcial";
			$la_titulos["campo"][5]="monparasi";
			$la_titulos["titulo"][6]="Base Imponible";
			$la_titulos["campo"][6]="basimpasi";
			$la_titulos["titulo"][7]="Monto Total";
			$la_titulos["campo"][7]="montotasi";
			$li_filastitulos = 7;
		break;
		case "CONTRATO":
			$la_titulos["titulo"][1]="Contrato";
			$la_titulos["campo"][1]="codcon";
			$la_titulos["titulo"][2]="Monto";
			$la_titulos["campo"][2]="monto";
			$la_titulos["titulo"][3]="Descripción";
			$la_titulos["campo"][3]="desobr";
			$la_titulos["titulo"][4]="Obra";
			$la_titulos["campo"][4]="codobr";
			$la_titulos["titulo"][5]="Inicio";
			$la_titulos["campo"][5]="fecinicon";
			$la_titulos["titulo"][6]="Finalización";
			$la_titulos["campo"][6]="fecfincon";
			$la_titulos["titulo"][7]="Registro";
			$la_titulos["campo"][7]="feccon";
			$la_titulos["titulo"][8]="Cod. Asig.";
			$la_titulos["campo"][8]="codasi";
			$la_titulos["titulo"][9]="Observacion";
			$la_titulos["campo"][9]="obscon";			
			$li_filastitulos = 9;
		break;		
		case "VALUACION":	
			$la_titulos["titulo"][1]="Valuación";
			$la_titulos["campo"][1]="codval";
			$la_titulos["titulo"][2]="Inicio";
			$la_titulos["campo"][2]="fecinival";
			$la_titulos["titulo"][3]="Finalización";
			$la_titulos["campo"][3]="fecfinval";
			$la_titulos["titulo"][4]="Observación";
			$la_titulos["campo"][4]="obsval";
			$la_titulos["titulo"][5]="Amortización";
			$la_titulos["campo"][5]="amoval";
			$la_titulos["titulo"][6]="Amort. Acumulada";
			$la_titulos["campo"][6]="amototval";
			$la_titulos["titulo"][7]="Amort. Restante";
			$la_titulos["campo"][7]="amoresval";
			$la_titulos["titulo"][8]="Base Imponible";
			$la_titulos["campo"][8]="basimpval";
			$la_titulos["titulo"][9]="Retenciones";
			$la_titulos["campo"][9]="totreten";
			$la_titulos["titulo"][10]="Monto Valuación";
			$la_titulos["campo"][10]="montotval";
			$la_titulos["titulo"][11]="Monto por Partidas";
			$la_titulos["campo"][11]="subtotpar";
			$la_titulos["titulo"][12]="Obs. Amortiz.";
			$la_titulos["campo"][12]="obsamoval";
			$li_filastitulos = 12;
		break;
		case "SEGUIMIENTOOBRA":	
			$la_titulos["titulo"][1]="Obra";
			$la_titulos["campo"][1]="codobr";
			$la_titulos["titulo"][2]="Comunidad";
			$la_titulos["campo"][2]="nomcom";
			$la_titulos["titulo"][3]="Descripción";
			$la_titulos["campo"][3]="desobr";
			$la_titulos["titulo"][4]="Dirección";
			$la_titulos["campo"][4]="dirobr";
			$la_titulos["titulo"][5]="Edo.(Ubic)";
			$la_titulos["campo"][5]="desest";
			$la_titulos["titulo"][6]="Registro (Obra)";
			$la_titulos["campo"][6]="feccreobr";
			$la_titulos["titulo"][7]="Finalización (Obra)";
			$la_titulos["campo"][7]="fecfinobr";
			$la_titulos["titulo"][8]="Inicio (Obra)";
			$la_titulos["campo"][8]="feciniobr";
			$la_titulos["titulo"][9]="Monto (Obra)";
			$la_titulos["campo"][9]="montoobra";
			$la_titulos["titulo"][10]="Municipio";
			$la_titulos["campo"][10]="denmun";
			$la_titulos["titulo"][11]="Organismo Ejecutor";
			$la_titulos["campo"][11]="orgejec";
			$la_titulos["titulo"][12]="Parroquia";
			$la_titulos["campo"][12]="denpar";			
			$la_titulos["titulo"][13]="Responsable";
			$la_titulos["campo"][13]="resobr";
			$la_titulos["titulo"][14]="Sistema Constructivo";
			$la_titulos["campo"][14]="nomsiscon";
			$la_titulos["titulo"][15]="Tenencia";
			$la_titulos["campo"][15]="nomten";
			$la_titulos["titulo"][16]="Estructura";
			$la_titulos["campo"][16]="nomtipest";
			$la_titulos["titulo"][17]="Tipo Obra";
			$la_titulos["campo"][17]="nomtob";
			$la_titulos["titulo"][18]="Contratista";
			$la_titulos["campo"][18]="cod_pro";
			$la_titulos["titulo"][19]="Inspectora";
			$la_titulos["campo"][19]="cod_pro_ins";
			$la_titulos["titulo"][20]="Contratista";
			$la_titulos["campo"][20]="nomcontratista";			
			$la_titulos["titulo"][21]="Contrato";
			$la_titulos["campo"][21]="codcon";
			$la_titulos["titulo"][22]="Tipo Cont";
			$la_titulos["campo"][22]="nomtco";
			$la_titulos["titulo"][23]="Inicio (Cont.)";
			$la_titulos["campo"][23]="fecinicon";
			$la_titulos["titulo"][24]="Finalización (Cont.)";
			$la_titulos["campo"][24]="fecfincon";
			$la_titulos["titulo"][25]="Registro (Cont.)";
			$la_titulos["campo"][25]="feccon";
			$la_titulos["titulo"][26]="Duración";
			$la_titulos["campo"][26]="placon";			
			$la_titulos["titulo"][27]="Monto Cont.";
			$la_titulos["campo"][27]="montocontrato";
			$la_titulos["titulo"][28]="Monto Lim.";
			$la_titulos["campo"][28]="monmaxcon";			
			$la_titulos["titulo"][29]="Observación";
			$la_titulos["campo"][29]="obscon";
			$la_titulos["titulo"][30]="Status";
			$la_titulos["campo"][30]="estcon";
			$la_titulos["titulo"][31]="Anticipo";
			$la_titulos["campo"][31]="totalanticipo";
			$la_titulos["titulo"][32]="Ejec. Financ.(%)";
			$la_titulos["campo"][32]="totalejecfin";
			$la_titulos["titulo"][33]="Amort. Anticip.(%)";
			$la_titulos["campo"][33]="amortizacionanticipo";
			$la_titulos["titulo"][34]="Ejec. Física(%)";
			$la_titulos["campo"][34]="totalejecfisic";
			$la_titulos["titulo"][35]="Fuentes Financ.";
			$la_titulos["campo"][35]="fuentesfinanciemiento";
			$la_titulos["titulo"][36]="Fecha Paralizac.";
			$la_titulos["campo"][36]="fechaparalizacion";
			$la_titulos["titulo"][37]="Motivo Paralizac.";
			$la_titulos["campo"][37]="motivoparalizacion";
			$la_titulos["titulo"][38]="Fecha Reanudac.";
			$la_titulos["campo"][38]="fechareanudacion";
			$la_titulos["titulo"][39]="Fecha Prórroga";
			$la_titulos["campo"][39]="fechaprorroga";
			$la_titulos["titulo"][40]="Motivo Prórroga";
			$la_titulos["campo"][40]="motivoprorroga";	
			$la_titulos["titulo"][41]="Fecha Inicio Obra";
			$la_titulos["campo"][41]="fecinireacon";
			$la_titulos["titulo"][42]="Fecha Fin Obra";
			$la_titulos["campo"][42]="fecfinreacon";		
			$li_filastitulos = 42;
		break;			
}
	if(array_key_exists("keys",$_SESSION))
	{
	 	//print"--existe--";
		 $la_campos=$_SESSION["keys"]; 
	}
	else
	{
	 	//print "no existe";
		 $la_campos=array_keys($aa_campos);
	}
	//print_r($la_campos);
	$li_filascampos=count($la_campos);	
	$li_k=1;
	for($li_i=0;$li_i<$li_filascampos;$li_i++)
	{
		
		for($li_j=1;$li_j<=$li_filastitulos;$li_j++)
		{
			if($la_titulos["campo"][$li_j]==$la_campos[$li_i])
			{
				$la_titulosaux["titulo"][$li_k]=$la_titulos["titulo"][$li_j];
				$la_titulosaux["campo"][$li_k]=$la_titulos["campo"][$li_j];
				$li_k++;
				break;
			}
		}
	}
	// print "------------>";
	 //print_r($la_titulosaux);
	 return $la_titulosaux;
}

/*function uf_datareporte($as_tiporeporte,$as_querystring)
{
	$la_titulos=$this->uf_titulos($as_tiporeporte,$li_filas);
	$this->uf_obtenerdata ($as_querystring,$la_data);
	for ($li_i=1;$li_i<=$li_filas;$li++)
	{
		if($la_titulos)
		la_datareporte[""][]
	}
}*/

function uf_datacombo($ls_sql,&$aa_data)
	{
	/***************************************************************************************/
	/*	Function:	    uf_datacombo                                                       */    
	/*  Fecha:          18/05/2006                                                         */        
	/*	Autor:          GERARDO CORDERO		                                               */     
	/***************************************************************************************/
		$lb_valido=false;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
    function uf_evalconsulta($la_salida,$li_numsal,$la_tabla,$li_numtab,$la_parametro,$li_numpar)
	{
	/***************************************************************************************/
	/*	Function:	    uf_datacombo                                                       */    
	/*  Fecha:          18/05/2006                                                         */        
	/*	Autor:          GERARDO CORDERO		                                               */     
	/***************************************************************************************/
	
	/*********CONSTRUYENDO LA CONSULTA******************/
	$ls_sql="SELECT ";
    
    /***********ARMANDO LOS PARAMETROS DE SALIDA******************/
	$ls_sqlB="";
	for($li_i=1;$li_i<=$li_numsal;$li_i++)
	 {
		   $labelsal=$la_salida[$li_i][1];
		   $tabsal=$la_salida[$li_i][2];
		   $la_tabla[$tabsal][3]="1";
	       $ls_sqlB=$ls_sqlB.$labelsal;
	      if($li_i!=$li_numsal)
		   {
		     $li_s=$li_i+1;
			 while($li_s<=$li_numsal)
             {
                if($la_salida[$li_s][1]!="")
				{
				 $ls_sqlB=$ls_sqlB.", ";
				 break;
				}
				$li_s++;
		     }
           }
           else
           {
		    $ls_sqlB=$ls_sqlB." FROM "; 
		   }
	 } 
	if($ls_sqlB!="")
	 {
	   $ls_sql=$ls_sql.$ls_sqlB; 
	 }
	/*************************************************************/
	
	/*********ARMANDO LOS PARAMETROS DE BUSQUEDA******************/
	$ls_sqlA=" AND ";
	for($li_i=1;$li_i<=$li_numpar;$li_i++)
	 {
		  $labelpar=$la_parametro[$li_i][1];
		  $valuepar=$la_parametro[$li_i][2];
          $evalpar=$la_parametro[$li_i][3];
		  $tabpar=$la_parametro[$li_i][4];		  		  
          if(($valuepar!=""))
          {
           if($valuepar!="%%")
		   {
		     $ls_sqlA=$ls_sqlA.$labelpar.$evalpar."'".$valuepar."'";
		     $la_tabla[$tabpar][3]="1";
			 $li_s=$li_i+1;
		     while($li_s<=$li_numpar)
             {
              if(($la_parametro[$li_s][2]!=""))
               {
                if($la_parametro[$li_s][2]!="%%")
		         {
		           $ls_sqlA=$ls_sqlA." AND ";
				     break;
			     }
			   }
    		  $li_s++;
             } 
		    }
		   } 
	   } 
   /*****************************************************************/	
   
   /******************CHEQUEANDO TABLAS ENLAZADAS********************/
	
	for($li_i=1;$li_i<=$li_numtab;$li_i++)
	 {
		  $tabnom=$la_tabla[$li_i][1];
		  $tabcon=$la_tabla[$li_i][2];
          $tabflag=$la_tabla[$li_i][3];
		  $tablink=$la_tabla[$li_i][4];
		  if($tabflag=="1")
		  {
		  	  if($tablink!=0)
		  	  {
			   $la_tabla[$tablink][3]="1";
			   $li_in=$la_tabla[$tablink][4];
			   while($li_in!=0)
			   {
			     $la_tabla[$li_in][3]="1";  
				 $li_in=$la_tabla[$li_in][4];
		  	   }	  
			  }
		        
		  	  
		  }		  
   	 } 
	/*****************************************************************/
   
   
   
   
   /***********AGREGANDO LAS TABLAS Y SUS ENLAZES********************/
	$ls_sqlC=" ";
	$ls_sqlD=" ";
	
	for($li_i=1;$li_i<=$li_numtab;$li_i++)
	 {
		  $tabnom=$la_tabla[$li_i][1];
		  $tabcon=$la_tabla[$li_i][2];
          $tabflag=$la_tabla[$li_i][3];
		  
		  
		  if($tabflag=="1")
		  {
		   $ls_sqlC=$ls_sqlC.$tabnom;
		   $ls_sqlD=$ls_sqlD.$tabcon;  
		  if($li_i!=$li_numtab)
		   {
		     $li_s=$li_i+1;
			 while($li_s<=$li_numtab)
		     {
			   if($la_tabla[$li_s][3]== "1")
		       {
			     $ls_sqlC=$ls_sqlC.",";
			     if($la_tabla[$li_s][2]!="")
			     {
				  $ls_sqlD=$ls_sqlD." AND ";  
				 }
				 break;
			   }
			   $li_s++;
    		 }
    	    }   
		  }		  
          if($li_i==$li_numtab)
		   {
		     $ls_sqlC=$ls_sqlC." WHERE"; 
		   }  
	 } 
	/*****************************************************************/
	
	/**************UNIENDO LAS SECCIONES DE LA CONSULTA***************/
	if($ls_sqlC!="")
	 {
	   $ls_sql=$ls_sql.$ls_sqlC; 
	 }

     if($ls_sqlD!="")
	 {
	   $ls_sql=$ls_sql.$ls_sqlD; 
	 }
	
	if($ls_sqlA!="" && $ls_sqlA!=" AND ")
	 {
	   $ls_sql=$ls_sql.$ls_sqlA; 
	 }
    /*****************************************************************/ 
	//print $ls_sqlB."\n";
    //print $ls_sqlC."\n";
	//print $ls_sqlD."\n";	
	//print $ls_sqlA."\n";
	//print $ls_sql;
	
	 return $ls_sql;
	
	}

function uf_obtenerdata ($as_querystring,&$aa_data)
{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_obtenerdata
	 //	Access:  public
	 //	Returns: -
	 //	Parametros: as_querystring: cadena con la sentencia SQL a ser ejecutada
	 //				aa_data: arreglo con la data obtenida
	 //	Descripcion: funcion que se encarga de ejecutar una tira sql y retornar la data resultante
	 // Fecha: 01/06/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	  	$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=$as_querystring;
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_obtenerdata".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
				$lb_valido=0;
			}			
		}		
		return $lb_valido;
}
}
?>
