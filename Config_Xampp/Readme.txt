Consignes :

* WARNING Le site a été créé avec une version php : 8.2.12, des problèmes de compatibilité pourraient apparaitre si vous n'utilisez pas cette version (notamment avec Mongodb, pensez à vérifier votre fichier "php_mongodb.dll" dans "Xampp/php/ext" afin qu'il corresponde à la bonne version de votre php)

* Ajouter la ligne suivante au fichier php.ini situé au chemin : "CheminInstallationXampp\Xampp\php"

extension=php_mongodb.dll

* Ajouter la ligne suivante au fichier Hosts situé au chemin "C:\Windows\System32\drivers\etc\hosts" :

127.0.0.1 arcadia.local

* Copier coller les fichier du dossier Xampp dans votre installation Xampp
* Remplacer les lignes "D:/Projet/Studi _Graduate_Flutter/ECF_David Brocherie/DB_ECF_Studi_Appli_Web" && "D:/Projet/Studi _Graduate_Flutter/ECF_David Brocherie/DB_ECF_Studi_Appli_Web" du fichier "httpd-vhosts.conf" par le chemin d'accès du dossier dans lequel se situe l'arborescence de dossier du site web (Ressources,Script,Site etc).

* Pensez à vérifier et à configurer les accès de sécurité du dossier par rapport à votre configuration (par défaut, vous devez ajouter les permission d'accès au groupe "tout le monde")
