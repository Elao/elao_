---
title: "Exemple"
lastmod: 2021-01-21
date: "2021-01-21"
name: "Exemple"

# Params
metaDescription: "Nunc auctor est dolor, eget placerat lorem semper sit amet. Integer aliquet mi orci, et eleifend urna fermetum. Nullam pelletesque frigilla vulputate."
description: "Nunc auctor est dolor, eget placerat lorem semper sit amet. Integer aliquet mi orci, et eleifend urna fermetum. Nullam pelletesque frigilla vulputate."
caseUrl: https://www.exemple.com/
clients: Example Corp.
users: Shrimps
size: 3 months
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "algolia", "vue-js"]
members: ["mcolin", "tjarrand"]
images: ["http://placekitten.com/630/380", "http://placekitten.com/240/160", "http://placekitten.com/240/160"]
---

## Les titres

1 page = 1 titre principal `h1`.

Dans le blog comme dans les études de cas, le `h1` est le titre de l'article. Dans le corps de l'article, on commence donc par des `h2`.

##h2 laceat quas odio atque molestiae
###h3 laceat quas odio atque molestiae
####h4 laceat quas odio atque molestiae
#####h5 laceat quas odio atque molestiae
######h6 laceat quas odio atque molestiae

## Les éléments typographiques

Nous avons des paragraphes, [des liens](https://www.elao.com/fr/), parfois du `code inline`.

- des listes de choses
- des listes de choses
- des listes de choses

> Nous avons aussi des citations.
> <cite>- Jane Doe</cite>

Un coup sur deux, on a un style différent de citation, sinon on s'ennuie.

> Quoi ? Un deuxième style de citation ? Eos officia, vel corporis eaque architecto eveniet voluptatibus, ullam impedit excepturi quis quidem sint facere laboriosam harum error esse iusto. Asperiores, placeat.
> <cite>John Doe</cite>

## Les images
Une image (une image qui a du sens, ça n'inclut pas les gifs rigolos) a toujours une légende, et si possible on crédite son auteur·ice.

<figure>
    <img src="https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80" alt="vivamus bibendum">
    <figcaption>
      <span class="figure__legend">David sous l'eau 🐬</span>
      <span class="figure__credits">Crédit photo : <a href="https://unsplash.com/@jbsinger1970">Jonathan Singer</a></span>
    </figcaption>
</figure>

## Les galeries d'images
Pour présenter plusieurs screenshots d'un projet, on préfère la galerie d'images. Elle se présente en grille de 2 images par ligne.

Chaque image est cliquable et peut-être agrandie dans une modal.

<!--
    Todo gallery

    Chaque .gallery__item contient
        - un <button> : la vignette cliquable (image croppée)
        - un <div class="modal"> : la modal qui contient le <img> (image complète)

    - Dans un .gallery__item
        - au clic sur le <button>, la <div class="modal"> prend la classe modal--show (ça la place en position fixed sur tout l'écran)
        - lorsque la modal est ouverte, le body doit prendre la classe .no-scroll pour empêcher le scroll en arrière-plan
        - la modal peut se fermer au clic sur le bouton .modal__button
        - possible de fermer la modal si on clique n'importe dans <div class="modal"> où sauf sur le <div class="content"> ?
-->
<ul class="gallery">
    <li class="gallery__item">
        <button>
            <span class="image">
                <span style="background: url(https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80)"></span>
                <i class="icon icon--enlarge" aria-hidden="true"></i>
            </span>
            <span class="legend">
                <span class="screen-reader">Ouvrir l'image</span>
                Légende de l'image
            </span>
        </button>
        <div class="modal"> <!-- modal--show -->
            <button class="modal__button">
                <i class="icon icon--close" aria-hidden="true"></i>
                <span class="screen-reader">Fermer l'image</span>
            </button>
            <div class="modal__content">
                <div class="content">
                    <img src="https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80" alt="Légende de l'image">
                </div>
            </div>
        </div>
    </li>
    <li class="gallery__item">
        <button>
            <span class="image">
                <span style="background: url(https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80)"></span>
                <i class="icon icon--enlarge" aria-hidden="true"></i>
            </span>
            <span class="legend">
                <span class="screen-reader">Ouvrir l'image</span>
                Légende de l'image
            </span>
        </button>
        <div class="modal"> <!-- modal--show -->
            <button class="modal__button">
                <i class="icon icon--close" aria-hidden="true"></i>
                <span class="screen-reader">Fermer l'image</span>
            </button>
            <div class="modal__content">
                <div class="content">
                    <img src="https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80" alt="Légende de l'image">
                </div>
            </div>
        </div>
    </li>
    <li class="gallery__item">
        <button>
            <span class="image">
                <span style="background: url(https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80)"></span>
                <i class="icon icon--enlarge" aria-hidden="true"></i>
            </span>
            <span class="legend">
                <span class="screen-reader">Ouvrir l'image</span>
                Légende de l'image
            </span>
        </button>
        <div class="modal"> <!-- modal--show -->
            <button class="modal__button">
                <i class="icon icon--close" aria-hidden="true"></i>
                <span class="screen-reader">Fermer l'image</span>
            </button>
            <div class="modal__content">
                <div class="content">
                    <img src="https://images.unsplash.com/photo-1530023868717-cdb5554aea96?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=788&q=80" alt="Légende de l'image">
                </div>
            </div>
        </div>
    </li>
</ul>

Lorem, ipsum, dolor sit amet consectetur adipisicing elit. Nihil distinctio repellendus ullam necessitatibus quaerat pariatur nam perspiciatis explicabo? Delectus nam quo vel consequuntur perferendis nulla, quis eos aliquid rerum suscipit.
