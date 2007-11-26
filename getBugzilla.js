var ajaxObject = AjaxObject();

function AjaxObject () {
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();
	else if (window.ActiveXObject)
		return new ActiveXObject('Microsoft.XMLHTTP');
	else 
		alert ("Error Creating AjaxObject");
		return false;		
}

function verifyBugzillaLogin()
{
	var bugzillaLogin = document.getElementById('bugzilla');
	var url = "ajaxBugzilla.php?bugzillaLogin=" + bugzillaLogin.value;
	ajaxObject.open("GET", url, true);
	ajaxObject.onreadystatechange = updatePage;
	ajaxObject.send(null);
}

function updatePage()
{
	var e = document.getElementById('verify');
    if (ajaxObject.readyState == 4){
    	response = ajaxObject.responseText;
    	e.innerHTML = response;
    }
}