function googleTranslate(src, dst)
{
	var source = document.id(src);
	var destination = document.id(dst);
	
	alert(source.value);
	
	new Request({
		url : 'index.php?option=com_kisskontent&task=translator.googleTranslate' + '&tmpl=component&format=raw',

		method : 'post',
		data: 'text=' + source.value,

		// onRequest..

		onSuccess : function(response) {
			destination.set('value', response);
		}
	}).send();

}

var KISSTranslator = new Class({
    
    /**
     * Copy the original to the translated field
     */
    copyTrans : function()
    {
        var s = this.stripQuotes(php2js.trim($('default').innerHTML));
        $('translation').value = s;
        $('translation').focus();
    },// function
    
    /**
     * Translate with the Google translation API
     */
    google_translate : function(langSrc, langTgt, idSrc, idTgt)
    {
//        $('translation').value = jgettext('Translating...');
//alert('aha'+langSrc+langTgt+idSrc+idTgt);
        if(0)// ! gbranding_displayed)
        {
            google.language.getBranding('gtranslate_branding');
            gbranding_displayed = true;
        }
        
        var text = document.id(idSrc).value;
        
        text =(undefined == text) ? document.id(idSrc).innerHTML : text;

        if(undefined == text)
        {
        	return false;
       	}
        
 //       text = text.replace(/<br\s*\/?>/mg,"\\n\\r")

//      var text = this.stripQuotes(php2js.trim($('default').innerHTML));
//        var text = this.stripQuotes(php2js.trim($('default').innerHTML));
//
        google.language.translate(text, '', langTgt, function(result)
        {
            if( ! result.error)
            {
//            	var r = result.translation.replace(/<br\s*\/?>/mg,"\n");
            	 //       text = text.replace(/<br\s*\/?>/mg,"\\n\\r")
            	
                document.id(idTgt).value = result.translation.replace(/<br\s*\/?>/mg,"\n");
            }
        });
    },//function
    
    loadTranslation : function(p, target, lang)
    {
    	console.log(p, target, lang);
    	
    	var destination = document.id(target);
    	var destination2 = document.id(target+'Esc');
    console.log(destination);
    console.log(destination2);
    	new Request({
    		url : 'index.php?option=com_kisskontent&task=translator.load' 
    			+ '&tmpl=component&format=raw',

    		method : 'post',
    		data: 'p=' + p,

    		// onRequest..

    		onSuccess : function(response) {
//    			alert(response);
    			destination.set('html', response);
    			destination2.set('html', response);
    		}
    	}).send();

    }//function

    
});
