<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;
use PhpOffice\PhpWord\Writer\HTML\Style\Image as ImageStyleWriter;

/**
 * Image element HTML writer
 *
 * @since 0.10.0
 */
class Image extends Element
{
    /**
     * Write image
     *
     * @return string
     */
    public function write()
    {
        $html = '';
        if (!$this->element instanceof ImageElement) {
            return $html;
        }
        if (!$this->parentWriter->isPdf()) {
            $imageData = $this->getBase64ImageData($this->element);
            if (!is_null($imageData)) {
                $styleWriter = new ImageStyleWriter($this->element->getStyle());
                $style = $styleWriter->write();

                $html = "<img border=\"0\" style=\"{$style}\" src=\"{$imageData}\"/>";
                if (!$this->withoutP) {
                    $html = "<p>{$html}</p>" . PHP_EOL;
                }
            }
        }

        return $html;
    }

    /**
     * Get Base64 image data
     *
     * @return string|null
     */
    private function getBase64ImageData(ImageElement $element)
    {
        $source = $element->getSource();
        $imageType = $element->getImageType();
        $imageData = null;
        $imageBinary = null;
        $actualSource = null;

        // Get actual source from archive image or other source
        // Return null if not found
        if ($element->getSourceType() == ImageElement::SOURCE_ARCHIVE) {
            $source = substr($source, 6);
            list($zipFilename, $imageFilename) = explode('#', $source);

            $zipClass = \PhpOffice\PhpWord\Settings::getZipClass();
            $zip = new $zipClass();
            if ($zip->open($zipFilename) !== false) {
                if ($zip->locateName($imageFilename)) {
                    $zip->extractTo($this->parentWriter->getTempDir(), $imageFilename);
                    $actualSource = $this->parentWriter->getTempDir() . DIRECTORY_SEPARATOR . $imageFilename;
                }
            }
            $zip->close();
        } else {
            $actualSource = $source;
        }
        if (is_null($actualSource)) {
            return null;
        }

        // Read image binary data and convert into Base64
        if ($element->getSourceType() == ImageElement::SOURCE_GD) {
            $imageResource = call_user_func($element->getImageCreateFunction(), $actualSource);
            ob_start();
            call_user_func($element->getImageFunction(), $imageResource);
            $imageBinary = ob_get_contents();
            ob_end_clean();
        } else {
            if ($fileHandle = fopen($actualSource, 'rb', false)) {
                $imageBinary = fread($fileHandle, filesize($actualSource));
                fclose($fileHandle);
            }
        }
        if (!is_null($imageBinary)) {
            $base64 = chunk_split(base64_encode($imageBinary));
            $imageData = 'data:' . $imageType . ';base64,' . $base64;
        }

        return $imageData;
    }
}
