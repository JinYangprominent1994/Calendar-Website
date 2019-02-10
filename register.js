function registerAjax(event){
  var username = document.getElementById("newUsername").value; // Get the username from the form
	var password = document.getElementById("newPassword").value; // Get the password from the form

	// Make a URL-encoded string for passing POST data:
  const data = {'username':username, 'password':password};

  fetch("register.php",{
    method:"POST",
    credentials: "include",
    body:JSON.stringify(data),
    headers:{'content-type':'application/json'}
  })
  .then(response => response.json())
  //.then(data => alert(data.success? "Register Successfully": 'Register Error In ${data.message}'));
  .then(function(data){
    if(data.success){
      alert("Register Successfully");
      $("#logout_function").hide();
      $("#login_function").show();
      $("#register_function").hide();
      $("#newEvent_function").hide();
      $("#editEvent_function").hide();
      $("#newUsername").val("")
      $("#newPassword").val("")
    } else {
      alert("Register Error in " + data.message);
    }
  });
} // if input user name and user password to register

// register button event listener
document.getElementById("register").addEventListener("click", registerAjax, false);
