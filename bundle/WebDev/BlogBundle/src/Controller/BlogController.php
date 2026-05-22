<?php

namespace WebDev\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use WebDev\BlogBundle\DTO\BlogFilter;
use WebDev\BlogBundle\Entity\Blog;
use WebDev\BlogBundle\Exception\BlogNotFoundException;
use WebDev\BlogBundle\Form\BlogFilterType;
use WebDev\BlogBundle\Form\BlogFormType;
use WebDev\BlogBundle\Service\BlogService;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly BlogService $blogService
    ){}

    #[Route('/', name: 'blog_index')]
    public function index(Request $request): Response
    {
        $filter = new BlogFilter();
        $filterForm = $this->createForm(BlogFilterType::class, $filter, [
            'action' => $this->generateUrl('blog_index'),
        ]);
        $filterForm->handleRequest($request);

        $page = $request->query->getInt('page', 1);

        $paginatedBlogs = $this->blogService->list($filter, $page);

        return $this->render('@WebDevBlog/blog/index.html.twig', [
            'blogs' => $paginatedBlogs,
            'filterForm' => $filterForm
        ]);
    }

    #[Route('/new', name: 'blog_new')]
    public function createProduct(Request $request): Response
    {
        $blog = new Blog();

        $form = $this->createForm(BlogFormType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->blogService->create($blog);

            return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
        }

        return new Response($this->render('@WebDevBlog/blog/new.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    #[Route('/{slug}', name: 'blog_show')]
    public function show(string $slug): Response
    {
        try {
            $blog = $this->blogService->get($slug);
        } catch (BlogNotFoundException $e) {
            throw $this->createNotFoundException('Blog not found');
        }

        return $this->render('@WebDevBlog/blog/show.html.twig', [
            'blog' => $blog
        ]);
    }

    #[Route('/{slug}/edit', name: 'blog_edit')]
    public function edit(string $slug, Request $request,): Response
    {
        try {
            $blog = $this->blogService->get($slug);
        } catch (BlogNotFoundException $e) {
            throw  $this->createNotFoundException('Blog not found');
        }

        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blogService->update($blog);

            return $this->redirectToRoute('blog_show', [
                'slug' => $blog->getSlug()
            ]);
        }

        return $this->render('@WebDevBlog/blog/edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    #[Route('/{slug}/delete', name: 'blog_delete')]
    public function delete(string $slug): Response
    {
        try {
            $blog = $this->blogService->get($slug);
        } catch (BlogNotFoundException $e) {
            throw $this->createNotFoundException('Blog not found');
        }
        $this->blogService->delete($slug);

        return $this->redirectToRoute('blog_index');
    }
}
