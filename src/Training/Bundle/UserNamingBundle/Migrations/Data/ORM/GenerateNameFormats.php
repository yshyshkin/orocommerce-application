<?php

namespace Training\Bundle\UserNamingBundle\Migrations\Data\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class GenerateNameFormats extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [LoadUserNamingTypes::class];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $nameProvider = $this->container->get('training_user_naming.provider.user_full_name');

        $namingTypes = $manager
            ->getRepository(UserNamingType::class)
            ->findAll();

        foreach ($namingTypes as $namingType) {
            if (null === $namingType->getExample()) {
                $namingType->setExample($nameProvider->getFullNameExample($namingType->getFormat()));
            }
        }

        $manager->flush();
    }
}
