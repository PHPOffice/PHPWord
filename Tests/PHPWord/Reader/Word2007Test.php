<?php
namespace PHPWord\Tests\Reader;

use PHPWord_Reader_Word2007;
use PHPWord_IOFactory;

/**
 * Class Word2007Test
 *
 * @package PHPWord\Tests
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    /** @var Test file directory */
    private $dir;

    /**
     * Init
     */
    public function tearDown()
    {
    }

    /**
     * Test canRead() method
     */
    public function testCanRead()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'documents')
        );
        $object = new PHPWord_Reader_Word2007;
        $file = $dir . DIRECTORY_SEPARATOR . 'reader.docx';
        $this->assertTrue($object->canRead($file));
    }

    /**
     * Test canRead() failure
     *
     * @expectedException Exception
     */
    public function testCanReadFailed()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'documents')
        );
        $object = new PHPWord_Reader_Word2007;
        $file = $dir . DIRECTORY_SEPARATOR . 'foo.docx';
        $this->assertFalse($object->canRead($file));
        $object = PHPWord_IOFactory::load($file);
    }

    /**
     * Test load document
     */
    public function testLoad()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'documents')
        );
        $file = $dir . DIRECTORY_SEPARATOR . 'reader.docx';
        $object = PHPWord_IOFactory::load($file);
        $this->assertInstanceOf('PHPWord', $object);
    }
}
