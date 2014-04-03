
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
					
					if ( arrayRequest[0] == "adminqueue:savequeue" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminqueuesrequest.php?adminqueue=savequeue&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminqueue:queuelist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminqueuesrequest.php?adminqueue=queuelist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminqueue:queueinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminqueuesrequest.php?adminqueue=queueinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminqueue:inboundqueuesreload" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminqueuesrequest.php?adminqueue=inboundqueuesreload", true );
					} else if ( arrayRequest[0] == "adminqueue:queuedelete" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminqueuesrequest.php?adminqueue=queuedelete&" + arrayRequest[1], true );
					} else {
						var tmNow = new Date();
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);						
					}
					
				} else {
					var tmNow = new Date();
					xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);					
				}
				
				xmlHttpKeepAlive.onreadystatechange = handleKeepAliveReadyState;
				xmlHttpKeepAlive.send( null );
			}
		} catch (e) {
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
	} else if ( arrResponse[0] == "adminqueue") {
		if ( arrResponse[1] == "savequeue" ) {
			if ( arrResponse[2] == "1" ) 
				adminSaveQueue(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveQueue( false, szMessage);
			}
		} else if ( arrResponse[1] == "queuelist" ) {
			var divQueuelist = document.getElementById("divqueuelist");
			var szQueueList = arrResponse[2];
			
			szQueueList = szQueueList.replace(/<dquote \/>/g, ":");
			szQueueList = szQueueList.replace(/<dpipe \/>/g, "|");
			
			divQueuelist.innerHTML = szQueueList;
		} else if ( arrResponse[1] == "queueinfo" ) {
			var szQueueInfo = arrResponse[2];
			
			szQueueInfo = szQueueInfo.replace(/<dquote \/>/g, ":");
			szQueueInfo = szQueueInfo.replace(/<dpipe \/>/g, "|");
			
			displayQueueInfo( szQueueInfo );
		} else if ( arrResponse[1] =="inboundqueuesreload" ) {
		} else if ( arrResponse[1] == "queuedelete" ) {
			alert(arrResponse[3]);
                        addHttpRequest("adminqueue:queuelist", "tenantid=" + document.getElementById("hdnTenantID").value );
                        addHttpRequest("adminqueue:inboundqueuesreload", "" );
                }
	} else {
		//alert( szResponse );
	}
}

function displayQueueInfo(szQueueInfo) {
	var arrQueueInfo = szQueueInfo.split("|");
	var szlistQueueInfo = arrQueueInfo[7] + ":";
	var szlistWrapupInfo = arrQueueInfo[8] + ":";
	var arrQueueSkills = szlistQueueInfo.split(":");
	var arrQueueWrapups = szlistWrapupInfo.split(":");
	
	document.getElementById("hdnQueueID").value = arrQueueInfo[0];
	document.getElementById("txtqueue").value = arrQueueInfo[2];
	document.getElementById("txttimeout").value = arrQueueInfo[3];
	document.getElementById("txtservicelevel").value = arrQueueInfo[4];
	document.getElementById("txtwrapuptime").value = arrQueueInfo[6];
	document.getElementById("txtpriority").value = arrQueueInfo[5];
	document.getElementById("btnSave").value ="Update Queue";
	
	// clear the checkbox first
	nCntX = 1;
	var chkSkill = document.getElementById("chkskill" + nCntX);
	while ( chkSkill ) {
		chkSkill.checked = false;
		nCntX++;
		chkSkill = document.getElementById("chkskill" + nCntX);
	}	
	
	nCntX = 1;
	var chkWrapup = document.getElementById("chkwrapup" + nCntX);
	while ( chkWrapup ) {
		chkWrapup.checked = false;
		nCntX++;
		chkWrapup = document.getElementById("chkwrapup" + nCntX);
	}
	
	for (nCnt=0; nCnt < arrQueueSkills.length; nCnt++ ) {
		nCntX = 1;
		chkSkill = document.getElementById("chkskill" + nCntX);
		while ( chkSkill ) {
			if ( chkSkill.value == arrQueueSkills[nCnt] ) {
				chkSkill.checked = true;
				break;
			}
			nCntX++;
			chkSkill = document.getElementById("chkskill" + nCntX);
		}
	}
	
	for (nCnt=0; nCnt < arrQueueWrapups.length; nCnt++ ) {
		nCntX = 1;
		chkWrapup = document.getElementById("chkwrapup" + nCntX );
		while ( chkWrapup ) {
			if ( chkWrapup.value == arrQueueWrapups[nCnt] ) {
				chkWrapup.checked = true;
				break;
			}
			nCntX++;
			chkWrapup = document.getElementById("chkwrapup" + nCntX );
		}
	}
	
	document.getElementById("adminqueuetitle").innerHTML = "Update Queue";
}

function adminSaveQueue(bSuccess,szMessage) {
	if ( bSuccess ) {
//		reloadWrapupList();
		alert("Successfully Save to the database!!!");
		addHttpRequest("adminqueue:queuelist", "tenantid=" + document.getElementById("hdnTenantID").value );
		addHttpRequest("adminqueue:inboundqueuesreload", "" );
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


