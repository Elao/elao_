<?php

declare(strict_types=1);

namespace App\Stenope\Processor;

use App\Emoji\ElaomojiParser;
use App\Model\Article;
use Stenope\Bundle\Behaviour\ProcessorInterface;
use Stenope\Bundle\Content;

class ElaomojisProcessor implements ProcessorInterface
{
    private ElaomojiParser $emojiParser;
    private string $property;

    public function __construct(ElaomojiParser $emojiParser, string $property = 'content')
    {
        $this->emojiParser = $emojiParser;
        $this->property = $property;
    }

    public function __invoke(array &$data, Content $content): void
    {
        if (!is_a($content->getType(), Article::class, true)) {
            return;
        }

        $data[$this->property] = $this->emojiParser->parse($data[$this->property]);
    }
}
