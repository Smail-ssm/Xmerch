<?php

/**
 * POD PRINT JOB ROUTES
 * 
 * Add these routes to your routes/web.php file
 * Place them inside the admin middleware group
 */

// Print Job Management Routes
Route::prefix('admin')->middleware('auth:admin')->group(function() {
    
    // Dashboard & Views
    Route::get('/printjobs', 'Admin\PrintJobController@index')->name('admin-printjob-index');
    Route::get('/printjobs/queue', 'Admin\PrintJobController@queue')->name('admin-printjob-queue');
    Route::get('/printjobs/printing', 'Admin\PrintJobController@printing')->name('admin-printjob-printing');
    Route::get('/printjobs/completed', 'Admin\PrintJobController@completed')->name('admin-printjob-completed');
    Route::get('/printjobs/failed', 'Admin\PrintJobController@failed')->name('admin-printjob-failed');
    
    // DataTables
    Route::get('/printjobs/datatables/{status}', 'Admin\PrintJobController@datatables')->name('admin-printjob-datatables');
    
    // Job Details
    Route::get('/printjobs/{id}', 'Admin\PrintJobController@show')->name('admin-printjob-show');
    
    // Job Actions
    Route::post('/printjobs/{id}/start', 'Admin\PrintJobController@start')->name('admin-printjob-start');
    Route::post('/printjobs/{id}/complete', 'Admin\PrintJobController@complete')->name('admin-printjob-complete');
    Route::post('/printjobs/{id}/fail', 'Admin\PrintJobController@fail')->name('admin-printjob-fail');
    Route::post('/printjobs/{id}/hold', 'Admin\PrintJobController@hold')->name('admin-printjob-hold');
    Route::post('/printjobs/{id}/resume', 'Admin\PrintJobController@resume')->name('admin-printjob-resume');
    Route::post('/printjobs/{id}/assign', 'Admin\PrintJobController@assign')->name('admin-printjob-assign');
    
    // Bulk Actions
    Route::post('/printjobs/bulk', 'Admin\PrintJobController@bulkAction')->name('admin-printjob-bulk');
    
    // Capacity Stats
    Route::get('/printjobs/capacity/stats', 'Admin\PrintJobController@capacityStats')->name('admin-printjob-capacity');
});
