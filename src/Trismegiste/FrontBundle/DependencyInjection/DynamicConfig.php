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
            ],
            'google' => [
                'public' => '854627745898-nds0remuikt5kl9mqmk8rsuu4po58has.apps.googleusercontent.com',
                'secret' => 'Pl70IFyTk-cX0HX6rnW103pi'
            ],
            'tumblr' => [
                'public' => 'KDMaisoZ60adrCrr0DssQ8amSoBUjqPCinReXynokw97QDm0j3',
                'secret' => 'jNeOvNwO9kV89kMgPfJ3GC6p01EsuI9zZXCjvIAVSl2Agova2a'
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