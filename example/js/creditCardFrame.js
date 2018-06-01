/**
 * Credit card javascript for payment frame
 * 
 * This is a coding example for credit and debit card integration
 * using Heidelpay Payment Frame
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 * @autor  Jens Richter
 * @category example
 */

var paymentFrameIframe = document.getElementById('paymentIframe');


/**
 * Get hostname and protocol from paymentIframe
 */
targetOrigin = getDomainFromUrl(paymentFrameIframe.src);

/**
 * Get the form element
 */

paymentFrameForm = document.getElementById('paymentFrameForm');


/**
 * Add an event listener to from submit, which will execute the sendMessage function
 */
if (paymentFrameForm.addEventListener) {// W3C DOM 
	paymentFrameForm.addEventListener('submit', sendMessage); }
else if (paymentFrameForm.attachEvent) { // IE DOM 
	paymentFrameForm.attachEvent('onsubmit', sendMessage); 
	}

/**
 * Define send Message function
 * This function will collect each inpunt inside the form and then submit
 * the iframe to the payment server. Please note that it is not allowed to submit
 * credit or debit card information form your webpage.
 */

function sendMessage(e) {
	
	if(e.preventDefault) { e.preventDefault(); } 
	else { e.returnValue = false; }
	
	var data = {}; 
	
	/**
	 * Collection form input fields
	 */
	for (var i = 0, len = paymentFrameForm.length; i < len; ++i) { 
		var input = paymentFrameForm[i]; 
		if (input.name) { data[input.name] = input.value; } 
		
	}
	
	/**
	 * Send html postmessage to payment frame
	 */
	paymentFrameIframe.contentWindow.postMessage(JSON.stringify(data), targetOrigin);
}

/**
 * Function to get the domain from a given url 
 */
function getDomainFromUrl(url) { 
	var arr = url.split("/"); 
	return arr[0] + "//" + arr[2]; 
	}


/**
 * Add an listener to your webpage, which will recieve the response message
 * from payment server.
 */
if (window.addEventListener) { // W3C DOM
	window.addEventListener('message', receiveMessage);
		
	} 
else if (window.attachEvent) { // IE DOM 
	window.attachEvent('onmessage', receiveMessage); 
	}

/**
 * Define receiveMessage function
 *
 * This function will recieve the response message form the payment server.
 */
function receiveMessage(e) { 
	
	if (e.origin !== targetOrigin) {
		return; 
	}
	
	var antwort = JSON.parse(e.data);
	console.log(antwort);
}



