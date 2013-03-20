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
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL;
require_once 'PPL/Gateway/GatewayInterface.php';

/**
 * The main gateway facade class.
 *
 * @category    PPL
 * @package     Gateway
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
class Gateway implements Gateway\GatewayInterface
{
    /**
     * Contains an instance of \PPL\Gateway\GatewayInterface adapter when set.
     *
     * @var \PPL\Gateway\GatewayInterface
     */
    protected $_adapter;

    /**
     * Sets the name of the adapter to be used.
     *
     * @param  \PPL\Gateway\GatewayInterface $adapter
     * @return \PPL\Gateway
     */
    public function setAdapter(\PPL\Gateway\GatewayInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Gets an instance of the adapter to be used. If
     * no adapter present an exception is thrown.
     *
     * @return \PPL\Gateway\GatewayInterface
     * @throws \RuntimeException
     */
    public function getAdapter()
    {
        if (false === $this->hasAdapter()) {
            throw new \RuntimeException('No adapter present');
        }

        return $this->_adapter;
    }

    /**
     * Checks to see if an adapter exists is set.
     *
     * @return boolean
     */
    public function hasAdapter()
    {
        if (!($this->_adapter instanceof \PPL\Gateway\GatewayInterface)) {
            return false;
        }

        return true;
    }
}