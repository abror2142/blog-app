<?php

namespace WebDev\BlogBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use WebDev\BlogBundle\DependencyInjection\BlogExtension;

class WebDevBlogBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BlogExtension();
    }
}
