import os
import re
import urllib.request
from PIL import Image
import io
from concurrent.futures import ThreadPoolExecutor

steps_dir = r"C:\Users\DSWM\.gemini\antigravity-ide\brain\1820541d-e676-4a02-99d6-f7788250790a\.system_generated\steps"
out_dir = r"e:\DS_HTML\assets\images"

os.makedirs(out_dir, exist_ok=True)

urls = set()
for root, dirs, files in os.walk(steps_dir):
    for f in files:
        if f.endswith(".txt"):
            path = os.path.join(root, f)
            with open(path, "r", encoding="utf-8", errors="ignore") as file:
                content = file.read()
                matches = re.findall(r'http://localhost:3845/assets/[a-zA-Z0-9_\-\.]+', content)
                for m in matches:
                    urls.add(m)

print(f"Total unique asset URLs to process: {len(urls)}")

def process_url(url):
    filename = url.split('/')[-1]
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=5) as resp:
            data = resp.read()

        if filename.lower().endswith('.svg'):
            out_path = os.path.join(out_dir, filename)
            with open(out_path, 'wb') as f:
                f.write(data)
            return ("svg", filename)
        else:
            name_without_ext = os.path.splitext(filename)[0]
            out_filename = f"{name_without_ext}.webp"
            out_path = os.path.join(out_dir, out_filename)

            img = Image.open(io.BytesIO(data))
            img.save(out_path, format="WEBP", quality=90)
            return ("webp", out_filename)

    except Exception as e:
        return ("error", f"{url}: {e}")

svg_count = 0
webp_count = 0
failed_count = 0

with ThreadPoolExecutor(max_workers=16) as executor:
    results = list(executor.map(process_url, sorted(urls)))

for status, res in results:
    if status == "svg":
        svg_count += 1
        print(f"[SVG] {res}")
    elif status == "webp":
        webp_count += 1
        print(f"[WebP] {res}")
    else:
        failed_count += 1
        print(f"[Failed] {res}")

print("\n--- Summary ---")
print(f"SVGs saved: {svg_count}")
print(f"WebP exported: {webp_count}")
print(f"Failed: {failed_count}")
