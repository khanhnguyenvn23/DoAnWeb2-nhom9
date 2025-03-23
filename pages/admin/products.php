<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/products.css">
    <style>
        
h2 {
    color: #2d6a4f; 
    font-weight: 600;
    text-align: center;
    margin-bottom: 25px;
}
.btn-primary {
    background: #52b788; 
    border: none;
    border-radius: 25px;
    padding: 10px 20px;
    transition: all 0.3s;
}
.btn-primary:hover {
    background: #40916c; 
    transform: translateY(-2px);
}
.table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}
.table thead {
    background: #40916c; 
    color: white;
}
.table tbody tr {
    transition: background 0.2s;
}
.table tbody tr:hover {
    background: #f0f7f4; 
}
.modal-content {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}
.modal-header {
    background: #40916c; 
    color: white;
    border-bottom: none;
}
.modal-body {
    padding: 15px;
}
.form-label {
    color: #2d6a4f;
    font-weight: 500;
}
.form-control {
    border-radius: 8px;
    border: 1px solid #b7e4c7; 
    padding: 10px;
    transition: border-color 0.3s;
}
.form-control:focus {
    border-color: #52b788; 
    box-shadow: 0 0 8px rgba(82, 183, 136, 0.3);
}
.image-preview {
    width: 100%;
    height: 150px;
    border: 2px dashed #b7e4c7;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    overflow: hidden;
}
.image-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}
.btn-sm {
    border-radius: 20px;
    padding: 6px 12px;
}
.btn-warning {
    background: #f4a261; 
    border: none;
}
.btn-lock {
    background: #e63946; 
    border: none;
}
.status-active {
    color: #40916c; 
    font-weight: bold;
}
.status-locked {
    color: #e63946; 
    font-weight: bold;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý sản phẩm</h2>
        <button class="btn btn-primary mb-4" onclick="showAddProductForm()">Thêm sản phẩm</button>
        
        <!-- Bảng hiển thị danh sách sản phẩm -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Nguyên liệu</th>
                    <th>Hình ảnh</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
            </tbody>
        </table>

        <!-- Modal cho form Thêm/Sửa -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm">
                            <input type="hidden" id="action" value="add">
                            <input type="hidden" id="productId">
                            <div class="row">
                                <!-- Cột 1: Upload ảnh -->
                                <div class="col-md-6">
                                    <h6 class="mb-3">Hình ảnh sản phẩm</h6>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="productImgInput" accept="image/*" onchange="previewImage(event)">
                                        <div class="image-preview" id="imagePreview">
                                            <span>Chưa có ảnh</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cột 2: Thông tin sản phẩm -->
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin sản phẩm</h6>
                                    <div class="mb-3">
                                        <label for="productName" class="form-label">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="productName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productPrice" class="form-label">Giá (VND)</label>
                                        <input type="number" class="form-control" id="productPrice" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productStock" class="form-label">Tồn kho</label>
                                        <input type="number" class="form-control" id="productStock" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productMaterial" class="form-label">Nguyên liệu</label>
                                        <input type="text" class="form-control" id="productMaterial" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="saveProduct()">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dữ liệu tĩnh (thêm trạng thái và nguyên liệu)
        let products = [
            { id: 1, name: "Áo thun", price: 150000, stock: 50, material: "Cotton", img: "https://via.placeholder.com/50", status: "active" },
            { id: 2, name: "Quần jeans", price: 300000, stock: 30, material: "Denim", img: "https://via.placeholder.com/50", status: "active" },
            { id: 3, name: "Giày thể thao", price: 500000, stock: 20, material: "Da tổng hợp", img: "https://via.placeholder.com/50", status: "locked" }
        ];

        // Hiển thị danh sách sản phẩm
        function displayProducts() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            products.forEach(product => {
                const statusText = product.status === 'active' ? 'Hoạt động' : 'Khóa';
                const statusClass = product.status === 'active' ? 'status-active' : 'status-locked';
                const row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.price.toLocaleString('vi-VN')} VND</td>
                        <td>${product.stock}</td>
                        <td>${product.material}</td>
                        <td><img src="${product.img}" alt="${product.name}" width="50" class="rounded"></td>
                        <td><span class="${statusClass}">${statusText}</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning me-2" onclick="editProduct(${product.id})">Sửa</button>
                            <button class="btn btn-sm btn-lock" onclick="lockProduct(${product.id})">${product.status === 'active' ? 'Khóa' : 'Mở khóa'}</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Xem trước ảnh khi upload
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span>Chưa có ảnh</span>';
            }
        }

        // Hiển thị form thêm sản phẩm
        function showAddProductForm() {
            document.getElementById('productModalLabel').innerText = 'Thêm sản phẩm';
            document.getElementById('action').value = 'add';
            document.getElementById('productId').value = '';
            document.getElementById('productName').value = '';
            document.getElementById('productPrice').value = '';
            document.getElementById('productStock').value = '';
            document.getElementById('productMaterial').value = '';
            document.getElementById('productImgInput').value = '';
            document.getElementById('imagePreview').innerHTML = '<span>Chưa có ảnh</span>';
            new bootstrap.Modal(document.getElementById('productModal')).show();
        }

        // Chỉnh sửa sản phẩm
        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                document.getElementById('productModalLabel').innerText = 'Sửa sản phẩm';
                document.getElementById('action').value = 'edit';
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock;
                document.getElementById('productMaterial').value = product.material;
                document.getElementById('imagePreview').innerHTML = `<img src="${product.img}" alt="Preview">`;
                new bootstrap.Modal(document.getElementById('productModal')).show();
            }
        }

        // Khóa/Mở khóa sản phẩm
        function lockProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                product.status = product.status === 'active' ? 'locked' : 'active';
                displayProducts();
            }
        }

        // Lưu sản phẩm (Thêm hoặc Sửa)
        function saveProduct() {
            const action = document.getElementById('action').value;
            const id = document.getElementById('productId').value;
            const name = document.getElementById('productName').value;
            const price = parseInt(document.getElementById('productPrice').value);
            const stock = parseInt(document.getElementById('productStock').value);
            const material = document.getElementById('productMaterial').value;
            const fileInput = document.getElementById('productImgInput');
            const file = fileInput.files[0];

            let img = products.find(p => p.id === parseInt(id))?.img || "https://via.placeholder.com/50"; // Default hoặc giữ ảnh cũ khi sửa
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img = e.target.result;
                    finalizeSave(action, id, name, price, stock, material, img);
                };
                reader.readAsDataURL(file);
            } else {
                finalizeSave(action, id, name, price, stock, material, img);
            }
        }

        // Hàm hoàn tất lưu sản phẩm
        function finalizeSave(action, id, name, price, stock, material, img) {
            if (action === 'add') {
                const newId = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
                products.push({ id: newId, name, price, stock, material, img, status: 'active' });
            } else if (action === 'edit') {
                const product = products.find(p => p.id === parseInt(id));
                if (product) {
                    product.name = name;
                    product.price = price;
                    product.stock = stock;
                    product.material = material;
                    product.img = img;
                }
            }
            displayProducts();
            bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
        }

        // Hiển thị danh sách sản phẩm khi trang tải
        window.onload = displayProducts;
    </script>
</body>
</html>