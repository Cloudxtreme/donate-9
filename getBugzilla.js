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
	var b = document.getElementById('bugzilla');
    if (ajaxObject.readyState == 4){
    	response = ajaxObject.responseText;
    	if (response == "Verified!")
    	{
    		e.setAttribute("class", "green");
    		b.setAttribute("style","border:2px solid #00FF00;");
    	}
    	else {
    		e.setAttribute("class", "red");
    		b.setAttribute("style","border:2px solid #FF0000;");
    	}
    	e.innerHTML = response;
    }
}