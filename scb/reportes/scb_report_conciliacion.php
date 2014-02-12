<?php
class scb_report_conciliacion
{
	function scb_report_conciliacion($conn)
	{
	  $this->fun = new class_funciones();
	  $this->SQL= new class_sql($conn);
	  $this->SQL_aux= new class_sql($conn);
	  $this->io_msg= new class_mensajes();		
	  $this->dat_emp=$_SESSION["la_empresa"];
	  $this->ds_disponibilidad=new class_datastore();
	  $this->ds_documentos=new class_datastore();
	}
	
	function uf_obtener_mov_conciliacion($ls_mesano,$ls_codban,$ls_ctaban,$ldec_salseglib,$ldec_salsegbco)
	{
		$io_fecha=new class_fecha();
		$ds_mov=new class_datastore();
		$ds_movimientos=new class_datastore();
		$ls_codemp=$this->dat_emp["codemp"];
		$ld_fechasta=$io_fecha->uf_last_day(substr($ls_mesano,0,2),substr($ls_mesano,2,4));
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ld_fechasta);
		$ld_fecdesde="01/".substr($ls_mesano,0,2)."/".substr($ls_mesano,2,4);
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ld_fecdesde);

		$ls_sql="SELECT * 
				 FROM scb_movbco
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND fecmov <='".$ld_fechasta."' AND (trim(estreglib)='' 
				 OR (trim(estreglib)<>'' AND feccon<>'".$ld_fecdesde."')) ORDER BY fecmov ASC  ";
		 
		$rs_data=$this->SQL->select($ls_sql);	
	 
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ds_mov->insertRow("codban",$ls_codban);
				$ls_ctaban=$row["ctaban"];
				$ds_mov->insertRow("ctaban",$ls_ctaban);
				$ls_numdoc=$row["numdoc"];
				$ds_mov->insertRow("numdoc",$ls_numdoc);
				$ls_nomproben=$row["nomproben"];
				$ds_mov->insertRow("nomproben",$ls_nomproben);
				$ld_fecmov=$row["fecmov"];
				$ds_mov->insertRow("fecmov",$ld_fecmov);
				$ldec_monto=$row["monto"];
				$ds_mov->insertRow("monto",$ldec_monto);
				$ls_conmov=$row["conmov"];
				$ds_mov->insertRow("conmov" ,$ls_conmov);	
				$ls_estmov=$row["estmov"];
				$ds_mov->insertRow("estmov" ,$ls_estmov);	
			}
			$this->SQL->free_result($rs_data);
		}		
		$ldec_saldo_ant = $this->uf_calcular_saldolibro($ls_codban,$ls_ctaban,$ld_fechasta);//Aca se manejaba fecha Hasta.
		$ldec_saldo_ant = number_format($ldec_saldo_ant,2,',','.');
		$ldec_salseglib = number_format($ldec_salseglib,2,',','.');
		$ld_monto 	    = abs($ldec_saldo_ant-$ldec_salseglib);
		//print $ldec_saldo_ant.' ; '.$ldec_salseglib.'<br>';
		if(abs($ldec_saldo_ant-$ldec_salseglib)>0.01)
		{
			$this->io_msg->message("Vuelva a modulo conciliación ya que hay movimientos no registrados $ldec_salseglib--- ".$ldec_saldo_ant);
			return false;
		}		
		
			$ls_sql= "SELECT '01' as tipo, '-' as suma, numdoc , nomproben, fecmov , monto-monret as monto, codope  
					  FROM scb_movbco
					  WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
					        trim(estreglib)=''  AND ((feccon > '".$ld_fecdesde."'  ) OR (feccon='1900-01-01')) AND 
					       (((codope='CH' or codope='ND' or codope='RE') and estmov<>'A') or ((codope='DP' or codope='NC') and estmov='A'))
						     ORDER BY fecmov ASC";


		
		$rs_data= $this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			print $this->SQL->message;
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}


			$ls_sql= "SELECT '02' as tipo, '+' as suma, numdoc, nomproben, fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND trim(estreglib)=''
					  AND ((feccon > '".$ld_fecdesde."' ) OR (feccon='1900-01-01'))
					  AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) 
					   ORDER BY fecmov ASC";
		
		$rs_data= $this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			print $this->SQL->message;
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}
			
		// No Registradas en Libros
		
		   $ls_sql = "SELECT 'A1' as tipo, '+' as suma, numdoc, conmov as nomproben,fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
					  AND  feccon='".$ld_fecdesde."' AND estreglib='A' AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR 
					  ((codope='DP' OR codope='NC') AND estmov='A'))  
					  ORDER BY fecmov ASC"; 
		
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);

		}		
		
		$ls_sql="SELECT 'A2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monto-monret as monto, codope 
				 FROM  scb_movbco
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				 AND feccon='".$ld_fecdesde."' AND estreglib='A' 
				 AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A'))
				   ORDER BY fecmov ASC";
			
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}		
				
		// Error Libro
		$ls_sql="SELECT 'B1' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) 
				 ORDER BY fecmov ASC";
			
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}				
		
		$ls_sql="SELECT 'B2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND  (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) 
				  ORDER BY fecmov ASC";
		
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);

		}
				
		// Error Banco
		$ls_sql="SELECT 'C1' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monmov-monret as monto, codope 
				 FROM scb_errorconcbco 
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ls_mesano."' AND esterrcon='C' AND 
				 (((codope='CH' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) 
				  ORDER BY fecmov ASC";
			
		$rs_data= $this->SQL->select($ls_sql);		
	
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}
		
		$ls_sql="SELECT 'C2' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monmov-monret as monto, codope 
				 FROM  scb_errorconcbco 
		 		 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ls_mesano."' and esterrcon='C' AND 
				 (((codope='CH' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) 
				  ORDER BY fecmov ASC";
		
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_codope);
			}
			$this->SQL->free_result($rs_data);
		}					
		return $ds_movimientos->data;	
	}
			
	function uf_calcular($data,$ls_mesano)		 
	{
		$ds_mov=new class_datastore();	
		$ds_mov->data=$data;
		$li_total=$ds_mov->getRowCount("numdoc");
		$ldec_CreditosTmp=0;
		$ldec_CreditosTmpNeg=0;
		$ldec_DebitosTmp=0;
		$ldec_DebitosTmpNeg=0;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_codope=$ds_mov->getValue("codope",$li_i);
			$ls_estmov=$ds_mov->getValue("estmov",$li_i);
			$ldec_monto=$ds_mov->getValue("monto",$li_i);
			if((($ls_codope=='CH')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov<>'A')){$ldec_CreditosTmp=$ldec_CreditosTmp+$ldec_monto;}
			if((($ls_codope=='CH')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov=='A')){$ldec_CreditosTmpNeg=$ldec_CreditosTmpNeg+$ldec_monto;}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov<>'A'))	{$ldec_DebitosTmp=$ldec_DebitosTmp+$ldec_monto;	}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov=='A'))	{$ldec_DebitosTmpNeg=$ldec_DebitosTmpNeg+$ldec_monto;}
		}
		$ldec_DebitosAnt = $ldec_DebitosTmp-$ldec_DebitosTmpNeg;
		$ldec_CreditosAnt = $ldec_CreditosTmp-$ldec_CreditosTmpNeg;
		$ldec_SaldoAnterior = $ldec_DebitosAnt - $ldec_CreditosAnt;				
		return round($ldec_SaldoAnterior,2);	
	}
	
	function uf_calcular_saldolibro($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	    :  uf_calcular_saldolibro
	//
	//	Return	    :  ldec_saldo
	//
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los movimientos registrdos en libro
	///////////////////////////////////////////////////////////////////////////// 
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;	
	$ls_codemp = $this->dat_emp["codemp"];		
	$ld_fecha  = $this->fun->uf_convertirdatetobd($ad_fecha);//fin
	$ls_fecha2 = substr($ld_fecha,0,8)."01";//inicio
	$ds_debe   = new class_datastore();
	$ds_haber  = new class_datastore();
    $ds_debe   = new class_datastore();
	$ds_haber  = new class_datastore();
	//--------------------DEBE------------------------------------//	
	$ls_sql = "SELECT (monto - monret) As mondeb,estmov
				 FROM scb_movbco
				WHERE codemp='".$ls_codemp."'
				  AND codban='".$as_codban."'
				  AND ctaban='".$as_ctaban."'
				  AND (codope='NC' OR codope='DP')  
				  AND (estreglib<>'A' OR (estreglib='A' AND estcon=1))
				  AND fecmov<='".$ld_fecha."'";//echo $ls_sql.'<br><br><br>';
		
	$rs_saldos=$this->SQL->select($ls_sql);
	if(($rs_saldos==false)&&($this->SQL->message!=""))
	{
		print "Saldolibro".$this->SQL->message;
	}
	else
	{
	    while($row=$this->SQL->fetch_row($rs_saldos))
		{
		  $ds_debe->insertRow("mondeb", $row["mondeb"]);
 	 	  $ds_debe->insertRow("estmov", $row["estmov"]);	
		}
	}
	$this->SQL->free_result($rs_saldos);
	
//-----------------------HABER---------------------------------------//
	$ls_sql="SELECT (monto - monret) As monhab,estmov
			   FROM scb_movbco
			  WHERE codemp='".$ls_codemp."'
			    AND codban='".$as_codban."'
			    AND ctaban='".$as_ctaban."'
				AND (codope='RE' OR codope='ND' OR codope='CH')
				AND (estreglib<>'A' OR (estreglib='A' AND estcon=1))
				AND fecmov<='".$ld_fecha."'";//echo $ls_sql.'<br><br><br>';
		    
	$rs_saldos=$this->SQL->select($ls_sql);
	if(($rs_saldos==false)&&($this->SQL->message!=""))
	{
		print "Saldolibro".$this->SQL->message;
	}
	else
	{
	    while($row=$this->SQL->fetch_row($rs_saldos))
		{
			$ds_haber->insertRow("monhab", $row["monhab"]);
			$ds_haber->insertRow("estmov", $row["estmov"]);	
		}
	}
	$this->SQL->free_result($rs_saldos);	
	$li_totdebe=$ds_debe->getRowCount("estmov");
	$ldec_totdeb=0;
	$ldec_totdeb_anulado=0;
	for($li_i=1;$li_i<=$li_totdebe;$li_i++)
	{
		$ls_estmov=$ds_debe->getValue("estmov",$li_i);
		$ls_mondeb=$ds_debe->getValue("mondeb",$li_i);
		if($ls_estmov!='A')
		{
			$ldec_totdeb+=$ls_mondeb;
		}
		else
		{
			$ldec_totdeb_anulado+=$ls_mondeb;
		}		
	}
	$ldec_totdeb=$ldec_totdeb-$ldec_totdeb_anulado;
	$li_tothaber=$ds_haber->getRowCount("estmov");
	$ldec_tothab=0;
	$ldec_tothab_anulado=0;
	for ($li_i=1;$li_i<=$li_tothaber;$li_i++)
	    {
		  $ls_estmov=$ds_haber->getValue("estmov",$li_i);
		  $ls_monhab=$ds_haber->getValue("monhab",$li_i);
		  if ($ls_estmov!='A')
		     {
			   $ldec_tothab+=$ls_monhab;
		     }
		  else
		     {
			   $ldec_tothab_anulado+=$ls_monhab;
		     }		
	}
	$ldec_tothab=$ldec_tothab-$ldec_tothab_anulado;
	$ldec_saldo=$ldec_totdeb-$ldec_tothab;
	return $ldec_saldo;	
	}
	function uf_fecha_anulacion($as_numdoc,$as_codban,$as_codope,$as_ctaban,$as_estmov,$as_fechainicio,$as_fechafin)
	{	  
		$ls_codemp = $this->dat_emp["codemp"];		
		$ls_sql="SELECT fecmov 
				FROM scb_movbco where codemp='$ls_codemp' AND numdoc='$as_numdoc' AND codban='$as_codban'
				 AND codope='$as_codope' AND ctaban='$as_ctaban' AND estmov='$as_estmov' AND fecmov<'$as_fechainicio'
				  AND fecmov>'$as_fechafin'";
		$rs_saldos=$this->SQL->select($ls_sql);
		if(($rs_saldos==false)&&($this->SQL->message!=""))
		{
			print "uf_fecha_anulacion".$this->SQL->message;
			return false;
		}
		else
		{
		    if($row=$this->SQL->fetch_row($rs_saldos))
			{
				return true;		
			}
			else
			{
				return false;
			}
	}
	
	}
	function uf_tipo_cuenta($ls_codban,$ls_ctaban)
	{
	 /////////////////////////////////////////////////////////////////////////////
	//  Funtion	    :  uf_tipo_cuenta
	//	Return	    :  ls_tipo_cuenta
	//	Descripcion :  Funcion que se encarga de retornar el tipo de una cuenta
	//  Autor       :  Ing. Laura Cabré
	//  Fecha       :  22/11/2006
	///////////////////////////////////////////////////////////////////////////// 
	    $ls_codemp=$this->dat_emp["codemp"];
	    $ls_sql="SELECT t.nomtipcta 
				FROM scb_tipocuenta t, scb_ctabanco s 
				WHERE s.codemp='$ls_codemp' AND s.codban='$ls_codban' AND s.ctaban='$ls_ctaban'
				AND s.codtipcta=t.codtipcta";
		$rs_tipo=$this->SQL->select($ls_sql);		
		if(($rs_tipo==false)&&($this->SQL->message!=""))
		{
			print "Tipo_Cuenta".$this->SQL->message;
		}
		else
		{		
			if($row=$this->SQL->fetch_row($rs_tipo))
			{
				$ls_tipocuenta=$row["nomtipcta"];
			
			}
			$this->SQL->free_result($rs_tipo);			
		}			
		return  $ls_tipocuenta;	
	}	
}
?>