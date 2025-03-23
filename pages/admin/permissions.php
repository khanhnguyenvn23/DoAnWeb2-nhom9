<?php
require_once  "../../includes/database.php";

$sql_function = "SELECT * FROM `function`";
$result_function = $conn->query($sql_function);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý phân quyền</h2>
        <button class="btn btn-primary mb-3" onclick="showAddAccountModal()">Thêm tài khoản</button>
        <button class="btn btn-primary mb-3" onclick="showRoleModal()">Nhóm quyền</button>
        <table class="table table-striped" id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr data-id="1">
                    <td>1</td>
                    <td>admin</td>
                    <td>admin@example.com</td>
                    <td>Admin</td>
                    <td><span class="text-success fw-bold status">Mở</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger lock-btn" onclick="toggleLock(1)">Khóa</button>
                        <button class="btn btn-sm btn-warning" onclick="editAccount(1)">Sửa</button>
                    </td>
                </tr>
                <tr data-id="2">
                    <td>2</td>
                    <td>editor</td>
                    <td>editor@example.com</td>
                    <td>Editor</td>
                    <td><span class="text-success fw-bold status">Mở</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger lock-btn" onclick="toggleLock(2)">Khóa</button>
                        <button class="btn btn-sm btn-warning" onclick="editAccount(2)">Sửa</button>
                    </td>
                </tr>
                <tr data-id="3">
                    <td>3</td>
                    <td>viewer</td>
                    <td>viewer@example.com</td>
                    <td>Viewer</td>
                    <td><span class="text-danger fw-bold status">Khóa</span></td>
                    <td>
                        <button class="btn btn-sm btn-success lock-btn" onclick="toggleLock(3)">Mở khóa</button>
                        <button class="btn btn-sm btn-warning" onclick="editAccount(3)">Sửa</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Modal thêm tài khoản -->
        <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm tài khoản</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addAccountForm">
                            <div class="mb-3">
                                <label for="newUsername" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" id="newUsername" required>
                            </div>
                            <div class="mb-3">
                                <label for="newEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="newEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="newRole" class="form-label">Nhóm quyền</label>
                                <select class="form-select" id="newRole" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Editor">Editor</option>
                                    <option value="Viewer">Viewer</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="addAccount()">Lưu</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa tài khoản -->
        <div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa thông tin tài khoản</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editAccountForm">
                            <input type="hidden" id="editUserId">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" id="editUsername" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="editRole" class="form-label">Nhóm quyền</label>
                                <select class="form-select" id="editRole" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Editor">Editor</option>
                                    <option value="Viewer">Viewer</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="saveAccount()">Lưu</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa nhóm quyền -->
        <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="roleForm" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chỉnh sửa nhóm quyền</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editRoleName" class="form-label">Tên nhóm quyền</label>
                                <input type="text" class="form-control" name="name_permission" id="editRoleName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chức năng</label>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Chức năng</th>
                                            <th>Xem</th>
                                            <th>Thêm</th>
                                            <th>Chỉnh sửa</th>
                                            <th>Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rolePermissionsTable">
                                        <?php
                                        while ($row_function = $result_function->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row_function['name']; ?></td>
                                                <td><input type="checkbox" name="function[<?php echo $row_function['id']; ?>][]" value="view"></td>
                                                <td><input type="checkbox" name="function[<?php echo $row_function['id']; ?>][]" value="add"></td>
                                                <td><input type="checkbox" name="function[<?php echo $row_function['id']; ?>][]" value="edit"></td>
                                                <td><input type="checkbox" name="function[<?php echo $row_function['id']; ?>][]" value="delete"></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary" onclick="saveRole()">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Khóa/Mở khóa người dùng
        function toggleLock(userId) {
            const row = document.querySelector(`#userTable tr[data-id="${userId}"]`);
            const statusSpan = row.querySelector('.status');
            const lockBtn = row.querySelector('.lock-btn');
            const isLocked = statusSpan.textContent === 'Khóa';

            if (isLocked) {
                statusSpan.textContent = 'Mở';
                statusSpan.className = 'text-success fw-bold status';
                lockBtn.textContent = 'Khóa';
                lockBtn.className = 'btn btn-sm btn-danger lock-btn';
            } else {
                statusSpan.textContent = 'Khóa';
                statusSpan.className = 'text-danger fw-bold status';
                lockBtn.textContent = 'Mở khóa';
                lockBtn.className = 'btn btn-sm btn-success lock-btn';
            }
        }

        // Hiển thị modal thêm tài khoản
        function showAddAccountModal() {
            document.getElementById('newUsername').value = '';
            document.getElementById('newEmail').value = '';
            document.getElementById('newRole').value = 'Admin';
            new bootstrap.Modal(document.getElementById('addAccountModal')).show();
        }



        // Hiển thị modal sửa tài khoản
        function editAccount(userId) {
            const row = document.querySelector(`#userTable tr[data-id="${userId}"]`);
            const username = row.cells[1].textContent;
            const email = row.cells[2].textContent;
            const role = row.cells[3].textContent;

            document.getElementById('editUserId').value = userId;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            new bootstrap.Modal(document.getElementById('editAccountModal')).show();
        }

        // Lưu thông tin tài khoản đã sửa
        function saveAccount() {
            const userId = document.getElementById('editUserId').value;
            const username = document.getElementById('editUsername').value;
            const email = document.getElementById('editEmail').value;
            const role = document.getElementById('editRole').value;

            const row = document.querySelector(`#userTable tr[data-id="${userId}"]`);
            row.cells[1].textContent = username;
            row.cells[2].textContent = email;
            row.cells[3].textContent = role;

            alert(`Đã cập nhật tài khoản ID ${userId}: ${username} | ${email} | ${role}`);
            bootstrap.Modal.getInstance(document.getElementById('editAccountModal')).hide();
        }

        // Hiển thị modal chỉnh sửa nhóm quyền
        function showRoleModal() {
            new bootstrap.Modal(document.getElementById('roleModal')).show();
        }

        // Lưu nhóm quyền
        function saveRole() {
            const roleName = document.getElementById('editRoleName').value.trim();
            const form = document.getElementById('roleForm');
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            const newPermissions = {};
            checkboxes.forEach(cb => {
                const funcId = parseInt(cb.name.match(/\d+/)[0]);
                newPermissions[funcId] = cb.checked;
            });
            roles[roleName] = newPermissions;
            alert(`Đã lưu nhóm quyền: ${roleName}`);
            bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
        }

        $(document).ready(function() {
            $("#roleForm").submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append("action", "save_role");
                
                $.ajax({
                    url: "/project_web2/pages/admin/ajax.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                    },
                    error: function () {
                        alert("Lỗi khi gửi dữ liệu!");
                    }
                });
                
            });
        });
    </script>
</body>

</html>