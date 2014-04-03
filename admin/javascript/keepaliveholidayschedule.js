
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
					
					if ( arrayRequest[0] == "adminholidayschedule:holidayslist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminholidayschedulerequest.php?adminholidayschedule=holidaylist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminholidayschedule:holidayinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminholidayschedulerequest.php?adminholidayschedule=holidayinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminholidayschedule:saveholiday" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminholidayschedulerequest.php?adminholidayschedule=saveholiday&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminholidayschedule:updateholiday" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminholidayschedulerequest.php?adminholidayschedule=updateholiday&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminholidayschedule:deleteholiday" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminholidayschedulerequest.php?adminholidayschedule=deleteholiday&" + arrayRequest[1], true );
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
			// alert(e.toString());
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
	} else if ( arrResponse[0] == "adminholiday") {
		if ( arrResponse[1] == "saveholiday" ) {
			if ( arrResponse[2] == "1" ) 
				adminSaveHoliday(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveHoliday( false, szMessage);
			}
		} else if ( arrResponse[1] == "updateholiday" ) {
			if ( arrResponse[2] == "1" )
				adminSaveHoliday(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveHoliday( false, szMessage);
			}
		} else if ( arrResponse[1] == "deleteholiday" ) {
                                reloadHolidayList();
				alert(arrResponse[3]);
		} else if ( arrResponse[1] == "holidaylist" ) {
			var divHolidayList = document.getElementById("divholidaylist");
			var szHolidayList = arrResponse[2];
			
			szHolidayList = szHolidayList.replace(/<dquote \/>/g, ":");
			szHolidayList = szHolidayList.replace(/<dpipe \/>/g, "|");
			
			divHolidayList.innerHTML = szHolidayList;
		} else if ( arrResponse[1] == "holidayinfo" ) {
			var szHolidayInfo = arrResponse[2];
			
			szHolidayInfo = szHolidayInfo.replace(/<dquote \/>/g, ":");
			szHolidayInfo = szHolidayInfo.replace(/<dpipe \/>/g, "|");
			
			displayHolidayInfo( szHolidayInfo );
		}
	} else {
		//alert( szResponse );
	}
}



function displayHolidayInfo(szHolidayInfo) {
	var arrHolidayInfo = szHolidayInfo.split("|");

	document.getElementById("txtHolidayFrom").value = arrHolidayInfo[1];
        document.getElementById("txtHolidayFrom").disabled = true;
        document.getElementById("txtHolidayTo").value = arrHolidayInfo[2];
        document.getElementById("txtHolidayTo").disabled = true;
        //document.getElementsByName("calendericon").item(0).height = "0";
        //document.getElementsByName("calendericon").item(0).width = "0";
        
	document.getElementById("txtDesc").value = arrHolidayInfo[0];
		
	document.getElementById("adminskilltitle").innerHTML = "Update Holiday";
	document.getElementById("btnSave").value = "Update Holiday";
        document.getElementById("btnSave").setAttribute("onclick","onBtnUpdateClick();" );
}

function adminSaveHoliday(bSuccess,szMessage) {
	if ( bSuccess ) {
		reloadHolidayList();
		alert("Successfully Save to the database!!!");
	} else
		alert(szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


