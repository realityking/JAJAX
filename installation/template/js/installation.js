/**
 * @version		$Id: installation.js 21960 2011-08-12 21:58:56Z dextercowley $
 * @package		Joomla.Installation
 * @subpackage	JavaScript
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Only define the Install namespace if not defined.
if (typeof(Install) === 'undefined') {
	var Install = {};
};

Install.submitform = function() {
	var url = baseUrl+'?tmpl=body';
	var form = document.id('adminForm');
	
	if (Install.busy) {
		return false;
	}

	var req = new Request.JSON({
		method: 'post',
		url: url,
		onRequest: function() {
			Install.spinner.show(true);
			Install.busy = true;
			Joomla.removeMessages();
		},
		onSuccess: function(r) {
			var lang = $$('html').getProperty('lang')[0];
			if (r.messages) {
				Joomla.renderMessages(r.messages);
			}
			if (lang.toLowerCase() === r.lang.toLowerCase()) {
				Install.goToPage(r.data.view);
			} else {
				window.location = baseUrl+'?view='+r.data.view;
			}
		},
		onFailure: function(xhr) {
			Install.spinner.hide(true);
			Install.busy = false;
			var r = JSON.decode(xhr.responseText);
			if (r) {
				Joomla.replaceTokens(r.token);
				alert(r.message);
			}
		}
	});
	req.post(form.toQueryString()+'&task='+form.task.value+'&format=json');

	return false;
};

Install.goToPage = function(page) {
	var url = baseUrl+'?tmpl=body&view='+page;
	var req = new Request.HTML({
		method: 'get',
		url: url,
		onSuccess: function (r) {
			document.id('rightpad').empty().adopt(r);
			Install.spinner.hide(true);
			Install.busy = false;

			//Re-attach the validator
			var forms = $$('form.form-validate');
			forms.each(function(form){ this.attachToForm(form); }, document.formvalidator);
			Install.addToggler();

			//Take care of the sidebar
			var active = $$('.active');
			active.removeClass('active');
			var nextStep = document.id(page);
			nextStep.addClass('active');
		}
	}).send();

	return false;
};

Install.addToggler = function () {
	new Accordion($$('h4.moofx-toggler'), $$('div.moofx-slider'), {
		onActive: function(toggler, i) {
			toggler.addClass('moofx-toggler-down');
		},
		onBackground: function(toggler, i) {
			toggler.removeClass('moofx-toggler-down');
		},
		duration: 300,
		opacity: false,
		alwaysHide:true,
		show: 1
	}); 
};

window.addEvent('domready', function() {
	Install.addToggler;
	Install.spinner = new Spinner('rightpad');
});

/**
 * Method to install sample data via AJAX request.
 */
Install.sampleData = function(el, filename) {
	// make the ajax call
	el = document.id(el);
	filename = document.id(filename);
	var req = new Request.JSON({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.loadSampleData', 'format':'json'},
		onRequest: function() {
			el.set('disabled', 'disabled');
			filename.set('disabled', 'disabled');
			document.id('theDefaultError').setStyle('display','none');
		},
		onSuccess: function(r) {
			if (r) {
				Joomla.replaceTokens(r.token);
				if (r.error == false) {
					el.set('value', r.data.text);
					el.set('onclick','');
					el.set('disabled', 'disabled');
					filename.set('disabled', 'disabled');
					document.id('jform_sample_installed').set('value','1');
				} else {
					document.id('theDefaultError').setStyle('display','block');
					document.id('theDefaultErrorMessage').set('html', r.message);
					el.set('disabled', '');
					filename.set('disabled', '');
				}
			} else {
				document.id('theDefaultError').setStyle('display','block');
				document.id('theDefaultErrorMessage').set('html', response );
				el.set('disabled', 'disabled');
				filename.set('disabled', 'disabled');
			}
		},
		onFailure: function(xhr) {
			var r = JSON.decode(xhr.responseText);
			if (r) {
				Joomla.replaceTokens(r.token);
				document.id('theDefaultError').setStyle('display','block');
				document.id('theDefaultErrorMessage').set('html', r.message);
			}
			el.set('disabled', '');
			filename.set('disabled', '');
		}
	}).send();
};

/**
 * Method to detect the FTP root via AJAX request.
 */
Install.detectFtpRoot = function(el) {
	// make the ajax call
	el = document.id(el);
	var req = new Request.JSON({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.detectFtpRoot', 'format':'json'},
		onRequest: function() { el.set('disabled', 'disabled'); },
		onFailure: function(xhr) {
			var r = JSON.decode(xhr.responseText);
			if (r) {
				Joomla.replaceTokens(r.token)
				alert(xhr.status+': '+r.message);
			} else {
				alert(xhr.status+': '+xhr.statusText);
			}
		},
		onSuccess: function(r) {
			if (r) {
				Joomla.replaceTokens(r.token)
				if (r.error == false) {
					document.id('jform_ftp_root').set('value', r.data.root);
				} else {
					alert(r.message);
				}
			}
			el.set('disabled', '');
		}
	}).send();
};

/**
 * Method to detect the FTP root via AJAX request.
 */
Install.verifyFtpSettings = function(el) {
	// make the ajax call
	el = document.id(el);
	var req = new Request.JSON({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.verifyFtpSettings', 'format':'json'},
		onRequest: function() { el.set('disabled', 'disabled'); },
		onFailure: function(xhr) {
			var r = JSON.decode(xhr.responseText);
			if (r) {
				Joomla.replaceTokens(r.token)
				alert(xhr.status+': '+r.message);
			} else {
				alert(xhr.status+': '+xhr.statusText);
			}
		},
		onSuccess: function(r) {
			if (r) {
				Joomla.replaceTokens(r.token)
				if (r.error == false) {
					alert(Joomla.JText._('INSTL_FTP_SETTINGS_CORRECT'));
				} else {
					alert(r.message);
				}
			}
			el.set('disabled', '');
		},
		onError: function(response) {
			alert('error');
		}
	}).send();
};

/**
 * Method to remove the installation Folder after a successful installation.
 */
Install.removeFolder = function(el) {
	el = document.id(el);
	var req = new Request.JSON({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.removeFolder', 'format':'json'},
		onRequest: function() {
			el.set('disabled', 'disabled');
			document.id('theDefaultError').setStyle('display','none');
		},
		onComplete: function(r) {
			if (r) {
				Joomla.replaceTokens(r.token);
				if (r.error == false) {
					el.set('value', r.data.text);
					el.set('onclick','');
					el.set('disabled', 'disabled');
				} else {
					document.id('theDefaultError').setStyle('display','block');
					document.id('theDefaultErrorMessage').set('html', r.message);
					el.set('disabled', '');
				}
			} else {
				document.id('theDefaultError').setStyle('display','block');
				document.id('theDefaultErrorMessage').set('html', response );
				el.set('disabled', 'disabled');
			}
		},
		onFailure: function(xhr) {
			var r = JSON.decode(xhr.responseText);
			if (r) {
				Joomla.replaceTokens(r.token);
				document.id('theDefaultError').setStyle('display','block');
				document.id('theDefaultErrorMessage').set('html', r.message);
			}
			el.set('disabled', '');
		}
	}).send();
};
