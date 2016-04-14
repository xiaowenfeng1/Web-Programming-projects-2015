/*
	Xiaowen Feng
	CSE 154 AA
	Assignment 8

	This is the JavaScript code of weather site, which user can type in a city, 
	and the site would show a short descritption of the city including current date, temperature
	and weather. Also user could check temperature, precipitation for next 7 days and 7-day forecast 
*/

(function() {
	"use strict";
	var SERVICE = "https://webster.cs.washington.edu/cse154/weather.php";
	var slideTemp; //stores the ajax request response text made for the slider 

	// load the city drop down list and all the buttons/slider function 
	window.onload = function() {
		downloadText("cities", cityList); // load the city drop down list 
		document.getElementById("search").onclick = search;
		document.getElementById("precip").onclick = clickPrecip; 
		document.getElementById("temp").onclick= clickTemp;
		document.getElementById("slider").onchange = sliderChange;
	};

	// the general ajax request function that used in multiple places of the site, takes in a 
	// load function that fetches data, and a mode, maybe city parameter for the web service
	function downloadText(mode, loadFunction) {
		var ajax = new XMLHttpRequest();
		ajax.onload = loadFunction;
		var url = SERVICE + "?mode=" + mode;

		var city = document.getElementById("citiesinput").value;
		if (city) { // if a extra city parameter is passed
			url += "&city=" + city;
		} 

		ajax.open("GET", url, true);	
		ajax.send();
	}

	// called when the search button is clicked, clear out previous search if there is any,
	// show the results areas, load all the ajax requests for future use
	function search() {
		// clear all the previous searches
		document.getElementById("location").innerHTML = ""; 
		document.getElementById("currentTemp").innerHTML = "";
		document.getElementById("graph").innerHTML = "";
		document.getElementById("forecast").innerHTML = "";

		// hide the error messages for subsequent searches
		appearance("nodata", "none");
		appearance("errors", "none");

		appearance("resultsarea", "block");
		showIcons("block"); //unhide all the loading images, the slider and buttons
		
		// load all the ajax.request that the site needs
		downloadText("rain", cityData);
		downloadText("rain", temperature);
		downloadText("rain", loadPrecip);
		downloadText("forecast", forecast);
	}

	// get all cities from the server and inject them into a datalist in the input box
	function cityList() {
		appearance("loadingnames", "none");
		if (this.status == 200) { //get ajax.request success
			var text = this.responseText;
			var lines = text.split("\n");

			for (var i = 0; i < lines.length; i++) {
				var option = document.createElement("option");
				option.innerHTML = lines[i];
				document.getElementById("cities").appendChild(option);
			} 

		} else {  // fail to get ajax.request
			error(this.status, this.statusText, this.responseText);
		}
		document.getElementById("citiesinput").disabled = false; // enable the input box
	}
	
	// description of the city including the name, the current time, 
	// the current temperature, create indiviual div for each description,
	// and inject to corresponding div
	function cityData() {
		appearance("loadinglocation", "none"); // hide the loading gif
		if (this.status == 200) {
			var name = this.responseXML.querySelector("name").textContent; // get city name
			var date = Date(); // get current time 
			var weather = this.responseXML.querySelector("symbol").getAttribute("name"); 
			var temp = this.responseXML.querySelector("temperature").getAttribute("value");
			temp = Math.round(temp) + "&#8457";

			// put all the desriptions of a city into an array
			var description = [name, date, weather, temp];
			
			for (var i = 0; i < 4; i++) {
				var div = document.createElement("div");
				div.innerHTML = description[i];
				
				if (i < 3) { // put them into the location div
					document.getElementById("location").appendChild(div);
				} else { // the temperature info 
					document.getElementById("currentTemp").appendChild(div);
				}
				if (i == 0) {
					div.classList.add("title"); // add a class of title to city name
				}
			} 
		} else if (this.status == 410) {
			showIcons("none");
			appearance("nodata", ""); // show the error message for 410 error

		} else {
			error(this.status, this.statusText, this.responseText);
		}
	}
	
	// called when the Ajax precipation data is arrived, 
	function loadPrecip() {
		appearance("graph", "none"); // hidden until the user clicks precip button
		appearance("loadinggraph", "none"); 
		if (this.status == 200) {
			var rain = this.responseXML.querySelectorAll("clouds");
			var tr = document.createElement("tr");

			// display 7 or less if there are less than 7 time tags present
			for (var i = 0; i < 7 && i < rain.length; i++) { 
				var td = document.createElement("td");
				var div = document.createElement("div");
				div.innerHTML = rain[i].getAttribute("all") + "%";
				div.style.height = rain[i].getAttribute("all") + "px"; //bar height
				td.appendChild(div);
				tr.appendChild(td);	
			}
			document.getElementById("graph").appendChild(tr);

		} else if (this.status == 410) { // http 410 error
			showIcons("none");
			appearance("nodata", "");

		} else { //errors other than 410
			error(this.status, this.statusText, this.responseText);
		}
	}

	// called when the precipation button is clicked, 
	// show the graph area and hide the slider
	function clickPrecip() {
		appearance("temps", "none");
		appearance("graph", "");
	}

	// called when the temperature button is clicked, show
	// the slider and hide the graph for precipation
	function clickTemp() {
		appearance("graph", "none");
		appearance("temps", "");
	}
	
	// get the ajax request for temperature XML data, store the data into
	// the global variable slideTemp for future use
	function temperature() {
		if (this.status == 200) {
			slideTemp = this.responseXML.querySelectorAll("time temperature");
		} else if (this.status == 410) {
			showIcons("none");
			appearance("nodata", "");
		} else { //errors other than 410
			error(this.status, this.statusText, this.responseText);
		}
	}

	// called when the slider is changed, every click is the temperature 
	// of the city every 3 hours 
	function sliderChange() {
		var slider = document.getElementById("slider").value;
		var temp = slideTemp[(slider / 3)].getAttribute("value");
		document.getElementById("currentTemp").innerHTML = Math.round(temp) + "&#8457";
	}

	// called when the Ajax forecast data is arrived, create 2 rows for weather 
	// icons and temperature and inject them to the forecast div
	function forecast() {
		appearance("loadingforecast", "none");
		if (this.status == 200) {
			var cityList = JSON.parse(this.responseText);
			
			for (var i = 0; i < 2; i++) {
				var tr = document.createElement("tr");

				// create a td for each image and temperature
				// use the first 7 or less if there is less than 7 days data
				for (var j = 0; j < 7 && j < cityList.list.length; j++) { 
					var td = document.createElement("td");
					if (i == 0) { //images
						var image = cityList.list[j].weather[0].icon; // get image from JSON data
						image = "https://openweathermap.org/img/w/" + image +".png";
						var img = document.createElement("img");
						img.src = image;
						td.appendChild(img);
					} else {
						var temp = Math.round(cityList.list[j].temp.day);
						td.innerHTML = temp + "&#176";
					}
					tr.appendChild(td);
				}
				document.getElementById("forecast").appendChild(tr);
			}

		} else if (this.status == 410) {
			showIcons("none");
			appearance("nodata", "");
		} else {
			error(this.status, this.statusText, this.responseText);
		}
	}

	// takes in an error status, its status text and server response text, 
	// and show the user a error message to the relevant error.
	function error(status, statusText, reponse) {
		showIcons("none");
		var error = document.getElementById("errors");
		error.innerHTML = "Error making request:<br />" + "Server Status:\n" +
							 status + " " + statusText + "<br />Server Reponse:\n" + reponse;
	}

	// changes the apperances of loading images, slider and the buttons based 
	// on the display parameter
	function showIcons(display) {
		appearance("loadinggraph", display);
		appearance("loadingforecast", display);
		appearance("loadinglocation", display);
		appearance("temps", display);
		appearance("buttons", display);
	}

	// takes in an id of an element, and the display stlye
	// changes the style for the element
	function appearance(id, style) {
		document.getElementById(id).style.display = style;
	}

})();