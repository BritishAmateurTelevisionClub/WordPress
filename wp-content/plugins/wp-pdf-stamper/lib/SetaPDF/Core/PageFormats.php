<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: PageFormats.php 816 2016-02-12 08:50:35Z jan.slabon $
 */

/**
 * Class for getting and handling page formats
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Core_PageFormats
{
    /**
     * Page format constant
     *
     * @var string
     */
    const A3 = 'a3';

    /**
     * Page format constant
     *
     * @var string
     */
    const A4 = 'a4';

    /**
     * Page format constant
     *
     * @var string
     */
    const A5 = 'a5';

    /**
     * Page format constant
     *
     * @var string
     */
    const LETTER = 'letter';

    /**
     * Page format constant
     *
     * @var string
     */
    const LEGAL = 'legal';
    // more to come

    /**
     * Portrait orientation
     *
     * @var string
     */
    const ORIENTATION_PORTRAIT = 'portrait';

    /**
     * Landscape orientation
     *
     * @var string
     */
    const ORIENTATION_LANDSCAPE = 'landscape';

    /**
     * If this orientation is used the 0 key will be the width while 1 will hold the height
     *
     * @var string
     */
    const ORIENTATION_AUTO = 'auto';

    /**
     * Formats in default user space (points) in portrait orientation
     *
     * @var array width, height
     */
    static $formats = array(
        self::A3 => array(841.89, 1190.55),
        self::A4 => array(595.28, 841.89),
        self::A5 => array(420.94, 595.28),
        self::LETTER => array(612, 792),
        self::LEGAL => array(612, 1008)
    );

    /**
     * Returns a normalized format by a page format name or by an array.
     *
     * @param string|array $format The format as an array with 2 values or a pre-defined format constant
     * @param string $orientation The orientation
     * @return array Array where the keys '0' and 'width' are the width and keys '1' and 'height' are the height.
     * @throws InvalidArgumentException
     */
    static public function getFormat($format, $orientation = self::ORIENTATION_PORTRAIT)
    {
        if (is_array($format)) {
            if (!isset($format[0]) || !isset($format[1])) {
                throw new InvalidArgumentException(
                    'Parameter should be an array of 2 values.'
                );
            }

        } else {
            if (!isset(self::$formats[$format])) {
                throw new InvalidArgumentException(
                    sprintf('Unknown page format: %s', $format)
                );
            }

            $format = self::$formats[$format];
        }

        if (self::ORIENTATION_LANDSCAPE === $orientation) {
            $width = max($format);
            $height = min($format);
        } elseif (self::ORIENTATION_PORTRAIT === $orientation) {
            $width = min($format);
            $height = max($format);
        } elseif (self::ORIENTATION_AUTO === $orientation) {
            $width = $format[0];
            $height = $format[1];
        } else {
            throw new InvalidArgumentException(
                sprintf('Invalid orientate parameter: %s', $orientation)
            );
        }

        return array(
            0 => $width,
            1 => $height,
            'width' => $width,
            'height' => $height
        );
    }

    /**
     * Get a page format as a boundary rect as a SetaPDF_Core_Type_Array.
     *
     * @param string|array $format
     * @param string $orientation
     * @param string $boundaryName
     * @return SetaPDF_Core_Type_Array
     * @todo TEST THIS
     */
    static public function getAsBoundary(
        $format, $orientation = self::ORIENTATION_PORTRAIT, $boundaryName = null
    )
    {
        $boundary = null;

        // A complete boundary
        if ($format instanceof SetaPDF_Core_DataStructure_Rectangle) {
            $boundary = clone $format->getValue();

        } elseif (count($format) == 4) {
            list($llx, $lly, $urx, $ury) = $format;

            // Only format name or an array with 2 values
        } else {
            $format = self::getFormat($format, $orientation);
            $llx = 0;
            $lly = 0;
            list($urx, $ury) = $format;
        }

        if (null === $boundary) {
            $boundary = new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric($llx),
                new SetaPDF_Core_Type_Numeric($lly),
                new SetaPDF_Core_Type_Numeric($urx),
                new SetaPDF_Core_Type_Numeric($ury)
            ));
        }

        if (null === $boundaryName)
            return $boundary;
        else {
            return new SetaPDF_Core_Type_Dictionary_Entry(
                new SetaPDF_Core_Type_Name($boundaryName),
                $boundary
            );
        }
    }

    /**
     * Get the height of a page format.
     *
     * @param string|array $format
     * @param string $orientation
     * @return integer
     */
    static public function getHeight($format, $orientation = self::ORIENTATION_PORTRAIT)
    {
        $format = self::getFormat($format, $orientation);

        return $format[1];
    }

    /**
     * Get the width of a page format.
     *
     * @param string|array $format
     * @param string $orientation
     * @return integer
     */
    static public function getWidth($format, $orientation = self::ORIENTATION_PORTRAIT)
    {
        $format = self::getFormat($format, $orientation);

        return $format[0];
    }

    /**
     * Prohibit object initiation by defining the constructor to be private.
     *
     * @internal
     */
    private function __construct()
    {
    }
}