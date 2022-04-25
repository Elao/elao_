---
type:               "post"
title:              "Feedback on a side-effect with Symfony 2.2, subdomains and sessions"
date:               "2013-02-28"
lastModified:       ~
lang:               "en"

description:        "Feedback on a side-effect with Symfony 2.2, subdomains and sessions"

thumbnail:          "content/images/blog/thumbnails/turtle.jpg"
banner:             "content/images/blog/headers/php_elao_code.jpg"
tags:               ["Symfony", "PHP"]
categories:         ["Dev", "Symfony", "PHP"]

authors:            ["tbessoussa"]
---

This is a small feedback regarding the new Symfony 2.2 feature which makes the subdomains handling easier. By the way you can see the official documentation about <a style="text-align: justify;" href="http://symfony.com/doc/master/components/routing/hostname_pattern.html" target="_blank">routing & subdomains here</a>.

### Scenario
With the support of subdomains in the Symfony 2.2 routing component, you're all excited and decide to take advantage of that feature.

### Problem 1
Once you've opened one or more dedicated routes involving a subdomain, your users keep complaining about your application asking them to re-auth although they did it 5 minutes ago. Yeah, that's right, the session isn't shared across main domain and the subdomain.

**Cause :** You forgot to set the path of the session cookie to a value that allows session sharing for all your subdomains too. By default, the value of *cookie_domain* takes the current domain from `$_SERVER` superglobal (which will in most case will output www.my-domain.com).

**Solution :** Add to your config.yml under the *session* key `cookie_domain : .my-domain.com`. Just see the gist below for the example. If you use the `remember_me` feature, don't forget to change the domain value of `security.yml` (see the 2nd gist).

### Problem 2
Once you've deployed the fix on your dev-preprod-prod server (choose the right stage according whether you like risks or not), you keep trying to login with your credentials without being actually logged in (and of course, no errors are logged).

**Cause :** If you open any tool which let us visualize the cookies stored by a website, you'll figure out soon that you have 2 cookies `PHPSESSID` with differents values in the domain (one with www.my-domain.com and the other with .my-domain.com).

**Solution :** Change the name of the session cookie. It will prevent conflicts which will lead to a silent login failure.

There you are, with your subdomain ready application. Enjoy.

```yaml

framework:
    esi:             { enabled: true }
    translator:      { fallback: %locale% }
    secret:          %secret%
    router:
        resource: "%kernel.root_dir%/config/routing.yml"
        strict_requirements: %kernel.debug%
    form:            ~
    csrf_protection: ~
    validation:      { enable_annotations: true }
    templating:
        engines: ['twig']
        #assets_version: SomeVersionScheme
    default_locale:  "%locale%"
    trusted_proxies: ~
    session:
        cookie_lifetime: 0
        save_path: %kernel.root_dir%/var/sessions
        cookie_domain: .my-domain.com
        name: SFSESSID
    fragments:       ~
```


```yaml

main:
    remember_me:
        key:      "%secret%"
        lifetime: 31536000
        path:     /
        domain:   .my-domain.com # Defaults to the current domain from $_SERVER
```
