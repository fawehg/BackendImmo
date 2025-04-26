<footer class="main-footer" role="contentinfo">
  <div class="footer-container">
    <div class="footer-content">
      <div class="footer-brand">
        <img src="{{ asset('admin_assets/img/hh.png') }}" alt="B2C Logo" class="footer-logo">
        <p class="footer-slogan">Connecter, Innover, Prospérer</p>
      </div>
      <div class="footer-social">
        <a href="https://twitter.com" target="_blank" class="social-icon" aria-label="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" class="social-icon" aria-label="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="https://facebook.com" target="_blank" class="social-icon" aria-label="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
      </div>
      <div class="footer-copyright">
        <span>Copyright © B2C {{ now()->year }} - Tous droits réservés</span>
      </div>
    </div>
  </div>
  <style>
    /* Footer Styles */
    .main-footer {
      background: linear-gradient(180deg, rgba(10, 14, 26, 0.95), rgba(30, 41, 59, 0.85));
      backdrop-filter: blur(12px);
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      color: var(--light);
      position: fixed;
      bottom: 0;
      left: var(--sidebar-width);
      right: 0;
      z-index: 900;
      font-family: 'Manrope', sans-serif;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
      transition: left var(--transition);
    }

    .sidebar.collapsed ~ .main-footer {
      left: var(--sidebar-collapsed-width);
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1.5rem;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 1rem;
    }

    .footer-brand {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }

    .footer-logo {
      width: 100px;
      height: auto;
      filter: drop-shadow(0 0 5px rgba(0, 221, 235, 0.5));
      transition: var(--transition);
    }

    .footer-logo:hover {
      transform: scale(1.1);
      filter: drop-shadow(0 0 10px rgba(0, 221, 235, 0.7));
    }

    .footer-slogan {
      font-size: 1rem;
      color: #a5b4fc;
      font-weight: 400;
      letter-spacing: 0.05em;
      text-shadow: 0 0 5px rgba(0, 221, 235, 0.3);
    }

    .footer-social {
      display: flex;
      gap: 1.5rem;
    }

    .social-icon {
      color: var(--light);
      font-size: 1.5rem;
      background: rgba(255, 255, 255, 0.1);
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: var(--transition);
      text-decoration: none;
    }

    .social-icon:hover {
      color: var(--primary);
      background: rgba(0, 221, 235, 0.2);
      box-shadow: var(--glow);
      transform: translateY(-3px);
    }

    .footer-copyright {
      font-size: 0.9rem;
      color: var(--light);
      font-weight: 500;
      letter-spacing: 0.03em;
      text-shadow: 0 0 3px rgba(255, 255, 255, 0.2);
    }

    .footer-copyright span {
      opacity: 0.9;
      transition: var(--transition);
    }

    .footer-copyright:hover span {
      opacity: 1;
      color: var(--primary);
      text-shadow: 0 0 5px rgba(0, 221, 235, 0.5);
    }

    /* Animation for Footer Elements */
    .footer-content > * {
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .footer-brand { animation-delay: 0.2s; }
    .footer-social { animation-delay: 0.4s; }
    .footer-copyright { animation-delay: 0.6s; }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-footer {
        left: var(--sidebar-collapsed-width);
      }

      .footer-container {
        padding: 1rem;
      }

      .footer-logo {
        width: 80px;
      }

      .footer-slogan {
        font-size: 0.9rem;
      }

      .social-icon {
        font-size: 1.2rem;
        width: 35px;
        height: 35px;
      }

      .footer-copyright {
        font-size: 0.8rem;
      }
    }

    @media (max-width: 576px) {
      .footer-social {
        gap: 1rem;
      }

      .footer-logo {
        width: 60px;
      }

      .footer-slogan {
        font-size: 0.8rem;
      }
    }
  </style>
</footer>