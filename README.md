

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
|                    |                              |              |             |                  |
|                    |                              |              |             |                  |
|                    |                              |              |             |                  |
|                    |                              |              |             |                  |
|                    |                              |              |             |                  |

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