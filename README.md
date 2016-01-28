# Admin package

## Installation

1. Require the package in Composer, by running `composer require bozboz/admin`
2. Add `Bozboz\Admin\AdminServiceProvider` to the providers array in
   app/config/app.php
3. Optionally, add `Bozboz\MediaLibrary\Models\Media` to the aliases array in
   app/config/app.php


## Controllers

`Controllers\ModelAdminController` is an abstract class containing standard CRUD
functionality for a model. Subclasses - as a bare minimum - must define a
constructor which passes in an instance of `Decorators\ModelAdminDecorator`.
E.g.:

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
`Decorators\ModelAdminDecorator` dependency.


## Decorators

Decorators contain information about how a model is displayed in the Admin area.
If you have a model that should be accessed in the Admin area, it needs an
associated decorator. The abstract `Decorators\ModelAdminDecorator` class
contains several abstract methods which must be defined on the subclass:

-   `getLabel` should return a suitable string representation of the model;
    typically the title of the entity
-   `getFields` should return an array of `Fields\Field` instances, used to
    create/edit the entity on the relevant screens.

There are a number of optional methods to override:

-   `getColumns` should return a key/value array of columns to display on the
    admin overview page
-   `getHeading` should return a string representation of the overall model
    (rather than that specific instance, as in `getLabel`). This method
    accepts a boolean paramter (defaulting to false) which allows the calling
    script to request the word as plural or singlular.
-   `modifyListingQuery` allows the listing query to be modified, e.g. to
    eager load relations, or apply a custom sort or conditional.

Similarly to subclasses of `Controllers\ModelAdminController`, decorators must
be passed a dependency in its constructor. This should be the entity which this
decorator represents in the admin. This class must implement the
`Models\BaseInterface` class.

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

### Representing many-to-many relationships

Belongs-to-many relations can also be represented in the admin, in the form of sync and list relations. Both these types use a Select2 field and allow you to manage a many-to-many relation between 2 instances.

A sync relation is the more common relationship, and allows a related models to be selected from a list, and on save, creates the mapping between the model being edited and the related models. To define a sync relation, add a `getSyncRelations` method to your decorator and return an array of the relations to sync, e.g.:

```
public function getSyncRelations()
{
    return ['categories'];
}
```

List relations are useful when the related model can be represented by a simple string (e.g. tags). List relations are like sync relations, except they can be composed within the edit/create screen. Using select2's "tags" option, you can add new options from within the select2 input. Any new instances created will be saved when the parent model is saved.

List relations are defined similarly to sync relations, except the field to save the new string into must be stated, e.g.:

```
public function getListRelations()
{
    return [
        'tags' => 'name'
    ];
}
```

In the above example, the Tag model must contain a "name" attribute.

Once any sync/list relations have been defined, to represent them in the edited model's form, you must add an instance of `BelongsToManyField` to the decorator's `getFields` method:

```
public function getFields($instance)
{
    return [
        ..
        new BelongsToManyField(new FooDecorator(new Foo), $instance->foos());
    ];
}
```

Additionally, for a list relation, to enable the "tags" functionality of Select2, you must set a `data-tags` attribute in the BelongsToManyField params:

```
public function getFields($instance)
{
    return [
        ..
        new BelongsToManyField(new TagDecorator(new Tag), $instance->tags(), [
            'data-tags' => true
        ]);
    ];
}
```

### Filtering the listing

Listing filters can be specified in decorators to provide options in the admin
to filter results returned on the admin overview screen. They should be defined
in a `getListingFilters` method on the respective decorator. This method should
return an array of `Reports\Filters\ListingFilter` subclasses.

The two types of listing filter out the box; `ArrayListingFilter` and
`SearchListingFilter`.

### Array listing filter
This filter accepts an array of options and renders a select box. It accepts an
optional 3rd argument which allows for custom filtering logic. If this is omitted,
a lookup based on the filter name as done on that model based on the option
selected. If a string is passed as the 3rd argument, this field will be used in
the where conditional. If the argument is a callback, this will be run to filter
the listing query.

A fourth "default" value can be specified, if the default option isn't 0/empty.

E.g.:

```
public function getListingFilters()
{
    return [
        new ArrayListingFilter('state',
            ['All', 'New', 'Completed', 'Failed']
        , 'state_id', 2)
    ];
}
```

### Search listing filter
This filter offers a fairly basic LIKE search of specified columns for each word
entered. The fields it uses to search will default to the name of the filter
(first argument), but these can optionally be specified by passing in an array
as the second argument.

The default query will perform a wildcard LIKE search for the query term on each
column define defined on construction of the filter. E.g. a search for "foo" on
columns "name" and "description" will produce the following SQL where condition:

```
WHERE `name` LIKE '%foo%' OR `description` LIKE '%foo%'
```

To override this default implementation, you can pass in a closure as the
constructor's 3rd argument, which takes a query builder object, and allows you
to query however you please.


## Models

All models within this package extend `Models\Base` (an implementation of
`Models\BaseInterface`).


## Validators

Validation rules for a model are stored in seperate validator service classes.
Validation subclasses should extend the `Services\Validators\Validator` class.
It is the responsiblity of a model wishing to be validated to define its own
Validator subtype in which to define the validation rules to validate the
model's data.

### "rules" property

These are rules that should be applied in all validation instances.

### "storeRules" property

Rules that should only be applied when storing a new model instance. Will be
merged with the "rules" property.

### "updateRules" property

Rules that should only be applied when updating an existing model instance.
Will be merged with the "rules" property.

### Unique rules

Laravel's unique rule will fail on update, as the value being validated on the on the saving model will clash with the value on the entry already in the database. To get around this, Laravel offers the following, more verbose syntax for defining unique validation rules: `'unique:<table>,<field>,<id>'`.

Table and field can be hard-coded in the rule, however, ID is a dynamic value. This can be represented in your validation service using curly-bracket placeholders, e.g. `'unique:pages,slug,{id}'`.

The placeholder syntax works for any attribute on the model, so it can be used for Laravel's extended unique functionality, e.g. `'unique:users,email_address,{id},id,account_id,{account_id}'`

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

When instantiating a `DateTimeField` object, it is possible to override the
default datetimepicker configuration by passing in an array mapped to the
'options' key.

This will be json encoded and merged in with the defaults when the DOM is
rendered. When passing in values that are defined as JS Date objects within the
datetimepicker documentation, please define these as epoch values (e.g. `time()`
or `$dateTime->format('U')`).
