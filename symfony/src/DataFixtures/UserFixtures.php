<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordEncoderInterface $userPasswordEncoder)
    {
        //
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('john@doe.com');
        $user->enable();
        $user->setLanguage('nl');
        $user->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $user,
                'test123'
            )
        );
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}
