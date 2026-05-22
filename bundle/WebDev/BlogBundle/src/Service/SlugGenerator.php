<?php

namespace WebDev\BlogBundle\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use WebDev\BlogBundle\Repository\BlogRepository;
use WebDev\BlogBundle\Service\Contract\SlugGeneratorInterface;

class SlugGenerator implements SlugGeneratorInterface
{
    public function __construct(
        private readonly BlogRepository $blogRepository,
        private readonly SluggerInterface $slugger,
    ){
    }

    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = $this->slugger->slug($title)->lower();
        $slug = $baseSlug;
        $suffix = 2;

        while($this->blogRepository->slugExists($slug, $excludeId)){
            $slug = $baseSlug . '-' . $suffix++;
        }

        return uniqid($title, true);
    }

}