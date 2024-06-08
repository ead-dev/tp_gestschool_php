<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>ETUDIANTS</title>
</head>
<body>
    <?php
        include('../../includes/menu.php');
        try{
            $conn = new PDO('mysql:host=localhost;dbname=tds4_db;charset=utf8', 'root', '');
            
            if(!empty($_POST)){
                $data = $_POST;
                $insert = "INSERT INTO etudiants (matricule,nom,prenom,phone,adresse) values (:m,:n,:p,:t,:a)";
                $requete = $conn->prepare($insert);
                $requete->execute([
                    'm'=>$data['mat'],
                    'n'=>$data['nom'],
                    'p'=>$data['prenom'],
                    't'=>$data['tel'],
                    'a'=>$data['adr'],
                ]);
            };
            
            $string = "select * from etudiants";
            $requete = $conn->prepare($string);
            $requete->execute();
            $etudiants = $requete->fetchAll();
            //var_dump($filieres[3]);
            //die();
        }
        catch(Exception $ex){
            echo "Erreur de connexion : ".$ex->getMessage();
        }
    ?>
    <div class="container">
        <div class="d-flex gap-3 justify-content-between mt-5">
            <h2>LISTE DES ETUDIANTS</h2>
            <p>Nous sommes le <?= date('d/m/Y') ?></p>
        </div>
        
        <div class="card">
            <div class="card-body bg-light">
                <div class="card-header">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ajout">Ajouter</button>
                </div>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>NOM</th>
                            <th>PRENOM</th>
                            <th>MATRICULE</th>
                            <th>TELEPHONE</th>
                            <th>ADRESSE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($etudiants as $item): ?>
                            <tr>
                                <td><?= $item['nom'] ?></td>
                                <td><?= $item['prenom'] ?></td>
                                <td><?= $item['matricule'] ?></td>
                                <td><?= $item['phone'] ?></td>
                                <td><?= $item['adresse'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <p> <?= count($etudiants) ?> etudiant(s) au total</p>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="ajout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">NOUVEL ETUDIANT</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="mat" class="form-control" placeholder="Matricule de l'etudiant">
                </div>
                <div class="form-group mt-3">
                    <input type="text" name="nom" class="form-control" placeholder="NOM">
                </div>
                <div class="form-group mt-3">
                    <input type="text" name="prenom" class="form-control" placeholder="PRENOM">
                </div>
                <div class="form-group mt-3">
                    <label for="">&numero; TEL</label>
                    <input type="text" name="tel" class="form-control" placeholder="exple: 064576186">
                </div>
                <div class="form-group mt-3">
                    <input type="text" name="adr" class="form-control" placeholder="Adresse">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ANNULER</button>
                <button type="submit" class="btn btn-success">ENREGISTRER</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script src="../../assets/js/bootstrap.bundle.js"></script>
</body>
</html>