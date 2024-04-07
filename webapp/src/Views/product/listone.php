<div>
    <h3> Liste des produits (vue unique) </h3>
    <table class="table">
    <thead>
        <tr>
            <th scope="col">:id</th>
            <th scope="col">Nom</th>
            <th scope="col">N° Produit</th>
            <th scope="col">Makeflag</th>
            <th scope="col">Couleur</th>
            <th scope="col">SafetyStockLevel</th>
            <th scope="col">ReorderPoint</th>
            <th scope="col">DaysToManufacture</th>
            <th scope="col">Class</th>
            <th scope="col">SellStartDate</th>
            <th scope="col">ModifiedDate</th>
        </tr>
    </thead>
    <tbody>
        <?php
            echo "<tr>" ;
            echo "<th scope='row'>". $product->ProductID . "</th>";
            echo "<td>" . $product->Name . "</td>";
            echo "<td>" . $product->ProductNumber . "</td>";
            echo "<td>" . $product->MakeFlag. "</td>";
            echo "<td>" . $product->Color . "</td>";
            echo "<td>" . $product->SafetyStockLevel . "</td>";
            echo "<td>" . $product->ReorderPoint . "</td>";
            echo "<td>" . $product->DaysToManufacture . "</td>";
            echo "<td>" . $product->Class . "</td>";
            echo "<td>" . $product->SellStartDate . "</td>";
            //echo "rowguid : " . $product->rowguid . "<br>";
            echo "<td>" . $product->ModifiedDate . "</td>";
            echo "</tr>";
        ?>
    </tbody>
    </table>
</div>

