Validanguage Overview

This REAMDE is outdated! To view an updated, online version of this documentation, formatted for easy readability, visit:
http://www.drlongghost.com/validanguage/documentation

Validanguage is a form validation framework supporting a number of powerful and easy-to-configure features. These features can be enabled on a web form via two different APIs*. Each API will be discussed below, beginning with the Comment-based API, which is recommended for those with limited javascript experience.
*API = Application Programming Interface, which is simply a fancy term meaning a computer language which is used to describe something -- in this case, validation rules.
Installing Validanguage

Download validanguage, upload it to your web server then include it on your web page by adding a script tag in the <head> section of your page:

<script type="text/javascript" src="/path/to/validanguage.js"></script>

Once the validanguage.js file has been included on your web page, the next requirement is to ensure that all form elements on the web page for which you intend to load validation rules includes an id attribute. This id attribute is used to specify which validation functions should be associated with which form elements. Each id should be unique (appearing only once on the web page). For example:

<input type="checkbox" name="termAgreement" id="termAgreement" value="1" />

It is also recommended that every <form> tag on your webpage also include an id (although, strictly speaking, this is only required if you want to have different settings apply to different forms).

Once you have the javascript installed on your page, and every item in your form has a unique id added to the HTML, the final step is to use one of the APIs described below to load the specific validation requirements for each form field.
API #1 -- The Comment-based API

The comment-based Validanguage API is the API which is recommended for anyone who is not familiar with javascript JSON notation. Even if you are familiar with JSON, you may find the Comment API to be easier to use and simpler (although it should be noted that the JSON API gives you a higher degree of control).

The Comment API, as its name implies, is placed on your web page inside HTML comments and uses the same syntax as HTML. The best way to describe the Comment API is to provide an example. Let's assume you have a checkbox with an id of "termAgreement" and you want to make the checkbox required so that the form cannot be submitted unless the checkbox is checked. You could load this simple validation rule by placing the following anywhere inside the <body> section of your web page:

<!-- <validanguage target="termAgreement" required="true" errorMsg="You must agree to the terms before submitting this form"> -->

Let's break down this example. All HTML comments are started with <!-- and ended with -->. Next, <validanguage appears within the comment to indicate that what follows is a Validanguage API statement. target="termAgreement" indicates which form element the rest of the statement is describing. required="true" tells the validanguage library that this form element needs to be filled out, checked, or selected before the form in which it appears will be allowed to submit. Finally, the errorMsg item provides the text which will be shown inside the alert message* which will be displayed to the user.
*We will discuss later how you can configure Validanguage to show this message inside your form, instead of displaying it in an alert message, via the onerror handler.

That's pretty much all there is to the Comment API. There are a several additional features I'll mention briefly.

Some attributes, such as target, allow you to specify more than one value, by placing commas in between each. For example, target="termAgreement, ageVerify" would load all the validation statements included inside the Validanguage Comment for both the termAgreement and the ageVerify form fields.

Although quotation marks are used to surround the values in all the examples we've seen so far, you can actually use any character you want as the delimiter (unlike in actual HTML). This comes in handy if you need to use quotation marks inside the value itself. For example, if your errorMsg contained quotation marks, you could define the errorMsg using the dollar sign as the value delimiter: errorMsg=$You must complete the "Education" section before continuing.$

Just like actual HTML, the order in which attributes in a Validanguage comment appear is irrelevant. Although the target attribute determines what form fields all the other attributes will apply to, the target attribute can appear first, second, third, or even last within the comment.

Unlike actual HTML, validanguage comments are case-sensitive. Be sure to use the proper capitalization.

Finally, be sure that you only include 1 validanguage tag per HTML comment. If you include more than one Validanguage tag in the same comment, only the first one will be processed.
List of Options Available in the Comment-based API
Basic Options

target="dateField" -- As explained above, the target defines which form fields the subsequent rules will apply to.

validations="validanguage.validateUSDate, someCustomFunction" -- This example would load 2 validations for the form target(s). The first function mentioned is Validanguage's built in function for validating US dates. The second function in this example is a custom function the the end user defined elsewhere.

onerror="showError" -- If you wish to display form error messages on the page itself, instead of using javascript's built in alerts, you can use the onerror option to load a custom function to display the message somewhere on your page.
Any function included in the onerror handler will be executed in the scope of the element which failed the validation, with the this keyword referring to the form element. This enables you to use "this.id" to retrieve the id of the form element from inside the showError() function ( which would return "dateField" in this example ).
Additionally, the error message assigned to the failed validation is passed to the function as its first argument. This enables you to write custom functions yourself such as the following:

    function showError( errorMsg) {
    document.getElementById( this.id + '_errorSpan' ).innerHTML = errorMsg;
    }

You could use a function similar to the function above to put the error message in <span> or <div> tags throughout your form, if the id's for the error message areas match the ids of the form field -- in the showError function above, the error message span's would all end in "_errorSpan", such as "dateField_errorSpan"

onsuccess="removeError" -- The onsuccess function is run on each form element being validated if it passes the validation. This can be used to remove error messages which are assigned using the onerror handler. For example, the removeError function might look something like:

    function removeError( ) {
    document.getElementById( this.id + '_errorSpan' ).innerHTML = '';
    }

errorMsg="This field did not validate" -- You can assign a custom error message to the form target validation functions using the errorMsg setting.

showAlert="false" -- Allows you to control whether or not a javascript alert() prompt is shown when a validation fails.

focusOnError="true" -- Setting this to true will make the form field which failed validation receive focus when the validation fails. Be careful when using this option in combination with showAlert="true" and onblur="true". Using all 3 settings together is not recommended, as it may lead to an infinite number of alert messages which the user can't get rid of.

onsubmitSuccess="hideSubmitButton" -- This option allows you to specify a custom function which will be run when the form is submitted and all validations have been passed. This is useful for hiding the submit button, among other things, to prevent users from clicking it a second time. This can also be used to submit a form via AJAX instead of the normal POST/GET form.
Event Handlers

onsubmit="true" -- Including onsubmit="true" (which is the default) will run any validations or other settings referenced inside the validation comment when the form is submitted. If the validation fails, the form will not be submitted. You can disable these validations from running onsubmit via onsubmit="false".

onblur="true" -- Runs validations when the form field loses focus, such as via the user pressing Tab or clicking on a different field.

onchange="true" -- Runs validations when the form field onchange event is fired -- mostly useful for select boxes.

onclick="true" -- Runs validations when the form field is clicked on.

onkeydown="true" -- Runs validations when a keydown event is triggered on the form field.

onkeyup="true" -- Runs validations when a keyup event is triggered on the form field.

onkeypress="true" -- Runs validations when a keypress event is triggered on the form field.
Specialized Validation Presets

required="true" -- Loads validation for the form target ensuring that the field is filled out or selected. If the form field accepts text input (textboxes and textareas), then the field will fail validation if it is empty. For radio buttons, the validation will fail if none of the radio buttons are clicked. For checkboxes, the validation will fail if the checkbox is not checked. For a select box, the validation will fail if the select box is left on the default, "empty" option (A selectbox is considered empty if the selected option's value is '', ' ', 0 or ' ', although this setting can be changed, if needed).

requiredAlternatives="checkbox2, checkbox3" -- Specifying a requiredAlternative allows a form field to pass validation if one of the defined alternatives is filled out. This enables you to load a group of checkboxes and allow a form to be submitted if at least one is checked. You can also define 2 textboxes so that at least 1 must be filled out.

maxlength="12" -- Loads a validation check to ensure that the text entered in the field does not exceed the specified number of characters.

minlength="2" -- Loads a validation check to ensure that the text entered in the field is at least as long as the specified number of characters.

expression="numeric$." -- You can use the expression attribute to load character validation for the target form field. A character validation expression allows you to specify which letters, numbers and punctuation are allowed to be entered into the form field. The behavior of the expression attribute is controlled by the mode attribute. If mode="allow" is defined (the default), then a form field will fail character validation if it contains any characters other than those defined in expression. If mode="deny" is defined, then a form field will fail validation if it contains any characters defined in expression.

The following shortcuts are supported inside expression:

    * "numeric" is the same as defining "0123456789"
    * "alpha" is the same as defining "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
    * "alphaUpper" is the same as defining "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    * "alphaLower" is the same as defining "abcdefghijklmnopqrstuvwxyz"
    * Note that shortcuts can be combined (e.g, "alphaLowernumeric")

Thus, when expression="numeric$." mode="allow" is defined, then input will fail validation if it contains any characters other than numbers, periods or dollar signs. (If you wanted to ensure that a dollar sign or period didn't appear multiple times, you would need to use a regular expression, which is described below).

mode="deny" -- Defines the behavior of the character validation expression. See above.

suppress="true" -- When suppress="true" is defined (which is the default when a character expression is defined), then anything which a user types on their keyboard that doesn't conform to the defined character validation will be suppressed from appearing in the textfield. For example, if the user attempts to type the letter 'P' in a textfield with expression="numeric$." defined, the keyboard event will be suppressed and the letter will not appear in the textfield.

regex="^\d{1,3}[a-zA-Z]{1}$" -- Specifies a validation rule using a regular expression (regex). By default, the form field's input must match the regular expression (although this behavior can be reversed through the use of the errorOnMatch argument).

errorOnMatch="true" -- Allows the user to control whether a match against the provided regular expression will result in the form field passing or failing the validation check. The default setting is errorOnMatch="false", which means that if the form field matches the regex, it will pass validation. By specifying errorOnMatch="false", this behavior can be reversed so that when the form field matches the regex, it will result in a validation error.

modifiers="im" -- Allows the user to add modifiers to the regex. Javascript supports 2 modifiers in this context:

    * i for case-insensitive matching
    * m for multi-line mode, in which caret and dollar signs refer to the start and end of a line, rather than start and end of a string

If you are using both modifiers, provide them without a comma in between.
Changing Settings Globally

A number of settings, including some of the ones mentioned above, can be changed globally for all form fields, or alternately, for all fields in a specific form. To change a setting globally, you simply redefine the setting inside a script tag which must appear in the page's HTML below the script tag which loads validanguage. For example:

<script type="text/javascript" src="/path/to/validanguage.js"></script>
<script type="text/javascript">
   validanguage.settings.showAlert = false; //Turn off alerts for all form fields
</script>


Any setting which is defined globally will be overruled if the same setting is also defined for a specific form field. For example, if showAlert were turned off using the global setting above, but one form field contained a validanguage comment with showAlert="true" then the alert would appear for that one field, but not for any others.
The following global settings are available:
Each setting is listed below with its corresponding default value.
Basic Settings:

validanguage.settings.showAlert = true; -- Enables the use of javascript alerts to be turned on/off for the entire page.

validanguage.settings.focusOnError = false; -- Setting this to true will make the form field which failed validation receive focus when the validation fails. Be careful when using this option in combination with showAlert=true and onblur validations. Using all 3 settings together is not recommended, as it may lead to an infinite number of alert messages which the user can't get rid of.

validanguage.settings.validateAllFieldsOnSubmit = false; -- Controls whether all form fields are validated when a form is submitted (true) or whether the validation will stop as soon as one form field has failed validation (false).

validanguage.settings.onsuccess = function() { }; -- Enables the user to define a function which will be run on all form fields which pass validation. Defining this setting globally allows the user to define the function once, instead of needing to do so for each individual field in the form.

validanguage.settings.onerror = function( errMsg ) { }; -- Enables the user to define a function which will be run on all form fields which fail validation.

validanguage.settings.onsubmitSuccess = function() { }; -- This option allows you to specify a custom function which will be run when the form is submitted and all validations have been passed. This is useful for hiding the submit button, among other things, to prevent users from clicking it a second time.

validanguage.settings.errorMsg = 'You have entered an invalid entry in the form'; -- Enables the user to define a default error message which will be used when a form-field-specific errorMsg has not been provided.

validanguage.settings.requiredErrorMsg = 'You have skipped a required field'; -- Enables the user to define a default error message which will be used for the required="true" validation when a form-field-specific errorMsg has not been provided.

validanguage.settings.minlengthErrorMsg = 'The indicated field must be at least {!minlength} characters long'; -- Enables the user to define a default error message which will be used for the minlength validation when a form-field-specific errorMsg has not been provided. When an errorMsg is used with minlength, the {!minlength} placeholder will be replaced with the minimum length defined by the user.

validanguage.settings.maxlengthErrorMsg = 'The indicated field may not be longer than {!maxlength} characters'; -- Enables the user to define a default error message which will be used for the maxlength validation.

validanguage.settings.characterValidationErrorMsg = 'You have entered invalid characters'; -- Enables the user to define a default error message which will be used for the expression="numeric" style character validation.

validanguage.settings.emptyOptionElements = array(' ', '0', ' ', ''); -- This array is used in the required="true" validation to determine whether a select box has been left on the default, "empty" option. If the currently selected option element in the select has a value included in this array, then it will fail the validation. Add/Remove from this array as needed.

validanguage.settings.defaultValidationHandlers = array('submit'); -- When a validation function is supplied without any event handlers mentioned in the comment, this setting determines which event handler(s) will be used as the default.
Advanced Settings:

Note: At this point in the documentation, we begin getting into some of the more advanced and developer-centric options in validanguage. If you are unfamiliar with javascript programming, you may find the next few pages of documentation to be more technical and confusing than the options which have already been described.

validanguage.settings.loadCommentAPI = true; -- If you are using the JSON-based API instead of the Comment-based API, you can turn off the parsing of comments by using this setting. Alternately, you can delete the entire loadCommentAPI function and the point where it is called in the source code to achieve a smaller file size.

validanguage.settings.validationErrorColor = '#FF6666'; -- Color for a textbox to flash when invalid input is entered and the expression="numeric" style character validation is defined. Default is light red. Set this to empty to turn flashing off.

validanguage.settings.normalTextboxColor = ''; -- Normal color of a textbox. Used in conjunction with validationErrorColor to make the textboxes flash.

validanguage.settings.timeDelay = 100; -- Amount of time the text box flashes the validationErrorColor. Default is 100ms.

validanguage.settings.validateRequiredAlternativesOnclick = true; -- Controls whether the validateRequiredAlternatives function will be assigned as an onclick event to any radio buttons and checkboxes named as "requiredAlternatives". This enables the onsuccess function to be triggered when a requiredAlternative is selected (or have onerror be triggered when a checkbox is unchecked). Otherwise, an error message would still be displayed, even when a valid selection had been made.

validanguage.settings.errorOnMatch = false; -- Defines the default behavior of the regex validations. Specifies whether a match against the regex is an error or a success.
Loading the Above Settings as Form-Specific instead of Global

If you have 2 forms on the same page and would like to define separate global settings for each form, this can be done easily. Within validanguage's populate() method, the program determines how many forms are on the page and creates separate copies of validanguage.settings for each form, which are then used as the global setting for all fields within each form. This is a form of inheritance which allows you to first define settings that all forms will inherit, and then, if needed, you can step in and change the form-specific settings at will by defining a custom validanguage.overloadFormSettings() function. All form-specific settings are stored in the validanguage.forms.{$id_of_the_form}.settings

Here is an example of how you might combine global settings and form-specific settings:

<script type="text/javascript">
   validanguage.settings.showAlert = false; //set this for ALL forms
   validanguage.overloadFormSettings = function() {
      validanguage.settings.forms.form1.onerror = 'showErrorsForForm1';
      validanguage.settings.forms.form2.onerror = 'showErrorsForForm2';
   }
</script>

API #2 -- The JSON-based API

The Comment-based API is actually converted into the JSO-based API within validanguage before the validation event handlers are assigned. Thus, all functionality available in the Comment-based API is available in the JSON API. However, you may find that the Comment API is easier and faster to work with. Or, you may prefer using the JSON API for the added flexibility it gives you.

Another consideration may be Konqueror support. Since Konqueror does not recognize HTML comments as part of the DOM, any validations loaded with the Comment-based API will be ignored by Konqueror. The same validation loaded via the JSON API will work in Konqueror. (I plan on making the Comment API work with Konqueror in the near future via an AJAX request to retrieve then parse the current document).

The validanguage JSON API is used by adding rules to the validanguage.el object, grouped by form field. For example:

validanguage.el.date: {
   characters: {
      mode: 'allow',
      expression: 'numeric/-',
      suppress: true,
      errorMsg: 'You have entered invalid characters'
   },
   required: true,
   errorMsg: 'Please enter a valid birthday.',
   validations: [
      {
      name: 'validanguage.validateUSDate',
      errorMsg: 'Please enter a valid birthday'
      }
   ]
};
validanguage.el.socks: {
   required: true,
   maxlength: 8,
   minlength: 6
};

Assuming you're familiar with JSON and have read through the documentation in the Comment API section above, then the above example should be pretty self-explanatory.
List of Options Available in the JSON-based API
Options available within validanguage.el.{$form_field_id}

onerror: 'showError, updateForm'
onsuccess: [hideError, updateStatus] -- The onsuccess and onerror handlers are available on the JSON API and allow the user to specify which functions to be run by using one or more of the following methods:

   1. A string containing the name of a single function or object method
   2. A string containing a comma-separated list of function/method names
   3. A function reference
   4. An array containing multiple values, each of which can be of any of the previously listed types

errorMsg: 'This field did not validate' -- This error message would be used for all validations listed for this form field (unless overidden by a more specific message).

showAlert: false -- Allows you to control whether or not a javascript alert() prompt is shown for this form field only.

focusOnError: true -- Setting this to true will make the form field which failed validation receive focus when the validation fails. Be careful when using this option in combination with showAlert="true" and onblur="true".

onsubmit: true
onblur: true,
onchange: true,
etc. -- Defining the event handlers within Validanguage.el.{$form_field_id} will make the defined handlers apply to all the validations for this form field unless a given function has its own event handlers assigned (which would have a higher specificity and override the more general ones defined on the element for that function)

required: true -- Loads the validateRequired validation within the corresponding validations array of functions.

requiredAlternatives: 'checkbox1,checkbox' -- You can specify the requiredAlternatives as either a comma-separated list of element IDs or as an array of element IDs.

minlength: 4,
maxlength: 12 -- Adds the minlength and maxlength validations.
Defining Custom Validations in validanguage.el.{$form_field_id}.validations

Custom validation functions are defined in the validanguage.el.{$form_field_id}.validations array, which is an array of custom objects, with each object containing details on a single validation function. The example below lists 2 custom validations and demonstrates all the supported options:

validanguage.el.login_name: {
   validations: [
      {
         name: 'checkSpelling',
         onblur: true,
         onsubmit: true,
         errorMsg: 'You have misspelled your login name'
      },

      {
         name: 'ajaxLoginCheck',
         onblur: true,
         onsubmit: false,
         errorMsg: 'Your login name is already in use. Please select another.'
      },

   ]
};

Defining Character Validation in validanguage.el.{$form_field_id}.characters

Character validation can be defined using the characters object with the same parameters available in the Comment-based API, as illustrated in the following example:

validanguage.el.date: {
   characters: {
      mode: 'allow',
      expression: 'numeric/-',
      suppress: true,
      errorMsg: 'You have entered invalid characters'
   }
};

Defining Validations with Regular Expressions in validanguage.el.{$form_field_id}.regex

Regex-based validation can be defined using the regex object with the same parameters available in the Comment-based API, as illustrated in the following example:

validanguage.el.money: {
   regex: {
      expression: /^[\$]{0,1}[0-9]+[\.]{0,1}[0-9]?$[/,   //You can supply either a string or a Regex to the expression argument
      errorOnMatch: false,       //This is the default option and doesnt really need to be specified
      errorMsg: 'You have not entered a valid amount'
   }
};

Final Thoughts

If you have any feedback or questions about validanguage, feel free to drop me an email or IM. My contact info is listed on the Contact page. Please let me know if you are using validanguage and what you think.

You can also check my Blog for updates and any bugs I may have found or new features I'll be adding.