const PANIER = "All4Sport-panier";


/**
 * Représente le contenu du panier.
 *
 * Le panier est un tableau d’objets.
 * Chaque objet correspond à un produit ajouté par l’utilisateur.
 *
 * Structure d’un élément :
 * - articleId (Number) : Identifiant unique du produit en base de données.
 * - quantiter (Number) : Quantité sélectionnée par l’utilisateur.
 *
 * Exemple :
 * [
 *   { articleId: 2,   quantiter: 2  },  // 2 unités du produit ID 2
 *   { articleId: 534, quantiter: 12 }   // 12 unités du produit ID 534
 * ]
 *
 * ⚠️ Les informations complètes du produit (nom, prix, image, etc.)
 * sont récupérées côté serveur à partir de l’articleId.
 */
