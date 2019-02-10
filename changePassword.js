function changePasswordDialogAjax(){
  var changePasswordDialog = $("#changePassword_function").dialog({
      autoOpen: false,
      height: 700,
      width: 800,
      modal: false,
      buttons: {
          "Change": changePasswordAjax,
           Cancel: function() {
             changePasswordDialog.dialog("close");
         }
      },
      close: function() {
          $("#changePasswordForm").trigger("reset");
      }
  });

	changePasswordDialog.dialog("open");
} // If user click change password, this dialog would show

function changePasswordAjax(){
    var unchangeUsername = $("#unchangeUsername").val();
    var oldPassword = $("#oldPassword").val();
	  var newChangePassword = $("#newChangePassword" ).val();

    // Make a URL-encoded string for passing POST data:
    const data = {'unchangeUsername':unchangeUsername, 'oldPassword':oldPassword, 'newChangePassword':newChangePassword};

    fetch("changePassword.php",{
      method:"POST",
      credentials: "include",
      body:JSON.stringify(data),
      headers:{'content-type':'application/json'}
    })
    .then(response => response.json())
    .then(function(data){
      if(data.success) {
        $("#changePassword_function").dialog("close");
        updateCalendar();
        alert("Change Password Successfully.");
      } else {
			  alert("Change Password Error in " + data.message);
      }
    });
} // Input username, old password and new password to change password.

// change password button event listener
document.getElementById("changePassword_button").addEventListener("click", changePasswordDialogAjax, false); // login_btn listener
