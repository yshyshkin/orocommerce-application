<?php

namespace Training\Bundle\UserNamingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class UserNamingController extends Controller
{
    /**
     * @Route("/", name="training_user_naming_index")
     * @Template
     * @AclAncestor("training_user_naming_view")
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => UserNamingType::class,
        ];
    }

    /**
     * @Route("/view/{id}", name="training_user_naming_view", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="training_user_naming_view",
     *      type="entity",
     *      class="TrainingUserNamingBundle:UserNamingType",
     *      permission="VIEW"
     * )
     *
     * @param UserNamingType $type
     * @return array
     */
    public function viewAction(UserNamingType $type)
    {
        return [
            'entity' => $type,
        ];
    }

    /**
     * @Route("/dashboard/user-information", name="training_user_dashboard_user_information", options={"expose"=true})
     * @Template
     */
    public function dashboardUserInformationAction()
    {
        $data = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig('current_user_information');
        $data['currentUser'] = $this->getUser();

        return $data;
    }
}
