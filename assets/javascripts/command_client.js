function sendPanier() {
	const p = getPanier();


	fetch("/commande/client", {
		method: "POST", 
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify(p)
	})

	.then(response => response.json())
	.then(data => {
		console.log("response")
		console.log(response);
		console.log(data)
		if (data.status == "success") {
			newModal("Commande passée avec succès !", 3);
			dropPanier();
		}else {
			newModal("Une erreur est survenue lors de la commande.", 3);
		}
	})
	

}

	const panierList= document.getElementById("listPanier");
const totalPrice = document.getElementById("totalPrice");


function getInfoPanier() {
	const p = getPanier();
	
  fetch("/getPanierJson", {
		method: "POST", 
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify(p.map(xp => xp.articleId)) 
	})
	.then(response => response.json())
	.then(data => {
		

			panierList.innerHTML = "";

		let total = 0;
		data.forEach(product => {
			product.qtt = p.filter(xp => xp.articleId == product.id)[0].quantiter;
			console.log(product);

			const element = `
			
				<div class="panier-item" data-price="10">
						<span class="nom"> ${product.libelle} </span>
						<div class="quantite-box">
							<button class="qty-btn minus" onclick="updateQtt( ${product.id}, 'minus' )">−</button>
							<span class="quantite" id="qtt_${product.id}"> ${product.qtt} </span>
							<button class="qty-btn plus" onclick="updateQtt( ${product.id}, 'plus' )">+</button>
						</div>

						<span class="prix" id="prix_${product.id}"> ${product.prix} €</span>
				</div>
			
			
			`;

			panierList.innerHTML += element;
			total += product.prix * product.qtt;

		});

		totalPrice.innerHTML = total.toFixed(2);

	
	})
	
}

getInfoPanier();