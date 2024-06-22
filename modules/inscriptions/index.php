<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Inscriptions</title>
</head>
<body>
    <?php
        include('../../includes/menu.php');
        try{
            $conn = new PDO('mysql:host=localhost;dbname=tds4_db;charset=utf8', 'root', '');
            
            if(!empty($_POST)){
                $data = $_POST;
                //var_dump($data);
                //die();
                $insert = "INSERT INTO inscriptions (etudiant_id,filiere_id,niveau_id,annee_id,jour) values (:e,:f,:n,:a,:j)";
                $requete = $conn->prepare($insert);
                $requete->execute([
                    'e'=>$data['etudiant_id'],
                    'f'=>$data['filiere_id'],
                    'n'=>$data['niveau_id'],
                    'a'=>$data['annee_id'],
                    'j'=> date_format(new \DateTime(),'Y-m-d H:i:s'),
                ]);
            }; 

             
            $string = 'select i.jour date, concat(e.nom," ",e.prenom) etudiant, f.nom filiere, concat(a.debut,"-",a.debut+1) ac, n.nom niveau from etudiants e JOIN inscriptions i ON (e.matricule = i.etudiant_id) JOIN filieres f ON (f.code_f = i.filiere_id) JOIN niveaux n ON (n.id = i.niveau_id) JOIN annees a ON (i.annee_id = a.id);';
           // $string = 'select i.jour date, concat(e.nom," ",e.prenom) etudiant, f.nom filiere from etudiants e JOIN inscriptions i ON (e.matricule = i.etudiant_id) JOIN filieres f ON (f.code_f = i.filiere_id)';
            $requete = $conn->prepare($string);
            $requete->execute();
            $inscriptions = $requete->fetchAll();

            $string = 'select concat(nom," ",prenom," - ",matricule) nom, matricule from etudiants';
            $requete = $conn->prepare($string);
            $requete->execute();
            $etudiants = $requete->fetchAll();
            
            $string = 'select * from filieres';
            $requete = $conn->prepare($string);
            $requete->execute();
            $filieres = $requete->fetchAll();

            $string = 'select * from niveaux';
            $requete = $conn->prepare($string);
            $requete->execute();
            $niveaux = $requete->fetchAll();
            //var_dump($niveaux);
            //die();
            $string = 'select * from annees';
            $requete = $conn->prepare($string);
            $requete->execute();
            $annees = $requete->fetchAll();
            //var_dump($etudiants);
            //die();
        }
        catch(Exception $ex){
            echo "Erreur de connexion : ".$ex->getMessage();
        }
    ?>
    <div class="container">
        <div class="d-flex gap-3 justify-content-between mt-5">
            <h2>HISTORIQUE DES INSCRIPTIONS</h2>
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
                            <th>DATE</th>
                            <th>ETUDIANT</th>
                            <th>FILIERE</th>
                            <th>ANNEE AC.</th>
                            <th>NIVEAU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inscriptions as $item): ?>
                            <tr>
                                <td><?= $item['date'] ?></td>
                                <td><?= $item['etudiant'] ?></td>
                                <td><?= $item['filiere'] ?></td>
                                <td><?= $item['ac'] ?></td>
                                <td><?= $item['niveau'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <p> <?= count($inscriptions) ?> inc.(s) au total</p>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="ajout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">NOUVELLE INSCRIPTION</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <select name="etudiant_id" class="form-control" id="">
                    <option value="">Selectionner un etudiant ...</option>
                    <?php foreach($etudiants as $et): ?>
                        <option value="<?= $et['matricule'] ?>"><?= $et['nom'] ?></option>
                    <?php endforeach ?>    
                  </select>
                </div>
                <div class="form-group mt-3">
                    <select name="filiere_id" class="form-control" id="">
                    <option value="">Selectionner une filiere ...</option>
                        <?php foreach($filieres as $et): ?>
                            <option value="<?= $et['code_f'] ?>"><?= $et['nom'] ?></option>
                        <?php endforeach ?>    
                    </select>
                </div>
                <div class="form-group mt-3">
                    <select name="niveau_id" class="form-control" id="">
                    <option value="">Selectionner un niveau ...</option>
                        <?php foreach($niveaux as $et): ?>
                            <option value="<?= $et['id'] ?>"><?= $et['nom'] ?></option>
                        <?php endforeach ?>    
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="">ANNEE ACADEMIQUE</label>
                    <select name="annee_id" class="form-control" id="">
                        <option value="">Selectionner une annee academique ...</option>
                        <?php foreach($annees as $et): ?>
                            <option value="<?= $et['id'] ?>"><?= $et['debut'] .' - '. ($et['debut']+1)  ?></option>
                        <?php endforeach ?>    
                    </select>
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