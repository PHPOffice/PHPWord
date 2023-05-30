# Changelog
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.1.0](https://github.com/PHPOffice/PHPWord/tree/1.1.0) (2023-05-30)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/1.0.0...1.1.0)

### Enhancements

- Introduce deleteRow() method for TemplateProcessor
- HTML Reader: Add basic support for CSS Style Tag
- Allow customizing macro syntax in TemplateProcessor
- Add background color support for textboxes
- Add softhyphen support to word reader
- Add support table row height when importing HTML
- Add support for fractional font sizes
- Added image quality support, with the maximum quality as default
- Support for reading nested tables

### Bug fixes

- DOCX reader: Read empty vmerge
- Fixed setting width of Cell Style

### Miscellaneous

- `master` is the new default branch

## [1.0.0](https://github.com/PHPOffice/PHPWord/tree/1.0.0) (2022-11-15)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/0.18.3...1.0.0)

### BREAKING CHANGE

Most deprecated things were dropped. See details in 
https://github.com/PHPOffice/PHPWord/commit/b9f1151bc6f90c276153c3c9dca10a5fc7f355fb.

#### Dropped classes:

- `PhpOffice\PhpWord\Template`

#### Dropped constants:

- `PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTHASH`
- `PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTHASHHEAVY`
- `PhpOffice\PhpWord\Style\Cell::VALIGN_TOP`
- `PhpOffice\PhpWord\Style\Cell::VALIGN_CENTER`
- `PhpOffice\PhpWord\Style\Cell::VALIGN_BOTTOM`
- `PhpOffice\PhpWord\Style\Cell::VALIGN_BOTH`
- `PhpOffice\PhpWord\Style\TOC::TABLEADER_DOT`
- `PhpOffice\PhpWord\Style\TOC::TABLEADER_UNDERSCORE`
- `PhpOffice\PhpWord\Style\TOC::TABLEADER_LINE`
- `PhpOffice\PhpWord\Style\TOC::TABLEADER_NONE`
- `PhpOffice\PhpWord\Style\Table::WIDTH_AUTO`
- `PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT`
- `PhpOffice\PhpWord\Style\Table::WIDTH_TWIP`
- `PhpOffice\PhpWord\PhpWord::DEFAULT_FONT_NAME`
- `PhpOffice\PhpWord\PhpWord::DEFAULT_FONT_SIZE`
- `PhpOffice\PhpWord\PhpWord::DEFAULT_FONT_COLOR`
- `PhpOffice\PhpWord\PhpWord::DEFAULT_FONT_CONTENT_TYPE`
- 
#### Dropped methods:

- `PhpOffice\PhpWord\Ekement\AbstractContainer::createTextRun()`
- `PhpOffice\PhpWord\Ekement\AbstractContainer::createFootnote()`
- `PhpOffice\PhpWord\Ekement\Footnote::getReferenceId()`
- `PhpOffice\PhpWord\Ekement\Footnote::setReferenceId()`
- `PhpOffice\PhpWord\Ekement\Image::getIsWatermark()`
- `PhpOffice\PhpWord\Ekement\Image::getIsMemImage()`
- `PhpOffice\PhpWord\Ekement\Link::getTarget()`
- `PhpOffice\PhpWord\Ekement\Link::getLinkSrc()`
- `PhpOffice\PhpWord\Ekement\Link::getLinkName()`
- `PhpOffice\PhpWord\Ekement\OLEObject::getObjectId()`
- `PhpOffice\PhpWord\Ekement\OLEObject::setObjectId()`
- `PhpOffice\PhpWord\Ekement\Section::getFootnotePropoperties()`
- `PhpOffice\PhpWord\Ekement\Section::setSettings()`
- `PhpOffice\PhpWord\Ekement\Section::getSettings()`
- `PhpOffice\PhpWord\Ekement\Section::createHeader()`
- `PhpOffice\PhpWord\Ekement\Section::createFooter()`
- `PhpOffice\PhpWord\Ekement\Section::getFooter()`
- `PhpOffice\PhpWord\Media::addSectionMediaElement()`
- `PhpOffice\PhpWord\Media::addSectionLinkElement()`
- `PhpOffice\PhpWord\Media::getSectionMediaElements()`
- `PhpOffice\PhpWord\Media::countSectionMediaElements()`
- `PhpOffice\PhpWord\Media::addHeaderMediaElement()`
- `PhpOffice\PhpWord\Media::countHeaderMediaElements()`
- `PhpOffice\PhpWord\Media::getHeaderMediaElements()`
- `PhpOffice\PhpWord\Media::addFooterMediaElement()`
- `PhpOffice\PhpWord\Media::countFooterMediaElements()`
- `PhpOffice\PhpWord\Media::getFooterMediaElements()`
- `PhpOffice\PhpWord\PhpWord::getProtection()`
- `PhpOffice\PhpWord\PhpWord::loadTemplate()`
- `PhpOffice\PhpWord\PhpWord::createSection()`
- `PhpOffice\PhpWord\PhpWord::getDocumentProperties()`
- `PhpOffice\PhpWord\PhpWord::setDocumentProperties()`
- `PhpOffice\PhpWord\Reader\AbstractReader::getReadDataOnly()`
- `PhpOffice\PhpWord\Settings::getCompatibility()`
- `PhpOffice\PhpWord\Style\AbstractStyle::setArrayStyle()`
- `PhpOffice\PhpWord\Style\Cell::getDefaultBorderColor()`
- `PhpOffice\PhpWord\Style\Font::getBold()`
- `PhpOffice\PhpWord\Style\Font::getItalic()`
- `PhpOffice\PhpWord\Style\Font::getSuperScript()`
- `PhpOffice\PhpWord\Style\Font::getSubScript()`
- `PhpOffice\PhpWord\Style\Font::getStrikethrough()`
- `PhpOffice\PhpWord\Style\Font::getParagraphStyle()`
- `PhpOffice\PhpWord\Style\Frame::getAlign()`
- `PhpOffice\PhpWord\Style\Frame::setAlign()`
- `PhpOffice\PhpWord\Style\NumberingLevel::getAlign()`
- `PhpOffice\PhpWord\Style\NumberingLevel::setAlign()`
- `PhpOffice\PhpWord\Style\Paragraph::getAlign()`
- `PhpOffice\PhpWord\Style\Paragraph::setAlign()`
- `PhpOffice\PhpWord\Style\Paragraph::getWidowControl()`
- `PhpOffice\PhpWord\Style\Paragraph::getKeepNext()`
- `PhpOffice\PhpWord\Style\Paragraph::getKeepLines()`
- `PhpOffice\PhpWord\Style\Paragraph::getPageBreakBefore()`
- `PhpOffice\PhpWord\Style\Row::getTblHeader()`
- `PhpOffice\PhpWord\Style\Row::isTblHeader()`
- `PhpOffice\PhpWord\Style\Row::getCantSplit()`
- `PhpOffice\PhpWord\Style\Row::getExactHeight()`
- `PhpOffice\PhpWord\Style\Spacing::getRule()`
- `PhpOffice\PhpWord\Style\Spacing::setRule()`
- `PhpOffice\PhpWord\Style\Table::getAlign()`
- `PhpOffice\PhpWord\Style\Table::setAlign()`
- `PhpOffice\PhpWord\Writer\AbstractWriter::getUseDiskCaching()`
- `PhpOffice\PhpWord\Writer\HTML::writeDocument()`

### Bug fixes

- Multiple PHP 8.1 fixes
- `loadConfig` returns config that was actually applied
- HTML Reader : Override inline style on HTML attribute for table
- HTML Reader : Use `border` attribute for tables
- HTML Reader : Style page-break-after in paragraph
- HTML Reader : Heading in Text Run is not allowed
- 
### Miscellaneous

- Drop support for PHP 7.0 and older

## [0.18.3](https://github.com/PHPOffice/PHPWord/tree/0.18.3) (2022-02-17)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/0.18.2...0.18.3)

### Bug fixes
- PHP 8.1 compatibility

## [0.18.2](https://github.com/PHPOffice/PHPWord/tree/0.18.2) (2021-06-04)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/0.18.1...0.18.2)

### Bug fixes
- when adding image to relationship first check that the generated RID is actually unique [\#2063](https://github.com/PHPOffice/PHPWord/pull/2063) ([tpv-ebben](https://github.com/tpv-ebben))
- Update chart, don't write 'c:overlap' if grouping is 'clustered' [\#2052](https://github.com/PHPOffice/PHPWord/pull/2052) ([dfsd534](https://github.com/dfsd534))
- Update Html parser to accept line-height:normal [\#2041](https://github.com/PHPOffice/PHPWord/pull/2041) ([joelgo](https://github.com/joelgo))
- Fix image border in Word2007 Writer for LibreOffice 7 [\#2021](https://github.com/PHPOffice/PHPWord/pull/2021) ([kamilmmach](https://github.com/kamilmmach))

### Miscellaneous
- Corrected namespace for Language class in docs. [\#2087](https://github.com/PHPOffice/PHPWord/pull/2087) ([MegaChriz](https://github.com/MegaChriz))
- Added support for Garamond font [\#2078](https://github.com/PHPOffice/PHPWord/pull/2078) ([artemkolotilkin](https://github.com/artemkolotilkin))
- Add BorderStyle for Cell Style to documentation [\#2090](https://github.com/PHPOffice/PHPWord/pull/2090) ([DShkrabak](https://github.com/DShkrabak))

## [0.18.1](https://github.com/PHPOffice/PHPWord/tree/0.18.1) (2021-03-08)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/0.18.0...0.18.1)

### Bug fixes
- Fix BC break in #1946. This package does not replace laminas/laminas-zendframework-bridge [\#2032](https://github.com/PHPOffice/PHPWord/pull/2032) ([mussbach](https://github.com/mussbach))

## [0.18.0](https://github.com/PHPOffice/PHPWord/tree/0.18.0) (2021-02-12)

[Full Changelog](https://github.com/PHPOffice/PHPWord/compare/0.17.0...0.18.0)

### Enhancements
- Add support for charts in template processor [\#2012](https://github.com/PHPOffice/PHPWord/pull/2012) ([dbarzin](https://github.com/dbarzin))
- add/setting page element border style. [\#1986](https://github.com/PHPOffice/PHPWord/pull/1986) ([emnabs](https://github.com/emnabs))
- allow to use customized pdf library [\#1983](https://github.com/PHPOffice/PHPWord/pull/1983) ([SailorMax](https://github.com/SailorMax))
- feat: Update addHtml to handle style inheritance [\#1965](https://github.com/PHPOffice/PHPWord/pull/1965) ([Julien1138](https://github.com/Julien1138))
- Add parsing of Shape node values [\#1924](https://github.com/PHPOffice/PHPWord/pull/1924) ([sven-ahrens](https://github.com/sven-ahrens))
- Allow to redefine TCPDF object [\#1907](https://github.com/PHPOffice/PHPWord/pull/1907) ([SailorMax](https://github.com/SailorMax))
- Enhancements to addHTML parser [\#1902](https://github.com/PHPOffice/PHPWord/pull/1902) ([lubosdz](https://github.com/lubosdz))
- Make Default Paper Configurable [\#1851](https://github.com/PHPOffice/PHPWord/pull/1851) ([oleibman](https://github.com/oleibman))
- Implement various missing features for the ODT writer [\#1796](https://github.com/PHPOffice/PHPWord/pull/1796) ([oleibman](https://github.com/oleibman))
- Added support for "cloudConvert" images [\#1794](https://github.com/PHPOffice/PHPWord/pull/1794) ([ErnestStaug](https://github.com/ErnestStaug))
- Add support for several features for the RTF writer [\#1775](https://github.com/PHPOffice/PHPWord/pull/1775) ([oleibman](https://github.com/oleibman))
- Add font style for Field elements [\#1774](https://github.com/PHPOffice/PHPWord/pull/1774) ([oleibman](https://github.com/oleibman))
- Add support for ListItemRun in HTML writer [\#1766](https://github.com/PHPOffice/PHPWord/pull/1766) ([stefan-91](https://github.com/stefan-91))
- Improvements in RTF writer [\#1755](https://github.com/PHPOffice/PHPWord/pull/1755) ([oleibman](https://github.com/oleibman))
- Allow a closure to be passed with image replacement tags [\#1716](https://github.com/PHPOffice/PHPWord/pull/1716) ([mbardelmeijer](https://github.com/mbardelmeijer))
- Add Option for Dynamic Chart Legend Position [\#1699](https://github.com/PHPOffice/PHPWord/pull/1699) ([Stephan212](https://github.com/Stephan212))
- Add parsing of HTML checkbox input field [\#1832](https://github.com/PHPOffice/PHPWord/pull/1832) ([Matze2010](https://github.com/Matze2010))

### Bug fixes
- Fix image stroke in libreoffice 7.x [\#1992](https://github.com/PHPOffice/PHPWord/pull/1992) ([Adizbek](https://github.com/Adizbek))
- Fix deprecated warning for non-hexadecimal number [\#1988](https://github.com/PHPOffice/PHPWord/pull/1988) ([Ciki](https://github.com/Ciki))
- Fix limit not taken into account when adding image in template [\#1967](https://github.com/PHPOffice/PHPWord/pull/1967) ([jsochor](https://github.com/jsochor))
- Add null check when setComplexValue is not found [\#1936](https://github.com/PHPOffice/PHPWord/pull/1936) ([YannikFirre](https://github.com/YannikFirre))
- Some document have non-standard locale code [\#1824](https://github.com/PHPOffice/PHPWord/pull/1824) ([ErnestStaug](https://github.com/ErnestStaug))
- Fixes PHPDoc @param and @return types for several Converter methods [\#1818](https://github.com/PHPOffice/PHPWord/pull/1818) ([caugner](https://github.com/caugner))
- Update the regexp to avoid catastrophic backtracking [\#1809](https://github.com/PHPOffice/PHPWord/pull/1809) ([juzser](https://github.com/juzser))
- Fix PHPUnit tests on develop branch [\#1771](https://github.com/PHPOffice/PHPWord/pull/1771) ([mdupont](https://github.com/mdupont))
- TemplateProcessor cloneBlock wrongly clones images [\#1763](https://github.com/PHPOffice/PHPWord/pull/1763) ([alarai](https://github.com/alarai))

### Miscellaneous
- Compatibility with PHP 7.4, PHP 8.0 and migrate to Laminas Escaper [\#1946](https://github.com/PHPOffice/PHPWord/pull/1946) ([liborm85](https://github.com/liborm85))
- Remove legacy PHPOffice/Common package, fix PHP 8.0 compatibility [\#1996](https://github.com/PHPOffice/PHPWord/pull/1996) ([liborm85](https://github.com/liborm85))
- Improve Word2007 Test Coverage [\#1858](https://github.com/PHPOffice/PHPWord/pull/1858) ([oleibman](https://github.com/oleibman))
- Fix typo in docs. Update templates-processing.rst [\#1952](https://github.com/PHPOffice/PHPWord/pull/1952) ([mnvx](https://github.com/mnvx))
- Fix documentation and method name for FootnoteProperties [\#1776](https://github.com/PHPOffice/PHPWord/pull/1776) ([mdupont](https://github.com/mdupont))
- fix: documentation about paragraph indentation [\#1764](https://github.com/PHPOffice/PHPWord/pull/1764) ([mdupont](https://github.com/mdupont))
- Update templates-processing.rst [\#1745](https://github.com/PHPOffice/PHPWord/pull/1745) ([igronus](https://github.com/igronus))
- Unused variables $rows, $cols in sample [\#1877](https://github.com/PHPOffice/PHPWord/pull/1877) ([ThanasisMpalatsoukas](https://github.com/ThanasisMpalatsoukas))
- Add unit test for NumberingStyle [\#1744](https://github.com/PHPOffice/PHPWord/pull/1744) ([Manunchik](https://github.com/Manunchik))
- Add unit test for PhpWord Settings [\#1743](https://github.com/PHPOffice/PHPWord/pull/1743) ([Manunchik](https://github.com/Manunchik))
- Add unit test for Media elements [\#1742](https://github.com/PHPOffice/PHPWord/pull/1742) ([Manunchik](https://github.com/Manunchik))
- Update templates processing docs [\#1729](https://github.com/PHPOffice/PHPWord/pull/1729) ([hcdias](https://github.com/hcdias))

v0.17.0 (01 oct 2019)
----------------------
### Added
- Add methods setValuesFromArray and cloneRowFromArray to the TemplateProcessor @geraldb-nicat #670
- Set complex type in template @troosan #1565
- implement support for section vAlign @troosan #1569
- ParseStyle for border-color @Gllrm0 #1551
- Html writer auto invert text color @SailorMax #1387
- Add RightToLeft table presentation. @troosan #1550
- Add support for page vertical alignment. @troosan #672 #1569
- Adding setNumId method for ListItem style @eweso #1329
- Add support for basic fields in RTF writer. @Samuel-BF #1717

### Fixed
- Fix HTML border-color parsing. @troosan #1551 #1570
- Language::validateLocale should pass with locale 'zxx'. @efpapado #1558
- can't align center vertically with the text @ter987 #672
- fix parsing of border-color and add test @troosan #1570
- TrackChange doesn't handle all return types of \DateTime::createFromFormat(...) @superhaggis #1584
- To support PreserveText inside sub container @bhattnishant #1637
- No nested w:pPr elements in ListItemRun. @waltertamboer #1628
- Ensure that entity_loader disable variable is re-set back to the original setting @seamuslee001 #1585

### Miscellaneous
- Use embedded http server to test loading of remote images @troosan #1544
- Change private to protected to be able extending class Html @SpinyMan #1646
- Fix apt-get crash in Travis CI for PHP 5.3 @mdupont #1707

v0.16.0 (30 dec 2018)
----------------------
### Added
- Add getVariableCount method in TemplateProcessor. @nicoder #1272
- Add setting Chart Title and Legend visibility @Tom-Magill #1433
- Add ability to pass a Style object in Section constructor @ndench #1416
- Add support for hidden text @Alexmg86 #1527
- Add support for setting images in TemplateProcessor @SailorMax #1170
- Add "Plain Text" type to SDT (Structured Document Tags) @morrisdj #1541
- Added possibility to index variables inside cloned block in TemplateProcessor @JPBetley #817
- Added possibility to replace variables inside cloned block with values in TemplateProcessor @DIDoS #1392

### Fixed
- Fix regex in `cloneBlock` function @nicoder #1269
- HTML Title Writer loses text when Title contains a TextRun instead a string. @begnini #1436
- Fix regex in fixBrokenMacros, make it less greedy @MuriloSo @brainwood @yurii-sio2 #1502 #1345
- 240 twips are being added to line spacing, should not happen when using lineRule fixed @troosan #1509 #1505
- Adding table layout to the generated HTML @aarangara #1441
- Fix loading of Sharepoint document @Garrcomm #1498
- RTF writer: Round getPageSizeW and getPageSizeH to avoid decimals @Patrick64 #1493
- Fix parsing of Office 365 documents @Timanx #1485
- For RTF writers, sizes should should never have decimals @Samuel-BF #1536
- Style Name Parsing fails if document generated by a non-english word version @begnini #1434

### Miscellaneous
- Get rid of duplicated code in TemplateProcessor @abcdmitry #1161

v0.15.0 (14 Jul 2018)
----------------------
### Added
- Parsing of `align` HTML attribute - @troosan #1231
- Parse formatting inside HTML lists - @troosan @samimussbach #1239 #945 #1215 #508
- Parsing of CSS `direction` instruction, HTML `lang` attribute, formatting inside table cell - @troosan #1273 #1252 #1254
- Add support for Track changes @Cip @troosan #354 #1262
- Add support for fixed Table Layout @aoloe @ekopach @troosan #841 #1276
- Add support for Cell Spacing @dox07 @troosan #1040
- Add parsing of formatting inside lists @atomicalnet @troosan #594
- Added support for Vertically Raised or Lowered Text (w:position) @anrikun @troosan #640
- Add support for MACROBUTTON field @phryneas @troosan #1021
- Add support for Hyphenation @Trainmaster #1282 (Document: `autoHyphenation`, `consecutiveHyphenLimit`, `hyphenationZone`, `doNotHyphenateCaps`, Paragraph: `suppressAutoHyphens`)
- Added support for Floating Table Positioning (tblpPr) @anrikun #639
- Added support for Image text wrapping distance @troosan #1310
- Added parsing of CSS line-height and text-indent in HTML reader @troosan #1316
- Added the ability to enable gridlines and axislabels on charts @FrankMeyer #576
- Add support for table indent (tblInd) @Trainmaster #1343
- Added parsing of internal links in HTML reader @lalop #1336
- Several improvements to charts @JAEK-S #1332
- Add parsing of html image in base64 format @jgpATs2w #1382
- Added Support for Indentation & Tabs on RTF Writer. @smaug1985 #1405
- Allows decimal numbers in HTML line-height style @jgpATs2w #1413

### Fixed
- Fix reading of docx default style - @troosan #1238
- Fix the size unit of when parsing html images - @troosan #1254
- Fixed HTML parsing of nested lists - @troosan #1265
- Save PNG alpha information when using remote images. @samsullivan #779
- Fix parsing of `<w:br/>` tag. @troosan #1274
- Bookmark are not writton as internal link in html writer @troosan #1263
- It should be possible to add a Footnote in a ListItemRun @troosan #1287 #1287
- Fix colspan and rowspan for tables in HTML Writer @mattbolt #1292
- Fix parsing of Heading and Title formating @troosan @gthomas2 #465
- Fix Dateformat typo, fix hours casing, add Month-Day-Year formats @ComputerTinker #591
- Support reading of w:drawing for documents produced by word 2011+ @gthomas2 #464 #1324
- Fix missing column width in ODText writer @potofcoffee #413
- Disable entity loader before parsing XML to avoid XXE injection @Tom4t0 #1427

### Changed
- Remove zend-stdlib dependency @Trainmaster #1284
- The default unit for `\PhpOffice\PhpWord\Style\Image` changed from `px` to `pt`.

### Miscellaneous
- Drop GitHub pages, switch to coveralls for code coverage analysis @czosel #1360

v0.14.0 (29 Dec 2017)
----------------------
This release fixes several bugs and adds some new features.
This version brings compatibility with PHP 7.0 & 7.1

### Added
- Possibility to control the footnote numbering - @troosan #1068
- Image creation from string - @troosan #937
- Introduced the `\PhpOffice\PhpWord\SimpleType\NumberFormat` simple type. - @troosan
- Support for ContextualSpacing - @postHawk #1088
- Possiblity to hide spelling and/or grammatical errors - @troosan #542
- Possiblity to set default document language as well as changing the language for each text element - @troosan #1108
- Support for Comments - @troosan #1067
- Support for paragraph textAlignment - @troosan #1165
- Add support for HTML underline tag <u> in addHtml - @zNightFalLz #1186
- Add support for HTML <br> in addHtml - @anrikun @troosan #659
- Allow to change cell width unit - guillaume-ro-fr #986
- Allow to change the line height rule @troosan
- Implement PageBreak for odt writer @cookiekiller #863 #824
- Allow to force an update of all fields on opening a document - @troosan #951
- Allow adding a CheckBox in a TextRun - @irond #727
- Add support for HTML img tag - @srggroup #934
- Add support for password protection for docx - @mariahaubner #1019

### Fixed
- Loosen dependency to Zend
- Images are not being printed when generating PDF - @hubertinio #1074 #431
- Fixed some PHP 7 warnings - @	likeuntomurphy #927
- Fixed PHP 7.2 compatibility (renamed `Object` class names to `ObjectElement`) - @SailorMax #1185
- Fixed Word 97 reader - @alsofronie @Benpxpx @mario-rivera #912 #920 #892
- Fixed image loading over https - @troosan #988
- Impossibility to set different even and odd page headers - @troosan #981
- Fixed Word2007 reader where unnecessary paragraphs were being created - @donghaobo #1043 #620
- Fixed Word2007 reader where margins were not being read correctly - @slowprog #885 #1008
- Impossible to add element PreserveText in Section - @rvanlaak #452
- Added missing options for numbering format - @troosan #1041
- Fixed impossibility to set a different footer for first page - @ctrlaltca #1116, @aoloe #875
- Fixed styles not being applied by HTML writer, better pdf output - @sarke #1047 #500 #1139
- Fixed read docx error when document contains image from remote url - @FBnil #1173 #1176
- Padded the $args array to remove error - @kaigoh #1150, @reformed #870
- Fix incorrect image size between windows and mac - @bskrtich #874
- Fix adding HTML table to document - @mogilvie @arivanbastos #324
- Fix parsing on/off values (w:val="true|false|1|0|on|off") - @troosan #1221 #1219
- Fix error on Empty Dropdown Entry - @ComputerTinker #592

### Deprecated
- PhpWord->getProtection(), get it from the settings instead PhpWord->getSettings()->getDocumentProtection();



v0.13.0 (31 July 2016)
-------------------
This release brings several improvements in `TemplateProcessor`, automatic output escaping feature for OOXML, ODF, HTML, and RTF (turned off, by default).
It also introduces constants for horizontal alignment options, and resolves some issues with PHP 7.
Manual installation feature has been dropped since the release. Please, use [Composer](https://getcomposer.org/) to install PHPWord.

### Added
- Introduced the `\PhpOffice\PhpWord\SimpleType\Jc` simple type. - @RomanSyroeshko
- Introduced the `\PhpOffice\PhpWord\SimpleType\JcTable` simple type. - @RomanSyroeshko
- Introduced writer for the "Paragraph Alignment" element (see `\PhpOffice\PhpWord\Writer\Word2007\Element\ParagraphAlignment`). - @RomanSyroeshko
- Introduced writer for the "Table Alignment" element (see `\PhpOffice\PhpWord\Writer\Word2007\Element\TableAlignment`). - @RomanSyroeshko
- Supported indexed arrays in arguments of `TemplateProcessor::setValue()`. - @RomanSyroeshko #618
- Introduced automatic output escaping for OOXML, ODF, HTML, and RTF. To turn the feature on use `phpword.ini` or `\PhpOffice\PhpWord\Settings`. - @RomanSyroeshko #483
- Supported processing of headers and footers in `TemplateProcessor::applyXslStyleSheet()`. - @RomanSyroeshko #335

### Changed
- Improved error message for the case when `autoload.php` is not found. - @RomanSyroeshko #371
- Renamed the `align` option of `NumberingLevel`, `Frame`, `Table`, and `Paragraph` styles into `alignment`. - @RomanSyroeshko
- Improved performance of `TemplateProcessor::setValue()`. - @kazitanvirahsan #614, #617
- Fixed some HTML tags not rendering any output (p, header & table) - #257, #324 - @twmobius and @garethellis

### Deprecated
- `getAlign` and `setAlign` methods of `NumberingLevel`, `Frame`, `Table`, and `Paragraph` styles.
Use the correspondent `getAlignment` and `setAlignment` methods instead. - @RomanSyroeshko
- `left`, `right`, and `justify` alignment options for paragraphs (now are mapped to `Jc::START`, `Jc::END`, and `Jc::BOTH`). - @RomanSyroeshko
- `left`, `right`, and `justify` alignment options for tables (now are mapped to `Jc::START`, `Jc::END`, and `Jc::CENTER`). - @RomanSyroeshko
- `TCPDF` due to its limited HTML support. Use `DomPDF` or `MPDF` writer instead. - @RomanSyroeshko #399

### Removed
- `\PhpOffice\PhpWord\Style\Alignment`. Style properties, which previously stored instances of this class, now deal with strings.
In each case set of available string values is defined by the correspondent simple type. - @RomanSyroeshko
- Manual installation support. Since the release we have dependencies on third party libraries,
so installation via ZIP-archive download is not an option anymore. To install PHPWord use [Composer](https://getcomposer.org/).
 We also removed `\PhpOffice\PhpWord\Autoloader`, because the latter change made it completely useless.
 Autoloaders provided by Composer are in use now (see `bootstrap.php`). - @RomanSyroeshko
- `\PhpOffice\PhpWord\Shared\Drawing` replaced by `\PhpOffice\Common\Drawing`. - @Progi1984 #658
- `\PhpOffice\PhpWord\Shared\Font`. - @Progi1984 #658
- `\PhpOffice\PhpWord\Shared\String` replaced by `\PhpOffice\Common\Text`. - @Progi1984 @RomanSyroeshko #658
- `\PhpOffice\PhpWord\Shared\XMLReader` replaced by `\PhpOffice\Common\XMLReader`. - @Progi1984 #658
- `\PhpOffice\PhpWord\Shared\XMLWriter` replaced by `\PhpOffice\Common\XMLWriter`. - @Progi1984 @RomanSyroeshko #658
- `AbstractContainer::addMemoryImage()`. Use `AbstractContainer::addImage()` instead.

### Fixed
- `Undefined property` error while reading MS-DOC documents. - @jaberu #610
- Corrupted OOXML template issue in case when its names is broken immediately after `$` sign.
That case wasn't taken into account in implementation of `TemplateProcessor::fixBrokenMacros()`. - @RomanSyroeshko @d-damien #548



v0.12.1 (30 August 2015)
-----------------------
Maintenance release. This release is focused primarily on `TemplateProcessor`.

### Changes
- Changed visibility of all private properties and methods of `TemplateProcessor` to `protected`. - @RomanSyroeshko #498
- Improved performance of `TemplateProcessor::setValue()`. - @RomanSyroeshko @nicoSWD #513

### Bugfixes
- Fixed issue with "Access denied" message while opening `Sample_07_TemplateCloneRow.docx` and `Sample_23_TemplateBlock.docx` result files on Windows platform. - @RomanSyroeshko @AshSat #532
- Fixed `PreserveText` element alignment in footer (see `Sample_12_HeaderFooter.php`). - @RomanSyroeshko @SSchwaiger #495



v0.12.0 (3 January 2015)
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



v0.11.1 (2 June 2014)
--------------------
This is an immediate bugfix release for HTML reader.

- HTML Reader: `<p>` and header tags puts no output - @canyildiz @ivanlanin #257



v0.11.0 (1 June 2014)
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



v0.10.1 (21 May 2014)
--------------------
This is a bugfix release for `php-zip` requirement in Composer.

- Change Composer requirements for php-zip from `require` to `suggest` - @bskrtich #246



v0.10.0 (4 May 2014)
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



v0.9.1 (27 Mar 2014)
-------------------
This is a bugfix release for PSR-4 compatibility.

- Fixed PSR-4 composer autoloader - @AntonTyutin



v0.9.0 (26 Mar 2014)
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



v0.8.1 (17 Mar 2014)
-------------------
This is a bugfix release for image detection functionality.

- Added fallback for computers that do not have exif_imagetype - @bskrtich, @gabrielbull



v0.8.0 (15 Mar 2014)
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



v0.7.0 (28 Jan 2014)
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
