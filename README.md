

## Database

- **Username**: `AP4USER`
- **Password**: `AP4MDPDEFOU_123a`
- **IP Address**: `88.189.251.90`
- **Port**: `21336`

## Redirection

| Controller         | Vue                          | Nom          | Route       | href             |
|--------------------|------------------------------|--------------|-------------|------------------|
|ProduitController   | Templates/Produit/index.html | Page Accueil | /           | app_produit      |
|SecurityController  | Templates/security/login     | Connexion    | /login      | app_login        |
|SecurityController  | ---------------------------- | Déconnexion  | /logout     | app_logout       |
|ProduitController   |  Templates/Produit/show.html | 1 Produit    |/produit/{id}| app_produit_show |
|CompteUtilisateurController|Templates/compte_utilisateur/index.html|Compte|/compte/utilisateur|app_compte_utilisateur|
|RegistrationController|Templates/registration/register.html|Inscription|/register|app_register     |
|CategorieController |  Templates/categorie/*.html      |Crud Categorie| /categorie  | app_categorie_*  |
| PanierController   | Templates/panier/index.html.twig |  Panier  | /panier  |    app_panier  |
|                    |                                  |              |             |                  |

# Local Storage Name

> All4Sport-panier

# Local Storage Function

```js
// Geter
let panier = JSON.parse(localStorage.getItem("All4Sport-panier")  || "[]");
```

```js
// Seter
localStorage.getItem("All4Sport-panier", maVariable)
```

http://192.168.192.12:8000/