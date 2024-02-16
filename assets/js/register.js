function capitalizeInput(element) {
    let inputValue = element.value;
    let capitalizedValue = inputValue.replace(/\b\w/g, c => c.toUpperCase());
    element.value = capitalizedValue;
}

var my_handlers = {

    fill_provinces: function () {
        var region_code = $(this).val();
        $('#province-dropdown').ph_locations('fetch_list', [{ "region_code": region_code }]);
        $('#city-dropdown').empty();
        $('#barangay-dropdown').empty();

    },

    fill_cities: function () {
        var province_code = $(this).val();
        $('#city-dropdown').ph_locations('fetch_list', [{ "province_code": province_code }]);
        $('#barangay-dropdown').empty();
    },


    fill_barangays: function () {
        var city_code = $(this).val();
        $('#barangay-dropdown').ph_locations('fetch_list', [{ "city_code": city_code }]);
    }
};

$(function () {
    $('#region-dropdown').on('change', my_handlers.fill_provinces);
    $('#province-dropdown').on('change', my_handlers.fill_cities);
    $('#city-dropdown').on('change', my_handlers.fill_barangays);

    $('#province-dropdown').ph_locations({ 'location_type': 'provinces' });
    $('#city-dropdown').ph_locations({ 'location_type': 'cities' });
    $('#barangay-dropdown').ph_locations({ 'location_type': 'barangays' });
});

