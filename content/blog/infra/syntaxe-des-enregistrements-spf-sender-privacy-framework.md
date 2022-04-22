---
type:               "post"
title:              "Syntaxe des enregistrements SPF (Sender Privacy Framework)"
date:               "2009-12-05"
lastModified:       ~

description:        "Syntaxe des enregistrements SPF, cas d'utilisation et options de configuration."

thumbnail:          "content/images/blog/thumbnails/mail_bottle.jpeg"
tags:               ["Linux", "Tips", "SPF"]
categories:         ["Infra", "Linux"]

authors:            ["gfaivre"]

---

Dans cet article je vais tenté d'expliquer comment fonctionne actuellement le SPF pour nos emails, mais également de détailler au maximum les différentes options que fournit le framework afin de créer nos propres enregistrements. L'ensemble de ces options peuvent être retrouvées sur le site officiel (anglais): <a href="http://www.openspf.org/SPF_Record_Syntax" title="OpenSPF">OpenSPF.org</a>

Le SPF est une spécification publiée le 28 Avril 2006, pour la RFC c'est <a href="http://www.openspf.org/RFC_4408" title="RFC_4408">ici</a>

Ce qui suit est une traduction du manuel officiel que l'on peut trouver sur le site ci-dessus: Les noms de domaines définissent 0 ou plusieurs mécanismes. Ces mécanismes peuvent être utilisés pour décrire le paramétrage des hôtes désignés comme serveurs de mail sortant, c'est à dire autorisés à envoyer des emails pour le domaine concerné.


<a href="http://www.openspf.org/SPF_Record_Syntax#all">all</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#ip4">ip4</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#ip6">ip6</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#a">a</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#mx">mx</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#ptr">ptr</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#exists">exists</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#include">include</a>


Chaque domaine peut également définir des "modificateurs". Chacun d'entre eux ne peut apparaitre **qu'une seule fois** !

<a href="http://www.openspf.org/SPF_Record_Syntax#redirect">redirect</a> | <a href="http://www.openspf.org/SPF_Record_Syntax#exp">exp</a>

### Les mécanismes

Les mécanismes peuvent être préfixer par l'un de ces quatres "qualificateurs" :

- "+" Pass (A passé la vérification)
- "-" Fail (Echec)
- "~" SoftFail (Echec non fatal)
- "?" Neutral (Neutre)

* Si un mécanisme abouti, la valeur de son qualificateur est utilisée. Le qualificateur par défaut est "+" (Pass).

```
Par exemple: "v=spf1 -all" "v=spf1 a -all" "v=spf1 a mx -all" "v=spf1 +a +mx -all"
```

Les mécanismes sont évalués dans l'ordre :

- Si aucun mécanisme ou modificateur ne correspond, la réponse par défaut est "Neutral" (Neutre). <br /> Si un domaine n'a pas d'enregistrement SPF, le résultat est "None" (Aucun).
- Si un domaine renvoi une erreur temporaire pendant la lecture DNS, vous aurez la réponse "TempError" (Erreur temporaire), elle est aussi appellée "error" dans les anciennes documentations.
- Si une erreur de syntaxe ou d'évaluation apparait (par exemple le domaine renvoi un mécanisme non reconnu) le résultat est "PermError" (Erreur permanente, autrefois appellée "Unknow" (inconnue)).

**L'évaluation d'un enregistrement SPF peut retourner l'un de ces résultats :**

<table width="100%" border="1" align="center">
  <tr>
    <td>Résultat</td>
    <td>Explication</td>
    <td>Action résultante</td>
  </tr>
  <tr>
    <td>Pass</td>
    <td>L'enregistrement SPF a désigné l'hôte comme autorisé à envoyer</td>
    <td>Accepté</td>
 </tr>
  <tr>
    <td>Fail</td>
    <td>L'enregistrement SPF a désigné l'hôte comme non autorisé à envoyer</td>
    <td>Rejeté</td>
 </tr>
  <tr>
    <td>SoftFail</td>
    <td>L'enregistrement SPF a désigné l'hôte comme non autorisé à envoyer mais est en cours de transition</td>
    <td>Accepté mais relevé</td>
 </tr>
  <tr>
    <td>Neutral</td>
    <td>L'enregistrement SPF a explicitement renvoyé que rien ne pouvait être dit à propos de la validité de la requête</td>
    <td>Accepté</td>
 </tr>
  <tr>
    <td>None</td>
    <td>Le domaine n'a pas d'enregistrement SPF ou alors l'enregistrement SPF n'évalue pas un résultat</td>
    <td>Accepté</td>
 </tr>
  <tr>
    <td>PermError</td>
    <td>Une erreur permanente a été renvoyée (badly formatted SPF record). (Enregistrement SPF mal formaté)</td>
    <td>Non spécifié</td>
 </tr>
  <tr>
    <td>TempError</td>
    <td>Une erreur passagère (temporaire) est arrivée</td>
    <td>Accepté ou rejeté</td>
  </tr>
</table>

### Le mécanisme "all"

Ce mécanisme correspond toujours, on le retrouve habituellement à la fin de l'enregistrement.

**Exemples:**

```
"v=spf1 mx -all"
```
*Autorise les noms de domaine de type MX à envoyer des mail pour le domaine, interdit tous les autres.*

```
"v=spf1 -all"
```

*Le domaine n'envoie pas du tout d'email.*

```
"v=spf1 +all"
```

*Le propriétaire du domaine considère que le SPF est inutile ou alors ne s'en préoccupe pas.*

### Le mécanisme "ip4"

```
ip4:
ip4:/
```

L'argument correspondant au mécanisme "ip4" est une d'adresse de réseau IPv4. S'il n'y a pas de "prefix-length" fourni, /32 est considéré par défaut.

**Exemple :**

```
"v=spf1 ip4:192.168.0.1/16 -all"
```
*Autorise toutes les adresses IP comprisent entre 192.168.0.1 et 192.168.255.255.*

### Le mécanisme "ip6"

```
ip6:
ip6:/
```

L'argument correspondant au mécanisme "ip6" est une d'adresse de réseau IPv6. S'il n'y a pas de "prefix-length" fourni, /128 est considéré par défaut.

**Exemple :**

```
"v=spf1 ip6:1080::8:800:200C:417A/96 -all"
```

*Autorise toutes les adresse IPv6 comprise entre 1080::8:800:0000:0000 et 1080::8:800:FFFF:FFFF.*

```
"v=spf1 ip6:1080::8:800:68.0.3.1/96 -all"
```
*Autorise toutes les adresse IPv6 comprise entre 1080::8:800:0000:0000 et 1080::8:800:FFFF:FFFF.*

### Le mécanisme "a"

```
a
a/
a:
a:/
```

Tous les enregistrement de type A pour le domaine sont testés. Si l'IP du client est trouvée parmis elles, le mécanisme correspond. Si le domaine n'est pas spécifié, le domaine courant est utilisé. L'enregistrement A doit correspondre exactement à l'adresse IP du client, sans quoi le `prefix-length` est fourni, auquel cas chaque IP retournée par la recherche A sera étendue à son préfix <a href="http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing" title="CIDR">CIDR</a> correspondant, et l'IP du client sera demandée à l'intérieur de ce sous-réseau.

**Exemple :**
```
"v=spf1 a -all"
```
Le domaine courant est utilisé.
```
"v=spf1 a:example.com -all"
```
Equivalent si le domaine courant est example.com.
```
"v=spf1 a:mailers.example.com -all"
```

Peut-être que example.com a choisi de lister explicitement tous les serveurs de courrier sortant dans un enregistrement A special sous mailers.example.com.

```
"v=spf1 a/24 a:offsite.example.com/24 -all"
```

Si example.com est résolu vers 192.0.2.1, la classe C entière de 192.0.2.0/24 sera recherchée pour l'IP du client. De même pour offsite.example.com. Si plus d'un enregistrement de type A a été retourné, chacun d'entre eux sera étendu au sous réseau <a href="http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing" title="CIDR">CIDR</a>.

### Le mécanisme "mx"

```
mx
mx/
mx:
mx:/
```

L'ensemble des enregistrements A pour tous les enregistrements MX pour le domaine sont testés dans l'ordre de leur priorité. Si l'IP du client est trouvée parmis eux, ce mécanisme correspond. Si le domaine n'est pas spécifié alors le domaine courant est utilisé. L'enregistrement A doit correspondre exactement à l'adresse IP cliente, sans quoi le `prefix-length` est fourni, auquel cas chaque IP retournée par la recherche A sera étendue à son préfix <a href="http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing" title="CIDR">CIDR</a> correspondant, et l'IP du client sera demandée à l'intérieur de ce sous-réseau.

**Exemples :**

```
"v=spf1 mx mx:deferrals.domain.com -all"
```

Peut être qu'un domaine a envoyé un mail via ce serveur MX ainsi qu'avec un autre ensemble de serveur dont le rôle est de retenter l'envoi pour les domaines dont la distribution a été différée.

```
"v=spf1 mx/24 mx:offsite.domain.com/24 -all"
```

Peut être qu'un serveur MX de domaine à reçu un mail sur une adresse IP, mais a envoyé le mail sur une adresse IP différente mais proche.
