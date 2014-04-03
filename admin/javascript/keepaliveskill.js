
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
					
					if ( arrayRequest[0] == "adminskill:skilllist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminskillsrequest.php?adminskill=skilllist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminskill:skillinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminskillsrequest.php?adminskill=skillinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminskill:saveskill" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminskillsrequest.php?adminskill=saveskill&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminskill:skilldelete" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminskillsrequest.php?adminskill=skilldelete&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminskill") {
		if ( arrResponse[1] == "saveskill" ) {
			if ( arrResponse[2] == "1" ) 
				adminSaveSkill(true,'');
			else {
				szMessage = arrResponse[3];
				szMessage = szMessage.replace(/<dquote \/>/g, ":");
				adminSaveSkill( false, szMessage);
			}
		} else if ( arrResponse[1] == "skilllist" ) {
			var divUserList = document.getElementById("divskilllist");
			var szSkillList = arrResponse[2];
			
			szSkillList = szSkillList.replace(/<dquote \/>/g, ":");
			szSkillList = szSkillList.replace(/<dpipe \/>/g, "|");
			
			divUserList.innerHTML = szSkillList;
		} else if ( arrResponse[1] == "skillinfo" ) {
			var szSkillInfo = arrResponse[2];
			
			szSkillInfo = szSkillInfo.replace(/<dquote \/>/g, ":");
			szSkillInfo = szSkillInfo.replace(/<dpipe \/>/g, "|");
			
			displaySkillInfo( szSkillInfo );
		} else if ( arrResponse[1] == "deleteskill" ) {
                        reloadSkillList();
			alert(arrResponse[3]);
                }
	} else {
		//alert( szResponse );
	}
}



function displaySkillInfo(szSkillInfo) {
	var arrSkillInfo = szSkillInfo.split("|");
	
	document.getElementById("hdnSkillID").value = arrSkillInfo[0];
	document.getElementById("hdnTenantID").value = arrSkillInfo[1];
	document.getElementById("hdnTenant").value = arrSkillInfo[1];
	document.getElementById("txtSkill").value = arrSkillInfo[3];
	document.getElementById("txtDesc").value = arrSkillInfo[4];
		
	document.getElementById("adminskilltitle").innerHTML = "Update Skill";
	document.getElementById("btnSave").value ="Update Skill";	
}

function adminSaveSkill(bSuccess,szMessage) {
	if ( bSuccess ) {
		reloadSkillList();
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


