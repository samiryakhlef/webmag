# Documentation du site yadelair

## yadelair

yadelair est un site de type webmagazine "webzine", permettant après inscription sur le site de publier ses oeuvres "photos ou videos".

### Environnement de développement

### Pré-requis

    *Symfony 6
    *PHP 8.1
    *Symfony CLI


    Vous pouvez vérifier les pré-requis avec la commande:

symfony check:requirements

#### Mise en place d'un CI "Intégration continue" avec github actions

    J'ai choisis de controler les dépendances de composer avec un plugin d'intégration continu

    *Pour la gestion du clean code j'utilise un autre plugin "static-code-analysis"avec inclus:
        -PHPStan
        -Post run action'gestion de mon github'

    *Pour une gestion global du framwork Symfony j'utilise
        -Symfony-tests (controle du dossier ".env",controle du repo, controle de l'authentification, lance les tests de PHPUNIT...)

### lancer des tests avec PHPunit:

    *commande pour lancer les tests:

         php bin/phpunit --testdox
                    ou
        CTR+MAJ+P (raccourcis clavier avec visualstudiocode)

### installation du webpack encore

    *commande d'installation:

        yarn install

# configuration webpack encore

Pour créer les éléments, exécutez la commande suivante si vous utilisez le gestionnaire de packages Yarn :
    *installation du bundles:
    composer require symfony/webpack-encore-bundle

    installez Encore dans le projet via Yarn: 
    *yarn add @symfony/webpack-encore --dev

    installer bootstrap 5:
    *yarn add bootstrap --dev


\*compiler automatiquement les assets sans les recharger:

        yarn encore dev --watch

    *deployer ou mettre à jour un build:

        yarn build

# intalation et configuration du sass-Loader

    Pour utiliser Sass, renommez le app.css fichier app.scsset mettez à jour l' import instruction
        *redémarrer Encore avec la commande:

            -yarn add sass-loader@^12.0.0 sass --dev
                et
            -yarn encore dev --watch

# Création du HomeController

    Pour obtenir une liste de toutes les routes de votre système avec "debug:router", utilisez la commande:

        php bin/console debug:router

    *vous pourrez créer un controller avec la commande:

        symfony console make:controller 'Nomducontroller'

# Mise place des vues avec twig

    *lancer la commande:
        composer require twig
>

    * installation des dépendences d'un thème bootstrap dans le fichier:
    -package.json
        -dependencies
    *lancer la commande d'installation avec:
        yarn run build
    *lancer la commande:
    composer require twig/string-extra (extension qui sera utile plutart pour la méthode truncate)

## création du Homecontroller

commande pour créer un controller:
 *symfony console make:controller HomeController

## Création des entitées
    symfony console make:user ( pour les utilisateurs )

    symfony console make:entity (pour les entitées )

    je creer un fichier de migration
        *php bin/console make:migration
    
    je pousse mon fichier migration en base de données 
        *php bin/console doctrine:migration:migrate

    commande de passage en force:
        *symfony console d:s:u --force
## instalation du thème blogzine 1.1.0
    creation d'un fichier gulpfile.js
    instalation de gulp avec la commande 
        *sudo npm install gulp-cli -g (installation en global)
    instalation du module NPM avec la commande:
        *npm install
    import du fichier style.scs et functions.js puis lancer la commande:
        *npm run build ou *yarn run build
    copie du templates souhaité par le client dans mon html.twig.

## mise en place du front
Mise en place de la navbar et du footer dans un dossier templates/base.
mise en place de la home page avec les modules du thèmes Blogzine 1.1.0

# création des datafixtures
    instalation de :DoctrineFixtureBundle avec la commande.
        *composer require --dev orm-fixtures
    exemple de l'écriture d'une fixtures

Dans le dossier src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // je fais une boucle de 20 produits
        for ($i = 0; $i <= 20; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setPrice(mt_rand(10, 100));
            $manager->persist($product);
        }
        $manager->flush();
    }
}


    -une fois les fixtures ecrites, il est temps de les faires migrer en base de données avec la commande:
        *php bin/console doctrine:fixtures:load
        ou 
        *php bin/console doctrine:fixtures:load --no-interaction (evite la question:'êtes vous sûr de vouloir exécuter cette action')

## mise en place des données dans les vues 
    -créer une requete dans le Repository articles afin d'afficher les données de nos datafixtures dans les vues.
    - dans le controller correspondant je rapelle la fonction et je l'envoi dans ma vues.
    - Dans ma vue je creer un boucle pour recupérer ma methode et je les rappelle aux places correspondantes mes entitées.

## Création des controlleurs de navigation par catégorie
    avec le maker je creer tout mes controller de navigation
        - symfony console make:controller
    je creer une requete avec le query builder pour récuperer mes catégories de chaque articles
        -    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findAllArticle(Categorie $categorie): array
    {
        return $this->createQueryBuilder('p')
            ->where(':categorie MEMBER OF p.categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    j'envoie dans ma vues la categorie corresspondante que je redirige par un slug personnaliser

## pagination des articles

mise en place de la pagination des articles avec le bundle knp paginator:
installation du bundle avec la commande suivante:
        -composer require knplabs/knp-paginator-bundle

configuration de la pagination dans chaque controller
mise en place du bouton de pagination dans chaque vue 

## mise en place des articles dans les vues par le slug et par categories
 mise en place des routes en récuperant le slug de chaque articles par catégories 

## création d'un formulaire de contact pour contacter l'administrateur du site 
avec la commande:
    -symfony console make:entity 
    je créer l'entité contact avec:
    *le nom
    *l'email
    *le message
    *la date de création
    *le isSend (status)
    je lance la commande:
    -symfony console make:migration 
    pour gener le fichier de migration
    -symfony console doctrine:migration:migrate
    pour le créer en base de données

    je créer un controller contact avec la commande 
        -symfony console make:controller 

    dans mon dossier form/ContactTypeForm 
    je définis le type des champs et je retire les champs createdAt et isSend que je gererai dans un dossier service afin de ne pas 
    surcharger l'ecriture de code dans le controler
    je créer une methode qui me permet de verifier et valider mon formulaire 
    et je créer un message personnalisé avec la methode (addFlash)
    dès lors mon formulaire et pret à l'utilisation 

## création d'un systeme de mailing en ligne de commande 
pour des raison pratique et pour simplifier le projet je souhaite mettre en place un systeme de mailing de reponse automatisé en ligne de commande 
je creer un fichier command dans le dossier SRC
je creer un fichier SendContactCommand et j'ecris une methode qui me permet de definir une command qui 
récupère tout les emails en attente d'envoi avec
*nom
*prenom
*email 
*le status 
*la date de creation 
je creer une boucles qui récupère toute les données réponds automatiquement ainsi que la mise jour du status en base de données

* lancer la commande avec:
* symfony console app:send-contact

## refactorisation du code et code-coverage
lancer la commande :
    -php bin/phpunit --coverage-html var/log/test/test-coverage

    ## rendre le messenger pour les les mails async
    commande:
     symfony console messenger:consume