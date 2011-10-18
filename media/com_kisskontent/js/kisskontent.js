/**
 * @package    KISSKontent
 * @subpackage JavaScript
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

var KISSKontent = new Class
({
    initialize: function(container, rootURI, p) 
    {
        this.container = container;
        this.rootURI = rootURI
        this.p = p;
        this.spinner = new Spinner(this.container);
    },//function
    
    preview: function() 
    {
        var preview = document.id('kisskontentPreview');
        var kontent = document.id('kisskontentKontent');

        preview.set('html', '');

        new Request({
            url: this.rootURI + 'index.php?option=com_kisskontent'
            + '&task=preview&tmpl=component&format=raw'
            + '&p=' + this.p,

            method: 'post',
            data: 'kontent=' + kontent.value,
            
            onRequest: function() 
            {
                this.spinner.show(true);
            }.bind(this),
            
            onSuccess: function(response) 
            {
                this.spinner.hide(true);
                preview.set('html', response);
                preview.set('class', 'kisskuntentPreview');
            }.bind(this),
            
            onFailure: function(xhr)
            {
                this.spinner.hide(true);
                var r = JSON.decode(xhr.responseText);
                if (r) 
                {
                    Joomla.replaceTokens(r.token);
                    alert(r.message);
                }
            }.bind(this)
        }).send();
    },//function
    
    differences: function() 
    {
        var preview = document.id('kisskontentPreview');
        var kontent = document.id('kisskontentKontent');
        
        var diffAll =(document.id('chkDiffAll').checked) ? '&diffAll=1' : '';
        
        preview.set('html', '');

        new Request
        ({
            url: this.rootURI + 'index.php?option=com_kisskontent'
            + '&task=differences&tmpl=component&format=raw'
            + '&p=' + this.p
            + diffAll,

            method: 'post',
            data: 'kontent=' + kontent.value,
            
            onRequest: function() 
            {
                this.spinner.show(true);
            }.bind(this),
            
            
            onSuccess: function(response) 
            {
                this.spinner.hide(true);
                preview.set('html', response);
                preview.set('class', 'kisskuntentPreview');
            }.bind(this),
            
            onFailure: function(xhr) 
            {
                this.spinner.hide(true);
                var r = JSON.decode(xhr.responseText);
                if (r) 
                {
                    Joomla.replaceTokens(r.token);
                    alert(r.message);
                }
            }.bind(this)
        }).send();
    },//function
    
    help: function(div) 
    {
        var container = document.id(div);
        
        container.set('html', '');

        new Request({
            url: 'index.php?option=com_kisskontent'
            + '&tmpl=component&format=raw'
            + '&task=help',

            method: 'post',
            
            onRequest: function() 
            {
                this.spinner.show(true);
            }.bind(this),
            
            onSuccess: function(response) 
            {
                this.spinner.hide(true);
                container.set('html', response);
                container.set('class', 'kisskuntentPreview');
            }.bind(this),

            onFailure: function(xhr) 
            {
                this.spinner.hide(true);
                var r = JSON.decode(xhr.responseText);
                if (r) 
                {
                    Joomla.replaceTokens(r.token);
                    alert(r.message);
                }
            }.bind(this)
        }).send();
    },//function
    
    loadDiff: function(href) 
    {
    	var container = document.id('kissDiffContainer');
    	
    	new Request({
    		url : href + '&tmpl=component&format=raw',

    		method : 'post',

            onRequest: function() 
            {
                this.spinner.show(true);
            }.bind(this),
            
    		onSuccess : function(response) {
                this.spinner.hide(true);
    			container.set('html', response);
    		}.bind(this),//function
    		
            onFailure: function(xhr) 
            {
                this.spinner.hide(true);
                var r = JSON.decode(xhr.responseText);
                if (r) 
                {
                    Joomla.replaceTokens(r.token);
                    alert(r.message);
                }
            }.bind(this)
    	}).send();

    	return false;
    },//function
});//class
