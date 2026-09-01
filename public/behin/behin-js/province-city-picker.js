/**
 * Province-City Picker
 * استفاده:
 *   initProvinceCityPicker('province_select_id', 'city_select_id', 'مقدار_شهر_فعلی', citiesData)
 *
 * citiesData: آرایه‌ای از آبجکت‌های { provinceName, cityName } که از cities.json خونده می‌شه
 */
function initProvinceCityPicker(provinceSelectId, citySelectId, selectedCity, citiesData) {
    var $province = $('#' + provinceSelectId);
    var $city     = $('#' + citySelectId);

    // نرمال‌سازی: حذف فاصله، تبدیل ی/ک عربی به فارسی
    function normalize(s) {
        return (s || '').trim()
            .replace(/ي/g, 'ی').replace(/ك/g, 'ک')
            .replace(/ة/g, 'ه').replace(/ؤ/g, 'و')
            .replace(/[إأ]/g, 'ا').replace(/\s+/g, '');
    }

    function loadCities(provinceName, preselectCity) {
        if (!provinceName) {
            $city.prop('disabled', true)
                 .html('<option value="">ابتدا استان را انتخاب کنید</option>');
            triggerSelect2($city);
            return;
        }

        var needle = normalize(provinceName);
        var filtered = (citiesData || []).filter(function(c) {
            return normalize(c.provinceName) === needle;
        });

        filtered.sort(function(a, b) {
            return a.cityName.localeCompare(b.cityName, 'fa');
        });

        var options = '<option value="">-- شهر را انتخاب کنید --</option>';
        filtered.forEach(function(c) {
            var sel = (preselectCity && c.cityName === preselectCity) ? ' selected' : '';
            options += '<option value="' + c.cityName + '"' + sel + '>' + c.cityName + '</option>';
        });

        $city.html(options).prop('disabled', false);
        triggerSelect2($city);
    }

    function triggerSelect2($el) {
        if (typeof $.fn.select2 !== 'undefined') {
            $el.trigger('change.select2');
        }
    }

    // بارگذاری اولیه
    var initialProvince = $province.val();
    if (initialProvince) {
        loadCities(initialProvince, selectedCity || null);
    } else {
        $city.prop('disabled', true)
             .html('<option value="">ابتدا استان را انتخاب کنید</option>');
    }

    // تغییر استان
    $province.on('change', function () {
        loadCities($(this).val(), null);
    });
}
