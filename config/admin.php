<?php

return [
    'nav_logo' => 'CMS',
	'listing_items_per_page' => 25,
    'collapsible_rows' => env('COLLAPSIBLE_CMS_ROWS', false),
    'widget_api_url' => env('ADMIN_WIDGET_API_URL', 'https://widgets.bozboz.dev/api/v1/widgets'),
];
