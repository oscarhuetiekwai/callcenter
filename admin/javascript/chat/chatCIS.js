var myUserName = "";
var myUserID = "";
var chatNotification=new Array();
var notificationColor="#bf1313"

function assignUsername(MyUname,MyUserID)
{
    myUserName = MyUname;
    myUserID = MyUserID;
    blink();
}

function toggleChatBox(ChatContentId, textareaId )
{
    var objChatContent = document.getElementById(ChatContentId);
    objChatContent.style.display = objChatContent.style.display == "none" ? "" : "none";

    if(textareaId != "")
        document.getElementById(textareaId).focus();
}

function chatWith(UserId, UserName, Type)
{
    if(Type == "broadcastall")
    {
        var namelist = "";
        var chatgroup = document.getElementsByName("chatgroup");
        for(var i in chatgroup){
            if(chatgroup[i].id)
            {
                var chatgrouptype = document.getElementsByName(chatgroup[i].id + "contact");
                for(var j in chatgrouptype){
                    if(chatgrouptype[j].id)
                        namelist += chatgrouptype[j].id.replace("contact","") + "|";
                }
            }
        }
        UserId = Type + "|" + namelist;
    }
    else if(Type == "broadcastmyteam")
    {
        var namelist = "";
        var chatagentgroup = document.getElementsByName("groupmyteamcontact");
        for(var j in chatagentgroup){
            if(chatagentgroup[j].id)
                namelist += chatagentgroup[j].id.replace("contact","") + "|";
        }
        UserId = Type + "|" + namelist;
    }
    else if(Type == "broadcastagent")
    {
        var namelist = "";
        var chatagentgroup = document.getElementsByName("groupagentcontact");
        for(var j in chatagentgroup){
            if(chatagentgroup[j].id)
                namelist += chatagentgroup[j].id.replace("contact","") + "|";
        }
        UserId = Type + "|" + namelist;
    }
    else if(Type == "broadcastsupervisor")
    {
        var namelist = "";
        var chatsupervisorgroup = document.getElementsByName("groupsupervisorcontact");
        for(var j in chatsupervisorgroup){
            if(chatsupervisorgroup[j].id)
                namelist += chatsupervisorgroup[j].id.replace("contact","") + "|";
        }
        UserId = Type + "|" + namelist;
    }
    
    if(!document.getElementById("chatbox" + UserId))
    {
        var chatboxes = document.getElementsByName("chatbox");
        var rightposition = 0;
        rightposition = chatboxes.length * 180;

        var html = "";

        html += '<div id="chatbox' + UserId + '" style="right:' + rightposition + 'px;" name="chatbox" class="chatbox" onclick="clearNotification(\'chatboxhead' + UserId + '\')">';
        html += '<div id="chatboxhead' + UserId + '" class="chatboxhead"><a href="javascript:void(0)" class="headertitle" onclick="toggleChatBox(\'chatbody' + UserId + '\',\'textarea' + UserId + '\')"><div id="chatheader" style="float: left; width: 160px;" >' + UserName.slice(0,1).toUpperCase() + UserName.slice(1) + '</div></a><div class="chatboxclose"><a href="javascript:void(0)" onclick="javascript:closeChatBox(\'chatbox' + UserId + '\')"><b>X</b></a></div></div>';
        html += '<div id="chatbody' + UserId + '">';
        html += '<div id="chatboxcontent' + UserId + '" class="chatboxcontent"></div>';
        html += '<div class="chatboxinput"><textarea id="chatboxtextarea' + UserId + '" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+UserId+'\',\'chatboxhead' + UserId + '\');" class="chatboxtextarea"></textarea></div>';
        html += '</div></div>';

        //document.body.innerHTML += html;
	 document.getElementById("chat").innerHTML += html;

        restructureChatBoxes();

        document.getElementById("chatboxtextarea" + UserId).focus();
    }
    else
    {
        var objChatBox = document.getElementById('chatbox' + UserId);
        if(objChatBox.style.display == "none")
        {
            objChatBox.style.right = restructureChatBoxes() + "px";
        }
        document.getElementById('chatbody' + UserId).style.display = "";
        objChatBox.style.display = "";
        
        document.getElementById("chatboxtextarea" + UserId).focus();
    }
}

function closeChatBox(objChatBoxId)
{
    var objChatBox = document.getElementById(objChatBoxId);

    objChatBox.style.display = "none";

    restructureChatBoxes();
}

function restructureChatBoxes()
{
    var rightposition = 0;
    var chatBoxes = document.getElementsByName("chatbox");
    
    for(var i in chatBoxes){
        if(i == "length")
            break;
        if(chatBoxes[i].style.display != "none")
        {
            chatBoxes[i].style.right = rightposition + "px";
            rightposition += 180;
        }
    }
    return rightposition;
}

function checkChatBoxInputKey(event,chatboxtextarea,toUserId,chatboxheadid) {
    //alert(event + " ; " + chatboxtextarea.value + " ; " + chatboxtitle);
    clearNotification(chatboxheadid);
    var arrToUserId = toUserId.split("|");
    
    if(event.keyCode == 13 && event.shiftKey == 0)  {
        var message = chatboxtextarea.value;
        
        if(chatboxtextarea.value != "")
        {
            xmlhttp=GetXmlHttpObject();
            xmlhttp.onreadystatechange=stateChanged;
            
            if(arrToUserId.length > 1)
                xmlhttp.open("GET", "includes/keepchatalive.php?chataction=broadcastchat&toUserId=" + toUserId + "&message=" + message, true );//broadcastchat
            else
                xmlhttp.open("GET", "includes/keepchatalive.php?chataction=sendchat&toUserId=" + toUserId + "&message=" + message, true );//sendchat
            
            xmlhttp.send(null);

            var chatboxcontent = document.getElementById('chatboxcontent' + toUserId);
            chatboxcontent.innerHTML += '<div><b>' + myUserName.slice(0,1).toUpperCase() + myUserName.slice(1) + ' : </b>' + message + '</div>';
            chatboxcontent.scrollTop = chatboxcontent.scrollHeight;

            chatboxtextarea.value="";
        }
        return false;
    }
    var chatboxtextarea = document.getElementById('chatboxtextarea' + toUserId);
    chatboxtextarea.scrollTop = chatboxtextarea.scrollHeight;
}

function GetXmlHttpObject()
{
    if (window.XMLHttpRequest)
    {
          // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }
    if (window.ActiveXObject)
    {
          // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}

function stateChanged()
{
    if (xmlhttp.readyState==4)
    {
        //alert(xmlhttp.responseText);
    }
}

function getNo(stringNo)
{
    var parsedNo = "";
    for(var n=0; n<stringNo.length; n++)
    {
        var i = stringNo.substring(n,n+1);

        if(i=="1"||i=="2"||i=="3"||i=="4"||i=="5"||i=="6"||i=="7"||i=="8"||i=="9"||i=="0")
            parsedNo += i;
    }
    return parseInt(parsedNo);
}

function removeElement(parentDiv, childDiv){
     if (childDiv == parentDiv) {
          alert("The parent div cannot be removed.");
     }
     else if (document.getElementById(childDiv)) {
          var child = document.getElementById(childDiv);
          var parent = document.getElementById(parentDiv);
          parent.removeChild(child);
     }
     else {
          alert("Child div has already been removed or does not exist.");
          return false;
     }
}

function toggleGroup(grouptitle){
    var grouptitleHTML = document.getElementById(grouptitle).innerHTML;
    document.getElementById(grouptitle).innerHTML = grouptitleHTML.substring(2,1) == "+" ? setCharAt(grouptitleHTML,1,'-') : setCharAt(grouptitleHTML,1,'+');
}

function setCharAt(str,index,chr) {
    if(index > str.length-1)
        return str;
    return str.substr(0,index) + chr + str.substr(index+1);
}

// Removes leading whitespaces
function LTrim( value ) {

	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");

}

// Removes ending whitespaces
function RTrim( value ) {

	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");

}

// Removes leading and ending whitespaces
function trim( value ) {

	return LTrim(RTrim(value));

}

function blink() {
        notificationColor=(notificationColor=="#bf1313")?"#000000":"#bf1313";
	
	for(var i = 0; i < chatNotification.length; i++)
	{
		if(chatNotification[i] != '')
			document.getElementById(chatNotification[i]).style.backgroundColor = notificationColor;
	}
        
        // Set it back to old color after 500 ms
        setTimeout("blink()", 500);
}

Array.prototype.pull = function (arg) {
    //  pulls an element out of an array, returns the single element.
    //  array.length becomes one less.
    //  may pass the index or value of element to remove
    var oEl, tmp;

    if (typeof arg == "number") {
        oEl = this[arg];
        tmp = this.slice(0,arg).concat(this.slice(arg + 1));
    }
    else if (typeof arg == "string") {
        for (var x = 0; x < this.length; x++) {
            if (this[x] == arg) {
                oEl = this[x];
                tmp = this.slice(0,x).concat(this.slice(x + 1));
                break;
            }
        }
    }
    if (typeof oEl != "undefined") {
        this.length = 0;
        for (var x = 0; x < tmp.length; x++)
            this[x] = tmp[x];
        return oEl;
    }
    else return null;
}

function clearNotification(chatboxheadid){
    chatNotification.pull(chatboxheadid);
    document.getElementById(chatboxheadid).style.backgroundColor = "#bf1313";
}

function refreshContacts(chatIdNameTemp,status){
    var chatIdName = chatIdNameTemp.split("^");
    if ( status == "offline" ) {
                if(document.getElementById("contact" + chatIdName[0]))
                {
                    if(chatIdName[2] == 2)
                    {
                        chatIdName[5] = trim(chatIdName[5]);//trim space of id before compare
                        var chatIDcompare = trim(document.getElementsByName("groupmyteamheadername").item(0).id);//trim space of id before compare
                        if(chatIDcompare == chatIdName[5])
                        {
                            //update the groupmyteamtitle counter
                            removeElement("groupmyteam", "contact" + chatIdName[0]);
                            var grouptitleHTML = document.getElementById("groupmyteamtitle").innerHTML;
                            var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                            document.getElementById("groupmyteamtitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' My Team (' + (NoOfContact - 1) + ')';
                        }
                        else
                        {
                            //update the groupagenttitle counter
                            removeElement("groupagent", "contact" + chatIdName[0]);
                            var grouptitleHTML = document.getElementById("groupagenttitle").innerHTML;
                            var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                            document.getElementById("groupagenttitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' Agent (' + (NoOfContact - 1) + ')';
                        }
                    }
                    else if(chatIdName[2] == 3 || chatIdName[2] == 5)//supervisor
                    {
                        //update the grouptitle counter
                        removeElement("groupsupervisor", "contact" + chatIdName[0]);
                        var grouptitleHTML = document.getElementById("groupsupervisortitle").innerHTML;
                        var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                        document.getElementById("groupsupervisortitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' Supervisor (' + (NoOfContact - 1) + ')';
                    }
                    //update the chatlist counter
                    var chatHeaderHTML = document.getElementById("chatheader").innerHTML;
                    var NoOfContact = getNo(chatHeaderHTML);//chatCIS.js
                    document.getElementById("chatheader").innerHTML = 'Chat List (' + (NoOfContact - 1) + ')';
                    //close the chatbox
                    var objChatBox = document.getElementById("chatbox" + chatIdName[0]);
                    objChatBox.style.display = "none";
                    restructureChatBoxes();//chatCIS.js
                }
            } else if ( status == "online" ) {
                if(!document.getElementById("contact"+chatIdName[0]))
                {
                    if(chatIdName[2] == 2)//agent
                    {
                        //if(chatIdName[5] == document.getElementById("groupmyteamheader").name)
                        if( document.getElementById("groupagent") )
                        {
                            chatIdName[5] = trim(chatIdName[5]);//trim space of id before compare
                            var chatIDcompare = trim(document.getElementsByName("groupmyteamheadername").item(0).id);//trim space of id before compare
                            if(chatIDcompare == chatIdName[5])
                            {
                                //update the groupmyteamtitle counter
                                chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                                document.getElementById("groupmyteam").innerHTML += '<div id="contact' + chatIdName[0] + '" name="groupmyteamcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[1] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[1] + ' </div></a></div>';
                                //update the grouptitle counter
                                var grouptitleHTML = document.getElementById("groupmyteamtitle").innerHTML;
                                var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                                document.getElementById("groupmyteamtitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' My Team (' + (NoOfContact + 1) + ')';
                            }
                            else
                            {
                                //update the groupagenttitle counter
                                chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                                document.getElementById("groupagent").innerHTML += '<div id="contact' + chatIdName[0] + '" name="groupagentcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[1] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[1] + ' </div></a></div>';
                                //update the grouptitle counter
                                var grouptitleHTML = document.getElementById("groupagenttitle").innerHTML;
                                var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                                document.getElementById("groupagenttitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' Agent (' + (NoOfContact + 1) + ')';
                            }
                        }
                    }
                    else if(chatIdName[2] == 3 || chatIdName[2] == 5)//supervisor
                    {
                        if( document.getElementById("groupsupervisor") )
                        {
                            chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                            document.getElementById("groupsupervisor").innerHTML += '<div id="contact' + chatIdName[0] + '" name="groupsupervisorcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[1] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[1] + ' </div></a></div>';
                            //update the grouptitle counter
                            var grouptitleHTML = document.getElementById("groupsupervisortitle").innerHTML;
                            var NoOfContact = getNo(grouptitleHTML);//chatCIS.js
                            document.getElementById("groupsupervisortitle").innerHTML = ' ' + grouptitleHTML.substring(2,1) + ' Supervisor (' + (NoOfContact + 1) + ')';
                        }
                    }

                    //update the chatlist counter
                    var chatHeaderHTML = document.getElementById("chatheader").innerHTML;
                    var NoOfContact = getNo(chatHeaderHTML);//chatCIS.js
                    document.getElementById("chatheader").innerHTML = 'Chat List (' + (NoOfContact + 1) + ')';
                }
            }
}

function refreshChatList(chatlist,chatusername)
{           
            var chatlisthtml = '';
            var agentlisthtml = '';
            var superlisthtml = '';
            var teamlisthtml = '';
            var totalnoinlist = 0;
            var myteamcount = 0;
            var mysupercount = 0;
            var myagentcount = 0;
            var chatListArray = chatlist.split("|");
            var groupsupervisortitletogglecheck = document.getElementById("groupsupervisortitle").innerHTML.substring(2,1);
            var groupagenttitletogglecheck = document.getElementById("groupagenttitle").innerHTML.substring(2,1);
            var groupmyteamtitletogglecheck = document.getElementById("groupmyteamtitle").innerHTML.substring(2,1);

            for(var i in chatListArray){
                if(i == "pull")
                    break;
                var chatIdName = chatListArray[i].split("^");
                if(chatIdName[1] != chatusername && chatIdName[1] != null)
                {
                    totalnoinlist ++;
                    chatIdName[1] = chatIdName[1].slice(0,1).toUpperCase() + chatIdName[1].slice(1);
                    if(chatIdName[2] == 2)//agent
                    {
                        if(chatIdName[5] == myUserID)//my team
                        {
                            teamlisthtml += '<div id="contact' + chatIdName[0] + '" name="groupmyteamcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[3] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[3] + ' </div></a></div>';
                            myteamcount++;
                        } else {
                            agentlisthtml += '<div id="contact' + chatIdName[0] + '" name="groupagentcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[3] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[3] + ' </div></a></div>';
                            myagentcount++;
                        }
                    }
                    else if(chatIdName[2] == 3 || chatIdName[2] == 5)//supervisor or team leader
                    {
                        superlisthtml += '<div id="contact' + chatIdName[0] + '" name="groupsupervisorcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' + chatIdName[0] + '\',\'' + chatIdName[3] + '\',\'chat\')"><div class="chatname" > ' + chatIdName[3] + ' </div></a></div>';
                        mysupercount++;
                    }

                }
            }

            var divchatlist = document.getElementById("chatlist");

            if ( divchatlist ) {
                chatlisthtml = '<div id="groupsupervisorheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupsupervisor\',\'\'); toggleGroup(\'groupsupervisortitle\')" class="grouptitle"><div id="groupsupervisortitle" style="width: 147px;"> - Supervisor (' + mysupercount + ')</div></a></td></tr></table></div>';
                chatlisthtml += '<div id="groupsupervisor" name="chatgroup" class="group">';
                chatlisthtml += superlisthtml;
                chatlisthtml += '</div>';
                chatlisthtml += '<div id="groupagentheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupagent\',\'\'); toggleGroup(\'groupagenttitle\')" class="grouptitle"><div id="groupagenttitle" style="width: 147px;"> - Agent (' + myagentcount + ')</div></a></td></tr></table></div>';
                chatlisthtml += '<div id="groupagent" name="chatgroup" class="group">';
                chatlisthtml += agentlisthtml;
                chatlisthtml += '</div>';
                chatlisthtml += '<div id="' + myUserID + '" class="grouptitlediv" name="groupmyteamheadername"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupmyteam\',\'\'); toggleGroup(\'groupmyteamtitle\')" class="grouptitle"><div id="groupmyteamtitle" style="width: 147px;"> - My Team (' + myteamcount + ')</div></a></td></tr></table></div>';
                chatlisthtml += '<div id="groupmyteam" name="chatgroup" class="group">';
                chatlisthtml += teamlisthtml;
                chatlisthtml += '</div>';
                chatlisthtml += '</div>';
                
                divchatlist.innerHTML = chatlisthtml;
                
                if(groupsupervisortitletogglecheck == '+')
                {
                    toggleChatBox('groupsupervisor',''); 
                    toggleGroup('groupsupervisortitle');
                }
                
                if(groupagenttitletogglecheck == '+')
                {
                    toggleChatBox('groupagent',''); 
                    toggleGroup('groupagenttitle');
                }
                
                if(groupmyteamtitletogglecheck == '+')
                {
                    toggleChatBox('groupmyteam',''); 
                    toggleGroup('groupmyteamtitle');
                }
            }

            var divchatheader = document.getElementById("chatheader");

            if ( divchatheader ) {
                divchatheader.innerHTML = 'Chat List (' + totalnoinlist + ')';
            }
            
            
            document.getElementById(grouptitle).innerHTML = grouptitleHTML.substring(2,1) == "+" ? setCharAt(grouptitleHTML,1,'-') : setCharAt(grouptitleHTML,1,'+');
}