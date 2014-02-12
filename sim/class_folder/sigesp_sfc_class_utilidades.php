<?Php
//////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_class_utilidades
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que contiene utilidades diversas.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_class_utilidades
{
 var $io_funcsob;
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

function sigesp_sfc_class_utilidades()
{

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];

}

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
			$this->is_msg_error="uf_datacombo".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	/*	Function:	    uf_evalconsulta                                                    */
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
}
?>
