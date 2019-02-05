<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\DataFixtures\Faker\MyProvider;
use App\Service\Slugger;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Tag;

class AppFixtures extends Fixture
{
    private $encoder;
    private $slugger;

    public function __construct(UserPasswordEncoderInterface $encoder, Slugger $slugger)
    {
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function randAuthor()
    {
        return $this->getDoctrine()->getRepository(User::class)->findAll();
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('fr_FR');

        $generator->addProvider(new MyProvider($generator));

        // Création des Roles
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setLabel('Administrateur');

        $roleModerator = new Role();
        $roleModerator->setName('ROLE_MODERATOR');
        $roleModerator->setLabel('Modérateur');

        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $roleUser->setLabel('Membre');
        
        $manager->persist($roleAdmin);
        $manager->persist($roleModerator);
        $manager->persist($roleUser);
        
        // Création des Users d'essai!
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@faqoclock.fr');
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setPasswordConfirm($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setRole($roleAdmin);

        $userModerator = new User();
        $userModerator->setUsername('moderateur');
        $userModerator->setEmail('moderateur@faqoclock.fr');
        $userModerator->setPassword($this->encoder->encodePassword($userModerator, 'moderateur'));
        $userModerator->setPasswordConfirm($this->encoder->encodePassword($userModerator, 'moderateur'));
        $userModerator->setRole($roleModerator);
        
        $userUser = new User();
        $userUser->setUsername('user');
        $userUser->setEmail('user@faqoclock.fr');
        $userUser->setPassword($this->encoder->encodePassword($userUser, 'user'));
        $userUser->setPasswordConfirm($this->encoder->encodePassword($userUser, 'user'));
        $userUser->setRole($roleUser);
        
        $manager->persist($userAdmin);
        $manager->persist($userModerator);
        $manager->persist($userUser);
    
        // Création de 25 users "landa"
        for ($i = 1; $i < 25; $i++) {
            $userLanda = new User();
            $userLanda->setUsername($generator->unique()->firstName());
            $userLanda->setEmail('user'.$i.'@faqoclock.fr');
            $userLanda->setPassword($this->encoder->encodePassword($userLanda, 'user'));
            $userLanda->setPasswordConfirm($this->encoder->encodePassword($userLanda, 'user'));
            $userLanda->setRole($roleUser);
            $manager->persist($userLanda);
            $manager->flush();
            $listUser[] = $userLanda;
        }

        // Création de 75 questions
        for ($i = 0; $i < 75; $i++) {
            $question = new Question();
            $question->setTitle($generator->unique()->questionTitle());
            $question->setAuthor($listUser[array_rand($listUser)]);
            $question->setSlug($this->slugger->slugify($question->getTitle()));
            $manager->persist($question);
            $manager->flush();
            $listQuestion[] = $question;
        }

        // Céation de 200 réponses
        for ($i = 0; $i < 200; $i++) {
            $reponse = new Reponse();
            $reponse->setBody($generator->text($maxNbChars = (rand(50, 1000))));
            $reponse->setAuthor($listUser[array_rand($listUser)]);
            $reponse->setQuestion($listQuestion[array_rand($listQuestion)]);
            $manager->persist($reponse);
            $manager->flush();
        }

        // Création des tags
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $tag->setName($generator->unique()->questionTag());
            $tag->setBackgroundColor($generator->hexcolor());
            $tag->setTextColor($generator->colorName());
            $tag->setSlug($this->slugger->slugify($tag->getName()));
            $manager->persist($tag);
            $manager->flush();
            $listTag[] = $tag;
        }
       
        foreach($listQuestion as $question) {
            shuffle($listTag);

            // Random pour choisir le nombre de Tag par Question
            $randTag = rand(1, 3);
            for ($i = 0; $i < $randTag; $i++) {
                $question->addTag($listTag[$i]);                
            }
        } 
        
        $manager->flush(); 
    }
}
