<?php
namespace PhpWord\Tests\Reader;

use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWord\IOFactory;

/**
 * @package PhpWord\Tests
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
            array(\PHPWORD_TESTS_BASE_DIR, '_files', 'documents')
        );
        $object = new Word2007;
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
            array(\PHPWORD_TESTS_BASE_DIR, '_files', 'documents')
        );
        $object = new Word2007;
        $file = $dir . DIRECTORY_SEPARATOR . 'foo.docx';
        $this->assertFalse($object->canRead($file));
        $object = IOFactory::load($file);
    }

    /**
     * Test load document
     */
    public function testLoad()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, '_files', 'documents')
        );
        $file = $dir . DIRECTORY_SEPARATOR . 'reader.docx';
        $object = IOFactory::load($file);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object);
    }
}