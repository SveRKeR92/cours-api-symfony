<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setName('Task ' . $i);
            $task->setDescription('Description ' . $i);
            $task->setDone(rand(0, 1));
            $manager->persist($task);
        }

        for($j = 1; $j <= 3; $j++){
            $user = new User();
            $user->setEmail('mail'.$j.'@mail.com');
            $user->setRoles([
                'ROLE_ADMIN'
            ]);
            $user->setApiKey(md5(random_bytes(16)));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
