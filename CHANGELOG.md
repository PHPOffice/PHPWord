CHANGELOG
=========

This is the changelog between releases of PHPWord. Releases are listed in reverse chronological order with the latest version listed on top, while additions/changes in each release are listed in chronological order. Changes in each release are divided into three parts: added or change features, bugfixes, and miscellaneous improvements. Each line contains short information about the change made, the person who made it, and the related issue number(s) in GitHub.

0.12.1 (30 August 2015)
-------------------

Maintenance release. This release is focused primarily on ``TemplateProcessor``.

### Changes
- Changed visibility of all private properties and methods of ``TemplateProcessor`` to ``protected``. - @RomanSyroeshko #498
- Improved performance of ``TemplateProcessor::setValue()``. - @RomanSyroeshko @nicoSWD #513

### Bugfixes
- Fixed issue with "Access denied" message while opening ``Sample_07_TemplateCloneRow.docx`` and ``Sample_23_TemplateBlock.docx`` result files on Windows platform. - @RomanSyroeshko @AshSat #532
- Fixed ``PreserveText`` element alignment in footer (see ``Sample_12_HeaderFooter.php``). - @RomanSyroeshko @SSchwaiger #495



0.12.0 (3 January 2015)
-----------------------

This release added form fields (textinput, checkbox, and dropdown), drawing shapes (arc, curve, line, polyline, rect, oval), and basic 2D chart (pie, doughnut, bar, line, area, scatter, radar) elements along with some new styles. Basic MsDoc reader is introduced.

### Features
- Element: Ability to add drawing shapes (arc, curve, line, polyline, rect, oval) using new `Shape` element - @ivanlanin #123
- Font: New `scale`, `spacing`, and `kerning` property of font style - @ivanlanin
- Paragraph:  Added shading to the paragraph style for full width shading - @lrobert #264
- RTF Writer: Support for sections, margins, and borders - @ivanlanin #249
- Section: Ability to set paper size, e.g. A4, A3, and Legal - @ivanlanin #249
- General: New `PhpWord::save()` method to encapsulate `IOFactory` - @ivanlanin
- General: New `Shared\Converter` static class - @ivanlanin
- Chart: Basic 2D chart (pie, doughnut, bar, line, area, scatter, radar) - @ivanlanin #278
- Chart: 3D charts and ability to set width and height - @ivanlanin
- FormField: Ability to add textinput, checkbox, and dropdown form elements - @ivanlanin #266
- Setting: Ability to define document protection (readOnly, comments, trackedChanges, forms) - @ivanlanin
- Setting: Ability to remove [Compatibility Mode] text in the MS Word title bar - @ivanlanin
- SDT: Ability to add structured document tag elements (comboBox, dropDownList, date) - @ivanlanin
- Paragraph: Support for paragraph with borders - @ivanlanin #294
- Word2007 Writer : Support for RTL - @Progi1984 #331
- MsDOC Reader: Basic MsDOC Reader - @Progi1984 #23, #287
- "absolute" horizontal and vertical positioning of Frame - @basjan #302
- Add new-page function for PDF generation. For multiple PDF-backends - @chc88 #426
- Report style options enumerated when style unknown - @h6w

### Bugfixes
- Fix rare PclZip/realpath/PHP version problem - @andrew-kzoo #261
- `addHTML` encoding and ampersand fixes for PHP 5.3 - @bskrtich #270
- Page breaks on titles and tables - @ivanlanin #274
- Table inside vertical border does not rendered properly - @ivanlanin #280
- `add<elementName>` of container should be case insensitive, e.g. `addToc` should be accepted, not only `addTOC` - @ivanlanin #294
- Fix specific borders (and margins) were not written correctly in word2007 writer - @pscheit #327
- "HTML is not a valid writer" exception while running "Sample_36_RTL.php" - @RomanSyroeshko #340
- "addShape()" magic method in AbstractContainer is mistakenly named as "addObject()" - @GMTA #356
- `Element\Section::setPageSizeW()` and `Element\Section::setPageSizeH()` were mentioned in the docs but not implemented.
- Special Characters (ampersand) in Title break docx output - @RomanSyroeshko #401
- `<th>` tag is closed with `</td>` tag: - @franzholz #438

### Deprecated
- `Element\Link::getTarget()` replaced by `Element\Link::getSource()`
- `Element\Section::getSettings()` and `Element\Section::setSettings()` replaced by `Element\Section::getStyle()` and `Element\Section::setStyle()`
- `Shared\Drawing` and `Shared\Font` merged into `Shared\Converter`
- `DocumentProperties` replaced by `Metadata\DocInfo`
- `Template` replaced by `TemplateProcessor`
- `PhpWord->loadTemplate($filename)`

### Miscellaneous
- Docs: Add known issue on `README` about requirement for temporary folder to be writable and update `samples/index.php` for this requirement check - @ivanlanin #238
- Docs: Correct elements.rst about Line - @chrissharkman #292
- PclZip: Remove temporary file after used - @andrew-kzoo #265
- Autoloader: Add the ability to set the autoloader options - @bskrtich #267
- Element: Refactor elements to move set relation Id from container to element - @ivanlanin
- Introduced CreateTemporaryFileException, CopyFileException - @RomanSyroeshko
- Settings: added method to set user defined temporary directory - @RomanSyroeshko #310
- Renamed `Template` into `TemplateProcessor` - @RomanSyroeshko #216
- Reverted #51. All text escaping must be performed out of the library - @RomanSyroeshko #51



0.11.1 (2 June 2014)
--------------------

This is an immediate bugfix release for HTML reader.

- HTML Reader: `<p>` and header tags puts no output - @canyildiz @ivanlanin #257



0.11.0 (1 June 2014)
--------------------

This release marked the change of PHPWord license from LGPL 2.1 to LGPL 3. Four new elements were added: TextBox, ListItemRun, Field, and Line. Relative and absolute positioning for images and textboxes were added. Writer classes were refactored into parts, elements, and styles. ODT and RTF features were enhanced. Ability to add elements to PHPWord object via HTML were implemented. RTF and HTML reader were initiated.

### Features
- Image: Ability to define relative and absolute positioning - @basjan #217
- Footer: Conform footer with header by adding firstPage, evenPage and by inheritance - @basjan @ivanlanin #219
- Element: New `TextBox` element - @basjan @ivanlanin #228, #229, #231
- HTML: Ability to add elements to PHPWord object via html - @basjan #231
- Element: New `ListItemRun` element that can add a list item with inline formatting like a textrun - @basjan #235
- Table: Ability to add table inside a cell (nested table) - @ivanlanin #149
- RTF Writer: UTF8 support for RTF: Internal UTF8 text is converted to Unicode before writing - @ivanlanin #158
- Table: Ability to define table width (in percent and twip) and position - @ivanlanin #237
- RTF Writer: Ability to add links and page breaks in RTF - @ivanlanin #196
- ListItemRun: Remove fontStyle parameter because ListItemRun is inherited from TextRun and TextRun doesn't have fontStyle - @ivanlanin
- Config: Ability to use a config file to store various common settings - @ivanlanin #200
- ODT Writer: Enable inline font style in TextRun - @ivanlanin
- ODT Writer: Enable underline, strike/doublestrike, smallcaps/allcaps, superscript/subscript font style - @ivanlanin
- ODT Writer: Enable section and column - @ivanlanin
- PDF Writer: Add TCPDF and mPDF as optional PDF renderer library - @ivanlanin
- ODT Writer: Enable title element and custom document properties - @ivanlanin
- ODT Reader: Ability to read standard and custom document properties - @ivanlanin
- Word2007 Writer: Enable the missing custom document properties writer - @ivanlanin
- Image: Enable "image float left" - @ivanlanin #244
- RTF Writer: Ability to write document properties - @ivanlanin
- RTF Writer: Ability to write image - @ivanlanin
- Element: New `Field` element - @basjan #251
- RTF Reader: Basic RTF reader - @ivanlanin #72, #252
- Element: New `Line` element - @basjan #253
- Title: Ability to apply numbering in heading - @ivanlanin #193
- HTML Reader: Basic HTML reader - @ivanlanin #80, #254
- RTF Writer: Basic table writing - @ivanlanin #245

### Bugfixes
- Header: All images added to the second header were assigned to the first header - @basjan #222
- Conversion: Fix conversion from cm to pixel, pixel to cm, and pixel to point - @basjan #233, #234
- PageBreak: Page break adds new line in the beginning of the new page - @ivanlanin #150
- Image: `marginLeft` and `marginTop` cannot accept float value - @ivanlanin #248
- Title: Orphan `w:fldChar` caused OpenOffice to crash when opening DOCX - @ivanlanin #236

### Deprecated
- Static classes `Footnotes`, `Endnotes`, and `TOC`
- `Writer\Word2007\Part`: `Numbering::writeNumbering()`, `Settings::writeSettings()`, `WebSettings::writeWebSettings()`, `ContentTypes::writeContentTypes()`, `Styles::writeStyles()`, `Document::writeDocument()` all changed into `write()`
- `Writer\Word2007\Part\DocProps`: Split into `Writer\Word2007\Part\DocPropsCore` and `Writer\Word2007\Part\DocPropsApp`
- `Element\Title::getBookmarkId()` replaced by `Element\Title::getRelationId()`
- `Writer\HTML::writeDocument`: Replaced by `Writer\HTML::getContent`

### Miscellaneous
- License: Change the project license from LGPL 2.1 into LGPL 3.0 - #211
- Word2007 Writer: New `Style\Image` class - @ivanlanin
- Refactor: Replace static classes `Footnotes`, `Endnotes`, and `TOC` with `Collections` - @ivanlanin #206
- QA: Reactivate `phpcpd` and `phpmd` on Travis - @ivanlanin
- Refactor: PHPMD recommendation: Change all `get...` method that returns `boolean` into `is...` or `has...` - @ivanlanin
- Docs: Create gh-pages branch for API documentation - @Progi1984 #154
- QA: Add `.scrutinizer.yml` and include `composer.lock` for preparation to Scrutinizer - @ivanlanin #186
- Writer: Refactor writer parts using composite pattern - @ivanlanin
- Docs: Show code quality and test code coverage badge on README
- Style: Change behaviour of `set...` function of boolean properties; when none is defined, assumed true - @ivanlanin
- Shared: Unify PHP ZipArchive and PCLZip features into PhpWord ZipArchive - @ivanlanin
- Docs: Create VERSION file - @ivanlanin
- QA: Improve dan update requirement check in `samples` folder - @ivanlanin



0.10.1 (21 May 2014)
--------------------

This is a bugfix release for `php-zip` requirement in Composer.

- Change Composer requirements for php-zip from `require` to `suggest` - @bskrtich #246



0.10.0 (4 May 2014)
-------------------

This release marked heavy refactorings on internal code structure with the creation of some abstract classes to reduce code duplication. `Element` subnamespace is introduced in this release to replace `Section`. Word2007 reader capability is greatly enhanced. Endnote is introduced. List numbering is now customizable. Basic HTML and PDF writing support is enabled. Basic ODText reader is introduced.

### Features
- Image: Get image dimensions without EXIF extension - @andrew-kzoo #184
- Table: Add `tblGrid` element for Libre/Open Office table sizing - @gianis6 #183
- Footnote: Ability to insert textbreak in footnote `$footnote->addTextBreak()` - @ivanlanin
- Footnote: Ability to style footnote reference mark by using `FootnoteReference` style - @ivanlanin
- Font: Add `bgColor` to font style to define background using HEX color - @jcarignan #168
- Table: Add `exactHeight` to row style to define whether row height should be exact or atLeast - @jcarignan #168
- Element: New `CheckBox` element for sections and table cells - @ozilion #156
- Settings: Ability to use PCLZip as alternative to ZipArchive - @bskrtich @ivanlanin #106, #140, #185
- Template: Ability to find & replace variables in headers & footers - @dgudgeon #190
- Template: Ability to clone & delete block of text using `cloneBlock` and `deleteBlock` - @diego-vieira #191
- TOC: Ability to have two or more TOC in one document and to set min and max depth for TOC - @Pyreweb #189
- Table: Ability to add footnote in table cell - @ivanlanin #187
- Footnote: Ability to add image in footnote - @ivanlanin #187
- ListItem: Ability to add list item in header/footer - @ivanlanin #187
- CheckBox: Ability to add checkbox in header/footer - @ivanlanin #187
- Link: Ability to add link in header/footer - @ivanlanin #187
- Object: Ability to add object in header, footer, textrun, and footnote - @ivanlanin #187
- Media: Add `Media::resetElements()` to reset all media data - @juzi #19
- General: Add `Style::resetStyles()` - @ivanlanin #187
- DOCX Reader: Ability to read header, footer, footnotes, link, preservetext, textbreak, pagebreak, table, list, image, and title - @ivanlanin
- Endnote: Ability to add endnotes - @ivanlanin
- ListItem: Ability to create custom list and reset list number - @ivanlanin #10, #198
- ODT Writer: Basic table writing support - @ivanlanin
- Image: Keep image aspect ratio if only 1 dimension styled - @japonicus #194
- HTML Writer: Basic HTML writer: text, textrun, link, title, textbreak, table, image (as Base64), footnote, endnote - @ivanlanin #203, #67, #147
- PDF Writer: Basic PDF writer using DomPDF: All HTML element except image - @ivanlanin #68
- DOCX Writer: Change `docProps/app.xml` `Application` to `PHPWord` - @ivanlanin
- DOCX Writer: Create `word/settings.xml` and `word/webSettings.xml` dynamically - @ivanlanin
- ODT Writer: Basic image writing - @ivanlanin
- ODT Writer: Link writing - @ivanlanin
- ODT Reader: Basic ODText Reader - @ivanlanin #71
- Section: Ability to define gutter and line numbering - @ivanlanin
- Font: Small caps, all caps, and double strikethrough - @ivanlanin #151
- Settings: Ability to use measurement unit other than twips with `setMeasurementUnit` - @ivanlanin #199
- Style: Remove `bgColor` from `Font`, `Table`, and `Cell` and put it into the new `Shading` style - @ivanlanin
- Style: New `Indentation` and `Spacing` style - @ivanlanin
- Paragraph: Ability to define first line and right indentation - @ivanlanin

### Bugfixes
- Footnote: Footnote content doesn't show footnote reference number - @ivanlanin #170
- Documentation: Error in a function - @theBeerNut #195

### Deprecated
- `createTextRun` replaced by `addTextRun`
- `createFootnote` replaced by `addFootnote`
- `createHeader` replaced by `addHeader`
- `createFooter` replaced by `addFooter`
- `createSection` replaced by `addSection`
- `Element\Footnote::getReferenceId` replaced by `Element\AbstractElement::getRelationId`
- `Element\Footnote::setReferenceId` replaced by `Element\AbstractElement::setRelationId`
- `Footnote::addFootnoteLinkElement` replaced by `Media::addElement`
- `Footnote::getFootnoteLinkElements` replaced by `Media::getElements`
- All current methods on `Media`
- `Element\Link::getLinkSrc` replaced by `Element\Link::getTarget`
- `Element\Link::getLinkName` replaced by `Element\Link::getText`
- `Style\Cell::getDefaultBorderColor`

### Miscellaneous
- Documentation: Simplify page level docblock - @ivanlanin #179
- Writer: Refactor writer classes and create a new `Write\AbstractWriter` abstract class - @ivanlanin #160
- General: Refactor folders: `Element` and `Exception` - @ivanlanin #187
- General: Remove legacy `HashTable` and `Shared\ZipStreamWrapper` and all related properties/methods - @ivanlanin #187
- Element: New `AbstractElement` abstract class - @ivanlanin #187
- Media: Refactor media class to use one method for all docPart (section, header, footer, footnote) - @ivanlanin #187
- General: Remove underscore prefix from all private properties name - @ivanlanin #187
- General: Move Section `Settings` to `Style\Section` - @ivanlanin #187
- General: Give `Abstract` prefix and `Interface` suffix for all abstract classes and interfaces as per [PHP-FIG recommendation](https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-naming-conventions.md) - @ivanlanin #187
- Style: New `Style\AbstractStyle` abstract class - @ivanlanin #187
- Writer: New 'ODText\Base` class - @ivanlanin #187
- General: Rename `Footnote` to `Footnotes` to reflect the nature of collection - @ivanlanin
- General: Add some unit tests for Shared & Element (100%!) - @Progi1984
- Test: Add some samples and tests for image wrapping style - @brunocasado #59
- Refactor: Remove Style\Tabs - @ivanlanin
- Refactor: Apply composite pattern for writers - @ivanlanin
- Refactor: Split `AbstractContainer` from `AbstractElement` - @ivanlanin
- Refactor: Apply composite pattern for Word2007 reader - @ivanlanin



0.9.1 (27 Mar 2014)
-------------------

This is a bugfix release for PSR-4 compatibility.

- Fixed PSR-4 composer autoloader - @AntonTyutin



0.9.0 (26 Mar 2014)
-------------------

This release marked the transformation to namespaces (PHP 5.3+).

### Features
- Image: Ability to use remote or GD images using `addImage()` on sections, headers, footer, cells, and textruns - @ivanlanin
- Header: Ability to use remote or GD images using `addWatermark()` - @ivanlanin

### Bugfixes
- Preserve text doesn't render correctly when the text is not the first word, e.g. 'Page {PAGE}' - @ivanlanin

### Miscellaneous
- Move documentation to [Read The Docs](http://phpword.readthedocs.org/en/develop/) - @Progi1984 @ivanlanin #82
- Reorganize and redesign samples folder - @ivanlanin #137
- Use `PhpOffice\PhpWord` namespace for PSR compliance - @RomanSyroeshko @gabrielbull #159, #58
- Restructure folders and change folder name `Classes` to `src` and `Tests` to `test` for PSR compliance - @RomanSyroeshko @gabrielbull
- Compliance to phpDocumentor - @ivanlanin
- Merge Style\TableFull into Style\Table. Style\TableFull is deprecated - @ivanlanin #160
- Merge Section\MemoryImage into Section\Image. Section\Image is deprecated - @ivanlanin #160



0.8.1 (17 Mar 2014)
-------------------

This is a bugfix release for image detection functionality.

- Added fallback for computers that do not have exif_imagetype - @bskrtich, @gabrielbull



0.8.0 (15 Mar 2014)
-------------------

This release merged a lot of improvements from the community. Unit tests introduced in this release and has reached 90% code coverage.

### Features
- Template: Permit to save a template generated as a file (PHPWord_Template::saveAs()) - @RomanSyroeshko #56, #57
- Word2007: Support sections page numbering - @gabrielbull
- Word2007: Added line height methods to mirror the line height settings in Word in the paragraph styling - @gabrielbull
- Word2007: Added support for page header & page footer height - @JillElaine #5
- General: Add ability to manage line breaks after image insertion - @bskrtich #6, #66, #84
- Template: Ability to limit number of replacements performed by setValue() method of Template class - @RomanSyroeshko #52, #53, #85
- Table row: Repeat as header row & allow row to break across pages - @ivanlanin #48, #86
- Table: Table width in percentage - @ivanlanin #48, #86
- Font: Superscript and subscript - @ivanlanin #48, #86
- Paragraph: Hanging paragraph - @ivanlanin #48, #86
- Section: Multicolumn and section break - @ivanlanin #48, #86
- Template: Ability to apply XSL style sheet to Template - @RomanSyroeshko #46, #47, #83
- General: PHPWord_Shared_Font::pointSizeToTwips() converter - @ivanlanin #87
- Paragraph: Ability to define normal paragraph style with PHPWord::setNormalStyle() - @ivanlanin #87
- Paragraph: Ability to define parent style (basedOn) and style for following paragraph (next) - @ivanlanin #87
- Clone table rows on the fly when using a template document - @jeroenmoors #44, #88
- Initial addition of basic footnote support - @deds #16
- Paragraph: Ability to define paragraph pagination: widow control, keep next, keep lines, and page break before - @ivanlanin #92
- General: PHPWord_Style_Font refactoring - @ivanlanin #93
- Font: Use points instead of halfpoints internally. Conversion to halfpoints done during XML Writing. - @ivanlanin #93
- Paragraph: setTabs() function - @ivanlanin #92
- General: Basic support for TextRun on ODT and RTF - @ivanlanin #99
- Reader: Basic Reader for Word2007 - @ivanlanin #104
- TextRun: Allow Text Break in Text Run - @bskrtich  #109
- General: Support for East Asian fontstyle - @jhfangying #111, #118
- Image: Use exif_imagetype to check image format instead of extension name - @gabrielbull #114
- General: Setting for XMLWriter Compatibility option - @bskrtich  #103
- MemoryImage: Allow remote image when allow_url_open = on - @ivanlanin #122
- TextBreak: Allow font and paragraph style for text break - @ivanlanin #18

### Bugfixes
- Fixed bug with cell styling - @gabrielbull
- Fixed bug list items inside of cells - @gabrielbull
- Adding a value that contains "&" in a template breaks it - @SiebelsTim #51
- Example in README.md is broken - @Progi1984 #89
- General: PHPWord_Shared_Drawing::centimetersToPixels() conversion - @ivanlanin #94
- Footnote: Corrupt DOCX reported by MS Word when sections > 1 and not every sections have footnote - @ivanlanin #125

### Miscellaneous
- UnitTests - @Progi1984



0.7.0 (28 Jan 2014)
-------------------

This is the first release after a long development hiatus in [CodePlex](https://phpword.codeplex.com/). This release initialized ODT and RTF Writer, along with some other new features for the existing Word2007 Writer, e.g. tab, multiple header, rowspan and colspan. [Composer](https://packagist.org/packages/phpoffice/phpword) and [Travis](https://travis-ci.org/PHPOffice/PHPWord) were added.

### Features
- Implement RTF Writer - @Progi1984 #1
- Implement ODT Writer - @Progi1984 #2
- Word2007: Add rowspan and colspan to cells - @kaystrobach
- Word2007: Support for tab stops - @RLovelett
- Word2007: Support Multiple headers - @RLovelett
- Word2007: Wrapping Styles to Images - @gabrielbull
- Added support for image wrapping style - @gabrielbull

### Bugfixes
- "Warning: Invalid error type specified in ...\PHPWord.php on line 226" is thrown when the specified template file is not found - @RomanSyroeshko #32
- PHPWord_Shared_String.IsUTF8 returns FALSE for Cyrillic UTF-8 input - @RomanSyroeshko #34
- Temporary files naming logic in PHPWord_Template can lead to a collision - @RomanSyroeshko #38

### Miscellaneous
- Add superscript/subscript styling in Excel2007 Writer - @MarkBaker
- add indentation support to paragraphs - @deds
- Support for Composer - @Progi1984 #27
- Basic CI with Travis - @Progi1984
- Added PHPWord_Exception and exception when could not copy the template - @Progi1984
- IMPROVED: Moved examples out of Classes directory - @Progi1984
- IMPROVED: Advanced string replace in setValue for Template - @Esmeraldo [#49](http://phpword.codeplex.com/workitem/49)
