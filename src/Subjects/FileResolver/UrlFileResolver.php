<?php

/**
 * TechDivision\Import\Adapter\Custom\Subjects\FileResolver\UrlFileResolver
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

namespace TechDivision\Import\Adapter\Custom\Subjects\FileResolver;

use TechDivision\Import\Adapter\Custom\Utils\ConfigurationKeys;
use TechDivision\Import\Subjects\FileResolver\AbstractFileResolver;

/**
 * File resolver to load the filenames from an URL.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-adapter-custom
 * @link      http://www.techdivision.com
 */
class UrlFileResolver extends AbstractFileResolver
{

    /**
     * Return's the URL from the configuration.
     *
     * @return string The URL
     */
    protected function getUrl()
    {
        return rtrim($this->getSubjectConfiguration()->getConfiguration()->getParam(ConfigurationKeys::URL), '/');
    }

    /**
     * Return's the configured prefix.
     *
     * @return string The prefix
     */
    protected function getPrefix()
    {
        return $this->getFileResolverConfiguration()->getPrefix();
    }

    /**
     * Return's the filename, in our case simply the date.
     *
     * @return string The filename
     */
    protected function getFilename()
    {
        return date('Ymd-His');
    }

    /**
     * Queries whether or not, the passed filename should be handled by the subject.
     *
     * @param string $filename The filename to query for
     *
     * @return boolean TRUE if the file should be handled, else FALSE
     */
    protected function shouldBeHandled($filename)
    {

        // initialize the array with the matches
        $matches = array();

        // update the matches, if the pattern matches
        if ($result = preg_match($this->preparePattern(), $filename, $matches)) {
            $this->addMatch($matches);
        }

        // stop processing, because the filename doesn't match the subjects pattern
        return (boolean) $result;
    }

    /**
     * Load the filenames from the URL and return them as array.
     *
     * @param string $serial The unique identifier of the actual import process
     *
     * @return array The array with the files matching the subjects suffix
     * @throws \Exception Is thrown, when the source directory is NOT available
     */
    public function loadFiles(string $serial) : array
    {

        // initialize the resolver
        $this->initialize($serial);

        // initialize the array with the filenames
        $filenames = array();

        // prepare the target filename for the content of the URL we want to download
        $filename = sprintf('%s_%s_01.%s', $this->getPrefix(), $this->getFilename(), $this->getSuffix());
        $targetFilename = sprintf('%s/%s', $this->getSourceDir(), $filename);

        // initialize the context to download the HTML file
        $context = array(
            "ssl" => array(
                "verify_peer"      => false,
                "verify_peer_name" => false,
            )
        );

        // try to download the HTML file
        if ($content = file_get_contents($sourceUrl = $this->getUrl(), false, stream_context_create($context))) {
            // save the content in the configured source directory
            if (file_put_contents($targetFilename, $content)) {
                // query whether or not the file has to be handled
                if ($this->shouldBeHandled($targetFilename)) {
                    $filenames[] = $targetFilename;
                }
            } else {
                throw new \Exception(sprintf('Can\'t write content to file "%s" for further processing', $targetFilename));
            }
        } else {
            throw new \Exception(sprintf('Can\'t load content from URL "%s"', $sourceUrl));
        }

        // initialize the array with the files matching the suffix found in the source directory
        return $filenames;
    }
}
