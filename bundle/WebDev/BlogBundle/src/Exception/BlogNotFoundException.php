<?php

namespace WebDev\BlogBundle\Exception;

class BlogNotFoundException extends \Exception
{
    public function __construct(string $slug)
    {
        parent::__construct(sprintf('Blog with slug "%d" was not found.', $slug));
    }
}