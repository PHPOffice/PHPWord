<?php
/**
 * PHPWord
 *
 * Copyright (c) 2009 - 2010 PHPWord
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
 * @package    PHPWord_Writer_PowerPoint2007
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

class PHPWord_Writer_ODText implements PHPWord_Writer_IWriter
{
	/**
	* Private PHPWord
	*
	* @var PHPWord
	*/
	private $_document;
	
	/**
	* Private writer parts
	*
	* @var PHPWord_Writer_ODText_WriterPart[]
	*/
	private $_writerParts;
	
	/**
	 * Private unique PHPWord_Worksheet_BaseDrawing HashTable
	 *
	 * @var PHPWord_HashTable
	 */
	private $_drawingHashTable;
	
	/**
	* Use disk caching where possible?
	*
	* @var boolean
	*/
	private $_useDiskCaching = false;
	
	/**
	 * Disk caching directory
	 *
	 * @var string
	 */
	private $_diskCachingDirectory;
	
	/**
	 * Create a new PHPWord_Writer_ODText
	 *
	 * @param 	PHPWord	$pPHPWord
	 */
	public function __construct(PHPWord $pPHPWord = null)
	{
		// Assign PHPWord
		$this->setPHPWord($pPHPWord);
		
		// Set up disk caching location
		$this->_diskCachingDirectory = './';
		
		// Initialise writer parts
		$this->_writerParts['content'] 	= new PHPWord_Writer_ODText_Content();
		$this->_writerParts['manifest'] = new PHPWord_Writer_ODText_Manifest();
		$this->_writerParts['meta'] 	= new PHPWord_Writer_ODText_Meta();
		$this->_writerParts['mimetype'] = new PHPWord_Writer_ODText_Mimetype();
		$this->_writerParts['styles'] 	= new PHPWord_Writer_ODText_Styles();
		
		
		// Assign parent IWriter
		foreach ($this->_writerParts as $writer) {
			$writer->setParentWriter($this);
		}
		
		// Set HashTable variables
		$this->_drawingHashTable 			= new PHPWord_HashTable();
	}
	
	/**
	 * Save PHPWord to file
	 *
	 * @param 	string 		$pFileName
	 * @throws 	Exception
	 */
	public function save($pFilename = null)
	{
		if (!is_null($this->_document)) {
			// If $pFilename is php://output or php://stdout, make it a temporary file...
			$originalFilename = $pFilename;
			if (strtolower($pFilename) == 'php://output' || strtolower($pFilename) == 'php://stdout') {
				$pFilename = @tempnam('./', 'phppttmp');
				if ($pFilename == '') {
					$pFilename = $originalFilename;
				}
			}
			
			// Create drawing dictionary
			
			// Create new ZIP file and open it for writing
			$objZip = new ZipArchive();
			
			// Try opening the ZIP file
			if ($objZip->open($pFilename, ZIPARCHIVE::OVERWRITE) !== true) {
				if ($objZip->open($pFilename, ZIPARCHIVE::CREATE) !== true) {
					throw new Exception("Could not open " . $pFilename . " for writing.");
				}
			}
			
			// Add mimetype to ZIP file
			//@todo Not in ZIPARCHIVE::CM_STORE mode
			$objZip->addFromString('mimetype', $this->getWriterPart('mimetype')->writeMimetype($this->_document));
			
			// Add content.xml to ZIP file
			$objZip->addFromString('content.xml', $this->getWriterPart('content')->writeContent($this->_document));

			// Add meta.xml to ZIP file
			$objZip->addFromString('meta.xml', $this->getWriterPart('meta')->writeMeta($this->_document));
			
			// Add styles.xml to ZIP file
			$objZip->addFromString('styles.xml', $this->getWriterPart('styles')->writeStyles($this->_document));
			
			// Add META-INF/manifest.xml
			$objZip->addFromString('META-INF/manifest.xml', $this->getWriterPart('manifest')->writeManifest($this->_document));
			
			// Add media
			for ($i = 0; $i < $this->getDrawingHashTable()->count(); ++$i) {
				if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_Drawing) {
					$imageContents = null;
					$imagePath = $this->getDrawingHashTable()->getByIndex($i)->getPath();
			
					if (strpos($imagePath, 'zip://') !== false) {
						$imagePath = substr($imagePath, 6);
						$imagePathSplitted = explode('#', $imagePath);
			
						$imageZip = new ZipArchive();
						$imageZip->open($imagePathSplitted[0]);
						$imageContents = $imageZip->getFromName($imagePathSplitted[1]);
						$imageZip->close();
						unset($imageZip);
					} else {
						$imageContents = file_get_contents($imagePath);
					}
			
					$objZip->addFromString('Pictures/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
				} else if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_MemoryDrawing) {
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
			
			// Close file
			if ($objZip->close() === false) {
				throw new Exception("Could not close zip file $pFilename.");
			}

			// If a temporary file was used, copy it to the correct file stream
			if ($originalFilename != $pFilename) {
				if (copy($pFilename, $originalFilename) === false) {
					throw new Exception("Could not copy temporary zip file $pFilename to $originalFilename.");
				}
				@unlink($pFilename);
			}
			
		} else {
			throw new Exception("PHPWord object unassigned.");
		}
	}
	
	/**
	 * Get PHPWord object
	 *
	 * @return PHPWord
	 * @throws Exception
	 */
	public function getPHPWord() {
		if (!is_null($this->_document)) {
			return $this->_document;
		} else {
			throw new Exception("No PHPWord assigned.");
		}
	}
	
	/**
	 * Get PHPWord object
	 *
	 * @param 	PHPWord 	$pPHPWord	PHPWord object
	 * @throws	Exception
	 * @return PHPWord_Writer_PowerPoint2007
	 */
	public function setPHPWord(PHPWord $pPHPWord = null) {
		$this->_document = $pPHPWord;
		return $this;
	}
	
	/**
	 * Get PHPWord_Worksheet_BaseDrawing HashTable
	 *
	 * @return PHPWord_HashTable
	 */
	public function getDrawingHashTable() {
		return $this->_drawingHashTable;
	}
	
	/**
	 * Get writer part
	 *
	 * @param 	string 	$pPartName		Writer part name
	 * @return 	PHPWord_Writer_ODText_WriterPart
	 */
	function getWriterPart($pPartName = '') {
		if ($pPartName != '' && isset($this->_writerParts[strtolower($pPartName)])) {
			return $this->_writerParts[strtolower($pPartName)];
		} else {
			return null;
		}
	}
	
	/**
	 * Get use disk caching where possible?
	 *
	 * @return boolean
	 */
	public function getUseDiskCaching() {
		return $this->_useDiskCaching;
	}
	
	/**
	 * Set use disk caching where possible?
	 *
	 * @param 	boolean 	$pValue
	 * @param	string		$pDirectory		Disk caching directory
	 * @throws	Exception	Exception when directory does not exist
	 * @return PHPWord_Writer_PowerPoint2007
	 */
	public function setUseDiskCaching($pValue = false, $pDirectory = null) {
		$this->_useDiskCaching = $pValue;
	
		if (!is_null($pDirectory)) {
			if (is_dir($pDirectory)) {
				$this->_diskCachingDirectory = $pDirectory;
			} else {
				throw new Exception("Directory does not exist: $pDirectory");
			}
		}
	
		return $this;
	}
	
	/**
	 * Get disk caching directory
	 *
	 * @return string
	 */
	public function getDiskCachingDirectory() {
		return $this->_diskCachingDirectory;
	}
}