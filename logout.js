document.getElementById("logout").addEventListener("click", logoutAjax, false);
// logout button event listener

function logoutAjax(event){
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.withCredentials = true;
	  xmlHttp.open("POST", "logout.php", true);
	  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
  		var data = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
  		if(data.success){
        token = null;
        $("#logout_function").hide();
        $("#login_function").show();
        $("#register_function").show();
        $("#changeUsername").hide();
        $("#changePassword").hide();
        $("#createEvent_div").hide();
        $("#shareEvent_function").hide();
        $("#showTag_div").hide();
        $("#createEvent_div").hide();
        $("#newEvent_function").hide();
        $("#editEvent_function").hide();
        updateCalendar();
        clearTagEventAjax();
        alert("Log Out Successfully!");
  		}else{
  			alert("Log Out Error");
  		}
  	}, false); // Bind the callback to the load event
  	xmlHttp.send(null);
} // user can clikc logout button to logout
