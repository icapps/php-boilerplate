<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use App\Utils\ProfileHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordEncoderInterface $userPasswordEncoder, private ProfileHelper $profileHelper)
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

        //Also create a Profile for this user
        $profileClass = $this->profileHelper->getDefaultProfileClass();
        $profile = new $profileClass();
        $profile->setFirstName('john');
        $profile->setLastName('doe');
        $user->setProfileType($this->profileHelper->getDefaultProfileType());
        $manager->persist($profile);
        $manager->flush();
        $user->setProfileId($profile->getId());
        $manager->persist($user);
        $manager->flush();
    }
}
