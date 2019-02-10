// event edit uploader
function editEventAjax(){
    var title = $("#showEventTitle").val();
    var date = $("#showEventDate").val();
    var tag =$( "#showEventTag option:selected" ).text();
    var location = $("#showEventLocation").val();
    var id = $("#showEventId").val();
    var time = $("#showEventTime").val();
    var editToken = token;

    // Make a URL-encoded string for passing POST data:
    const data = {'title':title, 'date':date, 'tag':tag, location:'location', 'id':id, 'time':time, 'editToken':editToken};

    fetch("editEvent.php",{
      method:"POST",
      credentials: "include",
      body:JSON.stringify(data),
      headers:{'content-type':'application/json'}
    })
    .then(response => response.json())
    //.then(response => console.log(response.text()))
    .then(function(data){
      if(data.success) {
        $("#showEvent_function").dialog("close");
        updateCalendar();
        showTagEventAjax();
        clearTagEventAjax();
        alert("Edit Event Successfully.");
      } else {
        alert("Edit Event Error in " + data.message);
      }
    });

} // user can modify event title, event date, event tag, event location, event time
