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
 * @package     GatewayTest
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL;
require_once 'PPL/Gateway.php';

/**
 * The main gateway facade class.
 *
 * @category    PPL
 * @package     GatewayTest
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
class GatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the adapter methods.
     */
    public function testAdapter()
    {
        require_once 'PPL/Gateway.php';
        $sut = new \PPL\Gateway();

        $this->assertFalse($sut->hasAdapter());

        require_once 'PPL/Gateway/GatewayInterface.php';
        $mockedAdapter = $this->getMock('\PPL\Gateway\GatewayInterface');
        $sut->setAdapter($mockedAdapter);

        $this->assertSame($mockedAdapter, $sut->getAdapter());
    }

    /**
     * Tests exception is thrown when adapter does not exist.
     */
    public function testAdapterException()
    {
        require_once 'PPL/Gateway.php';
        $sut = new \PPL\Gateway();

        $this->setExpectedException('\RuntimeException', 'No adapter present');

        $sut->getAdapter();
    }
}