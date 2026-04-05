-- Vider les données de l'app Congrès Analim (repartir de zéro)
-- Base : congresanalim (adapter le nom si besoin)
-- À exécuter dans phpMyAdmin (onglet SQL) ou : mysql -u root congresanalim < sql/vider_donnees.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE participer;
TRUNCATE TABLE activite;
TRUNCATE TABLE congressiste_b;

SET FOREIGN_KEY_CHECKS = 1;

-- Après exécution :
-- 1) Crée le premier compte via « Inscription » : il aura en général IDCongressiste = 1
--    → c’est celui qui voit « Ajouter une activité » (admin dans layout.php).
-- 2) Ou inscris-toi puis ajoute des activités avec ce compte si tu as mis à jour la condition admin.
