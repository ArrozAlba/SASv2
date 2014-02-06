M_ToolTipDelay=300    // Milliseconds after menu has been displayed before showing tooltip

_tos=_los=0

with(M_toolTipStyle=new mm_style()){
    offbgcolor = "#FFFFE1";
      offcolor = "#000000";
   bordercolor = "#999999";
   borderstyle = "solid";
       padding = 1
   borderwidth = 1
      fontsize = "10px";
     fontstyle = "normal";
    fontfamily = "tahoma, verdana"; 
      
    overfilter = "Fade(duration=0.2);Shadow(color='#777777', Direction=135, Strength=5)"
}
with(new menuname("M_toolTips"))
{
	top="offset=22"
	left="offset=10"
	style=M_toolTipStyle;
	margin=4
	if(_W.M_maxTipWidth)maxwidth=M_maxTipWidth
	aI("text=;type=ToolTip;");
}
drawMenus()

function _gTOb(_mob){
	t_M=$h(_mob)
	_Tgm=$c("menu"+t_M)}

_gTOb("M_toolTips")
_TipIF=$mD
function buildTips(_ttxt)
{
	if(!_m[t_M][23]&&!_startM){
		_m[t_M][23]=1
		g$(t_M)	
		}
		_m[t_M].tooltip=1
	_el=_m[t_M][0][0]
	if($tL(_Tgm.style.visibility)==$6)return
	//$E(_Tgm,_n,_n,_n,_n)
	_mi[_el][1]=_ttxt
	_Tgm.innerHTML=o$(t_M)
	_mcnt--
	_i=_itemRef
	popup(_m[t_M][1],1);
	_TipIF=$mD
	_itemRef=_i
	_p2(t_M)
	j_=$D(_gm)
	if((j_[0]+j_[2])>_bH)$E(_gm,Y_-j_[2])
	_Tgm.style.zIndex=_zi+100	
	_TtSo=0
}

function _cttO(){
	clearTimeout(_Mtip)
	_Mtip=null}

function hidetip(){
	_TtSo=0
	_Gtt=""
	_cttO()
	$Y(t_M,0)
	M_hideLayer(t_M,0)
	
	_tipFoll=0
	_TTD=M_ToolTipDelay
	_gTOb("M_toolTips")
}
	
hidetip()

function showtip(){
	_cttO()
	if(!op5||!op6||!ns4)
	//showObjProps(showtip)
	$arg=arguments
	if($arg[0]||_W._Gtt){
		if(_W._Gtt)_ttxt=_Gtt; else _ttxt=$arg[0];
		_Gtt=_ttxt
		if($arg[1])_tipFoll=1 // Toggle for whether tooltips follows mouse movement
		if($arg[2])_TTD=$arg[2] // Tooltip delay in milliseconds
		if($arg[3])_gTOb($arg[3]); // tipmenu
	}
	else{
		if(_itemRef==-1)return
		_ttxt=_mi[_itemRef][95]
		if(_mi[_itemRef][98])_TTD=_mi[_itemRef][98]
		if(_mi[_itemRef][100])_gTOb(_mi[_itemRef][100]);
		
	}
	if(_ttxt==""||_ttxt==_n)return	
	if(!inDragMode)_Mtip=setTimeout("buildTips('"+_ttxt+"')",_TTD)
	_TtSo=1
}

function _TtM(){
	if(_TtSo)showtip()
	if(_tipFoll==1||(_trueItemRef>-1&&_mi[_trueItemRef][99]))
	{
		_TY=Y_+_tos
		_TX=X_+_los
		$E(_Tgm,_TY,_TX)	
		_a9=$c("iF"+_TipIF)
		if(_a9)$E(_a9,_TY,_TX)
	}

}
