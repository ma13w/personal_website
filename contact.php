<?php
require_once __DIR__ . '/autoload.php';
if (is_file(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    require_once __DIR__ . '/config.example.php';
}
require_once __DIR__ . '/components/helpers.php';

// ============================================
//  SESSION + RATE LIMIT + CSRF
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- CSRF token generation ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --- Rate limit: max 3 POST per IP ogni 10 minuti ---
$ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$key = 'rl_' . md5($ip);

if (!isset($_SESSION[$key])) {
    $_SESSION[$key] = ['count' => 0, 'first' => time()];
}

if ((time() - $_SESSION[$key]['first']) > 600) {
    $_SESSION[$key] = ['count' => 0, 'first' => time()];
}

$rateLimited = false;
if ($_SESSION[$key]['count'] >= 3) {
    $rateLimited = true;
}

/* ============================================
   FORM HANDLING
   ============================================ */
$success = false;
$error   = false;
$name    = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    // CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }

    // Rate limit check
    if ($rateLimited) {
        http_response_code(429);
        $error = 'Too many requests. Please wait a few minutes.';
    } else {
        $_SESSION[$key]['count']++;

        $name      = trim($_POST['name']      ?? '');
        $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $type      = trim($_POST['type']      ?? 'Automatic');
        $messaggio = trim($_POST['messaggio'] ?? '');

        // 1. Validation
        if (empty($name) || empty($email) || empty($type) || empty($messaggio)) {
            $error = 'Please fill in all required fields.';
        } elseif (strlen($messaggio) < 2) {
            $error = 'Message must be at least 2 characters long.';
        } else {
            $success = true;
        }

        // 2. Send email
        if ($success) {
            $safeName = str_replace(["\r", "\n"], '', $name);
            try {
                foreach ([SMTP_HOST, SMTP_USERNAME, SMTP_PASS, SMTP_FROM_EMAIL, SMTP_TO_EMAIL] as $requiredValue) {
                    if ($requiredValue === '') {
                        throw new RuntimeException('SMTP configuration is incomplete.');
                    }
                }

                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USERNAME;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = SMTP_PORT;

                $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
                $mail->addAddress(SMTP_TO_EMAIL);
                $mail->addReplyTo($email, $safeName);

                $mail->Subject = "New contact form submission from $safeName";
                $mail->Body    = "Name: $name\nEmail: $email\nType: $type\nMessage:\n$messaggio";

                $mail->send();
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log('Contact form mailer error: ' . $e->getMessage());
                $success = false;
                $error = 'Failed to send your message. Please try again later.';
            }
        }

        // 3. Store in database
        if ($success) {
            try {
                $db = new PDO('sqlite:' . __DIR__ . '/data/contacts.db');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $db->exec("CREATE TABLE IF NOT EXISTS contacts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT,
                    email TEXT,
                    type TEXT,
                    message TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )");
                $stmt = $db->prepare("INSERT INTO contacts (name, email, type, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $type, $messaggio]);
            } catch (PDOException $e) {
                error_log('Contact form DB error: ' . $e->getMessage());
            }
        }
    }
}

$isHome              = false;
$cursorHoverSelector = 'a, button, [data-hover]';

$pageTitle = 'Contact';
$pageDesc  = 'Get in touch with Matteo Cali. Available for web development, software projects and collaborations.';
$pageUrl   = 'https://calimatteo.it/contact.php';
require __DIR__ . '/components/head.php';
?>
        /* ============================================
           CONTACT HERO
           ============================================ */
        .contact-hero {
            padding: clamp(6.48rem, 12.96vh, 9.72rem) clamp(1.5rem, 4vw, 4rem) clamp(4rem, 8vh, 7rem);
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: clamp(3rem, 6vw, 8rem);
            align-items: start;
        }

        /* ── Left column - heading + info ── */
        .contact-hero-left {
            position: sticky;
            top: clamp(6rem, 12vh, 9rem);
        }

        .contact-heading {
            font-size: clamp(3.5rem, 8vw, 8rem);
            font-weight: 800;
            line-height: 1.0;
            letter-spacing: -0.03em;
        }

        .contact-heading-line {
            display: block;
            overflow: hidden;
        }

        .contact-heading-line-inner {
            display: block;
            will-change: transform;
        }

        .contact-subtext {
            margin-top: 2rem;
            color: var(--color-text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 340px;
        }

        /* ── Left column - contact info ── */
        .contact-info-list {
            display: flex;
            flex-direction: column;
            margin-top: 3rem;
        }

        .contact-info-item {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 1.5rem;
            padding: 20px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .contact-info-item:first-child {
            border-top: 1px solid var(--color-border);
        }

        .contact-info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
            flex-shrink: 0;
        }

        .contact-info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--color-text-primary);
            text-align: right;
        }

        .contact-info-value a {
            color: inherit;
            text-decoration: none;
            position: relative;
            padding-bottom: 2px;
        }

        .contact-info-value a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--color-text-primary);
            transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .contact-info-value a:hover::after {
            width: 100%;
        }

        /* ============================================
           RIGHT COLUMN - FORM
           ============================================ */
        .contact-hero-right {
            padding-top: 0.5rem;
        }

        .form-section-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
            margin-bottom: 3rem;
            display: block;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 24px 0;
            border-bottom: 1px solid var(--color-border);
            position: relative;
        }

        .form-field label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
        }

        .form-field input,
        .form-field textarea,
        .form-field select {
            background: transparent;
            border: none;
            outline: none;
            font-size: clamp(1rem, 2vw, 1.4rem);
            color: var(--color-text-primary);
            font-family: inherit;
            padding: 4px 0;
            width: 100%;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-field textarea {
            min-height: 140px;
            resize: none;
            line-height: 1.6;
        }

        .form-field select {
            cursor: none;
        }

        .form-field select option {
            background: var(--color-bg);
            color: var(--color-text-primary);
        }

        /* Animated underline on focus */
        .form-field::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0%;
            height: 1.5px;
            background: var(--color-text-primary);
            transition: width 0.4s ease;
        }

        .form-field:focus-within::after {
            width: 100%;
        }

        /* Placeholder colour */
        .form-field input::placeholder,
        .form-field textarea::placeholder {
            color: var(--color-text-muted);
        }

        /* Submit button */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 2.5rem;
        }

        .submit-btn {
            background: transparent;
            border: 1.5px solid var(--color-text-primary);
            border-radius: 999px;
            padding: 14px 36px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
            cursor: none;
            font-family: inherit;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ============================================
           SUCCESS / ERROR MESSAGES
           ============================================ */
        .form-success {
            padding: 2.5rem 0;
            border-top: 1px solid var(--color-border);
        }

        .form-success p:first-child {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.1;
            color: var(--color-text-primary);
        }

        .form-success p:last-child {
            margin-top: 0.75rem;
            font-size: 1rem;
            color: var(--color-text-secondary);
        }

        .form-error {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #c0392b;
            letter-spacing: 0.01em;
        }

        /* ============================================
           AVAILABILITY BAR
           ============================================ */
        .availability-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 40px clamp(1.5rem, 4vw, 4rem);
            border-top: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .availability-dot {
            width: 8px;
            height: 8px;
            background: #4caf50;
            border-radius: 50%;
            flex-shrink: 0;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .contact-hero {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                padding: clamp(5rem, 10vh, 7rem) 20px clamp(3rem, 6vh, 5rem);
            }

            .contact-hero-left {
                position: static;
            }

            .contact-heading {
                font-size: clamp(3rem, 15vw, 5rem);
                line-height: 1.0;
            }

            .contact-subtext {
                max-width: 100%;
                font-size: 0.95rem;
                margin-top: 1.2rem;
            }

            .contact-info-list {
                margin-top: 2rem;
            }

            .contact-info-item {
                padding: 14px 0;
            }

            .contact-info-label {
                font-size: 0.7rem;
            }

            .contact-info-value {
                font-size: 0.88rem;
                text-align: right;
            }

            .form-section-label {
                font-size: 0.65rem;
                margin-bottom: 1.5rem;
            }

            .form-field {
                padding: 18px 0;
            }

            .form-field input,
            .form-field textarea,
            .form-field select {
                font-size: 1rem;
            }

            .form-field textarea {
                min-height: 110px;
            }

            .form-field select {
                cursor: auto;
            }

            .form-actions {
                padding-top: 1.8rem;
            }

            .submit-btn {
                width: 100%;
                justify-content: center;
                padding: 14px 28px;
                font-size: 0.95rem;
                cursor: auto;
            }

            .form-success p:first-child {
                font-size: clamp(1.5rem, 8vw, 2.2rem);
            }

            .availability-bar {
                padding: 24px 20px;
                font-size: 0.82rem;
            }
        }
    </style>
</head>

<body>

<?php require __DIR__ . '/components/nav.php'; ?>

    <!-- ============================================
         CONTACT HERO
         ============================================ -->
    <section class="contact-hero">

        <!-- Left: Heading + sub-text + contact info -->
        <div class="contact-hero-left">
            <h1 class="contact-heading" aria-label="Contact me!">
                <span class="contact-heading-line">
                    <span class="contact-heading-line-inner">Contact me!</span>
                </span>
            </h1>
            <p class="contact-subtext">
                Do you have a project in mind?<br>
                I'll get back to you within 24 hours.
            </p>

            <!-- Contact info -->
            <div class="contact-info-list">
                <div class="contact-info-item">
                    <span class="contact-info-label">Email</span>
                    <span class="contact-info-value">
                        <a href="mailto:me@calimatteo.it" data-hover>me@calimatteo.it</a>
                    </span>
                </div>
                <div class="contact-info-item">
                    <span class="contact-info-label">Github</span>
                    <span class="contact-info-value">
                        <a href="https://github.com/ma13w" target="_blank" rel="noopener" data-hover>@ma13w</a>
                    </span>
                </div>
                <div class="contact-info-item">
                    <span class="contact-info-label">LinkedIn</span>
                    <span class="contact-info-value">
                        <a href="https://linkedin.com/in/matteo-cali" target="_blank" rel="noopener" data-hover>Matteo Cali</a>
                    </span>
                </div>
                <!-- <div class="contact-info-item">
                    <span class="contact-info-label">P.IVA</span>
                    <span class="contact-info-value">IT01234567890</span>
                </div> -->
            </div>
        </div>

        <!-- Right: Form -->
        <div class="contact-hero-right">

            <span class="form-section-label">Send me a message</span>

            <?php if ($success): ?>
                <!-- Success message -->
                <div class="form-success" id="formSuccess">
                    <p>Thank you, <?= e($name) ?>!</p>
                    <p>I will contact you as soon as possible.</p>
                </div>
            <?php else: ?>
                <!-- Form -->
                <form method="POST" action="/contact.php" id="contactForm" class="contact-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="form-field">
                        <label for="name">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Your name"
                            required
                            value="<?= e($_POST['name'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="your@email.com"
                            required
                            value="<?= e($_POST['email'] ?? '') ?>"
                        >
                    </div>

                    <!-- <div class="form-field">
                        <label for="type">Type project</label>
                        <select id="type" name="type">
                            <option value="" disabled <?= empty($_POST['type']) ? 'selected' : '' ?>>Kies een optie</option>
                            <option value="Nieuwe website" <?= ($_POST['type'] ?? '') === 'Nieuwe website' ? 'selected' : '' ?>>Voglio un nuovo sito web</option>
                            <option value="Bestaand design" <?= ($_POST['type'] ?? '') === 'Bestaand design' ? 'selected' : '' ?>>Ho un progetto esistente</option>
                            <option value="Anders bespreken" <?= ($_POST['type'] ?? '') === 'Anders bespreken' ? 'selected' : '' ?>>Voglio discutere</option>
                        </select>
                    </div> -->

                    <div class="form-field">
                        <label for="messaggio">Message</label>
                        <textarea
                            id="messaggio"
                            name="messaggio"
                            placeholder="Tell me about..."
                            required
                        ><?= e($_POST['messaggio'] ?? '') ?></textarea>
                    </div>

                    <?php if ($error): ?>
                        <p class="form-error"><?= e($error) ?></p>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn" data-hover>
                            Send message &rarr;
                        </button>
                    </div>

                </form>
            <?php endif; ?>

        </div>

    </section>

    <!-- ============================================
         AVAILABILITY BAR
         ============================================ -->
    <div class="availability-bar">
        <div class="availability-dot"></div>
        <span>
    <?php
    $quotes = [
        "Let's build something amazing together",
        "Your idea deserves to come to life",
        "Great projects start with a single message",
        "I'm excited to hear your vision",
        "Let's turn your concept into reality",
        "Innovation begins with conversation",
        "Your project is my next challenge",
        "Together, we'll create something extraordinary",
        "Every great website starts here",
        "Let's make your digital dreams real",
        "Your project, my passion",
        "Collaboration is the key to success",
        "Let's craft a unique online experience",
        "Your vision, my expertise",
        "Let's create something that stands out",
    ];
    echo $quotes[array_rand($quotes)];
    ?>
    </span>    
    </div>

<?php require __DIR__ . '/components/footer.php'; ?>

<?php require __DIR__ . '/components/scripts.php'; ?>

        /* ============================================
           HERO HEADING - Clip-path line-by-line reveal
           ============================================ */
        gsap.from('.contact-heading-line-inner', {
            clipPath: 'inset(0 0 100% 0)',
            y: 20,
            duration: 1.0,
            ease: 'power4.out',
            stagger: 0.12,
            delay: 0.2,
            clearProps: 'all'
        });

        /* ============================================
           HERO SUB-TEXT - Fade in
           ============================================ */
        gsap.from('.contact-subtext', {
            autoAlpha: 0,
            y: 15,
            duration: 0.8,
            ease: 'power2.out',
            delay: 0.65,
            clearProps: 'all'
        });

        /* ============================================
           CONTACT INFO ITEMS - Stagger reveal
           ============================================ */
        gsap.from('.contact-info-item', {
            autoAlpha: 0,
            y: 20,
            duration: 0.7,
            ease: 'power2.out',
            stagger: 0.1,
            delay: 0.4,
            clearProps: 'all'
        });

        /* ============================================
           FORM LABEL - Fade in
           ============================================ */
        gsap.from('.form-section-label', {
            autoAlpha: 0,
            y: 10,
            duration: 0.6,
            ease: 'power2.out',
            delay: 0.5,
            clearProps: 'all'
        });

        /* ============================================
           FORM FIELDS - Stagger reveal
           ============================================ */
        gsap.from('.form-field', {
            autoAlpha: 0,
            y: 15,
            duration: 0.6,
            ease: 'power2.out',
            stagger: 0.08,
            delay: 0.55,
            clearProps: 'all'
        });

        /* ============================================
           SUBMIT BUTTON - Fade in
           ============================================ */
        gsap.from('.form-actions', {
            autoAlpha: 0,
            y: 10,
            duration: 0.5,
            ease: 'power2.out',
            delay: 0.9,
            clearProps: 'all'
        });

        /* ============================================
           SUBMIT BUTTON - GSAP hover
           ============================================ */
        (() => {
            const submitBtn = document.querySelector('.submit-btn');
            if (!submitBtn) return;

            submitBtn.addEventListener('mouseenter', () => {
                gsap.to(submitBtn, {
                    backgroundColor: 'var(--color-text-primary)',
                    color: 'var(--color-accent-text)',
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });

            submitBtn.addEventListener('mouseleave', () => {
                gsap.to(submitBtn, {
                    backgroundColor: 'transparent',
                    color: 'var(--color-text-primary)',
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });
        })();

        /* ============================================
           AVAILABILITY BAR - Scroll reveal
           ============================================ */
        gsap.from('.availability-bar', {
            autoAlpha: 0,
            y: 10,
            duration: 0.6,
            ease: 'power2.out',
            clearProps: 'all',
            scrollTrigger: {
                trigger: '.availability-bar',
                start: 'top 92%'
            }
        });

        /* ============================================
           SUCCESS MESSAGE - GSAP fade in
           ============================================ */
        (() => {
            const msg = document.getElementById('formSuccess');
            if (!msg) return;
            gsap.set(msg, { autoAlpha: 0, y: 20 });
            gsap.to(msg, {
                autoAlpha: 1,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                delay: 0.3,
                clearProps: 'all'
            });
        })();

<?php require __DIR__ . '/components/end.php'; ?>
