<?php

include 'config/db_connector.php';
/**
 * Verifie si le panier existe, le crée sinon
 * @return boolean
 */
function createBasket(){
   if (!isset($_SESSION['panier'])){
      $_SESSION['basket']=array();
      $_SESSION['basket']['itemName'] = array();
      $_SESSION['basket']['itemPrice'] = array();
      $_SESSION['basket']['lock'] = false;
   }
   return true;
}


/**
 * Ajoute un article dans le basket
 * @param string $itemName
 * @param float $productPrice
 * @return void
 */
function addItem_basket($itemName,$productPrice,$lock){

   //Si le basket existe
   if (createBasket() && !isLocked())
   {
      //Si le produit existe déjà on affiche un message d'erreur
      $itemPosition = array_search($itemName,  $_SESSION['basket']['itemName']);

      if ($itemPosition !== false)
      {
         echo "Le véhicule est déjà présent dans votre panier.";
      }
      else
      {
         //Sinon on ajoute le produit
         array_push( $_SESSION['basket']['itemName'],$itemName);
         array_push( $_SESSION['basket']['productPrice'],$productPrice);
      }
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}


/**
 * Supprime un article du panier
 * @param $itemName
 * @return unknown_type
 */
function delItem_basket($itemName){
   //Si le panier existe
   if (createBasket() && !isLocked())
   {
      //Nous allons passer par un panier temporaire
      $tmp=array();
      $tmp['itemName'] = array();
      $tmp['productPrice'] = array();
      $tmp['lock'] = $_SESSION['basket']['lock'];

      for($i = 0; $i < count($_SESSION['basket']['itemName']); $i++)
      {
         if ($_SESSION['basket']['itemName'][$i] !== $itemName)
         {
            array_push( $tmp['itemName'],$_SESSION['basket']['itemName'][$i]);
            array_push( $tmp['productPrice'],$_SESSION['basket']['productPrice'][$i]);
         }

      }
      //On remplace le panier en session par notre panier temporaire à jour
      $_SESSION['basket'] =  $tmp;
      //On efface notre panier temporaire
      unset($tmp);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}


/**
 * Montant total du panier
 * @return int
 */
function totalPrice(){
   $total=0;
   for($i = 0; $i < count($_SESSION['basket']['itemName']); $i++)
   {
      $total += $_SESSION['basket'] * $_SESSION['basket']['productPrice'][$i];
   }
   return $total;
}


/**
 * Fonction de suppression du panier
 * @return void
 */
function delBasket(){
   unset($_SESSION['basket']);
}

/**
 * Permet de savoir si le panier est verrouillé
 * @return booleen
 */
function isLocked(){
   if (isset($_SESSION['basket']) && $_SESSION['basket']['Lock'])
   return true;
   else
   return false;
}

/**
 * Compte le nombre d'articles différents dans le panier
 * @return int
 */
function countItems()
{
   if (isset($_SESSION['basket']))
   return count($_SESSION['basket']['itemName']);
   else
   return 0;

}

?>