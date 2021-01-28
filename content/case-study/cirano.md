---
title: "CIRano"
lastmod: 2019-01-09
date: "2019-01-09"
name: "CIRano"

# Params
page_headline: "<small>Etude de cas </small> CIRano"
case_url: https://cirano.io/
---

<section>
    <article>
        <p class="description">
            <div>Durée du projet : 3 mois</div>
            <div>Taille de l’équipe: 2 devs back, 1 intégratrice, 1 designer</div>
            <div>Technologies utilisées: Symfony 4.3, Vue.js, Bootstrap 4</div>
        </p>
    </article>
    <article>
        <h2>Le client</h2>
        <p>Businove est un cabinet conseil spécialiste du Crédit Impôt Recherche (CIR) et du Crédit Impôt Innovation (CII). Son besoin ? Proposer une plateforme en ligne permettant aux entreprises de calculer le montant auquel elles peuvent prétendre dans le cadre de ces deux dispositifs.</p>
    </article>
    <article>
        <h2>Comment ça marche ?</h2>
        <p>Une fois inscrit, l’utilisateur renseigne les informations de son entreprise qui interviennent dans le calcul du crédit d’impôt, puis obtient un résumé complet du montant éligible ainsi qu’une déclaration fiscale pré-remplie au format pdf.</p>
    </article>
    <article>
        <h2>Un outil pensé pour simplifier la saisie</h2>
        <p>Le recueil des informations nécessaires à la déclaration des activités en vue d’obtenir un crédit d’impôt peut s’avérer fastidieux et compliqué. Cirano a été pensé pour rendre cette saisie la plus fluide possible : l’utilisateur est guidé à chaque étape de saisie grâce à une interface intuitive et aux diverses rubriques d’aide contextuelle.</p>
        <p>Par ailleurs, le site propose sur la partie publique du site une FAQ détaillée ainsi qu’un test permettant aux utilisateurs qui envisagent de s’inscrire de tester préalablement leur niveau d’éligibilité au crédit d’impôt CIR ou CII.</p>
    </article>
    <article>
        <h2>Et techniquement, ça donne quoi ?</h2>
        <p>Le projet a été développé avec Symfony 4.3 en suivant la méthodologie DDD et l’architecture hexagonale.</p>
        <p>Le test d’éligibilité de la partie publique du site a été réalisé en Vue.js afin de proposer une expérience fluide et sans rechargement de page.</p>
        <p>
            Ce projet fut également l’occasion d’implémenter Stripe en tant que solution de paiement pour la première fois chez Elao. Stripe s’est avéré un choix satisfaisant à la fois pour les développeurs et pour le client :
            <ul>
                <li>il propose une API de qualité, facile à prendre en main par les développeurs</li>
                <li>il offre au client une interface complète de suivi des opérations financières (historique des paiements, gestion de la facturation, etc.)</li>
            </ul>
        </p>
    </article>
</section>

