
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

function startKeepAlive() {
	if ( xmlHttpKeepAlive ) {
		try {
			if ( xmlHttpKeepAlive.readyState == 4 || xmlHttpKeepAlive.readyState == 0 ) {
				if ( arrayHttpRequest.length > 0 ) {
					var arrayRequest = arrayHttpRequest.shift();
					
					if ( arrayRequest[0] == "adminuserlevel:authorityaccess" ) { 
						xmlHttpKeepAlive.open("GET", "includes/adminuserlevelrequest.php?adminuserlevel=userlevellist&" + arrayRequest[1], true );
					} else if( arrayRequest[0] == "adminuserlevel:saveauthorityaccess" ) {  
                                                xmlHttpKeepAlive.open("GET", "includes/adminuserlevelrequest.php?adminuserlevel=saveauthorityaccess&" + arrayRequest[1], true );
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
	} else if ( arrResponse[0] == "adminuserlevel") {
		if ( arrResponse[1] == "saveuserlevel" ) {
			if ( arrResponse[2] == "1" )
				adminUserLevel(true);
			else {
				adminUserLevel(false);
			}
		} else if ( arrResponse[1] == "accessauthority" ) {
			var divAccessAuthority = document.getElementById("authorityAccessContent");
                        var divsaveButton = document.getElementById("saveButton");
			var szAccessAuthority = arrResponse[3];
			
			szAccessAuthority = szAccessAuthority.replace(/<dquote \/>/g, ":");
			szAccessAuthority = szAccessAuthority.replace(/<dpipe \/>/g, "|");
			
			divAccessAuthority.innerHTML = szAccessAuthority;
                        divsaveButton.innerHTML = "<br/><hr/><br/><input type='button' name='btnSave' id='btnSave' value='Save' onclick='onBtnSaveClick(" + arrResponse[2] + ");'/>";
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

function adminUserLevel(bSuccess) {
	if ( bSuccess ) {
		alert("Successfully Save to the database!!!");
	} else
		alert("Error saving to the database!!!\n\n");
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();


