# Upgrade from 1.1 to 2.0

- JIT Image has been removed (due to lack of support for Laravel 5) and replaced
  with Intervention. To utilise intervention in the same way as jitimage:
	- First publish the imagecache config using the Artisan command `php artisan vendor:publish --provider=Intervention\Image\ImageServiceProviderLaravel5`
	- Define the route as "images"
	- Translate your "recipies" into templates, using the expressive API
	  (http://image.intervention.io/use/basics#editing)

- The Bozboz\MediaLibrary namespace has been consolidated with Bozboz\Admin.
  There have also been a number of directories inside Bozboz\Admin reorganised.
  The full mapping of old classes to new is as follows:
	- Bozboz\MediaLibrary\MediaLibraryServiceProvider => Bozboz\Admin\Providers\MediaLibraryServiceProvider
	- Bozboz\MediaLibrary\Controllers\MediaLibraryAdminController => Bozboz\Admin\Http\Controllers\MediaLibraryAdminController
	- Bozboz\MediaLibrary\Decorators\MediaAdminDecorator => Bozboz\Admin\Decorators\MediaAdminDecorator
	- Bozboz\MediaLibrary\Fields\MediaBrowser => Bozboz\Admin\Fields\MediaBrowser
	- Bozboz\MediaLibrary\Fields\MediaField => Bozboz\Admin\Fields\MediaField
	- Bozboz\MediaLibrary\Models\Media => Bozboz\Admin\Models\Media
	- Bozboz\MediaLibrary\Models\MediableTrait => Bozboz\Admin\Traits\MediableTrait
	- Bozboz\MediaLibrary\Validators\MediaValidator => Bozboz\Admin\Services\Validators\MediaValidator
	- Bozboz\Admin\AdminServiceProvider => Bozboz\Admin\Providers\AdminServiceProvider
	- Bozboz\Admin\Components\Menu => Bozboz\Admin\Base\Components\Menu
	- Bozboz\Admin\Composers\Nav => Bozboz\Admin\Base\Composers\Nav
	- Bozboz\Admin\Decorators\ModelAdminDecorator => Bozboz\Admin\Base\ModelAdminDecorator
	- Bozboz\Admin\Decorators\MediaAdminDecorator => Bozboz\Admin\Media\MediaAdminDecorator
	- Bozboz\Admin\Decorators\UserAdminDecorator => Bozboz\Admin\Users\UserAdminDecorator
	- Bozboz\Admin\Models\Base => Bozboz\Admin\Base\Model
	- Bozboz\Admin\Models\BaseInterface => Bozboz\Admin\Base\ModelInterface
	- Bozboz\Admin\Models\Media => Bozboz\Admin\Media\Media
	- Bozboz\Admin\Models\Sortable => Bozboz\Admin\Base\Sortable
	- Bozboz\Admin\Models\User => Bozboz\Admin\Users\User
	- Bozboz\Admin\Traits\DynamicSlugTrait => Bozboz\Admin\Base\DynamicSlugTrait
	- Bozboz\Admin\Traits\MediableTrait => Bozboz\Admin\Media\MediaTrait
	- Bozboz\Admin\Traits\SanitisesInputTrait => Bozboz\Admin\Base\SanitisesInputTrait

- Several classes in the Admin namespace have also been moved. The full mapping is as follows:
	- Bozboz\Admin\AdminServiceProvider => Bozboz\Admin\Providers\AdminServiceProvider
	- Bozboz\Admin\Controllers\ModelAdminController => Bozboz\Admin\Http\Controllers\ModelAdminController

- Pages have been removed from the Admin. This includes the following classes:
  Decorators\PageAdminDecorator, Controllers\PageAdminController,
  Controllers\PageController, Models\Page, Services\Validators\PageValidator and
  Subscribers\PageEventHandler. Please use an alternative implementation for
  handling pages.

- SearchListingFilter now accepts either an array of attributes OR a callback as
  its second parameter, not both.

- Field, and subclasses thereof, now must take an $errors argument, which is
  passed in from the view that renders it.

- ModelAdminDecorator::getModel has been removed. Do not rely on outside classes
  having knowledge of the decorator's underlying model.

- Services\Validator::passesEdit() and Services\Validator::$editRules have been
  removed. Use passesUpdate() and $updateRules instead.

- Reports\Report::overrideView() has been removed. Pass custom view in as second
  argument to constructor.

- Overview views must be passed the following variables: $editAction,
  $createAction and $destroyAction. These are passed in by default in the index
  method of ModelAdminController. If you are overriding the index method in your
  controller, ensure you pass `$this->getReportParams()` into the `render`
  method of the report.

- The `findInstanceOrFail` method has been removed from `ModelAdminDecorator`.
  This behaviour is now standard and has been moved into the `findInstance`
  method. Calls to `findInstance` will now throw a `ModelNotFoundException` if
  an ID cannot be found for that model.

- Typehints to `Models\BaseInterface` on `updateSyncRelations` and
  `injectSyncRelations` methods on `Decorators\MediaAdminDecorator` have been
  removed. Remove this typehint on any subclasses that override either of these
  methods.
