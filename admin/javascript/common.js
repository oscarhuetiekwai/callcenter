var isMozilla;
var objDiv = null;
var originalDivHTML = "";
var DivID = "";
var over = false;

function createXmlHttpRequestObject() {
	var xmlHttp;
	
	try {
		xmlHttp = new XMLHttpRequest();
	} catch (e) {
		var arrayHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										  "MSXML2.XMLHTTP.5.0",
										  "MSXML2.XMLHTTP.4.0",
										  "MSXML2.XMLHTTP.3.0",
										  "MSXML2.XMLHTTP.2.0",
										  "MSXML2.XMLHTTP",
										  "Microsoft.XMLHTTP");
		
		for(var i=0; i<arrayHttpVersions && !xmlHttp; i++ ) {
			try {
				xmlHttp = new ActiveXObject(arrayHttpVersions[i]);
			} catch (e) {
			}
		}
	}
	
	if ( !xmlHttp ) {
		// display error message
	} else {
		return xmlHttp;
	}
}

function createDimmerDiv() {
	document.write('<div id="dimmer" class="dimmer" style"width:' + window.screen.width +
				   'px;height:' + window.screen.height + 'px"></div>');
}

function displayFloatingDiv(divId, title, width, height, left, top) 
{
	DivID = divId;

	document.getElementById('dimmer').style.visibility = "visible";

    document.getElementById(divId).style.width = width + 'px';
    document.getElementById(divId).style.height = height + 'px';
    document.getElementById(divId).style.left = left + 'px';
    document.getElementById(divId).style.top = top + 'px';
	
	var addHeader;
	
	if (originalDivHTML == "")
	    originalDivHTML = document.getElementById(divId).innerHTML;
	
	addHeader = '<table style="width:' + width + 'px" class="floatingHeader">' +
	            '<tr><td ondblclick="void(0);" onmouseover="over=true;" onmouseout="over=false;" style="cursor:move;height:18px">' + title + '</td>' + 
	            '<td style="width:18px" align="right"><a href="javascript:hiddenFloatingDiv(\'' + divId + '\');void(0);">' + 
	            '<img alt="Close..." title="Close..." src="close.jpg" border="0"></a></td></tr></table>';
	

    // add to your div an header	
	document.getElementById(divId).innerHTML = addHeader + originalDivHTML;
	
	
	document.getElementById(divId).className = 'dimming';
	document.getElementById(divId).style.visibility = "visible";


}


//
//
//
function hiddenFloatingDiv(divId) 
{
	document.getElementById(divId).innerHTML = originalDivHTML;
	document.getElementById(divId).style.visibility='hidden';
	document.getElementById('dimmer').style.visibility = 'hidden';
	
	DivID = "";
}

//
//
//
function MouseDown(e) 
{
    if (over)
    {
        if (isMozilla) {
            objDiv = document.getElementById(DivID);
            X = e.layerX;
            Y = e.layerY;
            return false;
        }
        else {
            objDiv = document.getElementById(DivID);
            objDiv = objDiv.style;
            X = event.offsetX;
            Y = event.offsetY;
        }
    }
}


//
//
//
function MouseMove(e) 
{
    if (objDiv) {
        if (isMozilla) {
            objDiv.style.top = (e.pageY-Y) + 'px';
            objDiv.style.left = (e.pageX-X) + 'px';
            return false;
        }
        else 
        {
            objDiv.pixelLeft = event.clientX-X + document.body.scrollLeft;
            objDiv.pixelTop = event.clientY-Y + document.body.scrollTop;
            return false;
        }
    }
}

//
//
//
function MouseUp() 
{
    objDiv = null;
}


function runEffect(szObjectName)
{
	var objEffect = document.getElementById(szObjectName);
	var hashOptions = {};
	
	if ( objEffect.style.display != "none" && objEffect.style.display != "hide" ) {
		$( "#" + szObjectName ).hide( "blind", hashOptions, 1000 );
	} else {
		$( "#" + szObjectName ).show( "blind", hashOptions, 500 );
	}
}
//
//
//
function init()
{
    // check browser
    isMozilla = (document.all) ? 0 : 1;


    if (isMozilla) 
    {
        document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE | Event.MOUSEUP);
    }

    document.onmousedown = MouseDown;
    document.onmousemove = MouseMove;
    document.onmouseup = MouseUp;

    // add the div
    // used to dim the page
	buildDimmerDiv();

}

// call init
init();