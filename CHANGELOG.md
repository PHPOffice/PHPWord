# Changelog

This is the changelog between releases of PHPWord. Releases are listed in reverse chronological order with the latest version listed on top, while additions/changes in each release are listed in chronological order. Changes in each release are divided into three parts: added or change features, bugfixes, and miscellaneous improvements. Each line contains short information about the change made, the person who made it, and the related issue number(s) in GitHub.

## 0.9.1 - 27 Mar 2014

This is a bugfix release for PSR-4 compatibility.

- Fixed PSR-4 composer autoloader - @AntonTyutin

## 0.9.0 - 26 Mar 2014

This release marked the transformation to namespaces (PHP 5.3+).

### Features

- Image: Ability to use remote or GD images using `addImage()` on sections, headers, footer, cells, and textruns - @ivanlanin
- Header: Ability to use remote or GD images using `addWatermark()` - @ivanlanin

### Bugfixes

- Preserve text doesn't render correctly when the text is not the first word, e.g. 'Page {PAGE}' - @ivanlanin

### Miscellaneous

- Move documentation to [Read The Docs](http://phpword.readthedocs.org/en/develop/) - @Progi1984 @ivanlanin GH-82
- Reorganize and redesign samples folder - @ivanlanin GH-137
- Use `PhpOffice\PhpWord` namespace for PSR compliance - @RomanSyroeshko @gabrielbull GH-159 GH-58
- Restructure folders and change folder name `Classes` to `src` and `Tests` to `test` for PSR compliance - @RomanSyroeshko @gabrielbull
- Compliance to phpDocumentor - @ivanlanin
- Merge Style\TableFull into Style\Table. Style\TableFull is deprecated - @ivanlanin GH-160
- Merge Section\MemoryImage into Section\Image. Section\Image is deprecated - @ivanlanin GH-160

## 0.8.1 - 17 Mar 2014

This is a bugfix release for image detection functionality.

- Added fallback for computers that do not have exif_imagetype - @bskrtich, @gabrielbull

## 0.8.0 - 15 Mar 2014

This release merged a lot of improvements from the community. Unit tests introduced in this release and has reached 90% code coverage.

### Features

- Template: Permit to save a template generated as a file (PHPWord_Template::saveAs()) - @RomanSyroeshko GH-56 GH-57
- Word2007: Support sections page numbering - @gabrielbull
- Word2007: Added line height methods to mirror the line height settings in Word in the paragraph styling - @gabrielbull
- Word2007: Added support for page header & page footer height - @JillElaine GH-5
- General: Add ability to manage line breaks after image insertion - @bskrtich GH-6 GH-66 GH-84
- Template: Ability to limit number of replacements performed by setValue() method of Template class - @RomanSyroeshko GH-52 GH-53 GH-85
- Table row: Repeat as header row & allow row to break across pages - @ivanlanin GH-48 GH-86
- Table: Table width in percentage - @ivanlanin GH-48 GH-86
- Font: Superscript and subscript - @ivanlanin GH-48 GH-86
- Paragraph: Hanging paragraph - @ivanlanin GH-48 GH-86
- Section: Multicolumn and section break - @ivanlanin GH-48 GH-86
- Template: Ability to apply XSL style sheet to Template - @RomanSyroeshko GH-46 GH-47 GH-83
- General: PHPWord_Shared_Font::pointSizeToTwips() converter - @ivanlanin GH-87
- Paragraph: Ability to define normal paragraph style with PHPWord::setNormalStyle() - @ivanlanin GH-87
- Paragraph: Ability to define parent style (basedOn) and style for following paragraph (next) - @ivanlanin GH-87
- Clone table rows on the fly when using a template document - @jeroenmoors GH-44 GH-88
- Initial addition of basic footnote support - @deds GH-16
- Paragraph: Ability to define paragraph pagination: widow control, keep next, keep lines, and page break before - @ivanlanin GH-92
- General: PHPWord_Style_Font refactoring - @ivanlanin GH-93
- Font: Use points instead of halfpoints internally. Conversion to halfpoints done during XML Writing. - @ivanlanin GH-93
- Paragraph: setTabs() function - @ivanlanin GH-92
- General: Basic support for TextRun on ODT and RTF - @ivanlanin GH-99
- Reader: Basic Reader for Word2007 - @ivanlanin GH-104
- TextRun: Allow Text Break in Text Run - @bskrtich  GH-109
- General: Support for East Asian fontstyle - @jhfangying GH-111 GH-118
- Image: Use exif_imagetype to check image format instead of extension name - @gabrielbull GH-114
- General: Setting for XMLWriter Compatibility option - @bskrtich  GH-103
- MemoryImage: Allow remote image when allow_url_open = on - @ivanlanin GH-122
- TextBreak: Allow font and paragraph style for text break - @ivanlanin GH-18

### Bugfixes

- Fixed bug with cell styling - @gabrielbull
- Fixed bug list items inside of cells - @gabrielbull
- Adding a value that contains "&" in a template breaks it - @SiebelsTim GH-51
- Example in README.md is broken - @Progi1984 GH-89
- General: PHPWord_Shared_Drawing::centimetersToPixels() conversion - @ivanlanin GH-94
- Footnote: Corrupt DOCX reported by MS Word when sections > 1 and not every sections have footnote - @ivanlanin GH-125

### Miscellaneous

- UnitTests - @Progi1984

## 0.7.0 - 28 Jan 2014

This is the first release after a long development hiatus in [CodePlex](https://phpword.codeplex.com/). This release initialized ODT and RTF Writer, along with some other new features for the existing Word2007 Writer, e.g. tab, multiple header, rowspan and colspan. [Composer](https://packagist.org/packages/phpoffice/phpword) and [Travis](https://travis-ci.org/PHPOffice/PHPWord) were added.

### Features

- Implement RTF Writer - @Progi1984 GH-1
- Implement ODT Writer - @Progi1984 GH-2
- Word2007: Add rowspan and colspan to cells - @kaystrobach
- Word2007: Support for tab stops - @RLovelett
- Word2007: Support Multiple headers - @RLovelett
- Word2007: Wrapping Styles to Images - @gabrielbull
- Added support for image wrapping style - @gabrielbull

### Bugfixes

- "Warning: Invalid error type specified in ...\PHPWord.php on line 226" is thrown when the specified template file is not found - @RomanSyroeshko GH-32
- PHPWord_Shared_String.IsUTF8 returns FALSE for Cyrillic UTF-8 input - @RomanSyroeshko GH-34
- Temporary files naming logic in PHPWord_Template can lead to a collision - @RomanSyroeshko GH-38

### Miscellaneous

- Add superscript/subscript styling in Excel2007 Writer - @MarkBaker
- add indentation support to paragraphs - @deds
- Support for Composer - @Progi1984 GH-27
- Basic CI with Travis - @Progi1984
- Added PHPWord_Exception and exception when could not copy the template - @Progi1984
- IMPROVED: Moved examples out of Classes directory - @Progi1984
- IMPROVED: Advanced string replace in setValue for Template - @Esmeraldo CP-49
