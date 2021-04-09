---
type:           "post"
title:          "E-learning sans internet ou presque"
date:           "2017-11-22"
lastModified:       ~

description:    "Retour d'expérience sur la conception de la progressive web app de Chalkboard Education"
tableOfContent: 3

thumbnail:      "images/posts/2017/chalkboard-education/woman-phone.jpg"
banner:     "images/posts/2017/chalkboard-education/woman-phone.jpg"
tags:           ["Progressive Web App", "Offline", "React", "Symfony"]
categories:     ["dev", "Symfony", "javascript"]

author: "rhanna"

---

## Le contexte

Dans certains pays africains, le nombre de places disponibles à l'université est très limité.
Par conséquent de nombreux étudiants n'ont pas accès à l'université.
La startup [Chalkboard Education](https://chalkboard.education/) implantée au Ghana et en Côte d'Ivoire a pour but de
résoudre ce problème en diffusant les cours d'universités via les téléphones mobiles.
Les étudiant•e•s africains n'ont certes pas forcément le dernier modèle de smartphone ni une connexion Internet fiable
mais cela est suffisant pour accéder à la connaissance.

Reportage de France 24 à propos de Chalkboard Education :

<iframe style="width:100%; height:400px; margin: 0 !important" src="https://www.youtube.com/embed/DWWXSP5YFaw?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>

## Application native

Elao accompagne Chalkboard Education depuis 2015 sur la conception de son produit.
Une première preuve de concept a été réalisée en [React Native](https://facebook.github.io/react-native/) avec pour
résultat une application Android déployée sur Google Play Store à destination de plusieurs centaines d'étudiant•e•s de
l'University Of Ghana.

<p class="text-center">
    <img src="images/posts/2017/chalkboard-education/app-react-native.png" alt="React native app" />
</p>

## Progressive Web App

Avec l'émergence des [Progressive Web Apps](/blog/dev/la-revanche-du-web-par-les-progressive-web-apps/), nous avons
conseillé Chalkboard Education de revenir au web pour plusieurs raisons :

- Le public visé est majoritairement sur Android, OS pour lequel actuellement les navigateurs supportent le mieux le
Service Worker et le Web App Manifest, éléments clés du concept de Progressive Web App.
- Avec une application web, la couverture des appareils ciblés est beaucoup plus large.
- Le coût du développement est moins important que le développement d'applications natives pour Android et iOS.
- Le poids d'une web app est beaucoup moins important qu'une application native ce qui est un avantage pour des
populations ayant un accès limité à Internet.
- La fréquence de mise à jour est plus simple et ne dépend pas de la bonne volonté des stores d'applications.

En quoi Chalkboard Education est une Progressive Web App ?

- Le *front* déclare un [manifeste d'une application web](https://developer.mozilla.org/fr/docs/Web/Manifest) permettant
d'installer Chalkboard Education sur l'écran d'accueil du smartphone ou tablette.
- Utilisation hors-ligne de l'application : tous les contenus sont pré-chargés et mis en cache permettant la consulation
des cours par les étudiant•e•s sans Internet.

### React et Redux ♥️

Il était évident pour nous que l'application front devrait être autonome et mise en cache navigateur.
Nous avons fait le choix de
[React](https://github.com/facebookincubator/create-react-app), avec du routing géré par
[react-router](https://github.com/ReactTraining/react-router)
et des états gérés par [Redux](https://github.com/reactjs/redux).

### Démo

L'étudiant•e reçoit son accès par SMS contenant un lien permettant de l'identifier. A l'ouverture de l'application, le
contenu des cours est préchargé. La progression du chargement est affichée en haut de l'interface :

<div style="text-align:center;">
<video src="images/posts/2017/chalkboard-education/demo-chalkboard.mp4" loop autoplay style="height:500px; border:1px solid #333"></video>
</div>

### UI/UX inspirées des applications natives

Pour l'UI de l'application, nous avons utilisé [Material UI](http://www.material-ui.com/), un ensemble de composants
React qui implémente les bonnes pratiques *Material Design* édictées par Google.

<p class="text-center">
    <img src="images/posts/2017/chalkboard-education/submit-progression.png" alt="Submit progression" style="height:500px; border:1px solid #333"/>
</p>

Nous avons également travaillé l'UX pour mobile afin de s'approcher de l'UX des applications natives. Pour cela, nous
nous sommes inspirés d'applications existantes et nous nous sommes fixé quelques règles :

- Barre de navigation fixée en haut.
- Éviter les formulaires : un formulaire avec deux champs radio a été remplacé par deux boutons par exemple.
- Centrer à l'écran les actions à faire par l'utilisateur.
- Ecran simple avec une seule action à faire:
    - choisir un cours dans une liste,
    - lire un cours et passer à la suite
    - valider sa progression en choisissant parmi SMS ou Internet...

Le développement était testé sur un ancien smartphone Android avec une ancienne version de Chrome afin de se mettre
quasi en conditions réelles.

### Mobile-first et Offline-first

L'application web de Chalkboard Education a été conçue à la fois pour un usage en *Mobile-first* et un usage en
*Offline-first*.

L'étudiant•e avec son téléphone connecté à un *hotspot wifi* télécharge les contenus, cours et images à la première
connexion.

Les contenus sont stockés de différentes manières dans le navigateur de l'étudiant•e:

- le contenu du cours est dans le store Redux et persisté en
[localStorage](https://developer.mozilla.org/fr/docs/Web/API/Storage/LocalStorage),
- les médias (images) du cours sont stockés en
[CacheStorage](https://developer.mozilla.org/fr/docs/Web/API/CacheStorage)
grâce au
[Service Worker](https://developer.mozilla.org/fr/docs/Web/API/ServiceWorker) déclaré par l'application.

L'étudiant•e peut ainsi consulter les cours hors connexions.

Une vérification de mise à jour est faite toutes les 24h si l'utilisateur a une connexion internet.
Il est indiqué le nombre de Ko à télécharger pour chaque mise à jour.

[Redux Persist](https://github.com/rt2zz/redux-persist) permet de persister le *store Redux* en
[LocalStorage](https://developer.mozilla.org/fr/docs/Web/API/Storage/LocalStorage) et de réhydrater le *store* dès lors
que la page web est rechargée :

```js
// store.js
import ApolloClient, { createNetworkInterface } from 'apollo-client';
import { applyMiddleware, compose, createStore } from 'redux';
import { autoRehydrate } from 'redux-persist';

import appReducer from '../reducers';
import defaultState from './defaultState';

const store = createStore(
  appReducer,
  defaultState,
  compose(
    applyMiddleware(whateverMiddleware),
    autoRehydrate() // redux persist auto rehydrate the store
  )
);

const networkInterface = createNetworkInterface({
  uri: 'https://api.chalkboard.education'
});
```

*\#protips* : pour gérer l'accès à des contenus avec du routing et à la fois que ces contenus soient disponibles
hors-ligne sans que l'url ait été visitée au préalable, il est quasi indispensable d'utiliser des urls avec un
[hash](https://developer.mozilla.org/fr/docs/Web/API/window/location#Propri.C3.A9t.C3.A9s)
(/#/whatever) et le [HashRouter](https://reacttraining.com/react-router/web/api/HashRouter) de React Router fait le boulot.

### API GraphQL ♥️

Nous avons choisi d'implémenter une API [GraphQL](http://graphql.org/) au lieu de REST :

- pour minimiser le nombre de requêtes HTTP,
- laisser le *front* choisir les contenus qu'il souhaite utiliser,
- parce que GraphQL c'est quand même bien cool.

Par exemple dans la requête suivante le *front* demande à la fois :

- un *currentDate* (une date serveur),
- la liste des *courses* (ses *folders*, les *sessions* des *folders*, les *files* des *sessions*),
- et le *user* courant.

```js
// src/graphql/query/CoursesQuery.js
import gql from 'graphql-tag';

export default gql`
  {
    currentDate
    user {
      uuid
      firstName
      lastName
      country
      phoneNumber
      locale
    }
    courses {
      uuid
      title
      updatedAt
      folders {
        uuid
        title
        updatedAt
        sessions {
          uuid
          title
          updatedAt
          files {
            url
            updatedAt
          }
        }
      }
    }
  }
`;
```

Nous avons utilisé le très bon client GraphQL [Apollo client](https://github.com/apollographql/apollo-client).
Il existe aussi une implémentation [Apollo pour React](https://github.com/apollographql/react-apollo) mais nous
ne l'avons pas utilisée étant donné que notre application n'est pas *API-centric* : toutes les *data* sont récupérées
à la première connexion puis à la mise à jour.

Et pour l'identification de l'étudiant•e, on passe le *token user* dans l'entête HTTP
[Authorization](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Authorization) de cette façon :

```js
// store.js
import ApolloClient, { createNetworkInterface } from 'apollo-client';

// ...

const GraphqlClient = new ApolloClient({ networkInterface });

networkInterface.use([
  {
    applyMiddleware(req, next) {
      // Create the header object if needed
      if (!req.options.headers) {
        req.options.headers = {};
      }

      const userToken = store.getState().currentUser.token;

      req.options.headers.authorization = userToken
        ? `Bearer ${userToken}`
        : null;

      next();
    }
  }
]);
```

### Service worker

[Create React App](https://github.com/facebookincubator/create-react-app) permet facilement de *bootstraper* un front
avec React et un service worker qui pré charge en cache les assets de l'application (index.html, javascript et css).

Pour mettre en cache les urls chargées au *runtime* il faut surcharger la configuration du *builder* du Service Worker
avec [sw-precache](https://github.com/GoogleChromeLabs/sw-precache). On a ajouté une commande "generate-sw" dans notre
fichier package.json :

```json
{
  "name": "ChalkboardEducationV2ReactFront",
  "private": true,
  "dependencies": {
    ...
  },
  "devDependencies": {
    ...,
    "sw-precache": "^5.2.0"
  },
  "scripts": {
    "start": "react-scripts start",
    "build": "react-scripts build && yarn run generate-sw",
    "generate-sw": "sw-precache --root='build/' --config config/sw-precache-config.js",
    "test": "react-scripts test --env=jsdom"
  }
}
```

Configuration pour *sw-precache* pour gérer le *runtime caching* des images :

```js
// config/sw-precache-config.js
module.exports = {
  staticFileGlobs: [
    'build/**/*.js',
    'build/**/*.css',
    'build/index.html'
  ],
  runtimeCaching: [{
    urlPattern: /\.*\.(?:svg|jpg|gif|png)/g,
    handler: 'cacheFirst'
  }]
};
```

### Le SMS pour transporter de la donnée à la place d'Internet

Sur Chalkboard Education, l'étudiant•e doit valider sa progression. Pour cela il lui est laissé le choix d'utiliser
internet ou... le SMS.

<p class="text-center">
    <img src="images/posts/2017/chalkboard-education/submit-validation.png" alt="Submit validation by SMS" style="height:500px; border:1px solid #333" />
</p>

Un code est généré par l'application web. Ce code est envoyé par SMS par l'étudiant•e à un numéro donné.

A noter que pour déclencher la rédaction d'un SMS sous Android, il est possible d'utiliser un lien HTML avec
le *scheme* `sms:`

 `<a href="sms:+63344556677?body=Message">Send SMS</a>`

Le back-office reçoit le code, identifie l'étudiant•e et la session concernée. Un SMS contenant un lien + code lui est
envoyé en retour. L'étudiant•e clique sur le lien reçu, même hors-ligne, l'application web décode le code reçu et valide
la session. L'étudiant•e peut ainsi valider la session courante et passer à la session suivante.

### Poids de la PWA

Lorsqu'on a pour projet de réaliser une progressice web app, il est important de surveiller le poids des fichiers css et
javascript *buildés*. Sur ce projet, nous avons essayé d'avoir le minimum de dépendances.

`$ yarn build`

```bash
  276.43 KB (+142 B)  build/static/js/main.8502d0fb.js
  587 B (+11 B)       build/static/css/main.7edcdc8b.css
```

L'application *front* pèse ainsi moins de 300 Ko !

### Audit

Le panel Audit de Chrome indique qu'on est plutôt pas mal :

<p class="text-center">
    <img src="images/posts/2017/chalkboard-education/audit.png" alt="Audit" />
</p>

## Back office et API avec Symfony ♥️

Chalkboard Education a besoin d'un back-office d'administration permettant de :

- gérer les étudiant•e•s (créer, importer),
- saisir les contenus des cours,
- assigner des cours aux étudiant•e•s,
- envoyer un SMS contenant l'url d'accès à un.e étudiant•e,
- voir la progression des étudiant•e•s pour chaque cours.

Le back-office permet aussi de traiter automatiquement des SMS reçu contenant des codes de validation de session et
d'envoyer à l'étudiant•e un SMS en retour contenant un lien d'activation.

Etant donné que l'on connait bien Symfony, c'était la solution idéale pour rapidement *bootstraper* le back office
d'administration et l'API.

Toutes nos classes métiers et tous les *controllers* Symfony sont testés unitairement par Phpunit.
Des scénarios [Behat](https://github.com/Behat/Behat) permettent de couvrir également la quasi-totalité des
fonctionnalités du Back-Office.

### Back office

Pour tous nos *controllers*, nous nous sommes inspirés du *pattern Action-Domain-Response (ADR)* avec les
[Invokable Controllers](http://symfony.com/doc/current/controller/service.html#invokable-controllers).

Cela a beaucoup d'avantages :

- Une classe *controller* = une action.
- Peu de ligne de code dans un *controller* : *Keep It Simple Stupid*.
- Cela pousse à découpler le code, et toute logique hors *controller* est déportée dans des services du domaine.
- Une classe de *controller* est ainsi facilement testable unitairement.

Exemple de "nouveau" *controller* :

```php
<?php

namespace App\Ui\Admin\Action\Course;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Course\AssignUser;
use App\Domain\Model\Course;
use App\Ui\Admin\Form\Type\Course\AssignUserType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class AssignUserAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var FlashBagInterface */
    private $flashBag;

    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    public function __invoke(Request $request, Course $course): Response
    {
        $assign = new AssignUser($course);
        $form = $this->formFactory->create(AssignUserType::class, $assign, []);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($assign);
            $this->flashBag->add('success', 'flash.admin.course.assign_user.success');

            return new RedirectResponse($this->router->generate('admin_course_list'));
        }

        return $this->engine->renderResponse('Admin/Course/assign_users.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
        ]);
    }
}
```

Nous avons utilisé [l'autowiring des services](https://symfony.com/doc/current/service_container/autowiring.html)
qui rend beaucoup plus simple la gestion des dépendances:

```yaml
services:
    _defaults:
        # automatically injects dependencies in services
        autowire: true
        # automatically registers your services as commands, event subscribers, etc.
        autoconfigure: true

    # example:
    App\Ui\Admin\Action\Course\AssignUserAction:
        autowire: true
```

### GraphQL et Symfony

Pour concevoir un serveur d'API GraphQL, nous avons utilisé le bundle Symfony
[overblog/GraphQLBundle](https://github.com/overblog/GraphQLBundle)
lui-même utilisant l'implémentation en PHP de [webonyx/graphql-php](https://github.com/webonyx/graphql-php).

Déclaration d'un schéma d'un cours :

```yaml
Course:
    type: object
    config:
        description: "A course"
        fields:
            uuid:
                type: "String!"
                description: "The uuid of the course."
            title:
                type: "String!"
                description: "The title of the course."
            updatedAt:
                type: "DateTime!"
                description: "The last update date of the course in format 2017-01-15 10:00"
            folders:
                type: "[Folder]"
```

Déclaration d'un *resolver* d'un cours :

```yaml
services:
    App\Infrastructure\GraphQL\Resolver\CourseResolver:
        autowire: true
        tags:
            - { name: overblog_graphql.resolver, alias: "courses", method: "resolveCourses" }
```

Nous avions des besoins assez simples. La mise en place d'un serveur GraphQL n'est vraiment pas plus compliqué que REST.

## Conclusion

Nous avons adoré travailler sur React / Redux / GraphQL, à réfléchir l'utilisation d'une application web en hors-ligne
tout en faisant attention de bien penser l'UI/UX pour mobile.

Nous avons adoré travailler avec le *pattern Action-Domain-Response* pour des *controllers* Symfony sexy.

Et par dessus tout, nous avons adoré que notre travail permette à des étudiant•e•s d'accéder à la connaissance.

<blockquote>
« J'espère qu'avec Chalkboard Education chacun aura accès à l'éducation dont il rêve et qu'à mon échelle,
je vais faire progresser le monde. »
</blockquote>
Adrien, cofondateur de Chalkboard Education
