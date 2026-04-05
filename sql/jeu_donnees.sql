-- =============================================================================
-- Jeux de données — uniquement hotel, session, organismepayeur
-- Base : congresanalim
-- Les clés primaires sont laissées à l’AUTO_INCREMENT (pas de colonne ID dans les INSERT).
-- =============================================================================

SET NAMES utf8mb4;

INSERT INTO organismepayeur (nomOrganisme, adresseOrganisme, mailOrganisme, type) VALUES
('CNRS Délégation régionale', '3 rue Michel-Ange 75016 Paris', 'contact.dr@cnrs.fr', 'Public'),
('Université Paris-Saclay', 'Plateau de Saclay 91190', 'bourse.congres@universite.fr', 'Public'),
('LaboPharma Industries', 'Parc d''activités Lyon Est', 'partenariats@labopharma.fr', 'Privé');

INSERT INTO hotel (nomHotel, adresseHotel, categorie, prixHotel, prixDej, chambreDispo) VALUES
('Hôtel des Congrès', '12 av. des Scientifiques 69000 Lyon', '4 étoiles', 125.00, 18.50, 42),
('Résidence Analim', '8 quai du Rhône 69002 Lyon', '3 étoiles', 89.00, 14.00, 28),
('Campus Guest House', 'Campus LyonTech 69100', 'Affaires', 72.00, 12.00, 15);

INSERT INTO session (dateSession, descriptionSession, prixSession) VALUES
('2026-06-10 09:00:00', 'Ouverture & conférence plénière', 0.00),
('2026-06-10 14:00:00', 'Atelier méthodes analytiques', 45.00),
('2026-06-11 10:30:00', 'Table ronde industries & recherche', 35.00),
('2026-06-11 16:00:00', 'Session posters & networking', 0.00);
