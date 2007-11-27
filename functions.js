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
		b.disabled=false;
		b.className = "enabled";
	}
	else {
		b.disabled=true;
		b.className = "disabled";
	}
}
function validateForm() {
	var retVal = true;	
	var fn = document.getElementById("first_name");
	var ln = document.getElementById("last_name");
	var a = document.getElementById("amount");
	var b = document.getElementById("bugzilla");
	var v = document.getElementById("verify");
 
 	if (fn.value.length == 0)
 	{
 		alert("Please specify a first name.");
		retval = false;
	}
	if (ln.value.length == 0)
	{
		alert("Please specify a last name.");
		retval = false;
	}
	if (a.value <= 0)
	{
		alert("Amount must be greater then 0.");
		retval = false;
	}
	if (a.value.length == 0)
	{
		alert("Please specify an amount.");
		retval = false;
	}
	if (a.value >=35 && b.value.length !=0)
	{
		if (v.innerHTML.length == 0)
		{
			alert("Please verify your bugzilla login to continue");
			retval = false;
		}
		if (v.innerHTML != "Verified!")
		{
			alert ("Your Bugzilla Login is not correct.");
			retval = false;
		}
	}
	return retVal;
}
 		

function verifyBugzillaLogin()
{
	var v = document.getElementById('verify');
	var bugzillaLogin = document.getElementById('bugzilla');
	if (bugzillaLogin.value.length == 0)
	{
		v.innerHTML = "";
		v.removeAttribute("class");
		bugzillaLogin.removeAttribute("style");
	}
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
    	if (response == "Verified!" || b.value.length == 0)
   		{
    		if (response == "Verified!") 
			{
				e.innerHTML = response;    	
				e.setAttribute("class", "green");
				b.setAttribute("style","border:2px solid green;");
			}
    		if (validateForm())
    			document.donateForm.submit();
	    }
    	else {
    		e.innerHTML = response;    	
    		e.setAttribute("class", "red");
    		b.setAttribute("style","border:2px solid #FF0000;");
    		validateForm();
    	}
    }
}