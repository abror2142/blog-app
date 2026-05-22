<?php

namespace WebDev\BlogBundle\DTO;

use WebDev\BlogBundle\Enum\Status;

class BlogFilter
{
    public ?string $title = null;

    public ?Status $status = null;
}