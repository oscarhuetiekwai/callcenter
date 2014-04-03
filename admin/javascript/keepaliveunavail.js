
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
					
					if ( arrayRequest[0] == "adminunavail:unavailinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminunavailrequest.php?adminunavail=unavailinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminunavail:unavailsave" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminunavailrequest.php?adminunavail=unavailsave&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminunavail:unavaillist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminunavailrequest.php?adminunavail=unavaillist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminunavail:unavaildelete" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminunavailrequest.php?adminunavail=unavaildelete&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminunavail") {
		if ( arrResponse[1] == "unavaillist" ) {
			var divunavallist = document.getElementById("divunavallist");
			var userlist = arrResponse[2].replace(/<dquote \/>/g, ":");
			userlist = userlist.replace(/<dpipe \/>/g, "|");
			
			if ( divunavallist ) {
				divunavallist.innerHTML =  userlist;
			} 
		} else if ( arrResponse[1] == "unavailinfo" ) {
			adminUnavailInfo( arrResponse[2] );
		} else if ( arrResponse[1] == "unavailsave") {
			if ( arrResponse[2] == "1" ) {
				adminSaveUnavail( true, "" );
			} else {
				adminSaveUnavail( false, arrResponse[3] );
			}
		} else if ( arrResponse[1] == "unavaildelete" ) {
                        onFilterChange();
			alert(arrResponse[3]);
                }
	} else {
		//alert( szResponse );
	}
}

function adminUnavailInfo(userInfo) {
	userInfo = userInfo.replace(/<dquote \/>/g, ":");
	userInfo = userInfo.replace(/<dpipe \/>/g, "|");
	
	var arrUserInfo = userInfo.split("|");
	var hdnUserStatusID = document.getElementById("hdnUserStatusID");
	var txtSkill = document.getElementById("txtSkill");
	var selproductive = document.getElementById("selproductive");
	var adminskilltitle = document.getElementById("adminskilltitle");
	var btnSave = document.getElementById("btnSave");
	
	if ( adminskilltitle != null ) adminskilltitle.innerHTML = "Updating unavailable code";
	if ( btnSave != null ) btnSave.value = "Update Code";
	if ( hdnUserStatusID != null ) hdnUserStatusID.value = arrUserInfo[0];
	if ( txtUnavail != null ) txtUnavail.value = arrUserInfo[2];
	if ( selproductive != null ) selproductive.value = arrUserInfo[3];
}

function adminSaveUnavail(bSuccess,szMessage) {
	if ( bSuccess ) {
		alert("Successfully Save to the database!!!");
		onFilterChange();
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


