function showTagEventAjax(){
  var tag = $("#showTagEvent option:selected" ).text();
  var tagEventDiv = document.createElement("div");
  tagEventDiv.setAttribute("id","tagEvent");
  document.getElementById("tagDetail").appendChild(tagEventDiv);

  // Make a URL-encoded string for passing POST data:
  const data = {'tag':tag};

  fetch("showTagEvent.php",{
    method:"POST",
    credentials: "include",
    body:JSON.stringify(data),
    headers:{'content-type':'application/json'}
  })
  .then(response => response.json())
  .then(function(data){
      if(data.success) {
      var count = data.count;
      var jsonData = data.eventData;
      for(var i=0; i<count;i++){
      event_id = jsonData[i].event_id;
      event_title = jsonData[i].event_title;
      event_date = jsonData[i].event_date;
      event_tag = jsonData[i].event_tag;
      document.getElementById("tagEvent").innerHTML += "<div class='eventInDay' id=" + event_id + ">" + event_title + event_date + "</div>";
     }
    } else {
      alert("Unexpected Error " + data.message);
    }
  })
  .then(() => { $(".eventInDay").click(showEventDetailAjax); });
} // if click show tag event button, all events with this tag would show

function clearTagEventAjax(){
  document.getElementById("tagEvent").remove();
} // if click clear button, all event would be removed from this webpage.

// show tag event button event listener
document.getElementById("showTagEvent_button").addEventListener("click",showTagEventAjax,false);
// clear button event listener
document.getElementById("clearEvent_button").addEventListener("click",clearTagEventAjax,false);
