
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
				if ( arrayHttpRequest.length > 0 ) {
					var arrayRequest = arrayHttpRequest.shift();
					
					if ( arrayRequest[0] == "adminqmonitor:search" )  {
						xmlHttpKeepAlive.open("GET", "includes/adminqmonitorrequest.php?adminqmonitor=search&" + arrayRequest[1], true);
					}
                                        else {
                                          var tmNow = new Date();
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);
                                        }
				} else {
                                    if ( nSocketCmdCount == 1 ) {
					xmlHttpKeepAlive.open("GET", "includes/keepchatalive.php?chataction=getchatcontactlist", true );
					nSocketCmdCount++;
                                    } else {
						var tmNow = new Date();
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);                                       
                                        nSocketCmdCount = 1;
                                    }
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
	} else if ( arrResponse[0] == "adminqmonitor") {
		if ( arrResponse[1] == "search" ) {
			var divqmonitor = document.getElementById("divqmonitor");
			var divnavigation = document.getElementById("divnavigation");
			
			if ( divqmonitor ) {
				divqmonitor.innerHTML = arrResponse[6].replace(/<tcolon \/>/g, ":");
			}
			
			if ( divnavigation ) {
				var szNavigation = "";
				for ( var nCnt = 1; nCnt <= arrResponse[4]; nCnt++ ) {
					var szItemNav = "";
					if ( nCnt == arrResponse[5] ) {
						szItemNav = nCnt;
					} else {
						szItemNav = "<a href='#' onclick='onPageClick(" + nCnt + ")'>" + nCnt + "</a>";
					}
					
					if ( szNavigation == "" ) {
						szNavigation = "Pages : <span class='spannav'>" + szItemNav;
					} else {
						szNavigation += ( ", " + szItemNav );
					}
				}
				
				szNavigation += "</span>";
				
				divnavigation.innerHTML = szNavigation;
			}
		} 
	} else if ( arrResponse[0] == "getchatcontactlist" ) {
            refreshChatList(arrResponse[2],arrResponse[3]);//chatCIS.js
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

	}
}

function displayCallsInfo(szCallInfo) {
	var arrCallInfo = szCallInfo.split("|");
	
	
	document.getElementById("hdnTenantID").value = arrCallInfo[0];
	document.getElementById("username").value = arrCallInfo[1];	
	document.getElementById("queue").value = arrCallInfo[2];	
	document.getElementById("btnSearch").value ="Search";
	
}

function adminSaveCall(bSuccess,szMessage) {
	if ( bSuccess ) {
		reloadCallList();
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var nSocketCmdCount = 1;
var arrayHttpRequest = new Array();


