---
type:               "post"
title:              "Symfony 2 – L’injection de dépendances"
date:               "2010-06-03"
publishdate:        "2010-06-03"
draft:              false
slug:               "symfony-2-linjection-de-dependances"
description:        "Symfony 2 – L’injection de dépendances"

thumbnail:          "/images/posts/thumbnails/event-dispatcher.jpg"
tags:               ["Symfony", "PHP"]
categories:         ["Dev", "PHP", "Symfony"]

author_username:    "vbouzeran"
---


**Avertissement :**
Depuis la rédaction de cet article sur l'injection de dépendances, le coeur de Symfony2 a évolué de façon notable, en particulier certaines classes du répertoire **DependencyInjection** mentionnées dans cet article ont été renommées, révisées, ou refondues. Toutefois, les principes généraux décrits dans cet article restent d'actualité.

{{< figure src="/images/posts/2010/dependency-injection-symfony.png" title="Injection de dépendances" alt="dependency injection symfony Symfony 2 Linjection de dépendances" width="278" height="236">}}

Cet article est le premier d'une série à venir sur Symfony 2. Pour commencer, je vous invite à télécharger la <a href="http://symfony-reloaded.org/code" target="_blank">sandbox</a> de Symfony 2. J'ai décidé de commencer par l'injection de dépendances car il s'agit d'un composant clé de Symfony 2, et que la bonne compréhension de cette nouvelle version du Framework doit forcément en passer par là. L'injection de dépendance est réellement au coeur du Framework.

Nous allons commencer par tenter d'éclaircir cette notion (ou ce design pattern), puis nous verrons plus concrètement l'implémentation qui en est faite au sein de Symfony 2. Commençons par une petite définition.

> Définition Wikipedia:
>
> "**L'injection de dépendances** (Dependency Injection) est un mécanisme qui permet d'implanter le principe de l'Inversion de contrôle. Il consiste à créer dynamiquement (injecter) les dépendances entre les différentes classes en s'appuyant généralement sur une description (fichier de configuration). Ainsi les dépendances entre composants logiciels ne sont plus exprimées dans le code de manière statique mais déterminées dynamiquement à l'exécution."

### Qu'est ce que l'injection de dépendances ?

En programmation objet, et particulièrement avec les Frameworks, nous sommes souvent amenés à manipuler un grand nombre de classes d'objet différentes. Ces différents objets sont chargés de fournir une fonctionnalité ou un "service". Dans une application standard, nous serons par exemple amenés à manipuler :

*   Un objet **User**, supposé représenter l'utilisateur connecté à l'application
*   Un objet **Session**, pour la gestion de la session de l'utilisateur
*   Un objet **Requete**, représentant la requête envoyée par un client
*   Un objet **Réponse**, pour la réponse que l'application va renvoyer
*   Un objet **Log**, pour pouvoir gérer des logs
*   Un objet **Mailer**, pour pouvoir envoyer des mails
*   etc ...

La nécessité de l'injecteur de dépendances provient, d'une part, du fait que certains de ces objets sont liés entre eux, ou plutôt "dépendants" les uns des autres, d'autre part, que les objets sont de plus en plus découplés et scindés en classes bien particulières. Par exemple, le service "**Mailer**" utilisera peut être le service "**Log**" pour enregistrer les mails qui sont envoyés par l'application.

L'idée de l'injection de dépendances est d'utiliser un fichier de configuration pour auto-générer une classe (un "container") qui contiendra des méthodes nous permettant d'accéder à ces services ; elle contiendra l'initialisation des services (via l'instanciation de la classe qui les représente), gérera les dépendances de ces services (via les constructeurs ou l'appel de méthodes), la configuration des services, etc....

Pour illustrer ce concept, voyons un petit exemple.

Imaginons que nous ayons trois services :

*   un mailer (chargé d'envoyer des mails)
*   un logger (chargé d'enregistrer des logs)
*   un router (chargé du routing)

L'injecteur de dépendances pourrait ressembler à quelque chose comme ça :

#### Fichier de configuration

```
* Service 'mailer'
- Utiliser la classe 'MonMailer'
- Passer le service 'logger' dans son constructeur
- Appeler la méthode 'initialiser'* Service 'logger'
- Utiliser la classe 'MonLogger'* Service 'routeur'
- Utiliser la classe 'MonRouteur'
- Appeler la méthode 'setLogger' en lui passant le service 'logger' en paramètre
```

#### Container généré

{{< highlight php >}}
<?php
class Container()
{
  public function getServiceMailer()
  {
    $service = new MonMailer($this->getServiceLogger());
    $service->initialiser();
    return $mailer;
  }

  public function getServiceLogger()
  {
    $service = new MonLogger();
    return $service;
  }

  public function getServiceRouteur()
  {
    $service = new MonRouteur();
    $service->setLogger($this->getServiceLogger());
    return $service;
  }
}

{{< /highlight >}}

L'injection de dépendances permet donc une gestion beaucoup plus claire et souple de ces différents "services". En modifiant ce fichier de configuration nous pourrions très simplement :

*   Changer la classe utilisée pour représenter un service
*   Ajouter un service
*   Modifier / Ajouter des dépendances

### Symfony 1.x et le factories.yml

A l'origine, sous Symfony 1.x, ce qui remplaçait plus ou moins l'injecteur de dépendances était le fichier **factories.yml** avec comme container l'objet **sfContext**. Ce fichier était chargé en cache (**factories.yml.php**) et les différents 'services' inclus dans l'objet **sfContext** au travers de sa propriété **factories**.

{{< highlight php >}}
<?php
class sfContext implements ArrayAccess
{
  protected
  $dispatcher = null,
  $configuration = null,
  $mailerConfiguration = array(),
  $factories = array();
    ....
  /**
  * Loads the symfony factories.
  */
  public function loadFactories()
  {
    ....
    // include the factories configuration
    require($this->configuration->getConfigCache()->checkConfig('config/factories.yml'));
    ....
  }
}
{{< /highlight >}}


Le fichier **factories.yml** était géré par la classe **sfFactoryConfigHandler** et aboutissait dans le cache au fichier **factories.yml.php** qui se contentait d'enrichir le tableau **$factories** du **sfContext**.

{{< highlight php >}}

$class = sfConfig::get('sf_factory_controller', 'sfFrontWebController');
$this->factories['controller'] = new $class($this);
$class = sfConfig::get('sf_factory_request', 'sfWebRequest');
$this->factories['request'] = new $class(...)
{{< /highlight >}}


Les différents services étaient ensuite accessibles au travers du *sfContext :

{{< highlight php >}}
<?php
class sfContext implements ArrayAccess
{
  public function getController()
  ....
  public function getRequest()
  ...
}
{{< /highlight >}}


### Symfony 2 et l'injection de dépendances

Nous arrivons maintenant à la partie intéressante: L'implémentation de l'injection de dépendances dans Symfony 2.
En premier lieu, je vous conseille vivement la lecture de la documentation de Fabien Potencier dédiée au composant:
<a href="http://components.symfony-project.org/dependency-injection/trunk/book/00-Introduction" target="_blank">Symfony Dependency Injection</a> (il s'agit du composant php 5.2, mais la documentation est toujours d'actualité dans le sens où le fonctionnement est identique).

Jetons un oeil aux sources du composant (*/sandbox/src/vendor/symfony/src/Symfony/Components/DependencyInjection*) :

{{< figure src="/images/posts/2010/di-folder.png" title="Injection de dépendances" alt="di folder Symfony 2   Linjection de dépendances" width="284" height="253">}}

* Le répertoire ***Dumper** *va contenir les classes permettant de dumper un ***Builder*** sous un format spécifique (Yaml, Xml, PHP, Graphviz).
* Le répertoire ***Loader*** var contenir les classes permettant de charger des ***BuilderConfiguration*** à partir de formats spécifiques (Yaml, Xml, Ini)
* ***Container*** est notre classe de base, ie le container qui va contenir les différents services et les paramètres associés.
* ***Builder*** est une classe qui étend ***Container*** et qui va permettre d'auto-générer le ***Container*** à partir de la configuration, elle va consommer des objets de la classe ***BuilderConfiguration*** afin d'enrichir le ***Container***.
* ***BuilderConfiguration*** est la classe qui permet de configurer le ***Builder***. Les objets ***BuilderConfiguration*** vont contenir des définitions de services, des ressources nécessaires au fonctionnement des services déclarés, etc...
* ***Definition*** est la classe qui représente la définition d'un service. C'est à partir des objets de cette classe que le builder va pouvoir construire les services.

Le fonctionnement est somme toute assez simple. Les différents loaders vont permettre de générer des objets du type BuilderConfiguration (qui contiendront des objets Definition) qui seront mergés au sein du Builder pour aboutir à notre Container. Les dumpers permettront de faire l'opération inverse, à savoir : générer un fichier de définitions pour les formats Yaml et Xml, un output Graphviz et enfin un objet php Container en ce qui concerne le dumper PHP.
Un schéma valant mieux qu'un long discours, on peut synthétiser le fonctionnement comme suit :
Note: Le loader de fichiers .ini n'a pas été représenté puisqu'il ne permet que de définir des paramètres.

{{< figure src="/images/posts/2010/injection-dependances.png" title="Injection de dépendances" alt="injection dependances Symfony 2   Linjection de dépendances" width="846" height="468">}}

**Le dumper Graphviz**: Ce dumper a pour objectif de générer un dump exploitable par le logiciel <a href="http://graphviz.org/" target="_blank">Graphviz</a> ; il va nous permettre d'obtenir un graphique représentant nos services et leurs dépendances.
****Le cache:**** Afin d'accélérer le fonctionnement des applications utilisant l'injection de dépendances, il est évident que les différents fichiers de configuration ne seront pas reparsés à chaque appel. En réalité, le container sera dumpé sous la forme d'une classe PHP et mise en cache.

Attardons nous un peu sur ces fameuses définitions de services qui sont un peu la pierre angulaire de l'injection de dépendances.

<table style="width: 100%;" border="0">
  <tr>
    <th colspan="2">
      Définition d'un service (La classe Definition)
    </th>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$class</span>
    </td>

    <td>
      Il s'agit de la classe de base représentant le service
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$file</span>
    </td>

    <td>
      Eventuel fichier à inclure avant de déclarer le fichier
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$constructor</span>
    </td>

    <td>
      Le constructeur de la classe représentant le service
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$arguments</span>
    </td>

    <td>
      Les arguments à passer au constructeur
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$shared</span>
    </td>

    <td>
      Booléen définissant si le service est partagé (singleton) ou réinstancié quand on y accède
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$calls</span>
    </td>

    <td>
      Des éventuelles méthodes et leurs paramètres à appeler après avoir initialisé le service
    </td>
  </tr>

  <tr>
    <td>
      <span style="-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; font-size: 13px; line-height: 19px;">$annotations</span>
    </td>

    <td>
      Annotations associées au service (nous y reviendrons plus tard)
    </td>
  </tr>
</table>

A partir de ces informations, le builder sera capable de recréer la méthode permettant d'accéder au service.
De façon très synthétique et grossière, la définition nous permettrait d'aboutir à une méthode comme ceci:

{{< highlight php >}}
<?php
public function getServiceXXX()
{
  require $file;
  $service = new $class();
  $service->$constructor($arguments);
  foreach ($calls as $call)
  $service->$call();
  return $service;
}
{{< /highlight >}}


**Note: **Dans Symfony 2, les bundles de base utilisent le loader XML pour définir leurs services. Le format XML a l'avantage de pouvoir être validé via **Components/DependencyInjection/Loader/schema/dic/services/services-1.0.xsd**

### Exemple de service: le logger du ZendBundle

Dans le répertoire Ressources/config/ du Bundle (** /sandbox/src/vendor/symfony/src/Symfony/Framework/ZendBundle**), on trouve le fichier : logger.xml. Il s'agit de notre fichier de configuration. Notre fichier qui sera chargé par le loader pour enrichir notre container.

{{< highlight xml >}}

<!--?xml version="1.0" ?-->

Symfony\Framework\ZendBundle\Logger\LoggerZend_Log::CRITSymfony\Framework\ZendBundle\Logger\DebugLoggerZend_Log_Writer_StreamZend_Log_Formatter_Simple%%timestamp%% %%priorityName%%: %%message%%

%zend.logger.path%

%zend.formatter.filesystem.format%

%zend.logger.priority%
{{< /highlight >}}


5 services sont définis: zend.logger, zend.logger.writer.filesystem, zend.formatter.filesystem, zend.logger.writer.debug et zend.logger.filter.
Les paramètres sont également définis dans le fichier.

Ces différentes déclaration aboutiront à la création de méthodes de ce type dans le container généré par le Dumper PHP.

{{< highlight php >}}
<?php
protected function getZend_LoggerService()
{
  if (isset($this->shared['zend.logger']))
  return $this->shared['zend.logger'];

  $instance = new Symfony\Framework\ZendBundle\Logger\Logger();
  $instance->addWriter($this->getZend_Logger_Writer_FilesystemService());
  $instance->addWriter($this->getZend_Logger_Writer_DebugService());

  return $this->shared['zend.logger'] = $instance;
}

protected function getZend_Logger_Writer_FilesystemService()
{
  if (isset($this->shared['zend.logger.writer.filesystem']))
  return $this->shared['zend.logger.writer.filesystem'];

  $instance = new Zend_Log_Writer_Stream($this->getParameter('zend.logger.path'));
  $instance->addFilter($this->getZend_Logger_FilterService());
  $instance->setFormatter($this->getZend_Formatter_FilesystemService());

  return $this->shared['zend.logger.writer.filesystem'] = $instance;
}

protected function getZend_Formatter_FilesystemService()
{
  if (isset($this->shared['zend.formatter.filesystem']))
  return $this->shared['zend.formatter.filesystem'];

  $instance = new Zend_Log_Formatter_Simple($this->getParameter('zend.formatter.filesystem.format'));

  return $this->shared['zend.formatter.filesystem'] = $instance;
}

protected function getZend_Logger_Writer_DebugService()
{
  if (isset($this->shared['zend.logger.writer.debug']))
  return $this->shared['zend.logger.writer.debug'];

  $instance = new Symfony\Framework\ZendBundle\Logger\DebugLogger();
  $instance->addFilter($this->getZend_Logger_FilterService());

  return $this->shared['zend.logger.writer.debug'] = $instance;
}

protected function getZend_Logger_FilterService()
{
  if (isset($this->shared['zend.logger.filter']))
  return $this->shared['zend.logger.filter'];

  $instance = new Zend_Log_Filter_Priority($this->getParameter('zend.logger.priority'));

  return $this->shared['zend.logger.filter'] = $instance;
}

protected function getLoggerService()
{
  return $this->getZend_LoggerService();
}
{{< /highlight >}}

 Comme vous pouvez le constater, une méthode getLoggerService est générée. Cette méthode est générée car un alias a été défini lors de la configuration du builder (ligne 59 du fichier ZendExtension.php).

{{< highlight php>}}

<?php
$configuration->setAlias('logger', 'zend.logger');
{{< /highlight >}}


#### Les annotations
 Les annotations permettent de rajouter des propriétés à un service. Ces annotations ne sont pas exploitées directement par l'injecteur de dépendances mais par des composants tierces. Une annotation possède un nom et des attributs. Par exemple, dans le fichier de définitions: Framework/WebBundle/Resources/config/web.xml, on trouve la définition suivante sur le service **request_parser**:

Cette définition rajoute donc une annotation "**kernel.listener**" sur le service **request_parser** avec comme attributs **event** et **method**.
Dans l'exemple précédent, le **Kernel** se servira de cette annotation pour définir le service en tant qu'écouteur (listener) sur l'event "core.request" (nous y reviendrons dans le prochain article).

#### Les extensions :

Lorsque les différents Bundles vont définir leurs services, ceux-ci ne seront pas directement chargés dans la configuration du Builder global. Au lieu de ça, les Bundles vont définir des extensions. Les extensions ne sont qu'un wrapper permettant de charger la configuration d'un bundle "à la demande". Les extensions vont permettre de mieux organiser les différents services. Pour expliquer le fonctionnement des extensions, nous allons étudier le Bundle Web (Framework/WebBundle).

Le fichier Bundle.php du WebBundle ressemble à ceci:

{{< highlight php >}}
<?php
class Bundle extends BaseBundle
{
  public function buildContainer(ContainerInterface $container)
  {
    Loader::registerExtension(new WebExtension());
    ....
  }
}
{{< /highlight >}}


La méthode **buildContainer()** définie sur chaque Bundle permet d'indiquer au **Kernel** comment configurer le Container pour ce Bundle. Ici, le Bundle va se contenter de déclarer une extension sur le loader. Il enregistre donc l'extension **WebExtension** dans le loader.
Voyons maintenant le fichier WebExtension.php

{{< highlight php >}}
<?php
class WebExtension extends LoaderExtension
{
  protected $resources = array(
  'templating'=> 'templating.xml',
  'web' => 'web.xml',
  'debug' => 'debug.xml',
  'user' => 'user.xml',
  );

  public function webLoad($config)
  {
    $configuration = new BuilderConfiguration();

    $loader = new XmlFileLoader(__DIR__.'/../Resources/config');
    $configuration->merge($loader->load($this->resources['web']));

    return $configuration;
  }

  public function userLoad($config)
  {....}

  public function templatingLoad($config)
  {....}

  public function debugLoad($config)
  {....}
  ...
  public function getAlias()
  {
    return 'web';
  }
}
{{< /highlight >}}


On voit que les extensions permettent de charger des configurations de Builder un peu différemment. Dans les différents fichiers de configuration manipulés, quel que soit le format du loader utilisé, on se contente de déclarer des services. Cependant, il faut savoir qu'il est également possible de charger et configurer des extensions ainsi définies.
Ce qu'on trouve en général dans les fichiers config.yml / config_dev.yml / config_prod.yml d'une application utilise principalement la déclaration des extensions prédéfinies par les Bundles et ne déclare pas de nouveaux services.
Exemple:

{{< highlight yaml >}}

kernel.config: ~
web.web: ~
web.templating: ~
web.debug:
exception: %kernel.debug%
toolbar: %kernel.debug%
zend.logger:
priority: info
path: %kernel.root_dir%/logs/%kernel.environment%.log
swift.mailer:
transport: gmail
{{< /highlight >}}


Dans ce fichier de configuration, seules des extensions sont déclarées. A noter que si une extension n'est pas déclarée dans le fichier de configuration d'un Container, ses services ne seront pas inclus dans le Container final.
Le but des extensions est de fournir une méthode plus "propre" pour déclarer ses services, mais le fonctionnement reste identique, les Bundles vont déclarer des services comme vu précédemment, les extensions ne fournissant qu'un moyen de charger ou configurer ces déclarations.

### Mise en pratique

Afin de bien comprendre le fonctionnement, je vous propose une petite mise en pratique au travers de quelques exemples. Les exemples seront basés sur la sandbox proposée sur le site de <a href="http://www.symfony-reloaded.org" target="_blank">Symfony 2</a> et l'application Hello World !

Au niveau de la sandbox, les fichiers de configuration du Builder sont : config.yml, config_dev.yml et config_prod.yml. La méthode qui va charger ces fichiers est la méthode registerContainerConfiguration() de la classe HelloKernel. Nous allons travailler dans le cadre de nos exemples sur l'environnement de production. Le Container final généré se trouve dans le cache hello/cache/prod/helloProjectContainer.php

{{< highlight php >}}
<?php
class HelloKernel extends Kernel
{
  ...
  public function registerContainerConfiguration()
  {
    $loader = new ContainerLoader($this->getBundleDirs());
    $configuration = $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    return $configuration;
  }
  ...
}
{{< /highlight >}}


C'est au niveau de cette méthode que nous allons intervenir dans un premier temps.

#### Modifier la classe d'un service
 Dans notre premier exemple, nous allons tenter de modifier un service. En l'occurrence, le service représentant la requête, le bien nommé "RequestService". Notre container (HelloProjectContainer.php) définit le service ainsi:

{{< highlight php >}}
<?php
protected function getRequestService()
{
  if (isset($this->shared['request'])) return $this->shared['request'];
  $instance = new Symfony\Components\RequestHandler\Request();
  return $this->shared['request'] = $instance;
}
{{< /highlight >}}


Pour modifier la classe utilisée pour ce service, rien de plus simple, il nous suffit de modifier la configuration du container.
 Nous allons simplement modifier la classe associée dans la définition de ce service de cette façon :

{{< highlight php >}}
<?php
public function registerContainerConfiguration()
{
  $loader = new ContainerLoader($this->getBundleDirs());
  $configuration = $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
  $configuration->getDefinition('request')->setClass('MaClasseRequest'); // Nous modifions la définition du service "request"
  return $configuration;
}
{{< /highlight >}}


C'est fait ! Si on vide le cache et que l'on rappelle une url sur le projet, la définition de notre service dans le container ressemble maintenant à ça:

{{< highlight php >}}
<?php
protected function getRequestService()
{
    if (isset($this->shared['request'])) return $this->shared['request'];
    $instance = new MaClasseRequest();
    return $this->shared['request'] = $instance;
}
{{< /highlight >}}


#### Créer un service
 Dans cette exemple, nous allons voir comment déclarer un nouveau service. Nous allons tenter de déclarer le service "Monservice", utilisant la classe "Maclasse", dépendante du service "logger" sur laquelle on appellera les méthodes init1() et init2() à qui on passera le paramètre "coucou". Nous avions vu que le fichier config.yml déclarait les extensions à utiliser, mais c'est un fichier de configuration de Container comme un autre, nous pouvons donc y définir des services.

{{< highlight yaml >}}

services:
  monservice:
    id: monservice
    class: Maclasse
    arguments:
     - @logger
    calls:
     - [init1, []]
      - [init2, ['coucou']]
{{< /highlight >}}


C'est tout. Nous venons de déclarer un nouveau service dans notre application. Si nous jetons un oeil au Container généré:

{{< highlight php >}}
<?php
protected function getMonserviceService()
{
    if (isset($this->shared['monservice'])) return $this->shared['monservice'];

    $instance = new Maclasse($this->getService('logger'));
    $instance->init1();
    $instance->init2('coucou');

    return $this->shared['monservice'] = $instance;
}
{{< /highlight >}}


### En conclusion

Comme vous avez pu le découvrir dans cet article, l'injecteur de dépendances de Symfony 2 est vraiment puissant. Nous pouvons customizer, tweaker, étendre le fonctionnement du coeur même du Framework par ce biais.

L'injection de dépendances ne devrait plus avoir de secrets pour vous !

PS: Merci à Xavier R pour sa relecture et ses remarques avisées.
