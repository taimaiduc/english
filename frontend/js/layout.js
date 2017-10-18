'use strict';

import $ from 'jquery';
import 'bootstrap-sass';
import 'babel-polyfill';
import '../css/style.scss';

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
