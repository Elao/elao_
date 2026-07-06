<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recrutement')]
class JobController extends AbstractController
{
    /**
     * The jobs listing now lives on the {@link SiteController::carriere "carriere" page}.
     * We keep this legacy route to permanently redirect to it.
     */
    #[Route(name: 'jobs', options: [
        'stenope' => ['sitemap' => false],
    ])]
    public function list(): Response
    {
        return $this->redirectToRoute('carriere', [], Response::HTTP_MOVED_PERMANENTLY);
    }

    #[Route('/{job<.+>}', name: 'job')]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ])->setLastModified($job->lastModified);
    }
}
