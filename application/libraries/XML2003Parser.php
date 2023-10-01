<?php

/**
 * Excel 2003 XML-Parser
 * 
 * PHP library for parsing Microsoft Excel 2003 XML Spreadsheet
 * http://gist.github.com/862741
 *  
 * Copyright (c) 2011 Faisalman <movedpixel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @author		Faisalman
 * @copyright	2011 (c) Faisalman
 * @example		see example.php
 * @license		http://www.opensource.org/licenses/mit-license
 * @link		http://gist.github.com/862741
 * @package		SimpleExcel
 * @version		0.0.1
 */
class XML2003Parser {

    /**
     * Holds the parsed result
     * @access	private
     * @var		array
     */
    private $table_arr;
    private $total_row_num;
    private $read_minimum = FALSE;

    /**
     * @param	string	$url	Path to XML file (optional)
     * @param	bool	$escape	Set whether input had to be escaped from HTML tags, default to TRUE
     * @return	void
     */
    public function __construct($url = NULL, $escape = TRUE, $workbook_index = 0, $read_minimum = FALSE) {

        $this->read_minimum = $read_minimum;

        if (isset($url))
            $this->loadXMLFile($url, $escape, $workbook_index);
    }

    /**
     * Extract attributes from SimpleXMLElement object
     * @access	private
     * @param	object	$attrs_obj
     * @return 	array
     */
    private function getAttributes($attrs_obj) {
        $attrs_arr = array();
        foreach ($attrs_obj as $attrs) {
            $attrs = (array) $attrs;
            foreach ($attrs as $attr) {
                $attr_keys = array_keys($attr);
                $attrs_arr[$attr_keys[0]] = $attr[$attr_keys[0]];
            }
        }
        return $attrs_arr;
    }

    public function getTotalRowNum() {
        return $this->total_row_num;
    }

    /**
     * Get data of the specified cell as an array
     * @param	int	$row_num	Row number
     * @param	int	$col_num	Column number
     * @return	mixed			Returns an array or FALSE if cell doesn't exist
     */
    public function getCellData($row_num, $col_num) {

        // check whether the cell exists
        if (!isset($this->table_arr['table_contents'][$row_num - 1]['row_contents'][$col_num - 1])) {
            return FALSE;
        }
        return $this->table_arr['table_contents'][$row_num - 1]['row_contents'][$col_num - 1];
    }

    /**
     * Get data of the specified column as an array
     * @param	int	$col_num	Column number
     * @return	mixed			Returns an array or FALSE if table doesn't exist
     */
    public function getColumnData($col_num) {
        $col_arr = array();

        if (!isset($this->table_arr['table_contents'])) {
            return FALSE;
        }
        // get the specified column within every row
        foreach ($this->table_arr['table_contents'] as $row) {
            array_push($col_arr, $row['row_contents'][$col_num - 1]);
        }

        // return the array, if empty then return FALSE
        return $col_arr;
    }

    /**
     * Get data of the specified row as an array
     * @param	int	$row_num	Row number
     * @return	mixed			Returns an array FALSE if row doesn't exist
     */
    public function getRowData($row_num) {
        if (!isset($this->table_arr['table_contents'][$row_num - 1]['row_contents'])) {
            return FALSE;
        }
        $row = $this->table_arr['table_contents'][$row_num - 1]['row_contents'];
        $row_arr = array();

        // get the specified column within every row 
        foreach ($row as $cell) {
            array_push($row_arr, $cell);
        }
        // return the array, if empty then return FALSE			
        return $row_arr;
    }

    /**
     * Get data of all cells as an array
     * @return	mixed	Returns an array or FALSE if table doesn't exist
     */
    public function getTableData() {
        return isset($this->table_arr) ? $this->table_arr : FALSE;
    }

    /**
     * Load the XML file to be parsed
     * @param	string	$url	Path to XML file
     * @param	bool	$escape	Set whether input had to be escaped from HTML tags, default to TRUE
     * @return	bool			Returns TRUE if file exist and valid, FALSE if does'nt
     * @todo					Check for valid XML 2003 namespace
     */
    public function loadXMLFile($url, $escape = TRUE, $workbook_index = 0) {
        $this->table_arr = array();
        if (!$this->read_minimum) {
            $this->table_arr = array(
                'doc_props' => array(),
                'table_contents' => array()
            );
        }
        $simplexml_table = simplexml_load_file($url);
        // assign simpleXML object


        if ($simplexml_table) {

            // check XML namespace and return if the loaded file isn't a valid XML 2003 spreadsheet					
            $xmlns = $simplexml_table->getDocNamespaces();
            if ($xmlns['ss'] != 'urn:schemas-microsoft-com:office:spreadsheet') {
                return FALSE;
            }
        } else {

            // when error loading file
            return FALSE;
        }
        // extract document properties
        $doc_props = (array) $simplexml_table->DocumentProperties;
//var_dump($this->read_minimum);exit;
        if (!$this->read_minimum) {
            $this->table_arr['doc_props'] = $doc_props;
        }

        if ($workbook_index > count($simplexml_table->Worksheet)) {
            return FALSE;
        }

        $rows = $simplexml_table->Worksheet[$workbook_index]->Table->Row;

        $row_num = 1;



        // loop through all rows		
        foreach ($rows as $row) {
            $cells = $row->Cell;
            $row_attrs = $row->xpath('@ss:*');
            $row_attrs_arr = $this->getAttributes($row_attrs);
            $row_arr = array();
            $col_num = 1;

            // loop through all row's cells
            foreach ($cells as $cell) {

                // check whether ss:Index attribute exist
                $cell_index = $cell->xpath('@ss:Index');

                // if exist, push empty value until the specified index
                if (count($cell_index) > 0) {
                    $gap = $cell_index[0] - count($row_arr);
                    for ($i = 1; $i < $gap; $i++) {
                        $arr_data = $this->read_minimum ?  '' : array('row_num' => $row_num,
                            'col_num' => $col_num,
                            'datatype' => '',
                            'value' => '');


                        array_push($row_arr, $arr_data);
                        $col_num += 1;
                    }
                }
                // get all cell and data attributes				
                $cell_attrs = $cell->xpath('@ss:*');
                $cell_attrs_arr = $this->getAttributes($cell_attrs);
                // extract data from cell
                $cell_value = (string) $cell->Data;

                $cell_datatype = 'String';
                if ($cell_value != "") {
                    $data_attrs = $cell->Data->xpath('@ss:*');
                    $data_attrs_arr = $this->getAttributes($data_attrs);
                    $cell_datatype = $data_attrs_arr['Type'];
                }



                // filter from any HTML tags
                if ($escape)
                    $cell_value = htmlspecialchars($cell_value);

                $arr_data = $this->read_minimum ?  $cell_value : array('row_num' => $row_num,
                    'col_num' => $col_num,
                    'datatype' => $cell_datatype,
                    'value' => $cell_value);
                // push column array
                array_push($row_arr, $arr_data);
                $col_num += 1;
            }
            if ($this->read_minimum) {
                // push row array
                array_push($this->table_arr, array(
                    'row_num' => $row_num,
                    'row_contents' => $row_arr,
                        //'row_attrs' => $row_attrs_arr
                ));
            } else {
                // push row array
                array_push($this->table_arr['table_contents'], array(
                    'row_num' => $row_num,
                    'row_contents' => $row_arr,
                        //'row_attrs' => $row_attrs_arr
                ));
            }
            $row_num += 1;
        }

        $this->total_row_num = $row_num;

        // load succeed :)
        return TRUE;
    }

}

?>