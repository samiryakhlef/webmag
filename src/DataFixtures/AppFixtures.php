<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\BlogPost;
use App\Entity\Categorie;
use App\Entity\Newsletter;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // constructeur pour récupérer la méthode UserPawwordHasherInterface
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {

        // je créer les fixtures de mes utilisateurs

        // j'instancie Faker
        $faker = \Faker\Factory::create('fr_FR');
        // je crée 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {

            //j'appelle la classe user
            $user = new User();

            // je set les attributs de l'utilisateur
            $user->setEmail($faker->email)
                ->setRoles(["ROLE_USER"])
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setPseudo($faker->userName)
                ->setSocial($faker->url)
                ->setContribution($faker->numberBetween(0, 100))
                ->setAPropos($faker->text);

            //cryptage du mot de passe
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'password'));

            // je persiste les fausses données
            $manager->persist($user);
        }
        $admin = new user();
        $admin->setEmail('testing@gmail.com')
            ->setRoles(["ROLE_ADMIN"])
            ->setNom('yakhlef')
            ->setPrenom('samir')
            ->setpseudo('rayzaan')
            ->setSocial($faker->url)
            ->setContribution($faker->numberBetween(0, 100))
            ->setAPropos($faker->text);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'yakhlef'));
        $manager->persist($admin);
        //je créer les fixtures de l'entités Blogpost//

        //je créer une boucle de 10 blogposts
        for ($blog = 0; $blog <= 10; $blog++) {

            //je creer une variable date pour le datetime immutable
            $date = new \DateTime("2014-06-20 11:45 Europe/London");
            $immutable = DateTimeImmutable::createFromMutable($date);

            //j'instancie faker
            $faker = \Faker\Factory::create('fr_FR');

            //j'appelle ma classe BlogPost
            $blogpost = new BlogPost();

            //je set les attributs
            $blogpost->setTitre($faker->sentence)
                ->setContenu($faker->text)
                ->setAuteur($faker->name)
                ->setSlug($faker->slug(rand(1, 10)))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime('d_m_Y H:i:s')))
                ->setUser($user);

            //je persiste les données
            $manager->persist($blogpost);
        }

        //je créer les fixtures de l'entités Articles//

        //je créer une boucle de 10 articles
        for ($art = 0; $art <= 10; $art++) {

            //je rappelle la classe Article
            $article = new Article();

            //je set les attributs
            $article->setTitre($faker->sentence)
                ->setContenu($faker->text)
                ->setAuteur($faker->name)
                ->setSlug($faker->slug(rand(1, 10)))
                ->setFile('Yadelair1.jpg')
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime('d_m_Y H:i:s')))
                ->setUser($user)
                ->getVideoFile('https://www.youtube.com/watch?v=EArJ_CMlig8');

            //je persiste les données
            $manager->persist($article);
        }

        // je créer la fixtures de l'entités catégorie.

        $defaultCategories = [
            [
                "label" => "Initiative",
                "slug"  => "initiative",
                "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea, eos!"
            ],
            [
                "label" => "Bien-Être",
                "slug" => "bien-etre",
                "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea, eos!"
            ],
            [
                "label" => "A La Une",
                "slug" => "a-la-une",
                "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea, eos!"
            ]
        ];

        foreach ($defaultCategories as $defaultCategorie) {
            $categorie = new Categorie();
            $categorie
                ->setNom($defaultCategorie["label"])
                ->setSlug($defaultCategorie["slug"])
                ->setDescription($defaultCategorie["description"])
                ->setInMenu(true);
            $manager->persist($categorie);
        }

        // je créer une boucle de 8 catégories
        for ($c = 0; $c <= 8; $c++) {

            // j'appelle la classe Categorie
            $categorie = new Categorie();

            //je set les attributs
            $categorie
                ->setNom($faker->word(3, true))
                ->setDescription($faker->sentence(3, true))
                ->setSlug($faker->slug())
                ->setInMenu(false);

            //je persiste les données
            $manager->persist($categorie);

            //A l'intérieur de la boucle catégorie je créer une boucle de 4 articles

            //je créer une boucle de 4 articles
            for ($art = 0; $art <= 4; $art++) {

                //je rappelle la classe Article
                $article = new Article();

                //je set les attributs
                $article->setTitre($faker->sentence)
                    ->setContenu($faker->text)
                    ->setAuteur($faker->name)
                    ->setSlug($faker->slug(rand(1, 10)))
                    ->setFile('Yadelair1.jpg')
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime('d_m_Y H:i:s')))
                    ->setUser($user)
                    ->addCategorie($categorie);

                //je persiste les données
                $manager->persist($article);
            }
        }
            //je boucles de 4 inscription à la newsletter
            for ($newsletter = 0; $newsletter <= 4; $newsletter++) {
                //j'instancie la classe newsletter
                $newsletter = new Newsletter();
                //je set les attributs
                $newsletter->setEmail($faker->email)
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime('d_m_Y H:i:s')));
            }
            // je flush les données (envoi dans la BDD)
            $manager->flush();
    }
}
