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
 * @subpackage  Object\Card
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL\Gateway\Object;
require_once 'PPL/Gateway/Object/Base.php';

/**
 * The gateway adapter for DataCash.
 *
 * @category    PPL
 * @package     Gateway
 * @subpackage  Object\Card
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
class Card extends Base
{
    protected $_config = array(
        // Card Number
        'number' => array(
            'required' => true,
            'filters'    => array(
                // 0=name, 1=constructorArrayParam, 2=namespace
                array('Digits', null),
            ),
            'validators' => array(
                // 0=name, 1=break-chain on failure, 2=constructorArrayParam, 3=namespace
                array('Digits', true, null),
                array('CreditCard', true, array('min' => '2', 'max' => '2'), 'PPL\Filter'),
            ),
            'default' => false,
        ),
        // Card Holder's Name
        'name' => array(
            'required' => true,
            'filters'    => array(
                // 0=name, 1=constructorArrayParam, 2=namespace
                array('Alnum', array(true)),
            ),
            'validators' => array(
                // 0=name, 1=break-chain on failure, 2=constructorArrayParam, 3=namespace
                array('Alnum', true, array(true))
            ),
            'default' => false,
        ),
        // Start Date Day
        'startDateMonth' => array(
            'required' => false,
            'filters'    => array(
                // 0=name, 1=constructorArrayParam, 2=namespace
                array('Digits', null),
            ),
            'validators' => array(
                // 0=name, 1=break-chain on failure, 2=constructorArrayParam, 3=namespace
                array('Digits', true, null),
                array('StringLength', true, array('min' => '2', 'max' => '2')),
            ),
            'default' => false,
        ),
        // Start Date Year
        'startDateYear' => array(
            'required' => false,
            'filters'    => array(
                // 0=name, 1=constructorArrayParam, 2=namespace
                array('Digits', null),
            ),
            'validators' => array(
                // 0=name, 1=break-chain on failure, 2=constructorArrayParam, 3=namespace
                array('Digits', true, null),
                array('StringLength', true, array('min' => '2', 'max' => '2')),
            ),
            'default' => false,
        ),
    );
}