<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Member;
use Stenope\Bundle\ContentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Yaml\Yaml;

class GenerateArticleCommand extends Command
{
    protected static $defaultName = 'app:generate:article';

    private string $path;
    private ContentManager $contents;
    private Filesystem $filesystem;

    public function __construct(string $path, ContentManager $contents)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = new Filesystem();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Créer un nouvel article');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Création d\'un nouvel article');

        $title = $io->ask('Titre', null, Validation::createCallable(new NotBlank([
            'message' => 'Le titre est obligatoire.',
        ])));

        $slug = $io->ask('Slug', $this->getSlug($title));
        $category = $io->choice('Catégorie', $this->listCategories(), 'dev');

        if ($this->exists($category, $slug)) {
            throw new \RuntimeException(sprintf('L\'article "%s/%s" existe déjà.', $category, $slug));
        }

        $authors = $io->askQuestion(
            (new ChoiceQuestion('Auteur(e)s (séparé(e)s par des virgules)', $this->listAuthors()))->setMultiselect(true)
        );

        $date = $io->ask('Date de publication', (new \DateTimeImmutable())->format('Y-m-d'));
        $description = $io->ask('Description');
        $tags = array_map('trim', explode(',', $io->ask('Tags (séparés par des virgules)') ?? ''));
        $thumbnail = $io->ask('Miniature', "images/posts/thumbnails/$slug.jpg");

        $io->definitionList(
            ['Titre' => $title],
            ['Slug' => $slug],
            ['Categorie' => $category],
            ['Auteur(e)s' => implode(', ', $authors)],
            ['Date' => $date],
            ['Description' => $description],
            ['Tag(s)' => implode(', ', $tags)],
            ['Miniature' => $thumbnail],
        );

        if (!$io->confirm('Confirmer la création ?')) {
            return 0;
        }

        $io->note('Création du fichier ...');

        $this->createMarkdownFile(
            $filepath = "$category/$slug.md",
            [
                new Header(['title' => $title]),
                new Header(['date' => $date], 'Au format YYYY-MM-DD'),
                new Header(
                    ['lastModified' => $date],
                    'À utiliser pour indiquer explicitement qu\'un article à été mis à jour',
                    false
                ),
                new Header(['description' => $description]),
                new Header(
                    [(\count($authors) > 1 ? 'authors' : 'author') => \count($authors) > 1 ? $authors : $authors[0]],
                    'author|authors (multiple acceptés)'
                ),
                new Header(
                    ['tableOfContent' => true],
                    '`true` pour activer ou `3` pour lister les titres sur 3 niveaux.',
                    false
                ),
                new Header(['tags' => $tags]),
                new Header(['thumbnail' => $thumbnail]),
                new Header(
                    ['banner' => "images/posts/headers/$slug.jpg"],
                    'Uniquement si différent de la minitature (thumbnail)',
                    false
                ),
                new Header(
                    ['credit' => ['name' => 'Thomas Jarrand', 'url' => 'https://unsplash.com/@tom32i']],
                    'Pour créditer la photo utilisée en miniature',
                    false
                ),
                new Header(['tweetId' => null], 'Ajouter l\'id du Tweet après publication.', false),
            ]
        );

        $io->success("Article créé : $this->path/$filepath - http://localhost:8000/blog/$category/$slug");

        return 0;
    }

    private function listCategories(): array
    {
        $finder = new Finder();

        $finder->in($this->path)->directories();

        return array_map(
            static fn (\SplFileInfo $dir) => $dir->getBasename(),
            array_values(iterator_to_array($finder))
        );
    }

    private function listAuthors(): array
    {
        $members = array_values(
            $this->contents->getContents(Member::class, ['name' => true], ['active' => true])
        );

        return array_combine(
            array_map(static fn (Member $member) => $member->slug, $members),
            array_map(static fn (Member $member) => $member->pseudo ?? $member->name, $members)
        );
    }

    private function exists(string $category, string $slug): bool
    {
        $finder = new Finder();

        return $finder->in("{$this->path}/$category")->name("$slug*")->hasResults();
    }

    private function getSlug(string $name): string
    {
        $slugger = new AsciiSlugger();

        return strtolower((string) $slugger->slug($name));
    }

    private function createMarkdownFile(string $path, array $headers): void
    {
        $head = implode(PHP_EOL, $headers);

        $this->filesystem->dumpFile($this->path . '/' . $path, <<<EOT
---
$head
---

<!--- Mon contenu ici -->
EOT);
    }
}

class Header
{
    public array $value;
    public ?string $comment;
    public bool $active;

    public function __construct(array $value, ?string $comment = null, bool $active = true)
    {
        $this->value = $value;
        $this->comment = $comment;
        $this->active = $active;
    }

    public function __toString()
    {
        $line = trim(Yaml::dump($this->value, 1, 4, Yaml::DUMP_NULL_AS_TILDE), PHP_EOL);

        if (!$this->active) {
            $line = "#$line";
        }

        if (null !== $this->comment) {
            $line = "$line # {$this->comment}";
        }

        return $line;
    }
}
