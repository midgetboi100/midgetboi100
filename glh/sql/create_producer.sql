INSERT INTO Producers (Name, Location, FarmingMethod, Email, Password)
VALUES (
    'Green Farm Ltd',
    'Surrey',
    'Organic',
    'producer@test.com',
    '$2y$10$yourhashedpassword'
);
-- the current data is a template, to use this, import into the database to create a producer account.
--get the password by running the hash.php file
--http://localhost/glh/hash.php