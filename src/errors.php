<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

App::error(function(ModelNotFoundException $e) {
	return View::make('admin::error')->with([
		'message' => sprintf(
			'%s not found.',
			class_basename($e->getModel())
		)
	]);
});