<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use App\Router\ExternalUrlGenerator;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;
use Stenope\Bundle\Provider\Factory\LocalFilesystemProviderFactory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Adds the link to edit an article file on Github
 */
class GithubEditLinkProcessor implements ProcessorInterface
{
    private ExternalUrlGenerator $urlGenerator;
    private string $projectDir;
    private string $repository;
    private string $reference;

    public function __construct(ExternalUrlGenerator $urlGenerator, string $projectDir, string $repository, string $reference)
    {
        $this->urlGenerator = $urlGenerator;
        $this->projectDir = $projectDir;
        $this->repository = $repository;
        $this->reference = $reference;
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!is_a($content->getType(), Article::class, true)) {
            return;
        }

        if (LocalFilesystemProviderFactory::TYPE !== ($content->getMetadata()['provider'] ?? null)) {
            // Cannot resolve from a non local filesystem content.
            return;
        }

        $data['githubEditLink'] = $this->urlGenerator->generate('github_edit_file', [
            'repo' => $this->repository,
            'ref' => $this->reference,
            'file' => (new Filesystem())->makePathRelative($content->getMetadata()['path'], $this->projectDir),
        ]);
    }
}
