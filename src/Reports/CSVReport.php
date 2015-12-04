<?php

namespace Bozboz\Admin\Reports;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Fluent;

class CSVReport implements BaseInterface
{
	protected $responseHeaders = [
		'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
		'Content-type'        => 'text/csv',
		'Expires'             => '0',
		'Pragma'              => 'public',
	];

	public function __construct(Downloadable $decorator)
	{
		$this->decorator = $decorator;
	}

	/**
	 * Render a CSV response
	 *
	 * @param  array  $params
	 * @return Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function render(array $params = [])
	{
		$fluentParams = new Fluent($params);

		$headers = $this->responseHeaders;

		$filename = $fluentParams->get('filename', 'cms-report.csv');

		$headers['Content-Disposition'] = 'attachment; filename=' . $filename;

		return Response::stream([$this, 'buildCsv'], 200, $headers);
	}

	/**
	 * Build a CSV file in memory from the decorator's listing models
	 *
	 * @return void
	 */
	public function buildCsv()
	{
		$fp = fopen('php://output', 'w');

		$headings = false;

		$this->decorator->getListingModelsChunked(200, function($models) use ($fp, $headings) {
			foreach($models as $instance) {
				$columns = $this->getColumnsFromInstance($instance);
				if ( ! $headings) {
					fputcsv($fp, array_keys($columns));
					$headings = true;
				}
				fputcsv($fp, $columns);
			}
		});

		fclose($fp);
	}

	/**
	 * Render columns for CSV from an instance
	 *
	 * @param  Bozboz\Admin\Base\Model  $instance
	 * @return array
	 */
	protected function getColumnsFromInstance($instance)
	{
		return $this->decorator->getColumnsForCSV($instance);
	}
}
