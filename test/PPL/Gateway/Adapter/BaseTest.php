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
 * @subpackage  Adapter\BaseTest
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL\Gateway\Adapter;
require_once 'PPL/Gateway/Adapter/Base.php';

/**
 * The gateway adapter abstract class test.
 *
 * @category    PPL
 * @package     Gateway
 * @subpackage  Adapter\BaseTest
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    //



    /**
     * Tests the set/getOptions implementation.
     */
    public function testGetSetOptions()
    {
        $sut = $this->getAbstractMock(array());

        /**
         * Let's set the options and test thatw we can retrieve them.
         */
        $username = 'glumesc';
        $password = 'da';

        $options = array(
            'user' => $username,
            'pass' => $password,
        );

        /**
         * Tests set and get options.
         */
        $sut->setOptions($options, true);
        $this->assertSame($options, $sut->getOptions());

        /**
         * Test that getOption method returns correctly.
         */
        $this->assertEquals($username, $sut->getOption('user'));

        /**
         * Finally, let's test the exception when incorrect option value is called.
         */
        $message = sprintf("Option '%s' was not set", 'does-not-exist-option');
        $this->setExpectedException('\InvalidArgumentException', $message);
        $sut->getOption('does-not-exist-option');
    }

    /**
     * Tests the default options implementation.
     */
    public function testGetSetDefaultOptions()
    {
        $sut = $this->getAbstractMock(array());

        $username = 'glumesc';
        $password = 'da';

        $defaultOptions = array(
            'username' => $username,
        );

        /**
         * Tests set and get default options.
         */
        $sut->setDefaultOptions($defaultOptions, true);
        $this->assertSame($defaultOptions, $sut->getDefaultOptions());

        /**
         * Set override options
         */
        $options = array(
            'password' => $password,
        );
        $sut->setOptions($options, true);

        /**
         * Combine the two
         */
        $combinedOptions = array_merge($defaultOptions, $options);

        /**
         * Assert that get options returns both default and override options.
         */
        $this->assertSame($combinedOptions, $sut->getOptions());
    }

    /**
     * Tests that the constructor sets the options correctly.
     */
    public function testConstructorSetsOptions()
    {
        $username = 'glumesc';
        $password = 'da';

        $options = array(
            'username' => $username,
            'password' => $password,
        );

        /**
         * Tests set and get options.
         */
        $sut = $this->getAbstractMock(array($options));
        $this->assertSame($options, $sut->getOptions());

        // Test with ZF config object.
        require_once 'Zend/Config.php';
        $config = new \Zend_Config($options);
        $sut = $this->getAbstractMock(array($config));
        $this->assertSame($options, $sut->getOptions());
    }


    /**
     * Returns an instance of the mocked abstract class. We have to mock
     * the absract class as we cannot call it directly.
     *
     * @param    array $constructorArgs
     * @param    array $mockedMethods
     * @return   \PPL\Gateway\Adapter\Base
     */
    public function getAbstractMock(array $constructorArgs = array(), array $mockedMethods = array())
    {
        srand(time() * rand(0, 100000000000000));

        return $this->getMockForAbstractClass(
            '\PPL\Gateway\Adapter\Base',
            $constructorArgs,
            'PPL_Gateway_Adapter_Base_' . rand(),
            /* callOriginalConstructor */ true,
            /* callOriginalClone */ true,
            /* callAutoload */ true,
            /* mockedMethods */ $mockedMethods
        );
    }
}