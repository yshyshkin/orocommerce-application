<?php

namespace Training\Bundle\UserNamingBundle\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;

use Doctrine\Common\Persistence\ManagerRegistry;

use Oro\Bundle\UIBundle\Event\BeforeListRenderEvent;

class UserViewNamingListener
{
    /** @var ManagerRegistry */
    private $registry;

    /** @var RequestStack */
    private $requestStack;

    /**
     * @param ManagerRegistry $registry
     * @param RequestStack $requestStack
     */
    public function __construct(ManagerRegistry $registry, RequestStack $requestStack)
    {
        $this->registry = $registry;
        $this->requestStack = $requestStack;
    }

    /**
     * @param BeforeListRenderEvent $event
     */
    public function onUserView(BeforeListRenderEvent $event)
    {
        $userId = $this->requestStack->getCurrentRequest()->get('id');
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
