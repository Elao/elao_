<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles redirection from legacy routes starting with "la-tribu" to "la-team".
 */
#[Route('/la-tribu')]
class LegacyTeamController extends AbstractController
{
    #[Route]
    public function list(): Response
    {
        return $this->redirectToRoute('team', [], Response::HTTP_MOVED_PERMANENTLY);
    }

    #[Route('/{member}')]
    public function show(Member $member): Response
    {
        return $this->redirectToRoute('team_member', [
            'member' => $member->slug,
        ], Response::HTTP_MOVED_PERMANENTLY);
    }
}
