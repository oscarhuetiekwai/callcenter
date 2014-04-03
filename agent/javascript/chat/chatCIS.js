var myUserName = "";
var chatNotification=new Array();
var notificationColor="#bf1313"

function assignUserName(MyUname)
{
    myUserName = MyUname;
    blink();
}

function toggleChatBox(ChatContentId, textareaId )
{
    var objChatContent = document.getElementById(ChatContentId);
    objChatContent.style.display = objChatContent.style.display == "none" ? "" : "none";

    if(textareaId != "")
        document.getElementById(textareaId).focus();
}

function chatWith(UserId, UserName)
{
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

        document.body.innerHTML += html;

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
    if(event.keyCode == 13 && event.shiftKey == 0)  {
        var message = chatboxtextarea.value;
        
        if(chatboxtextarea.value != "")
        {
            xmlhttp=GetXmlHttpObject();
            xmlhttp.onreadystatechange=stateChanged;
            xmlhttp.open("GET", "includes/keepchatalive.php?chataction=sendchat&toUserId=" + toUserId + "&message=" + message, true );
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