-- Ensure app user has full access to the database (runs only on first volume init)
GRANT ALL PRIVILEGES ON `venuefinder`.* TO 'venuefinder'@'%';
FLUSH PRIVILEGES;
