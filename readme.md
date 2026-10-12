# Kara David's Wish Note — QAT Assignment

## Files
- `TestCase.txt` — manual test cases and expected results.
- `webapp-generation-prompt.txt` — prompt describing the educational web app.
- `test-webapp.php` — sample PHP web application using PDO prepared statements.

## Important
This is a classroom sample. It is not connected to GitHub or a live database automatically. You must configure your local PHP/MySQL environment and use the table schema that matches the application before testing.

## Example table schema
The PHP sample expects a database named `kara_david_wishnote` and a table with these columns:

```sql
CREATE DATABASE IF NOT EXISTS kara_david_wishnote;
USE kara_david_wishnote;

CREATE TABLE IF NOT EXISTS main_corrupt_politicians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    WishNote TEXT NOT NULL,
    Location VARCHAR(150) NOT NULL
);
```

If your lesson's provided database uses different column names, adjust the sample code to match that schema.

## Run locally
1. Start your local MySQL and PHP services.
2. Create the database and table using the example SQL above.
3. Update the database username/password in `test-webapp.php` if needed.
4. Place the PHP file in your local web server's document root.
5. Open it through your local server URL (for example, `http://localhost/test-webapp.php`).
6. Submit a sample wish note and confirm the Location value is saved.

## Test focus
The key bug discussed in the lesson is that the `Location` column can be omitted from an SQL `INSERT` statement or its bound parameters. Verify that the SQL column list, placeholders, and values all include `Location`.
