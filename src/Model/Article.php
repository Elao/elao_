<?php

declare(strict_types=1);

namespace App\Model;

class Article
{
    public string $type; // "post"
    public string $title; // "Pourquoi devriez-vous utiliser Vue.js dans vos projets ?"
    public string $slug; // "pourquoi-devriez-vous-utiliser-vue-js-dans-vos-projets"
    public string $content;
    public ?\DateTimeImmutable $date = null; // "2016-10-17"
    public ?\DateTimeImmutable $publishdate = null; // "2016-10-19"
    public \DateTimeImmutable $lastModified; // automatic
    public bool $draft = true; // false
    public ?string $description; // "Retour d'expérience sur le framework frontend Vue.js et pourquoi l'utiliser"
    public string $thumbnail; // "/images/posts/thumbnails/vuejs.jpg"
    public string $header; // "/images/posts/headers/vuejs.jpg"
    public array $tags = []; // ["Vuejs","Javascript","Front","Frontend","Framework"]
    public array $categories; // ["Dev", "Vuejs", "Javascript"]
    public Member $author; // "mcolin"
    /** @var int|bool [description] */
    public $summary = null;
    public string $category;
}
