<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Member;
use Content\ContentManager;
use Content\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/la-tribu",)
 */
class TeamController extends AbstractController
{
    private ContentManager $manager;

    public function __construct(ContentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="team")
     */
    public function team(): Response
    {
        $members = $this->manager->getContents(Member::class, ['name' => true], ['active' => true]);

        return $this->render('team/index.html.twig', [
            'members' => $members,
        ])->setLastModified(ContentUtils::max($members, 'lastModified'));
    }

    /**
     * @Route("/{member}", name="team_member")
     */
    public function teamMember(Member $member): Response
    {
        $articles = $this->manager->getContents(
            Article::class,
            ['date' => false],
            fn ($article) => $article->hasAuthor($member, 1)
        );

        return $this->render('team/member.html.twig', [
            'member' => $member,
            'articles' => \array_slice($articles, 0, 3),
        ])->setLastModified($member->lastModified);
    }
}
