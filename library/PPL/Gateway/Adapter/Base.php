<?php
/**
 * PHP Payment Library
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * @category    PPL
 * @package     Gateway
 * @subpackage  Adapter\Base
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL\Gateway\Adapter;
require_once 'PPL/Gateway/GatewayInterface.php';

/**
 * The gateway adapter abstract class.
 *
 * @category    PPL
 * @package     Gateway
 * @subpackage  Adapter\Base
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
abstract class Base implements \PPL\Gateway\GatewayInterface
{
    /**#@+
     * Options array
     *
     * @var array
     */
    protected $_defaultOptions = array();
    protected $_options = array();
    /**#@-*/

    /**
     * Constructor takes options array as a parameter.
     *
     * @param  \Zend_Config|array $options
     * @return \PPL\Gateway\Adapter\Base
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            if ($options instanceof \Zend_Config) {
                $this->setOptions($options->toArray());
            } else {
                $this->setOptions($options);
            }
        }
    }

    /**
     * Sets options into an array.
     *
     * @param  array $options
     * @return \PPL\Gateway\Adapter\Base
     */
    public function setOptions(array $options, $reset = false)
    {
        if (true === $reset) {
            $this->_options = array();
        }

        /**
         * We loop through, so as not to destroy any options previously set.
         */
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }

        return $this;
    }

    /**
     * Allows to set an option midway through using the adapter.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return \PPL\Gateway\Adapter\Base
     */
    public function setOption($key, $value)
    {
        $this->_options[$key] = $value;
        return $this;
    }

    /**
     * Gets the local options.
     *
     * @return array
     */
    public function getOptions()
    {
        /**
         * $this->_options overrides default options, but nonetheless, we need the default options.
         */
        $this->_options = array_merge($this->getDefaultOptions(), $this->_options);
        return $this->_options;
    }

    /**
     * Gets a single option.
     *
     * @param  string $option
     * @throws \InvalidArgumentException
     * @return array
     */
    public function getOption($option)
    {
        $options = $this->getOptions();
        if (!array_key_exists($option, $options)) {
            throw new \InvalidArgumentException("Option '{$option}' was not set");
        }

        return $options[$option];
    }

    /**
     * Sets options into an array.
     *
     * @param  array $options
     * @return \PPL\Gateway\Adapter\Base
     */
    public function setDefaultOptions(array $options)
    {
        $this->_defaultOptions = $options;
        return $this;
    }

    /**
     * Gets the options array.
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->_defaultOptions;
    }
}