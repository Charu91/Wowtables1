<?php

Route::post('conciergeapi/version', 'ConciergeAPI\MiscController@checkVersion');

// Download Route
Route::get('download/{filename}', function($filename)
{
    // Check if file exists in app/storage/file folder
    $file_path = storage_path() .'/app/file/'. $filename;
    if (file_exists($file_path))
    {
        // Send Download
        return Response::download($file_path, $filename, [
            'Content-Length: '. filesize($file_path)
        ]);
    }
    else
    {
        // Error
        exit('Requested file does not exist on our server!');
    }
})
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

//Get FAQs
Route::get('conciergeapi/faq', [
    'uses' => 'ConciergeAPI\MiscController@getFaqs',
    'middleware' => 'concierge.api'
]);
