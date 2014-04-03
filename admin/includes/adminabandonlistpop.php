<script src="../javascript/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<script src="../javascript/jquery-ui.js"></script>

<script>
function loadOnPrint()
{
var sortByFil = opener.document.getElementById("sortByFil");
var calleridFil = opener.document.getElementById("calleridFil");
var queueNameFil = opener.document.getElementById("queueNameFil");
var dateFil = opener.document.getElementById("dateFil");

$('#abandonPrintList').load('adminabandonlistfiltered.php?&queueNameFil='+queueNameFil.value+'&dateFil='+dateFil.value+'&sortByFil='+sortByFil.value+'&calleridFil='+calleridFil.value);
window.print();
}
</script>

<body onload="loadOnPrint();">

<style>
#abandonPrintList {
width:100%;
height:100%;
}

</style>

<div id="abandonPrintList">
</div>