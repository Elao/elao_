<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use App\Model\CaseStudy;
use App\Model\Member;
use App\Model\Technology;
use Stenope\Bundle\Behaviour\ContentManagerAwareInterface;
use Stenope\Bundle\Behaviour\ContentManagerAwareTrait;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

/**
 * Fetch technologies from Technology list
 */
class TechnologyProcessor implements ProcessorInterface, ContentManagerAwareInterface
{
    use ContentManagerAwareTrait;

    public function __invoke(array &$data, string $type, Content $content): void
    {
        if (!is_a($type, CaseStudy::class, true)) {
            return;
        }

        if (!isset($data['technologies']) && !isset($data['techonology'])) {
            throw new \Exception('At least one techonology must be specified.');
        }

        $technologies = $data['technologies'] ?? $data['techonology'];

        $data['technologies'] = array_map(
            fn (string $id) => $this->contentManager->getContent(Technology::class, $id),
            \is_array($technologies) ? $technologies : [$technologies]
        );
    }
}
