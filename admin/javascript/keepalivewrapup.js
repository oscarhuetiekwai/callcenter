
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
					
					if ( arrayRequest[0] == "adminwrapup:savewrapup" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminwrapupsrequest.php?adminwrapup=savewrapup&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminwrapup:wrapuplist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminwrapupsrequest.php?adminwrapup=wrapuplist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminwrapup:wrapupinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminwrapupsrequest.php?adminwrapup=wrapupinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminwrapup:wrapupdelete" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminwrapupsrequest.php?adminwrapup=wrapupdelete&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminwrapup") {
		if ( arrResponse[1] == "savewrapup" ) {
			if ( arrResponse[2] == "1" ) 
				adminSaveWrapup(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveWrapup( false, szMessage);
			}
		} else if ( arrResponse[1] == "wrapuplist" ) {
			var divUserList = document.getElementById("divwrapuplist");
			var szWrapupList = arrResponse[2];
			
			szWrapupList = szWrapupList.replace(/<dquote \/>/g, ":");
			szWrapupList = szWrapupList.replace(/<dpipe \/>/g, "|");
			
			divUserList.innerHTML = szWrapupList;
		} else if ( arrResponse[1] == "wrapupinfo" ) {
			var szWrapupInfo = arrResponse[2];
			
			szWrapupInfo = szWrapupInfo.replace(/<dquote \/>/g, ":");
			szWrapupInfo = szWrapupInfo.replace(/<dpipe \/>/g, "|");
			
			displayWrapupInfo( szWrapupInfo );
		} else if ( arrResponse[1] == "wrapupdelete" ) {
                        reloadWrapupList();
			alert(arrResponse[3]);
                }
	} else {
		//alert( szResponse );
	}
}

function displayWrapupInfo(szWrapupInfo) {
	var arrWrapupInfo = szWrapupInfo.split("|");
	
	document.getElementById("hdnWrapupID").value = arrWrapupInfo[0];
	document.getElementById("hdnTenantID").value = arrWrapupInfo[1];
	document.getElementById("txtwrapup").value = arrWrapupInfo[2];
	document.getElementById("txtdescription").value = arrWrapupInfo[3];
	
	document.getElementById("adminwrapuptitle").innerHTML = "Update Wrapup";
	document.getElementById("btnSave").value ="Update Wrapup";	
}

function adminSaveWrapup(bSuccess,szMessage) {
	if ( bSuccess ) {
		reloadWrapupList();
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


