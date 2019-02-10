function starMarkAjax(id){
	let disp = document.getElementById(id);
	let star_red = document.createElement("IMG");
	star_red.setAttribute("class", "star_red");
	star_red.setAttribute("src", "star_red.png");
	star_red.setAttribute("width", "15px");
	star_red.setAttribute("height", "15px");
	disp.appendChild(star_red);


	alert("Successfully mark this day as an important day!");

	star_red.addEventListener("click", function(event){
		disp.removeChild(star_red);
		starUnmarkAjax(id);
	}, false);
} // if click star, user can mark this date as an important date
