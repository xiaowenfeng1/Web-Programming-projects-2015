/*
	Xiaowen Feng
	CSE 154
	Assignment 6
	JavaScript code of a UI interactive speed reading site.
*/

(function() {
	"use strict";
	var timer;
	var input;
	var count = 0;

	window.onload = function() {
		disabled("stop", true);
		document.getElementById("medium").onclick = sizeClick;
		document.getElementById("big").onclick = sizeClick;
		document.getElementById("bigger").onclick = sizeClick;

		var startButton = document.getElementById("start");
		startButton.onclick = start;

		var stopButton = document.getElementById("stop");
		stopButton.onclick = stop;

		var speedList = document.getElementById("speed");
		speedList.onchange = speedChange;
	};

	// when the "start" button is clicked, split the input text
	// into frames
	function start() {
		disabled("start", true);
		input = document.getElementById("content").value;
		input = input.split(/\s+/);

		var speed = speedChange();
		timer = setInterval(readIt, speed);
	}

	// a set of behaviors when the "stop" button is clicked, 
	function stop() {
		disabled("start", false);
		displayText(true);
		clearInterval(timer);
		timer = null; // for checking whether it is stopped
		count = 0; // to restart reading from the beginning
	}

	// display the text, when it reaches the end, stop it.
	function readIt() {
		displayText(false); 
		count++;

		if (count >= input.length) { // stop when the input reaches the last word
			count = 0;
			stop();
		}
	}

	// change the size when one of the size button is checked
	function sizeClick() {
		var text = document.getElementById("reader");
		text.className = this.id; 
	}

	// check a word ends with a punctuation, if so, make it display
	// double of the normal time
	function checkPunctuation() {
		var punctuation = [",", ".", "!", "?", ";", ":"];
		for (var i = 0; i < punctuation.length; i++) {

			if (input[count].endsWith(punctuation[i])) {
				input[count] = input[count].substring(0, input[count].length - 1);
				input.splice(count, 0, input[count]); // make the word appear twice long
			}
		}	
		return input[count];
	}

	// change reading speed
	function speedChange() {
		var speed = document.getElementById("speed").value;
		if (timer) { // check whether if it is stopped
			clearInterval(timer);
			timer = setInterval(readIt, speed);
		}
		return speed;
	}

	// disable a certain control when disabled is true
	function disabled(control, disabled) {
		document.getElementById(control).disabled = disabled;
		// when the start button is disabled, the content will be also
		// disable, but the stop is enabled
		if (control == "start") {
			document.getElementById("content").disabled = disabled;
			document.getElementById("stop").disabled = !disabled;
		}
	}

	// display the input in the reader box, if stops reading, 
	// the box diplays nothing 
	function displayText(stopReading) {
		var readerBox = document.getElementById("reader");
		var word = checkPunctuation();
		if (stopReading) {
			readerBox.innerHTML = "";
		} else {
			readerBox.innerHTML = word;
		}
	}

}) ();


