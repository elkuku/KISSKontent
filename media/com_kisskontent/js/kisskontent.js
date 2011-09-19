function kisskontentPreview(baseUri, p)
{
	var preview = document.id('kisskontentPreview');
	var kontent = document.id('kisskontentKontent');

	preview.set('html', '');

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

function kisskontentDifferences(baseUri, p)
{
	var preview = document.id('kisskontentPreview');
	var kontent = document.id('kisskontentKontent');
	
	var diffAll =(document.id('chkDiffAll').checked) ? '&diffAll=1' : '';
	
	preview.set('html', '');

    new Request({
    	url: baseUri + 'index.php?option=com_kisskontent'
    	+ '&task=differences&tmpl=component&format=raw'
    	+ '&p=' + p
    	+ diffAll,

		method: 'post',
		data: 'kontent=' + kontent.value,
		
		//onRequest..
		
		onSuccess: function(response) {
			preview.set('html', response);
			preview.set('class', 'kisskuntentPreview');
		}
	}).send();
}//function

