<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 10; ++$i) {
            $user = new User();
            $user
                ->setFirstName('Etudiant '.$i)
                ->setLastName('Nom')
                ->setEmail('etudiant'.$i.'@email.fr')
                ->setRoles(['ROLE_USER', 'ROLE_STUDENT'])
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setPhone('0612345678')
                ->setType(User::TYPE_STUDENT);

            $manager->persist($user);
        }

        for ($j = 1; $j < 10; ++$j) {
            $user = new User();
            $user
                ->setFirstName('Etudiant '.$j)
                ->setLastName('Nom')
                ->setEmail('entreprise'.$j.'@email.fr')
                ->setRoles(['ROLE_USER', 'ROLE_COMPANY'])
                ->setCompanyName('Entreprise '.$j)
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setPhone('0612345678')
                ->setType(User::TYPE_COMPANY);

            $manager->persist($user);
        }

        $user = new User();
        $user
            ->setFirstName('Admin')
            ->setLastName('Istrateur')
            ->setEmail('admin@email.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ISCOM'])
            ->setCompanyName('Entreprise '.$j)
            ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
            ->setPhone('0612345678')
            ->setType(User::TYPE_ISCOM);

        $manager->persist($user);
        $manager->flush();
    }
}
