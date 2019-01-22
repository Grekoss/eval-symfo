# Retour du correcteur

## Commentaire général

Félicitations pour ce travail abouti et bien conçu en amont :clap: Hormis l'AJAX en bonus tout fonctionne bien et tu t'es même payé le luxe d'ajouter des fonctionnalités non démandées. Les bases de Symfony sont acquises, tu es sur la bonne voie :) Voir détails ci-dessous.

## Points forts

- Documents de conception fournis
    - MCD OK
    - Wireframes maison :thumbsup:
    - Trello utilisé à bon escient
    - 54 commits :boom:
- Migrations fonctionnelles
- Fixtures fonctionnelles + Custom Provider :thumbsup:
- Le classement front/back des contrôleurs
- Le contrôleur de pasges statiques
- L'usage des Repository et requêtes custom
- Top le listener pour le nuage de tags :clap:
- Utilisation des partiels
- Qualité du code très correcte (hors commentaires :wink: sauf commentaires dans les requêtes custom, bien)
- **Toutes fonctionnalités demandées fonctionnelles**
- Implémentation de features non demandées
    - Recherche par mot-clé
    - Recherche par auteur
    - Pagination partout et pas que sur les questions
    - +1 pour l'utilisation du service Slugger
- 9 jours de travail acharné :muscle:
- Intégration CSS correcte.

## Recommandations

> Vu le travail effectué, ces remarques sont pour la plupart des _fioritures_ ou simplement des features déjà implémentées ailleurs dans l'appli mais pas partout / ou des conseils d'amélioration de l'existant.

- Mettre des contraintes de validation sur _toutes_ les entités :wink:
- Bien gérer chaque redirection : au login, à l'ajout de question ou de réponse, tu devrais renvoyer respectivement vers : connexion, page de la question, page de la question.
- Sécurité bien gérée mais reste quelques failles, notamment on peut éditer n'importe quel utilisateur :scream:
- Manque cruellement de commentaires :scream:
- Communication avec AJAX à re-bosser à l'occasion :wink:
- Ajouter des tests fonctionnels pour bien couvrir l'application (cas de certains accès pour un user qui bug en anonyme - erreurs non gérées)
- Sur une appli plus "pro", ne pas hésiter à utiliser des thèmes graphiques existants (pas demandé ici)
- Je note l'effort des nommages en anglais, tu peux persévérer ;)

### Corrections

- [x] Modification de la pagination de la homepage avec une meilleur utilisation a mon goût du bundle de `knp_paginator`. Egalement rajout d'un `background-color` lorsqu'un modérateur banni des questions.
 
- [x] Controller les contraintes de validations de toutes les entités.

- [ ] Controller les redirections
- [ ] Sécuriter sur la modification des profils
- [ ] Commenter le CODE!
- [ ] AJAX! pour le vote et le bannissement des questions / réponses
- [ ] Ajouter du test unitaires
