<?php
include_once(realpath(dirname(__FILE__)) . '/../../../classes/PluginsClassiques.class.php');
include_once(realpath(dirname(__FILE__)) . '/../../../classes/Commande.class.php');

class Commentairecommande extends PluginsClassiques {

    function init() {
        $this->query('ALTER TABLE  `' . Commande::TABLE . '` ADD  `commentairecommande` TEXT NOT NULL');
    }

    function destroy() {
        $this->query('ALTER TABLE  `' . Commande::TABLE . '` DROP  `commentairecommande`');
    }

    function pre() {
        if(!empty($_POST['commentairecommande'])) {
            $_SESSION['navig']->commentairecommande = $_POST['commentairecommande'];
        }
    }

    function aprescommande($commande) {

        if(!empty($_SESSION['navig']->commentairecommande)) {
            $this->query('
                UPDATE ' . Commande::TABLE . '
                SET `commentairecommande`="' . mysql_real_escape_string($_SESSION['navig']->commentairecommande) . '"
                WHERE id=' . $commande->id);
            unset($_SESSION['navig']->commentairecommande);
        }
    }

    function post() {
        global $res;

        preg_match_all("`\#COMMENTAIRECOMMANDE`", $res, $cut);
        if(count($cut) > 0) {
            $comment = '';
            if(!empty($_SESSION['navig']->commentairecommande)) {
                $comment = $_SESSION['navig']->commentairecommande;
            }
            $res = str_replace("#COMMENTAIRECOMMANDE", $comment, $res);
        }

        return $res;
    }
    
    public function substitutionsmailcommande(&$txt, $commande) {
        $txt = str_replace("__COMMENTAIRE_COMMANDE__", $this->getCommentaireOnCommand($commande->id), $txt);
        return $txt;
    }

    /*** Fin des pipelines Thelia **/

    public function getCommentaireOnCommand($idCommande) {
        if(!preg_match('/^[0-9]{1,}$/', $idCommande)) return false;

        $result = CacheBase::getCache()->mysql_query('SELECT commentairecommande FROM ' . Commande::TABLE . ' WHERE id=' . $idCommande, $this->link);
        if(!$result) return false;

        return $result[0]->commentairecommande;
    }
}