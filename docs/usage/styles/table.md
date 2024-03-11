# Table

Available Table style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\JcTable`` and ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in *twip*.
- ``cellMargin(Top|Right|Bottom|Left)``. Cell margin in *twip*.
- ``indent``. Table indent from leading margin. Must be an instance of ``\PhpOffice\PhpWord\ComplexType\TblWidth``.
- ``width``. Table width in Fiftieths of a Percent or Twentieths of a Point.
- ``unit``. The unit to use for the width. One of ``\PhpOffice\PhpWord\SimpleType\TblWidth``. Defaults to *auto*.
- ``layout``. Table layout, either *fixed* or *autofit*  See ``\PhpOffice\PhpWord\Style\Table`` for constants.
- ``cellSpacing`` Cell spacing in *twip*
- ``position`` Floating Table Positioning, see below for options
- ``bidiVisual`` Present table as Right-To-Left

Floating Table Positioning options:

- ``leftFromText`` Distance From Left of Table to Text in *twip*
- ``rightFromText`` Distance From Right of Table to Text in *twip*
- ``topFromText`` Distance From Top of Table to Text in *twip*
- ``bottomFromText`` Distance From Top of Table to Text in *twip*
- ``vertAnchor`` Table Vertical Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::VANCHOR_*``
- ``horzAnchor`` Table Horizontal Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::HANCHOR_*``
- ``tblpXSpec`` Relative Horizontal Alignment From Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::XALIGN_*``
- ``tblpX`` Absolute Horizontal Distance From Anchorin *twip*
- ``tblpYSpec`` Relative Vertical Alignment From Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::YALIGN_*``
- ``tblpY`` Absolute Vertical Distance From Anchorin *twip*

Available Row style options:

- ``cantSplit``. Table row cannot break across pages, *true* or *false*.
- ``exactHeight``. Row height is exact or at least.
- ``tblHeader``. Repeat table row on every new page, *true* or *false*.

Available Cell style options:

- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in *twip*.
- ``border(Top|Right|Bottom|Left)Style``. Border style. You can use constants from ``\PhpOffice\PhpWord\SimpleType\Border``
- ``gridSpan``. Number of columns spanned.
- ``textDirection(btLr|tbRl)``. Direction of text.
   You can use constants ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR`` and ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL``
- ``valign``. Vertical alignment, *top*, *center*, *both*, *bottom*.
- ``vMerge``. *restart* or *continue*.
- ``width``. Cell width in *twip*.