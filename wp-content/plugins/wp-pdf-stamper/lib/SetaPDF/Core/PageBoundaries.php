<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: PageBoundaries.php 816 2016-02-12 08:50:35Z jan.slabon $
 */

/**
 * Page Boundaries
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Core_PageBoundaries
{
    /**
     * MediaBox
     *
     * The media box defines the boundaries of the physical medium on which the page is to be printed.
     *
     * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
     * @var string
     */
    const MEDIA_BOX = 'MediaBox';

    /**
     * CropBox
     *
     * The crop box defines the region to which the contents of the page shall be clipped (cropped) when displayed or
     * printed.
     *
     * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
     * @var string
     */
    const CROP_BOX = 'CropBox';

    /**
     * BleedBox
     *
     * The bleed box defines the region to which the contents of the page shall be clipped when output in a
     * production environment.
     *
     * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
     * @var string
     */
    const BLEED_BOX = 'BleedBox';

    /**
     * TrimBox
     *
     * The trim box defines the intended dimensions of the finished page after trimming.
     *
     * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
     * @var string
     */
    const TRIM_BOX = 'TrimBox';

    /**
     * ArtBox
     *
     * The art box defines the extent of the page’s meaningful content (including potential white space) as intended
     * by the page’s creator.
     *
     * @see PDF 32000-1:2008 - 14.11.2 Page Boundaries
     * @var string
     */
    const ART_BOX = 'ArtBox';

    /**
     * All page boundaries
     *
     * @var array
     */
    static public $all = array(
        self::MEDIA_BOX, self::CROP_BOX, self::BLEED_BOX,
        self::TRIM_BOX, self::ART_BOX
    );

    /**
     * Checks if a name is a valid page boundary name.
     *
     * @param string $name The boundary name
     * @return boolean A boolean value whether the name is valid or not.
     */
    static public function isValidName($name)
    {
        return in_array($name, self::$all);
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