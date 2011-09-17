function kisskontentPreview(baseUri, p)
{
	var preview = document.id('kisskontentPreview');
	var kontent = document.id('kisskontentKontent');

    new Request({
    	url: baseUri + 'index.php?option=com_kisskontent'
    	+ '&task=preview&tmpl=component&format=raw'
    	+ '&p=' + p,

		method: 'post',
		data: 'kontent=' + kontent.value,
		
		//onRequest..
		
		onSuccess: function(response) {
			preview.set('html', response);
			preview.set('class', 'kisskuntentPreview');
		}
	}).send();
}//function
