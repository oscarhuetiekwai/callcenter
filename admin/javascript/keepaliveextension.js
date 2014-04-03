
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
					
					if ( arrayRequest[0] == "adminextension:extensionlist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminextensionsrequest.php?adminextension=extensionlist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminextension:extensioninfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminextensionsrequest.php?adminextension=extensioninfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminextension:saveextension" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminextensionsrequest.php?adminextension=saveextension&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminextension") {
		if ( arrResponse[1] == "saveextension" ) {
			if ( arrResponse[2] == "1" ) 
				adminSaveExtension(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveExtension( false, szMessage);
			}
		} else if ( arrResponse[1] == "extensionlist" ) {
			var divUserList = document.getElementById("divextensionlist");
			var szExtensionList = arrResponse[2];
			
			szExtensionList = szExtensionList.replace(/<dquote \/>/g, ":");
			szExtensionList = szExtensionList.replace(/<dpipe \/>/g, "|");
			
			divUserList.innerHTML = szExtensionList;
		} else if ( arrResponse[1] == "extensioninfo" ) {
			var szExtensionInfo = arrResponse[2];
			
			szExtensionInfo = szExtensionInfo.replace(/<dquote \/>/g, ":");
			szExtensionInfo = szExtensionInfo.replace(/<dpipe \/>/g, "|");
			
			displayExtensionInfo( szExtensionInfo );
		}
	} else {
		//alert( szResponse );
	}
}



function displayExtensionInfo(szExtensionInfo) {
	var arrExtensionInfo = szExtensionInfo.split("|");
	
	document.getElementById("hdnExtensionID").value = arrExtensionInfo[0];
	document.getElementById("hdnTenantID").value = arrExtensionInfo[1];
	document.getElementById("hdnTenantName").value = arrExtensionInfo[1];
	document.getElementById("txtExtensionName").value = arrExtensionInfo[3];
	document.getElementById("txtCallerId").value = arrExtensionInfo[4];
	document.getElementById("txtSecret").value = arrExtensionInfo[5];
	
	document.getElementById("adminextensiontitle").innerHTML = "Update Extension";
	document.getElementById("btnSave").value ="Update Extension";	
}

function adminSaveExtension(bSuccess,szMessage) {
	if ( bSuccess ) {
		reloadExtensionList();
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


