// event delete uploader
function deleteEventAjax(){
    var event_id = $("#showEventId").val();
    var deleteToken = token;

    // Make a URL-encoded string for passing POST data:
    const data = {'event_id':event_id, 'deleteToken':deleteToken};

    fetch("deleteEvent.php",{
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
        alert("Delete Successfully");
      } else {
        alert("Delete Error in " + data.message);
      }
    });
} // when view the detail of an event, user can delete this event
