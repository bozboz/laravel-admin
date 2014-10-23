# Upgrade from 0.2.4 to 0.3.0

- The `getFields` method on subtypes of `ModelAdminDecorator` must now take an `$instance` parameter.
- Any calls to `getId` on `Base` subtypes must be replaced with `getKey`.
