<?php

/**
 * TechDivision\Import\Adapter\Custom\Adapter\CustomImportAdapterFactory
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
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Adapter\Custom\Adapter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TechDivision\Import\Adapter\ImportAdapterFactoryInterface;
use TechDivision\Import\Configuration\ImportAdapterAwareConfigurationInterface;
use TechDivision\Import\Adapter\Custom\Utils\DependencyInjectionKeys;

/**
 * Factory for all CSV import adapter implementations.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */
class CustomImportAdapterFactory implements ImportAdapterFactoryInterface
{

    /**
     * The DI container instance.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Initialize the factory with the DI container instance.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The DI container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Creates and returns the import adapter for the subject with the passed configuration.
     *
     * @param \TechDivision\Import\Configuration\ImportAdapterAwareConfigurationInterface $configuration The subject configuration
     *
     * @return \TechDivision\Import\Adapter\ExportAdapterInterface The import adapter instance
     */
    public function createImportAdapter(ImportAdapterAwareConfigurationInterface $configuration)
    {

        // load the import adapter configuration
        $importAdapterConfiguration = $configuration->getImportAdapter();

        // load the serializer factory instance
        $serializerFactory = $this->container->get($importAdapterConfiguration->getSerializer()->getId());

        // create the instance and pass the import adapter configuration instance
        $importAdapter = $this->container->get(DependencyInjectionKeys::IMPORT_ADAPTER_IMPORT_CUSTOM);
        $importAdapter->init($importAdapterConfiguration, $serializerFactory);

        // return the initialized import adapter instance
        return $importAdapter;
    }
}
