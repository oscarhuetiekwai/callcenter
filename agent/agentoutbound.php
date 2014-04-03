<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Accordia Solution: Agent Outbound</title>
</head>

<body>
<div>
	<div id="divtitle">
    	<h1>Manual Outbound Dialer</h1>
    </div>
    <div>
    	<table id="tblmain" width="100%" height="100%">
        	<tr valign="top">
            	<td height="25">Campaign:</td>
                <td>
                	<select id="selcampaign" style="width:200px;">
                    	<option value="1">Toyota</option>
                    </select>
                </td>
                <td>
                	<input type="button" id="btncallback" value="Callback" />
                </td>
                <td>
                	<input type="button" id="btndashboard" value="Dashboard" />
                </td>
            </tr>
            <tr valign="top">
            	<td colspan="4">
                	<div id="divoutboundcontent">
                    	<table width="100%">
                        	<tr align="center">
                            	<td>Name</td>
                                <td>Phone 1</td>
                                <td>Phone 2</td>
                                <td>Phone 3</td>
                                <td>Date/Time</td>
                                <td>Wrapup</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>