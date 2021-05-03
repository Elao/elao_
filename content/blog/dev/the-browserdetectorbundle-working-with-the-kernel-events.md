---
type:               "post"
title:              "The BrowserDetectorBundle: working with the Kernel events"
date:               "2013-08-02"
lastModified:       ~

description:        "The BrowserDetectorBundle: working with the Kernel events"

thumbnail:          "images/posts/thumbnails/homer.jpg"
tags:               ["Symfony", "PHP", "Kernel", "Browser"]
categories:         ["Dev", "Symfony", "PHP"]

author:    "tbessoussa"
---

A quoi sert l'évènement kernel.terminate ? Regardons du côté de la documentation :

> "To perform some "heavy" action after the response has been streamed to the user".

Une question que vous vous posez surement si vous n'avez pas eu l'occasion de travailler avec cet évènement : "Quand est-ce que je peux utiliser l'évènement "kernel.terminate" pour effectuer mes traitements ?" La réponse en image :

<p class="text-center">
    {{< figure src="images/posts/2014/kernel_terminate.png" title="Utilisation de lévènement kernel.terminate sous Symfony2" alt="Utilisation de lévènement kernel.terminate sous Symfony2" >}}
</p>


Concrètement, vous pouvez quasiment tout faire si vous utilisez cet évènement. A une chose près : votre traitement ne doit pas altérer la réponse. Pourquoi ? Parce qu'il est déclenché après que la réponse soit envoyé au client. Il n'y à donc plus moyen d'y rajouter des informations ou d'en altérer son contenu dans le but de l'envoyer au client. (Attention kernel.terminate a été rajouté en Symfony2.1, donc si vous êtes encore en 2.0, vous pouvez oublier).

```php
// ...
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
```

Alors finalement comment le mettre en place ? Voilà un exemple de la classe *GuzzleExceptionListener* qui écoute 2 évènements.

```php
<?php

namespace Tristanbes\ElophantBundle\EventListener;

use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Tristanbes\ElophantBundle\Manager\StatsManager;

/**
 * Class GuzzleExceptionListener
 */
class GuzzleExceptionListener
{
    private $statsManager;
    private $fail = false;

    /**
     * Constructor
     *
     * @param StatsManager $manager The stats Manager
     */
    public function __construct(StatsManager $manager)
    {
        $this->statsManager = $manager;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof BadResponseException) {
            $this->fail = true;
            $response   = new JsonResponse(array('success' => false));
            $event->setResponse($response);
        }
    }

    public function onKernelTerminate()
    {
        if (false === $this->fail) {
            return;
        }

        $this->statsManager->addFail();
    }
}
```


```xml
<service id="tristanbes_elophant.guzzle_exception_eventlistener" class="%tristanbes_elophant.guzzle.exception.class%">
    <tag name="kernel.event_listener" event="kernel.exception" method="onKernelException" />;
    <tag name="kernel.event_listener" event="kernel.terminate" method="onKernelTerminate" />;
    <argument type="service" id="tristanbes_elophant.stats.manager" />;
</service>;
```


** Explications : **

Le premier évènement écouté est, *kernel.exception. *Ce dernier, lorsqu'il sera déclenché, appellera la méthode *onKernelException* de la classe *GuzzleExceptionListener* introspectera la classe de l'exception et regardera si celle ci provient d'une erreur renvoyée par Guzzle à la suite d'un appel à un WebService.

Si c'est le cas, elle modifiera un attribut nommé $fail et renverra une réponse en *json au client.*

Une fois cette réponse envoyée, Symfony va [via la méthode $kernel->terminate()]; déclencher l'évènement kernel.terminate. Ce qui tombe plutôt bien car notre classe écoute cet évènement aussi. Si jamais $fail renvoie true, le service StatsManager s'occupera d'incrémenter une valeur en base de données (afin de savoir combien de requêtes ont échouées par jour).

Ici, on est loin de gagner des secondes sur la réponse, en effet, incrémenter une valeur en base de données est loin d'être une opération "lourde", alors pourquoi passer par kernel.terminate ? Parce qu'on peut, alors pourquoi se priver même pour de la "micro" optim ?

Attention cependant à bien débugger votre code, car vous ne verrez aucun output étant donné que la réponse est déjà envoyée.

A savoir que vous pouvez aussi ajoutez un listener directement depuis le dispatcher de Symfony2. Voilà un example avec l'utilisation d'une closure :

```php
<?php

namespace Tristanbes\ElophantBundle\EventListener;

use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Tristanbes\ElophantBundle\Manager\StatsManager;

/**
 * Class GuzzleExceptionListener
 */
class GuzzleExceptionListener
{
    private $statsManager;
    private $dispatcher;
    private $fail = false;

    /**
     * Constructor
     *
     * @param StatsManager $manager The stats Manager
     * @param
     */
    public function __construct(StatsManager $manager, EventDispatcher $dispatcher)
    {
        $this->statsManager = $manager;
        $this->dispatcher   = $dispatcher;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $statsManager = $this->statsManager;

        if ($exception instanceof BadResponseException) {

            $this->dispatcher->addListener('kernel.terminate', function (Event $event) use ($statsManager) {
                $statsManager->addFail();
            });
        }
    }
}
```

```xml
<service id="tristanbes_elophant.guzzle_exception_eventlistener" class="%tristanbes_elophant.guzzle.exception.class%">;
    <tag name="kernel.event_listener" event="kernel.exception" method="onKernelException" />;
    <argument type="service" id="tristanbes_elophant.stats.manager" />;
    <argument type="service" id="event_dispatcher" />;
</service>
```
