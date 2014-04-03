
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
	//alert("Starting keep alive");
	if ( xmlHttpKeepAlive ) {
		try {
			if ( xmlHttpKeepAlive.readyState == 4 || xmlHttpKeepAlive.readyState == 0 ) {
				//alert(" if ( arrayHttpRequest.length > 0 ) { " );
				if ( arrayHttpRequest.length > 0 ) {
					var arrayRequest = arrayHttpRequest.shift();
					
					if ( arrayRequest[0] == "admintenant:tenantinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/admintenantsrequest.php?admintenant=tenantinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "admintenant:tenantsave" ) {
						xmlHttpKeepAlive.open("GET", "includes/admintenantsrequest.php?admintenant=tenantsave&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "admintenant:tenantlist" ) {
						xmlHttpKeepAlive.open("GET", "includes/admintenantsrequest.php?admintenant=tenantlist", true );
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
				//alert("done");
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
	} else if ( arrResponse[0] == "admintenant") {
		if ( arrResponse[1] == "tenantlist" ) {
			var divtenantlist = document.getElementById("divtenantlist");

			if ( divtenantlist ) {
				divtenantlist.innerHTML =  arrResponse[2].replace(/<dpipe \/>/g, "|").replace(/<dquote \/>/g, ":");
			} 
		} else if ( arrResponse[1] == "tenantinfo" ) {
			adminDisplayTenantInfo( arrResponse[2] );
		} else if ( arrResponse[1] == "tenantsave") {
			if ( arrResponse[2] == "1" ) {
				adminSaveTenant( true, "" );
			} else {
				adminSaveTenant( false, arrResponse[3] );
			}
		}
	} else {
		//alert( szResponse );
	}
}

function adminDisplayTenantInfo(tenantInfo) {
	var arrTenantInfo = tenantInfo.replace(/<dpipe \/>/g, "|").split("|");
	var hdnTenantID = document.getElementById("hdnTenantID");
	var txttenant = document.getElementById("txttenant");
	var txtcontactperson = document.getElementById("txtcontactperson");
	var txtofficephone = document.getElementById("txtofficephone");
	var txthomephone = document.getElementById("txthomephone");
	var txtmobileno = document.getElementById("txtmobileno");
	
	if ( hdnTenantID ) hdnTenantID.value = arrTenantInfo[0];
	if ( txttenant ) txttenant.value = arrTenantInfo[1];
	if ( txtcontactperson ) txtcontactperson.value = arrTenantInfo[2];
	if ( txtofficephone ) txtofficephone.value = arrTenantInfo[3];
	if ( txthomephone ) txthomephone.value = arrTenantInfo[4];
	if ( txtmobileno ) txtmobileno.value = arrTenantInfo[5];
	
	document.getElementById("admintenanttitle").innerHTML = "Update Tenant";
}

function adminSaveTenant(bSuccess,szMessage) {
	if ( bSuccess ) {
		addHttpRequest( "admintenant:tenantlist", "" );
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


