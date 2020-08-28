<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/la-tribu",)
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/", name="team")
     */
    public function team()
    {
        return $this->render('team/index.html.twig', [
            'members' => []
        ]);
    }

    /**
     * @Route("/{slug}", name="team_member")
     */
    public function teamMember($slug)
    {
        return $this->render('team/member.html.twig', [
            'member' => []
        ]);
    }
}
