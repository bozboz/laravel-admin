# Admin package

## Controllers

Most packages will contain a FooController and a FooAdminController. The FooAdminController should extend the abstract ModelAdminController class and should - as a bare minimum - define a constructor specifying that particular controller's dependency - a ModelAdminDecorator subclass.

```php
use Bozboz\Admin\Decorators\FooAdminDecorator;

class FooAdminController extends ModelAdminController
{
    public function __construct(FooAdminDecorator $foo)
    {
        parent::__construct($foo);
    }
}
```

Laravel will smartly resolve the dependency out of the IoC container when instantiating the controller, so the only remaining thing to do is to register this controller as a resource in the routes file, like so:

```php
Route::resource('admin/foo', 'FooAdminController');
```

This will enable entire CRUD functionality on the model defined in the ModelAdminDecorator dependency. All this functionality is inside the ModelAdminController. The FooController class would be your standard "front-end" controller, which should be referenced in your routes.php file, either explicitly per route, restfully, or as a resource.

## Models

All models within this package need to extend Bozboz\Admin\Models\Base unless you have a good reason to deviate.

## Validators

Instead of storing validation rules on the actual models we have decided to create a Validator service. Base functionality resides in Bozboz\Admin\Services\Validators\Validator. It is the responsiblity of a model wishing to be validated to define its own Validator subtype (e.g. User model utilises the UserValidator class) in which it defines the validation rules to be used. Furthermore the Base type has an abstract "getValidator" method in which the model returns an instance of its Validator subtype.

## Decorators

Decorators contain information about how a model is displayed in the Admin area. If you have a model that should be accessed in the Admin area, it needs an associated decorator and controller subclass. The abstract ModelAdminDecorator class currently contains a couple of abstract methods - getColumns and getLabel - as well as a few defaults getModel, getListingModels and getFields. Similarly to ModelAdminController subclasses, subclasses of ModelAdminDecorator must, as a minimum, define their own constructor, type-hinting an Eloquent model as its argument:

```php
use Bozboz\Admin\Models\Page;

class PageAdminDecorator extends ModelAdminDecorator
{
    public function __construct(Page $page)
    {
        parent::__construct($page);
    }
}
```

Controllers will generally do all its communicating of models through a decorator - they won't deal directly with a model instance.
