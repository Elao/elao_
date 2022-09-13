---
type:               "post"
title:              "Plusieurs mailer dans une application Symfony 2"
date:               "2013-10-11"
lastModified:       ~

description:        "Plusieurs mailer dans une application Symfony 2"

thumbnail:          "content/images/blog/thumbnails/mailers.jpg"
tags:               ["Symfony", "errorNotifierBundle", "SwiftMailer", "Mailer transport"]

authors:            ["jlopes"]
---

Voici une petite astuce que j'ai découvert hier et que j'ai pensé utile de partager !<!--more-->

# Problématique

**Contexte :**

*   Votre application Symfony2 utilise un service tiers pour envoyer vos e-mails (comme [Mailjet][1] par exemple)
*   Votre application a besoin d'envoyer des e-mails de notifications aux administrateurs lorsque des erreurs se produisent (Erreurs 500 par exemple)<!--more-->

Imaginez qu’une erreur se produise sur l’une de vos pages et que vous ayez des centaines d’utilisateurs en train de visualiser cette même page.Pour chaque affichage de cette dernière, vous allez recevoir un e-mail de notification.
Si ces e-mails passent par Mailjet par exemple, votre quota fondra à vue d’œil...
Au mieux vous atteindrez une limite de quota et les mails suivants seront bloqués, au pire vous ferez un énorme hors forfait qui pourra vous couter très cher...

# Solution proposée

La solution que je vous propose pour palier à cela est de créer 2 mailer utilisant 2 transports différents :

*   Les e-mails classiques destinés aux utilisateurs de votre application passeront par votre serveur SMTP
*   Les e-mails techniques destinés aux administrateurs de votre application seront envoyés via la fonction mail de votre PHP.

# Mise en œuvre de la solution

Pour cela rien de plus simple, créez plusieurs mailers dans votre app/config/config.yml :

```yaml
# Swiftmailer Configuration
swiftmailer:
    default_mailer: default
    mailers:
        default:
            transport: smtp
            host:      in.mailjet.com
            username:  mylogin
            password:  mypassword
        notifier:
            transport: mail
```


Vous remarquerez que cela aura pour effet de créer 2 services d'envoi d'e-mails que vous pourrez ensuite utiliser dans votre application :

```bash

jlopes:/Volumes/Elao/workspace/myProject ./app/console container:debug | grep mailer
mailer                                            n/a       alias for swiftmailer.mailer.default
swiftmailer.mailer                                n/a       alias for swiftmailer.mailer.default
swiftmailer.mailer.default                        container Swift_Mailer
swiftmailer.mailer.default.plugin.messagelogger   container Swift_Plugins_MessageLogger
swiftmailer.mailer.default.transport              container Swift_Transport_EsmtpTransport
swiftmailer.mailer.notifier                       container Swift_Mailer
swiftmailer.mailer.notifier.plugin.messagelogger  container Swift_Plugins_MessageLogger
swiftmailer.mailer.notifier.transport             container Swift_Transport_MailTransport
...
```


Cette astuce reste très simple mais je n'ai rien trouvé dans la documentation officielle de Symfony qui l'expliquait clairement. Elle peut se révéler très utile lorsque vous utilisez le bundle **[ErrorNotifierBundle][2]**

Merci et à bientôt !

 [1]: https://fr.mailjet.com/
 [2]: https://github.com/Elao/ErrorNotifierBundle
