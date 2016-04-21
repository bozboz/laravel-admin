<?php

namespace Bozboz\Admin\Reports;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Illuminate\Support\Facades\Input;

class PaginatedReport extends Report
{
	protected $perPage;

	public function __construct(ModelAdminDecorator $decorator, $perPage = null, $view = null)
	{
		parent::__construct($decorator, $view);

		$this->perPage = $perPage;
	}

	public function getHeader()
	{
		$perPageOptions = $this->decorator->getItemsPerPageOptions();
		$perPageValue = $this->rows->perPage();

		return parent::getHeader()->with(compact('perPageOptions', 'perPageValue'));
	}

	public function getFooter()
	{
		return $this->rows->appends(Input::except('page'))->render();
	}

	protected function queryRows()
	{
		return $this->decorator->getListingModelsPaginated($this->perPage);
	}
}
