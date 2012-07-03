<?php
$commande = new Commande();
$commande->charger_ref($_GET['ref']);
if(!$commande)  exit();
include_once(realpath(dirname(__FILE__)) . '/Commentairecommande.class.php');
?>
<div class="bordure_bottom" style="margin:0 0 10px 0;">
    <div class="entete_liste_client">
        <div class="titre">Commentaire client sur la commande</div>
    </div>

    <ul class="ligne_claire_BlocDescription">
        <li>
        <?php
        $commentaire = new Commentairecommande();
        echo $commentaire->getCommentaireOnCommand($commande->id);
        ?>
        </li>
    </ul>
</div>