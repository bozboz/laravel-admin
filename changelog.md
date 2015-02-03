# Version 0.5.0 (FUTURE)

-   Bug fix with DateTimeField concerning rendering of the DateTime DB value
-   Change scope of `consolidateJavascript` method on `ModelAdminController` from
    private to protected
-   Add `getSuccessResponse` to `ModelAdminController`. This centralises the call
    to `Redirect::action()` and allows it be easily changed by subtypes.
-   Utilise $fillable over $guarded in Page


# Version 0.4.2 (2015-01-26)

-   Remove "menu_icon" field from PageAdminDecorator
-   Change private methods `getRedirectOptions` and `getTemplateOptions` to protected


# Version 0.4.1 (2015-01-16)

-   Prevent issue with input being passed to new model instances twice.


# Version 0.4.0 (2015-01-13)

-	Add `status` attribute to Page model
-	Move FileField into bozboz/media-library
-	Update bootstrap version; remove custom-bootstrap partial
-	Allow a string to be passed for name (instead of array) to Field constructor
-	Allow top level menu items to be defined
-	Add DynamicSlugTrait
-	Add support for pagination in Report footer
-	Add listing filters to Report
-	Tidy-up of routes
-	Bug fix in `Validator::updateUniques()`
-	Add `is_admin` attribute to User model
-	Add `external_link` attribute to Page model
-	Remove used references in gulpfile
-	Bring in jQuery via CDN
-	Add DateTimeField
-	Improve functionality & appeparance of BelongsToManyField to use Select2 library
-	Remove username and name attributes from User model
-	Add first_name and lastname to User model
-	Tidy up a few old comments and unused properties
