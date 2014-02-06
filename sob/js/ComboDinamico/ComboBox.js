/*
 *	ComboBox
 *	By Jared Nuzzolillo
 *
 *	Updated by Erik Arvidsson
 *	http://webfx.eae.net/contact.html#erik
 *	2002-06-13	Fixed Mozilla support and improved build performance
 *
 */

Global_run_event_hook = true;
Global_combo_array    = new Array();

Array.prototype.remove=function(dx)
{ 
    if(isNaN(dx)||dx>this.length){self.status='Array_remove:invalid request-'+dx;return false}
    for(var i=0,n=0;i<this.length;i++)
    {  
        if(this[i]!=this[dx])
        {
            this[n++]=this[i]
        }
    }
    this.length-=1
}

function ComboBox_make()
{
    var bt,nm;
	nombre=this.name;
    nm = this.name+"txt"; 
    
    this.txtview = document.createElement("INPUT")
    this.txtview.type = "text";
    this.txtview.name = nm;
    this.txtview.id = nm;
    this.txtview.className = "combo-input";
	this.txtview.size="20";
	this.txtview.maxlength="20";		       
    this.view.appendChild(this.txtview);
	
	
    this.valcon = document.createElement("INPUT");
    this.valcon.type = "hidden";	
    this.view.appendChild(this.valcon);   
   
    this.txtview.onkeyup=new Function ("","ue_valida_combojs(nombre,20)");
	
	/* function() 
	{		
		if (combojs.txtview.value.length > 20) // if too long...trim it!
		{
			combojs.txtview.value = combojs.value.substring(0,20);	
			combojs.valcon.value=combojs.txtview.value;
		}
	} 	*/
	
     var tmp = document.createElement("IMG");
    tmp.src = "___";
	tmp.src = "";
    tmp.style.width = "1px";
    tmp.style.height = "0";
    this.view.appendChild(tmp);

	
    var tmp = document.createElement("INPUT");	
	var navegador=navigator.appName;
	if(navegador=="Microsoft Internet Explorer")
	{
		tmp.value="6";
	}
	else
	{
		tmp.value="...";
	}
	tmp.type="button";	
    //tmp.appendChild(document.createTextNode(6));
    tmp.className = "combo-button";	
    
	this.view.appendChild(tmp);
   	tmp.onfocus = function () { this.blur(); };
	tmp.onclick = new Function ("", this.name + ".toggle()");
}

function ComboBox_choose(realval,txtval)
{
    this.value         = realval;
    var samstring = this.name+".view.childNodes[0].value='"+txtval+"'"
    window.setTimeout(samstring,1)
    this.valcon.value  = realval;
}

function ComboBox_mouseDown(e)
{
   var obj,len,el,i;
    el = e.target ? e.target : e.srcElement;
    while (el.nodeType != 1) el = el.parentNode;
    var elcl = el.className;
    if(elcl.indexOf("combo-")!=0)
    {
				
        len=Global_combo_array.length
        for(i=0;i<len;i++)
        {
        
            curobj = Global_combo_array[i]
            
            if(curobj.opslist)
            {
                curobj.opslist.style.display='none'
            }
        }
    }
}

function ComboBox_handleKey(e)
{
    var key,obj,eobj,el,strname;
    eobj = e;
    key  = eobj.keyCode;
    el = e.target ? e.target : e.srcElement;
    while (el.nodeType != 1) el = el.parentNode;
    elcl = el.className
    if(elcl.indexOf("combo-")==0)
    {
        if(elcl.split("-")[1]=="input")
        {
            strname = el.id.split("txt")[0]
            obj = window[strname];
			
            obj.expops.length=0
            obj.update();
            obj.build(obj.expops);
            if(obj.expops.length==1&&obj.expops[0].text=="(No matches)"){}//empty
            else{obj.opslist.style.display='block';}
            obj.value = el.value;
            obj.valcon.value = el.value;
        }
     }
}

function ComboBox_update()
{
   var opart,astr,alen,opln,i,boo;
    boo=false;
    opln = this.options.length
    astr = this.txtview.value.toLowerCase();
    alen = astr.length
    if(alen==0)
    {
        for(i=0;i<opln;i++)
        {
            this.expops[this.expops.length]=this.options[i];boo=true;
        }
    }
    else
    {
        for(i=0;i<opln;i++)
        {
            opart=this.options[i].text.toLowerCase().substring(0,alen)
            if(astr==opart)
            {
                this.expops[this.expops.length]=this.options[i];boo=true;
            }
        }
    }
    if(!boo){this.expops[0]=new ComboBoxItem("(No matches)","")}
}


function ComboBox_remove(index)
{
   this.options.remove(index)
}

function ComboBox_add()
{
    var i,arglen;
    arglen=arguments.length
    for(i=0;i<arglen;i++)
    {
        this.options[this.options.length]=arguments[i]
    }
}

function ComboBox_build(arr)
{
    var str,arrlen
    arrlen=arr.length;
    str = '<table class="combo-list-width" cellpadding=0 cellspacing=0>';
    var strs = new Array(arrlen);
    for(var i=0;i<arrlen;i++)
    {
        strs[i] = '<tr>' +
			'<td class="combo-item" onClick="'+this.name+'.choose(\''+arr[i].value+'\',\''+arr[i].text+'\');'+this.name+'.opslist.style.display=\'none\';"' +
			'onMouseOver="this.className=\'combo-hilite\';" onMouseOut="this.className=\'combo-item\'" >&nbsp;'+arr[i].text+'&nbsp;</td>' +
			'</tr>';
    }
    str = str + strs.join("") + '</table>'
    
    if(this.opslist){this.view.removeChild(this.opslist);}
    
    this.opslist = document.createElement("DIV")
    this.opslist.innerHTML=str;
    this.opslist.style.display='none';
    this.opslist.className = "combo-list";
    this.opslist.onselectstart=returnFalse;
    this.view.appendChild(this.opslist);   
}

function ComboBox_toggle()
{
    if(this.opslist)
    {
        if(this.opslist.style.display=="block")
        {
            this.opslist.style.display="none"
        }
        else
        {
            this.update();
            this.build(this.options);
            this.view.style.zIndex = ++ComboBox.prototype.COMBOBOXZINDEX
            this.opslist.style.display="block"
        }
    }
    else
    {
        this.update();
        this.build(this.options);
        this.view.style.zIndex = ++ComboBox.prototype.COMBOBOXZINDEX
        this.opslist.style.display="block"
    }
}

function ComboBox()
{
   if(arguments.length==0)
    {
        self.status="ComboBox invalid - no name arg"
    }
    this.name     = arguments[0];
	
    this.par      = arguments[1]||document.body;
    this.view     = document.createElement("div");
    this.view.style.position='absolute';
    this.options  = new Array();
    this.expops   = new Array();
    this.value    = "";

    this.build  = ComboBox_build
    this.make   = ComboBox_make;
    this.choose = ComboBox_choose;
    this.add    = ComboBox_add;
    this.toggle = ComboBox_toggle;
    this.update = ComboBox_update;
    this.remove = ComboBox_remove;

    this.make();
    this.txtview = this.view.childNodes[0];
    this.valcon  = this.view.childNodes[1];
    
    this.par.appendChild(this.view);

    Global_combo_array[Global_combo_array.length]=this;
    if(Global_run_event_hook){ComboBox_init();}
}

ComboBox.prototype.COMBOBOXZINDEX =1000 //change this if you must

function ComboBox_init() 
{
	if (document.addEventListener) {
		document.addEventListener("keyup", ComboBox_handleKey, false );
		document.addEventListener("mousedown", ComboBox_mouseDown, false );
	}
	else if (document.attachEvent) {
		document.attachEvent("onkeyup", function () { ComboBox_handleKey(window.event); } );
		document.attachEvent("onmousedown", function () { ComboBox_mouseDown(window.event); } );
	}
	
    Global_run_event_hook = true;
}

function returnFalse(){return false;}

function ComboBoxItem(text,value)
{
    this.text  = text;
    this.value = value;
}

//document.write('<link rel="STYLESHEET" type="text/css" href="ComboBox.css">')