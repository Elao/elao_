---
type:           "post"
title:          "Intégrer des icônes vectorielles dans React Native"
date:           "2018-03-02"
publishdate:    "2018-03-06"
draft:          false
tableOfContent:        true

description:    "Comment intégrer des icônes vectorielles dans une app React Native grâce à une police de caractères personnalisée."

thumbnail:      "images/posts/thumbnails/harpal-singh-396280-unsplash.jpg"
header_img:     "images/posts/headers/harpal-singh-396280-unsplash.jpg"
tags:           ["react native", "react", "mobile", "font", "icon"]
categories:     ["dev"]

author:    "tjarrand"

---

Les avantages du vectoriel pour intégrer une suite d'icônes dans une application sont assez nombreux :

- Varier la __taille__ du pictogramme sans perte de qualité.
- Modifier sa __couleur__ avec une simple propriété CSS (changement d'état au survol, etc).
- Supporter "automatiquement" les différentes __densités de pixels__ des écrans (écrans retina, etc).

### Problème

Malheureusement pour nous, React Native ne supporte pas nativement le format SVG, principal format vectoriel que nous utilisons sur le web.

Le développement mobile avec React Native nous contraint donc à fournir chaque illustration en plusieurs exemplaires pour supporter les différentes résolutions d'écrans. Et chaque changement de couleur donne lieu à une nouvelle série d'images pour le même pictogramme.

### Solution !

Heureusement pour nous, ce que React Native supporte bien, ce sont les polices de caractères personnalisées.

Et ça tombe bien, car on dispose déjà les outils web qui nous permettent de fournir une suite d'icônes sous forme de police de caractères (également un format vectoriel).

Voici donc les étapes pour intégrer nos icônes SVG dans une application React Native grâce aux polices personnalisées !

## Générer une police d'icônes

Pour générer une police de caractères à partir d'icônes au format SVG, plusieurs outils existent déjà.
Chez élao, nous travaillons généralement avec [IcoMoon](https://icomoon.io/app/#/select).

1 . Créez votre suite d'icônes en sélectionnant parmi les polices proposées et/ou en uploadant vos propres pictogrammes au format SVG.

![](images/posts/2018/react-native-font-icon/compose_font.png)

2 . Cliquez sur _Generate Font_ puis ouvrez les propriétés de la police (à côté du bouton _Download_) et saisissez un nom pour votre police qui ne contienne que des lettres standards [a-z], minuscules et/ou majuscules (ex : `acmeIcon`).

![](images/posts/2018/react-native-font-icon/customize_font_name.png)

3 . Fermez la pop-in pour valider votre changement puis téléchargez votre police grâce au bouton _Download_. Enfin décompressez le fichier ZIP téléchargé.

4 . Dans le dossier obtenu, récuperez la police au format `ttf` contenu dans le répertoire `fonts` et placez-la dans votre projet dans un répertoire de votre choix (ex: `./assets/fonts/acmeIcon.ttf`).

![](images/posts/2018/react-native-font-icon/icon.ttf.png)

⚠️ _Note :_ Attention, pour bien fonctionner sur iOS et Android, le nom du fichier `.ttf` doit correspondre __exactement__ au nom de la police choisie dans l'étape 2.

## Intégrer la police au build React Native

_Note :_ Ici je ne fais que répéter les étapes décrites dans cet article assez complet ["React Native Custom Fonts" 🇬🇧](https://medium.com/react-native-training/react-native-custom-fonts-ccc9aacf9e5e)

### Configurer le build natif

Ajoutez la section suivante à votre `package.json` :

```json
{
    "rnpm": {
      "assets": ["./assets/fonts/"]
    }
}
```

_Note :_ ici le chemin doit correspondre au dossier choisi à l'étape 4.

### Lier la police

Executez un simple `react-native link` ✨

Si tout s'est bien passé, vous pouvez voir votre police copiée dans le dossier de ressources Android :

```
$ ls ./android/app/src/main/assets/fonts
> acmeIcon.ttf
```

Côté iOS, votre fichier `ios/AcmeApp/Info.plist` devrait comprendre les nouvelles lignes qui suivent :

```xml
<key>UIAppFonts</key>
<array>
  <string>acmeIcon.ttf</string>
</array>
```

_Note :_ La suite d'icônes est amenée à évoluer durant la vie de votre app. Pour la mettre à jour, remplacez votre fichier `./assets/fonts/acmeIcon.ttf` par sa nouvelle version puis exécutez `react-native link` à nouveau.

## Afficher les icônes dans notre app

En CSS, pour afficher une icône à partir d'une police, on s'y prend de la manière suivante :

On définit d'abord une classe pour notre pictogramme et on lui attribue un pseudo-élément contenant le caractère UTF-8 correspondant à l'icône voulue dans la police générée.

```css
.icon-home:before {
  font-family: 'acmeIcon';
  content: "\e902";
}
```

Puis on l'affiche en HTML comme ceci :

```html
<span class="icon-home"></span>
```

Côté React Native, l'équivalent de cette technique s'écrirait ainsi :

```jsx
<Text style={{ fontFamily: 'acmeIcon' }}>{'\u{e902}'}</Text>
```

💡 _Petite subtilité :_ si le caractère UTF-8 en CSS se note `"\e902"`, en Javascript c'est `'\u{e902}'`.
La partie variable pour chaque icône ici est `e902`. IcoMoon vous fournit ce code unique pour chaque pictogramme, à vous de l'adapter au format Javascript.

![](images/posts/2018/react-native-font-icon/icon_code.png)

Bien que cette notation fonctionne, je vous propose de créer un composant réutilisable et plus simple à utiliser !

### Créer un composant Icon

Nous allons maintenant créer un composant `Icon` chargé de rendre une icône.

```javascript
import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Text, StyleSheet } from 'react-native';

export default class Icon extends Component {
  /**
   * Liste les pictogrames disponibles en faisant correspondre un nom à chaque code UTF8
   */
  static icons = {
    'elao': '\u{e910}',
    'home': '\u{e902}',
    'pencil': '\u{e905}',
    'camera': '\u{e90f}',
  };

  /**
   * On crée un style de base qui utilise notre police personnalisée
   */
  static styles = StyleSheet.create({
    icon: {
      fontFamily: 'acmeIcon',
    },
  });

  static propTypes = () => ({
    // On déclare une propType "icon" qui servira à sélectionner le pictogramme
    icon: PropTypes.oneOf(Object.keys(Icon.icons)).isRequired,
    // On reprend la propType "style" du composant Text
    style: Text.propTypes.style,
  });

  /**
   * Certaines propriétés CSS font bugger l'affichage des polices personnalisées sur Android.
   * Nous préférons donc les exclure des styles à appliquer au pictogramme.
   */
  safeIconStyle(styles) {
    const style = StyleSheet.flatten(styles);

    delete style.fontWeight;

    return style;
  }

  /**
   * On rend un composant Text contenant le pictogramme demandé
   */
  render() {
    const { icons, styles } = this.constructor;
    const { icon, style } = this.props;

    return <Text style={this.safeIconStyle([styles.icon, style])}>{icons[icon]}</Text>;
  }
}
```

### Utilisation du composant Icon

Nous pouvons maintenant afficher des icônes dans notre app comme ceci :

```jsx
<Icon icon="home" style={{ fontSize: 16, color: 'green' }} />
```

_Note :_ Le composant `Icon` se comportera comme le composant `Text` de React Native, notamment concernant l'héritage des styles lorsqu'il est contenu dans un composant `Text` stylisé. Voir exemple ci-dessous :

```jsx
<Text style={styles.title}
  <Icon icon="home"/>
  Retour à l'accueil
</Text>
```

Et le résultat !

![](images/posts/2018/react-native-font-icon/result.png)

---

<em>Illustration photo par <em><a style="background-color:black;color:white;text-decoration:none;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px;" href="https://unsplash.com/@aquatium?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from Harpal Singh"><span style="display:inline-block;padding:2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-1px;fill:white;" viewBox="0 0 32 32"><title>unsplash-logo</title><path d="M20.8 18.1c0 2.7-2.2 4.8-4.8 4.8s-4.8-2.1-4.8-4.8c0-2.7 2.2-4.8 4.8-4.8 2.7.1 4.8 2.2 4.8 4.8zm11.2-7.4v14.9c0 2.3-1.9 4.3-4.3 4.3h-23.4c-2.4 0-4.3-1.9-4.3-4.3v-15c0-2.3 1.9-4.3 4.3-4.3h3.7l.8-2.3c.4-1.1 1.7-2 2.9-2h8.6c1.2 0 2.5.9 2.9 2l.8 2.4h3.7c2.4 0 4.3 1.9 4.3 4.3zm-8.6 7.5c0-4.1-3.3-7.5-7.5-7.5-4.1 0-7.5 3.4-7.5 7.5s3.3 7.5 7.5 7.5c4.2-.1 7.5-3.4 7.5-7.5z"></path></svg></span><span style="display:inline-block;padding:2px 3px;">Harpal Singh</span></a>
