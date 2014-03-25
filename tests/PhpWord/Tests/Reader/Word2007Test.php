<?php
namespace PhpOffice\PhpWord\Tests\Reader;

use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWord\IOFactory;

class Word2007Test extends \PHPUnit_Framework_TestCase
{
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
        $object = new Word2007();
        $fqFilename = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'PhpWord', 'Tests', '_files', 'documents', 'reader.docx')
        );
        $this->assertTrue($object->canRead($fqFilename));
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testCanReadFailed()
    {
        $object = new Word2007();
        $fqFilename = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'PhpWord', 'Tests', '_files', 'documents', 'foo.docx')
        );
        $this->assertFalse($object->canRead($fqFilename));
        $object = IOFactory::load($fqFilename);
    }

    public function testLoad()
    {
        $fqFilename = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'PhpWord', 'Tests', '_files', 'documents', 'reader.docx')
        );
        $object = IOFactory::load($fqFilename);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object);
    }
}
