# Version 1.0.0 (FUTURE)

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

# Version 0.4.3 (2015-02-19)
-   Add "meta_title" field to pages table, which replaces "html_title" field


# Version 0.4.2 (2015-01-26)

-   Remove "menu_icon" field from PageAdminDecorator
-   Change private methods `getRedirectOptions` and `getTemplateOptions` to protected


# Version 0.4.1 (2015-01-16)

-   Prevent issue with input being passed to new model instances twice.


# Version 0.4.0 (2015-01-13)

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
