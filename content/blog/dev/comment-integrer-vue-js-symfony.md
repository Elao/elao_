---
type:               "post"
title:              "Comment intégrer Vue.js dans une application Symfony"
date:               "2016-10-21"
publishdate:        "2016-10-21"
modifieddate:       "2020-05-05"
draft:              false
slug:               "comment-integrer-vue-js-application-symfony"
description:        "Guide d'intrégration de Vue.js dans une application Symfony"

thumbnail:          "/images/posts/thumbnails/vuejs.jpg"
header_img:         "/images/posts/headers/vuejs.jpg"
tags:               ["Vuejs","Javascript","Front","Frontend","Framework","Symfony"]
categories:         ["Dev", "Vuejs", "Javascript", "Symfony"]

author_username:    "mcolin"
---

Dans mon [précédent article](/fr/dev/pourquoi-devriez-vous-utiliser-vue-js-dans-vos-projets/) je vous parlais des avantages de Vue.js et vous expliquais pourquoi vous devriez l'utiliser dans vos projets. Je disais que **Vue.js** était parfait pour ajouter des fonctionnalités frontend à **Symfony**, je vais vous détailler dans cet article comment l'intégrer au framework **PHP**.

## Installation

Nous allons tout d'abbord installer **Vue.js** :

```
npm install vue vue-loader vue-template-compiler
```

ou

```
yarn add vue vue-loader vue-template-compiler
```

Puis **Webpack Encore** :

```
npm install @symfony/webpack-encore --save-dev
composer require symfony/webpack-encore-bundle
```

ou

```
yarn add @symfony/webpack-encore --dev
composer require symfony/webpack-encore-bundle
```

Dans `webpack.config.js`, activez le *vue loader* avec `enableVueLoader()` :

{{< highlight javascript >}}
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('ats', './assets/app.js')

    // ...

    .enableVueLoader()
;

module.exports = webpackConfig;
{{< /highlight >}}

**Webpack Encore** est maintenant paré pour compiler vos composants **Vue.js**.

```
yarn encore dev --watch --watch-poll
```

## Integration

Vous avez alors plusieurs posibilités pour intégrer des composants **Vue.js** dans votre application **Symfony**. Vous pouvez [intégrer directement vos template Vue.js dans Twig](#mélanger-twig-et-vue-js) ou utiliser des [Single File Component](#single-file-component).

### Mélanger Twig et Vue.js

Vous pouvez intégrer directement du templating **Vue.js** dans votre template **Twig**.

Néanmoins, il faut savoir que Twig et Vue.js utilisent les mêmes délimiteurs pour afficher les variables. S'il est possible de [modifier les délimiteurs utilisés par Twig](https://twig.symfony.com/doc/3.x/recipes.html#customizing-the-syntax) comme de modifier ceux par Vue.js. Je recommende plutôt d'encadrer votre template Vue.js par les balises `{% verbatim %}{% endverbatim %}` pour indiquer à Twig de ne pas interpréter le code à l'interieur.

{{< highlight html >}}
<h1>{{ 'Ce texte est rendu par Twig' }}</h1>

{% verbatim %}
  <div id="app">
    {{ 'Ce texte est rendu par Vue.js' }}
  </div>
{% endverbatim %}
{{< /highlight >}}

{{< highlight javascript >}}
import Vue from 'vue'

new Vue({ el: '#app' })
{{< /highlight >}}

Si vous utilisez des composants, vous pouvez soit utiliser des templates inlines :

{{< highlight html >}}
<div id="app">
  <mon-composant inline-template>
    <div>
      {% verbatim %}{{ foobar }}{% endverbatim %}
    </div>
  </mon-composant>
</div>
{{< /highlight >}}

{{< highlight javascript >}}
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
{{< /highlight >}}

Soit utiliser des `x-templates` :

{{< highlight html >}}
<div id="app">
  <mon-composant></mon-composant>
</div>

<template id="mon-composant-template" type="text/x-template">
  <div>
    {% verbatim %}{{ foobar }}{% endverbatim %}
  </div>
</template>
{{< /highlight >}}

{{< highlight javascript >}}
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
{{< /highlight >}}

Ces deux méthodes sont interessantes si vous avez besoin de rendre des morceaux de Twig dans votre template Vue. C'est quelque chose qui peut être très utile pour lier un composant Vue à un formulaire Symfony par exemple.

{{< highlight html >}}
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
{{< /highlight >}}

{{< highlight javascript >}}
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
{{< /highlight >}}

Ou pour injecter des variables Twig dans les props :

{{< highlight html >}}
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
{{< /highlight >}}

Vous pouvez également injecter des données complexes en *JSON* :

{{< highlight html >}}
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
{{< /highlight >}}

Très pratique pour ajouter une petite touche de **Vue.js** par endroit, je ne recommande pas ces méthodes pour créer une application complexe.

<div style="border-left: 5px solid #00a6ff;padding: 20px;margin: 20px 0;">
  Si vous souhaitez tout de même changer les délimiteurs, sur une instance de Vue :

  {{< highlight javascript >}}
  new Vue({
    delimiters: ['${', '}']
  })
  {{< /highlight >}}

  ou globalement pour toutes les instances de Vue :

  {{< highlight javascript >}}
  Vue.config.delimiters = ['${', '}'];
  {{< /highlight >}}

  Vous pourrez ainsi utiliser conjointement les deux moteurs de templates :

  {{< highlight html >}}
  <h1>{{ variable_twig }}</h1>
  <p>${ variable_vue }<p>
  {{< /highlight >}}
</div>

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Attention néanmoins, le changement de délimiteurs de façon globale peut vous couper des composants tiers que vous pourriez installer et qui embarqueraient leur template avec les anciens délimiteurs.
</div>

### Single File Component

Une autre façon d'écrire vos composants est d'utiliser les [composants monofichier (Single File Component)](https://fr.vuejs.org/v2/guide/single-file-components.html). Ces fichiers `.vue` contiennent à la fois le script et le template de vos composants.

`assets/components/Greeting.vue` :

{{< highlight html >}}
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
{{< /highlight >}}

`assets/components/App.vue` :

{{< highlight html >}}
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
{{< /highlight >}}

`assets/app.js`

{{< highlight javascript >}}
import Vue from 'vue'
import App from './components/App.vue'

new Vue({ render: h => h(App) }).$mount('#app')
{{< /highlight >}}

`template/app.html.twig` :

{{< highlight html >}}
<html>
  <head>
    {{ encore_entry_link_tags('app') }}
  </head>
  <body>
    <div id="app"></div>

    {{ encore_entry_script_tags('cooptation') }}
  </body>
</html>
{{< /highlight >}}

Vous ne pourrez plus utiliser **Twig** dans vos templates **Vue**, mais vous aurez l'avantage d'avoir des composants complètement autonomes et réutilisables facilement. Je recommande cette méthode si vous devez inclure une importante partie réactive dans votre application Symfony.

### Injecter des variables Symfony

En utilise les **composants monofichier (Single File Component)** vous ne pouvez plus injecter de *props* à vos composant depuis **Twig** comme on pouvait le faire avec les *inline template*.

Voici donc une petite astuce pour créer des props à partir des attributs de l'élément sur lequel vous montez votre application **Vue.js**.

`assets/components/App.vue` :

{{< highlight html >}}
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
{{< /highlight >}}

`assets/app.js`
{{< highlight javascript >}}
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
{{< /highlight >}}

`template/app.html.twig` :

{{< highlight html >}}
<div id="app" data-name="{{ app.user.username }}"></div>
{{< /highlight >}}

## Conclusion

Ces pistes peuvent vous permettre de mettre en place des applications hybrides reposant sur une base de **Symfony** pour certaines choses plus complexe à mettre en place côté client comme la gestion des utilisateurs, l'authentification ou le back-office mais de proposer tout de même une interface réactive et moderne grâce à **Vue.js**.
