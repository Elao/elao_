---
title: "Chalkboard Education"
description: "Solution de mobile learning : cours accessible sur mobile sur tous types de téléphones mobiles; même sans connexion Internet."
lastmod: 2017-09-04
date: "2017-09-04"
name: "Chalkboard Education"

# Params
page_headline: "<small>Etude de cas </small> Chalkboard Education"
---
<section class="preview">
    <div class="col-lg-9 frame">
        <img data-slideshow="illustration" src="/images/etudes-de-cas/chalkboardeducation_mockup1.png" alt="Chalkboard Education">
    </div>
    <div class="col-lg-3 frame--side pull-right">
        <img data-slideshow-thumb="illustration" src="/images/etudes-de-cas/chalkboardeducation_mockup1.png" alt="" class="active">
        <img data-slideshow-thumb="illustration" src="/images/etudes-de-cas/chalkboardeducation_mockup2.png" alt="">
    </div>
</section>
<div class="clearfix"></div>
<section>
    <article>
        <h2>Contexte projet</h2>
        <p>
            Dans certains pays africains, le nombre de places disponibles à l'université est très limité.
            De nombreux étudiants n'ont par conséquent pas accès aux cours.
            La startup <a href="https://chalkboard.education/">Chalkboard Education</a> a pour but de résoudre ce problème en diffusant les cours via les téléphones mobiles.
            Les étudiants africains n'ont certes pas forcément le dernier modèle de smartphone ni une connexion Internet fiable mais cela est suffisant pour rendre possible l'accès à la connaissance.
        </p>
    </article>
    <article>
        <h2>Expertise développement</h2>
        <a class="tag tag--small">Symfony</a>
        <a class="tag tag--small">GraphQL</a>
        <a class="tag tag--small">React</a>
        <a class="tag tag--small">React Redux</a>
        <a class="tag tag--small">Progressive Web App</a>
        <a class="tag tag--small">Service Worker</a>
        <a class="tag tag--small">Offline-first</a>
        <a class="tag tag--small">Mobile-first</a>
        <a class="tag tag--small">Material Design</a>
        <p>
            Elao accompagne Chalkboard Education depuis 2015 sur la conception de son produit.
            Un premier Proof Of Concept a été réalisé en ReactNative et une application pour Android déployée sur Google Play Store pour des étudiants de l'University Of Ghana.
            Avec l'émergence des Progressive Web Apps, nous avons conseillé Chalkboard Education de miser sur le web pour plusieurs raisons :
            <ul>
                <li>le public visé est majoritairement sur Android, OS sur lequel actuellement les techniques des PWA sont les plus avancées,</li>
                <li>le coût du développement est moins important que le développement d'applications natives pour Android et iOS,</li>
                <li>la fréquence de mise à jour est plus simple et ne dépend pas de la bonne volonté des stores d'applications,</li>
                <li>le poids d'une web app est beaucoup moins important qu'une application native ce qui est un avantage pour des populations ayant un accès limité à Internet,</li>
                <li>la couverture des appareils ciblés est beaucoup plus large du fait qu'il s'agisse d'une application web.</li>
            </ul>
        </p>
        <p>
            Le projet Chalkboard Education s'articule autour de deux plateformes :
            <ul>
                <li>un back-office permettant de gérer les étudiants et les cours et consulter la progression des étudiants,</li>
                <li>une application web pour l'étudiant afin qu'il puisse accéder à ses cours, répondre à des QCM et valider sa progression.</li>
            </ul>
        </p>
        <p>
            L'application étudiant est une Progressive Web App :
            <ul>
                <li>elle est propulsée par React et React Redux,</li>
                <li>elle peut être installée sur l'écran d'accueil du téléphone,</li>
                <li>les contenus sont téléchargés à la connexion puis conservés en cache navigateur ; la consultation des cours est donc disponible en déconnecté (offline).</li>
            </ul>
        </p>
        <p>
            Les deux plateformes sont connectées avec :
            <ul>
                <li>une API en GraphQL, solution pertinente par rapport à REST pour laisser l'application consommatrice de l'API choisir les contenus qu'elle souhaite en une seule requête HTTP</li>
                <li>l'envoi / réception de SMS pour que l'étudiant puisse valider sa progression sans Internet.</li>
            </ul>
        <p>
    </article>
    <article>
        <h2>Méthodologie & process qualité</h2>
        <a class="tag tag--small">Code review</a>
        <a class="tag tag--small">Tests unitaires</a>
        <a class="tag tag--small">Tests fonctionnels</a>
        <a class="tag tag--small">Méthode agile</a>
        <p>Le niveau de qualité appliqué à ce projet correspond aux standards d'Élao. Il implique notamment :</p>
        <ul class="red-square">
            <li><span>des ateliers de co-conception avec le CEO</span></li>
            <li><span>des revues de code</span></li>
            <li><span>des tests unitaires et fonctionnels</span></li>
            <li><span>des recettes à fréquence régulière</span></li>
            <li><span>des rétrospectives agiles dans le but d'une amélioration continue.</span></li>
        </ul>
    </article>
</section>
