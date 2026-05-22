<?php

namespace WebDev\BlogBundle\Service\Contract;

use Knp\Component\Pager\Pagination\PaginationInterface;
use WebDev\BlogBundle\DTO\BlogFilter;
use WebDev\BlogBundle\Entity\Blog;

interface BlogServiceInterface
{
    public function create(Blog $blog): Blog;

    public function update(Blog $blog): Blog;

    public function delete(string $slug): void;

    public function get(string $slug): Blog;

    public function list(BlogFilter $filter, int $page): PaginationInterface;
}