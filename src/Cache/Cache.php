<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template\Cache;

interface Cache
{
    public function getCacheImplementation(): object;
}
