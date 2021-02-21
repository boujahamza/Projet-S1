# Projet-S1

## Introduction

Ce projet est un site d'organisation de compétitions. Il permet:
- aux organisateurs de créer des "compétitions". C'est-à-dire des pages dans lequelles ils peuvent voir une liste des participants et leurs emails. Ils peuvent aussi supprimer les participants, et manipuler un simple système de points.
- Aux participants de rejoindre des compétitions dont ils possédent l'identifiant et, s'il est demandé, le mot de passe.

## Les modifications à apporter avant d'héberger le site

1. Dans le fichier 'SQLqueries.php', remplacer les variables ($host,$username,$db_password,$db_name) par le nom de l'hôte, le nom d'utilisateur, le nom d'utilisateur, le mot de passe, et le nom de la base de données, respectivement.
2. Le fichier "navbar.php" suppose que les fichiers se trouvent dans le fichier root du site pour indiquer la page courante (grace à un script dans le fichier lui-même). Si on choisit de modifier le dossier où se trouvent ces fichiers, les liens spécifiés dans les attributs "brefs" du fichier "navbar.php" doivent aussi être modifié pour que cette fonctionnalité marche encore.
