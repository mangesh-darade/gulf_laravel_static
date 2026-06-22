<?php

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Static mobility landing: serve files from public/mobility-and-recovery/ directly.
// No PHP API — admin uses manage.html + download/upload JSON.
