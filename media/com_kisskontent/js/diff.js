/**
 * @package    KISSKontent
 * @subpackage JavaScript
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

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