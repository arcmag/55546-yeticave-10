<?php

function to_format_currency($price) {
    return number_format($price, 0, '', ' ');
}

function get_dt_range($date) {
    return explode(':', date('H:i', strtotime($date) - time()));
}

