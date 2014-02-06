function getKeyCode(e)
{
 if (window.event)
    return window.event.keyCode;
 else if (e)
    return e.which;
 else
    return null;
}

function keyRestrictgrid(e) 
{
 var validchars='';	
 var key='', keychar='';
 
 validchars='1234567890.,';
 key = getKeyCode(e);
 if (key == null) return true;
 keychar = String.fromCharCode(key);
 keychar = keychar.toLowerCase();
 validchars = validchars.toLowerCase();
 if (validchars.indexOf(keychar) != -1)
  return true;
 if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
  return true;
 return false;
}



