function display(action, id)
{
if (action == 'show')
{
document.getElementById("divqueue"+id).style.display = "block";
document.getElementById("link"+id).href= "javascript:display('hide', "+id+")";
document.getElementById("link"+id).innerHTML = "- Queue";
}

if (action == 'hide')
{
document.getElementById("divqueue"+id).style.display = "none";
document.getElementById("link"+id).href= "javascript:display('show', "+id+")";
document.getElementById("link"+id).innerHTML = "+ Queue";
}
if (action == 'view')
{
document.getElementById("chartdiv"+id).style.display = "block";
document.getElementById("loan"+id).href= "javascript:display('hidden', "+id+")";
document.getElementById("loan"+id).innerHTML = "- Call Graph";
}
if (action == 'hidden')
{
document.getElementById("chartdiv"+id).style.display = "none";
document.getElementById("loan"+id).href= "javascript:display('view', "+id+")";
document.getElementById("loan"+id).innerHTML = "+ Call Graph";
}
}