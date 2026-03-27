function sendPanier() {
	const p = getPanier();

	console.log(JSON.stringify(p));


	try {
		fetch("/commande/client", {
			method: "POST", 
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(p)
		})
	
		.then(response => response.json())
		.then(data => {
			if (data.status == "success") {
				newModal("Commande passée avec succès !", 3);
			 // 	dropPanier();
				window.location.href = "/commande/client/payee";
			}else if (data.status == "error"){
				let errorMessage = " Erreur : " + data.message + "<br>"; ;
				let qttMissing = data.details;
				let time = 3;

				console.log(data.details);

				if ( qttMissing.length > 0) {
					errorMessage += "Articles manquants : <br>";
					qttMissing.forEach(missing => {
						const dif = missing.quantiteDemandee - missing.quantiteDisponible; 
							errorMessage += " => " + missing.name + " - missing : " + dif + " <br> ";

							time += 2;
					});
				}
				newModal(errorMessage , time);
			}else {
				newModal("ELSE Une erreur est survenue lors de la commande.", 3);
			}
		})
	} catch (error) {
		console.error("Error:", error);
		newModal("Une erreur est survenue lors de la commande.", 3);
	}

	

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
		
		const panierFinal = document.getElementById("panierFinal");

		console.log(panierFinal);

		let total = 0;
		data.forEach(product => {
			product.qtt = p.filter(xp => xp.articleId == product.id)[0].quantiter;
			console.log(product);

			const element = `
				<tr>
            		<td>${product.libelle} </td>
            		<td> ${product.qtt}</td>
            		<td> ${product.prix} €</td>
        		</tr>
			`;

			panierFinal.innerHTML += element;
			total += product.prix * product.qtt;

		});

		document.getElementById("totalPanier").innerHTML = total.toFixed(2);

	
	})
	
}

getInfoPanier();