<!-- views/toevoegen_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Toevoegen - De Speelkast</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

    <div class="container">
        <a href="index.php" class="back-link">&larr; Terug naar de kast</a>

        <div class="form-card">
            <h2>Nieuw item toevoegen</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div id="jsError" class="alert-error"></div>

            <form id="itemForm" action="toevoegen.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="type">Type item:</label>
                    <select name="type" id="type">
                        <option value="bordspel" <?= $type === 'bordspel' ? 'selected' : '' ?>>Bordspel</option>
                        <option value="uitbreiding" <?= $type === 'uitbreiding' ? 'selected' : '' ?>>Uitbreiding</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="titel">Titel:</label>
                    <input type="text" name="titel" id="titel" value="<?= htmlspecialchars($titel) ?>" required>
                </div>

                <div class="form-group">
                    <label for="foto">Doosfoto (JPG, PNG of GIF):</label>
                    <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.gif" required>
                </div>

                <!-- Velden specifiek voor Bordspel -->
                <div id="bordspelVelden">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="min_spelers">Min. spelers:</label>
                            <input type="number" name="min_spelers" id="min_spelers" min="1" value="<?= htmlspecialchars($minSpelers) ?>">
                        </div>
                        <div class="form-group">
                            <label for="max_spelers">Max. spelers:</label>
                            <input type="number" name="max_spelers" id="max_spelers" min="1" value="<?= htmlspecialchars($maxSpelers) ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="speelduur">Speelduur (minuten):</label>
                            <input type="number" name="speelduur" id="speelduur" min="1" value="<?= htmlspecialchars($speelduur) ?>">
                        </div>
                        <div class="form-group">
                            <label for="moeilijkheidsgraad">Moeilijkheidsgraad:</label>
                            <select name="moeilijkheidsgraad" id="moeilijkheidsgraad">
                                <option value="">-- Kies optie --</option>
                                <option value="Eenvoudig" <?= $moeilijkheidsgraad === 'Eenvoudig' ? 'selected' : '' ?>>Eenvoudig</option>
                                <option value="Gemiddeld" <?= $moeilijkheidsgraad === 'Gemiddeld' ? 'selected' : '' ?>>Gemiddeld</option>
                                <option value="Moeilijk" <?= $moeilijkheidsgraad === 'Moeilijk' ? 'selected' : '' ?>>Moeilijk</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Veld specifiek voor Uitbreiding -->
                <div id="uitbreidingVelden" style="display: none;">
                    <div class="form-group">
                        <label for="basisspel">Bij welk basisspel hoort deze uitbreiding?:</label>
                        <input type="text" name="basisspel" id="basisspel" value="<?= htmlspecialchars($basisspel) ?>">
                    </div>
                </div>

                <button type="submit" class="btn-submit">Item opslaan</button>
            </form>
        </div>
    </div>

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
        updateFormFields();

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