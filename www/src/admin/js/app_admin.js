import naja from 'naja';
import netteForms from 'live-form-validation';
import LazyLoad from "vanilla-lazyload";

import toastr from 'toastr';
import * as Ladda from 'ladda';

window.Nette = netteForms;
window.naja = naja;
window.toastr = toastr;
window.Ladda = Ladda;


document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
// netteForms.initOnLoad();

import * as datagrid from './bower_components/ublaboo-datagrid/assets/datagrid'
import * as datagridspinners from './bower_components/ublaboo-datagrid/assets/datagrid-spinners'
import './bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker';
import './bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.cs.min';

import { DateTime, Duration, Interval } from "luxon";

const lazyLoadOptions = {/* your options here */};
const pageLazyLoad = new LazyLoad(lazyLoadOptions);

window.moment = require("./plugins/moment-with-locales.min");
window.moment.locale("cs");


window.DateTime = DateTime;
window.Duration = Duration;
window.Interval = Interval;
window.humanizeDuration = require("humanize-duration");
window.tooltip = require("./inspinia_js/popper.min");
//require("./inspinia_js/bootstrap.min");

import Prism from './../../front/js/plugins/prism';
import SimpleMDE from "simplemde";
import youtube from 'lite-youtube-embed'

window.Prism = Prism;

toastr.options = {
	"closeButton": true,
	"debug": false,
	"progressBar": true,
	"preventDuplicates": false,
	"positionClass": "toast-bottom-right",
	"onclick": null,
	"showDuration": "400",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "2000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
};


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


require('./myScripts');
