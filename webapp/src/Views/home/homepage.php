<div>
    <h3>Page d'accueil</h3>

    <p>Bienvenue sur la page d'accueil de notre site web. Vous pouvez naviguer sur les pages suivantes:</p>

    <?php echo '<a href="'.WEBROOT.'/webapp/produit"> Aller vers la page /webapp/produit</a> <br>';
        echo '<a href="'.WEBROOT.'/webapp/produit/1"> Aller vers la page /webapp/produit/:1</a><br> '; ?>

    

    <?php echo '<a href="'.WEBROOT.'/webapi/produit"> Aller vers la page GET /webapi/produit</a> <br>';
        echo '<a href="'.WEBROOT.'/webapi/produit/1"> Aller vers la page GET /webapi/produit/:1</a><br> '; ?>
</div>