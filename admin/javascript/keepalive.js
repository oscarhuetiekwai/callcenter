
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
					
					if ( arrayRequest[0] == "adminuser:userlist" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminusersrequest.php?adminuser=userlist&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminuser:userinfo" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminusersrequest.php?adminuser=userinfo&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminuser:saveuser" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminusersrequest.php?adminuser=saveuser&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminuser:userqueuesreload" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminusersrequest.php?adminuser=userqueuesreload&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "adminuser:userdelete" ) {
						xmlHttpKeepAlive.open("GET", "includes/adminusersrequest.php?adminuser=userdelete&" + arrayRequest[1], true );
					} else {
						//xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
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
	} else if ( arrResponse[0] == "adminuser") {
		if ( arrResponse[1] == "userlist" ) {
			var elementDivUserList = document.getElementById("divuserlist");

			if ( elementDivUserList ) {
				elementDivUserList.innerHTML =  arrResponse[2].replace(/<dquote \/>/g, ":");
			} 
		} else if ( arrResponse[1] == "userinfo" ) {
			adminDisplayUserInfo( arrResponse[2] );
		} else if ( arrResponse[1] == "saveuser") {
			if ( arrResponse[2] == "1" ) {
				adminSaveUser( true, "" );
			} else {
				adminSaveUser( false, arrResponse[3] );
			}
		} else if ( arrResponse[1] == "deleteuser" ) {
                        onFilterChange();
			alert(arrResponse[3]);
                }
	} else {
		//alert( szResponse );
	}
}

function adminDisplayUserInfo(userInfo) {
	var arrUserInfo = userInfo.split("|");
	var ctlUsername = document.getElementById("txtusername");
	var ctlPassword = document.getElementById("txtpassword");
	var ctlConfirmPassword = document.getElementById("txtconfirmpassword");
	var ctlLastName = document.getElementById("txtlastname");
	var ctlFirstName = document.getElementById("txtfirstname");
	var ctlUserLevel = document.getElementById("selUserLevel");
	var ctlAgentID = document.getElementById("hdnAgentID");
	var ctlSupervisor = document.getElementById("selSupervisor");
	var btnSave = document.getElementById("btnSave");
	var ctlPQueueTimeout = document.getElementById("txtpqtimeout");
	var ctlPQueueRouteType = document.getElementById("selroute");
	var ctlPSelRouteQueue = document.getElementById("selqueue");
	var ctlPSelRouteAgent = document.getElementById("selagent");
	var ctlPSelRouteExten = document.getElementById("selext");
	var ctlPSelRouteVM = document.getElementById("txtvoicemail");

	if ( ctlAgentID != null ) ctlAgentID.value = arrUserInfo[0];
	if ( ctlUsername != null ) ctlUsername.value = arrUserInfo[3];
	if ( ctlPassword != null ) ctlPassword.value = arrUserInfo[4];
	if ( ctlConfirmPassword != null ) ctlConfirmPassword.value = arrUserInfo[4];	
	if ( ctlUserLevel != null ) ctlUserLevel.value = arrUserInfo[5];
	if ( ctlLastName != null ) ctlLastName.value = arrUserInfo[6];
	if ( ctlFirstName != null ) ctlFirstName.value = arrUserInfo[7];
	if ( btnSave != null ) btnSave.value = "Update User";
	if ( ctlSupervisor != null ) ctlSupervisor.value = arrUserInfo[8];
	if ( ctlPQueueTimeout != null ) ctlPQueueTimeout.value = arrUserInfo[11];
	if ( ctlPQueueRouteType != null ) {
		ctlPQueueRouteType.value = arrUserInfo[12];
		
		onroutechange();
		
		if ( arrUserInfo[12] == "1" ) {
			if ( ctlPSelRouteQueue != null ) ctlPSelRouteQueue.value = arrUserInfo[13];
		} else if ( arrUserInfo[12] == "2" ) {
			if ( ctlPSelRouteAgent != null ) ctlPSelRouteAgent.value = arrUserInfo[13];
		} else if ( arrUserInfo[12] == "3" ) {
			if ( ctlPSelRouteVM != null ) ctlPSelRouteVM.value = arrUserInfo[13];
		} else if ( arrUserInfo[12] == "4" ) {
			if ( ctlPSelRouteExten != null ) ctlPSelRouteExten.value = arrUserInfo[13];
		}
	}
	
	document.getElementById("adminusertitle").innerHTML = "Update User";
	
	// skills
	var skills = arrUserInfo[9];
	skills = skills.replace(/<dquote \/>/g, ":");
	skills = skills.replace(/<dpipe \/>/g, "|");
	var arrSkills = skills.split("|");
	for ( x = 0; x < arrSkills.length ;x++ ) {
		var skillset = arrSkills[x];
		var arraySkillSet = skillset.split(":");
		var nCnt = 1;
		var chkBox = document.getElementById("chkskill" + nCnt);
		
		while ( chkBox != null ) {
			if ( chkBox.value == arraySkillSet[0] ) {
				var txtSkill = document.getElementById("txtskill" + nCnt);
				
				chkBox.checked = true;
				txtSkill.value = arraySkillSet[1];
				txtSkill.style.display = "block";
				break;
			}
			nCnt++;
			chkBox = document.getElementById("chkskill" + nCnt);
		}
	}
	
	// queues
	var queues = arrUserInfo[10];
	queues = queues.replace(/<dpipe \/>/g, "|");
	var arrQueues = queues.split("|");
	for ( y = 0; y < arrQueues.length; y++ ) {
		var nCnt = 1;
		var chkBox = document.getElementById("chkqueue" + nCnt);
		while ( chkBox != null ) {
			if ( chkBox.value == arrQueues[y] ) {
				chkBox.checked = true;
				break;
			}
			nCnt++;
			chkBox = document.getElementById("chkqueue" + nCnt);
		}
	}
	
	// wrapups
	var wrapups = arrUserInfo[14];
	wrapups = wrapups.replace(/<dpipe \/>/g, "|");
	var arrWrapups = wrapups.split("|");
	for ( z = 0; z < arrWrapups.length; z++ ) {
		var nCnt = 1;
		var chkWrapup = document.getElementById("chkwrapup" + nCnt );
		while ( chkWrapup != null ) {
			if ( chkWrapup.value == arrWrapups[z] ) {
				chkWrapup.checked = true;
				break;
			}
			nCnt++;
			chkWrapup = document.getElementById("chkwrapup" + nCnt );
		}
	}
	
	onUserLevelChange();
}

function adminSaveUser(bSuccess,szMessage) {
	if ( bSuccess ) {
		onFilterChange(document.getElementById("hdnTenantID").value);
		alert("Successfully Save to the database!!!");
		if ( document.getElementById("hdnAgentID").value > 0 ) {
			addHttpRequest( "adminuser:userqueuesreload", "userid=" + document.getElementById("hdnAgentID").value );
		} 
	} else
		alert("Error saving to the database!!!\n\n" + szMessage);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


