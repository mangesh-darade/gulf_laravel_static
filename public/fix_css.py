import os

filepath = "/Users/designer/Downloads/gulf_landing_modular_v3 (1)/css/gulf-landing.css"

with open(filepath, 'r') as f:
    content = f.read()

# Split the content at the first occurrence of @media (max-width: 900px) {
parts = content.split("@media (max-width: 900px) {", 1)

if len(parts) == 2:
    new_css = """@media (max-width: 900px) {
    .hero-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding-top: 40px;
        gap: 40px;
    }

    .hero-copy {
        margin-bottom: 0px;
        max-width: 100%;
        margin-left: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .hero h1 {
        font-size: 38px;
    }

    .hero-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 16px;
    }

    .btn-hero-solid {
        width: 100%;
        max-width: 280px;
    }

    .hero-people {
        width: 100%;
        max-width: 440px;
        margin: 0 auto;
        margin-top: 0px;
    }

    .hero-stats-grid {
        justify-content: center;
        width: 100%;
        gap: 20px;
        margin-top: 32px;
        margin-left: 0;
        max-width: 100%;
    }

    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 40px 0;
        text-align: center;
    }

    .stats {
        background: #fff;
        padding: 86px 0 11px;
        position: relative;
        z-index: 1;
    }

    .stats-grid div strong {
        font-size: 24px;
    }

    .stats-grid div span {
        font-size: 10px;
    }

    .card-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .catalog-grid {
        grid-template-columns: 1fr;
    }

    .filters {
        display: none !important;
    }

    .products {
        grid-template-columns: 1fr;
    }

    .newsletter-inner {
        flex-direction: column;
        text-align: left;
        padding: 48px var(--gp-page-pad);
    }

    .news-actions {
        width: 100%;
        justify-content: flex-start;
        display: block;
    }

    .subscribe-form,
    .app-links {
        max-width: 100%;
        align-items: flex-start;
        text-align: left;
    }

    .subscribe-form__field input {
        width: 100%;
    }

    .store-badges {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .promo {
        min-height: auto;
        text-align: center;
        padding: 60px 20px;
    }

    .promo-copy h2 {
        font-size: 28px;
    }

    .brand-marquee-group {
        display: flex;
        align-items: center;
        gap: 40px !important;
        flex-direction: row;
        padding: 0 14px !important;
    }

    .brand-item img, .brand-logo img {
        height: 32px;
    }

    h2#catalog-heading {
        text-align: center;
        font-weight: 800;
        font-size: 21px;
        margin: 0 0 8px;
    }

    .support-composite-img {
        width: 100%;
    }

    .support-decor {
        display: none !important;
    }

    .faq-section {
        padding: 48px 0;
    }

    .faq-toggle {
        font-size: 15px;
    }

    .faq-container {
        max-width: 100%;
    }
}

/* FAQ Section Styling */
.faq-section {
    padding: 64px 0;
    background: #ffffff;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    border-bottom: 1px solid #eaedf0;
    padding: 20px 0;
    transition: all 0.3s ease;
}

.faq-toggle {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1a2332;
    text-align: left;
    transition: color 0.2s ease;
}

.faq-toggle:hover {
    color: var(--gp-teal-mid);
}

.faq-toggle svg {
    flex-shrink: 0;
    transition: transform 0.3s ease;
    color: var(--gp-teal-mid);
}

.faq-toggle[aria-expanded="true"] svg {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 16px 0;
    color: #647481;
    font-weight: 500;
    line-height: 1.6;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.site-footer-info {
    background: var(--gp-teal-mid);
    color: #fff;
    padding: 64px 0;
    margin-top: 10px;
}

.info-column-left .footer-info-logo img {
    height: 56px;
    margin-bottom: 24px;
    filter: brightness(0) invert(1);
}

.footer-info-grid {
    display: grid;
    grid-template-columns: 1.2fr 1.5fr 1fr;
    gap: clamp(24px, 4vw, 48px);
    align-items: flex-start;
}

.info-item strong {
    display: block;
    font-size: 18px;
    margin-bottom: 4px;
}

.info-item p {
    margin: 0;
    opacity: 0.9;
    font-size: 15px;
    line-height: 1.5;
}

.info-item-flex {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
}

.info-social strong {
    display: block;
    margin-bottom: 16px;
    font-size: 18px;
}

.social-links {
    display: flex;
    gap: 12px;
}

.social-links a {
    width: 40px;
    height: 40px;
    border: 1px solid #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}

.social-links a:hover {
    background: #fff;
    color: var(--gp-teal-mid);
}

.info-column-middle .info-disclaimer {
    margin-top: 0;
    border-top: none;
    padding-top: 0;
}

.info-disclaimer strong {
    display: block;
    margin-bottom: 8px;
    font-size: 18px;
}

.info-disclaimer p {
    font-size: 14px;
    line-height: 1.6;
    opacity: 0.9;
    margin: 0;
}

@media (max-width: 768px) {
    .footer-info-grid {
        grid-template-columns: 1fr;
        gap: 48px;
    }

    .footer-copy-wrap {
        flex-direction: column;
        text-align: center;
        gap: 32px !important;
    }
    
    .brand-mark {
        display: block;
        height: 36px;
        width: auto;
        max-width: min(200px, 46vw);
        object-fit: contain;
        margin: 0 auto;
    }

    .topbar-inner {
        flex-direction: column;
        gap: 12px;
        padding: 12px var(--gp-page-pad);
        text-align: center;
    }

    .top-icons {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin: 0;
    }

    .mohap-license {
        font-size: 11px;
        padding: 0;
    }

    .icon-btn {
        width: 36px;
        height: 36px;
    }

    .footer-links {
        order: 2;
    }

    .footer-apps {
        order: 1;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .hero h1 {
        font-size: 36px;
    }
    
    .card-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-stats-grid {
        flex-wrap: wrap;
    }
    
    .hero-stats-item {
        flex: 0 0 45%;
    }
    
    .faq-section {
        padding: 40px 0;
    }

    .faq-item {
        padding: 16px 0;
    }

    .faq-toggle {
        font-size: 14px;
    }

    .faq-answer {
        padding: 12px 0;
        font-size: 14px;
    }
}
"""
    new_content = parts[0] + new_css
    with open(filepath, 'w') as f:
        f.write(new_content)
    print("Done replacing.")
else:
    print("Could not find the target to split.")
