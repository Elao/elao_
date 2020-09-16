<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Member;
use Content\ContentManager;
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
        return $this->render('team/index.html.twig', [
            'members' => $this->manager->getContents(Member::class, ['name' => true]),
        ]);
    }

    /**
     * @Route("/{member}", name="team_member")
     */
    public function teamMember(Member $member): Response
    {
        return $this->render('team/member.html.twig', [
            'member' => $member,
        ]);
    }
}
