# Upgrade from 0.4 to 1.0

- Update references of Bozboz\Admin\Reports\ListingFilter to Bozboz\Admin\Reports\Filters\ArrayListingFilter.
- Remove bozboz/media-library dependency in composer.json
- To define a "mediable" model:
	- Use the new Bozboz\MediaLibrary\Models\MediableTrait trait on the model (this will add a `media()` method)
	- Add a Bozboz\MediaLibrary\Fields\MediaBrowser instance to the array in the decorator's `getFields()` method
	- Define the model's media relationship in the decorator's `getSyncRelations()` method
- References to a save method in Bozboz\Admin\Traits\DynamicSlugTrait should be removed
- If you have overriden the "admin.form" view, you'll need to @include "admin.partials.listing" to get the "Back to Listing" element
- If you have overriden the "admin.overview" view, you'll need to encode each instance id with `data-id="{{ $row->getId() }}"` as opposed to `id="sortable-item_{{ $row->getId() }}"`
