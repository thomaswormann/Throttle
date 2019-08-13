<?php

namespace ThomasWormann\Throttle\Store;

interface StoreInterface
{
    public function get($key);
    public function set($key);
}

