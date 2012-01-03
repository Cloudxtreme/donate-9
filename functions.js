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
	var anon = document.getElementById("os1");
 
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
	if (a.value < 2)
	{
		alert("Amount must be greater then $2.");
		return false;
	}
	if (isNumeric(a.value) == false)
	{
		alert("Amount must contain numbers only.");
		return false;
	}
	if (a.value.length == 0)
	{
		alert("Please specify an amount.");
		return false;
	}
	if (parseInt(a.value) >= 35) {
		if (anon.checked == true)	{

			if (b.value.length !=0)	{
				if (v.innerHTML.length == 0) {
					alert("Please verify your Eclipse.org login to continue");
					return false;
				}
				if (v.innerHTML != "Verified!") {
					alert ("Your Eclipse.org ID could not be verified." + '\n' + "For donations of $35 dollars or more we require you to provide a valid ID.  If you do not wish to provide your Eclipse.org ID please list your name Anonymously.");
					return false;	
				}
			}
			else {
				alert("For donations of $35 dollars or more we require you to provide your Eclipse.org account ID.  If you do not wish to provide it, please list your name Anonymously.");
				return false;
			}
			
		}
	}
	return retVal;
}
 		
 		
function isNumeric(input)
{
	var numbers = "01234567890.-";
	var singleChar;
	var retVal = true;
	if (input.length > 0)
	{
		for (i = 0; i < input.length && retVal == true; i++)
		{
			singleChar = input.charAt(i);
			if (numbers.indexOf(singleChar) == -1)
			{
				retVal = false;
			}
		}
	}
	return retVal;
}

function verifyBugzillaLogin()
{
	var v = document.getElementById('verify');
	var a = document.getElementById('amount');
	var bugzillaLogin = document.getElementById('bugzilla');
	if (bugzillaLogin.value.length == 0 || parseInt(a.value) < 35)
	{
		v.innerHTML = "";
		v.removeAttribute("class");
		bugzillaLogin.value = "";
		bugzillaLogin.removeAttribute("style");
	}
	
	var url = "ajaxBugzilla.php?bugzillaLogin=" + bugzillaLogin.value.replace('+', '%2B');
	ajaxObject.open("GET", url, true);
	ajaxObject.onreadystatechange = updatePage;
	ajaxObject.send(null);
	
}

function updatePage()
{
	var v = document.getElementById('verify');
	var b = document.getElementById('bugzilla');
	var anon = document.getElementById('os1');
    if (ajaxObject.readyState == 4){
    	response = ajaxObject.responseText;
    	if (response == "Verified!" || b.value.length == 0 || anon.checked == false)
   		{
    		if (response == "Verified!") 
			{
				v.innerHTML = response;    	
				v.setAttribute("class", "green");
				b.setAttribute("style","border:2px solid green;");
			}
    		if (validateForm())
    			document.donateForm.submit();
	    }
    	else {
    		v.innerHTML = response;    	
    		v.setAttribute("class", "red");
    		b.setAttribute("style","border:2px solid #FF0000;");
    		validateForm();
    	}
    }
}