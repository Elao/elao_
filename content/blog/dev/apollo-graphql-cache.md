---
type:               "post"
title:              "Comprendre le cache du client Graphql Apollo"
date:               "2020-02-17"
publishdate:        "2020-02-17"
draft:              false

description:        "Découverte du fonctionnement du cache du client GraphQL Apollo."

thumbnail:          "images/posts/thumbnails/graphql-apollo.jpg"
header_img:         "images/posts/headers/graphql-apollo.jpg"
tags:               ["GraphQL","Cache","Apollo","Javascript","API"]
categories:         ["Dev", "Javascript"]

author:    "mcolin"
---

## Introduction

GraphQL est un standard qui s'impose peu à peu dans le monde des API. Comme tout protocole API, il vient avec différents clients facilitant le dialogue avec le serveur.

[Apollo GraphQL](https://www.apollographql.com/) est un service SAAS proposant une solution serveur GraphQL qui fournit également un très bon [client GraphQL Javascript](https://github.com/apollographql/apollo-client). Le client est open source et fonctionne avec n'importe quel serveur GraphQL.

GraphQL propose beaucoup d'avantages par rapport à d'autres protocoles API *over HTTP* comme REST par exemple (batching de query, sélection des données à retourner, format des données, ...) mais souffre d'un défaut si l'on peut dire : comme toutes les requêtes sont effectuées en POST sur le même endpoint, il est impossible de poser un simple **cache** HTTP en amont. Pas de Varnish, pas de **cache** navigateur, etc.

Si l'on veut mettre du **cache** côté client, il sera forcement applicatif. C'est justement l'une des grandes forces d'**ApolloClient**, il propose un **cache très performant**, chose qui va nous intéresser dans cet article.

## Première requête

L'utilisation basique du client est très simple et dans la veine de ce qu'on retrouve dans n'importe quel client API.

L'installation :

```bash
# installing the preset package
yarn add apollo-boost graphql-tag graphql
# installing each piece independently
yarn add apollo-client apollo-cache-inmemory apollo-link-http graphql-tag graphql
```

On instancie le client avec l'url du endpoint GraphQL :

```javascript
import ApolloClient from 'apollo-boost'

const client = new ApolloClient({
  uri: 'https://graphql.example.com'
})
```

Et c'est parti, on peut lancer nos requêtes GraphQL :

```javascript
import gql from 'graphql-tag'

client
  .query({
    query: gql`
      query Todos {
        todos {
          id
          text
          completed
        }
      }
    `
  })
  .then(data => console.log(data))
  .catch(error => console.error(error))
```

A partir de ce moment, cette requête est cachée en mémoire. Vous pouvez la relancer autant de fois que vous le souhaitez, aucun appel réseau ne sera fait.

## Mutations

Le client permet évidemment d'exécuter des mutations (modifications de données) :

```javascript
import gql from 'graphql-tag';

client
  .mutate({
    mutation: gql`
      mutation AddTodo($text: String!) {
        addTodo(text: $text) {
          id
          completed
        }
      }
    `,
    variables: {
      text: 'Foobar',
    },
  })
  .then(data => console.log(data))
  .catch(error => console.error(error))
```

Grâce à cette mutation, nos données on été mise à jour sur notre serveur. Par contre par la même notre cache n'est plus à jour, et comme dit précédement, Apollo ne refera pas l'appel à l'API pour la requête.

> Il y a seulement 2 problèmes compliqués en informatique : nommer les choses, et l’invalidation de cache.

Nous allons donc devoir mettre à jour ce cache, et pour le coup, c'est plutôt simple.

## Lire et écrire dans le cache

Le cache d'Apollo et son paradygme est un peu spécial. Il ne s'agit pas seulement d'un simple cache de requête. Il agit comme un *state manager* à l'instar de [Redux](https://redux.js.org/) ou [Vuex](https://vuex.vuejs.org/). Les données récupérées via une requête sont automatiquement stockées dans le cache mais il nous est possible d'y accéder et même d'y inserer ou d'y modifier de données sans refaire de requête.

Pour cela nous allons utiliser les méthodes `readQuery` et `writeQuery`. Attention, il faut appeler ces méthodes avec exactement la même requête GraphQL (variables inclues) que celle utilisée pour recupérer les données.

Pour cela nous allons utliser la méthode `readQuery` pour lire les données attaché à la requête :

```javascript
const TODOS = gql`
  query Todos {
    todos {
      id
      text
      completed
    }
  }
`

const data = client.readQuery({
  query: TODOS
})
```

Nous pouvons modifier ces données puis utiliser la méthode `writeQuery` pour les insérer dans le cache :

```javascript
client.writeQuery({
  query: TODOS
  data
})
```

En combinant l'utilisation de ce deux methodes dans l'`update` de la mutation, nous pouvons ainsi mettre à jour notre cache :

```javascript
const ADD_TODO = gql`
  mutation AddTodo($text: String!) {
    addTodo(text: $text) {
      id
      text
      completed
    }
  }
`

client
  .mutate({
    mutation: ADD_TODO,
    variables: {
      text: 'Foobar',
    },
    update: (cache, { data: AddTodo }) => {
      const { Todos } = cache.readQuery({
        query: TODOS,
      })

      const NewTodos = [...Todos, AddTodo]

      cache.writeQuery({
        query: TODOS,
        data: { Todos: NewTodos }
      })
    }
  })
```

## Fragments

De la même manière qu'avec `readQuery` et `writeQuery`, il est posible de mettre à jour des portions de requêtes utilisant le même fragment avec les méthodes `readFragment` et `writeFragment`.

Par exemple avec les requêtes suivantes :

```graphql
fragment TodoFields on Todo {
  id
  text
  completed
}

query Todo($id: ID!) {
  todo(id: $id) {
    ...TodoFields
  }
}

query Todos {
  todos {
    ...TodoFields
  }
}
```

Le même fragment est utilisé dans deux requêtes, il est possible de mettre à jour le fragment dans les deux requêtes en une fois à condition qu'il contienne l'identifiant d'un objet.

```javascript
const TodoFields = gql`
  fragment TodoFields on Todo {
    id
    text
    completed
  }
`

const todo = client.readFragment({
  id: 3, // identifiant de l'objet
  fragment: TodoFields
})
```

```javascript
client.writeFragment({
  id: 3, // identifiant de l'objet
  fragment: TodoFields
  data: { ...todo, completed: true }
})
```

## Mise à jour automatique

Heuresement, il n'est pas toujours obligatoire de mettre manuellement à jour le cache comme ci-dessus. Il y a certains cas où le cache est automatiquement mis à jour par Apollo.

Lorsque vous réalisez une requête recupérant des données contenant un `id`, par exemple :

```graphql
query {
  Foobar(id: 3) {
    id
    name
  }
}
```

Puis une mutation utilisante le même `id` :

```graphql
mutation {
  UpdateFoobar(id: 3, name: "Updated name") {
    id
    name
  }
}
```

Apollo a compris que vous intevennez sur le même objet (même type et meme id). Le cache sera alors automatiquement mis à jour et la propriété `name` sera modifiée partout. Attention par contre, le cache sera mis à jour avec les données renvoyées par la mutation, il faut donc bien faire attention à selectionner les données que l'on souhaite modifier dans le cache.

Je vous parlais du côté *state manager* du cache, et bien c'est ce qui se passe ici. Les objets y sont stockés avec une clé de cache de façon à pouvoir mettre à jour tous les résultats de requête concernés en une fois lorsque que l'un d'eux est modifié (sous couvert qu'ils soient identifiables par un `id` évidemment).

## Configurer le cache

Si vos objets n'ont pas de propriété `id` mais une propriété `uuid` par exemple, il est possible de configurer le cache pour l'utiliser et conserver la mise à jour automatique :

```javascript
import { InMemoryCache } from 'apollo-cache-inmemory';
import { HttpLink } from 'apollo-link-http';
import { ApolloClient } from 'apollo-client';

const link = new HttpLink();

const cache = new InMemoryCache({
  dataIdFromObject: object => object.uuid
});

const client = new ApolloClient({ link, cache });
```

Il est également possible de définir cet identifiant selon le type de l'objet :

```javascript
import { InMemoryCache, defaultDataIdFromObject } from 'apollo-cache-inmemory';

const cache = new InMemoryCache({
  dataIdFromObject: object => {
    if (object.__typename === 'Todo') {
      return object.uuid
    }

    return defaultDataIdFromObject(ojbect)
  }
});
```

D'autre configuration sont possibles, je vous encourage à lire [la documentation à ce sujet](https://www.apollographql.com/docs/react/caching/cache-configuration/) pour les découvrir.

## Redirection de cache

Dans certains cas, les données que vous requêtez peuvent déjà être présentes dans le cache d'une autre requête. L'exemple typique est lorsque vous fait un requête listant des objets, puis plus tard une requête avec les mêmes données retournant l'un de ces objets.

Par exemple pour la liste :

```graphql
query Todos {
  todos {
    id
    text
    completed
  }
}
```

Puis pour les détails :

```graphql
query Todo($id: ID!) {
  todo(id: $id) {
    id
    text
    completed
  }
}
```

Les deux requêtes utilisent les même données mais Apollo fera la seconde requête même si l'objet est déjà dans le case de la première car les données ne sont pas stockés avec la même clé de cache.

La redirection de cache permettra d'aller chercher ces données dans le cache d'une autre requête.

```javascript
import { InMemoryCache } from 'apollo-cache-inmemory'

const cache = new InMemoryCache({
  cacheRedirects: {
    Query: {
      Todo: (_, args, { getCacheKey }) =>
        getCacheKey({ __typename: 'Todo', id: args.id })
    },
  },
})
```

## Gérer la suppression de données

Si la mise à jour automatique est simple (un objet identifié par son ID peut être mise à jour automatiquement pour toutes les requêtes dans le cache), c'est un peu plus compliqué pour les suppressions.

Comme pour l'ajout, vous allez devoir mettre à jour manuellement le cache de chaque requête retournant la donnée supprimée.

```javascript
const REMOVE_TODO = gql`
  mutation RemoveTodo($id: ID!) {
    RemoveTodo(id: $id) {
      id
    }
  }
`

client
  .mutate({
    mutation: REMOVE_TODO,
    variables: {
      id: 3,
    },
    update: (cache, { data: RemoveTodo }) => {
      // Mise à jour du cache de la requête TODOS
      const { Todos } = cache.readQuery({ query: TODOS })
      const NewTodos = Todos.filter(todo => todo.id !== RemoveTodo.id)

      cache.writeQuery({
        query: TODOS,
        data: { Todos: NewTodos }
      })

      // Mise à jour du cache de la requête TODO(3)
      cache.writeQuery({
        query: TODO,
        variables: { id: 3 },
        data: { Todo: null }
      })

    }
  })
```

Avouons le, cela peut vite devenir long et fastidieux si une resource apparait dans beaucoup de requêtes différentes. Il existe actuellement [une *feature request*](https://github.com/apollographql/apollo-feature-requests/issues/4) pour palier à cela et proposer un moyen de supprimer simplement un objet dans l'ensemble du cache. La fonctionnalité semble prévue dans la *roadmap* de version 3.0 du client.

En attendant, plusieurs *workaround* temporaires sont proposés dans ce même post, j'utilise [celui-ci](https://github.com/apollographql/apollo-feature-requests/issues/4#issuecomment-437041503) :

Dans le fichier instanciant votre cache, ajoutez la fonction suivante :

```javascript
/**
 * Recursively delete all properties matching with the given predicate function in the given value
 * @param {Object} value
 * @param {Function} predicate
 * @return the number of deleted properties or indexes
 */
function deepDeleteAll(value, predicate) {
  let count = 0
  if (isArray(value)) {
    value.forEach((item, index) => {
      if (predicate(item)) {
        value.splice(index, 1)
        count++
      } else {
        count += deepDeleteAll(item, predicate)
      }
    })
  } else if (isPlainObject(value)) {
    Object.keys(value).forEach(key => {
      if (predicate(value[key])) {
        delete value[key]
        count++
      } else {
        count += deepDeleteAll(value[key], predicate)
      }
    })
  }
  return count
}


/**
 * Improve InMemoryCache prototype with a function deleting an entry and all its
 * references in cache.
 */
InMemoryCache.prototype.delete = function(entry) {
  // get entry id
  const id = this.config.dataIdFromObject(entry)

  // delete all entry references from cache
  deepDeleteAll(this.data.data, ref => ref && (ref.type === 'id' && ref.id === id))

  // delete entry from cache (and trigger UI refresh)
  this.data.delete(id)
}
```

Ainsi, pour vous simplement appeler la méthode `cache.delete(entry)` dans l'`update` de votre mutation pour supprimer totalement l'objet du cache.

```javascript
client
  .mutate({
    mutation: REMOVE_TODO,
    variables: { id: todo.id },
    update: cache => cache.delete(todo),
  })
```

## Fetch policy

Pour chaque requête vous pouvez configurer l'option `fetchPolicy` afin d'indiquer à Apollo comment utiliser le cache.

* `cache-first` : c'est la valeur par defaut, le client recherche le résultat dans le cache avant de faire une requête.
* `cache-and-network` : le client retournera le contenu du cache mais fera tout de même la requête afin de le mettre à jour, permet d'avoir une réponse rapide.
* `network-only` : le client ne retournera jamais le contenu du cache pour cette requête et fera systématiquement un appel réseau.
* `cache-only` : le client ne fera aucun appel réseau et se contentera de lire le cache.
* `no-cache` : le client fera un appel réseau et ne lira pas le cache, mais au contraire de `network-only`, le résultat de la requête ne sera pas écrit dans le cache.

## Persistence

Par default, le cache Apollo utilise l'adapteur `InMemoryCache` qui comme son nom l'indique, stocke le cache en mémoire. Dans le cas d'une application web, chaque actualisation de la page ou nouvel onglet remet le cache à zero. En cas d'app native, cela se fera à chaque fermeture de l'app.

Heureusement il est possible de [persister le cache](https://github.com/apollographql/apollo-cache-persist#storage-providers) de façon non volatile en fournissant un *storage provider*. En *local storage* pour du web par exemple, ou encore dans une base *SQLite* ou en fichier pour les app natives.

Par exemple avec du *local storage* :

```javascript
import { InMemoryCache } from 'apollo-cache-inmemory';
import { persistCache } from 'apollo-cache-persist';

const cache = new InMemoryCache()

persistCache({
  cache,
  storage: window.localStorage,
})

const client = new ApolloClient({
  cache,
})
```

## Conclusion

Cet article n'est qu'un petit aperçu des possibilités offertes par le cache Apollo. Pour plus de détails, je vous conseille de lire attentivement les documentations très complètes sur la [configuration du cache](https://www.apollographql.com/docs/react/caching/cache-configuration/) et la [manipulation de cache](https://www.apollographql.com/docs/react/caching/cache-interaction/) qui ont inspiré cet article.
