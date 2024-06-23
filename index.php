<?php

require_once '../toDo/includes/inc_header.php';
$filename = __DIR__ . '/data.json';

const ERREUR_VIDE = 'Veuillez faire votre liste';
const EREEUR_TROP_COURT = 'Veulliez écrire au moins 5 caractère';
$erreur = '';
$listes = [];

if (file_exists($filename)) {
    $donnees = file_get_contents($filename);
    // (true) pour le rendre en tableau associatifs.
    $listes = json_decode($donnees, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $nouvelleListe = $_POST['liste'] ?? '';

    if (empty($nouvelleListe)) {
        $erreur =  ERREUR_VIDE;
    } else if (mb_strlen($nouvelleListe) < 5) {
        $erreur = EREEUR_TROP_COURT;
    }

    if (!$erreur) {
        $listes = [
            //spread (…) permet de decomposer et recuperer les tableaux en les mettant au debut du nouveau tableau.
            ...$listes,
            [
                'nom' => $nouvelleListe,
                //(flase) 
                'tacheAchevee' => false,
                'id' => time()
            ]
        ];
        file_put_contents($filename, json_encode($listes, JSON_PRETTY_PRINT), LOCK_EX);

        // schéma PRG : empêche la resoumission du formulaire si l'utilisateur rafraîchit la page. 
        header('Location: ' . 'index.php');
        exit;
    }
}
?>

<div class="container">
    <header>
        <div class="logo">ToDo</div>
    </header>
    <main class="content">
        <div class="todo-container">
            <h1>Liste de tâches</h1>
            <div class="todo-form"></div>
            <div class="todo-list"></div>

            <form action="" method="post" class="formulaire">
                <input type="text" name="liste" id="liste" class="submit" placeholder="Ajoute une tâche à faire"> <input type="submit" value="Ajouter" class="button">
                <?php if (!empty($erreur)) : ?>
                    <p class="erreur"> <?= $erreur ?></p>
                <?php endif ?>
                <ul class="liste">
                    <?php foreach ($listes as $liste) : ?>
                        <li>
                            <?= $liste['nom']; ?>
                            <button class="fait">
                                <a href="remove.php?id=<?= $liste['id'] ?>">
                                    <?= $liste['tacheAchevee'] ? 'C\'est fait' : 'A faire' ?>
                                </a>
                            </button>
                            <button class="supprimer">
                                <a href="delet.php?id=<?= $liste['id'] ?>">
                                    Supprimer
                                </a>
                            </button>
                        </li>
                    <?php endforeach ?>
                </ul>
            </form>
        </div>
    </main>

    <?php
    require_once '../toDo/includes/inc_footer.php';
