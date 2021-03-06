<?php 
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: Annotations.php 816 2016-02-12 08:50:35Z jan.slabon $
 */

/**
 * Helper class for handling annotations of a page
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotations
{
    /**
     * The page object
     * 
     * @var SetaPDF_Core_Document_Page
     */
    protected $_page;
    
    /**
     * The constructor.
     * 
     * @param SetaPDF_Core_Document_Page $page
     */
    public function __construct(SetaPDF_Core_Document_Page $page)
    {
        $this->_page = $page;
    }

    /**
     * Release memory/resources.
     */
    public function cleanUp()
    {
        $this->_page = null;
    }

    /**
     * Get the page.
     *
     * @return SetaPDF_Core_Document_Page
     */
    public function getPage()
    {
        return $this->_page;
    }
    
    /**
     * Returns the Annots array if available or creates a new one.
     *
     * @param boolean $create
     * @return false|SetaPDF_Core_Type_Array
     */
    public function getArray($create = false)
    {
        $pageDict = $this->_page->getPageObject(true)->ensure(true);
        
        if (false === $pageDict->offsetExists('Annots')) {
        	if (false === $create)
        		return false;
        
        	$pageDict->offsetSet('Annots', new SetaPDF_Core_Type_Array());
        }
        
        return $pageDict->offsetGet('Annots')->ensure();
    }
    
    /**
     * Get all annotations of this page.
     *
     * Optionally the results can be filtered by the subtype parameter.
     * 
     * @param string $subtype See SetaPDF_Core_Document_Page_Annotation::TYPE_* constants for possible values.
     * @return SetaPDF_Core_Document_Page_Annotation[]
     */
    public function getAll($subtype = null)
    {
    	$annotationsArray = $this->getArray();
    	if (false === $annotationsArray)
    		return array();
    
    	$annotations = array();
    	foreach ($annotationsArray AS $annotationValue) {
    		$annotationDictionary = $annotationValue->ensure(true);
    		if (null === $subtype || SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($annotationDictionary, 'Subtype', $subtype))
    			$annotations[] = SetaPDF_Core_Document_Page_Annotation::byObjectOrDictionary($annotationValue);
    	}
    
    	return $annotations;
    }

    /**
     * Get an annotation by its name (NM entry)
     *
     * @param string $name The name of the annotation.
     * @param string $encoding
     *
     * @return bool|SetaPDF_Core_Document_Page_Annotation
     */
    public function getByName($name, $encoding = 'UTF-8')
    {
        $annotationsArray = $this->getArray();
        if (false === $annotationsArray)
            return false;

        foreach ($annotationsArray AS $annotationValue) {
            $annotationDictionary = $annotationValue->ensure(true);
            if (!$annotationDictionary->offsetExists('NM'))
                continue;

            if (SetaPDF_Core_Encoding::convertPdfString(
                $annotationDictionary->getValue('NM')->getValue(), $encoding
            ) == $name) {
                return SetaPDF_Core_Document_Page_Annotation::byObjectOrDictionary($annotationValue);
            }
        }

        return false;
    }

    /**
     * Adds an annotation to the page.
     *
     * @param SetaPDF_Core_Document_Page_Annotation $annotation
     * @return SetaPDF_Core_Type_IndirectObjectInterface
     */
    public function add(SetaPDF_Core_Document_Page_Annotation $annotation)
    {
        $annotationsArray = $this->getArray(true);
        $object = $annotation->getIndirectObject();

        if (null === $object) {
            $document = $this->_page->getPageObject(true)->getOwnerPdfDocument();
            $object = $document->createNewObject($annotation->getAnnotationDictionary());
            $annotation->setIndirectObject($object);
        }

        $annotationsArray->offsetSet(null, $object);
        
        return $object;
    }

    /**
     * Removes an annotation from the annotation array of the page.
     *
     * @param SetaPDF_Core_Document_Page_Annotation $annotation
     * @return bool
     */
    public function remove(SetaPDF_Core_Document_Page_Annotation $annotation)
    {
        $annotationsArray = $this->getArray();
        if (false === $annotationsArray)
            return false;

        $object = $annotation->getIndirectObject();
        if ($object) {
            $document = $this->_page->getPageObject(true)->getOwnerPdfDocument();
            if ($document->getInstanceIdent() !== $object->getOwnerPdfDocument()->getInstanceIdent()) {
                return false;
            }

            foreach ($annotationsArray AS $key => $annotationValue) {
                if ($annotationValue instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
                    if ($annotationValue->getObjectIdent() === $object->getObjectIdent()) {
                        $annotationsArray->offsetUnset($key);
                        return true;
                    }
                }
            }

        } else {
            $value = $annotation->getAnnotationDictionary()->toPhp();

            foreach ($annotationsArray AS $key => $annotationValue) {
                if (!($annotationValue instanceof SetaPDF_Core_Type_IndirectObjectInterface)) {
                    if ($annotationValue->toPhp() === $value) {
                        $annotationsArray->offsetUnset($key);
                        return true;
                    }
                }
            }
        }

        return false;
    }
}