<?
/**
 * This file contains the XML class, "xml"
 * Licensed under the BSD license
 * All credits to strangeways (http://www.strangeways.se/)
 * for letting this code out in the free where it belongs!
 *
 * @author Mikael "Lilleman" Goransson <lilleman@strangeways.se>
 * @version 0.1
 * @package SW-CMS
 */

/**
 * XML class
 *
 * @author Mikael "Lilleman" Goransson <lilleman@strangeways.se>
 * @version 0.1
 * @package SW-CMS
 * @subpackage xml
 */
class xml {

    /**
     * Content as array
     *
     * @var arr
     */
    var $array = array();

    /**
     * Errors
     *
     * @var arr
     */
    var $errors = array();

    /**
     * The XML
     *
     * @var str
     */
    var $XML = '';

    /**
     * XML Encoding
     *
     * @var str
     */
    var $XMLEncoding = 'ISO-8859-1';

    /**
     * XML Head
     *
     * @var str
     */
    var $XMLHead = '';

    /**
     * XML default indent char
     *
     * @var str
     */
    var $XMLIndentChar = ' ';

    /**
     * XML number of indents per level
     *
     * @var int
     */
    var $XMLIndentNum = 4;

    /**
     * XML Line Break
     *
     * @var str
     */
    var $XMLLineBreak = "\n";

    /**
     * XML Version
     *
     * @var str
     */
    var $XMLVersion = '1.0';

    /**
     * Class constructor
     *
     * @return boolean
     * @access Public
     */
    function xml() {

        $this->setXMLHead();

        return true;
    } // End of xml()

    /**
     * Generate tags recursive
     * (This is the magic XML-creator)
     *
     * @param arr $arr
     * @param int $lvl
     * @param int $prev_lvl - will be one less than lvl if this is the first row, else it'll be the same
     * @return str
     */
    function generateTag($arr,$lvl = 0,$prev_lvl = 0) {
        $str = '';

        foreach ($arr as $key => $value) {
//		print $value;
            if ($key == '0' && count($arr) == 1 && !is_array($value)) {
                // This is the only value around... so push it out without tags:)
                $this->XML .= $this->XMLLineBreak;			
                $this->XML .= $this->indent($lvl) . '<' . $this->strToXMLKeySafe($value) . ' />' . $this->XMLLineBreak;
            } else { // End of if ($key == '0' && !isset($arr['1']))
                if ($prev_lvl != $lvl) {
				//	print $value;
                    $this->XML .= $this->XMLLineBreak;
                } // End of if ($prev_lvl != $lvl)
                $this->XML .= $this->indent($lvl) . '<' . $this->strToXMLKeySafe($key) . '>';
			print  $this->XML;
                if (is_array($value))   $this->XML .= $this->generateTag($value,$lvl + 1,$lvl);
                else                    $this->XML .= $this->strToXMLSafe($value);

                // Remove attributes from closing key
                list($key) = explode(' ',$key);

                if (is_array($value)) $this->XML .= $this->indent($lvl);
                $this->XML .= '</' . $this->strToXMLKeySafe($key) . '>' . $this->XMLLineBreak;

                $prev_lvl = $lvl;
            } // End of else to if ($key == '0' && !isset($arr['1']))
        } // End of foreach ($arr as $key => $value)

        return $str;
    } // End of generateTag()

    /**
     * Create an indent string
     *
     * @param int $num - number of indents
     * @return str
     * @access Private
     */
    function indent($num) {
        $str = '';
        for ($i = 1; $i <= ($this->XMLIndentNum * ($num)); $i++) {
            $str .= $this->XMLIndentChar;
        } // End of for ($i = 1; $i <= ($this->XMLIndentNum * ($num)); $i++)
        return $str;
    } // End of indent()

    /**
     * Generate XML from $this->array
     *
     * @return boolean
     * @access Private
     */
    function generateXML() {
        $this->XML = $this->XMLHead;

        $this->XML .= $this->generateTag($this->array);

        return true;
    } // End of generateXML()

    /**
     * Output XML Data
     *
     * @param str $method - Either "return" or "echo" - "return" will make the function return, "echo" will print it to screen with header 'n all
     * @access Public
     */
    function outputXML($method = 'return') {
        $this->generateXML();

        if ($method == 'return') {
            return $this->XML;
        } elseif ($method == 'echo') { // End of if ($method == 'return')
            header('Content-Type: text/xml');
			
            echo $this->XML;
            return true;
        } else { // End of elseif ($method == 'echo')
            return false;
        } // End of elseif ($method == 'echo')
    } // End of outputXML()

    /**
     * Set the array of contents
     *
     * @param arr $arr
     * @return boolean
     * @access Public
     */
    function setArray($arr) {
        $this->array = $arr;
	//	print $arr;
        return true;
    } // End of setArray()

    /**
     * Set indent character
     *
     * @param str $str
     * @return boolean
     * @access Public
     */
    function setIndentChar($str) {
        $this->XMLIndentChar = strval($str);
        $this->setXMLHead();
        return true;
    } // End of setIndentChar()

    /**
     * Set number of indents
     *
     * @param int $int
     * @return boolean
     * @access Public
     */
    function setIndentNum($int) {
        $this->XMLIndentNum = intval($int);
        $this->setXMLHead();
        return true;
    } // End of setIndentNum()

    /**
     * Set XML line breaks
     *
     * @param str $str
     * @return boolean
     * @access Public
     */
    function setLineBreak($str) {
        $this->XMLLineBreak = strval($str);
        $this->setXMLHead();
        return true;
    } // End of setLineBreak()

    /**
     * Set XML Encoding
     *
     * @param str $str
     * @return boolean
     */
    function setXMLEncoding($str) {
        $this->XMLEncoding = strval($str);
        $this->setXMLHead();
        return true;
    } // End of setXMLEncoding()

    /**
     * Set XML Head to use in XML output
     *
     * @return boolean
     * @access Private
     */
    function setXMLHead() {
        $this->XMLHead = '<?xml version="' . $this->XMLVersion . '" encoding="' . $this->XMLEncoding . '"?>' . $this->XMLLineBreak;
        return true;
    } // End of setXMLHead();

    /**
     * Set XML Version (as a string)
     * example: "1.0"
     *
     * @param str $str
     * @return boolean
     * @access Public
     */
    function setXMLVersion($str) {
        $this->XMLVersion = strval($str);
        $this->setXMLHead();
        return true;
    } // End of setXMLVersion()

    /**
     * String to XML Safe for key (tag) values
     *
     * @param str $str
     * @return str
     */
    function strToXMLKeySafe($str) {
        if (is_numeric(substr($str,0,1))) $str = 'a' . $str;

        return $str;
    } // End of strToXMLKeySafe()

    /**
     * String to XML Safe
     *
     * @param str $str
     * @return str
     */
    function strToXMLSafe($str) {
        $str = str_replace('<','&lt;',$str);
        $str = str_replace('>','&gt;',$str);
        $str = str_replace('&','&amp;',$str);
        $str = str_replace("'",'&apos;',$str);
        $str = str_replace('"','&quot;',$str);

        return $str;
    } // End of strToXMLSafe()

} // End of class xml
?>
