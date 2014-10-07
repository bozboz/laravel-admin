# Upgrade from 0.2.4 to 0.2.5

- The `getFields` method on subtypes of `ModelAdminDecorator` must now take an `$instance` parameter.
- Any subtypes of Base must define a getLabel method implementation if they do not have a "name" attribute.
