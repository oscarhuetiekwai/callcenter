
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
				
				if ( nSocketCmdCount == 1 ) {
					xmlHttpKeepAlive.open("GET", "includes/keepchatalive.php?chataction=getchatcontactlist", true );
					nSocketCmdCount++;
                                } else {
						var tmNow = new Date();
					xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);                                       
                                        nSocketCmdCount = 1;
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
var nSocketCmdCount = 1;
var arrayHttpRequest = new Array();


