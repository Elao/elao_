<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Job;
use App\Model\Member;
use App\Model\Misc;
use Stenope\Bundle\ContentManagerInterface;
use Stenope\Bundle\Service\ContentUtils;
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
        $members = $manager->getContents(Member::class, [], ['active' => true, 'meta' => false]);
        $caseStudies = $manager->getContents(CaseStudy::class, ['date' => false], ['enabled' => true]);

        return $this->render('site/home.html.twig', [
            'lastTwoArticles' => \array_slice($articles, 0, 2),
            'lastThreeCaseStudies' => \array_slice($caseStudies, 0, 3),
            'membersCount' => \count($members),
        ]);
    }

    #[Route('/demarche', name: 'approach')]
    public function approach(): Response
    {
        return $this->render('site/approach/approach.html.twig');
    }

    #[Route('/demarche/collaboration', name: 'collaboration')]
    public function collaboration(): Response
    {
        return $this->render('site/approach/collaboration.html.twig');
    }

    #[Route('/demarche/methodologie', name: 'methodology')]
    public function methodology(): Response
    {
        return $this->render('site/approach/methodology.html.twig');
    }

    #[Route('/nos-valeurs', name: 'values')]
    public function values(ContentManagerInterface $manager): Response
    {
        $activeMembers = $manager->getContents(Member::class, null, ['active' => true, 'meta' => false]);
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

    #[Route('/carriere', name: 'carriere')]
    public function carriere(ContentManagerInterface $manager): Response
    {
        $jobs = $manager->getContents(Job::class, ['date' => false], ['active' => true]);

        return $this->render('site/carriere.html.twig', [
            'jobs' => $jobs,
        ])->setLastModified(\count($jobs) > 0 ? ContentUtils::max($jobs, 'lastModified') : null);
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
