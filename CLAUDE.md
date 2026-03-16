# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Elao company website and blog — a Symfony 7.0 static site built with **Stenope** (static site generator). Content is authored in Markdown (with YAML front-matter) under `content/`, rendered through Twig templates, and compiled to static HTML. Frontend uses **Stimulus** controllers with **Webpack Encore** and SCSS.

## Common Commands

All commands are run via `make`:

| Command | Description |
|---------|-------------|
| `make install` | Install all dependencies (Composer + NPM) |
| `make serve` | Start PHP dev server (port 35080) + webpack dev server with HMR (port 35081) |
| `make build.static` | Full production build (assets + static content) |
| `make build.content.without-images` | Fast content build (skips image resizing) |
| `make lint` | Run all linters (php-cs-fixer, phpstan, twig, yaml, eslint, container, composer) |
| `make test` | Run tests (builds content without images) |
| `make article` | Generate a new blog article interactively |

Individual linters: `make lint.php-cs-fixer`, `make lint.phpstan`, `make lint.eslint`, `make lint.twig`, `make lint.yaml`

## Architecture

### Content Pipeline

1. Markdown files in `content/` (blog articles, team members, case studies, jobs, glossary terms, certifications)
2. Stenope loads content into **Model** classes (`src/Model/`) using YAML front-matter
3. **Processors** (`src/Stenope/Processor/`) transform content (author linking, tag processing, TOC generation, image resizing, emoji handling, anchor links)
4. Twig templates render models to HTML
5. `stenope:build` generates the static site

### Content Types & Models

- `content/blog/{dev,elao,infra,methodo}/` → `Article.php`
- `content/member/` → `Member.php`
- `content/case-study/` → `CaseStudy.php`
- `content/job/` → `Job.php`
- `content/certif/` → `Certification.php`
- `content/term/` → `Glossary/Term.php`

### Frontend

- **Stimulus controllers** in `assets/js/controllers/` for interactivity
- **Swup** for smooth page transitions (configured via `swup_plugins_controller.js`)
- SCSS components in `assets/scss/components/`
- Entry points: `assets/js/app.js` (main), snake game easter egg

### Image Processing

Glide handles image resizing with presets defined in `config/packages/glide.yaml`. Images in `content/images/` are resized during build. Twig macros generate srcset for retina support.

## Code Style

- **PHP**: `@Symfony` ruleset via php-cs-fixer, strict types required, PHPStan level max
- **JavaScript**: 4-space indent, single quotes, semicolons required, `console.log`/`console.warn` restricted (use `.info` or `.error`)
- **PHP version**: 8.3 — **Node version**: 16+

## Key Configuration Files

- `config/packages/stenope.yaml` — content sources and processor registration
- `config/packages/glide.yaml` — image resize presets
- `webpack.config.js` — Webpack Encore setup
- `phpstan.neon.dist` — static analysis (level: max)
- `.php-cs-fixer.dist.php` — PHP code style rules
