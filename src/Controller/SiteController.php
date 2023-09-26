<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Member;
use App\Model\Misc;
use Stenope\Bundle\ContentManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(ContentManagerInterface $manager): Response
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
        return $this->render('site/services/services.html.twig');
    }

    #[Route('/nos-services/ia', name: 'ia', options: [
        'stenope' => [
            'sitemap' => true,
        ],
    ])]
    public function iaServices(): Response
    {
        return $this->render('site/services/ia.html.twig');
    }

    #[Route('/methodologie', name: 'methodology')]
    public function methodology(): Response
    {
        return $this->render('site/methodology.html.twig');
    }

    #[Route('/nos-valeurs', name: 'values')]
    public function values(ContentManagerInterface $manager): Response
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

    #[Route('/atelier-innovation', name: 'innovation-workshop', options: [
        'stenope' => [
            'sitemap' => false,
        ],
    ])]
    public function innovationWorkshop(): Response
    {
        return $this->render('site/innovation-workshop.html.twig');
    }

    #[Route('/confidentialite', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('site/privacy.html.twig');
    }

    #[Route('/elaomojis', name: 'elaomojis')]
    public function elaomojis(ContentManagerInterface $manager): Response
    {
        return $this->render('site/elaomojis.html.twig', [
            'config' => $manager->getContent(Misc::class, 'elaomojis'),
        ]);
    }

    #[Route('/social', name: 'social', options: [
        'stenope' => ['sitemap' => false],
    ])]
    public function social(): Response
    {
        return $this->render('site/social.html.twig');
    }
}
