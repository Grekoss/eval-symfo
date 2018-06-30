<?php

namespace App\DataFixtures\Faker;

class MyProvider extends \Faker\Provider\Base
{
    protected static $tags = [
        'Informatique',
        'Bricolage',
        'Famille',
        'Planete',
        'Santé',
        'Cinéma',
        'Sport',
        'Politique',
        'Nature',
        'Sciences',
        'Education',
        'Histoire',
        'Mode',
        'People',
    ];

    protected static $questions = [
        'À quoi tu penses le soir en te couchant ?',
        'Ton souvenir de party de famille préféré, c\'est quoi?',
        'L\'endroit où tu te sens le mieux ?',
        'De qui es-tu le plus proche dans ta famille ?',
        'Es-tu certain(e) de ce que tu veux faire comme métier dans la vie ?',
        'S\'il fallait que tu ne manges qu\'une seule chose pendant un mois, ça serait quoi ?',
        'Ton métier de rêve quand tu étais jeune, c\'était quoi?',
        'Penses-tu avoir des enfants? (Si oui, combien?)',
        'Raconte-moi un de tes plus beaux souvenirs d\'enfance',
        'Raconte-moi un de tes pires souvenirs d\'enfance',
        'Ta destination idéale pour partir en voyage, c\'est quoi?',
        'Raconte-moi la pire date que tu as déjà eue..',
        'S\'il te restait une journée à vivre, tu ferais quoi ?',
        'Qu\'est-ce que t\'aimes le plus chez ta / ton meilleur(e) ami(e) ?',
        'Si tu pouvais changer une chose chez toi(sans obligatoirement être ton physique) ce serait quoi ?',
        'Nomme 3 personnages de film / télésérie qui te décrivent parfaitement',
        'Est-ce que t\'aimes quand on te dit un compliment ou tu deviens awkward à chaque fois?',
        'Qui est ton band/artiste préféré de tout temps?',
        'Est-ce que tu crois au coup de foudre?',
        'As-tu déjà été en amour? Combien de fois?',
        'Comment tu es, lorsque tu es en peine d\'amour ?',
        'Qu\'est-ce qui te séduis vraiment chez quelqu\'un ?',
        'Qu\'est-ce qui te turn off automatiquement?',
        'Qu\'est - ce que tu aimerais accomplir un jour ?',
        'Si l\'argent n\'importait pas, tu ferais quoi dans la vie?',
        'Si tu étais pour retourner aux études, ce serait en quoi?',
        'Aimes-tu mieux avoir des regrets ou des remords, dans la vie?',
        'Le plus bel endroit où t\'es allé, c\'est où?',
        'Crois-tu au karma?',
        'Qu\'est-ce qui te différencie des autres, selon toi ?',
        'Quelle est ta plus grande qualité, selon toi ?',
        'Comment tes amies te décriraient ?',
        'Un film que tu écouterais encore et encore, ça serait quoi ?',
        'Si tu te retrouverais dans la peau d\'un gars (ou d\'une fille) pour une semaine, tu ferais quoi ?',
        'Si tu n\'avais aucune gêne, qu\'est-ce que tu ferais ?',
        'Est-ce que tu crois aux âmes sœurs ?',
        'Qu\'est-ce que tu aimes le plus physiquement chez toi?',
        'Qu\'est-ce que tu aimes le plus chez toi qui n\'est pas de ton physique?',
        'Raconte-moi ta pire brosse (ou ton top 3, si t\'en as trop) ?',
        'Une grosse folie que t\'as fait dans ta vie, c\'est quoi ?',
        'Sur quoi tu sais que tu dois t\'améliorer dans la vie?',
        'À quel point ta vie se compliquerait si on t\'enlevait ton cellulaire ?',
        'Ta blague préférée, c\'est quoi?',
        'Crois-tu en la vie après la mort, la vie sur une autre planète et le destin?',
        'Qu\'est-ce que tu veux souvent poser au sexe opposé, mais que tu n\'oses pas? (Et pourquoi?)',
        'De quoi tu t\'ennuies du secondaire ?',
        'Pour quelle raison as-tu pleuré la dernière fois ?',
        'Si on te donne une semaine de congé live, qu\'est-ce que tu fais?',
        'Si tu pouvais revenir à n\'importe quel moment de ta vie, non pour changer quelque chose, mais pour apprécier le moment, ce serait quand ?',
        'Quel âge auriez vous si vous ne saviez pas quel âge vous avez ?',
        'Puisque nous passons notre temps à dire que la vie est trop courte, pourquoi faisons nous tant de choses que nous n\'aimons pas faire ?',
        'Si le bonheur était la monnaie nationale, quel job vous rendrait millionnaire ?',
        'Si la durée de vie moyenne d\'un être humain devenait 40 ans, que changeriez vous à la façon dont vous vivez aujourd\'hui ?',
        'Vous souciez vous plus de bien faire les choses ou de faire les bonnes choses ?',
        'Si vous pouviez donner un conseil unique à un nouveau né, quel serait - il ?',
        'Tueriez-vous pour éviter la mort d’un être aimé ?',
        'Si vous deviez obligatoirement aller vivre dans un autre pays, lequel serait-ce et pourquoi ?',
        'Quelle chose rêvez vous de faire par dessus tout ? Pourquoi ne pas l’avoir fait ?',
        'Vous arrive t\'il de pousser le bouton de l\'ascenseur plusieurs fois d’affilée ?',
        'Pensez-vous vraiment que cela serve à quelque chose ?',
        'Vous soucieriez vous d\'avantage pour un génie ou pour un simplet ?',
        'Ëtes vous le genre d\'amis que vous aimez avoir en ami ?',
        'Préfèreriez vous perdre tous vos souvenirs ou être incapable d\'avoir de nouveaux souvenirs ?',
        'Est-ce que vos plus grandes peurs se sont déjà réalisées ?',
        'Vous êtes vous déjà retrouvé avec quelqu’un sans dire un mot, et vous être dit en rentrant chez vous que vous veniez d`’avoir la meilleure conversation de votre vie ?',
        'Quand vous êtes vous senti le plus passionné et vivant au cours des 12 derniers mois ? Pourquoi ?',
        'Pourquoi les religions, fondées sur l\'amour, sont-elles l\'objets de tant de guerres ?',
        'Si vous gagniez un million d\'euros aujourd\'hui, quitteriez vous votre job ?',
        'Aimeriez vous avoir moins de travail, ou avoir plus de travail que vous aimez ?',
        'Avez vous l\'impression d\'avoir déjà vécu cette journée une centaine de fois ?',
        'A quand remonte la dernière ou vous vous êtes aventurés dans l\'inconnu avec comme seul guide une idée en laquelle vous croyiez ?',
        'Si tous vos proches devaient mourir demain, qui chercheriez vous a voir en priorité aujourd\'hui ?',
        'Quelle est la différence entre « être en vie » et « vivre pleinement » ? Laquelle de ces deux définitions vous correspond le mieux ?',
        'Pourquoi avez-vous si peur de faire une erreur puisqu\'il semblerait que nous sommes capable d\'apprendre de nos erreurs ?',
        'Qu\'aimez vous ? Quand avez vous, de par vos actions, exprimé cet amour récemment ?',
        'Vous rappellerez vous de ce que vous avez fait aujourd’hui dans cinq ans ? Et de ce que vous avez fait hier ? Et avant - hier ?',
        'Qui serez-vous dans cinq ans ? Pourquoi ne pas être cette personne dés aujourd’hui ?',
        'Qu\'est ce qui fait battre votre cœur ? L\'avez vous fait aujourd’hui ?',
        'Que risquez vous à plaquer tout une partie de votre vie demain ?',
        'Etes vous heureux ? Pourriez vous l’être plus ?',
        'Doit-on accorder de l\'importance aux mots?',
        'Le passé est-il encore réel?',
        'Tout savoir est-il un pouvoir?',
        'La raison peut-elle rendre raison de tout?',
        'Les images sont-elles plus riches de sens que les mots?',
        'Faut-il renoncer à s\'interroger sur ce qui est hors de portée de l\'expérience scientifique?',
        'Pourquoi les femmes ouvrent la bouche en se maquillant les yeux ?',
        'Pourquoi dit-on bâbord et tribord sur un bateau, et pas gauche et droite ?',
        'Pourquoi les sorcières chevauchent-elles un balai ?',
        'Pourquoi a-t-on une bosse quand on se cogne ?',
        'Pourquoi la langue colle-t-elle sur un glaçon ?',
        ' Pourquoi les insectes se jettent-ils sur les lampes allumées ?',
        'Est-ce qu\'il y a un autre mot qui veut dire « synonyme »?',
        'Combien de bières est-ce que ça prend à un crabe pour qu’il marche droit?',
        'Un sourd-muet capable de parler des deux mains, est-ce qu’on dit qu\'il est ambidextre ou bilingue?',
        'Si les icebergs étaient jaune orange, est-ce que le Titanic existerait encore?',
        'Pourquoi il n\'y a pas de nourriture pour chats à saveur de souris ou de nourriture pour chiens à saveur de facteur?',
        'Pourquoi on prend la peine d\'écrire « modèle réduit » sur les boîtes de modèles d’avion réduits quand la boîte est grosse comme ma main?',
        'Plus tu étudies, plus tu apprends. Plus tu apprends, plus tu oublies. Plus tu oublies, moins t\'en sais. Alors à quoi ça sert d\'étudier?',
        'Plus il y a de fromage, plus il y a de trous. Plus il y a de trous, moins il y a de fromage. Alors, plus il y a de fromage, moins il y a de fromage?',
        'Quand des fabricants d\'affiches sont en grève, est-ce qu\'il y a quelque chose d\'écrit sur leurs piquets de grève?',
        'Est-ce que les grenouilles doivent attendre une heure après un repas avant de retourner se baigner?',
        'Si une télécommande de télévision fonctionne à batteries, pourquoi ne fonctionne-t-elle pas quand il y a une panne d’électricité?',
        'Si le travail n\'est pas une maladie, pourquoi y a-t-il une médecine de travail?',
        'Un aveugle qui prédit l\'avenir, est-ce qu\'on appelle ça un voyant non-voyant?',
        'Pourquoi faut-il appuyer sur la touche « Démarrer » pour fermer un ordinateur?',
        'Pourquoi les magasins ouverts 24 heures sur 24 et 7 jours sur 7 ont-ils une serrure?',
        'Pourquoi est-ce toujours du côté passager que l\'essuie-glace fonctionne le mieux?',
        'Pourquoi le gagnant de la partie ne dit jamais : « L\'important, ce n’est pas de gagner, mais de participer »?',
        'Si la police arrête un mime, est-ce qu\'elle lui dit qu\'il a droit de garder le silence?',
        'Si tu accroches un petit miroir à un sapin, va-t-il sentir l\'auto?',
        'Que doit-on faire quand on voit un animal en danger d\'extinction manger une plante en danger d\'extinction?',
        'Pourquoi un chien n\'est-il jamais aussi affectueux que lorsqu\'il est trempé et couvert de boue?',
        'Pourquoi « séparé » s\'écrit-il tout ensemble alors que « tout ensemble » s\'écrit séparé?',
        'Pourquoi les Kamikazes portaient-ils un casque?',
        'D\'où vient l\'idée de stériliser l\'aiguille qui va servir à faire une injection létale à un condamné à mort?',
        'Si la vue d\'un bureau encombré évoque un esprit encombré, que penser d\'un bureau vide?',
        'Je veux acheter un boomerang neuf. Comment puis-je me débarrasser du vieux?',
        'Pourquoi, dans un pays où l\'on prône la liberté d\'expression, existe-t-il des factures de téléphone?',
        'Pourquoi est-ce que les tournevis pour vis plus petites ont un manche plus petit, puisque nos mains restent de la même grandeur?',
    ];

    public static function questionTag()
    {
        return static::randomElement(static::$tags);
    }

    public static function questionTitle()
    {
        return static::randomElement(static::$questions);
    }
}