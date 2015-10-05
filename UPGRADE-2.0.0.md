# Upgrade from 1.1 to 2.0

- JIT Image has been removed (due to lack of support for Laravel 5) and replaced
  with Intervention. To utilise intervention in the same way as jitimage:
	- First publish the imagecache config using the Artisan command `php artisan vendor:publish --provider=Intervention\Image\ImageServiceProviderLaravel5`
	- Define the route as "images"
	- Translate your "recipies" into templates, using the expressive API
	  (http://image.intervention.io/use/basics#editing)

- The Bozboz\MediaLibrary namespace has been removed. The full mapping of old classes to new is as follows:
	- Bozboz\MediaLibrary\MediaLibraryServiceProvider => Bozboz\Admin\Providers\MediaLibraryServiceProvider
	- Bozboz\MediaLibrary\Controllers\MediaLibraryAdminController => Bozboz\Admin\Http\Controllers\MediaLibraryAdminController
	- Bozboz\MediaLibrary\Decorators\MediaAdminDecorator => Bozboz\Admin\Decorators\MediaAdminDecorator
	- Bozboz\MediaLibrary\Fields\MediaBrowser => Bozboz\Admin\Fields\MediaBrowser
	- Bozboz\MediaLibrary\Fields\MediaField => Bozboz\Admin\Fields\MediaField
	- Bozboz\MediaLibrary\Models\Media => Bozboz\Admin\Models\Media
	- Bozboz\MediaLibrary\Models\MediableTrait => Bozboz\Admin\Traits\MediableTrait
	- Bozboz\MediaLibrary\Validators\MediaValidator => Bozboz\Admin\Services\Validators\MediaValidator

- Several classes in the Admin namespace have also been moved. The full mapping is as follows:
	- Bozboz\Admin\AdminServiceProvider => Bozboz\Admin\Providers\AdminServiceProvider
	- Bozboz\Admin\Controllers\ModelAdminController => Bozboz\Admin\Http\Controllers\ModelAdminController
	- Bozboz\Admin\Controllers\PageController => Bozboz\Admin\Http\Controllers\PageController
	- Bozboz\Admin\Controllers\PageAdminController => Bozboz\Admin\Http\Controllers\PageAdminController

- SearchListingFilter now accepts either an array of attributes OR a callback as
  its second parameter, not both.

- Field, and subclasses thereof, now must take an $errors argument, which is
  passed in from the view that renders it.

- ModelAdminDecorator::getModel has been removed. Do not rely on outside classes
  having knowledge of the decorator's underlying model.

- Services\Validator::passesEdit() has been removed. Use passesUpdate method
  instead.

- Reports\Report::overrideView() has been removed. Pass custom view in as second
  argument to constructor.
