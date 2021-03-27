export const printCountdownTime = (endTime, element) => {
	let end = new Date(endTime).getTime();
	let now = new Date().getTime();
	const distance = end - now;

	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	if (seconds < 0) {
		minutes = 0;
		seconds = 0;
		hours = 0;
	}

	element.textContent = `${ hours > 0 ? String(hours).padStart(2, '0') + " : " : ""}${ String(minutes).padStart(2, '0')} : ${ String(seconds).padStart(2, '0')}`;
}

export const printCountdownText = (text, element) => {
	element.textContent = text;
}
