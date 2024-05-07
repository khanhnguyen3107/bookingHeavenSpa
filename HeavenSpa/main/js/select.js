
    // Lấy tham chiếu đến các thẻ select
    var citySelect = document.getElementById('city');
    var districtSelect = document.getElementById('district');

    var data;

    // Hàm để tạo các option cho thẻ select
    function createOption(value, text) {
        var option = document.createElement('option');
        option.value = value;
        option.text = text;
        return option;
    }

    // Hàm để xử lý dữ liệu từ file JSON
    function handleData(responseData) {
        data = responseData;

        // Xóa tất cả các option hiện tại
        while (citySelect.firstChild) {
            citySelect.removeChild(citySelect.firstChild);
        }

        // Thêm các option mới từ dữ liệu JSON
        data.forEach(function(item) {
            citySelect.appendChild(createOption(item.Id, item.Name));
        });
    }

    // Hàm để cập nhật các option cho thẻ select "district"
    function updateDistricts() {
        var selectedCity = citySelect.value;

        // Xóa tất cả các option hiện tại
        while (districtSelect.firstChild) {
            districtSelect.removeChild(districtSelect.firstChild);
        }

        // Tìm city đã chọn trong dữ liệu và thêm các districts tương ứng
        var city = data.find(function(item) {
            return item.Id === selectedCity;
        });

        if (city) {
            city.Districts.forEach(function(district) {
                districtSelect.appendChild(createOption(district.Id, district.Name));
            });
        }
    }

    // Thêm sự kiện onchange cho thẻ select "city"
    citySelect.addEventListener('change', updateDistricts);

    // Sử dụng axios để lấy dữ liệu từ file JSON
    axios.get('../js/data.json')
        .then(function(response) {
            handleData(response.data);
        })
        .catch(function(error) {
            console.error('Error fetching data: ' + error);
        });
