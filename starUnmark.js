function starUnmarkAjax(id){
	let disp = document.getElementById(id);
	let star_grey = document.createElement("IMG");
	star_grey.setAttribute("class", "star_grey");
	star_grey.setAttribute("src", "star_grey.png");
	star_grey.setAttribute("width", "15px");
	star_grey.setAttribute("height", "15px");
	disp.appendChild(star_grey);
	alert("You have successfully unmark this day as important!");

	star_grey.addEventListener("click", function(event){
		disp.removeChild(star_grey);
		starMarkAjax(id);
	}, false);
} // if user click star, user can unmark this date as an important date
