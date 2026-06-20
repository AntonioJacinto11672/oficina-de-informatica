import pathlib
import re
root = pathlib.Path(r'c:\xampp\htdocs\oficina-de-informatica')
pattern = re.compile(r"mysqli_connect\(DBHOST, DBUSER, DBPASS, DBNAME\)")
changed = []
for p in root.rglob('*.php'):
    text = p.read_text(encoding='utf-8')
    new = pattern.sub('mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT)', text)
    if new != text:
        p.write_text(new, encoding='utf-8')
        changed.append(str(p))
print('files changed:', len(changed))
for f in changed:
    print(f)
