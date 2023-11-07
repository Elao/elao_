<?php

declare(strict_types=1);

namespace App\Stenope\Listener;

use App\Model\Member;
use Stenope\Bundle\ContentManagerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BuildListener implements EventSubscriberInterface
{
    private ContentManagerInterface $manager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(ContentManagerInterface $manager, UrlGeneratorInterface $urlGenerator)
    {
        $this->manager = $manager;
        $this->urlGenerator = $urlGenerator;
    }

    public function onCommand(ConsoleCommandEvent $event): void
    {
        if (null !== $event->getCommand() && $event->getCommand()->getName() !== 'stenope:build') {
            return;
        }

        $this->addTeamMemberSignatureUrls();
        $this->addLegacyTeamMemberRedirects();
    }

    private function addTeamMemberSignatureUrls(): void
    {
        // For each active member, we pre-generate their mail signature URLs,
        // so it's included in the build despite not being linked anywhere:
        /** @var Member $member */
        foreach ($this->manager->getContents(Member::class, [], ['active' => true]) as $member) {
            $this->urlGenerator->generate('team_member_mail_signature', ['member' => $member->slug]);
        }
    }

    private function addLegacyTeamMemberRedirects(): void
    {
        /** @var Member $member */
        foreach ($this->manager->getContents(Member::class, [], ['active' => true]) as $member) {
            $this->urlGenerator->generate('app_legacyteam_show', ['member' => $member->slug]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [ConsoleEvents::COMMAND => 'onCommand'];
    }
}
