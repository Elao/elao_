---
type:               "post"
title:              "Migrer les mots de passe utilisateur vers une autre méthode d'encodage avec Symfony"
date:               "2017-09-12"
publishdate:        "2017-09-12"
draft:              false

description:        "Migration continue de mots de passe legacy d'une méthode d'encodage à une autre dans Symfony. Par exemple, migrer de md5 vers bcrypt."

thumbnail:          "/images/posts/thumbnails/password.jpg"
header_img:         "/images/posts/headers/password.jpg"
tags:               ["Sécurité", "Mot de passe", "Migration", "Encodage", "Symfony", "PHP"]
categories:         ["Dev", "Symfony"]

author:    "mcolin"
---

# Contexte

Si vous avez un jour travaillé sur la refonte d'une application, vous avez sûrement dû importer des données dites "legacy" provenant de l'application existante. Ces données contiennent bien souvent des comptes utilisateurs et donc des hashs de mots de passe qu'il faudra réintégrer à la nouvelle application.

Les standards de sécurité évoluent, là où hier on se contentait d'un hash md5 ou sha1, on utilise plutôt bcrypt aujoud'hui. Afin de maintenir votre application aux standards actuels, vous allez devoir migrer ces hashs de mots de passe.

# Solution

Par définition, il n'est pas possible de retrouver simplement le mot de passe à partir du hash. Vous ne pouvez donc pas simplement migrer l'ensemble de mots de passe au moment d'importer les données dans le nouveau système. La seule personne à connaitre le mot de passe en clair est l'utilisateur lui-même.

L'idée est donc de réaliser une migration continue lorsque l'utilisateur rentre son mot de passe.

Par exemple, pour une migration de mots de passe de `md5` vers `bcrypt`, lors d'une tentative de connexion :

- Si l'utilisateur n'a pas été migré, on vérifie que le mot de passe fourni correspond au hash `md5`. Si c'est le cas, on calcule le hash `bcrypt` à partir du mot de passe, puis on le stocke.
- Si l'utilisateur a déjà été migré, on vérifie le mot de passe avec le hash `bcrypt`

Ainsi, chaque utilisateur migrera son mot de passe lors de sa première connexion à la nouvelle plateforme. Une fois que tous les utilisateurs auront été migrés, nous pourront effacer complètement les hashs `md5` de la base de données et n'utiliser que `bcrypt`.

<figure style="text-align: center;">
    <a href="/images/posts/2017/password-encoding-switch.png">
        <img src="/images/posts/2017/password-encoding-switch.png" style="max-width: 600px;" alt="Logique de migration" />
    </a>
    <figcaption>Processus d'authentification</figcaption>
</figure>

# Symfony

Il est possible de réaliser une méthode d'authentification intégrant ce processus de migration dans Symfony. Pour cela vous devez implémenter une authentification personnalisée. Plusieurs solutions s'offrent à vous, de la plus complexe à la plus simple:

* [Créer un Authentication Provider](http://symfony.com/doc/current/security/custom_authentication_provider.html)
* [Créer un système d'authentification avec Guard](http://symfony.com/doc/current/security/guard_authentication.html)
* [Créer un Form Password Authenticator](http://symfony.com/doc/current/security/custom_password_authenticator.html)

Si vous utilisez un formulaire de connexion simple, de type login/password avec l'option `form_login`, la dernière solution est la plus simple. A la place d'utiliser `form_login`, nous allons utiliser `simple_form` qui fonctionne de la même façon hormis qu'il faudra lui fournir un service dédié à l'authentification grâce à la clé `authenticator`.

Ce service doit implémenter la classe [`SimpleFormAuthenticatorInterface`](http://api.symfony.com/3.0/Symfony/Component/Security/Http/Authentication/SimpleFormAuthenticatorInterface.html) qui requiert l'implémentation des trois méthodes suivantes :

* `createToken` : le formulaire est de type login/password, nous allons donc créer un `UsernamePasswordToken`.
* `supportsToken` : l'authenticator supportera les `UsernamePasswordToken`.
* `authenticateToken` : et enfin, c'est ici que nous allons mettre notre logique d'authentification.

Dans l'exemple suivant, la méthode d'encodage "legacy" est la suivante : `HASH = MD5(PASSWORD + SALT)`. Si l'application à refondre est déjà une application Symfony utilisant un encodeur de Symfony, vous pouvez le reproduire dans votre refonte et l'injecter dans votre service.

```php
<?php

namespace App\Infrastructure;

class MigrationAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder, $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManager $em)
    {
        $this->encoder = $encoder;
        $this->em      = $em;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $user = $userProvider->loadUserByUsername($token->getUsername());
        $password = $token->getCredentials();

        // The user hasn't password, it's not migrated
        if (null === $user->getPassword()) {
            // Check legacy password with legacy encoding method
            if (md5($password . $user->getLegacySalt()) === $user->getLegacyPassword()) {

                // Encode the password and migrate the user
                $encodedPassword = $this->encoder->encodePassword($user, $password);
                $user->updatePassword($encodedPassword);
                $this->em->flush($user);

                return new UsernamePasswordToken(
                    $user,
                    $user->getPassword(),
                    $providerKey,
                    $user->getRoles()
                );
            }
        } else {
            // Check password with the current encoder
            if ($this->encoder->isPasswordValid($user, $password)) {
                return new UsernamePasswordToken(
                    $user,
                    $user->getPassword(),
                    $providerKey,
                    $user->getRoles()
                );
            }
        }

        throw new BadCredentialsException('Invalid username or password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
```

Déclarez votre service :

```yaml
services:
    app.migration_authenticator:
        class: App\Infrastructure\MigrationAuthenticator
        arguments:
            - '@security.user_password_encoder.generic'
            - '@doctrine.orm.entity_manager'
```

Puis renseignez le dans la configuration de votre firewall :

```yaml
security:
    encoders:
        App\Domain\Model\User:
            algorithm: bcrypt
            cost:      12
    firewalls:
        main:
            pattern: ^/
            anonymous: ~
            simple_form:
                authenticator: app.migration_authenticator
                login_path: login
                check_path: login_check
```

Si vous avez besoin de créer un `Authentication Provider` ou d'utiliser le composant `Guard`, reportez vous à la documentation de Symfony pour savoir où integrer le processus d'authentification, mais le principe reste le même.

# Bonus

En bonus, après un certain temps, vous pourrez identifier les utilisateurs qui ne sont plus actifs, ils correspondront aux utilisateurs qui n'auront pas migré leur mot de passe.
