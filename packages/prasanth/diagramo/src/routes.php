<?php


Route::group(['prefix' => 'diagramo'], function () {

	// Route::get('index', function(){
	// 	echo 'Hello from the calculator package!';
	// });

	Route::get('login', 'prasanth\diagramo\DiagramoController@login');
	
	Route::get('index', 'prasanth\diagramo\DiagramoController@index');
	Route::get('index/{diagramId}', 'prasanth\diagramo\DiagramoController@index');
	
	
	
	Route::get('controller', 'prasanth\diagramo\DiagramoController@controller');
	Route::post('controller', 'prasanth\diagramo\DiagramoController@controller');

	Route::get('save_diagram', 'prasanth\diagramo\DiagramoController@save_diagram');
	
	Route::get('work_flow_view/{diagramId}', 'prasanth\diagramo\DiagramoController@work_flow_view');
	Route::get('png/{diagramId}', 'prasanth\diagramo\DiagramoController@png');

	



});