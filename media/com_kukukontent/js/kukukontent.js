function kukukontentPreview(baseUri, p)
{
	var preview = document.id('kukukontentPreview');
	var kontent = document.id('kukukontentKontent');

    new Request({
    	url: baseUri + 'index.php?option=com_kukukontent'
    	+ '&task=preview&tmpl=component&format=raw'
    	+ '&p=' + p,

		method: 'post',
		data: 'kontent=' + kontent.value,
		
		//onRequest..
		
		onSuccess: function(response) {
			preview.set('html', response);
			preview.set('class', 'kukukuntentPreview');
		}
	}).send();
}//function
