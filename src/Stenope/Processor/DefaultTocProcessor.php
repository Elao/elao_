<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Model\Article;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

class DefaultTocProcessor implements ProcessorInterface
{
    private string $tableOfContentProperty;

    public function __construct(string $tableOfContentProperty = 'tableOfContent')
    {
        $this->tableOfContentProperty = $tableOfContentProperty;
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!is_a($content->getType(), Article::class, true)) {
            return;
        }

        if (!isset($data[$this->tableOfContentProperty])) {
            // By default, always generate a TOC for articles, with max depth of 2:
            $data[$this->tableOfContentProperty] = 2;
        }
    }
}
