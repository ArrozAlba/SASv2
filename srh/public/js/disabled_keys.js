
////////   Evitar el Actualizar   ////////////////
var msg = 'That functionality is restricted.';
var asciiBack       = 8;
var asciiTab        = 9;
var asciiSHIFT      = 16;
var asciiCTRL       = 17;
var asciiALT        = 18;
var asciiHome       = 36;
var asciiLeftArrow  = 37;
var asciiRightArrow = 39;
var asciiMS         = 92;
var asciiView       = 93;
var asciiF1         = 112;
var asciiF2         = 113;
var asciiF3         = 114;
var asciiF4         = 115;
var asciiF5         = 116;
var asciiF6         = 117;
var asciiF11        = 122;
var asciiF12        = 123;
var asciiF11        = 122;

	if(document.all)
	{ //ie 
		document.onkeydown = onKeyPress;
	}
	else if (document.layers || document.getElementById)
	{ //NS and mozilla 
		document.onkeypress = onKeyPress;
	}

	function onKeyPress(evt) 
	{
		window.status = '';
		var oEvent = (window.event) ? window.event : evt;
		
		var nKeyCode =  oEvent.keyCode ? oEvent.keyCode : oEvent.which ? oEvent.which :	void 0;
		var bIsFunctionKey = false;
	
		if(oEvent.charCode == null || oEvent.charCode == 0)
		{ 
			bIsFunctionKey = (nKeyCode >= asciiF2 && nKeyCode <= asciiF12) 
		|| 
			(nKeyCode == asciiALT || nKeyCode == asciiMS || nKeyCode == asciiView || nKeyCode == asciiHome || nKeyCode == asciiBack)
		}
		
		//convertir la tecla en un caracter para hacer mas entendible el codigo
		var sChar = String.fromCharCode(nKeyCode).toUpperCase();
	
		var oTarget = (oEvent.target) ? oEvent.target : oEvent.srcElement;
		var sTag = oTarget.tagName.toLowerCase();
		var sTagType = oTarget.getAttribute("type");
		
		var bAltPressed = (oEvent.altKey) ? oEvent.altKey : oEvent.modifiers & 1 > 0;
		var bShiftPressed = (oEvent.shiftKey) ? oEvent.shiftKey : oEvent.modifiers & 4 > 0;
		var bCtrlPressed = (oEvent.ctrlKey) ? oEvent.ctrlKey : oEvent.modifiers & 2 > 0;
		var bMetaPressed = (oEvent.metaKey) ? oEvent.metaKey : oEvent.modifiers & 8 > 0;
	
		var bRet = true; 
	
		if(sTagType != null){sTagType = sTagType.toLowerCase();}
	
		if  (sTag == "textarea" || (sTag == "input" && (sTagType == "text" || sTagType == "password")) && 
			(
				nKeyCode == asciiBack || nKeyCode == asciiSHIFT || nKeyCode == asciiHome || bShiftPressed || 
				(bCtrlPressed && (nKeyCode == asciiLeftArrow || nKeyCode == asciiRightArrow)))
			)
			{
			return true;
		}
		else if(bAltPressed && (nKeyCode == asciiLeftArrow || nKeyCode == asciiRightArrow))
		{ // block alt + left or right arrow
			bRet = false;
		}
		else if(bCtrlPressed && (sChar == 'A' || sChar == 'C' || sChar == 'V' || sChar == 'X' || sChar == 'R'))
		{ // ALLOW cut, copy and paste, and SELECT ALL
			bRet = false;
		}
		else if(bShiftPressed && nKeyCode == asciiTab)
		{//allow shift + tab
			bRet = false;
		}
		else if(bIsFunctionKey)
		{ // Capture and stop these keys
			bRet = false;
		}
		else if(bCtrlPressed || bShiftPressed || bAltPressed)
		{ //block  ALL other sequences, includes CTRL+O, CTRL+P, CTRL+N, etc....
			bRet = false;
		}

		if(!bRet)
		{
			try
			{
				oEvent.returnValue = false;
				oEvent.cancelBubble = true;
	
				if(document.all)
				{ //IE
					oEvent.keyCode = 0;
				}
				else
				{ //NS
					oEvent.preventDefault();
					oEvent.stopPropagation();
				}
				window.status = msg; 
			}
			catch(ex)
			{
				//alert(ex);
			}
		}

		return bRet;
	}


////////   Evitar el ATRAS   ////////////////
	if (history.forward(1)){location.replace(history.forward(1))}


////////   Evitar el click derecho   ////////////////
	var message = "";
	function clickIE()
	{ 
		if (document.all)
		{ 
			(message); 
			return false; 
		} 
	} 
	function clickNS(e)
	{ 
		if (document.layers || (document.getElementById && !document.all))
		{ 
			if (e.which == 2 || e.which == 3)
			{ 
				(message); 
				return false; 
			} 
		} 
	} 
	if (document.layers)
	{ 
		document.captureEvents(Event.MOUSEDOWN); 
		document.onmousedown = clickNS; 
	} 
	else 
	{ 
		document.onmouseup = clickNS; 
		document.oncontextmenu = clickIE; 
	} 


	document.oncontextmenu = new Function("return false")
	   document.onkeydown = function(){  
    if(window.event && window.event.keyCode == 116){ 
     window.event.keyCode = 505;  
    } 
    if(window.event && window.event.keyCode == 505){  
     return false;     
    }  
   }  


////////////////////////////////////////////////////////////

