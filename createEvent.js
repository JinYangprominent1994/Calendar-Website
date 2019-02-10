function createEventDialogAjax(){
  var newEventDialog = $("#createEvent_function").dialog({
      autoOpen: false,
      height: 700,
      width: 800,
      modal: false,
      buttons: {
          "Create": createEventAjax,
           Cancel: function() {
             newEventDialog.dialog("close");
         }
      },
      close: function() {
          $("#createEventForm").trigger("reset");
      }
  });

	newEventDialog.dialog("open");
} // if click create event button, this dialog would show


function createEventAjax(){
    var title = $("#createEventTitle").val();
    var date = $("#createEventDate").val();
	  var tag = $("#createEventTag option:selected" ).text();
    var location = $("#creatEventLocation").val();
    var time = $("#createEventTime").val();

    var event_id = null;

    // Make a URL-encoded string for passing POST data:
    const data = {'title':title, 'date':date, 'tag':tag, 'location':location, 'time':time};

    fetch("createEvent.php",{
      method:"POST",
      //credentials: "include",
      body:JSON.stringify(data),
      headers:{'content-type':'application/json'}
    })
    .then(response => response.json())
    //.then(response => console.log(response.text()))
    .then(function(data){
      if(data.success) {
        $("#createEvent_function").dialog("close");
        updateCalendar();
        alert("Event Create Successfully.");
      } else {
			  alert("Event Create Error in " + data.message);
      }
    });
} // input title, date, tag, location, time to create new event

// create event button event listener
document.getElementById("createEvent_button").addEventListener("click", createEventDialogAjax, false);
