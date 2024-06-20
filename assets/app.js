
import './styles/app.scss';

import './js/home-title';
import './js/lorem_orelsum.min'
import './js/edit-scroll'
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

console.log("It is working 🎉")
