# Font

Available Font style options:

- ``allCaps``. All caps, *true* or *false*.
- ``bgColor``. Font background color, e.g. *FF0000*.
- ``bold``. Bold, *true* or *false*.
- ``color``. Font color, e.g. *FF0000*.
- ``doubleStrikethrough``. Double strikethrough, *true* or *false*.
- ``fgColor``. Font highlight color, e.g. *yellow*, *green*, *blue*.
   See ``\PhpOffice\PhpWord\Style\Font::FGCOLOR_...`` class constants for possible values
- ``hint``. Font content type, *default*, *eastAsia*, or *cs*.
- ``italic``. Italic, *true* or *false*.
- ``name``. Font name, e.g. *Arial*.
- ``rtl``. Right to Left language, *true* or *false*.
- ``size``. Font size, e.g. *20*, *22*.
- ``smallCaps``. Small caps, *true* or *false*.
- ``strikethrough``. Strikethrough, *true* or *false*.
- ``subScript``. Subscript, *true* or *false*.
- ``superScript``. Superscript, *true* or *false*.
- ``underline``. Underline, *single*, *dash*, *dotted*, etc.
   See ``\PhpOffice\PhpWord\Style\Font::UNDERLINE_...`` class constants for possible values
- ``lang``. Language, either a language code like *en-US*, *fr-BE*, etc. or an object (or as an array) if you need to set eastAsian or bidirectional languages
   See ``\PhpOffice\PhpWord\Style\Language`` class for some language codes.
- ``position``. The text position, raised or lowered, in half points
- ``hidden``. Hidden text, *true* or *false*.
- ``whiteSpace``. How white space is handled when generating html/pdf. Possible values are *pre-wrap* and *normal* (other css values for white space are accepted, but are not expected to be useful).
- ``fallbackFont``. Fallback generic font for html/pdf. Possible values are *sans-serif*, *serif*, and *monospace* (other css values for generic fonts are accepted).
