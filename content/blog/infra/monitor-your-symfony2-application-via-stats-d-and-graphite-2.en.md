---
type:               "post"
title:              "Monitor your Symfony2 application via Stats.d and Graphite (2/2)"
date:               "2012-11-23"
publishdate:        "2012-11-23"
draft:              false
slug:               "monitor-your-symfony2-application-via-stats-d-and-graphite-2"
description:        "Monitor your Symfony2 application via Stats.d and Graphite Part. 2"

thumbnail:          "/images/posts/thumbnails/rocket.jpg"
header_img:         "/images/posts/headers/minions.jpg"
tags:               ["Monitoring", "symfony", "Statd", "infra", "ops"]
categories:         ["Infra", "Monitoring"]

author_username:    "tbessoussa"
---

*This article is the last part of[ Install Stats.d / Graphite on a debian server in order to monitor a Symfony2 application ( 1/2 )](/en/infra/feedback-monitor-your-symfony2-application-via-stats-d-and-graphite/).*

<img src="/en/images/posts/2012/users.png" alt="graphite" class="outside-left">
Last week, we saw that [StatsDClientBundle](https://github.com/liuggio/StatsDClientBundle) provides great metrics for your application,. But what if I want to monitor my own things in my application ? During the last part of this tutorial, we will see **how to monitor our own application events**. I'll assume you did the first part of the tutorial.

In my personnal application (named Seek Team), gamers can purchase a premium account in order to unlock additional features on the website.

I wanted to display on my graphite dashboard, which looked like the screen on the left, the statistics on how many premium accounts were ordered, cancelled, activated or finished.

So here's what I did :

{{< highlight php >}}
<?php

namespace SeekTeam\PremiumBundle\Controller;

use SeekTeam\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

use SeekTeam\PremiumBundle\Entity\Premium\Order;
use SeekTeam\PremiumBundle\Event\Events;

/**
 * Default Controller of the PremiumBundle
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}/payment/{id}/cancel", name="payment_cancel", requirements={"_locale" = "en|fr"})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function cancelAction(Order $order)
    {
        $order->setStatus(Order::STATUS_CANCEL);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush($order);

        $this->get('event_dispatcher')->dispatch(Events::PREMIUM_CANCEL, new GenericEvent($order));

        $this->setFlash('error', 'premium.order.cancel');

        return $this->redirect($this->generateUrl('user_profile') . '#premium');
    }

    /**
     * @Route("/{_locale}/payment/{id}/complete", name="payment_complete", requirements={"_locale" = "en|fr"})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function completeAction(Order $order)
    {
        // Lot of business logic I removed for the tutorial purpose
        $order->setStatus(Order::STATUS_VALID);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush($order);

        $this->getRepository('SeekTeamPremiumBundle:Premium')->updatePremium($order);

        $this->get('event_dispatcher')->dispatch(Events::PREMIUM_SUCCESS, new GenericEvent($order));

        $this->setFlash('success', 'premium.order.success');

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * Returns the EntityManger
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
{{< /highlight >}}

{{< highlight php >}}
<?php

namespace SeekTeam\PremiumBundle\Event;

final class Events
{
    const PREMIUM_START   = 'gamercertified.premium.start';
    const PREMIUM_SUCCESS = 'gamercertified.premium.success';
    const PREMIUM_CANCEL  = 'gamercertified.premium.cancel';
    const PREMIUM_ERROR   = 'gamercertified.premium.error';
}
{{< /highlight >}}

The following code is pretty simple. In the Controller functions, we dispatch 2 events named ```Events::PREMIUM_CANCEL``` and ```Events::PREMIUM_SUCCESS``` using the <a href="http://symfony.com/doc/current/components/event_dispatcher/introduction.html" target="_blank">EventDispatcher</a> and a <a href="http://symfony.com/doc/master/components/event_dispatcher/generic_event.html" target="_blank">GenericEvent</a> whether the payment was complete or not.

Next thing to do, is to create & plug our listener and tell him to listen to those events.

{{< highlight php >}}
<?php

namespace SeekTeam\PremiumBundle\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

use SeekTeam\PremiumBundle\Event\Events;

class StatsListener implements EventSubscriberInterface
{
    protected $statsdFactory;
    protected $statsdClient;

    public function __construct($statsdFactory, $statsdClient)
    {
        $this->statsdFactory = $statsdFactory;
        $this->statsdClient  = $statsdClient;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::PREMIUM_SUCCESS => 'onPremiumSuccess',
            Events::PREMIUM_START   => 'onPremiumStart',
            Events::PREMIUM_CANCEL  => 'onPremiumCancel',
            Events::PREMIUM_ERROR   => 'onPremiumError',
        );
    }

    public function onPremiumSuccess(GenericEvent $event)
    {
        $data = $this->statsdFactory->createStatsDataIncrement('premium.success');
        $this->statsdClient->send($data);
    }

    public function onPremiumCancel(GenericEvent $event)
    {
        $data = $this->statsdFactory->createStatsDataIncrement('premium.cancel');
        $this->statsdClient->send($data);
    }

    public function onPremiumStart(GenericEvent $event)
    {
        $data = $this->statsdFactory->createStatsDataIncrement('premium.start');
        $this->statsdClient->send($data);
    }

    public function onPremiumError(GenericEvent $event)
    {
        $data = $this->statsdFactory->createStatsDataIncrement('premium.error');
        $this->statsdClient->send($data);
    }
}
{{< /highlight >}}

You have noticed in the constructor the presence of 2 services that we need in order to send to Stats.d the data. Each time you'll add a "." to your stats category (in the example I used *premium.success*, it'll create a subfolder in your graphite dashboard).

So let's inject them via the <a href="http://symfony.com/doc/2.0/components/dependency_injection/introduction.html" target="_blank">DI</a>.

{{< highlight xml >}}
<service id="seek_team_premium.contact.listener" class="SeekTeam\PremiumBundle\Event\Listener\StatsListener">
     <tag name="kernel.event_subscriber" />
     <argument type="service" id="liuggio_stats_d_client.factory" />
     <argument type="service" id="liuggio_stats_d_client.service" />
</service>
{{< /highlight >}}

And it's done, if you go on your graphite dashboard, you can now see the new category called "premium".
