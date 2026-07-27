---
story: "Socle de navigation clavier : focus visible, lien d'évitement, reprise de focus Swup"
story_code: "a11y-socle-clavier"
created: 2026-07-27
status: "In Progress"
---

# Journal de développement

## Progression

| Tâche | Statut | Date |
|-------|--------|------|
| T0 — Préparer l'environnement (`make install`, commit du doc d'audit sur la branche parente, création de la sous-branche) | En attente | |
| T1 — Indicateur de prise de focus visible (`base/_focus.scss`, import, suppression des 10 resets) | En attente | |
| T2 — Corriger le `<main>` imbriqué des articles (`templates/blog/article.html.twig`) | En attente | |
| T3 — Lien d'évitement vers le contenu principal (`components/_skip-link.scss`, import, `base.html.twig`) | En attente | |
| T4 — Rétablir le focus après les transitions Swup (`@swup/a11y-plugin`, `swup_plugins_controller.js`) | En attente | |
| T5 — Neutraliser le défilement animé sous `prefers-reduced-motion` (`animateScroll` conditionnel) | En attente | |
| Q1 — Vérifications automatiques (`make lint.eslint`, `make lint.twig`, `make test`) | En attente | |
| Q2 — Validation manuelle (4 protocoles A/B/C/D, 7 pages, Chrome + Firefox + Safari, VoiceOver) | En attente | |

## Journal

<!-- Les entrées seront ajoutées ici au fur et à mesure du développement -->
