<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: Cmap.php 856 2016-06-07 10:53:06Z jan.slabon $
 */

/**
 * Class representing a CMAP.
 *
 * This class includes a very simple parser for CID data. The extracted data are limited
 * to unicode and cid mappings.
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Font_Cmap
{

    /**
     * Code space ranges.
     *
     * @var array
     */
    protected $_codeSpaceRanges = array();

    /**
     * CID and uncidoe mappings.
     *
     * @var array
     */
    protected $_mappings = array(
        'uni' => array(
            'single' => array(),
            'range' => array()
        ),
        'cid' => array(
            'single' => array(),
            'range' => array()
        ),
    );

    /**
     * The name resolved from the CMAP file.
     *
     * @var string
     */
    protected $_name;

    /**
     * A separate CMAP instance of only CID mappings.
     *
     * @var SetaPDF_Core_Font_Cmap
     */
    protected $_cidMap;

    /**
     * Resolved data for an optimization of a reverse lookup.
     *
     * @var array
     */
    public $_lookUps = array('uni' => array(), 'cid' => array());

    /**
     * Creates an instance of an existing CMAP.
     *
     * Existing CMAPs can be found in /SetaPDF/Font/Cmap/_cmaps/.
     *
     * @param $name
     * @param bool $cache
     *
     * @return mixed|null|SetaPDF_Core_Font_Cmap
     */
    static public function createNamed($name, $cache = false)
    {
        $name = preg_replace('/[^a-z0-9_\-]/i', '', $name);
        $dir = dirname(__FILE__) . '/Cmap/_cmaps/';
        $path = realpath($dir . $name);

        if (false === $path || !is_readable($path)) {
            throw new InvalidArgumentException('Unable to load cmap file for "' . $name . '"');
        }

        if ($cache && file_exists($path . '.cache')) {
            $cmap = unserialize(file_get_contents($path . '.cache'));
        } else {
            $cmap = self::create(new SetaPDF_Core_Reader_File($path));
            if ($cache && is_writeable($dir)) {
                file_put_contents($path . '.cache', serialize($cmap));
            }
        }

        return $cmap;
    }

    /**
     * Create an instance based on CMAP data through an reader instance.
     *
     * @param SetaPDF_Core_Reader_AbstractReader $reader
     * @return null|SetaPDF_Core_Font_Cmap
     */
    static public function create(SetaPDF_Core_Reader_AbstractReader $reader)
    {
        $tokenizer = new SetaPDF_Core_Tokenizer($reader);
        $cmap = null;
        $stack = array();

        while (($token = $tokenizer->readToken()) !== false) {
            switch ($token) {
                case '%':
                    $tokenizer->getReader()->readLine();
                    continue;
                case 'usecmap':
                    $name = array_pop($stack);
                    $tmpCmap = self::createNamed($name);
                    $cmap->_mappings = $tmpCmap->_mappings;
                    $cmap->_codeSpaceRanges = $tmpCmap->_codeSpaceRanges;
                    unset($tmpCmap);
                    $stack = array();
                    break;
                case 'begincmap':
                    $cmap = new self();
                    break;
                case 'endcmap':
                    break 2;
                case 'begincodespacerange':
                    $values = array();
                    while (($value = self::_readValue($tokenizer)) !== 'endcodespacerange' && false !== $value) {
                        $values[] = $value;
                    }

                    self::_checkForCMapInstance($cmap);

                    for ($i = 0; $i < count($values); $i += 2) {
                        $cmap->addCodeSpaceRange(
                            SetaPDF_Core_Type_HexString::hex2str($values[$i]),
                            SetaPDF_Core_Type_HexString::hex2str($values[$i + 1])
                        );
                    }
                    break;
                case 'beginbfchar':
                    $values = array();
                    while (($value = self::_readValue($tokenizer)) !== 'endbfchar' && false !== $value) {
                        $values[] = $value;
                    }

                    self::_checkForCMapInstance($cmap);

                    for ($i = 0; $i < count($values); $i += 2) {
                        $cmap->addSingleMapping(
                            SetaPDF_Core_Type_HexString::hex2str($values[$i]),
                            SetaPDF_Core_Type_HexString::hex2str($values[$i + 1])
                        );
                    }
                    break;
                case 'beginbfrange':
                    $values = array();
                    while (($value = self::_readValue($tokenizer)) !== 'endbfrange' && false !== $value) {
                        $values[] = $value;
                    }

                    self::_checkForCMapInstance($cmap);

                    for ($i = 0; $i < count($values); $i += 3) {
                        $cmap->addRangeMapping(
                            hexdec($values[$i]),
                            hexdec($values[$i + 1]),
                            $values[$i + 2],
                            strlen($values[$i]) / 2
                        );
                    }
                    break;
                case 'begincidchar':
                    $values = array();
                    while (($value = self::_readValue($tokenizer)) !== 'endcidchar' && false !== $value) {
                        $values[] = $value;
                    }

                    self::_checkForCMapInstance($cmap);

                    for ($i = 0; $i < count($values); $i += 2) {
                        $cmap->addCidSingleMapping(
                            SetaPDF_Core_Type_HexString::hex2str($values[$i]),
                            $values[$i + 1]
                        );
                    }

                    break;
                case 'begincidrange':
                    $values = array();
                    while (($value = self::_readValue($tokenizer)) !== 'endcidrange' && false !== $value) {
                        $values[] = $value;
                    }

                    self::_checkForCMapInstance($cmap);

                    for ($i = 0; $i < count($values); $i += 3) {
                        $cmap->addCidRangeMapping(
                            hexdec($values[$i]),
                            hexdec($values[$i + 1]),
                            $values[$i + 2],
                            strlen($values[$i]) / 2
                        );
                    }
                    break;

                case 'def':
                    $lenght = count($stack);
                    if ($lenght > 2) {
                        if ($stack[$lenght - 3] === 'CMapName') {
                            self::_checkForCMapInstance($cmap);
                            $cmap->_name = $stack[$lenght - 1];
                        }
                        $stack = array();
                        break;
                    }

                default:
                    $stack[] = $token;
                    continue;
            }
        }

        return $cmap;
    }

    /**
     * Helper method that throws an exception if the given parameter is not an instance of self.
     * 
     * @param mixed $cmap
     * @return bool
     * @throws SetaPDF_Core_Font_Exception
     */
    static protected function _checkForCMapInstance($cmap)
    {
        if (!$cmap instanceof SetaPDF_Core_Font_Cmap) {
            throw new SetaPDF_Core_Font_Exception('No "begincmap" token ever found. This cmap table is corrupted.');
        }

        return true;
    }

    /**
     * Read the next value via the tokenizer instance.
     *
     * @param SetaPDF_Core_Tokenizer $tokenizer
     * @return array|string
     */
    static protected function _readValue(SetaPDF_Core_Tokenizer $tokenizer)
    {
        $token = $tokenizer->readToken();

        switch ($token) {
            case '<':
                $values = array();
                while (($value = self::_readValue($tokenizer)) !== '>') {
                    $values[] = $value;
                }

                return join('', $values);

            case '[':
                $values = array();
                while (($value = self::_readValue($tokenizer)) !== ']') {
                    $values[] = $value;
                }
                return $values;
                break;
            default:
                return $token;
        }
    }

    /**
     * Add a codespace range.
     *
     * @param string $start
     * @param string $end
     */
    public function addCodeSpaceRange($start, $end)
    {
        $this->_codeSpaceRanges[strlen($start)] = array($start, $end);
    }

    /**
     * Add a single mapping.
     *
     * @param string $src
     * @param string $dst
     */
    public function addSingleMapping($src, $dst)
    {
        $this->_mappings['uni']['single'][$src] = $dst;
    }

    /**
     * Add a range mapping.
     *
     * @param integer $src1
     * @param integer $src2
     * @param string $dst
     * @param integer $size
     */
    public function addRangeMapping($src1, $src2, $dst, $size)
    {
        $this->_mappings['uni']['range'][$size][] = array($src1 , $src2, $dst);
    }

    /**
     * Add a single cid mapping.
     *
     * @param string $src
     * @param string $dst
     */
    public function addCidSingleMapping($src, $dst)
    {
        $this->_mappings['cid']['single'][$src] = $dst;
    }

    /**
     * Add a cid range mapping.
     *
     * @param integer $src1
     * @param integer $src2
     * @param string $dst
     * @param integer $size
     */
    public function addCidRangeMapping($src1, $src2, $dst, $size)
    {
        $this->_mappings['cid']['range'][$size][] = array($src1 , $src2, $dst);
    }

    /**
     * Copy CID Mapping from another map into this instance.
     *
     * @param SetaPDF_Core_Font_Cmap $map
     */
    public function copyCidRangeMapping(self $map)
    {
        foreach ($map->_mappings['cid']['range'] AS $size => $data) {
            $this->_mappings['cid']['range'][$size] = $data;
        }
    }

    /**
     * Set the CID map instance.
     *
     * @param SetaPDF_Core_Font_Cmap $cidMap
     */
    public function setCidMap(SetaPDF_Core_Font_Cmap $cidMap)
    {
        $this->_cidMap = $cidMap;
    }

    /**
     * Get the separate CID Map.
     *
     * @return SetaPDF_Core_Font_Cmap
     */
    public function getCidMap()
    {
        return $this->_cidMap;
    }

    /**
     * Lookup by a type.
     *
     * @param string $type
     * @param string $src
     * @return bool|number|string
     */
    protected function _lookup($type, $src)
    {
        $table = $this->_mappings[$type];
        if (isset($table['single'][$src])) {
            return $table['single'][$src];
        }

        if (isset($this->_lookUps[$type][$src])) {
            return $this->_lookUps[$type][$src];
        }

        $srcInt = hexdec(SetaPDF_Core_Type_HexString::str2hex($src));
        $size = strlen($src);

        $sizes = array($size);
        if (!isset($table['range'][$size])) {
            $sizes = array_keys($table['range']);
            if (count($sizes) === 0 || max($sizes) > $size) {
                return false;
            }
        }

        foreach ($sizes AS $size) {
            /* walk backwards to get latest definitions first
             * e.g.
             *   ETenms-B5-H make use of "/ETen-B5-H usecmap" which is executed first
             *   but ascii definitions are done in ETenms-B5-H later...
             */
            for ($i = count($table['range'][$size]) - 1; $i >= 0; $i--) {
                $range = $table['range'][$size][$i];
                $src1 = $range[0];
                $src2 = $range[1];
                if ($srcInt >= $src1 && $srcInt <= $src2) {
                    if (is_array($range[2])) {
                        $diff = $srcInt - $src1;
                        if ($type == 'cid') {
                            return $range[2][$diff];
                        }
                        $this->_lookUps[$type][$src] = SetaPDF_Core_Type_HexString::hex2str($range[2][$diff]);
                        return $this->_lookUps[$type][$src];
                    } else {
                        $diff = $srcInt - $src1;

                        if ($type == 'cid') {
                            $this->_lookUps[$type][$src] = $range[2] + $diff;
                            return $this->_lookUps[$type][$src];
                        }

                        // fallback for invalid byte strings. It should only the last byte get incremented.
                        if ($diff > 255) {
                            $value = hexdec($range[2]) + $diff;
                            $value = SetaPDF_Core_Encoding::unicodePointToUtf16Be($value);
                        } else {
                            $value = SetaPDF_Core_Type_HexString::hex2str($range[2]);
                            $value[strlen($value) - 1] = chr(ord($value[strlen($value) - 1]) + $diff);
                        }

                        $this->_lookUps[$type][$src] = $value;

                        return $value;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Do a reverse lookup.
     *
     * This method only returns values for previously looked up sources!
     *
     * @param string $dest
     * @return bool|mixed
     */
    public function reverseLookup($dest)
    {
        return $this->_reverseLookup($dest, 'uni');
    }

    /**
     * Do a reverse CID lookup.
     *
     * This method only returns values for previously looked up sources!
     *
     * @param string $dest
     * @return bool|mixed
     */
    public function reverseCidLoopkup($dest)
    {
        return $this->_reverseLookup($dest, 'cid');
    }

    /**
     * Do a reverse lookup by a specific type.
     *
     * This method only returns values for previously looked up sources!
     *
     * @param string $dest
     * @param string $type
     * @return bool|mixed
     */
    protected function _reverseLookup($dest, $type)
    {
        $table = $this->_mappings[$type];

        $src = array_search($dest, $table['single']);
        if ($src !== false) {
            return $src;
        }

        $src = array_search($dest, $this->_lookUps[$type]);
        if ($src !== false) {
            return $src;
        }

        /* TODO: A reverse lookup for values that were not looked up before.
         * Actually not used at any time but to be complete this should be a feature.
         */

        return false;
    }

    /**
     * Lookup a unicode value.
     *
     * @param string $src
     * @return bool|number|string
     */
    public function lookup($src)
    {
        if ($this->_cidMap !== null) {
            $cid = $this->_cidMap->lookupCid($src);
            if (!$cid) {
                return $cid;
            }

            $src = SetaPDF_Core_Encoding::unicodePointToUtf16Be($cid);
        }

        return $this->_lookup('uni', $src);
    }

    /**
     * Lookup for a CID.
     *
     * @param string $src
     * @return bool|number|string
     */
    public function lookupCid($src)
    {
        return $this->_lookup('cid', $src);
    }

    /**
     * Get the name of the CID map.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
}