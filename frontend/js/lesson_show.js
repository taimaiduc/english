'use strict';

import $ from 'jquery';
import Lesson from './Components/Lesson';

$(() => {
    Lesson.init(
        App,
        $('.js-sentences-wrapper'),
        $('.js-update-user-progress')
    );
});
