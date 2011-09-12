function kukukontentPreview(baseUri)
{
	var kontent = document.id('kukukontentKontent').value;

var req = new Request({
		method: 'post',
		data: 'kontent='+kontent,
		url: baseUri+'index.php?option=com_kukukontent&task=preview&tmpl=component&format=raw',
		
		onSuccess: function(response) {
			document.id('kukukontentPreview').set('html', response);
		}
	});
	
	req.send();
	
	//alert('hu');
}