<?php

namespace App\Library\PhpOffice\PhpWord\Reader\Word2007;

use DateTime;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007\AbstractPart;
use PhpOffice\PhpWord\Shared\XMLReader;

class Comments extends AbstractPart
{
    /**
     * Collection name comments
     *
     * @var string
     */
    protected $collection = 'comments';

    /**
     * Read settings.xml.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(PhpWord $phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        //$xmlReader2 = new XMLReader();
        //$xmlReader2->getDomFromZip($this->docFile, 'word/document.xml');
        //dd($xmlReader2);

        $comments = $phpWord->getComments();

        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $name = str_replace('w:', '', $node->nodeName);
                $value = $xmlReader->getAttribute('w:author', $node);
                $author = $xmlReader->getAttribute('w:author', $node);
                $date = $xmlReader->getAttribute('w:date', $node);
                $initials = $xmlReader->getAttribute('w:initials', $node);
                $id = $xmlReader->getAttribute('w:id', $node);
                $element = new Comment($author, new DateTime($date), $initials);//$this->getElement($phpWord, $id);
                //$element->set
                // $range = $xmlReader2->getElements('.//*[("commentRangeStart"=local-name() or "commentRangeEnd"=local-name()) and @*[local-name()="id" and .="'.$id.'"]]');
                try {
                    unset($range);
                    $range = $phpWord->getCommentReference($id);
                    $range->start->setCommentRangeStart($element);
                    $range->end->setCommentRangeEnd($element);
                } catch(\Exception $e) {
                    //dd('range', [$element, $id, $node, $node->C14N(), $range ?? null, $e]);
                }
                //dd($startElement, $endElement, current(current($phpWord->getSections())->getElements()));
                //dump($element, $range);
                //dd($element, $node, $id, $node->C14N());
                $method = 'set' . $name;
                //dump([$element, $id, $name, $value, $author, $date, $initials, $method, $xmlReader->getElements('w:p/w:r/w:t', $node)]);
                //dd('dsf');
                $pNodes = $xmlReader->getElements('w:p/w:r', $node);
                foreach ($pNodes as $pNode) {
                    //dump(['>', $xmlReader, $pNode, $node, $this->collection, '<']);
                    $this->readRun($xmlReader, $pNode, $element, $this->collection);
                }

                /*if (in_array($name, $this::$booleanProperties)) {
                    if ($value == 'false') {
                        $comments->$method(false);
                    } else {
                        $comments->$method(true);
                    }
                } else*/if (method_exists($this, $method)) {
                    $this->$method($xmlReader, $phpWord, $node);
                } elseif (method_exists($comments, $method)) {
                    $comments->$method($value);
                } elseif (method_exists($phpWord, $method)) {
                    $phpWord->$method($value);
                } elseif (method_exists($comments, 'addItem')) {
                    $comments->addItem($element);
                }
            }
        }
	}

    /**
     * Searches for the element with the given relationId
     *
     * @param PhpWord $phpWord
     * @param int $relationId
     * @return \PhpOffice\PhpWord\Element\AbstractContainer|null
     */
    private function getElement(PhpWord $phpWord, $relationId)
    {
        $getMethod = "get{$this->collection}";
        //$getMethod = "getTrackChange";
        $collection = $phpWord->$getMethod();//->getItems();

        //not found by key, looping to search by relationId
        foreach ($collection as $collectionElement) {
            if ($collectionElement->getRelationId() == $relationId) {
                return $collectionElement;
            }
        }

        return null;
    }
}
