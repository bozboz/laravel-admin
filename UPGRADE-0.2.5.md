# Upgrade from 0.4 to 1.0

- Update references of Bozboz\Admin\Reports\ListingFilter to Bozboz\Admin\Reports\Filters\ArrayListingFilter.
- Remove bozboz/media-library dependency in composer.json
- To define a "mediable" model:
	- Use the new Bozboz\MediaLibrary\Models\MediableTrait trait on the model (this will add a `media()` method)
	- Add a Bozboz\MediaLibrary\Fields\MediaBrowser instance to the array in the decorator's `getFields()` method
	- Define the model's media relationship in the decorator's `getSyncRelations()` method

# Upgrade from 0.2.4 to 0.3.0

- The `getFields` method on subtypes of `ModelAdminDecorator` must now take an `$instance` parameter.
- Any calls to `getId` on `Base` subtypes must be replaced with `getKey`.
