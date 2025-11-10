# Test Collapse Feature - Instrukcje

## Przygotowanie

1. **Build assets:**
   ```bash
   cd woow-admin
   npm run build
   ```

2. **Wyczyść cache przeglądarki** (Ctrl+Shift+R lub Cmd+Shift+R)

3. **Otwórz stronę wtyczki** w WordPress Admin

## Testy do wykonania

### ✅ Test 1: Collapse Sidebar

1. Znajdź przycisk ze strzałką w prawym górnym rogu logo sidebar
2. Kliknij przycisk
3. **Oczekiwany rezultat:**
   - Sidebar zwija się do 80px szerokości
   - Tekst "WOOW! Admin Styler" znika
   - Etykiety menu znikają, zostają tylko ikony
   - Ikona strzałki obraca się o 180°
   - Animacja jest płynna (300ms)

4. Kliknij ponownie
5. **Oczekiwany rezultat:**
   - Sidebar rozwija się do 240px
   - Wszystkie teksty wracają
   - Ikona strzałki wraca do pozycji początkowej

### ✅ Test 2: Collapse Preview

1. Znajdź przycisk ze strzałką w prawym górnym rogu panelu "Live Preview"
2. Kliknij przycisk
3. **Oczekiwany rezultat:**
   - Preview zwija się do 60px szerokości
   - Tytuł "Live Preview" znika
   - Przycisk refresh znika
   - Zawartość preview znika
   - Ikona strzałki obraca się o 180°
   - Animacja jest płynna (300ms)

4. Kliknij ponownie
5. **Oczekiwany rezultat:**
   - Preview rozwija się do 400px
   - Wszystkie elementy wracają

### ✅ Test 3: Skróty klawiszowe

1. Naciśnij `Ctrl + B` (Windows/Linux) lub `Cmd + B` (Mac)
2. **Oczekiwany rezultat:** Sidebar się zwija/rozwija

3. Naciśnij `Ctrl + P` (Windows/Linux) lub `Cmd + P` (Mac)
4. **Oczekiwany rezultat:** Preview się zwija/rozwija

### ✅ Test 4: Persystencja stanu

1. Zwiń sidebar i preview
2. Odśwież stronę (F5)
3. **Oczekiwany rezultat:**
   - Sidebar pozostaje zwinięty
   - Preview pozostaje zwinięty
   - Stan jest przywrócony z localStorage

### ✅ Test 5: Ustawienia domyślne

1. Przejdź do zakładki "Settings"
2. Znajdź sekcję "Interface Layout"
3. Zaznacz "Collapse Sidebar by Default"
4. Zaznacz "Collapse Live Preview by Default"
5. Kliknij "Apply Changes"
6. Odśwież stronę
7. **Oczekiwany rezultat:**
   - Sidebar i Preview startują w stanie zwiniętym

### ✅ Test 6: Console logs

1. Otwórz DevTools (F12)
2. Przejdź do zakładki Console
3. Kliknij przyciski toggle
4. **Oczekiwany rezultat:**
   ```
   [LayoutController] Initialized
   [LayoutController] Sidebar toggled: collapsed
   [LayoutController] Sidebar toggled: expanded
   [LayoutController] Preview toggled: collapsed
   [LayoutController] Preview toggled: expanded
   ```

### ✅ Test 7: Responsive (opcjonalny)

1. Zmień szerokość okna przeglądarki do < 1024px
2. **Oczekiwany rezultat:**
   - Layout przełącza się w tryb responsywny
   - Collapse nadal działa poprawnie

## Debugowanie

### Jeśli przyciski nie działają:

1. Sprawdź console na błędy JavaScript
2. Sprawdź czy LayoutController został zainicjalizowany:
   ```javascript
   console.log(window.woowAdmin?.components?.layoutController);
   ```

3. Sprawdź czy elementy istnieją:
   ```javascript
   console.log(document.querySelector('.woow-sidebar'));
   console.log(document.querySelector('.woow-preview-container'));
   ```

### Jeśli style nie działają:

1. Sprawdź czy CSS został zbudowany:
   ```bash
   ls -la woow-admin/assets/dist/css/
   ```

2. Sprawdź w DevTools czy klasa `.collapsed` jest dodawana:
   ```javascript
   document.querySelector('.woow-sidebar').classList.contains('collapsed');
   ```

3. Wyczyść cache WordPress:
   - WP Admin → Settings → Clear Cache (jeśli masz plugin cache)
   - Lub wyczyść cache przeglądarki (Ctrl+Shift+Delete)

### Jeśli localStorage nie działa:

1. Sprawdź localStorage w DevTools:
   ```javascript
   localStorage.getItem('woow_sidebar_collapsed');
   localStorage.getItem('woow_preview_collapsed');
   ```

2. Wyczyść localStorage:
   ```javascript
   localStorage.removeItem('woow_sidebar_collapsed');
   localStorage.removeItem('woow_preview_collapsed');
   ```

## Oczekiwane zachowanie

### Sidebar collapsed (80px):
- ✅ Tylko ikony widoczne
- ✅ Logo icon widoczne
- ✅ Tekst ukryty
- ✅ Badges ukryte
- ✅ Strzałka obrócona

### Preview collapsed (60px):
- ✅ Tylko przycisk toggle widoczny
- ✅ Tytuł ukryty
- ✅ Refresh button ukryty
- ✅ Zawartość ukryta
- ✅ Strzałka obrócona

## Sukces!

Jeśli wszystkie testy przeszły pomyślnie, funkcjonalność collapse działa poprawnie! 🎉

## Zgłaszanie problemów

Jeśli napotkasz problemy:
1. Sprawdź console na błędy
2. Sprawdź czy wszystkie pliki zostały zbudowane
3. Sprawdź czy cache został wyczyszczony
4. Zrób screenshot problemu
5. Zapisz logi z console
