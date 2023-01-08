<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use DateTime;
use InvalidArgumentException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\AbstractStyle;

/**
 * Element abstract class.
 *
 * @since 0.10.0
 */
abstract class AbstractElement
{
    /**
     * PhpWord object.
     *
     * @var null|PhpWord
     */
    protected $phpWord;

    /**
     * Section Id.
     *
     * @var null|int
     */
    protected $sectionId;

    /**
     * Document part type: Section|Header|Footer|Footnote|Endnote.
     * Used by textrun and cell container to determine where the element is
     * located because it will affect the availability of other element,
     * e.g. footnote will not be available when $docPart is header or footer.
     *
     * @var string
     */
    protected $docPart = 'Section';

    /**
     * Document part Id.
     * For header and footer, this will be = ($sectionId - 1) * 3 + $index
     * because the max number of header/footer in every page is 3, i.e.
     * AUTO, FIRST, and EVEN (AUTO = ODD).
     *
     * @var int
     */
    protected $docPartId = 1;

    /**
     * Index of element in the elements collection (start with 1).
     *
     * @var int
     */
    protected $elementIndex = 1;

    /**
     * Unique Id for element.
     *
     * @var null|string
     */
    protected $elementId;

    /**
     * Relation Id.
     *
     * @var null|int
     */
    protected $relationId;

    /**
     * Depth of table container nested level; Primarily used for RTF writer/reader.
     * 0 = Not in a table; 1 = in a table; 2 = in a table inside another table, etc.
     *
     * @var null|int
     */
    private $nestedLevel = 0;

    /**
     * A reference to the parent.
     *
     * @var null|AbstractElement
     */
    private $parent;

    /**
     * changed element info.
     *
     * @var null|TrackChange
     */
    private $trackChange;

    /**
     * Has media relation flag; true for Link, Image, and Object.
     *
     * @var bool
     */
    protected $mediaRelation = false;

    /**
     * Is part of collection; true for Title, Footnote, Endnote, Chart, and Comment.
     *
     * @var bool
     */
    protected $collectionRelation = false;

    /**
     * The start position for the linked comment.
     *
     * @var null|Comment
     */
    protected $commentRangeStart;

    /**
     * The end position for the linked comment.
     *
     * @var null|Comment
     */
    protected $commentRangeEnd;

    /**
     * Get PhpWord.
     */
    public function getPhpWord(): ?PhpWord
    {
        return $this->phpWord;
    }

    /**
     * Set PhpWord as reference.
     */
    public function setPhpWord(?PhpWord $phpWord = null): void
    {
        $this->phpWord = $phpWord;
    }

    /**
     * Get section number.
     */
    public function getSectionId(): ?int
    {
        return $this->sectionId;
    }

    /**
     * Set doc part.
     */
    public function setDocPart(string $docPart, int $docPartId = 1): void
    {
        $this->docPart = $docPart;
        $this->docPartId = $docPartId;
    }

    /**
     * Get doc part.
     */
    public function getDocPart(): ?string
    {
        return $this->docPart;
    }

    /**
     * Get doc part Id.
     */
    public function getDocPartId(): ?int
    {
        return $this->docPartId;
    }

    /**
     * Return media element (image, object, link) container name.
     *
     * @return string section|headerx|footerx|footnote|endnote
     */
    private function getMediaPart(): string
    {
        $mediaPart = $this->docPart;
        if ($mediaPart == 'Header' || $mediaPart == 'Footer') {
            $mediaPart .= $this->docPartId;
        }

        return strtolower($mediaPart);
    }

    /**
     * Get element index.
     */
    public function getElementIndex(): int
    {
        return $this->elementIndex;
    }

    /**
     * Set element index.
     */
    public function setElementIndex(int $value): void
    {
        $this->elementIndex = $value;
    }

    /**
     * Get element unique ID.
     */
    public function getElementId(): ?string
    {
        return $this->elementId;
    }

    /**
     * Set element unique ID from 6 first digit of md5.
     */
    public function setElementId(): void
    {
        $this->elementId = substr(md5(mt_rand()), 0, 6);
    }

    /**
     * Get relation Id.
     */
    public function getRelationId(): ?int
    {
        return $this->relationId;
    }

    /**
     * Set relation Id.
     */
    public function setRelationId(int $value): void
    {
        $this->relationId = $value;
    }

    /**
     * Get nested level.
     */
    public function getNestedLevel(): int
    {
        return $this->nestedLevel;
    }

    /**
     * Get comment start.
     */
    public function getCommentRangeStart(): ?Comment
    {
        return $this->commentRangeStart;
    }

    /**
     * Set comment start.
     */
    public function setCommentRangeStart(Comment $value): void
    {
        if ($this instanceof Comment) {
            throw new InvalidArgumentException('Cannot set a Comment on a Comment');
        }
        $this->commentRangeStart = $value;
        $this->commentRangeStart->setStartElement($this);
    }

    /**
     * Get comment end.
     */
    public function getCommentRangeEnd(): ?Comment
    {
        return $this->commentRangeEnd;
    }

    /**
     * Set comment end.
     */
    public function setCommentRangeEnd(Comment $value): void
    {
        if ($this instanceof Comment) {
            throw new InvalidArgumentException('Cannot set a Comment on a Comment');
        }
        $this->commentRangeEnd = $value;
        $this->commentRangeEnd->setEndElement($this);
    }

    /**
     * Get parent element.
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * Set parent container.
     * Passed parameter should be a container, except for Table (contain Row) and Row (contain Cell).
     *
     * @param AbstractElement $container
     */
    public function setParentContainer(self $container): void
    {
        $parentContainer = substr(get_class($container), strrpos(get_class($container), '\\') + 1);
        $this->parent = $container;

        // Set nested level
        $this->nestedLevel = $container->getNestedLevel();
        if ($parentContainer == 'Cell') {
            ++$this->nestedLevel;
        }

        // Set phpword
        $this->setPhpWord($container->getPhpWord());

        // Set doc part
        if (!$this instanceof Footnote) {
            $this->setDocPart($container->getDocPart(), $container->getDocPartId());
        }

        $this->setMediaRelation();
        $this->setCollectionRelation();
    }

    /**
     * Set relation Id for media elements (link, image, object; legacy of OOXML).
     * - Image element needs to be passed to Media object
     * - Icon needs to be set for Object element.
     */
    private function setMediaRelation(): void
    {
        if (!$this instanceof Link && !$this instanceof Image && !$this instanceof OLEObject) {
            return;
        }

        $elementName = substr(static::class, strrpos(static::class, '\\') + 1);
        if ($elementName == 'OLEObject') {
            $elementName = 'Object';
        }
        $mediaPart = $this->getMediaPart();
        $source = $this->getSource();
        $image = null;
        if ($this instanceof Image) {
            $image = $this;
        }
        $rId = Media::addElement($mediaPart, strtolower($elementName), $source, $image);
        $this->setRelationId($rId);

        if ($this instanceof OLEObject) {
            $icon = $this->getIcon();
            $rId = Media::addElement($mediaPart, 'image', $icon, new Image($icon));
            $this->setImageRelationId($rId);
        }
    }

    /**
     * Set relation Id for elements that will be registered in the Collection subnamespaces.
     */
    private function setCollectionRelation(): void
    {
        if ($this->collectionRelation === true && $this->phpWord instanceof PhpWord) {
            $elementName = substr(static::class, strrpos(static::class, '\\') + 1);
            $addMethod = "add{$elementName}";
            $rId = $this->phpWord->$addMethod($this);
            $this->setRelationId($rId);
        }
    }

    /**
     * Check if element is located in Section doc part (as opposed to Header/Footer).
     */
    public function isInSection(): bool
    {
        return $this->docPart === 'Section';
    }

    /**
     * Set new style value.
     *
     * @param mixed $styleObject  Style object
     * @param mixed $styleValue   Style value
     * @param bool  $returnObject Always return object
     *
     * @return mixed
     */
    protected function setNewStyle($styleObject, $styleValue = null, bool $returnObject = false)
    {
        if ($styleValue instanceof AbstractStyle) {
            return $styleValue;
        }

        if (is_array($styleValue)) {
            $styleObject->setStyleByArray($styleValue);
            $style = $styleObject;
        } else {
            $style = $returnObject ? $styleObject : $styleValue;
        }

        return $style;
    }

    /**
     * Sets the trackChange information.
     */
    public function setTrackChange(TrackChange $trackChange): void
    {
        $this->trackChange = $trackChange;
    }

    /**
     * Gets the trackChange information.
     *
     * @return TrackChange
     */
    public function getTrackChange(): ?TrackChange
    {
        return $this->trackChange;
    }

    /**
     * Set changed.
     *
     * @param string            $type INSERTED|DELETED
     * @param null|DateTime|int $date allways in UTC
     */
    public function setChangeInfo(string $type, string $author, $date = null): void
    {
        $this->trackChange = new TrackChange($type, $author, $date);
    }

    /**
     * Set enum value.
     *
     * @todo Merge with the same method in AbstractStyle
     */
    protected function setEnumVal(?string $value = null, array $enum = [], ?string $default = null): ?string
    {
        if ($value !== null && trim($value) !== '' && !empty($enum) && !in_array($value, $enum)) {
            throw new InvalidArgumentException("Invalid style value: {$value}");
        } elseif ($value === null || trim($value) == '') {
            $value = $default;
        }

        return $value;
    }
}
