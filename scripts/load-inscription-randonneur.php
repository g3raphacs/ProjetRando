<?php
    // Connexion à la base de données
    $dsn = 'mysql:dbname=projet_rando;host=localhost';
    $user = 'root';
    $password = '';

    try {
        $base = new PDO($dsn, $user, $password);
        $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        header('Location: errorbdd.php');
    }



    if(isset($_POST['search']) && isset($_POST['page']) && isset($_POST['maxBricks']) && isset($_POST['mainID'])){
        $ID=$_POST['mainID'];
        $maxBricks=(int)$_POST['maxBricks'];
        $page=(int)$_POST['page'];
        $pageStart=($page-1)*$maxBricks;

        $search = "%" . $_POST['search'] . "%";
        $req = $base->prepare("SELECT *
                                FROM randonneurs
                                WHERE nom LIKE :search OR prenom LIKE :search
                                ORDER BY nom
                                LIMIT :maxBricks OFFSET :pageStart ");
        $req->bindValue('maxBricks', $maxBricks, PDO::PARAM_INT);
        $req->bindValue('pageStart', $pageStart, PDO::PARAM_INT);
        $req->bindValue('search', $search, PDO::PARAM_STR);

        $req->execute();
}


    while ($donnees = $req->fetch()){
        $id=$donnees['ID'];
?>
        <div class="elementBox col-lg-6 col-xl-3">
            <div class="card mb-3 main-card">
                <div class="card-body">
                    <div class="widget-content-left">
                        <div class="card-title"><?php echo $donnees['nom']; ?><span class="text-primary"><?php echo ' '.$donnees['prenom']; ?></span></div>
                        <div class="card-subtitle"><?php echo 'Tel. '.$donnees['tel']; ?></div>
                    </div>
                    <div class="collapse" id="<?php echo 'excu-collapse'.$donnees['ID']; ?>">
                        <p><strong>Email: </strong><span class="text-primary"><a href="mailto:<?php echo $donnees['mail']; ?>"><?php echo $donnees['mail']; ?></a></span></p>
                        <p><strong>Adresse: </strong><span class="text-primary"><?php echo $donnees['adresse']; ?></span></p>
                        <p><strong>Ville: </strong><span class="text-primary"><?php echo $donnees['ville']; ?></span></p>
                        <p><strong>Code Postal: </strong><span class="text-primary"><?php echo $donnees['codepostal']; ?></span></p>
                        <p><strong>Pays: </strong><span class="text-primary"><?php echo $donnees['pays']; ?></span></p>
                    </div>
                    <input name="id" type="hidden" value="<?php echo $id;?>">
                    <div class="msgInsc alert alert-secondary" role="alert" style="display:none"><strong>Inscrire ?</strong><button class="ml-5 mb-1 btn border-0 btn-success font-weight-bold" onclick="Inscription(<?php echo $ID.','.$id;?>)">OUI</button><a href="#" class="ml-2 mb-1 btn border-0 btn-secondary font-weight-bold" onclick="hideInscMsg()">NON</a></div>
                </div>
                <div class="card-footer">
                    <button type="button" data-toggle="collapse" href="<?php echo '#excu-collapse'.$donnees['ID']; ?>" class="mr-2 btn border-0 btn-outline-secondary"><span class="fas fa-eye"></span></button>
                    <button class="mr-2 btn border-0 btn-outline-success" onclick="clickInscription(<?php echo $id;?>)"><span class="fas fa-user-plus mr-2"></span><strong>Inscrire</strong></button>
                </div>
            </div>
        </div>
<?php } $req->closeCursor(); ?>