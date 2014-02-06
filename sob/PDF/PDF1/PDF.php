<?php
/**
 * PDF::
 *
 * The PDF:: class provides a PHP-only implementation of a PDF library.
 * No external libs or PHP extensions are required.
 *
 * Based on the FPDF class by Olivier Plathey (http://www.fpdf.org) and the
 * Horde Framework PDF package (http://www.horde.org)
 *
 * Copyright 2003 Marko Djukic <marko@oblo.com>
 * Copyright 2003 Olivier Plathey <olivier@fpdf.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Olivier Plathey <olivier@fpdf.org>
 * @author  Marko Djukic <marko@oblo.com>
 */

class PDF {

    var $_buffer = '';          // Buffer holding in-memory PDF.
    var $_state = 0;            // Current document state.
    var $_page = 0;             // Current page number.
    var $_n = 2;                // Current object number.
    var $_offsets = array();    // Array of object offsets.
    var $_pages = array();      // Array containing the pages.
    var $_w;                    // Page width in points.
    var $_h;                    // Page height in points
    var $_fonts = array();      // An array of used fonts.
    var $_font_family = '';     // Current font family.
    var $_font_style = '';      // Current font style.
    var $_current_font;         // Array with current font info.
    var $_font_size = 12;       // Current font size in points.
    var $_compress;             // Flag to compress or not.
    var $_core_fonts = array('courier'      => 'Courier',
                             'courierB'     => 'Courier-Bold',
                             'courierI'     => 'Courier-Oblique',
                             'courierBI'    => 'Courier-BoldOblique',
                             'helvetica'    => 'Helvetica',
                             'helveticaB'   => 'Helvetica-Bold',
                             'helveticaI'   => 'Helvetica-Oblique',
                             'helveticaBI'  => 'Helvetica-BoldOblique',
                             'times'        => 'Times-Roman',
                             'timesB'       => 'Times-Bold',
                             'timesI'       => 'Times-Italic',
                             'timesBI'      => 'Times-BoldItalic',
                             'symbol'       => 'Symbol',
                             'zapfdingbats' => 'ZapfDingbats');

    /**
     * Attempts to return a conrete PDF instance. It allows to set up the page
     * format, the orientation and the units of measurement used in all the
     * methods (except for the font sizes).
     *
     * Example:
     * $pdf = &PDF::factory('P', 'A4');
     *
     * @access public
     *
     * @param optional string $orientation  Default page orientation.
     *                                      Possible values are (case
     *                                      insensitive):
     *                                        - P or Portrait (default)
     *                                        - L or Landscape
     * @param optional mixed format         The format used for pages. It can
     *                                      be either one of the following
     *                                      values (case insensitive):
     *                                        - A3
     *                                        - A4 (default)
     *                                        - A5
     *                                        - Letter
     *                                        - Legal
     *                                      or a custom format in the form of
     *                                      a two-element array containing the
     *                                      width and the height (expressed in
     *                                      the unit given by unit).
     */
    function &factory($orientation = 'P', $format = 'A4')
    {
        /* Create the PDF object. */
        $pdf = &new PDF();

        /* Page format. */
        $format = strtolower($format);
        if ($format == 'a3') {           // A3 page size.
            $format = array(841.89, 1190.55);
        } elseif ($format == 'a4') {     // A4 page size.
            $format = array(595.28, 841.89);
        } elseif ($format == 'a5') {     // A5 page size.
            $format = array(420.94, 595.28);
        } elseif ($format == 'letter') { // Letter page size.
            $format = array(612, 792);
        } elseif ($format == 'legal') {  // Legal page size.
            $format = array(612, 1008);
        } else {
            die(sprintf('Unknown page format: %s', $format));
        }   
        $pdf->_w = $format[0];
        $pdf->_h = $format[1];

        /* Page orientation. */
        $orientation = strtolower($orientation);
        if ($orientation == 'l' || $orientation == 'landscape') {
            $w = $pdf->_w;
            $pdf->_w = $pdf->_h;
            $pdf->_h = $w;
        } elseif ($orientation != 'p' && $orientation != 'portrait') {
            die(sprintf('Incorrect orientation: %s', $orientation));
        }

        /* Turn on compression by default. */
        $pdf->setCompression(true);

        return $pdf;
    }

    /**
     * Activates or deactivates page compression. When activated, the internal
     * representation of each page is compressed, which leads to a compression
     * ratio of about 2 for the resulting document.
     * Compression is on by default.
     * Note: the Zlib extension is required for this feature. If not present,
     * compression will be turned off.
     *
     * @param bool $compress  Boolean indicating if compression must be
     *                        enabled or not.
     */
    function setCompression($compress)
    {   
        /* If no gzcompress function is available then default to
         * false. */
        $this->_compress = (function_exists('gzcompress') ? $compress : false);
    }

    /**
     * This method begins the generation of the PDF document; it must be
     * called before any output commands. No page is created by this method,
     * therefore it is necessary to call PDF::addPage.
     *
     * @access public
     *
     * @see PDF::addPage
     * @see PDF::close
     */
    function open()
    {   
        $this->_state = 1;          // Set state to initialised.
        $this->_out('%PDF-1.3');    // Output the PDF header.
    }

    /**
     * Adds a new page to the document. The font which was set before calling
     * is automatically restored. There is no need to call PDF::setFont again
     * if you want to continue with the same font.
     *
     * @access public
     *
     * @see PDF::PDF
     */
    function addPage()
    {   
        $this->_page++;                   // Increment page count.
        $this->_pages[$this->_page] = ''; // Start the page buffer.
        $this->_state = 2;                // Set state to page
                                          // opened.
        /* Check if font has been set before this page. */
        if ($this->_font_family) {
            $this->setFont($this->_font_family, $this->_font_style, $this->_font_size);
        }
    }

    /**
     * Sets the font used to print character strings. It is mandatory to call
     * this method at least once before printing text or the resulting
     * document would not be valid. The font must be a standard one. Standard
     * fonts use Windows encoding cp1252 (Western Europe).
     * The method can be called before the first page is created and the font
     * is retained from page to page.
     * If you just wish to change the current font size, it is simpler to call
     * PDF::setFontSize.
     *
     * @access public
     *
     * @param string $family          Family font. It must be one of the
     *                                standard families (case insensitive):
     *                                  - Courier (fixed-width)
     *                                  - Helvetica or Arial (sans serif)
     *                                  - Times (serif)
     *                                  - Symbol (symbolic)
     *                                  - ZapfDingbats (symbolic)
     *                                It is also possible to pass an empty
     *                                string. In that case, the current
     *                                family is retained.
     * @param optional string $style  Font style. Possible values are (case
     *                                insensitive):
     *                                  - empty string: regular
     *                                  - B: bold
     *                                  - I: italic
     *                                or any combination. The default value is
     *                                regular. Bold and italic styles do not
     *                                apply to Symbol and ZapfDingbats.
     * @param optional int $size      Font size in points. The default value
     *                                is the current size. If no size has been
     *                                specified since the beginning of the
     *                                document, the value taken is 12.
     */
    function setFont($family, $style = '', $size = null)
    {
        $family = strtolower($family);
        if ($family == 'arial') {               // Use helvetica.
            $family = 'helvetica';
        } elseif ($family == 'symbol' ||        // No styles for
                  $family == 'zapfdingbats') {  // these two fonts.
            $style = '';
        }

        $style = strtoupper($style);
        if ($style == 'IB') {                   // Accept any order
            $style = 'BI';                      // of B and I.
        }

        if (is_null($size)) {                   // No size specified,
            $size = $this->_font_size;          // use current size.
        }

        if ($this->_font_family == $family &&   // If font is already 
            $this->_font_style == $style &&     // current font
            $this->_font_size == $size) {       // simply return.
            return;
        }

        /* Set the font key. */
        $fontkey = $family . $style;

        if (!isset($this->_fonts[$fontkey])) {  // Test if cached.
            $i = count($this->_fonts) + 1;      // Increment font
            $this->_fonts[$fontkey] = array(    // object count and
                'i'    => $i,                   // store cache.
                'name' => $this->_core_fonts[$fontkey]);
        }

        /* Store current font information. */
        $this->_font_family  = $family;
        $this->_font_style   = $style;
        $this->_font_size    = $size;
        $this->_current_font = $this->_fonts[$fontkey];

        /* Output font information if at least one page has been
         * defined. */
        if ($this->_page > 0) {
            $this->_out(sprintf('BT /F%d %.2f Tf ET', $this->_current_font['i'], $this->_font_size));
        }
    }

    /**
     * Defines the size of the current font.
     *
     * @access public
     *
     * @param float $size  The size (in points).
     *
     * @see PDF::setFont
     */
    function setFontSize($size)
    {
        if ($this->_font_size == $size) {   // If already current
            return;                         // size simply return.
        }

        $this->_font_size = $size;          // Set the font.

        /* Output font information if at least one page has been
         * defined. */
        if ($this->_page > 0) {
            $this->_out(sprintf('BT /F%d %.2f Tf ET',
                                $this->_current_font['i'],
                                $this->_font_size));
        }
    }

    /**
     * Prints a character string. The origin is on the left of the first
     * character, on the baseline. This method allows to place a string
     * precisely on the page.
     *
     * @access public
     *
     * @param float $x      Abscissa of the origin.
     * @param float $y      Ordinate of the origin.
     * @param string $text  String to print.
     *
     * @see PDF::setFont
     */
    function text($x, $y, $text)
    {
        $text = $this->_escape($text);    // Escape any harmful
                                          // characters.

        $out = sprintf('BT %.2f %.2f Td (%s) Tj ET',
                       $x, $this->_h - $y, $text);
        $this->_out($out);
    }

    /**
     * Terminates the PDF document. It is not necessary to call this method
     * explicitly because PDF::output does it automatically.
     * If the document contains no page, PDF::addPage is called to prevent
     * from getting an invalid document.
     *
     * @access public
     *
     * @see PDF::open
     * @see PDF::output
     */
    function close()
    {
        if ($this->_page == 0) {    // If not yet initialised, add
            $this->addPage();       // one page to make this a valid
        }                           // PDF.

        $this->_state = 1;          // Set the state page closed.

        /* Pages and resources. */
        $this->_putPages();
        $this->_putResources();

        /* Print some document info. */
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Producer (My First PDF Class)');
        $this->_out(sprintf('/CreationDate (D:%s)',
                            date('YmdHis')));
        $this->_out('>>');
        $this->_out('endobj');

        /* Print catalog. */
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Type /Catalog');
        $this->_out('/Pages 1 0 R');
        $this->_out('/OpenAction [3 0 R /FitH null]');
        $this->_out('/PageLayout /OneColumn');
        $this->_out('>>');
        $this->_out('endobj');

        /* Print cross reference. */
        $start_xref = strlen($this->_buffer); // Get the xref offset.
        $this->_out('xref');                  // Announce the xref.
        $this->_out('0 ' . ($this->_n + 1));  // Number of objects.
        $this->_out('0000000000 65535 f ');
        /* Loop through all objects and output their offset. */
        for ($i = 1; $i <= $this->_n; $i++) {
            $this->_out(sprintf('%010d 00000 n ', $this->_offsets[$i]));
        }

        /* Print trailer. */
        $this->_out('trailer');
        $this->_out('<<');
        /* The total number of objects. */
        $this->_out('/Size ' . ($this->_n + 1));
        /* The root object. */
        $this->_out('/Root ' . $this->_n . ' 0 R');
        /* The document information object. */
        $this->_out('/Info ' . ($this->_n - 1) . ' 0 R');
        $this->_out('>>');
        $this->_out('startxref');
        $this->_out($start_xref);  // Where to find the xref.
        $this->_out('%%EOF');
        $this->_state = 3;         // Set the document state to
                                   // closed.
    }

    /**
     * Function to output the buffered data to the browser.
     *
     * @access public
     */
    function output($filename)
    {
        if ($this->_state < 3) {    // If document not yet closed
            $this->close();         // close it now.
        }

        /* Make sure no content already sent. */
        if (headers_sent()) {
            die('Unable to send PDF file, some data has already been output to browser.');
        }

        /* Offer file for download and do some browser checks
         * for correct download. */
        $agent = trim($_SERVER['HTTP_USER_AGENT']);
        if ((preg_match('|MSIE ([0-9.]+)|', $agent, $version)) ||
            (preg_match('|Internet Explorer/([0-9.]+)|', $agent, $version))) {
            header('Content-Type: application/x-msdownload');
            Header('Content-Length: ' . strlen($this->_buffer));
            if ($version == '5.5') {
                header('Content-Disposition: filename="' . $filename . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $filename . '"');
            }
        } else {
            Header('Content-Type: application/pdf');
            Header('Content-Length: ' . strlen($this->_buffer));
            Header('Content-disposition: attachment; filename=' . $filename);
        }
        echo $this->_buffer;
    }

    function _out($s)
    {
        if ($this->_state == 2) {
            $this->_pages[$this->_page] .= $s . "\n";
        } else {
            $this->_buffer .= $s . "\n";
        }
    }

    function _escape($s)
    {   
        $s = str_replace('\\', '\\\\', $s);   // Escape any '\\'
        $s = str_replace('(', '\\(', $s);     // Escape any '('
        return str_replace(')', '\\)', $s);   // Escape any ')'
    }


    function _newobj()
    {
        /* Increment the object count. */
        $this->_n++;
        /* Save the byte offset of this object. */
        $this->_offsets[$this->_n] = strlen($this->_buffer);
        /* Output to buffer. */
        $this->_out($this->_n . ' 0 obj');
    }

    function _putPages()
    {
        /* If compression is required set the compression tag. */
        $filter = ($this->_compress) ? '/Filter /FlateDecode ' : '';
        /* Print out pages, loop through each. */
        for ($n = 1; $n <= $this->_page; $n++) {
            $this->_newobj();                 // Start a new object.
            $this->_out('<</Type /Page');     // Object type.
            $this->_out('/Parent 1 0 R');
            $this->_out('/Resources 2 0 R');
            $this->_out('/Contents ' . ($this->_n + 1) . ' 0 R>>');
            $this->_out('endobj');

            /* If compression required gzcompress() the page content. */
            $p = ($this->_compress) ? gzcompress($this->_pages[$n]) : $this->_pages[$n];

            /* Output the page content. */
            $this->_newobj();                 // Start a new object.
            $this->_out('<<' . $filter . '/Length ' . strlen($p) . '>>');
            $this->_putStream($p);            // Output the page.
            $this->_out('endobj');
        }

        /* Set the offset of the first object. */
        $this->_offsets[1] = strlen($this->_buffer);
        $this->_out('1 0 obj');
        $this->_out('<</Type /Pages');
        $kids = '/Kids [';
        for ($i = 0; $i < $this->_page; $i++) {
            $kids .= (3 + 2 * $i) . ' 0 R ';
        }   
        $this->_out($kids . ']');
        $this->_out('/Count ' . $this->_page);
        /* Output the page size. */
        $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',
                            $this->_w, $this->_h));
        $this->_out('>>');
        $this->_out('endobj');
    }

    function _putStream($s)
    {
        $this->_out('stream');
        $this->_out($s);
        $this->_out('endstream');
    }

    function _putResources()
    {
        /* Output any fonts. */
        $this->_putFonts();

        /* Resources are always object number 2. */
        $this->_offsets[2] = strlen($this->_buffer);
        $this->_out('2 0 obj');
        $this->_out('<</ProcSet [/PDF /Text]');
        $this->_out('/Font <<');
        foreach ($this->_fonts as $font) {
            $this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
        }
        $this->_out('>>');
        $this->_out('>>');
        $this->_out('endobj');
    }

    function _putFonts()
    {
        /* Print out font details. */
        foreach ($this->_fonts as $k => $font) {
            $this->_newobj();
            $this->_fonts[$k]['n'] = $this->_n;
            $name = $font['name'];
            $this->_out('<</Type /Font');
            $this->_out('/BaseFont /' . $name);
            $this->_out('/Subtype /Type1');
            if ($name != 'Symbol' && $name != 'ZapfDingbats') {
                $this->_out('/Encoding /WinAnsiEncoding');
            }
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

}

?>
