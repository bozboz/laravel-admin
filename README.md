# Admin package

## Installation

1. Require the package in Composer, by running `composer require bozboz/admin`
2. Add `Bozboz\Admin\AdminServiceProvider` to the providers array in
   app/config/app.php
3. Optionally, add `Bozboz\MediaLibrary\Models\Media` to the aliases array in
   app/config/app.php


## Controllers

`ModelAdminController` is an abstract class containing standard CRUD
functionality for a model. Subclasses - as a bare minimum - must define a
constructor which passes in an instance of `ModelAdminDecorator`. E.g.:

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

Laravel will smartly resolve the dependency out of the IoC container when
instantiating the controller, so the only remaining thing to do is to register
this controller as a resource in the routes file, like so:

```php
Route::resource('admin/foo', 'FooAdminController');
```

This will enable entire CRUD functionality on the model defined in the
`ModelAdminDecorator` dependency.


## Decorators

Decorators contain information about how a model is displayed in the Admin area.
If you have a model that should be accessed in the Admin area, it needs an
associated decorator. The abstract `ModelAdminDecorator` class contains several
abstract methods which must be defined on the subclass:

    - `getLabel` should return a suitable string representation of the model;
      typically the title of the entity
    - `getFields` should return an array of `Bozboz\Admin\Fields\Field`
      instances, used to create/edit the entity on the relevant screens.

There are a number of optional methods to override:

    - `getColumns` should return a key/value array of columns to display on the
      admin overview page
    - `getHeading` should return a string representation of the overall model
      (rather than that specific instance, as in `getLabel`). This method
      accepts a boolean paramter (defaulting to false) which allows the calling
      script to request the word as plural or singlular.
    - `modifyListingQuery` allows the listing query to be modified, e.g. to
      eager load relations, or apply a custom sort or conditional.

Similarly to subclasses of `ModelAdminController`, decorators must be passed a
dependency in its constructor. This should be the entity which this decorator
represents in the admin. This class must implement the
`Bozboz\Admin\Models\BaseInterface` class.

E.g.:

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

### Saving Many-To-Many relationships

As the mapping between the two models is stored inside a join table, there's a
small amount of additional to get this data synced in the admin. A decorator
must define a `getSyncRelations` method, which returns an array of relations
which should be synced. E.g.

```
public function getSyncRelations()
{
    return ['categories'];
}
```

Additionally, for this relationship to be interacted with in the admin, an
instance of the `BelongsToManyField` should be returned by the decorator's
`getFields` method:

```
public function getFields($instance)
{
    return [
        ..
        new BelongsToManyField(new FooDecorator(new Foo)), $instance->foos());
    ];
}
```

### Filtering the listing

TODO


## Models

All models within this package extend `Bozboz\Admin\Models\Base`
(an implementation of `Bozboz\Admin\Models\BaseInterface`).


## Validators

Validation rules for a model are stored in seperate validator service classes.
Validation subclasses should extend the
`Bozboz\Admin\Services\Validators\Validator` class. It is the responsiblity of a
model wishing to be validated to define its own Validator subtype in which to
define the validation rules to validate the model's data.

### "rules" property

These are rules that should be applied in all validation instances.

### "storeRules" property

Rules that should only be applied when storing a new model instance. Will be
merged with the "rules" property.

### "updateRules" property

Rules that should only be applied when updating an existing model instance.
Will be merged with the "rules" property.


## Traits

### DynamicSlugTrait
Automatically generates a value for a model's slug. `use` the trait on the
respective model and define a `getSlugSourceField` method which returns the name
of the property from which to derive the slug.


## Fields

### DateTimeField

Uses the datetimepicker addon which extends the functionality of the jQuery UI
DatePicker. Documentation can be found
[here](http://trentrichardson.com/examples/timepicker/).

When instantiating a DateTimeField object, it is possible to override the
default datetimepicker configuration by passing in an array mapped to the
'options' key.

This will be json encoded and merged in with the defaults when the DOM is
rendered. When passing in values that are defined as JS Date objects within the
datetimepicker documentation, please define these as epoch values (e.g. `time()`
or `$dateTime->format('U')`).
