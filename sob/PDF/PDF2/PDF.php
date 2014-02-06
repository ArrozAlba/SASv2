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
    var $_fill_color = '0 g';   // Color used on text and fills.
    var $_draw_color = '0 G';   // Line draw color.
    var $_line_width = 1;       // Drawing line width.
    var $_images = array();     // An array of used images.

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
     */
    function addPage()
    {   
        $this->_page++;                    // Increment page count.
        $this->_pages[$this->_page] = '';  // Start the page buffer.
        $this->_state = 2;                 // Set state to page
                                           // opened.
        /* Check if font has been set before this page. */
        if ($this->_font_family) {
            $this->setFont($this->_font_family, $this->_font_style, $this->_font_size);
        }
        /* Check if fill color has been set before this page. */
        if ($this->_fill_color != '0 g') {
            $this->_out($this->_fill_color);
        }   
        /* Check if draw color has been set before this page. */
        if ($this->_draw_color != '0 G') {
            $this->_out($this->_draw_color);
        }
        /* Check if line width has been set before this page. */
        if ($this->_line_width != 1) {
            $this->_out($this->_line_width);
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
     * Sets the fill color. Specifies the color for both filled areas and
     * text. Depending on the colorspace called, the number of color component
     * parameters required can be either 1, 3 or 4. The method can be called
     * before the first page is created and the color is retained from page to
     * page.
     *
     * @access public
     *
     * @param string $cs          Indicates the colorspace which can be either
     *                            'rgb', 'cmyk' or 'gray'. Defaults to 'rgb'.
     * @param float $c1           First color component, floating point value
     *                            between 0 and 1. Required for gray, rgb and
     *                            cmyk.
     * @param optional float $c2  Second color component, floating point value
     *                            between 0 and 1. Required for rgb and cmyk.
     * @param optional float $c3  Third color component, floating point value
     *                            between 0 and 1. Required for rgb and cmyk.
     * @param optional float $c4  Fourth color component, floating point value
     *                            between 0 and 1. Required for cmyk.
     */
    function setFillColor($cs = 'rgb', $c1, $c2 = 0, $c3 = 0, $c4 = 0)
    {
        $cs = strtolower($cs);
        if ($cs = 'rgb') {
            /* Using a three component RGB color. */
            $this->_fill_color = sprintf('%.3f %.3f %.3f rg',
                                         $c1, $c2, $c3);
        } elseif ($cs = 'cmyk') {
            /* Using a four component CMYK color. */
            $this->_fill_color = sprintf('%.3f %.3f %.3f %.3f k',
                                         $c1, $c2, $c3, $c4);
        } else {
            /* Grayscale one component color. */
            $this->_fill_color = sprintf('%.3f g', $c1);
        }
        /* If document started output to buffer. */
        if ($this->_page > 0) {
            $this->_out($this->_fill_color);
        }
    }

    /**
     * Sets the draw color, used when drawing lines. Depending on the
     * colorspace called, the number of color component parameters required
     * can be either 1, 3 or 4. The method can be called before the first page
     * is created and the color is retained from page to page.
     *
     * @access public
     *
     * @param string $cs          Indicates the colorspace which can be either
     *                            'rgb', 'cmyk' or 'gray'. Defaults to 'rgb'.
     * @param float $c1           First color component, floating point value
     *                            between 0 and 1. Required for gray, rgb and
     *                            cmyk.
     * @param optional float $c2  Second color component, floating point value
     *                            between 0 and 1. Required for rgb and cmyk.
     * @param optional float $c3  Third color component, floating point value
     *                            between 0 and 1. Required for rgb and cmyk.
     * @param optional float $c4  Fourth color component, floating point value
     *                            between 0 and 1. Required for cmyk.
     */
    function setDrawColor($cs = 'rgb', $c1, $c2 = 0, $c3 = 0, $c4 = 0)
    {   
        $cs = strtolower($cs);
        if ($cs = 'rgb') {
            $this->_draw_color = sprintf('%.3f %.3f %.3f RG',
                                         $c1, $c2, $c3);
        } elseif ($cs = 'cmyk') {
            $this->_draw_color = sprintf('%.3f %.3f %.3f %.3f K',
                                         $c1, $c2, $c3, $c4);
        } else {
            $this->_draw_color = sprintf('%.3f G', $c1);
        }   
        /* If document started output to buffer. */
        if ($this->_page > 0) {
            $this->_out($this->_draw_color);
        }
    }

    /**
     * Defines the line width. By default, the value equals 1 pt. The method
     * can be called before the first page is created and the value is
     * retained from page to page. 
     * 
     * @access public
     * 
     * @param float $width  The width.
     */
    function setLineWidth($width)
    {
        $this->_line_width = $width;
        if ($this->_page > 0) {
            $this->_out(sprintf('%.2f w', $width));
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
     * Prints an image in the page. The upper-left corner and at least one of
     * the dimensions must be specified; the height or the width can be
     * calculated automatically in order to keep the image proportions.
     * Supported format is JPEG.
     *
     * For JPEG, all flavors are allowed:
     *   - gray scales
     *   - true colors (24 bits)
     *   - CMYK (32 bits)
     *
     * The format will be inferred from the file extension.
     *
     * Remark: if an image is used several times, only one copy will be
     * embedded in the file.
     *
     * @access public
     *
     * @param string $file            Name of the file containing the image.
     * @param float $x                Abscissa of the upper-left corner.
     * @param float $y                Ordinate of the upper-left corner.
     * @param float $width            Width of the image in the page. If equal
     *                                to zero, it is automatically calculated
     *                                to keep the original proportions.
     * @param optional float $height  Height of the image in the page. If not
     *                                specified or equal to zero, it is
     *                                automatically calculated to keep the
     *                                original proportions.
     */
    function image($file, $x, $y, $width , $height)
    {
        if (!isset($this->_images[$file])) {
            /* First use of requested image, get the extension. */
            if (($pos = strrpos($file, '.')) === false) {
                die(sprintf('Image file %s has no extension and no type was specified', $file));
            }
            $type = strtolower(substr($file, $pos + 1));

            /* Check the image type and parse. */
            if ($type == 'jpg' || $type == 'jpeg') {
                $info = $this->_parseJPG($file);
            } else {
                die(sprintf('Unsupported image file type: %s', $type));
            }
            /* Set the image object id. */
            $info['i'] = count($this->_images) + 1;
            /* Set image to array. */

            $this->_images[$file] = $info;
        } else {
            $info = $this->_images[$file];          // Known image, retrieve
                                                    // from array.
        }

        /* If not specified, do automatic width and height
         * calculations, either setting to original or
         * proportionally scaling to one or the other given
         * dimension. */
        if (empty($width) && empty($height)) {
            $width = $info['w'];
            $height = $info['h'];
        } elseif (empty($width)) {
            $width = $height * $info['w'] / $info['h'];
        } elseif (empty($height)) {
            $height = $width * $info['h'] / $info['w'];
        }

        $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q', $width, $height, $x, $this->_h - ($y + $height), $info['i']));
    }

    /**
     * Draws a line between two points.
     * 
     * @access public
     * 
     * @param float $x1  Abscissa of first point. 
     * @param float $y1  Ordinate of first point.
     * @param float $x2  Abscissa of second point.
     * @param float $y2  Ordinate of second point.
     * 
     */                           
    function line($x1, $y1, $x2, $y2)
    {   
        $this->_out(sprintf('%.2f %.2f m %.2f %.2f l S', $x1, $this->_h - $y1, $x2, $this->_h - $y2));
    }

    /**                           
     * Outputs a rectangle. It can be drawn (border only), filled (with no
     * border) or both.
     *
     * @access public
     *
     * @param float $x                Abscissa of upper left corner.
     * @param float $y                Ordinate of upper left corner.
     * @param float $width            Width.
     * @param float $height           Height.
     * @param optional string $style  Style of rendering. Possible values are:
     *                                  - D or empty string: draw (default)
     *                                  - F: fill
     *                                  - DF or FD: draw and fill
     */
    function rect($x, $y, $width, $height, $style = '')
    {
        $style = strtolower($style);
        if ($style == 'f') {
            $op = 'f';      // Style is fill only.
        } elseif ($style == 'fd' || $style == 'df') {
            $op = 'B';      // Style is fill and stroke.
        } else {
            $op = 'S';      // Style is stroke only.
        }

        $this->_out(sprintf('%.2f %.2f %.2f %.2f re %s', $x, $this->_h - $y, $width, -$height, $op));
    }

    /**
     * Outputs a circle. It can be drawn (border only), filled (with no
     * border) or both.
     *
     * @access public
     *
     * @param float $x                Abscissa of the center of the circle.
     * @param float $y                Ordinate of the center of the circle.
     * @param float $r                Circle radius.
     * @param optional string $style  Style of rendering. Possible values are:
     *                                  - D or empty string: draw (default)
     *                                  - F: fill
     *                                  - DF or FD: draw and fill
     */
    function circle($x, $y, $r, $style = '')
    {
        $style = strtolower($style);
        if ($style == 'f') {
            $op = 'f';      // Style is fill only.
        } elseif ($style == 'fd' || $style == 'df') {
            $op = 'B';      // Style is fill and stroke.
        } else {
            $op = 'S';      // Style is stroke only.
        }

        $y = $this->_h - $y;                 // Adjust y value.
        $b = $r * 0.552;                     // Length of the Bezier
                                             // controls.
        /* Move from the given origin and set the current point
         * to the start of the first Bezier curve. */
        $c = sprintf('%.2f %.2f m', $x - $r, $y);
        $x = $x - $r;
        /* First circle quarter. */
        $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
                      $x, $y + $b,           // First control point.
                      $x + $r - $b, $y + $r, // Second control point.
                      $x + $r, $y + $r);     // Final point.
        /* Set x/y to the final point. */
        $x = $x + $r;
        $y = $y + $r;
        /* Second circle quarter. */
        $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
                      $x + $b, $y,
                      $x + $r, $y - $r + $b,
                      $x + $r, $y - $r);
        /* Set x/y to the final point. */
        $x = $x + $r;
        $y = $y - $r;
        /* Third circle quarter. */
        $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
                      $x, $y - $b,
                      $x - $r + $b, $y - $r,
                      $x - $r, $y - $r);
        /* Set x/y to the final point. */
        $x = $x - $r;
        $y = $y - $r;
        /* Fourth circle quarter. */
        $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c %s',
                      $x - $b, $y,
                      $x - $r, $y + $r - $b,
                      $x - $r, $y + $r,
                      $op);
        /* Output the whole string. */
        $this->_out($c);
    }

    /**
     * Terminates the PDF document. It is not necessary to call this method
     * explicitly because PDF::output does it automatically.
     * If the document contains no page, PDF::addPage is called to prevent
     * from getting an invalid document.
     *
     * @access public
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
        $this->_putFonts();              // Output any fonts.
        $this->_putImages();             // Output any images.

        /* Resources are always object number 2. */
        $this->_offsets[2] = strlen($this->_buffer);
        $this->_out('2 0 obj');
        $this->_out('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        $this->_out('/Font <<');
        foreach ($this->_fonts as $font) {
            $this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
        }
        $this->_out('>>');
        if (count($this->_images)) {     // Loop through any images
            $this->_out('/XObject <<');  // and output the objects.
            foreach ($this->_images as $image) {
                $this->_out('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
            }
            $this->_out('>>');
        }
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

    function _putImages()
    {
        /* Output any images. */
        $filter = ($this->_compress) ? '/Filter /FlateDecode ' : ''; 
        foreach ($this->_images as $file => $info) {
            $this->_newobj();
            $this->_images[$file]['n'] = $this->_n;
            $this->_out('<</Type /XObject');
            $this->_out('/Subtype /Image');
            $this->_out('/Width ' . $info['w']);    // Image width.
            $this->_out('/Height ' . $info['h']);   // Image height.
            $this->_out('/ColorSpace /' . $info['cs']); //Colorspace
            if ($info['cs'] == 'DeviceCMYK') {
                $this->_out('/Decode [1 0 1 0 1 0 1 0]');
            }   
            $this->_out('/BitsPerComponent ' . $info['bpc']); // Bits
            $this->_out('/Filter /' . $info['f']);  // Filter used.
            $this->_out('/Length ' . strlen($info['data']) . '>>');
            $this->_putStream($info['data']);       // Image data.
            $this->_out('endobj');
        }
    }

    function _parseJPG($file)
    {
        /* Extract info from the JPEG file. */
        $img = @getimagesize($file);
        if (!$img) {
            die(sprintf('Missing or incorrect image file: %s', $file));
        }

        /* Check if dealing with an actual JPEG. */
        if ($img[2] != 2) {
            die(sprintf('Not a JPEG file: %s', $file));
        }
        /* Get the image colorspace. */
        if (!isset($img['channels']) || $img['channels'] == 3) {
            $colspace = 'DeviceRGB';
        } elseif ($img['channels'] == 4) {
            $colspace = 'DeviceCMYK';
        } else {
            $colspace = 'DeviceGray';
        }
        $bpc = isset($img['bits']) ? $img['bits'] : 8;

        /* Read the whole file. */
        $f = fopen($file, 'rb');
        $data = fread($f, filesize($file));
        fclose($f);

        return array('w' => $img[0], 'h' => $img[1], 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'DCTDecode', 'data' => $data);
    }

}

?>
