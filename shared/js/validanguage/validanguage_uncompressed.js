/**
 * The validanguage library was written by DrLongGhost in 2008. See attached MIT_License.js
 * and readme.txt for licensing and documentation.
 * Visit http://www.drlongghost.com/validanguage.php for updates.
 * 
 * 
 * @namespace  Global validanguage object
 * @author     DrLongGhost
 * @version    1.0.2
 */
var validanguage = {
/**
 * Valid values are 'none', 'dojo', 'jquery', 'prototype', and 'scriptaculous'.
 * @public
 * @default 'none'
 */
useLibrary: 'none',

/**
 * @private
 */
version: '1.0.2',

/**
 * @namespace  validanguage.settings object
 */
settings: {
    /**
     *  Should an alert() be shown when a validation fails?
     *  By default, validanguage.showError() and validanguage.hideError() instead place the
     *  error msg underneath the failed field.
     *  @default false
     */
    showAlert: false,
    
    /**
    * Should the target element of a failed validation receive focus when a validation fails?
    * IMPORTANT note regarding showAlert and focusOnError. Do NOT set both of these to true if using onblur validations. Pick either one or the other.
    * When you use both, it is possible to create infinite loops in which a validation failure generates an alert, triggering an onblur,
    * which triggers another validation failure and subsequent alert.   
    * If you aren't using onblur validations at all, you can safely use both.
    * @default false
    */
    focusOnerror: false,
    
    /**
    * When a form is submitted, are all form fields validated, or do we stop once one fails?
    * @default true
    */
    validateAllFieldsOnsubmit: true,
    
    /**
    * Override this to set a global success handlers for all validation results
    * If you want to use only alert messages via showAlert, set this to {} to turn off inline error msgs
    * @default 'validanguage.hideError' 
    */
    onsuccess: 'validanguage.hideError',
    
    /**
    * Override this to set a global error handler for all validation results
    * If you want to use only alert messages via showAlert, set this to {} to turn off inline error msgs
    * @default 'validanguage.showError' 
    */
    onerror: 'validanguage.showError',
    
    /** 
    * Default generic error message
    * @default  'You have entered an invalid entry in the form'
    */
    errorMsg: 'You have entered an invalid entry in the form',
    
    /** 
    * Default error message for the validateRequired validation
    * @default  'You have skipped a required field'
    */
    requiredErrorMsg: 'You have skipped a required field',
    
    /** 
    * Default error message for the validateMinlength validation
    * @default  'The indicated field must be at least {!minlength} characters long'
    */
    minlengthErrorMsg: 'The indicated field must be at least {!minlength} characters long',
    
    /** 
    * Default error message for the validateMaxlength validation
    * @default  'The indicated field may not be longer than {!maxlength} characters'
    */
    maxlengthErrorMsg: 'The indicated field may not be longer than {!maxlength} characters',
    
    /** 
    * Default error message for the validateCharacters function
    * @default  'You have entered invalid characters'
    */
    characterValidationErrorMsg: 'You have entered invalid characters',
    
    /**
    * Class name used in showError() to assign to the DIVs
    * which are created to show the inline error msgs.
    * @default  'vdError'
    */
    onErrorClassName: 'vdError',
    
    /**
    * Class name used in hideError() to assign to a DIV
    * which was created to show an inline error msgs which is then removed.
    * @default  'vdNoError'
    */
    noErrorClassName: 'vdNoError',
    
    /**
     * Class name used in hideError() to assign to a form field which passes validation
     * @default 'passedField'
     */
    passedFieldClassName: 'passedField',
    
    /**
     * Class name used in showError() to assign to a form field which fails validation
     * @default 'failedField'
     */
    failedFieldClassName: 'failedField',
    
    /**
    * Used to make the ID used in hideError() to assign to the SPAN element inside the vdError
    * DIV.  The errorMsgSpanSuffix is appended to the end of the form field's ID to make the SPAN ID.
    * If a SPAN with this ID already exists in the DOM, it will be used. If it doesn't exist, one will
    * be created dynamically.
    * @default  '_errorMsg'
    */
    errorMsgSpanSuffix: '_errorMsg',
    
    /** 
    * To display a combined list of all fields which failed validation in addition to the
    * inline error msgs, set showFailedFields to true.  The fields will be listed using the
    * "field" attribute (or ID if field is not available).
    * @default  false
    */
    showFailedFields: false,
    
    /** 
    * The text specified in errorListText will be placed at the top of the errorDiv generated
    * by the showFailedFields option in showError().
    * @default  '<strong>Please correct the following fields:</strong>'
    */
    errorListText: '<strong>Please correct the following fields:</strong>',
    
    /** 
    * Specifies the ID to be assigned to the DIV used for the showFailedFields option in showError().
    * If a DIV with this ID exists in the DOM, it will be used. If it doesn't exist, one will
    * be created dynamically.
    * @default  'vdErrorDiv'
    */
    errorDivId: 'vdErrorDiv',
    
    /** 
    * Specifies the ID to be assigned to the UL used for the showFailedFields option in showError().
    * @default  'vdErrorList'
    */
    errorListId: 'vdErrorList',
    
    /** 
    * Used to make the ID used for the showFailedFields option in showError().
    * The errorListItemSuffix is appended to the end of the form field's ID to make the ID for the LI item.
    * @default  '_vd_li'
    */
    errorListItemSuffix: '_vd_li',
    
    /**
    * Determines the ID of the DIV created in the showSubmitMessage() function used to
    * replace a form's submit button once the form has been submitted.
    * @default  'vdSubmitMessage'
    */
    showSubmitMessageId: 'vdSubmitMessage',
    
    /**
    * Determines the text used by the showSubmitMessage() function  which is used
    * replace a form's submit button once the form has been submitted.  If desired, you can include HTML
    * or IMG tags instead of the default text.
    * @default  'Loading'
    */
    showSubmitMessageMessage: 'Loading',
    
    /**
    * This array is used in the validateRequired function to determine whether a select box
    * has been left on the default, "empty" option.  Add/Remove from this array as needed.
    * @default  ['&nbsp;','0',' ','']
    */
    emptyOptionElements: ['&nbsp;','0',' ',''],
    
    /**
    * If a validation is supplied without any event handlers, how should it be treated in loadElAPI()?
    * This setting also affects the behavior of the required=true and maxlength/minlength shortcuts.
    * @default  ['submit']
    */
    defaultValidationHandlers: ['submit'],
    
    /**
    * If a transformation is supplied without any event handlers, how should it be treated in loadElAPI()?
    * @default  ['blur']
    */
    defaultTransformationHandlers: ['blur'],
    
    /**
     * Should any validanguage.toggle() transformations which are defined for form fields on the
     * page be automatically called when the page has finished loading.
     * @default true
     */
    callToggleTransformationsOnload: true,
    
    /**
     * Should the toggle visibility API in validanguage.toggle() default to "hidden" if a given target
     * does not satisfy any provided "visible" conditions?  If you set this to false, you will need to
     * explicitly provide the desired "hidden" conditions.
     * @default true
     */
    toggleVisibilityDefaultsToHidden: true,
    
    /**
     * How long are ajax requests allowed to run before they are timed out?
     * Especially useful for ajax requests used as part of a form submission.
     * @default 30
     */
    ajaxTimeout: 30,
    
    /**
     * When an ajax request is sent as part of a form submit validation routine 
     * and it times out, should the form submit by default?
     * This defaults to true to help guard against programming errors blocking
     * users from being able to submit a form due to a broken ajax validation.
     * @default true
     */
    submitFormOnExpiredAjax: true,
    
    /**
     * Should ajax lookups be cached?  If set to true, any ajax calls for the same value
     * on the same form field will use the cached results from the earlier lookup.
     * @default true
     */
    cacheAjaxLookups: true,
    
    /**
    * Should the HTML document be scanned for validanguage comment tags?
    * Set this to false if you arent using the comment API for better performance.
    * @default  true
    */
    loadCommentAPI: true,
    
    /**
    * Determines the delimeter used in the loadCommentAPI() function to split up each
    * comment into multiple validanguage tags.
    * You probably want to keep this as "\n" to be safe, but if you want to be allowed
    * to use carriage returns inside validanguage comment tags, you can set this to
    * "/>" if you are careful to always close your validanguage tags
    * @default  "\n"
    */
    commentDelimiter: "\n",
    
    /**
    * Color for the textbox to flash when invalid input is entered. The default is light red.
    * Set this to empty to turn flashing off.
    * @default  '#FF6666'
    */
   // validationErrorColor: '#FF6666',
 //   validationErrorColor: 'transparent url("../shared/imagebank/tools20/bg.jpg") no-repeat',
 	validationErrorColor: '#FF0000',
    /**
    * If a field has already failed validation and it fails again, should the new
    * failure trigger the onerror handlers? Set this to false to prevent a given
    * field's onkeydown validation from retriggering with every keystroke. If
    * retriggerErrors is set to false and a different validation function fails,
    * ie, the error message should change from one error to another, the error WILL
    * be triggered.
    * @default  true
    */
    retriggerErrors: true,
    
    /**
    * Normal color of the textbox. The default is empty. Used in conjunction with validationErrorColor
    * to make the textboxes flash.
    * @default  ''
    */
    normalTextboxColor: '',
    
    /**
    * Amount of time the text box flashes the validationErrorColor. The default is 100ms
    * @default  100
    */
    timeDelay: 100,
    
    /**
    * Typing delay for the ontyping event. This is the amount of time between keystrokes
    * that must elapse before the event fires.  The default is just over 1 second.
    * @default  1100
    */
    typingDelay: 1100,
    
    /**
    * Should the validateRequiredAlternatives function be assigned onclick to radio buttons
    * and checkboxes named as "requiredAlternatives"?  Setting this to true ensures that
    * checking/unchecking a radio button or checkbox will correctly call showError/hideError.
    * @default  true
    */
    validateRequiredAlternativesOnclick: true,
    
    /**
    * Defines the default behavior of the validateRegex function.
    * Is a match against the regex an error or a success?
    * @default  false
    */
    errorOnMatch: false,
    
    /**
    * Override this to setup a function to run after all validanguage form fields have
    * been intialized inside the populate() function.  The default is an empty function.
    * @default  function() { }
    */
    onload: function() { },
    
    //dummy field I put here so the onload above will have a comma after it
    foo: ''
},

//PRIVATE PROGRAM VARIABLES
ajaxLookup:             {},   //hash table to store details on dispatched ajax requests
alertCounter:           true, //this counter prevents infinite loops from being created between alerts() and onblur handlers
debug:                  false, //enable debugging msgs in Ajax code?
el:                     {},
fields:                 {},
forcedSubmission:       false, //enables a form submission to bypass all validation
forms:                  {},
ignoreTheseKeyCodes:    [8,37,38,39,40,46], //keycodes that are always permitted during keypress suppression
requiredAlternatives:   [],  //hash table used to store requiredAlternatives associations
supportedEvents:        ['blur','change','keypress','keyup','keydown','submit','click','typing','focus'],
supportedEventHandlers: ['onblur','onchange','onkeypress','onkeyup','onkeydown','onsubmit','onclick','ontyping','onfocus'],
typingDelay:            [],  //hash table to store ontyping timeouts
vdLoaded:               false, //changed to true after populate() has completed

/**
 * Omnipresent dollar function. Converts an ID to a domNode
 * @param {String|DomNode} DomNode or its ID
 */
$: function(input) {
    if (typeof input=='string') input = document.getElementById(input);
    return input;
}, //close $

/**
* Generic cross-browser addEvent() function.
* 
* @param {Object} Object to receive the event
* @param {Object} Event type
* @param {Object} Function to be called
*/
addEvent: function(obj, event, func){
    if (obj.addEventListener) {
        obj.addEventListener(event, func, false);
        return true;
    } else if (obj.attachEvent){
        var newEvent = obj.attachEvent("on"+event, func);
        return newEvent;
    }
}, //close addEvent

/**
* Reassigns the validanguage.addEvent function, if an external library is being used.
*/
addEventInit: function() {
    // overwrite validanguage.addEvent()
    switch ( this.useLibrary ) {
        case 'prototype':
        case 'scriptaculous':
            this.addEvent = function(obj, evtHandler, func) {
                Event.observe(obj, evtHandler, func);
            }
            break;
        case 'dojo':
            this.addEvent = function(obj, evtHandler, func) {
                dojo.connect(obj, 'on'+evtHandler, func);
            }
            break;
        case 'jquery':
            this.addEvent = function(obj, evtHandler, func) {
                if (obj == window) {
                    jQuery(document).ready(func);
                } else {
                    var selector = '#' + obj.id;
                    jQuery(selector).bind(evtHandler, func);
                }
            }
            break;      
    }
}, //close addEventInit

/**
* This function wraps multiple validanguage.el.elemId.validations event handlers
* and transformations within a single wrapper to call all loaded validations/transformations
* and exit as soon as a validation returns false.
* 
* @param {Object} Form element object
* @param {string} eventType, such as "blur" or "keydown"
* @param {integer} validationsCounter, denotes the array index of this item in 
*                       validanguage.el.elemId.validations
*/
addOrCreateValidationWrapper: function( Obj, eventType, validationsCounter ) {
    var id = Obj.id;
    if (eventType == 'submit') {
        if (this.empty(validationsCounter)) return; // exit early for onsubmit transformations
        var formId = validanguage.getFormId(id);
        if (typeof formId == 'number') {
            var form = document.forms[formId];
        } else {
            var form = this.$(formId);
        }
        if (typeof validanguage.forms[formId].validations == 'undefined') {
            validanguage.forms[formId].validations = [];
            this.addEvent(form, eventType, function(e) {
                var evt = e || window.evt;
                var result = validanguage.validationWrapper(e);
                if (result == false) {
                    evt.returnValue = false; //IE
                    if (evt.preventDefault) evt.preventDefault(); //Everyone else
                    return false;
                } else {
                    return true;
                }
            });
        }
        //add the element and validationsCounter to the list of onsubmit validations for the parent form
        validanguage.forms[formId].validations[validanguage.forms[formId].validations.length] = { element: Obj, validationsCounter: validationsCounter };
    } else {

        if( typeof validanguage.el[id].handlers == 'undefined' ) validanguage.el[id].handlers = {};
        if( typeof validanguage.el[id].handlers[eventType] == 'undefined' ) {
            validanguage.el[id].handlers[eventType] = [];
            if( eventType == 'typing') {
                this.addEvent(Obj, 'keyup', function(e){ validanguage.validationWrapper(e, 'typingTimeout'); });               
            } else {
                this.addEvent(Obj, eventType, function(e){ validanguage.validationWrapper(e); });               
            }
        }
        //add validationsCounter to the list of validations for this object/eventType combo
        validanguage.el[id].handlers[eventType][validanguage.el[id].handlers[eventType].length] = validationsCounter;
    }
},  //close addOrCreateValidationWrapper

/**
* This function is used to either load a new validation for a form field, or to
* reactivate a validation previously removed with the removeValidation() method.
* 
* NOTE: When adding a new validation, you will need to have previously inserted
* all the relevant details about the validation in the validanguage.el.formField
* object.
* 
* @param {String} elemId
* @param {String/Array} eventTypes
* @param {String/Array/Function} validationNames
*/
addValidation: function ( elemId, eventTypes, validationNames ) {
    if( typeof validationNames[0]=='undefined' ) validationNames = [ validationNames ];
    if( typeof eventTypes=='string' ) eventTypes = [ eventTypes ];

    var vals = this.el[elemId].validations;
    for (var i = vals.length - 1; i > -1; i--) {
        if ( validationNames[0] == '*' || this.inArray(vals[i].name, validationNames) ) {
            for( var j=eventTypes.length-1; j>-1; j--) {
                this.addOrCreateValidationWrapper(this.$(elemId), eventTypes[j]);
            }
        }
    }
}, //close addValidation

/**
* Very simple AJAX function
* @param {String} url
* @param {Function} callback
*/
ajax: function( url, callback ) {
    if(window.ActiveXObject){      
        var ajaxObj = new ActiveXObject("Microsoft.XMLHTTP");      
    } else if(window.XMLHttpRequest){      
        var ajaxObj = new XMLHttpRequest();      
    }
    
    ajaxObj.open("POST", url, true);
    ajaxObj.onreadystatechange = function() {
        if(ajaxObj.readyState==4) {
            callback(ajaxObj.responseText);
        }
    };
    ajaxObj.send(null);
}, //close ajax

/**
* Initializes validanguage.ajax as browser-specific
*/
ajaxInit: function() {
    switch ( this.useLibrary ) {
        //reassign validanguage.ajax
        case 'prototype':
        case 'scriptaculous':
            this.ajax = function(url, callback, options) {
                if (validanguage.empty(options)) options = {};
                options.onSuccess = callback;
                new Ajax.Request(url, options);
            }
            break;
        case 'dojo':
            this.ajax = function(url, callback, options) {
                if (validanguage.empty(options)) options = {};
                options.url = url;
                options.handle = callback;
                dojo.xhrGet(options);
            }
            break;
        case 'jquery':
            this.ajax = function(url, callback, options) {
                if (validanguage.empty(options)) options = {};
                options.url = url;
                options.success = callback;
                jQuery.ajax(options);
            }
            break;
    }
},

/**
 * This function is called by setInterval and is used to check
 * whether or not all ajax callbacks for a form have returned.
 * @param {String} ID or index of the form
 * @param {String} Event Type
 */
ajaxValidationWrapper: function( form, eventType ) {
    var nodeType = (this.getFormId(form)==null) ? 'forms' : 'fields';
    if (this.empty(validanguage[nodeType][form][eventType].dispatchedAjax)) {
        window.clearInterval(validanguage[nodeType][form][eventType].ajaxInterval);
    } else {
        for (var id in validanguage[nodeType][form][eventType].dispatchedAjax) {
            if (typeof time == 'function') continue;
            if (validanguage[nodeType][form][eventType].dispatchedAjax[id] + (validanguage.settings.ajaxTimeout*1000) < new Date().getTime()) {
                //abort requests older than X seconds old
                if (this.debug) console.log('Aborting request...');
                delete this[nodeType][form][eventType].failedValidations[id];
                if (this.empty(this[nodeType][form][eventType].failedValidations)) this[nodeType][form][eventType].failedValidations = 'callManually';
                delete this[nodeType][form][eventType].dispatchedAjax;
                if (nodeType=='forms' && this.validateForm(form).result === true) {
                    if (this.debug) console.log('Request Aborted.');
                    if (this.settings.submitFormOnExpiredAjax) {
                        this.forcedSubmission = true;
                        this.$(form).submit();
                    }
                }
                // If a non-"Form Submit" ajax request is aborted, this is currently handled by doing nothing...
                return;
            }
        }
    }
}, //close ajaxValidationWrapper

/**
* This function loads all the validanguage.toggle() rules which
* are defined for a form following document.onload()
*/
callToggleTransformationsOnload: function() {
    if (this.settings.callToggleTransformationsOnload) {
        for (var id in this.el) {
            if (typeof this.el[id].transformations != 'undefined') {
                for (var i = this.el[id].transformations.length - 1; i > -1; i--) {
                    if (typeof this.el[id].transformations[i].name == 'undefined') 
                        continue;
                    var funcString = this.el[id].transformations[i].name;
                    if (typeof funcString == 'string' && funcString.indexOf('validanguage.toggle') > -1) {
                        var transformations = this.resolveArray(funcString, 'function');
                        var j = transformations.length;
                        for (var k = 0; k < j; k++) {
                            transformations[k].call(this.$(id));
                        }
                    }
                }
            }
        }
    }    
},

/**
* Combines 2 node lists into 1
* @param {Object} obj1
* @param {Object} obj2
*/
concatCollection: function(obj1,obj2) {
    var i;
    var arr = new Array();
    var len1 = obj1.length;
    var len2 = obj2.length;
    for (i=0; i<len1; i++) {
        arr.push(obj1[i]);
    }
    for (i=0; i<len2; i++) {
        arr.push(obj2[i]);
    }
    return arr;
},

/**
 * Determines whether the passed domNode is contained
 * within the passed parentNode. Useful for telling if
 * a form field belongs to a given form or DIV. * 
 * @param {Object|String} node or ID
 * @param {Object|String} node or ID
 */
contains: function (needle, _parentNode) {
    needle = this.$(needle);
    _parentNode = this.$(_parentNode);
    while (needle && _parentNode != needle) {
        needle = needle.parentNode;
    }
    return needle == _parentNode;
}, //close contains

/**
* Emulates PHP's empty() function. For convenience, you can specify whether
* boolean false is considered empty. Defaults to false is NOT empty.
* Ignores functions.
* 
* @param {Object} testVar
* @param {bool} falseIsEmpty
*/
empty: function ( testVar, falseIsEmpty ) {
    if (testVar == null || testVar == undefined || testVar == NaN || testVar === 'null' || (testVar =='' && typeof testVar == 'string') ) return true;
    if (falseIsEmpty==true && testVar==false) {
         return true;
    }
    if (typeof testVar == 'object') {
        for (var i in testVar) {
            if( typeof testVar[i] == 'function' ) continue;
            
            // Prevent infinite recursion in Safari 4
            var recurse = true;
            for (var j in testVar[i]) {
                if (testVar[i][j] === testVar) recurse = false;
            }
            
            if(recurse && validanguage.empty(testVar[i], falseIsEmpty)==false ) {
                return false; 
            }
        }
        return true;
    } else {
       return false;
    }
},

/**
* This is a preset transformation which is used to reformat text input
* to match a desired pattern
* @param {String} Pattern using x to represent alphanumeric characters.
*                 For example:  "(xxx) xxx-xxxx"
* @param {String} String listing any characters to be removed from the
*                 form field's value prior to potential reformatting.
*                 INCLUDE ALL the delimiters used in "pattern"
*                 For example:  "()- "
* @param {String/Regex} Regular expression which, if provided, will be used
*                  to determine whether or not to proceed with reformatting.
*                  If not provided, the function will only reformat if the number
*                  of characters in the form field (after stripThese is applied)
*                  matches the number of x's in the provided pattern
*/
format: function( pattern, stripThese, regexMatch ) {
    var text = this.value;
    var origText = text;
    
    if(stripThese!=null && typeof stripThese=='string') {
       var i = stripThese.length;
       for( var i=stripThese.length-1; i>-1; i-- ) {
          while (text.indexOf(stripThese.charAt(i)) != -1) {
             text = text.replace(stripThese.charAt(i),'','g');               
          }            
       }
    }
    
    if (this.priorStrippedValue && (this.priorStrippedValue == text)) {
        // exit early if they hit backspace key to delete a delimeter
        return;
    }
    
    // Save the value in DOM for later
    this.priorStrippedValue = text;
    if (regexMatch!=null) {         
       var myreg = (typeof regexMatch=='string') ? new RegExp(regexMatch) : regexMatch;
       var thisMatch = myreg.exec(text);
       if (thisMatch == null) return; //exit early for no match
    } else {
       //check for required length based on number of x's in the pattern
       var countMe = pattern.replace(/[^x]/g,'');
       if( text.length != countMe.length ) return;
    }
    
    // Store the caret position
    var pos = validanguage.getCaretPos(this);
    
    var i = pattern.length;
    var textLength = text.length;
    var k = -1; //counter for text
    var newtext = '';
    var numEaten = 0;
    // We iterate thru the length of the pattern,
    // but we exit early once the X's have been exhausted.
    for (var j = 0; j < i; j++) {
        if (pattern.charAt(j) == 'x') {
            numEaten++;
            if (numEaten > textLength) break;
        }
        newtext += (pattern.charAt(j) == 'x') ? text.charAt(++k) : pattern.charAt(j);
    }
    
    // Don't change anything unless we need to
    if (newtext != origText) {
        this.value = newtext;
        
        // Adjust caret pos if the text and text length changed
        if (pos == origText.length) 
            pos = newtext.length;
        
        // Restore the caret position
        validanguage.setCaretPos(this, pos);
    }  
}, //close format

/**
 * This function iterates thru the ajaxLookup array and returns the
 * index number corresponding to the passed counter value
 * @param {String} element ID
 * @param {Object} counter
 */
getAjaxLookupIndex: function(id, counter) {
    for( var i=this.ajaxLookup[id].length-1; i>-1; i--) {
        if (this.ajaxLookup[id][i].counter==counter) return i;
    }
    return 0;
}, //close getAjaxLookupIndex

/**
 * Gets the current caret position on an object
 * @param {Object} obj
 */
getCaretPos: function(obj) {
    if (obj.createTextRange && this.browser!='opera') {
        // IE
        if (obj.nodeName.toLowerCase() == 'input') {
            var range = document.selection.createRange().duplicate();
            range.moveEnd('character', obj.value.length);
            if (range.text == '') 
                return obj.value.length;
            return obj.value.lastIndexOf(range.text);
        } else {
            // Code below from http://linebyline.blogspot.com/2006/11/textarea-cursor-position-in-internet.html
            // Unfortnately, it doesn't seem to work consistently for multi-line textareas,
            // so I commented it out for the moment. Maybe I'll try and fix it one day.
            /*
            var selection_range = document.selection.createRange().duplicate();
            
            var before_range = document.body.createTextRange();
            before_range.moveToElementText(obj); // Selects all the text
            before_range.setEndPoint("EndToStart", selection_range); // Moves the end where we need it
            var before_finished = false;
            var before_text, untrimmed_before_text;
            
            // Load the text values we need to compare
            before_text = untrimmed_before_text = before_range.text;
            
            // Check each range for trimmed newlines by shrinking the range by 1 character and seeing
            // if the text property has changed.  If it has not changed then we know that IE has trimmed
            // a \r\n from the end.
            do {
                if (!before_finished) {
                    if (before_range.compareEndPoints("StartToEnd", before_range) == 0) {
                        before_finished = true;
                    }
                    else {
                        before_range.moveEnd("character", -1)
                        if (before_range.text == before_text) {
                            untrimmed_before_text += "\r\n";
                        }
                        else {
                            before_finished = true;
                        }
                    }
                }
                
            }
            while (!before_finished);
            return untrimmed_before_text.length;
            */
            return 0;
        }
    } else {
        // Moz
        return obj.selectionStart;
    }
}, //close getCaretPos

/**
* Fetches all comment nodes in the passed form node and returns them in a node list
* Doesnt work in konqueror, since konqueror strips all comments from the DOM
* 
* @param {Containing Node} el
*/
getComments: function(el) {
    if (!el) el = document.documentElement;
    var comments = new Array();
    var length = (el.childNodes) ? el.childNodes.length : 0;
    for (var c = 0; c < length; c++) {
        if (el.childNodes[c].nodeType == 8) {
            comments[comments.length] = el.childNodes[c];
        } else if (el.childNodes[c].nodeType == 1) {
            comments = comments.concat(this.getComments(el.childNodes[c]));
        }
    }
    return comments;
}, //close getComments

/**
* Helper function used by validateDate() and validateTimestamp().
* @param {Object} options object provided by the user to validateDate() or validateTimestamp().
* @param {Object} defaults which should be used.  Used to allow validateDate() and validateTimestamp()
* to have different default dateOrder values.
*/
getDateTimeDefaultOptions: function ( options, defaults ) {
    if( options==null ) options = {};
    
    // Date options
    if( typeof options.dateOrder=='undefined' ) options.dateOrder=defaults.dateOrder;
    options.dateOrder = options.dateOrder.toLowerCase();
    if( typeof options.allowedDelimiters=='undefined' || typeof options.allowedDelimiters!='string' ) options['allowedDelimiters'] = './-';
    if( typeof options.twoDigitYearsAllowed=='undefined' ) options.twoDigitYearsAllowed = false;
    if( typeof options.oneDigitDaysAndMonthsAllowed=='undefined' ) options.oneDigitDaysAndMonthsAllowed = true;
    if( typeof options.maxYear=='undefined' ) options.maxYear = new Date().getFullYear() + 15;
    if( typeof options.minYear=='undefined' ) options.minYear = 1900;
    if( typeof options.rejectDatesInTheFuture=='undefined' ) options.rejectDatesInTheFuture = false;
    if( typeof options.rejectDatesInThePast=='undefined' ) options.rejectDatesInThePast = false;
    
    // Time options
    if( typeof options.timeIsRequired=='undefined' ) options.timeIsRequired = false;
    if( typeof options.timeUnits=='undefined' ) options.timeUnits = 'hms';
    if( typeof options.microsecondPrecision=='undefined' ) options.microsecondPrecision = 6;
    return options;
}, //close getDateTimeDefaultOptions

/**
* This function checks for a given setting in increasing specificity
* within the validanguage.forms[formId].settings object, and within the passed
* validanguage.el objects
* 
* @param {string} Name of the setting to be retrieved
* @param {string} ID of the form field object being validated
* @param {Object} validanguage.el.objId.validations[index] object
*/
getElSetting: function( setting, id, validationObj ) {
    var formSetting = this.getFormSettings(id);
    var retVal = formSetting[setting]; //global setting
    if( typeof validationObj!='undefined' && typeof validationObj[setting] != 'undefined' ) {
        retVal = validationObj[setting];   
    } else if( typeof this.el[id][setting] != 'undefined' ) {
        retVal = this.el[id][setting];
    }
    return retVal;
},

/**
* This function returns the validanguage.form[formId].setting object for the passed element ID
* @param  {string or Node}  id of the input field or input node
* @return {Object}  settings object
*/
getFormSettings: function(id) {
    var formName = ( this.$(id).nodeName.toLowerCase()=='form' ) ? 
        id : this.getFormId(id);
    return this.forms[formName].settings;
},

/**
 * This function returns the Id of the parent Form for an element
 * @param {String|DomNode} Form node or its ID
 */
getFormId: function(formField) {
    if (this.$(formField).form) {
        return this.$(formField).form.getAttribute("id");
    } else {
        return null;
    }
},

/**
* This function parses the passed comment to retrieve the indicated setting
* 
* @param  {String}  Name of the setting to retrieve / needle
* @param  {String}  Full text of the HTML comment   / haystack
* @return {String}  The value of the requested setting
*/
getSettingFromComment: function( setting, comment ) {  
    var needle = ' '+setting+'='; 
    var startPos = comment.indexOf(needle);
    if( startPos == -1) return null;
    var delimiterPos = (startPos*1) + (needle.length*1);
    var delimeter = '\\' + comment.charAt(delimiterPos);
    var Regex = needle+delimeter+'(.+?)'+delimeter;
    var myreg = new RegExp(Regex);
    var thisMatch = myreg.exec(comment, 'gi');
    if (thisMatch == null) {
        return null; //no match
    } else if (thisMatch[1]) {
        //Convert booleans. I hope this doesnt screw anyone later....
        if(thisMatch[1]=='true') thisMatch[1]=true;
        if(thisMatch[1]=='false') thisMatch[1]=false;
        return thisMatch[1];
    }
}, //close getSettingFromComment

/**
* This function hides the div containing the validanguage error messages for
* failed validations
*/
hideError: function() {
    var settings = validanguage.getFormSettings(this.id);
    var errorDisplay = document.getElementById(this.id + settings.errorMsgSpanSuffix);
    if (errorDisplay != null) {
        errorDisplay.innerHTML = '';
        var errorDiv = errorDisplay.parentNode;
    
        errorDiv.style.display = 'none';
        errorDiv.className = settings.noErrorClassName;
    }
    if (! this.className.match(validanguage.settings.passedFieldClassName)) this.className += ' '+validanguage.settings.passedFieldClassName;
    if (this.className.match(validanguage.settings.failedFieldClassName)) this.className = this.className.replace(validanguage.settings.failedFieldClassName,'');
    
    //Do we need to remove any vd_li items?
    if( !settings.showFailedFields ) return;
    if( document.getElementById(this.id + settings.errorListItemSuffix) != null ) {
        var errorList = document.getElementById(settings.errorListId);
        errorList.removeChild( document.getElementById(this.id + settings.errorListItemSuffix) );
        if( errorList.getElementsByTagName('LI').length==0 )
            document.getElementById(settings.errorDivId).style.display='none';
    }
}, //close hideError

/**
* Determines whether the passed item is present in the array or object.
* 
* @param {Object} needle
* @param {Object} haystack
*/
inArray: function( needle, haystack ) {
    for( var i=haystack.length-1; i>-1; i-- ){
        if( haystack[i]===needle ) return true;
    }
    return false;
},

/**
* This function searches settingsHaystack for all variables defined in the settingsNeedles
* array, and if they are located, they are copied over to the settingsTarget
* 
* @param {Object} settingsHaystack -- Object location to be searched for settings
* @param {Array}  settingsNeedles  -- Array of settings to be checked
* @param {Object} settingsTarget   -- Object location where any defined settings should be copied to
* @param {String} constrainType    -- Optional type constraint
*/
inheritIfDefined: function ( settingsHaystack, settingsNeedles, settingsTarget, constrainType ) {
    if( typeof settingsNeedles.length == 'undefined' ) return false;
    for( var i=settingsNeedles.length-1;i>-1;i--) {
        if ( typeof settingsHaystack[settingsNeedles[i]]!='undefined' &&
           ( this.empty(constrainType) || typeof settingsHaystack[settingsNeedles[i]]==constrainType )
        ) {
            settingsTarget[settingsNeedles[i]] = settingsHaystack[settingsNeedles[i]];
        }
    }
},

/**
* Initialization function for validanguage. Adds the onload hook
* which fires off the populate() method to add all the other event
* handlers
*/
init: function() {
    if (typeof validanguageLibrary!='undefined') this.useLibrary = validanguageLibrary;
    this.addEventInit();
    this.ajaxInit();
    this.addEvent(window, 'load', function() {
        validanguage.populate.call(validanguage);
    });
},

/**
* Function to insert 1 Node after another in the DOM. If the referenceNode
* is a label, this function will use the nextSibling instead
* 
* @param {Node} nodeToAdd
* @param {Node} referenceNode
*/
insertAfter: function (nodeToAdd, referenceNode ) {
    if (referenceNode.nextSibling) {
        if (referenceNode.nextSibling.nodeName.toLowerCase() == 'label') {
            referenceNode.parentNode.insertBefore(nodeToAdd, referenceNode.nextSibling.nextSibling);
        } else {
           referenceNode.parentNode.insertBefore(nodeToAdd, referenceNode.nextSibling);
        }
    } else {
        referenceNode.parentNode.appendChild(nodeToAdd);
    }
}, //close insertAfter

/**
 * This function examines the ajaxLookup array to determine whether or not the
 * specified ajaxCounter pertains to the most recent ajax call for that form field.
 * @param {String} formFieldId
 * @param {Integer} ajaxCounter
 */
isExpiredAjax: function (formFieldId, ajaxCounter) {
    if (this.empty(formFieldId) || this.empty(ajaxCounter)) return false;
    var h     = this.getAjaxLookupIndex(formFieldId, ajaxCounter);
    var arr   = this.ajaxLookup[formFieldId];
    var event = arr[h].eventType;
    
    for (var i = arr.length-1; i > 0; i--) {
        if (event == arr[i].eventType) {
            if (arr[i].counter == ajaxCounter) {
                return false;
            } else {
                return true;
            }
        }
    }
    return false;
}, //close isExpiredAjax

/**
* This function parses all comments in the current document, looking for
* the comment-based API and converts any validanguage statements it
* finds into the element/json-based API for further processing.
* 
* @param  {Object} Dom Node containing comments to be loaded
* @param  {Array}  For konqueror, we pass this function an Array with all
*                  the comments (retrieved via AJAX)
*                  For all other browsers, konquerorComments is undefined and
*                  we retrieve the comments normally via the DOM
*/
loadCommentAPI: function( domNode, konquerorComments ) {
    domNode = this.$(domNode);
    
    var supportedSettings = ['mode','expression','suppress','onsubmit','onblur','onchange',
        'onkeypress','onkeyup','onkeydown','onclick', 'ontyping','onfocus',
        'errorMsg','onerror','onsuccess','focusOnError',
        'showAlert','required','requiredAlternatives',
        'maxlength','minlength','regex','field',
        'errorOnMatch','modifiers','transformations','validations'];

    var allComments = (this.empty(konquerorComments)) ? this.getComments(domNode) : konquerorComments;
    var length = allComments.length;
    for (var j=0; j<length; j++) {

    var singleComment = (this.empty(konquerorComments)) ? allComments[j].nodeValue : allComments[j];
    var tagArray = singleComment.split(validanguage.settings.commentDelimiter); 
    var tagArrayLength = tagArray.length;

    for (var a=0; a<tagArrayLength; a++) {
        var commentText = tagArray[a];
        commentText = commentText.replace(/\n/g,' ');
        commentText = commentText.replace(/\r/g,' ');
        var isValidanguageRegEx = /<validanguage/i;
        if (isValidanguageRegEx.test(commentText)) {
            //get the targets
            var targets = this.getSettingFromComment('target', commentText);
            var settings = []; //reset settings
            if (this.empty(targets, true)) 
                continue;
            targets = this.resolveArray(targets, 'string');
            for (var k = supportedSettings.length - 1; k > -1; k--) {
                var tempSetting = this.getSettingFromComment(supportedSettings[k], commentText);
                if (!(tempSetting == null || (typeof tempSetting == 'string' && tempSetting == '') ))
                    settings[supportedSettings[k]] = tempSetting;
            }

            //iterate thru our targets and assign the settings
            k = targets.length;
            for (var l = 0; l < k; l++) {
                var id = targets[l];
                var obj = this.$(id);
                if (typeof this.el[id] == 'undefined' || obj == null) 
                    this.el[id] = {};

                /** CHARACTER VALIDATION **/
                //start keypressValidation
                if (typeof settings.expression != 'undefined') {
                    this.el[id].characters = {};
                    this.inheritIfDefined(settings, ['expression','errorMsg','mode','suppress','validateCharacters','onerror','onsuccess'], this.el[id].characters);
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].characters);
                }
                //close keypressValidation

                /** REGEX **/
                if (typeof settings.regex != 'undefined') {
                    this.el[id].regex = { expression: settings.regex };
                    this.inheritIfDefined(settings, ['errorOnMatch','modifiers'], this.el[id].regex);
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].regex);
                }

                /** MISC SETTINGS **/
                // Only inherit event handlers that are non-boolean transformations
                this.inheritIfDefined(settings, ['field'], this.el[id], 'string');
                this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id], 'string');
                this.inheritIfDefined(settings, ['minlength','maxlength','requiredAlternatives','required','focusOnError','showAlert',
                    'onsuccess','onerror','errorMsg'], this.el[id]);                  
                if (typeof settings.minlength != 'undefined') {
                    this.el[id].minlengthEvents = {};
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].minlengthEvents);
                }
                if (typeof settings.maxlength != 'undefined') {
                    this.el[id].maxlengthEvents = {};
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].maxlengthEvents);
                }
                if (typeof settings.required != 'undefined') {
                    this.el[id].requiredEvents = {};
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].requiredEvents);
                }

                /** VALIDATIONS AND TRANSFORMATIONS **/
                if (typeof this.el[id].validations == 'undefined') this.el[id].validations = [];
                if (typeof this.el[id].transformations == 'undefined') this.el[id].transformations = [];
                var functionModifiers = ['focusOnError','showAlert','onsuccess','onerror','errorMsg','isAjax'];

                //Load validations
                if( typeof settings.validations != 'undefined' && !this.empty(settings.validations) ) {
                    this.el[id].validations[this.el[id].validations.length] = {};
                    this.el[id].validations[this.el[id].validations.length-1].name = settings.validations;
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].validations[this.el[id].validations.length-1]);
                    this.inheritIfDefined(settings, functionModifiers, this.el[id].validations[this.el[id].validations.length-1]);
                }
                //Load transformations
                if( typeof settings.transformations != 'undefined' && !this.empty(settings.transformations) ) {
                    this.el[id].transformations[this.el[id].transformations.length] = {};
                    this.el[id].transformations[this.el[id].transformations.length-1].name = settings.transformations;
                    this.inheritIfDefined(settings, this.supportedEventHandlers, this.el[id].transformations[this.el[id].transformations.length-1]);
                }

                } // foreach (targets) 
            } // close if(validanguage_comment)
        } // close tagArray loop
    } // close allComments loop         
}, //close loadCommentAPI

/**
 * This function parses the validanguage.el object to load all the
 * form-element-specific validation settings which the end user has defined
 * via the Object-based API
 * 
 * @param {String|Object} (Optional) If provided, this is either a DOM node
 *    or a node's ID. Providing a DOM node will limit the function
 *    to loading validations for only elements contained within that node,
 *    or if the node is a form field itself, limit to only that field.
 */
loadElAPI: function( _parentNode ) {
    if (_parentNode != null) _parentNode = this.$(_parentNode);
    
    for( var elem in this.el ) {  //for each element....
    
        // Skip to the next if it's not an element ID
        try { if( typeof this.$(elem) == undefined || this.empty(this.$(elem)) ) continue; } catch(e) { continue; }
        
        if (_parentNode != null && _parentNode.getAttribute("id") != elem) {
            // Skip this item if its not a descendant of _parentNode
            if (!this.contains(elem, _parentNode)) continue;
        }
        var Obj = this.$(elem);
        var settings = validanguage.getFormSettings(elem);
        if (typeof this.el[elem].validations == 'undefined') this.el[elem].validations = [];
        if (typeof this.el[elem].field == 'undefined') this.el[elem].field = elem;

        /** REQUIRED **/         
        if (typeof this.el[elem].required != 'undefined' && this.el[elem].required==true) {
            this.el[elem].validations[this.el[elem].validations.length] = {};
            this.el[elem].validations[this.el[elem].validations.length-1].name = 'validanguage.validateRequired';
            this.el[elem].validations[this.el[elem].validations.length-1].errorMsg = (typeof this.el[elem].errorMsg=='undefined') ? settings.requiredErrorMsg : this.el[elem].errorMsg;
            this.inheritIfDefined( this.el[elem], this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length-1] );
    
            //If specific requiredEvents are provided, use those instead of the element level event handlers
            if( typeof this.el[elem]['requiredEvents']!='undefined') this.inheritIfDefined( this.el[elem]['requiredEvents'], this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length-1] );
    
            //We need to call the validateRequiredAlternatives function when a requiredAlternative is clicked
            if(settings.validateRequiredAlternativesOnclick==true && typeof this.el[elem].requiredAlternatives != 'undefined' ) {
                var onsuccessFuncs = (typeof this.el[elem].onsuccess!='undefined') ? this.el[elem].onsuccess : settings.onsuccess;
                var onerrorFuncs = (typeof this.el[elem].onerror!='undefined') ? this.el[elem].onerror : settings.onerror;
                var alts = this.resolveArray(this.el[elem].requiredAlternatives,'string');
                for( var y=alts.length-1; y>-1; y--) {
                    this.requiredAlternatives[alts[y]] = {};
                    if( !((typeof this.$(alts[y]).type != 'undefined') && (this.$(alts[y]).type=='checkbox'||this.$(alts[y]).type=='radio')) ) continue;
                    this.requiredAlternatives[alts[y]].onsuccess = onsuccessFuncs;
                    this.requiredAlternatives[alts[y]].onerror = onerrorFuncs;
                    this.requiredAlternatives[alts[y]].errorMsg = (typeof this.el[elem].errorMsg=='undefined') ? settings.requiredErrorMsg : this.el[elem].errorMsg;
                    this.requiredAlternatives[alts[y]].parentId = elem;
                    this.addEvent( this.$(alts[y]), 'click', function(e) { validanguage.validateRequiredAlternatives(e); } );
                }
            }
        }

        /** REGEX **/
        if (typeof this.el[elem].regex != 'undefined') {
            this.el[elem].validations[this.el[elem].validations.length] = {};
            this.el[elem].validations[this.el[elem].validations.length - 1].name = 'validanguage.validateRegex';
            var errorMsg = (typeof this.el[elem].errorMsg == 'undefined') ? settings.errorMsg : this.el[elem].errorMsg;
            if(typeof this.el[elem].regex.errorMsg != 'undefined') errorMsg = this.el[elem].regex.errorMsg
               this.el[elem].validations[this.el[elem].validations.length - 1].errorMsg = errorMsg;
            this.inheritIfDefined(this.el[elem], this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length - 1]);
            this.inheritIfDefined(this.el[elem].regex, this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length - 1]);
            if(typeof this.el[elem].regex.errorOnMatch=='undefined') this.el[elem].regex.errorOnMatch=settings.errorOnMatch;
        }

        /** MAXLENGTH **/
        if (typeof this.el[elem].maxlength != 'undefined') {
            this.el[elem].validations[this.el[elem].validations.length] = {};
            this.el[elem].validations[this.el[elem].validations.length-1].name = 'validanguage.validateMaxlength';
            this.el[elem].validations[this.el[elem].validations.length-1].errorMsg = settings.maxlengthErrorMsg.replace('{!maxlength}',this.el[elem].maxlength);
            //If specific maxlengthEvents are provided, use those instead of the element level event handlers
            if( typeof this.el[elem]['maxlengthEvents']!='undefined') this.inheritIfDefined( this.el[elem]['maxlengthEvents'], this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length-1] );
        }

        /** MINLENGTH **/
        if (typeof this.el[elem].minlength != 'undefined') {
            this.el[elem].validations[this.el[elem].validations.length] = {};
            this.el[elem].validations[this.el[elem].validations.length-1].name = 'validanguage.validateMinlength';
            this.el[elem].validations[this.el[elem].validations.length-1].errorMsg = settings.minlengthErrorMsg.replace('{!minlength}',this.el[elem].minlength);
            //If specific minlengthEvents are provided, use those instead of the element level event handlers
            if( typeof this.el[elem]['minlengthEvents']!='undefined') this.inheritIfDefined( this.el[elem]['minlengthEvents'], this.supportedEventHandlers, this.el[elem].validations[this.el[elem].validations.length-1] );
        }

        //start keypressValidation
        /** CHARACTERS **/
        if (typeof this.el[elem].characters != 'undefined' &&
            typeof this.el[elem].characters.mode != 'undefined' &&
            typeof this.el[elem].characters.expression != 'undefined'
        ) {

            //supported shortcuts
            var expression = this.el[elem].characters.expression;
            expression = expression.replace('alphaUpper','ABCDEFGHIJKLMNOPQRSTUVWXYZ/*-+?¡¿!{}[]´&%#°');
            expression = expression.replace('alphaLower','abcdefghijklmnopqrstuvwxyz/*-+?¡¿!{}[]´&%#°');
            expression = expression.replace('alpha','abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/*-+?¡¿!{}[]´&%#°');
            expression = expression.replace('numeric','0123456789');
            expression = expression.replace('special','`~!@#$%^&*()-_=+[{]}\\|;:\'",<.>/?');
            this.el[elem].characters.characterExpression = expression;
            var validanguageExpr = ';';
			
            for (var j=expression.length-1;j>-1;j--){
                validanguageExpr += expression.charCodeAt(j) + ';';
            }

            this.el[elem].characters.expression = validanguageExpr;         
            if(typeof this.el[elem].characters.suppress=='undefined' || this.el[elem].characters.suppress==true) this.addEvent(Obj, "keypress", validanguage.validateKeypress );
            
            // Load validanguage.validateCharacters
            if (typeof this.el[elem].characters.validateCharacters == 'undefined' || this.el[elem].characters.validateCharacters) {
                this.el[elem].validations[this.el[elem].validations.length] = {};
                this.el[elem].validations[this.el[elem].validations.length - 1].name = 'validanguage.validateCharacters';
                this.el[elem].validations[this.el[elem].validations.length - 1].errorMsg = true;
                
                for (var z = this.supportedEventHandlers.length - 1; z > -1; z--) {
                    if (typeof this.el[elem].characters[this.supportedEventHandlers[z]] != 'undefined' && this.el[elem].characters[this.supportedEventHandlers[z]] == true) 
                        this.el[elem].validations[this.el[elem].validations.length - 1][this.supportedEventHandlers[z]] = true;
                }
                
                //assign onerror
                if (typeof this.el[elem].characters.errorMsg != 'undefined') {
                    this.el[elem].validations[this.el[elem].validations.length - 1].errorMsg = this.el[elem].characters.errorMsg;
                } else {
                    this.el[elem].validations[this.el[elem].validations.length - 1].errorMsg = settings.characterValidationErrorMsg;
                }
            }
        }
        //close keypressValidation
        if (typeof this.el[elem].transformations == 'undefined') this.el[elem].transformations = [];

        /** TRANSFORMATIONS **/
        //First, check for transformations listed by event type, such as onblur="foo"
        var j = this.supportedEventHandlers.length;
        for (var k = 0; k < j; k++) {
            var handler = this.supportedEventHandlers[k];
            if( typeof this.el[elem][handler] != 'undefined' && typeof this.el[elem][handler] != 'boolean' ) {
                //Add the defined transformation to the transformations array
                this.el[elem].transformations[this.el[elem].transformations.length] = {};
                var n = this.el[elem].transformations.length - 1;
                this.el[elem].transformations[n].name = this.el[elem][handler];
                //store the event handler
                this.el[elem].transformations[n][handler] = true;
            }
        }
        
        var h = this.el[elem].transformations.length;
        for (var i = 0; i < h; i++) {
            //for each transformation, load the appropriate function in the element's transformations array
            var eventLoaded = false;
            var j = this.supportedEvents.length;
            for (var k = 0; k < j; k++) {
                if(this.supportedEvents[k]=='submit') continue;
                if (typeof this.el[elem].transformations[i]['on' + this.supportedEvents[k]] != 'undefined' && this.el[elem].transformations[i]['on' + this.supportedEvents[k]] == true) {
                    eventLoaded = true;
                    this.addOrCreateValidationWrapper(Obj, this.supportedEvents[k]);
                }
            }
        
            //if they didnt supply any events, we default to the defined defaultTransformationHandlers (usually only "blur")
            if (eventLoaded == false) {
                if (Obj.nodeName.toLowerCase() == 'form') {
                    this.addOrCreateValidationWrapper(Obj, 'submit');
                } else {
                    for (var l = settings.defaultTransformationHandlers.length - 1; l > -1; l--) {
                        this.addOrCreateValidationWrapper(Obj, settings.defaultTransformationHandlers[l], 999);
                    }
                }
            }
        }

        /** VALIDATIONS **/
        if (typeof this.el[elem].validations != 'undefined') {

            // Sort the validations array to ensure that any isAjax function appears last        
            var h = this.el[elem].validations.length-1;
            ajaxLoop:
            for (var i = 0; i < h; i++) {
                if (this.el[elem].validations[i].isAjax) {
                    var copy = this.el[elem].validations[i];
                    this.el[elem].validations.splice(i,1);
                    this.el[elem].validations.push(copy);
                    break ajaxLoop;
                }
            }
            
            var h = this.el[elem].validations.length;
            for (var i = 0; i < h; i++) {
                //for each validation, load the appropriate function in the element's validations array
                var eventLoaded = false;
                var j = this.supportedEvents.length;
                for (var k = 0; k < j; k++) {
                    if (typeof this.el[elem].validations[i]['on' + this.supportedEvents[k]] != 'undefined' && this.el[elem].validations[i]['on' + this.supportedEvents[k]] === true) {
                        eventLoaded = true;
                        this.addOrCreateValidationWrapper(Obj, this.supportedEvents[k], i);
                    }
                }
        
                //if they didnt supply any events, we default to the defined defaultValidationHandlers (usually only "submit")
                if (eventLoaded == false) {
                    for( var l=settings.defaultValidationHandlers.length-1; l>-1;l--) {
                        this.addOrCreateValidationWrapper(Obj, settings.defaultValidationHandlers[l], i);                     
                    }
                }
            }
        }          
    } //close elem loop
},

/**
 * This function loads a form and its settings into the
 * global validanguage object so it can receive validation
 * criteria. If the form does not have an ID, one is assigned.
 * @param {String|Object} form Node or ID of the form
 */
loadForm: function(form) {
    var formName;
    form = this.$(form);
    if (this.empty(form.getAttribute("id"))) {
        formName = 'validanguageForm' + this.formCounter;
        form.id = formName;
    } else {
        formName = form.getAttribute("id");
    }
    this.forms[formName] = { settings: this.settings };
}, //close loadForm

/**
 * This function loads all the validation rules specified within the
 * passed element.  For example, if it is passed a DIV, all the validation
 * rules in that DIV will be loaded. This supports both the comment API
 * and the Object API.  However, if you are using the object API with an
 * AJAX request, you may need to find the script tags within the returned
 * HTML and eval() them prior to calling loadNewFields(). Also, be aware
 * that you can mess up your form's validation by loading rules which have
 * already been loaded once.  Be sure that the DIV or form field which you
 * give to the loadNewFields() function doesn't contain validations which
 * have previously been loaded, or unexpected issues may arise.
 * @param {String|DomNode} containingElement or its ID
 */
loadNewFields: function(containingElement) {
    this.loadCommentAPI(containingElement);
    this.loadElAPI(containingElement);
},

/**
* This function searches the passed subject and returns an Array of strings 
* which are delimited by the characters passed to the function in the 
* first 2 arguments.  Used to pull comments from the document source
* @param  {String} startChar
* @param  {String} endChar
* @param  {String} subject
* @return {Array}
*/
parseSubstring: function( startChar, endChar, subject ) {
    var matches = [];
    var parts = subject.split(startChar);
    for( var i=0; i<parts.length; i++) {
        var endPos = parts[i].indexOf(endChar);
        if( endPos != -1) matches.push( parts[i].substring(0, endPos) );
    }
    return matches;
}, //close parseSubstring

/**
 * Main function to be called onload to load all the validations
 */
populate: function(){
    this.sniffBrowser();
    if( this.browser=='ie5' ) return; //There's no way I'm supporting IE5, so it's safest to just not run validanguage at all
    
    if (typeof console == 'undefined') this.debug=false;
    
    /** 
    *  Iterate thru all the form elements on the page to populate 
    *  the formLookup hash table and load the default settings
    **/
    var forms = document.getElementsByTagName('form');
    for (var i=0, j=forms.length; i<j; i++) {
        this.formCounter = i; // this supports forms with no Ids
        this.loadForm(forms[i]);
    }
    
    if (this.browser == 'konqueror' && this.settings.loadCommentAPI == true) {
        this.ajax(document.location.href, function(docText) {
            //prototype
            if (docText.responseText) docText = docText.responseText;
            var comments = validanguage.parseSubstring( '<!--', '-->', docText );
            validanguage.loadCommentAPI( window.document, comments );
            if (validanguage.overloadFormSettings) validanguage.overloadFormSettings();
            if (validanguage.el && !validanguage.empty(validanguage.el)) {
                validanguage.loadElAPI();
                if (validanguage.callToggleTransformationsOnload) validanguage.callToggleTransformationsOnload();
                //Call any onload handler defined by the user
                validanguage.settings.onload.call(validanguage);                
                validanguage.vdLoaded = true;
            }
        });
    } else {      
        //Load comment API
        if (this.settings.loadCommentAPI == true) this.loadCommentAPI( );
    
        //Load Form-Specific Settings      
        if (this.overloadFormSettings) this.overloadFormSettings();
    
        //Load the validanguage.el API
        if (this.el && !this.empty(this.el)) this.loadElAPI();
        
        // Call any defined validanguage.toggle() functions to true up the UI
        if (this.callToggleTransformationsOnload) this.callToggleTransformationsOnload();
        
        //Call any onload handler defined by the user
        this.settings.onload.call(this);
        
        this.vdLoaded = true;
    }
    //Garbage collection
    this.addEvent(window, 'unload', function() { delete validanguage; });
            
}, //close populate

/**
 * This transformation function updates a div or span
 * with the total number of characters remaining,
 * based on a comparison between the number of characters
 * the user has typed and the defined minLength and maxLength
 * values for the field. See the demo page for an example.
 */
remainingChars: function() {
    var div = validanguage.$(this.id+'_remaining');
    var minLength = validanguage.el[this.id].minlength || 0;
    var maxLength = validanguage.el[this.id].maxlength;
    var length = this.value.length;
    var remainingClass = ((length <= maxLength) && (length >= minLength)) ? 'vdLengthPassed' : 'vdLengthFailed';
    div.innerHTML = '<span class="'+remainingClass+'">' + length + '</span> / ' + maxLength;
}, //close remainingChars

/**
 * This function removes all references to a form and its elements from
 * the global validanguage object
 * @param {String|DomNode} Which form to remove
 */
removeForm: function (formId) {
    if (typeof formId != 'string') formId = formId.getAttribute("id");
    
    // Remove any related form fields from validanguage.el  
    for (var elem in this.el) {
        if (this.contains(elem, formId)) delete this.el[elem];
    }
    delete this.forms[formId];
}, //close removeForm

/**
 * This function will remove all the validations for any
 * form fields contained within the passed containing element.
 * For example, if containingElem is a DIV that has 3 textareas
 * inside it, all the validations for those 3 textareas will be
 * removed. You can also call the function with no argument
 * to remove all the validations on the current page.
 * @param {String|DomNode} Containing element or its ID
 */
removeAllValidations: function (containingElem) {
    if (containingElem==undefined) containingElem = window.document;
    for (var elem in this.el) {
        if (this.contains(elem, containingElem)) this.removeValidation(elem, '*', '*');
    }
}, //close removeAllValidations

/**
* This function is used to deactivate a previously loaded validation.
* Provide the element ID of the field and a list of event types and validation
* names to deactivate. You can use the * character as the eventType and/or
* the validationName arguments to include ALL eventTypes/validationNames.
* 
* NOTE: if you are tempted to use removeValidation('id','*','*'), you may be
* better off using validanguage.el.id.disabled=true, as this is much easier to
* undo later.
* 
* NOTE: It is up to you to make sure no error msgs are displaying before disabling
* a validation.  If one is showing and all validations are disabled, the onsuccess
* handlers used to clear the error msgs will never be called.
* 
* @param {String} elemId
* @param {String/Array} eventTypes
* @param {String/Array/Function} validationNames
*/
removeValidation: function ( elemId, eventTypes, validationNames ) {
    //prep our arguments
    if( eventTypes == '*' ) {
        eventTypes = this.supportedEvents;
    } else if( typeof eventTypes[0]=='undefined') {
        eventTypes = [ eventTypes ];
    }
    if( typeof validationNames=='string' ) validationNames = [ validationNames ];
    for (var j = eventTypes.length - 1; j > -1; j--) {
        if (eventTypes[j] == 'submit') {
            // Remove form.onsubmit validations
            var vals = this.forms[this.getFormId(elemId)].validations;
            formValLoop:
            for (var i = vals.length - 1; i > -1; i--) {
                if( vals[i]==undefined || vals[i].element.getAttribute("id") != elemId ) continue formValLoop;
                if ( validationNames[0] == '*' || this.inArray( this.el[elemId].validations[vals[i].validationsCounter].name, validationNames ) ) {
                    try { delete vals[i]; } catch(e) {}
                }
            }
        } else {
            // Remove field-specific validations
            var vals = this.el[elemId].validations;
            for (var i = vals.length - 1; i > -1; i--) {
                if ( validationNames[0] == '*' || this.inArray(vals[i].name, validationNames) ) {
                    try { delete this.el[elemId].handlers[eventTypes[j]][i]; } catch(e) {}
                }
            }            
        }
    }
}, //close removeValidation

/**
* This function accepts as input a function, a string, an array of
* functions, an array of strings, or a comma-delimited list of functon
* names as its argument and returns an Array of functions or strings 
* comprising the passed arguments. Example:  transforms 'foo, bar'
* to [foo, bar]
* 
* @param {Function or String or Array} args
* @param {String}  returnType should be either 'string' or 'function'
* @return {Array of Functions}
*/
resolveArray: function (args, returnType, ignoreCommas) {
    var returnArray = [];
    if( typeof args == 'object' ) {
        var i=args.length;
        for (var j=0; j<i; j++) {
            returnArray[returnArray.length] = this.resolveArray(args[j],returnType)[0];
        }
        return returnArray;
    }
    if( typeof args == 'function' ) {
        returnArray[0] = args;
        return returnArray;
    }
    if ( typeof args == 'string' ) {
        if(returnType=='string') args = args.replace(' ',''); //dont remove spaces when returning a function
    
        if( args.indexOf(',') == -1 || ignoreCommas==true ) {
            //function name as a string
            if( returnType=='function' ) {
                if( args.indexOf('(') != -1 && args.indexOf('function')==-1 ) {
                    //In order to preserve scope for functions with parameters attached,
                    //we must transform "func1(text,foo)" into "function(text) { return func1.call(this,text,foo) }"
                    var splitAt = args.indexOf('(');
                    var funcName = args.substring(0,splitAt);
                    var params = args.substring(++splitAt,args.length);
                    var args = 'function(text) { return '+funcName+'.call(this,'+params+'}';
                }
                eval("var argsHandle="+args); //easiest way to handle dot notation and framesets
                returnArray[returnArray.length] = argsHandle;
            } else {
                returnArray[returnArray.length] = args;
            }
        } else {
            //comma-delimited list of function names
            var tempArray = this.smartCommaSplit(args);
            var i=tempArray.length;
            if(i==1) {
                //The only commas in the string appear within braces or parens
                returnArray = this.resolveArray(tempArray[0], returnType, true);
            } else {
                for (var j=0; j<i; j++) {
                    returnArray[returnArray.length] = this.resolveArray(tempArray[j],returnType)[0];
                }
            }
        }
        return returnArray;
    }
    return false;      
}, //close resolveArray

/**
 * Sets the caret at a specified position on an object
 * @param {Object} Dom Node
 * @param {Object} Position
 */
setCaretPos: function(obj, pos) { 
    if(obj.createTextRange && this.browser!='opera') {
        // IE
        var range = obj.createTextRange(); 
        range.move('character', pos); 
        range.select(); 
    } else if(obj.selectionStart) { 
        // Moz
        obj.focus(); 
        obj.setSelectionRange(pos, pos); 
    }
}, //close setCaretPos

/**
 * This function is called from an ajax callback to report back
 * to validanguage the status of the ajax validation.
 * @param {Object} id Id of the form field associated with this validation
 * @param {Boolean} returnStatus Whether or not the field is valid
 * @param {String|Integer} type Event Type or (alternately) the ajaxCounter that can be used
 *     to check validanguage.ajaxLookup to determine the eventType
 * @param {String} errorMsg Error message to show for the failed field
 */
setValidationStatus: function( id, returnStatus, type, errorMsg ) {
    if (type == undefined) {
        type = 'submit';
    } else if (!this.inArray(type, this.supportedEvents)) {
        var i = this.getAjaxLookupIndex(id, type);
        type = (this.ajaxLookup[id][i].eventType) ? this.ajaxLookup[id][i].eventType : 'submit';
    }
    var nodeType = (type=='submit') ? 'forms' : 'fields';
    var form = (type=='submit') ? validanguage.getFormId(id) : id;
    
    if (this.debug) {
        console.log('setValidationStatus for '+id+'. Pending requests before deleting:');
        console.dir(this[nodeType][form][type].dispatchedAjax);
        console.dir(this[nodeType][form][type].failedValidations);
    }
    
    //exit early if the request has been aborted. TO DO: Better way to detect aborted request
    if (typeof this[nodeType][form][type].failedValidations[id] == 'undefined') {
        if (this.debug) console.log('Exiting setValidationStatus for aborted request');
        //return;
    }
    
    if (returnStatus === false) {
        if (!this.empty(errorMsg)) this[nodeType][form][type].failedValidations[id].errorMsg = errorMsg;
    } else {
        delete this[nodeType][form][type].failedValidations[id];
        if (this.empty(this[nodeType][form][type].failedValidations)) this[nodeType][form][type].failedValidations = 'callManually';
    }
    delete this[nodeType][form][type].dispatchedAjax[id];
    
    // Store the details on this request in ajaxLookup if the user supplied an ajaxCounter
    if (typeof i != 'undefined') {
        this.ajaxLookup[id][i].result = returnStatus;
        if (!this.empty(errorMsg)) this.ajaxLookup[id][i].errorMsg = errorMsg;
    }
    
    if (this.debug) {
        console.log('Pending requests after deleting:');
        console.dir(this[nodeType][form][type].dispatchedAjax);
        console.log('Failed validations:');
        console.dir(this[nodeType][form][type].failedValidations);
    }
    
    // We only validate the form if there are no other pending ajax requests we're waiting on to come back
    if (nodeType=='forms' && this.empty(this[nodeType][form][type].dispatchedAjax) && this.validateForm(form).result === true) {
        if (this.debug) console.log('Form Submitted');
        validanguage.forcedSubmission = true;
        this.$(form).submit();
    } else if (nodeType=='fields' && this.empty(this[nodeType][form][type].dispatchedAjax)) {
        // Trigger another blur/typing/etc event
        if (this.debug) console.log('Throwing Event');
        this.validationWrapper(id, type);
    }
}, //close setValidationStatus

/**
* This function shows the error messages for failed validations by dynamically
* creating a div
* @param {string}  Text of the error message to be displayed
*/
showError: function( errorMsg ) {
    var settings = validanguage.getFormSettings(this.id);
    var errorDisplay = document.getElementById(this.id + settings.errorMsgSpanSuffix);
    if( errorDisplay == null ) {
         var formField = document.getElementById(this.id);
         var errorDiv = document.createElement('DIV');
         validanguage.insertAfter( errorDiv, formField );
         var innerHTML = '<span id="'+ this.id + settings.errorMsgSpanSuffix+'">&nbsp;</span>';
         errorDiv.innerHTML = innerHTML;
         errorDiv.className = settings.onErrorClassName;            
         var errorDisplay = document.getElementById(this.id + settings.errorMsgSpanSuffix);            
    } else {         
         var errorDiv = errorDisplay.parentNode;
         errorDiv.style.display = 'block';
         errorDiv.className = settings.onErrorClassName;
    }
    if(validanguage.useLibrary=='scriptaculous') new Effect.Highlight(errorDiv, { startcolor: '#A85F5F', endcolor: '#C0A6A6', restorecolor: '#ddd' });
    errorDisplay.innerHTML = errorMsg;
    if (!this.type || (this.type != 'radio' && this.type != 'checkbox')) {
        if (!this.className.match(validanguage.settings.failedFieldClassName)) 
            this.className += ' ' + validanguage.settings.failedFieldClassName;
        if (this.className.match(validanguage.settings.passedFieldClassName)) 
            this.className = this.className.replace(validanguage.settings.passedFieldClassName, '');
    }    
    
    //Do we need to add any vd_li items?
    if( !settings.showFailedFields ) return;
    if( document.getElementById(settings.errorDivId) == null ) {
        var errorDiv = document.createElement('DIV');
        errorDiv.id = settings.errorDivId;
        document.body.appendChild(errorDiv);
    } else {
        var errorDiv = document.getElementById(settings.errorDivId);
    }
    if (document.getElementById(settings.errorListId) == null) {
        errorDiv.innerHTML = settings.errorListText + '<br/><ul id="'+settings.errorListId+'"></ul>';
    }
    var errorDivInner = errorDiv.innerHTML.toLowerCase();
    errorDivInner = errorDivInner.replace(/"/g,''); //remove quotes for IE weirdness
    
    var errorList = document.getElementById(settings.errorListId);
    var listItem = '<li id="'+this.id+settings.errorListItemSuffix+'">'+validanguage.el[this.id].field+'</li>';
    var listItemExists = listItem.toLowerCase();
    listItemExists = listItemExists.replace(/"/g,''); //remove quotes for IE weirdness
    
    if(errorDivInner.indexOf(listItemExists)==-1) errorList.innerHTML += listItem;
    document.getElementById(settings.errorDivId).style.display='block';
}, //close showError

/**
* This function is intended for us as an onsubmit transformation. It replaces the form's
* submit button with the text specified in settings.showSubmitMessageMessage.
* 
* @param {Object} validationResult
* @param {Object} failedValidations
*/
showSubmitMessage: function( validationResult, failedValidations ) {
    if( validationResult==false ) return;
    var settings = validanguage.forms[this.getAttribute("id")].settings;
    
    //first, we need to find the submit button and hide it
    var inputs = this.getElementsByTagName('INPUT');
    for( var i=inputs.length-1; i>-1; i-- ) {
        if( typeof inputs[i].type!='undefined' && inputs[i].type=='submit' ) {
            validanguage.forms.submitButton = submitButton = inputs[i];
            break;
        }
    }
    submitButton.style.display='none';
    var loadingDiv = document.createElement('DIV');
    loadingDiv.id = settings.showSubmitMessageId;
    loadingDiv.innerHTML = settings.showSubmitMessageMessage; 
    validanguage.insertAfter( loadingDiv, inputs[i] );
    
    //set a timeout to unhide the submit button after 60 seconds
    //in case the request times out and the user needs to resubmit the form
    setTimeout( function( ){ validanguage.forms.submitButton.style.display='inline'; }, 60000);
}, //close showSubmitMessage

/**
* This function splits a string into an array using commas as the delimiter,
* while being smart enough to ignore commas appearing inside parenthesis and
* braces.
* 
* @param {string} Comma-delimited string
* @return {Array}
*/
smartCommaSplit: function ( str ) {
    var openParens = 0;
    var openBraces = 0;
    var lastSplit = 0;
    var returnArray = [];
    var len = str.length;
    for( var i=0; i<len; i++ ) {
        switch (str.charAt(i)) {
            case '(': openParens++; break;
            case ')': openParens--; break;
            case '{': openBraces++; break;
            case '}': openBraces--; break;
            case ',':
            if( openParens==0 && openBraces==0 ){
                returnArray[returnArray.length] = str.substring(lastSplit,i);
                lastSplit = ++i;               
            }
            break;
        }
    }
    returnArray[returnArray.length] = str.substring(lastSplit,i);
    return returnArray;
},

/**
* Determines roughly which browser they're using. Defaults to FF for anything
* that isnt IE, Opera, Konqueror or Safari, which assuming the browser is standards-compliant,
* should be good enough for what I'm using it for.  Yea, yea, I know....
*/
sniffBrowser: function() {
    //yo...   dont hate.
    var isIE/*@cc_on=1@*/;      
    if (isIE) {
        this.browser = 'ie';
        var version = parseFloat(navigator.appVersion.split('MSIE')[1]);
        if( version < 6 ) this.browser = 'ie5';
    } else if(navigator.appName.indexOf('Opera')!=-1) {
        this.browser = 'opera';
    } else if(navigator.vendor.indexOf('Apple')!=-1) {
        this.browser = 'safari';
    } else if (navigator.vendor.indexOf('KDE')!=-1) {
        this.browser = 'konqueror';
    } else {
        this.browser = 'ff';
    }
},

/**
 * This function will strip all the leading and trailing whitespace
 * from a form field prior to its being validated.
 */
stripWhitespace: function() {
    this.value = this.value.replace(/^\s+|\s+$/g,'');
},

/**
 * This function replaces one subset of text with a different subset
 * of text, such as making all uppercase letters, lowercase.
 * @param {Array|String} Array of characters to replace
 *     or either "upper" or "lower"
 * @param {Array|String} Array of characters used for replacements
 *     or either "upper" or "lower"
 */
substituteText: function( find, replaceWith ) {
    var lower = ['a','b','c','d','e','f','g','h','i','j','k','l','m',
        'n','o','p','q','r','s','t','u','v','w','x','y','z'];
    var upper = ['A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    
    if (find === 'lower' || validanguage.empty(find)) {
        find = lower;
    } else if (find === 'upper') {
        find = upper;
    }
    if (replaceWith === 'upper' || validanguage.empty(replaceWith)) {
        replaceWith = upper;
    } else if (replaceWith === 'lower') {
        replaceWith = lower;
    }
    var subject = this.value;
    
    // Store the caret position
    var pos = validanguage.getCaretPos(this);
    
    // Do our regex replace
    for (var i = find.length - 1; i > -1; i--) {
        var myreg = new RegExp(find[i], 'g');
        subject = subject.replace(myreg, replaceWith[i]);
    }
    this.value = subject;
    
    // Restore the caret position
    validanguage.setCaretPos(this, pos);
}, //close substituteText

/**
 * Transformation supporting 3 major features: toggling visibility,
 * changing the values of form fields and adding options to select
 * elements.
 * @param {Array} toggleArgs Array of objects
 */
toggle: function( toggleArgs ) {
    var j = toggleArgs.length;
    var settings = validanguage.getFormSettings(this.id);
    var formName = validanguage.getFormId(this.id);
    for (var i=0; i < j; i++) {
        var obj = toggleArgs[i];
        var targets = validanguage.resolveArray(obj.target, 'string');
        var radioExceptionApplies = false;
        // If we are running toggle() for a radio button find out if all the other radio buttons in the same group are unchecked,
        if (this.nodeName.toLowerCase()=='input' && this.type.toLowerCase() == 'radio') {
            var radioButtonChecked = false;
            var radios = false;
            for (var k = document.forms.length - 1; k > -1; k--) {
                if (document.forms[k].id == formName) {
                    radios = document.forms[k][this.name];
                    break;
                }
            }
            for (var k = radios.length - 1; k > -1; k--) if (radios[k].checked) radioButtonChecked = true;
            if (!radioButtonChecked) radioExceptionApplies=true;
        }
        
        // toggle visibility
        if (obj.toggle) {
            var visibleMet = (obj.toggle.visible) ? validanguage.toggleCriteriaMet(this, obj.toggle.visible, settings) : false;
            var hiddenMet = (obj.toggle.hidden) ? validanguage.toggleCriteriaMet(this, obj.toggle.hidden, settings) : false;
            for (var k = targets.length - 1; k > -1; k--) {
                if (visibleMet && !radioExceptionApplies) validanguage.toggleDisplay(targets[k], '');
                if (hiddenMet || (settings.toggleVisibilityDefaultsToHidden && !visibleMet)) validanguage.toggleDisplay(targets[k], 'none');
            }
        } // close toggle visibility
        
        // toggleAttribute
        if (typeof obj.toggleAttribute != 'undefined') {
            for (var k = targets.length - 1; k > -1; k--) {
                var attribute = obj.toggleAttribute.attribute;
                var value     = obj.toggleAttribute.value;
                if (obj.toggleAttribute.condition == 'checked' && this.checked == true)
                    validanguage.$(targets[k])[attribute] = value;
                else  if (obj.toggleAttribute.condition == 'unchecked' && this.checked == false) 
                    validanguage.$(targets[k])[attribute] = value;
                else if ((this.value) && (obj.toggleAttribute.condition == this.value)) 
                    validanguage.$(targets[k])[attribute] = value ;
            }
        } // close toggleAttribute
        
        // value control
        if (typeof obj.values != 'undefined') {
            for (var k = targets.length - 1; k > -1; k--) {
                if (typeof obj.values.checked != 'undefined' && this.checked==true) validanguage.$(targets[k]).value=obj.values.checked;
                else if (typeof obj.values.unchecked != 'undefined' && this.checked==false) validanguage.$(targets[k]).value=obj.values.unchecked;
                else if (typeof obj.values[this.value] != 'undefined') validanguage.$(targets[k]).value=obj.values[this.value];
            }
        } // close toggle value
        
        // dynamic selects
        if (typeof obj.dynamicSelect != 'undefined') {
            // store the value
            var newValue = this.value;
            if (typeof validanguage.el[this.id]['value']!= 'undefined' && validanguage.el[this.id]['value']==newValue) return;
            var sel2 = validanguage.$(targets[0]); // dynamicSelect only supports 1 target
            for (var sel1Key in obj.dynamicSelect) {
                if (typeof obj.dynamicSelect[sel1Key] == 'object' ) {
                    var sel1Val = obj.dynamicSelect[sel1Key];
                    if (sel1Key == this.value) {
                        // remove the existing options
                        while (sel2.options.length > 0) { sel2.remove(0); }
                        for (var sel2Key in sel1Val) {
                            if (sel2Key == '_default') continue;
                            var opt = document.createElement('option');
                            opt.value = sel2Key;
                            opt.text  = sel1Val[sel2Key];
                            sel2.options.add(opt);
                        }
                        if (typeof sel1Val['_default'] != 'undefined') sel2.value = sel1Val['_default'];
                    }
                }
            }
            validanguage.el[this.id]['value'] = newValue;
        } // close dynamicSelect
    }
}, //close toggle

/**
 * Determines whether the criteria used by the toggle function
 * has been met
 * @param {Object} field
 * @param {string} criteria
 * @param {Object} settings object
 */
toggleCriteriaMet: function( field, criteria, settings ) {
    if (criteria == 'checked') {
        return !!(field.checked);
    } else if (criteria == 'unchecked') {
        return !(field.checked);
    } else if (criteria == 'empty') {
        return !!(validanguage.inArray(field.value, settings.emptyOptionElements));
    } else if (criteria == 'notEmpty') {
        return !(validanguage.inArray(field.value, settings.emptyOptionElements));
    } else {
        return !!(field.value == criteria);
    }    
}, //close toggleCriteriaMet

/**
 * Function used to hide or show a node. This function will also automatically disable or enable
 * any form fields contained within the passed node.
 * @param {Object} nodeId ID of the node to hide/show
 * @param {Object} visibility (optional) Pass either 'none' or '' to hide/show. Or leave blank to toggle
 */
toggleDisplay: function( nodeId, visibility ) {
    var node = validanguage.$(nodeId);
    var nodeName = node.nodeName.toLowerCase();
    if (visibility==null||visibility==undefined) visibility = (node.style.display=='none') ? '' : 'none';
    disabledBool = (visibility=='none') ? true : false;

    // show/hide the passed node
    node.style.display = visibility;
    
    // disable/enable any form fields contained within the node
    if (nodeName == 'input' || nodeName == 'textarea' || nodeName == 'select') {
        node.disabled = disabledBool;
        return;
    }
    var allInputs = node.getElementsByTagName('input');
    var allTextareas = node.getElementsByTagName('textarea');
    var allSelect = node.getElementsByTagName('select');
    var allObjects = this.concatCollection(allInputs, allTextareas);
    var allObjects = this.concatCollection(allObjects, allSelect);
    for (var i = allObjects.length - 1; i > -1; i--) {
        allObjects[i].disabled = disabledBool;
    }
}, //close toggleDisplay

/**
 * Transformation function which strips all leading and trailing
 * whitespace from a form field prior to validation.
 */
trim: function() {
    this.value = this.value.replace(/^\s+|\s+$/g,'');
},

/**
* Validates that a field does not contain any of the invalid characters
* listed in the characters validation rules.
* 
* @param {string} text
*/
validateCharacters: function( text ) {
    var id = this.id;
    var mode = validanguage.el[id].characters.mode;
    var expression = validanguage.el[id].characters.characterExpression;
    switch(mode) {
        case 'allow':
            outerLoop:        
            for( var i=text.length-1;i>-1;i--) {
                innerLoop: 
                for (var j=expression.length-1; j>-1; j--) {
                    if(expression.charAt(j)==text.charAt(i)) continue outerLoop;
                }
                //if we got here, they entered a disallowed character
                return false;
            }
            break;
        case 'deny':
            outerLoop:        
            for( var i=text.length-1;i>-1;i--) {
                innerLoop: 
                for (var j=expression.length-1; j>-1; j--) {
                    if(expression.charAt(j)==text.charAt(i)) return false;
                }
            }
            break;
    }
    return true;
}, //close validateCharacters

/**
 * Validates that a valid credit card number has been supplied
 * @param {string}  text
 * @param {array}   cardTypes
 * @param {boolean} testChecksum  Pass false to skip the luhn checksum test 
 */
validateCreditCard: function (text, cardTypes, testChecksum) {
    if (validanguage.empty(cardTypes)) cardTypes = ['amex','disc','mc','visa'];
    // Strip any non-digits
    var text=text.replace(/\D/g,'');

    var cards = {
        'amex'     : '^3[4|7]\\d{13}$',
        'bankcard' : '^56(10\\d\\d|022[1-5])\\d{10}$',
        'diners'   : '^(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})$',
        'disc'     : '^(?:6011|650\\d)\\d{12}$',
        'electron' : '^(?:417500|4917\\d{2}|4913\\d{2})\\d{10}$',
        'enroute'  : '^2(?:014|149)\\d{11}$',
        'jcb'      : '^(3\\d{4}|2100|1800)\\d{11}$',
        'maestro'  : '^(?:5020|6\\d{3})\\d{12}$',
        'mc'       : '^5[1-5]\\d{14}$',
        'solo'     : '^(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?$',
        'switch'   : '^(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)$',
        'visa'     : '^4\\d{12}(\\d{3})?$',
        'voyager'  : '^8699[0-9]{11}$'
    };
    var validCard = false;
    for (var i = cardTypes.length; i--; i > -1) {
        validCard = validanguage.validateRegex(text, { expression: cards[cardTypes[i]] });
        if (validCard) break;
    }
    if (!validCard) return false;
    if (testChecksum===false) return true;
    
    /** Run the luhn checksum test
      * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
    */

    // Set the string length and parity
    var number_length=text.length;
    var parity=number_length % 2;
    
    // Loop through each digit and do the maths
    var total=0;
    for (var i=0; i < number_length; i++) {
        var digit=text.charAt(i);
        // Multiply alternate digits by two
        if (i % 2 == parity) {
            digit=digit * 2;
            // If the sum is two digits, add them together (in effect)
            if (digit > 9) {
              digit=digit - 9;
            }
        }
        // Total up the digits
        total = total + parseInt(digit);
    }
    
    // If the total mod 10 equals 0, the number is valid
    if (total % 10 == 0) {
        return true;
    } else {
        return false;
    }
}, //close validateCreditCard

/**
 * Validates that a valid date is supplied and is entered in the correct format.
 * The format is specified by the following arguments<br/>
 * @param {String} text to be validated<br/>
 * @param {Object} Options object containing any of the following options<br/>
 *   dateOrder: {String} This must be either 'ymd','mdy','dmy','myd','ydm', or 'dym<br/>
 *   allowedDelimiters: {String}. A string containing the list of delimiters which are allowed. 
 *                       Example: './-'<br/>
 *   twoDigitYearsAllowed: {Boolean}. Is a 2-digit year valid<br/>
 *   oneDigitDaysAndMonthsAllowed: {Boolean}. Is a 1-digit month or a 1-digit day valid<br/>
 *   maxYear: {Integer}.  Years greater than maxYear will be treated as invalid. Defaults to 15 years from today<br/>
 *   minYear: {Integer}.  Years less than minYear will be treated as invalid.  Defaults to 1900<br/>
 *   rejectDatesInTheFuture: {Boolean}. Are dates in the future valid?  rejectDatesInTheFuture defaults to false<br/>
 *   rejectDatesInThePast: {Boolean}. Are dates in the past valid?  rejectDatesInThePast defaults to false<br/>
 */
validateDate: function( text, options ) {
    //Set default values
    options = validanguage.getDateTimeDefaultOptions(options, {dateOrder: 'mdy'} );
    
    //Loop thru the allowedDelimiters to start building our regex and figure out which one was actually used
    var delimiterUsed;
    var delimiterRegex = '(';
    for( var i=options.allowedDelimiters.length-1;i>-1;i--) {
        delimiterRegex += '\\' + options.allowedDelimiters.charAt(i);
        if (i>0) delimiterRegex += '|';
        if (text.indexOf(options.allowedDelimiters.charAt(i)) > -1) {
            delimiterUsed = options.allowedDelimiters.charAt(i);
        }
    }
    delimiterRegex += ')';
    if( delimiterUsed==null ) return false; //no delimiter was used
    var parts = text.split(delimiterUsed);
    if( parts.length!=3 ) return false;     //they used more than one delimiter or didnt give us a valid date
    
    //Next we need to build the regex to validate the date comprises only integers and delimiters
    var regex = '^';
    for(var j=0; j<3; j++) {
        switch( options.dateOrder.charAt(j) ) {
            case 'y':
                var num = (options.twoDigitYearsAllowed) ? '{2,4}' : '{4}';
                regex += '\\d' + num;
                break;
            case 'm':
            case 'd':
                var num = (options.oneDigitDaysAndMonthsAllowed) ? '{1,2}' : '{2}';
                regex += '\\d' + num;
                break;
        }
        if(j<2) regex += delimiterRegex;
    }
    regex += '$';
    //Run the regex
    var reg = new RegExp( regex );
    var thisMatch = reg.exec(text);
    if (thisMatch == null) return false;
    
    //grab our dates
    var year = parts[options.dateOrder.indexOf('y')];
    var month = parts[options.dateOrder.indexOf('m')];
    var day = parts[options.dateOrder.indexOf('d')];
    
    // Verify the year isnt 3-digits long to account for me being lazy in the regex check above
    if( year.length==3 ) return false;
    
    //Make sure the year is in bounds
    if( (year < options.minYear && year.length==4) || (year > options.maxYear) ) return false;
    
    //Next we check that the date actually exists, to rule out stuff like "12/32/1976"
    if( !validanguage.validateDateExists(year,month,day) ) return false;
    
    if( options.rejectDatesInTheFuture || options.rejectDatesInThePast ){
        var now = new Date();
        var then = new Date();
        then.setDate(day);
        then.setMonth(--month); // January = 0
        then.setFullYear(year);
        if( (options.rejectDatesInTheFuture && then > now) || (options.rejectDatesInThePast && then < now) ) return false;
    }
    return true;
}, //close validateDate

/**
* Helper function to verify a date actually exists. Used to reject values
* such as "12/35/2009"
* @param {integer} year, preferably 4-digit
* @param {integer} month
* @param {integer} day
* @return {Boolean}
*/
validateDateExists: function(year, month, day) {
    if(year.length==2) {
        var prefix = (year > 20 ) ? '19' : '20';
        year = prefix+year.toString();
    }
    if( month.charAt(0)=='0' ) month = month.substr(1,1);
    if( day.charAt(0)=='0' ) day = day.substr(1,1);
    if( month < 0 || month > 12 ) return false;
    switch( month.toString() ) {
        case '4':
        case '6':
        case '9':
        case '11':
            var maxDay = 30;
            break;
        case '2':
            var maxDay = ((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0)) ) ? 29 : 28;
            break;
        default:
            var maxDay = 31;         
    }
    if( day < 0 || day > maxDay ) return false;
    return true;
}, //close validateDateExists

/**
 * Validates an email address
 */
validateEmail: function( text ) {
    if(! text.match(/^([a-zA-Z0-9]+[a-zA-Z0-9._%-]*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4})$/) )
       return false;
    else return true;
}, //close validateEmail

/**
* Calls the validationWrapper function on the passed form ID
* @param {String|Form Node} Form node or its ID
* @return {Object}  Object containing 2 elements:
*    Object.result is a boolean to indicate whether any fields in the form failed validation
*    Object.failedValidations will contain info detailing any form fields with failed validations
*/
validateForm: function(form) {
    form = this.$(form);
    if (form==undefined) form = document.forms[0];
    return this.validationWrapper( form, 'validateForm' );
}, //close validateForm

/**
 * Validates that a field contains a valid IPv4 address
 */
validateIP: function( text ) {
	var bytes = text.split('.');
	if (bytes.length == 4) {
        for (var i=bytes.length-1; i> -1; i--) {            
			if (!(validanguage.validateNumeric(bytes[i]) && bytes[i] >= 0 && bytes[i] <= 255)) {
				return false;
			}
        }
		return true;
	}
	return false;    
}, //close validateIP

/**
* Function to suppress the keys specified in validanguage.el.characters
* from being entered into a textarea or text box.
* 
* This function must be forked to fetch the keyCode
* and differentiate between control and non-control characters.
* For example: in Mozilla both the delete key and . equate to 46.  
* We dont fork based on supported functions because IE and Moz event models
* for keyCodes/charCodes are different and confusing, so we're
* better off sniffing the browser.
* 
* @param {Event Object} e
*/
validateKeypress: function(e) {
    var evt = e || window.evt;
    var $this = evt.currentTarget || evt.srcElement;
    var id = $this.id;
    var formName = validanguage.getFormId(id);
    var settings = validanguage.getFormSettings(id);
    
    if (validanguage.browser == 'ie' || validanguage.browser == 'opera') {
        //branch for IE and opera
        //we dont have to worry about noncontrol keys since they dont fire keypress events in IE
        var charCode = evt.keyCode;
        if (((charCode == 16) && (evt.shiftKey)) || (evt.ctrlKey)) 
            return true; //prevents firing on ctrl key in opera
            
        if (evt.which == 0) return true;
            
    } else {
        //branch for Mozilla
        if ((evt.charCode == 0) || (evt.ctrlKey)) 
            return true;
        //return true for all control keys and if control is held down
        
        charCode = evt.which; //capture charCode of non-control keys
    }
    
    charCode += ';';
    searchString = new String(validanguage.el[id].characters.expression);
    var mode = validanguage.el[id].characters.mode;
    
    if ( ( (searchString.search(charCode) != -1 ) && (mode == 'allow') ) ||
          ( (searchString.search(charCode) == -1 ) && (mode == 'deny') ) ){
        return true;
    } else {
        $this.style.backgroundColor = settings.validationErrorColor;
        setTimeout("document.getElementById('" + id + "').style.backgroundColor = validanguage.forms['"+formName+"'].settings.normalTextboxColor",validanguage.forms[formName].settings.timeDelay);
        evt.returnValue = false; //IE
        if(evt.preventDefault) evt.preventDefault();    //Everyone else
        return false;
    }
}, //close validateKeypress

/**
* Validates that a field is less than maxlength characters long
* @param {string} text
*/
validateMaxlength: function( text, max ) {
    var id = this.id;
    var maxlength = (validanguage.empty(max)) ? validanguage.el[id].maxlength : max;
    if(text.length > maxlength)
        return false;
    else return true;
}, //close validateMaxlength

/**
* Validates that a field is greater than minlength characters long
* @param {string} text
*/
validateMinlength: function( text, min ) {
    var id = this.id;
    var minlength = (validanguage.empty(min)) ? validanguage.el[id].minlength : min;
    if(text.length < minlength)
       return false;
    else return true;
}, //close validateMinlength

/**
* Validates that a field is numeric
* @param {string} text
*/
validateNumeric: function( text ) {
    if(! text.match(/^\d+$/) )
        return false;
    else return true;
}, //close validateNumeric

/**
* Validates whether a password is adequately secure.
* Additionally, this function can display a password
* strength meter, based on the same, or different,
* criteria than whether or not it validates.
* With default arguments, a password must contain at
* least one letter and one number and be 6 characters
* long, but you can easily make the requirements more
* stringent.<br/>
* @param {String} text
* @param {Object} Argument object. The following options
* are supported:<br/>
*   args.minLength: Minlength for the password<br/>
*   args.minStrength: Minimum strength for the password
*     to validate. This is a number from 1 to 4 to indicate
*     how many character types it must have.<br/>
*   args.mustMatch: Array of character types which password
*     must have to validate. For example, to require all
*     4 you would use args.mustMatch = ['hasUpper','hasLower',
*     'hasDigit','hasSpecial']<br/>
*   args.strong: Used for password meter. Array of numbers
*     determining what qualifies as a "strong" password.
*     For example: args.strong = [4] // All 4 char types<br/>
*   args.medium: Used for password meter. Array of numbers
*     determining what qualifies as a "medium" password.
*     For example: args.medium = [2,3] // 2 or 3 char types
*     If a password doesn't qualify as strong or medium, then
*     it defaults to weak.<br/>
*/
validatePasswordStrength: function(text, args) {
    if (!args) args = {};
    var minLength = args.minLength || 6;
    var minStrength = args.minStrength || 2;
    var strong = args.strong || [4];
    var medium = args.medium || [2,3];
    var mustMatch = args.mustMatch || ['hasDigit'];
    
    var hasDigit = text.match(/\d/);
    var hasUpper = text.match(/[A-Z]/);
    var hasLower = text.match(/[a-z]/);
    var hasSpecial = text.match(/[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\{|\[|\}|\]|\\|\|]|\;|\:|\'|\"|\,|\<|\.|\>|\/|\?/);
    
    var strength = 0;
    if (hasDigit) strength++;
    if (hasUpper) strength++;
    if (hasLower) strength++;
    if (hasSpecial) strength++;    
    if (text.length < minLength) strength = 0;
    
    var ps = document.getElementById('passwordStrength');
    if (ps) {
        if (validanguage.inArray(strength, strong)) {
            var strengthLevel = 'Strong';
        } else if (validanguage.inArray(strength, medium)) {
            var strengthLevel = 'Medium';
        } else {
            var strengthLevel = 'Weak';
        }
        var msg = '<span class="passwordStrengthMsg">Password Strength: ' + strengthLevel + '</span>';
        msg += '<br/><div class="passwordClass"><div class="passwordClass' + strengthLevel + '">&nbsp;</div></div><br/>';
        ps.innerHTML = msg;
    }
    
    for (var i=mustMatch.length-1; i>-1; i--) {
        eval('if (!'+mustMatch[i]+') strength=0;');
    }
    
    if (strength >= minStrength) {
        return true;
    } else {
        return false;
    }
}, //close validatePasswordStrength

/**
* Validates the element against a user-defined regex stored in
* validanguage.el[id].regex. Modifiers are supported by supplying
* them in obj within the regex, or, *if obj is a string*, you can
* pass the modifiers in obj.modifiers.
* 
* @param {string} text
* @param {object} optional object containing regex settings.
*   Supports "errorOnMatch" and "modifiers"
*/
validateRegex: function( text, obj ) {
    var id = this.id;
    var regexObj = (obj && obj.expression) ? obj : validanguage.el[id].regex;
    if(typeof regexObj.modifiers=='undefined') regexObj.modifiers='';
    if(typeof regexObj.errorOnMatch=='undefined') regexObj.errorOnMatch=false;
    var myreg = (typeof regexObj.expression=='string') ? new RegExp(regexObj.expression, regexObj.modifiers) : regexObj.expression;
    var thisMatch = myreg.exec(text);
    if (thisMatch == null) {  //no match
        var returnStatus = (regexObj.errorOnMatch==false||regexObj.errorOnMatch=='false') ? false : true;
    } else {                  //match
        var returnStatus = (regexObj.errorOnMatch==false||regexObj.errorOnMatch=='false') ? true : false;         
    }
    return returnStatus;
}, //close validateRegex

/***
* Validates whether or not an element has been filled out,
* selected or checked.  This function is a wrapper which
* calls validateRequiredChild()
* 
* @param {string} text
*/
validateRequired: function( unused ) {
    var id = this.id; 
    if(typeof validanguage.el[id].requiredAlternatives == 'undefined') {
        var alternatives = [ id ];
    } else {         
        var alternatives = validanguage.resolveArray(validanguage.el[id].requiredAlternatives,'string');
        alternatives[alternatives.length] = id;
    }
    for( var i=alternatives.length-1; i>-1; i--) {
        id = alternatives[i];
        var elem = validanguage.$(id);
        var text = elem.value;
        var notEmpty = validanguage.validateRequiredChild.call(elem, text);
        if(notEmpty==true) return true; //if this element or one of its alternatives is not empty, it validates
    }
    return false;
},

/**
* This function calls the validateRequired method on the "master/required"
* form field when the "alternative" form field is clicked and then calls
* the appropriate onerorr/onsuccess function.
*/
validateRequiredAlternatives: function(e) {
    var evt = e || window.evt;
    var $this = evt.currentTarget || evt.srcElement;
    var id = $this.id;
    var parentId = validanguage.requiredAlternatives[id].parentId;
    var onsuccess = validanguage.requiredAlternatives[id].onsuccess;
    var onerror = validanguage.requiredAlternatives[id].onerror;
    var parent = validanguage.$(parentId);
    if (validanguage.validateRequired.call(parent) == true) {
        successHandlers = validanguage.resolveArray(onsuccess, 'function');
        for (var m = successHandlers.length - 1; m > -1; m--) {
            successHandlers[m].call(parent);
        }
    } else {      
        errorHandlers = validanguage.resolveArray(onerror, 'function');
        for (var m = errorHandlers.length - 1; m > -1; m--) {
            errorHandlers[m].call(parent, validanguage.requiredAlternatives[id].errorMsg);
        }
    }
},

/***
* Child function called by validateRequired to validates whether or not an element has been filled out,
* selected or checked.  validateRequiredChild is required to add support for the requiredAlternatives
* array.
* 
* @param {string} text
*/
validateRequiredChild: function( text ) {
    var type = ( typeof this.type != 'undefined' ) ? this.type : null;
    if( this.nodeName.toLowerCase() == 'textarea' ) type = 'text';
    if( this.nodeName.toLowerCase() == 'select' ) type = 'select';
    
    switch( type ) {
        case 'checkbox':
            if( this.checked == false ){
                return false;
            }
            break;
        
        case 'radio':
            var formId = validanguage.getFormId(this.id);
            var radios = (typeof formId == 'number') ? document.forms[formId][this.name] : validanguage.$(formId)[this.name];
            for( var i=radios.length-1; i>-1;i--) {
                if (radios[i].checked == true) return true;
            }
            return false;
            break;
        
        case 'text':
        case 'password':
        case 'file':
            if(validanguage.empty(text)) {
                return false;
            }
            break;
        
        case 'select':
            if (validanguage.empty(text)) {
                return false;
            }
            settings = validanguage.getFormSettings(this.id);
            for( var i=settings.emptyOptionElements.length-1; i>-1; i-- ) {
                //see if they have selected any of the "empty" option elements
                if( text==settings.emptyOptionElements[i]) return false;
            }
            break;
    } //close switch
    return true;
},

/**
* Validates that the entered text is a valid timestamp.  The options object supports all the options listed
* under the validateDate function as well as the following additional onces<br/>
* @param {String} text to be validated<br/>
* @param {Object} Options object containing any of the following options:<br/>
*   timeIsRequired: {Boolean}  Is a date which is provided without an accompanying time considered a valid timestamp?
*     timeIsRequired defaults to false.<br/>
*   timeUnits: {String} A string containing a list of all the time units which are allowed to be entered in the timestamp.
*     These may include any of the following: h for hours, m for minutes, s for seconds, u for microseconds,
*     and t for timezone.  Example:  "hms" for hours, minutes and seconds or "hmsut" for all 5 units.
*     Defaults to "hms"<br/>
*   microsecondPrecision {Integer} Indicates the supported number of decimal places for the microseconds. Defaults to 6.
*   
*/
validateTimestamp: function( text, options ) {
    // Set default options
    options = validanguage.getDateTimeDefaultOptions(options, {dateOrder: 'ymd'}  );
        
    // Check the date portion of the timestamp
    var pos = text.indexOf(' ');
    var date = ( pos == -1 ) ? text : text.substr(0,pos);      
    if( !validanguage.validateDate(date, options) ) return false;

    // Check whether they provided a time
    if( pos != -1 ) {
        var time = text.substring(++pos);
    } else {
        if( !options.timeIsRequired ) return true;
        if( options.timeIsRequired ) return false;
    }
        
    // Build the regex to validate the time
    var regex = '^\\d{1,2}:\\d{1,2}';
    if( options.timeUnits.indexOf('s')!=-1 ) regex += '(:\\d{1,2}';
    if( options.timeUnits.indexOf('u')!=-1 ) regex += '(\\.\\d{1,'+options.microsecondPrecision+'})?';
    if( options.timeUnits.indexOf('s')!=-1 ) regex += ')?';
    if( options.timeUnits.indexOf('t')!=-1 ) {
        regex += '( ?[\\+|\\-]{1,1}(\\d|0\\d|10|11|12|13)(\\:(00|30))?)?';
    }
    regex += '$';

    //Run the regex
    var reg = new RegExp( regex );
    var thisMatch = reg.exec(time);
    if (thisMatch == null) return false;

    //Finally we need to make sure that the hours, minutes and seconds which were entered are valid
    var timeparts = time.split(':');
    if( timeparts[0] > 23) return false;
    if( timeparts[1] > 59) return false;
    if( timeparts.length > 2){
        var seconds = timeparts[2].substr(0,2);
        if(seconds > 59) return false;
    }
    return true;
}, //close validateTimestamp

/**
* Validates a URL
* 
* @param {string} text
*/
validateURL: function( text ) {
    if(! text.match(/^((([hH][tT][tT][pP][sS]?|[fF][tT][pP])\:\/\/)?([\w\.\-]+(\:[\w\.\&%\$\-]+)*@)?((([^\s\(\)\<\>\\\"\.\[\]\,@;:]+)(\.[^\s\(\)\<\>\\\"\.\[\]\,@;:]+)*(\.[a-zA-Z]{2,4}))|((([01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}([01]?\d{1,2}|2[0-4]\d|25[0-5])))(\b\:(6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{0,3}|0)\b)?((\/[^\/][\w\.\,\?\'\\\/\+&%\$#\=~_\-@]*)*[^\.\,\?\"\'\(\)\[\]!;<>{}\s\x7F-\xFF])?)$/) )
        return false;
    else return true;
},

/**
 * Validates that a US Phone number is entered
 * 
 * @param {string} text
 */
validateUSPhoneNumber: function( text ) {
    if(! text.match(/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/) )
        return false;
    else return true;
},

/**
 * Validates that a US Social Security Number is entered
 * 
 * @param {string} text
 */
validateUSSSN: function( text ) {
    if(! text.match(/^\d{3}( |-|.){0,1}\d{2}( |-|.){0,1}\d{4}$/) )
        return false;
    else return true;
},

/**
 * Validates that a US zip code was entered
 * 
 * @param {string} text
 */
validateUSZipCode: function( text ) {
    if(! text.match(/^\d{5}( |-|.){0,1}(\d{4})?$/) )
        return false;
    else return true;
},

/**
* This is a wrapper for all the validation event handlers assigned to both
* form field element and to forms themselves. This function also calls any
* transformations before running the validations.
* 
* We use a wrapper for a number of reasons, including exiting validation
* early as soon as a single validation function fails.
* 
* @param {Event|String} Event object or Node ID
* @param {String} Custom Event
* @param {Number} ajaxLookupIndex
* 
*/
validationWrapper: function(e, customEvent) {
    var calledManually = false;
    if (validanguage.inArray(customEvent, validanguage.supportedEvents)) {
        // check for manually thrown events
        var $this = validanguage.$(e);
        var type = customEvent;
        var id = e;
        var calledManually = true;
    } else if (customEvent=='validateForm') {
        // validate form event
        var $this = e;
        var form = $this.getAttribute('id');
        var id = form;
        var type = 'submit';
    } else {
        // standard event handlers
        var evt = e || window.evt;
        var $this = evt.currentTarget || evt.srcElement;
        var type = evt.type;
        if (type == 'submit') {
           var form = $this.getAttribute('id');
           var id = form;
        } else {
           var id = $this.id;
           var form = validanguage.getFormId(id);
        }
        if (customEvent=='typingTimeout') {    
           if( validanguage.typingDelay[id] ) window.clearTimeout(validanguage.typingDelay[id]);
           eval("validanguage.typingDelay[id] = window.setTimeout(\"validanguage.validationWrapper('"+id+"', 'typing')\", validanguage.settings.typingDelay );");
           return true;
        } 
    }
    
    // Bail out early if the parent form is marked as disabled
    if( typeof validanguage.el[form] != 'undefined' && typeof validanguage.el[form].disabled != 'undefined' && validanguage.el[form].disabled == true ) return true;
    if (validanguage.forcedSubmission) return true;
    var validations = (type=='submit') ? validanguage.forms[form].validations : validanguage.el[id].handlers[type];
    var i=validations.length;
    var failedValidations = {};  //key value pair of id => validation
        
    // Prep the dispatchedAjax and failedValidations vars
    if (!validanguage.ajaxLookup[id]) validanguage.ajaxLookup[id] = [ 1 ];
    var ajaxLocation = (type=='submit') ? 'forms' : 'fields';
    
    if (!validanguage[ajaxLocation][id]) validanguage[ajaxLocation][id] = {};
    if (!validanguage[ajaxLocation][id][type]) validanguage[ajaxLocation][id][type] = {};
    validanguage[ajaxLocation][id][type].dispatchedAjax = {};
    if (typeof validanguage[ajaxLocation][id][type].failedValidations == 'undefined') 
        validanguage[ajaxLocation][id][type].failedValidations = {};
    
    // Handle re-validating a field when a prior ajax validation still has not returned
    // by resetting failedValidations and aborting the pending ajax requests
    if (!calledManually && !validanguage.empty(validanguage[ajaxLocation][id][type].failedValidations)) {
        validanguage[ajaxLocation][id][type].failedValidations = {};
    }        
    
    if(type=='submit') {

        if (validanguage.empty(validanguage[ajaxLocation][form][type].failedValidations)) {
        
            outerLoop:
            for(var j=0; j<i; j++) {
                if( typeof validations[j]=='undefined' || validations[j]==999) continue outerLoop; //skip deativated validations and transformations
                id = validations[j].element.id; //reassign $this and id to the form field in question
                var $this = validations[j].element;
                if( (typeof failedValidations[id] != 'undefined') || 
                       (typeof $this.disabled != 'undefined' && $this.disabled==true) ||
                       (typeof validanguage.el[id].disabled != 'undefined' && validanguage.el[id].disabled==true)
                 ) {
                    continue outerLoop; //skip disabled fields or fields that already flunked
                }
                if( typeof validanguage.el[id].failed != 'undefined' && validanguage.el[id].failed==true) {
                    failedValidations[id] = { failed: true, field: validanguage.el[id].field };
                    continue outerLoop; //handle fields manually marked as invalid
                }
                var validOptionalField = !!(typeof validanguage.el[id].required != 'undefined' && (validanguage.el[id].required==false||validanguage.el[id].required=='false') &&
                    !$this.value.match(/[^\s]/));
                    
                var validationsCounter = validations[j].validationsCounter;
                var validation = validanguage.el[id].validations[validationsCounter];
                var funcs = validanguage.resolveArray(validation.name, 'function');
                innerLoop:
                for (var m=funcs.length-1; m>-1; m--) {
                    if (typeof failedValidations[id] != 'undefined') continue innerLoop; //this field already flunked
                    
                    //handle ajax validations
                    if (validation.isAjax && !validOptionalField) {
                        
                        if (!validanguage.ajaxLookup[id]) validanguage.ajaxLookup[id] = [ 1 ];                                
                        var dispatchAjax = true;
                        
                        // handle cached ajax lookups
                        if (validanguage.settings.cacheAjaxLookups && validanguage.ajaxLookup[id].length > 1) {
                            ajaxLookupLoop:
                            for (var ajaxLookupIndex = validanguage.ajaxLookup[id].length - 1; ajaxLookupIndex > -1; ajaxLookupIndex--) {
                                var lookupToCheck = validanguage.ajaxLookup[id][ajaxLookupIndex];
                                if (lookupToCheck.value == $this.value && typeof lookupToCheck.result=='boolean') {
                                    dispatchAjax = false;
                                    var result = lookupToCheck.result;
                                    if (result == false) {
                                        failedValidations[id]          = validation; //store this function in failedValidations. We remove it later if it's not applicable.
                                        failedValidations[id].field    = validanguage.el[id].field;
                                        failedValidations[id].errorMsg = lookupToCheck.errorMsg;
                                    }
                                    break ajaxLookupLoop;
                                }
                            }
                        }
                        
                        if (dispatchAjax) {
                            validanguage[ajaxLocation][form][type].dispatchedAjax[$this.id] = new Date().getTime();
                            failedValidations[id] = validation; //store this function in failedValidations. We remove it later if it's not applicable.
                            failedValidations[id].field = validanguage.el[id].field;
                            funcs[m].call($this, $this.value, validanguage.ajaxLookup[id][0]);  //fire off the ajax call
                            
                            // Store the details in ajaxLookup
                            var ajaxCounter = validanguage.ajaxLookup[id][0]++;
                            validanguage.ajaxLookup[id].push({
                                counter: ajaxCounter,
                                eventType: type,
                                value: $this.value,
                                result: 'pending'
                            });
                            var result = 'pending';
                        }                        
                    } else {
                        var result = (validOptionalField || funcs[m].call($this, $this.value));
                    }
                    
                    // Run the handlers on this item
                    if (result == false) {
                        failedValidations[id] = validation; //defer onerror handlers till later
                        failedValidations[id].field = validanguage.el[id].field;
                        if(! validanguage.forms[form].settings.validateAllFieldsOnsubmit) break outerLoop;
                    } else {
                        var onsuccess = validanguage.getElSetting('onsuccess',id,validation);
                        successHandlers = validanguage.resolveArray(onsuccess, 'function');
                        for (var n=successHandlers.length-1; n>-1; n--) {
                            successHandlers[n].call($this);
                        }
                    }
                } //close innerLoop
            } //close outerLoop
            validanguage.forms[form][type].failedValidations = failedValidations;
        }
        
        if (!validanguage.empty(validanguage.forms[form][type].dispatchedAjax)) {
            //If one or more ajax calls are pending, cancel the form submit until
            //the ajax call comes back
            validanguage.forms[form][type].ajaxInterval = window.setInterval( function() {
                validanguage.ajaxValidationWrapper(form, type);
            }, 500);
            return false;
        }

        //swap failedValidations into local variable
        failedValidations = (validanguage.forms[form][type].failedValidations==='callManually') ? {} : validanguage.forms[form][type].failedValidations;
        validanguage.forms[form][type].failedValidations = {};
        
        if( validanguage.empty(failedValidations) ) {
           var submitStatus = true;
        } else { //call all appropriate onerror handlers
           for (var o in failedValidations) {
               if( typeof failedValidations[o] == 'function' ) continue;
               var id = o;
               $this = validanguage.$(o);
               validation = failedValidations[o];
               var focusOnerror = validanguage.getElSetting('focusOnerror',id,validation);
               var errorMsg = validanguage.getElSetting('errorMsg',id,validation);
               var onerror = validanguage.getElSetting('onerror',id,validation);
               errorHandlers = validanguage.resolveArray(onerror,'function');
               for (var m=errorHandlers.length-1; m>-1; m--) {
                   errorHandlers[m].call($this, errorMsg);
               }

               var focusOnerror = validanguage.getElSetting('focusOnerror',id,validation);
               if( focusOnerror==true ) $this.focus();
               var showAlert = validanguage.getElSetting('showAlert',id,validation);
               if( showAlert ) alert(errorMsg);
           }
           var submitStatus = false;
        }

        //Call onsubmit transformations
        var transformation = (validanguage.el[form] &&  validanguage.el[form].onsubmit) ? validanguage.el[form].onsubmit : [];
        if (typeof transformation == 'string' || typeof transformation == 'function') transformation = [ transformation ];
        for (var n=transformation.length-1; n>-1; n--) {
           var transformations = validanguage.resolveArray(transformation[n],'function');
           for (var o=transformations.length-1; o>-1; o--) {
               var returnStatus = transformations[o].call(validanguage.$(form), submitStatus, failedValidations);
               if(typeof returnStatus == 'boolean') submitStatus = returnStatus;
           }
        }
        if( customEvent=='validateForm' ) {
           return { result: submitStatus, failedValidations: failedValidations };
        }
        return submitStatus;

    } else {

        var validation;
        
        //Skip disabled fields
        if( (typeof validanguage.el[id].disabled=='boolean' && validanguage.el[id].disabled==true)
               || (typeof $this.disabled!='undefined' && $this.disabled==true) ) {
           return;
        }
        var transformations = validanguage.el[id].transformations;
        var p = transformations.length;
        for( var q=0; q<p; q++) {
            if( typeof transformations[q]['on'+type]=='undefined' || transformations[q]['on'+type]!=true ) continue;
            var transformation = validanguage.resolveArray(transformations[q].name, 'function');
            var trLength = transformation.length;
            for (var m=0; m<trLength; m++) {
                transformation[m].call($this);
            }         
        }

        var validOptionalField = !!(typeof validanguage.el[id].required != 'undefined' && (validanguage.el[id].required==false||validanguage.el[id].required=='false') &&
            !$this.value.match(/[^\s]/));
                           
        if( typeof validanguage.el[id].failed=='boolean' && validanguage.el[id].failed==true ) {
           //Allow a user to manually flunk a field
           result = false;
        } else {
            
           if (validanguage.empty(validanguage.fields[id][type].failedValidations)) {
               //see if the field is valid
                var validationCounter;
                outerLoop: for (var j = 0; j < i; j++) {
                    if (typeof validations[j] == 'undefined' || validations[j] == 999) {
                        continue outerLoop; //skip deactivated validations and transformations
                    }
                    else {
                        validationCounter = validations[j];
                    }
                    //we run thru all the validations until one fails
                    validation = validanguage.el[id].validations[validationCounter];
                    var funcs = validanguage.resolveArray(validation.name, 'function');
                    
                    for (var m = funcs.length - 1; m > -1; m--) {
                        //handle ajax validations
                        if (typeof validation.isAjax != 'undefined') {

                            var dispatchAjax = true;
                            
                            // handle cached ajax lookups
                            if (validanguage.settings.cacheAjaxLookups && validanguage.ajaxLookup[id].length > 1) {
                                ajaxLookupLoop:
                                for (var ajaxLookupIndex = validanguage.ajaxLookup[id].length - 1; ajaxLookupIndex > -1; ajaxLookupIndex--) {
                                    var lookupToCheck = validanguage.ajaxLookup[id][ajaxLookupIndex];
                                    if (lookupToCheck.value == $this.value && typeof lookupToCheck.result=='boolean') {
                                        dispatchAjax = false;
                                        var result = lookupToCheck.result;
                                        if (result == false) {
                                            failedValidations[id]          = validation; //store this function in failedValidations. We remove it later if it's not applicable.
                                            failedValidations[id].field    = validanguage.el[id].field;
                                            failedValidations[id].errorMsg = lookupToCheck.errorMsg;
                                        }
                                        break ajaxLookupLoop;
                                    }
                                }
                            }
                            
                            if (dispatchAjax && !validOptionalField) {
                                validanguage.fields[id][type].dispatchedAjax[id] = new Date().getTime();
                                failedValidations[id] = validation; //store this function in failedValidations. We remove it later if it's not applicable.
                                failedValidations[id].field = validanguage.el[id].field;
                                funcs[m].call($this, $this.value, validanguage.ajaxLookup[id][0]); //fire off the ajax call
                                
                                // Store the details in ajaxLookup
                                var ajaxCounter = validanguage.ajaxLookup[id][0]++;
                                validanguage.ajaxLookup[id].push({
                                    counter: ajaxCounter,
                                    eventType: type,
                                    value: $this.value,
                                    result: 'pending'
                                });
                            }
                        } else {
                            var result = (validOptionalField || funcs[m].call($this, $this.value));
                        }
                        
                        if (result == false) {
                            // Record lastFailed1 and 2
                            if (validanguage.el[id].lastFailed1) validanguage.el[id].lastFailed2 = validanguage.el[id].lastFailed1;
                            validanguage.el[id].lastFailed1 = funcs[m].toString();
                            break outerLoop;
                        }
                    }
                }
                if (validationCounter == undefined) return true; //exit early if all validations have been removed
                validanguage.fields[id][type].failedValidations = failedValidations;
            }
        }        
        
        if (!validanguage.empty(validanguage.fields[id][type].dispatchedAjax)) {
            //If one or more ajax calls are pending, exit early until the ajax call comes back
            validanguage.fields[id][type].ajaxInterval = window.setInterval( function() {
                validanguage.ajaxValidationWrapper(id, type);
            }, 500);
            return false;
        }

        //swap failedValidations into local variable
        if (typeof result == 'undefined') {
            failedValidations = (validanguage.fields[id][type].failedValidations === 'callManually') ? {} : validanguage.fields[id][type].failedValidations;
            if (failedValidations[id] && failedValidations[id].name) validation = failedValidations[id].name;
            var result = validanguage.empty(failedValidations) ? true : false;
        }
        validanguage.fields[id][type].failedValidations = {};
        
        //handle the result
        if( result == true ) {
            validanguage.el[id].lastFailed1 = {};
            var onsuccess = validanguage.getElSetting('onsuccess',id,validation);
            successHandlers = validanguage.resolveArray(onsuccess,'function');
            for (var m=successHandlers.length-1; m>-1; m--) {
               successHandlers[m].call($this);
            }   
            return true;
        } else {
            var retriggerErrors = validanguage.getElSetting('retriggerErrors',id,validation);
            var failedFieldClassName = validanguage.getElSetting('failedFieldClassName',id,validation);
            
            // Trigger errors if retriggerErrors is on, or if the last 2 failures were different
            if (retriggerErrors || (validanguage.el[id].lastFailed1!=validanguage.el[id].lastFailed2)) {
            
                var focusOnerror = validanguage.getElSetting('focusOnerror', id, validation);
                var errorMsg = (failedValidations[id] && failedValidations[id].errorMsg) ? failedValidations[id].errorMsg : validanguage.getElSetting('errorMsg', id, validation);
                var onerror = (failedValidations[id] && failedValidations[id].onerror) ? failedValidations[id].onerror : validanguage.getElSetting('onerror', id, validation);
                errorHandlers = validanguage.resolveArray(onerror, 'function');
                for (var m = errorHandlers.length - 1; m > -1; m--) {
                    errorHandlers[m].call($this, errorMsg);
                }
                
                var focusOnerror = validanguage.getElSetting('focusOnerror', id, validation);
                if (focusOnerror == true) $this.focus();
                
                var showAlert = validanguage.getElSetting('showAlert', id, validation);
                if (showAlert) alert(errorMsg);
            }
            return false;
        }      
    }
}

} //close validanguage

validanguage.init();
