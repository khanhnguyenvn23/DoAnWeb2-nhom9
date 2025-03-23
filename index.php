<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý cửa hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="src/css/products.css">
</head>
<body>
   <div  class="d-flex">
        <?php 
        include("pages/admin/sidebar.php");
        ?>
        <div id="content" class="p-3 flex-grow-1">
        </div>
    </div>

    <!-- jQuery và Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Lấy tham số page từ URL
            var urlParams = new URLSearchParams(window.location.search);
            var pageParam = urlParams.get('page');
            var defaultPage = pageParam ? 'src/admin/' + pageParam + '.php' : 'src/admin/dashboard.php';
            
            // Tải trang dựa trên tham số URL hoặc mặc định
            loadPage(defaultPage);

            // Xử lý click trên các liên kết trong sidebar
            $('.nav-link').click(function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadPage(page);
                
                // Cập nhật URL trên thanh địa chỉ
                var pageName = page.split('/').pop().replace('.php', ''); 
                history.pushState({ page: page }, '', '?page=' + pageName);
            });

            // Hàm tải trang qua AJAX
            function loadPage(page) {
                $('#content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>');
                $.ajax({
                    url: page,
                    method: 'GET',
                    success: function(data) {
                        $('#content').html(data);
                        // Cập nhật trạng thái active trên sidebar
                        updateActiveLink(page);
                    },
                    error: function() {
                        $('#content').html('<p>Đã xảy ra lỗi khi tải trang.</p>');
                    }
                });
            }

            function updateActiveLink(page) {
                $('.nav-link').removeClass('active');
                $('.nav-link[data-page="' + page + '"]').addClass('active');
            }

            // Xử lý khi người dùng nhấn nút Back/Forward trên trình duyệt
            window.onpopstate = function(event) {
                if (event.state && event.state.page) {
                    loadPage(event.state.page);
                } else {
                    // Nếu không có state, lấy từ URL
                    var urlParams = new URLSearchParams(window.location.search);
                    var pageParam = urlParams.get('page');
                    var page = pageParam ? 'src/admin/' + pageParam + '.php' : 'src/admin/dashboard.php';
                    loadPage(page);
                }
            };
        });
    </script>
</body>
</html>