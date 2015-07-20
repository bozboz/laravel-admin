<?php

namespace Bozboz\Admin\Reports;

class CSVReport extends Report
{
	public function __construct(Downloadable $decorator)
	{
		$this->decorator = $decorator;
		$this->rows = $this->decorator->getListingModelsNoLimit();
	}

	/**
	 * Get data array in CSV format
	 *
	 * @return array
	 */
	public function getData()
	{
		$data = [];

		if ($this->hasRows()) {
			$data[] = $this->getHeadings();
			foreach($this->getRows() as $row) {
				$data[] = $row->getColumns();
			}
		}

		return $data;
	}

	protected function getColumnsFromInstance($instance)
	{
		return $this->decorator->getColumnsForCSV($instance);
	}
}
