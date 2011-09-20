function loadDiff(href) {
	var container = document.id('kissDiffContainer');
	
	new Request({
		url : href + '&tmpl=component&format=raw',

		method : 'post',

		// onRequest..

		onSuccess : function(response) {
			container.set('html', response);
		}
	}).send();

	return false;
}