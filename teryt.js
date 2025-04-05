
jQuery(document).ready(function ($) {
  if (typeof acf === 'undefined') return;
  if (!$('body').hasClass('post-type-obszar')) return;

  const terytURL = '/wp-content/uploads/teryt.json';
  let terytData = [];

  function loadJSON(callback) {
    if (terytData.length > 0) return callback();
    $.getJSON(terytURL, function (data) {
      terytData = data;
      callback();
    });
  }

  function initDynamicSelects() {
    const $woj = $('div[data-name="wojewodztwo"] select');
    const $pow = $('div[data-name="powiat"] select');
    const $gmi = $('div[data-name="gmina"] select');

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

    $pow.on('change', function () {
      const val = $(this).val();
      if (val) {
        updateGminy(val);
        $gmi.closest('.acf-field').show();
      } else {
        $gmi.empty().append('<option value="">Wybierz gminę</option>').closest('.acf-field').hide();
      }
    });

    // Obsługa zapisanych danych
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

  loadJSON(initDynamicSelects);
});
