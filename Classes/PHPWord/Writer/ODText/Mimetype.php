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
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


/**
 * PHPWord_Writer_ODText_Mimetype
 *
 * @category   PHPWord
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Writer_ODText_Mimetype extends PHPWord_Writer_ODText_WriterPart
{
	/**
	 * Write Mimetype to Text format
	 *
	 * @param 	PHPWord $pPHPWord
	 * @return 	string 						Text Output
	 * @throws 	Exception
	 */
	public function writeMimetype(PHPWord $pPHPWord = null)
	{
		
		return 'application/vnd.oasis.opendocument.text';
	}

}
