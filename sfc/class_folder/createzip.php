<?
/* Autor: Martin R. Mondragon Sotelo
   e-mail: martin@mygnet.com
*/
function createzip($struct,$namezip=0)
{ 	$ZipData = array();
	$Dircont = array();
	$DirFile = array();
	$offseto = 0;
	while(list($file,$data)=each($struct))
	{	$file= str_replace("\\", "/", $file);
	    $dir=explode("/",$file);
		for($i=0; $i<sizeof($dir); $i++)if($dir[$i]=="")unset($dir[$i]);
		$num=count($dir); //Total de niveles
		$ele=0;  		  //Nivel actual
		$dirname="";	  //Nombre archivo o directorio
		while(list($idx,$val)=each($dir))
		{	$ty=(++$ele)==$num?true:false;
			$ty=trim($data)!=""?$ty:false;//Compruebar si el ultimo elemento es directorio o archivo
			$dirname.=$val.($ty?"":"/");
			if(isset($DirFile[$dirname]))continue; else $DirFile[$dirname]=true;
			$gzdata="";
			if($ty)
			{	$unziplen=strlen($data);
				$czip=crc32($data);
				$gzdata=gzcompress($data);
				$gzdata=substr(substr($gzdata,0,strlen($gzdata)-4),2);
				$cziplen=strlen($gzdata);
			}
			$ZipData[]="\x50\x4b\x03\x04".($ty?"\x14":"\x0a")."\x00\x00\x00".($ty?"\x08":"\x00")."\x00\x00\x00\x00\x00".
					   pack("V",$ty?$czip:0).pack("V",$ty?$cziplen:0).pack("V",$ty?$unziplen:0).pack("v",strlen($dirname)).
					   pack("v",0).$dirname.$gzdata.pack("V",$ty?$czip:0).pack("V",$ty?$cziplen:0).pack("V",$ty?$unziplen:0);
			$Dircont[]="\x50\x4b\x01\x02\x00\x00".($ty?"\x14":"\x0a")."\x00\x00\x00".($ty?"\x08":"\x00")."\x00\x00\x00\x00\x00".
					   pack("V",$ty?$czip:0).pack("V",$ty?$cziplen:0).pack("V",$ty?$unziplen:0).pack("v",strlen($dirname)).
					   pack("v", 0 ).pack("v",0).pack("v",0).pack("v",0).pack("V",$ty?32:16).pack("V",$offseto).$dirname;
			$offseto=strlen(implode("",$ZipData));
		}//Fin While dir
	}//Fin While archivos
	$data = implode("",$ZipData);
	$cdir = implode("",$Dircont);
	$data=$data.$cdir."\x50\x4b\x05\x06\x00\x00\x00\x00".pack("v",sizeof($Dircont)).pack("v",sizeof($Dircont)).pack("V",strlen($cdir)).pack("V",strlen($data))."\x00\x00";
    if($namezip)//Construir el archivo
    {	if(($fp=fopen($namezip,"wb")))
   		{	fwrite($fp,$data);
			fclose ($fp);
			return true;
		}else return false;
    }else return $data;
}

?>