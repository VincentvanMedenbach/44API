<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Core\User\User;

class AppFixtures extends Fixture
{  private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        // ...

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'the_new_password'));
        $user->setUsername("test");
        $user->setEmail("test@test.com");
        $manager->persist($user);

        $manager->flush();
        // ...
    }
}
