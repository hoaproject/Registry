<?php

declare(strict_types=1);

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Registry;

use Hoa\Consistency;
use Hoa\Protocol;

/**
 * Class \Hoa\Registry.
 *
 * Hold a register of something.
 */
class Registry extends \ArrayObject
{
    /**
     * Instance.
     *
     * @var self
     */
    private static $_instance = null;



    /**
     * Private constructor.
     */
    public function __construct()
    {
        throw new Exception(
            'Cannot instance the %s object. Use set, get, remove ' .
            'and isRegistered static methods instead.',
            0,
            __CLASS__
        );

        return;
    }

    /**
     * Get instance of \Hoa\Registry.
     */
    protected static function getInstance(): parent
    {
        if (null === static::$_instance) {
            static::$_instance = new parent();
        }

        return static::$_instance;
    }

    /**
     * Set a new registry.
     */
    public static function set($index, $value)
    {
        static::getInstance()->offsetSet($index, $value);

        return;
    }

    /**
     * Get a registry.
     */
    public static function get($index)
    {
        $registry = static::getInstance();

        if (false === $registry->offsetExists($index)) {
            throw new Exception(
                'Registry %s does not exist.',
                1,
                $index
            );
        }

        return $registry->offsetGet($index);
    }

    /**
     * Check if an index is registered.
     */
    public static function isRegistered($index): bool
    {
        return static::getInstance()->offsetExists($index);
    }

    /**
     * Unset an registry.
     */
    public static function remove($index)
    {
        static::getInstance()->offsetUnset($index);

        return;
    }
}

/**
 * Class \Hoa\Registry\_Protocol.
 *
 * The `hoa://Library/Registry` node.
 */
class _Protocol extends Protocol\Node
{
    /**
     * Component's name.
     *
     * @var string
     */
    protected $_name = 'Registry';



    /**
     * ID of the component.
     */
    public function reachId($id)
    {
        return Registry::get($id);
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity(Registry::class);

/**
 * Add the `hoa://Library/Registry` node. Should be use to reach/get an entry
 * in the registry, e.g.: resolve('hoa://Library/Registry#AnID')`.
 */
$protocol              = Protocol::getInstance();
$protocol['Library'][] = new _Protocol();
