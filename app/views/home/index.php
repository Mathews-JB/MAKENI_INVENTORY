<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School IVM - Educational Resource Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/landing.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo URL_ROOT; ?>" class="logo" style="display: flex; align-items: center; gap: 15px; text-decoration: none;">
                <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo" style="height: 60px; width: auto;">
                <span style="font-size: 1.5rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.5px;">MAKENI ISLAMIC <span style="color:var(--primary)">INVENTORY</span></span>
            </a>
            
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#customers">Customers</a>
                <a href="#pricing">Pricing</a>
                <a href="#about">About</a>
            </div>

            <div class="nav-cta">
                <a href="<?php echo URL_ROOT; ?>/auth/login" class="btn btn-outline">Log In</a>
                <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <style>
            .hero {
                position: relative;
                overflow: hidden;
            }

            .hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.6));
                z-index: 1;
            }

            .hero-bg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                animation: slideshow 20s infinite;
                z-index: 0;
            }

            @keyframes slideshow {
                0% {
                    background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 1;
                }
                25% {
                    background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 1;
                }
                30% {
                    opacity: 0;
                }
                35% {
                    background-image: url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 0;
                }
                40% {
                    opacity: 1;
                }
                65% {
                    background-image: url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 1;
                }
                70% {
                    opacity: 0;
                }
                75% {
                    background-image: url('https://images.unsplash.com/photo-1565793298595-6a879b1d9492?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 0;
                }
                80% {
                    opacity: 1;
                }
                95% {
                    background-image: url('https://images.unsplash.com/photo-1565793298595-6a879b1d9492?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
                    opacity: 1;
                }
                100% {
                    opacity: 0;
                }
            }

            .hero .container {
                position: relative;
                z-index: 2;
            }
        </style>
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <h1 style="color: #ffffff !important; text-shadow: 0 3px 10px rgba(0, 0, 0, 0.8), 0 6px 20px rgba(0, 0, 0, 0.6) !important;">Empower Your School with <br> Intelligent Asset Tracking</h1>
                <p style="color: #ffffff !important; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7) !important;">The all-in-one platform for modern educational institutions. Track textbooks, labs, and equipment across all campuses in real-time.</p>
                <div class="hero-actions">
                    <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary btn-large">Get Started Free</a>
                    <a href="<?php echo URL_ROOT; ?>/auth/login" class="btn btn-outline btn-large">Sign In</a>
                </div>
            </div>
            <div class="hero-image">
                <!-- Unsplash: Dashboard/Analytics Concept -->
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" alt="School IVM Dashboard Interface">
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="social-proof" style="background: #0F172A !important; border-top: 1px solid rgba(255, 255, 255, 0.1) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;">
        <div class="container">
            <p style="color: rgba(255, 255, 255, 0.7) !important;">Trusted by leading educational institutions</p>
            <div class="logos">
                <i class="fas fa-layer-group" title="Company A" style="color: rgba(255, 255, 255, 0.6) !important;"></i>
                <i class="fas fa-cube" title="Company B" style="color: rgba(255, 255, 255, 0.6) !important;"></i>
                <i class="fas fa-globe-africa" title="Company C" style="color: rgba(255, 255, 255, 0.6) !important;"></i>
                <i class="fas fa-industry" title="Company D" style="color: rgba(255, 255, 255, 0.6) !important;"></i>
                <i class="fas fa-truck-loading" title="Company E" style="color: rgba(255, 255, 255, 0.6) !important;"></i>
            </div>
        </div>
    </section>

    <!-- Video Section Removed -->

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            
            <!-- Feature 1: Multi-Campus -->
            <div class="feature-row">
                <div class="feature-text">
                    <h2>Multi-Campus Management</h2>
                    <p>Stop jumping between tabs. Manage materials for all your schools and associated campuses from a single, unified command center.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Centralized supply visibility</li>
                        <li><i class="fas fa-check-circle"></i> Inter-campus transfers</li>
                        <li><i class="fas fa-check-circle"></i> Department-specific allocation</li>
                    </ul>
                </div>
                <div class="feature-image">
                    <!-- Unsplash: School/Library -->
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Campus Management">
                </div>
            </div>

            <!-- Feature 2: Analytics (Reversed) -->
            <div class="feature-row reverse">
                <div class="feature-text">
                    <h2>School Material Analytics</h2>
                    <p>Make data-driven educational decisions. Our powerful reporting engine gives you instant insights into material usage trends and supply levels.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Visual usage charts</li>
                        <li><i class="fas fa-check-circle"></i> Low material alerts</li>
                        <li><i class="fas fa-check-circle"></i> Academic reporting</li>
                    </ul>
                </div>
                <div class="feature-image">
                    <!-- Unsplash: Data/Charts -->
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Material Analytics">
                </div>
            </div>

            <!-- Feature 3: Secure RBAC -->
            <div class="feature-row">
                <div class="feature-text">
                    <h2>Academic Integrity & Security</h2>
                    <p>Your institutional data is precious. We protect it with enterprise-grade security standards, tailored for school roles.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Faculty-Based Access Control</li>
                        <li><i class="fas fa-check-circle"></i> Secure audit trails</li>
                        <li><i class="fas fa-check-circle"></i> Data isolation by school</li>
                    </ul>
                </div>
                <div class="feature-image">
                    <!-- Unsplash: Security/Tech -->
                    <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="System Security">
                </div>
            </div>

        </div>
    </section>

    <!-- Statistics -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>500+</h3>
                    <p>Schools Onboarded</p>
                </div>
                <div class="stat-item">
                    <h3>1M+</h3>
                    <p>Educational Materials Tracked</p>
                </div>
                <div class="stat-item">
                    <h3>99.9%</h3>
                    <p>System Uptime</p>
                </div>
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Dedicated Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.6)), url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'); background-size: cover; background-position: center; background-attachment: fixed; color: white; padding: 8rem 0;">
        <div class="cta-container">
            <h2 style="color: white;">Ready to streamline your school's inventory?</h2>
            <p style="color: rgba(255, 255, 255, 0.95);">Join leading institutions who have upgraded from spreadsheets to School IVM.</p>
            <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary btn-large" style="background: white; color: #2563EB; font-weight: 700;">Start Managing Materials</a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #0F172A !important; border-top: 1px solid rgba(255, 255, 255, 0.1) !important;">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo" style="height: 40px; width: auto; filter: brightness(0) invert(1);">
                        <h4 style="color: #ffffff !important; margin-bottom: 0;">MAKENI ISLAMIC INVENTORY</h4>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.7) !important;">Empowering educational institutions with intelligent inventory solutions.</p>
                </div>
                <div class="footer-links">
                    <h5 style="color: #ffffff !important;">Resources</h5>
                    <ul>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Features</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Help Center</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Guides</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h5 style="color: #ffffff !important;">Institution</h5>
                    <ul>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">About Us</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Contact</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Partners</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h5 style="color: #ffffff !important;">Legal</h5>
                    <ul>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Privacy</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Terms</a></li>
                        <li><a href="#" style="color: rgba(255, 255, 255, 0.6) !important;">Security</a></li>
                    </ul>
                </div>
            </div>
            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1); text-align: center; color: rgba(255, 255, 255, 0.5); font-size: 0.9rem;">
                &copy; <?php echo date('Y'); ?> School IVM System. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
