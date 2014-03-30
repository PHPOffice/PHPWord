<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\HashTable;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\ODText\Content;
use PhpOffice\PhpWord\Writer\ODText\Manifest;
use PhpOffice\PhpWord\Writer\ODText\Meta;
use PhpOffice\PhpWord\Writer\ODText\Mimetype;
use PhpOffice\PhpWord\Writer\ODText\Styles;

/**
 * ODText writer
 */
class ODText extends Writer implements IWriter
{
    /**
     * Private unique PHPWord_Worksheet_BaseDrawing HashTable
     *
     * @var HashTable
     */
    private $drawingHashTable;

    /**
     * Create new ODText writer
     * @param PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Set writer parts
        $this->writerParts['content'] = new Content();
        $this->writerParts['manifest'] = new Manifest();
        $this->writerParts['meta'] = new Meta();
        $this->writerParts['mimetype'] = new Mimetype();
        $this->writerParts['styles'] = new Styles();
        foreach ($this->writerParts as $writer) {
            $writer->setParentWriter($this);
        }

        // Set HashTable variables
        $this->drawingHashTable = new HashTable();
    }

    /**
     * Save PhpWord to file
     *
     * @param  string $pFilename
     * @throws Exception
     */
    public function save($pFilename = null)
    {
        if (!is_null($this->phpWord)) {
            $pFilename = $this->getTempFile($pFilename);

            // Create new ZIP file and open it for writing
            $zipClass = Settings::getZipClass();
            $objZip = new $zipClass();

            // Retrieve OVERWRITE and CREATE constants from the instantiated zip class
            // This method of accessing constant values from a dynamic class should work with all appropriate versions of PHP
            $ro = new \ReflectionObject($objZip);
            $zipOverWrite = $ro->getConstant('OVERWRITE');
            $zipCreate = $ro->getConstant('CREATE');

            // Remove any existing file
            if (file_exists($pFilename)) {
                unlink($pFilename);
            }

            // Try opening the ZIP file
            if ($objZip->open($pFilename, $zipOverWrite) !== true) {
                if ($objZip->open($pFilename, $zipCreate) !== true) {
                    throw new Exception("Could not open " . $pFilename . " for writing.");
                }
            }

            // Add mimetype to ZIP file
            //@todo Not in \ZipArchive::CM_STORE mode
            $objZip->addFromString('mimetype', $this->getWriterPart('mimetype')->writeMimetype($this->phpWord));

            // Add content.xml to ZIP file
            $objZip->addFromString('content.xml', $this->getWriterPart('content')->writeContent($this->phpWord));

            // Add meta.xml to ZIP file
            $objZip->addFromString('meta.xml', $this->getWriterPart('meta')->writeMeta($this->phpWord));

            // Add styles.xml to ZIP file
            $objZip->addFromString('styles.xml', $this->getWriterPart('styles')->writeStyles($this->phpWord));

            // Add META-INF/manifest.xml
            $objZip->addFromString('META-INF/manifest.xml', $this->getWriterPart('manifest')->writeManifest($this->phpWord));

            // Add media. Has not used yet. Legacy from PHPExcel.
            // @codeCoverageIgnoreStart
            for ($i = 0; $i < $this->getDrawingHashTable()->count(); ++$i) {
                if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_Drawing) {
                    $imageContents = null;
                    $imagePath = $this->getDrawingHashTable()->getByIndex($i)->getPath();

                    if (strpos($imagePath, 'zip://') !== false) {
                        $imagePath = substr($imagePath, 6);
                        $imagePathSplitted = explode('#', $imagePath);

                        $zipClass = Settings::getZipClass();
                        $imageZip = new $zipClass();
                        $imageZip->open($imagePathSplitted[0]);
                        $imageContents = $imageZip->getFromName($imagePathSplitted[1]);
                        $imageZip->close();
                        unset($imageZip);
                    } else {
                        $imageContents = file_get_contents($imagePath);
                    }

                    $objZip->addFromString('Pictures/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
                } elseif ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $this->getDrawingHashTable()->getByIndex($i)->getRenderingFunction(),
                        $this->getDrawingHashTable()->getByIndex($i)->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();

                    $objZip->addFromString('Pictures/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
                }
            }
            // @codeCoverageIgnoreEnd

            // Close file
            if ($objZip->close() === false) {
                throw new Exception("Could not close zip file $pFilename.");
            }

            $this->cleanupTempFile();
        } else {
            throw new Exception("PhpWord object unassigned.");
        }
    }

    /**
     * Get PHPWord_Worksheet_BaseDrawing HashTable
     *
     * @return HashTable
     */
    public function getDrawingHashTable()
    {
        return $this->drawingHashTable;
    }
}
