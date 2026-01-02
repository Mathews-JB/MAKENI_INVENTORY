# Hwo to Configure Email in XAMPP (Gmail Example)

Since I cannot access your system files directly, please follow these steps to configure XAMPP to send emails using your Gmail account.

## Prerequisites
1.  **Gmail Account**: You need a Gmail account.
2.  **App Password**: You **cannot** use your regular password. You must generate an "App Password".
    *   Go to [Google Account Security](https://myaccount.google.com/security).
    *   Enable **2-Step Verification** if not already enabled.
    *   Search for "App Passwords" (or go to 2-Step Verification > App Passwords).
    *   Create a new one named "XAMPP".
    *   **Copy the 16-character code**. You will need this.

---

## Step 1: Configure PHP.ini

1.  Open the file: `C:\xampp\php\php.ini` in your text editor (Notepad, VS Code, etc.).
2.  Search for `[mail function]`.
3.  Find and **comment out** (add `;` at the start) the generic configuration lines if they are active:
    ```ini
    ; SMTP=localhost
    ; smtp_port=25
    ```
4.  Find and **uncomment** (remove `;`) the `sendmail_path` line and ensure it looks like this:
    ```ini
    sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
    ```
5.  Save the file.

---

## Step 2: Configure sendmail.ini

1.  Open the file: `C:\xampp\sendmail\sendmail.ini`.
2.  Replace the content (or update the settings) to match this configuration:

    ```ini
    [sendmail]
    smtp_server=smtp.gmail.com
    smtp_port=587
    error_logfile=error.log
    debug_logfile=debug.log
    auth_username=YOUR_GMAIL_ADDRESS@gmail.com
    auth_password=YOUR_16_CHAR_APP_PASSWORD
    force_sender=YOUR_GMAIL_ADDRESS@gmail.com
    ```
    *   **smtp_server**: `smtp.gmail.com`
    *   **smtp_port**: `587` (TLS)
    *   **auth_username**: Your full Gmail address.
    *   **auth_password**: The 16-character **App Password** you generated (NOT your login password).
    *   **force_sender**: Your full Gmail address.

3.  Save the file.

---

## Step 3: Restart Apache

1.  Open **XAMPP Control Panel**.
2.  Click **Stop** next to Apache.
3.  Wait a moment, then click **Start**.

## Step 4: Test

1.  Go to your IVM application.
2.  Create a new User Invitation.
3.  The email should now appear in the recipient's inbox (check Spam folder too just in case).

> **Note**: If you see errors, check `C:\xampp\sendmail\error.log` for details.
