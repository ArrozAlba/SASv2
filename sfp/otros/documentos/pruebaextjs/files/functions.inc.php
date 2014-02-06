<?
	/*****************************************************************************************************
	 *****************************************************************************************************
		formateo de cadenas
	 *****************************************************************************************************
	*****************************************************************************************************/


	function showError($txt)
	{
		?>
		<div class="error">
			<h5>Error</h5>
			<p><?=$txt?></p>
		</div>
		<?
	}

	function mayus($key)
	{
		$key=strtoupper($key);
		$key=ereg_replace("á","Á",$key);
		$key=ereg_replace("é","É",$key);
		$key=ereg_replace("í","Í",$key);
		$key=ereg_replace("ó","Ó",$key);
		$key=ereg_replace("ú","Ú",$key);
		return $key;
	}

	function cleanSearch($key) {
		$sql="REPLACE(LOWER($key),'á','a')";
		$sql="REPLACE($sql,'à','a')";
		$sql="REPLACE($sql,'à','a')";
		$sql="REPLACE($sql,'é','e')";
		$sql="REPLACE($sql,'è','e')";
		$sql="REPLACE($sql,'í','i')";
		$sql="REPLACE($sql,'ó','o')";
		$sql="REPLACE($sql,'ú','u')";
		$sql="REPLACE($sql,'ï','i')";
		$sql="REPLACE($sql,'ü','u')";

		return $sql;
	}


	function cleanString($str)
	{
		$tmp=strtolower(stripslashes($str));
		$tmp=strtolower($tmp);
		$tmp=ereg_replace(" ","_",$tmp);
		$tmp=ereg_replace("á|à","a",$tmp);
		$tmp=ereg_replace("é|è","e",$tmp);
		$tmp=ereg_replace("í|ì","i",$tmp);
		$tmp=ereg_replace("ú|ù","u",$tmp);
		$tmp=ereg_replace("ó|ò","o",$tmp);
		$tmp=ereg_replace("Á|À","a",$tmp);
		$tmp=ereg_replace("É|È","e",$tmp);
		$tmp=ereg_replace("Í|Ì","i",$tmp);
		$tmp=ereg_replace("Ú|Ù","u",$tmp);
		$tmp=ereg_replace("Ó|Ò","o",$tmp);
		$tmp=ereg_replace("'|`|/","",$tmp);
		$tmp=ereg_replace("\.","",$tmp);
		$tmp=ereg_replace("\"","",$tmp);
		$tmp=ereg_replace("ñ","n",$tmp);
		$tmp=ereg_replace("Ñ","n",$tmp);
		$tmp=ereg_replace("ç","c",$tmp);
		$tmp=ereg_replace("Ç","c",$tmp);
		$tmp=ereg_replace("'","",$tmp);
		$tmp=ereg_replace("`","",$tmp);
		$tmp=ereg_replace("'","",$tmp);
		$tmp=ereg_replace("·","",$tmp);
		return $tmp;
	}


	/*****************************************************************************************************
	 *****************************************************************************************************
		FECHAS
	 *****************************************************************************************************
	*****************************************************************************************************/
	function getTimeStamp($date)
	{
		$temp=explode("/",$date);
		return mktime(0,0,0,intval($temp[1]),intval($temp[0]),intval($temp[2]));
	}

	function getDateFromTimeStamp($timeStamp)
	{

		return date(DATE_FORMAT,$timeStamp);
	}
	function splitDate($date)
	{
		$day = substr($date,0,2);
		$month = substr($date,3,2);
		$year = substr($date,6,4);

		$foo['day'] = $day;
		$foo['month'] = $month;
		$foo['year'] = $year;

		return $foo;
	}


	/*****************************************************************************************************
	 *****************************************************************************************************
		Acceso a la base de datos
	 *****************************************************************************************************
	*****************************************************************************************************/

	function sql($sql,$silent=false)
	{

		global $connection,$ERROR,$ERROR_TXT;

		$response=$connection->sql($sql);

		if(!$silent)
		{
			$tmp=!$response;
			$ERROR=$ERROR||$tmp;
			if ($connection->error()!="") $ERROR_TXT .="<p> SQL: ".$sql."<br/>Error: ".$connection->error()."</p>";
		}
		return $response;
	}

	function lastID($table)
	{

		$sql="SELECT distinct LAST_INSERT_ID() as 'last_id' FROM $table;";
		$response=sql($sql);
		return $response[0]['last_id'];
	}

	/*****************************************************************************************************
	 *****************************************************************************************************
		Envio de correo
	 *****************************************************************************************************
	*****************************************************************************************************/

	function enviaCorreo($to_array,$body,$fromEmail,$FromName,$Subject,$attachment_path='',$attachment='')
	{
		global $smtp_server,$error,$error_txt;

		$mail = new PHPMailer();
		//$mail->CharSet = "UTF-8";
		$mail->From     = ADMIN_MAIL;
		$mail->FromName = ADMIN_NAME;
		$mail->Host     = SMTP_SERVER;
		$mail->Mailer   = "smtp";
		$mail->Subject   = $Subject;
		$mail->ContentType = "text/html";
		$mail->Body  = $body;

		$mail->AddAddress($to_array['email'],$to_array['name']);

		if ($attachment!='') $mail->AddStringAttachment ($attachment_path,$attachment);

		if($mail->Send())
		{
			// Clear all addresses and attachments for next loop
	   		$mail->ClearAddresses();
	    	$mail->ClearAttachments();
	    	$return[0]=true;
	      	return $return;
		}
		else {
			$return[0]=false;
			$return[1]="Mail: Error: ".$mail->ErrorInfo;

			return $return;
		}
	}


	/*****************************************************************************************************
	 *****************************************************************************************************
		Ordenación
	 *****************************************************************************************************
	*****************************************************************************************************/

	function orderByDescription($a, $b)
	{
		if ($a['description'] == $b['description']) {
		   return 0;
	   }
	   return ($a['description'] < $b['description']) ? -1 : 1;
	}




	/*****************************************************************************************************
	 *****************************************************************************************************
		VARIAS
	 *****************************************************************************************************
	*****************************************************************************************************/

	function my_print_r($v)
	{
		?><pre><? print_r($v); ?></pre><?
	}


	function scan($dir)
	{


		if ($handle=@opendir($dir))
		{

			while ($entrada = readdir($handle))
			{


				if (!($entrada == "." || $entrada == ".."))
				{


					$file_name=$entrada;
					$file=$dir.$entrada;
					$file_type=filetype($file);
					$file_mime=filemtime($file);

					switch ($file_type)
					{
						case "dir":

							$tmp['name']=$file_name;
							$tmp['mtime']=$file_mime;
							$folders[]=$tmp;
							break;
						default:

							$tmp['name']=$file_name;
							$tmp['mtime']=$file_mime;
							$files[]=$tmp;
							break;
					}
				}
			}
			closedir($handle);

			//Ordenem els arxius per nom
			if (count($folders)>0) sort($folders);
			if (count($files)>0) sort($files);

			$result['folders']=$folders;
			$result['files']=$files;

			return $result;
		}
		else
		{
			return false;
		}
	}

	function cutText($txt,$l)
	{
		if (strlen($txt)>$l)
		{
			return substr($txt,0,$l-4)." ...";
		}
		else return $txt;

	}

?>