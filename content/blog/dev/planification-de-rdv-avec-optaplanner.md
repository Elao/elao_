---
type:           "post"
title:          "Planification de rendez-vous avec OptaPlanner"
date:           "2017-10-10"
publishdate:    "2017-10-10"
draft:          false

description:    ""

thumbnail:      "images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-teacher-agenda.png"
header_img:     "images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-logo.png"
tags:           ["Planification", "OptaPlanner"]
categories:     ["dev"]

author: "rhanna"

---

## Le contexte

Notre client, Proximum Group avec son produit [Vimeet](https://www.elao.com/fr/etudes-de-cas/vimeet/) propose à des
organisateurs d’événements une plateforme de gestion de rendez-vous B2B.

Avant l’événement les participants s’inscrivent sur la plateforme et consultent le catalogue des participants :

<p class="text-center">
    <img src="/images/posts/2017/planification-de-rdv-avec-optaplanner/vimeet-catalogue.png" alt="Catalogue Vimeet" />
</p>

Les participants demandent en rendez-vous d'autres participants, acceptent ou refusent des propositions de rendez-vous :

<p class="text-center">
    <img src="/images/posts/2017/planification-de-rdv-avec-optaplanner/vimeet-gdr.png" alt="Catalogue Vimeet" />
</p>

Avant l’ouverture de l’événement l’agenda des rendez-vous de chaque participant est généré.
Toutes les demandes de rendez-vous acceptées ne sont pas satisfaites faute de disponibilité commune entre les
participants.

Un événement dure un à plusieurs jours durant le(s)quel(s) des professionnels vont se rencontrer en rendez-vous.
La plateforme permet également de gérer d’autres types de rendez-vous comme des recruteurs qui rencontrent des candidats
pendant une journée dédiée.

## La problématique

L'événement dure un ou plusieurs jours et des créneaux de rendez-vous sont définis par l'organisateur.
Une demande de rendez-vous acceptée est transformée en rendez-vous en lui allouant __un créneau de rendez-vous__ et
__un lieu de rendez-vous__.

L'objectif de l'organisateur est __de maximiser le nombre de rendez-vous__.

L'objectif d'un participant est __d'avoir le maximum de ses demandes de rendez-vous positionnées en rendez-vous__.

À cela s'ajoutent quelques règles de gestion :

* chaque participant a un nombre limité de rendez-vous selon le forfait qu'il a choisi,
* des priorités entre les types de participants,
* priorisation du positionnement d'un rendez-vous sur le lieu attribué au participant plutôt que sur un lieu
mutualisé,
* le participant et les lieux peuvent être en indisponibilité pour certains créneaux de rendez-vous.

## Problème difficile à résoudre

Il s'agit d'un __[problème NP-complet](https://fr.wikipedia.org/wiki/Probl%C3%A8me_NP-complet)__ car le problème est
difficile à résoudre : pour avoir la solution idéale, il faudrait calculer toutes les combinaisons possibles.
Ce qui pourrait prendre des années voire une éternité.

En conséquence, il vaut mieux chercher des __solutions approchées__ en limitant le temps de calcul.

Pour cela, nous avons étudié des algorithmes comme
l'[algorithme de colonies de fourmis](https://fr.wikipedia.org/wiki/Algorithme_de_colonies_de_fourmis)
ou l'[algorithme génétique](https://fr.wikipedia.org/wiki/Algorithme_g%C3%A9n%C3%A9tique).

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/algorithmes.png"
        alt="Algorithme génétique et algorithme de colonies de fourmis"
    />
</p>

Ce sont des algorithmes difficiles à maîtriser.

Un _proof of concept_ en PHP a été codé pour positionner séquentiellement des rendez-vous puis évaluer la solution
globale. Cela a été intéressant pour comprendre comment modéliser le problème, mais la lenteur des itérations nous a
poussés à aller vers d'autres langages ou vers des librairies Open Source.

## OptaPlanner

Solution Open Source, [OptaPlanner](https://www.optaplanner.org/), est décrit comme un
__solveur de satisfaction de contraintes__.

Sous licence Apache Software et chapeauté par Red Hat, OptaPlanner est écrit en Java et [Drools](https://www.drools.org/),
un meta langage pour écrire des règles de gestion.

OptaPlanner est livré avec des exemples variés :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-examples.png"
        alt="Optaplanner exemples"
    />
</p>

Dont, l'optimisation de l'agenda des professeurs :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-teacher-agenda.png"
        alt="Optimisation agenda de profs"
    />
</p>

L'affectation des lits d'un hôpital :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-hospital.png"
        alt="Optimisation lits d'hôpital"
    />
</p>

La minimisation du trajet d'un voyageur de commerce :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-traveller.png"
        alt="Optimisation trajet du voyageur de commerce"
    />
</p>

Et même l'optimisation du plan de tables d'un mariage :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/optaplanner-wedding.png"
        alt="Optimisation du plan de table de mariage"
    />
</p>

Sous le capot, OptaPlanner implémente de nombreux algorithmes de
[construction heuristique](https://fr.wikipedia.org/wiki/Heuristique_(math%C3%A9matiques))
et de
[recherche locale](https://fr.wikipedia.org/wiki/Recherche_locale_(optimisation)).

> __Une heuristique__ est une méthode de calcul par l'exploration et qui fournit rapidement une solution acceptable.

> __Un algorithme de recherche locale__ permet de découper le problème en plus petits problèmes pour découvrir des optimums
locaux.

### Comment démarrer avec OptaPlanner ?

Notre erreur a été de créer notre problème _from scratch_. La courbe d'apprentissage d'OptaPlanner semble plutôt
une falaise à gravir par avis de tempête. La bonne idée est de partir d’un exemple proche fourni par OptaPlanner
et l’adapter petit à petit.

### Modéliser le problème

Il n'est jamais simple de modéliser un problème de planification. Le moyen d'y arriver est de définir :

- __l'Objectif__ : ici, maximiser le nombre de rendez-vous positionnés c'est à dire avec un créneau et un lieu.
- __les ressources__ : ici, les __créneaux__ de rendez-vous et les __lieux__ de rendez-vous.
- __Contraintes__ :
    - "__Hard__" : celles qui empêchent la tenue d'un rendez-vous
    - "__Soft__" : celles permettant d'optimiser le positionnement des rendez-vous

### Les annotations de OptaPlanner

<p class="text-center">
    <img src="/images/posts/2017/planification-de-rdv-avec-optaplanner/model-annotations.png" alt="Modèle" />
</p>

Voici un extrait du code de MeetingSchedule :

```java
import org.optaplanner.core.api.domain.solution.PlanningEntityCollectionProperty;
import org.optaplanner.core.api.domain.solution.PlanningScore;
import org.optaplanner.core.api.domain.solution.PlanningSolution;
import org.optaplanner.core.api.domain.solution.drools.ProblemFactCollectionProperty;
import org.optaplanner.core.api.domain.valuerange.ValueRangeProvider;

@PlanningSolution
public class MeetingSchedule {

    private List<Meeting> meetingList;
    private List<Slot> slotList;
    private List<Spot> spotList;
    private List<User> userList;
    private List<Sheet> sheetList;

    @PlanningEntityCollectionProperty
    public List<Meeting> getMeetingList() {
        return meetingList;
    }

    @ValueRangeProvider(id = "slotRange")
    @ProblemFactCollectionProperty
    public List<Slot> getSlotList() {
        return slotList;
    }

    @ValueRangeProvider(id = "spotRange")
    @ProblemFactCollectionProperty
    public List<Spot> getSpotList() {
        return spotList;
    }

    @ProblemFactCollectionProperty
    public List<User> getUserList() {
        return userList;
    }

    @ProblemFactCollectionProperty
    public List<Sheet> getSheetList() {
        return sheetList;
    }
```

Et un extrait du code de Meeting :

```java
import org.optaplanner.core.api.domain.entity.PlanningEntity;
import org.optaplanner.core.api.domain.variable.PlanningVariable;

@PlanningEntity()
public class Meeting {

    private List<Sheet> sheetList;
    private List<User> userList;

    private Slot slot = null;
    private Spot spot = null;

    @PlanningVariable(valueRangeProviderRefs = {"slotRange"}, nullable = true)
    public Slot getSlot() {
        return slot;
    }

    @PlanningVariable(valueRangeProviderRefs = {"spotRange"}, nullable = true)
    public Spot getSpot() {
        return spot;
    }
```

On peut voir que ce code comporte des annotations fournies par OptaPlanner :

- __@PlanningSolution__ : définit l'entité d'une solution optimale contenant tous les rendez-vous.
- __@PlanningEntityCollectionProperty__ : définit une collection de _PlanningEntity_.
- __@ProblemFactCollectionProperty__ : définit qu'une propriété sur une classe _PlanningSolution_ est une collection
de données qui sert au planificateur mais qui ne changent pas lors de la résolution.
- __@ValueRangeProvider__ : fournit les valeurs pouvant être utilisées dans une annotation _@PlanningVariable_.
- __@PlanningEntity__ : définit l'élément d'une solution, ici un rendez-vous (_meeting_).
- __@PlanningVariable__ : définit la variable (la ressource) que OptaPlanner attribue au PlanningEntity grâce à ses
algorithmes de construction heuristique. Ici le créneau (_slot_) et le lieu (_spot_).

### Les règles de gestion et les contraintes

__Décrire les contraintes__ de notre problème au solveur d'OptaPlanner est fait en __Drools__.
Grâce à ces règles, OptaPlanner évalue __le score d'une solution__.
L'idée est que chaque règle va permettre d'agir sur ce score, en pénalisant la solution plus ou moins fortement.

#### Contrainte "Medium"

Il s'agit de l'__objectif__ : on souhaite maximiser le nombre de rendez-vous positionnés lors d'un événement.
Le score de la solution est __pénalisée de -1__ pour chaque rendez-vous non positionné :

```java
rule "Assign every meeting"
    when
        Meeting(isNotAssigned())
    then
        scoreHolder.addMediumConstraintMatch(kcontext, -1);
end
```

#### Contrainte "Hard"

Ce sont des contraintes ne permettant pas de positionner un rendez-vous.

Prenons un exemple : le rendez-vous ne peut pas être positionné sur un créneau de rendez-vous si les participants sont
indisponibles durant ce créneau.

En langage Drools, on peut appeler une méthode du modèle :

```java
rule "Unavailability conflict"
    when
        Meeting(hasUnavailabilityConflict())
    then
        scoreHolder.addHardConstraintMatch(kcontext, -10);
end
```

et dans le modèle Meeting :

```java
@PlanningEntity()
public class Meeting {
    // ...

    public boolean hasUnavailabilityConflict() {
        if (null == slot) {
            return false;
        }

        for (User user : getUserList()) {
            List<Slot> unavailabilityList  = user.getUnavailabilityList();

            if (unavailabilityList != null) {
                for (Slot unavailability : unavailabilityList) {
                    if (slot == unavailability) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
```

Prenons un exemple un peu plus complexe :
lorsque la fiche de participant a consommé tous ses crédits de rendez-vous, il n'est pas possible de lui positionner un
nouveau rendez-vous :

```java
rule "Sheet do not have enought meetings quantity conflict"
    when
        $sheet : Sheet($possibleMeetingsQuantity : possibleMeetingsQuantity)
        accumulate(
            $meeting : Meeting(isAssigned(), sheetList contains $sheet);
            $totalMeetingBySheet : count($meeting);
            $totalMeetingBySheet > $possibleMeetingsQuantity
        )
    then
        scoreHolder.addHardConstraintMatch(kcontext, -1);
end
```

#### Contrainte "Soft"

Ce sont les contraintes qui permettent d'optimiser la satisfaction des participants ou d'optimiser l'utilisation des
ressources.

Par exemple, nous allons satisfaire équitablement chaque participant en fonction du nombre de rendez-vous possibles.
Pour cela, il faut calculer un ratio de nombre de rendez-vous positionnés sur le nombre de rendez-vous à positionner et
pénaliser de -1 par palier de 10% :

```java
rule "Satisfaction : add -1 point penalty per 10% satisfaction = meetings assigned / possibleMeetingsQuantity"
    when
        $sheet : Sheet(possibleMeetingsQuantity > 0, $possibleMeetingsQuantity : possibleMeetingsQuantity)
        accumulate(
            $meeting : Meeting(isAssigned(), sheetList contains $sheet);
            $meetingCount : count($meeting)
        )
    then
        scoreHolder.addSoftConstraintMatch(kcontext, - (int) Math.ceil(10 - 10 * (float) $meetingCount / $possibleMeetingsQuantity));
end
```

## Le solveur

Notre configuration du solveur solver-config.xml :

```xml
<?xml version="1.0" encoding="UTF-8"?>
<solver>
  <solutionClass>org.vimeet.meetings.domain.MeetingSchedule</solutionClass>
  <entityClass>org.vimeet.meetings.domain.Meeting</entityClass>

  <scoreDirectorFactory>
    <scoreDrl>org/vimeet/meetings/solver/meetingsScoreRules.drl</scoreDrl>
  </scoreDirectorFactory>

  <constructionHeuristic/>

  <localSearch>
    <termination>
      <minutesSpentLimit>4</minutesSpentLimit>
    </termination>
  </localSearch>

  <constructionHeuristic/>

  <localSearch />

  <termination>
    <minutesSpentLimit>30</minutesSpentLimit>
  </termination>
</solver>
```

Le solveur peut être configuré finement. Ici on alterne les phases de construction heuristique et la recherche
locale.
Enfin avec le paramètre _termination / minutesSpentLimit_ on fixe le temps total de calcul à 30 minutes.

On injecte au solveur nos données _meetings-not-solved.xml_ contenant notre modèle de données avec des rendez-vous
sans créneau ni lieu (_spot_ et _slot_ à null) et les ressources disponibles (créneaux, lieux, utilisateurs...) :

```java
import org.optaplanner.core.api.solver.Solver;
import org.optaplanner.core.api.solver.SolverFactory;
import org.optaplanner.meetings.common.persistence.SolutionDao;
import org.optaplanner.meetings.meetings.domain.Meeting;
import org.optaplanner.meetings.meetings.domain.MeetingSchedule;
import org.optaplanner.meetings.meetings.persistence.MeetingsDao;

import java.io.File;

public class MeetingsCliApp {

    public static void main(String[] args) {
        // Build the Solver
        SolverFactory<MeetingSchedule> solverFactory = SolverFactory.createFromXmlResource("path/to/solver-config.xml");
        Solver<MeetingSchedule> solver = solverFactory.buildSolver();

        // Load a problem
        SolutionDao<MeetingSchedule> meetingsDao = new MeetingsDao();

        // Read the input data
        MeetingSchedule unsolvedMeetingSchedule = meetingsDao.readSolution(new File("path/to/meetings-not-solved.xml"));

        // Solve the problem
        MeetingSchedule solvedMeetingSchedule = solver.solve(unsolvedMeetingSchedule);

        // Write the solution
        meetingsDao.writeSolution(solvedMeetingSchedule, new File("path/to/meetings-solved.xml"));
    }
```

## Demo

Visualisation d'une solution optimale des plannings de rendez-vous des participants d'un événement :

<p class="text-center">
    <img src="/images/posts/2017/planification-de-rdv-avec-optaplanner/planner-real-event.gif" alt="Demo" />
</p>

* _Spot_ : les lieux
* _User_ : les participants
* _Sheet_ : les rendez-vous par fiche de participation (généralement équivalente à une fiche société).

Le participant a ensuite son agenda des rendez-vous accessible sur son ordinateur ou sur mobile :

<p class="text-center">
    <img
        src="/images/posts/2017/planification-de-rdv-avec-optaplanner/vimeet-agenda-mobile.png"
        alt="Agenda utilisateur"
        style="height: 600px"
    />
</p>

## Bilan

Notre instance d'OptaPlanner pour [Vimeet](https://www.elao.com/fr/etudes-de-cas/vimeet/) a maintenant réalisé la planification de dizaines d'événements depuis début
2017. Les organisateurs ont noté en moyenne une __augmentation de 10%__ des rendez-vous positionnés par rapport à
l'ancienne application de planification des rendez-vous.

De plus, les organisateurs d’événements sont maintenant __autonomes__ pour réaliser la planification de
rendez-vous. Depuis le backoffice Vimeet, ils cliquent sur un bouton "Planifier" et quelques minutes plus tard,
les agendas de rendez-vous sont créés. L'application de planification est maintenant dépourvue d'UI et est
appelée comme une API.

Le __temps de calcul__ pour obtenir une solution acceptable est variable.
Il est fonction des ressources en créneaux et en lieux de l'événement et du nombre de demandes acceptées.
En réalité, on pourrait faire tourner le planificateur autant qu'on le souhaite. __Plus il a de temps de calcul,
plus il va tendre vers une meilleure solution.__

<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th></th>
            <th>Demandes de Rdv acceptées</th>
            <th>Rdv positionnés</th>
            <th>% transformation</th>
            <th>Temps de calcul</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Event X</th>
            <td class="text-center">630</td>
            <td class="text-center">560</td>
            <td class="text-center">89%</td>
            <td class="text-center">3 minutes</td>
        </tr>
        <tr>
            <th>Event Y</th>
            <td class="text-center">6700</td>
            <td class="text-center">5400</td>
            <td class="text-center">80%</td>
            <td class="text-center">6 heures</td>
        </tr>
    </tbody>
</table>

<p class="text-center">
    <img src="/images/posts/2017/planification-de-rdv-avec-optaplanner/stats-planifications.png" alt="Planifications" />
    <i>Les statistiques et les planifications successives réalisées pour un événement</i>
</p>

## Axes d'amélioration

- __Améliorer la vitesse du solveur__ : améliorer le modèle, ré-écrire les règles,
[benchmarker les algorithmes](https://docs.optaplanner.org/7.3.0.Final/optaplanner-docs/html_single/index.html#benchmarker)...
- __Ajouter des règles métier pour satisfaire encore plus le participant__ : par exemple à la fois diluer les rendez-vous
d’un participant sur la journée mais aussi réduire les écarts entre rendez-vous
(par exemple pas de rendez-vous en début puis en fin de journée).
- __Faire de la planification en continu__ même pendant l’événement pour positionner des rendez-vous en temps réel.

## Quand utiliser OptaPlanner ?

Lorsqu'un problème possède des __objectifs__, des __règles de gestion__ et tout cela avec des __ressources limitées__,
c'est très probablement un problème de planification auquel OptaPlanner peut répondre.
