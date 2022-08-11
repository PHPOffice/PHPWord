<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Abstract part reader
 *
 * This class is inherited by ODText reader
 *
 * @since 0.10.0
 */
abstract class AbstractPart
{
    /**
     * Conversion method
     *
     * @const int
     */
    const READ_VALUE = 'attributeValue';            // Read attribute value
    const READ_EQUAL = 'attributeEquals';           // Read `true` when attribute value equals specified value
    const READ_TRUE = 'attributeTrue';              // Read `true` when element exists
    const READ_FALSE = 'attributeFalse';            // Read `false` when element exists
    const READ_SIZE = 'attributeMultiplyByTwo';     // Read special attribute value for Font::$size

    protected $wingdings = array(
        '20'=>'0020','21'=>'1F589','22'=>'2702','23'=>'2701','24'=>'1F453','25'=>'1F56D','26'=>'1F56E','27'=>'1F56F','28'=>'1F57F','29'=>'2706','2A'=>'1F582','2B'=>'1F583','2C'=>'1F4EA','2D'=>'1F4EB','2E'=>'1F4EC','2F'=>'1F4ED','30'=>'1F4C1','31'=>'1F4C2','32'=>'1F4C4','33'=>'1F5CF','34'=>'1F5D0','35'=>'1F5C4','36'=>'231B','37'=>'1F5AE','38'=>'1F5B0','39'=>'1F5B2','3A'=>'1F5B3','3B'=>'1F5B4','3C'=>'1F5AB','3D'=>'1F5AC','3E'=>'2707','3F'=>'270D','40'=>'1F58E','41'=>'270C','42'=>'1F44C','43'=>'1F44D','44'=>'1F44E','45'=>'261C','46'=>'261E','47'=>'261D','48'=>'261F','49'=>'1F590','4A'=>'263A','4B'=>'1F610','4C'=>'2639','4D'=>'1F4A3','4E'=>'2620','4F'=>'1F3F3','50'=>'1F3F1','51'=>'2708','52'=>'263C','53'=>'1F4A7','54'=>'2744','55'=>'1F546','56'=>'271E','57'=>'1F548','58'=>'2720','59'=>'2721','5A'=>'262A','5B'=>'262F','5C'=>'0950','5D'=>'2638','5E'=>'2648','5F'=>'2649','60'=>'264A','61'=>'264B','62'=>'264C','63'=>'264D','64'=>'264E','65'=>'264F','66'=>'2650','67'=>'2651','68'=>'2652','69'=>'2653','6A'=>'1F670','6B'=>'1F675','6C'=>'25CF','6D'=>'1F53E','6E'=>'25A0','6F'=>'25A1','70'=>'1F790','71'=>'2751','72'=>'2752','73'=>'2B27','74'=>'29EB','75'=>'25C6','76'=>'2756','77'=>'2B25','78'=>'2327','79'=>'2BB9','7A'=>'2318','7B'=>'1F3F5','7C'=>'1F3F6','7D'=>'1F676','7E'=>'1F677','80'=>'24EA','81'=>'2460','82'=>'2461','83'=>'2462','84'=>'2463','85'=>'2464','86'=>'2465','87'=>'2466','88'=>'2467','89'=>'2468','8A'=>'2469','8B'=>'24FF','8C'=>'2776','8D'=>'2777','8E'=>'2778','8F'=>'2779','90'=>'277A','91'=>'277B','92'=>'277C','93'=>'277D','94'=>'277E','95'=>'277F','96'=>'1F662','97'=>'1F660','98'=>'1F661','99'=>'1F663','9A'=>'1F65E','9B'=>'1F65C','9C'=>'1F65D','9D'=>'1F65F','9E'=>'00B7','9F'=>'2022','A0'=>'25AA','A1'=>'26AA','A2'=>'1F786','A3'=>'1F788','A4'=>'25C9','A5'=>'25CE','A6'=>'1F53F','A7'=>'25AA','A8'=>'25FB','A9'=>'1F7C2','AA'=>'2726','AB'=>'2605','AC'=>'2736','AD'=>'2734','AE'=>'2739','AF'=>'2735','B0'=>'2BD0','B1'=>'2316','B2'=>'27E1','B3'=>'2311','B4'=>'2BD1','B5'=>'272A','B6'=>'2730','B7'=>'1F550','B8'=>'1F551','B9'=>'1F552','BA'=>'1F553','BB'=>'1F554','BC'=>'1F555','BD'=>'1F556','BE'=>'1F557','BF'=>'1F558','C0'=>'1F559','C1'=>'1F55A','C2'=>'1F55B','C3'=>'2BB0','C4'=>'2BB1','C5'=>'2BB2','C6'=>'2BB3','C7'=>'2BB4','C8'=>'2BB5','C9'=>'2BB6','CA'=>'2BB7','CB'=>'1F66A','CC'=>'1F66B','CD'=>'1F655','CE'=>'1F654','CF'=>'1F657','D0'=>'1F656','D1'=>'1F650','D2'=>'1F651','D3'=>'1F652','D4'=>'1F653','D5'=>'232B','D6'=>'2326','D7'=>'2B98','D8'=>'2B9A','D9'=>'2B99','DA'=>'2B9B','DB'=>'2B88','DC'=>'2B8A','DD'=>'2B89','DE'=>'2B8B','DF'=>'1F868','E0'=>'1F86A','E1'=>'1F869','E2'=>'1F86B','E3'=>'1F86C','E4'=>'1F86D','E5'=>'1F86F','E6'=>'1F86E','E7'=>'1F878','E8'=>'1F87A','E9'=>'1F879','EA'=>'1F87B','EB'=>'1F87C','EC'=>'1F87D','ED'=>'1F87F','EE'=>'1F87E','EF'=>'21E6','F0'=>'21E8','F1'=>'21E7','F2'=>'21E9','F3'=>'2B04','F4'=>'21F3','F5'=>'2B00','F6'=>'2B01','F7'=>'2B03','F8'=>'2B02','F9'=>'1F8AC','FA'=>'1F8AD','FB'=>'1F5F6','FC'=>'2714','FD'=>'1F5F7','FE'=>'1F5F9'
    );
    protected $wingdings2 = array(
        '20'=>'0020','21'=>'1F58A','22'=>'1F58B','23'=>'1F58C','24'=>'1F58D','25'=>'2704','26'=>'2700','27'=>'1F57E','28'=>'1F57D','29'=>'1F5C5','2A'=>'1F5C6','2B'=>'1F5C7','2C'=>'1F5C8','2D'=>'1F5C9','2E'=>'1F5CA','2F'=>'1F5CB','30'=>'1F5CC','31'=>'1F5CD','32'=>'1F4CB','33'=>'1F5D1','34'=>'1F5D4','35'=>'1F5B5','36'=>'1F5B6','37'=>'1F5B7','38'=>'1F5B8','39'=>'1F5AD','3A'=>'1F5AF','3B'=>'1F5B1','3C'=>'1F592','3D'=>'1F593','3E'=>'1F598','3F'=>'1F599','40'=>'1F59A','41'=>'1F59B','42'=>'1F448','43'=>'1F449','44'=>'1F59C','45'=>'1F59D','46'=>'1F59E','47'=>'1F59F','48'=>'1F5A0','49'=>'1F5A1','4A'=>'1F446','4B'=>'1F447','4C'=>'1F5A2','4D'=>'1F5A3','4E'=>'1F591','4F'=>'1F5F4','50'=>'2713','51'=>'1F5F5','52'=>'2611','53'=>'2612','54'=>'2612','55'=>'2BBE','56'=>'2BBF','57'=>'29B8','58'=>'29B8','59'=>'1F671','5A'=>'1F674','5B'=>'1F672','5C'=>'1F673','5D'=>'203D','5E'=>'1F679','5F'=>'1F67A','60'=>'1F67B','61'=>'1F666','62'=>'1F664','63'=>'1F665','64'=>'1F667','65'=>'1F65A','66'=>'1F658','67'=>'1F659','68'=>'1F65B','69'=>'24EA','6A'=>'2460','6B'=>'2461','6C'=>'2462','6D'=>'2463','6E'=>'2464','6F'=>'2465','70'=>'2466','71'=>'2467','72'=>'2468','73'=>'2469','74'=>'24FF','75'=>'2776','76'=>'2777','77'=>'2778','78'=>'2779','79'=>'277A','7A'=>'277B','7B'=>'277C','7C'=>'277D','7D'=>'277E','7E'=>'277F','80'=>'2609','81'=>'1F315','82'=>'263D','83'=>'263E','84'=>'2E3F','85'=>'271D','86'=>'1F547','87'=>'1F55C','88'=>'1F55D','89'=>'1F55E','8A'=>'1F55F','8B'=>'1F560','8C'=>'1F561','8D'=>'1F562','8E'=>'1F563','8F'=>'1F564','90'=>'1F565','91'=>'1F566','92'=>'1F567','93'=>'1F668','94'=>'1F669','95'=>'2022','96'=>'25CF','97'=>'26AB','98'=>'2B24','99'=>'1F785','9A'=>'1F786','9B'=>'1F787','9C'=>'1F788','9D'=>'1F78A','9E'=>'29BF','9F'=>'25FE','A0'=>'25A0','A1'=>'25FC','A2'=>'2B1B','A3'=>'2B1C','A4'=>'1F791','A5'=>'1F792','A6'=>'1F793','A7'=>'1F794','A8'=>'25A3','A9'=>'1F795','AA'=>'1F796','AB'=>'1F797','AC'=>'2B29','AD'=>'2B25','AE'=>'25C6','AF'=>'25C7','B0'=>'1F79A','B1'=>'25C8','B2'=>'1F79B','B3'=>'1F79C','B4'=>'1F79D','B5'=>'2B2A','B6'=>'2B27','B7'=>'29EB','B8'=>'25CA','B9'=>'1F7A0','BA'=>'25D6','BB'=>'25D7','BC'=>'2BCA','BD'=>'2BCB','BE'=>'25FC','BF'=>'2B25','C0'=>'2B1F','C1'=>'2BC2','C2'=>'2B23','C3'=>'2B22','C4'=>'2BC3','C5'=>'2BC4','C6'=>'1F7A1','C7'=>'1F7A2','C8'=>'1F7A3','C9'=>'1F7A4','CA'=>'1F7A5','CB'=>'1F7A6','CC'=>'1F7A7','CD'=>'1F7A8','CE'=>'1F7A9','CF'=>'1F7AA','D0'=>'1F7AB','D1'=>'1F7AC','D2'=>'1F7AD','D3'=>'1F7AE','D4'=>'1F7AF','D5'=>'1F7B0','D6'=>'1F7B1','D7'=>'1F7B2','D8'=>'1F7B3','D9'=>'1F7B4','DA'=>'1F7B5','DB'=>'1F7B6','DC'=>'1F7B7','DD'=>'1F7B8','DE'=>'1F7B9','DF'=>'1F7BA','E0'=>'1F7BB','E1'=>'1F7BC','E2'=>'1F7BD','E3'=>'1F7BE','E4'=>'1F7BF','E5'=>'1F7C0','E6'=>'1F7C2','E7'=>'1F7C4','E8'=>'2726','E9'=>'1F7C9','EA'=>'2605','EB'=>'2736','EC'=>'1F7CB','ED'=>'2737','EE'=>'1F7CF','EF'=>'1F7D2','F0'=>'2739','F1'=>'1F7C3','F2'=>'1F7C7','F3'=>'272F','F4'=>'1F7CD','F5'=>'1F7D4','F6'=>'2BCC','F7'=>'2BCD','F8'=>'203B','F9'=>'2042',
    );
    protected $wingdings3 = array(
        '20'=>'0020','21'=>'2B60','22'=>'2B62','23'=>'2B61','24'=>'2B63','25'=>'2B66','26'=>'2B67','27'=>'2B69','28'=>'2B68','29'=>'2B70','2A'=>'2B72','2B'=>'2B71','2C'=>'2B73','2D'=>'2B76','2E'=>'2B78','2F'=>'2B7B','30'=>'2B7D','31'=>'2B64','32'=>'2B65','33'=>'2B6A','34'=>'2B6C','35'=>'2B6B','36'=>'2B6D','37'=>'2B4D','38'=>'2BA0','39'=>'2BA1','3A'=>'2BA2','3B'=>'2BA3','3C'=>'2BA4','3D'=>'2BA5','3E'=>'2BA6','3F'=>'2BA7','40'=>'2B90','41'=>'2B91','42'=>'2B92','43'=>'2B93','44'=>'2B80','45'=>'2B83','46'=>'2B7E','47'=>'2B7F','48'=>'2B84','49'=>'2B86','4A'=>'2B85','4B'=>'2B87','4C'=>'2B8F','4D'=>'2B8D','4E'=>'2B8E','4F'=>'2B8C','50'=>'2B6E','51'=>'2B6F','52'=>'238B','53'=>'2324','54'=>'2303','55'=>'2325','56'=>'23B5','57'=>'237D','58'=>'21EA','59'=>'2BB8','5A'=>'1F8A0','5B'=>'1F8A1','5C'=>'1F8A2','5D'=>'1F8A3','5E'=>'1F8A4','5F'=>'1F8A5','60'=>'1F8A6','61'=>'1F8A7','62'=>'1F8A8','63'=>'1F8A9','64'=>'1F8AA','65'=>'1F8AB','66'=>'2190','67'=>'2192','68'=>'2191','69'=>'2193','6A'=>'2196','6B'=>'2197','6C'=>'2199','6D'=>'2198','6E'=>'1F858','6F'=>'1F859','70'=>'25B2','71'=>'25BC','72'=>'25B3','73'=>'25BD','74'=>'25C4','75'=>'25BA','76'=>'25C1','77'=>'25B7','78'=>'25E3','79'=>'25E2','7A'=>'25E4','7B'=>'25E5','7C'=>'1F780','7D'=>'1F782','7E'=>'1F781','80'=>'1F783','81'=>'25B2','82'=>'25BC','83'=>'25C0','84'=>'25B6','85'=>'2B9C','86'=>'2B9E','87'=>'2B9D','88'=>'2B9F','89'=>'1F810','8A'=>'1F812','8B'=>'1F811','8C'=>'1F813','8D'=>'1F814','8E'=>'1F816','8F'=>'1F815','90'=>'1F817','91'=>'1F818','92'=>'1F81A','93'=>'1F819','94'=>'1F81B','95'=>'1F81C','96'=>'1F81E','97'=>'1F81D','98'=>'1F81F','99'=>'1F800','9A'=>'1F802','9B'=>'1F801','9C'=>'1F803','9D'=>'1F804','9E'=>'1F806','9F'=>'1F805','A0'=>'1F807','A1'=>'1F808','A2'=>'1F80A','A3'=>'1F809','A4'=>'1F80B','A5'=>'1F820','A6'=>'1F822','A7'=>'1F824','A8'=>'1F826','A9'=>'1F828','AA'=>'1F82A','AB'=>'1F82C','AC'=>'1F89C','AD'=>'1F89D','AE'=>'1F89E','AF'=>'1F89F','B0'=>'1F82E','B1'=>'1F830','B2'=>'1F832','B3'=>'1F834','B4'=>'1F836','B5'=>'1F838','B6'=>'1F83A','B7'=>'1F839','B8'=>'1F83B','B9'=>'1F898','BA'=>'1F89A','BB'=>'1F899','BC'=>'1F89B','BD'=>'1F83C','BE'=>'1F83E','BF'=>'1F83D','C0'=>'1F83F','C1'=>'1F840','C2'=>'1F842','C3'=>'1F841','C4'=>'1F843','C5'=>'1F844','C6'=>'1F846','C7'=>'1F845','C8'=>'1F847','C9'=>'2BA8','CA'=>'2BA9','CB'=>'2BAA','CC'=>'2BAB','CD'=>'2BAC','CE'=>'2BAD','CF'=>'2BAE','D0'=>'2BAF','D1'=>'1F860','D2'=>'1F862','D3'=>'1F861','D4'=>'1F863','D5'=>'1F864','D6'=>'1F865','D7'=>'1F867','D8'=>'1F866','D9'=>'1F870','DA'=>'1F872','DB'=>'1F871','DC'=>'1F873','DD'=>'1F874','DE'=>'1F875','DF'=>'1F877','E0'=>'1F876','E1'=>'1F880','E2'=>'1F882','E3'=>'1F881','E4'=>'1F883','E5'=>'1F884','E6'=>'1F885','E7'=>'1F887','E8'=>'1F886','E9'=>'1F890','EA'=>'1F892','EB'=>'1F891','EC'=>'1F893','ED'=>'1F894','EE'=>'1F896','EF'=>'1F895','F0'=>'1F897',
    );
    protected $webdings = array(
        '20'=>'0020','21'=>'1F577','22'=>'1F578','23'=>'1F572','24'=>'1F576','25'=>'1F3C6','26'=>'1F396','27'=>'1F587','28'=>'1F5E8','29'=>'1F5E9','2A'=>'1F5F0','2B'=>'1F5F1','2C'=>'1F336','2D'=>'1F397','2E'=>'1F67E','2F'=>'1F67C','30'=>'1F5D5','31'=>'1F5D6','32'=>'1F5D7','33'=>'23F4','34'=>'23F5','35'=>'23F6','36'=>'23F7','37'=>'23EA','38'=>'23E9','39'=>'23EE','3A'=>'23ED','3B'=>'23F8','3C'=>'23F9','3D'=>'23FA','3E'=>'1F5DA','3F'=>'1F5F3','40'=>'1F6E0','41'=>'1F3D7','42'=>'1F3D8','43'=>'1F3D9','44'=>'1F3DA','45'=>'1F3DC','46'=>'1F3ED','47'=>'1F3DB','48'=>'1F3E0','49'=>'1F3D6','4A'=>'1F3DD','4B'=>'1F6E3','4C'=>'1F50D','4D'=>'1F3D4','4E'=>'1F441','4F'=>'1F442','50'=>'1F3DE','51'=>'1F3D5','52'=>'1F6E4','53'=>'1F3DF','54'=>'1F6F3','55'=>'1F56C','56'=>'1F56B','57'=>'1F568','58'=>'1F508','59'=>'1F394','5A'=>'1F395','5B'=>'1F5EC','5C'=>'1F67D','5D'=>'1F5ED','5E'=>'1F5EA','5F'=>'1F5EB','60'=>'2B94','61'=>'2714','62'=>'1F6B2','63'=>'25A1','64'=>'1F6E1','65'=>'1F4E6','66'=>'1F6F1','67'=>'25A0','68'=>'1F691','69'=>'1F6C8','6A'=>'1F6E9','6B'=>'1F6F0','6C'=>'1F7C8','6D'=>'1F574','6E'=>'26AB','6F'=>'1F6E5','70'=>'1F694','71'=>'1F5D8','72'=>'1F5D9','73'=>'2753','74'=>'1F6F2','75'=>'1F687','76'=>'1F68D','77'=>'26F3','78'=>'1F6C7','79'=>'2296','7A'=>'1F6AD','7B'=>'1F5EE','7C'=>'007C','7D'=>'1F5EF','7E'=>'1F5F2','80'=>'1F6B9','81'=>'1F6BA','82'=>'1F6C9','83'=>'1F6CA','84'=>'1F6BC','85'=>'1F47D','86'=>'1F3CB','87'=>'26F7','88'=>'1F3C2','89'=>'1F3CC','8A'=>'1F3CA','8B'=>'1F3C4','8C'=>'1F3CD','8D'=>'1F3CE','8E'=>'1F698','8F'=>'1F5E0','90'=>'1F6E2','91'=>'1F4B0','92'=>'1F3F7','93'=>'1F4B3','94'=>'1F46A','95'=>'1F5E1','96'=>'1F5E2','97'=>'1F5E3','98'=>'272F','99'=>'1F584','9A'=>'1F585','9B'=>'1F583','9C'=>'1F586','9D'=>'1F5B9','9E'=>'1F5BA','9F'=>'1F5BB','A0'=>'1F575','A1'=>'1F570','A2'=>'1F5BD','A3'=>'1F5BE','A4'=>'1F4CB','A5'=>'1F5D2','A6'=>'1F5D3','A7'=>'1F4D6','A8'=>'1F4DA','A9'=>'1F5DE','AA'=>'1F5DF','AB'=>'1F5C3','AC'=>'1F5C2','AD'=>'1F5BC','AE'=>'1F3AD','AF'=>'1F39C','B0'=>'1F398','B1'=>'1F399','B2'=>'1F3A7','B3'=>'1F4BF','B4'=>'1F39E','B5'=>'1F4F7','B6'=>'1F39F','B7'=>'1F3AC','B8'=>'1F4FD','B9'=>'1F4F9','BA'=>'1F4FE','BB'=>'1F4FB','BC'=>'1F39A','BD'=>'1F39B','BE'=>'1F4FA','BF'=>'1F4BB','C0'=>'1F5A5','C1'=>'1F5A6','C2'=>'1F5A7','C3'=>'1F579','C4'=>'1F3AE','C5'=>'1F57B','C6'=>'1F57C','C7'=>'1F4DF','C8'=>'1F581','C9'=>'1F580','CA'=>'1F5A8','CB'=>'1F5A9','CC'=>'1F5BF','CD'=>'1F5AA','CE'=>'1F5DC','CF'=>'1F512','D0'=>'1F513','D1'=>'1F5DD','D2'=>'1F4E5','D3'=>'1F4E4','D4'=>'1F573','D5'=>'1F323','D6'=>'1F324','D7'=>'1F325','D8'=>'1F326','D9'=>'2601','DA'=>'1F327','DB'=>'1F328','DC'=>'1F329','DD'=>'1F32A','DE'=>'1F32C','DF'=>'1F32B','E0'=>'1F31C','E1'=>'1F321','E2'=>'1F6CB','E3'=>'1F6CF','E4'=>'1F37D','E5'=>'1F378','E6'=>'1F6CE','E7'=>'1F6CD','E8'=>'24C5','E9'=>'267F','EA'=>'1F6C6','EB'=>'1F588','EC'=>'1F393','ED'=>'1F5E4','EE'=>'1F5E5','EF'=>'1F5E6','F0'=>'1F5E7','F1'=>'1F6EA','F2'=>'1F43F','F3'=>'1F426','F4'=>'1F41F','F5'=>'1F415','F6'=>'1F408','F7'=>'1F66C','F8'=>'1F66E','F9'=>'1F66D','FA'=>'1F66F','FB'=>'1F5FA','FC'=>'1F30D','FD'=>'1F30F','FE'=>'1F30E','FF'=>'1F54A',
    );

    /**
     * Document file
     *
     * @var string
     */
    protected $docFile;

    /**
     * XML file
     *
     * @var string
     */
    protected $xmlFile;

    /**
     * Part relationships
     *
     * @var array
     */
    protected $rels = array();

    /**
     * Read part.
     */
    abstract public function read(PhpWord $phpWord);

    /**
     * Create new instance
     *
     * @param string $docFile
     * @param string $xmlFile
     */
    public function __construct($docFile, $xmlFile)
    {
        $this->docFile = $docFile;
        $this->xmlFile = $xmlFile;
    }

    /**
     * Set relationships.
     *
     * @param array $value
     */
    public function setRels($value)
    {
        $this->rels = $value;
    }

    /**
     * Read w:p.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     *
     * @todo Get font style for preserve text
     */
    protected function readParagraph(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart = 'document')
    {
        // Paragraph style
        $paragraphStyle = null;
        $headingDepth = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $paragraphStyle = $this->readParagraphStyle($xmlReader, $domNode);
            //var_export($paragraphStyle);
            $headingDepth = $this->getHeadingDepth($paragraphStyle);
        }

        // PreserveText
        if ($xmlReader->elementExists('w:r/w:instrText', $domNode)) {
            $ignoreText = false;
            $textContent = '';
            $fontStyle = $this->readFontStyle($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $instrText = $xmlReader->getValue('w:instrText', $node);
                if ($xmlReader->elementExists('w:fldChar', $node)) {
                    $fldCharType = $xmlReader->getAttribute('w:fldCharType', $node, 'w:fldChar');
                    if ('begin' == $fldCharType) {
                        $ignoreText = true;
                    } elseif ('end' == $fldCharType) {
                        $ignoreText = false;
                    }
                }
                if (!is_null($instrText)) {
                    $textContent .= '{' . $instrText . '}';
                } else {
                    if (false === $ignoreText) {
                        $textContent .= $xmlReader->getValue('w:t', $node);
                    }
                }
            }
            $parent->addPreserveText(htmlspecialchars($textContent, ENT_QUOTES, 'UTF-8'), $fontStyle, $paragraphStyle);
        } elseif ($xmlReader->elementExists('w:pPr/w:numPr', $domNode)) {
            // List item
            $numId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:numId');
            $levelId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:ilvl');
            $nodes = $xmlReader->getElements('*', $domNode);

            $listItemRun = $parent->addListItemRun($levelId, "PHPWordList{$numId}", $paragraphStyle);

            foreach ($nodes as $node) {
                $this->readRun($xmlReader, $node, $listItemRun, $docPart, $paragraphStyle);
            }
        } elseif ($headingDepth !== null) {
            // Heading or Title
            $textContent = null;
            $nodes = $xmlReader->getElements('w:r', $domNode);
            if ($nodes->length === 1) {
                $textContent = htmlspecialchars($xmlReader->getValue('w:t', $nodes->item(0)), ENT_QUOTES, 'UTF-8');
            } else {
                $textContent = new TextRun($paragraphStyle);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $textContent, $docPart, $paragraphStyle);
                }
            }
            $parent->addTitle($textContent, $headingDepth);
        } else {
            // Text and TextRun
            $textRunContainers = $xmlReader->countElements('w:r|w:ins|w:del|w:hyperlink|w:smartTag', $domNode);
            if (0 === $textRunContainers) {
                $parent->addTextBreak(null, $paragraphStyle);
            } else {
                $nodes = $xmlReader->getElements('*', $domNode);
                $paragraph = $parent->addTextRun($paragraphStyle);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $paragraph, $docPart, $paragraphStyle);
                }
            }
        }
    }

    /**
     * Returns the depth of the Heading, returns 0 for a Title
     *
     * @param array $paragraphStyle
     * @return number|null
     */
    private function getHeadingDepth(array $paragraphStyle = null)
    {
        if (is_array($paragraphStyle) && isset($paragraphStyle['styleName'])) {
            if ('Title' === $paragraphStyle['styleName']) {
                return 0;
            }

            $headingMatches = array();
            preg_match('/Heading(\d)/', $paragraphStyle['styleName'], $headingMatches);
            if (!empty($headingMatches)) {
                return $headingMatches[1];
            }
        }

        return null;
    }

    /**
     * Read w:r.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     *
     * @todo Footnote paragraph style
     */
    protected function readRun(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart, $paragraphStyle = null)
    {
        if (in_array($domNode->nodeName, array('w:ins', 'w:del', 'w:smartTag', 'w:hyperlink'))) {
            $nodes = $xmlReader->getElements('*', $domNode);
            foreach ($nodes as $node) {
                $this->readRun($xmlReader, $node, $parent, $docPart, $paragraphStyle);
            }
        } elseif ($domNode->nodeName == 'w:r') {
            $fontStyle = $this->readFontStyle($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('*', $domNode);
            foreach ($nodes as $node) {
                $this->readRunChild($xmlReader, $node, $parent, $docPart, $paragraphStyle, $fontStyle);
            }
        }
    }

    /**
     * Parses nodes under w:r
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement $node
     * @param AbstractContainer $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     * @param mixed $fontStyle
     */
    protected function readRunChild(XMLReader $xmlReader, \DOMElement $node, AbstractContainer $parent, $docPart, $paragraphStyle = null, $fontStyle = null)
    {
        $runParent = $node->parentNode->parentNode;
        if ($node->nodeName == 'w:footnoteReference') {
            // Footnote
            $wId = $xmlReader->getAttribute('w:id', $node);
            $footnote = $parent->addFootnote();
            $footnote->setRelationId($wId);
        } elseif ($node->nodeName == 'w:endnoteReference') {
            // Endnote
            $wId = $xmlReader->getAttribute('w:id', $node);
            $endnote = $parent->addEndnote();
            $endnote->setRelationId($wId);
        } elseif ($node->nodeName == 'w:pict') {
            // Image
            $rId = $xmlReader->getAttribute('r:id', $node, 'v:shape/v:imagedata');
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                if ('External' == $this->getTargetMode($docPart, $rId)) {
                    $imageSource = $target;
                } else {
                    $imageSource = "zip://{$this->docFile}#{$target}";
                }
                $parent->addImage($imageSource);
            }
        } elseif ($node->nodeName == 'w:drawing') {
            // Office 2011 Image
            $xmlReader->registerNamespace('wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
            $xmlReader->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
            $xmlReader->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
            $xmlReader->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

            $name = $xmlReader->getAttribute('name', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');
            $embedId = $xmlReader->getAttribute('r:embed', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
            if ($name === null && $embedId === null) { // some Converters puts images on a different path
                $name = $xmlReader->getAttribute('name', $node, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');
                $embedId = $xmlReader->getAttribute('r:embed', $node, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
            }
            $target = $this->getMediaTarget($docPart, $embedId);
            if (!is_null($target)) {
                $imageSource = "zip://{$this->docFile}#{$target}";
                $parent->addImage($imageSource, null, false, $name);
            }
        } elseif ($node->nodeName == 'w:object') {
            // Object
            $rId = $xmlReader->getAttribute('r:id', $node, 'o:OLEObject');
            // $rIdIcon = $xmlReader->getAttribute('r:id', $domNode, 'w:object/v:shape/v:imagedata');
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                $textContent = "&lt;Object: {$target}>";
                $parent->addText($textContent, $fontStyle, $paragraphStyle);
            }
        } elseif ($node->nodeName == 'w:br') {
            $parent->addTextBreak();
        } elseif ($node->nodeName == 'w:tab') {
            $parent->addText("\t");
        } elseif ($node->nodeName == 'mc:AlternateContent') {
            if ($node->hasChildNodes()) {
                // Get fallback instead of mc:Choice to make sure it is compatible
                $fallbackElements = $node->getElementsByTagName('Fallback');

                if ($fallbackElements->length) {
                    $fallback = $fallbackElements->item(0);
                    // TextRun
                    $textContent = htmlspecialchars($fallback->nodeValue, ENT_QUOTES, 'UTF-8');

                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }
            }
        } elseif ($node->nodeName == 'w:t' || $node->nodeName == 'w:delText') {
            // TextRun
            $textContent = htmlspecialchars($xmlReader->getValue('.', $node), ENT_QUOTES, 'UTF-8');
            if ($runParent->nodeName == 'w:hyperlink') {
                $rId = $xmlReader->getAttribute('r:id', $runParent);
                $target = $this->getMediaTarget($docPart, $rId);
                if (!is_null($target)) {
                    $parent->addLink($target, $textContent, $fontStyle, $paragraphStyle);
                } else {
                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }
            } else {
                /** @var AbstractElement $element */
                $element = $parent->addText($textContent, $fontStyle, $paragraphStyle);
                if (in_array($runParent->nodeName, array('w:ins', 'w:del'))) {
                    $type = ($runParent->nodeName == 'w:del') ? TrackChange::DELETED : TrackChange::INSERTED;
                    $author = $runParent->getAttribute('w:author');
                    $date = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $runParent->getAttribute('w:date'));
                    $element->setChangeInfo($type, $author, $date);
                }
            }
        }else if($node->nodeName == 'w:sym'){
            $char = $xmlReader->getAttribute('w:char', $node);
            $font = $xmlReader->getAttribute('w:font', $node);
            $char = strlen($char) > 2 ? substr($char, strlen($char)-2) : $char;
            $unicode = null;
            if($font == 'Wingdings'){
                $unicode = $this->wingdings[$char];
            }
            if($font == 'Wingdings 2'){
                $unicode = $this->wingdings2[$char];
            }
            if($font == 'Wingdings 3'){
                $unicode = $this->wingdings3[$char];
            }
            if($font == 'Webdings'){
                $unicode = $this->webdings[$char];
            }
            if($unicode != null){
                $text = mb_convert_encoding(pack("H*", $unicode), "UTF-8", "UCS-2BE");
                $parent->addText($text);
            }
        }
    }

    /**
     * Read w:tbl.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     */
    protected function readTable(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart = 'document')
    {
        // Table style
        $tblStyle = null;
        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $tblStyle = $this->readTableStyle($xmlReader, $domNode);
        }

        /** @var \PhpOffice\PhpWord\Element\Table $table Type hint */
        $table = $parent->addTable($tblStyle);
        $tblNodes = $xmlReader->getElements('*', $domNode);
        foreach ($tblNodes as $tblNode) {
            if ('w:tblGrid' == $tblNode->nodeName) { // Column
                // @todo Do something with table columns
            } elseif ('w:tr' == $tblNode->nodeName) { // Row
                $rowHeight = $xmlReader->getAttribute('w:val', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $xmlReader->getAttribute('w:hRule', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $rowHRule == 'exact';
                $rowStyle = array(
                    'tblHeader'   => $xmlReader->elementExists('w:trPr/w:tblHeader', $tblNode),
                    'cantSplit'   => $xmlReader->elementExists('w:trPr/w:cantSplit', $tblNode),
                    'exactHeight' => $rowHRule,
                );

                $row = $table->addRow($rowHeight, $rowStyle);
                $rowNodes = $xmlReader->getElements('*', $tblNode);
                foreach ($rowNodes as $rowNode) {
                    if ('w:trPr' == $rowNode->nodeName) { // Row style
                        // @todo Do something with row style
                    } elseif ('w:tc' == $rowNode->nodeName) { // Cell
                        $cellWidth = $xmlReader->getAttribute('w:w', $rowNode, 'w:tcPr/w:tcW');
                        $cellStyle = null;
                        $cellStyleNode = $xmlReader->getElement('w:tcPr', $rowNode);
                        if (!is_null($cellStyleNode)) {
                            $cellStyle = $this->readCellStyle($xmlReader, $cellStyleNode);
                        }

                        $cell = $row->addCell($cellWidth, $cellStyle);
                        $cellNodes = $xmlReader->getElements('*', $rowNode);
                        foreach ($cellNodes as $cellNode) {
                            if ('w:p' == $cellNode->nodeName) { // Paragraph
                                $this->readParagraph($xmlReader, $cellNode, $cell, $docPart);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Read w:pPr.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array|null
     */
    protected function readParagraphStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        if (!$xmlReader->elementExists('w:pPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:pPr', $domNode);
        $styleDefs = array(
            'styleName'           => array(self::READ_VALUE, array('w:pStyle', 'w:name')),
            'alignment'           => array(self::READ_VALUE, 'w:jc'),
            'spacing'           => array(self::READ_VALUE, 'w:spacing', 'w:line'),
            'spacingLineRule'           => array(self::READ_VALUE, 'w:spacing', 'w:lineRule'),
            'basedOn'             => array(self::READ_VALUE, 'w:basedOn'),
            'next'                => array(self::READ_VALUE, 'w:next'),
            'indent'              => array(self::READ_VALUE, 'w:ind', 'w:left'),
            'hanging'             => array(self::READ_VALUE, 'w:ind', 'w:hanging'),
            'spaceAfter'          => array(self::READ_VALUE, 'w:spacing', 'w:after'),
            'spaceBefore'         => array(self::READ_VALUE, 'w:spacing', 'w:before'),
            'widowControl'        => array(self::READ_FALSE, 'w:widowControl'),
            'keepNext'            => array(self::READ_TRUE,  'w:keepNext'),
            'keepLines'           => array(self::READ_TRUE,  'w:keepLines'),
            'pageBreakBefore'     => array(self::READ_TRUE,  'w:pageBreakBefore'),
            'contextualSpacing'   => array(self::READ_TRUE,  'w:contextualSpacing'),
            'bidi'                => array(self::READ_TRUE,  'w:bidi'),
            'suppressAutoHyphens' => array(self::READ_TRUE,  'w:suppressAutoHyphens'),
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:rPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array|null
     */
    protected function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        if (is_null($domNode)) {
            return null;
        }
        // Hyperlink has an extra w:r child
        if ('w:hyperlink' == $domNode->nodeName) {
            $domNode = $xmlReader->getElement('w:r', $domNode);
        }
        if (!$xmlReader->elementExists('w:rPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:rPr', $domNode);
        $styleDefs = array(
            'styleName'           => array(self::READ_VALUE, 'w:rStyle'),
            'name'                => array(self::READ_VALUE, 'w:rFonts', array('w:ascii', 'w:hAnsi', 'w:eastAsia', 'w:cs')),
            'hint'                => array(self::READ_VALUE, 'w:rFonts', 'w:hint'),
            'size'                => array(self::READ_SIZE,  array('w:sz', 'w:szCs')),
            'color'               => array(self::READ_VALUE, 'w:color'),
            'underline'           => array(self::READ_VALUE, 'w:u'),
            'bold'                => array(self::READ_TRUE,  'w:b'),
            'italic'              => array(self::READ_TRUE,  'w:i'),
            'strikethrough'       => array(self::READ_TRUE,  'w:strike'),
            'doubleStrikethrough' => array(self::READ_TRUE,  'w:dstrike'),
            'smallCaps'           => array(self::READ_TRUE,  'w:smallCaps'),
            'allCaps'             => array(self::READ_TRUE,  'w:caps'),
            'superScript'         => array(self::READ_EQUAL, 'w:vertAlign', 'w:val', 'superscript'),
            'subScript'           => array(self::READ_EQUAL, 'w:vertAlign', 'w:val', 'subscript'),
            'fgColor'             => array(self::READ_VALUE, 'w:highlight'),
            'rtl'                 => array(self::READ_TRUE,  'w:rtl'),
            'lang'                => array(self::READ_VALUE, 'w:lang'),
            'position'            => array(self::READ_VALUE, 'w:position'),
            'hidden'              => array(self::READ_TRUE,  'w:vanish'),
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:tblPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return string|array|null
     * @todo Capture w:tblStylePr w:type="firstRow"
     */
    protected function readTableStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        $margins = array('top', 'left', 'bottom', 'right');
        $borders = array_merge($margins, array('insideH', 'insideV'));

        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            if ($xmlReader->elementExists('w:tblPr/w:tblStyle', $domNode)) {
                $style = $xmlReader->getAttribute('w:val', $domNode, 'w:tblPr/w:tblStyle');
            } else {
                $styleNode = $xmlReader->getElement('w:tblPr', $domNode);
                $styleDefs = array();
                foreach ($margins as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["cellMargin$ucfSide"] = array(self::READ_VALUE, "w:tblCellMar/w:$side", 'w:w');
                }
                foreach ($borders as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["border{$ucfSide}Size"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:sz');
                    $styleDefs["border{$ucfSide}Color"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:color');
                    $styleDefs["border{$ucfSide}Style"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:val');
                }
                $styleDefs['layout'] = array(self::READ_VALUE, 'w:tblLayout', 'w:type');
                $styleDefs['bidiVisual'] = array(self::READ_TRUE, 'w:bidiVisual');
                $styleDefs['cellSpacing'] = array(self::READ_VALUE, 'w:tblCellSpacing', 'w:w');
                $style = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);

                $tablePositionNode = $xmlReader->getElement('w:tblpPr', $styleNode);
                if ($tablePositionNode !== null) {
                    $style['position'] = $this->readTablePosition($xmlReader, $tablePositionNode);
                }

                $indentNode = $xmlReader->getElement('w:tblInd', $styleNode);
                if ($indentNode !== null) {
                    $style['indent'] = $this->readTableIndent($xmlReader, $indentNode);
                }
            }
        }

        return $style;
    }

    /**
     * Read w:tblpPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    private function readTablePosition(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'leftFromText'   => array(self::READ_VALUE, '.', 'w:leftFromText'),
            'rightFromText'  => array(self::READ_VALUE, '.', 'w:rightFromText'),
            'topFromText'    => array(self::READ_VALUE, '.', 'w:topFromText'),
            'bottomFromText' => array(self::READ_VALUE, '.', 'w:bottomFromText'),
            'vertAnchor'     => array(self::READ_VALUE, '.', 'w:vertAnchor'),
            'horzAnchor'     => array(self::READ_VALUE, '.', 'w:horzAnchor'),
            'tblpXSpec'      => array(self::READ_VALUE, '.', 'w:tblpXSpec'),
            'tblpX'          => array(self::READ_VALUE, '.', 'w:tblpX'),
            'tblpYSpec'      => array(self::READ_VALUE, '.', 'w:tblpYSpec'),
            'tblpY'          => array(self::READ_VALUE, '.', 'w:tblpY'),
        );

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Read w:tblInd
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return TblWidthComplexType
     */
    private function readTableIndent(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'value' => array(self::READ_VALUE, '.', 'w:w'),
            'type'  => array(self::READ_VALUE, '.', 'w:type'),
        );
        $styleDefs = $this->readStyleDefs($xmlReader, $domNode, $styleDefs);

        return new TblWidthComplexType((int) $styleDefs['value'], $styleDefs['type']);
    }

    /**
     * Read w:tcPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    private function readCellStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'valign'        => array(self::READ_VALUE, 'w:vAlign'),
            'textDirection' => array(self::READ_VALUE, 'w:textDirection'),
            'gridSpan'      => array(self::READ_VALUE, 'w:gridSpan'),
            'vMerge'        => array(self::READ_VALUE, 'w:vMerge'),
            'bgColor'       => array(self::READ_VALUE, 'w:shd', 'w:fill'),
        );

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Returns the first child element found
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement|null $parentNode
     * @param string|array|null $elements
     * @return string|null
     */
    private function findPossibleElement(XMLReader $xmlReader, \DOMElement $parentNode = null, $elements = null)
    {
        if (is_array($elements)) {
            //if element is an array, we take the first element that exists in the XML
            foreach ($elements as $possibleElement) {
                if ($xmlReader->elementExists($possibleElement, $parentNode)) {
                    return $possibleElement;
                }
            }
        } else {
            return $elements;
        }

        return null;
    }

    /**
     * Returns the first attribute found
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement $node
     * @param string|array $attributes
     * @return string|null
     */
    private function findPossibleAttribute(XMLReader $xmlReader, \DOMElement $node, $attributes)
    {
        //if attribute is an array, we take the first attribute that exists in the XML
        if (is_array($attributes)) {
            foreach ($attributes as $possibleAttribute) {
                if ($xmlReader->getAttribute($possibleAttribute, $node)) {
                    return $possibleAttribute;
                }
            }

            return null;
        }

        return $attributes;
    }

    /**
     * Read style definition
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $parentNode
     * @param array $styleDefs
     * @ignoreScrutinizerPatch
     * @return array
     */
    protected function readStyleDefs(XMLReader $xmlReader, \DOMElement $parentNode = null, $styleDefs = array())
    {
        $styles = array();

        foreach ($styleDefs as $styleProp => $styleVal) {
            list($method, $element, $attribute, $expected) = array_pad($styleVal, 4, null);

            $element = $this->findPossibleElement($xmlReader, $parentNode, $element);
            if ($element === null) {
                continue;
            }

            if ($xmlReader->elementExists($element, $parentNode)) {
                $node = $xmlReader->getElement($element, $parentNode);

                $attribute = $this->findPossibleAttribute($xmlReader, $node, $attribute);

                // Use w:val as default if no attribute assigned
                $attribute = ($attribute === null) ? 'w:val' : $attribute;
                $attributeValue = $xmlReader->getAttribute($attribute, $node);

                $styleValue = $this->readStyleDef($method, $attributeValue, $expected);
                if ($styleValue !== null) {
                    $styles[$styleProp] = $styleValue;
                }
            }
        }

        return $styles;
    }

    /**
     * Return style definition based on conversion method
     *
     * @param string $method
     * @ignoreScrutinizerPatch
     * @param string|null $attributeValue
     * @param mixed $expected
     * @return mixed
     */
    private function readStyleDef($method, $attributeValue, $expected)
    {
        $style = $attributeValue;

        if (self::READ_SIZE == $method) {
            $style = $attributeValue / 2;
        } elseif (self::READ_TRUE == $method) {
            $style = $this->isOn($attributeValue);
        } elseif (self::READ_FALSE == $method) {
            $style = !$this->isOn($attributeValue);
        } elseif (self::READ_EQUAL == $method) {
            $style = $attributeValue == $expected;
        }

        return $style;
    }

    /**
     * Parses the value of the on/off value, null is considered true as it means the w:val attribute was not present
     *
     * @see http://www.datypic.com/sc/ooxml/t-w_ST_OnOff.html
     * @param string $value
     * @return bool
     */
    private function isOn($value = null)
    {
        return $value === null || $value === '1' || $value === 'true' || $value === 'on';
    }

    /**
     * Returns the target of image, object, or link as stored in ::readMainRels
     *
     * @param string $docPart
     * @param string $rId
     * @return string|null
     */
    private function getMediaTarget($docPart, $rId)
    {
        $target = null;

        if (isset($this->rels[$docPart]) && isset($this->rels[$docPart][$rId])) {
            $target = $this->rels[$docPart][$rId]['target'];
        }

        return $target;
    }

    /**
     * Returns the target mode
     *
     * @param string $docPart
     * @param string $rId
     * @return string|null
     */
    private function getTargetMode($docPart, $rId)
    {
        $mode = null;

        if (isset($this->rels[$docPart]) && isset($this->rels[$docPart][$rId])) {
            $mode = $this->rels[$docPart][$rId]['targetMode'];
        }

        return $mode;
    }
}
