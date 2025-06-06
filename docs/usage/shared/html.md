# HTML

## HTML
You can generate a Word file from html.

``` php
<?php

  $phpWordInstance    = new PhpWord();
  $phpSectionInstance = $phpWordInstance->addSection([
      'orientation'  => 'landscape',
      'marginLeft'   => (int)round(20 * 56.6929133858),
      'marginRight'  => (int)round(20 * 56.6929133858),
      'marginTop'    => (int)round(20 * 56.6929133858),
      'marginBottom' => (int)round(20 * 56.6929133858),
  ]);

  $html = '<!-- Any html. Table for example -->
    <table>
        <tr>
            <td style="border-left:solid blue 2px;">border-left:solid blue 2px;</td>
            <td style="border-right:solid 2px red;">border-right:solid 2px red;</td>
        </tr>
        <tr>        
            <td>
                <img src="https://any.domain/path-to-img/name.jpg" />
            </td>
            <td></td>
        </tr>
  </table>';

  $fullHTML = false;
  $preserveWhiteSpace = true;

  $options = [];
  $options['IMG_SRC_SEARCH'] = 'path-to-img/name.jpg';
  $options['IMG_SRC_REPLACE'] = 'another-path-to-img/new-name.jpg';

  Html::addHtml($phpSectionInstance, $html, $fullHTML, $preserveWhiteSpace, $options);
    
  $fqName = new PhpOffice\PhpWord\Writer\Word2007($phpWordInstance);
  $fqName->save('./test.docx');
```

$html - $html param must have root node such as "html" if it is full html;

$fullHTML - If $html is not full html, it may not have a root element. In  this case $fullHTML should be false.

$preserveWhiteSpace - Do not remove redundant white space. Default to true.

$options - which could be applied when such an element occurs in the parseNode function.