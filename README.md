# LumiDev_ExtendedAdminGrid

## Description
Le module LumiDev Extended Admin Grid ajoute des colonnes clés aux grilles des factures, commandes et avoirs Magento 2. 
Il simplifie l’analyse comptable et le suivi administratif sans requérir d’export manuel ou de développements spécifiques.
## Fonctionnalités
- Ajoute la colonne numéro de TVA client dans la grille des commandes
- Ajoute les colonnes TVA appliquée, Numéro de TVA client, Pays client, Remise appliquée dans la grille des factures et avoirs.
- Compatible tri et filtre natifs Magento
- Aucun impact sur les performances backoffice
- Développé selon les standards Magento 2 (plugins, dependency injection, pas d’override de core)


## Installation

### Via composer
1.composer require lumidev/extended-admin-grid
2. Lancer :
   ```bash
   bin/magento module:enable LumiDev_ExtendedAdminGrid
   bin/magento setup:upgrade
   bin/magento cache:flush

### Via `app/code` :
1. Copier le dossier `LumiDev/ExtendedAdminGrid` dans `app/code/`.
2. Lancer :
   ```bash
   bin/magento module:enable LumiDev_ExtendedAdminGrid
   bin/magento setup:upgrade
   bin/magento cache:flush

### Compatibilté
Magento versions : 2.4.3 – 2.4.7
Éditions : Community, Enterprise
PHP versions : 7.4 – 8.2

### Mentions légales

© 2025 LumiDev. Tous droits réservés.
