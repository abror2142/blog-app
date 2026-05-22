<?php

namespace WebDev\BlogBundle\Service\Contract;

interface SlugGeneratorInterface
{
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string;
}