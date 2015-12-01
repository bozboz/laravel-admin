# Admin package

## Installation

1. Add package to `composer.json` requirements

```js
"require": {
	"bozboz/admin": "~0.1.0"
}
```

2. Optionally, update `phpunit.xml` to hit package tests

```xml
<directory>./vendor/bozboz/admin/tests</directory>
```

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

### "rules" property

These are rules that should be applied in all validation instances.

### "storeRules" property

Rules that should only be applied when creating a new model instance. Will be merged with the "rules" property.

### "editRules" property

Rules that should only be applied when updating an existing model instance. Will be merged with the "rules" property.

### Unique rules

Laravel's unique rule will fail on update, as the value being validated on the on the saving model will clash with the value on the entry already in the database. To get around this, Laravel offers the following, more verbose syntax for defining unique validation rules: `'unique:<table>,<field>,<id>'`.

Table and field can be hard-coded in the rule, however, ID is a dynamic value. This can be represented in your validation service using curly-bracket placeholders, e.g. `'unique:pages,slug,{id}'`.

The placeholder syntax works for any attribute on the model, so it can be used for Laravel's extended unique functionality, e.g. `'unique:users,email_address,{id},id,account_id,{account_id}'`

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

## Saving Many-To-Many relationships

As the mapping between the two models is stored inside a join table, there's a bit more leg work to get them working within the admin module.

Define a `getSyncRelations` method on the respective ModelAdminDecorator subtype (e.g. if you're setting up a relationship between BlogPost and BlogCategory and wish to handle the relationship when creating/editing existing BlogPosts, do the following inside the BlogPostDecorator class):

```
public function getSyncRelations()
{
    return ['categories'];
}
```

Add BelongsToManyField instance to the ModelAdminDecorator subtype's `getFields` implementation:

```
public function getFields()
{
    return [
        new BelongsToManyField($this, $instance->foos(), ['label' => 'Some label'],
            function(\Illuminate\Database\Eloquent\Builder $builder)
            {
                return $builder->where('status', '=', 1);
            }
        )
    ];
}
```

# Traits

- DynamicSlugTrait: Automatically generates a value for a model's slug. `use` the trait on the respective model and define a `getSlugSourceField` implementation which returns the name of the property from which to derive the slug. 

# Editing Admin Theme

The admin theme uses *gulp* and *bower* for download and compiling assets for the theme. To be able to compile the assets you need to run:

```
$ npm install
$ bower install
```

From there, a simple `gulp` will compile all css and js - however, during development a `gulp watch` can be run

# Field subtypes

## DateTimeField

Uses the datetimepicker addon which extends the functionality of the jQuery UI DatePicker. Documentation can be found [here](http://trentrichardson.com/examples/timepicker/).

When instantiating a DateTimeField object, it is possible to override the default datetimepicker configuration by passing in an array mapped to the 'options' key.
This will be json encoded and merged in with the defaults when the DOM is rendered. When passing in values that are defined as JS Date objects within the datetimepicker
documentation, please define these as epoch values (e.g. `time()` or `$dateTime->format('U')`). 
