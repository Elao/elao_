---
type:               "post"
title:              "Installation et premiers pas avec le plugin Symfony sfImageTransformExtraPlugin"
date:               "2010-07-12"
publishdate:        "2010-07-12"
draft:              false
slug:               "installation-et-premiers-pas-avec-le-plugin-symfony-sfimagetransformextraplugin"
description:        "Installation et premiers pas avec le plugin Symfony sfImageTransformExtraPlugin."

thumbnail:          "/images/posts/thumbnails/toad.jpg"
tags:               ["Développement", "PHP", "Symfony"]
categories:         ["PHP", "Symfony"]

author_username:    "xroldo"
---

Bonjour,

Nous allons voir aujourd'hui comment installer et utiliser le plugin <a href="http://www.symfony-project.org/plugins/sfImageTransformExtraPlugin" target="_blank">sfImageTransformExtraPlugin</a>.

Il s'agit d'un plugin très puissant permettant d'appliquer des traitements à des images au moyen de fichiers de configuration, sans qu'il soit donc nécessaire de polluer le code métier. Par ailleurs, il permet d'appliquer plusieurs traitements successifs de manière très intuitive. Il gère lui-même l'emplacement des fichiers générés et propose un système de cache réduisant les délais de transmission des images déjà générées.

Le sfImageTransformExtraPlugin est dépendant du plugin <a href="http://www.symfony-project.org/plugins/sfImageTransformPlugin" target="_blank">sfImageTransformPlugin</a> mais ne l'installez pas avant d'avoir lu la suite ! Pour faciliter la lecture et éviter toute confusion (les noms des deux plugins sont très proches), dans la suite de l'article je ferai référence au "plugin de base" pour sfImageTransformPlugin et au "plugin Extra" pour sfImageTransformExtraPlugin.

L'outil est donc très puissant mais sa documentation contient de nombreuses zones d'ombre qui ne facilitent absolument pas sa prise en main. Et les ennuis commencent dès l'installation ! D'où l'intérêt de cet article.

### L'installation des plugins ou le début <del>des emm...</del> de nos démarches

> Le plugin sfImageTransformPlugin nécessite que GD ou ImageMagick soit installé sur votre machine.

En effet, au moment de la rédaction de ce billet, la version courante du plugin "Extra" est la version 1.0.5 qui requiert la version 1.3.0 ou 1.3.1 du plugin sfImageTransformPlugin. Or, la version courante de ce dernier est 1.4.0, mais elle est incompatible avec la dernière version du plugin "Extra". Si bien que si vous essayez d'installer le plugin "Extra" via la ligne de commande **plugin:install**, l'opération échouera car elle installera préalablement la dernière version de sfImageTransformPlugin, donc une version incompatible ! Vous me suivez ? Oui, je sais que ces histoires de versions ne sont pas passionnantes mais si vous souhaitez utiliser sfImageTransformExtraPlugin (et nous le recommandons !), mieux vaut être au courant de ces incompatibilités. Voici donc la démarche que nous préconisons :

Installer le plugin de base (sfImageTransformPlugin) en ligne de commande en prenant soin de préciser la version souhaitée :

```bash
./symfony plugin:install --release=1.3.1 sfImageTransformPlugin
```


Pour le plugin "Extra", il faut l'installer manuellement. Pour cela :

- rendez-vous sur <a title="Télécharger le plugin sfImageTransformExtraPlugin" href="http://www.symfony-project.org/plugins/sfImageTransformExtraPlugin" target="_blank">la page dédiée au plugin</a> et cliquez sur le lien "Download Package"

- décompressez le contenu de l'archive dans un répertoire temporaire

- copiez le répertoire "sfImageTransformExtraPlugin-1.0.5&#8243; (la racine de l'archive est un répertoire qui s'appelle "sfImageTransformExtraPlugin-1.0.5&#8243;, et il contient lui-même un sous-répertoire qui porte également ce nom ; c'est le sous-répertoire qu'il faut copier et non pas la racine de l'archive) dans le répertoire "plugins" de votre projet Symfony

- renommez ce répertoire en "plugins/sfImageTransformExtraPlugin" (**ie**, supprimer le tiret et le numéro de version)

Enfin, un article consacré à Symfony qui ne contient pas une instruction **symfony cc** n'est pas digne de ce nom ! Donc :

```bash
./symfony cc
```

> A ce stade des opérations, nous n'activons pas encore le plugin "Extra". La configuration du plugin se fera plus tard car pour l'heure, nous allons tester le plugin sfImageTransformPlugin. Nous nous soucierons du plugin "Extra" plus tard.

### A présent, essayons le plugin de base

Nous allons nous assurer dans un premier temps que le plugin de base (sfImageTransformPlugin) est correctement installé et configuré. Pour cela, nous allons créer une page très simple, avec un bouton déclenchant un traitement sur une image de notre choix. Commençons par créer un module "image" dans l'application "Frontend".

Si elle n'existe pas encore, on crée l'application "Frontend" ...

```bash
./symfony generate:app frontend
```

... puis le module :

```bash
./symfony generate:module frontend image
```

Pour notre démonstration, j'ai choisi cette image, partant du principe qu'un prosélytisme de bon aloi ne saurait nuire à la carrière d'un développeur :

![Installation et premier pas avec le plugin sfImageTransformExtraPlugin](/images/posts/2010/zf.jpg)

J'ai nommé cette image zf.jpg et je l'ai placée dans le répertoire web/images de notre projet.

Nous allons dans un premier temps écrire le code de notre template pour y insérer le formulaire par lequel nous allons appeler la transformation de notre image :

```html
# apps/frontend/modules/image/templates/indexSuccess.php
<h1>Image Transform & Extra plugin</h1>

<div style="text-align:center; width: 400px; border: 1px solid lightgrey; padding:25px;">
    <img src="/images/zf.jpg"></img><br/><br/>
    <form method="post">
        <input type="submit" value="Transform image !"></input>
    </form>
</div>
```


Comme nous venons juste de créer le module, pensez à supprimer le code par défaut de la méthode executeIndex du fichier `apps/frontend/modules/actions/actions.class.php`. Affichons à présent la page dans notre navigateur : http://mon_hote_virtuel/frontend_dev.php/image. Le résultat est admirable ... je ne m'en lasse pas ...

![indexSuccess.php Installation et premiers pas avec le plugin Symfony sfImageTransformExtraPlugin](/images/posts/2010/sfImageTransformExtrePlugin.png)

Cela étant, notre but étant d'utiliser dans un premier temps le plugin sfImageTransformPlugin, nous allons écrire une méthode sans prétention qui va appliquer un traitement à cette image lorsque nous soumettons le formulaire. Pour cela, nous allons modifier le fichier actions.class.php afin de mettre à jour la méthode executeIndex :

```php
<?php
# apps/frontend/modules/image/actions/actions.class.php
class imageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if(sfRequest::POST == $request->getMethod())
    {
      $file = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'zf.jpg' ;
      $img = new sfImage($file, 'image/jpg') ;
      $response = $this->getResponse();
      $response->setContentType($img->getMIMEType());
      $img->resize(100,null) ;
      $response->setContent($img);

      return sfView::NONE;
    }
  }
}
```


Rien de très compliqué, à la soumission du formulaire, nous créons une miniature de notre image d'origine (dimension 100 px) et nous l'affichons dans le navigateur. Cette méthode n'est pas d'un grand intérêt, je l'avoue ; elle a pour seule but de s'assurer que nous avons correctement configuré le plugin de base. A présent, attaquons-nous au plat de résistance :

### La bête : le plugin "Extra"

Nous allons commencer par activer le plugin :

```php
<?php
// ...
class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfImageTransformPlugin');
    $this->enablePlugins('sfImageTransformExtraPlugin');
  }
}
```


Le point d'entrée du plugin "Extra" se trouve dans un module que nous allons activer :

```yaml

# apps/frontend/config/settings.yml
all:
  .settings:
    # ....
   enabled_modules:        [ sfImageTransformator ]
```


Pour faire fonctionner le plugin, il est nécessaire d'activer la détection automatique du tyme MIME. Cela se fait dans le fichier app.yml de l'application :

```yaml
# apps/frontend/config/app.yml
all:
  sfImageTransformPlugin:
    mime_type:
      auto_detect:  true
      library:      gd_mime_type #  gd_mime_type (GD), Fileinfo (PECL), MIME_Type (PEAR)
   font_dir:       %SF_PLUGINS_DIR%/sfImageTransformExtraPlugin/data/example-resources/fonts
```


Les routes par défaut du plugin "Extra" sont configurées avec l'URL relative /thumbnails/.. pour générer et/ou afficher les miniatures générées. Pour qu'elles fonctionnent, nous devons créer un répertoire "thumbnails" dans le répertoire "web" et nous assurer que le serveur possède les droits en écriture sur ce répertoire. Dans la console, en nous plaçant sous la racine du projet, nous entrons donc les commandes suivantes :

```
mkdir web/thumbnails
chmod 777 web/thumbnails
```

Comme cela fait un long moment que nous n'avons pas tapé un **symfony cc**, il est temps d'y remédier :

```
./symfony cc
```

Bon, nous avons passé un bon moment à préparer le terrain mais force est de constater que nous n'avons pas été très productifs jusqu'à présent. Il est donc temps d'exploiter ce plugin ! Mais avant toute chose, quelques remarques utiles. Je vous invite dans un premier temps à recharger la page où nous affichons le formulaire et à consulter les informations qui figurent dans la Web Debug Toolbar. Sélectionnez-y l'onglet "config" et affichez le contenu du sous-menu "Settings". Vous noterez tout en bas de la liste, après les constantes app_* et sf_*, la présence d'une constante nommée "thumbnailing_formats" :

```yaml
thumbnailing_formats:
  default: { quality: 25, mime_type: image/gif, transformations: ... }
  original: { quality: 100, mime_type: image/jpg, transformations: {  } }
```


Mais que sont ces "thumbnailing_formats" et où sont-ils définis exactement ? Pour répondre à la seconde question, ces formats sont définis dans le fichier plugins/sfImageTransformExtraPlugin/thumbnailing.yml et voici son contenu :

```yaml

# plugins/sfImageTransformExtraPlugin/thumbnailing.yml
all:
  .settings:
    formats:
# -------------------------------------------------------------------------------
# --- // 404 image
     default:
        quality:                    25
        mime_type:                  image/gif
        transformations:
          - { adapter: GD, transformation: create, param: { x: 250, y: 200 } }}
          - { adapter: GD, transformation: text,   param: { text: '404', x: 10, y:  50, size: 72, font: accid___, color: '#FF0000', angle: 0 } }}
          - { adapter: GD, transformation: text,   param: { text: 'Image could not be found', x: 10, y:  120, size: 12, font: accid___, color: '#FF0000', angle: 0 } }}
# -------------------------------------------------------------------------------
# --- // original image. no transformation
     original:
        quality:                    100
        mime_type:                  image/jpg
        transformations:            []
```


Comme je le disais en préambule, le plugin “Extra” va nous permettre d’appliquer plusieurs traitements successifs sur une image, sans que nous ayons à écrire du code PHP, grâce à des fichiers de configuration. Et c’est dans ce fichier qui occupe une place centrale dans le fonctionnement et l’exploitation du plugin que l’on détermine cette configuration. Noter au passage que la documentation du plugin y fait référence de manière extrêmement maladroite et ambigüe. Ainsi, en analysant ce fichier, nous constatons que le format “default” est prévu pour générer un format de sortie de type “image/gif” ; il crée (create) une nouvelle image de 250 * 200 pixels, lui ajoute le texte ‘404‘, puis le texte ‘Image could not be found‘. Ces transformations successives sont décrites dans l’entrée “transformations” du fichier yaml.

Nous allons à présent copier ce fichier dans le répertoire “config” de notre application et l’adapter pour créer nos propres formats :

```
cp plugins/sfImageTransformExtraPlugin/thumbnailing.yml apps/frontend/config/thumbnailing.yml
```

Modifions le fichier que nous venons de copier afin d’appliquer à une image originale des transformations plus visibles. Pour cela, remplaçons le contenu du fichier :

```yaml

# apps/frontend/config/thumbnailing.yml
all:
  .settings:
    formats:
# -------------------------------------------------------------------------------

      mon_format_1:
        quality:               25
        mime_type:             image/png
        transformations:
          - { adapter: GD, transformation: crop, param: { left: 90, top: 72, width: 120, height: 120 }}
          - { adapter: GD, transformation: rotate, param: { angle: 20, background: "#FFFFFF" }}
          - { adapter: GD, transformation: crop, param: { left: 17, top: 17, width: 120, height: 120 }}
```


Si nous rechargeons notre page web et que nous consultons les “settings” de la Web Debug Toolbar, nous constatons que notre nouveau format est disponible. Nous allons donc appliquer ces transformations à notre image originale. Le plugin offre la possibilité de modifier une image située dans le répertoire public de l’application, une image distante disponible via le protocole http, ou bien une image située en base de données. Dans le cadre de notre exemple, nous allons transformer l’image de l’exemple précédent, que nous aurons placée dans le répertoire “web/uploads”.

> La documentation du plugin est très floue quant à la manière d’indiquer l’emplacement d’une image dans le répertoire public de notre application. En fait, les images doivent être stockées dans le répertoire web/uploads ou un de ses sous-répertoires, mais la documentation n’est absolument pas claire sur ce point (et sur bien d’autres !).

Copions l’image du logo ZF dans le répertoire public :

```
cp web/images/zf.jpg web/uploads/zf.jpg
```

Nous devons à présent déclarer une route pointant sur le module sfImageTransformator, chargé de transformer les images. Comme nous souhaitons modifier une image du répertoire public de l’application, nous précisons dans l’entrée “options” que l’image source est de type “File” :

```yaml

# apps/frontend/config/routing.yml
sf_image_file:
  class: sfImageTransformRoute
  url:  /thumbnails/:format/:filepath.:sf_format
  param: { module: sfImageTransformator, action: index }
  requirements:
    format:   "[\w_-]+"
    filepath: "[\w/_.]+"
    sf_format: "gif|png|jpg"
    sf_method: [ get ]
  options:
    image_source: File
```


Et pour finir, nous allons ajouter dans la template indexSuccess.php le lien permettant d’invoquer la transformation de notre image et d’afficher le résultat.

```php
# apps/frontend/modules/image/templates/indexSuccess.php
// ..... affichage du formulaire -- aucun changement
<?php echo image_tag(url_for("sf_image_file", array("format" => "mon_format_1", "filepath" => "zf.jpg")));
```


> Le filepath est relatif au répertoire “uploads”. Ainsi, si l’image se trouvait par exemple dans le répertoire web/uploads/mes_fichiers, nous aurions indiqué le filepath de cette manière :
> "filepath" => "mes_fichiers/zf.jpg"

Si l’on recharge notre page web, le résultat des transformations s’affiche alors sous notre formulaire. Voici le code HTML de la balise image générée par le plugin :

```
<img src="/frontend_dev.php/thumbnails/mon_format_1/zf.jpg.png"/>
```

Cela correspond exactement au pattern de la route que nous avons déclarée précédemment.

> Si l’image ne s’affiche pas, n’hésitez pas à consulter les logs de Symfony ou d’Apache. A titre d’exemple, à mesure que j’écrivais cet article, je développais l’exemple en parallèle sur un poste informatique plutôt “obsolète” et il s’est avéré que ma librairie graphique GD ne me permettait pas d’appliquer des rotations. Résultat sans appel dans le fichier de log de Symfony :
>
> [err] {sfImageTransformException} Cannot perform transform: sfImageRotateGD. Your install of GD does not support imagerotate

### Conclusion (provisoire)

Cet article est beaucoup plus long que ce à quoi je m’attendais et pourtant nous n’avons fait qu’effleurer le sujet, tant sont vastes les possibilités offertes par le plugin. sfImageTransformExtraPlugin est indéniablement un outil qui mérite que l’on s’y intéresse mais sa prise en main est assez délicate, en raison notamment d’une documentation que je juge assez approximative (ce qui est tout de même compréhensible, c’est un peu le revers de la médaille : plus un outil est riche, plus la mise à jour de la documentation est problématique). Cet article n’avait donc pas d’autre prétention que de vous initier à ce plugin et vous mettre le pied à l’étrier en évitant les premiers écueils.

### Remerciements

Je tiens à remercier chaleureusement Yves Heitz sans qui la rédaction de ce billet n’aurait pas été possible. Il a essuyé les plâtres en mettant en oeuvre le plugin sur un projet réel et il a su contourner les nombreux écueils auxquels on se heurte lorsque l’on souhaite utiliser le plugin.
