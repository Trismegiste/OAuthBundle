<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\DependencyInjection;

/**
 * DynamicConfig is 
 */
class DynamicConfig implements \ArrayAccess
{

    public function offsetExists($offset)
    {
        
    }

    public function offsetGet($offset)
    {
        return [
            'twitter' => [
                'public' => '',
                'secret' => ''
            ],
            'github' => [
                'public' => '51a83ff9f1216abd83ee',
                'secret' => '36a8f497751ba31321ad47fbba68fc42eb24bf8e'
            ]
        ];
    }

    public function offsetSet($offset, $value)
    {
        
    }

    public function offsetUnset($offset)
    {
        
    }

}