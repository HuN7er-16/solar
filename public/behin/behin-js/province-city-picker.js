/**
 * Province-City Picker
 * استفاده:
 *   initProvinceCityPicker('province_select_id', 'city_select_id', 'مقدار_شهر_فعلی')
 */
function initProvinceCityPicker(provinceSelectId, citySelectId, selectedCity) {
    var $province = $('#' + provinceSelectId);
    var $city     = $('#' + citySelectId);

    function loadCities(provinceName, preselectCity) {
        $city.prop('disabled', true).html('<option value="">در حال بارگذاری...</option>');

        if (!provinceName) {
            $city.prop('disabled', true).html('<option value="">ابتدا استان را انتخاب کنید</option>');
            triggerSelect2($city);
            return;
        }

        $.getJSON('/api/cities', { province: provinceName }, function (cities) {
            var options = '<option value="">-- شهر را انتخاب کنید --</option>';
            $.each(cities, function (i, c) {
                var sel = (preselectCity && c.cityName === preselectCity) ? ' selected' : '';
                options += '<option value="' + c.cityName + '"' + sel + '>' + c.cityName + '</option>';
            });
            $city.html(options).prop('disabled', false);
            triggerSelect2($city);
        }).fail(function () {
            $city.prop('disabled', false)
                 .html('<option value="">خطا در بارگذاری شهرها</option>');
        });
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
