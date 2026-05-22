<?php

namespace WebDev\BlogBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WebDev\BlogBundle\DTO\BlogFilter;
use WebDev\BlogBundle\Entity\Blog;
use WebDev\BlogBundle\Exception\BlogNotFoundException;
use WebDev\BlogBundle\Repository\BlogRepository;
use WebDev\BlogBundle\Service\Contract\BlogServiceInterface;
use WebDev\BlogBundle\Service\Contract\SlugGeneratorInterface;

class BlogService implements BlogServiceInterface
{
    public function __construct(
        private readonly SlugGeneratorInterface $slugger,
        private readonly BlogRepository $blogRepository,
        private readonly ValidatorInterface $validator,
        private readonly PaginatorInterface $paginator,
        private readonly int $blogsPerPage
    ){
    }

    public function create(Blog $blog): Blog
    {
        $blog->setSlug($this->slugger->generateUniqueSlug($blog->getTitle()));
        $this->validate($blog);
        $this->blogRepository->save($blog);

        return $blog;
    }

    public function update(Blog $blog): Blog
    {
        $blog->setSlug($this->slugger->generateUniqueSlug($blog->getTitle(), $blog->getId()));
        $this->validate($blog);
        $this->blogRepository->save($blog);

        return $blog;
    }

    /**
     * @throws BlogNotFoundException
     */
    public function delete(string $slug): void
    {
        $blog = $this->findOrFail($slug);
        $this->blogRepository->remove($blog);
    }

    /**
     * @throws BlogNotFoundException
     */
    public function get(string $slug): Blog
    {
        return $this->findOrFail($slug);
    }

    public function list(BlogFilter $filter, int $page): PaginationInterface
    {
        $blogs = $this->blogRepository->findFilteredBlogs($filter);

        return $this->paginator->paginate($blogs, $page, $this->blogsPerPage);
    }

    public function validate(Blog $blog): void
    {
        $errors = $this->validator->validate($blog);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
    }

    /**
     * @throws BlogNotFoundException
     */
    public function findOrFail(string $slug): Blog
    {
        $blog = $this->blogRepository->findOneBy(['slug' => $slug]);
        if (!$blog) {
            throw new BlogNotFoundException($slug);
        }
        return $blog;
    }
}