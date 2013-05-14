/* 
	blueprint grid v1.0 - For Blueprint CSS Framework (http://www.blueprintcss.org/)
	(c) 2011 Dave Rogers (http://shinytype.com), freely distributable under the terms of the MIT license:
	
		Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
		documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
		the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
		and to dpermit persons to whom the Software is furnished to do so, subject to the following conditions:

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO 
		THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
		TORT OR OTHERWISE, ARISING FROM, OUT OF OR 	IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	
	
	Based off less grid v4.0 (c) by Arnau March (http://arnaumarch.com/) - also under MIT License
*/

$(document).ready(function() {
	createSwitch();
});


function createSwitch() {
	$('body').append('<span id="toggle-button">o</span>');
	
	// floating toggle button
	$('#toggle-button').css({ 
							position: "absolute",
							top: "0",
							right: "0",
							background: "#3d5fa3",
							border: "2px solid #fff",
							borderTop: 0,
							color: "#fff",
							fontSize: "13px",
							lineHeight: "13px",
							padding: "2px 8px 6px 8px",
							cursor: "pointer",
							"border-radius": "0 0 5px 5px",
							"-moz-border-radius": "0 0 5px 5px",
							zIndex: 1000
	});
	$('#toggle-button').toggle(function() {
		$(this).text("x");
		$("#container").toggleClass("showgrid");
	}, function() {
		$(this).text('o');
		$("#container").toggleClass("showgrid");
	});
}