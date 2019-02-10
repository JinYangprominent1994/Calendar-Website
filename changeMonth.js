function updateCalendar(){
	var weeks = currentMonth.getWeeks();
	let month_dis = weeks[2].getDates()[1].getMonth() + 1;//month to display at the top
	let year_dis = weeks[2].getDates()[1].getYear() +1900;//year to display at the top

	document.getElementById("showTime_button").addEventListener("click",function(){
		currentTime = new Date();
		document.getElementById("time").innerHTML = currentTime;
	},false) // Update Time Function

	let htmlParent = document.getElementById("displayMonth");

	while (htmlParent.firstChild) {
		htmlParent.removeChild(htmlParent.firstChild);
	}

	//display the month and year at the top of the calendar
	let month_year = document.createElement("div");
	month_year.setAttribute("class", "month_year");
	let month = monthDisplay(month_dis);
	month_year.appendChild(document.createTextNode(month + " "));
	month_year.appendChild(document.createTextNode(year_dis));
	htmlParent.appendChild(month_year);

	//print out the Sunday to Saturday on the page
	let month_format = document.createElement("div");
	month_format.setAttribute("class","month");
	htmlParent.appendChild(month_format);

	let sun = document.createElement("div");
	sun.setAttribute("class", "sun");
	sun.appendChild(document.createTextNode("Sunday"));
	month_format.appendChild(sun);

	let mon = document.createElement("div");
	mon.setAttribute("class", "mon");
	mon.appendChild(document.createTextNode("Monday"));
	month_format.appendChild(mon);

	let tue = document.createElement("div");
	tue.setAttribute("class", "tue");
	tue.appendChild(document.createTextNode("Tuesday"));
	month_format.appendChild(tue);

	let wed = document.createElement("div");
	wed.setAttribute("class", "wed");
	wed.appendChild(document.createTextNode("Wednesday"));
	month_format.appendChild(wed);

	let thr = document.createElement("div");
	thr.setAttribute("class", "thu");
	thr.appendChild(document.createTextNode("Thursday"));
	month_format.appendChild(thr);

	let fri = document.createElement("div");
	fri.setAttribute("class", "fri");
	fri.appendChild(document.createTextNode("Friday"));
	month_format.appendChild(fri);

	let sat = document.createElement("div");
	sat.setAttribute("class", "sat");
	sat.appendChild(document.createTextNode("Saturday"));
	month_format.appendChild(sat);

	for(var w in weeks){
		let week_format = document.createElement("div");
		week_format.setAttribute("class","week");
		htmlParent.appendChild(week_format);

		var days = weeks[w].getDates();

		for(var d in days){
			let day_format = document.createElement("div");
			let day_content = days[d].getDate();

			let star_date_dis = document.createElement("div");
			star_date_dis.setAttribute("class", "day");

			let star_dis = document.createElement("div");

			let date_dis = document.createElement("div");
			star_dis.setAttribute("style", "display: inline-block");

			star_date_dis.appendChild(star_dis);
			star_date_dis.appendChild(date_dis);

			if (days[d].getMonth()!= weeks[2].getDates()[1].getMonth()){
				day_content = "test";
				star_dis.setAttribute("style", "visibility: hidden");
				date_dis.setAttribute("style", "visibility: hidden");
			}
			else {
				if (day_content <= 9) {
				day_content = "0" + day_content;
				}
			}

			//display the date number
			let id =  days[d];
			temp = changeDateFormat(id);
			day_format.setAttribute("id", temp);
			day_format.appendChild(document.createTextNode(day_content));


			//display the star image(mark as important function)
			let star_grey = document.createElement("IMG");
			star_grey.setAttribute("class", "star_grey");
			let id_star = temp + "_star";
			star_dis.setAttribute("id", id_star);
			star_grey.setAttribute("src", "star_grey.png");
			star_grey.setAttribute("width", "15px");
			star_grey.setAttribute("height", "15px");


			week_format.appendChild(star_date_dis);
			star_dis.appendChild(star_grey);
			date_dis.appendChild(day_format);

			star_grey.addEventListener("click", function(event){
				star_dis.removeChild(star_grey);
				starMarkAjax(id_star);
			}, false); // Mark Specific Date

			// Make a URL-encoded string for passing POST data:
			var date = temp;
			var data = {"date":date};
			fetch("showEvent.php",{
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
					document.getElementById(event_date).innerHTML += "<div class='eventInDay' id=" + event_id + ">" + event_title + "</div>";
					}
				} else {
					alert("Unexpected Error " + data.message);
				}
			})
			.then(() => { $(".eventInDay").click(showEventDetailAjax); });
		} // Show All Events
	}
}

function monthDisplay(monthNum) {
	if (monthNum == 1) {return month = "Jan";}
	if (monthNum == 2) {return month = "Feb";}
	if (monthNum == 3) {return month = "March";}
	if (monthNum == 4) {return month = "April";}
	if (monthNum == 5) {return month = "May";}
	if (monthNum == 6) {return month = "June";}
	if (monthNum == 7) {return month = "July";}
	if (monthNum == 8) {return month = "Aug";}
	if (monthNum == 9) {return month = "Sept";}
	if (monthNum == 10) {return month = "Oct";}
	if (monthNum == 11) {return month = "Novm";}
	if (monthNum == 12) {return month = "Dec";}
} // display month one by one
function changeDateFormat(date){
	let result = "";
	let temp = (String(date)).substr(4,12);
	let year = "";
	let month = "";
	let datee = "";

	temp_year = temp.substr(7,4);
	temp_month = temp.substr(0,3);
	temp_datee = temp.substr(4,2);

	if (temp_month == "Jan") {temp_month = "01";}
	if (temp_month == "Feb") {temp_month = "02";}
	if (temp_month == "Mar") {temp_month = "03";}
	if (temp_month == "Apr") {temp_month = "04";}
	if (temp_month == "May") {temp_month = "05";}
	if (temp_month == "Jun") {temp_month = "06";}
	if (temp_month == "Jul") {temp_month = "07";}
	if (temp_month == "Aug") {temp_month = "08";}
	if (temp_month == "Sep") {temp_month = "09";}
	if (temp_month == "Oct") {temp_month = "10";}
	if (temp_month == "Nov") {temp_month = "11";}
	if (temp_month == "Dec") {temp_month = "12";}

	result = temp_year + "-" + temp_month + "-" + temp_datee;

	return result;
}  // change date format in order to assign the id
