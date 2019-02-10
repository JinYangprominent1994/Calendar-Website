// show even detail dialog
function showEventDetailAjax(event){
    var event_id = $(this).attr('id');

    // Make a URL-encoded string for passing POST data:
    const data = {'event_id':event_id};

    fetch("showEventDetail.php",{
      method:"POST",
      credentials: "include",
      body:JSON.stringify(data),
      headers:{'content-type':'application/json',
               'Accept':'application/json'}
    })
    .then(response => response.json())
    //.then(response => console.log(response.text()))
    .then(function(data){
      if(data.success) {
        $("#showEventTitle").val(data.event_title);
        $("#showEventDate").val(data.event_date);
        $("#showEventLocation").val(data.event_location);
        $("#showEventTag option:selected" ).text(data.event_tag);
        //$("#showEventTag").val(date.event_tag);
        $("#showEventTime").val(data.event_time);
        $("#showEventId").val(data.event_id);
        var showEventDialog = $("#showEvent_function").dialog({
            autoOpen: false,
            height: 700,
            width: 800,
            modal: false,
            buttons: {
                "Edit": editEventAjax,
                "Delete": deleteEventAjax,
                "Share": shareEventDialogAjax,
                Cancel: function() {
                    showEventDialog.dialog( "close" );
                }
            },
            close: function() {
                $("#showEventForm").trigger("reset");
            }
        });
        showEventDialog.dialog("open");
      } else {
        alert("Log In Error in " + data.message);
      }
    });
  } // when click event title, this dialog would show and user can get detail information of this event
