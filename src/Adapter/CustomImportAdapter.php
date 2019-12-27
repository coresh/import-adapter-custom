<?php

/**
 * TechDivision\Import\Adapter\Custom\Adapter\CustomImportAdapter
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-adapter-custom
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Adapter\Custom\Adapter;

use PHPHtmlParser\Dom;
use TechDivision\Import\Adapter\CsvImportAdapter;

/**
 * Custom import adapter implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-adapter-custom
 * @link      http://www.techdivision.com
 */
class CustomImportAdapter extends CsvImportAdapter
{

    /**
     * Imports the content from somewhere with the passed filename, which can also be an URL for example.
     *
     * @param callable $callback The callback that processes the row
     * @param string   $filename The filename to process
     *
     * @return void
     */
    public function import(callable $callback, $filename)
    {

        // open your HTML file here
        $dom = new Dom();
        $dom->loadFromFile($filename);

        // create at least the header and one row
        $rows = array(
            array('sku','store_view_code','attribute_set_code','product_type', 'name'),
            array('24-MB01', null,'Default','simple', $dom->find('title', 0))
        );

        // this is NOT the best way, as it'll consume a lot of memory, better will
        // be a streaming approach by loading and processing one row after another
        foreach ($rows as $row) {
            $callback($row);
        }
    }
}
