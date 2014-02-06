<?php 
require_once('crypt_class.php'); 
$crypt_class = new CRYPT_CLASS; 

$crypt_class->set_cipher($_REQUEST['cipher']); // set the cipher 
$crypt_class->set_mode('cfb'); // set encryption mode 
$crypt_class->set_key($_POST['enckey']); 


if ($_POST['action'] == 'encrypt') { 
   $data = $crypt_class->encrypt($_POST['text']); 
} elseif ($_POST['action'] == 'decrypt') { 
   $data = $crypt_class->decrypt($_POST['text']); 
} 
    

$ciphers = mcrypt_list_algorithms(); 
natsort($ciphers); 

foreach ($ciphers as $cipher) { 
    
   if ($_POST['cipher'] == $cipher) { 
      $selected = 'selected'; 
   } else { 
      unset($selected); 
   } 
    
   $cipheroptions .= '<option ' . $selected . '>' . $cipher . '</option>'; 
} 

unset ($cipher); 

?> 
<p><h3>Directions:</h3></p> 
<blockquote><p> 
(1.) Input the data to be encrypted or decrypted into Text<br> 
(2.) Type in an encryption/decryption key 
(3.) Select a encryption cipher (twofish is excellent)<br> 
(4.) Select Encrypt or Decrypt 
(5.) Click Do It To It<br> 
</p></blockquote> 

<FORM method="post" action="<?php print $_SERVER['PHP_SELF']; ?>"> 
<table cellspacing="2" cellpadding="2"> 
<tr> 
   <td><b>Text</b></td><td><input type="text" size="60" name="text" value="<?php print $_POST['text']; ?>"></td> 
</tr> 

<tr> 
   <td><b>Encryption Key:</td><td><input type="text" size="32" name="enckey" value="<?php print $_POST['enckey']; ?>"></td> 
</tr> 

<tr> 
   <td>&nbsp;</td> 
</tr> 

<tr> 
   <td><b>Cipher:</b></td> 
   <td><select name="cipher"><?php print $cipheroptions; ?></select></td> 
</tr> 

<tr> 
   <td>&nbsp;</td> 
</tr> 

<tr> 
   <td colspan="2"><input type="radio" name="action" value="encrypt" checked> Encrypt &nbsp;&nbsp; <input type="radio" name="action" value="decrypt"> Decrypt</td> 
</tr> 
</table> 
<p align="center"><input type="submit" name="submit" value="Do It To It!"></p> 
</FORM> 

<?php 
   if (isset($_POST['submit'])) { 
      print '<p>Result: ' . $data . '</p>'; 
   } 
?> 