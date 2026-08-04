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

    /**
     * Plan du site — WCAG 2.4.5 (Accès multiples) / RGAA 12.7.
     *
     * À ne pas confondre avec le `sitemap.xml` que Stenope génère déjà : celui-ci
     * s'adresse aux robots, celui-là aux humains. Le critère demande un second moyen
     * d'atteindre chaque page, en plus de la navigation principale.
     *
     * Les collections sont chargées avec les mêmes filtres et le même tri que leurs
     * pages d'index respectives, pour que le plan reflète ce que l'on trouve
     * réellement en naviguant. Les 164 articles ne sont volontairement pas listés :
     * un plan du site donne la structure, pas un index exhaustif — on s'arrête aux
     * 7 rubriques du blog.
     */
    #[Route('/plan-du-site', name: 'sitemap')]
    public function sitemap(ContentManagerInterface $manager): Response
    {
        $articles = $manager->getContents(Article::class, ['date' => false]);

        // Rubriques du blog déduites du premier segment de slug (`dev/mon-article`),
        // comme le fait la route `blog_articles_from_path`.
        $categories = [];
        foreach ($articles as $article) {
            $segments = explode('/', $article->slug);
            if (\count($segments) > 1) {
                $categories[$segments[0]] = true;
            }
        }
        ksort($categories);

        return $this->render('site/sitemap.html.twig', [
            'blogCategories' => array_keys($categories),
            'caseStudies' => $manager->getContents(CaseStudy::class, ['date' => false], ['enabled' => true]),
            'members' => $manager->getContents(Member::class, ['name' => true], ['active' => true, 'meta' => false]),
            'jobs' => $manager->getContents(Job::class, ['date' => false], ['active' => true]),
        ]);
    }
}
