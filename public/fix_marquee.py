import os

filepath = "/Users/designer/Downloads/gulf_landing_modular_v3 (1)/css/gulf-landing.css"

with open(filepath, 'r') as f:
    content = f.read()

# Replace the marquee block inside the @media (max-width: 900px) block
old_block = """    .brand-marquee-group {
        display: flex;
        align-items: center;
        gap: 40px !important;
        flex-direction: row;
        padding: 0 14px !important;
    }

    .brand-item img, .brand-logo img {
        height: 32px;
    }"""

new_block = """    .brand-marquee-track {
        width: 100%;
        animation: none;
        justify-content: center;
    }

    .brand-marquee-group {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 24px !important;
        padding: 0 14px !important;
    }

    .brand-marquee-viewport {
        mask-image: none;
        -webkit-mask-image: none;
        padding: 0;
    }

    .brand-item img, .brand-logo img {
        height: auto;
        max-width: 120px;
        max-height: 40px;
        object-fit: contain;
    }"""

if old_block in content:
    content = content.replace(old_block, new_block)
    with open(filepath, 'w') as f:
        f.write(content)
    print("Replaced marquee group in mobile media query.")
else:
    print("Old block not found.")
