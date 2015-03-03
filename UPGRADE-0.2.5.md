# Upgrade from 0.4 to 0.5

- Rename Bozboz\Admin\Reports\ListingFilter to Bozboz\Admin\Reports\Filters\ArrayListingFilter.

# Upgrade from 0.2.4 to 0.3.0

- The `getFields` method on subtypes of `ModelAdminDecorator` must now take an `$instance` parameter.
- Any calls to `getId` on `Base` subtypes must be replaced with `getKey`.
