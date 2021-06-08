<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Member;
use Stenope\Bundle\ContentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Slugger\AsciiSlugger;
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

        $title = $io->ask('Titre');
        $slug = $io->ask('Slug', $this->getSlug($title));
        $category = $io->choice('Catégorie', $this->listCategories(), 'dev');

        if ($this->exists($category, $slug)) {
            $io->error("L'article \"$category/$slug\" existe déjà.");

            return 1;
        }

        $author = $io->choice('Auteur(e)', $this->listAuthors());
        $date = $io->ask('Date de publication', (new \DateTimeImmutable())->format('Y-m-d'));
        $lang = $io->ask('Langue', 'fr');
        $description = $io->ask('Description');
        $tags = array_map('trim', explode(',', $io->ask('Tags')));
        $thumbnail = $io->ask('Miniature', "images/posts/thumbnails/$slug.jpg");
        $banner = $io->ask('Bannière', "images/posts/headers/$slug.jpg");
        $draft = $io->confirm('Brouillon');

        $io->definitionList(
            ['Titre' => $title],
            ['Description' => $description],
            ['Categorie' => $category],
            ['Auteur(e)' => $author],
            ['Date' => $date],
            ['Tag(s)' => implode(', ', $tags)],
            ['Langue' => $lang],
            ['Slug' => $slug],
            ['Miniature' => $thumbnail],
            ['Bannière' => $banner],
            ['Brouillon' => $draft ? 'Oui' : 'Non'],
        );

        if (!$io->confirm('Confirmer la création ?')) {
            return 0;
        }

        $io->note('Création du fichier ...');

        $this->createMarkdownFile(
            $filepath = "$category/$slug.md",
            [
                'title' => $title,
                'author' => $author,
                'date' => $date,
                'lang' => $lang,
                'description' => $description,
                'tags' => $tags,
                'thumbnail' => $thumbnail,
                'banner' => $banner,
                'draft' => $draft,
            ]
        );

        $io->success("Article créé : $filepath");

        return 0;
    }

    private function listCategories(): array
    {
        $finder = new Finder();

        $finder->in($this->path)->directories();

        return array_map(
            fn (\SplFileInfo $dir) => $dir->getBasename(),
            array_values(iterator_to_array($finder))
        );
    }

    private function listAuthors(): array
    {
        $members = array_values(
            $this->contents->getContents(Member::class, ['name' => true], ['active' => true])
        );

        $choices = array_combine(
            array_map(fn (Member $member) => $member->slug, $members),
            array_map(fn (Member $member) => $member->pseudo ?? $member->name, $members)
        );

        if ($choices === false) {
            throw new \LogicException('Should not happen.');
        }

        return $choices;
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

    private function createMarkdownFile(string $path, array $header): void
    {
        $this->filesystem->dumpFile(
            $this->path . '/' . $path,
            implode(PHP_EOL, ['---', Yaml::dump($header, 1), '---'])
        );
    }
}
