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
{
	let PANIER = "All4Sport-panier";

	function getPanier() {
		console.log("*[LSPANIER]* getPanier appelé");
		let _ = localStorage.getItem(PANIER) || "[]";
		return JSON.parse(_);
	}

	function savePanier(newPanier) {
		console.log("*[LSPANIER]* savePanier appelé", newPanier);
		localStorage.setItem(PANIER, JSON.stringify(newPanier));
	}

	function addToPanier(id, qtt, name = "Article") {
		console.log("*[LSPANIER]* addToPanier appelé", { id, qtt, name });

		let _ = getPanier();

		let alreadyExiste = _.find(e => e.articleId == id);

		if (alreadyExiste) {
			console.log("*[LSPANIER]* Produit déjà existant, incrémentation");
			alreadyExiste.quantiter += qtt;
		} else {
			console.log("*[LSPANIER]* Nouveau produit ajouté");
			_.push({
				articleId: id,
				quantiter: qtt
			});
		}

		savePanier(_);
		newModal(` ${name}, <br> ajouter au panier`, 3);
	}

	function dropPanier() {
		console.log("*[LSPANIER]* dropPanier appelé");
		localStorage.setItem(PANIER, []);
	}
}