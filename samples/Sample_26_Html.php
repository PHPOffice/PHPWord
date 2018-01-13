<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$html = '<h1>Adding element via HTML</h1>';
$html .= '<p>Some well formed HTML snippet needs to be used</p>';
$html .= '<p>With for example <strong>some<sup>1</sup> <em>inline</em> formatting</strong><sub>1</sub></p>';

$html .= '<p style="margin-top: 240pt;">Unordered (bulleted) list:</p>';
$html .= '<ul><li>Item 1</li><li>Item 2</li><ul><li>Item 2.1</li><li>Item 2.1</li></ul></ul>';

$html .= '<p style="margin-top: 240pt;">Ordered (numbered) list:</p>';
$html .= '<ol>
                <li><p style="font-weight: bold;">List 1 item 1</p></li>
                <li>List 1 item 2</li>
                <ol>
                    <li>sub list 1</li>
                    <li>sub list 2</li>
                </ol>
                <li>List 1 item 3</li>
            </ol>
            <p style="margin-top: 15px;">A second list, numbering should restart</p>
            <ol>
                <li>List 2 item 1</li>
                <li>List 2 item 2</li>
                <ol>
                    <li>sub list 1</li>
                    <li>sub list 2</li>
                </ol>
                <li>List 2 item 3</li>
                <ol>
                    <li>sub list 1, restarts with a</li>
                    <li>sub list 2</li>
                </ol>
            </ol>';

$html .= '<p style="margin-top: 240pt;">List with formatted content:</p>';
$html .= '<ul>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 16px;">big list item1</span>
                    </span>
                </li>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 10px; font-weight: bold;">list item2 in bold</span>
                    </span>
                </li>
            </ul>';

$html .= '<p style="margin-top: 240pt;">A table with formatting:</p>';
$html .= '<table align="center" style="width: 50%; border: 6px #0000FF double;">
                <thead>
                    <tr style="background-color: #FF0000; text-align: center; color: #FFFFFF; font-weight: bold; ">
                        <th style="width: 50pt">header a</th>
                        <th style="width: 50">header          b</th>
                        <th style="background-color: #FFFF00; border-width: 12px"><span style="background-color: #00FF00;">header c</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted;">1</td><td colspan="2">2</td></tr>
                    <tr><td>4</td><td>5</td><td>6</td></tr>
                </tbody>
            </table>';

\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
