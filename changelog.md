# Bozboz Admin Package Changelog

## Version 2.3.2 (2017-02-03)
- Don't show first option in dropdown on split button actions
- Update tag date in changelog
- Show commit hash on versions table
- Allow versions page to show all package versions

## Version 2.3.1 (2017-01-19)
- Allow dropdown actions to present as split buttons
- Make param varchar on permissions table
- Add route for displaying bozboz package versions

## Verison 2.3.0 (2017-01-04)
- Lock down user/role editing so sneaky clients can't assign themselves as Bozboz admin

## Version 2.2.1 (Future)
- Add role filter to users report
- Sort users report by email

## Version 2.2.0 (2016-12-16)

### Added
-   Allow `URLField` to auto generate a slug from another field.
-   Add bulk update functionality
-   Add switch user button to users listing
-   Add `asset_version` helper
-   Add user roles
-   Add DateFilter

### Changed
-   Rework model saving so that validation errors can be thrown as exceptions deeper in the save method
-   Add cache breaker to default styles and scripts
-   Only activate admin auth middleware on admin urls
-   Allow a null user to be passed to UserRule
-   Allow user role permissions to be managed form the role edit screen

## Version 2.1.6 (2016-10-19)
-   Set DB to strict mode and check errors when sorting to avoid setting _lft and _rgt to null


## Verison 2.1.5 (2016--10-04)
-	Fix relation syncing when nothing is selected


## Version 2.1.4 (2016-09-15)
-   Do sorting in transaction in case anything disasterous happens


## Version 2.1.3 (2016-09-06)
-   Add `getSortableSyncRelations` for defining sortable belongs to mnay relations
-   Increase scope of `scopeModifySortingQuery` method on SortableTrait


## Version 2.1.2 (2016-08-11)
-   Fix tree select field ids


## Version 2.1.1 (2016-08-04)
-   Use paginated report for media
-   Fix media uploader
-   Set the max filesize in media uploader
-   Make media caption nullable
-   Don't make caption a required field in media uploader
-   Fix console binding of UserInterface for `php artisan routes:list`
-   Fix nested sortable


## Version 2.1.0 (2016-07-13)

### Added
-   Add help text functionality to admin fields
-   Add `getRowActions` and `getReportActions` methods to
    `Http\Controllers\ModelAdminController`
-   `Base\ModelAdminDecorator::getHeadingForInstance` method
-   `Base\ModelAdminDecorator::getListingModelsPaginated` method
-   `NestedSortableTrait` to sort nested set models
-   `SortableTrait` to sort non-nested set models
-   `hide_is_value_filled` and `hide_if_value_empty` attributes added to fields
-   `Reports\PaginatedReport` class
-   `Reports\Filters\RelationFilter` class
-   `failsStore` and `failsUpdate` methods added to `Services\Validators\Validator`

### Changed
-   Add web middleware to routes inline with changes between L5.1 and L5.2
-   Improved appearance of media browser field
-   Changed "Save and Continue" button label to "Save"
-   `Fields\BelongsToField` now uses select2 by default
-   The Illuminate\Html package has been replaced with the more actively
    developed and equivilant Collective\Html package
-   Query values from the request are now injected into the report and stored
    statically on the `Reports\ListingFilter` class
-   Updates/stores in `Http\Controllers\ModelAdminController` are now performed
    in a database transaction
-   Allow `TreeSelectField` to select none
-   Update Guzzle dependency to latest

### Fixed
-   Allow a parameter to be passed into the `Menu::gate()` method
-   `Fields\BelongsToManyField` can now have a custom name passed in as an attribute
-   `Fields\DateField` now has a normalised name to prevent JS errors with hyphens

### Deprecated
-   `Base\ModelAdminDecorator::getListingModels` method
-   `Base\Sortable` class
-   `Http\Controllers\ModelAdminController::getReportParams` method

## Version 2.0.9 (2016-08-16)
-   Add `getSortableSyncRelations` for defining sortable belongs to mnay relations


## Version 2.0.8 (2016-08-02)
-   Fix to MediaBrowser field


## Version 2.0.7 (2016-06-20)
-   Fix filtering issue on permissions screen


## Version 2.0.6 (2016-04-26)
-   Fix GrantAdminUserPermissions migration
-   Fix listing filter filtering


## Version 2.0.5 (2016-04-06)
-   Replace aliased facade classes with full namespaces, for 5.2 compatiblity (Input alias removed)


## Version 2.0.4 (2016-03-31)
-   Tie to stable version of bozboz/permissions (v1.0.0)


## Version 2.0.3 (2016-03-22)
-   Remove unused Authorizable trait from User model.


## Version 2.0.2 (2016-03-02)
-   Fix password reset


## Version 2.0.1 (2016-03-01)
-   Fix email validation rule when updating a user


## Version 2.0.0 (2016-02-10)

### Added
-   Support for Laravel 5

### Changed
-   Remove dependancy on Base model and replace it with BaseInterface
-   Consolidate migrations
-   ModelAdminDecorator::getFields method no longer fires "admin.fields.built" event
-   ModelAdminDecorator::findInstance now throws a ModelNotFoundException if no matching ID is found.

### Fixed
-   Prevent media with many relation from attempting to sync null relations
-   Using the SanitisesInputTrait without a $nullable property defined will no longer error
-   Nav selected state will now work with query string URLs
-   Fixed issue when dealing with old/session data when using CheckboxField
-   Fixed name on dashboard welcome message

### Removed
-   Support for Laravel 4
-   Remove migrations directory from Composer autoload
-   Remove Reports\Row::getModel method
-   Remove Decorators\ModelAdminDecorator::getModel method
-   Remove Decorators\ModelAdminDecorator::findInstanceOrFail method
-   Remove Services\Validator::passesEdit() method
-   Remove Reports\Report::overrideView() method
-   Remove Pages
-   Remove Meta namespace

## Version 1.3.0 (2016-06-20)

### Added
-   Add null option to TreeSelectField
-   Add the ability to add media via a URL to media library

## Version 1.2.6 (2016-08-24)
-   Fix CSV report headings showing every 200 rows bug

## Version 1.2.5 (2016-06-20)
-   Fix filtering issue on permissions screen


## Version 1.2.4 (2016-03-26)
-   Fix listing filter filtering


## Version 1.2.3 (2016-03-26)
-   Fix GrantAdminUserPermissions migration


## Version 1.2.2 (2016-03-04)
-   Fix CheckboxField


## Version 1.2.1 (2016-02-03)
-   Include wildcard in list of actions when editing a permission


## Version 1.2.0 (2016-01-21)

### Added
-   Add tags to Media Library
-   Add MultiOptionListingFilter
-   Added messages functionality to Validator service
-   View can now be overridden in constructor of Report class
-   Add items per page select to listing filters
-   Add nicer error page when you edit a resource that doesn't exist
-   Add basic permissions (bozboz/permissions package) for CRUD functionality
-   Ability to edit currently authenticated user's password
-   Add a default `getValidator` method on `Models\Base` which returns an EmptyValidator object.
-   `Reports\Row::check(callable)` method added to assert if an action (callable) can be performed on the row
-   `Components\Menu::gate($rule)` method added to check if a specific view permission is allowed for current user
-   Added "list" relations, to go alongside sync relations
-   Added Overridable methods to ModelAdminController: `canView`, `canCreate`, `canEdit`, `canDelete`
-   Default functionality of above methods can be extended by overriding: `viewPermissions`, `createPermissions`, `editPermissions` and `deletePermissions` methods
-   Added option to interface to change amount of results displayed per listing
-   Nested item <li> elements in partials/nested-item view now contain a data-id attribute
-   Append id to uploaded media filename to keep them unique

### Changed
-   $editRules and passesEdit() method on Validator changed to $updateRules and passesUpdate()
-   Unlink media file after deleting the DB row
-   To log into the admin, a user must now have an "admin_login" rule, as opposed to the is_admin boolean flag.
-   Create, update and destroy actions are now passed in from the controller to the overview view
-   If an instance is not found when editing, updating or destroying an instance, a ModelNotFoundException will now be thrown
-   Listings of sortable models will no longer be paginated
-   Password can now be changed when editing a user
-   Fields\URLField can accept a route name as a string as its second argument
-   Subclasses of Models\Base no longer need to define a getValidator method
-   Upload functionality within `MediaLibraryAdminController` has been moved into separate `Uploader` class
-   The drop area for uploads on the HTMLEditorField has been restricted
-   Basic `$canEdit`/`$canDelete` boolean variables have been replaced with closures, accepting a `Reporting\Row` instance

### Deprecated
-   Deprecate Services\Validator::passesEdit() method
-   Deprecate Reports\Report::overrideView() method

### Fixed
-   CSVReport can now handle large data sets without running out of memory


## Version 1.1.7 (2015-12-22)
-   Fix uploading of inline media caused by bug in 1.1.6


## Version 1.1.6 (2015-12-21)
-   Fix issue with duplicate captions when uploading multiple files using media uploader


## Version 1.1.5 (2015-12-16)
-   Fix rendering of nested structures in NestedReport when working with kalnoy/nestedset models
-   Fix bug where image thumbnails aren't displaying in the media browser


## Version 1.1.4 (2015-10-25)
-   Fix handling of nulled `parent_id` for Baum models


## Version 1.1.3 (2015-10-22)
-   Fix media macro for case where $subject is null


## Version 1.1.2 (2015-10-13)
-   Add "external_link" attribute to Page's $fillable


## Version 1.1.1 (2015-09-17)
-   Fix issue with HTML::media macro when passing in Media instances
-   Add `Media::getFilenameOrFallback` method


## Version 1.1.0 (2015-08-26)

### Added
-   Add listingPerPageLimit method to ModelAdminDecorator
-   Add defaultAttributes method to Field
-   Add URLField
-   Add "canDelete" param to overview screen to hide the delete button
-   Add "Save and continue" button on form screen
-   Fire model created and deleted flash messagees in addition to updated
-   Add concept of "private" media stored outside of the public dir.
-   Add report, report_header and report_footer sections in overview view
-   Add CSVReport

### Changed
-   Rename "fullModelName" variable to "heading"
-   Allow null fields in ModelAdminDecorator::getFields
-   Generate a better label in BelongsToManyField
-   Improve getHeading method of ModelAdminDecorator
-   Allow warn btn js to work on ajaxed elements
-   Remove blank sortable column on overview screen if model is not sortable
-   Give ModelAdminDecorator::getColumns a sensible default, making it no longer abstract
-   Redirect back after delete, rather than overview

### Deprecated
-   Deprecate Reports\Row::getModel() method
-   Deprecate ModelAdminDecorator::getModel() method
-   Deprecate "admin.fields.built" event in ModelAdminDecorator

### Fixed
-   Fix non-standard behaviour of getColumns by always passing it an instance,
    rather than a factory
-   Correct case of "New {modelName}" in admin
-   Correctly display fallback image for non-image Media
-   Fix password reset
-   Prevent media with many relation from attempting to sync null relations


# Version 1.0.13 (2015-12-21)
-   Fix issue with duplicate captions when uploading multiple files using media uploader


## Version 1.0.12 (2015-12-16)
-   Fix rendering of nested structures in NestedReport when working with kalnoy/nestedset models


## Version 1.0.11 (2015-11-24)
-   Fix bug where image thumbnails aren't displaying in the media browser


## Version 1.0.10 (2015-10-25)
-   Fix handling of nulled `parent_id` for Baum models


## Version 1.0.9 (2015-10-22)
-   Fix media macro for case where $subject is null


## Version 1.0.8 (2015-10-13)
-   Add "external_link" attribute to Page's $fillable


## Version 1.0.7 (2015-09-17)
-   Fix issue with HTML::media macro when passing in Media instances
-   Add `Media::getFilenameOrFallback` method


## Version 1.0.6 (2015-09-10)
-   Insert full size image in to WYSIWYG on upload rather than thumb


## Version 1.0.5 (2015-06-15)
-   Access correct fallback image


## Version 1.0.4 (2015-06-15)
-   Fix for placeholder when sorting in-line media


## Version 1.0.3 (2015-06-12)
-   Fix media browser, so inline-uploaded files are selected immediately


## Version 1.0.2 (2015-06-09)
-   Fix media type directory when uploading PDFs


## Version 1.0.1 (2015-05-08)
-   Use strict type comparison for listing filter values


## Version 1.0.0 (2015-05-06)

-   Null data sanitisation added, by defining a $nullable property on a model
-   Fixed error when sorting non-nestable models
-   Bug fix with DateTimeField concerning rendering of the DateTime DB value
-   Increase scope of `consolidateJavascript` method on ModelAdminController.
-   Add `getSuccessResponse`, `getStoreResponse` and `getUpdateResponse` methods to `ModelAdminController`.
-   Utilise $fillable over $guarded in Page
-   Introduced `getListingBuilder` to ModelAdminDecorator to easily override query builder.
-   Listing filters will now work out of the box.
-   Abstracted some display logic from ListingFilter to ArrayListingFilter.
-   Moved Bozboz\Admin\Reports\ListingFilter to abstract Bozboz\Admin\Reports\Filters\ListingFilter.
-   Added SearchListingFilter.
-   Output actual name of User on index screen.
-   Meta\Provider::forPage method now accepts a more flexible MetaInterface object, as opposed to a concrete Page model
-   Styling amends to bootstrap classes
-   Moved bozboz/media-library package inside this repository
-   Media can now be uploaded in bulk
-   Media overview is now paginated, searchable and in descending date order
-   Media can be uploaded on edit screens containing the Media Browser field
-   Failed validation no longer removes media browser selections
-   Summernote WYSIWYG editor will now upload to the Media Library
-   Type is now automatically resolved from the uploaded file's mime type
-   Output flash message upon successful model update
-   If model is sortable, automatically order by sorting field in Admin
-   Add menu active states
-   Added BelongsToField
-   Remove jquery-sortable plugin, replace with nestedSortable
-   Rename pages.html_title to pages.meta_title
-   Separate out `create` method on `ModelAdminController` so it can be more easily overridden
-   ModelAdminDecorator::buildFields now take mandatory instance argument
-   Move the "Back to Listing" element from admin.partials.save into admin.partials.listing
-   ModelAdminController::edit 'listingAction' value is now derived from a method call
-   overview.blade.php now encodes model id using data-id attribute
-   Allow for multiple sortable "things" on a single pages
-   Add FieldGroup field type for making groups of fields


## Version 0.4.3 (2015-02-19)
-   Add "meta_title" field to pages table, which replaces "html_title" field


## Version 0.4.2 (2015-01-26)

-   Remove "menu_icon" field from PageAdminDecorator
-   Change private methods `getRedirectOptions` and `getTemplateOptions` to protected


## Version 0.4.1 (2015-01-16)

-   Prevent issue with input being passed to new model instances twice.


## Version 0.4.0 (2015-01-13)

-   Add `status` attribute to Page model
-   Move FileField into bozboz/media-library
-   Update bootstrap version; remove custom-bootstrap partial
-   Allow a string to be passed for name (instead of array) to Field constructor
-   Allow top level menu items to be defined
-   Add DynamicSlugTrait
-   Add support for pagination in Report footer
-   Add listing filters to Report
-   Tidy-up of routes
-   Bug fix in `Validator::updateUniques()`
-   Add `is_admin` attribute to User model
-   Add `external_link` attribute to Page model
-   Remove used references in gulpfile
-   Bring in jQuery via CDN
-   Add DateTimeField
-   Improve functionality & appeparance of BelongsToManyField to use Select2 library
-   Remove username and name attributes from User model
-   Add first_name and lastname to User model
-   Tidy up a few old comments and unused properties
