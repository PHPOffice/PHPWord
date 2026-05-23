# Elements

Elements are objects representing the various parts of a document. Elements can be [containers](containers.md) for other elements.

## Basic Example

``` php
<?php

// Containers are added by creating new variables equal to the container.
$section = $phpWord->addSection();
$table = $section->addTable();

// Elements are added to containers without creating variables.
$section->addTitle(0, 'Hello, World!');
$section->addText('How are you doing?');

```

## PHPWord Elements
Below are the matrix of element availability in each container. The column shows the containers while the rows lists the elements.

| | [Section](..containers.md) | [Header](..containers.md) | [Footer](..containers.md) | [Cell](table.md)<br>[Text Box](textbox.md) | [Text Run](text.md)<br>[List Item Run](list.md) | [Endnote](note.md)<br>[Footnote](note.md) |
|---|---|---|---|---|---|---|
| Bookmark | :question: | :question: | :question: |  :question: | :question: | :question: |
| [Chart](chart.md) | :white_check_mark: | :question: | :question: | :white_check_mark: | :question: | :red_circle: | :question: |
| [CheckBox](checkbox.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: |
| [Endnote](note.md) | :white_check_mark: | :red_circle: | :red_circle: | :ballot_box_with_check: | :ballot_box_with_check: | :red_circle: |
| [Field](field.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Footer](..containers.md) | :white_check_mark: | :red_circle: | :red_circle: | :red_circle: | :red_circle: | :red_circle: |
| FormField | :question: | :question: | :question: | :question: | :question: | :question: |
| [Footnote](note.md) | :white_check_mark: | :red_circle: | :red_circle: | :ballot_box_with_check: | :ballot_box_with_check: | :red_circle: |
| [Formula](formula.md) | :question: | :question: | :question: | :question: | :question: | :question: |
| [Header](..containers.md) | :white_check_mark: | :red_circle: | :red_circle: | :red_circle: | :red_circle: | :red_circle: |
| [Image](image.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Line](line.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Link](link.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [List Item](list.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: | :red_circle: |
| [List Item Run](list.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: | :red_circle: |
| [OLEObject](oleobject.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Page Break](pagebreak.md) | :white_check_mark: | :red_circle: | :red_circle: | :red_circle: | :red_circle: | :red_circle: |
| [Preserve Text](preservetext.md) | :question: | :white_check_mark: | :white_check_mark: | :heavy_check_mark: | :red_circle: | :red_circle: |
| [Ruby](ruby.md) | :question: | :question: | :question: | :question: | :question: | :question: |
| SDT | :question: | :question: | :question: | :question: | :question: | :question: |
| Shape | :question: | :question: | :question: | :question: | :question: | :question: |
| [TOC](toc.md) | :white_check_mark: | :red_circle: | :red_circle: | :red_circle: | :red_circle: | :red_circle: |
| [Table](table.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: | :red_circle: |
| [Text](text.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Text Break](textbreak.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Text Box](textbox.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: | :red_circle: |
| [Text Run](text.md) | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :red_circle: | :white_check_mark: |
| [Title](title.md) | :white_check_mark: | :question: | :question: | :question: | :question: | :question: |
| [Watermark](watermark.md) | :red_circle: | :white_check_mark: | :red_circle: | :red_circle: | :red_circle: | :red_circle: |

Legend:

- :white_check_mark: : Available.
- :heavy_check_mark: : Available only when inside header/footer.
- :ballot_box_with_check: : Available only when inside section.
- :red_circle: : Not available.
- :question: : Should be available.

# Unique Elements
See each of the following elements for details on how to use them.

- [Row](table.md) and [Cell](table.md) can only be added to a [Table](table.md).
- [Comment](comment.md)
- [Track Change](trackchanges.md)
