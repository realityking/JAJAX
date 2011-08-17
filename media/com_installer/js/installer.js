/**
 * @version		$Id: installer.js 21554 2011-06-17 14:36:22Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Extension installation via JavaScript
 */
Joomla = Joomla || {};

Joomla.Installer = new Class({
	initialize: function(view, fileBt, directoryBt, urlBt, fileInput) {
		fileBt = document.id(fileBt);
		directoryBt = document.id(directoryBt);
		urlBt = document.id(urlBt);
		this.fileInput = document.id(fileInput);
		
		Joomla.spinner = new Spinner(view);
		
		fileBt.addEvent('click', function(e) {
			event.target.form.installtype.value = 'upload';
			event.target.form.submit();
		});

		directoryBt.addEvent('click', function(e){
			this.install(e);
		}.bind(this));
		urlBt.addEvent('click', function(e){
			this.install(e);
		}.bind(this));

		FormData.extend = function(){};
		new Type('FormData', FormData);
	},
	
	install: function(event) {
		event.target.form.installtype.value = event.target.get('data-installtype');
		this.removeExtensionMessage();

		var req = new Request.JSON({
			method: 'post',
			url: event.target.form.action,
			onRequest: function() {
				Joomla.spinner.show(true);
			},
			onSuccess: function (r) {
				Joomla.replaceTokens(r.token);

				Joomla.spinner.hide(true);
				if (r.messages) {
					Joomla.renderMessages(r.messages);
				}
				if (r.data.redirect) {
					window.location = r.data.redirect;
				}

				this.renderExtensionMessage(r.data.message, r.data.extensionmessage);
			}.bind(this),
			
			onFailure: function(xhr) {
				var r = JSON.decode(xhr.responseText);

				Joomla.spinner.hide(true);
				if (r) {
					Joomla.replaceTokens(r.token);
					if (r.messages) {
						Joomla.renderMessages(r.messages);
					}
					alert(r.message);
				}
			}
		}, this);
		req.post(event.target.form.toQueryString()+'&format=json');
	},
	
	renderExtensionMessage: function(message, extensionmessage) {
		if (!(message || extensionmessage)) {
			return;
		}
		var container = document.id('installmessage');

		var table = new Element('table', {
			class: 'adminform'
		});
		var tbody = new Element('tbody');

		if (message) {
			var tr1 = new Element('tr');
			var th = new Element('th', {
				html: message
			}).inject(tr1);
			tr1.inject(tbody);
		}

		if (extensionmessage) {
			var tr2 = new Element('tr');
			var td = new Element('td', {
				html: extensionmessage
			}).inject(tr2);
			tr2.inject(tbody);
		}
		
		tbody.inject(table);
		table.inject(container);
	},
	
	removeExtensionMessage: function() {
		$$('#installmessage > *').destroy();
	}
});
