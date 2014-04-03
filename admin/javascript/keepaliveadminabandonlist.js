function addHttpRequest(szModule, szRequest) {
	var arrayRequest = new Array();
	
	arrayRequest[0] = szModule;
	arrayRequest[1] = szRequest;
	
	arrayHttpRequest.push(arrayRequest);
}

function includeJavaScript(jsFile)
{
  document.write('<script type="text/javascript" src="'
    + jsFile + '"></scr' + 'ipt>'); 
}

//function startKeepAlive() {
//	
//        var jsFunction = document.getElementById("onJsFunction").innerHTML;
//        
//        switch(jsFunction)
//        {
//        case "filterByQueueName":
//            filterByQueueName();
//        break;
//        case "filterByCallerId":
//            filterByCallerId();
//        break;
//        case "filterByDate":
//            filterByDate();
//        break;
//        case "sortTheList":
//            sortTheList();
//        break;
//        case "popprintTheList":
//            popprintTheList();
//        break;
//        case "goToThisPage":
//            var pageNumber = document.getElementById("pageNumber").innerHTML;
//            goToThisPage(pageNumber);
//        break;
//        case "filterDefault":
//            filterDefault();
//        break;
//        }
//				
//        handleKeepAliveReadyState();
//}

function startKeepAlive() {
	if ( xmlHttpKeepAlive ) {
		try {
			if ( xmlHttpKeepAlive.readyState == 4 || xmlHttpKeepAlive.readyState == 0 ) {
//				if ( arrayHttpRequest.length > 0 ) {
//					xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
//                                        
//				} else {
//                                        xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
//                                        nSocketCmdCount = 1;
//				}
				xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
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
			
			setTimeout("startKeepAlive();", 1000);
		} 
	}
}

function processKeepAliveResponse(szResponse) {
	var arrResponse = szResponse.split(":");
	
	if ( arrResponse[0] == "keepalive" ) {
		if ( arrResponse[1] == "0" ) {
			window.location = "login.php";
		}
	} else {

	}
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var nSocketCmdCount = 1;
var arrayHttpRequest = new Array();