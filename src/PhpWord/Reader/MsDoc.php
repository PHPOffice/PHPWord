<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\OLERead;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Element\Section;

/**
 * Reader for Word97
 *
 * @since 0.10.0
 */
class MsDoc extends AbstractReader implements ReaderInterface
{
    /**
     * PhpWord object
     *
     * @var PhpWord
     */
    private $phpWord;

    /**
     * WordDocument Stream
     *
     * @var
     */
    private $dataWorkDocument;
    /**
     * 1Table Stream
     *
     * @var
     */
    private $data1Table;
    /**
     * Data Stream
     *
     * @var
     */
    private $dataData;
    /**
     * Object Pool Stream
     *
     * @var
     */
    private $dataObjectPool;
    /**
     * @var integer
     */
    private $pos;

    /**
     * Loads PhpWord from file
     *
     * @param string $filename
     * @return PhpWord
     */
    public function load($filename)
    {
        $this->phpWord = new PhpWord();

        $this->loadOLE($filename);

        $this->readFib($this->dataWorkDocument);
        $this->readFibContent();
        /*$this->read1Table($this->data1Table);
        $this->readData($this->dataData);
        $this->readObjectPool($this->dataObjectPool);*/

        return $this->phpWord;
    }

    /**
     * Load an OLE Document
     * @param string $filename
     */
    private function loadOLE($filename)
    {
        // OLE reader
        $ole = new OLERead();
        $ole->read($filename);

        // Get WorkDocument stream
        $this->dataWorkDocument = $ole->getStream($ole->wrkdocument);
        // Get 1Table stream
        $this->data1Table = $ole->getStream($ole->wrk1Table);
        // Get Data stream
        $this->dataData = $ole->getStream($ole->wrkData);
        // Get Data stream
        $this->dataObjectPool = $ole->getStream($ole->wrkObjectPool);
        // Get Summary Information data
        $this->_summaryInformation = $ole->getStream($ole->summaryInformation);
        // Get Document Summary Information data
        $this->_documentSummaryInformation = $ole->getStream($ole->documentSummaryInformation);
    }

    /**
     *
     * @link http://msdn.microsoft.com/en-us/library/dd949344%28v=office.12%29.aspx
     * @link https://igor.io/2012/09/24/binary-parsing.html
     */
    private function readFib($data)
    {
        $length = 0;
        print_r('============ readFib'.PHP_EOL);
        print_r('============ length : '.strlen($data).PHP_EOL);
        print_r('======================== FibBase'.PHP_EOL);
        //----- FibBase
        // wIdent
        $wIdent = self::_GetInt2d($data, $length);
        $length += 2;
        print_r('$wIdent : '.$wIdent.'#'.dechex($wIdent).PHP_EOL);
        // nFib
        $nFib = self::_GetInt2d($data, $length);
        print_r('$nFib : '.$nFib.'#'.dechex($nFib).PHP_EOL);
        $length += 2;
        // unused
        $length += 2;
        // lid : Language Identifier
        $lid = self::_GetInt2d($data, $length);
        $length += 2;
        // pnNext
        $pnNext = self::_GetInt2d($data, $length);
        $length += 2;

        $mem = self::_GetInt2d($data, $length);
        $fDot = ($mem >> 15) & 1;
        $fGlsy = ($mem >> 14) & 1;
        $fComplex = ($mem >> 13) & 1;
        $fHasPic = ($mem >> 12) & 1;
        $cQuickSaves = ($mem >> 8) & bindec('1111');
        $fEncrypted = ($mem >> 7) & 1;
        $fWhichTblStm = ($mem >> 6) & 1;
        print_r('$fWhichTblStm : '.$fWhichTblStm.'#'.dechex($fWhichTblStm).PHP_EOL);
        $fReadOnlyRecommended = ($mem >> 5) & 1;
        $fWriteReservation = ($mem >> 4) & 1;
        $fExtChar = ($mem >> 3) & 1;
        $fLoadOverride = ($mem >> 2) & 1;
        $fFarEast = ($mem >> 1) & 1;
        $fObfuscated = ($mem >> 0) & 1;
        $length += 2;
        // nFibBack
        $nFibBack = self::_GetInt2d($data, $length);
        $length += 2;
        // lKey
        $lKey = self::_GetInt4d($data, $length);
        $length += 4;
        // envr
        $envr = self::_GetInt1d($data, $length);
        $length += 1;

        $mem = self::_GetInt1d($data, $length);
        $fMac = ($mem >> 7) & 1;
        $fEmptySpecial = ($mem >> 6) & 1;
        $fLoadOverridePage = ($mem >> 5) & 1;
        $reserved1 = ($mem >> 4) & 1;
        $reserved2 = ($mem >> 3) & 1;
        $fSpare0 = ($mem >> 0) & bindec('111');
        $length += 1;

        $reserved3 = self::_GetInt2d($data, $length);
        $length += 2;
        $reserved4 = self::_GetInt2d($data, $length);
        $length += 2;
        $reserved5 = self::_GetInt4d($data, $length);
        $length += 4;
        $reserved6 = self::_GetInt4d($data, $length);
        $length += 4;

        //----- csw
        print_r('======================== csw'.PHP_EOL);
        $csw = self::_GetInt2d($data, $length);
        $length += 2;
        print_r('$csw : '.$csw.'#'.dechex($csw).PHP_EOL);

        //----- fibRgW
        print_r('======================== fibRgW'.PHP_EOL);
        $fibRgW_reserved1 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved2 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved3 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved4 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved5 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved6 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved7 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved8 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved9 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved10 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved11 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved12 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_reserved13 = self::_GetInt2d($data, $length);
        $length += 2;
        $fibRgW_lidFE = self::_GetInt2d($data, $length);
        $length += 2;

        //----- cslw
        print_r('======================== cslw'.PHP_EOL);
        $cslw = self::_GetInt2d($data, $length);
        $length += 2;
        print_r('$cslw : '.$cslw.'#'.dechex($cslw).PHP_EOL);

        //----- fibRgLw
        print_r('======================== fibRgLw'.PHP_EOL);
        $fibRgLw_cbMac = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved1 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved2 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpText = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpFtn = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpHdd = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved3 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpAtn = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpEdn = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpTxbx = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_ccpHdrTxbx = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved4 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved5 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved6 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved7 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved8 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved9 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved10 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved11 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved12 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved13 = self::_GetInt4d($data, $length);
        $length += 4;
        $fibRgLw_reserved14 = self::_GetInt4d($data, $length);
        $length += 4;

        //----- cbRgFcLcb
        print_r('======================== cbRgFcLcb'.PHP_EOL);
        $cbRgFcLcb = self::_GetInt2d($data, $length);
        print_r('$cbRgFcLcb : '.$cbRgFcLcb.'#'.dechex($cbRgFcLcb).PHP_EOL);
        $length += 2;
        //----- fibRgFcLcbBlob
        print_r('======================== fibRgFcLcbBlob'.PHP_EOL);
        switch ($cbRgFcLcb) {
            case 0x005D:
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_97);
                break;
            case 0x006C:
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_97);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2000);
                break;
            case 0x0088:
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_97);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2000);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2002);
                break;
            case 0x00A4 :
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_97);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2000);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2002);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2003);
                break;
            case 0x00B7:
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_97);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2000);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2002);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2003);
                $length = $this->readBlockFibRgFcLcb($data, $length, self::VERSION_2007);
                break;
        }
        //print_r($this->arrayFib);
        //----- cswNew
        print_r('======================== cswNew'.PHP_EOL);
        $cswNew = self::_GetInt2d($data, $length);
        $length += 2;
        print_r('$cswNew : '.$cswNew.'#'.dechex($cswNew).PHP_EOL);

        if($cswNew != 0){
            // fibRgCswNew
            print_r('======================== fibRgCswNew'.PHP_EOL);
        }

        print_r('======================== length : '.$length.'#'.dechex($length).PHP_EOL);
        return $length;
    }

    const VERSION_97 = '97';
    const VERSION_2000 = '2000';
    const VERSION_2002 = '2002';
    const VERSION_2003 = '2003';
    const VERSION_2007 = '2007';

    /**
     * @var array
     */
    private $arrayFib = array();

    private function readBlockFibRgFcLcb($data, $length, $version)
    {
        if($version == self::VERSION_97){
            $this->arrayFib['fcStshfOrig'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbStshfOrig'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcStshf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbStshf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcffndRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcffndRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcffndTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcffndTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfandRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfandRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfandTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfandTxt '] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfSed'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfSed'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcPad'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcPad'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfPhe'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfPhe'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfGlsy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfGlsy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfGlsy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfGlsy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfHdd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfHdd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBteChpx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBteChpx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBtePapx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBtePapx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfSea'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfSea'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfFfn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfFfn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldMom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldMom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldHdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldHdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldAtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldAtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldMcr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldMcr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmk'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmk'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcCmds'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbCmds'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfMcr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfMcr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPrDrvr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPrDrvr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPrEnvPort'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPrEnvPort'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPrEnvLand'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPrEnvLand'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcWss'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbWss'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcDop'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbDop'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfAssoc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfAssoc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcClx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbClx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfPgdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfPgdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAutosaveSource'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAutosaveSource'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcGrpXstAtnOwners'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbGrpXstAtnOwners'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfAtnBkmk'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfAtnBkmk'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused3'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused3'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcSpaMom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcSpaMom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcSpaHdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcSpaHdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfAtnBkf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfAtnBkf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfAtnBkl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfAtnBkl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPms'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPms'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcFormFldSttbs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbFormFldSttbs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfendRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfendRef'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfendTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfendTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused4'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused4'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcDggInfo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbDggInfo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfRMark'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfRMark'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfCaption'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfCaption'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfAutoCaption'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfAutoCaption'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfWkb'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfWkb'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfSpl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfSpl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcftxbxTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcftxbxTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfFldTxbx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfFldTxbx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfHdrtxbxTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfHdrtxbxTxt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcffldHdrTxbx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcffldHdrTxbx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcStwUser'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbStwUser'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbTtmbd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbTtmbd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcCookieData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbCookieData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdMotherOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdMotherOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdMotherOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdMotherOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdFtnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdFtnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdFtnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdFtnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdEdnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdEdnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdEdnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdEdnOldOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfIntlFld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfIntlFld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcRouteSlip'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbRouteSlip'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbSavedBy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbSavedBy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbFnm'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbFnm'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlfLst'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlfLst'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlfLfo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlfLfo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfTxbxBkd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfTxbxBkd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfTxbxHdrBkd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfTxbxHdrBkd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcDocUndoWord9'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbDocUndoWord9'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcRgbUse'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbRgbUse'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUsp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUsp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUskf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUskf'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcupcRgbUse'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcupcRgbUse'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcupcUsp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcupcUsp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbGlsyStyle'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbGlsyStyle'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlgosl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlgosl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcocx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcocx'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBteLvc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBteLvc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['dwLowDateTime'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['dwHighDateTime'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfLvcPre10'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfLvcPre10'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfAsumy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfAsumy'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfGram'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfGram'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbListNames'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbListNames'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfUssr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfUssr'] = self::_GetInt4d($data, $length);
            $length += 4;
        }
        if($version == self::VERSION_2000){
            $this->arrayFib['fcPlcfTch'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfTch'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcRmdThreading'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbRmdThreading'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcMid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbMid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbRgtplc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbRgtplc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcMsoEnvelope'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbMsoEnvelope'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfLad'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfLad'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcRgDofr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbRgDofr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcosl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcosl'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfCookieOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfCookieOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdMotherOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdMotherOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdMotherOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdMotherOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdFtnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdFtnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdFtnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdFtnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdEdnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdEdnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdEdnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdEdnOld'] = self::_GetInt4d($data, $length);
            $length += 4;
        }
        if($version == self::VERSION_2002){
            $this->arrayFib['fcUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfPgp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfPgp'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfuim'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfuim'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlfguidUim'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlfguidUim'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAtrdExtra'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAtrdExtra'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlrsid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlrsid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfcookie'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfcookie'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklFactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcFactoidData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbFactoidData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcDocUndo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbDocUndo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklFcc'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfbkmkBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfbkmkBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfbkfBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfbkfBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfbklBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfbklBPRepairs'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPmsNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPmsNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcODSO'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbODSO'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiOldXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiOldXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiNewXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiNewXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiMixedXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiMixedXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcffactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcffactoid'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcOldXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcOldXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcNewXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcNewXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcMixedXP'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcMixedXP'] = self::_GetInt4d($data, $length);
            $length += 4;
        }
        if($version == self::VERSION_2003){
            $this->arrayFib['fcHplxsdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbHplxsdr'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklSdt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcCustomXForm'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbCustomXForm'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklProt'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbProtUser'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbProtUser'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiOldInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiOldInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfpmiNewInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfpmiNewInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcOld'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcOldInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcOldInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcNew'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcflvcNewInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcflvcNewInline'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAfdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAfdMother'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAfdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAfdFtn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPgdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPgdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcBkdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbBkdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAfdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAfdEdn'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcAfd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbAfd'] = self::_GetInt4d($data, $length);
            $length += 4;
        }
        if($version == self::VERSION_2007){
            $this->arrayFib['fcPlcfmthd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfmthd'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklMoveFrom'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklMoveTo'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused1'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused2'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused3'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused3'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcSttbfBkmkArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbSttbfBkmkArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBkfArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBkfArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcPlcfBklArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbPlcfBklArto'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcArtoData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbArtoData'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused4'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused4'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused5'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused5'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcUnused6'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbUnused6'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcOssTheme'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbOssTheme'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['fcColorSchemeMapping'] = self::_GetInt4d($data, $length);
            $length += 4;
            $this->arrayFib['lcbColorSchemeMapping'] = self::_GetInt4d($data, $length);
            $length += 4;
        }
        return $length;
    }

    private function readFibContent()
    {
        #$this->readRecordSTSH();
        $this->readRecordPlcfSed();
    }
    private function readRecordSTSH()
    {
        print_r('============ readRecordSTSH'.PHP_EOL);
        // Table Stream
        // fcStshf (4 bytes): An unsigned integer that specifies an offset in the Table Stream. An STSH that specifies the style sheet for this document begins at this offset.
        // lcbStshf (4 bytes): An unsigned integer that specifies the size, in bytes, of the STSH that begins at offset fcStshf in the Table Stream. This MUST be a nonzero value.
        /*[fcStshf] => 0
        [lcbStshf] => 1060*/

        $posMem = $this->arrayFib['fcStshf'];

        // RECORD "STSH"

        // lpstshi (variable): An LPStshi that specifies information about the stylesheet.
        // - LPStshi
        // - LPStshi : cbStshi
        $cbStshi = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        print_r('$cbStshi :'.$cbStshi.PHP_EOL);

        // - LPStshi : stshi
        // - LPStshi : stshi : stshif (18o)
        // - LPStshi : stshi : stshif : cstd
        $cstd = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : cbSTDBaseInFile
        $cbSTDBaseInFile = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : fStdStylenamesWritten (1 bit)
        // - LPStshi : stshi : stshif : fReserved (15 bits)
        $fStdStylenamesWritten = self::_GetInt1d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : stiMaxWhenSaved
        $stiMaxWhenSaved = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        print_r('$stiMaxWhenSaved :'.$stiMaxWhenSaved.PHP_EOL);
        // - LPStshi : stshi : stshif : istdMaxFixedWhenSaved
        $istdMaxFixedWhenSaved = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : nVerBuiltInNamesWhenSaved
        $nVerBuiltInNamesWhenSaved = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : ftcAsci
        $ftcAsci = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : ftcFE
        $ftcFE = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : stshif : ftcOther
        $ftcOther = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;

        // - LPStshi : stshi : ftcBi (2o)
        $ftcBi = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        // - LPStshi : stshi : StshiLsd
        // - LPStshi : stshi : StshiLsd : cbLSD
        $cbLSD = self::_GetInt2d($this->data1Table, $posMem);
        $posMem += 2;
        print_r('$cbLSD :'.$cbLSD.PHP_EOL);


        // - LPStshi : stshi : StshiLsd : mpstiilsd
        // - LPStshi : stshi : StshiB

        // rglpstd (variable): An array of LPStd that specifies the style definitions.
    }
    private function readRecordPlcfSed(){
        // fcPlcfSed (4 bytes): An unsigned integer that specifies an offset in the Table Stream. A PlcfSed begins at this offset and specifies the locations of property lists for each section in the Main Document. If lcbPlcfSed is zero, fcPlcfSed is undefined and MUST be ignored.
        // lcbPlcfSed (4 bytes): An unsigned integer that specifies the size, in bytes, of the PlcfSed that begins at offset fcPlcfSed in the Table Stream.
        print_r('============ readRecordPlcfSed'.PHP_EOL);

        $posMem = $this->arrayFib['fcPlcfSed'];
        // PlcfSed
        // PlcfSed : aCP
        $aCP = array();
        $aCP[0] = self::_GetInt4d($this->data1Table, $posMem);
        $posMem += 4;
        $aCP[1] = self::_GetInt4d($this->data1Table, $posMem);
        $posMem += 4;

        print_r('$aCP :'.PHP_EOL);
        print_r($aCP);

        // PlcfSed : aSed
        $numSed = ($this->arrayFib['lcbPlcfSed'] - 4) / 12;

        $aSed = array();
        for($iInc = 1 ; $iInc < $numSed ; ++$iInc){
            // ignored
            $posMem += 2;
            // A signed integer value that specifies the position in the WordDocument Stream at which a Sepx structure is located.
            $aSed[$iInc] = self::_GetInt4d($this->data1Table, $posMem);
            $posMem += 4;
            // ignored
            $posMem += 2;
            // ignored
            $posMem += 4;
        }

        // page 541 pour la lecture des informations sur la section
    }


    private function read1Table($data)
    {
        /*
        offset[0] = offsetClx + 1;
        int lcb = stream.getInteger(offset);

        int countPcd = (lcb - 4)/12;
        int countCp = (lcb - countPcd*8)/4;
        int offsetPlcpcd = offsetClx + 5;

        for(int i=0;i<countPcd;i++)
        {
            int offsetPcd = offsetPlcpcd + countCp*4 + i*8;

            offset[0] = offsetPcd + 2;
            int start = stream.getInteger(offset);
            int fc = start >> 30;
            start = (start << 2) >> 2;

            offset[0] = offsetPlcpcd + i*4;
            int cpPre = stream.getInteger(offset);
            int cpNext = stream.getInteger(offset);
            int length = cpNext - cpPre -1;
            if(fc == 0)
            {
                length *= 2;
            }
            else
            {
                start = start/2;
            }

            start += 512;
            bytesToString(ogiBytes, content, start, length, fc);

            System.out.println(start +", "+ length);
        }*/

        print_r('============ read1Table'.PHP_EOL);
        $clxPosition = $this->arrayFib['fcClx'];

        $text = self::_GetInt1d($data, $clxPosition);
        print_r('$text : '.$text.PHP_EOL);
        $lcb_piece_table = self::_GetInt4d($data, $clxPosition + 1);
        $piece_table = $clxPosition + 5;
        $piece_count = ($lcb_piece_table - 4) / 12;
        print_r('$piece_count : '.$piece_count.PHP_EOL);


        $countCp = ($lcb_piece_table - $piece_count*8)/4;
        print_r('$countCp : '.$countCp.PHP_EOL);
        for($i=0 ; $i < $piece_count ; $i++) {

            $piece_start = self::_GetInt4d($data,  $piece_table + ($i * 4));
            $piece_end = self::_GetInt4d($data, $piece_table + (($i + 1) * 4));
            $piece_descriptor = $piece_table + (($piece_count + 1) * 4) + ($i * 8);
            $fc = self::_GetInt4d ($data, $piece_descriptor + 2);
            $is_ansi = ($fc & 0x40000000) == 0x40000000;
            if (!$is_ansi) {
                $fc = ($fc & 0xBFFFFFFF);
            } else {
                $fc = ($fc & 0xBFFFFFFF) >> 1;
            }
            $piece_size = $piece_end - $piece_start;
            print_r('$piece_size : '.$piece_size.PHP_EOL);
            if (!$is_ansi) {
                $piece_size *= 2;
            }
            print_r('$piece_size : '.$piece_size.PHP_EOL);
            if ($piece_size >= 1) {
                $fc+=512;
                print_r(chr(self::_GetInt2d($data, $fc + 2)));
                print_r(chr(self::_GetInt2d($data, $fc + 4)));
                print_r(chr(self::_GetInt2d($data, $fc + 6)));
                print_r(chr(self::_GetInt2d($data, $fc + 8)));
                print_r(chr(self::_GetInt2d($data, $fc + 10)));
                print_r(chr(self::_GetInt2d($data, $fc + 12)));
                print_r(chr(self::_GetInt2d($data, $fc + 14)));
                print_r(chr(self::_GetInt2d($data, $fc + 16)));
                print_r(chr(self::_GetInt2d($data, $fc + 18)));
                print_r(chr(self::_GetInt2d($data, $fc + 20)));
                print_r(chr(self::_GetInt2d($data, $fc + 22)));
                print_r(chr(self::_GetInt2d($data, $fc + 24)));
                print_r(chr(self::_GetInt2d($data, $fc + 26)));
                print_r(chr(self::_GetInt2d($data, $fc + 28)));
                print_r(chr(self::_GetInt2d($data, $fc + 30)));
                print_r(chr(self::_GetInt2d($data, $fc + 32)));
                print_r(chr(self::_GetInt2d($data, $fc + 34)));
                print_r(chr(self::_GetInt2d($data, $fc + 36)));
                print_r(chr(self::_GetInt2d($data, $fc + 38)));
                print_r(PHP_EOL);
            }

            $offsetPcd = $piece_table + $countCp*4 + $i*8;

            $start = self::_GetInt4d($data, $offsetPcd+2);
            print_r('$start : '.$start.PHP_EOL);
            $fc = $start >> 30;
            $start = ($start << 2) >> 2;
            print_r('$fc : '.$fc.PHP_EOL);
            print_r('$start : '.$start.PHP_EOL);

            $offset = $piece_table + $i*4;
            print_r('$offset : '.$offset.PHP_EOL);
            $cpPre = self::_GetInt4d($data, $offset);
            print_r('$cpPre : '.$cpPre.PHP_EOL);
            $cpNext = self::_GetInt4d($data, $offset + 4);
            print_r('$cpNext : '.$cpNext.PHP_EOL);
            $length = $cpNext - $cpPre -1;
            if($fc == 0) {
                $length *= 2;
            } else {
                $start = $start/2;
            }

            $start += 512;
            print_r($start);
            print_r(PHP_EOL);
            print_r($length);
            print_r(PHP_EOL);
            print_r(self::_GetInt2d($data, $start + 2));
            print_r(PHP_EOL);
            print_r(chr(self::_GetInt2d($data, $start + 2)));
            print_r(PHP_EOL);
            print_r(chr(self::_GetInt2d($data, $start + 4)));
            print_r(chr(self::_GetInt2d($data, $start + 6)));
            print_r(chr(self::_GetInt2d($data, $start + 8)));
            print_r(chr(self::_GetInt2d($data, $start + 10)));
            print_r(chr(self::_GetInt2d($data, $start + 12)));
            print_r(chr(self::_GetInt2d($data, $start + 14)));
            print_r(chr(self::_GetInt2d($data, $start + 16)));
            print_r(chr(self::_GetInt2d($data, $start + 18)));
            print_r(chr(self::_GetInt2d($data, $start + 20)));
            print_r(chr(self::_GetInt2d($data, $start + 22)));
            print_r(chr(self::_GetInt2d($data, $start + 24)));
            print_r(chr(self::_GetInt2d($data, $start + 26)));
            print_r(chr(self::_GetInt2d($data, $start + 28)));
            print_r(chr(self::_GetInt2d($data, $start + 30)));
            print_r(chr(self::_GetInt2d($data, $start + 32)));
            print_r(chr(self::_GetInt2d($data, $start + 34)));
            print_r(chr(self::_GetInt2d($data, $start + 36)));
            print_r(chr(self::_GetInt2d($data, $start + 38)));
        }
/*

        $start = self::_GetInt4d($data, 1850);
        print_r('$start : '.$start.PHP_EOL);
        $end = self::_GetInt4d($data, 1854);
        print_r('$end : '.$end.PHP_EOL);*/

        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        //print_r($data);
        print_r(PHP_EOL);
        //print_r(dechex($data));
        print_r(PHP_EOL);
    }

    private function readData($data)
    {
        print_r('============ readData'.PHP_EOL);
        $text = self::_GetInt1d($data, 0);
        print_r(dechex($text));

        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        //print_r($data);
        print_r(PHP_EOL);
        //print_r(dechex($data));
        print_r(PHP_EOL);
    }

    private function readObjectPool($data)
    {
        print_r('============ readObjectPool'.PHP_EOL);

        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        //print_r($data);
        print_r(PHP_EOL);
        //print_r(dechex($data));
        print_r(PHP_EOL);
    }

    /**
     * Read 8-bit unsigned integer
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    public static function _GetInt1d($data, $pos)
    {
        return ord($data[$pos]);
    }

    /**
     * Read 16-bit unsigned integer
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    public static function _GetInt2d($data, $pos)
    {
        return ord($data[$pos]) | (ord($data[$pos+1]) << 8);
    }

    /**
     * Read 32-bit signed integer
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    public static function _GetInt4d($data, $pos)
    {
        // FIX: represent numbers correctly on 64-bit system
        // http://sourceforge.net/tracker/index.php?func=detail&aid=1487372&group_id=99160&atid=623334
        // Hacked by Andreas Rehm 2006 to ensure correct result of the <<24 block on 32 and 64bit systems
        $_or_24 = ord($data[$pos + 3]);
        if ($_or_24 >= 128) {
            // negative number
            $_ord_24 = -abs((256 - $_or_24) << 24);
        } else {
            $_ord_24 = ($_or_24 & 127) << 24;
        }
        return ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | $_ord_24;
    }
}
