import naja from "naja";
import SimpleMDE from 'simplemde';
window.simplemdes = [];


document.addEventListener('DOMContentLoaded', function () {
	initDatePicker();
	initMarkDown();
	initLada();
});

naja.snippetHandler.addEventListener("afterUpdate", function () {
	initDatePicker();
	initMarkDown();
	window.Prism.highlightAll();
	initLada();
});

function initDatePicker() {
	$('.datepicker').datepicker({
		autoclose: true,
		todayHighlight: true,
		format: 'dd.mm.yyyy',
		language: 'cs'
	});
}

function initMarkDown() {
	let markdowns = document.querySelectorAll(".markdown");

	markdowns.forEach(function (markdown, index) {
		if (window.simplemdes[markdown.getAttribute("id")] === undefined) {
			window.simplemdes[markdown.getAttribute("id")] = new SimpleMDE({
				element: markdown,
				showIcons: ["table"],
				forceSync: true
			});
		}
	})
}

window.updateClock = function (elementId, time) {
	let now = moment();
	let codeShareTime = document.querySelector("#" + elementId);
	codeShareTime.textContent = moment(time, "YYYYMMDDHHmmss").from(now);
}



// add loader to ajax buttons
function initLada() {

	let inputs = document.querySelectorAll("input[type=submit]");

	inputs.forEach(function (input) {
		let button = document.createElement("button");
		button.type = "submit";
		button.innerText = input.value;
		button.classList.add("btn");
		button.classList.add("btn-primary");
		input.replaceWith(button);
	});

	Ladda.bind('button[type=submit]', {timeout: 2000});
	Ladda.bind('.btn.ajax', {timeout: 2000});
}
