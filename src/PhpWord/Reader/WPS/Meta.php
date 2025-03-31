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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\WPS;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * WPS meta reader.
 *
 * @since 0.18.0
 */
class Meta extends AbstractPart
{
    /**
     * Read meta.xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $docProps = $phpWord->getDocInfo();

        // Title
        $title = $xmlReader->getValue('office:meta/dc:title');
        if (!empty($title)) {
            $docProps->setTitle($title);
        }

        // Subject
        $subject = $xmlReader->getValue('office:meta/dc:subject');
        if (!empty($subject)) {
            $docProps->setSubject($subject);
        }

        // Creator
        $creator = $xmlReader->getValue('office:meta/meta:initial-creator');
        if (!empty($creator)) {
            $docProps->setCreator($creator);
        }

        // Keywords
        $keywords = $xmlReader->getValue('office:meta/meta:keyword');
        if (!empty($keywords)) {
            $docProps->setKeywords($keywords);
        }

        // Description
        $description = $xmlReader->getValue('office:meta/dc:description');
        if (!empty($description)) {
            $docProps->setDescription($description);
        }

        // Category
        $category = $xmlReader->getValue('office:meta/meta:user-defined[@meta:name="Category"]');
        if (!empty($category)) {
            $docProps->setCategory($category);
        }

        // Company
        $company = $xmlReader->getValue('office:meta/meta:user-defined[@meta:name="Company"]');
        if (!empty($company)) {
            $docProps->setCompany($company);
        }
    }
}
