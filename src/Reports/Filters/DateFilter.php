<?php

namespace Bozboz\Admin\Reports\Filters;

use Request, Form;
use Illuminate\Database\Eloquent\Builder;
use Bozboz\Admin\Reports\Filters\ListingFilter;

class DateFilter extends ListingFilter
{
    protected $defaultOptions = [
        'range' => true,
    ];
    protected $options = [];

    public function __construct($name, $callback = null, $options = [])
    {
        parent::__construct($name, $callback);
        $this->options = array_merge($this->defaultOptions, $options);
    }

    protected function defaultFilter($field)
    {
        return function($builder, $values) use ($field)
        {
            $fromDate = $values['from_date'];
            if ($fromDate) {
                $fromDate .= ' 00:00:00';
            }

            $toDate = $values['to_date'];
            if ($toDate) {
                $toDate .= ' 23:59:59';
            }

            if ($fromDate && $toDate) {
                $builder->whereBetween($this->name, [$fromDate, $toDate]);
            } elseif ($fromDate && ! $toDate) {
                $builder->where($this->name, '>=', $fromDate);
            } elseif (! $fromDate && $toDate) {
                $builder->where($this->name, '<=', $toDate);
            }
        };
    }

    protected function fromFieldName()
    {
        return "{$this->name}_from_date";
    }

    protected function toFieldName()
    {
        return "{$this->name}_to_date";
    }

    public function filter(Builder $builder)
    {
        $this->call($builder, [
            'from_date' =>Request::get($this->fromFieldName()),
            'to_date' =>Request::get($this->toFieldName()),
        ]);
    }

    public function __toString()
    {
        $fromFieldName = $this->fromFieldName();
        $toFieldName = $this->toFieldName();
        $fromDate = Request::get($fromFieldName);
        $toDate = Request::get($toFieldName);

        $label = Form::label($this->name);

        $html = <<<HTML
            {$label}
            <div class="input-group input-group-sm">
                <input type="text"
                    name="{$fromFieldName}"
                    class="js-date-range-filter js-{$fromFieldName}-filter form-control"
                    data-date="{$fromDate}"
                    data-onchange-affect-input=".js-{$toFieldName}-filter"
                    data-onchange-affect-boundary="minDate"
                >
HTML;
            if ($this->options['range']) {
                $html .= <<<HTML
                <span class="input-group-addon">
                    <label for="to_date" class="sr-only">To</label>
                    To
                </span>
                <input type="text"
                    name="{$toFieldName}"
                    class="js-date-range-filter js-{$toFieldName}-filter form-control"
                    data-date="{$toDate}"
                    data-onchange-affect-input=".js-{$fromFieldName}-filter"
                    data-onchange-affect-boundary="maxDate"
                >
                <div class="input-group-btn">
                    <button type="submit" value="Filter" class="btn btn-sm btn-default">Filter</button>
                </div>
HTML;
            }
            $html .= <<<HTML
            </div>

            <script>
                $(function() {
                    var prettyDateFormat = 'dd/mm/yy';
                    var isoDateFormat = 'yy-mm-dd';

                    $('[name={$fromFieldName}],[name={$toFieldName}]').each(function() {
                        var altInput = $(this);
                        var input = $('<input>', {
                            type: 'text',
                            class: altInput.attr('class'),
                            // name: altInput.prop('name') + '_alt',
                        });

                        altInput.attr('class', '');

                        input.insertAfter(altInput);

                        altInput.prop('type', 'hidden');

                        input.datepicker({
                            dateFormat: prettyDateFormat,
                            setDate: altInput.data('date'),
                            altField: altInput,
                            altFormat: isoDateFormat,
                            numberOfMonths: 3,
                            onClose: function( selectedDate ) {
                                $(altInput.data('onchange-affect-input')).datepicker( "option", altInput.data('onchange-affect-boundary'), selectedDate );
                                if (selectedDate === '') {
                                    altInput.val('');
                                }
                                if ( ! $(altInput.data('onchange-affect-input')).val()) {
                                    $(altInput.data('onchange-affect-input')).datepicker( "setDate", selectedDate );
                                }
HTML;
            if ( ! $this->options['range']) {
                $html .= <<<HTML
                                altInput.closest('form').submit();
HTML;
            }
            $html .= <<<HTML
                            }
                        });

                        if (altInput.data('date')) {
                            input.datepicker('setDate', new Date(altInput.data('date')));
                        }
                    });
                });
            </script>
HTML;
        return $html;
    }
}
