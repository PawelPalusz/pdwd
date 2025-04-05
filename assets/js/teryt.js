jQuery(document).ready(function ($) {
  
  // Sprawdzamy, czy ACF jest dostępny
  if (typeof acf === 'undefined') return;

  // Zmienna z admin-scripts.php -> myPdwd.teryt_url
  const terytURL = (typeof myPdwd !== 'undefined') ? myPdwd.teryt_url : null;
  if (!terytURL) return;

  let terytData = [];

  /**
   * Pobieranie pliku teryt.json
   */
  function loadJSON(callback) {
    if (terytData.length > 0) {
      callback();
      return;
    }
    $.getJSON(terytURL, function (data) {
      terytData = data;
      callback();
    });
  }

  /**
   * Inicjalizacja dynamicznych selectów
   * (szukamy pól ACF np. o data-name="wojewodztwo", "powiat", "gmina" itp.)
   */
  function initDynamicSelects() {
    // Przykład: selektory ACF
    const $woj = $('div[data-name="wojewodztwo"] select');
    const $pow = $('div[data-name="powiat"] select');
    const $gmi = $('div[data-name="gmina"] select');

    // Jeśli nie znajdziemy pól, to kończymy
    if (!$woj.length || !$pow.length || !$gmi.length) return;

    function updatePowiaty(wojewodztwo) {
      const powiaty = [...new Set(terytData
        .filter(row => row.wojewodztwo === wojewodztwo)
        .map(row => row.powiat))].sort();

      $pow.empty().append('<option value="">Wybierz powiat</option>');
      powiaty.forEach(p => $pow.append(`<option value="${p}">${p}</option>`));
    }

    function updateGminy(powiat) {
      const gminy = [...new Set(terytData
        .filter(row => row.powiat === powiat)
        .map(row => row.gmina))].sort();

      $gmi.empty().append('<option value="">Wybierz gminę</option>');
      gminy.forEach(g => $gmi.append(`<option value="${g}">${g}</option>`));
    }

    // Obsługa zmiany województwa
    $woj.on('change', function () {
      const val = $(this).val();
      if (val) {
        updatePowiaty(val);
        $pow.closest('.acf-field').show();
        $gmi.empty().append('<option value="">Wybierz gminę</option>');
        $gmi.closest('.acf-field').hide();
      } else {
        $pow.empty().append('<option value="">Wybierz powiat</option>').closest('.acf-field').hide();
        $gmi.empty().append('<option value="">Wybierz gminę</option>').closest('.acf-field').hide();
      }
    });

    // Obsługa zmiany powiatu
    $pow.on('change', function () {
      const val = $(this).val();
      if (val) {
        updateGminy(val);
        $gmi.closest('.acf-field').show();
      } else {
        $gmi.empty().append('<option value="">Wybierz gminę</option>').closest('.acf-field').hide();
      }
    });

    // Obsługa zapisanych danych (jeśli otwieramy istniejący wpis)
    const wojVal = $woj.val();
    const powVal = $pow.val();
    const gmiVal = $gmi.val();

    if (wojVal) {
      updatePowiaty(wojVal);
      $pow.val(powVal).trigger('change').closest('.acf-field').show();
      if (powVal) {
        updateGminy(powVal);
        $gmi.val(gmiVal).closest('.acf-field').show();
      }
    } else {
      $pow.closest('.acf-field').hide();
      $gmi.closest('.acf-field').hide();
    }
  }

  // Załaduj i zainicjalizuj
  loadJSON(initDynamicSelects);

  /**
   * Jeśli korzystasz z ACF Extended i okna modalnego do dodawania
   * nowego Obszaru, można nasłuchiwać eventów acf:
   */
  acf.addAction('load', function($el){
    // Każdorazowo init, by selecty w modalu też działały
    loadJSON(initDynamicSelects);
  });

});
