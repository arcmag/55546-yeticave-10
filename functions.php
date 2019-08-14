<?php

function to_format_currency($price) {
    return number_format($price, 0, '', ' ');
}

