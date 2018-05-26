<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Parser
 * @license    http://www.setasign.com/ Commercial
 * @version    $Id: CrossReferenceTable.php 816 2016-02-12 08:50:35Z jan.slabon $
 */

/**
 * A PDF cross reference parser
 *
 * @copyright  Copyright (c) 2016 Setasign - Jan Slabon (http://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Parser
 * @license    http://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Parser_CrossReferenceTable
    extends SetaPDF_Core_Document_CrossReferenceTable
    implements SetaPDF_Core_Parser_CrossReferenceTable_CrossReferenceTableInterface
{
    /**
     * The byte count in which the initial xref keyword should be searched for
     *
     * @var integer
     */
    static public $fileTrailerSearchLength = 1024;

    /**
     * A flag indicating the way of reading the xref table.
     *
     * If set to true, the xref table will only read/resolved if an access
     * to an object is needed. This is very fast for a small amount of access (updates).
     * If set to false, the complete xref-table will be read in at once.
     * This is faster if the document should be completely rewritten.
     *
     * @var boolean
     */
    static public $readOnAccess = true;

    /**
     * The PDF parser instance
     *
     * @var SetaPDF_Core_Parser_Pdf
     */
    protected $_parser;

    /**
     * The initial pointer to the xref table
     *
     * @var integer
     */
    protected $_pointerToXref;

    /**
     * Offset positions of subsections or cross reference stream objects
     *
     * @var array
     */
    protected $_xrefSubsection = array();

    /**
     * Object offsets in the parser File
     *
     * @var array
     */
    protected $_parserObjectOffsets = array();

    /**
     * The trailer dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_trailer;

    /**
     * Cross reference uses compressed object streams
     *
     * @var boolean
     */
    protected $_compressed = false;

    /**
     * An array holding all resolved indirect objects representing compressed xref tables.
     *
     * @var array
     */
    protected $_compressedXrefObjects = array();

    /**
     * The constructor.
     *
     * @param SetaPDF_Core_Parser_Pdf $parser
     */
    public function __construct(SetaPDF_Core_Parser_Pdf $parser)
    {
        $this->_parser = $parser;
        $this->_readTrailerAndXref($this->getPointerToXref());
    }

    /**
     * Release memory/references.
     */
    public function cleanUp()
    {
        $this->_parser = null;
        $this->_compressedXrefObjects = array();
    }

    /**
     * Check if the xref table uses compressed xref streams.
     *
     * @return boolean
     */
    public function isCompressed()
    {
        return $this->_compressed;
    }

    /**
     * Get all defined object ids.
     *
     * This method returns an array of all objects which are noticed in any cross reference table.
     * The appearance of an object id in this list is not an evidence of existence of the desired object.
     *
     * @return array
     */
    public function getDefinedObjectIds()
    {
        $subsections = array();
        $objects = array_keys($this->_parserObjectOffsets);

        foreach ($this->_xrefSubsection AS $subsection) {
            if (!isset($subsection[2])) {
                $subsections[] = array($subsection[0], $subsection[1]);
            } else {
                foreach ($subsection[3] AS $_subsection) {
                    $subsections[] = $_subsection;
                }
            }
        }

        foreach ($subsections AS $subsection) {
            list($start, $end) = $subsection;
            $end += $start;
            for (; $start < $end; $start++) {
                if ($start === 0)
                    continue;

                $objects[] = $start;
            }
        }

        $objects = array_unique($objects);
        sort($objects);

        return $objects;
    }

    /**
     * Get the generation number by an object id.
     *
     * @param integer $objectId
     * @return integer|boolean
     */
    public function getGenerationNumberByObjectId($objectId)
    {
        $offset = $this->getParserOffsetFor($objectId);
        if ($offset !== false) {
            // check for free entry
            return key($this->_parserObjectOffsets[$objectId]);
        }

        return false;
    }

    /**
     * Read the document trailer and initiate the initial parsing of the xref table.
     *
     * @param integer|boolean $xrefOffset
     * @throws SetaPDF_Core_Parser_CrossReferenceTable_Exception
     */
    protected function _readTrailerAndXref($xrefOffset)
    {
        while (false !== $xrefOffset) {

            $this->_parser->reset($xrefOffset, 4);
            $initValue = $this->_parser->readValue();

            // normal old styled xref table
            if ($initValue instanceof SetaPDF_Core_Type_Token && $initValue->getValue() === 'xref') {
                $this->_readXref();

                // skip the trailer keyword
                $trailerKeyword = $this->_parser->readValue();
                if (false === $trailerKeyword || $trailerKeyword->getValue() !== 'trailer') {
                    throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                        sprintf('Unexpected end of cross reference. trailer-keyword expected, got: %s', $trailerKeyword ? $trailerKeyword->getValue() : 'nothing')
                    );
                }

                // read trailer
                $trailer = $this->_parser->readValue();

                if (!($trailer instanceof SetaPDF_Core_Type_Dictionary)) {
                    throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception('Could not read trailer dictionary');
                }
                if (null === $this->_trailer) {
                    $this->_trailer = $trailer;
                } else {
                    foreach ($trailer AS $key => $value) {
                        if (!$this->_trailer->offsetExists($key)) {
                            $this->_trailer->offsetSet($key, $value);
                        }
                    }
                }

                $xrefOffset = $trailer->offsetExists('Prev')
                    ? $trailer->offsetGet('Prev')->getValue()->getValue()
                    : false;

                // Handle hybrid-reference files
                if ($trailer->offsetExists('XRefStm')) {
                    $this->_readTrailerAndXref($trailer->getValue('XRefStm')->getValue());
                    $this->_compressed = false;
                }

            } elseif ($initValue instanceof SetaPDF_Core_Type_IndirectObject) {
                $xrefStream = $initValue->getValue();
                if ($xrefStream instanceof SetaPDF_Core_Type_Stream &&
                    $xrefStream->getValue()->offsetExists('Type') &&
                    $xrefStream->getValue()->getValue('Type')->getValue() === 'XRef'
                ) {
                    $this->_compressedXrefObjects[$initValue->getObjectId()] = $initValue;

                    $xrefDict = $xrefStream->getValue();

                    if ($xrefDict->offsetExists('Index')) {
                        $index = $xrefDict->getValue('Index');

                        $subsections = array();
                        $min = null;
                        $max = 0;

                        for ($i = 0, $c = count($index); $i < $c; $i += 2) {
                            $start = (int)$index[$i]->getValue();
                            $count = (int)$index[$i + 1]->getValue();

                            $subsections[] = array($start, $count);

                            $min = (null !== $min) ? min($min, $start) : $start;
                            $max = max($max, $start + $count - 1);
                            $this->updateSize($max);
                        }

                        $this->_xrefSubsection[] = array($min, $max - $min + 1, $xrefStream, $subsections);
                    } else {
                        $size = $xrefDict->getValue('Size')->getValue();
                        $this->_xrefSubsection[] = array(0, $size, $xrefStream, array(array(0, $size)));
                        $this->updateSize($size - 1);
                    }

                    if (null === $this->_trailer) {
                        $this->_trailer = new SetaPDF_Core_Type_Dictionary();
                    }

                    foreach (array('Size', 'Root', 'Encrypt', 'Info', 'ID') AS $key) {
                        if (!$this->_trailer->offsetExists($key) && $xrefDict->offsetExists($key)) {
                            $this->_trailer->offsetSet($key, $xrefDict->offsetGet($key));
                        }
                    }

                    $xrefOffset = $xrefDict->offsetExists('Prev')
                        ? $xrefDict->getValue('Prev')->getValue()
                        : false;

                    $this->_compressed = true;
                } else {
                    throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                        sprintf('Unable to find xref table.')
                    );
                }

            } else {
                throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                    sprintf("Unable to find xref table. xref-keyword or xref-stream object is missing at offset (%s)", $xrefOffset)
                );
            }
        }
    }

    /**
     * Returns the trailer dictionary.
     *
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getTrailer()
    {
        return $this->_trailer;
    }

    /**
     * Get all indirect objects holding cross reference streams.
     *
     * @return array
     */
    public function getCompressedXrefObjects()
    {
        return $this->_compressedXrefObjects;
    }

    /**
     * Returns the offset position of an object.
     *
     * @param integer $objectId
     * @param integer $generation
     * @param integer $objectGeneration The final generation number, resolved if no generation number was given.
     * @return boolean|mixed
     */
    public function getParserOffsetFor($objectId, $generation = null, &$objectGeneration = null)
    {
        $offsetExists = isset($this->_parserObjectOffsets[$objectId]);
        $generationExists = ($offsetExists && isset($this->_parserObjectOffsets[$objectId][$generation]));
        if (
            $generationExists ||
            ($offsetExists && $generation === null)
        ) {
            $objectGeneration = $generationExists
                ? $generation
                : key($this->_parserObjectOffsets[$objectId]);

            return $this->_parserObjectOffsets[$objectId][$objectGeneration];
        }

        foreach ($this->_xrefSubsection AS $subsectionOffset => $subsection) {
            if ($subsection[0] > $objectId || $objectId >= ($subsection[0] + $subsection[1])) {
                continue;
            }

            // no object streams are in use
            if (!isset($subsection[2])) {
                $reader = $this->_parser->getReader();
                $xrefOffset = $subsectionOffset + ($objectId - $subsection[0]) * 20;
                $reader->reset($xrefOffset, 20);
                $line = $reader->readLine(20);

                // try to fix table entries with a wrong byte count
                if ('' === trim($line)) {
                    $line = $reader->readLine(20);
                }

                $parts = explode(' ', $line);
                if (count($parts) < 3) {
                    return false;
                }

                if ($parts[2] != 'n')
                    continue;

                $objectGeneration = (int)$parts[1];

                if ($generation === null || $objectGeneration == $generation) {
                    if (!$offsetExists)
                        $this->_parserObjectOffsets[$objectId] = array();

                    $this->_parserObjectOffsets[$objectId][$objectGeneration] = array((int)$parts[0], $objectGeneration);
                    return $this->_parserObjectOffsets[$objectId][$objectGeneration];
                }

                // object streams
            } else {
                // make sure the stream is decoded only once
                $subsection[2]->unfilterStream();
                $stream = $subsection[2]->getStream();
                $streamDict = $subsection[2]->getValue();
                $entryLengthsObject = $streamDict->getValue('W');
                $entryLengths = array();
                foreach ($entryLengthsObject AS $fieldSize) {
                    $entryLengths[] = $fieldSize->getValue();
                }

                $entryLength = array_sum($entryLengths);

                $subsections = $subsection[3];
                $offset = 0;
                foreach ($subsections AS $subsectionData) {
                    if ($subsectionData[0] > $objectId || $objectId >= ($subsectionData[0] + $subsectionData[1])) {
                        $offset += ($entryLength * $subsectionData[1]);
                        continue;
                    }

                    $offset = (int)($offset + ($objectId - $subsectionData[0]) * $entryLength);

                    $fields = array(1, 0, 0);
                    if ($entryLengths[0] > 0) {
                        if ($entryLengths[0] == 1) {
                            $fields[0] = ord($stream[$offset++]);
                        } else {
                            $fields[0] = 0;
                            for ($k = 0; $k < $entryLengths[0]; $k++) {
                                $fields[0] = ($fields[0] << 8) + (ord($stream[$offset++]) & 0xff);
                            }
                        }
                    }

                    for ($i = 1; $i < 3; $i++) {
                        if ($entryLengths[$i] > 0) {
                            if ($entryLengths[$i] == 1) {
                                $fields[$i] = ord($stream[$offset++]);
                            } else {
                                $fields[$i] = 0;
                                for ($k = 0; $k < $entryLengths[$i]; $k++) {
                                    $fields[$i] = ($fields[$i] << 8) + (ord($stream[$offset++]) & 0xff);
                                }
                            }
                        }
                    }

                    switch ($fields[0]) {
                        case 1:
                            // break: wrong generation number
                            if ($generation !== null && $fields[2] != $generation)
                                continue;

                            $objectGeneration = (int)$fields[2];

                            if (!isset($this->_parserObjectOffsets[$objectId]))
                                $this->_parserObjectOffsets[$objectId] = array();

                            $this->_parserObjectOffsets[$objectId][(int)$fields[2]] = array((int)$fields[1], (int)$fields[2]);
                            return $this->_parserObjectOffsets[$objectId][(int)$fields[2]];

                        case 2:
                            if (!isset($this->_parserObjectOffsets[$objectId]))
                                $this->_parserObjectOffsets[$objectId] = array();

                            $this->_parserObjectOffsets[$objectId][0] = array(array((int)$fields[1], 0), $fields[2]);
                            return $this->_parserObjectOffsets[$objectId][0];

                            break;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Find the initial point to the xref table.
     *
     * @return integer
     * @throws SetaPDF_Core_Parser_CrossReferenceTable_Exception
     */
    public function getPointerToXref()
    {
        if (null === $this->_pointerToXref) {
            $reader = $this->_parser->getReader();
            $reader->reset(-self::$fileTrailerSearchLength, self::$fileTrailerSearchLength);

            $buffer = $reader->getBuffer(false);
            $pos = strrpos($buffer, 'startxref');
            $addOffset = 9;
            if (false === $pos) {
                // Some corrupted documents uses startref, instead of startxref
                $pos = strrpos($buffer, 'startref');
                if (false === $pos) {
                    throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception('Unable to find pointer to xref table');
                }
                $addOffset = 8;
            }

            $reader->setOffset($pos + $addOffset);

            $value = $this->_parser->readValue();
            if (!($value instanceof SetaPDF_Core_Type_Numeric)) {
                throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception('Corrupted pointer to xref table.');
            }
            $this->_pointerToXref = $value->getValue();
        }

        return $this->_pointerToXref;
    }

    /**
     * Read the xref table at a specific position.
     *
     * @throws SetaPDF_Core_Parser_CrossReferenceTable_Exception
     */
    protected function _readXref()
    {
        // skip white spaces
        $this->_parser->getTokenizer()->leapWhiteSpaces();
        $reader = $this->_parser->getReader();

        // don't read the complete xref but only the subsections
        if (true === self::$readOnAccess) {
            $startObject = $objectCount = $lastLineStart = null;
            while (($line = $reader->readLine(20)) !== false) {
                if (strpos($line, 'trailer') !== false)
                    break;

                // jump over if line content doesn't match the expected string
                if (2 !== sscanf($line, '%d %d', $startObject, $objectCount)) {
                    continue;
                }

                $pos = $reader->getPos() + $reader->getOffset();

                if (count($this->_xrefSubsection) === 0) {
                    $nextLine = trim($reader->readBytes(21));
                    /* Check the next line for maximum of 20 bytes and not longer
                     * By catching 21 bytes and trimming the length should be still 21.
                     * Less bytes (e.g. 19) are catched later when the "trailer" keyword is not matched.
                     */
                    if (strlen($nextLine) !== 21) {
                        throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                            "This cross reference seems to be corrupted. Let's try separate parser."
                        );
                    }

                    // Catch corrupted documents where start count is invalid
                    if ($startObject === 1 && trim(substr($nextLine, 0, 20)) === '0000000000 65535 f') {
                        throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                            "This cross reference seems to be corrupted. Let's try separate parser."
                        );
                    }
                }

                $this->_xrefSubsection[$pos] = array($startObject, $objectCount);

                $lastLineStart = $pos + $objectCount * 20;
                $reader->reset($lastLineStart);

                $this->updateSize($startObject + $objectCount - 1);
            }

            $reader->reset($lastLineStart);

        } else {
            $cycles = -1;
            $bytesPerCycle = 100;

            $reader->reset(null, $bytesPerCycle);

            while (($trailerPos = strpos($reader->getBuffer(false), 'trailer', max($bytesPerCycle * $cycles++, 0))) === false) {
                if (false === $reader->increaseLength($bytesPerCycle))
                    break;
            }

            if (false === $trailerPos) {
                throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception('Unexpected end of cross reference. trailer-keyword not found');
            }

            // get the xref content
            $xrefContent = substr($reader->getBuffer(false), 0, $trailerPos);
            // reset the reader to the trailer-keyword
            $reader->reset($reader->getPos() + $trailerPos);

            // get eol markers in the first 100 bytes
            preg_match_all("/(\r\n|\n|\r)/", substr($xrefContent, 0, 100), $m);

            $differentLineEndings = count(array_unique($m[0]));
            if ($differentLineEndings > 1) {
                $lines = preg_split("/(\r\n|\n|\r)/", $xrefContent, -1, PREG_SPLIT_NO_EMPTY);
            } else {
                $lines = explode($m[0][0], $xrefContent);

            }

            $xrefContent = $differentLineEndings = $m = null;
            unset($xrefContent, $differentLineEndings, $m);

            $linesCount = count($lines);
            $start = 1;

            // Catch corrupted documents where start count is invalid
            if ($linesCount > 1 && (($line = trim($lines[0])) !== '')) {
                $pieces = explode(' ', $line);
                if (count($pieces) == 2 && $pieces[0] == 1) {
                    $nextLine = trim($lines[1]);
                    if (trim($nextLine) === '0000000000 65535 f') {
                        throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                            "This cross reference seems to be corrupted. Let's try separate parser."
                        );
                    }
                }
            }

            for ($i = 0; $i < $linesCount; $i++) {
                $line = trim($lines[$i]);
                if ($line) {
                    $pieces = explode(' ', $line);

                    $c = count($pieces);
                    switch ($c) {
                        case 2:
                            $start = (int)$pieces[0];
                            break;
                        case 3:
                            if ($pieces[2] != 'n') {
                                $start++;
                                $this->updateSize($start);
                                continue;
                            }

                            $generation = (int)$pieces[1];
                            if (!isset($this->_parserObjectOffsets[$start])) {
                                $this->_parserObjectOffsets[$start] = array();
                            }
                            if (!isset($this->_parserObjectOffsets[$start][$generation])) {
                                $this->_parserObjectOffsets[$start][$generation] = array(
                                    (int)$pieces[0], $generation
                                );
                            }

                            $start++;
                            $this->updateSize($start);

                            break;
                        default:
                            throw new SetaPDF_Core_Parser_CrossReferenceTable_Exception(
                                sprintf('Unexpected data in xref table (%s)', join(' ', $pieces))
                            );
                    }
                }
            }
        }
    }
}