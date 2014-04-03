function setTenantID(tenantID) {
	nTenantID = tenantID;
}

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
				
				/*if ( nSocketCmdCount == 1 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=callstatus", true );
					nSocketCmdCount++;
				} else if ( nSocketCmdCount == 2 ) {
					xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=userstatus", true );
					nSocketCmdCount++; */
				{
					if ( nSocketCmdCount == 1 ) {
						xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=queues", true );
						nSocketCmdCount++;
					} else if ( nSocketCmdCount == 2) {
						xmlHttpKeepAlive.open("GET", "includes/adminsrequest.php?tenantid=" + nTenantID + "&statstype=queuessummary", true );
						nSocketCmdCount++;
					} else if ( nSocketCmdCount == 3) {
                                                xmlHttpKeepAlive.open("GET", "includes/keepchatalive.php?chataction=getchatcontactlist", true );
						
					
                                                nSocketCmdCount++;
                                        } else {
						var tmNow = new Date();
						xmlHttpKeepAlive.open("GET", "includes/keepalive.php?tm=" + tmNow.getTime(), true);
						nSocketCmdCount = 1;
					     }
					}
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
			
			setTimeout("startKeepAlive();", 2000);
		} 
	}
}

function processKeepAliveResponse(szResponse) {
	var arrResponse = szResponse.split(":");
	
	if ( arrResponse[0] == "keepalive" ) {
		if ( arrResponse[1] == "0" ) {
			window.location = "login.php";
		}
	} else if ( arrResponse[0] == "adminstats") {
		if ( arrResponse[1] == "callstatus" ) {
			var divcallstats = document.getElementById("divcallstats");
			var szReturn = "<table class=\"tblcallstats\" cellpadding=\"1\" cellspacing=\"1\" border=\"1\">";
				
			szReturn += "<tr ><th width=\"80\">Caller ID</th><th width=\"70\">Status</th><th width=\"120\">Time Stamp</th><th width=\"140\">Queue</th><th width=\"150\">Agent</th><th width=\"100\">Extension</th></tr>";

			if ( divcallstats && arrResponse[3].length > 0 ) {
				var szCallValues = arrResponse[3].replace(/<dquote \/>/g, ":");
				var arrCallValues = szCallValues.split("|");
				for( nCnt = 0; nCnt < arrCallValues.length; nCnt++ ) {
					var szCallValue = arrCallValues[nCnt];
					var arrCallInfo = szCallValue.split(":");
					var szTableRow = "<tr>";
					
					szTableRow += ( "<td>" + arrCallInfo[0] + "</td>");
					szTableRow += ( "<td>" + arrCallInfo[1] + "</td>");
					szTableRow += ( "<td>" + arrCallInfo[2].replace(/<tcolon \/>/g, ":") + "</td>");
					szTableRow += ( "<td>" + arrCallInfo[3] + "</td>");
//					szTableRow += ( "<td>" + arrCallInfo[4] + "</td>");
//					szTableRow += ( "<td>" + arrCallInfo[5] + "</td>");
					szTableRow += ( "<td>" + (arrCallInfo[6] =="NULL"? '': arrCallInfo[6]) + "</td>");
					szTableRow += ( "<td>" + arrCallInfo[7] + "</td>");
					szTableRow += "</tr>";
					
					szReturn += szTableRow;
				}
			} 
			szReturn += "</table>";
			divcallstats.innerHTML =  szReturn;			
		} else if ( arrResponse[1] == "userstatus" ) {
			var divuserstats = document.getElementById("divuserstats");
			var szReturn = "<table class=\"tbluserstats\" cellpadding=\"1\" cellspacing=\"1\" border=\"1\">";
				
			szReturn += "<tr ><th width=\"60\">Agent</th><th width=\"130\">Extension</th><th width=\"120\">User Status</th><th width=\"150\">Duration</th></tr>";

             //alert( arrResponse[3] );
			if ( divuserstats && arrResponse[3].length > 0 ) {
				var szUserValues = arrResponse[3].replace(/<dquote \/>/g, ":");
				var arrUserValues = szUserValues.split("|");
				for( nCnt = 0; nCnt < arrUserValues.length; nCnt++ ) {
					var szUserValue = arrUserValues[nCnt];
					var arrUserInfo = szUserValue.split(":");
					var szTableRow = "<tr>";
					
					szTableRow += ( "<td>" + arrUserInfo[0] + "</td>");
					szTableRow += ( "<td>" + arrUserInfo[1] + "</td>");
					szTableRow += ( "<td>" + arrUserInfo[2] + "</td>");
					szTableRow += ( "<td>" + arrUserInfo[3].replace(/<tcolon \/>/g, ":") + "</td>");
					szTableRow += "</tr>";
					
					szReturn += szTableRow;
				}
			} 
			szReturn += "</table>";
			divuserstats.innerHTML =  szReturn;			
		} else if ( arrResponse[1] == "queues" ) {//alert(szResponse);
			var divqueue = document.getElementById("divqueue");
			var divqueueborder = document.getElementById("divqueueborder");
			var szPieData = "<graph pieSliceDepth='15' showBorder='1' numberSuffix='' showValues='1' showPercentageInLabel='0' decimalPrecision='0' showNames='1'>";
			
			//alert( arrResponse[2] + " : " + arrResponse[3] );
			//szPieData += ("<set name='Connected' value='" + ( arrResponse[3] == 0 ? 1 : arrResponse[3] ) + "' color='00FF00' />");
			//szPieData += ("<set name='Queue' value='" + ( arrResponse[4] == 0 ? 1 : arrResponse[4] ) + "' color='FF0000' />");
			if ( arrResponse[3] == 0 && arrResponse[4] == 0 ) {
				szPieData += ("<set name='Idle' value='1' color='0000FF' />");
			} else {
				szPieData += ("<set name='Connected' value='" +  arrResponse[3] + "' color='00FF00' />");
				szPieData += ("<set name='Queue' value='" + arrResponse[4] + "' color='FF0000' />");
			}
			szPieData += "</graph>";
			
			if ( divqueueborder ) {
				if ( divqueueborder.style.display != "none" ) {
					if ( divqueue ) {
		
						divqueue.innerHTML = arrResponse[5].replace(/<tcolon \/>/g, ":");
						//renderChart("FusionCharts/FCF_Pie3D.swf", "", szPieData, "test", 600, 300);
						if (szolddivqueuegraph != szPieData ) {
							updateChartXML('queuePieChart', szPieData);
							szolddivqueuegraph = szPieData;
						}
					}
				}
			}
		} else if ( arrResponse[1] == "queuessummary" ) {
			var divqueuesummary = document.getElementById("divqueuesummary");
			var szQueueSummaryContent = '<table>';
			var szGraphCategories = '<categories>';
			var szGraphDatasetSL = '<dataset seriesName="Service Level" color="00FF00" >';
			var szGraphDatasetAbandon = '<dataset seriesName="Abandon" color="FF0000" >';
			var szGraphDatasetRcvd = '<dataset seriesName="Calls Received" color="0000FF" >';
			var szGraphDatasetOrphaned = '<dataset seriesName="Calls Orphaned" color="FF00FF" >';
			
			if ( divqueuesummary ) {
				if ( arrResponse[3].length  > 0 ) {
					var szSummaryResponse = arrResponse[3].replace(/<tcolon \/>/g, ":");
					var arrSummary = szSummaryResponse.split("|");
					var bToggle = true;
					
					for ( nCnt = 0; nCnt < arrSummary.length; nCnt++ ) {
						if ( arrSummary[nCnt].length > 0 ) {
							var arrSummaryDetail = arrSummary[nCnt].split(":");
							
							if ( arrSummaryDetail.length > 4 ) {
								szQueueSummaryContent += '<tr bgcolor="' + ( bToggle ? "#f8f8f8" : "#f0f0f0" ) + '">';
								szQueueSummaryContent += ( '<td style="width: 120px;">' + arrSummaryDetail[0] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[1] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[2] +'</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[3] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[4] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[5] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[6] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[7] + '</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[8] + '%</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[9] + '%</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[10] + '%</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[11] + '%</td>' );
								szQueueSummaryContent += ( '<td style="width: 60px;text-align:center;">' + arrSummaryDetail[12] + '%</td>' );
								szQueueSummaryContent += ( '<td style="width: 70px;text-align:center;">' + arrSummaryDetail[13].replace(/<dcolon \/>/g, ":") + '</td>' );
								szQueueSummaryContent += '</tr>';
								bToggle = ( bToggle ? false : true );
								
								szGraphCategories += ( '<category name="' +  arrSummaryDetail[0] + '" />' );
								szGraphDatasetSL += ( '<set value="' + arrSummaryDetail[3] + '" />' );
								szGraphDatasetAbandon += ( '<set value="' + arrSummaryDetail[6] + '" />' );
								szGraphDatasetRcvd += ( '<set value="' + arrSummaryDetail[1] + '" />' );
								szGraphDatasetOrphaned += ( '<set value="' + arrSummaryDetail[7] + '" />' );
							}
						}
					}
					
					szQueueSummaryContent += '</table>';
					szGraphDatasetSL += '</dataset>';
					szGraphDatasetAbandon += '</dataset>';
					szGraphDatasetRcvd += '</dataset>';
					szGraphDatasetOrphaned += '</dataset>';
					szGraphCategories += '</categories>';
					
					var szGraph = '<graph decimalPrecision="0" animation="0" showAlternateHGridColor="1" AlternateHGridAlpha="30" AlternateHGridColor="CCCCCC" >' + szGraphCategories +
						szGraphDatasetRcvd + szGraphDatasetSL + szGraphDatasetAbandon + szGraphDatasetOrphaned + '</graph>';
					var divqueuesummarygraph = document.getElementById("divqueuesummarygraph");	
					//alert( szGraph );
					
					if ( divqueuesummarygraph ) {
						if ( divqueuesummarygraph.style.display != "none" ) {
							if ( szolddivqueuesummarygraph != szGraph ) {
								updateChartXML('queueColumnChart', szGraph);
								szolddivqueuesummarygraph = szGraph;
							}
						}
					}
					
					divqueuesummary.innerHTML  = szQueueSummaryContent;
				 	//divqueuesummary.innerHTML = arrResponse[3].replace(/<tcolon \/>/g, ":");
				}
			}
		}
	
	} else if ( arrResponse[0] == "getchatcontactlist" ) {
            refreshChatList(arrResponse[2],arrResponse[3]);
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

var xmlHttpKeepAlive = createXmlHttpRequestObject();
var nErrorCounter = 0;
var arrayHttpRequest = new Array();
var nSocketCmdCount = 0;
var nTenantID = 0;

var szolddivqueuesummarygraph = "";
var szolddivqueuegraph = "";

