---

type:               "post"
title:              "Créer une clé bootable OSX 10.10 Yosemite"
date:               "2014-09-17"
lastModified:       ~

description:        "Créer une clé bootable OSX 10.10 Yosemite."

thumbnail:          "images/posts/thumbnails/yosemite.jpg"
tags:               ["OSX"]
categories:         ["Tech", "OSX"]

authors:            ["gfaivre"]

---

Suite à la sortie récente de <strong>OSX Yosemite</strong> vous trouverez ci-dessous la marche à suivre pour créer une clé "bootable" à partir de l'image (DMG) récupérée de l'installation de l'AppStore.

Celle-ci permettra entre autre:

* D'éviter que toute l'équipe télécharge 5Go de données pour l'installer
* De conserver une copie locale "au cas ou"
* Ou encore de faire une installation "from scratch" sur un poste, parce que des fois c'est quand même bien de faire du propre ;)


1.-  En premier lieu procurez-vous Yosemite via le mac app store. (pour rappel il est installé dans /Applications)
Avant de lancer la mise à jour, copier le fichier <strong>InstallESD.dmg</strong> vers un emplacement de sauvegarde, en effet une fois la mise à jour faites le package sera automatiquement supprimé.

2.-  Connectez une clé USB formatée à votre machine, si vous le faites avec l'utilitaire de disque la partition par défaut devrait s'appeler "Untitled" modifiez les instruction en conséquence si ce n'est pas le cas chez vous.

Attention pour cette partie il faut bien faire attention de sélectionner "GUID Partition Table" dans les options lors de la création de la partition comme ci-dessous:

<p class="text-center">
    ![Créer une clé bootable osx yosemite](images/posts/2014/OSX_Yosemite.png)
</p>


3.-  Exécuter la liste d'instructions suivantes:

```
sudo hdiutil attach /Applications/Install\ OS\ X\ Yosemite.app/Contents/SharedSupport/InstallESD.dmg
```

```
sudo asr restore -source /Volumes/OS\ X\ Install\ ESD/BaseSystem.dmg -target /Volumes/Untitled -erase -format HFS+
```

```
sudo rm /Volumes/OS\ X\ Base\ System/System/Installation/Packages
```

```
sudo cp -a /Volumes/OS\ X\ Install\ ESD/Packages /Volumes/OS\ X\ Base\ System/System/Installation/Packages
```

```
sudo cp -a /Volumes/OS\ X\ Install\ ESD/BaseSystem.chunklist /Volumes/OS\ X\ Base\ System
```

```
sudo cp -a /Volumes/OS\ X\ Install\ ESD/BaseSystem.dmg /Volumes/OS\ X\ Base\ System
```

Et enfin:

```
hdiutil detach /Volumes/OS\ X\ Install\ ESD
```

Vous disposez à présent d'une clé "bootable" permettant de faire une installation "propre" de Yosemite. Il suffit de redémarrer votre Mac et de maintenant la touche "Alt" enfoncée et de choisir de démarrer à partir de la clé.
