---
type:           "post"
title:          "Le web n'est pas mort, la revanche par les Progressives Web Apps"
date:           "2016-12-05"
publishdate:    "2016-12-05"
draft:          false

description:    "Les Progressives Web Apps ont pour objectif de rivaliser avec les apps natives. Voyons comment cela fonctionne et le gain que cela apporte à vos utilisateurs."

thumbnail:      "images/posts/thumbnails/pwa-general.jpg"
header_img:     "images/posts/2016/pwa/pwa-general.jpg"
tags:           ["progressive web app", "service worker", "web", "mobile", "offline"]
categories:     ["dev"]

author: "rhanna"

---

<small>Temps de lecture : 15 minutes</small>

En 2010, le magazine américain Wired titrait "[The web is dead](https://www.wired.com/2010/08/ff_webrip/)"
et prédisait que les apps allaient remplacer le web.
Retournement de veste en 2014 lorsque ce même magazine annonce "[The web is not dead](https://www.wired.com/insights/2014/02/web-dead/)".
L'installation d'apps n'a finalement pas pris le dessus sur l'utilisation du web.
En réalité la plupart des gens [n'installent ou n'utilisent que très peu d'apps](http://www.recode.net/2016/6/8/11883518/app-boom-over-snapchat-uber), celles des messageries et des réseaux sociaux.
Au contraire, l'usage du web en position de mobilité a explosé.

La plupart des sources citées dans cet article sont des sites Google ou des blogs de ses ingénieurs,
tout simplement parce que les contenus sont de qualité.
Cela s'explique car le mastondonte américain fait un lobby de dingue pour pousser les Progressives web apps et faire plier son rival Apple, qui est en retard en la matière.
Et honnêtement, Google n'a pas tout à fait tort. Voici pourquoi.

## Qu'est ce qu'une Progressive Web App ?

- **Amélioration progressive** : le site fonctionne pour n'importe quel utilisateur quel que soit le navigateur utilisé. Seuls les navigateurs modernes (comprendre Chrome et Firefox) profiteront de toutes les possibilités.
- **Responsive** : s'ajuste à la taille de l'écran, sur ordinateur, mobile ou tablette.
- **Indépendant de la connexion** : expérience améliorée grâce au Service Worker qui permet à l'application de fonctionner hors connexion ou en très bas débit.
- **Sécurité garantie** : l'utilisation d'un Service Worker est conditionnée par le fait que le site est délivré en https.
- **Ré-engagement de l'utilisateur** grâce :
    - aux notifications push,
    - à la possibilité d'installer un icône de raccourci comme pour une application native sur l'écran d'accueil de l'appareil (sur mobile, tablette...).
- **Légère et rapide** : le poids d'une app native est souvent minimum x10 par rapport à son équivalent web optimisé pour mobile.
Dans nos contrées où le haut-débit, la 4G et le forfait data quasi illimité sont des normes, il en n'est pas de même dans les pays en voie de développement.
De plus, nous ne profitons pas toujours d'une connectivité ou d'un débit constant.
Dans les transports souterrains, dans un lieu confiné ou dans de lointaines campagnes, il n'est pas rare d'être complètement "déconnecté".

### Des retours sur investissement assez impressionnants

- [AliExpress](https://developers.google.com/web/showcase/2016/aliexpress) : 104% de nouveaux utilisateurs quelque soit le navigateur utilisé ; 82% de hausse du taux de conversion des utilisateurs iOS,
- [eXtra Electronics](https://developers.google.com/web/showcase/2016/extra) : 100% d'augmentation des ventes avec les Web Push Notifications,
- [Jumia](https://developers.google.com/web/showcase/2016/jumia) : 9 fois plus de conversions de paniers abandonnés avec les Web Push Notifications,
- [5miles](https://developers.google.com/web/showcase/2016/5miles) : 30% de hausse de la conversion des utilisateurs qui passent par l'app placée en écran d'accueil.

## Le Service Worker

Depuis plusieurs années, il existe une technologie planquée dans nos navigateurs permettant de gérer du cache et donc de faire du hors-ligne : [Application Cache](https://developer.mozilla.org/fr/docs/Web/HTML/Utiliser_Application_Cache)
mais celle-ci est dépréciée au profit du [Service Worker](https://developer.mozilla.org/fr/docs/Web/API/Service_Worker_API/Using_Service_Workers).

### Comment ça marche ?

Un service worker est déclaré ainsi dans le code JavaScript de vos pages :

```js
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/service-worker.js');
  });
}
```

On dit alors que le Service Worker est "*registered*" dans le navigateur.
De plus, le scope est très important : /service-worker.js à la racine du domaine signifie que le service worker est disponible pour l'ensemble du domaine.
S'il était dans un répertoire /blog/service-worker.js, il ne fonctionnerait que sur les pages dont les urls commencent par /blog/.

Et notre service-worker.js dans tout ça ? Et bien, il contient des écouteurs d'évènements :

```js
self.addEventListener('install', event => {
  console.log('Service worker install');
});

self.addEventListener('activate', event => {
  console.log('Service worker ready');
});
```

### Gestion du cache

La mise en cache de ressources se fait ainsi dans notre Service Worker :

```js
var CACHE_NAME = 'my-cache-v1';
var urlsToCache = [
  '/',
  '/styles/main.css',
  '/script/main.js'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        return cache.addAll(urlsToCache);
      })
  );
});
```

La récupération de ressources en cache se fait en écoutant l'évènement "fetch".
Si la ressource n'est pas trouvée en cache, on tente notre chance via le réseau :

```js
self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        if (response) {
          return response;
        }

        // use the network to fetch request
        return fetch(event.request);
      }
    )
  );
});
```

Modifions notre code précédent pour mettre en cache la ressource récupérée du réseau :

```js
// we need to clone the response.
var fetchRequest = event.request.clone();

return fetch(fetchRequest).then(
  function(response) {
    // Check if we received a valid response
    if (!response || response.status !== 200 || response.type !== 'basic') {
      return response;
    }

    // we need to clone it so we have two streams.
    var responseToCache = response.clone();

    caches.open(CACHE_NAME)
      .then(function(cache) {
        cache.put(event.request, responseToCache);
      });

    return response;
  }
);
```

Il s'agit ici d'un exemple simple. La gestion du cache n'est pas forcément triviale.
De nombreuses stratégies de gestion du cache existent.
Jake Archibald, un des ingénieurs de Google a écrit un article complet à ce sujet : [The offline cookbook](https://jakearchibald.com/2014/offline-cookbook/).
Il n'y a pas de "meilleure solution"; tout dépendra de votre besoin.

### Mise à jour d'un Service Worker

Pour mettre à jour un Service Worker ou les ressources mises en cache par le Service Worker, il faut supprimer les ressources en cache.

```js
self.addEventListener('activate', function(event) {
  var cacheWhitelist = ['pages-cache-v1', 'blog-posts-cache-v1'];

  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
```

Les nouvelles versions des fichiers seront alors récupérées au prochain chargement de la page, via l'évènement "install" vu plus haut dans cet article.

### Architecture

Pour booster vos web apps, il est recommandé d'opter pour une architecture de type "App Shell".
C'est à dire que vous fournissez une coquille vide contenant la présentation de votre app (html, js, css, images).
Ces ressources sont mises en cache et n'ont pas besoin d'être retéléchargées à chaque requête.
Ensuite, les données sont récupérées via des *fetch* d'API par exemple et viennent s'insérer dans votre présentation à l'aide par exemple de votre framework Frontend préféré.
Les requêtes à ces API peuvent elles-mêmes être mises en cache.

<p class="text-center">
    <img src="/images/posts/2016/pwa/appshell.png" alt="Appshell" style="max-width:80%"/>
</p>

### C'est réservé aux apps mobiles ?

Il est vrai que le problème de connectivité, on l'a surtout en position de mobilité et grâce à la gestion du cache, une web app reste utilisable même en mode déconnecté.
Mais rien ne vous empêche d'utiliser un Service Worker pour booster vos applications web *desktop*.
Les mastodontes tels que Gmail ou Facebook les utilisent couramment.

## Le Web App Manifest

Le Web App Manifest a pour but de permettre l'installation des applications Web sur l'écran d'accueil d'un appareil, notamment sur les smartphones,
offrant aux utilisateurs un accès plus rapide.

L'ouverture du site dans le navigateur se présente comme une application native avec également un Splash Screen :

<p class="text-center">
    <img src="/images/posts/2016/pwa/splashscreen.gif" alt="splashscreen" style="max-width:80%"/>
</p>

Ce Web App Manifest se présente sous forme d'un fichier json :

```json
{
  "short_name": "Elao App",
  "name": "Elao, agence web agile",
  "icons": [
    {
      "src": "logo-icon-1x.png",
      "type": "image/png",
      "sizes": "48x48"
    },
    {
      "src": "logo-icon-2x.png",
      "type": "image/png",
      "sizes": "96x96"
    },
    {
      "src": "logo-icon-4x.png",
      "type": "image/png",
      "sizes": "192x192"
    }
  ],
  "start_url": "/?utm_source=homescreen",
  "background_color": "#2196F3",
  "display": "standalone"
}
```

À noter :

- Dans notre *start_url* on a inséré un paramètre *utm_source* pour collecter par exemple via Analytics les stats utilisateurs ayant installé l'app sur leur écran d'accueil.
- Pour simuler un affichage de style App native dans notre navigateur, c'est à dire sans l'interface du navigateur, on a spécifié pour "display" la valeur "standalone". Sinon on peut utiliser la valeur par défaut "browser".
- De plus, on peut forcer l'orientation de l'affichage, par exemple en mode paysage cela donne `"orientation": "landscape"`.

Dans votre `<head>` html, il suffit de déclarer votre manifest de la façon suivante :

```html
<link rel="manifest" href="/manifest.json">
```

### Bannière d'installation sur l'écran d'accueil

À quel moment le "prompt" ou bannière d'installation sur l'écran d'accueil s'affiche ?

En tant que développeur, il n'est pas possible de déclencher cet évènement.
C'est le navigateur qui décide de l'afficher sous certaines conditions.
Par exemple, les conditions de Chrome sont (liste non exhaustive) :

- l'utilisateur a visité deux fois la page dans les 5 minutes,
- un web app manifest est déclaré,
- un Service worker est enregistré.

Et ces conditions peuvent changer dans les futures versions des navigateurs !

En tant que développeur, on peut toutefois attraper cet évènement et l'afficher plus tard par exemple en attendant que l'utilisateur réalise une "action positive" sur notre application.
Afin qu'il soit sollicité dans le bon timing.
Par exemple, dans le code ci-dessous, nous allons sauvegarder le prompt en écoutant l'évènement "beforeinstallprompt"
et différer l'affichage lorsque l'utilisateur aura cliqué sur un bouton :

```js
var deferredPrompt;

window.addEventListener('beforeinstallprompt', function(e) {
  e.preventDefault();

  // Stash the event so it can be triggered later.
  deferredPrompt = e;

  return false;
});

btnSave.addEventListener('click', function() {
  if(deferredPrompt !== undefined) {
    deferredPrompt.prompt();

    deferredPrompt.userChoice.then(function(choiceResult) {
      if(choiceResult.outcome == 'accepted') {
        console.log('App added to home screen');
      } else {
        console.log('User cancelled home screen install');
      }

      deferredPrompt = null;
    });
  }
});
```

## Push Notifications

Les Push et les Notifications sont deux technologies différentes mais complémentaires :

- l'[API Push](https://developer.mozilla.org/en-US/docs/Web/API/Push_API) est utilisée lorsqu'un serveur sur Internet envoi une notification attrapée et traitée par le service worker sur notre navigateur
- l'[API Notifications](https://developer.mozilla.org/en-US/docs/Web/API/Notifications_API) permet au Service Worker d'afficher la notification à l'utilisateur.

### L'API Push ou demander à l'utilisateur de souscrire aux notifications

Cela se passe ainsi, non pas dans le Service Worker mais dans le code JavaScript de vos pages :

```js
var swRegistration;
var isSubscribed = false;

if ('serviceWorker' in navigator && 'PushManager' in window) {
  // Register the Service Worker
  navigator.serviceWorker.register('/myserviceworker.js')
  .then(function(serviceWorkerRegistered) {
    swRegistration = serviceWorkerRegistered;
  })
  .catch(function(error) {
    console.error('Service Worker Error', error);
  });

  // Set the initial subscription value
  swRegistration.pushManager.getSubscription()
  .then(function(subscription) {
    isSubscribed = !(subscription === null);

    if (isSubscribed) {
      console.log('User IS subscribed.');
    } else {
      console.log('User is NOT subscribed.');
    }
  });

  // On click on a "Subscribe to notification" button, call the pushManager to subscribe the user
  myButton.addEventListener('click', function() {
    if (swRegistration !== undefined) {
      return;
    }

    const applicationServerKey = urlB64ToUint8Array("yourApplicationServerPublicKey");

    swRegistration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: applicationServerKey
    })
    .then(function(subscription) {
      console.log('User is subscribed:', subscription);

      // We need to implement something to save the subscription, for example an API call to save it on our database
      // updateSubscriptionOnServer(subscription);

      isSubscribed = true;
    })
    .catch(function(error) {
      console.log('Failed to subscribe the user: ', error);
    });
  }
}
```

Le paramètre "userVisibleOnly: true" est une option mais il est en réalité requis.
Cela permet de s'engager qu'une notification sera affichée à chaque fois qu'il y aura un Push.

Et pour en savoir plus sur comment obtenir le paramètre *applicationServerKey*,
consultez cet article : [Generating the applicationServerKey](https://developers.google.com/web/fundamentals/engage-and-retain/push-notifications/sending-messages#generating-the-key).

Exemple d'objet Subscription généré par le navigateur :

```json
{
  "endpoint": "https://example.com/push-service/send/dbDqU8xX10w:APA91b...",
  "keys": {
    "auth": "qLAYRzG9TnUwbprns6H2Ew==",
    "p256dh": "BILXd-c1-zuEQYXH\\_tc3qmLq52cggfqqTr\\_ZclwqYl6A7-RX2J0NG3icsw..."
  }
}
```

Le *endpoint* dépend du navigateur utilisé et c'est lui même qui vous le fourni.
Par exemple pour Chrome, c'est un endpoint qui ressemble à ça : *https://android.googleapis.com/gcm/send/APA91bHPffi...*

### L'API Notifications

Pour générer une notification Push, votre serveur devra utiliser l'objet Subscription et donc le endpoint fourni.
Pour voir en détail comment gérer cela, consultez cet article : [Sending Messages](https://developers.google.com/web/fundamentals/engage-and-retain/push-notifications/sending-messages).

La prise en compte d'une notification est réalisée par le Service Worker, en écoutant un évènement "push" :

```js
self.addEventListener('push', event => {
  event.waitUntil(
    // Display a notification
    self.registration.showNotification('You got a notification!');
  );
});
```

Une notification ne requis qu'un titre, mais d'autres options sont possibles :

```js
self.registration.showNotification('You got a notification!', {
  "body": "Souhaitez-vous confirmer le rendez-vous du 20/11/2016 avec M. Martin ?",
  "icon": "/images/meeting.png",
  "tag": "meeting",
  "actions": [
    { "action": "yes", "title": "Yes", "icon": "images/yes.png" },
    { "action": "no", "title": "No", "icon": "images/no.png" }
  ]
 });
```

Comme vous pouvez le voir, outre le contenu et l'icone de la notification, il est possible :

- de taguer la notification (ici "meeting") afin de permettre de grouper les notifications de même tag,
- de proposer différentes actions pour permettre à l'utilisateur d'interagir avec notre web app directement depuis la notification.

Deux autres écouteurs sont disponibles pour réaliser des actions lorsque l'utilisateur ouvre ou ferme la notification :

```js
self.addEventListener('notificationclick', event => {
  // Do something with the event
  event.notification.close();
});

self.addEventListener('notificationclose', event => {
  // Do something with the event
});
```

Pour en savoir plus sur la gestion des notifications :

- [Sending Messages](https://developers.google.com/web/fundamentals/engage-and-retain/push-notifications/sending-messages)
- [Handling Messages](https://developers.google.com/web/fundamentals/engage-and-retain/push-notifications/handling-messages).

## Démos

- [Pokedex.org](https://www.pokedex.org/) est une web app que vous pouvez consulter en mode déconnecté.
- [Wikipedia offline demo](https://wiki-offline.jakearchibald.com/) est une démo montrant les possibilités de concevoir les applications en "offline-first".
- [Flipkart](https://www.flipkart.com/) est un site marchand qui fonctionne également en hors-ligne. Les contenus sont grisés lorsque le contenu n'est pas caché.
Un article à lire à ce sujet : [Building Flipkart Lite: A Progressive Web App](https://medium.com/@AdityaPunjani/building-flipkart-lite-a-progressive-web-app-2c211e641883#.zfx2f64ws).

## Outils

Le panel "Application" de Developer Tools de Chrome est déjà assez riche en fonctionnalités pour débuguer toutes les facettes d'une Progressive Web App.

Il permet de consulter le Cache Storage :

<p class="text-center">
    <img src="/images/posts/2016/pwa/chrome-devtools-cachestorage.png" alt="Cache storage" style="max-width:80%"/>
</p>

Vérifier le contenu du Web App Manifest et simuler l'ajout à l'écran d'accueil.
Sous Chrome desktop, l'ajout sera effectivement fait dans les onglets Applications.

<p class="text-center">
    <img src="/images/posts/2016/pwa/chrome-devtools-manifest.png" alt="service worker" style="max-width:80%"/>
</p>

Consulter le Service Worker utilisé et avoir différentes options parmi lesquelles :

- simuler la déconnection avec "offline",
- mettre à jour le Service Worker notamment en phase de développement avec "Update on reload",
- contourner le service worker pour obliger le navigateur à récupérer une ressource depuis le réseau (au lieu du cache) avec "Bypass for network".

<p class="text-center">
    <img src="/images/posts/2016/pwa/chrome-devtools-serviceworker.png" alt="service worker" style="max-width:80%"/>
</p>

Enfin, une fonction "Clear storage" permet de tout réinitialiser :

<p class="text-center">
    <img src="/images/posts/2016/pwa/chrome-devtools-clearstorage.png" alt="service worker" style="max-width:80%"/>
</p>

Pour en savoir plus, voir cet article [Debug Progressive Web Apps](https://developers.google.com/web/tools/chrome-devtools/progressive-web-apps).

De plus, voici d'autres outils - tous propulsés par Google - pour faciliter le développement d'une Progressive Web App :

- [Service Worker Precache](https://github.com/GoogleChrome/sw-precache/) est un module node pour faciliter la gestion de la mise en cache des ressouces statiques (HTML, JavaScript, CSS, images, etc.) via un Service Worker. Un [codelab](https://codelabs.developers.google.com/codelabs/sw-precache/index.html) est disponible.
- [Service Worker Toolbox](https://github.com/GoogleChrome/sw-toolbox) est un ensemble d'outils permettant notamment de gérer le *routing* vers du contenu caché ou du contenu en ligne.
- [Lighthouse](https://chrome.google.com/webstore/detail/lighthouse/blipmdconlkpinefehnmjammfjpmpbjk) est une extension Chrome permettant d'analyser une page et nous aider à implémenter les bonnes pratiques d'une Progressive Web App.
- [Progressive Web App Checklist](https://developers.google.com/web/progressive-web-apps/checklist)

## Ils en sont où Safari et iOS ?

L'arrivée des Progressive Web Apps met clairement en danger le modèle du store d'Apple. La firme à la pomme traine sans doute volontairement des pieds.

Une Progressive Web App fonctionne sous iOS, mais les utilisateurs ne profitent ni du Service Worker, ni donc de la mise en cache et des notifications Push. Ils voient un site web responsive "normal".

L'implémentation du Service Worker dans WebKit, le moteur de rendu de Safari, est "[under consideration](https://webkit.org/status/#specification-service-workers)".

Inutile d'utiliser un navigateur Chrome sur votre iPhone ou votre iPad pour profiter des Progressive Web App, cela ne fonctionnera pas. En effet, Chrome sous iOS est en réalité du packaging Google autour d'un WebKit :)

Du côté de [Microsoft Edge](https://developer.microsoft.com/en-us/microsoft-edge/platform/status/serviceworker/), bonne nouvelle, le Service Worker est en cours d'implémentation.

En bref, en cette fin 2016, tout le potentiel des Progressives Web Apps n'est exploité que sous Android + Chrome, Firefox ou Opera (oui Opera, vous avez bien lu).

Ce n'est pas une raison d'attendre pour vous mettre aux Progressives Web Apps ;
ces technologies sont en cours de propagation ;
les utilisateurs Android sont majoritaires par rapport à tous les autres OS, autant les adresser maintenant et les autres en profiteront dès que ces technologies seront supportées.

Pour suivre l'avancement de l'implémentation de Service Worker, un site : [Is Service Worker Ready?](https://jakearchibald.github.io/isserviceworkerready/)

## Et après ?

Que peut-on ajouter à notre Progressive Web Apps pour améliorer encore plus l'expérience utilisateur ?

- [Credential Management API](https://developers.google.com/web/updates/2016/04/credential-management-api) pour faciliter l'authentification de l'utilisateur.
- [Payment Request API](https://developers.google.com/web/updates/2016/07/payment-request) pour faciliter les paiements sur Internet.
- [BackgroundSync](https://github.com/WICG/BackgroundSync/blob/master/explainer.md) pour permettre la synchronisation de données en tâche de fond dès lors que la connectivité est retrouvée.

Devinez-quoi ? Ces technologies ne sont disponibles que dans les dernières versions de Chrome.
Cependant c'est très prometteur. A suivre donc de près !
