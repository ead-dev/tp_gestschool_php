<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>FILIERES</title>
</head>
<body>
    <?php
        include('../../includes/menu.php');
        try{
            $conn = new PDO('mysql:host=localhost;dbname=tds4_db;charset=utf8', 'root', '');
            $string = "select f.nom,f.abb,c.nom cycle from filieres f LEFT JOIN cycles c ON (c.code_s = f.cycle_id)";
            $requete = $conn->prepare($string);
            $requete->execute();
            $filieres = $requete->fetchAll();
            //var_dump($filieres[3]);
            //die();
        }
        catch(Exception $ex){
            echo "Erreur de connexion : ".$ex->getMessage();
        }
    ?>
    <h2>GESTION DES FILIERES</h2>
    <table class="table">
        <thead>
            <tr>
                <th>FILIERE</th>
                <th>CODE</th>
                <th>CYCLE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($filieres as $item): ?>
                <tr>
                    <td><?= $item['nom'] ?></td>
                    <td><?php echo($item['abb']) ?></td>
                    <td><?php echo($item['cycle']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>