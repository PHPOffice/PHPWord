<?php

//***********************************
//                                  *
//  This code is dedicated          *
//  to the morons of complexity     *
//  and the godesses of simplicity  *
//                                  *
//***********************************

//Include EASYTABLES in your project.

//Usage
//=====
//
//WordTableStyle(clear);
//WordTableText(clear);
//
//WordTableText(row);
//WordTableText('Country');
//WordTableText('Food');
//WordTableText('Rating');
//
//WordTableText(row);
//WordTableText('Italy');
//WordTableText('Pizza');
//WordTableText('★★★☆☆');
//
//WordTableText(row);
//WordTableText('China');
//WordTableText('Rice*');
//WordTableText('★★☆☆☆');

//WordTableText(row);
//WordTableText('Mexico');
//WordTableText('Tacos');
//WordTableText('★★★★☆');

//WordTableText(row);
//WordTableText('Japan');
//WordTableText('Sushi');
//WordTableText('★★★☆☆');
//
//PutWordTable();

//Override the defaults
//=====================
//WordTableStyle('widthDefault',4000);
//WordTableStyle('theme','box'); //none,grid,box,header,headerLeft
//WordTableStyle('size',15);
//WordTableStyle('fadeCue','*');
//WordTableStyle('shade',colorGreenLight);  //Red,Green,Blue - Light or Dark
//WordTableStyle('spaceAfter',0);
//WordTableStyle('vAlign','bottom');
//WordTableStyle('borderStyle','double');
//WordTableStyle('alignment','center');
//WordTableStyle('alignTable','left');
//WordTableStyle('abbreviate',true); //Used for abbrev...
//WordTableStyle('hyphenate',false);
//WordTableStyle('language','en'); //Used for hyphenation
//WordColumnWidth(3,3000);
//WordColumnAlign(2,'right');
//WordColumnBold(1,true);
//WordRowBold(4,true);

//Support
//===========
//Konrad
//Emil
//Martha
//Berta
//Richard
//Emil
//Gustav
//@ one of the big providers, but not the biggest provider.


define('clear',8);
define('left',24);
define('right',25);
define('center',26);
define('row',27);
define('remove',28);
define('colorBlue','#3944BC');
define('colorBlueDark','#0A1172');
define('colorBlueLight','#59788E');
define('colorRed','#D0312D');
define('colorRedDark','#990F02');
define('colorRedLight','#BC544b');
define('colorGreen','#D0312D');
define('colorGreenLight','#99EDC3');
define('colorGreenDark','#32612D');


function PutWordTable()
{
$Contents=WordTableText();
$Rows=count($Contents);
$Columns=count($Contents[0]);
WordTableStyle('numColumns',$Columns);
if($Rows==0) return;
if($Columns==0) return;
SetDefaultStyles();
WordTable(add);
for($r=1;$r<=$Rows;$r++) 
  {
  AddTableRow();
  for($c=1;$c<=$Columns;$c++)
    {
    SetWordCellStyle($Rows,$Columns,$r,$c);
    TableCell($r,$c);
    }
  }
}

function BorderSizeHeavy($SetSize=''){static $TheSize=4;if($SetSize=='') return $TheSize;$TheSize=$SetSize;}
function BorderSizeLight($SetSize=''){static $TheSize=2;if($SetSize=='') return $TheSize;$TheSize=$SetSize;}
function BorderColorHeavy($SetColor=''){static $TheColor='000000';if($SetColor=='') return $TheColor;$TheColor=$SetColor;}
function BorderColorLight($SetColor=''){static $TheColor='AAAAAA';if($SetColor=='') return $TheColor;$TheColor=$SetColor;}

function WordCellBorderHeavy($Place)
{
$Place=ucfirst($Place);
WordTableStyle('border'.$Place.'Size',BorderSizeHeavy());
WordTableStyle('border'.$Place.'Color',BorderColorHeavy());
}

function WordCellBorderLight($Place)
{
$Place=ucfirst($Place);
WordTableStyle('border'.$Place.'Size',BorderSizeLight());
WordTableStyle('border'.$Place.'Color',BorderColorLight());
}

function SetDefaultStyle($Style,$Value){if(WordTableStyle($Style)!='') return;WordTableStyle($Style,$Value);}

function SetDefaultStyles()
{
WordTableStyle('widthDefault',11000/(intval(WordTableStyle('numCol'))+1));
SetDefaultStyle('theme','header');//If not already set by the user.
SetDefaultStyle('size',14);
SetDefaultStyle('fadeCue','');
SetDefaultStyle('shade',colorBlueLight);
SetDefaultStyle('spaceAfter',0);
SetDefaultStyle('vAlign','center');
SetDefaultStyle('borderStyle','single');
SetDefaultStyle('alignment','left');
SetDefaultStyle('alignTable','center');
SetDefaultStyle('abbreviate',false);
SetDefaultStyle('hyphenate',true); 
WordTableStyle('cellMarginLeft',0);
WordTableStyle('cellMarginRight',0);
WordTableStyle('cellMarginTop',4);
WordTableStyle('cellMarginBottom',4);
WordTableStyle('cellSpacing',0);
WordTableStyle('colorFaded',ColorFaded(WordTableStyle('color')));
}

function SetWordCellStyle($Rows,$Columns,$r,$c)
{
WordTableStyle('bold',false);
WordTableStyle('bgColor','FFFFFF');
WordTableStyle('borderTopSize',remove);
WordTableStyle('borderTopColor',remove);
WordTableStyle('borderLeftSize',remove);
WordTableStyle('borderLeftColor',remove);
WordTableStyle('borderBottomSize',0);
WordTableStyle('borderRightSize',0);
WordTableStyle('borderBottomColor','FFFFFF');
WordTableStyle('borderRightColor','FFFFFF');
WordTableStyle('width',WordTableStyle('widthDefault'));
WordTableStyle('color','000000');

if(WordColumnBold($c)) WordTableStyle('bold',true);
if(WordRowBold($r)) WordTableStyle('bold',true);
if(WordColumnWidth($c)!='') WordTableStyle('width',WordColumnWidth($c));
if(WordColumnAlign($c)!='') WordTableStyle('alignment',WordColumnAlign($c));

if(WordTableStyle('theme')=='none') return;

if(WordTableStyle('theme')=='grid') 
  {
  if($r==1) WordCellBorderHeavy('top');
  WordCellBorderLight('bottom');
  if($r==$Rows) WordCellBorderHeavy('bottom');
  if($c==1) WordCellBorderHeavy('left');
  WordCellBorderLight('right');
  if($c==$Columns) WordCellBorderHeavy('right');
  return;
  }

if(WordTableStyle('theme')=='box') 
  {
  if($r==1) WordCellBorderHeavy('top');
  if($r==$Rows) WordCellBorderHeavy('bottom');
  if($c==1) WordCellBorderHeavy('left');
  if($c==$Columns) WordCellBorderHeavy('right');
  return;
  }

if(WordTableStyle('theme')=='header') 
  {
  WordCellBorderLight('bottom');
  if($r==$Rows) WordCellBorderHeavy('bottom');
  if($c==1) WordCellBorderHeavy('left');
  WordCellBorderLight('right');
  if($c==$Columns) WordCellBorderHeavy('right');
  if($r==1) 
    {
    WordCellBorderHeavy('top');
    WordCellBorderHeavy('bottom');
    WordTableStyle('bold',true);
    WordTableStyle('alignment','center');
    WordTableStyle('bgColor',WordTableStyle('shade'));
    if(ColorBrightness(WordTableStyle('shade'))<=128) WordTableStyle('color','FFFFFF');
    }
  return;
  }

if(WordTableStyle('theme')=='headerLeft') 
  {
  if($r==1) WordCellBorderHeavy('top');
  WordCellBorderLight('bottom');
  if($r==$Rows) WordCellBorderHeavy('bottom');
  WordCellBorderLight('right');
  if($c==$Columns) WordCellBorderHeavy('right');
  if($c==1) 
    {
    WordCellBorderHeavy('left');
    WordCellBorderHeavy('right');
    WordTableStyle('bold',true);
    WordTableStyle('bgColor',WordTableStyle('shade'));
    WordTableStyle('alignment','left');
    if(!ColorBrightness(WordTableStyle('shade'))<=128) WordTableStyle('color','FFFFFF');
    }
  return;
  }
}

function WordTable($Add=null)
{
global $section;
static $TheTable;
if($Add===null) return $TheTable;
$TableStyle=[];
$TableStyle['alignment']=WordTableStyle('alignTable');
$TableStyle['cellSpacing']=WordTableStyle('cellSpacing');
$TableStyle['cellMarginLeft']=WordTableStyle('cellMarginLeft');
$TableStyle['cellMarginRight']=WordTableStyle('cellMarginRight');
$TableStyle['cellMarginTop']=WordTableStyle('cellMarginTop');
$TableStyle['cellMarginBottom']=WordTableStyle('cellMarginBottom');
$TheTable=$section->addTable($TableStyle);
}

function AddTableRow(){WordTable()->addRow();}

function AddTableCell($Text,$FontStyle,$SpacingStyle)
{
if(WordTableStyle('alignment')=='center'){WordTable()->addCell(WordTableStyle('width'),WordTableStyle())->addText($Text,$FontStyle,$SpacingStyle);return;}
$Padding='_____';
$Invisible['color']='FFFFFF';
$Invisible['size']=20/(WordTableStyle('numColumns')+2);
$Cell=WordTable()->addCell(WordTableStyle('width'),WordTableStyle());
$TextRun=$Cell->addTextRun($SpacingStyle);
if(WordTableStyle('alignment')=='left') 
  {
  if(WordTableStyle('numColumns')>3) $Invisible['size']=0;
  $TextRun->addText($Padding,$Invisible);$TextRun->addText($Text,$FontStyle);
  return;
  }
$TextRun->addText($Text,$FontStyle);
$TextRun->addText($Padding,$Invisible);
}

function TableCell($row,$column)
{
$FontStyle=[];
$TextStyle=[];
$FontStyle['bold']=WordTableStyle('bold');
$FontStyle['size']=WordTableStyle('size');
$FontStyle['color']=WordTableStyle('color');
$SpacingStyle['alignment']=WordTableStyle('alignment');
$SpacingStyle['spaceAfter']=WordTableStyle('spaceAfter');
$Text=WordTableText();
$Text=$Text[$row-1][$column-1];
if(mb_strpos($Text,WordTableStyle('fadeCue'))!==false) {$FontStyle['color']=WordTableStyle('colorFaded');$Text=str_replace(WordTableStyle('fadeCue'),'',$Text);}
$LetterThreshold=Round(WordTableStyle('width')/130);  //Width 2000->Words longer than 15 letters will be hyphenated
if(WordTableStyle('abbreviate')) $Text=Abbreviated($Text,$LetterThreshold); //Width 2000, abbreviates to 10 letters.  
if(!WordTableStyle('abbreviate')) if(WordTableStyle('hyphenate')) $Text=Hyphenated($Text,$LetterThreshold,WordTableStyle('language'));
AddTableCell($Text,$FontStyle,$SpacingStyle);
}

function WordTableText($text=null)
{
static $TextArray=[];
static $Row=0;
static $Col=0;
if($text===null) return $TextArray;//Return the array. 
if($text===row) if(empty($TextArray))return; //Start new row on on empty array. No go
if($text===row) {$Row++;$Col=0;return;}//Start new row
if(is_array($text)) 
  {
  $text=array_filter($text,function($line){return trim($line)!=='';});
  $text=implode("\n",$text);
  }
$TextArray[$Row][$Col]=$text;// Add text at current position
$Col++;
if($text!==clear) return;//Text was added. No need to return anything
$Row=0;
$Col=0;
$k=$TextArray;
$TextArray=null;
unset($TextArray);
return $k;//No argument=reset 
}

function WordTableStyle($ThisStyle='',$ThisValue='')
{
static $StyleArray=[];
if($ThisStyle==clear) {$StyleArray=null;unset($StyleArray);return;}
if($ThisStyle==='') return $StyleArray;
if($ThisValue===remove) {unset($StyleArray[$ThisStyle]);return;}
if($ThisValue==='') {return isset($StyleArray[$ThisStyle])?$StyleArray[$ThisStyle]:'';}
$StyleArray[$ThisStyle]=$ThisValue;
}

function WordColumnWidth($Column='',$Width='')
{
static $WidthArray=[];
if($Column=='') {$WidthArray=null;unset($WidthArray);return;}
if($Column==clear) {$WidthArray=null;unset($WidthArray);return;}
if($Width=='') return isset($WidthArray[$Column])?$WidthArray[$Column]:'';
$WidthArray[$Column]=$Width;
}

function WordColumnAlign($Column='',$Alignment='')
{
static $AlignArray=[];
if($Column=='') {$AlignArray=null;unset($AlignArray);return;}
if($Column==clear) {$AlignArray=null;unset($AlignArray);return;}
if($Alignment=='') return isset($AlignArray[$Column])?$AlignArray[$Column]:'left';
$AlignArray[$Column]=$Alignment;
}

function WordColumnBold($Column='',$IsBold='')
{
static $BoldArray=[];
if($Column=='') {$BoldArray=null;unset($BoldArray);return;}
if($Column==clear) {$BoldArray=null;unset($BoldArray);return;}
if($IsBold=='') return isset($BoldArray[$Column]);
$BoldArray[$Column]=$IsBold;
}

function WordRowBold($Row='',$IsBold='')
{
static $BoldArray=[];
if($Row=='') {$BoldArray=null;unset($BoldArray);return;}
if($Row==clear) {$BoldArray=null;unset($BoldArray);return;}
if($IsBold=='') return isset($BoldArray[$Row]);
$BoldArray[$Row]=$IsBold;
}

function Abbreviated($Word,$Theshold=10) 
{
if(mb_strpos($Word,'-')!==false) return $Word;
if(mb_strpos($Word,' ')!==false) return $Word;
if(mb_strlen($Word)<=$Theshold) return $Word;
if(preg_match('/^\d/',$Word)) return $Word; //Starts with a digit — looks like a number
$Word=mb_substr($Word,0,$Theshold-1).'...';
echo "Abbreviated $Word<br>";
return $Word;
}

function ConsonantPairs($Language)
{
if($Language=='de') return ['br','bl','ck','dr','ft','fr','gl','gr','kr','kl','nb','ng','nd','nk','nt','mp','pl','pr','pf','pz','rm','rp','rs','rt','sk','sl','st','sp','tg','tm','tr','tz'];
return ['ck','ct','mp','nd','nt','lt','rt','st','sp','sk','pl','pr','gr','bl','br','tr','cl','cr','dr','fr'];
}

function Hyphenated($Word,$Theshold=10,$Language='en') 
{
$Middle = (int) floor($Length / 2);
$vowels = '/[aeiouy]/i';
$Pairs=ConsonantPairs($Language);
$MaxOffset = 2;
  
if(preg_match('/^\d/',$Word)) return $Word; //Starts with a digit — looks like a number
if(mb_strpos($Word, '-')!==false) return $Word;
if(mb_strpos($Word, ' ')!==false) return $Word;
    
$Length=mb_strlen($Word);
if($Length<$Theshold) return $Word;
if(mb_strpos($Word,'name')!==false) return str_replace('name','-name',$Word);
    
// Check for consonant pairs
for ($Offset = 0; $Offset <= $MaxOffset; $Offset++) 
  foreach (['back' => $Middle - $Offset - 1, 'forward' => $Middle + $Offset - 1] as $Pos) {
    $Pair = mb_substr($Word, $Pos, 2);
    if (in_array($Pair, $Pairs)) return mb_substr($Word, 0, $Pos + 1) . '-' . mb_substr($Word, $Pos + 1);
    }


// Check for identical letters
for ($Offset = 0; $Offset <= $MaxOffset; $Offset++) 
 foreach (['back' => $Middle - $Offset - 1, 'forward' => $Middle + $Offset - 1] as $Pos) {
   $Char = mb_substr($Word, $Pos, 1);
   $Next = mb_substr($Word, $Pos + 1, 1);
   if ($Char === $Next) return mb_substr($Word, 0, $Pos + 1) . '-' . mb_substr($Word, $Pos + 1);
 }
  
// Check for vowels near middle
for ($Offset = 0; $Offset < $Middle - 1; $Offset++) 
 foreach (['back' => $Middle - $Offset, 'forward' => $Middle + $Offset] as $Pos) {
   $Char = mb_substr($Word, $Pos, 1);
   if (preg_match($vowels, $Char)) return mb_substr($Word, 0, $Pos + 1) . '-' . mb_substr($Word, $Pos + 1);
  }
    
// Fallback split at middle
return mb_substr($Word, 0, $Middle) . '-' . mb_substr($Word, $Middle);
}
