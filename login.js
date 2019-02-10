function loginAjax(){
	var username = document.getElementById("username").value; // Get the username from the form
	var password = document.getElementById("password").value; // Get the password from the form

	// Make a URL-encoded string for passing POST data:
  const data = {'username':username, 'password':password};

  fetch("login.php",{
    method:"POST",
    credentials: "include",
    body:JSON.stringify(data),
    headers:{'content-type':'application/json'}
  })
  .then(response => response.json())
  .then(function(data){
    if(data.success) {
			token = data.token;
      alert("Log In Successfully");
      $("#logout_function").show();
      $("#login_function").hide();
      $("#register_function").hide();
			$("#createEvent_div").show();
			$("#changeUsername").show();
			$("#changePassword").show();
			$("#username").val("");
			$("#password").val("");
			$("#createEvent_div").show();
			$("#showTag_div").show();
      $("#token").val(data.token);
      updateCalendar();
      $("#newEvent_function").show();
      $("#editEvent_function").show();
    } else {
      alert("Log In Error in " + data.message);
    }
  });
} // if input username and user password to log in

// login button event listener
document.getElementById("login").addEventListener("click", loginAjax, false); // login_btn listener
