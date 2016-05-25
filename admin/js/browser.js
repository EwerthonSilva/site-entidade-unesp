//**************************
// Detects if the current device is an iPhone.
function isIphone()
{
	var uagent = navigator.userAgent.toLowerCase();
	if (uagent.search("iphone") > -1)
		return true;
	return false;
}

//**************************
// Detects if the current device is an iPod Touch.
function isIpod()
{
	var uagent = navigator.userAgent.toLowerCase();
	if (uagent.search("ipod") > -1)
		return true;
	return false;
}

//**************************
// Detects if the current device is an iPad.
function isIpad()
{
	// For use within normal web clients 
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	if(isiPad){
		return true;
	}
	return false;
	// For use within iPad developer UIWebView
	// Thanks to Andrew Hedges!
	//var ua = navigator.userAgent;
	//var isiPad = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);

}

//**************************
// Detects if the current device is an Android.
function isAndroid()
{
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
	if(isAndroid){
		return true;
	}
	return false;
}

//**************************
// Detects if the current device is an Internet Explorer.
function isIe()
// Returns the version of Windows Internet Explorer or a -1
// (indicating the use of another browser).
{
	var rv = -1; // Return value assumes failure.
	if (navigator.appName == 'Microsoft Internet Explorer') { return true; }
	return false;
}

function ieVersion()
// Returns the version of Windows Internet Explorer or a -1
// (indicating the use of another browser).
{
   var rv = -1; // Return value assumes failure.
   if (navigator.appName == 'Microsoft Internet Explorer')
   {
      var ua = navigator.userAgent;
      var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
      if (re.exec(ua) != null)
         rv = parseFloat( RegExp.$1 );
   }
   return rv;
}
