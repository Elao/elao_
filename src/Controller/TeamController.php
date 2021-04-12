<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Member;
use Stenope\Bundle\ContentManager;
use Stenope\Bundle\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/la-tribu")
 */
class TeamController extends AbstractController
{
    private ContentManager $manager;

    public function __construct(ContentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route(name="team")
     */
    public function list(): Response
    {
        $members = $this->manager->getContents(Member::class, ['name' => true], ['active' => true]);

        return $this->render('team/index.html.twig', [
            'members' => $members,
        ])->setLastModified(ContentUtils::max($members, 'lastModified'));
    }

    /**
     * @Route("/{member}", name="team_member")
     */
    public function show(Member $member): Response
    {
        $articles = $this->manager->getContents(
            Article::class,
            ['date' => false],
            fn (Article $article): bool => $article->hasAuthor($member, 1)
        );

        $projects = $this->manager->getContents(
            CaseStudy::class,
            ['date' => false],
            fn (CaseStudy $project): bool => $project->hasMember($member) && $project->enabled
        );

        return $this->render('team/member.html.twig', [
            'member' => $member,
            'articles' => \array_slice($articles, 0, 3),
            'projects' => \array_slice($projects, 0, 3),
        ])->setLastModified($member->lastModified);
    }

    /**
     * @Route("/{member}/signature", name="team_member_mail_signature", options={ "stenope": { "sitemap": false }})
     */
    public function emailSignature(Member $member): Response
    {
        return $this->render('team/mail_signature.html.twig', [
            'member' => $member,
        ])->setLastModified($member->lastModified);
    }
}
