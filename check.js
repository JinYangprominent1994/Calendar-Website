$(document).ready(function(){
	// check the statue of log in
    var data = null;
    var xmlHttp = new XMLHttpRequest();
    xml.withCredentials = true;
    xmlHttp.open("POST", "checkStatus.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
    data = JSON.parse(event.target.responseText);
		if (data.success){
			$("#login_function").hide();
			$("#logout_function").show();
      $("#register_function").hide();
      $("#newEvent_function").hide();
      $("#editEvent_function").hide();
      $("#user_name").val(data.user_name);
      $("#user_id").val(data.user_id);
      $("#token").val(data.token);
      updateCalendar();
		} else {
      alert(data.message);
			$("#login_function").show();
      $("#register_function").show();
			$("#logout_function").hide();
      $("#newEvent_function").hide();
      $("#editEvent_function").hide();
		}
  }, false);

});
