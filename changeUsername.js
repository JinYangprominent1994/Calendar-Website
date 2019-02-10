function changeUsernameDialogAjax(){
  var changeUsernameDialog = $("#changeUsername_function").dialog({
      autoOpen: false,
      height: 700,
      width: 800,
      modal: false,
      buttons: {
          "Change": changeUsernameAjax,
           Cancel: function() {
             changeUsernameDialog.dialog("close");
         }
      },
      close: function() {
          $("#changeUsernameForm").trigger("reset");
      }
  });

	changeUsernameDialog.dialog("open");
} // if user click change username button, this dialog would show

function changeUsernameAjax(){
    var oldUsername = $("#oldUsername").val();
    var unchangePassword = $("#unchangePassword").val();
	  var newChangeUsername = $("#newChangeUsername" ).val();

    // Make a URL-encoded string for passing POST data:
    const data = {'oldUsername':oldUsername, 'unchangePassword':unchangePassword, 'newChangeUsername':newChangeUsername};

    fetch("changeUsername.php",{
      method:"POST",
      credentials: "include",
      body:JSON.stringify(data),
      headers:{'content-type':'application/json'}
    })
    .then(response => response.json())
    .then(function(data){
      if(data.success) {
        $("#changeUsername_function").dialog("close");
        updateCalendar();
        alert("Change Username Successfully.");
      } else {
			  alert("Change Username Error in " + data.message);
      }
    });
} // If input old username, password, new username, user ca change username

// change username button event listener
document.getElementById("changeUsername_button").addEventListener("click", changeUsernameDialogAjax, false); // login_btn listener
