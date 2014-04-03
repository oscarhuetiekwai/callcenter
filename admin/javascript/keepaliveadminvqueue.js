function setTenantID(tenantID) {
	nTenantID = tenantID;
}

function addHttpRequest(szModule, szRequest) {
	var arrayRequest = new Array();
	var tmNow = new Date();

	if ( szRequest.length > 0 )
		szRequest += ("&tm=" + tmNow.getTime());
	else
		szRequest += ("tm=" + tmNow.getTime());
	arrayRequest[0] = szModule;
	arrayRequest[1] = szRequest;
	
	arrayHttpRequest.push(arrayRequest);
}

function includeJavaScript(jsFile)
{
  document.write('<script type="text/javascript" src="'
    + jsFile + '"></scr' + 'ipt>'); 
}

function startKeepAlive() {
	if ( xmlHttpKeepAlive ) {
		try {
			if ( xmlHttpKeepAlive.readyState == 4 || xmlHttpKeepAlive.readyState == 0 ) {
				
				if ( nSocketCmdCount == 3 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminrequestvqueue.php?tenantid=" + nTenantID, true );
					nSocketCmdCount = 1;
				} else {
					var tmNow = new Date();
					xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);					
					nSocketCmdCount++;
				}
				
				xmlHttpKeepAlive.onreadystatechange = handleKeepAliveReadyState;
				xmlHttpKeepAlive.send( null );
			}
		} catch (e) {
			//alert(e.toString());
		}
	} 
}

function handleKeepAliveReadyState() {
	if ( xmlHttpKeepAlive ) {
		if ( xmlHttpKeepAlive.readyState == 4 && xmlHttpKeepAlive.status == 200 ) {
			try {
				var szResponse = xmlHttpKeepAlive.responseText;
				szResponse = szResponse.replace("\n", "");
				processKeepAliveResponse( szResponse );

			} catch (e) {
				//alert( e );	
			}
			
			setTimeout("startKeepAlive();", 500);
		} 
	}
}

function processKeepAliveResponse(szResponse) {
	var arrResponse = szResponse.split(":");
	
	if ( arrResponse[0] == "keepalive" ) {
		if ( arrResponse[1] == "0" ) {
			window.location = "login.php";
		}
	} else if ( arrResponse[0] == "vqueue") {
		var nTotalCall = arrResponse[1];
		var nTotalAbandon = arrResponse[2];
		var nTotalSL = arrResponse[3];
		var divdata = document.getElementById('divdata');
		var imggraph = document.getElementById("imggraph");
		
		if ( divdata ) {
			divdata.innerHTML = arrResponse[4];
		}
		
		if ( imggraph  ) {
			imggraph.src = "includes/graphvqueue.php?imgcount=" + nImgCount; //?queues=" . arrResponse[4].replace(/<dquote \/>/g, ":").replace(/<dpipe \/>/g, ":");
			nImgCount++;
		}
										  
	} else {
		//alert( szResponse );
	}
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();
var nSocketCmdCount = 0;
var nTenantID = 0;
var nImgCount = 0;
