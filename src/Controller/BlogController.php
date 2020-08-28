<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/{slug}", name="blog_article")
     */
    public function article()
    {
        return $this->render('blog/article.html.twig');
    }

    /**
     * @Route("/tag/{tag}", name="blog_tag")
     */
    public function tag()
    {
        return $this->render('blog/tag.html.twig');
    }
}
