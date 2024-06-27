
import './styles/app.scss';

import './js/home-title';
import './js/lorem_orelsum.min';
import './js/edit-scroll';
// import './js/match-button';
const $ = require('jquery');
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

console.log("It is working 🎉")
