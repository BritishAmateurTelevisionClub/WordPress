<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: XObject.php 816 2016-02-12 08:50:35Z jan.slabon $
 */

/**
 * Abstract class representing an external object
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.com/ Commercial
 */
abstract class SetaPDF_Core_XObject implements SetaPDF_Core_Resource
{
    /**
     * An array caching XObject objects
     *
     * @var array
     */
    static protected $_xObjects = array();

    /**
     * The indirect object of the XObject
     *
     * @var SetaPDF_Core_Type_IndirectObject
     */
    protected $_indirectObject;


    /**
     * Release XObject instances by a document instance.
     *
     * @param SetaPDF_Core_Document $document
     */
    static public function freeCache(SetaPDF_Core_Document $document)
    {
        unset(self::$_xObjects[$document->getInstanceIdent()]);
    }

    /**
     * Get an external object by an indirect object/reference.
     *
     * @param SetaPDF_Core_Type_IndirectObjectInterface $xObjectReference
     * @param string $subType
     * @return SetaPDF_Core_XObject_Form|SetaPDF_Core_XObject_Image
     * @throws SetaPDF_Exception_NotImplemented
     */
    static public function get(SetaPDF_Core_Type_IndirectObjectInterface $xObjectReference, $subType = null)
    {
        $indirectObject = $xObjectReference;

        $documentIdent = $indirectObject->getOwnerPdfDocument()->getInstanceIdent();
        $ident = $indirectObject->getObjectIdent();
        if (isset(self::$_xObjects[$documentIdent][$ident])) {
            return self::$_xObjects[$documentIdent][$ident];
        }

        $xObjectDict = $indirectObject->ensure()->getValue();
        $subType = $subType ? $subType : $xObjectDict->getValue('Subtype')->getValue();
        
        switch ($subType) {
            case 'Image':
                $xObject = new SetaPDF_Core_XObject_Image($indirectObject);
                break;
            case 'Form':
                $xObject = new SetaPDF_Core_XObject_Form($indirectObject);
                break;
            default:
                throw new SetaPDF_Exception_NotImplemented('Not implemented yet. (XObject: ' . $subType . ')');
        }

        self::$_xObjects[$documentIdent][$ident] = $xObject;
        return $xObject;
    }

    /**
     * The constructor.
     *
     * @param SetaPDF_Core_Type_IndirectObjectInterface $indirectObject
     */
    public function __construct(SetaPDF_Core_Type_IndirectObjectInterface $indirectObject)
    {
        $this->_indirectObject = $indirectObject;
    }

    /**
     * Release memory and cycled references.
     */
    public function cleanUp()
    {
        $this->_indirectObject = null;
    }

    /**
     * Get the indirect object of this XObject.
     *
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getIndirectObject()
    {
        return $this->_indirectObject;
    }

    /**
     * Get the resource type for external objects.
     * 
     * @see SetaPDF_Core_Resource::getResourceType()
     * @return string
     */
    public function getResourceType()
    {
        return SetaPDF_Core_Resource::TYPE_X_OBJECT;
    }
    
    /**
     * Draw the external object on the canvas.
     *
     * @param SetaPDF_Core_Canvas $canvas
     * @param int $x
     * @param int $y
     * @param null|float $width
     * @param null|float $height
     * @return mixed
     */
    abstract public function draw(SetaPDF_Core_Canvas $canvas, $x = 0, $y = 0, $width = null, $height = null);

    /* it is not possible to implement an abstract method which also is defined in an interface by the implementing class...
    abstract function getHeight();
    
    abstract function getWidth();
    */
}