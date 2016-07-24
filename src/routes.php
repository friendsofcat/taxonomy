<?php

$prefix = config('taxonomy.config.route_prefix');

Route::group(array('prefix' => $prefix), function() use ($prefix) {

  Route::resource('taxonomy', 'Trexology\Taxonomy\Controllers\TaxonomyController');

  Route::post('taxonomy/order-terms/{id}', array(
    'as' => $prefix .'.taxonomy.order.terms',
    'uses' => 'Trexology\Taxonomy\Controllers\TaxonomyController@postOrderTerms',
  ));

  Route::resource('terms', 'Trexology\Taxonomy\Controllers\TermsController');

});
