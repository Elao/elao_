---
type:               "post"
title:              "Comment intégrer Vue.js dans une application Symfony"
date:               "2016-10-21"
lastModified:       ~
modifieddate:       "2020-05-05"

description:        "Guide d'intrégration de Vue.js dans une application Symfony"

thumbnail:          "content/images/blog/thumbnails/vuejs.jpg"
banner:             "content/images/blog/headers/vuejs.jpg"
tags:               ["Vuejs","Javascript","Frontend","Symfony"]

authors:            ["mcolin"]
---

Dans mon [précédent article](/dev/pourquoi-devriez-vous-utiliser-vue-js-dans-vos-projets/) je vous parlais des avantages de Vue.js et vous expliquais pourquoi vous devriez l'utiliser dans vos projets. Je disais que **Vue.js** était parfait pour ajouter des fonctionnalités frontend à **Symfony**, je vais vous détailler dans cet article comment l'intégrer au framework **PHP**.

## Installation

Nous allons tout d'abord installer **Vue.js** :

```bash
npm install vue vue-loader vue-template-compiler
```

ou

```bash
yarn add vue vue-loader vue-template-compiler
```

Puis **Webpack Encore** :

```bash
npm install @symfony/webpack-encore --save-dev
composer require symfony/webpack-encore-bundle
```

ou

```bash
yarn add @symfony/webpack-encore --dev
composer require symfony/webpack-encore-bundle
```

Dans `webpack.config.js`, activez le *vue loader* avec `enableVueLoader()` :

```javascript
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('ats', './assets/app.js')

    // ...

    .enableVueLoader()
;

module.exports = webpackConfig;
```

**Webpack Encore** est maintenant paré pour compiler vos composants **Vue.js**.

```bash
yarn encore dev --watch --watch-poll
```

## Integration

Vous avez alors plusieurs posibilités pour intégrer des composants **Vue.js** dans votre application **Symfony**. Vous pouvez [intégrer directement vos template Vue.js dans Twig](#mélanger-twig-et-vue-js) ou utiliser des [Single File Component](#single-file-component).

### Mélanger Twig et Vue.js

Vous pouvez intégrer directement du templating **Vue.js** dans votre template **Twig**.

Néanmoins, il faut savoir que Twig et Vue.js utilisent les mêmes délimiteurs pour afficher les variables. S'il est possible de [modifier les délimiteurs utilisés par Twig](https://twig.symfony.com/doc/3.x/recipes.html#customizing-the-syntax) comme de modifier ceux par Vue.js. Je recommende plutôt d'encadrer votre template Vue.js par les balises `{% verbatim %}{% endverbatim %}` pour indiquer à Twig de ne pas interpréter le code à l'interieur.

```html
<h1>{{ 'Ce texte est rendu par Twig' }}</h1>

{% verbatim %}
  <div id="app">
    {{ 'Ce texte est rendu par Vue.js' }}
  </div>
{% endverbatim %}
```

```javascript
import Vue from 'vue'

new Vue({ el: '#app' })
```

Si vous utilisez des composants, vous pouvez soit utiliser des templates inlines :

```html
<div id="app">
  <mon-composant inline-template>
    <div>
      {% verbatim %}{{ foobar }}{% endverbatim %}
    </div>
  </mon-composant>
</div>
```

```javascript
const MonComposant = {
  data() {
    return { foobar: 'Foobar' }
  }
}

new Vue({
  el: '#app',
  components: {
    MonComposant
  },
})
```

Soit utiliser des `x-templates` :

```html
<div id="app">
  <mon-composant></mon-composant>
</div>

<template id="mon-composant-template" type="text/x-template">
  <div>
    {% verbatim %}{{ foobar }}{% endverbatim %}
  </div>
</template>
```

```javascript
const MonComposant = {
  template: '#mon-composant-template',
  data() {
    return { foobar: 'Foobar' }
  }
}

new Vue({
  el: '#app',
  components: {
    MonComposant
  },
})
```

Ces deux méthodes sont interessantes si vous avez besoin de rendre des morceaux de Twig dans votre template Vue. C'est quelque chose qui peut être très utile pour lier un composant Vue à un formulaire Symfony par exemple.

```html
<div id="app">
{{ form_start(form) }}

  <my-counter :max="1000" inline-template>
    <div>
      {{ form_row(form.content, { attr: { 'v-model': 'content' } }) }}
      {% verbatim %}
        <span :class="{ error: hasError }">{{ contentLength }} / {{ max }}</span>
      {% endverbatim %}
    </div>
  </my-counter>

{{ form_end(form) }}
</div>
```

```javascript
const MyCounter = {
  props: ['content', 'max'],
  data() {
    return {
      content: null,
    }
  },
  computed: {
    contentLength() {
      return this.content.length
    },
    hasError: {
      return this.content.length > max
    },
  }
}

new Vue({
  el: '#app',
  MyCounter: {
    MyCounter
  },
})
```

Ou pour injecter des variables Twig dans les props :

```html
<div id="app">
  <my-component :name="'{{ app.user.username }}'" inline-template>
    <div>
      {% verbatim %}
        Hello {{ name }}
      {% endverbatim %}
    </div>
  </my-component>
  <my-api-component :endpoint="{{ path('api_endpoint') }}" :initial-data="{{ data|json_encode }}">
    <ul>
      <li v-if="loading">Chargement...</li>
      {% verbatim %}
        <li v-for="item in items">{{ item.name }}</li>
      {% endverbatim %}
    </ul>
  </my-api-component>
</div>
```

Vous pouvez également injecter des données complexes en *JSON* :

```html
<div id="app">
  <my-component :initial-data="{{ data|json_encode }}">
    <ul>
      {% verbatim %}
        <li v-for="row in initialData">
          {{ row.firstname }} {{ row.lastname }}
        </li>
      {% endverbatim %}
    </ul>
  </my-component>
</div>
```

Très pratique pour ajouter une petite touche de **Vue.js** par endroit, je ne recommande pas ces méthodes pour créer une application complexe.

!!! info ""
    Si vous souhaitez tout de même changer les délimiteurs, sur une instance de Vue :

```javascript
new Vue({
  delimiters: ['${', '}']
})
```
    
ou globalement pour toutes les instances de Vue :

```javascript
Vue.config.delimiters = ['${', '}'];
```

Vous pourrez ainsi utiliser conjointement les deux moteurs de templates :

```html
<h1>{{ variable_twig }}</h1>
<p>${ variable_vue }<p>
```

!!! danger ""
    Attention néanmoins, le changement de délimiteurs de façon globale
    peut vous couper des composants tiers que vous pourriez installer et qui
    embarqueraient leur template avec les anciens délimiteurs.


### Single File Component

Une autre façon d'écrire vos composants est d'utiliser les [composants monofichier (Single File Component)](https://fr.vuejs.org/v2/guide/single-file-components.html). Ces fichiers `.vue` contiennent à la fois le script et le template de vos composants.

`assets/components/Greeting.vue` :

```html
<template>
    <h2>{{ greeting }}</h2>
</template>

<script>
  export default {
    data() {
      return {
        greeting: "Hello world"
      };
    }
  };
</script>
```

`assets/components/App.vue` :

```html
<template>
  <Greeting></Greeting>
</template>

<script>
  import Greeting './Greeting.vue'

  export default {
    components: {
      Greeting,
    },
  }
</script>
```

`assets/app.js`

```javascript
import Vue from 'vue'
import App from './components/App.vue'

new Vue({ render: h => h(App) }).$mount('#app')
```

`template/app.html.twig` :

```html
<html>
  <head>
    {{ encore_entry_link_tags('app') }}
  </head>
  <body>
    <div id="app"></div>

    {{ encore_entry_script_tags('cooptation') }}
  </body>
</html>
```

Vous ne pourrez plus utiliser **Twig** dans vos templates **Vue**, mais vous aurez l'avantage d'avoir des composants complètement autonomes et réutilisables facilement. Je recommande cette méthode si vous devez inclure une importante partie réactive dans votre application Symfony.

### Injecter des variables Symfony

En utilise les **composants monofichier (Single File Component)** vous ne pouvez plus injecter de *props* à vos composant depuis **Twig** comme on pouvait le faire avec les *inline template*.

Voici donc une petite astuce pour créer des props à partir des attributs de l'élément sur lequel vous montez votre application **Vue.js**.

`assets/components/App.vue` :

```html
<template>
  <div>
    Hello {{ name }}
  </div>
</template>

<script>
  export default {
    props: ['name']
  }
</script>
```

`assets/app.js`
```javascript
import Vue from 'vue'
import App from './components/App.vue'

new Vue({
  render(h) {
    return h(App, {
      props: {
        name: this.$el.getAttribute('data-name'),
      },
    })
  },
}).$mount('#app')
```

`template/app.html.twig` :

```html
<div id="app" data-name="{{ app.user.username }}"></div>
```

## Conclusion

Ces pistes peuvent vous permettre de mettre en place des applications hybrides reposant sur une base de **Symfony** pour certaines choses plus complexe à mettre en place côté client comme la gestion des utilisateurs, l'authentification ou le back-office mais de proposer tout de même une interface réactive et moderne grâce à **Vue.js**.
