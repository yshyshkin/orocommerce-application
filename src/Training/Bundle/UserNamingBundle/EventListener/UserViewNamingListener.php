<?php

namespace Training\Bundle\UserNamingBundle\EventListener;

use Doctrine\Common\Persistence\ManagerRegistry;

use Oro\Bundle\SecurityBundle\SecurityFacade;
use Oro\Bundle\UIBundle\Event\BeforeListRenderEvent;

class UserViewNamingListener
{
    /** @var ManagerRegistry */
    private $registry;

    /** @var SecurityFacade */
    private $securityFacade;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry,
        SecurityFacade $securityFacade
    ) {
        $this->registry = $registry;
        $this->securityFacade = $securityFacade;
    }

    /**
     * @param BeforeListRenderEvent $event
     */
    public function onUserView(BeforeListRenderEvent $event)
    {
        if (!$this->securityFacade->isGranted('training_user_naming_info')) {
            return;
        }

        $userId = $event->getEntity() ? $event->getEntity()->getId() : null;
        if (!$userId) {
            return;
        }

        $user = $this->registry->getManagerForClass('OroUserBundle:User')
            ->getRepository('OroUserBundle:User')
            ->find($userId);
        if (!$user) {
            return;
        }

        $template = $event->getEnvironment()->render(
            'TrainingUserNamingBundle:User:namingData.html.twig',
            ['entity' => $user]
        );
        $subBlockId = $event->getScrollData()->addSubBlock(0);
        $event->getScrollData()->addSubBlockData(0, $subBlockId, $template);
    }
}
