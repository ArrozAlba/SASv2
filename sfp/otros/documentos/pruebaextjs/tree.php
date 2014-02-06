[{

    text:'Administracion',
    expanded:true,
    children:[
    {
    	id : 'client',
    	tabType:'load',
        text:'Clientes',
        iconCls:'client_icon',
        leaf:true
    },{
    	id : 'invoice',
    	tabType:'load',
        text:'Facturas',
        iconCls:'invoice_icon',
        leaf:true
    }]
},
{

	text:'Web',
	expanded:true,
	iconCls:'web_icon',
	children:[
	{
		id : 'google',
		tabType:'iframe',
		url: 'http://www.google.com',
	    text:'Google',
	    iconCls:'google_icon',
	    leaf:true
	}
	,
	{
		id : 'limestore',
		tabType:'iframe',
		url: 'http://www.limestore.es',
	    text:'LimeStore',
	    iconCls:'limestore_icon',
	    leaf:true
	}
	,
	{
		id : 'yahoo',
		tabType:'iframe',
		url: 'http://www.yahoo.es',
	    text:'Yahoo',
	    iconCls:'yahoo_icon',
	    leaf:true
	}
	]
}
]