<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Member;
use Stenope\Bundle\ContentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(ContentManager $manager): Response
    {
        /** @var Article[] $articles */
        $articles = $manager->getContents(Article::class, ['date' => false]);
        $members = $manager->getContents(Member::class, [], ['active' => true]);

        return $this->render('site/home.html.twig', [
            'lastArticle' => current($articles),
            'membersCount' => \count($members),
        ]);
    }

    #[Route('/nos-services', name: 'services')]
    public function services(): Response
    {
        return $this->render('site/services.html.twig');
    }

    #[Route('/methodologie', name: 'methodology')]
    public function methodology(): Response
    {
        return $this->render('site/methodology.html.twig');
    }

    #[Route('/nos-valeurs', name: 'values')]
    public function values(ContentManager $manager): Response
    {
        $activeMembers = $manager->getContents(Member::class, null, static fn (Member $member): bool => $member->active);
        $count = \count($activeMembers);
        $velotafCount = \count(array_filter($activeMembers, static fn (Member $member): bool => $member->🚲));

        return $this->render('site/values.html.twig', [
            'velotafRatio' => $velotafCount / $count,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig');
    }

    #[Route('/legal', name: 'legal')]
    public function legal(): Response
    {
        return $this->render('site/legal.html.twig');
    }

    #[Route('/confidentialite', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('site/privacy.html.twig');
    }
}
