# Form fields

Form fields can be added to sections or table cells with `addFormField`.

```php
$section->addFormField('textinput')
    ->setName('name')
    ->setDefault('Default value');

$section->addFormField('dropdown')
    ->setName('color')
    ->setEntries(['Red', 'Blue']);
```

The supported types are `textinput`, `checkbox`, and `dropdown`. The `ODText`
writer serializes them as native ODF form controls and preserves their names,
values, and dropdown entries.
