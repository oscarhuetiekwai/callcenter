
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
					
					if ( arrayRequest[0] == "adminoutbound:saveoutbound" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminoutboundrequest.php?adminoutbound=saveoutbound&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminoutbound:outboundinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminoutboundrequest.php?adminoutbound=outboundinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminoutbound:outboundlist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminoutboundrequest.php?adminoutbound=outboundlist&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminoutbound") {
		if ( arrResponse[1] == "saveoutbound") {
			if ( arrResponse[2] == "1" ) {
				adminSaveOutbound( true, "" );
			} else {
				adminSaveOutbound( false, arrResponse[3] );
			}
		} else if ( arrResponse[1] == "outboundinfo" ) {
			if ( arrResponse[2] == "1" ) {
				adminDisplayOutboundInfo( arrResponse[3] );
			} else {
				alert("Unavaible to retrieve outbound info!!!");
			}
		} else if ( arrResponse[1] == "outboundlist" ) {
			var divoutboundlist = document.getElementById("divoutboundlist");
			
			if ( divoutboundlist ) {
				divoutboundlist.innerHTML = arrResponse[2];
			}
		}
	} else {
		//alert( szResponse );
	}
}


function adminSaveOutbound(bSuccess,szMessage) {
	if ( bSuccess ) {
		//onFilterChange(document.getElementById("hdnTenantID").value);
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

function adminDisplayOutboundInfo(szResponse) {
	var arrResponse = szResponse.split("|");
	var hdnOutboundID = document.getElementById("hdnOutboundID");
	var seloutboundtype = document.getElementById("seloutboundtype");
	var txtoutbound = document.getElementById("txtoutbound");
	var txtstartdate = document.getElementById("txtstartdate");
	var txtenddate = document.getElementById("txtenddate");
	
	if ( hdnOutboundID ) hdnOutboundID.value = arrResponse[0];
	if ( txtoutbound ) txtoutbound.value = arrResponse[1];
	if ( seloutboundtype ) seloutboundtype.value = arrResponse[2];
	if ( txtstartdate ) txtstartdate.value = arrResponse[3];
	if ( txtenddate ) txtenddate.value = arrResponse[4];
	
	var arrSkills = arrResponse[5].split(",");
	var arrWrapups = arrResponse[6].split(",");
	var arrOutboundSched = arrResponse[7].split(",");
	
	nControl = 1;
	var chkSkills = document.getElementById("chkskill" + nControl ) ;
	
	while ( chkSkills ) {
		chkSkills.checked = false;
		
		nControl++;
		chkSkills = document.getElementById("chkskill"  + nControl);
	}
	
	for (nCnt = 0; nCnt < arrSkills.length; nCnt++ ) {
		nControl = 1;
		var chkSkills = document.getElementById("chkskill" + nControl ) ;
		
		while ( chkSkills ) {
			if ( chkSkills.value == arrSkills[nCnt] ) {
				chkSkills.checked = true;
				break;
			}
			
			nControl++;
			chkSkills = document.getElementById("chkskill"  + nControl);
		}
	}
	
	nControl = 1;
	var chkWrapups = document.getElementById("chkwrapup" + nControl );
	
	while ( chkWrapups ) {
		chkWrapups.checked = false;
		
		nControl++;
		chkWrapups = document.getElementById("chkwrapup" + nControl );	
	}
		
	for (nCnt = 0; nCnt < arrWrapups.length; nCnt++ ) {
		nControl = 1;
		var chkWrapups = document.getElementById("chkwrapup" + nControl );
		
		while ( chkWrapups ) {
			if ( chkWrapups.value == arrWrapups[nCnt] ) {
				chkWrapups.checked = true;
			}
			
			nControl++;
			chkWrapups = document.getElementById("chkwrapup" + nControl );	
		}
	}
	
	for (nCnt = 0; nCnt < 7; nCnt++ ) {
		document.getElementById("chkday" + nCnt ).checked = false;
		document.getElementById("txtstartday" + nCnt).value = "00:00:00";
		document.getElementById("txtendday" + nCnt).value = "00:00:00";
		onChkDayClick(nCnt);
	}
	
	for (nCnt = 0; nCnt < arrOutboundSched.length; nCnt++ ) {
		var arrSched = arrOutboundSched[nCnt].split("^");
		
		if ( arrSched.length > 2 ) {
			document.getElementById("chkday" + arrSched[0] ).checked = true;
			document.getElementById("txtstartday" + arrSched[0]).value = arrSched[1].replace(/<dcolon \/>/g, ":") ;
			document.getElementById("txtendday" + arrSched[0]).value = arrSched[2].replace(/<dcolon \/>/g, ":") ;
			onChkDayClick(arrSched[0]);
		}
	}
	
	onSelOutboundChange();
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


