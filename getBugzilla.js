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

function whatsThisToggle(i) {
	var e = document.getElementById(i);
	var t = e.className;
	if (t.match('invisible')) { t = t.replace(/invisible/gi, 'visible'); }
	else { t = t.replace(/visible/gi, 'invisible'); }
	e.className = t;
}
 		
function amountCheck() {
	var a = document.getElementById("amount");
	var b = document.getElementById("bugzilla"); 
	if (a.value >=35)
	{
		b.removeAttribute("disabled");
	}
	else {
		b.setAttribute("disabled", "");
	}
}
function validateForm() {
	var fn = document.getElementById("first_name");
	var ln = document.getElementById("last_name");
	var a = document.getElementById("amount");
	var b = document.getElementById("bugzilla");
	var v = document.getElementById("verify");
 
 	if (fn.value.length == 0)
 	{
 		alert("Please specify a first name.");
		return false;
	}
	if (ln.value.length == 0)
	{
		alert("Please specify a last name.");
		return false;
	}
	if (a.value <= 0)
	{
		alert("Amount must be greater then 0.");
		return false;
	}
	if (a.value.length == 0)
	{
		alert("Please specify an amount.");
		return false;
	}
	if (a.value >=35)
	{
		if (v.innerHTML.length == 0 && b.value.length !=0)
		{
			alert("Please verify your bugzilla login to continue");
			return false;
		}
		if (v.innerHTML != "Verified!")
		{
			alert ("Your Bugzilla Login is not correct.");
			return false;
		}
	}
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
    		b.setAttribute("style","border:2px solid green;");
    		if (validateForm())
    			document.donateForm.submit();
	    }
    	else {
    		e.setAttribute("class", "red");
    		b.setAttribute("style","border:2px solid #FF0000;");
    		validateForm();
    	}
    	e.innerHTML = response;
    }
}