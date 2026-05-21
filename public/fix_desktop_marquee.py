import os

filepath = "/Users/designer/Downloads/gulf_landing_modular_v3 (1)/css/gulf-landing.css"

with open(filepath, 'r') as f:
    content = f.read()

old_desktop_block = """.brand-marquee-viewport {
    overflow: hidden;
    width: 100%;
    padding: 4px 100px;
    -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
    mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
}

.brand-marquee-track {
    display: flex;
    width: max-content;
    align-items: center;
    animation: brand-marquee-slide 42s linear infinite;
}

.brand-marquee-group {
    display: flex;
    align-items: center;
    gap: 71px !important;
    padding: 0 45px !important;
}"""

new_desktop_block = """.brand-marquee-viewport {
    width: 100%;
    padding: 4px 0;
}

.brand-marquee-track {
    display: flex;
    width: 100%;
    justify-content: center;
    align-items: center;
}

.brand-marquee-group {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: clamp(32px, 6vw, 71px) !important;
    padding: 0 !important;
}"""

if old_desktop_block in content:
    content = content.replace(old_desktop_block, new_desktop_block)
    with open(filepath, 'w') as f:
        f.write(content)
    print("Replaced desktop marquee group.")
else:
    print("Old desktop block not found.")
