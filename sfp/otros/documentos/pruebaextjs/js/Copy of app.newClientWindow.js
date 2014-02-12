Ext.ns('app');


app.newClientWindow  = Ext.extend(Ext.Window, {
    // Constructor Defaults, can be overridden by user's config object
	id : 'newClientWindow',
    title:'Nuevo Cliente',
    layout:'fit',
    width:700,
    height:400,
    modal: true,
    closeAction:'hide',
    plain: true,
	iconCls: 'client_icon',
	buttons: [
        {
            id: 'newClientWindow_butonSave',
            text: 'Guardar',

            handler: function () {
            	var win= Ext.getCmp('newClientWindow');
				win.saveClient();
            }
		}
        ,{
        	id : 'newClientWindow_butonClose',
            text: 'Cerrar',
        	handler: function () {
            	var win= Ext.getCmp('newClientWindow');
				win.closeWindow();
            }
        }
	]
	,
	newForm: new Ext.FormPanel({
	        id:'newClientWindow_form',
	        labelWidth: 75, // label settings here cascade unless overridden
	        url:'client/save_new_client.php',
	        frame:true,
	        border:false,
	        bodyStyle:'padding:5px 5px 0',
	        width: 700,

	        defaults: {width:'200'},
	        defaultType: 'textfield',

	        items: [

	        	{
	                fieldLabel: 'Razón Social',
	                name: 'cli_razon_social',
	                allowBlank:false

	            },{
	                fieldLabel: 'CIF/NIF',
	                name: 'cli_cif_nif',
	                allowBlank:false

	            },{
	                fieldLabel: 'Dirección',
	                name: 'cli_direccion'

	            },
	            {
	                fieldLabel: 'Localidad',
	                name: 'cli_localidad',
	                allowBlank:false
	            },
	            {
	                fieldLabel: 'CP',
	                name: 'cli_cp',
	                allowBlank:false
					vtype:'integer'
	            },
	            {
	                fieldLabel: 'Provincia',
	                name: 'cli_provincia',
	                 allowBlank:false
	            },
	            {
	                fieldLabel: 'Pais',
	                name: 'cli_pais',
	                 allowBlank:false
	            },
	            {
	                fieldLabel: 'Teléfono',
	                name: 'cli_telefono'
	            },
	            {
	                fieldLabel: 'Email',
	                name: 'cli_email',
	                vtype:'email'
	            },
	            {
	                fieldLabel: 'Web',
	                name: 'cli_web'
	            },
	            {
	                fieldLabel: 'Cuenta Bancaria',
	                name: 'cli_cuenta_banco'
	            }

	        ]
	    })
	,


    closeWindow: function () {

		this.newForm.getForm().reset();
        this.hide();
    },

    saveClient : function () {

    	this.newForm.getForm().submit({
	    	waitTitle:'Creando registro ...',
			//waitMsg:'Sending data...',
			scope: this,
	        success: function(f, a){
	            if (a.success)
	            {
	            	f.reset();
	            	//Creamos la ventana
	            	this.hide();
	            	//Recargamos la grid
	            	Ext.example.msg('Mensaje del sistema', 'Nuevo cliente creado');
	            	app.client_store.reload();
	            }
	        },
	        failure: function(){
	                    // código para error
	        }
	    });
    }
    ,
	initComponent : function () {
		//Comprovamos si se le pasa para que no de error
		if (this.record) var record=this.record.data;

        var args = {
            items : [this.newForm]
        }
		/*
        //Dependiento de tipo de ventara realizacremos una acciones o otras
		switch (this.windowType)
		{
			case 'new':
				this.title='Nuevo Cliente'
			break;
			case 'modify':
				this.title='Editar registro: ' + record.cli_razon_social;
			break;
		}
		*/
        Ext.apply(this, args);
        app.newClientWindow.superclass.initComponent.apply(this, arguments);
    }
});

// register xtype to allow for lazy initialization
Ext.reg('newClientWindow', app.newClientWindow );