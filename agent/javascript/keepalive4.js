
function outCall(outNumber) {
        var outboundoldstatus = document.getElementById('outboundoldstatus');
        var seluserstatus = document.getElementById('seluserstatus');

        outboundoldstatus.value = seluserstatus.value;
	nOutboundAvailableCount = 0;
        
        addHttpRequest("statuschange", "code=1");
	addHttpRequest("outcall","outnumber=" + outNumber);	
}

function addHttpRequest(szModule, szRequest) {
	var arrayRequest = new Array();
	
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
					
					if ( arrayRequest[0] == "outcall" ) {
						xmlHttpKeepAlive.open("GET", "includes/outcall.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "endcall" ) {
						xmlHttpKeepAlive.open("GET", "includes/endcall.php", true );
					} else if ( arrayRequest[0] == "transfer" ) {
						xmlHttpKeepAlive.open("GET", "includes/transfer.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "statuschange" ) {
						xmlHttpKeepAlive.open("GET", "includes/statuschange.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "wrapupdetails" ) {
						xmlHttpKeepAlive.open("GET", "includes/wrapupdetails.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "callwrapups" ) {
						xmlHttpKeepAlive.open("GET", "includes/callwrapups.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "gettransferagents" ) {
						xmlHttpKeepAlive.open("GET", "includes/gettransferagents.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "getagentstatus" ) {
						xmlHttpKeepAlive.open("GET", "includes/getagentstatus.php?" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "hold" ) {
						xmlHttpKeepAlive.open("GET", "includes/holdcall.php", true );
					} else if ( arrayRequest[0] == "customercallstart" ) {
						xmlHttpKeepAlive.open("GET", "includes/webserviceail.php?soapclient=customercallstart&" + arrayRequest[1], true );
					} else if ( arrayRequest[0] == "customercallend" ) {
						xmlHttpKeepAlive.open("GET", "includes/webserviceail.php?soapclient=customercallend&" + arrayRequest[1], true );
					} else {
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
					} 
				} else {
					if ( bKeepAliveCall ==1 )  {
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php", true);
						bKeepAliveCall = 2;
					} else if ( bKeepAliveCall == 2 ) {
						xmlHttpKeepAlive.open("GET", "includes/queueget.php", true );
						bKeepAliveCall = 3;
					} else if ( bKeepAliveCall == 3 ) {//sync user status
                                                xmlHttpKeepAlive.open("GET", "includes/getuserstatus.php", true );
                                                bKeepAliveCall = 4;
                                        } else {
                                                xmlHttpKeepAlive.open("GET", "includes/keepchatalive.php?chataction=getchatcontactlist", true );
                                                bKeepAliveCall = 1;
                                        }
				}
				xmlHttpKeepAlive.onreadystatechange = handleKeepAliveReadyState;
				xmlHttpKeepAlive.send( null );
			}
		} catch (e) {
                        window.location.reload();
                        //setTimeout("startKeepAlive();", 300);
			//alert(e.toString());
		}
	} 
}

function handleKeepAliveReadyState() {
	if ( xmlHttpKeepAlive ) {
		var divstatus = document.getElementById("divstatus");

		if ( divstatus ) {
			var currentTime = new Date();
                        var hours = currentTime.getHours();
                        var min = currentTime.getMinutes();
                        var sec = currentTime.getSeconds();

                        if ( hours < 10 ) hours = "0" + hours;
                        if ( min < 10 ) min = "0" + min;
                        if ( sec < 10 ) sec = "0" + sec;

                        divstatus.innerHTML = "Time: " + hours + ":" + min + ":" + sec;
		}

		if ( xmlHttpKeepAlive.readyState == 4 ) {
			if ( xmlHttpKeepAlive.status == 200 ) {
				try {
					var szResponse = xmlHttpKeepAlive.responseText;
					szResponse = szResponse.replace("\n", "");
					processKeepAliveResponse( szResponse );
					nSessionLostCount = 0;
				} catch (e) {
					
				}
			} else {
				nSessionLostCount++;
			}

            setTimeout("startKeepAlive();", 300);
		} else if ( xmlHttpKeepAlive.readyState == 3 ) {
			nSessionLostCount++;
		}

		if ( nSessionLostCount > 10 ) {
			nSessionLostCount = 0;
			window.location = "login.php";
			//window.location.reload();
		}	
	}

}

function processKeepAliveResponse(szResponse) {
	var arrResponse = szResponse.split(":");
	
	
	if ( arrResponse[0] == "keepalive" ) {
 		if ( arrResponse[1] == "0" ) {
 			nErrorCounter++;
		
			if ( nErrorCounter > 3 ) {
				window.location = "login.php";
			}
		} else {
			nErrorCounter = 0;
		}
	} else if ( arrResponse[0] == "getagentstatus" ) {//alert(arrResponse[2] + " - " + arrResponse[3])
		var optuserstatus = document.getElementById("opt" + arrResponse[2]);
		
		if ( optuserstatus ) {
			optuserstatus.innerHTML = arrResponse[2] + " - " + arrResponse[3];
		}
	} else if ( arrResponse[0] == "getuserstatus" ) {//alert(arrResponse[2] + " - " + arrResponse[3])
		var optuserstatus = document.getElementById("seluserstatus");
		
		if ( optuserstatus ) {
			optuserstatus.value = arrResponse[3];
		}
	} else if ( arrResponse[0] == "asterisk" ) {
		//ßßßßalert( szResponse );
		if ( arrResponse[1] == "queuememberstatus" ) { 
			//var divuserstatus = document.getElementById("divuserstatus");
			
			//if ( divuserstatus ) {
			//	divuserstatus.innerHTML = arrResponse[2];
			//}
		}
	} else if ( arrResponse[0] == "queueget" ) {
		var divqueue = document.getElementById("divqueue");
		var btnqueue = document.getElementById("btnqueue");
		
		if ( divqueue ) {
			var szInnerHTML = "<table class=\"tblqueues\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">"
			var bAppearQueue = false;
			/*szInnerHTML += "<tr>";
			szInnerHTML += ("<th width=\"150\">Caller ID</th>");
			szInnerHTML += ("<th width=\"200\">Queue</th>");
			szInnerHTML += ("<th width=\"150\">Time</th>");
			szInnerHTML += ("<th>Duration</th>" );
			szInnerHTML += "</tr>"; */
			
			
			
			if ( arrResponse.length >= 3 && arrResponse[2] != "" ) {
				var szCallValues = arrResponse[2].replace(/<dquote \/>/g, ":");
				var arrCallValues = szCallValues.split("|");
				var bToggle = true;
				
				for( nCnt = 0; nCnt < arrCallValues.length; nCnt++ ) {
					var szCallValue = arrCallValues[nCnt];
					var arrCallInfo = szCallValue.split(":");
					var szTableRow = "<tr bgcolor=\"" + ( bToggle ? "#f8f8f8" : "#f0f0f0" ) + "\">";
					var szTime = arrCallInfo[2].replace(/<tcolon \/>/g, ":");
						
					szTableRow += ( "<td width=\"25%\" class=\"tdqueue\">" + arrCallInfo[0] + "</td>");
					/* szTableRow += ( "<td width=\"25%\" class=\"tdqueue\">0123936595</td>"); */
					szTableRow += ( "<td width=\"52%\" class=\"tdqueue\">" + arrCallInfo[3] + "</td>");
//					szTableRow += ( "<td>" + szTime + "</td>");
					szTableRow += ( "<td width=\"23%\" class=\"tdqueue\">" + arrCallInfo[8].replace(/<tcolon \/>/g, ":") + "</td>");
//					szTableRow += ( "<td>" + arrCallInfo[3] + "</td>");
//					szTableRow += ( "<td>" + arrCallInfo[4] + "</td>");
//					szTableRow += ( "<td>" + arrCallInfo[5] + "</td>");
//					szTableRow += ( "<td>" + (arrCallInfo[6] =="NULL"? '': arrCallInfo[6]) + "</td>");/
//					szTableRow += ( "<td>" + arrCallInfo[7] + "</td>");
					szTableRow += "</tr>";
					
					bAppearQueue = true;
					szInnerHTML += szTableRow;
					
					bToggle = ( bToggle ? false : true );
				}
			}  
			
			if ( btnqueue ) {
				if ( bAppearQueue ) {
					if ( btnqueue.style.display == "none" ) {
						var effectQueue = new Effect.toggle( 'btnqueue', 'appear' );
					}
				} else {
					if ( btnqueue.style.display != "none" ) {
						var effectQueue = new Effect.toggle( 'btnqueue', 'appear' );
					} 
					if ( divqueue.style.display != "none" ) {
						var effectDivQueue = new Effect.toggle( 'divqueue', 'blind' );
					}
				}
			}
			
			szInnerHTML += "</table>";
			
			divqueue.innerHTML = szInnerHTML;
		}
	} else if ( arrResponse[0] == "callinfo" ) {
		var divcallinfo = document.getElementById("divcallinfo");
		
		if ( divcallinfo ) {
			var divcallinfo = document.getElementById("divcallinfo");
			var lblcampaigntype = document.getElementById("lblcampaigntype");
			var lbldnis = document.getElementById("lbldnis");
			var lblqueue = document.getElementById("lblqueue");
			var lblcallerno = document.getElementById("lblcallerno");
			var frameCRM = document.getElementById("frameCRM");
			var hdncallid = document.getElementById("hdncallid");
			
			if ( hdncallid ) hdncallid.value = arrResponse[2];
			if ( lbldnis ) lbldnis.innerHTML = arrResponse[1];
			if ( lblqueue ) lblqueue.innerHTML = arrResponse[4];
			if ( lblcallerno ) lblcallerno.innerHTML = arrResponse[3];
			if ( lblcampaigntype ) lblcampaigntype.innerHTML = arrResponse[5];
			
			if ( frameCRM ) {
				frameCRM.src = "../../callcenter/customers/index.php?s=" + arrResponse[3];
			}
			
			if ( divcallinfo.style.display == "none" ) {
				var effectToggle = new Effect.toggle('divcallinfo', 'appear');
			}
			
			var divoutcall = document.getElementById("divoutcall");
			
			if ( divoutcall ) {
				if ( divoutcall.style.display != "none" ) {
					var effectOutCall = new Effect.toggle('divoutcall', 'appear');
				}
			}
			
			//addHttpRequest("customercallstart", "callerid=" + lblcallerno.innerHTML + "&uniqueid=" + hdncallid.value );
		}
	} else if ( arrResponse[0] == "gettransferagents" ) {
		var divtransferagent = document.getElementById("divtransferagent");
		
		if ( divtransferagent ) {
			divtransferagent.innerHTML = arrResponse[2].replace(/<tcolon \/>/g, ":");
		}
	} else if ( arrResponse[0] == "userstatus" ) {
		var seluserstatus = document.getElementById("seluserstatus");
		
		if ( seluserstatus ) {
			var oldseruserstatusvalue = seluserstatus.value;
			seluserstatus.value = arrResponse[1];
			
			if ( arrResponse[1] != "2" && arrResponse[1] != "3" && arrResponse[1] != "4" ) {
				var divcallinfo = document.getElementById("divcallinfo");
				var divoutcall = document.getElementById("divoutcall");

				if ( divcallinfo.style.display != "none" ) {
					var effectToggle = new Effect.toggle('divcallinfo', 'appear' );
				}
				
				if ( divoutcall.style.display == "none" ) {
					var effectOutCall = new Effect.toggle('divoutcall', 'appear' );
				}

				if ( arrResponse[1] == 1 && nOutboundAvailableCount >= 1)
				{
	                                var outboundoldstatus = document.getElementById('outboundoldstatus');

        	                        if ( outboundoldstatus.value != "" && nOutboundAvailableCount >= 1 )
                	                {
                        	                var seluserstatus = document.getElementById('seluserstatus');

                                	        seluserstatus.value = outboundoldstatus.value;
                                        	addHttpRequest("statuschange", "code=" + outboundoldstatus.value);
                                        	outboundoldstatus.value = "";
 	                               }
				}
				if ( arrResponse[1] == 1 )
				{
					nOutboundAvailableCount++;
				}

			}

			/* Status Busy */
			if ( arrResponse[1] == "3" ) {
				var divcallcontrol = document.getElementById("divcallcontrol");
				var hdnagenttenantid = document.getElementById("hdnagenttenantid");
				var htnagentusername = document.getElementById("htnagentusername");
				
				addHttpRequest("gettransferagents", "tenantid="+ hdnagenttenantid.value + "&excludeuser=" + htnagentusername.value);
				
				if ( divcallcontrol ) {
					if ( divcallcontrol.style.display == "none" ) {
						var effectCallControl = new Effect.toggle('divcallcontrol', 'appear' );
					}
				}
			/* Available */
			} else if ( arrResponse[1] == "1" ) {
				var divcallcontrol = document.getElementById("divcallcontrol");
				var divtransfercontrol = document.getElementById("divtransfercontrol");
				var divwrapup1 = document.getElementById("divwrapup1");
				var hdncallid = document.getElementById("hdncallid");
				
				if ( hdncallid ) {
					var szCallID = hdncallid.value;				
					var nCnt = 1;
					var checkwrapup = document.getElementById("chkwrapup" + nCnt);
					var szwrapups = "";
					
					while ( checkwrapup != null ) {
						if ( checkwrapup.checked ) {
							if ( szwrapups == "" ) {
								szwrapups = checkwrapup.value;
							} else {
								szwrapups += ("," + checkwrapup.value );
							}
						}
						
						nCnt++;
						checkwrapup = document.getElementById("chkwrapup" + nCnt);
					}
					
					if ( szwrapups != "" ) {
						addHttpRequest( "callwrapups", "callid=" + szCallID + "&wrapups=" + szwrapups );
					} 
				}
				
				if ( divcallcontrol ) {
					if ( divcallcontrol.style.display != "none" ) {
						var effectCallControl = new Effect.toggle('divcallcontrol', 'appear' );
					}
				}
				
				if ( divtransfercontrol ) {
					if ( divtransfercontrol.style.display != "none" ) {
						var effectTransferControl = new Effect.toggle( 'divtransfercontrol', 'appear' );
					}
				}
				
				if ( divwrapup1.style.display != "none" ) {
					var effectWrapup = new Effect.toggle( 'divwrapup1', 'appear');
				}
				
				if ( hdncallid.value != 0 ) {
					var lblcallerno = document.getElementById('lblcallerno');
					
					//addHttpRequest("customercallend", "callerid=" + lblcallerno.innerHTML + "&uniqueid=" + hdncallid.value );
				}
				
				hdncallid.value = 0;
			/* this should be all unavailable codes */
			} else if ( arrResponse[1]  > 100  || arrResponse[1] == 6 ) {
				var divcallcontrol = document.getElementById("divcallcontrol");
				var divtransfercontrol = document.getElementById("divtransfercontrol");
				var divwrapup1 = document.getElementById("divwrapup1");
				var hdncallid = document.getElementById("hdncallid");
				
				if ( divcallcontrol ) {
					if ( divcallcontrol.style.display != "none" ) {
						var effectCallControl = new Effect.toggle('divcallcontrol', 'appear' );
					}
				}
				
				if ( divtransfercontrol ) {
					if ( divtransfercontrol.style.display != "none" ) {
						var effectTransferControl = new Effect.toggle( 'divtransfercontrol', 'appear' );
					}
				}
				
				if ( divwrapup1.style.display != "none" ) {
					var effectWrapup = new Effect.toggle( 'divwrapup1', 'appear');
				}
				
				hdncallid.value = 0;
			}
		}
	} else if ( arrResponse[0] == "endcall" ) {
	} else if ( arrResponse[0] == "statuschange" ) {
		if ( arrResponse[1] == "1" ) {
			var seluserstatus = document.getElementById("seluserstatus");
			
			if ( seluserstatus ) {
				seluserstatus.value = arrResponse[2];
			}
		}
	} else if ( arrResponse[0] == "wrapupshow" ) {
		if ( arrResponse[1] == "I" ) {
			addHttpRequest("wrapupdetails","wrapupdetails=inbound&queueid=" + arrResponse[2]);	
		}
	} else if ( arrResponse[0] == "wrapupdetails" ) {
		var divwrapup1 = document.getElementById("divwrapup1");
		var divwrapups = document.getElementById('divwrapups');
		
		if ( divwrapups ) {
			divwrapups.innerHTML = arrResponse[2].replace(/<tcolon \/>/g, ":");
		}
		
		if ( divwrapup1 ) {
			if ( divwrapup1.style.display == "none" ) {
				var effectWrapup = new Effect.toggle( 'divwrapup1', 'appear');
			}
		}
	} else if ( arrResponse[0] == "transfer" ) {
	} else if ( arrResponse[0] == "hold" ) {
	} else if ( arrResponse[0] == "callstart") {
		callstart(arrResponse[2], arrResponse[1], arrResponse[3]);
	} else if ( arrResponse[0] == "callend" ) {
		callend(arrResponse[2], arrResponse[1], arrResponse[3],arrResponse[4]);
        } else if ( arrResponse[0] == "getchatcontactlist" ) {
            var chatlisthtml = '';
            var noinlist = 0;
            var chatListArray = arrResponse[2].split("|");
            
            for(var i in chatListArray){
                if(i == "pull")
                    break;
                var chatIdName = chatListArray[i].split("^");
                if(chatIdName[1] != arrResponse[3] && chatIdName[1] != null)
                {
                    noinlist++;
                    chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                    chatlisthtml += '<div id="contact' + chatIdName[0] + '"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[1] + '\')"><div class="chatname"> ' + chatIdName[1] + ' </div></a></div>';
                }
            }
            
            var divchatlist = document.getElementById("chatlist");
            
            if ( divchatlist ) {
                divchatlist.innerHTML = chatlisthtml;
            }
            var divchatheader = document.getElementById("chatheader");

            if ( divchatheader ) {
                divchatheader.innerHTML = 'Chat List (' + noinlist + ')';
            }
        } else if ( arrResponse[0] == "chatreceive" ) {
            var chatIdName = arrResponse[1].split("^");

            chatWith(chatIdName[0], chatIdName[1]);
            var chatboxcontent = document.getElementById('chatboxcontent' + chatIdName[0]);
            chatboxcontent.innerHTML += '<b>' + chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1) + ' : </b>' + arrResponse[2] + '<br/>';
            chatboxcontent.scrollTop = chatboxcontent.scrollHeight;
            
            chatNotification.push('chatboxhead' + chatIdName[0]);//blink notification
        } else if ( arrResponse[0] == "broadcastreceive" ) {
            var chatIdName = arrResponse[1].split("^");

            chatWith(chatIdName[0], chatIdName[1]);
            var chatboxcontent = document.getElementById('chatboxcontent' + chatIdName[0]);
            chatboxcontent.innerHTML += '<b>' + chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1) + ' : </b>' + arrResponse[2] + '<br/>';
            chatboxcontent.scrollTop = chatboxcontent.scrollHeight;
            
            chatNotification.push('chatboxhead' + chatIdName[0]);//blink notification
        } else if ( arrResponse[0] == "chatcontactstatus" ) {
            var chatIdName = arrResponse[2].split("^");
            if ( arrResponse[1] == "offline" ) {
                if(document.getElementById("contact" + chatIdName[0]))
                {
                    removeElement("chatlist", "contact" + chatIdName[0]);
                    var chatHeaderHTML = document.getElementById("chatheader").innerHTML;
                    var NoOfContact = getNo(chatHeaderHTML);//chatCIS.js
                    document.getElementById("chatheader").innerHTML = 'Chat List (' + (NoOfContact - 1) + ')';
                }
            } else if ( arrResponse[1] == "online" ) {
                if(!document.getElementById("contact"+chatIdName[0]))
                {
                    if( document.getElementById("chatlist") )
                    {
                        chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                        document.getElementById("chatlist").innerHTML += '<div id="contact' + chatIdName[0] + '"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[1] + '\')"><div class="chatname"> ' + chatIdName[1] + ' </div></a></div>';
                        var chatHeaderHTML = document.getElementById("chatheader").innerHTML;
                        var NoOfContact = getNo(chatHeaderHTML);//chatCIS.js
                        document.getElementById("chatheader").innerHTML = 'Chat List (' + (NoOfContact + 1) + ')';
                    }
                }
            }
        } else {
		//if ( szResponse != "" ) {
		// alert( szResponse );
		//}
	
	}
}

function callstart(szUniqueID,szQueue,szCallerID) {
   addHttpRequest("customercallstart", "callerid=" + szCallerID + "&uniqueid=" + szUniqueID );
}

function callend(szUniqueID,szQueue,szCallerID,nTalkDuration) {
	addHttpRequest("customercallend", "callerid=" + szCallerID + "&uniqueid=" + szUniqueID + "&duration=" + nTalkDuration +
				   "&voiceurl=http://192.168.0.11/callcenter/admin/flash/playrecord.php?filename=" + szQueue + "-" + szUniqueID + 
				   "&queue=" + szQueue);
}

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var bKeepAliveCall = 1;
var arrayHttpRequest = new Array();
var nSessionLostCount = 0;
var nOutboundAvailableCount = 0;
