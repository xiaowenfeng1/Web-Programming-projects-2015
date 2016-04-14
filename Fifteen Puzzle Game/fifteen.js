/*
	Xiaowen Feng
	CSE 154 AA
	Assignment 7

	This is the JavaScript code of a puzzle game called "Fifteen". It allows users to 
	interactively play the puzzle game with a shuffle feature, and provides 
	hints whether a tile is movable.
*/

(function() {
	"use strict";
	var size = 100; // width and height of each tile
	var puzzleSize = 4; // rows/columns in the puzzle area
	var emptyCoord = [300, 300]; // original coordinates of the empty square 

	window.onload = function() {
		addTiles();
		var shuffleButton = document.getElementById("shufflebutton");
		shuffleButton.onclick = shuffle;
	};

	//	creates the fifteen tiles with numbers for the game, and assign each 
	//	tile a part of the background image, and an unique id 
	//	based on their posistion. 
	function addTiles() {
		for (var i = 1; i < (puzzleSize * puzzleSize); i++) {
			// x and y coordinates of each tile
			var x = (i - 1) % puzzleSize * size;
			var y = Math.floor((i - 1) / puzzleSize) * size;

			var tile = document.createElement("div");
			tile.className = "tile";

			var puzzleArea = document.getElementById("puzzlearea");
			puzzleArea.appendChild(tile);

			tile.style.backgroundPosition = (-x) + "px " + (-y) + "px";
			tile.style.left =  x + "px";
			tile.style.top =  y + "px";
			tile.innerHTML = i;

			// assign each tile an id based on their row/column
			tile.id = (x / size) + "_" + (y / size); 
			tile.onclick = moveIt;
			tile.onmouseover = rectMouseOver;
			tile.onmouseout = rectMouseOut;
		}	
	}

	// if a tile is movable, move the clicked tile to the empty square 
	// and update the coordinates and the id of the empty square
	function moveIt() {
		if (isMovable(this)) {
			var oldCoord = getCoordinates(this); 
			this.style.left =  emptyCoord[0] + "px";
			this.style.top = emptyCoord[1] + "px";
			this.id = (emptyCoord[0] / size) + "_" + (emptyCoord[1] / size); // update tile id

			emptyCoord = oldCoord; // update the empty square coordinates. 
		}
	}

	// get the x and y coordinates of the clicked tile and return the values.
	function getCoordinates(tile) {
		var xCoord = parseInt(window.getComputedStyle(tile).left);
		var yCoord = parseInt(window.getComputedStyle(tile).top);
		return [xCoord, yCoord];
	}

	// return a boolean whether or not the cliked tile is movable
	function isMovable(tile) {
		var currentCoord = getCoordinates(tile);

		// if the absolute values of the differencs of x and y coordinates
		// together between the empty square and the clicked tile is equal to the tile size, 
		// the clicked tile and empty qaure are neighbors.
		return (Math.abs(emptyCoord[0] - currentCoord[0]) + 
			    Math.abs(emptyCoord[1] - currentCoord[1]) == size);
	}

	// called when the mouse cursor move around on a tile, to provide a hint 
	// for the users whether the tile is movabled or not
	function rectMouseOver() {
		var currentCoord = getCoordinates(this);
		if (isMovable(this)) {
			this.classList.add("movable");
		}
	}

	// called when the mouse cursor leave the tile, and remove the hint
	function rectMouseOut() {
		if (isMovable(this)) {
			this.classList.remove("movable");
		}
	}
	
	// called when the shuffle button is clicked, shuffle the tiles
	// by finding a random movable neighbor and slide to it
	function shuffle() {
		for (var i = 0; i< 1000; i++) {
			findNeighbors().click(moveIt);
		}
	} 
	// find the empty squre's neighbors, and return a random neighbor
	function findNeighbors() {
		var neighbors = [];

		// the row and column position of the empty square
		var emptyX = emptyCoord[0] / size;
		var emptyY = emptyCoord[1] / size;

		// check whether there are horizontal neighbors 
		for (var i = emptyX - 1; i <= emptyX + 1; i+= 2) {
			var horizontal = document.getElementById( i + "_" + emptyY);
			if (horizontal) {
				neighbors.push(horizontal);
			} 
		}

		// check whether there are vertical neighbors 
		for (var i = emptyY -1; i<= emptyY + 1; i += 2) {
			var vertical = document.getElementById((emptyX + "_" + i));
		
			if (vertical) {
				neighbors.push(vertical);
			}
		}

		var move = parseInt(Math.random() * neighbors.length);
		return neighbors[move];
	}

})();