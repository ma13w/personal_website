# New Portfolio

Portfolio in PHP with a contact form, SQLite storage for submissions, and PHPMailer for outgoing email.

## Requirements

- PHP 8.2 or newer
- Composer
- A writable `data/` directory
- SMTP credentials for the contact form

## Local setup

1. Install dependencies:

   ```bash
   composer install
   ```

2. Configure SMTP through environment variables or by creating a local `.env` file from [.env.example](.env.example) for Docker, or a local [config.php](config.php) from [config.example.php](config.example.php) for non-Docker runs:

   - `SMTP_HOST`
   - `SMTP_PORT`
   - `SMTP_USERNAME`
   - `SMTP_PASS`
   - `SMTP_FROM_EMAIL`
   - `SMTP_FROM_NAME`
   - `SMTP_TO_EMAIL`

3. If you use Docker, copy `.env.example` to `.env` and fill in the SMTP values before starting the stack.

4. Make sure `data/` is writable so the contact form can create `data/contacts.db`.

## License

See [LICENSE](LICENSE).
