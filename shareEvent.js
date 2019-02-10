function shareEventDialogAjax(){
  var shareEventDialog = $("#shareEvent_function").dialog({
      autoOpen: false,
      height: 700,
      width: 800,
      modal: false,
      buttons: {
          "Share": shareEventAjax,
           Cancel: function() {
             shareEventDialog.dialog("close");
         }
      },
      close: function() {
          $("#shareEventForm").trigger("reset");
      }
  });
  shareEventDialog.dialog("open");
} // if clikc share button, this dialog would show, user has to input username that he/she wants to show

function shareEventAjax(){
  var title = $("#showEventTitle").val();
  var date = $("#showEventDate").val();
  var tag = $( "#showEventTag option:selected" ).text();
  var location = $("#showEventLocation").val();
  var time = $("#showEventTime").val();
  var shareUsername = $("#shareUsername").val();

  // Make a URL-encoded string for passing POST data:
  const data = {'title':title, 'date':date, 'tag':tag, 'location':location, 'time':time, 'shareUsername':shareUsername};

  fetch("shareEvent.php",{
    method:"POST",
    credentials: "include",
    body:JSON.stringify(data),
    headers:{'content-type':'application/json'}
  })
  .then(response => response.json())
  //.then(response => console.log(response.text()))
  .then(function(data){
    if(data.success) {
      $("#shareEvent_function").dialog("close");
      updateCalendar();
      alert("Event Share Successfully.");
    } else {
  		alert("Event Share Error in " + data.message);
    }
  });
} // input share username to share this event 
