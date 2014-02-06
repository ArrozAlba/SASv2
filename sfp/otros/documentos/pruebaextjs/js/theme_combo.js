Ext.ux.ThemeCombo = Ext.extend(Ext.form.ComboBox, {
    // configurables
     themeBlueText: 'Ext Blue Theme'
    ,themeGrayText: 'Gray Theme'
    ,themeBlackText: 'Black Theme'
    ,themeOliveText: 'Olive Theme'
    ,themePurpleText: 'Purple Theme'
    ,themeDarkGrayText: 'Dark Gray Theme'
    ,themeSlateText: 'Slate Theme'
    ,themeVistaText: 'Vista Theme'
    ,themePeppermintText: 'Peppermint Theme'
    ,themeChocolateText: 'Chocolate Theme'
    ,selectThemeText: 'Select Theme'
    ,lazyRender:true
    ,lazyInit:true
    ,cssPath:'../ext/resources/css/' // mind the trailing slash

    // {{{
    ,initComponent:function() {

        Ext.apply(this, {
            store: new Ext.data.SimpleStore({
                 fields: ['themeFile', 'themeName']
                ,data: [
                     ['xtheme-default.css', this.themeBlueText]
                    ,['xtheme-gray.css', this.themeGrayText]
                    ,['xtheme-darkgray.css', this.themeDarkGrayText]
                    ,['xtheme-black.css', this.themeBlackText]
                    ,['xtheme-olive.css', this.themeOliveText]
                    ,['xtheme-purple.css', this.themePurpleText]
                    ,['xtheme-slate.css', this.themeSlateText]
                    ,['xtheme-peppermint.css', this.themePeppermintText]
                    ,['xtheme-chocolate.css', this.themeChocolateText]
                ]
            })
            ,valueField: 'themeFile'
            ,displayField: 'themeName'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,fieldLabel: this.selectThemeText
        }); // end of apply

        // call parent
        Ext.ux.ThemeCombo.superclass.initComponent.apply(this, arguments);
    } // end of function initComponent
    // }}}
    // {{{
    ,onSelect:function() {
        // call parent
        Ext.ux.ThemeCombo.superclass.onSelect.apply(this, arguments);

        // set theme
        var theme = this.getValue();
        Ext.util.CSS.swapStyleSheet('theme', this.cssPath + theme);

        if(Ext.state.Manager.getProvider()) {
            Ext.state.Manager.set('theme', theme);
        }
    } // end of function onSelect
    // }}}

}); // end of extend
