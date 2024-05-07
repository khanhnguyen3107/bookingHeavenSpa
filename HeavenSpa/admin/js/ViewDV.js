$(document).ready(function () {
                $('.view-button').click(function () {
                    var madv = $(this).data('madv');
                    $.ajax({
                        url: '/admin/php/ViewDV.php',
                        method: 'GET',
                        data: { view_id: madv },
                        success: function (response) {
                            $('#modalContent').html(response);
                            $('#myModal').modal('show');
                        },
                        error: function () {
                            alert('Có lỗi xảy ra khi lấy thông tin chi tiết.');
                        }
                    });
                });
});
