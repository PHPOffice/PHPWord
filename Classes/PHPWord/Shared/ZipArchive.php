<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

if (!defined('PCLZIP_TEMPORARY_DIR')) {
    // PCLZIP needs the temp path to end in a back slash
    define('PCLZIP_TEMPORARY_DIR', PHPWord_Shared_File::sys_get_temp_dir().'/');
}
require_once(PHPWORD_BASE_PATH . 'PHPWord/Shared/PCLZip/pclzip.lib.php');


/**
 * PHPWord_Shared_ZipArchive
 *
 * @category   PHPWord
 * @package    PHPWord_Shared_ZipArchive
 * @copyright  Copyright (c) 2006 - 2014 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Shared_ZipArchive {

    /** constants */
    const OVERWRITE = 'OVERWRITE';
    const CREATE    = 'CREATE';

    /**
     * Temporary storage directory
     *
     * @var string
     */
    private $_tempDir;

    /**
     * Zip Archive Stream Handle
     *
     * @var string
     */
    private $_zip;

    /**
     * Open a new zip archive
     *
     * @param  string  $fileName Filename for the zip archive
     * @return boolean
     */
    public function open($fileName) {
        $this->_tempDir = PHPWord_Shared_File::sys_get_temp_dir();
        $this->_zip = new PclZip($fileName);

        return true;
    }

    /**
     * Close this zip archive
     *
     */
    public function close() {
    }

    /**
     * Add a new file to the zip archive.
     *
     * @param string $filename  Directory/Name of the file to add to the zip archive
     * @param string $localname Directory/Name of the file added to the zip
     */
    public function addFile($filename, $localname = NULL) {
        $filenameParts = pathinfo($filename);
        $localnameParts = pathinfo($localname);

        // To Rename the file while adding it to the zip we
        //   need to create a temp file with the correct name
        if ($filenameParts['basename'] != $localnameParts['basename']) {
            $temppath = $this->_tempDir.'/'.$localnameParts['basename'];
            copy($filename, $temppath);
            $filename = $temppath;
            $filenameParts = pathinfo($temppath);
        }

        $res = $this->_zip->add($filename,
                                PCLZIP_OPT_REMOVE_PATH, $filenameParts['dirname'],
                                PCLZIP_OPT_ADD_PATH, $localnameParts["dirname"]
                               );

        if ($res == 0) {
            throw new PHPWord_Writer_Exception("Error zipping files : " . $this->_zip->errorInfo(true));
            return false;
        }

        return true;
    }

    /**
     * Add a new file to the zip archive from a string of raw data.
     *
     * @param string $localname Directory/Name of the file to add to the zip archive
     * @param string $contents  String of data to add to the zip archive
     */
    public function addFromString($localname, $contents) {
        $filenameParts = pathinfo($localname);

        // Write $contents to a temp file
        $handle = fopen($this->_tempDir.'/'.$filenameParts["basename"], "wb");
        fwrite($handle, $contents);
        fclose($handle);

        // Add temp file to zip
        $res = $this->_zip->add($this->_tempDir.'/'.$filenameParts["basename"],
                                PCLZIP_OPT_REMOVE_PATH, $this->_tempDir,
                                PCLZIP_OPT_ADD_PATH, $filenameParts["dirname"]
                               );
        if ($res == 0) {
            throw new PHPWord_Writer_Exception("Error zipping files : " . $this->_zip->errorInfo(true));
            return false;
        }

        // Remove temp file
        unlink($this->_tempDir.'/'.$filenameParts["basename"]);

        return true;
    }

    /**
     * Find if given fileName exist in archive (Emulate ZipArchive locateName())
     *
     * @param  string  $fileName Filename for the file in zip archive
     * @return boolean
     */
    public function locateName($fileName) {
        $list = $this->_zip->listContent();
        $listCount = count($list);
        $list_index = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                $list_index = $i;
                break;
            }
        }
        return ($list_index > -1);
    }

    /**
     * Extract file from archive by given fileName (Emulate ZipArchive getFromName())
     *
     * @param  string $fileName Filename for the file in zip archive
     * @return string $contents File string contents
     */
    public function getFromName($fileName) {
        $list = $this->_zip->listContent();
        $listCount = count($list);
        $list_index = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                $list_index = $i;
                break;
            }
        }

        $extracted = "";
        if ($list_index != -1) {
            $extracted = $this->_zip->extractByIndex($list_index, PCLZIP_OPT_EXTRACT_AS_STRING);
        } else {
            $filename = substr($fileName, 1);
            $list_index = -1;
            for ($i = 0; $i < $listCount; ++$i) {
                if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                    strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                    $list_index = $i;
                    break;
                }
            }
            $extracted = $this->_zip->extractByIndex($list_index, PCLZIP_OPT_EXTRACT_AS_STRING);
        }
        if ((is_array($extracted)) && ($extracted != 0)) {
            $contents = $extracted[0]["content"];
        }

        return $contents;
    }

}
