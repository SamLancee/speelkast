<!-- views/toevoegen_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Item Toevoegen - De Speelkast</title>
</head>
<body>

    <p><a href="index.php">&larr; Terug naar de kast</a></p>
    <h2>Nieuw item toevoegen</h2>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div id="jsError" style="color: red;"></div>

    <form id="itemForm" action="toevoegen.php" method="POST" enctype="multipart/form-data">
        <p>
            <label for="type">Type item:</label><br>
            <select name="type" id="type">
                <option value="bordspel" <?= $type === 'bordspel' ? 'selected' : '' ?>>Bordspel</option>
                <option value="uitbreiding" <?= $type === 'uitbreiding' ? 'selected' : '' ?>>Uitbreiding</option>
            </select>
        </p>

        <p>
            <label for="titel">Titel:</label><br>
            <input type="text" name="titel" id="titel" value="<?= htmlspecialchars($titel) ?>" required>
        </p>

        <p>
            <label for="foto">Doosfoto (JPG, PNG of GIF):</label><br>
            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.gif" required>
        </p>

        <!-- Velden specifiek voor Bordspel -->
        <div id="bordspelVelden">
            <p>
                <label for="min_spelers">Minimaal aantal spelers:</label><br>
                <input type="number" name="min_spelers" id="min_spelers" min="1" value="<?= htmlspecialchars($minSpelers) ?>">
            </p>
            <p>
                <label for="max_spelers">Maximaal aantal spelers:</label><br>
                <input type="number" name="max_spelers" id="max_spelers" min="1" value="<?= htmlspecialchars($maxSpelers) ?>">
            </p>
            <p>
                <label for="speelduur">Speelduur (in minuten):</label><br>
                <input type="number" name="speelduur" id="speelduur" min="1" value="<?= htmlspecialchars($speelduur) ?>">
            </p>
            <p>
                <label for="moeilijkheidsgraad">Moeilijkheidsgraad:</label><br>
                <select name="moeilijkheidsgraad" id="moeilijkheidsgraad">
                    <option value="">-- Kies optie --</option>
                    <option value="Eenvoudig" <?= $moeilijkheidsgraad === 'Eenvoudig' ? 'selected' : '' ?>>Eenvoudig</option>
                    <option value="Gemiddeld" <?= $moeilijkheidsgraad === 'Gemiddeld' ? 'selected' : '' ?>>Gemiddeld</option>
                    <option value="Moeilijk" <?= $moeilijkheidsgraad === 'Moeilijk' ? 'selected' : '' ?>>Moeilijk</option>
                </select>
            </p>
        </div>

        <!-- Veld specifiek voor Uitbreiding -->
        <div id="uitbreidingVelden" style="display: none;">
            <p>
                <label for="basisspel">Bij welk basisspel hoort deze uitbreiding?:</label><br>
                <input type="text" name="basisspel" id="basisspel" value="<?= htmlspecialchars($basisspel) ?>">
            </p>
        </div>

        <p>
            <button type="submit">Item opslaan</button>
        </p>
    </form>

    <script>
        const typeSelect = document.getElementById('type');
        const bordspelVelden = document.getElementById('bordspelVelden');
        const uitbreidingVelden = document.getElementById('uitbreidingVelden');
        const basisspelInput = document.getElementById('basisspel');
        const form = document.getElementById('itemForm');
        const jsError = document.getElementById('jsError');

        function updateFormFields() {
            if (typeSelect.value === 'uitbreiding') {
                bordspelVelden.style.display = 'none';
                uitbreidingVelden.style.display = 'block';
                basisspelInput.required = true;
            } else {
                bordspelVelden.style.display = 'block';
                uitbreidingVelden.style.display = 'none';
                basisspelInput.required = false;
            }
        }

        typeSelect.addEventListener('change', updateFormFields);
        updateFormFields(); // Uitvoeren bij het laden

        // Client-side validatie
        form.addEventListener('submit', function(e) {
            jsError.innerText = '';
            const titel = document.getElementById('titel').value.trim();
            const foto = document.getElementById('foto').files[0];

            if (!titel) {
                e.preventDefault();
                jsError.innerText = 'Vul a.u.b. een titel in.';
                return;
            }

            if (!foto) {
                e.preventDefault();
                jsError.innerText = 'Selecteer een doosfoto.';
                return;
            }

            const allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            const fileExt = foto.name.split('.').pop().toLowerCase();
            if (!allowedExt.includes(fileExt)) {
                e.preventDefault();
                jsError.innerText = 'Ongeldig bestandstype. Alleen JPG, PNG of GIF.';
                return;
            }

            if (typeSelect.value === 'bordspel') {
                const min = parseInt(document.getElementById('min_spelers').value);
                const max = parseInt(document.getElementById('max_spelers').value);
                if (min && max && min > max) {
                    e.preventDefault();
                    jsError.innerText = 'Minimaal aantal spelers mag niet groter zijn dan maximaal aantal spelers.';
                    return;
                }
            } else if (typeSelect.value === 'uitbreiding') {
                if (!basisspelInput.value.trim()) {
                    e.preventDefault();
                    jsError.innerText = 'Geef a.u.b. het bijbehorende basisspel op.';
                    return;
                }
            }
        });
    </script>
</body>
</html>