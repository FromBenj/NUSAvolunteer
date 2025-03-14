import './bootstrap.js';
import './styles/app.scss';
import './js/home-title';
import './js/profile-scroll';
import  './js/address-autocomplete';
import '@fortawesome/fontawesome-free/css/all.min.css';
// For the moment, directly declare on the organisation/search.html.twig file
// import './js/match-button';

// Transfer to public/js folder because it is only used in the home page
//import './js/lorem_orelsum.min';

const $ = require('jquery');
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});


console.log("It is working 🎉")
