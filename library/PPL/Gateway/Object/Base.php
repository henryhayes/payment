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
 * @subpackage  Object\Base
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
namespace PPL\Gateway\Object;

/**
 * The object base class.
 *
 * @category    PPL
 * @package     Gateway
 * @subpackage  Object\Base
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
abstract class Base
{
    /**
     * This option makes the object read only.
     *
     * @var boolean
     */
    protected $_isReadOnly = false;

    /**
     * Contains the configuration for this object in a name/value pair fashion.
     * For example:
     * keyName => array('required' => false);
     *
     * @var array
     */
    protected $_config = array();

    /**
     * The data in name/value pairs for the object.
     *
     * @var array
     */
    protected $_data = array();

    /**
     * The raw, unfiltered and unvalidatoed data in name/value pairs.
     *
     * @var array
     */
    protected $_rawData = array();

    /**
     * An array of Zend_Validate_Interface Objects indexed by parameter
     * name keys. Built in the _isValid() method.
     *
     * @var array
     */
    protected $_validators = array();

    /**
     * Object instantiation. If an array of data is passed
     * as the parameter, they are added to the object.
     *
     * This object is capable of being read-only. To use this functionality, please
     * ensure that you set the _isReadOnly property to true when building your
     * child class.
     *
     * @param array $data An array of key=>value pairs of data.
     */
    public function __construct(array $data = null)
    {
        // Read only workaround part 1 - see below
        $readonly = $this->_isReadOnly;
        $this->_isReadOnly = false;

        if (!is_null($data) && (count($data) > 0)) {
            foreach ($data as $name => $value) {
                $this->add($name, $value);
            }
        }

        // Read only workaround part 2
        $this->_isReadOnly = $readonly;

        $this->init();
    }

    /**
     * Please override this method in child classes.
     *
     * @return void
     */
    public function init()
    {
        //
    }

    /**
     * Adds a virtual property.
     *
     * @param  mixed $name
     * @param  mixed $value
     * @throws \UnexpectedValueException
     * @return PPL\Gateway\Object\Base
     */
    public function add($name, $value)
    {
        $this->_checkReadOnly();

        if (count($this->_config) && !array_key_exists($name, $this->_config)) {
            throw new \UnexpectedValueException("{$name} is not valid for this object");
        }

        $this->_rawData[$name] = $value;

        // Filter the value.
        $value = $this->_filter($name, $value);

        // Validate the value - if invalid, throw \UnexpectedValueException.
        if (false === $this->_isValid($name, $value)) {
            throw new \UnexpectedValueException(
                "'{$name}' invalid: " .
                    implode(', ', $this->getCurrentValidationErrorMessages($name))
                );
        }

        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Gets a virtual class property value.
     *
     * @param  string $name
     * @throws \UnexpectedValueException
     * @return mixed
     */
    public function get($name)
    {
        if (false === $this->__isset($name)) {

            // Since this value is not set, we look to find if a
            // default value is available - if so, return it.
            if ($default = $this->_getDefaultValue($name)) {

                // We do not filter the default value.
                return $default;

            } else {
                throw new \UnexpectedValueException("'{$name}' does not exist");
            }
        }

        $value = $this->_data[$name];

        return $value;
    }

    /**
     * Gets the raw (unfiltered) data that was added in the set() method. If you call the get()
     * method and catch the \UnexpectedValueException then you can get the original raw value
     * by calling this method. Return value of NULL means that there never was a value passed.
     *
     * @param  string $name
     * @return mixed
     */
    public function getUnfiltered($name)
    {
        if (true === array_key_exists($name, $this->_rawData)) {
            return $this->_rawData[$name];
        }

        return null;
    }

    /**
     * Removes a virtual property by name.
     *
     * @param  mixed $name
     * @return PPL\Gateway\Object\Base
     */
    public function remove($name)
    {
        $this->_checkReadOnly();

        if ($this->__isset($name)) {
            unset($this->_data[$name]);
        }

        return $this;
    }

    /**
     * Sets a virtual class property.
     *
     * @param  string $name
     * @param  string $value
     * @throws \UnexpectedValueException
     * @return void
     */
    public function __set($name, $value)
    {
        $this->add($name, $value);
    }

    /**
     * Magically gets a virtual class property value.
     *
     * @param  string $name
     * @throws \UnexpectedValueException
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Unsets the value if it exists.
     *
     * @param  mixed $name
     * @return void
     */
    public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Finds out if a virtual class property exists.
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (false === array_key_exists($name, $this->_data)) {
            return false;
        }

        return true;
    }

    /**
     * Returns an integer of the amount of virtual properties
     * inside this object.
     *
     * @link http://www.php.net/manual/en/countable.count.php
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     * Finds out if an array element exists by offset/key name.
     *
     * @param offset $offset
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Gets an array element by offset/key name.
     *
     * @param  offset $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->__get($offset);
    }

    /**
     * Sets an array element by offset/key name.
     *
     * @param mixed offset
     * @param mixed value
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * Unsets an array element by offset/key name.
     *
     * @param offset $offset
     */
    public function offsetUnset($offset)
    {
        $this->__unset($name);
    }

    /**
     * Checks if the object is read-only. If this is the case,
     * then only the constructor can populate this object.
     *
     * @throws \Exception
     */
    protected function _checkReadOnly()
    {
        if (true === $this->_isReadOnly) {
            throw new \Exception(
                $name . ' cannot be set as ' . get_class($this) . " is a read-only object"
            );
        }
    }

    /**
     * This object cannot be cast to a string. If you want your object
     * (that extends this one) to do so, please override __toString().
     *
     * @return \Exception
     */
    public function __toString()
    {
        throw new \Exception('This object cannot be cast to a string');
    }

    /**
     * Validates the parameter value, returns true if valid,
     * or if no validators exist, false if invalid.
     *
     * @param  string $name
     * @param  string $value
     * @return bool
     */
    protected function _isValid($name, $value)
    {
        /**
         * This will return true if the parameter is optional
         * and the value is empty.
         */
        if ($this->isOptional($name) && empty($value)) {
            return true;
        }

        $config = $this->_getConfig();

        if (!array_key_exists('validators', $config[$name])) {
            return true;
        }

        $validate = new Zend_Validate();
        foreach ($config[$name]['validators'] as $validator) {

            if (is_array($validator)) {
                if (!isset($validator[0])) {
                    require_once 'Zend/Validate/Exception.php';
                    throw new \Zend_Validate_Exception('There was no information for the validator');
                } else {
                    // Array keys = 0=name, 1=break-chain on failure, 2=constructorArrayParam, 3=namespace
                    if (isset($validator[3])) {
                        $validatorClassName = $this->_buildClassName($validator[3], $validator[0]);
                    } else {
                        $validatorClassName = 'Zend_Validate_' . $validator[0];
                    }
                    if (!isset($validator[2])) {
                        $validatorObj = new $validatorClassName();
                    } else {
                        $validatorObj = new $validatorClassName($validator[2]);
                    }
                    $validate->addValidator($validatorObj, (isset($validator[1]) ? $validator[1] : true));
                }
            } else {
                $validatorClassName = 'Zend_Validate_' . $validator;
                $validate->addValidator(new $validatorClassName(), true);
            }
        }

        $this->_validators[$name] = $validate;

        return $validate->isValid($value);
    }

    protected function _buildClassName($prefix, $name, $namespace = false)
    {
        if (strstr($prefix, "\\")) {
            $namespace = true;
        }

        if (true === $namespace) {
            $class = '\\' . ltrim($prefix, '\\') . '\\' . $name;
            require_once str_replace('\\', '/', $class) . 'php';
        } else {

            $class = $prefix . '_' . $name;
            require_once str_replace('_', '/', $class) . 'php';
        }

        return $class;
    }

    /**
     * Gets validation error messages for the field name.
     *
     * @param  string $name
     * @return array
     */
    public function getCurrentValidationErrorMessages($name)
    {
        return $this->_validators[$name]->getMessages();
    }

    /**
     * Returns the params array as-is.
     *
     * @return array
     */
    public function getRequiredNames()
    {
        $required = array();
        foreach ($this->_getConfig() as $name => $options) {
            if (true === $options['required']) {
                $required[] = $name;
            }
        }

        return $required;
    }

    /**
     * Returns bool if required or not.
     *
     * @param mixed $name
     * @return boolean
     */
    public function isOptional($name)
    {
        $optional = $this->getOptionalNames();
        if (in_array($name, $optional)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the defaut value if there is one.
     *
     * @param  string $name
     * @return string|null
     */
    protected function _getDefaultValue($name, $key = 'default')
    {
        $config = $this->_getConfig();

        // Does the $name exist in the expected params array? If not, return null.
        if (!array_key_exists($name, $config)) {
            return null;
        }

        // Is there a default value? If not, return null.
        if (!array_key_exists($key, $config[$name])) {
            return null;
        }

        return $config[$name][$key];
    }

    /**
     * Returns the params array as-is.
     *
     * @return array
     */
    public function getOptionalNames()
    {
        $optional = array();
        foreach ($this->_getConfig() as $name => $options) {
            if (false === $options['required'] || !array_key_exists('required', $options)) {
                $optional[] = $name;
            }
        }

        return $optional;
    }

    /**
     * Returns a filtered string.
     *
     * @param  string $name
     * @param  string $value
     * @return string
     */
    protected function _filter($name, $value)
    {
        $config = $this->_getConfig();

        if (!array_key_exists('filters', $config[$name])) {
            return $value;
        }

        foreach ($config[$name]['filters'] as $filter) {

            if (is_array($filter)) {

                // Array keys = 0=name, 1=constructorArrayParam, 2=namespace
                if (isset($filter[2])) {
                    $filterClassName = $this->_buildClassName($filter[2], $filter[0]);
                } else {
                    $filterClassName = 'Zend_Filter_' . $filter[0];
                }
                if (!isset($filter[1])) {
                    $filterObj = new $filterClassName();
                } else {
                    $filterObj = new $filterClassName($filter[1]);
                }
                $value = $filterObj->filter($value);
            } else {
                $filterClassName = 'Zend_Filter_' . $filter;
                $filterObj = new $filterClassName();
                $value = $filterObj->filter($value);
            }
        }

        return $value;
    }

    /**
     * Returns the params array as-is.
     *
     * @return array
     */
    protected function _getConfig()
    {
        return $this->_config;
    }
}