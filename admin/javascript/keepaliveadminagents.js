function setTenantID(tenantID) {
	nTenantID = tenantID;
}

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

function startKeepAlive() {
	if ( xmlHttpKeepAlive ) {
		try {
			if ( xmlHttpKeepAlive.readyState == 4 || xmlHttpKeepAlive.readyState == 0 ) {
				
				/*if ( nSocketCmdCount == 1 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=callstatus", true );
					nSocketCmdCount++;
				} else if ( nSocketCmdCount == 2 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=userstatus", true );
					nSocketCmdCount++; */
				
				if ( nSocketCmdCount == 1 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminagentsrequest.php?tenantid=" + nTenantID + "&statstype=agents", true );
					nSocketCmdCount++;
				} else if ( nSocketCmdCount == 2) {
					xmlHttpKeepAlive.open("GET", "includes/adminagentsrequest.php?tenantid=" + nTenantID + "&statstype=agentsummary", true );
					nSocketCmdCount++;
				} else if ( nSocketCmdCount == 3) {
					xmlHttpKeepAlive.open("GET", "includes/keepchatalive.php?chataction=getchatcontactlist", true );
					nSocketCmdCount++;
				} else {
					var tmNow = new Date();
					xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);					

					nSocketCmdCount = 1;
				}
				
				xmlHttpKeepAlive.onreadystatechange = handleKeepAliveReadyState;
				xmlHttpKeepAlive.send( null );
			}
		} catch (e) {
			//alert("startKeepAlive: " + e.toString());
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
				//alert("handleKeepAliveReadyState:" + e.toString() );	
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
	} else if ( arrResponse[0] == "adminagentstats" ) {
		if ( arrResponse[1] == "agents" ) {
			var divagentrealtime = document.getElementById("divagentrealtime");
			
			if ( divagentrealtime ) {
				divagentrealtime.innerHTML = arrResponse[9].replace(/<tcolon \/>/g, ":");
				
				var szBarData = "<graph decimalPrecision='0' animation='0'>";	
				
				szBarData += "<categories><category name='Agents Status' /></categories>";
				szBarData += ("<dataset seriesName='Available' color='00FF00' ><set value='" + arrResponse[3] + "'/></dataset>");
				szBarData += ("<dataset seriesName='Ringing' color='FFFF00' ><set value='" + arrResponse[4] + "'/></dataset>");
				szBarData += ("<dataset seriesName='Busy' color='FF0000' ><set value='" + arrResponse[5] + "' /></dataset>");
				szBarData += ("<dataset seriesName='After Call Work' color='A52A2A' ><set value='" + arrResponse[7] + "'/></dataset>");
				szBarData += ("<dataset seriesName='Others' color='0000FF' ><set value='" + arrResponse[8] + "'/></dataset>");
				szBarData += "</graph>";
				
				var divagentgraphborder = document.getElementById("divagentgraphborder");
				
				if ( divagentgraphborder ) {
					if ( divagentgraphborder.style.display != "none" ) {
						//var objBarChar = getChartFromId('agentBarChart');
						
						//if (  objBarChar ) {
						//	objBarChar.setDataXML( szBarData );
						//}
						updateChartXML('agentBarChart', szBarData );
					}
				}
			}
		} else if ( arrResponse[1] == "agentsummary" ) {
			var divagentsummary = document.getElementById("divagentsummary");
			
			if ( divagentsummary ) {
				divagentsummary.innerHTML = arrResponse[3].replace(/<tcolon \/>/g, ":");
			}
		} 
	} else if ( arrResponse[0] == "getchatcontactlist" ) {
            refreshChatList(arrResponse[2],arrResponse[3]);
        } else if ( arrResponse[0] == "chatreceive" ) {
            var chatIdName = arrResponse[1].split("^");

            chatWith(chatIdName[0], chatIdName[1]);
            var chatboxcontent = document.getElementById('chatboxcontent' + chatIdName[0]);
            chatboxcontent.innerHTML += '<b>' + chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1) + ' : </b>' + arrResponse[2] + '<br/>';
            chatboxcontent.scrollTop = chatboxcontent.scrollHeight;//message keep scroll on bottom
            
            chatNotification.push('chatboxhead' + chatIdName[0]);//blink notification
        } else if ( arrResponse[0] == "broadcastreceive" ) {
            var chatIdName = arrResponse[1].split("^");

            chatWith(chatIdName[0], chatIdName[1]);
            var chatboxcontent = document.getElementById('chatboxcontent' + chatIdName[0]);
            chatboxcontent.innerHTML += '<b>' + chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1) + ' : </b>' + arrResponse[2] + '<br/>';
            chatboxcontent.scrollTop = chatboxcontent.scrollHeight;//message keep scroll on bottom
            
            chatNotification.push('chatboxhead' + chatIdName[0]);//blink notification
        } else if ( arrResponse[0] == "chatcontactstatus" ) {
            var chatIdName = arrResponse[2];
            var status = arrResponse[1];
            refreshContacts(chatIdName,status);//chatCIS.js
        } else {
		//alert( "processKeepAliveResponse: " + szResponse );
	}
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();
var nSocketCmdCount = 0;
var nTenantID = 0;
