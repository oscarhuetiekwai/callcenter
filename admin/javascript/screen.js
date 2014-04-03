/**
 * @author Joseph Ian G. Farillas
 * Created Date: 08-26-2010
 * joseph@accordiasolution.com
 */

$(document).ready(function()
{
	var wt;
    var ht;
 
	 // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
	 
	 if (typeof window.innerWidth != 'undefined')
	 {
	      wt = $(window).width();
		  ht = $(window).height();
		  //alert(wt + "-" + ht);
	 }
	 
	// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
	
	 else if (typeof document.documentElement != 'undefined'
	     && typeof document.documentElement.clientWidth !=
	     'undefined' && document.documentElement.clientWidth != 0)
	 {
	       wt = document.documentElement.clientWidth,
	       ht = document.documentElement.clientHeight
	 }
	 
	 // older versions of IE
	 
	 else
	 {
	       wt = document.getElementsByTagName('body')[0].clientWidth,
	       ht = document.getElementsByTagName('body')[0].clientHeight
	 }
	 
    // make the height of left panel based on the user's screen
	$(".placeholder").css("width", + screenWidth(wt) + "px");
	$(".leftnav").css("height", "" + screenHeight(ht) + "px");
	$(".divcontainer").css("height", "" + screenHeight(ht) + "px");
	$(".divcontainer").css("width", "" + screenWidth((wt - 288)) + "px");
	
	//function for left panel height
	function screenHeight(htt)
	{
		return parseInt(htt) - 140;
	}
	
	//function for width
	function screenWidth(wtt)
	{
		return parseInt(wtt) - 2;
	}

}
);
