<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

App::error(function(ModelNotFoundException $e)
{
	return View::make('admin::error')->with([
		'title' => '404 Not Found',
		'message' => sprintf(
			'%s instance was not found.',
			class_basename($e->getModel())
		)
	]);
});

App::error(function(HttpException $e)
{
	switch($e->getStatusCode()) {
		case 403:
			return View::make('admin::error')->with([
				'title' => '403 Forbidden',
				'message' => 'You are not authorised to view this page'
			]);
	}
});

App::missing(function($e)
{
	return View::make('admin::error')->with([
		'title' => '404 Not found',
		'message' => 'This page could not be found in the admin.'
	]);
});
