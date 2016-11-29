	var xmlhttp,alerted
	/*@cc_on @*/
	/*@if (@_jscript_version >= 5)
	  try {
	  xmlhttp=new ActiveXObject("Msxml2.XMLHTTP")
	 } catch (e) {
	  try {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
	  } catch (E) {
	   alert("You must have Microsofts XML parsers available")
	  }
	 }
	@else
	 alert("Diperlukan JScript versi 5 or keatas.")
	 xmlhttp=false
	 alerted=true
	@end @*/
	if (!xmlhttp && !alerted) {
	 try {
	  xmlhttp = new XMLHttpRequest();
	 } catch (e) {
	  alert("Anda membutuhkan Browser yang mendukung fasilitas XMLHttpRequest Object.\nMozilla 0.9.5 atau IE5 keatas mendukung fasilitas ini. Mohon ganti Browser Anda.")
	 }
	}

	function RSchange() {
	 if (xmlhttp.readyState==4) {
		//document.write (xmlhttp.responseText)
		document.getElementById('StatistikContainer').innerHTML = xmlhttp.responseText
	 }
	}
	
	function go(PageParam) {
	if (xmlhttp) { 
	  d=document
	  xmlhttp.open("GET", PageParam,true);
	  xmlhttp.onreadystatechange=RSchange
	  xmlhttp.send(null)
	 }
	}
