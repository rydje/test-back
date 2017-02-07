<?php

abstract class AbstractEntity
{
    protected static $_tokenList = [];

    public static function getTokenList()
    {
        return static::$_tokenList;
    }

    public function renderToken($token)
    {
        if (in_array($token, static::$_tokenList)) {
            $tokenRenderMethod = 'render' . str_replace(' ', '', ucwords(str_replace('_', ' ', $token)));
            $result = $tokenRenderMethod();
        } else {
            // Log notice: token $token is not declared in the get_class($this) token list.
            $result = null;
        }
        return $result;
    }
}