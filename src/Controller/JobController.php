<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Job;
use Stenope\Bundle\ContentManagerInterface;
use Stenope\Bundle\Service\ContentUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recrutement')]
class JobController extends AbstractController
{
    private ContentManagerInterface $manager;

    public function __construct(ContentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route(name: 'jobs')]
    public function list(): Response
    {
        $jobs = $this->manager->getContents(Job::class, ['date' => false], ['active' => true]);

        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
        ])->setLastModified(\count($jobs) > 0 ? ContentUtils::max($jobs, 'lastModified') : null);
    }

    #[Route('/{job<.+>}', name: 'job')]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ])->setLastModified($job->lastModified);
    }
}
