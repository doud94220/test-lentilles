<?php

//// --------- Etape 1 : Traiter le fichier JSON reçu -----------

//Lire les données dans le fichier d'entrée 'data.json'
$dataJsonFileContents = file_get_contents("../input/data.json");

//Mettre les données lues dans un tableau (array PHP)
$arrayClientsData = json_decode($dataJsonFileContents, true);

//Creer 2 tableaux pour bien séparer les clients des commandes
$arrayClients = $arrayClientsData['clients'];
$arrayOrders = $arrayClientsData['orders'];

//On va créer un tableau des commandes avec en KEY les id des clients
$arrayOrdersWithGoodKeys = [];

foreach ($arrayOrders as $oneOrder) {
    $arrayOrdersWithGoodKeys[$oneOrder['client_id']] = $oneOrder;
}

//// --------- Etape 2 : Fabriquer le tableau PHP contenant toutes les informations requises en sortie ---------
$arrayAllClientsInformation = [];

foreach ($arrayClients as $currentClient) {
    $idCurrentClient = $currentClient['id'];
    $currentOrder = $arrayOrdersWithGoodKeys[$idCurrentClient];
    $arrayAllCurrentClientInformation = [];
    $arrayAllCurrentClientInformation['client_id'] = $currentClient['id'];
    $arrayAllCurrentClientInformation['client_name'] = $currentClient['name'];
    $arrayAllCurrentClientInformation['last_order_id'] = $currentOrder['id']; //Là on envisage pas le cas où il y a plusieurs commandes pour le client en cours de process
    $arrayAllCurrentClientInformation['last_order_date'] = $currentOrder['date'];

    foreach ($currentOrder['details'] as $currentOrderDetails) {
        if (array_key_exists("duration", $currentOrderDetails)) {
            $arrayAllCurrentClientInformation['last_order_duration'] = $currentOrderDetails['duration'];
            $orderDateWithDateFormat = new DateTime($currentOrder['date']);
            $renewDate = $orderDateWithDateFormat->modify("+" . $currentOrderDetails['duration'] . " day");
            $renewDateStringFormat = $renewDate->format('Y-m-d');
            $arrayAllCurrentClientInformation['expected_renew_date'] = $renewDateStringFormat;
            array_push($arrayAllClientsInformation, $arrayAllCurrentClientInformation);
        }
    }
}

//// --------- Etape 3 : Convertir le tableau PHP en JSON pour être au bon format pour la sortie --------
$arrayClientsOutputJsonFormat = json_encode($arrayAllClientsInformation, true);

if (file_put_contents('renew.json', $arrayClientsOutputJsonFormat)) {
    echo "Fichier de sortie renew.json fabriqué avec succès !<br><br>";
} else {
    echo "Problème lors de l'écriture du fichier de sortie renew.json...<br><br>";
}



//// -------------------------------------- POUR ALLER PLUS LOIN --------------------------------------

/*
   Cette fonction sera appelé tous les jours par un JOB, et vérifiera pour chaque client si on est à J-7 de la date de renouvelellement
*/

//Declaration de la fonction
function prevenirClientDateRenouvellentApproche()
{
    //Lire les données dans le fichier 'renewTestFunction.json'
    $dataRenewJsonFileContents = file_get_contents("renewTestFunction.json");

    //Mettre les données lues dans un tableau (array PHP)
    $arrayClientsRenewData = json_decode($dataRenewJsonFileContents, true);

    foreach ($arrayClientsRenewData as $key => $oneClientRenewData) {

        $idClient = $oneClientRenewData['client_id'];
        $dateRenouvellement = $oneClientRenewData['expected_renew_date'];
        $dateRenouvellementFormatDate = new DateTime($dateRenouvellement);
        $dateRenouvellementFormatDateMoinsSeptJours = $dateRenouvellementFormatDate->modify('-7 day')->format('Ymd');

        // Recuperation de la date du jour
        $dateDuJour = new DateTime();
        $dateDuJourFormatee = $dateDuJour->format('Ymd');

        if ($dateRenouvellementFormatDateMoinsSeptJours == $dateDuJourFormatee) {
            echo "Il faut envoyer un mail au client " . $idClient . " pour lui dire que dans 7 jours on arrive à son renouvellement.<br>";
        }
    }
}

//Execution de la fonction
prevenirClientDateRenouvellentApproche();
