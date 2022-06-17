Challenge Lentillesmoinscheres
------------------------------

## Intro

Créez une pull request avec votre solution.

Le code doit être écrit en PHP.

## Enoncé

Nous souhaitons générer un fichier json contenant pour chaque client, la date de renouvellement calculée à laquelle le client n'aura plus de lentilles si possible.
La date de renouvellement se calcule en ajoutant la durée de port `duration` (en jours) à la date de dernière commande.

## Résultat attendu

Les données initiales sont présentes dans `data.json`, écrivez le code qui génère un fichier `renew.json` contenant la liste des renouvellements pour chaque client.
Un renouvellement doit avoir la structure suivante :
```

    {
        "client_id": 1,
        "client_name": Toto,
        "last_order_id": 1,
        "last_order_date": 2022-01-01,
        "last_order_duration": 30,
        "expected_renew_date": 2022-01-31,
    }

```

## Pour aller plus loin

Nous souhaiterions prévenir les clients 7 jours avant la date de renouvellement.
Quelle solution pourrions-nous mettre en place pour répondre à ce nouveau besoin ? 
