import naja from 'naja';
import netteForms from 'live-form-validation';
import LazyLoad from "vanilla-lazyload";

import toastr from 'toastr';

window.Nette = netteForms;
window.naja = naja;
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
//netteForms.initOnLoad();

const lazyLoadOptions = {/* your options here */};
const pageLazyLoad = new LazyLoad(lazyLoadOptions);

window.pageLazyLoad = pageLazyLoad;

import { DateTime, Duration, Interval } from "luxon";

window.DateTime = DateTime;
window.Duration = Duration;
window.Interval = Interval;
window.humanizeDuration = require("humanize-duration");

window.moment = require("./plugins/moment-with-locales.min")
window.moment.locale("cs");

import { Tooltip, Toast, Popover, Modal } from 'bootstrap';

import Prism from './../../front/js/plugins/prism';
import youtube from 'lite-youtube-embed'




window.Tooltip = Tooltip;
window.Modal = Modal;
window.Prism = Prism;

window.WOW = require('./../massive-template/js/wow.min')
require('./../massive-template/js/tiny-slider')
window.GLightbox = require('./../massive-template/js/glightbox.min')
require('./../massive-template/js/imagesloaded.min')
require('./../massive-template/js/isotope.min')
require('./../massive-template/js/main')

naja.snippetHandler.addEventListener("afterUpdate", function () {
	window.Prism.highlightAll();
});



window.updateClock = function (elementId, time) {
	let now = moment();
	let codeShareTime = document.querySelector("#" + elementId);
	codeShareTime.textContent = moment(time, "YYYYMMDDHHmmss").from(now);
}


import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import csLocale from '@fullcalendar/core/locales/cs';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;
window.csLocale = csLocale;



import {printCountdownTime, printCountdownText} from "./countdown";

window.printCountdownTime = printCountdownTime;
window.printCountdownText = printCountdownText;


import './images';
