# Structured document tags

PHPWord structured document tags (SDTs/content controls) are mapped to native
OpenDocument form controls by the ODText writer:

- `plainText` becomes `form:text`.
- `comboBox` becomes `form:combobox`.
- `dropDownList` becomes `form:listbox`.
- `date` becomes `form:date`.

Values and choice labels are written when the corresponding ODF control supports
them. Word SDTs have no exact ODF equivalent, so metadata and behavior such as
locking and tags do not round-trip exactly.
