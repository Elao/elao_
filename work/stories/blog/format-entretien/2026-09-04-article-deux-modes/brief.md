---
id: STOR-001
epic: blog
feature: format-entretien
slug: article-deux-modes
title: Un article de blog porte deux modes de lecture, récit et entretien, avec bascule
created: 2026-09-04
status: Done
---

# Un article de blog porte deux modes de lecture, récit et entretien, avec bascule

## Contexte

La série d'interviews du blog se heurte à un choix que la relecture a rendu intenable : le récit se lit vite et s'organise par thématique, mais efface la voix directe de l'invité·e comme celles des intervieweurs ; l'entretien intégral restitue l'échange fidèlement et remet l'invité·e au premier plan, au prix d'une longueur qui décourage. Plutôt que de sacrifier l'un des deux, l'article en porte deux et le lecteur choisit.

Le blog ne sait rendre aujourd'hui ni les prises de parole nommées fondues dans le fil du texte, ni les remises en contexte de la rédaction, ni la bascule elle-même. Cette story livre le format complet, visuellement intégré, sur un article de démonstration. Elle est indépendante du calendrier de publication de l'entretien qui l'a motivée.

## Périmètre

- Un article se déclare au format entretien et active ainsi les comportements ci-dessous ; les autres articles sont inchangés.
- Un article de ce format se rédige dans un seul texte portant trois zones : une introduction commune, puis les deux modes.
- Le gabarit compose le bloc d'introduction qui annonce les deux modes ; la rédaction peut en redéfinir le texte article par article.
- Les libellés des deux modes ont une valeur par défaut redéfinissable article par article.
- Le mode récit est affiché à l'arrivée ; l'entretien est atteignable par les contrôles ou par un lien.
- Les contrôles de bascule sont posés dans le bloc d'introduction et rappelés à la fin de chaque mode.
- Le sommaire reste à son emplacement actuel et est remplacé par celui du mode activé.
- Une durée de lecture peut être renseignée pour chaque mode et s'affiche alors sur son contrôle.
- Dans le mode entretien, une prise de parole d'intervieweur porte le nom de la personne et se lit dans l'enchaînement du corps.
- Chaque intervieweur reçoit une couleur distincte, prise dans la charte et attribuée sans saisie.
- Dans le mode entretien, une remise en contexte porte la voix de la rédaction, n'est attribuée à personne, et reste facultative section par section.
- L'adresse de la page porte le mode affiché, sans créer une seconde page.
- Un article de démonstration, exclu de la production, donne à voir tous les cas de rendu du format.

## Hors périmètre

- Mémoriser le mode choisi d'une visite à l'autre ou d'un article à l'autre : le choix appartient à l'article, pas au lecteur.
- Retrouver sa position de lecture en changeant de mode : les deux modes n'ont ni les mêmes sections ni la même couverture, la correspondance n'existe pas.
- ~~Documenter durablement la syntaxe du format ailleurs que dans l'article de démonstration, qui est jetable.~~
  **Décision révisée le 2026-09-04** : l'article de démonstration est conservé aux côtés du guide de style et
  devient la documentation de référence du format. L'article réel viendra en plus, non à sa place.
- Relier un intervieweur à un membre de l'équipe pour en afficher le portrait ou la page : les noms restent de simples noms, ce qui couvre aussi un intervenant extérieur.
- Utiliser les blocs propres à l'entretien dans le mode récit.
- Une numérotation et une liste de notes propres à chaque mode.
- Un gabarit portant plus de deux modes.
- Atténuer le coût du retour arrière : parce que chaque bascule ajoute une entrée à l'historique, sortir de l'article après plusieurs allers-retours demande autant de retours que de bascules. Assumé.

## Critères d'acceptance

1. Un article déclaré au format entretien affiche, avant tout autre contenu, un bloc annonçant les deux modes.
2. À l'arrivée sans indication de mode, le récit est affiché et l'entretien ne l'est pas.
3. Les contrôles de bascule sont présents dans le bloc d'introduction et rappelés à la fin de chaque mode.
4. Une bascule remplace le sommaire affiché par celui du mode activé.
5. Une bascule met à jour l'adresse de la page sans quitter la page.
6. Une bascule ajoute une entrée à l'historique : le bouton retour du navigateur ramène au mode précédemment affiché.
7. Une adresse désignant un mode ouvre l'article sur ce mode.
8. Une adresse désignant une section ouvre le mode qui contient cette section et amène le lecteur jusqu'à elle.
9. Dans le mode entretien, une prise de parole d'intervieweur affiche le nom de la personne et se lit sans rompre le fil du corps de texte.
10. Deux intervieweurs distincts d'un même article reçoivent deux couleurs distinctes, sans que la rédaction ait rien saisi.
11. Une remise en contexte se distingue visuellement d'une prise de parole et n'affiche aucun nom.
12. Une section d'entretien peut enchaîner plusieurs échanges question / réponse successifs.
13. Une section d'entretien peut n'avoir aucune remise en contexte, et une section peut ne contenir aucune question.
14. Une durée de lecture renseignée pour un mode s'affiche sur son contrôle ; absente, le contrôle n'affiche que le libellé.
15. Si les scripts de la page ne s'exécutent pas, les deux modes s'affichent l'un après l'autre, chacun précédé de son titre, et les deux sommaires restent lisibles.
16. Les blocs propres à l'entretien ne produisent leur rendu qu'à l'intérieur du mode entretien.
17. Les notes de bas d'article restent une liste unique, commune aux deux modes.
18. Un article qui ne déclare pas ce format s'affiche exactement comme avant.

## Flux fonctionnels

### Flux nominal

1. Le lecteur ouvre l'article depuis le blog ou un partage.
2. Il lit le titre, le chapô et le bloc d'introduction, qui lui annonce deux lectures possibles et, si elles sont renseignées, leurs durées.
3. Le récit s'affiche, précédé de son sommaire.
4. Arrivé au bout, il retrouve les contrôles et bascule sur l'entretien.
5. Le sommaire et le corps sont remplacés, l'adresse de la page reflète le mode entretien.

### Flux alternatif : arrivée par un lien vers une section de l'entretien

1. Le lecteur ouvre une adresse désignant une section précise de l'entretien.
2. L'article s'ouvre directement sur le mode entretien, et non sur le récit.
3. Le lecteur est amené à la section visée.

### Flux alternatif : retour arrière après plusieurs bascules

1. Le lecteur bascule du récit vers l'entretien, puis revient au récit.
2. Il actionne le bouton retour du navigateur.
3. Le mode entretien est réaffiché.

### Flux alternatif : les scripts de la page ne s'exécutent pas

1. Le lecteur ouvre l'article.
2. Le bloc d'introduction est affiché, les contrôles n'ont aucun effet.
3. Le récit s'affiche en entier, précédé de son titre et de son sommaire, puis l'entretien s'affiche à sa suite, précédé des siens.

## Données externes requises

- Un article de démonstration exclu de la production, dont le contenu est dérivé des ébauches en cours de l'entretien de Martin Dufresne — assez proche pour juger du rendu réel, allégé côté entretien, et couvrant les cas suivants : une section avec plusieurs échanges successifs, une section sans remise en contexte, une section sans question, et au moins deux intervieweurs.

## Exigences

- **Accessibilité** : les contrôles de bascule s'utilisent au clavier ; le mode actif est annoncé aux technologies d'assistance ; le mode masqué est réellement retiré de la restitution et non seulement invisible à l'écran.
- **Intégration visuelle** : les nouveaux blocs sont construits avec les couleurs, les polices et les espacements déjà en place sur le blog. Les prises de parole et les remises en contexte sont discrètement mises en forme et lisibles dans l'enchaînement du corps — à l'opposé des blocs de citation en exergue des articles classiques.
- **Navigation** : la bascule reste opérante après une transition de page du site, sans rechargement complet.

## Stratégie de test

- `[unit]` Un texte à trois zones produit une introduction et deux modes distincts. → 1
- `[unit]` Une prise de parole nommée est reconnue et porte le nom de l'intervenant. → 9
- `[unit]` Une remise en contexte est reconnue et ne porte aucun nom. → 11
- `[unit]` Deux intervieweurs distincts reçoivent deux couleurs distinctes ; un même intervieweur garde la sienne d'un bout à l'autre de l'article. → 10
- `[unit]` Une section enchaînant plusieurs échanges question / réponse les rend dans l'ordre. → 12
- `[unit]` Une section sans remise en contexte, et une section sans question, sont rendues sans erreur. → 13
- `[unit]` Deux sections homonymes, une dans chaque mode, restent adressables séparément. → 8
- `[unit]` Les blocs d'entretien rencontrés hors du mode entretien ne produisent pas leur rendu. → 16
- `[unit]` Un article sans le format déclaré produit le même rendu qu'avant. → 18
- `[func]` Le bloc d'introduction s'affiche avant tout autre contenu de l'article et porte les contrôles. → 1, 3
- `[func]` Les contrôles sont rappelés à la fin de chaque mode. → 3
- `[func]` À l'arrivée sans indication, le récit est affiché et l'entretien ne l'est pas. → 2
- `[func]` La bascule remplace corps et sommaire, et met à jour l'adresse de la page. → 4, 5
- `[func]` Le bouton retour du navigateur ramène au mode précédemment affiché. → 6
- `[func]` Une adresse désignant un mode ouvre ce mode ; une adresse désignant une section ouvre le mode qui la contient et amène à la section. → 7, 8
- `[func]` Remise en contexte et prise de parole se distinguent l'une de l'autre à la lecture du corps. → 11
- `[func]` Un mode dont la durée est renseignée l'affiche sur son contrôle ; un mode sans durée n'affiche que son libellé. → 14
- `[func]` Les notes de bas d'article s'affichent en une liste unique, identique quel que soit le mode affiché. → 17
- `[func]` Les contrôles s'atteignent et s'actionnent au clavier seul, et le mode actif est annoncé. → 3
- `[func]` Scripts désactivés, les deux modes et les deux sommaires sont lisibles, chacun sous son titre. → 15
- `[func]` Après une transition de page vers un article de ce format, la bascule fonctionne.

## Questions ouvertes

- [ ] Revue du rendu par Eva et la designer, à mener sur pièces une fois le rendu produit (non bloquant pour démarrer, bloquant avant d'ouvrir le format à d'autres articles).
- [ ] Le second mode est présent dans la page livrée pour rester lisible sans scripts, et masqué à l'initialisation : un affichage transitoire des deux modes pendant une transition de page est-il acceptable ? (à arbitrer en /stories:draft)
- [ ] Le contenu de démonstration dérive d'ébauches non définitives ; il sera remplacé par le véritable article (temporaire).
