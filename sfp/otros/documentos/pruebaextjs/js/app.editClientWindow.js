Ext.ns('app');


app.editClientWindow  = Ext.extend(Ext.Window, {
    // Constructor Defaults, can be overridden by user's config object
	id : 'editClientWindow',
	//title: 'Editar Cliente;'+this.record.data.cli_razon_social,
    layout:'fit',
    width:600,
    height:400,
    modal: true,
    closeAction:'destroy',
    plain: true,
	iconCls: 'client_icon',
	buttons: [
        {
            id: 'editClientWindow_butonSave',
            text: 'Guardar',

            handler: function () {
            	var win= Ext.getCmp('editClientWindow');
				win.saveClient();
            }
		}
        ,{
        	id : 'editClientWindow_butonClose',
            text: 'Cerrar',
        	handler: function () {
            	var win= Ext.getCmp('editClientWindow');
				win.closeWindow();
            }
        }
	]
	,


    closeWindow: function () {

		//Ext.getCmp('editClientWindow_form').getForm().reset();
        this.destroy(true);
    },

    saveClient : function () {

    	Ext.getCmp('editClientWindow_form').getForm().submit({
	    	waitTitle:'Guardando registro ...',
			//waitMsg:'Sending data...',

			scope: this,
	        success: function(f, a){
	            if (a.success)
	            {
	            	//Ext.getCmp('editClientWindow_form').reset();
	            	//Creamos la ventana
	            	//this.hide();
	            	var win= Ext.getCmp('editClientWindow');
					win.closeWindow();
	            	//Recargamos la grid
	            	Ext.example.msg('Mensaje del sistema', 'Cliente modificado');
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
            items : [
            	new Ext.FormPanel({
			        id:'editClientWindow_form',
			        labelWidth: 100, // label settings here cascade unless overridden
			        url:'client/save_edit_client.php',
					width:600,
    				height:400,
			        border:false,

			        defaultType: 'textfield',
			        items: {
			            xtype:'tabpanel',
			            height:400,
			            activeTab: 0,
			            border:false,
			            defaults:{autoHeight:true, bodyStyle:'padding:10px'},
			            items:[{
			                title:'Datos fiscales',
			                layout:'form',
			                defaults: {width: 150},
			                defaultType: 'textfield',
			                items: [
								{

					                name: 'cli_id',
					                value : this.record.id,
									hidden:true,
									labelSeparator:" ",
									style:"visibility:hidden;"
					            },
					        	{
					                fieldLabel: 'Razón Social',
					                name: 'cli_razon_social',
					                allowBlank:false,
					                width: 400,
					                value : this.record.data.cli_razon_social

					            },{
					                fieldLabel: 'CIF/NIF',
					                name: 'cli_cif_nif',
					                allowBlank:false,
					                value : this.record.data.cli_cif_nif

					            },{
					                fieldLabel: 'Dirección',
					                name: 'cli_direccion',
					                width: 400,
					                value : this.record.data.cli_direccion

					            },
					            {
					                fieldLabel: 'Localidad',
					                name: 'cli_localidad',
					                allowBlank:false,
					                value : this.record.data.cli_localidad
					            },
					            {
					                fieldLabel: 'CP',
					                name: 'cli_cp',
					                allowBlank:false,
					                value : this.record.data.cli_cp
					            },
					            {
					                fieldLabel: 'Provincia',
					                name: 'cli_provincia',
					                 allowBlank:false,
					                value : this.record.data.cli_provincia
					            },
					            {
					                fieldLabel: 'Pais',
					                name: 'cli_pais',
					                 allowBlank:false,
					                value : this.record.data.cli_pais
					            }
			                ]
			            },{
			                title:'Datos de contacto',
			                layout:'form',
			                defaults: {width: 150},
			                defaultType: 'textfield',

			                items: [
			                	{
					                fieldLabel: 'Teléfono',
					                name: 'cli_telefono',
					                value : this.record.data.cli_telefono
					            },
					            {
					                fieldLabel: 'Email',
					                name: 'cli_email',
					                vtype:'email',
					                value : this.record.data.cli_email
					            },
					            {
					                fieldLabel: 'Web',
					                name: 'cli_web',
					                value : this.record.data.cli_web
					            }
			                ]
			            },
			            {
			                title:'Datos bancarios',
			                layout:'form',
			                defaults: {width: 150},
			                defaultType: 'textfield',

			                items: [
			                	{
					                fieldLabel: 'Cuenta Bancaria',
					                name: 'cli_cuenta_banco',
					                width: 400,
					                value : this.record.data.cli_cuenta_banco
					            }
			                ]
			            }
			            ]
			        }
			    })
            ]
        }

        //Modificamos el título de la ventana
        this.title='Editar Cliente: '+this.record.data.cli_razon_social;


        Ext.apply(this, args);
        app.newClientWindow.superclass.initComponent.apply(this, arguments);
    }
});

// register xtype to allow for lazy initialization
Ext.reg('newClientWindow', app.newClientWindow );